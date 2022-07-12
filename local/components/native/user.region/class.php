<?php
/*
 * Изменено: 18 сентября 2021, суббота
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

use Bitrix\Main\Loader;
use Bitrix\Sale\Location\GeoIp;
use Bitrix\Sale\Location\LocationTable;
use Native\App\Sale\Location;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die;

class UserRegionComponent extends CBitrixComponent
{
	public function executeComponent ()
	{
		$this->setFrameMode(true);
		$this->prepareComponentResult();
		$this->IncludeComponentTemplate();
	}

	public function onPrepareComponentParams ($arParams): array
	{
		if (!$arParams['CACHE_TIME']) {
			$arParams['CACHE_TIME'] = 86400000;
		}
		return $arParams;
	}

	private function prepareComponentResult ()
	{
		$arResult =& $this->arResult;
		$this->getLocationList();
		$this->getCurrentLocation();
	}

	private function getLocationList ()
	{
		$arParams =& $this->arParams;
		$arResult =& $this->arResult;
		$cache = new CPHPCache();
		if ($cache->InitCache($arParams['CACHE_TIME'], $this->getCacheID(), SITE_ID . $this->getRelativePath())) {
			$arResult = $cache->GetVars();
		} else if ($cache->StartDataCache()) {
			Loader::includeModule('sale');
			$r = LocationTable::getList([
				'select' => [
					'ID',
					'CODE',
					'NAME_VALUE' => 'NAME.NAME',
					'ISO_CODE'   => 'NAME.SHORT_NAME',
					'TYPE_CODE'  => 'TYPE.CODE',
					'COUNTRY_ID',
					'REGION_ID',
					'CITY_ID',
					'PARENT_ID',
				],
				'filter' => [
					'=TYPE_CODE'        => ['COUNTRY', 'REGION', 'CITY', 'VILLAGE'],
					'=NAME.LANGUAGE_ID' => LANGUAGE_ID,
					'!CODE'             => Location::OTHER,
				],
				'order'  => [
					'DEPTH_LEVEL' => 'asc',
					'SORT'        => 'asc',
					'NAME_VALUE'  => 'asc',
				],
				'cache'  => [
					'ttl' => $arParams['CACHE_TIME'],
				],
			]);
			while ($a = $r->fetchRaw()) {
				if ($a['TYPE_CODE'] === 'COUNTRY') {
					unset(
						$a['COUNTRY_ID'],
						$a['REGION_ID'],
						$a['CITY_ID'],
						$a['PARENT_ID']
					);
				} else if ($a['TYPE_CODE'] === 'REGION') {
					unset(
						$a['REGION_ID'],
						$a['CITY_ID']
					);
				} else if ($a['TYPE_CODE'] === 'CITY') {
					if (!$a['REGION_ID']) {
						unset($a['REGION_ID']);
					}
					unset(
						$a['CITY_ID']
					);
				}
				$a['TYPE'] = $a['TYPE_CODE'];
				$a['NAME'] = $a['NAME_VALUE'];
				$a['ISO_CODE'] = $a['ISO_CODE'] ? trim(mb_strtoupper($a['ISO_CODE'])) : Native\App\Sale\Location::OTHER;
				unset(
					$a['TYPE_CODE'],
					$a['NAME_VALUE']
				);
				if ($a['TYPE'] === 'VILLAGE') {
					$a['TYPE'] = 'CITY';
				}
				if ($a['TYPE'] === 'COUNTRY') {
					$arResult['LOCATION']['COUNTRY'][$a['ID']] = $a;
					// Данные для обработки в JS
					$arResult['JS']['LOCATION']['COUNTRY'][$a['ISO_CODE']] = $a;
				} else if ($a['TYPE'] === 'REGION') {
					$country =& $arResult['LOCATION']['COUNTRY'][$a['COUNTRY_ID']];
					$country['REGION'][$a['ID']] = $a;
					// Данные для обработки в JS
					$arResult['JS']['LOCATION']['COUNTRY'][$country['ISO_CODE']]['REGION'][$a['ID']] = $a;
				} else {
					$country =& $arResult['LOCATION']['COUNTRY'][$a['COUNTRY_ID']];
					$region =& $country['REGION'][$a['REGION_ID']];
					$country['CITY'][$a['ID']] = $a;
					// Количество локаций (городов) которые будут выводиться в ТОП у каждой страны
					if (count($country['TOP_CITY']) < 9) {
						$country['TOP_CITY'][$a['ID']] = $a;
					}
					// Данные для обработки в JS
					$arResult['JS']['LOCATION']['COUNTRY'][$country['ISO_CODE']]['CITY'][$a['ID']] = $a;
					// Данные для JS поиска
					$search = $a['NAME'];
					if ($region['NAME']) {
						$search .= ', ' . $region['NAME'];
					}
					$arResult['JS']['SEARCH']['DATA'][$country['ISO_CODE']][mb_strtoupper($search)] = [
						'NAME'    => $search,
						'ID'      => $a['ID'],
						'COUNTRY' => [
							'CODE' => $country['ISO_CODE'],
						],
					];
					// Индексированный массив для быстрого поиска в JS
					$arResult['JS']['SEARCH']['INDEX'][$country['ISO_CODE']][] = mb_strtoupper($search);
					// Сразу считаем количество элементов, чтобы позже использовать при JS поиске
					$arResult['JS']['SEARCH']['LENGTH'][$country['ISO_CODE']]++;
				}
			}
			$arResult[Location::OTHER] = Location::getOther();
			// Данные для обработки в JS
			$arResult['JS']['LOCATION'][Location::OTHER] = $arResult[Location::OTHER];
			// Уникальный ID текущего компонента
			$arResult['UNIQUE_ID'] = md5($this->getName() . $this->randString() . $this->getTemplateName());
			$cache->EndDataCache($arResult);
		}
	}

	private function getCurrentLocation ()
	{
		$arResult =& $this->arResult;
		if (!empty(Location::getCurrent())) {
			$this->setCurrentLocation(Location::getCurrent());
		} else {
			Loader::includeModule('sale');
			$locationId = GeoIp::getLocationId();
			if ($locationId) {
				$r = LocationTable::getList([
					'select' => [
						'ID',
						'CODE',
						'NAME_VALUE' => 'NAME.NAME',
						'ISO_CODE'   => 'NAME.SHORT_NAME',
						'COUNTRY_ID',
					],
					'filter' => [
						'=ID'               => $locationId,
						'=NAME.LANGUAGE_ID' => LANGUAGE_ID,
					],
					'limit'  => 1,
				]);
				if ($r->getSelectedRowsCount() > 0) {
					$r = $r->fetchRaw();
					$r['NAME'] = $r['NAME_VALUE'];
					unset($r['NAME_VALUE']);
					$this->setCurrentLocation($r);
				}
			}
			if (empty($arResult['CURRENT'])) {
				$this->setCurrentLocation($arResult[Location::OTHER]);
			}
		}
	}

	private function setCurrentLocation (array $location)
	{
		$arResult =& $this->arResult;
		$arResult['CURRENT'] = [
			'ID'      => $location['ID'],
			'CODE'    => $location['ISO_CODE'] ? trim(mb_strtoupper($location['ISO_CODE'])) : ($location['CODE'] ? : Location::OTHER),
			'NAME'    => $location['NAME'] ? : '',
			'COUNTRY' => [
				'ID'   => $location['COUNTRY_ID'] ? : ($location['COUNTRY']['ID'] ? : ''),
				'CODE' => $arResult['LOCATION']['COUNTRY'][$location['COUNTRY_ID']]['ISO_CODE'] ? : ($location['COUNTRY']['CODE'] ? : ''),
			],
		];
		$_COOKIE[Location::getCookieName()] = json_encode($arResult['CURRENT']);
		setcookie(Location::getCookieName(), $_COOKIE[Location::getCookieName()], time() + 864000000, '/');
	}
}