<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

use Bitrix\Sale;
use Bitrix\Catalog\ProductTable;
use Native\App\Catalog\Product;
use Native\App\Sale\Store;
CModule::IncludeModule("iblock");
if (CModule::IncludeModule("sale")) {
	if ($_POST["ID"]) {
		$basket = Sale\Basket::loadItemsForFUser(Sale\Fuser::getId(), Bitrix\Main\Context::getCurrent()->getSite());
		if ($_POST["QUANTITY"] > 0) {
			$price = $basket->getPrice();
			//if ($price) {
				$basketItems = $basket->getBasketItems();
				//$POINTS_SUM = 0;

				/*$PRICE_FULL = $basket->getBasePrice();
				$PRICE_SUM = $basket->getPrice();*/

				foreach ($basket as $basketItem) {
					$CART_ID = $basketItem->getId();
					if ($_POST["ID"] == $CART_ID) {
						$PRODUCT_ID = $basketItem->getProductId();
						$res = CIBlockElement::GetByID($PRODUCT_ID);
						if ($ar_res = $res->GetNext()) {
							$basketItem->setField('QUANTITY', $_POST["QUANTITY"]);
							$basketItem->save();
						}
					}
				}
			//}
		}
		else{
			$basket->getItemById($_POST["ID"])->delete();
			$basket->save();
		}
	}
	if($_POST["PRODUCT_ID"] && $_POST["QUANTITY"]){
		$basket = Sale\Basket::loadItemsForFUser(Sale\Fuser::getId(), Bitrix\Main\Context::getCurrent()->getSite());
		$basketItems = $basket->getBasketItems();
		foreach ($basket as $basketItem) {
			$CART_ID = $basketItem->getId();
			$PRODUCT_ID = $basketItem->getProductId();
			if ($_POST["PRODUCT_ID"] == $PRODUCT_ID) {

				$res = CIBlockElement::GetByID($PRODUCT_ID);
				if ($ar_res = $res->GetNext()) {
					$basketItem->setField('QUANTITY', $_POST["QUANTITY"]);
					$basketItem->save();
				}
			}
		}
	}
}
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_after.php");
