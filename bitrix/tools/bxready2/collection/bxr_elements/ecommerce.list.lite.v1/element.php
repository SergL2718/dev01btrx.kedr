<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
use Alexkova\Bxready2\Draw;
IncludeModuleLangFile(__FILE__);
//echo "<pre>"; print_r($arElement); echo "</pre>";
//echo "<pre>"; print_r($arElementParams); echo "</pre>";
$addClass = ''; $UID = 0;
$picture = false;

global $APPLICATION;

$elementDraw = Draw::getInstance($this);
if (isset($arElementParams["BXREADY_LIST_MARKER_TYPE"]) && strlen($arElementParams["BXREADY_LIST_MARKER_TYPE"])>0)
	$elementDraw->setMarkerCollection($arElementParams["BXREADY_LIST_MARKER_TYPE"]);
//$elementDraw->setMarkerCollection('circle_vertical');
//$elementDraw->setMarkerCollection('circle_vertical_small');
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
$useCompare = ('Y' == $arElementParams['DISPLAY_COMPARE']);

?>
<div class="bxr-element-ecommerce-list-lite-v1" data-uid="<?=$UID?>" data-resize="1" id="<?=$arItemIDs["ID"]?>">
    <table class="bxr-element-container">
        <tr>
            <td class="bxr-element-image-col">
                <div class="bxr-element-name hidden-lg hidden-md" id="bxr-element-name-<?=$arElement["ID"]?>">
                    <a href="<?=$arElement["DETAIL_PAGE_URL"]?>"><?=$arElement["NAME"]?></a>
                </div>
                <div class="bxr-element-image">
                <?
                $title = ($arElement["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"]) ? $arElement["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_ALT"] : $arElement["NAME"];
                $alt = ($arElement["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_ALT"]) ? $arElement["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_ALT"] : $arElement["NAME"];
                ?>
                    <a href="<?=$arElement["DETAIL_PAGE_URL"]?>">
                        <img src="<?=$picture["src"]?>" id="<?=$arItemIDs["PICT"]?>" alt="<?=$alt?>" title="<?=$title?>">
                    </a>
                </div>
                <?$elementDraw->showMarkerGroup($markerGroup);?>
            </td>
            <td class="bxr-element-name-col hidden-sm hidden-xs">
                <div class="bxr-element-name" id="bxr-element-name-<?=$arElement["ID"]?>">
                    <a href="<?=$arElement["DETAIL_PAGE_URL"]?>"><?=$arElement["NAME"]?></a>
                </div>
                <?if ($arElement["PREVIEW_TEXT"]) {?>
                    <div class="bxr-element-anounce">
                        <?if ($arElement['PREVIEW_TEXT_TYPE']=='text' && $arElementParams['ANOUNCE_TRUNCATE_LEN'] && $arElementParams['ANOUNCE_TRUNCATE_LEN']>0){?>
                            <?=TruncateText($arElement["~PREVIEW_TEXT"], $arElementParams['ANOUNCE_TRUNCATE_LEN'])?>
                        <?}else{?>
                            <?=$arElement["~PREVIEW_TEXT"]?>
                        <?}?>
                    </div>
                <?}?>
                <table width="100%" class="bxr-props-table">
                    <tbody>
                        <?
                            $arServiceProperties = array(
                                "BXR_PRICE" => array(),
                                "BXR_DISCOUNT_PRICE" => array(),
                                "BXR_UNIT_PRICE" => array(),
                                "BXR_DISCOUNT_PERIOD_FROM" => array(),
                                "BXR_DISCOUNT_PERIOD_TO" => array(),
                                "BXR_INSTOCK" => array(),
                            );
                            $arElement["DISPLAY_PROPERTIES"] = array_diff_assoc($arElement["DISPLAY_PROPERTIES"], $arServiceProperties);
                        ?>
                        <?foreach ($arElement["DISPLAY_PROPERTIES"] as $arProperty) {?>
                            <?if (!is_array($arProperty["DISPLAY_VALUE"]) && $arProperty["DISPLAY_VALUE"]){?>
                            <tr>
                                <td class="bxr-props-name">
                                    <span><?=$arProperty["NAME"]?></span>
                                </td>
                                <td class="bxr-props-data">
                                    <span><?=$arProperty["DISPLAY_VALUE"]?></span>
                                </td>
                            </tr>
                            <?} elseif (is_array($arProperty["DISPLAY_VALUE"]) && count($arProperty["DISPLAY_VALUE"] > 0)) {?>
                                <?
                                    $withDesc = false;
                                    foreach($arProperty["DESCRIPTION"] as $cell=>$val){
                                        if ($val) {
                                            $withDesc = true;
                                            break;
                                        }
                                    }?>
                                    <?if ($withDesc) {?>
                                        <tr>
                                                <td colspan="2" class="bxr-props-data bxr-props-data-group">
                                                        <b><?=$arProperty["NAME"]?></b></td>
                                        </tr>
                                        <?foreach($arProperty["DISPLAY_VALUE"] as $cell=>$val){?>
                                                <tr itemprop="additionalProperty" itemscope itemtype="http://schema.org/PropertyValue">
                                                        <td class="bxr-props-name no-bold"><span itemprop="name"><?=$arProperty["DESCRIPTION"][$cell]?></span></td>
                                                        <td class="bxr-props-data"><span itemprop="value"><?=$val?></span></td>
                                                </tr>
                                        <?}?>
                                    <?} else {?>
                                        <tr itemprop="additionalProperty" itemscope itemtype="http://schema.org/PropertyValue">
                                            <td class="bxr-props-name"><span itemprop="name"><?=$arProperty["NAME"]?></span></td>
                                            <td class="bxr-props-data"><span itemprop="value"><?=  implode(', ', $arProperty["DISPLAY_VALUE"])?></span></td>
                                        </tr>
                                    <?}?>
                            <?}?>
                        <?}?>
                    </tbody>
                </table>
            </td>
            <td class="bxr-element-btn-col"> 
                <?include('availability.php');?>
                <div class="bxr-element-price" id="<?=$arItemIDs["PRICE"]?>">
                    <?include('price.php');?>
                </div>

                <div class="bxr-element-action"  id="<?=$arItemIDs["BASKET_ACTIONS"]?>">
                    <?include('basket_btn.php');?>
                </div>
            </td>
        </tr>
    </table>
</div>
<?
$elementDraw->setAdditionalFile("CSS", "/bitrix/tools/bxready2/collection/bxr_elements/ecommerce.list.lite.v1/include/style.css", false);
?>