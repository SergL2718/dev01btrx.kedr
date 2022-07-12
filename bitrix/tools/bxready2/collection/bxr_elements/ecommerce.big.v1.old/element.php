<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Alexkova\Bxready2\Draw;
use Alexkova\Market2\Core;
IncludeModuleLangFile(__FILE__);

$addClass = '';
$picture = false;

global $APPLICATION;
$elementDraw = \Alexkova\Bxready2\Draw::getInstance($this);

$arElementParams["BXREADY_LIST_MARKER_TYPE"] = "ribbon.vertical";
if (isset($arElementParams["BXREADY_LIST_MARKER_TYPE"]) && strlen($arElementParams["BXREADY_LIST_MARKER_TYPE"])>0)
    $elementDraw->setMarkerCollection($arElementParams["BXREADY_LIST_MARKER_TYPE"]);

$markerGroup = array(
    "NEW" => ($arElement["PROPERTIES"]["NEWPRODUCT"]["VALUE_XML_ID"] == "Y") ? true : false,
    "SALE" => ($arElement["PROPERTIES"]["SPECIALOFFER"]["VALUE_XML_ID"] == "Y") ? true : false,
    "DISCOUNT" => $arElement["MIN_PRICE"]["DISCOUNT_DIFF_PERCENT"],
    "HIT" => ($arElement["PROPERTIES"]["SALELEADER"]["VALUE_XML_ID"] == "Y") ? true : false,
    "REC" => ($arElement["PROPERTIES"]["RECOMMENDED"]["VALUE_XML_ID"] == "Y") ? true : false,
);

$arMatrix = array('width' => 180, 'height' => 180);
if (is_array($arElement["PREVIEW_PICTURE"])){
    $picture = $elementDraw->prepareImage($arElement["PREVIEW_PICTURE"]["ID"], $arMatrix);
} else {
    if (is_array($arElement["DETAIL_PICTURE"]))
        $picture = $elementDraw->prepareImage($arElement["DETAIL_PICTURE"]["ID"], $arMatrix);
}
if (!is_array($picture) || strlen($picture["src"])<=0){
	$picture = array("src" => $elementDraw->getDefaultImage());
}

$UID = (intval($arElementParams["UNICUM_ID"])>0) ? $arElementParams["UNICUM_ID"] : 1;

$strMainID = $arElementParams["AREA_ID"];
$arItemIDs = array(
    'ID' => $strMainID,
    'NAME' => $strMainID.'_name',
    'PICT' => $strMainID.'_pict',
    'SECOND_PICT' => $strMainID.'_secondpict',
    'STICKER_ID' => $strMainID.'_sticker',
    'SECOND_STICKER_ID' => $strMainID.'_secondsticker',
    'AVAIL' => $strMainID.'_avail',
    'QUANTITY' => $strMainID.'_quantity',
    'QUANTITY_DOWN' => $strMainID.'_quant_down',
    'QUANTITY_UP' => $strMainID.'_quant_up',
    'QUANTITY_MEASURE' => $strMainID.'_quant_measure',
    'BUY_LINK' => $strMainID.'_buy_link',
    'BASKET_ACTIONS' => $strMainID.'_basket_actions',
    'NOT_AVAILABLE_MESS' => $strMainID.'_not_avail',
    'SUBSCRIBE_LINK' => $strMainID.'_subscribe',
    'COMPARE_LINK' => $strMainID.'_compare_link',
    'DISCOUNT_TIMER' => $strMainID.'_discount_timer',
    'PRICE' => $strMainID.'_price',
    'DSC_PERC' => $strMainID.'_dsc_perc',
    'SECOND_DSC_PERC' => $strMainID.'_second_dsc_perc',
    'PROP_DIV' => $strMainID.'_sku_tree',
    'PROP' => $strMainID.'_prop_',
    'DISPLAY_PROP_DIV' => $strMainID.'_sku_prop',
    'BASKET_PROP_DIV' => $strMainID.'_basket_prop',
);
$strObName = 'ob'.preg_replace("/[^a-zA-Z0-9_]/", "x", $strMainID);

$voteDisplayAsRating = $arElementParams['VOTE_DISPLAY_AS_RATING'];
$useVoteRating = ($arElementParams['USE_VOTE_RATING'] == "Y");
$rating = 0;
$useCompare = ($arElementParams['DISPLAY_COMPARE'] == "Y");
if ($useVoteRating)
{
    if ($voteDisplayAsRating=='vote_avg')
        $rating = (($arElement['PROPERTIES']['vote_sum']['VALUE']/$arElement['PROPERTIES']['vote_count']['VALUE'])/5)*100;
    else
        $rating = ($arElement['PROPERTIES']['rating']['VALUE']/5)*100;

    $rating = (int)$rating;
}
$showCatalogQty = ($arElementParams["SHOW_MAX_QUANTITY"] != "N") ? true : false;
?>

    <div class="bxr-m2-ecommerce-v1" data-uid="<?=$UID?>" data-resize="1" id="<?=$arItemIDs["ID"]?>">
        <div class="bxr-element-container">
            <div class="bxr-element-image">
            <?
            $title = ($arElement["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"]) ? $arElement["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_ALT"] : $arElement["NAME"];
            $alt = ($arElement["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_ALT"]) ? $arElement["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_ALT"] : $arElement["NAME"];
            ?>
                <a href="<?=$arElement["DETAIL_PAGE_URL"]?>">
                    <img class="lazy" data-original="<?=$picture["src"]?>" id="<?=$arItemIDs["PICT"]?>" alt="<?=$alt?>" title="<?=$title?>">
                </a>
            </div>
            <?$elementDraw->showMarkerGroup($markerGroup);?>
            <div class="bxr-cart-basket-indicator">
                <div class="bxr-indicator-item bxr-indicator-item-basket" data-item="<?=$arElement["ID"]?>">
                    <span class="fa fa-shopping-basket"></span>
                    <span class="bxr-counter-item bxr-counter-item-basket" data-item="<?=$arElement["ID"]?>">0</span>
                </div>
            </div>

            <div class="bxr-sale-indicator">
                    <div class="bxr-basket-group">
                        <form class="bxr-basket-action bxr-basket-group" action="">
                            <button class="bxr-indicator-item bxr-indicator-item-favor bxr-basket-favor" data-item="<?=$arElement["ID"]?>" tabindex="0" title="<?=GetMessage("BXR_FAVOR")?>">
                                <span class="fa fa-heart-o"></span>
                            </button>
                            <input type="hidden" name="item" value="<?=$arElement["ID"]?>" tabindex="0">
                            <input type="hidden" name="action" value="favor" tabindex="0">
                            <input type="hidden" name="favor" value="yes">
                        </form>
                    </div>
                    <?
                    //compare
                    if ($useCompare)
                    {
                    ?>
                    <div class="bxr-basket-group">
                            <button class="bxr-indicator-item bxr-indicator-item-compare bxr-compare-button" value="" data-item="<?=$arElement["ID"]?>" title="<?=GetMessage("BXR_COMPARE")?>">
                                    <span class="fa fa-bar-chart " aria-hidden="true"></span>
                            </button>
                    </div>
                    <?}?>
            </div>
            <?$name = ($arElement["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"]) ? $arElement["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"] : $arElement["NAME"];?>
            <div class="bxr-element-name" id="bxr-element-name-<?=$arElement["ID"]?>">
                    <a href="<?=$arElement["DETAIL_PAGE_URL"]?>" id="<?=$arItemIDs["NAME"]?>" class="bxr-font-color bxr-font-color-hover" title="<?=$name?>">
                        <? echo (strlen($arElement["SHORT_NAME"])>0) ? $arElement["SHORT_NAME"] : $name;?>
                    </a>
                    <?if ($arElementParams["TILE_SHOW_PROPERTIES"]=="Y"){?>
                        <table class="bxr-element-props-table">
                            <tbody>
                                <?foreach ($arElement["DISPLAY_PROPERTIES"] as $arProperty) {?>
                                    <?if (!is_array($arProperty["DISPLAY_VALUE"]) && $arProperty["DISPLAY_VALUE"]){?>
                                        <tr>
                                            <td class="bxr-props-table-name"><?=trim($arProperty["NAME"])?></td>
                                            <td class="bxr-props-table-value"><?=trim($arProperty["DISPLAY_VALUE"])?></td>
                                        </tr>
                                    <?}?>
                                <?}?>
                            </tbody>
                        </table>
                    <?}?>
            </div>

            <?
            //rating block
            if ($useVoteRating)
            {?>
                <div class="bxr-element-rating">
                    <div class="bxr-stars-container">
                            <div class="bxr-stars-bg"></div>
                            <div class="bxr-stars-progres" style="width: <?=$rating?>%;"></div>
                    </div>
                </div>            
            <?}?>
            <div class="bxr-element-info">
                <? //if ($showCatalogQty) {?>
                <div class="bxr-element-avail" id="<?=$arItemIDs["AVAIL"]?>">
                    <?include('avail.php');?>
                </div>
                <? //}?>
                <?if ($arElement["PROPERTIES"]["CML2_ARTICLE"]["VALUE"]) {?>
                    <div class="bxr-element-article">
                        <?=GetMessage("ARTICLE")?><?=$arElement["PROPERTIES"]["CML2_ARTICLE"]["VALUE"];?>
                    </div>
                <?}?>
            </div>
            <?if (count($arElement["OFFERS"]) > 0) {?>
                <div class="bxr-element-hover">
                    <div class="bxr-element-offers">
                        <?include('sku.php');?>
                    </div>
                </div>
            <?}?>
            <div class="bxr-element-price" id="<?=$arItemIDs["PRICE"]?>">
                <?include('price.php');?>
            </div>
            <div class="bxr-element-action"  id="<?=$arItemIDs["BASKET_ACTIONS"]?>">
                <?include('basket_btn.php');?>
            </div>
            <div class="bxr-discount-timer"  id="<?=$arItemIDs["DISCOUNT_TIMER"]?>">
                <?include('discount_timer.php');?>
            </div>
        </div>
    </div>
<?
$dirName = str_replace($_SERVER["DOCUMENT_ROOT"],'', dirname(__FILE__));
$elementDraw->setAdditionalFile("JS", "/bitrix/tools/bxready2/collection/bxr_elements/ecommerce.big.v1/include/script.js", true);
$elementDraw->setAdditionalFile("CSS", "/bitrix/tools/bxready2/collection/bxr_elements/ecommerce.big.v1/include/style.css", false);
