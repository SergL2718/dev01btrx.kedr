<?php
/*
 * Изменено: 10 ноября 2021, среда
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die;
$arComponentDescription = [
	"NAME"        => 'Статический блок',
	"DESCRIPTION" => 'Блок для группировки кода',
	"SORT"        => 10,
	"CACHE_PATH"  => "Y",
	"PATH"        => [
		"ID"    => "native",
		"NAME"  => 'Нативные компоненты',
		"SORT"  => 10,
		"CHILD" => [
			"ID"   => "include_area",
			"NAME" => 'Встраиваемые области',
			"SORT" => 10,
		],
	],
];