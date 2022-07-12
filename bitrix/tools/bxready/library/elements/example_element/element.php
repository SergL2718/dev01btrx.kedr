<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?
use Alexkova\Bxready\Draw;
$addClass = ''; $UID = 0;


if(intval($arElementParams["UNICUM_ID"])>0){
	$UID = $arElementParams["UNICUM_ID"];
}
?>

<div class="bxr_example_element" data-uid="<?=$UID?>" data-resize="1">
	<div class="bxr_example_name">
		<a href="<?=$arElement["DETAIL_PAGE_URL"]?>"><?=$arElement["NAME"]?></a>
	</div>
</div>

<?
/*
 * Pri neobhodimosti podkluchaem nuzhnye biblioteki
 */
$elementDraw = \Alexkova\Bxready\Draw::getInstance();
$dirName = str_replace($_SERVER["DOCUMENT_ROOT"],'', dirname(__FILE__));
$elementDraw->setAdditionalFile("JS", "/bitrix/tools/bxready/library/elements/example_element/include/script.js", true);
$elementDraw->setAdditionalFile("CSS", "/bitrix/tools/bxready/library/elements/example_element/include/style.css", false);
?>