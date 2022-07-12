<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>

<?foreach($arResult["ITEMS"] as $arItem):?>
	<?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));

    //echo "<pre>"; print_r($arItem); echo "</pre>";
	if($arItem["PROPERTIES"]["TYPE"]["VALUE_XML_ID"] == "in"){
        $title = "НАЧИСЛЕНО БОНУСОВ";
        $simbol = "+";
    }
    else{
		$title = "СПИСАНО БОНУСОВ";
		$simbol = "-";
    }
    ?>
    <div class="order-side-card">
        <div class="order-side-card__title"><?=$title?></div>
        <div class="order-side-card__body">
            <div class="bonus-line"><?=$simbol?><?echo $arItem["PROPERTIES"]["BONUS"]["VALUE"]?> зкр
                <div class="icon icon-pine-cone"></div>
            </div>
        </div>
    </div>

<?endforeach;?>

