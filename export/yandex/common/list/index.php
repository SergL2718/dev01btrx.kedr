<?php
/*
 * Изменено: 03 декабря 2021, пятница
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

/*
 * Фид для яндекс-маркета
 * Выборка осуществляется только по новосибирскому складу и для категории Масло сибирского кедра
 */


use Bitrix\Main\LoaderException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;
use Native\App\Provider\YandexMarket;

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';

try {
	$arParams = [
		'ELEMENT_ID' => [
			7954,
			7955,
			7956,
			8147,
			9515,
			9780,
			10085,
			10195,
			108052,
			116992,
			132259,
			138307,
			110550,
			7957,
			7977,
			7978,
			7979,
			8172,
			8173,
			8174,
			8175,
			8189,
			8190,
			8191,
			8192,
			110362,
			110363,
			110364,
			7960,
			7961,
			7962,
			110907,
		],
	];
	YandexMarket::export($arParams);
}
catch (ObjectPropertyException | SystemException | LoaderException $e) {
}