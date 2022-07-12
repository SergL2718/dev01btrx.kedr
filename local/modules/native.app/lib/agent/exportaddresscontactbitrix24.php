<?php
/*
 * @updated 07.12.2020, 19:16
 * @author Артамонов Денис <bitrix.developer@inbox.ru>
 * @copyright Copyright (c) 2020, Компания Webco <hello@wbc.cx>
 * @link http://wbc.cx
 */

namespace Native\App\Agent;


use Native\App\Foundation\Agent;
use Native\App\Foundation\Bitrix24;

class ExportAddressContactBitrix24 extends Agent
{
    public function __construct()
    {
        $this->function = '(new Native\App\Agent\ExportAddressContactBitrix24())->run();';
    }

    public function run()
    {
        \Bitrix\Main\Loader::includeModule('sale');

        $bitrix24 = new Bitrix24();
        $batch = []; // массив запросов к порталу
        $deals = []; // массив для хранения сделок
        $counter = 1;
        $maxCounter = 50; // максимальное количество сделок за один запуск агента
        $log = [];

        $fileCsv = $_SERVER['DOCUMENT_ROOT'] . '/contacts.csv';
        $csv = file_get_contents($fileCsv);
        $csv = explode("\n", $csv);
        array_shift($csv);

        foreach ($csv as $row) {
            $row = explode(';', $row);
            $deals[$row[1]] = $row;
        }

        if (count($deals) === 0) {
            unlink($fileCsv);
            return false;
        }

        foreach ($deals as $dealId => $data) {
            if ($counter > $maxCounter) break;
            $batch['crm.deal.get.' . $dealId] = 'crm.deal.get?id=' . $dealId;
            $counter++;
        }

        $responseDeals = $bitrix24->batch($batch);

        foreach ($responseDeals['result'] as $deal) {
            $dealId =& $deal['ID'];
            $orderId =& $deal['ORIGIN_ID'];
            $contactId =& $deal['CONTACT_ID'];

            // Заказ пользователя
            $order = \Bitrix\Sale\Order::load($orderId);

            if (!$order) {
                $log[$dealId]['dealId'] = 'dealId: ' . $dealId;
                $log[$dealId]['orderId'] = 'orderId: ' . $orderId;
                $log[$dealId]['contactId'] = 'contactId: ' . $contactId;
                $log[$dealId]['addContactAddress'] = 'addContactAddress: false';
                $log[$dealId]['message'] = 'Заказ на сайте не найден';
                unset($deals[$dealId]);
                continue;
            }

            // Получим адрес заказа из свойств заказа
            $orderAddress = [];
            $properties = $order->getPropertyCollection()->getArray()['properties'];
            if (count($properties) > 0) {
                $need = [
                    'COUNTRY_CODE' => true,
                    'COUNTRY_NAME' => true,
                    'REGION' => true,
                    'ZIP' => true,
                    'CITY' => true,
                    'STREET' => true,
                    'HOUSE' => true,
                    'APARTMENT' => true,
                ];
                foreach ($properties as $property) {
                    if (!$need[$property['CODE']]) continue;
                    $code = trim($property['CODE']);
                    $value = trim($property['VALUE'][0]);
                    $orderAddress[$code] = $value;
                }
            }

            /*if (!$orderAddress['COUNTRY_NAME'] || !$orderAddress['CITY']) {

                $log[$dealId]['dealId'] = 'dealId: ' . $dealId;
                $log[$dealId]['orderId'] = 'orderId: ' . $orderId;
                $log[$dealId]['contactId'] = 'contactId: ' . $contactId;
                $log[$dealId]['addContactAddress'] = 'addContactAddress: false';
                $log[$dealId]['address'] = 'Не указано название страны или города';
                unset($deals[$dealId]);
                continue;
            }*/

            // Получим список адресов контакта из CRM
            $contactAddresses = $bitrix24->getContactAddressListById($contactId);

            // Проверим наличие адреса из заказа в списке адресов контакта
            // В случае отсутствия адреса добавим его контакту
            $needAddAddress = true;
            if (count($contactAddresses) > 0) {
                foreach ($contactAddresses as $contactAddress) {
                    // Проверим наличие адреса из заказа в списке адресов
                    // Если адрес имеется, тогда отключаем флаг необходимости добавления адреса
                    if (
                        //strtolower($contactAddress['COUNTRY']) ===mb_strtolower($orderAddress['COUNTRY_NAME']) &&
                       mb_strtolower($contactAddress['CITY']) ===mb_strtolower($orderAddress['CITY'])
                    ) {
                        $needAddAddress = false;
                    }
                }
            }

            // Если необходимо добавить адрес из заказа контакту
            if ($needAddAddress === true) {
                $fullAddress = '';
                $address_1 = '';
                $address_2 = '';

                if ($orderAddress['ZIP']) {
                    $fullAddress .= $orderAddress['ZIP'];
                }

                if ($orderAddress['COUNTRY_NAME']) {
                    $fullAddress .= ', ' . $orderAddress['COUNTRY_NAME'];
                }

                if ($orderAddress['REGION']) {
                    $fullAddress .= ', ' . $orderAddress['REGION'];
                }

                if ($orderAddress['CITY']) {
                    $fullAddress .= ', ' . $orderAddress['CITY'];
                }

                if ($orderAddress['STREET']) {
                    $address_1 .= 'ул. ' . $orderAddress['STREET'];
                    $fullAddress .= ', ул. ' . $orderAddress['STREET'];
                }

                if ($orderAddress['HOUSE']) {
                    $address_1 .= ', ' . $orderAddress['HOUSE'];
                    $fullAddress .= ', строение ' . $orderAddress['HOUSE'];
                }

                if ($orderAddress['APARTMENT']) {
                    $address_2 = $orderAddress['APARTMENT'];
                    $fullAddress .= ', помещение ' . $orderAddress['APARTMENT'];
                }

                if ($address_1) {
                    $address_1 = trim($address_1, ',');
                }

                if ($fullAddress) {
                    $fullAddress = trim($fullAddress, ',');
                }

                $address = [
                    'COUNTRY_CODE' => $orderAddress['COUNTRY_CODE'],
                    'COUNTRY' => $orderAddress['COUNTRY_NAME'],
                    'REGION' => $orderAddress['REGION'],
                    'CITY' => $orderAddress['CITY'],
                    'POSTAL_CODE' => $orderAddress['ZIP'],
                    'ADDRESS_1' => $address_1, // Улица, дом, корпус, строение
                    'ADDRESS_2' => $address_2, // Квартира / офис
                ];

                if ($response = $bitrix24->addContactAddress($contactId, $address)) {

                    $request['fields'] = [
                        'ENTITY_ID' => $contactId,
                        'ENTITY_TYPE' => 'contact',
                        'COMMENT' => 'На основании заказа №' . $order->getField('ACCOUNT_NUMBER') . ' (' . $orderId . ') был добавлен адрес: ' . $fullAddress,
                    ];
                    $bitrix24->request('crm.timeline.comment.add', $request);

                    $log[$dealId]['dealId'] = 'dealId: ' . $dealId;
                    $log[$dealId]['orderId'] = 'orderNumber: ' . $order->getField('ACCOUNT_NUMBER');
                    $log[$dealId]['contactId'] = 'contactId: ' . $contactId;
                    $log[$dealId]['addContactAddress'] = 'addContactAddress: true';
                    $log[$dealId]['address'] = 'address: ' . implode(', ', $address);
                    $log[$dealId]['separator'] = '================';

                    $log[$dealId] = implode('<br>', $log[$dealId]);
                }
            }/* else {
                $log[$dealId]['dealId'] = 'dealId: ' . $dealId;
                $log[$dealId]['orderId'] = 'orderNumber: ' . $order->getField('ACCOUNT_NUMBER');
                $log[$dealId]['contactId'] = 'contactId: ' . $contactId;
                $log[$dealId]['addContactAddress'] = 'addContactAddress: false';
                $log[$dealId]['separator'] = '================';

                $log[$dealId] = implode('<br>', $log[$dealId]);
            }*/

            // Удалим обработанные сделки и контакты из массива
            unset($deals[$dealId]);
        }

        // Актуализируем файл csv
        $contentCsv = [];
        foreach ($deals as $deal) {
            $deal = implode(';', $deal);
            if ($deal) {
                $contentCsv[] = $deal;
            }
        }
        array_unshift($contentCsv, implode(';', ['Contact', 'Deal', 'Order']));
        $contentCsv = implode("\n", $contentCsv);

        // Запишем обновленный массив адресов
        file_put_contents($fileCsv, $contentCsv);

        if (count($log) > 0) {
            $log = implode('<br>', $log);
            $this->log([
                'DESCRIPTION' => $log,
                'AUDIT_TYPE_ID' => 'Экспорт: Битрикс24',
                'ITEM_ID' => 'Адрес Контакта'
            ]);
        }

        return $this->function;
    }
}
