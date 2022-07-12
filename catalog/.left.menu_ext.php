<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

global $APPLICATION;
$IBLOCK_ID = 37; // Инфоблок товаров
$aMenuLinksExt = $APPLICATION->IncludeComponent(
    'alexkova.market:menu.sections',
    '',
    [
        'IS_SEF' => 'Y',
        'ID' => $_REQUEST['ID'],
        'IBLOCK_TYPE' => '1с_catalog',
        'IBLOCK_ID' => $IBLOCK_ID,
        'SECTION_URL' => '',
        'DEPTH_LEVEL' => '3',
        'CACHE_TYPE' => 'N',
        'CACHE_TIME' => '36000000',
        'SEF_BASE_URL' => '/catalog/',
        'SECTION_PAGE_URL' => '#SECTION_CODE#/',
        'DETAIL_PAGE_URL' => '#SECTION_CODE#/#ELEMENT_CODE#/'
    ],
    false,
    [
        'HIDE_ICONS' => 'Y'
    ]
);

foreach ($aMenuLinksExt as $k => &$val) {
    if ($k != 'PICTURE') $val['DEPTH_LEVEL']++;
}

$aMenuLinks = array_merge($aMenuLinks, $aMenuLinksExt);