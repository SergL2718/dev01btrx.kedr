<?
/*
 * Изменено: 05 марта 2018, понедельник
 * Автор: Артамонов Денис <artamonov.d.i@yandex.com>
 * copyright (c) 2022
 */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$this->setFrameMode(true);

$elementTemplate = ".default";

global $unicumID;
if ($unicumID <= 0) {
    $unicumID = 1;
} else {
    $unicumID++;
}

$arParams["UNICUM_ID"] = $unicumID;

$colToElem = array();
$bootstrapGridCount = $arParams["BXREADY_LIST_BOOTSTRAP_GRID_STYLE"];
if ($bootstrapGridCount > 0) {
    for ($i = 1; $i <= $bootstrapGridCount; $i++) {
        if (($bootstrapGridCount % $i) == 0) {
            $colToElem[$bootstrapGridCount / $i] = $i;
        }
    }
}