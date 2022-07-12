<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?
use Alexkova\Bxready\Draw2;
$elementDraw = \Alexkova\Bxready2\Draw::getInstance($this);
$UID = 0;
global $APPLICATION;

$picture = false;
$arMatrix = array('width' => 270, 'height' => 150);
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
?>
<?if(isset($arElementParams["BXR_PRST_USE_HR"]) && $arElementParams["BXR_PRST_USE_HR"]=="Y"):?>
    <hr class="bxr-news-horizontal-v1-hr" >
    <div class="bxr-news-horizontal-v1 use-hr" data-uid="<?=$UID?>" data-resize="1">
<?else:?>
    <div class="bxr-news-horizontal-v1" data-uid="<?=$UID?>" data-resize="1">
<?endif;?>
    <div class="bxr-section-container">
        <?if(!isset($arElementParams["BXR_PRST_SHOW_IMAGE"]) || $arElementParams["BXR_PRST_SHOW_IMAGE"]=="Y"):?>
            <div class="bxr-element-image" >
                <a href="<?=$arElement["DETAIL_PAGE_URL"]?>"><img src="<?=$picture["src"]?>" alt="<?=$arElement['NAME']?>"></a>
            </div>
        <?endif;?>
        <div class="bxr-element-content">
            <div class="bxr-element-name">
                <a href="<?=$arElement["DETAIL_PAGE_URL"]?>">
                    <?=$arElement["NAME"]?>
                </a>
            </div>
            <?if (strlen($arElement["PREVIEW_TEXT"])>0):?>
                    <div class="bxr-element-description">
                            <?=$arElement["PREVIEW_TEXT"]?>
                    </div>
            <?endif;?>
            <?if ((in_array("DATE_ACTIVE_FROM", $arElementParams["FIELD_CODE"]) || in_array("DATE_ACTIVE_TO", $arElementParams["FIELD_CODE"])) && ($arElement['DATE_ACTIVE_FROM'] || $arElement['DATE_ACTIVE_TO'])) {?>
                <div class="bxr-element-date">
                    <div class="bxr-date-gray">
                        <?if ($arElement['DATE_ACTIVE_FROM'] && in_array("DATE_ACTIVE_FROM", $arElementParams["FIELD_CODE"])) {?>
                            <?=date($arElementParams['ACTIVE_DATE_FORMAT'],  strtotime($arElement['DATE_ACTIVE_FROM']))?>
                        <?}?>
                        <?if ($arElement['DATE_ACTIVE_FROM'] && $arElement['DATE_ACTIVE_TO']
                              && in_array("DATE_ACTIVE_FROM", $arElementParams["FIELD_CODE"])
                              && in_array("DATE_ACTIVE_TO", $arElementParams["FIELD_CODE"])) {?>
                         - 
                        <?}?>
                        <?if ($arElement['DATE_ACTIVE_TO'] && in_array("DATE_ACTIVE_TO", $arElementParams["FIELD_CODE"])) {?>
                            <?=date($arElementParams['ACTIVE_DATE_FORMAT'],  strtotime($arElement['DATE_ACTIVE_TO']))?>
                         <?}?>
                    </div>
                </div>
            <?}?>
            <?if (strlen($arElementParams["DETAIL_PAGE_URL_CAPTION"])>0):?>
                <div class="bxr-element-action">
                    <a href="<?=$arElement["DETAIL_PAGE_URL"]?>" class="bxr-color-button bxr-color-button-small">
                            <?=$arElementParams["DETAIL_PAGE_URL_CAPTION"]?>
                    </a>
                </div>
            <?endif;?>
        </div>
    </div>
</div>
<?
$elementDraw->setAdditionalFile("CSS", "/bitrix/tools/bxready2/collection/bxr_elements/news.horizontal.v1/include/style.css", false);
if($arElementParams["BXREADY_VERTICAL_ALIGN"] != 'N')
    $elementDraw->setAdditionalFile("JS", "/bitrix/tools/bxready2/collection/bxr_elements/news.horizontal.v1/include/script.js", false);
?>