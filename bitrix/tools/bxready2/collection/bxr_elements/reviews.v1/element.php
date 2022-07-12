<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
use Alexkova\Bxready2\Draw;
IncludeModuleLangFile(__FILE__);
$elementDraw = Draw::getInstance($this);
$UID = 0;
global $APPLICATION;

$colorClass = "";

if ( !empty($arElementParams["BXR_PRST_COLOR_TYPE"]) ) {
    if ( $arElementParams["BXR_PRST_COLOR_TYPE"] == "base" )
        $colorClass = "bxr-color-flat";
    if ( $arElementParams["BXR_PRST_COLOR_TYPE"] == "gray-border" )
        $colorClass = "bxr-element-reviews-color-gray bxr-element-color-triangle";
    if ( $arElementParams["BXR_PRST_COLOR_TYPE"] == "gray" )
        $colorClass = "bxr-element-reviews-color-gray2";
}

if(intval($arElementParams["UNICUM_ID"])>0){
    $UID = $arElementParams["UNICUM_ID"]>0 ? $arElementParams["UNICUM_ID"]>0 : 1;
}
?>
<div class="bxr-element-reviews-v1" data-uid="<?=$UID?>" data-resize="1" id="<?=$UID?>">
    <div class="bxr-element-preview <?=$colorClass?>">   
        <div class="bxr-element-quote fa fa-quote-left"></div>
        <?=$arElement["FIELDS"]["PREVIEW_TEXT"];?>
    </div>
    <div class="bxr-element-aftor"><?=$arElement["FIELDS"]["NAME"]?></div>
    <div class="bxr-element-post"><?=$arElement["PROPERTIES"]["BXR_POST"]["VALUE"]?></div>
</div>

<?$elementDraw->setAdditionalFile("CSS", "/bitrix/tools/bxready2/collection/bxr_elements/reviews.v1/include/style.css", false);?>