<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

$SECTION_CODE = $_GET["SECTION_CODE"];
CModule::IncludeModule("iblock");
$IBLOCK_ID = 41;
$arSelect = array();
$arFilter = array(
	"IBLOCK_ID" => $IBLOCK_ID,
	"ACTIVE" => "Y",
	"CODE" => $SECTION_CODE
);
$res = CIBlockSection::GetList(array("SORT" => "ASC"), $arFilter, false, Array("*", "UF_*"));
while ($ar_fields = $res->GetNext()) {
	$arResult = $ar_fields;
    //echo "<pre>"; print_r($arResult); echo "</pre>";
	$ipropValues = new \Bitrix\Iblock\InheritedProperty\SectionValues($ar_fields["IBLOCK_ID"], $ar_fields["ID"]);
	$arResult["IPROPERTY_VALUES"] = $ipropValues->getValues();
	//echo "<pre>"; print_r($arResult); echo "</pre>";
	if ($arResult["DETAIL_PICTURE"]) {
		$arResult["DETAIL_PICTURE_SRC"] = CFile::GetPath($arResult["DETAIL_PICTURE"]);
	} else {
		$arResult["DETAIL_PICTURE_SRC"] = CFile::GetPath($arResult["PICTURE"]);
	}
	$APPLICATION->SetTitle($arResult["IPROPERTY_VALUES"]["SECTION_META_TITLE"]);
	$APPLICATION->AddChainItem($ar_fields["NAME"]);


	/*$MAX_STR = 400;
	$obParser = new CTextParser;
	if (strlen($arResult["DESCRIPTION"]) > $MAX_STR) {
		$arResult["PREVIEW_TEXT"] = $obParser->html_cut($arResult["DESCRIPTION"], $MAX_STR);
	} else {
		$arResult["PREVIEW_TEXT"] = $arResult["DESCRIPTION"];
	}*/
	?>

    <section class="manufacturer-page">
        <div class="container">
            <div class="page-title"><?= $arResult["NAME"] ?></div>
            <div class="manufacturer-page-info">
                <div class="manufacturer-page-info__image"><img src="<?= $arResult["DETAIL_PICTURE_SRC"] ?>"
                                                                alt=""></div>
                <div class="manufacturer-page-info__content">
                    <div class="manufacturer-page-info__content-wrap"><?= $arResult["UF_PREVIEW_TEXT"] ?>
                    </div>
					<? if ($arResult["DESCRIPTION"]) { ?>
                        <div class="link-more">ПОКАЗАТЬ БОЛЬШЕ</div>
					<?
					} ?>
                </div>
            </div>
            <div class="manufacturer-page-more">
                <div class="manufacturer-page-more-wrap"><?= $arResult["DESCRIPTION"] ?></div>
            </div>
        </div>
    </section>



	<?
	$intSectionID = $APPLICATION->IncludeComponent(
	"bitrix:catalog.section", 
	"slider_manufacturers", 
	array(
		"IBLOCK_TYPE" => "1c_catalog",
		"IBLOCK_ID" => "41",
		"ELEMENT_SORT_FIELD" => $arParams["ELEMENT_SORT_FIELD"],
		"ELEMENT_SORT_ORDER" => $arParams["ELEMENT_SORT_ORDER"],
		"ELEMENT_SORT_FIELD2" => $arParams["ELEMENT_SORT_FIELD2"],
		"ELEMENT_SORT_ORDER2" => $arParams["ELEMENT_SORT_ORDER2"],
		"PROPERTY_CODE" => (isset($arParams["LIST_PROPERTY_CODE"])?$arParams["LIST_PROPERTY_CODE"]:[]),
		"PROPERTY_CODE_MOBILE" => "",
		"META_KEYWORDS" => $arParams["LIST_META_KEYWORDS"],
		"META_DESCRIPTION" => $arParams["LIST_META_DESCRIPTION"],
		"BROWSER_TITLE" => "-",
		"SET_LAST_MODIFIED" => "N",
		"INCLUDE_SUBSECTIONS" => "Y",
		"BASKET_URL" => $arParams["BASKET_URL"],
		"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
		"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
		"SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
		"PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
		"PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
		"FILTER_NAME" => "arrFilter",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "N",
		"SET_TITLE" => "N",
		"MESSAGE_404" => $arParams["~MESSAGE_404"],
		"SET_STATUS_404" => "N",
		"SHOW_404" => "N",
		"FILE_404" => $arParams["FILE_404"],
		"DISPLAY_COMPARE" => "N",
		"PAGE_ELEMENT_COUNT" => "156",
		"LINE_ELEMENT_COUNT" => $arParams["LINE_ELEMENT_COUNT"],
		"PRICE_CODE" => array(
			0 => "BASE",
		),
		"USE_PRICE_COUNT" => "N",
		"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
		"PRICE_VAT_INCLUDE" => "N",
		"USE_PRODUCT_QUANTITY" => "N",
		"ADD_PROPERTIES_TO_BASKET" => "N",
		"PARTIAL_PRODUCT_PROPERTIES" => "N",
		"PRODUCT_PROPERTIES" => (isset($arParams["PRODUCT_PROPERTIES"])?$arParams["PRODUCT_PROPERTIES"]:[]),
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"PAGER_TITLE" => $arParams["PAGER_TITLE"],
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"],
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
		"PAGER_SHOW_ALL" => "N",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"PAGER_BASE_LINK" => $arParams["PAGER_BASE_LINK"],
		"PAGER_PARAMS_NAME" => $arParams["PAGER_PARAMS_NAME"],
		"LAZY_LOAD" => "N",
		"MESS_BTN_LAZY_LOAD" => $arParams["~MESS_BTN_LAZY_LOAD"],
		"LOAD_ON_SCROLL" => "N",
		"OFFERS_CART_PROPERTIES" => (isset($arParams["OFFERS_CART_PROPERTIES"])?$arParams["OFFERS_CART_PROPERTIES"]:[]),
		"OFFERS_FIELD_CODE" => array(
			0 => "",
			1 => $arParams["LIST_OFFERS_FIELD_CODE"],
			2 => "",
		),
		"OFFERS_PROPERTY_CODE" => (isset($arParams["LIST_OFFERS_PROPERTY_CODE"])?$arParams["LIST_OFFERS_PROPERTY_CODE"]:[]),
		"OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
		"OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
		"OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
		"OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
		"OFFERS_LIMIT" => (isset($arParams["LIST_OFFERS_LIMIT"])?$arParams["LIST_OFFERS_LIMIT"]:0),
		"SECTION_ID" => $arResult["ID"],
		"SECTION_CODE" => "",
		"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
		"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
		"USE_MAIN_ELEMENT_SECTION" => "N",
		"CONVERT_CURRENCY" => "N",
		"CURRENCY_ID" => $arParams["CURRENCY_ID"],
		"HIDE_NOT_AVAILABLE" => "N",
		"HIDE_NOT_AVAILABLE_OFFERS" => "N",
		"LABEL_PROP" => array(
		),
		"LABEL_PROP_MOBILE" => $arParams["LABEL_PROP_MOBILE"],
		"LABEL_PROP_POSITION" => $arParams["LABEL_PROP_POSITION"],
		"ADD_PICT_PROP" => "-",
		"PRODUCT_DISPLAY_MODE" => "N",
		"PRODUCT_BLOCKS_ORDER" => $arParams["LIST_PRODUCT_BLOCKS_ORDER"],
		"PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'6','BIG_DATA':false},{'VARIANT':'6','BIG_DATA':false},{'VARIANT':'6','BIG_DATA':false},{'VARIANT':'6','BIG_DATA':false},{'VARIANT':'6','BIG_DATA':false},{'VARIANT':'6','BIG_DATA':false},{'VARIANT':'6','BIG_DATA':false},{'VARIANT':'6','BIG_DATA':false},{'VARIANT':'6','BIG_DATA':false},{'VARIANT':'6','BIG_DATA':false},{'VARIANT':'6','BIG_DATA':false},{'VARIANT':'6','BIG_DATA':false},{'VARIANT':'6','BIG_DATA':false},{'VARIANT':'6','BIG_DATA':false},{'VARIANT':'6','BIG_DATA':false},{'VARIANT':'6','BIG_DATA':false},{'VARIANT':'6','BIG_DATA':false},{'VARIANT':'6','BIG_DATA':false},{'VARIANT':'6','BIG_DATA':false},{'VARIANT':'6','BIG_DATA':false},{'VARIANT':'6','BIG_DATA':false},{'VARIANT':'6','BIG_DATA':false},{'VARIANT':'6','BIG_DATA':false},{'VARIANT':'6','BIG_DATA':false},{'VARIANT':'6','BIG_DATA':false},{'VARIANT':'6','BIG_DATA':false}]",
		"ENLARGE_PRODUCT" => "STRICT",
		"ENLARGE_PROP" => isset($arParams["LIST_ENLARGE_PROP"])?$arParams["LIST_ENLARGE_PROP"]:"",
		"SHOW_SLIDER" => "N",
		"SLIDER_INTERVAL" => isset($arParams["LIST_SLIDER_INTERVAL"])?$arParams["LIST_SLIDER_INTERVAL"]:"",
		"SLIDER_PROGRESS" => isset($arParams["LIST_SLIDER_PROGRESS"])?$arParams["LIST_SLIDER_PROGRESS"]:"",
		"OFFER_ADD_PICT_PROP" => $arParams["OFFER_ADD_PICT_PROP"],
		"OFFER_TREE_PROPS" => (isset($arParams["OFFER_TREE_PROPS"])?$arParams["OFFER_TREE_PROPS"]:[]),
		"PRODUCT_SUBSCRIPTION" => "N",
		"SHOW_DISCOUNT_PERCENT" => "N",
		"DISCOUNT_PERCENT_POSITION" => $arParams["DISCOUNT_PERCENT_POSITION"],
		"SHOW_OLD_PRICE" => "N",
		"SHOW_MAX_QUANTITY" => "N",
		"MESS_SHOW_MAX_QUANTITY" => (isset($arParams["~MESS_SHOW_MAX_QUANTITY"])?$arParams["~MESS_SHOW_MAX_QUANTITY"]:""),
		"RELATIVE_QUANTITY_FACTOR" => (isset($arParams["RELATIVE_QUANTITY_FACTOR"])?$arParams["RELATIVE_QUANTITY_FACTOR"]:""),
		"MESS_RELATIVE_QUANTITY_MANY" => (isset($arParams["~MESS_RELATIVE_QUANTITY_MANY"])?$arParams["~MESS_RELATIVE_QUANTITY_MANY"]:""),
		"MESS_RELATIVE_QUANTITY_FEW" => (isset($arParams["~MESS_RELATIVE_QUANTITY_FEW"])?$arParams["~MESS_RELATIVE_QUANTITY_FEW"]:""),
		"MESS_BTN_BUY" => (isset($arParams["~MESS_BTN_BUY"])?$arParams["~MESS_BTN_BUY"]:""),
		"MESS_BTN_ADD_TO_BASKET" => (isset($arParams["~MESS_BTN_ADD_TO_BASKET"])?$arParams["~MESS_BTN_ADD_TO_BASKET"]:""),
		"MESS_BTN_SUBSCRIBE" => (isset($arParams["~MESS_BTN_SUBSCRIBE"])?$arParams["~MESS_BTN_SUBSCRIBE"]:""),
		"MESS_BTN_DETAIL" => (isset($arParams["~MESS_BTN_DETAIL"])?$arParams["~MESS_BTN_DETAIL"]:""),
		"MESS_NOT_AVAILABLE" => (isset($arParams["~MESS_NOT_AVAILABLE"])?$arParams["~MESS_NOT_AVAILABLE"]:""),
		"MESS_BTN_COMPARE" => (isset($arParams["~MESS_BTN_COMPARE"])?$arParams["~MESS_BTN_COMPARE"]:""),
		"USE_ENHANCED_ECOMMERCE" => "N",
		"DATA_LAYER_NAME" => (isset($arParams["DATA_LAYER_NAME"])?$arParams["DATA_LAYER_NAME"]:""),
		"BRAND_PROPERTY" => (isset($arParams["BRAND_PROPERTY"])?$arParams["BRAND_PROPERTY"]:""),
		"TEMPLATE_THEME" => (isset($arParams["TEMPLATE_THEME"])?$arParams["TEMPLATE_THEME"]:""),
		"ADD_SECTIONS_CHAIN" => "N",
		"ADD_TO_BASKET_ACTION" => "ADD",
		"SHOW_CLOSE_POPUP" => "N",
		"COMPARE_PATH" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["compare"],
		"COMPARE_NAME" => $arParams["COMPARE_NAME"],
		"USE_COMPARE_LIST" => "Y",
		"BACKGROUND_IMAGE" => (isset($arParams["SECTION_BACKGROUND_IMAGE"])?$arParams["SECTION_BACKGROUND_IMAGE"]:""),
		"COMPATIBLE_MODE" => "N",
		"DISABLE_INIT_JS_IN_COMPONENT" => "N",
		"COMPONENT_TEMPLATE" => "slider_manufacturers",
		"SECTION_USER_FIELDS" => array(
			0 => "",
			1 => "",
		),
		"SHOW_ALL_WO_SECTION" => "Y",
		"CUSTOM_FILTER" => "{\"CLASS_ID\":\"CondGroup\",\"DATA\":{\"All\":\"AND\",\"True\":\"True\"},\"CHILDREN\":[]}",
		"RCM_TYPE" => "personal",
		"RCM_PROD_ID" => $_REQUEST["PRODUCT_ID"],
		"SHOW_FROM_SECTION" => "N",
		"SEF_MODE" => "N",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "N",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"SET_BROWSER_TITLE" => "N",
		"SET_META_KEYWORDS" => "N",
		"SET_META_DESCRIPTION" => "N",
		"TITLE" => "ТОВАРЫ ПРОИЗВОДИТЕЛЯ"
	),
	false
);
	?>

    <?php
    $LAST_NEXT = Array();
	$arFilter = array(
		"IBLOCK_ID" => $IBLOCK_ID,
		"ACTIVE" => "Y",
        "DEPTH_LEVEL" => 1
	);
	$res = CIBlockSection::GetList(array("SORT" => "ASC"), $arFilter, false);
	while ($ar_fields = $res->GetNext()) {
        //echo "<pre>"; print_r($ar_fields); echo "</pre>";
		$LAST_NEXT[] = Array("ID"=>$ar_fields["ID"], "URL"=>$ar_fields["SECTION_PAGE_URL"]);
	}
    $LAST = Array();
    $NEXT = Array();
    foreach($LAST_NEXT as $KEY=>$ARR){
        if($ARR["ID"] == $arResult["ID"]){
			if($LAST_NEXT[$KEY-1])$LAST = $LAST_NEXT[$KEY-1];
            else $LAST = end($LAST_NEXT);
			if($LAST_NEXT[$KEY+1])$NEXT = $LAST_NEXT[$KEY+1];
            else $NEXT = current($LAST_NEXT);
        }
    }
	//echo "<pre>"; print_r($LAST_NEXT); echo "</pre>";
    ?>
    <div class="manufacturer-page-nav">
        <div class="container">
            <div class="manufacturer-page-nav__inner">
                <a class="manufacturer-page-nav__prev" href="<?=$LAST["URL"]?>">
                    <div class="icon icon-chevron-circle-right"></div>
                    ПРЕДЫДУЩИЙ ПРОИЗВОДИТЕЛЬ</a>
                <a class="manufacturer-page-nav__next" href="<?=$NEXT["URL"]?>">СЛЕДУЮЩИЙ ПРОИЗВОДИТЕЛЬ
                    <div class="icon icon-chevron-circle-right"></div>
                </a>
            </div>
        </div>
    </div>
	<?
}
?>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
