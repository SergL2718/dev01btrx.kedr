<?php
/*
 * Изменено: 24 January 2022, Monday
 * Автор: Артамонов Денис <artamonov.d.i@yandex.com>
 * copyright (c) 2022
 */

/**
 * @global CMain $APPLICATION
 */

use Bitrix\Iblock\Component\Tools;
use Bitrix\Iblock\ElementTable;
use Bitrix\Iblock\SectionTable;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;
use Bitrix\Main\UI\PageNavigation;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die;

class ArticleListComponent extends CBitrixComponent
{
	/**
	 * @return void
	 * @throws ArgumentException
	 * @throws ObjectPropertyException
	 * @throws SystemException
	 */
	public function executeComponent ()
	{
		$this->setFrameMode(true);
		$this->prepareComponentResult();
		$this->IncludeComponentTemplate();
	}

	/**
	 * @return void
	 * @throws ArgumentException
	 * @throws ObjectPropertyException
	 * @throws SystemException
	 */
	private function prepareComponentResult ()
	{
		global $APPLICATION;
		$arParams =& $this->arParams;
		$arResult =& $this->arResult;
		$nav = new PageNavigation('pagination');
		$nav->allowAllRecords(false)->setPageSize($arParams['COUNT_PER_PAGE'])->initFromUri();
		$currentDir = $APPLICATION->GetCurDir();
		$currentSection = '';
		if (strpos($currentDir, 'articles/sovety-dietologa') !== false) {
			$currentSection = 'sovety-dietologa';
		} else if (strpos($currentDir, 'articles/blog') !== false) {
			$currentSection = 'blog';
		} else if (strpos($currentDir, 'articles/retsepty') !== false) {
			$currentSection = 'retsepty';
		} else if (strpos($currentDir, '/news/') !== false) {
			$currentSection = 'news';
		}
		if (empty($currentSection)) {
			if ($nav->getCurrentPage() === 1 && $currentDir !== '/articles/') {
				LocalRedirect('/articles/');
			} else if ($nav->getCurrentPage() > 1 && $currentDir !== '/articles/pagination/page-' . $nav->getCurrentPage() . '/') {
				LocalRedirect('/articles/pagination/page-' . $nav->getCurrentPage() . '/');
			}
		} else {
			if ($currentSection !== 'news') {
				if ($nav->getCurrentPage() === 1 && $currentDir !== '/articles/' . $currentSection . '/') {
					LocalRedirect('/articles/' . $currentSection . '/');
				} else if ($nav->getCurrentPage() > 1 && $currentDir !== '/articles/' . $currentSection . '/pagination/page-' . $nav->getCurrentPage() . '/') {
					LocalRedirect('/articles/' . $currentSection . '/pagination/page-' . $nav->getCurrentPage() . '/');
				}
			}
		}
		$date = new \Bitrix\Main\Type\DateTime();
		// Разделы
		$arResult['SECTIONS']['news'] = [
			'CODE'            => 'news',
			'NAME'            => 'Новости',
			'DETAIL_PAGE_URL' => '/news/',
		];
		$r = SectionTable::getList([
			'select' => [
				'ID',
				'CODE',
				'NAME',
			],
			'filter' => [
				'=IBLOCK_ID' => [
					15, // Новости
					16, // Статьи
				],
			],
			'order'  => [
				'SORT' => 'ASC',
				'NAME' => 'ASC',
			],
			'cache'  => [
				'ttl' => $arParams['CACHE_TIME'],
			],
		]);
		while ($a = $r->fetch()) {
			$a['DETAIL_PAGE_URL'] = '/articles/' . $a['CODE'] . '/';
			if ($a['CODE'] === 'sovety-dietologa') {
				$a['NAME'] = 'Здоровье';
			}
			if (!empty($currentSection) && $a['CODE'] === $currentSection) {
				$APPLICATION->SetTitle($a['NAME']);
				$APPLICATION->SetPageProperty('title', $a['NAME']);
				$APPLICATION->SetDirProperty('title', $a['NAME']);
				$APPLICATION->AddChainItem($a['NAME']);
			}
			$arResult['SECTIONS'][$a['ID']] = $a;
		}
		// Элементы
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
		if (is_array($arParams['IBLOCK_ID'])) {
			$filter[] = [
				'LOGIC' => 'OR',
				[
					'=IBLOCK.CODE'         => 'news',
					'=IBLOCK_SECTION.CODE' => false,
				],
				[
					'=IBLOCK.CODE'          => 'articles',
					'!=IBLOCK_SECTION.CODE' => false,
				],
			];
		} else if ($arParams['IBLOCK_ID'] == 15) {
			$filter['=IBLOCK.CODE'] = 'news';
			$filter['=IBLOCK_SECTION.CODE'] = false;
		} else {
			$filter['=IBLOCK.CODE'] = 'articles';
			$filter['!=IBLOCK_SECTION.CODE'] = false;
		}
		if (!empty($currentSection)) {
			$filter['=IBLOCK_SECTION.CODE'] = $currentSection;
		}
		$r = ElementTable::getList([
			'select'      => [
				'IBLOCK_CODE' => 'IBLOCK.CODE',
				'IBLOCK_SECTION_ID',
				'ID',
				'NAME',
				'CODE',
				'PREVIEW_PICTURE',
				'PREVIEW_TEXT',
			],
			'filter'      => $filter,
			'order'       => [
				'ACTIVE_FROM' => 'DESC',
				'ID'          => 'DESC',
				'SORT'        => 'ASC',
			],
			'count_total' => true,
			'offset'      => $nav->getOffset(),
			'limit'       => $nav->getLimit(),
			'cache'       => [
				'ttl' => $arParams['CACHE_TIME'],
			],
		]);
		if ($r->getSelectedRowsCount() === 0) {
			if ($nav->getCurrentPage() > 1) {
				Tools::process404('Not Found', true, true, true);
			}
			return;
		}
		while ($a = $r->fetchRaw()) {
			if (!$a['IBLOCK_SECTION_ID']) {
				$a['IBLOCK_SECTION_ID'] = 'news';
			}
			if (!empty($a['PREVIEW_PICTURE'])) {
				$a['PREVIEW_PICTURE'] = [
					'ID'  => $a['PREVIEW_PICTURE'],
					'SRC' => \CFile::ResizeImageGet($a['PREVIEW_PICTURE'], ['width' => 400, 'height' => 320], BX_RESIZE_IMAGE_EXACT)['src'],
				];
			}
			if (!empty($a['NAME']) && mb_strlen($a['NAME']) > 120) {
				$a['NAME'] = mb_strimwidth($a['NAME'], 0, 120);
			}
			if (!empty($a['PREVIEW_TEXT']) && mb_strlen($a['PREVIEW_TEXT']) > 200) {
				$a['PREVIEW_TEXT'] = mb_strimwidth($a['PREVIEW_TEXT'], 0, 200);
			}
			if ($a['IBLOCK_CODE'] === 'news') {
				$a['DETAIL_PAGE_URL'] = '/news/' . $a['CODE'] . '/';
			} else {
				$a['DETAIL_PAGE_URL'] = '/articles/' . $arResult['SECTIONS'][$a['IBLOCK_SECTION_ID']]['CODE'] . '/' . $a['CODE'] . '/';
			}
			$arResult['ITEMS'][] = $a;
		}
		// Навигация
		$nav->setRecordCount($r->getCount());
		$arResult['NAV'] = $nav;
	}

	/**
	 * @param $arParams
	 *
	 * @return array
	 */
	public function onPrepareComponentParams ($arParams): array
	{
		if (!$arParams['IBLOCK_ID']) {
			$arParams['IBLOCK_ID'] = [
				15, // Новости
				16, // Статьи
			];
		}
		if (!$arParams['COUNT_PER_PAGE']) {
			$arParams['COUNT_PER_PAGE'] = 9;
		}
		if (!$arParams['CACHE_TIME']) {
			$arParams['CACHE_TIME'] = 86400000;
		}
		return $arParams;
	}
}