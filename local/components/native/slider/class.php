<?php
/*
 * Изменено: 17 сентября 2021, пятница
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

use Bitrix\Iblock\ElementTable;
use Bitrix\Iblock\Iblock;
use Bitrix\Main\Config\Option;
use Native\App\Sale\Location;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die;

class SliderComponent extends CBitrixComponent
{
	public function executeComponent ()
	{
		$arParams =& $this->arParams;
		if (!$arParams['IBLOCK_ID']) {
			return;
		}
		$this->setFrameMode(true);
		$this->prepareComponentResult();
		$this->IncludeComponentTemplate();
	}

	public function onPrepareComponentParams ($arParams): array
	{
		$this->getCacheParams($arParams);
		return $arParams;
	}

	private function prepareComponentResult ()
	{
		$arParams =& $this->arParams;
		$arResult =& $this->arResult;
		$cache = new CPHPCache();
		$ttl = $arParams['CACHE_TIME'] ? : (($arParams['CACHE_TIMESTAMP'] - time()) > 0 ? $arParams['CACHE_TIMESTAMP'] - time() : 0);
		if ($cache->InitCache($ttl, $this->getCacheID(), SITE_ID . $this->getRelativePath())) {
			$arResult = $cache->GetVars();
		} else if ($cache->StartDataCache()) {
			$date = new \Bitrix\Main\Type\DateTime();
			$location = Location::getCurrent();
			$filter = [
				'=IBLOCK_ID'    => $arParams['IBLOCK_ID'],
				'=ACTIVE'       => 'Y',
				'<=ACTIVE_FROM' => $date,
				[
					'LOGIC' => 'OR',
					[
						'>=ACTIVE_TO' => $date,
					],
					[
						'=ACTIVE_TO' => false,
					],
				],
			];
			if ($location['CODE'] === Location::MSK || $location['CODE'] === Location::NSK) {
				$filter[] = [
					'LOGIC' => 'OR',
					[
						'=REGION_VALUE' => $location['CODE'],
					],
					[
						'=REGION_VALUE' => false,
					],
				];
			} else {
				$filter['=REGION_VALUE'] = false;
			}
			$elementTable = Iblock::wakeUp($arParams['IBLOCK_ID'])->getEntityDataClass();
			$r = $elementTable::getList([
				'select' => [
					'ID',
					'REGION_VALUE' => 'REGION.ITEM.XML_ID',
					'URL_VALUE'    => 'URL.VALUE',
					'TARGET_VALUE' => 'TARGET.ITEM.XML_ID',
					'1920_NAME'    => 'IMAGE_1920.FILE.FILE_NAME',
					'1920_PATH'    => 'IMAGE_1920.FILE.SUBDIR',
					'1600_NAME'    => 'IMAGE_1600.FILE.FILE_NAME',
					'1600_PATH'    => 'IMAGE_1600.FILE.SUBDIR',
					'992_NAME'     => 'IMAGE_992.FILE.FILE_NAME',
					'992_PATH'     => 'IMAGE_992.FILE.SUBDIR',
					'768_NAME'     => 'IMAGE_768.FILE.FILE_NAME',
					'768_PATH'     => 'IMAGE_768.FILE.SUBDIR',
					'375_NAME'     => 'IMAGE_375.FILE.FILE_NAME',
					'375_PATH'     => 'IMAGE_375.FILE.SUBDIR',
				],
				'filter' => $filter,
				'order'  => [
					'SORT'        => 'ASC',
					'ACTIVE_FROM' => 'DESC',
				],
			]);
			if ($r->getSelectedRowsCount() === 0) {
				$cache->AbortDataCache();
				return;
			}
			while ($a = $r->fetch()) {
				$arResult['ITEMS'][$a['ID']]['URL'] = $a['URL_VALUE'];
				$arResult['ITEMS'][$a['ID']]['TARGET'] = $a['TARGET_VALUE'];
				$arResult['ITEMS'][$a['ID']]['IMAGE']['1920'] = '/' . Option::get('main', 'upload_dir', 'upload') . '/' . $a['1920_PATH'] . '/' . $a['1920_NAME'];
				$arResult['ITEMS'][$a['ID']]['IMAGE']['1600'] = '/' . Option::get('main', 'upload_dir', 'upload') . '/' . $a['1600_PATH'] . '/' . $a['1600_NAME'];
				$arResult['ITEMS'][$a['ID']]['IMAGE']['992'] = '/' . Option::get('main', 'upload_dir', 'upload') . '/' . $a['992_PATH'] . '/' . $a['992_NAME'];
				$arResult['ITEMS'][$a['ID']]['IMAGE']['768'] = '/' . Option::get('main', 'upload_dir', 'upload') . '/' . $a['768_PATH'] . '/' . $a['768_NAME'];
				$arResult['ITEMS'][$a['ID']]['IMAGE']['375'] = '/' . Option::get('main', 'upload_dir', 'upload') . '/' . $a['375_PATH'] . '/' . $a['375_NAME'];
			}
			$arResult['UNIQUE_ID'] = md5($this->getName() . $this->randString());
			$cache->EndDataCache($arResult);
		}
	}

	private function getCacheParams (&$arParams): void
	{
		if ($arParams['CACHE_TIME']) {
			return;
		}
		$arParams['CACHE_TIME'] = 0;
		$arParams['CACHE_TIMESTAMP'] = 2556133199; // 31.12.2050 23:59:59
		$arParams['COMPARED'] = 0;
		$arParams['LAST_UPDATED'] = 0;
		$location = Location::getCurrent();
		if ($location['CODE'] === Location::MSK || $location['CODE'] === Location::NSK) {
			$arParams['REGION'] = $location['CODE'];
		} else {
			$arParams['REGION'] = Location::OTHER;
		}
		$date = new \Bitrix\Main\Type\DateTime();
		$time = [];
		$r = ElementTable::getList([
			'select' => [
				'TIMESTAMP_X',
				'ACTIVE_FROM',
				'ACTIVE_TO',
			],
			'filter' => [
				'=IBLOCK_ID' => $arParams['IBLOCK_ID'],
				[
					'LOGIC' => 'OR',
					['!ACTIVE_FROM' => false],
					['!ACTIVE_TO' => false],
				],
			],
			'order'  => [
				'TIMESTAMP_X' => 'desc',
				'ACTIVE_FROM' => 'desc',
				'ACTIVE_TO'   => 'desc',
			],
			'limit'  => 15,
		]);
		while ($a = $r->fetch()) {
			if ($a['ACTIVE_FROM'] && $a['ACTIVE_FROM']->getTimestamp() > $date->getTimestamp()) {
				$time[] = $a['ACTIVE_FROM']->getTimestamp();
			}
			if ($a['ACTIVE_TO']) {
				$time[] = $a['ACTIVE_TO']->getTimestamp();
			}
			if ($a['TIMESTAMP_X'] && $a['TIMESTAMP_X']->getTimestamp() > $arParams['LAST_UPDATED']) {
				$arParams['LAST_UPDATED'] = $a['TIMESTAMP_X']->getTimestamp();
			}
		}
		if (!empty($time)) {
			sort($time);
			// Ближайшая временная метка, до которой может быть актуален кэш
			foreach ($time as $t) {
				if ($t <= time()) {
					continue;
				}
				$arParams['CACHE_TIMESTAMP'] = $t;
				$arParams['COMPARED'] = $r->getSelectedRowsCount();
				break;
			}
		}
	}
}