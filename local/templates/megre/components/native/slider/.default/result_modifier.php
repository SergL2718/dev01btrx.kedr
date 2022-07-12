<?php
/*
 * Изменено: 16 сентября 2021, четверг
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