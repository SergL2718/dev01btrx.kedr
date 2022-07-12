<?
use Alexkova\Bxready\Draw2;
$elementDraw = \Alexkova\Bxready2\Draw::getInstance($this);

$dirName = str_replace($_SERVER["DOCUMENT_ROOT"],'', dirname(__FILE__));
$elementDraw->setAdditionalFile("CSS", $dirName."/include/style.css", false);?>