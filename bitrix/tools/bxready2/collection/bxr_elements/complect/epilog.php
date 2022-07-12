<?
global $APPLICATION;

$dirName = str_replace($_SERVER["DOCUMENT_ROOT"],'', dirname(__FILE__));
$APPLICATION->SetAdditionalCSS("/bitrix/tools/bxready2/collection/bxr_elements/complect/include/style.css");
$APPLICATION->AddHeadScript("/bitrix/tools/bxready2/collection/bxr_elements/complect/include/script.js");
?>