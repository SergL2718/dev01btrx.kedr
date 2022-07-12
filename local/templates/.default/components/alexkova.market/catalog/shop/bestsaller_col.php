<?
/*
 * @updated 05.12.2020, 15:25
 * @author Артамонов Денис <artamonov.ceo@gmail.com>
 * @copyright Copyright (c) 2020, Компания Webco
 * @link http://wbc.cx
 */

/**
 * @var CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var array $arRecomData
 * @var array $arResponsiveParams
 * @var array $elementLibrary
 * @var CBitrixComponent $component
 */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die;

global $bxreadyMarkers;
?>

<div class="col-xs-12 hidden-xs">
    <? $APPLICATION->IncludeComponent(
        "bitrix:sale.bestsellers",
        "",
        array(
            "HIDE_NOT_AVAILABLE" => $arParams["HIDE_NOT_AVAILABLE"],
            "PAGE_ELEMENT_COUNT" => $arParams["BESTSALLERS_CNT"],
            "SHOW_DISCOUNT_PERCENT" => $arParams['SHOW_DISCOUNT_PERCENT'],
            "PRODUCT_SUBSCRIPTION" => $arParams['PRODUCT_SUBSCRIPTION'],
            "SHOW_NAME" => "Y",
            "SHOW_IMAGE" => "Y",
            "MESS_BTN_BUY" => $arParams['MESS_BTN_BUY'],
            "MESS_BTN_DETAIL" => $arParams['MESS_BTN_DETAIL'],
            "MESS_NOT_AVAILABLE" => $arParams['MESS_NOT_AVAILABLE'],
            "MESS_BTN_SUBSCRIBE" => $arParams['MESS_BTN_SUBSCRIBE'],
            "LINE_ELEMENT_COUNT" => 5,
            "TEMPLATE_THEME" => (isset($arParams['TEMPLATE_THEME']) ? $arParams['TEMPLATE_THEME'] : ''),
            "DETAIL_URL" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["element"],
            "CACHE_TYPE" => $arParams["CACHE_TYPE"],
            "CACHE_TIME" => $arParams["CACHE_TIME"],
            "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
            "BY" => array(
                0 => "AMOUNT",
            ),
            "PERIOD" => array(
                0 => "15",
            ),
            "FILTER" => array(
                0 => "CANCELED",
                1 => "ALLOW_DELIVERY",
                2 => "PAYED",
                3 => "DEDUCTED",
                4 => "N",
                5 => "P",
                6 => "F",
            ),
            "FILTER_NAME" => $arParams["FILTER_NAME"],
            "ORDER_FILTER_NAME" => "arOrderFilter",
            "DISPLAY_COMPARE" => $arParams["USE_COMPARE"],
            "SHOW_OLD_PRICE" => $arParams['SHOW_OLD_PRICE'],
            "PRICE_CODE" => $arParams["PRICE_CODE"],
            "SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
            "PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
            "CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
            "CURRENCY_ID" => $arParams["CURRENCY_ID"],
            "BASKET_URL" => $arParams["BASKET_URL"],
            "ACTION_VARIABLE" => (!empty($arParams["ACTION_VARIABLE"]) ? $arParams["ACTION_VARIABLE"] : "action") . "_slb",
            "PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
            "PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
            "ADD_PROPERTIES_TO_BASKET" => (isset($arParams["ADD_PROPERTIES_TO_BASKET"]) ? $arParams["ADD_PROPERTIES_TO_BASKET"] : ''),
            "PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
            "PARTIAL_PRODUCT_PROPERTIES" => (isset($arParams["PARTIAL_PRODUCT_PROPERTIES"]) ? $arParams["PARTIAL_PRODUCT_PROPERTIES"] : ''),
            "USE_PRODUCT_QUANTITY" => $arParams['USE_PRODUCT_QUANTITY'],
            "SHOW_PRODUCTS_" . $arParams["IBLOCK_ID"] => "Y",
            "OFFER_TREE_PROPS_" . $arRecomData['OFFER_IBLOCK_ID'] => $arParams["OFFER_TREE_PROPS"],
            "ADDITIONAL_PICT_PROP_" . $arParams['IBLOCK_ID'] => $arParams['ADD_PICT_PROP'],
            "ADDITIONAL_PICT_PROP_" . $arRecomData['OFFER_IBLOCK_ID'] => $arParams['OFFER_ADD_PICT_PROP'],
            "BLOCK_TITLE" => $arParams["TOP_TITLE"],
            "LG_CNT" => 12,
            "MD_CNT" => 12,
            "SM_CNT" => 12,
            "XS_CNT" => 12,
            "BXREADY_ELEMENT_DRAW" => $elementLibrary,
            "BXREADY_LIST_VERTICAL_SLIDER_MODE" => "N",
            "BXREADY_LIST_HIDE_SLIDER_ARROWS" => "Y",
            "BXREADY_LIST_HIDE_MOBILE_SLIDER_ARROWS" => "N",
            "BXREADY_LIST_MARKER_TYPE" => $bxreadyMarkers,
            "USE_VOTE_RATING" => "Y",
            "VOTE_DISPLAY_AS_RATING" => "N",
            "SHOW_CATALOG_QUANTITY_CNT" => $arParams["SHOW_CATALOG_QUANTITY_CNT"],
            "SHOW_CATALOG_QUANTITY" => $arParams["SHOW_CATALOG_QUANTITY"],
            "QTY_SHOW_TYPE" => $arParams["QTY_SHOW_TYPE"],
            "IN_STOCK" => $arParams["IN_STOCK"],
            "NOT_IN_STOCK" => $arParams["NOT_IN_STOCK"],
            "QTY_MANY_GOODS_INT" => $arParams["QTY_MANY_GOODS_INT"],
            "QTY_MANY_GOODS_TEXT" => $arParams["QTY_MANY_GOODS_TEXT"],
            "QTY_LESS_GOODS_TEXT" => $arParams["QTY_LESS_GOODS_TEXT"],
            "OFFERS_VIEW" => $arParams["OFFERS_VIEW"],
        ),
        $component,
        array("HIDE_ICONS" => "Y")
    ); ?>
</div>
