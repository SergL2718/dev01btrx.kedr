<?
use Alexkova\Bxready2\Draw;

$dirName = str_replace($_SERVER["DOCUMENT_ROOT"],'', dirname(__FILE__));
$elementDraw = Draw::getInstance();
$elementDraw->setAdditionalFile("CSS", "/bitrix/tools/bxready2/collection/bxr_markers/ribbon.vertical/include/style.css", false);
?>