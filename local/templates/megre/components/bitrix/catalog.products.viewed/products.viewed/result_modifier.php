<?php
/*
 * Изменено: 29 ноября 2021, понедельник
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

/**
 * @var array $arResult
 */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die;
if (empty($arResult['ITEMS'])) {
	return;
}
$this->addExternalCss(SITE_TEMPLATE_PATH . '/lib/owlcarousel/owl.carousel.min.css');
$this->addExternalJS(SITE_TEMPLATE_PATH . '/lib/owlcarousel/owl.carousel.min.js');
$arResult['UNIQUE_ID'] = md5($this->getName() . $this->randString());
foreach ($arResult['ITEMS'] as &$item) {
	if (empty($item['PREVIEW_PICTURE']) && !empty($item['DETAIL_PICTURE'])) {
		$item['PREVIEW_PICTURE'] = $item['DETAIL_PICTURE'];
	}
	if (!empty($item['PREVIEW_PICTURE']) && $item['PREVIEW_PICTURE']['WIDTH'] > 255) {
		$item['PREVIEW_PICTURE'] = [
			'ID'  => $item['PREVIEW_PICTURE']['ID'],
			'SRC' => \CFile::ResizeImageGet($item['PREVIEW_PICTURE']['ID'], ['width' => 255, 'height' => 255])['src'],
		];
	}
}