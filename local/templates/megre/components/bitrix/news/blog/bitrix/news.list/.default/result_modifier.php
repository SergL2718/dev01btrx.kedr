<?php
/*
 * Изменено: 24 January 2022, Monday
 * Автор: Артамонов Денис <artamonov.d.i@yandex.com>
 * copyright (c) 2022
 */

/**
 * @@global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 */

use Bitrix\Iblock\SectionTable;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die;
try {
    $r = SectionTable::getList([
        'select' => [
            'ID',
            'CODE',
            'NAME',
            'PICTURE',
        ],
        'filter' => [
            '=IBLOCK_ID' => $arParams['IBLOCK_ID'],
            '=ACTIVE' => 'Y',
            '!=CODE' => 'blog',
        ],
        'order' => [
            'SORT' => 'ASC',
            'NAME' => 'ASC',
        ],
        'cache' => [
            'ttl' => $arParams['~CACHE_TIME'],
        ],
    ]);
    while ($a = $r->fetch()) {
        $a['DETAIL_PAGE_URL'] = '/' . $arResult['CODE'] . '/' . $a['CODE'] . '/';
        if ($a['PICTURE']) {
            $a['PICTURE'] = CFile::GetPath($a['PICTURE']);
        }
        $arResult['SECTIONS'][$a['ID']] = $a;
    }
} catch (ObjectPropertyException|SystemException $e) {
}

foreach ($arResult['ITEMS'] as &$item) {
    if ($item['PREVIEW_PICTURE']['ID']) {
        $item['PREVIEW_PICTURE']['SRC'] = \CFile::ResizeImageGet($item['PREVIEW_PICTURE']['ID'], ['width' => 400, 'height' => 320], BX_RESIZE_IMAGE_EXACT)['src'];
    }
}