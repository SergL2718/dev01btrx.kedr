<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

use Alexkova\Bxready2\Draw;
$elementDraw = \Alexkova\Bxready2\Draw::getInstance($this);
$elementDraw->setAdditionalFile("JS", "/bitrix/tools/bxready2/collection/bxr_elements/ecommerce.m2.small.v1/include/script.js", true);
$elementDraw->setAdditionalFile("CSS", "/bitrix/tools/bxready2/collection/bxr_elements/ecommerce.m2.small.v1/include/style.css", false);
//$APPLICATION->SetAdditionalCSS("/bitrix/components/bxready.market2/catalog.product.subscribe/templates/.default/style.css");
//$APPLICATION->AddHeadScript("/bitrix/components/bxready.market2/catalog.product.subscribe/templates/.default/script.js");
?>