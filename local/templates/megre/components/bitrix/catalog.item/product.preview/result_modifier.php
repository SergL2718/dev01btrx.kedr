<?php
/*
 * Изменено: 22 ноября 2021, понедельник
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

/**
 * @var array $arResult
 */

use Bitrix\Catalog\ProductTable;
use Native\App\Catalog\Product;
use Native\App\Sale\Store;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die;
if (empty($arResult['ITEM'])) {
	return;
}
$item =& $arResult['ITEM'];


if ($item['PRODUCT']['TYPE'] == ProductTable::TYPE_SKU && !$item['ITEM_PRICES']) {
	$prices = [];
	foreach ($item['OFFERS'] as $i => $offer) {
		$prices[$i] = current($offer['ITEM_PRICES'])['PRICE'];
	}
	asort($prices);
	$prices = array_diff($prices, ['']);
	$item['ITEM_PRICES'] = $item['OFFERS'][array_key_first($prices)]['ITEM_PRICES'];
}
$item['ITEM_PRICES'] = $item['ITEM_PRICES'][0];
if ($item['CATALOG_CAN_BUY_ZERO'] === 'Y' || $item['PRODUCT']['CAN_BUY_ZERO'] === 'Y') {
	$item['CATALOG_CAN_BUY_ZERO'] = $item['PRODUCT']['CAN_BUY_ZERO'] = 'Y';
}
$item['CATALOG_QUANTITY'] = $item['PRODUCT']['QUANTITY'] = Product::getQuantityByStore($item['ID'], Store::getInstance()->getCurrent());
if ($item['PROPERTIES']['OFFER_WEEK']['VALUE_XML_ID'] === 'Y') {
	$item['BADGES']['OFFER_WEEK']['CODE'] = 'OFFER_WEEK';
	$item['BADGES']['OFFER_WEEK']['TITLE'] = $item['PROPERTIES']['OFFER_WEEK']['NAME'];
}
if ($item['PROPERTIES']['NEWPRODUCT']['VALUE']) {
	$item['BADGES']['NEW']['CODE'] = 'NEW';
	$item['BADGES']['NEW']['TITLE'] = $item['PROPERTIES']['NEWPRODUCT']['NAME'];
}
if ($item['PROPERTIES']['SPECIALOFFER']['VALUE']) {
	$item['BADGES']['SPECIAL_OFFER']['CODE'] = 'SPECIAL_OFFER';
	$item['BADGES']['SPECIAL_OFFER']['TITLE'] = $item['PROPERTIES']['SPECIALOFFER']['NAME'];
}
if ($item['PROPERTIES']['SALELEADER']['VALUE']) {
	$item['BADGES']['HIT']['CODE'] = 'HIT';
	$item['BADGES']['HIT']['TITLE'] = $item['PROPERTIES']['SALELEADER']['NAME'];
}
if ($item['PROPERTIES']['RECOMMENDED']['VALUE']) {
	$item['BADGES']['RECOMMENDED']['CODE'] = 'RECOMMENDED';
	$item['BADGES']['RECOMMENDED']['TITLE'] = $item['PROPERTIES']['RECOMMENDED']['NAME'];
}
if (!$item['PROPERTIES']['NUMBER_BONUSES']['VALUE']) {
	$item['PROPERTIES']['NUMBER_BONUSES']['VALUE'] = Product::getNumberBonuses($item['ID']);
}

$arSelect = array("IBLOCK_SECTION_ID");
$IBLOCK_ID = 41;
$arFilter = array(
	"IBLOCK_ID" => $IBLOCK_ID,
	"ACTIVE" => "Y",
	"CODE" => $arResult["CODE"]
);
$res = CIBlockElement::GetList(array("SORT" => "ASC"), $arFilter, false, array("nTopCount" => 1), $arSelect);
while ($ar_fields = $res->GetNext()) {
	if($ar_fields["IBLOCK_SECTION_ID"]){
		$resSec = CIBlockSection::GetByID($ar_fields["IBLOCK_SECTION_ID"]);
		if($ar_resSec = $resSec->GetNext()) {
			$arResult["FAMILY"] = $ar_resSec['NAME'];

			if(!stristr($arResult['ITEM']["DETAIL_PAGE_URL"], $ar_resSec['CODE'])){
				$arResult['ITEM']["DETAIL_PAGE_URL"] = "/catalog/".$ar_resSec['CODE']."/".$arResult['ITEM']['CODE']."/";
			}
		}
	}
}
