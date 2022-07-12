<?
global $APPLICATION;

$dirName = str_replace($_SERVER["DOCUMENT_ROOT"],'', dirname(__FILE__));
$APPLICATION->SetAdditionalCSS("/bitrix/tools/bxready/library/elements/ecommerce_v2_list/include/style.css");
$APPLICATION->AddHeadScript("/bitrix/tools/bxready/library/elements/ecommerce_v2_list/include/script.js");
