<?php
/*
 * Изменено: 29 декабря 2021, среда
 * Автор: Артамонов Денис <artamonov.d.i@yandex.com>
 * copyright (c) 2022
 */

/**
 * @var array $arParams
 * @var array $arResult
 */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die;
$parents = [];
$children = [];
$products = [];
$obCache = new CPHPCache();
$cachePath = '/' . SITE_ID . '/bitrix/menu/';
$cacheID = 'GeneralMenuSections';
if ($obCache->InitCache($arParams['CACHE_TIME'], $cacheID, $cachePath . $cacheID)) {
	$cache = $obCache->GetVars();
	$parents = $cache['parents'];
	$children = $cache['children'];
	$products = $cache['products'];
} else if ($obCache->StartDataCache()) {
	$sections = CIBlockSection::GetList(
		['SORT' => 'ASC', 'LEFT_MARGIN' => 'ASC', 'NAME' => 'ASC'],
		[
			'IBLOCK_ID' => 66,
			'ACTIVE'    => 'Y',
		],
		false,
		[
			'ID',
			'NAME',
			'IBLOCK_SECTION_ID',
			'DEPTH_LEVEL',
			'PICTURE',
			'DESCRIPTION',
			'UF_DETAIL_PAGE_URL',
			'UF_PRODUCT',
			'UF_PRODUCT_PREVIEW_TEXT',
			'UF_PRODUCT_DETAIL_PAGE_URL',
			'UF_PRODUCT_DETAIL_PAGE_URL_TITLE',
		]
	);
	while ($section = $sections->Fetch()) {
		if ($section['UF_PRODUCT']) {
			$products[$section['UF_PRODUCT']] = $section['UF_PRODUCT'];
		}
		if ($section['DEPTH_LEVEL'] == 1) {
			$parents[] = $section;
		} else {
			$children[$section['IBLOCK_SECTION_ID']][$section['ID']] = $section;
		}
	}
	$obCache->EndDataCache([
		'parents'  => $parents,
		'children' => $children,
		'products' => $products,
	]);
}
if (!empty($products)) {
	$cacheID = 'GeneralMenuProducts';
	if ($obCache->InitCache($arParams['CACHE_TIME'], $cacheID, $cachePath . $cacheID)) {
		$products = $obCache->GetVars();
	} else if ($obCache->StartDataCache()) {
		$elements = \CIBlockElement::GetList([], ['=ID' => array_keys($products)], false, ['nTopCount' => count($products)],
			[
				'ID',
				'PREVIEW_TEXT',
				'PREVIEW_PICTURE',
				'DETAIL_PICTURE',
				'DETAIL_PAGE_URL',
				'PROPERTY_NEWPRODUCT',
				'PROPERTY_SPECIALOFFER',
				'PROPERTY_RECOMMENDED',
				'PROPERTY_SALELEADER',
				'PROPERTY_OFFER_WEEK',
			]);
		while ($product = $elements->GetNext(false, false)) {
			$products[$product['ID']] = $product;
		}
		$obCache->EndDataCache($products);
	}
}
foreach ($parents as $parentId => $item) {
	if ($children[$item['ID']]) {
		$item['CHILDREN'] = $children[$item['ID']];
	}
	unset(
		$item['IBLOCK_SECTION_ID'],
		$item['DEPTH_LEVEL'],
		$item['DESCRIPTION_TYPE'],
		$item['SORT'],
		$item['LEFT_MARGIN'],
	);
	if (!$products[$item['UF_PRODUCT']]) {
		unset(
			$item['UF_PRODUCT_PREVIEW_TEXT'],
			$item['UF_PRODUCT_DETAIL_PAGE_URL'],
			$item['UF_PRODUCT_DETAIL_PAGE_URL_TITLE'],
		);
	} else {
		unset(
			$item['PICTURE'],
			$item['DESCRIPTION'],
		);
		$item['UF_PRODUCT'] = $products[$item['UF_PRODUCT']];
		if ($item['UF_PRODUCT_DETAIL_PAGE_URL']) {
			$item['UF_PRODUCT_DETAIL_PAGE_URL'] = trim($item['UF_PRODUCT_DETAIL_PAGE_URL']);
		}
		if (!$item['UF_PRODUCT_DETAIL_PAGE_URL_TITLE']) {
			$item['UF_PRODUCT_DETAIL_PAGE_URL_TITLE'] = 'Купить сейчас';
		}
		if (!$item['UF_PRODUCT_PREVIEW_TEXT']) {
			$item['UF_PRODUCT_PREVIEW_TEXT'] = $item['UF_PRODUCT']['PREVIEW_TEXT'];
		}
		if (!$item['UF_PRODUCT_DETAIL_PAGE_URL']) {
			$item['UF_PRODUCT_DETAIL_PAGE_URL'] = $item['UF_PRODUCT']['DETAIL_PAGE_URL'];
		}
		if (!$item['UF_PRODUCT']['PREVIEW_PICTURE'] && $item['UF_PRODUCT']['DETAIL_PICTURE']) {
			$item['UF_PRODUCT']['PREVIEW_PICTURE'] = $item['UF_PRODUCT']['DETAIL_PICTURE'];
		}
		if ($item['UF_PRODUCT']['PREVIEW_PICTURE']) {
			$item['UF_PRODUCT']['PREVIEW_PICTURE'] = [
				'ID'  => $item['UF_PRODUCT']['PREVIEW_PICTURE'],
				'SRC' => \CFile::ResizeImageGet($item['UF_PRODUCT']['PREVIEW_PICTURE'], ['width' => 300, 'height' => 300])['src'],
			];
		}
	}
	if (!$item['UF_PRODUCT']) {
		unset($item['UF_PRODUCT']);
	} else {
		if (count($item['CHILDREN']) === 8) {
			array_pop($item['CHILDREN']); // удалим последний пункт меню
			$item['CHILDREN'][] = [
				'NAME'               => 'Посмотреть всё',
				'UF_DETAIL_PAGE_URL' => $item['UF_DETAIL_PAGE_URL'],
				'SEE_ALL_URL'        => 'Y',
			];
		}
	}
	if (!$item['UF_DETAIL_PAGE_URL']) {
		$item['UF_DETAIL_PAGE_URL'] = 'javascript:void(0)';
	} else {
		$item['UF_DETAIL_PAGE_URL'] = trim($item['UF_DETAIL_PAGE_URL']);
	}
	if (!$item['UF_PRODUCT']['PREVIEW_PICTURE'] && $item['PICTURE']) {
		$item['PICTURE'] = [
			'ID'  => $item['PICTURE'],
			'SRC' => \CFile::ResizeImageGet($item['PICTURE'], ['width' => 300, 'height' => 300])['src'],
		];
	}
	$arResult[$item['ID']] = $item;
}