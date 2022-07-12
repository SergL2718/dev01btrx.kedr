<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Alexkova\Bxready\Core;
use Alexkova\Bxready\Lists;

if(!CModule::IncludeModule("alexkova.bxready"))
	return;

$arTemplateParameters = array(
	"SHOW_LINK_MAIN_PAGE_IBLOCK" => Array(
		"NAME" => GetMessage("T_SHOW_LINK_MAIN_PAGE_IBLOCK"),
		"TYPE" => "CHECKBOX",
		"MULTIPLE" => "N",
		"VALUE" => "Y",
		"DEFAULT" =>"N",
		"REFRESH"=> "Y",
	)
);

if ($arCurrentValues["SHOW_LINK_MAIN_PAGE_IBLOCK"] == "Y")
{
	$arTemplateParameters["SHOW_LINK_MAIN_PAGE_NAME"] = array(
		"NAME" => GetMessage("T_SHOW_LINK_MAIN_PAGE_NAME"),
		"TYPE" => "STRING",
		"DEFAULT" => "",
	);
	$arTemplateParameters["SHOWN_MAIN_PAGE_LINK"] = array(
		"NAME" => GetMessage("T_SHOWN_MAIN_PAGE_LINK"),
		"TYPE" => "STRING",
		"DEFAULT" => "",
	);
}
?>