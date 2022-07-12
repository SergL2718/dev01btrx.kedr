<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?
use Alexkova\Bxready\Draw;
$addClass = ''; $UID = 0;
$picture = false;

global $APPLICATION;

//echo "<pre>"; print_r($arElement); echo "</pre>";

$elementDraw = \Alexkova\Bxready\Draw::getInstance($this);

$arMatrix = array('width' => 120, 'height' => 120);

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
	$UID = $arElementParams["UNICUM_ID"]>0 ? $arElementParams["UNICUM_ID"]>0 : 1;
}
?>

<div class="bxr-catalog-index-v1" data-uid="<?=$UID?>" data-resize="1">
	<div class="bxr-section-container">

		<div class="bxr-element-image">
			<a href="<?=$arElement["SECTION_PAGE_URL"]?>"><img src="<?=$picture["src"]?>"></a>
		</div>

		<div class="bxr-element-content">
			<div class="bxr-element-name">
				<a href="<?=$arElement["SECTION_PAGE_URL"]?>">
					<?=$arElement["NAME"]?>
					<?if ($arElementParams["COUNT_ELEMENTS"]):?>
					(<?=$arElement["ELEMENT_CNT"]?>)
					<?endif;?>
				</a>
			</div>
			<div class="bxr-element-items">
				<?foreach($arElement["ITEMS"] as $arItem):?>
					<a href="<?=$arItem["SECTION_PAGE_URL"]?>" class="bxr-gray-content">
						<?=$arItem["NAME"]?>
						<?if ($arElementParams["COUNT_ELEMENTS"]):?>
							(<?=$arItem["ELEMENT_CNT"]?>)
						<?endif;?>
					</a>
				<?endforeach?>
			</div>
			<?if (strlen($arElement["DESCRIPTION"])>0):?>
                            <?
                            $arDesc = explode("#STEXT#", $arElement["DESCRIPTION"]);
                            $sectionDesc = $arDesc[0];
                            ?>
				<div class="bxr-element-description">
					<?=$sectionDesc?>
				</div>
			<?endif;?>
		</div>



	</div>
</div>

<?
/*
 * Pri neobhodimosti podkluchaem nuzhnye biblioteki
 */

$dirName = str_replace($_SERVER["DOCUMENT_ROOT"],'', dirname(__FILE__));
//$elementDraw->setAdditionalFile("JS", $dirName."/include/script.js", true);
$elementDraw->setAdditionalFile("CSS", "/bitrix/tools/bxready/library/elements/catalog_index_v1/include/style.css", false);
?>