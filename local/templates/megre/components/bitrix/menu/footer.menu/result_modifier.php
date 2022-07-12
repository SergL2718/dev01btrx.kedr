<?php
/*
 * Изменено: 08 сентября 2021, среда
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die;
if (empty($arResult)) {
	return;
}
foreach ($arResult as &$item) {
	if (mb_strpos($item['LINK'], '/tel:') !== false) {
		$item['LINK'] = str_replace('/tel:', 'tel:', $item['LINK']);
	}
}