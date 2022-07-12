<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

foreach ($arResult['SECTIONS'] as &$arSection) {
    $arSection = [
        'ID' => $arSection['ID'],
        'NAME' => $arSection['NAME'],
        'SECTION_PAGE_URL' => $arSection['SECTION_PAGE_URL'],
        'PICTURE' => $arSection['PICTURE']['SRC']
    ];
}