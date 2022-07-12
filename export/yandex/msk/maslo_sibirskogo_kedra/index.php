<?php
/*
 * Изменено: 12 октября 2021, вторник
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
		'STORE_CODE'           => Native\App\Sale\Location::MSK,
		'SECTION_CODE'         => [
			'maslo_sibirskogo_kedra',
			'kedrovyy_orekh',
		],
		'EXCLUDE_ELEMENT_CODE' => [
			'sorbent_kedrovyy_molotaya_skorlupa_100_g_doy_pak',
			'eliksir_megre_mumiye_100_ml',
			'eliksir_megre_koren_imbirya_100_ml',
		],
	];
	YandexMarket::export($arParams);
}
catch (ObjectPropertyException | SystemException | LoaderException $e) {
}
