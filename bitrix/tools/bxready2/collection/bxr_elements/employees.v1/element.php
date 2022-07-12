<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
use Alexkova\Bxready2\Draw;
IncludeModuleLangFile(__FILE__);
$elementDraw = Draw::getInstance($this);
$UID = 0;
global $APPLICATION;

$arMatrix = array('width' => 140, 'height' => 140);

if (is_array($arElement["PREVIEW_PICTURE"])){
	$picture = $elementDraw->prepareImage($arElement["PREVIEW_PICTURE"]["ID"], $arMatrix);
}else{
	if (is_array($arElement["DETAIL_PICTURE"])){
		$picture = $elementDraw->prepareImage($arElement["DETAIL_PICTURE"]["ID"], $arMatrix);
	}
}

if(intval($arElementParams["UNICUM_ID"])>0){
    $UID = $arElementParams["UNICUM_ID"]>0 ? $arElementParams["UNICUM_ID"]>0 : 1;
}
?>
<?
foreach ($arElement["PROPERTIES"]["BXR_INFO"]["VALUE"] as $val) {
     $infoValue[] = $val;
}
foreach ($arElement["PROPERTIES"]["BXR_INFO"]["~DESCRIPTION"] as $key => $descr) {
     $info[] = array (
         "value" => $descr,
         "info" =>  $infoValue[$key]
     );
}
?>
<div class="bxr-element-employees-v1" data-uid="<?=$UID?>" data-resize="1" id="<?=$UID?>">
    <div class="bxr-element-image">
        <img src="<?=$arElement["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=$arElement["FIELDS"]["NAME"]?>">
    </div>
    <div class="bxr-element-content">
        <div class="bxr-element-employee">
            <div class="bxr-element-name">
                <?=$arElement["FIELDS"]["NAME"]?>
            </div>
            <div class="bxr-element-post">
                <?=$arElement["PROPERTIES"]["BXR_POST"]["VALUE"]?>
            </div>
        </div>
        <div class="bxr-element-info">
            
            <?foreach ($info as $item):?>
                <div>
                    <?=$item[value]?>
                    <span class="bxr-element-text"><?=$item[info]?></span>
                </div>
            <?endforeach;?>
            
        </div>
    </div>
</div>

<?
$elementDraw->setAdditionalFile("CSS", "/bitrix/tools/bxready2/collection/bxr_elements/employees.v1/include/style.css", false);
?>