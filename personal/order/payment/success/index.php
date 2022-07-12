<?php
/*
 * Изменено: 18 февраля 2022, пятница
 * Автор: Артамонов Денис <artamonov.d.i@yandex.com>
 * copyright (c) 2022
 */

/**
 * @var $APPLICATION
 */

use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\ArgumentOutOfRangeException;
use \Bitrix\Main\Config\Option;
use Bitrix\Main\Loader;
use Bitrix\Main\Mail\Event;
use Bitrix\Sale\Order;
use Native\App\Helper;
use Native\App\Sale\DeliverySystem;

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php';

Bitrix\Main\Page\Asset::getInstance()->addCss('/personal/order/payment/style.min.css');

$APPLICATION->SetTitle('Заказ успешно оплачен');
$lastOrder = [];
$cookie = '';
try {
    $cookie = Option::get('main', 'cookie_name') . '_LAST_ORDER';
    $lastOrder = unserialize($_COOKIE[$cookie]);
} catch (ArgumentNullException | ArgumentOutOfRangeException $e) {
}
/*$logRequests = $_SERVER['DOCUMENT_ROOT'] . '/personal/order/payment/logs/success/' . date('dmY') . '.log';
$log[] = 'Дата: ' . date('d.m.Y H:i:s');
$log[] = print_r($_GET, true);
$log[] = print_r($_COOKIE, true);
$log[] = '================================';
$log[] = PHP_EOL . PHP_EOL;
\Bitrix\Main\IO\File::putFileContents($logRequests, implode(PHP_EOL, $log), \Bitrix\Main\IO\File::APPEND);*/
$orderId = $lastOrder['ID'] ?? false;
$accountNumber = $lastOrder['ACCOUNT_NUMBER'];
setcookie($cookie, '', time() + 8640000, '/'); // удалим куки
if (!$accountNumber || !is_string($accountNumber) || strlen($accountNumber) > 15) {
    LocalRedirect('/');
}
Loader::includeModule('sale');
if ($orderId) {
    $order = Order::load($orderId);
    $accountNumber = $order->getField('ACCOUNT_NUMBER');
} else {
    $order = Order::loadByAccountNumber($accountNumber);
}
?>
    <div class="order-complete-content mb-5">
        <div class="order-complete-message mt-0 mt-lg-3">
            <div class="order-complete-message-text">
                <div id="order-payment-online">
                    Большое спасибо за ваш заказ и за оказанное нам доверие!
                    <br>
                    <br>
                    Я прослежу, чтобы ваш заказ <a
                            href="/personal/order/?ID=<?= $accountNumber ?>"><?= $accountNumber ?></a> был обработан в
                    срок. Вы получите письмо с подтверждением
                    заказа
                    сейчас и еще одно, когда заказ будет собран и передан к доставке (обязательно проверьте папку «спам»
                    если не
                    получили письмо).
                    <br>
                    <br>
                    Если у вас возникнут вопросы по заказу или пожелания по нашей работе, пишите на почту admin@megre.ru
                    или
                    звоните
                    по бесплатному номеру 8-800-350-0270.
                </div>
            </div>
            <div class="order-complete-message-photo">
                <img src="../images/alina.jpg" alt="">
            </div>
            <div class="order-complete-message-photo-description">
                Алина, администратор megre.ru
            </div>
        </div>
        <div class="order-complete-button mt-4">
            <a href="/">На главную</a>
        </div>
    </div>
<?php

if ($accountNumber) {
    $personTypeId = $order->getPersonTypeId();
    $deliveryCode = DeliverySystem::getInstance()->getCodeById($order->getField('DELIVERY_ID'));
    $fields['ORDER_ID'] = $accountNumber;
    $fields['ORDER_DATE'] = $order->getField('DATE_INSERT')->toString();
    $properties = $order->getPropertyCollection()->getArray()['properties'];
    foreach ($properties as $property) {
        if ($property['PERSON_TYPE_ID'] != $personTypeId) continue;
        switch ($property['CODE']) {
            case 'NAME':
                $fields['USER_NAME'] = $property['VALUE'][0];
                break;
            case 'EMAIL':
                $fields['USER_EMAIL'] = $property['VALUE'][0];
                break;
            case 'DELIVERY_PERIOD':
                $fields['DELIVERY_PERIOD'] = $property['VALUE'][0];
                break;
            default:
                break;
        }
    }
    if (!$fields['DELIVERY_PERIOD']) {
        $fields['DELIVERY_PERIOD'] = date('d.m.Y', strtotime('+7 days')) . '-' . date('d.m.Y', strtotime('+10 days'));
    }
    $fields['DELIVERY_PRICE'] = CCurrencyLang::CurrencyFormat($order->getField('PRICE_DELIVERY'), $order->getCurrency());
    $fields['PRODUCTS_PRICE'] = CCurrencyLang::CurrencyFormat($order->getPrice() - $order->getField('PRICE_DELIVERY'), $order->getCurrency());
    $fields['ORDER_PRICE'] = CCurrencyLang::CurrencyFormat($order->getPrice(), $order->getCurrency());
    // Отправим письмо пользователю
    $messageId = null;
    if ($deliveryCode === DeliverySystem::PICKUP_NSK) {
        $fields['DELIVERY_PERIOD'] = new \Bitrix\Main\Type\DateTime();
        $fields['DELIVERY_PERIOD']->add('1 days 4 hours');
        $fields['DELIVERY_PERIOD'] = FormatDate('d F', $fields['DELIVERY_PERIOD']);
        do {
            $fields['DELIVERY_PERIOD'] = new \Bitrix\Main\Type\DateTime();
            $fields['DELIVERY_PERIOD']->add('1 days 4 hours');
            $fields['DELIVERY_PERIOD'] = FormatDate('d F', $fields['DELIVERY_PERIOD']);
        } while (Helper::getInstance()->isWeekend($fields['DELIVERY_PERIOD']));
        $messageId = 162;
    } else if (
        $deliveryCode === DeliverySystem::COURIER_MSK_OUTSIDE_MKAD ||
        $deliveryCode === DeliverySystem::COURIER_MSK_INSIDE_MKAD ||
        $deliveryCode === DeliverySystem::COURIER_MSK_INSIDE_MKAD_FREE
    ) {
        $messageId = 165;
    } else if ($deliveryCode === DeliverySystem::PICKUP_MSK_NOVOSLOBODSKAYA) {
        $messageId = 166;
    } else {
        $messageId = 158;
    }
    if ($messageId > 0) {
        Event::sendImmediate([
            'EVENT_NAME' => 'NATIVE',
            'MESSAGE_ID' => $messageId,
            'LANGUAGE_ID' => LANGUAGE_ID,
            'LID' => SITE_ID,
            'C_FIELDS' => $fields,
        ]);
    }
}

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php';
