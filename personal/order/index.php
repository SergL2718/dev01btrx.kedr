<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Заказы");
?>
<?$APPLICATION->IncludeComponent(
	"bitrix:sale.personal.order", 
	"megre", 
	array(
		"SEF_MODE" => "N",
		"SEF_FOLDER" => "/personal/order/",
		"ORDERS_PER_PAGE" => "10",
		"PATH_TO_PAYMENT" => "/personal/order/payment/",
		"PATH_TO_BASKET" => "/personal/basket/",
		"SET_TITLE" => "Y",
		"SAVE_IN_SESSION" => "N",
		"NAV_TEMPLATE" => "arrows",
		"SHOW_ACCOUNT_NUMBER" => "Y",
		"COMPONENT_TEMPLATE" => "megre",
		"PROP_1" => "",
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600",
		"CACHE_GROUPS" => "N",
		"CUSTOM_SELECT_PROPS" => array(
		),
		"HISTORIC_STATUSES" => array(
			0 => "F",
		),
		"STATUS_COLOR_N" => "green",
		"STATUS_COLOR_F" => "gray",
		"STATUS_COLOR_PSEUDO_CANCELLED" => "red",
		"DETAIL_HIDE_USER_INFO" => array(
			0 => "0",
		),
		"PROP_3" => array(
		),
		"PROP_4" => array(
		),
		"PATH_TO_CATALOG" => "/catalog/",
		"RESTRICT_CHANGE_PAYSYSTEM" => array(
			0 => "C",
			1 => "F",
		),
		"REFRESH_PRICES" => "Y",
		"ORDER_DEFAULT_SORT" => "ACCOUNT_NUMBER",
		"ALLOW_INNER" => "N",
		"ONLY_INNER_FULL" => "N",
		"STATUS_COLOR_C" => "gray",
		"STATUS_COLOR_D" => "gray",
		"STATUS_COLOR_O" => "gray",
		"STATUS_COLOR_P" => "yellow",
		"DISALLOW_CANCEL" => "N",
		"STATUS_COLOR_ST" => "gray"
	),
	false
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
