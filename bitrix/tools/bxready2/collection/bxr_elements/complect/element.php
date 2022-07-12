<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?
use Alexkova\Bxready2\Draw;
use Alexkova\Market2\Core;
IncludeModuleLangFile(__FILE__);

$addClass = '';
$picture = false;

global $APPLICATION;
$elementDraw = \Alexkova\Bxready2\Draw::getInstance($this);

$arMatrix = array('width' => 200, 'height' => 200);
if (is_array($arElement["PREVIEW_PICTURE"])){
    $picture = $elementDraw->prepareImage($arElement["PREVIEW_PICTURE"]["ID"], $arMatrix);
} else {
    if (is_array($arElement["DETAIL_PICTURE"]))
        $picture = $elementDraw->prepareImage($arElement["DETAIL_PICTURE"]["ID"], $arMatrix);
}
if (!is_array($picture) || strlen($picture["src"])<=0){
	$picture = array("src" => $elementDraw->getDefaultImage());
}

$UID = (intval($arElementParams["UNICUM_ID"])>0) ? $arElementParams["UNICUM_ID"] : 1;

$strMainID = $arElementParams["AREA_ID"];
$arItemIDs = array(
    'ID' => $strMainID,
    'NAME' => $strMainID.'_name',
    'PICT' => $strMainID.'_pict',
    'PRICE' => $strMainID.'_price'
);
$strObName = 'ob'.preg_replace("/[^a-zA-Z0-9_]/", "x", $strMainID);
?>

    <div class="bxr-m2-complect<?=($arElementParams["LAST"] != "Y")?" separator":""?>" data-uid="<?=$UID?>" data-resize="1" id="<?=$arItemIDs["ID"]?>">
        <div class="bxr-element-container">
            <div class="bxr-element-image">
            <?
            $title = ($arElement["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"]) ? $arElement["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_ALT"] : $arElement["NAME"];
            $alt = ($arElement["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_ALT"]) ? $arElement["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_ALT"] : $arElement["NAME"];
            ?>
                <a href="<?=$arElement["DETAIL_PAGE_URL"]?>">
                    <img src="<?=$picture["src"]?>" id="<?=$arItemIDs["PICT"]?>" alt="<?=$alt?>" title="<?=$title?>">
                </a>
            </div>
            <?$name = ($arElement["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"]) ? $arElement["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"] : $arElement["NAME"];?>
            <div class="bxr-element-name" id="bxr-element-name-<?=$arElement["ID"]?>">
                <a href="<?=$arElement["DETAIL_PAGE_URL"]?>" id="<?=$arItemIDs["NAME"]?>" class="bxr-font-color bxr-font-color-hover" title="<?=$name?>">
                    <?=(strlen($arElement["SHORT_NAME"])>0) ? $arElement["SHORT_NAME"] : $name;?>
                </a>
            </div>
            <div class="bxr-element-price" id="<?=$arItemIDs["PRICE"]?>">
                <?include('price.php');?>
            </div>
        </div>
    </div>
<?
$dirName = str_replace($_SERVER["DOCUMENT_ROOT"],'', dirname(__FILE__));
$elementDraw->setAdditionalFile("JS", "/bitrix/tools/bxready2/collection/bxr_elements/complect/include/script.js", true);
$elementDraw->setAdditionalFile("CSS", "/bitrix/tools/bxready2/collection/bxr_elements/complect/include/style.css", false);
?>
<?if ($_REQUEST["bxr_ajax"]):
    global $resizeIndicator;

    if ($resizeIndicator != true){
        $resizeIndicator = true;
        ?>
        <script>
            bxrM2Complect.resizeVerticalBlock();
        </script>
    <?}
endif;?>

<script>
    bxrM2Complect.resizeVerticalBlock();
</script>