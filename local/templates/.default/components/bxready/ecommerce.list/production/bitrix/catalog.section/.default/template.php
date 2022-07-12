<?php

use Alexkova\Bxready\Draw;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

global $unicumID;

$this->setFrameMode(true);
$draw = Draw::getInstance();

if ($unicumID <= 0) {
    $unicumID = 1;
} else {
    $unicumID++;
}
?>

    <!--noindex-->
    <div class="section-description">
        <p>
            Данный каталог содержит полный перечень продукции, маркированной знаком «Звенящие кедры России», которую Вы
            можете встретить в продаже.
        </p>
        <p>
            На продукции, которую можно купить в интернет-магазине с доставкой в любую точку России, расположена кнопка<span class="btn-buy-shop">Купить в магазине</span> Нажав на неё, Вы перейдёте в интернет-магазин <a href="/" rel="nofollow">megre.ru</a> для оформления заказа.
        </p>
        <p>
            На продукци, которая продаётся только в фирменных отделах, расположена кнопка<span class="btn-buy-dealer">Купить у дилера</span> Нажав на неё, Вы сможете увидеть адреса торговых точек, где может быть доступен товар. ВНИМАНИЕ: свяжитесь с
            нужным Вам отделом, чтобы уточнить наличие интересующей позиции или сделать заказ.
        </p>
    </div>
    <!--/noindex-->

<? if (count($arResult["ITEMS"]) > 0): ?>
    <div class="row bxr-list">
        <? foreach ($arResult["ITEMS"] as $cell => $arItem): ?>
            <?
            $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
            $strMainID = $this->GetEditAreaId($arItem['ID']);
            ?>
            <div id="<?= $strMainID ?>"
                 class="t_<?= $unicumID ?> col-lg-<?= $arParams["BXREADY_LIST_LG_CNT"] ?> col-md-<?= $arParams["BXREADY_LIST_MD_CNT"] ?> col-sm-<?= $arParams["BXREADY_LIST_SM_CNT"] ?> col-xs-<?= $arParams["BXREADY_LIST_XS_CNT"] ?>"><?
                $draw->setCurrentTemplate($this);
                ?>
                <div class="bxr-ecommerce-v1">
                    <div class="bxr-element-container">
                        <div class="bxr-element-image">
                            <img src="<?= ($arItem['PREVIEW_PICTURE']) ? $arItem['PREVIEW_PICTURE']['SRC'] : $draw->getDefaultImage() ?>"
                                 id="bx_<?= $strMainID ?>_<?= $arItem['ID'] ?>_pict"
                                 alt="<?= $arItem['NAME'] ?>" title="<?= $arItem['NAME'] ?>">
                        </div>
                        <div class="bxr-element-name" id="bx_<?= $strMainID ?>_<?= $arItem['ID'] ?>_name">
                            <?= $arItem['NAME'] ?>
                        </div>
                        <div class="bxr-element-action" id="bx_<?= $strMainID ?>_<?= $arItem['ID'] ?>_basket_actions">
                            <? if ($arItem['SHOP_DETAIL_PAGE_URL']): ?>
                                <a href="<?= $arItem['SHOP_DETAIL_PAGE_URL'] ?>" class="bxr-color-button" target="_blank">Купить в магазине</a>
                            <? else: ?>
                                <a href="/dealers/" class="bxr-color-button blue" target="_blank">Купить у дилера</a>
                            <? endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <? endforeach; ?>
    </div>
    <? if ($arParams["DISPLAY_BOTTOM_PAGER"]): ?>
        <div class="pagination-page">
            <?= $arResult["NAV_STRING"]; ?>
        </div>
    <? endif; ?>
<? endif; ?>