<?
use Alexkova\Bxready\Draw2;
$elementDraw = \Alexkova\Bxready2\Draw::getInstance($this);
$arMatrix = array('width' => 75, 'height' => 75);

if (isset($arElement["PROPERTIES"]["BXR_GLYPH"]) && strlen($arElement["PROPERTIES"]["BXR_GLYPH"]["VALUE"])>0){
	$elementType = "icon";
}else{
	$elementType = "image";

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
}
?>
<div class="bxr-trigger-v1" data-uid="<?=$arElementParams["UNICUM_ID"]?>">
	<div class="bxr-element-container">
            <?
            if (file_exists(dirname(__FILE__)."/".$elementType.".php")){
                include($elementType.".php");
            }
            ?>

            <div class="bxr-element-name">
                <?if ( empty($arElement["PREVIEW_TEXT"]) ):?>
                    <?=$arElement['~NAME']?>
                <?else:?>
                    <?if ( !empty($arElement["PROPERTIES"]["BXR_URL"]["VALUE"])): ?>
                        <a href="<?= $arElement["PROPERTIES"]["BXR_URL"]["VALUE"] ?>">
                            <?=$arElement['~NAME']?>
                        </a>
                    <? else: ?>
                        <?=$arElement['~PREVIEW_TEXT']?>
                    <? endif ?>
                <?endif?>
            </div>
	</div>
</div>
<?$elementDraw->setAdditionalFile("CSS", "/bitrix/tools/bxready2/collection/bxr_elements/trigger.flat.horizontal.v1/include/style.css", false);?>