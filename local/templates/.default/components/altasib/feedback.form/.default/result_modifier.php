<?php
/*
 * Изменено: 15 декабря 2021, среда
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

/**
 * @var array $arResult
 */
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die;
foreach ($arResult['FIELDS'] as &$field) {
	if ($field['CODE'] === 'THEME_FID2' && $field['ENUM']) {
		foreach ($field['ENUM'] as $i => $item) {
			// Уберем тему Поездка в Кедровый Дом
			if ($item['XML_ID'] === '7c92dda82f6e0aa78ca60b5af2aa41ba') {
				unset($field['ENUM'][$i]);
			}
		}
	}
}