<?php
/*
 * Изменено: 29 сентября 2021, среда
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$path = \Bitrix\Main\IO\Path::normalize(__DIR__);
$path = '/bitrix/' . substr($path, strpos($path, 'components/bitrix'));
\CJSCore::RegisterExt('main_user_consent', [
	'js'   => $path . '/user_consent.js',
	'css'  => $path . '/user_consent.css',
	'lang' => $path . '/user_consent.php',
	'rel'  => [],
]);
CUtil::InitJSCore(['popup', 'ajax', 'main_user_consent']);