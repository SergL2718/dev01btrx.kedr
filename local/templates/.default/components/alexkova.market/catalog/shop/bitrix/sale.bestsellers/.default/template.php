<?
/*
 * @author Артамонов Денис <artamonov.ceo@gmail.com>
 * @updated 01.09.2020, 19:29
 * @copyright 2011-2020
 */

/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */
/** @global CDatabase $DB */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$this->setFrameMode(true);

if (count($arResult['ITEMS']) > 0){
?>
<noindex>
    <h2><?= $arParams["BLOCK_TITLE"] ?></h2>
    <div class="row bxr-list" id="product-list-bestsellers">
        <div class="clearfix"></div>

        <?
        $elementDraw = \Alexkova\Bxready\Draw::getInstance($this);
        $elementDraw->setCurrentTemplate($this);
        global $unicumID;
        if ($unicumID <= 0) {
            $unicumID = 1;
        } else {
            $unicumID++;
        } ?>

        <? foreach ($arResult['ITEMS'] as $key => $arItem) {

            $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
            $strMainID = $this->GetEditAreaId($arItem['ID']);

            ?>

            <div id="<?= $strMainID ?>"
                 class="t_<?= $unicumID ?> col-lg-<?= $arParams["LG_CNT"] ?> col-md-<?= $arParams["MD_CNT"] ?> col-sm-<?= $arParams["SM_CNT"] ?> col-xs-<?= $arParams["XS_CNT"] ?>">
                <? $elementDraw->showElement($arParams["BXREADY_ELEMENT_DRAW"], $arItem, $arParams) ?>
            </div>
        <? } ?>
    </div>

    <script>
        BX.Vue.create({
            el: '#product-list-bestsellers'
        });
    </script>

    <? } ?>
</noindex>
