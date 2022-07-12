<?php
/*
 * Изменено: 12 июля 2021, понедельник
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */


use Bitrix\Main\Loader;
use Bitrix\Sale;
use Native\App\Foundation\Bitrix24;

/**
 * @var $request
 */

//define('LOG_FILENAME', __DIR__ . '/request.log');
//AddMessage2Log($request, 'megre.bitrix24.ru');

$request['id'] = (int)$request['id'];
$request['contactId'] = (int)$request['contactId'];

$dealId =& $request['id'];
$contactId =& $request['contactId'];

if (empty($dealId) || $dealId <= 0 || empty($contactId) || $contactId <= 0) die;

// Получим данные по сущностям
$bitrix24 = new Bitrix24();

$batch = []; // Массив запросов к порталу
$batch['crm.deal.get'] = 'crm.deal.get?id=' . $dealId;
$batch['crm.contact.get'] = 'crm.contact.get?id=' . $contactId;

$response = $bitrix24->batch($batch);

// Обработаем данные по сущностям
$deal = $response['result']['crm.deal.get'];
$contact = $response['result']['crm.contact.get'];

//pr($deal);

if (!$deal || !$contact || /*!$deal['ADDITIONAL_INFO'] ||*/
    !$deal['ORIGIN_ID']) die;

//$deal['ADDITIONAL_INFO'] = unserialize($deal['ADDITIONAL_INFO']);

// Убедимся, что сделка создана по информации с сайта megre.ru
// То есть, это заказ выгруженный с сайта в сделку crm
//if (mb_strpos($deal['ADDITIONAL_INFO']['SITE'], '[zg]') === false) die;

// Обработаем данные по заказу
Loader::includeModule('sale');

// Получим номер заказа на сайте
$orderId =& $deal['ORIGIN_ID'];
// Получим заказ
$order = Sale\Order::load($orderId);
// Получим адрес заказа из свойств заказа
$orderAddress = [];
$properties = $order->getPropertyCollection()->getArray()['properties'];
$personTypeId = $order->getPersonTypeId();

$personTypeId = $order->getPersonTypeId();
$need = [
    'COUNTRY_CODE' => true,
    'COUNTRY_NAME' => true,
    'REGION' => true,
    'ZIP' => true,
    'CITY' => true,
    'STREET' => true,
    'HOUSE' => true,
    'APARTMENT' => true,
    'DELIVERY_ADDRESS' => true,
];
foreach ($properties as $property) {
    if (!$need[$property['CODE']] || $property['PERSON_TYPE_ID'] !== $personTypeId) continue;
    $code = trim($property['CODE']);
    $value = trim($property['VALUE'][0]);
    $orderAddress[$code] = $value;
}
if (!$orderAddress['COUNTRY_NAME'] || !$orderAddress['CITY']) die;
// Получим список адресов контакта из CRM
$contactAddresses = $bitrix24->getContactAddressListById($contact['ID']);
// Проверим наличие адреса из заказа в списке адресов контакта
// В случае отсутствия адреса добавим его контакту
$addAddAddress = true;
foreach ($contactAddresses as $contactAddress) {
    // Проверим наличие адреса из заказа в списке адресов
    // Если адрес имеется, тогда отключаем флаг необходимости добавления адреса
    if (
        mb_strtolower($contactAddress['POSTAL_CODE']) === mb_strtolower($orderAddress['ZIP']) &&
        mb_strtolower($contactAddress['COUNTRY']) === mb_strtolower($orderAddress['COUNTRY_NAME'])
        && mb_strtolower($contactAddress['CITY']) === mb_strtolower($orderAddress['CITY'])
    ) {
        $addAddAddress = false;
        if ($orderAddress['STREET']) {
            $searchStreet = mb_strtolower(trim(str_replace(['ул.', 'ул'], '', $orderAddress['STREET'])));
            if (mb_strpos(mb_strtolower($contactAddress['ADDRESS_1']), $searchStreet) === false) {
                $addAddAddress = true;
            }
        }
        if ($addAddAddress === false && $contactAddress['ADDRESS_2'] && $contactAddress['ADDRESS_2'] != $orderAddress['APARTMENT']) {
            $addAddAddress = true;
        }
    }
}
if ($addAddAddress === true) {
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
        if (mb_strpos($orderAddress['STREET'], 'ул. ') !== false) {
            $orderAddress['STREET'] = str_replace('ул. ', '', $orderAddress['STREET']);
        }
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
    $requisite = [
        'NAME' => $orderAddress['DELIVERY_ADDRESS'],
    ];
    if ($response = $bitrix24->addContactAddress($contact['ID'], $address, $requisite)) {
        $timelineComment = \Native\App\Provider\Bitrix24\TimelineComment::getInstance();
        $comment = 'На основании заказа №' . $order->getField('ACCOUNT_NUMBER') . ' были обновлены поля' . "\n";
        $comment .= '- Фактический адрес: ' . $fullAddress . "\n";
        $timelineComment->add('contact', $contact['ID'], $comment);
    }
}

// Дополним сделку информацией из заказа
$data = [];

// Способ оплаты
/*if ($paySystemName = \Bitrix\Main\Application::getConnection()->query('select NAME from b_sale_pay_system_action where PAY_SYSTEM_ID="' . $order->getField('PAY_SYSTEM_ID') . '" limit 1')) {
    $paySystemName = $paySystemName->fetchRaw()['NAME'];
}

// Служба доставки
if ($deliveryName = \Bitrix\Main\Application::getConnection()->query('select NAME from b_sale_delivery_srv where ID="' . $order->getField('DELIVERY_ID') . '" limit 1')) {
    $deliveryName = $deliveryName->fetchRaw()['NAME'];
}*/

// Скидка/купон
$coupon = [];
$discount = $order->getDiscount();
$discount = $discount->getApplyResult(true);
if ($discount) {
    $coupon = array_shift($discount['COUPON_LIST']);
    $discount = array_shift($discount['DISCOUNT_LIST']);
    $discount = array_shift($discount['ACTIONS_DESCR_DATA']['BASKET']);
}
$coupon = $coupon['COUPON'] ?: '';
$discount = strlen($discount['VALUE']) > 0 ? $discount['VALUE'] : '0';

//$deal = \Native\App\Provider\Bitrix24\Deal::getInstance();
//$comment = 'На основании заказа №' . $order->getField('ACCOUNT_NUMBER') . ' были обновлены поля' . "\n";
/*if ($paySystemName) {
    $comment .= '- Платежная система: ' . $paySystemName . "\n";
}
if ($deliveryName) {
    $comment .= '- Служба доставки: ' . $deliveryName . "\n";
}
if ($coupon) {
    $comment .= '- Примененный промокод: ' . $coupon . "\n";
}*/


$deal = \Native\App\Provider\Bitrix24\Deal::getInstance();
$comment = '';

if ($discount !== '0') {
    $comment = 'На основании заказа №' . $order->getField('ACCOUNT_NUMBER') . ' были обновлены поля' . "\n";
    $comment .= '- Размер скидки %: ' . $discount . "\n";
}

$data = [
    //$bitrix24->getFieldCode('paySystemName') => $paySystemName,
    //$bitrix24->getFieldCode('deliveryName') => $deliveryName,
    //$bitrix24->getFieldCode('coupon') => $coupon,
    $bitrix24->getFieldCode('discountPercent') => $discount,
];

$deal->update($dealId, $data, $comment);
