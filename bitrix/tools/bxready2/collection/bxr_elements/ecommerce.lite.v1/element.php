<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
use Alexkova\Bxready2\Draw;
IncludeModuleLangFile(__FILE__);
$elementDraw = Draw::getInstance($this);
$UID = 0;
global $APPLICATION;

if (isset($arElementParams["BXREADY_LIST_MARKER_TYPE"]) && strlen($arElementParams["BXREADY_LIST_MARKER_TYPE"])>0)
	$elementDraw->setMarkerCollection($arElementParams["BXREADY_LIST_MARKER_TYPE"]);
//$elementDraw->setMarkerCollection('circle_vertical');
//$elementDraw->setMarkerCollection('circle_vertical_small');

$markerGroup = array(
    "NEW" => ($arElement["PROPERTIES"]["NEWPRODUCT"]["VALUE"]) ? true : false,
    "SALE" => ($arElement["PROPERTIES"]["SPECIALOFFER"]["VALUE"]) ? true : false,
    "DISCOUNT" => $arElement["MIN_PRICE"]["DISCOUNT_DIFF_PERCENT"],
    "HIT" => ($arElement["PROPERTIES"]["SALELEADER"]["VALUE"]) ? true : false,
    "REC" => ($arElement["PROPERTIES"]["RECOMMENDED"]["VALUE"]) ? true : false,
);

$picture = false;
$arMatrix = array('width' => 190, 'height' => 160);
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
    $UID = $arElementParams["UNICUM_ID"]>0 ? $arElementParams["UNICUM_ID"]>0 : 1;
}
//$UID = $arElement["ID"];
/*echo '<pre>';
print_r($arElement);
print_r($arElementParams);
echo '</pre>';*/
?>
<div class="bxr-element-ecommerce-lite-v1" data-uid="<?=$UID?>" data-resize="1" id="<?=$UID?>">
    <div class="bxr-element-container">
        <div class="bxr-element-image">
            <?
                $title = ($arElement["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"]) ? $arElement["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_ALT"] : $arElement["NAME"];
                $alt = ($arElement["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_ALT"]) ? $arElement["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_ALT"] : $arElement["NAME"];
            ?>
            <a href="<?=$arElement["DETAIL_PAGE_URL"]?>">
                <img src="<?=$picture["src"]?>" id="<?=$UID;?>_pict" alt="<?=$alt?>" title="<?=$title?>">
            </a>
        </div>
        <?$elementDraw->showMarkerGroup($markerGroup);?>
        <div class="bxr-element-name" id="bxr-element-name-<?=$arElement["ID"]?>">
            <a href="<?=$arElement["DETAIL_PAGE_URL"]?>" title="<?=$arElement["NAME"]?>">
                <? echo (strlen($arElement["SHORT_NAME"])>0) ? $arElement["SHORT_NAME"] : $arElement["NAME"];?>
            </a>
	</div>
        <div class="bxr-element-info">
            <?include('availability.php');?>
            <?if(!empty($arElement["DISPLAY_PROPERTIES"]["CML2_ARTICLE"]["VALUE"])):?>
                <div class="property-article"><span><?=$arElement["PROPERTIES"]["CML2_ARTICLE"]["NAME"]?>:</span> <?=$arElement["PROPERTIES"]["CML2_ARTICLE"]["VALUE"]?></div>
            <?endif;?>
        </div><div class="clear"></div><?/*endif;*/?>
        <div class="bxr-element-price" id="<?=$UID?>_price">
            <?include('price.php');?>
        </div>
        <div class="bxr-element-action"  id="<?=$UID?>_basket_actions">
            <?include('basket_btn.php');?>
        </div>
    </div>
</div>
<?
$elementDraw->setAdditionalFile("JS", "/bitrix/tools/bxready2/collection/bxr_elements/ecommerce.lite.v1/include/script.js", true);
$elementDraw->setAdditionalFile("CSS", "/bitrix/tools/bxready2/collection/bxr_elements/ecommerce.lite.v1/include/style.css", false);
?>