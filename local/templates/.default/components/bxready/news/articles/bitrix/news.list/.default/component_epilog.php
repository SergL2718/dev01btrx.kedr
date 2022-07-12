<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Alexkova\Bxready\BXReady;

/*if (isset($arResult["BXREADY_ADDITIONAL_FILES"]) && count($arResult["BXREADY_ADDITIONAL_FILES"])>0){
	Bxready::getInstance()
		->addAdditionalFiles($arResult["BXREADY_ADDITIONAL_FILES"]);
}*/


global $setActivitiesLink;
$setActivitiesLink = false;

if ($arResult["COUNT_ELS"]>0)
	$setActivitiesLink = true;

$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/slick/slick.js');
$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/js/slick/slick.css', false);
?>