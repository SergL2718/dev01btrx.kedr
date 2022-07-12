<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
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


//echo "<pre>"; print_r($arResult); echo "</pre>";
?>

<table class="cabinet-bonus-list">
    <tr>
        <th>Дата</th>
        <th></th>
        <th>Начисление/<br/> списание</th>
        <th>Остаток</th>
    </tr>

	<? foreach ($arResult["ITEMS"] as $arItem): ?>
		<?
		$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
		$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
		?>
        <tr>
            <td><? echo $arItem["DISPLAY_ACTIVE_FROM"] ?></td>
            <td><?= $arItem["NAME"] ?><br/> <span>№ <?= $arItem["ID"] ?></span></td>
            <td>
                <div class="bonus-line<?echo ($arItem["PROPERTIES"]["TYPE"]["VALUE_XML_ID"] == "in") ? "" : " bonus-line_red";?>"><? echo ($arItem["PROPERTIES"]["TYPE"]["VALUE_XML_ID"] == "in") ? "+" : "-";
					echo $arItem["PROPERTIES"]["BONUS"]["VALUE"]; ?>
                    <div class="icon icon-pine-cone"></div>
                </div>
                <p>Остаток: <?= $arItem["PROPERTIES"]["SUMM"]["VALUE"] ?></p>
            </td>
            <td>
                <div class="bonus-line"><?= $arItem["PROPERTIES"]["SUMM"]["VALUE"] ?>
                    <div class="icon icon-pine-cone"></div>
                </div>
            </td>
        </tr>

	<? endforeach; ?>
</table>
