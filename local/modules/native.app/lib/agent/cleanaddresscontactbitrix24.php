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
use Native\App\Provider\Bitrix24\Contact;

class CleanAddressContactBitrix24 extends Agent
{
    public function __construct()
    {
        $this->function = '(new Native\App\Agent\CleanAddressContactBitrix24())->run();';
    }

    public function run()
    {
        return false;

        \Bitrix\Main\Loader::includeModule('sale');

        $fileCsv = __DIR__ . '/email.csv';
        $emails = file_get_contents($fileCsv);
        $emails = explode("\n", $emails);

        if (count($emails) === 0) {
            unlink($fileCsv);
            $this->log([
                'DESCRIPTION' => 'Обработка завершена',
                'AUDIT_TYPE_ID' => 'Контакты: Битрикс24',
                'ITEM_ID' => 'Чистка e-mail'
            ]);
            return false;
        }

        $bitrix24 = new Bitrix24();
        $batch = []; // массив запросов к порталу
        $counter = 0;
        $maxCounter = 50;

        $log = [];

        foreach ($emails as $index => $email) {
            if ($counter === $maxCounter) break;
            $request = [
                'filter' => [
                    'EMAIL' => $email
                ],
                'select' => [
                    'ID',
                    'NAME',
                    'LAST_NAME',
                    'EMAIL',
                    $bitrix24->getFieldCode('subscriptionName')
                ]
            ];

            //$request);

            $batch['index_' . $index] = 'crm.contact.list?' . http_build_query($request);
            $counter++;
        }

        $delete = [];
        $update = [];

        $response = $bitrix24->batch($batch);

        //pr($response);

        foreach ($response['result'] as $index => $item) {
            if (!isset($item[0])) continue;

            $index = str_replace('index_', '', $index);
            $originalEmail =mb_strtolower($emails[$index]);
            $item = $item[0];
            $id = $item['ID'];

            if ($item['NAME'] === 'Анонимный' && empty($item['LAST_NAME']) && count($item['EMAIL']) === 1) {
                $delete['index_' . $index] = 'crm.contact.delete?id=' . $id;
                continue;
            }

            // Уберем емаил из карточки
            if (count($item['EMAIL']) > 1) {
                $ar = [];
                foreach ($item['EMAIL'] as $key => $arEmail) {

                    if ($originalEmail ===mb_strtolower($arEmail['VALUE'])) {
                        $ar[] = [
                            'ID' => $arEmail['ID'],
                            'VALUE' => '',
                        ];
                    } else {
                        $ar[] = $arEmail;
                    }
                }
                $item['EMAIL'] = $ar;

            } elseif (isset($item['EMAIL'][0])) {
                $item['EMAIL'] = [
                    [
                        'ID' => $item['EMAIL'][0]['ID'],
                        'VALUE' => '',
                    ]
                ];
            }

            // Уберем подписку на Новости
            if (count($item[$bitrix24->getFieldCode('subscriptionName')]) > 1) {

                foreach ($item[$bitrix24->getFieldCode('subscriptionName')] as $key => $subscriptionId) {
                    if ($subscriptionId == 69) { // 69 ID подписки Новости
                        unset($item[$bitrix24->getFieldCode('subscriptionName')][$key]);
                        break;
                    }
                }

            } elseif (count($item[$bitrix24->getFieldCode('subscriptionName')]) === 1 && $item[$bitrix24->getFieldCode('subscriptionName')][0] == 69) {
                $item[$bitrix24->getFieldCode('subscriptionName')] = [false];
            }

            unset($item['ID']);

            $request = [
                'id' => $id,
                'params' => ['REGISTER_SONET_EVENT' => 'N'],
                'fields' => $item
            ];

            $update['index_' . $index] = 'crm.contact.update?' . http_build_query($request);
            continue;
        }

        //pr('DELETE');
        //pr($delete);
        //pr('UPDATE');
        //pr($update);

        // Если имеются контакты на удаление
        if (count($delete) > 0) {
            $response = $bitrix24->batch($delete);
            foreach ($response['result'] as $index => $status) {
                if ($status == 1) {
                    $index = str_replace('index_', '', $index);
                    $email = $emails[$index];
                    $log[] = $email . ': ' . 'удалён';
                    unset($emails[$index]);
                }
            }
        }

        // Если имеются контакты на обновление
        if (count($update) > 0) {
            $response = $bitrix24->batch($update);

            /*pr('UPDATED');
            pr($response);*/

            foreach ($response['result'] as $index => $status) {
                if ($status == 1) {
                    $index = str_replace('index_', '', $index);
                    $email = $emails[$index];
                    $log[] = $email . ': ' . 'обновлён';
                    unset($emails[$index]);
                }
            }
        }

        $emails = implode("\n", $emails);

        // Запишем обновленный массив адресов
        file_put_contents($fileCsv, $emails);

        if (count($log) > 0) {

            //pr('LOG');
            //pr($log);

            $log = implode('<br>', $log);
            $this->log([
                'DESCRIPTION' => $log,
                'AUDIT_TYPE_ID' => 'Контакты: Битрикс24',
                'ITEM_ID' => 'Чистка e-mail'
            ]);
        }

        return $this->function;
    }
}
