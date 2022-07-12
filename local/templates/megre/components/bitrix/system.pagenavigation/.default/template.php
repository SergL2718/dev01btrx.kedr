<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/*
 * Изменено: 24 January 2022, Monday
 * Автор: Артамонов Денис <artamonov.d.i@yandex.com>
 * copyright (c) 2022
 */

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

if (!$arResult["NavShowAlways"]) {
    if ($arResult["NavRecordCount"] == 0 || ($arResult["NavPageCount"] == 1 && $arResult["NavShowAll"] == false))
        return;
}

$strNavQueryString = ($arResult["NavQueryString"] != "" ? $arResult["NavQueryString"] . "&amp;" : "");
$strNavQueryStringFull = ($arResult["NavQueryString"] != "" ? "?" . $arResult["NavQueryString"] : "");
?>
<div class="list-pagination-wrapper">
    <div class="list-pagination-container">
        <ul>
            <?php if ($arResult['NavPageNomer'] > 1): ?>
                <?php if ($arResult['NavPageNomer'] > 2): ?>
                    <li class="list-pagination-pag-prev"><a
                                href="<?= $arResult['sUrlPath'] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult['NavNum'] ?>=<?= ($arResult['NavPageNomer'] - 1) ?>"><span><i
                                        class="pagination-arrow-left"></i></span></a></li>
                <?php else: ?>
                    <li class="list-pagination-pag-prev"><a
                                href="<?= $arResult['sUrlPath'] ?><?= $strNavQueryStringFull ?>"><span><i
                                        class="pagination-arrow-left"></i></span></a></li>
                <?php endif ?>
            <?php else: ?>
                <li class="list-pagination-pag-prev"><span><i class="pagination-arrow-left"></i></span></li>
            <?php endif ?>

            <?php while ($arResult['nStartPage'] <= $arResult["nEndPage"]): ?>
                <?php if ($arResult['nStartPage'] == $arResult['NavPageNomer']): ?>
                    <li class="list-pagination-active"><span><?= $arResult['nStartPage'] ?></span></li>
                <?php elseif ($arResult['nStartPage'] == 1 && $arResult['bSavePage'] == false): ?>
                    <li class="">
                        <a href="<?= $arResult['sUrlPath'] ?><?= $strNavQueryStringFull ?>"><span><?= $arResult['nStartPage'] ?></span></a>
                    </li>
                <?php else: ?>
                    <li class="">
                        <a href="<?= $arResult['sUrlPath'] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult['NavNum'] ?>=<?= $arResult['nStartPage'] ?>"><span><?= $arResult['nStartPage'] ?></span></a>
                    </li>
                <?php endif ?>
                <?php $arResult['nStartPage']++ ?>
            <?php endwhile ?>

            <?php if ($arResult['NavPageNomer'] < $arResult["NavPageCount"]): ?>
                <li class="list-pagination-pag-next">
                    <a href="<?= $arResult['sUrlPath'] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult['NavNum'] ?>=<?= ($arResult['NavPageNomer'] + 1) ?>"><span><i
                                    class="pagination-arrow-right"></i></span></a>
                </li>
            <?php else: ?>
                <li class="list-pagination-pag-next"><span><i class="pagination-arrow-right"></i></span></li>
            <?php endif ?>
        </ul>
    </div>
</div>
