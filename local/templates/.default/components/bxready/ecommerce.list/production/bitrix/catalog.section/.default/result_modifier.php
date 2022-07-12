<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

if (!empty($arResult['ITEMS'])) {

    $imageWidth = 160;
    $imageHeight = 160;

    $IBLOCK_ID_SHOP = 37;
    $arCodes = [];

    foreach ($arResult['ITEMS'] as $ar) {
        $arCodes[$ar['XML_ID']] = $ar['ID'];
    }
    // Проверим наличие товара в интернет магазине
    // Если товар имеется, тогда поставим ему отличительный знак
    if ($arCodes) {
        $arSkuInfo = [];
        $arSections = [];
        $arFilter = [
            'IBLOCK_ID' => $IBLOCK_ID_SHOP,
            'ACTIVE' => 'Y',
            '=XML_ID' => array_keys($arCodes)
        ];
        $arSelect = [
            'ID',
            'IBLOCK_ID',
            'XML_ID',
            'PREVIEW_PICTURE',
            'DETAIL_PICTURE',
            'DETAIL_PAGE_URL'
        ];
        $res = CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
        while ($ar = $res->fetch()) {
            $sections = CIBlockElement::GetElementGroups($ar['ID'], false, ['ID', 'CODE']);
            while ($section = $sections->fetch()) {
                $ar['IBLOCK_SECTION_ID'] = $section['ID'];
                $arSections[$section['ID']] = $section['CODE'];
            }
            $arCodes[$ar['XML_ID']] = [
                'ID' => $arCodes[$ar['XML_ID']],
                'CODE' => $ar['CODE'],
                'IBLOCK_SECTION_ID' => $ar['IBLOCK_SECTION_ID'],
                'SHOP_DETAIL_PAGE_URL' => $ar['DETAIL_PAGE_URL']
            ];
            if (!$ar['PREVIEW_PICTURE'] && $ar['DETAIL_PICTURE']) {
                $ar['PREVIEW_PICTURE'] = $ar['DETAIL_PICTURE'];
            }
            if (!$ar['PREVIEW_PICTURE'] && !$ar['DETAIL_PICTURE']) {
                if (!$arSkuInfo) {
                    $arSkuInfo = CCatalogSKU::GetInfoByProductIBlock($ar['IBLOCK_ID']);
                }
                $offers = CCatalogSku::getOffersList($ar['ID'], $arSkuInfo['PRODUCT_IBLOCK_ID'], ['ACTIVE' => 'Y', '!DETAIL_PICTURE' => false], ['DETAIL_PICTURE']);
                $ar['PREVIEW_PICTURE'] = array_shift($offers[$ar['ID']])['DETAIL_PICTURE'];
            }
            if ($ar['PREVIEW_PICTURE']) {
                if ($tmp = CFile::ResizeImageGet($ar['PREVIEW_PICTURE'], array('width' => $imageWidth, 'height' => $imageHeight), BX_RESIZE_IMAGE_PROPORTIONAL, true)) {
                    $arCodes[$ar['XML_ID']]['PREVIEW_PICTURE'] = $tmp['src'];
                }
            }
        }
        // Если товар имеется в магазине
        // Тогда добавим ссылку на магазин
        foreach ($arResult['ITEMS'] as &$ar) {
            if ($arCodes[$ar['XML_ID']] && $ar['ID'] == $arCodes[$ar['XML_ID']]['ID']) {
                if (!$ar['PREVIEW_PICTURE'] && $arCodes[$ar['XML_ID']]['PREVIEW_PICTURE']) {
                    $ar['PREVIEW_PICTURE']['SRC'] = $arCodes[$ar['XML_ID']]['PREVIEW_PICTURE'];
                }
                $url = $arCodes[$ar['XML_ID']]['SHOP_DETAIL_PAGE_URL'];
                $url = str_replace('#SECTION_CODE#', $arSections[$arCodes[$ar['XML_ID']]['IBLOCK_SECTION_ID']], $url);
                $url = str_replace('#ELEMENT_CODE#', $arCodes[$ar['XML_ID']]['CODE'], $url);
                $ar['SHOP_DETAIL_PAGE_URL'] = $url;
            }
            $data = [
                'ID' => $ar['ID'],
                'NAME' => $ar['NAME'],
                'PREVIEW_PICTURE' => $ar['PREVIEW_PICTURE'],
                'EDIT_LINK' => $ar['EDIT_LINK'],
                'DELETE_LINK' => $ar['DELETE_LINK'],
                'SHOP_DETAIL_PAGE_URL' => $ar['SHOP_DETAIL_PAGE_URL']
            ];
            $ar = $data;
        }
    }
}
