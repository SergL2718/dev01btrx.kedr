<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

use Alexkova\Bxready2\Draw;
$elementDraw = \Alexkova\Bxready2\Draw::getInstance($this);
$elementDraw->setAdditionalFile("JS", "/bitrix/tools/bxready2/collection/bxr_elements/ecommerce.m2.big.v1/include/script.js", true);
$elementDraw->setAdditionalFile("CSS", "/bitrix/tools/bxready2/collection/bxr_elements/ecommerce.m2.big.v1/include/style.css", false);
$elementDraw->setAdditionalFile("JS", "/bitrix/js/alexkova.bxready2/slick/slick.js", true);
$elementDraw->setAdditionalFile("CSS", "/bitrix/js/alexkova.bxready2/slick/slick.css", false);
$elementDraw->setAdditionalFile("JS", "/bitrix/js/alexkova.bxready2/countdown/countdown.js", true);
$elementDraw->setAdditionalFile("CSS", "/bitrix/js/alexkova.bxready2/countdown/countdown.css", false);
if (isset($arElementParams["BXR_PRESENT_SETTINGS"]["BXREADY_LIST_MARKER_TYPE"]) && strlen($arElementParams["BXR_PRESENT_SETTINGS"]["BXREADY_LIST_MARKER_TYPE"])>0)
    $elementDraw->setMarkerCollection($arElementParams["BXR_PRESENT_SETTINGS"]["BXREADY_LIST_MARKER_TYPE"]);
$elementDraw->showMarkerGroup(array(), true);
//$APPLICATION->SetAdditionalCSS("/bitrix/components/bxready.market2/catalog.product.subscribe/templates/.default/style.css");
//$APPLICATION->AddHeadScript("/bitrix/components/bxready.market2/catalog.product.subscribe/templates/.default/script.js");
?>
