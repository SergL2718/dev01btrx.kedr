<?php
/*
 * Изменено: 29 December 2021, Wednesday
 * Автор: Артамонов Денис <artamonov.d.i@yandex.com>
 * copyright (c) 2022
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
	if (!empty($item['PREVIEW_PICTURE'])) {
		$item['PREVIEW_PICTURE'] = [
			'ID'  => $item['PREVIEW_PICTURE']['ID'],
			'SRC' => \CFile::ResizeImageGet($item['PREVIEW_PICTURE']['ID'], ['width' => 400, 'height' => 320], BX_RESIZE_IMAGE_EXACT)['src'],
		];
	}
}