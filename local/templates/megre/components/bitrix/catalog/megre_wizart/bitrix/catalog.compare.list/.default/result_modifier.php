<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/*
 * Изменено: 13 сентября 2021, понедельник
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

$defaultParams = [
	'POSITION_FIXED' => 'Y',
	'POSITION'       => 'top left',
];

$arParams = array_merge($defaultParams, $arParams);
unset($defaultParams);
if ($arParams['POSITION_FIXED'] != 'N')
	$arParams['POSITION_FIXED'] = 'Y';

if (!isset($arParams['POSITION']))
	$arParams['POSITION'] = '';
if (!is_array($arParams['POSITION']))
	$arParams['POSITION'] = explode(' ', trim($arParams['POSITION']));

if (empty($arParams['POSITION']) || count($arParams['POSITION']) != 2)
	$arParams['POSITION'] = ['top', 'left'];
if ($arParams['POSITION'][0] != 'bottom')
	$arParams['POSITION'][0] = 'top';
if ($arParams['POSITION'][1] != 'right')
	$arParams['POSITION'][1] = 'left';