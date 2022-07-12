<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var CBitrixComponentTemplate $this
 * @var CatalogElementComponent $component
 */
use Bitrix\Catalog\ProductTable;
use Bitrix\Sale;
use Native\App\Catalog\Product;
use Native\App\Sale\Store;


$component = $this->getComponent();
$arParams = $component->applyTemplateModifications();

//if ($USER->IsAdmin()){echo "<pre>"; print_r($arResult["OFFERS"]); echo "</pre>";}

$arResult["ADD_TO_CART_BTN"] = "В корзину";
$arResult["ADD_TO_CART_QUANTITY"] = 1;
$basket = Sale\Basket::loadItemsForFUser(Sale\Fuser::getId(), Bitrix\Main\Context::getCurrent()->getSite());
$basketItems = $basket->getBasketItems();
foreach ($basket as $basketItem) {
	//echo $basketItem->getProductId();
	if($arResult["ID"] == $basketItem->getProductId()) {
		$arResult["ADD_TO_CART_QUANTITY"] =	$basketItem->getQuantity();
		$arResult["ADD_TO_CART_BTN"] = "Добавлено";
	}
	else{
		foreach($arResult["OFFERS"] as $OFFER){
			if($OFFER["ID"] == $basketItem->getProductId()) {
				$arResult["ADD_TO_CART_QUANTITY"] =	$basketItem->getQuantity();
				$arResult["ADD_TO_CART_BTN"] = "Добавлено";
			}
		}
	}
}

//if ($USER->IsAdmin()){echo "<pre>"; print_r($arResult['PROPERTIES']); echo "</pre>";}

if ($arResult['PRODUCT']['TYPE'] == ProductTable::TYPE_SKU && !$arResult['ITEM_PRICES']) {
	$prices = [];
	foreach ($arResult['OFFERS'] as $i => $offer) {
		$prices[$i] = current($offer['ITEM_PRICES'])['PRICE'];
	}
	asort($prices);
	$prices = array_diff($prices, ['']);
	$arResult['ITEM_PRICES'] = $arResult['OFFERS'][array_key_first($prices)]['ITEM_PRICES'];
}
$arResult['ITEM_PRICES'] = $arResult['ITEM_PRICES'][0];
if ($arResult['CATALOG_CAN_BUY_ZERO'] === 'Y' || $arResult['PRODUCT']['CAN_BUY_ZERO'] === 'Y') {
	$arResult['CATALOG_CAN_BUY_ZERO'] = $arResult['PRODUCT']['CAN_BUY_ZERO'] = 'Y';
}
$arResult['CATALOG_QUANTITY'] = $arResult['PRODUCT']['QUANTITY'] = Product::getQuantityByStore($arResult['ID'], Store::getInstance()->getCurrent());
if ($arResult['PROPERTIES']['OFFER_WEEK']['VALUE_XML_ID'] === 'Y') {
	$arResult['BADGES']['OFFER_WEEK']['CODE'] = 'OFFER_WEEK';
	$arResult['BADGES']['OFFER_WEEK']['TITLE'] = $arResult['PROPERTIES']['OFFER_WEEK']['NAME'];
}
if ($arResult['PROPERTIES']['NEWPRODUCT']['VALUE']) {
	$arResult['BADGES']['NEW']['CODE'] = 'NEW';
	$arResult['BADGES']['NEW']['TITLE'] = $arResult['PROPERTIES']['NEWPRODUCT']['NAME'];
}
if ($arResult['PROPERTIES']['SPECIALOFFER']['VALUE']) {
	$arResult['BADGES']['SPECIAL_OFFER']['CODE'] = 'SPECIAL_OFFER';
	$arResult['BADGES']['SPECIAL_OFFER']['TITLE'] = $arResult['PROPERTIES']['SPECIALOFFER']['NAME'];
}
if ($arResult['PROPERTIES']['SALELEADER']['VALUE']) {
	$arResult['BADGES']['HIT']['CODE'] = 'HIT';
	$arResult['BADGES']['HIT']['TITLE'] = $arResult['PROPERTIES']['SALELEADER']['NAME'];
}
if ($arResult['PROPERTIES']['RECOMMENDED']['VALUE']) {
	$arResult['BADGES']['RECOMMENDED']['CODE'] = 'RECOMMENDED';
	$arResult['BADGES']['RECOMMENDED']['TITLE'] = $arResult['PROPERTIES']['RECOMMENDED']['NAME'];
}
if (!$arResult['PROPERTIES']['NUMBER_BONUSES']['VALUE']) {
	//echo "<pre>"; print_r($arResult); echo "</pre>";
	$arResult['PROPERTIES']['NUMBER_BONUSES']['VALUE'] = Product::getNumberBonuses($arResult['ID']);
}


$arSelect = array("IBLOCK_SECTION_ID");
$IBLOCK_ID = 41;
$arFilter = array(
	"IBLOCK_ID" => $IBLOCK_ID,
	"ACTIVE" => "Y",
	"CODE" => $arResult["CODE"]
);
$res = CIBlockElement::GetList(array("SORT" => "ASC"), $arFilter, false, array("nTopCount" => 1), $arSelect);
while ($ar_fields = $res->GetNext()) {
	if($ar_fields["IBLOCK_SECTION_ID"]){
		$resSec = CIBlockSection::GetByID($ar_fields["IBLOCK_SECTION_ID"]);
		if($ar_resSec = $resSec->GetNext())
			$arResult["FAMILY"] = $ar_resSec['NAME'];
	}
}
