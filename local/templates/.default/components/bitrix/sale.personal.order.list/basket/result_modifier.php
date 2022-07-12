<?php
/*
 * @updated 09.12.2020, 18:54
 * @author Артамонов Денис <bitrix.developer@inbox.ru>
 * @copyright Copyright (c) 2020, Компания Webco <hello@wbc.cx>
 * @link http://wbc.cx
 */

/**
 * @var array $arParams
 * @var array $arResult
 */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die;

foreach ($arResult['ORDERS'] as $key => &$item) {
    $order =& $item['ORDER'];
    if ($order['STATUS_ID'] !== 'N') {
        unset($arResult['ORDERS'][$key]);
        continue;
    }

    $order['URL_TO_DETAIL'] = $order['URL_TO_DETAIL'] . '?ID=' . $order['ACCOUNT_NUMBER'];
    $order['URL_TO_COPY'] = $order['URL_TO_DETAIL'] . '&COPY_ORDER=Y';
    $order['URL_TO_CANCEL'] = $order['URL_TO_DETAIL'] . '&CANCEL=Y';
}
