<?php
/*
 * Изменено: 19 ноября 2021, пятница
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die;

class StaticBlockComponent extends CBitrixComponent
{
	public function executeComponent ()
	{
		$this->setFrameMode(true);
		$arParams =& $this->arParams;
		if ($arParams['CACHE_TIME'] > 0) {
			if ($this->startResultCache()) {
				$this->setResultCacheKeys([]);
				$this->includeComponentTemplate();
			}
			return;
		}
		$this->includeComponentTemplate();
	}

	public function onPrepareComponentParams ($arParams): array
	{
		if (!isset($arParams['CACHE_TIME'])) {
			$arParams['CACHE_TIME'] = 86400000;
		}
		return $arParams;
	}
}