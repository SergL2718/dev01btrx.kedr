<?php
/*
 * @updated 11.12.2020, 12:16
 * @author Артамонов Денис <bitrix.developer@inbox.ru>
 * @copyright Copyright (c) 2020, Компания Webco <hello@wbc.cx>
 * @link http://wbc.cx
 */

/**
 * @var $USER
 * @var $arResult
 */

use Native\App\Sale\Location;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die;

foreach ($arResult['ITEMS'] as $index => &$item) {
    if (
        !empty($item['PROPERTIES']['ALLOWED_CITY_LIST']['VALUE_XML_ID'])
        && !in_array(Location::getCurrentCityCode(), $item['PROPERTIES']['ALLOWED_CITY_LIST']['VALUE_XML_ID'])
    ) {
        unset($arResult['ITEMS'][$index]);
        continue;
    }
    unset($item['DISPLAY_PROPERTIES']);
    //  Определим, когда окончание таймера: сегодня или позже
    if ($item['PROPERTIES']['TIMER']['VALUE']) {
        $dateStart = time();
        $expire = strtotime($item['PROPERTIES']['TIMER']['VALUE']);
        if ($expire > $dateStart) {
            $hours = floor(abs($expire - $dateStart) / 60) / 60;
            $item['PROPERTIES']['TIMER']['EXPIRE'] = 'PAST';
            $item['PROPERTIES']['TIMER']['EXPIRE'] = $hours <= 24 ? 'TODAY' : 'LATER';
            $item['PROPERTIES']['TIMER']['TIMESTAMP'] = $expire;
            $item['SHOW_TIMER'] = 'Y';
        } else {
            $item['PROPERTIES']['TIMER']['TIMESTAMP'] = '';
            $item['SHOW_TIMER'] = 'N';
        }
    }
}
