<?php
/*
 * Изменено: 03 февраля 2022, четверг
 * Автор: Артамонов Денис <artamonov.d.i@yandex.com>
 * copyright (c) 2022
 */

/*
 * Скрипт для получения списка веса заказов
 */

global $USER;
require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';
if (!$USER->IsAuthorized() || !$USER->IsAdmin()) {
    die('Доступ запрещен. Необходимо авторизоваться под учетной записью администратора.');
}
$startTime = microtime(true);
$dateFrom = new \Bitrix\Main\Type\DateTime(date('01.m.Y 00:00:00'));
$dateTo = new \Bitrix\Main\Type\DateTime(date('t.m.Y 23:59:59'));
if (!empty($_GET)) {
    $_GET = array_change_key_case($_GET, CASE_UPPER);
}
if ($_GET['DATE_FROM']) {
    $dateFrom = new \Bitrix\Main\Type\DateTime($_GET['DATE_FROM']);
}
if ($_GET['DATE_TO']) {
    $dateTo = new \Bitrix\Main\Type\DateTime($_GET['DATE_TO']);
    $dateTo->add('86399 seconds');
}
//echo 'Период: ' . $dateFrom->toString() . ' – ' . $dateTo->toString() . '<br>';
if ($dateFrom > $dateTo) {
    die('Указан некорректный период');
}
\Bitrix\Main\Loader::includeModule('sale');
$separatorColumn = ', ';
$file = __DIR__ . '/' . basename(__FILE__, '.php') . '.csv';
$fileUrl = 'https://' . $_SERVER['SERVER_NAME'] . (str_replace($_SERVER['DOCUMENT_ROOT'], '', __DIR__)) . '/' . basename(__FILE__, '.php') . '.csv';
$content = [];
$deliveriesIds = [];
$deliveries = \Bitrix\Sale\Delivery\Services\Table::getList([
    'select' => [
        'ID',
    ],
    'filter' => [
        '=ACTIVE' => 'Y',
        '!XML_ID' => [
            'pickup-msk-novoslobodskaya',
            'courier-msk-inside-mkad-free',
            'courier-msk-inside-mkad',
            'pickup-nsk',
            'courier-nsk',
            'courier-nsk-free',
            'courier-berdsk',
            'courier-berdsk-free',
            'without-delivery',
            'bx_d01cec1dfdc97dcdf2d1cbc959c606ba',
            'bx_82e7db1aa3f5a9041ac2a4e7876eed03',
        ],
    ],
]);
while ($delivery = $deliveries->fetchRaw()) {
    $deliveriesIds[] = $delivery['ID'];
}
unset($deliveries, $delivery);
$orders = \Bitrix\Sale\Internals\OrderTable::getList([
    'select' => [
        'ID',
    ],
    'filter' => [
        '=STATUS_ID' => 'F',
        '=PAYED' => 'Y',
        '=CANCELED' => 'N',
        '=DELIVERY_ID' => $deliveriesIds,
        '>DATE_STATUS' => $dateFrom,
        '<DATE_STATUS' => $dateTo,
    ],
    'order' => [
        'ID' => 'asc',
    ],
]);
unset($deliveriesIds);
//echo 'Всего заказов: ' . $orders->getSelectedRowsCount() . '<br>';
//echo '-------<br>';
$headers = [
    'ORDER_DATE_INSERT' => 'Creation date',
    'ORDER_DATE_STATUS' => 'Completion date',
    'ORDER_ID' => 'Order ID',
    'SHIPMENT_ID' => 'Shipment ID',
    'SHIPMENT_WEIGHT' => 'Weight (gr)',
    'TOTAL_WEIGHT' => 'Total weight (gr)',
];
$currentHeaders = [];
$totalWeight = 0;
$totalColumn = 0;
while ($order = $orders->fetchRaw()) {
    $order = \Bitrix\Sale\Order::load($order['ID']);
    $shipmentCollection = $order->getShipmentCollection();
    foreach ($shipmentCollection as $shipment) {
        if ($shipment->isSystem()) {
            continue;
        }
        $row = [
            'ORDER_DATE_INSERT' => $order->getDateInsert(),
            'ORDER_DATE_STATUS' => $order->getField('DATE_STATUS'),
            //'ORDER_ID' => '<a href="https://' . $_SERVER['SERVER_NAME'] . '/bitrix/admin/sale_order_view.php?lang=' . LANGUAGE_ID . '&ID=' . $order->getId() . '" target="_blank">' . $order->getField('ACCOUNT_NUMBER') . '</a>',
            'ORDER_ID' => $order->getField('ACCOUNT_NUMBER'),
            //'SHIPMENT_ID' => '<a href="https://' . $_SERVER['SERVER_NAME'] . '/bitrix/admin/sale_order_shipment_edit.php?order_id=' . $order->getId() . '&shipment_id=' . $shipment->getId() . '&lang=' . LANGUAGE_ID . '" target="_blank">' . $shipment->getId() . '</a>',
            'SHIPMENT_ID' => $shipment->getId(),
            'SHIPMENT_WEIGHT' => $shipment->getWeight(),
        ];
        if (empty($currentHeaders)) {
            $currentHeaders = array_keys($row);
        }
        if ($totalColumn === 0) {
            $totalColumn = count($row);
        }
        $content[] = implode($separatorColumn, $row);
        $totalWeight += $shipment->getWeight();
    }
}
unset($orders, $order, $shipmentCollection, $shipment, $row);
if (!empty($content) && !empty($currentHeaders)) {
    $currentHeaders = array_flip($currentHeaders);
    array_unshift($content, implode($separatorColumn, array_intersect_key($headers, $currentHeaders)));
}
unset($currentHeaders);
if ($totalColumn > 0 && $totalWeight > 0) {
    $totalColumn -= 2;
    $row = array_fill(0, $totalColumn, '');
    $row[] = $headers['TOTAL_WEIGHT'];
    $row[] = $totalWeight;
    $content[] = implode($separatorColumn, $row);
}
unset($headers, $totalColumn, $totalWeight, $row, $separatorColumn);
if (!empty($content)) {
    // disable caching
    $now = gmdate("D, d M Y H:i:s");
    header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
    header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
    header("Last-Modified: {$now} GMT");

    // force download
    header("Content-Type: application/force-download; charset=" . SITE_CHARSET);
    header("Content-Type: application/octet-stream; charset=" . SITE_CHARSET);
    header("Content-Type: application/download; charset=" . SITE_CHARSET);

    // disposition / encoding on response body
    header("Content-Disposition: attachment;filename=" . basename(__FILE__, '.php') . '-' . $dateFrom->format('dmY') . '-' . $dateTo->format('dmY') . '.csv');
    header("Content-Transfer-Encoding: binary");
    echo implode(PHP_EOL, $content);
    //ob_clean();
} else {
    //echo 'Нет данных для экспорта<br>';
}
//echo '-------<br>';
//echo 'Время выполнения: ' . round(microtime(true) - $startTime, 2) . ' сек';