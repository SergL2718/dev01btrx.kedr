<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php"); ?>
<?
/*
 * Изменено: 09 декабря 2021, четверг
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

$template = ".default";
if (isset($_SESSION["BXR_BASKET_COMPARE"]) && strlen($_SESSION["BXR_BASKET_COMPARE"]) > 0)
	$template = $_SESSION["BXR_BASKET_COMPARE"];
?>
<? $APPLICATION->IncludeComponent(
	"alexkova.market:catalog.compare.list",
	$template,
	[
		"IBLOCK_TYPE"         => "catalog",
		"IBLOCK_ID"           => '33',
		"AJAX_MODE"           => "N",
		"AJAX_OPTION_JUMP"    => "N",
		"AJAX_OPTION_STYLE"   => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"DETAIL_URL"          => "",
		"COMPARE_URL"         => SITE_DIR . "catalog/compare.php",
		"NAME"                => "CATALOG_COMPARE_LIST",
	]
); ?>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_after.php"); ?>
