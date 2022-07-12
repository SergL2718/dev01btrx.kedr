<?
use Alexkova\Bxready\Draw;
$elementDraw = \Alexkova\Bxready\Draw::getInstance($this);
$arMatrix = array('width' => 240, 'height' => 120);

//echo "<pre>"; print_r($arElementParams); echo "</pre>";

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
?>

<div class="bxr-classic-image-v1" data-uid="<?=$arElementParams["UNICUM_ID"]?>" data-resize="1">
	<div class="bxr-element-container">
		<div class="bxr-element-image">
			<img src="<?=$picture["src"]?>">
		</div>
		<div class="bxr-element-name">
			<a href="<?=$arElement['DETAIL_PAGE_URL']?>"><?=$arElement['NAME']?></a>
		</div>
		<?if (in_array('PREVIEW_TEXT', $arElementParams["FIELD_CODE"])):?>
			<div class="bxr-element-description">
				<?=$arElement['PREVIEW_TEXT']?>
			</div>
		<?endif;?>
	</div>
</div>
<?
/*
* Pri neobhodimosti podkluchaem nuzhnye biblioteki
*/

$dirName = str_replace($_SERVER["DOCUMENT_ROOT"],'', dirname(__FILE__));
$elementDraw->setAdditionalFile("JS", "/bitrix/tools/bxready/library/elements/classic.image.v1.autoheight/include/script.js", true);
$elementDraw->setAdditionalFile("CSS", "/bitrix/tools/bxready/library/elements/classic.image.v1.autoheight/include/style.css", false);?>