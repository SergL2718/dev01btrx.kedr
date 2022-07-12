<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$this->setFrameMode(true);

Alexkova\Bxready\Draw::getInstance()->setCurrentTemplate($this);

if ($arParams["BXREADY_LIST_SLIDER"] == "Y") {
    $this->addExternalJS(SITE_TEMPLATE_PATH . '/js/slick/slick.js');
    $this->addExternalCss(SITE_TEMPLATE_PATH . '/js/slick/slick.css', false);
}

// Доработка функционала
// Скрываем товар из каталога
// https://megre.bitrix24.ru/workgroups/group/9/tasks/task/view/10803/
$GLOBALS[$arParams['FILTER_NAME']]['PROPERTY_HIDDEN'] = false;
// https://megre.bitrix24.ru/workgroups/group/9/tasks/task/view/21639/
$GLOBALS[$arParams['FILTER_NAME']]['PROPERTY_DUPLICATED'] = false;

$arParams['USE_PRICE_COUNT'] = 'N';
$APPLICATION->IncludeComponent(
    "bitrix:catalog.section",
    "",
    $arParams,
    $component,
    array("HIDE_ICONS" => "Y")
);
