<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

use Alexkova\Bxready2\Draw;
$elementDraw = \Alexkova\Bxready2\Draw::getInstance($this);
$elementDraw->setAdditionalFile("CSS", "/bitrix/tools/bxready2/collection/bxr_elements/image.v2/include/style.css", false);
?>