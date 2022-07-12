<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Результат от платежной системы");
?><?$APPLICATION->IncludeComponent(
	"bitrix:sale.order.payment.receive",
	"",
	Array(
		"PAY_SYSTEM_ID_NEW" => "8"
	)
);
echo true;
?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>