<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
use Alexkova\Bxready2\Draw;
IncludeModuleLangFile(__FILE__);
$elementDraw = Draw::getInstance($this);
$UID = 0;
global $APPLICATION;
//echo "<pre>";
//var_dump($arElement);
//echo "</pre>";
if(intval($arElementParams["UNICUM_ID"])>0){
    $UID = $arElementParams["UNICUM_ID"]>0 ? $arElementParams["UNICUM_ID"]>0 : 1;
}
?>
<div class="bxr-element-reviews-v2" data-uid="<?=$UID?>" data-resize="1" id="<?=$UID?>">
    <div class="bxr-element-person">   
        <span class="bxr-element-name"><?=$arElement["FIELDS"]["NAME"]?></span>
        <span class="bxr-element-post"><?=(!empty($arElement["PROPERTIES"]["BXR_POST"]["VALUE"])) ? '(' . $arElement["PROPERTIES"]["BXR_POST"]["VALUE"] . ')' : ""?></span>
    </div>
    <div class="bxr-element-note">
        <span class="bxr-element-date"><?=$arElement["DISPLAY_ACTIVE_FROM"]?></span>
    </div>
    <div class="bxr-element-preview">
        <?=$arElement["FIELDS"]["PREVIEW_TEXT"];?>
    </div>
</div>

<?
$elementDraw->setAdditionalFile("JS", "/bitrix/tools/bxready2/collection/bxr_elements/reviews.v2/include/script.js", true);
$elementDraw->setAdditionalFile("CSS", "/bitrix/tools/bxready2/collection/bxr_elements/reviews.v2/include/style.css", false);
?>