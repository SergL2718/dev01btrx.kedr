<?php
/*
 * Изменено: 04 июля 2021, воскресенье
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

use Bitrix\Main\Loader;
use Bitrix\Main\Mail\Event;

define('STOP_STATISTICS', true);
define('NO_AGENT_STATISTIC', 'Y');
define('NO_AGENT_CHECK', true);
define('NO_KEEP_STATISTIC', true);
define('DisableEventsCheck', true);

header('Content-type: application/json; charset=utf-8');

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';

$request =& $_GET;
$response = [];

if (
    !isset($request['action']) ||
    empty($request['action']) ||
    $request['action'] !== 'Send' ||
    !isset($request['type']) ||
    empty($request['type'])
) {
    die(json_encode([
        'error' => true,
        'message' => 'Ошибка в параметрах запроса'
    ]));
}

Loader::includeModule('sale');

$arSelect = [
    'ID',
    'ACCOUNT_NUMBER',
    'USER_EMAIL',
    'PAY_SYSTEM_ID',
    'PAYED',
];
$order = \CSaleOrder::GetList([], ['ID' => $request['orderId']], false, ['nTopCount' => 1], $arSelect)->fetch();

if (!$order) {
    die(json_encode([
        'error' => true,
        'message' => 'Заказ не найден'
    ]));
}

if ($order['PAYED'] === 'Y') {
    die(json_encode([
        'error' => true,
        'message' => 'Заказ уже оплачен'
    ]));
}

$type =& $request['type'];

if ($type === \Native\App\Sale\PaymentSystem::BILL_CODE) {
    // Сформируем PDF-счет на оплату
    $fileId = false;
    $document = new \Native\App\Sale\Document();
    $orderAccountNumber =& $order['ACCOUNT_NUMBER'];
    if ($document->pdf($orderAccountNumber)) {
        $fileId = $document->convertToJpg();
    }
    if ($fileId > 0) {
        $data = [
            'EVENT_NAME' => 'SALE_FILES_ORDER',
            'MESSAGE_ID' => 139,
            'LANGUAGE_ID' => LANGUAGE_ID,
            'LID' => \Native\App\Helper::SITE_ID,
            'C_FIELDS' => [
                'ORDER_ID' => $order['ACCOUNT_NUMBER'],
                'EMAIL' => $order['USER_EMAIL']
            ],
            'FILE' => [$fileId]
        ];
        if (Event::sendImmediate($data) === 'Y') {
            if ($data['FILE']) {
                foreach ($data['FILE'] as $fileId) {
                    \CFile::Delete($fileId);
                }
            }
            $response['message'] = 'Письмо со счётом было отправлено на email: ' . $order['USER_EMAIL'];
        } else {
            $response['message'] = 'Не удалось отправить письмо';
        }
    } else {
        die(json_encode([
            'error' => true,
            'message' => 'Не удалось сформировать файл счёта'
        ]));
    }

} elseif ($type === \Native\App\Sale\PaymentSystem::CARD_CODE) {

    /*$payment = false;
    $url = false;

    if (($entity = \Bitrix\Sale\Order::load($order['ID'])) && ($paymentCollection = $entity->getPaymentCollection())) {
        foreach ($paymentCollection as $p) {
            if (!$p->isInner()) {
                $payment = $p;
                break;
            }
        }
    }

    if ($payment->getPaymentSystemId() != \Native\App\Sale\PaymentSystem::getInstance()->getIdByCode(\Native\App\Sale\PaymentSystem::CARD_CODE)) {
        die(json_encode(['message' => 'Нет данных для обработки']));
    }

    if ($payment && $service = \Bitrix\Sale\PaySystem\Manager::getObjectById($payment->getPaymentSystemId())) {
        $context = \Bitrix\Main\Application::getInstance()->getContext();
        if (($res = $service->initiatePay($payment, $context->getRequest(), \Bitrix\Sale\PaySystem\BaseServiceHandler::STRING)) && $res->isSuccess()) {
            $template = $res->getTemplate();
            $template = explode('a href="', $template);
            if (isset($template[1])) {
                $url = explode('" class="sberbank__payment-link">Оплатить</a>', $template[1]);
                $url = $url[0];
            } else {
                $response['message'] = $template;
            }
        }
    }*/

    if ($url = \Native\App\Sale\Order::getInstance()->getPaymentSberbankUrl($order['ID'])) {

        $sent = CEvent::SendImmediate('SALE_FILES_ORDER', \Native\App\Helper::SITE_ID, [
            'ORDER_ID' => $order['ACCOUNT_NUMBER'],
            'EMAIL' => $order['USER_EMAIL'],
            'URL' => $url
        ], 'Y', 150);

        if ($sent) {
            $response['message'] = 'Письмо со ссылкой на оплату было отправлено на email: ' . $order['USER_EMAIL'] . PHP_EOL . $url;
        } else {
            $response['message'] = 'Не удалось отправить письмо';
        }
    } else {
        die(json_encode([
            'error' => true,
            'message' => 'Не удалось сформировать ссылку на оплату'
        ]));
    }

} else {
    $response['message'] = 'Нет данных для обработки';
}

die(json_encode($response));
