<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$this->setFrameMode(true);

$APPLICATION->IncludeComponent(
        "bitrix:catalog.section",
        "",
        $arParams,
        $component,
        array("HIDE_ICONS"=>"Y")
);

    