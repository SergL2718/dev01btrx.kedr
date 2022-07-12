<?
/*
 * @updated 08.12.2020, 16:00
 * @author Артамонов Денис <bitrix.developer@inbox.ru>
 * @copyright Copyright (c) 2020, Компания Webco <hello@wbc.cx>
 * @link http://wbc.cx
 */

/**
 * @var CMain $APPLICATION
 * @var CUser $USER
 * @var array $arParams
 * @var array $arResult
 */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

global $ELEMENT_DATA;

$ELEMENT_DATA = array(
    "ID" => $arResult["ID"]
);

if (isset($arResult["PROPERTIES"][$arParams["LINK_PROPERTY_CODE"]]) && count($arResult["PROPERTIES"][$arParams["LINK_PROPERTY_CODE"]]["VALUE"]) > 0) {
    $ELEMENT_DATA["OTHER_ELEMENTS"] = $arResult["PROPERTIES"][$arParams["LINK_PROPERTY_CODE"]]["VALUE"];
}

global $arrFilter;

$this->__template->SetViewTarget('#PRODUCT_10085#'); //дальше контент который буферизируется
$arrFilter['ID'] = '10085';
$APPLICATION->IncludeComponent(
    "bitrix:catalog.section",
    "article",
    array(
        "ACTION_VARIABLE" => "action",
        "ADD_PROPERTIES_TO_BASKET" => "Y",
        "BASKET_URL" => "/personal/basket.php",

        "BXREADY_ELEMENT_DRAW" => "system#ecommerce.v2.lite",

        "CACHE_FILTER" => "N",
        "CACHE_GROUPS" => "Y",
        "CACHE_TIME" => "36000000",
        "CACHE_TYPE" => "A",
        "COMPONENT_TEMPLATE" => "article",
        "CONVERT_CURRENCY" => "N",
        "DISPLAY_BOTTOM_PAGER" => "N",
        "DISPLAY_TOP_PAGER" => "N",
        "ELEMENT_SORT_FIELD" => "sort",
        "ELEMENT_SORT_FIELD2" => "id",
        "ELEMENT_SORT_ORDER" => "asc",
        "ELEMENT_SORT_ORDER2" => "desc",
        "FILTER_NAME" => "arrFilter",
        "HIDE_NOT_AVAILABLE" => "Y",
        "IBLOCK_ID" => "37",
        "IBLOCK_TYPE" => "1c_catalog",
        "INCLUDE_SUBSECTIONS" => "Y",
        "OFFERS_CART_PROPERTIES" => "",
        "OFFERS_LIMIT" => "0",
        "OFFERS_PROPERTY_CODE" => "",
        "OFFERS_SORT_FIELD" => "sort",
        "OFFERS_SORT_FIELD2" => "id",
        "OFFERS_SORT_ORDER" => "asc",
        "OFFERS_SORT_ORDER2" => "desc",
        "PAGER_DESC_NUMBERING" => "N",
        "PAGER_DESC_NUMBERING_CACHE_TIME" => "3600000",
        "PAGER_SHOW_ALL" => "N",
        "PAGER_SHOW_ALWAYS" => "N",
        "PAGER_TEMPLATE" => ".default",
        "PAGER_TITLE" => "Товары",
        "PAGE_ELEMENT_COUNT" => "20",
        "PARTIAL_PRODUCT_PROPERTIES" => "N",
        "PRICE_CODE" => array(
            0 => "Розница",
        ),
        "PRICE_VAT_INCLUDE" => "Y",
        "PRODUCT_ID_VARIABLE" => "id",
        "PRODUCT_PROPERTIES" => "",
        "PRODUCT_PROPS_VARIABLE" => "prop",
        "PRODUCT_QUANTITY_VARIABLE" => "",
        "SHOW_ALL_WO_SECTION" => "Y",
        "SHOW_PRICE_COUNT" => "1",
        "TAB_ACTION_SETTING" => "Y",
        "TAB_ACTION_SORT" => "100",
        "TAB_HIT_SETTING" => "Y",
        "TAB_HIT_SORT" => "400",
        "TAB_NEW_SETTING" => "Y",
        "TAB_NEW_SORT" => "300",
        "TAB_RECCOMEND_SETTING" => "Y",
        "TAB_RECCOMEND_SORT" => "200",
        "USE_PRICE_COUNT" => "N",
        "USE_PRODUCT_QUANTITY" => "N",
        "COMPOSITE_FRAME_MODE" => "A",
        "COMPOSITE_FRAME_TYPE" => "AUTO",
        "SECTION_CODE" => "",
        "SECTION_USER_FIELDS" => array(
            0 => "",
            1 => "",
        ),
        "CUSTOM_FILTER" => "{\"CLASS_ID\":\"CondGroup\",\"DATA\":{\"All\":\"AND\",\"True\":\"True\"},\"CHILDREN\":[]}",
        "HIDE_NOT_AVAILABLE_OFFERS" => "N",
        "OFFERS_FIELD_CODE" => array(
            0 => "",
            1 => "",
        ),
        "BACKGROUND_IMAGE" => "-",
        "SECTION_URL" => "",
        "DETAIL_URL" => "",
        "SECTION_ID_VARIABLE" => "SECTION_ID",
        "SEF_MODE" => "N",
        "AJAX_MODE" => "N",
        "AJAX_OPTION_JUMP" => "N",
        "AJAX_OPTION_STYLE" => "Y",
        "AJAX_OPTION_HISTORY" => "N",
        "AJAX_OPTION_ADDITIONAL" => "",
        "SET_TITLE" => "N",
        "SET_BROWSER_TITLE" => "N",
        "BROWSER_TITLE" => "-",
        "SET_META_KEYWORDS" => "N",
        "META_KEYWORDS" => "-",
        "SET_META_DESCRIPTION" => "N",
        "META_DESCRIPTION" => "-",
        "SET_LAST_MODIFIED" => "N",
        "USE_MAIN_ELEMENT_SECTION" => "N",
        "ADD_SECTIONS_CHAIN" => "N",
        "DISPLAY_COMPARE" => "N",
        "PAGER_BASE_LINK_ENABLE" => "N",
        "SET_STATUS_404" => "N",
        "SHOW_404" => "N",
        "MESSAGE_404" => "",
        "COMPATIBLE_MODE" => "Y",
        "DISABLE_INIT_JS_IN_COMPONENT" => "N"
    ),
    false
);

$this->__template->EndViewTarget(); //конец буферизации
