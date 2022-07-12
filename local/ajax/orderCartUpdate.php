<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
use Bitrix\Sale;
use Bitrix\Catalog\ProductTable;
use Native\App\Catalog\Product;
use Native\App\Sale\Store;
if (CModule::IncludeModule("sale")) {
	$basket = Sale\Basket::loadItemsForFUser(Sale\Fuser::getId(), Bitrix\Main\Context::getCurrent()->getSite());
	$price = $basket->getPrice();
	$arResult = array();
	if ($price) {
		$basketItems = $basket->getBasketItems();
		$POINTS_SUM = 0;

		$PRICE_FULL = $basket->getBasePrice();
		$PRICE_SUM = $basket->getPrice();

		foreach ($basket as $basketItem) {
			$CART_ID = $basketItem->getId();
			$PRODUCT_ID = $basketItem->getProductId();
			$res = CIBlockElement::GetByID($PRODUCT_ID);
			if ($ar_res = $res->GetNext()) {
				$POINTS = Product::getNumberBonuses($PRODUCT_ID)*$basketItem->getQuantity();
				$POINTS_SUM += $POINTS;
			}
		}
		$arResult = array(
			"POINTS_SUM" => $POINTS_SUM,
			"PRICE_FULL" => $PRICE_FULL,
			"PRICE_SUM" => $PRICE_SUM,
		);
		echo json_encode($arResult);
	}
}

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
