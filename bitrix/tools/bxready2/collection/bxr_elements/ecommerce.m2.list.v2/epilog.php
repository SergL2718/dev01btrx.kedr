<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

use Alexkova\Bxready2\Draw;
$elementDraw = \Alexkova\Bxready2\Draw::getInstance($this);
$elementDraw->setAdditionalFile("CSS", "/bitrix/tools/bxready2/collection/bxr_elements/ecommerce.m2.list.v2/include/style.css", false);
if (isset($arElementParams["BXR_PRESENT_SETTINGS"]["BXREADY_LIST_MARKER_TYPE"]) && strlen($arElementParams["BXR_PRESENT_SETTINGS"]["BXREADY_LIST_MARKER_TYPE"])>0)
    $elementDraw->setMarkerCollection($arElementParams["BXR_PRESENT_SETTINGS"]["BXREADY_LIST_MARKER_TYPE"]);
$elementDraw->showMarkerGroup(array(), true);
?>
