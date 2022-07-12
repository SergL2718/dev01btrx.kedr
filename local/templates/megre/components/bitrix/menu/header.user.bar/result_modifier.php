<?php
/*
 * Изменено: 27 сентября 2021, понедельник
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

/**
 * @var array $arResult
 */

use Native\App\Sale\Favorites;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die;
$previewImageWidth = 80;
$previewImageHeight = 80;
$arResult['FAVORITES'] = [];
foreach (Favorites::getList() as $item) {
	$item = \Native\App\Catalog\Product::getById($item['ID']);
	if (!empty($item['PREVIEW_PICTURE'])) {
		$item['PREVIEW_PICTURE'] = [
			'ID'  => $item['PREVIEW_PICTURE'],
			'SRC' => \CFile::ResizeImageGet($item['PREVIEW_PICTURE'], ['width' => $previewImageWidth, 'height' => $previewImageHeight])['src'],
		];
	}
	$arResult['FAVORITES'][$item['ID']] = $item;
}