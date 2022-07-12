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

<div class="bxr-section-horizontal-v1" data-uid="<?=$UID?>" data-resize="1">
    <div class="bxr-section-container">
        <div class="bxr-element-image">
            <a href="<?=$arElement["DETAIL_PAGE_URL"]?>"><img src="<?=$picture["src"]?>" alt="<?=$arElement['NAME']?>"></a>
        </div>
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

			<?if(is_array($arElement["CHILD"]) && count($arElement["CHILD"])>0){?>
			<div class="bxr-child">
				<ul>
				<?foreach ($arElement["CHILD"] as $cell=>$val):?>
					<li>
						<a href="<?=$val['DETAIL_PAGE_URL']?>"><?=$val['NAME']?></a>
					</li>
				<?endforeach;?>
				</ul>
				<div class="clearfix"></div>
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
$elementDraw->setAdditionalFile("CSS", "/bitrix/tools/bxready2/collection/bxr_elements/section.horizontal.v1/include/style.css", false);
if($arElementParams["BXREADY_VERTICAL_ALIGN"] != 'N')
    $elementDraw->setAdditionalFile("JS", "/bitrix/tools/bxready2/collection/bxr_elements/section.horizontal.v1/include/script.js", false);
?>