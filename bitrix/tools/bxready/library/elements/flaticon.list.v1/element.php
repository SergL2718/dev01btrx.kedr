<?
use Alexkova\Bxready\Draw;
$elementDraw = Draw::getInstance();
$arMatrix = array('width' => 100, 'height' => 100);

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

<div class="bxr-flaticon-list-v1" data-uid="<?=$arElementParams["UNICUM_ID"]?>" data-resize="1">
	<div class="bxr-element-container">
		<div class="bxr-element-image">
			<img alt="<?=$arElement['NAME']?>" src="<?=$picture["src"]?>">
		</div>
		<div class="bxr-element-description">
			<?=$arElement["NAME"]?>
		</div>
	</div>
</div>
<?
/*
* Pri neobhodimosti podkluchaem nuzhnye biblioteki
*/

$dirName = str_replace($_SERVER["DOCUMENT_ROOT"],'', dirname(__FILE__));
//$elementDraw->setAdditionalFile("JS", $dirName."/include/script.js", true);
$elementDraw->setAdditionalFile("CSS", "/bitrix/tools/bxready/library/elements/flaticon.list.v1/include/style.css", false);?>