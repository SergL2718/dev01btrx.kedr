<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?
use Alexkova\Bxready\Draw;
$addClass = ''; $UID = 0;
$picture = false;

global $APPLICATION;

$elementDraw = \Alexkova\Bxready\Draw::getInstance($this);

$arMatrix = array('width' => 160, 'height' => 160);

if (is_array($arElement["PICTURE"])){
	$picture = $elementDraw->prepareImage($arElement["PICTURE"]["ID"], $arMatrix);
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
?>

<div class="catalog-index-v1" data-uid="<?=$UID?>" data-resize="1">
	<div class="bxr-section-container">

		<div class="bxr-element-name" style="background: #F00">
			<a href="<?=$arElement["SECTION_PAGE_URL"]?>"><?=$arElement["NAME"]?></a>
		</div>

		<?foreach($arElement["ITEMS"] as $arItem):?>
			<div style="float:left">
				<?=$arItem["NAME"]?>
			</div>
		<?endforeach?>

	</div>
</div>

<?
/*
 * Pri neobhodimosti podkluchaem nuzhnye biblioteki
 */

//$dirName = str_replace($_SERVER["DOCUMENT_ROOT"],'', dirname(__FILE__));
//$elementDraw->setAdditionalFile("JS", $dirName."/include/script.js", true);
//$elementDraw->setAdditionalFile("CSS", $dirName."/include/style.css", false);
?>