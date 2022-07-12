<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?
use Alexkova\Bxready2\Draw;
IncludeModuleLangFile(__FILE__);
$addClass = ''; $UID = 0;
$picture = false;

global $APPLICATION;

$elementDraw = \Alexkova\Bxready2\Draw::getInstance($this);

$arMatrix = array('width' => 50, 'height' => 50);

if (is_array($arElement["PREVIEW_PICTURE"])){
    $picture = $elementDraw->prepareImage($arElement["PREVIEW_PICTURE"]["ID"], $arMatrix);
}else{
    if (is_array($arElement["DETAIL_PICTURE"])){
        $picture = $elementDraw->prepareImage($arElement["DETAIL_PICTURE"]["ID"], $arMatrix);
    }
}

if (!is_array($picture) || strlen($picture["src"])<=0){
    $picture = array("src" => $elementDraw->getDefaultImage());
}

$UID = (intval($arElementParams["UNICUM_ID"])>0) ? $arElementParams["UNICUM_ID"] : 1;

$strMainID = $arElementParams["AREA_IDS"][$arElement["ID"]];
$arItemIDs = array(
    'ID' => $strMainID,
    'NAME' => $strMainID.'_name',
    'PICT' => $strMainID.'_pict',
    'SECOND_PICT' => $strMainID.'_secondpict',
    'PICT_SLIDER' => $strMainID.'_pict_slider',
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
    'OLD_PRICE' => $strMainID.'_old_price',
    'SUBSCRIBE_LINK' => $strMainID.'_subscribe',
    'PROP_DIV' => $strMainID.'_sku_tree',
    'PROP' => $strMainID.'_prop_',
    'DISPLAY_PROP_DIV' => $strMainID.'_sku_prop',
    'BASKET_PROP_DIV' => $strMainID.'_basket_prop',
    'FAST_VIEW' => $strMainID.'_fast_view',
);
$strObName = 'ob'.preg_replace("/[^a-zA-Z0-9_]/", "x", $strMainID);

$useVoteRating = ($arElementParams['BXR_PRESENT_SETTINGS']['BXR_SHOW_RATING'] != "N") ? true : false;
$rating = 0;
if ($useVoteRating)
{
    if ($arElementParams['BXR_PRESENT_SETTINGS']['BXR_SHOW_RATING'] == 'vote_avg')
        $rating = (($arElement['PROPERTIES']['vote_sum']['VALUE']/$arElement['PROPERTIES']['vote_count']['VALUE'])/5)*100;
    else
        $rating = ($arElement['PROPERTIES']['rating']['VALUE']/5)*100;

    $rating = (int)$rating;
}

$useCompare = (!$arElementParams['DISPLAY_COMPARE']) ? false : true;
$showCatalogQty = ($arElementParams["SHOW_MAX_QUANTITY"] != "N") ? true : false;
?>
<div class="bxr-ecommerce-m2-table-v1" data-uid="<?=$UID?>" data-resize="1" id="<?=$arItemIDs["ID"]?>">
    <table class="bxr-element-container">
        <tr>
            <td class="bxr-element-image-col hidden-sm hidden-xs">
                <? include('block_templates/image.php');?>
            </td>
            <td class="bxr-element-name-col hidden-sm hidden-xs">
                <? include('block_templates/name.php');?>
                <? include('block_templates/avail.php');?>
            </td>
            <td class="bxr-element-price-col hidden-sm hidden-xs">
                <? include('block_templates/price.php');?>
            </td>
            <td class="bxr-element-btn-col">
                <div class="bxr-element-mobile hidden-lg hidden-md">
                    <? include('block_templates/name.php');?>
                    <? include('block_templates/avail.php');?>
                    <? include('block_templates/price.php');?>
                </div>
                <? include('block_templates/indicators.php');?>
                <? include('block_templates/buttons.php');?>
            </td>
        </tr>
    </table>
</div>
<?include 'epilog.php';?>
