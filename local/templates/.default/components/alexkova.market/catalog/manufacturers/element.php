<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$arParams["USE_PRODUCT_QUANTITY"] = "Y";
$arParams["SHOW_MAX_QUANTITY"] = "Y";
global $moreSettings;
use Alexkova\Market\Core;
$BXReady = \Alexkova\Market\Core::getInstance();

$this->setFrameMode(true);?>
<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
	<?if (isset($arParams["SHOW_LEFT_MENU"]) && $arParams["SHOW_LEFT_MENU"]=="Y"):?>
		<?$APPLICATION->IncludeComponent(
			"alexkova.market:menu",
			isset($arParams["LEFT_MENU_TEMPLATE"]) ? $arParams["LEFT_MENU_TEMPLATE"] : "left",
			array(
                            "ROOT_MENU_TYPE" => isset($arParams["ROOT_MENU_TYPE"]) ? $arParams["ROOT_MENU_TYPE"] : "left",
                            "MAX_LEVEL" => isset($arParams["MAX_LEVEL"]) ? $arParams["MAX_LEVEL"] : "2",
                            "CHILD_MENU_TYPE" => isset($arParams["CHILD_MENU_TYPE"]) ? $arParams["CHILD_MENU_TYPE"] : "left",
                            "USE_EXT" => isset($arParams["USE_EXT"]) ? $arParams["USE_EXT"] : "Y",
                            "DELAY" => isset($arParams["DELAY"]) ? $arParams["DELAY"] : "N",
                            "TITLE_MENU" => isset($arParams["TITLE_MENU"]) ? $arParams["TITLE_MENU"] : "",                    
                            "STYLE_MENU" => isset($arParams["STYLE_MENU"]) ? $arParams["STYLE_MENU"] : "colored_light", 			
                            "PICTURE_SECTION" => isset($arParams["PICTURE_SECTION"]) ? $arParams["PICTURE_SECTION"] : "N", 
                            "STYLE_MENU_HOVER" => isset($arParams["STYLE_MENU_HOVER"]) ? $arParams["STYLE_MENU_HOVER"] : "colored_light",
                            "PICTURE_SECTION_HOVER" => isset($arParams["PICTURE_SECTION_HOVER"]) ? $arParams["PICTURE_SECTION_HOVER"] : "N",
                            "PICTURE_CATEGARIES" => isset($arParams["PICTURE_CATEGARIES"]) ? $arParams["PICTURE_CATEGARIES"] : "N",
                            "HOVER_MENU_COL_LG" => isset($arParams["HOVER_MENU_COL_LG"]) ? $arParams["HOVER_MENU_COL_LG"] : "2",
                            "HOVER_MENU_COL_MD" => isset($arParams["HOVER_MENU_COL_MD"]) ? $arParams["HOVER_MENU_COL_MD"] : "2",
                            "HOVER_MENU_COL_SM" => isset($arParams["HOVER_MENU_COL_SM"]) ? $arParams["HOVER_MENU_COL_SM"] : "2",
                            "HOVER_MENU_COL_XS" => isset($arParams["HOVER_MENU_COL_XS"]) ? $arParams["HOVER_MENU_COL_XS"] : "2",
                            "SUBMENU" => isset($arParams["SUBMENU"]) ? $arParams["SUBMENU"] : "ACTIVE_SHOW",
                            "HOVER_TEMPLATE" => isset($arParams["HOVER_TEMPLATE"]) ? $arParams["HOVER_TEMPLATE"] : "classic",
                            "MENU_CACHE_TYPE" => $arParams["CACHE_TYPE"],
                            "MENU_CACHE_TIME" => $arParams["CACHE_TIME"],
                            "MENU_CACHE_USE_GROUPS" => $arParams["CACHE_GROUPS"],
                            "MENU_CACHE_GET_VARS" => array(
                            ),
                            "SHOW_TREE" => "Y",
                        ),
			false,
			array("HIDE_ICONS" => "Y")
		);?>
	<?endif;?>
	<?$BXReady->showBannerPlace("LEFT");?>
</div>
<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12" itemscope itemtype="http://schema.org/Product">
    <h1 itemprop="name"><?=$APPLICATION->ShowTitle('h1')?></h1>
    <?$arParams["OFFERS_VIEW"] = ($arParams["OFFERS_VIEW"]) ? $arParams["OFFERS_VIEW"] : "SELECT";?>
    <?$ElementID = $APPLICATION->IncludeComponent(
            "bitrix:catalog.element",
            "",
            array(
                    "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                    "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                    "PROPERTY_CODE" => array_merge($arParams["DETAIL_PROPERTY_CODE"], $arParams["PREVIEW_DETAIL_PROPERTY_CODE"]),
                    "META_KEYWORDS" => $arParams["DETAIL_META_KEYWORDS"],
                    "META_DESCRIPTION" => $arParams["DETAIL_META_DESCRIPTION"],
                    "BROWSER_TITLE" => $arParams["DETAIL_BROWSER_TITLE"],
                    "SET_CANONICAL_URL" => $arParams["DETAIL_SET_CANONICAL_URL"],
                    "BASKET_URL" => $arParams["BASKET_URL"],
                    "ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
                    "PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
                    "SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
                    "CHECK_SECTION_ID_VARIABLE" => (isset($arParams["DETAIL_CHECK_SECTION_ID_VARIABLE"]) ? $arParams["DETAIL_CHECK_SECTION_ID_VARIABLE"] : ''),
                    "PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
                    "PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
                    "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                    "CACHE_TIME" => $arParams["CACHE_TIME"],
                    "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                    "SET_TITLE" => $arParams["SET_TITLE"],
                    "SET_LAST_MODIFIED" => $arParams["SET_LAST_MODIFIED"],
                    "MESSAGE_404" => $arParams["MESSAGE_404"],
                    "SET_STATUS_404" => $arParams["SET_STATUS_404"],
                    "SHOW_404" => $arParams["SHOW_404"],
                    "FILE_404" => $arParams["FILE_404"],
                    "PRICE_CODE" => $arParams["PRICE_CODE"],
                    "SHOW_PRICE_NAME" => $arParams["SHOW_PRICE_NAME"],
                    "USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
                    "SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
                    "PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
                    "PRICE_VAT_SHOW_VALUE" => $arParams["PRICE_VAT_SHOW_VALUE"],
                    "USE_PRODUCT_QUANTITY" => $arParams['USE_PRODUCT_QUANTITY'],
                    "PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],
                    "ADD_PROPERTIES_TO_BASKET" => (isset($arParams["ADD_PROPERTIES_TO_BASKET"]) ? $arParams["ADD_PROPERTIES_TO_BASKET"] : ''),
                    "PARTIAL_PRODUCT_PROPERTIES" => (isset($arParams["PARTIAL_PRODUCT_PROPERTIES"]) ? $arParams["PARTIAL_PRODUCT_PROPERTIES"] : ''),
                    "LINK_IBLOCK_TYPE" => $arParams["LINK_IBLOCK_TYPE"],
                    "LINK_IBLOCK_ID" => $arParams["LINK_IBLOCK_ID"],
                    "LINK_PROPERTY_SID" => $arParams["LINK_PROPERTY_SID"],
                    "LINK_ELEMENTS_URL" => $arParams["LINK_ELEMENTS_URL"],
                    'PRODUCT_DISPLAY_MODE' => $arParams['PRODUCT_DISPLAY_MODE'],
                    "OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
                    "OFFERS_FIELD_CODE" => $arParams["DETAIL_OFFERS_FIELD_CODE"],
                    "OFFERS_PROPERTY_CODE" => $arParams["DETAIL_OFFERS_PROPERTY_CODE"],
                    "OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
                    "OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
                    "OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
                    "OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],

                    "ELEMENT_ID" => $arResult["VARIABLES"]["ELEMENT_ID"],
                    "ELEMENT_CODE" => $arResult["VARIABLES"]["ELEMENT_CODE"],
                    "SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
                    "SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
                    "SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
                    "DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
                    'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
                    'CURRENCY_ID' => $arParams['CURRENCY_ID'],
                    'HIDE_NOT_AVAILABLE' => $arParams["HIDE_NOT_AVAILABLE"],
                    'USE_ELEMENT_COUNTER' => $arParams['USE_ELEMENT_COUNTER'],
                    'SHOW_DEACTIVATED' => $arParams['SHOW_DEACTIVATED'],
                    "USE_MAIN_ELEMENT_SECTION" => $arParams["USE_MAIN_ELEMENT_SECTION"],

                    'ADD_PICT_PROP' => $arParams['ADD_PICT_PROP'],
                    'LABEL_PROP' => $arParams['LABEL_PROP'],
                    'OFFER_ADD_PICT_PROP' => $arParams['OFFER_ADD_PICT_PROP'],
                    'OFFER_TREE_PROPS' => $arParams['OFFER_TREE_PROPS'],
                    'PRODUCT_SUBSCRIPTION' => $arParams['PRODUCT_SUBSCRIPTION'],
                    'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'],
                    'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'],
                    'SHOW_MAX_QUANTITY' => $arParams['DETAIL_SHOW_MAX_QUANTITY'],
                    'MESS_BTN_BUY' => $arParams['MESS_BTN_BUY'],
                    'MESS_BTN_ADD_TO_BASKET' => $arParams['MESS_BTN_ADD_TO_BASKET'],
                    'MESS_BTN_SUBSCRIBE' => $arParams['MESS_BTN_SUBSCRIBE'],
                    'MESS_BTN_COMPARE' => $arParams['MESS_BTN_COMPARE'],
                    'MESS_NOT_AVAILABLE' => $arParams['MESS_NOT_AVAILABLE'],
                    'USE_VOTE_RATING' => $arParams['DETAIL_USE_VOTE_RATING'],
                    'VOTE_DISPLAY_AS_RATING' => (isset($arParams['DETAIL_VOTE_DISPLAY_AS_RATING']) ? $arParams['DETAIL_VOTE_DISPLAY_AS_RATING'] : ''),
                    'USE_COMMENTS' => $arParams['DETAIL_USE_COMMENTS'],
                    'BLOG_USE' => (isset($arParams['DETAIL_BLOG_USE']) ? $arParams['DETAIL_BLOG_USE'] : ''),
                    'BLOG_URL' => (isset($arParams['DETAIL_BLOG_URL']) ? $arParams['DETAIL_BLOG_URL'] : ''),
                    'BLOG_EMAIL_NOTIFY' => (isset($arParams['DETAIL_BLOG_EMAIL_NOTIFY']) ? $arParams['DETAIL_BLOG_EMAIL_NOTIFY'] : ''),
                    'VK_USE' => (isset($arParams['DETAIL_VK_USE']) ? $arParams['DETAIL_VK_USE'] : ''),
                    'VK_API_ID' => (isset($arParams['DETAIL_VK_API_ID']) ? $arParams['DETAIL_VK_API_ID'] : 'API_ID'),
                    'FB_USE' => (isset($arParams['DETAIL_FB_USE']) ? $arParams['DETAIL_FB_USE'] : ''),
                    'FB_APP_ID' => (isset($arParams['DETAIL_FB_APP_ID']) ? $arParams['DETAIL_FB_APP_ID'] : ''),
                    'BRAND_USE' => (isset($arParams['DETAIL_BRAND_USE']) ? $arParams['DETAIL_BRAND_USE'] : 'N'),
                    'BRAND_PROP_CODE' => (isset($arParams['DETAIL_BRAND_PROP_CODE']) ? $arParams['DETAIL_BRAND_PROP_CODE'] : ''),
                    'DISPLAY_NAME' => (isset($arParams['DETAIL_DISPLAY_NAME']) ? $arParams['DETAIL_DISPLAY_NAME'] : ''),
                    'ADD_DETAIL_TO_SLIDER' => (isset($arParams['DETAIL_ADD_DETAIL_TO_SLIDER']) ? $arParams['DETAIL_ADD_DETAIL_TO_SLIDER'] : ''),
                    'ADD_DETAIL_TO_SLIDER_SKU' => (isset($arParams['DETAIL_ADD_DETAIL_TO_SLIDER_SKU']) ? $arParams['DETAIL_ADD_DETAIL_TO_SLIDER_SKU'] : 'Y'),
                    'TEMPLATE_THEME' => (isset($arParams['TEMPLATE_THEME']) ? $arParams['TEMPLATE_THEME'] : ''),
                    "ADD_SECTIONS_CHAIN" => (isset($arParams["ADD_SECTIONS_CHAIN"]) ? $arParams["ADD_SECTIONS_CHAIN"] : ''),
                    "ADD_ELEMENT_CHAIN" => (isset($arParams["ADD_ELEMENT_CHAIN"]) ? $arParams["ADD_ELEMENT_CHAIN"] : ''),
                    "DISPLAY_PREVIEW_TEXT_MODE" => (isset($arParams['DETAIL_DISPLAY_PREVIEW_TEXT_MODE']) ? $arParams['DETAIL_DISPLAY_PREVIEW_TEXT_MODE'] : ''),
		    "HIDE_PREVIEW_PROPS_INLIST" => (isset($arParams['HIDE_PREVIEW_PROPS_INLIST']) ? $arParams['HIDE_PREVIEW_PROPS_INLIST'] : ''),
                    "DETAIL_PICTURE_MODE" => (isset($arParams['DETAIL_DETAIL_PICTURE_MODE']) ? $arParams['DETAIL_DETAIL_PICTURE_MODE'] : ''),
                    'ADD_TO_BASKET_ACTION' => $basketAction,
                    'SHOW_CLOSE_POPUP' => isset($arParams['COMMON_SHOW_CLOSE_POPUP']) ? $arParams['COMMON_SHOW_CLOSE_POPUP'] : '',
                    'DISPLAY_COMPARE' => (isset($arParams['USE_COMPARE']) ? $arParams['USE_COMPARE'] : ''),
                    'COMPARE_PATH' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['compare'],
                    'SHOW_BASIS_PRICE' => (isset($arParams['DETAIL_SHOW_BASIS_PRICE']) ? $arParams['DETAIL_SHOW_BASIS_PRICE'] : 'Y'),
                    'BACKGROUND_IMAGE' => (isset($arParams['DETAIL_BACKGROUND_IMAGE']) ? $arParams['DETAIL_BACKGROUND_IMAGE'] : ''),
                    "ZOOM_ON" => $arParams["ZOOM_ON"],
                    "OFFERS_VIEW" => $arParams["OFFERS_VIEW"],
                    "HIDE_OFFERS_LIST" => $arParams["HIDE_OFFERS_LIST"],
                    "GROUP_PRICE_COUNT" => $arParams["GROUP_PRICE_COUNT"],
                    "OFFER_PRICE_SHOW_FROM" => $arParams["OFFER_PRICE_SHOW_FROM"],
                    "MIN_AMOUNT" => $arParams["MIN_AMOUNT"],
                    "USE_MIN_AMOUNT" => $arParams["USE_MIN_AMOUNT"],
                    "SHOW_CATALOG_QUANTITY_CNT" => $arParams["SHOW_CATALOG_QUANTITY_CNT"],
                    "SHOW_CATALOG_QUANTITY" => $arParams["SHOW_CATALOG_QUANTITY"],
                    "QTY_SHOW_TYPE" => $arParams["QTY_SHOW_TYPE"],
                    "IN_STOCK" => $arParams["IN_STOCK"],
                    "NOT_IN_STOCK" => $arParams["NOT_IN_STOCK"],
                    "QTY_MANY_GOODS_INT" => $arParams["QTY_MANY_GOODS_INT"],
                    "QTY_MANY_GOODS_TEXT" => $arParams["QTY_MANY_GOODS_TEXT"],
                    "QTY_LESS_GOODS_TEXT" => $arParams["QTY_LESS_GOODS_TEXT"],
                    "PREVIEW_DETAIL_PROPERTY_CODE" => $arParams["PREVIEW_DETAIL_PROPERTY_CODE"],
                    "USE_REVIEW" => $arParams["USE_REVIEW"],
                    "USE_STORE" => $arParams["USE_STORE"],  
                    "SKU_PROPS_SHOW_TYPE" => $arParams["SKU_PROPS_SHOW_TYPE"],
                    "DETAIL_DISPLAY_SHOW_FILES" => $arParams["DETAIL_DISPLAY_SHOW_FILES"],
                    "DETAIL_DISPLAY_SHOW_VIDEO" => $arParams["DETAIL_DISPLAY_SHOW_VIDEO"],
                    "VIDEO_TYPE" => $arParams["VIDEO_TYPE"],
                    "VIDEO_PLAYER" => $arParams["VIDEO_PLAYER"],
                    "VIDEO_PLAYER_FULLSCREEN" => $arParams["VIDEO_PLAYER_FULLSCREEN"],
		    "STORES" => $arParams["STORES"],
		    "SHOW_EMPTY_STORE" => $arParams["SHOW_EMPTY_STORE"],
		    "SHOW_GENERAL_STORE_INFORMATION" => $arParams["SHOW_GENERAL_STORE_INFORMATION"],
		    "FIELDS" => $arParams["FIELDS"],
		    "USER_FIELDS" => $arParams["USER_FIELDS"],
		    "MAIN_TITLE" => $arParams["MAIN_TITLE"]
            ),
            $component
    );?>
    
    <?
    if (0 < $ElementID)
    {
            $arRecomData = array();
            $recomCacheID = array('IBLOCK_ID' => $arParams['IBLOCK_ID']);
            $obCache = new CPHPCache();
            if ($obCache->InitCache(36000, serialize($recomCacheID), "/catalog/recommended"))
            {
                    $arRecomData = $obCache->GetVars();
            }
            elseif ($obCache->StartDataCache())
            {
                    if (\Bitrix\Main\Loader::includeModule("catalog"))
                    {
                            $arSKU = CCatalogSKU::GetInfoByProductIBlock($arParams['IBLOCK_ID']);
                            $arRecomData['OFFER_IBLOCK_ID'] = (!empty($arSKU) ? $arSKU['IBLOCK_ID'] : 0);
                            $arRecomData['IBLOCK_LINK'] = '';
                            $arRecomData['ALL_LINK'] = '';
                            $rsProps = CIBlockProperty::GetList(
                                    array('SORT' => 'ASC', 'ID' => 'ASC'),
                                    array('IBLOCK_ID' => $arParams['IBLOCK_ID'], 'PROPERTY_TYPE' => 'E', 'ACTIVE' => 'Y')
                            );
                            $found = false;
                            while ($arProp = $rsProps->Fetch())
                            {
                                    if ($found)
                                    {
                                            break;
                                    }
                                    if ($arProp['CODE'] == '')
                                    {
                                            $arProp['CODE'] = $arProp['ID'];
                                    }
                                    $arProp['LINK_IBLOCK_ID'] = intval($arProp['LINK_IBLOCK_ID']);
                                    if ($arProp['LINK_IBLOCK_ID'] != 0 && $arProp['LINK_IBLOCK_ID'] != $arParams['IBLOCK_ID'])
                                    {
                                            continue;
                                    }
                                    if ($arProp['LINK_IBLOCK_ID'] > 0)
                                    {
                                            if ($arRecomData['IBLOCK_LINK'] == '')
                                            {
                                                    $arRecomData['IBLOCK_LINK'] = $arProp['CODE'];
                                                    $found = true;
                                            }
                                    }
                                    else
                                    {
                                            if ($arRecomData['ALL_LINK'] == '')
                                            {
                                                    $arRecomData['ALL_LINK'] = $arProp['CODE'];
                                            }
                                    }
                            }
                            if ($found)
                            {
                                    if(defined("BX_COMP_MANAGED_CACHE"))
                                    {
                                            global $CACHE_MANAGER;
                                            $CACHE_MANAGER->StartTagCache("/catalog/recommended");
                                            $CACHE_MANAGER->RegisterTag("iblock_id_".$arParams["IBLOCK_ID"]);
                                            $CACHE_MANAGER->EndTagCache();
                                    }
                            }
                    }
                    $obCache->EndDataCache($arRecomData);
            }
            if (!empty($arRecomData) && ($arRecomData['IBLOCK_LINK'] != '' || $arRecomData['ALL_LINK'] != ''))
            {
            ?><?
            }

            if($arParams["USE_ALSO_BUY"] == "Y" && \Bitrix\Main\ModuleManager::isModuleInstalled("sale") && !empty($arRecomData))
            {
                    ?><?$APPLICATION->IncludeComponent("bitrix:sale.recommended.products", 'market_recommended' , array(
                            "ID" => $ElementID,
                            "TEMPLATE_THEME" => (isset($arParams['TEMPLATE_THEME']) ? $arParams['TEMPLATE_THEME'] : ''),
                            "MIN_BUYES" => $arParams["ALSO_BUY_MIN_BUYES"],
                            "ELEMENT_COUNT" => $arParams["ALSO_BUY_ELEMENT_COUNT"],
                            "LINE_ELEMENT_COUNT" => $arParams["ALSO_BUY_ELEMENT_COUNT"],
                            "DETAIL_URL" => $arParams["DETAIL_URL"],
                            "BASKET_URL" => $arParams["BASKET_URL"],
                            "ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
                            "PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
                            "SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
                            "PAGE_ELEMENT_COUNT" => $arParams["ALSO_BUY_ELEMENT_COUNT"],
                            "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                            "CACHE_TIME" => $arParams["CACHE_TIME"],
                            "PRICE_CODE" => $arParams["PRICE_CODE"],
                            "USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
                            "SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
                            "PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
                            'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
                            'CURRENCY_ID' => $arParams['CURRENCY_ID'],
                            'HIDE_NOT_AVAILABLE' => $arParams["HIDE_NOT_AVAILABLE"],
                            "SHOW_PRODUCTS_".$arParams["IBLOCK_ID"] => "Y",
                            "PROPERTY_CODE_".$arRecomData['OFFER_IBLOCK_ID'] => array(    ),
                            "OFFER_TREE_PROPS_".$arRecomData['OFFER_IBLOCK_ID'] => $arParams["OFFER_TREE_PROPS"],
                            "BLOCK_TITLE" => $arParams["ALSO_BUY_TITLE"],
                            ),
                            $component
                    );
    ?><?
            }
    }
    ?>
	<?$BXReady->showBannerPlace("CATALOG_BOTTOM");?>
</div>
<div class="clearfix"></div>