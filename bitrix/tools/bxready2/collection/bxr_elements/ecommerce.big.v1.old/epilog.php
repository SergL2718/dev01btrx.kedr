<?
global $APPLICATION;

$dirName = str_replace($_SERVER["DOCUMENT_ROOT"],'', dirname(__FILE__));
$APPLICATION->SetAdditionalCSS("/bitrix/tools/bxready2/collection/bxr_elements/ecommerce.m2.v1/include/style.css");
$APPLICATION->AddHeadScript("/bitrix/tools/bxready2/collection/bxr_elements/ecommerce.m2.v1/include/script.js");
$APPLICATION->SetAdditionalCSS("/bitrix/components/bxready.market2/catalog.product.subscribe/templates/.default/style.css");
$APPLICATION->AddHeadScript("/bitrix/components/bxready.market2/catalog.product.subscribe/templates/.default/script.js");
