<?php
/*
 * Изменено: 18 сентября 2021, суббота
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
$previewImageWidth = 215;
$previewImageHeight = 215;
foreach ($arResult['ITEMS'] as &$item) {
	$item['NAME'] = '';
	if ($item['PROPERTIES']['LAST_NAME']['VALUE']) {
		$item['NAME'] = $item['PROPERTIES']['LAST_NAME']['VALUE'];
	}
	if ($item['PROPERTIES']['LAST_NAME']['VALUE']) {
		$item['NAME'] .= ' ' . $item['PROPERTIES']['LAST_NAME']['VALUE'];
	}
	if ($item['PROPERTIES']['TITLE']['VALUE']) {
		$item['NAME'] .= ' – ' . $item['PROPERTIES']['TITLE']['VALUE'];
	}
	if (!empty($item['PREVIEW_PICTURE']) && $item['PREVIEW_PICTURE']['WIDTH'] > $previewImageWidth) {
		$item['PREVIEW_PICTURE'] = [
			'ID'  => $item['PREVIEW_PICTURE']['ID'],
			'SRC' => \CFile::ResizeImageGet($item['PREVIEW_PICTURE']['ID'], ['width' => $previewImageWidth, 'height' => $previewImageHeight], BX_RESIZE_IMAGE_EXACT)['src'],
		];
	}
}