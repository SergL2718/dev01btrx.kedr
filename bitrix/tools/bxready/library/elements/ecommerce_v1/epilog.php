<?
global $APPLICATION;

$dirName = str_replace($_SERVER["DOCUMENT_ROOT"], '', dirname(__FILE__));
$APPLICATION->SetAdditionalCSS("/bitrix/tools/bxready/library/elements/ecommerce_v1/include/style.css");
$APPLICATION->AddHeadScript("/bitrix/tools/bxready/library/elements/ecommerce_v1/include/script.js");
?>
