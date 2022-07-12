<?php
/*
 * Изменено: 26 декабря 2021, воскресенье
 * Автор: Артамонов Денис <artamonov.d.i@yandex.com>
 * copyright (c) 2021
 */

use Bitrix\Main\PhoneNumber\Format as PhoneNumberFormat;
use Bitrix\Main\PhoneNumber\Parser as PhoneParser;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die;

class StoreListComponent extends CBitrixComponent
{
	public function executeComponent ()
	{
		$this->setFrameMode(false);
		if ($this->StartResultCache()) {
			$this->createResult();
			$this->includeComponentTemplate();
		}
	}

	private function createResult ()
	{
		$arParams = &$this->arParams;
		$arResult = &$this->arResult;
		if (!$arParams['IBLOCK_ID']) {
			return;
		}
		$result = [];
		$cache = new CPHPCache();
		if ($cache->InitCache(8640000, 'store_list', '/' . SITE_ID . '/native/store.list/')) {
			$result = $cache->GetVars();
		} else if ($cache->StartDataCache()) {
			Bitrix\Main\Loader::IncludeModule('iblock');
			$countries = [];
			$items = \CIBlockElement::GetList([
				'SORT' => 'ASC',
				'CITY' => 'ASC',
			],
				['ACTIVE' => 'Y', 'IBLOCK_ID' => $arParams['IBLOCK_ID']],
				false,
				[],
				[
					'ID',
					'NAME',
					'PREVIEW_TEXT',
					'SORT',
					'IBLOCK_SECTION_ID',
					'PROPERTY_ZIP',
					'PROPERTY_CITY',
					'PROPERTY_ADDRESS',
					'PROPERTY_PHONE',
					'PROPERTY_EMAIL',
					'PROPERTY_WWW',
					'PROPERTY_TC',
					'PROPERTY_TIME',
				]);
			while ($item = $items->fetch()) {
				$point = [
					'ID'              => $item['ID'],
					'NAME'            => $item['NAME'],
					'DESCRIPTION'     => $item['PREVIEW_TEXT'],
					'SHOPPING_CENTER' => $item['PROPERTY_TC_VALUE'],
					'SCHEDULE'        => $item['PROPERTY_TIME_VALUE'],
					'EMAIL'           => $item['PROPERTY_EMAIL_VALUE'],
					'WWW'             => $item['PROPERTY_WWW_VALUE'],
					'ZIP'             => $item['PROPERTY_ZIP_VALUE'],
					'CITY'            => $item['PROPERTY_CITY_VALUE'],
					'ADDRESS'         => $item['PROPERTY_ADDRESS_VALUE'],
				];
				foreach ($item['PROPERTY_PHONE_VALUE'] as $key => $phone) {
					$parsedPhone = PhoneParser::getInstance()->parse($phone);
					if ($parsedPhone->isValid()) {
						$point['PHONE'][$key] = [
							'VALUE'     => $parsedPhone->format(PhoneNumberFormat::E164),
							'FORMATTED' => $parsedPhone->format($parsedPhone->getCountryCode() === 'RU' || $parsedPhone->getCountryCode() === 'KZ' ? PhoneNumberFormat::NATIONAL : PhoneNumberFormat::INTERNATIONAL),
						];
					} else {
						$point['PHONE'][$key] = [
							'FORMATTED' => trim($phone),
						];
					}
					if ($item['PROPERTY_PHONE_DESCRIPTION'][$key]) {
						$point['PHONE'][$key]['DESCRIPTION'] = trim($item['PROPERTY_PHONE_DESCRIPTION'][$key]);
					}
				}
				if (!$country = $countries[$item['IBLOCK_SECTION_ID']]) {
					$country = \CIBlockSection::GetNavChain(false, $item['IBLOCK_SECTION_ID'])->fetch()['NAME'];
				}
				$point['WWW'] = str_replace('http://', 'https://', $point['WWW']);
				$point['WWW'] = str_replace('www.', '', $point['WWW']);
				$point['WWW'] = trim($point['WWW'], '/');
				if (!empty($point['WWW']) && strpos($point['WWW'], 'https://') === false) {
					$point['WWW'] = 'https://' . $point['WWW'];
				}
				$point['COUNTRY'] = $country;
				$id = $point['SORT'] . '_' . $point['ID'];
				$searchCountry = trim(mb_strtolower($point['COUNTRY']));
				$searchCity = str_replace(['г.', 'г .'], '', trim(mb_strtolower($point['CITY'])));
				$result['search']['country'][$searchCountry][$id] = $point;
				$result['search']['city'][$searchCity][$id] = $point;
			}
			$cache->EndDataCache($result);
		}
		if (isset($result['search'])) {
			$arResult['JS'] = \CUtil::PhpToJSObject($result['search']);
		} else {
			$arResult['JS'] = [];
		}
	}
}
