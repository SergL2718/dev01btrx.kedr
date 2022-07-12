<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$arResult = [];
$sectionCode = explode('/', $_SERVER['REQUEST_URI'])[2];
$arResult['SECTION'] = CIBlockSection::GetList([], ['IBLOCK_ID' => $arParams['IBLOCK_ID'], 'CODE' => $sectionCode])->fetch();
$arResult['SHOW_CITIES'] = [
    'russia',
    'belarus',
    'ukraine',
    'kazakhstan'
];
if ($res = CIBlockSection::GetByID($arResult['SECTION']['ID'])->fetch()) {
    if (in_array($arResult['SECTION']['CODE'], $arResult['SHOW_CITIES'])) {
        $arFilter = [
            'IBLOCK_ID' => $res['IBLOCK_ID'],
            'ACTIVE' => 'Y',
            '!CODE' => false,
            '>ELEMENT_CNT' => 0,
            '>LEFT_MARGIN' => $res['LEFT_MARGIN'],
            '<RIGHT_MARGIN' => $res['RIGHT_MARGIN'],
            '>DEPTH_LEVEL' => $res['DEPTH_LEVEL'],
            'CNT_ACTIVE' => 'Y'
        ];
        $arSelect = [
            'NAME',
            'SECTION_PAGE_URL',
            'ELEMENT_CNT'
        ];
        $res = CIBlockSection::GetList(['NAME' => 'asc'], $arFilter, true, $arSelect);
        while ($ar = $res->getNext()) {
            if ($ar['~ELEMENT_CNT'] == 0) {
                continue;
            }
            $letter = substr($ar['NAME'], 0, 1);
            $arResult['CITIES'][$letter][] = [
                'NAME' => $ar['~NAME'],
                'URL' => $ar['~SECTION_PAGE_URL'],
                'COUNT' => $ar['~ELEMENT_CNT']
            ];
        }
    } else {
        $arSelect = [
            'ID',
            'IBLOCK_ID',
            'NAME',
            'PROPERTY_SHOP',
            'PROPERTY_CITY',
            'PROPERTY_ADDRESS',
            'PROPERTY_TC',
            'PROPERTY_TIME',
            'PROPERTY_PHONE',
            'PROPERTY_WWW',
            'PROPERTY_EMAIL'
        ];
        $arFilter = [
            'IBLOCK_ID' => $arParams['IBLOCK_ID'],
            'SECTION_CODE' => $arResult['SECTION']['CODE'],
            'INCLUDE_SUBSECTIONS' => 'Y',
            'ACTIVE' => 'Y'
        ];
        $res = CIBlockElement::GetList(['SORT' => 'ASC', 'NAME' => 'ASC'], $arFilter, false, false, $arSelect);
        while ($ar = $res->fetch()) {
            $ar['PROPERTY_WWW_VALUE'] = trim(str_replace(['http://', 'https://'], '', $ar['PROPERTY_WWW_VALUE']), '/');
            $arResult['STORES'][$ar['ID']] = [
                'NAME' => $ar['NAME'],
                'SHOP' => $ar['PROPERTY_SHOP_VALUE'],
                'CITY' => $ar['PROPERTY_CITY_VALUE'],
                'ADDRESS' => $ar['PROPERTY_ADDRESS_VALUE'],
                'TC' => $ar['PROPERTY_TC_VALUE'],
                'TIME' => $ar['PROPERTY_TIME_VALUE'],
                'WWW' => $ar['PROPERTY_WWW_VALUE'],
                'WWW_LINK' => 'http://' . $ar['PROPERTY_WWW_VALUE'],
                'EMAIL' => $ar['PROPERTY_EMAIL_VALUE']
            ];
            foreach ($ar['PROPERTY_PHONE_VALUE'] as $key => $phone) {
                if ($ar['PROPERTY_PHONE_DESCRIPTION'][$key]) {
                    $phone .= ' [' . $ar['PROPERTY_PHONE_DESCRIPTION'][$key] . ']';
                }
                $arResult['STORES'][$ar['ID']]['PHONE'][] = $phone;
            }
        }
    }
}
$APPLICATION->AddChainItem($APPLICATION->GetTitle(), '/dealers/');
$APPLICATION->SetTitle($APPLICATION->getTitle() . ': ' . $arResult['SECTION']['NAME']);
$APPLICATION->AddChainItem($arResult['SECTION']['NAME']);