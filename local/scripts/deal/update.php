<?php
/*********************************************************************
 * @author Артамонов Денис <software.engineer@internet.ru>
 * @copyright Copyright (c) 2021
 * @modified 04 мая 2021, вторник
 ********************************************************************/

use Bitrix\Main\IO\File;
use Native\App\Foundation\Bitrix24;
use Native\App\Provider\Bitrix24\Deal;

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';

global $USER;
if (!$USER->IsAdmin()) {
    die('Доступ запрещён');
}
$file = __DIR__ . '/orders.csv';
if (!File::isFileExists($file)) {
    $file = __DIR__ . '/orders.txt';
    if (!File::isFileExists($file)) {
        die('Файл orders.{csv|txt} не найден');
    }
}
$list = File::getFileContents($file);
$list = explode("\n", $list);
$list = array_diff($list, ['']);
if (empty($list) || count($list) === 0 || (is_array($list) && isset($list[0]) && empty($list[0]))) {
    File::deleteFile($file);
    die('Не найдено элементов для обработки');
}
$titles = array_shift($list);
$total = count($list);
$bitrix24 = new Bitrix24();
$deal = Deal::getInstance();
$batchDealList = [];
$batchUpdate = [];
$batchComment = [];
$response = [];
$counter = 0;
foreach ($list as $index => $item) {
    if ($counter === 50) {
        break;
    }
    $counter++;
    $item = explode(';', $item);
    $orderNumber = trim($item[0]);
    $query = [
        'filter' => [
            'TITLE' => $deal->getNumberByOrderNumber($orderNumber)
        ],
        'select' => ['ID']
    ];
    $query = http_build_query($query);
    $batchDealList[$index] = 'crm.deal.list?' . $query;
}
$response = $bitrix24->batch($batchDealList); // получим ID сделок на основании номера заказа
foreach ($response['result'] as $index => $deal) {
    $dealId = $deal[0]['ID'];
    $item = $list[$index];
    $item = explode(';', $item);
    $orderNumber = trim($item[0]);
    $paid = str_replace([',', ' '], ['.', ''], trim($item[1]));
    $dateReceipt = trim($item[2]);
    $query = [
        'id' => $dealId,
        'params' => ['REGISTER_SONET_EVENT' => 'N'],
        'fields' => [
            $bitrix24->getFieldCode('wePaidForDelivery') => $paid,
            $bitrix24->getFieldCode('dateReceiptOrder') => $dateReceipt,
        ],
    ];
    $query = http_build_query($query);
    $batchUpdate[$index] = 'crm.deal.update?' . $query;
    $query = [
        'fields' => [
            'ENTITY_ID' => $dealId,
            'ENTITY_TYPE' => 'deal',
            'COMMENT' => '<b>Дата получения заказа</b>: ' . $dateReceipt . PHP_EOL . '<b>Мы оплатили за доставку</b>: ' . $paid . ' руб.',
        ],
    ];
    $query = http_build_query($query);
    $batchComment[$index] = 'crm.timeline.comment.add?' . $query;
}
$response = $bitrix24->batch($batchUpdate); // обновим сделки
foreach ($response['result'] as $index => $success) { // актуализируем список записей
    if ($success) {
        unset($list[$index]);
    }
}
$bitrix24->batch($batchComment);  // добавим комментарий к сделкам об обновлении данных по сделке
if (count($list) === 0) {
    File::deleteFile($file);
} else {
    File::putFileContents($file, implode("\n", $list));
}
die('Обработано элементов: ' . $counter . ' из ' . $total . '<br>Файл с данными ' . (count($list) === 0 ? 'удалён' : 'обновлён'));