<?php
/*
 * @updated 09.12.2020, 18:38
 * @author Артамонов Денис <bitrix.developer@inbox.ru>
 * @copyright Copyright (c) 2020, Компания Webco <hello@wbc.cx>
 * @link http://wbc.cx
 */

/**
 * @var $APPLICATION
 */

if ($arParams['USE_GIFTS'] === 'Y') {
    $APPLICATION->IncludeComponent(
        "bitrix:sale.gift.basket",
        "market",
        array(
            "SHOW_PRICE_COUNT" => 1,
            "PRODUCT_SUBSCRIPTION" => 'N',
            'PRODUCT_ID_VARIABLE' => 'id',
            "PARTIAL_PRODUCT_PROPERTIES" => 'N',
            "USE_PRODUCT_QUANTITY" => 'N',
            "ACTION_VARIABLE" => "actionGift",
            "ADD_PROPERTIES_TO_BASKET" => "Y",

            "BASKET_URL" => $APPLICATION->GetCurPage(),
            "APPLIED_DISCOUNT_LIST" => $arResult["APPLIED_DISCOUNT_LIST"],
            "FULL_DISCOUNT_LIST" => $arResult["FULL_DISCOUNT_LIST"],

            "TEMPLATE_THEME" => $arParams["TEMPLATE_THEME"],
            "PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_SHOW_VALUE"],
            "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],

            'BLOCK_TITLE' => $arParams['GIFTS_BLOCK_TITLE'],
            'HIDE_BLOCK_TITLE' => $arParams['GIFTS_HIDE_BLOCK_TITLE'],
            'TEXT_LABEL_GIFT' => $arParams['GIFTS_TEXT_LABEL_GIFT'],
            'PRODUCT_QUANTITY_VARIABLE' => $arParams['GIFTS_PRODUCT_QUANTITY_VARIABLE'],
            'PRODUCT_PROPS_VARIABLE' => $arParams['GIFTS_PRODUCT_PROPS_VARIABLE'],
            'SHOW_OLD_PRICE' => $arParams['GIFTS_SHOW_OLD_PRICE'],
            'SHOW_DISCOUNT_PERCENT' => $arParams['GIFTS_SHOW_DISCOUNT_PERCENT'],
            'SHOW_NAME' => $arParams['GIFTS_SHOW_NAME'],
            'SHOW_IMAGE' => $arParams['GIFTS_SHOW_IMAGE'],
            'MESS_BTN_BUY' => $arParams['GIFTS_MESS_BTN_BUY'],
            'MESS_BTN_DETAIL' => $arParams['GIFTS_MESS_BTN_DETAIL'],
            'PAGE_ELEMENT_COUNT' => $arParams['GIFTS_PAGE_ELEMENT_COUNT'],
            'CONVERT_CURRENCY' => $arParams['GIFTS_CONVERT_CURRENCY'],
            'HIDE_NOT_AVAILABLE' => $arParams['GIFTS_HIDE_NOT_AVAILABLE'],

            "LINE_ELEMENT_COUNT" => $arParams['GIFTS_PAGE_ELEMENT_COUNT'],
        ),
        false
    );
}
