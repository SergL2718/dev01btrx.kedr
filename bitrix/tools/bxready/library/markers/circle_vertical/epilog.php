<?
use Alexkova\Bxready\Draw;

$dirName = str_replace($_SERVER["DOCUMENT_ROOT"],'', dirname(__FILE__));

$elementDraw = \Alexkova\Bxready\Draw::getInstance();
$elementDraw->setAdditionalFile("CSS", "/bitrix/tools/bxready/library/markers/circle_vertical/include/style.css", false);
?>