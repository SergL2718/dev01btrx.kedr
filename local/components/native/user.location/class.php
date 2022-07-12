<?php
/*
 * Изменено: 10 сентября 2021, пятница
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

use Native\App\Sale\Location;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die;

/**
 * @deprecated
 */
class UserLocationComponent extends CBitrixComponent
{
    public function executeComponent()
    {
        $this->setFrameMode(true);
        $this->prepareComponentResult();
        $this->IncludeComponentTemplate();
    }

    private function prepareComponentResult()
    {
        $arResult =& $this->arResult;
        $arResult['COOKIE'] = Location::COOKIE_CITY_CODE;
        $arResult['LIST'] = Location::getList();
        $arResult['LOCATION'] = Location::getList()[mb_strtoupper(Location::getCurrentCityCode())];
        $this->setResultCacheKeys([]);
    }
}