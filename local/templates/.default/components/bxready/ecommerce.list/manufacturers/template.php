<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/*
 * Изменено: 29 декабря 2021, среда
 * Автор: Артамонов Денис <artamonov.d.i@yandex.com>
 * copyright (c) 2022
 */

$this->setFrameMode(true);

$APPLICATION->IncludeComponent(
        "bitrix:catalog.section",
        "",
        $arParams,
        $component,
        array("HIDE_ICONS"=>"Y")
);

    