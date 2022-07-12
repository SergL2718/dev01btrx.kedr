<?php
/*
 * Изменено: 06 декабря 2021, понедельник
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

global $APPLICATION, $USER;

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Корзина");


LocalRedirect('/cart/');
if (\Native\App\Template::isNewVersion()) {

}

$template = 'native';
$APPLICATION->IncludeComponent(
	"bitrix:sale.basket.basket",
	$template,
	array (
		"COUNT_DISCOUNT_4_ALL_QUANTITY" => "N",
		"COLUMNS_LIST"                  => array (
			0 => "NAME",
			1 => "DISCOUNT",
			2 => "PROPS",
			3 => "DELETE",
            4 => "DELAY",
            5 => "PRICE",
            6 => "QUANTITY",
            7 => "SUM",
        ),
        "AJAX_MODE" => "N",
        "AJAX_OPTION_JUMP" => "N",
        "AJAX_OPTION_STYLE" => "Y",
        "AJAX_OPTION_HISTORY" => "N",
        "PATH_TO_ORDER" => SITE_DIR . "personal/order/make/",
        "HIDE_COUPON" => "N",
        "QUANTITY_FLOAT" => "N",
        "PRICE_VAT_SHOW_VALUE" => "N",
        "SET_TITLE" => "Y",
        "AJAX_OPTION_ADDITIONAL" => "",
        "OFFERS_PROPS" => "",
        "COMPONENT_TEMPLATE" => $template,
        "USE_PREPAYMENT" => "N",
        "ACTION_VARIABLE" => "action",
        "COLUMNS_LIST_EXT" => array(
            0 => "DISCOUNT",
            1 => "DELETE",
            2 => "DELAY",
            3 => "SUM",
        ),
        "CORRECT_RATIO" => "Y",
        "AUTO_CALCULATION" => "Y",
        "COMPATIBLE_MODE" => "Y",
        "GIFTS_TEXT_LABEL_GIFT" => "",
        "GIFTS_PRODUCT_PROPS_VARIABLE" => "",
        "GIFTS_SHOW_OLD_PRICE" => "N",
        "GIFTS_SHOW_DISCOUNT_PERCENT" => "N",
        "GIFTS_SHOW_NAME" => "N",
        "GIFTS_SHOW_IMAGE" => "N",
        "GIFTS_CONVERT_CURRENCY" => "N",
        "ADDITIONAL_PICT_PROP_37" => "-",
        "ADDITIONAL_PICT_PROP_38" => "-",
        "ADDITIONAL_PICT_PROP_39" => "-",
        "ADDITIONAL_PICT_PROP_40" => "-",
        "ADDITIONAL_PICT_PROP_41" => "-",
        "ADDITIONAL_PICT_PROP_42" => "-",
        "ADDITIONAL_PICT_PROP_51" => "-",
        "ADDITIONAL_PICT_PROP_52" => "-",
        "BASKET_IMAGES_SCALING" => "adaptive",
        "USE_GIFTS" => "Y",
        "GIFTS_PLACE" => "TOP",
        "GIFTS_BLOCK_TITLE" => "Выберите подарок",
        "GIFTS_HIDE_BLOCK_TITLE" => "N",
        "GIFTS_PRODUCT_QUANTITY_VARIABLE" => "quantity",
        "GIFTS_MESS_BTN_BUY" => "Выбрать",
        "GIFTS_MESS_BTN_DETAIL" => "Подробнее",
        "GIFTS_PAGE_ELEMENT_COUNT" => "8",
        "GIFTS_HIDE_NOT_AVAILABLE" => "N",
        "TEMPLATE_THEME" => "blue",
        "USE_ENHANCED_ECOMMERCE" => "N",
        "DEFERRED_REFRESH" => "N",
        "USE_DYNAMIC_SCROLL" => "Y",
        "SHOW_FILTER" => "Y",
        "SHOW_RESTORE" => "Y",
        "COLUMNS_LIST_MOBILE" => array(
            0 => "DISCOUNT",
            1 => "SUM",
        ),
        "TOTAL_BLOCK_DISPLAY" => array(
            0 => "top",
        ),
        "DISPLAY_MODE" => "extended",
        "PRICE_DISPLAY_MODE" => "Y",
        "SHOW_DISCOUNT_PERCENT" => "Y",
        "DISCOUNT_PERCENT_POSITION" => "bottom-right",
        "PRODUCT_BLOCKS_ORDER" => "props,sku,columns",
        "USE_PRICE_ANIMATION" => "Y",
        "LABEL_PROP" => "",
        "LABEL_PROP_MOBILE" => "",
        "LABEL_PROP_POSITION" => ""
    ),
    false
);

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
