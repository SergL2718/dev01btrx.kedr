<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

if (CModule::IncludeModule("sale")) {
	$basket = Bitrix\Sale\Basket::loadItemsForFUser(Bitrix\Sale\Fuser::getId(), Bitrix\Main\Context::getCurrent()->getSite());
	$price = $basket->getPrice();
	if ($price < 5000) {
		echo "Закажите еще на <b>" . (5000 - $price) . " руб.</b><br/> и получите бесплатную доставку!";
	} else {
		echo "<b>У вас бесплатная доставка</b>";
	}
}
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
