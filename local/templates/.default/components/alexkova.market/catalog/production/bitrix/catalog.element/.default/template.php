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
$this->setFrameMode(true);
global $moreSettings;
$templateData = array(
	'TEMPLATE_THEME' => $this->GetFolder().'/themes/'.$arParams['TEMPLATE_THEME'].'/style.css',
	'TEMPLATE_CLASS' => 'bx_'.$arParams['TEMPLATE_THEME']
);

$strMainID = $this->GetEditAreaId($arResult['ID']);
$arItemIDs = array(
	'ID' => $strMainID,
	'PICT' => $strMainID.'_pict',
	'DISCOUNT_PICT_ID' => $strMainID.'_dsc_pict',
	'STICKER_ID' => $strMainID.'_sticker',
	'BIG_SLIDER_ID' => $strMainID.'_big_slider',
	'BIG_IMG_CONT_ID' => $strMainID.'_bigimg_cont',
	'SLIDER_CONT_ID' => $strMainID.'_slider_cont',
	'SLIDER_LIST' => $strMainID.'_slider_list',
	'SLIDER_LEFT' => $strMainID.'_slider_left',
	'SLIDER_RIGHT' => $strMainID.'_slider_right',
	'OLD_PRICE' => $strMainID.'_old_price',
	'PRICE' => $strMainID.'_price',
	'DISCOUNT_PRICE' => $strMainID.'_price_discount',
	'SLIDER_CONT_OF_ID' => $strMainID.'_slider_cont_',
	'SLIDER_LIST_OF_ID' => $strMainID.'_slider_list_',
	'SLIDER_LEFT_OF_ID' => $strMainID.'_slider_left_',
	'SLIDER_RIGHT_OF_ID' => $strMainID.'_slider_right_',
	'QUANTITY' => $strMainID.'_quantity',
	'QUANTITY_DOWN' => $strMainID.'_quant_down',
	'QUANTITY_UP' => $strMainID.'_quant_up',
	'QUANTITY_MEASURE' => $strMainID.'_quant_measure',
	'QUANTITY_LIMIT' => $strMainID.'_quant_limit',
	'BUY_LINK' => $strMainID.'_buy_link',
	'ADD_BASKET_LINK' => $strMainID.'_add_basket_link',
	'COMPARE_LINK' => $strMainID.'_compare_link',
	'PROP' => $strMainID.'_prop_',
	'PROP_DIV' => $strMainID.'_skudiv',
	'DISPLAY_PROP_DIV' => $strMainID.'_sku_prop',
	'OFFER_GROUP' => $strMainID.'_set_group_',
	'BASKET_PROP_DIV' => $strMainID.'_basket_prop',
);
$strObName = 'ob'.preg_replace("/[^a-zA-Z0-9_]/", "x", $strMainID);
$templateData['JS_OBJ'] = $strObName;

$strTitle = (
	isset($arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_TITLE"]) && '' != $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_TITLE"]
	? $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_TITLE"]
	: $arResult['NAME']
);
$strAlt = (
	isset($arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_ALT"]) && '' != $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_ALT"]
	? $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_ALT"]
	: $arResult['NAME']
);

$useVoteRating = ('Y' == $arParams['USE_VOTE_RATING']);
$useReview = ('Y' == $arParams['USE_REVIEW']);
$storeAmount = ('Y' == $arParams['USE_STORE']);
$useCompare = ('Y' == $arParams['DISPLAY_COMPARE']);
?>
<?
    $show_files = false;
    if( isset($arParams["DETAIL_DISPLAY_SHOW_FILES"]) && $arParams["DETAIL_DISPLAY_SHOW_FILES"] == "Y" &&
     (!empty($arResult["PROPERTIES"]["FILES"]["VALUE"]) || !empty($arResult["PROPERTIES"]["UF_FILES"]))) {
        $show_files = true;
    }
    
    $show_video = false;
    if(isset($arParams["DETAIL_DISPLAY_SHOW_VIDEO"]) && $arParams["DETAIL_DISPLAY_SHOW_VIDEO"] == "Y") {
        if(!empty($arResult["PROPERTIES"]["UF_VIDEO"]))
            $show_video = true;
        
        if(!empty($arResult["PROPERTIES"]["VIDEO"]["VALUE"]) && !empty($arResult["PROPERTIES"]["VIDEO"]["VALUE"][0])) 
            $show_video = true;
        else
            unset($arResult["PROPERTIES"]["VIDEO"]["VALUE"][0]);
    }

?>

<?if ($arResult['SHOP_DETAIL_PAGE_URL']):?>
    <a href="<?=$arResult['SHOP_DETAIL_PAGE_URL']?>" style="background-color: red; color: white">Ссылка на товар в магазине</a>
<?endif;?>

<?  include_once 'top_tabs.php';?>
<div class="row">
    <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
        <!--slider block, also includes manufacturer logo, sale icons and value-->
        <?if (count($arResult["MORE_PHOTO"])>0):?>
            <?  include_once 'slider.php';?>
        <?endif;?>
    </div>
    <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
        <?
        //rating block
        if ($useVoteRating)
        {?>
            <div class="bxr-rating-wrap" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
		<meta itemprop="ratingValue" content="<?=($arResult["PROPERTIES"]["rating"]["VALUE"])?$arResult["PROPERTIES"]["rating"]["VALUE"]:0?>">
		<meta itemprop="ratingCount" content="<?=($arResult["PROPERTIES"]["vote_count"]["VALUE"])?$arResult["PROPERTIES"]["vote_count"]["VALUE"]:'0'?>">
            <?$APPLICATION->IncludeComponent(
                "bitrix:iblock.vote",
                "stars",
                array(
                        "IBLOCK_TYPE" => $arParams['IBLOCK_TYPE'],
                        "IBLOCK_ID" => $arParams['IBLOCK_ID'],
                        "ELEMENT_ID" => $arResult['ID'],
                        "ELEMENT_CODE" => "",
                        "MAX_VOTE" => "5",
                        "VOTE_NAMES" => array("1", "2", "3", "4", "5"),
                        "SET_STATUS_404" => "N",
                        "DISPLAY_AS_RATING" => $arParams['VOTE_DISPLAY_AS_RATING'],
                        "CACHE_TYPE" => $arParams['CACHE_TYPE'],
                        "CACHE_TIME" => $arParams['CACHE_TIME']
                ),
                $component,
                array("HIDE_ICONS" => "Y")
            );?>
            </div>
        <?}?>
        <?  include_once 'quantity.php';?>
        <div class="clearfix"></div>
        <?if ($arResult["PROPERTIES"]["CML2_ARTICLE"]["VALUE"] && false) {?>
            <div class="bxr-good-article">
                <?=GetMessage("BXR_ARTICLE_TITLE_DETAIL").": ".$arResult["PROPERTIES"]["CML2_ARTICLE"]["VALUE"]?> 
            </div>
        <?}?>
        <div class="clearfix"></div>
        <!--prices block -->
        <div id="bxr-market-price-wrap" itemprop="offers" itemscope <?=(count($arResult["OFFERS"]) > 0)?'itemtype="http://schema.org/AggregateOffer"':'itemtype="http://schema.org/Offer"'?>>
            <?  include_once 'prices.php';?>
        </div>
        <!--sku-select block -->
        <?if (count($arResult["OFFERS"]) > 0) {?>
            <div id="bxr-market-sku-select-wrap" class="tb20">
                <?  include_once 'sku_select.php';?>
            </div>
        <?}?>
        <!--basket-btns block-->
        <div id="bxr-market-detail-basket-btn-wrap">
            <?  include_once 'basket_btn.php';?>
        </div>
        <?if ($arResult["PREVIEW_TEXT"] || count($arParams["PREVIEW_DETAIL_PROPERTY_CODE"]) > 0) {?>
            <div class="bxr-detail-preview-wrap">
                <?if ($arResult["PREVIEW_TEXT"]) {?>
                    <?  include_once 'anounce.php';?>
                <?}?>
                <?if (count($arParams["PREVIEW_DETAIL_PROPERTY_CODE"]) > 0) {?>
                    <?  include_once 'preview_props.php';?>
                <?}?>
            </div>
        <?}?>
    </div>
    <div class="clearfix"></div>
</div>
<div class="row tb20" id="bxr-detail-block-wrap">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?if ($arResult['OFFER_GROUP']) {?>
            <?  include_once 'set.php';?>
        <?}?>
        <?  include_once 'tabs.php';?>
        <?if (count($arResult["OFFERS"]) > 0) {?>
            <?  include_once 'script.php';?>
            <?if ($arParams["HIDE_OFFERS_LIST"] != "Y")?>
                <?  include_once 'offers_list.php';?>
        <?}?>
        <?if ($arResult["DETAIL_TEXT"]) {?>
            <?  include_once 'detail_text.php';?>
        <?} else {?>
	    <meta itemprop="description" content="<?=$arResult["NAME"]?>">
	<?}?>
        <?if (count($arResult["DISPLAY_PROPERTIES"]) > 0) {?>
            <?  include_once 'properties_list.php';?>
        <?}?>
        <?if ($show_files) {?>
            <?  include_once 'files.php';?>
        <?}?>
        <?if ($show_video) {?>
            <?  include_once 'video.php';?>
        <?}?>
        <?if ($storeAmount) {?>
            <?  include_once 'store_amount.php';?>
        <?}?>
    </div>
</div>