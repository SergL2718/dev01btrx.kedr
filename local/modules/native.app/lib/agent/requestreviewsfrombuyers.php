<?php
/*
 * Изменено: 18 февраля 2022, пятница
 * Автор: Артамонов Денис <artamonov.d.i@yandex.com>
 * copyright (c) 2022
 */

namespace Native\App\Agent;

use Bitrix\Highloadblock\HighloadBlockTable;
use Bitrix\Iblock\ElementPropertyTable;
use Bitrix\Iblock\ElementTable;
use Bitrix\Main\Application;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\Mail\Event;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;
use Bitrix\Main\Type\DateTime;
use Bitrix\Sale\Basket;
use Bitrix\Sale\Internals\OrderPropsValueTable;
use Bitrix\Sale\Order;
use Exception;

class RequestReviewsFromBuyers
{
    private static string $agentFunction = '\Native\App\Agent\RequestReviewsFromBuyers::execute();';
    private static string $agentCode = 'REQUEST_REVIEWS';

    /**
     * @return string
     * @throws ArgumentException
     * @throws LoaderException
     * @throws ObjectPropertyException
     * @throws SystemException
     * @throws Exception
     */
    public static function execute(): string
    {
        $startTime = microtime(true);
        $site = \CSite::GetList('sort', 'asc', ['SITE_ID' => Application::getInstance()->getContext()->getSite()])->fetch();
        $eventLog = []; // Логирование для журнала сайта
        Loader::includeModule('highloadblock');
        $r = HighloadBlockTable::getList([
            'select' => ['ID'],
            'filter' => ['NAME' => 'Events'],
            'limit' => 1,
            'cache' => ['ttl' => 86400000],
        ]);
        if ($r->getSelectedRowsCount() === 0) {
            Event::send([
                'EVENT_NAME' => 'NATIVE.APP',
                'MESSAGE_ID' => 172,
                'LANGUAGE_ID' => $site['LANGUAGE_ID'],
                'LID' => $site['LID'],
                'C_FIELDS' => [
                    'THEME' => 'Регламентное задание завершено с ошибками',
                    'MESSAGE' => 'При отработке регламентного задания пр запросу отзывов покупателей возникли ошибки.',
                    'USER_EMAIL' => $site['EMAIL'],
                ],
            ]);
            return self::$agentFunction;
        }
        $date = new DateTime();
        $r = $r->fetchRaw();
        $r = HighloadBlockTable::compileEntity($r['ID']);
        $eventTable = $r->getDataClass();
        $r = $eventTable::getList([
            'select' => [
                'ID',
                'UF_ENTITY_ID',
            ],
            'filter' => [
                '=UF_EVENT_CODE' => 'REVIEW',
                '=UF_ENTITY_CODE' => 'ORDER',
                '=UF_STATUS' => false,
                [
                    'LOGIC' => 'OR',
                    ['=UF_DATE_EXEC' => false],
                    ['<=UF_DATE_EXEC' => $date],
                ],
            ],
            'order' => [
                'UF_DATE_CREATE' => 'asc',
            ],
        ]);
        if ($r->getSelectedRowsCount() === 0) {
            // Завершаем работу
            $eventLog[] = 'Выполнен запрос отзывов у покупателей';
            $eventLog[] = 'Время выполнения: ' . round(microtime(true) - $startTime, 2) . ' сек';
            $eventLog[] = 'События для обработки не найдены';
            \CEventLog::Add([
                'AUDIT_TYPE_ID' => self::$agentCode,
                'MODULE_ID' => 'native.app',
                'ITEM_ID' => 'REQUESTED',
                'DESCRIPTION' => implode('<br>', $eventLog),
            ]);
            return self::$agentFunction;
        }
        Loader::includeModule('sale');
        $events = []; // События по которым отравляем уведомления
        $orderIds = []; // ID заказов
        $numbers = []; // Номера заказов
        $properties = []; // Свойства заказов
        $products = []; // Товары заказов
        $productIds = []; // ID товаров
        $files = []; // Файлы товаров
        $images = []; // Изображения товаров
        $offers = []; // Товары без изображения, обычно это торговые предложения
        $total = 0; // Количество обработанных событий
        // Список событий
        while ($a = $r->fetchRaw()) {
            $events[$a['UF_ENTITY_ID']] = $a['ID'];
            $orderIds[] = $a['UF_ENTITY_ID'];
        }
        // Список заказов
        $r = Order::getList([
            'select' => [
                'ID',
                'ACCOUNT_NUMBER',
            ],
            'filter' => [
                '=ID' => $orderIds,
                '=STATUS_ID' => 'F',
            ],
            'limit' => count($orderIds),
        ]);
        $orderIds = [];
        while ($a = $r->fetchRaw()) {
            $numbers[$a['ID']] = $a['ACCOUNT_NUMBER'];
            $orderIds[] = $a['ID'];
        }
        if (count($orderIds) === 0) {
            // Завершаем работу
            $eventLog[] = 'Выполнен запрос отзывов у покупателей';
            $eventLog[] = 'Время выполнения: ' . round(microtime(true) - $startTime, 2) . ' сек';
            $eventLog[] = 'Заказы для обработки не найдены';
            \CEventLog::Add([
                'AUDIT_TYPE_ID' => self::$agentCode,
                'MODULE_ID' => 'native.app',
                'ITEM_ID' => 'REQUESTED',
                'DESCRIPTION' => implode('<br>', $eventLog),
            ]);
            return self::$agentFunction;
        }
        // Список свойств заказов
        $r = OrderPropsValueTable::getList([
            'select' => [
                'ORDER_ID',
                'CODE',
                'VALUE',
            ],
            'filter' => [
                '=ORDER_ID' => $orderIds,
                '=ENTITY_TYPE' => 'ORDER',
                [
                    'LOGIC' => 'OR',
                    ['=CODE' => 'NAME'],
                    ['=CODE' => 'EMAIL'],
                ],
            ],
        ]);
        while ($a = $r->fetchRaw()) {
            $properties[$a['ORDER_ID']][$a['CODE']] = trim($a['VALUE']);
        }
        // Список товаров заказов
        $r = Basket::getList([
            'select' => [
                'ORDER_ID',
                'PRODUCT_ID',
                'NAME',
                'DETAIL_PAGE_URL',
            ],
            'filter' => [
                '=ORDER_ID' => $orderIds,
                '=SET_PARENT_ID' => false,
            ],
        ]);
        while ($a = $r->fetchRaw()) {
            if ($a['DETAIL_PAGE_URL']) {
                $a['DETAIL_PAGE_URL'] = 'https://' . $site['SERVER_NAME'] . '/' . ltrim($a['DETAIL_PAGE_URL'], '/');
            }
            $products[$a['ORDER_ID']][$a['PRODUCT_ID']] = $a;
            $productIds[$a['PRODUCT_ID']] = $a['PRODUCT_ID'];
        }
        unset($orderIds);
        // Данные товаров заказов
        $r = ElementTable::getList([
            'select' => [
                'ID',
                'PREVIEW_PICTURE',
                'DETAIL_PICTURE',
            ],
            'filter' => [
                '=ID' => $productIds,
            ],
        ]);
        while ($a = $r->fetchRaw()) {
            if (!$a['DETAIL_PICTURE'] && $a['PREVIEW_PICTURE']) {
                $a['DETAIL_PICTURE'] = $a['PREVIEW_PICTURE'];
            }
            if ($a['DETAIL_PICTURE']) {
                $files[$a['ID']] = $a['DETAIL_PICTURE'];
            } else {
                $offers[] = $a['ID'];
            }
        }
        unset($productIds);
        if (!empty($offers)) {
            $property = \CCatalogSku::GetProductInfo($offers[0]);
            $r = ElementPropertyTable::getList([
                'select' => [
                    'VALUE',
                    'IBLOCK_ELEMENT_ID',
                ],
                'filter' => [
                    '=IBLOCK_PROPERTY_ID' => $property['SKU_PROPERTY_ID'],
                    '=IBLOCK_ELEMENT_ID' => $offers,
                ],
            ]);
            $offers = [];
            while ($a = $r->fetchRaw()) {
                $offers[$a['IBLOCK_ELEMENT_ID']] = $a['VALUE'];
            }
            $r = ElementTable::getList([
                'select' => [
                    'ID',
                    'PREVIEW_PICTURE',
                    'DETAIL_PICTURE',
                ],
                'filter' => [
                    '=ID' => $offers,
                ],
            ]);
            while ($a = $r->fetchRaw()) {
                if (!$a['DETAIL_PICTURE'] && $a['PREVIEW_PICTURE']) {
                    $a['DETAIL_PICTURE'] = $a['PREVIEW_PICTURE'];
                }
                if ($a['DETAIL_PICTURE']) {
                    $files[$a['ID']] = $a['DETAIL_PICTURE'];
                }
            }
            foreach ($offers as $offerId => $parentId) {
                if ($files[$parentId]) {
                    $files[$offerId] = $files[$parentId];
                }
            }
            unset($property, $offerId, $parentId);
        }
        unset($r, $a, $order, $offers);
        if (!empty($files)) {
            $tmp = [];
            foreach ($files as $productId => $imageId) {
                if (!$tmp[$imageId]) {
                    $tmp[$imageId] = 'https://' . $site['SERVER_NAME'] . '/' . trim(\CFile::GetPath($imageId), '/');
                }
                $images[$productId] = $tmp[$imageId];
            }
            unset($files, $productId, $imageId, $tmp);
        }
        foreach ($events as $orderId => $eventId) {
            $number = $numbers[$orderId];
            $propertyList = $properties[$orderId];
            $productList = $products[$orderId];
            $productTableHtml = self::generateProductTableHtml($productList, $images);
            $r = Event::send([
                'EVENT_NAME' => 'NATIVE.APP',
                'MESSAGE_ID' => 171,
                'LANGUAGE_ID' => $site['LANGUAGE_ID'],
                'LID' => $site['LID'],
                'C_FIELDS' => [
                    'ORDER_ID' => $number,
                    'USER_NAME' => $propertyList['NAME'],
                    'PRODUCT_TABLE' => $productTableHtml,
                    'USER_EMAIL' => $propertyList['EMAIL'],
                ],
            ]);
            if ($r->isSuccess()) {
                $r = $eventTable::update($eventId, [
                    'UF_DATE_EXEC' => $date,
                    'UF_STATUS' => 'Y',
                ]);
                if ($r->isSuccess()) {
                    $total++;
                }
            }
        }
        // Завершаем работу
        $eventLog[] = 'Выполнен запрос отзывов у покупателей';
        $eventLog[] = 'Время выполнения: ' . round(microtime(true) - $startTime, 2) . ' сек';
        $eventLog[] = 'Событий обработано: ' . $total;
        if (!empty($eventLog)) {
            \CEventLog::Add([
                'AUDIT_TYPE_ID' => self::$agentCode,
                'MODULE_ID' => 'native.app',
                'ITEM_ID' => 'REQUESTED',
                'DESCRIPTION' => implode('<br>', $eventLog),
            ]);
        }
        return self::$agentFunction;
    }

    /**
     * @param array $products
     * @param array $images
     *
     * @return string
     */
    private static function generateProductTableHtml(array $products, array $images = []): string
    {
        $counter = 0;
        $html = '<table style="width: 100%; max-width: 550px;margin: 0 auto;">';
        foreach ($products as $product) {
            $counter++;
            if ($counter === 1) {
                $html .= '<tr>';
            }
            $html .= '<td style="vertical-align:top;padding: 3px 5px;">';
            $html .= '<div style="text-align: center;">';

            $html .= '<div>';
            $html .= '<img src="' . $images[$product['PRODUCT_ID']] . '" style="width: 132px">';
            $html .= '</div>';

            $html .= '<div style="color:#1b1715;font-size: 13px;margin:15px 0;line-height: 1.3;font-family: Arial, Helvetica, sans-serif; height:32px;overflow: hidden;">';
            $html .= $product['NAME'];
            $html .= '</div>';

            $html .= '<div>';
            $html .= '<a href="' . $product['DETAIL_PAGE_URL'] . '" target="_blank" style="font-family: Arial, Helvetica, sans-serif; text-decoration:none;color:#fff;background-color:#8bc34a;width:165px;height:35px;line-height:35px;display:inline-block;">Оставить отзыв</a>';
            $html .= '</div>';

            $html .= '</div>';
            $html .= '</td>';
            if ($counter === 2) {
                $html .= '</tr>';
                $html .= '<tr><td colspan="2" style="height: 10px;">&nbsp;</td></tr>';
                $counter = 0;
            }
        }
        $html .= '</table>';
        return $html;
    }
}
