<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?
use Alexkova\Bxready2\Draw;
IncludeModuleLangFile(__FILE__);
$addClass = ''; $UID = 0;
$picture = false;

global $APPLICATION;

$elementDraw = Draw::getInstance($this);
if (isset($arElementParams["BXREADY_LIST_MARKER_TYPE"]) && strlen($arElementParams["BXREADY_LIST_MARKER_TYPE"])>0)
	$elementDraw->setMarkerCollection($arElementParams["BXREADY_LIST_MARKER_TYPE"]);
$elementDraw->setMarkerCollection('ribbon_vertical');

$arMatrix = array('width' => 160, 'height' => 160);

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


if(intval($arElementParams["UNICUM_ID"])>0){
	$UID = $arElementParams["UNICUM_ID"];
}

$markerGroup = array(
	"NEW" => ($arElement["PROPERTIES"]["NEWPRODUCT"]["VALUE"]) ? true : false,
	"SALE" => ($arElement["PROPERTIES"]["SPECIALOFFER"]["VALUE"]) ? true : false,
	"DISCOUNT" => $arElement["MIN_PRICE"]["DISCOUNT_DIFF_PERCENT"],
	"HIT" => ($arElement["PROPERTIES"]["SALELEADER"]["VALUE"]) ? true : false,
	"REC" => ($arElement["PROPERTIES"]["RECOMMENDED"]["VALUE"]) ? true : false,
);

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
$useVoteRating = ('Y' == $arElementParams['USE_VOTE_RATING']);
$useVoteRating = true;
$rating = 0;

if ($useVoteRating)
{
	if ($voteDisplayAsRating=='vote_avg')
		$rating = (($arElement['PROPERTIES']['vote_sum']['VALUE']/$arElement['PROPERTIES']['vote_count']['VALUE'])/5)*100;
	else
		$rating = ($arElement['PROPERTIES']['rating']['VALUE']/5)*100;

	$rating = (int)$rating;
}
$showCatalogQty = ('Y' == $arElementParams["SHOW_CATALOG_QUANTITY"]);
?>
<div class="bxr-element-ecommerce-table-lite-v1" data-uid="<?=$UID?>" data-resize="1" id="<?=$arItemIDs["ID"]?>">
    <table class="bxr-element-container">
        <tr>
            <td class="bxr-element-mobile hidden-lg hidden-md">
                <div class="bxr-element-name" id="bxr-element-name-<?=$arElement["ID"]?>">
                    <a href="<?=$arElement["DETAIL_PAGE_URL"]?>"><?=$arElement["NAME"]?></a>
                    <?if(!empty($arElement["DISPLAY_PROPERTIES"]["CML2_ARTICLE"]["VALUE"])):?>
                        <div class="property-article"><span><?=$arElement["PROPERTIES"]["CML2_ARTICLE"]["NAME"]?>:</span> <?=$arElement["PROPERTIES"]["CML2_ARTICLE"]["VALUE"]?></div>
                    <?endif;?>
                </div>
                <?include('availability.php');?>
                <div class="bxr-element-price" id="<?=$arItemIDs["PRICE"]?>">
                    <?include('price.php');?>
                </div>
            </td>
            <td class="bxr-element-image-col hidden-sm hidden-xs">
                <div class="bxr-element-image">
                <?
                $title = ($arElement["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"]) ? $arElement["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_ALT"] : $arElement["NAME"];
                $alt = ($arElement["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_ALT"]) ? $arElement["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_ALT"] : $arElement["NAME"];
                ?>
                    <a href="<?=$arElement["DETAIL_PAGE_URL"]?>">
                        <img src="<?=$picture["src"]?>" id="<?=$arItemIDs["PICT"]?>" alt="<?=$alt?>" title="<?=$title?>">
                    </a>
                </div>
            </td>
            <td class="bxr-element-name-col hidden-sm hidden-xs">
                <div class="bxr-element-name" id="bxr-element-name-<?=$arElement["ID"]?>">
                    <a href="<?=$arElement["DETAIL_PAGE_URL"]?>"><?=$arElement["NAME"]?></a>
                    <?if(!empty($arElement["DISPLAY_PROPERTIES"]["CML2_ARTICLE"]["VALUE"])):?>
                        <div class="property-article"><span><?=$arElement["PROPERTIES"]["CML2_ARTICLE"]["NAME"]?>:</span> <?=$arElement["PROPERTIES"]["CML2_ARTICLE"]["VALUE"]?></div>
                    <?endif;?>
                </div>
            </td>
            <td class="bxr-element-price-col hidden-sm hidden-xs">
                <?include('availability.php');?>
                <div class="bxr-element-price" id="<?=$arItemIDs["PRICE"]?>">
                    <?include('price.php');?>
                </div>
            </td>
            <td class="bxr-element-btn-col">
                <div class="bxr-element-action"  id="<?=$arItemIDs["BASKET_ACTIONS"]?>">
                    <?include('basket_btn.php');?>
                </div>
            </td>
        </tr>
    </table>
</div>
<?
$elementDraw->setAdditionalFile("CSS", "/bitrix/tools/bxready2/collection/bxr_elements/ecommerce.table.lite.v1/include/style.css", false);
?>