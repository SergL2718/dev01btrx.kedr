<?php
/*
 * Изменено: 26 декабря 2021, воскресенье
 * Автор: Артамонов Денис <artamonov.d.i@yandex.com>
 * copyright (c) 2021
 */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die;

/**
 * @deprecated since 2021-12-14
 */
class SearchSalesPointComponent extends CBitrixComponent
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
		$arResult = &$this->arResult;
		Bitrix\Main\Loader::IncludeModule('iblock');
		$result = [];
		$countries = [];
		$order = [
			'SORT' => 'ASC',
			'CITY' => 'ASC',
		];
		$select = [
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
		];
		$filter = ['ACTIVE' => 'Y', 'IBLOCK_ID' => 53];
		$items = \CIBlockElement::GetList($order, $filter, false, [], $select);
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
				if ($item['PROPERTY_PHONE_DESCRIPTION'][$key]) {
					$phone .= ' [' . $item['PROPERTY_PHONE_DESCRIPTION'][$key] . ']';
				}
				$point['PHONE'][] = $phone;
			}
			if (!$country = $countries[$item['IBLOCK_SECTION_ID']]) {
				$country = \CIBlockSection::GetNavChain(false, $item['IBLOCK_SECTION_ID'])->fetch()['NAME'];
			}
			$point['COUNTRY'] = $country;
			$id = $point['SORT'] . '_' . $point['ID'];
			$searchCountry = trim(strtolower($point['COUNTRY']));
			$searchCity = mb_strtolower($point['CITY']);
			$searchCity = str_replace(['г.', 'г .'], '', $searchCity);
			$searchCity = trim($searchCity);
			$result['search']['country'][$searchCountry][$id] = $point;
			$result['search']['city'][$searchCity][$id] = $point;
		}
		$arResult['JS'] = \CUtil::PhpToJSObject($result['search']);
	}
}
