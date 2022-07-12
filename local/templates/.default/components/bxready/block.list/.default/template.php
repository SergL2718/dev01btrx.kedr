<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
if ($arParams["BXREADY_LIST_SLIDER"] == "Y") {
    $this->addExternalJS(SITE_TEMPLATE_PATH . '/js/slick/slick.js');
    $this->addExternalCss(SITE_TEMPLATE_PATH . '/js/slick/slick.css', false);
}
$APPLICATION->IncludeComponent(
    "bitrix:news.list",
    "",
    $arParams,
    $component,
    array("HIDE_ICONS" => "Y")
);
?>
<div class="clearfix"></div>