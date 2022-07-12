<?php
/*
 * Изменено: 11 августа 2021, среда
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

use Bitrix\Currency\CurrencyTable;
use Bitrix\Main\Type\Collection;
use Native\App\Catalog\Product;
use Native\App\Sale\Location;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die;
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */

$imageWidth = 160;
$imageHeight = 160;

$arDefaultParams = [
	'TEMPLATE_THEME'         => 'blue',
	'PRODUCT_DISPLAY_MODE'   => 'N',
	'ADD_PICT_PROP'          => '-',
	'LABEL_PROP'             => '-',
	'OFFER_ADD_PICT_PROP'    => '-',
	'OFFER_TREE_PROPS'       => ['-'],
	'PRODUCT_SUBSCRIPTION'   => 'N',
	'SHOW_DISCOUNT_PERCENT'  => 'N',
	'SHOW_OLD_PRICE'         => 'N',
	'ADD_TO_BASKET_ACTION'   => 'ADD',
	'SHOW_CLOSE_POPUP'       => 'N',
	'MESS_BTN_BUY'           => '',
	'MESS_BTN_ADD_TO_BASKET' => '',
	'MESS_BTN_SUBSCRIBE'     => '',
	'MESS_BTN_DETAIL'        => '',
	'MESS_NOT_AVAILABLE'     => '',
	'MESS_BTN_COMPARE'       => '',
];
$arParams = array_merge($arDefaultParams, $arParams);

global $USER;

Bitrix\Main\Loader::includeModule('highloadblock');
$accessStorage = Bitrix\Highloadblock\HighloadBlockTable::getList(['select' => ['ID'], 'filter' => ['NAME' => 'Access'], 'limit' => 1, 'cache' => ['ttl' => 86400000]]);
$accessStorage = $accessStorage->fetchRaw()['ID'];
$accessStorage = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($accessStorage);
$accessStorage = $accessStorage->getDataClass();

if (!isset($arParams['LINE_ELEMENT_COUNT']))
	$arParams['LINE_ELEMENT_COUNT'] = 3;
$arParams['LINE_ELEMENT_COUNT'] = intval($arParams['LINE_ELEMENT_COUNT']);
if (2 > $arParams['LINE_ELEMENT_COUNT'] || 5 < $arParams['LINE_ELEMENT_COUNT'])
	$arParams['LINE_ELEMENT_COUNT'] = 3;

$arParams['TEMPLATE_THEME'] = (string) ($arParams['TEMPLATE_THEME']);
if ('' != $arParams['TEMPLATE_THEME']) {
	$arParams['TEMPLATE_THEME'] = preg_replace('/[^a-zA-Z0-9_\-\(\)\!]/', '', $arParams['TEMPLATE_THEME']);
	if ('site' == $arParams['TEMPLATE_THEME']) {
		$templateId = COption::GetOptionString("main", "wizard_template_id", "eshop_bootstrap", SITE_ID);
		$templateId = (preg_match("/^eshop_adapt/", $templateId)) ? "eshop_adapt" : $templateId;
		$arParams['TEMPLATE_THEME'] = COption::GetOptionString('main', 'wizard_' . $templateId . '_theme_id', 'blue', SITE_ID);
	}
	if ('' != $arParams['TEMPLATE_THEME']) {
		if (!is_file($_SERVER['DOCUMENT_ROOT'] . $this->GetFolder() . '/themes/' . $arParams['TEMPLATE_THEME'] . '/style.css'))
			$arParams['TEMPLATE_THEME'] = '';
	}
}
if ('' == $arParams['TEMPLATE_THEME'])
	$arParams['TEMPLATE_THEME'] = 'blue';
$arResult['NAV_PARAM']['TEMPLATE_THEME'] = $arParams['TEMPLATE_THEME'];

$arResult['NAV_STRING'] = $arResult['NAV_RESULT']->GetPageNavStringEx(
	$navComponentObject,
	$arParams['PAGER_TITLE'],
	$arParams['PAGER_TEMPLATE'],
	$arParams['PAGER_SHOW_ALWAYS'],
	$this->__component,
	$arResult['NAV_PARAM']
);

if ('Y' != $arParams['PRODUCT_DISPLAY_MODE'])
	$arParams['PRODUCT_DISPLAY_MODE'] = 'N';

$arParams['ADD_PICT_PROP'] = trim($arParams['ADD_PICT_PROP']);
if ('-' == $arParams['ADD_PICT_PROP'])
	$arParams['ADD_PICT_PROP'] = '';
$arParams['LABEL_PROP'] = trim($arParams['LABEL_PROP']);
if ('-' == $arParams['LABEL_PROP'])
	$arParams['LABEL_PROP'] = '';
$arParams['OFFER_ADD_PICT_PROP'] = trim($arParams['OFFER_ADD_PICT_PROP']);
if ('-' == $arParams['OFFER_ADD_PICT_PROP'])
	$arParams['OFFER_ADD_PICT_PROP'] = '';
if ('Y' == $arParams['PRODUCT_DISPLAY_MODE']) {
	if (!is_array($arParams['OFFER_TREE_PROPS']))
		$arParams['OFFER_TREE_PROPS'] = [$arParams['OFFER_TREE_PROPS']];
	foreach ($arParams['OFFER_TREE_PROPS'] as $key => $value) {
		$value = (string) $value;
		if ('' == $value || '-' == $value)
			unset($arParams['OFFER_TREE_PROPS'][$key]);
	}
	if (empty($arParams['OFFER_TREE_PROPS']) && isset($arParams['OFFERS_CART_PROPERTIES']) && is_array($arParams['OFFERS_CART_PROPERTIES'])) {
		$arParams['OFFER_TREE_PROPS'] = $arParams['OFFERS_CART_PROPERTIES'];
		foreach ($arParams['OFFER_TREE_PROPS'] as $key => $value) {
			$value = (string) $value;
			if ('' == $value || '-' == $value)
				unset($arParams['OFFER_TREE_PROPS'][$key]);
		}
	}
} else {
	$arParams['OFFER_TREE_PROPS'] = [];
}
if ('Y' != $arParams['PRODUCT_SUBSCRIPTION'])
	$arParams['PRODUCT_SUBSCRIPTION'] = 'N';
if ('Y' != $arParams['SHOW_DISCOUNT_PERCENT'])
	$arParams['SHOW_DISCOUNT_PERCENT'] = 'N';
if ('Y' != $arParams['SHOW_OLD_PRICE'])
	$arParams['SHOW_OLD_PRICE'] = 'N';
if ($arParams['ADD_TO_BASKET_ACTION'] != 'BUY')
	$arParams['ADD_TO_BASKET_ACTION'] = 'ADD';
if ($arParams['SHOW_CLOSE_POPUP'] != 'Y')
	$arParams['SHOW_CLOSE_POPUP'] = 'N';
$arParams['MESS_BTN_BUY'] = trim($arParams['MESS_BTN_BUY']);
$arParams['MESS_BTN_ADD_TO_BASKET'] = trim($arParams['MESS_BTN_ADD_TO_BASKET']);
$arParams['MESS_BTN_SUBSCRIBE'] = trim($arParams['MESS_BTN_SUBSCRIBE']);
$arParams['MESS_BTN_DETAIL'] = trim($arParams['MESS_BTN_DETAIL']);
$arParams['MESS_NOT_AVAILABLE'] = trim($arParams['MESS_NOT_AVAILABLE']);
$arParams['MESS_BTN_COMPARE'] = trim($arParams['MESS_BTN_COMPARE']);
$arParams["PREVIEW_TRUNCATE_LEN"] = intval($arParams["PREVIEW_TRUNCATE_LEN"]);

if (!empty($arResult['ITEMS'])) {
	$arEmptyPreview = false;
	$strEmptyPreview = $this->GetFolder() . '/images/no_photo.png';
	if (file_exists($_SERVER['DOCUMENT_ROOT'] . $strEmptyPreview)) {
		$arSizes = getimagesize($_SERVER['DOCUMENT_ROOT'] . $strEmptyPreview);
		if (!empty($arSizes)) {
			$arEmptyPreview = [
				'SRC'    => $strEmptyPreview,
				'WIDTH'  => intval($arSizes[0]),
				'HEIGHT' => intval($arSizes[1]),
			];
		}
		unset($arSizes);
	}
	unset($strEmptyPreview);

	$arSKUPropList = [];
	$arSKUPropIDs = [];
	$arSKUPropKeys = [];
	$boolSKU = false;
	$strBaseCurrency = '';
	$boolConvert = isset($arResult['CONVERT_CURRENCY']['CURRENCY_ID']);

	if ($arResult['MODULES']['catalog']) {
		if (!$boolConvert)
			$strBaseCurrency = CCurrency::GetBaseCurrency();

		$arSKU = CCatalogSKU::GetInfoByProductIBlock($arParams['IBLOCK_ID']);
		$boolSKU = !empty($arSKU) && is_array($arSKU);
		if ($boolSKU && !empty($arParams['OFFERS_PROPERTY_CODE'])) {
			$arSKUPropList = CIBlockPriceTools::getTreeProperties(
				$arSKU,
				$arParams['OFFERS_PROPERTY_CODE'],
				[
					'PICT' => $arEmptyPreview,
					'NAME' => '-',
				]
			);

			$arNeedValues = [];
			CIBlockPriceTools::getTreePropertyValues($arSKUPropList, $arNeedValues);
			$arSKUPropIDs = array_keys($arSKUPropList);
			if (empty($arSKUPropIDs))
				$arParams['PRODUCT_DISPLAY_MODE'] = 'N';
			else
				$arSKUPropKeys = array_fill_keys($arSKUPropIDs, false);
		}
	}

	$arNewItemsList = [];
	foreach ($arResult['ITEMS'] as $key => $arItem) {

		// https://megre.bitrix24.ru/workgroups/group/9/tasks/task/view/7595/
		if ($arItem['CATALOG_CAN_BUY_ZERO'] === 'Y' && $arItem['CATALOG_QUANTITY'] <= 0) {
			$arItem['CATALOG_QUANTITY'] = 9999999;
		}

		$obParser = new CTextParser;
		$arItem["SHORT_NAME"] = '';
		if ($arParams["PREVIEW_TRUNCATE_LEN"] > 0)
			$arItem["SHORT_NAME"] = $obParser->html_cut($arItem["~NAME"], $arParams["PREVIEW_TRUNCATE_LEN"]);

		$arItem['CHECK_QUANTITY'] = false;
		if (!isset($arItem['CATALOG_MEASURE_RATIO']))
			$arItem['CATALOG_MEASURE_RATIO'] = 1;
		if (!isset($arItem['CATALOG_QUANTITY']))
			$arItem['CATALOG_QUANTITY'] = 0;
		$arItem['CATALOG_QUANTITY'] = (
		0 < $arItem['CATALOG_QUANTITY'] && is_float($arItem['CATALOG_MEASURE_RATIO'])
			? floatval($arItem['CATALOG_QUANTITY'])
			: intval($arItem['CATALOG_QUANTITY'])
		);
		$arItem['CATALOG'] = false;
		if (!isset($arItem['CATALOG_SUBSCRIPTION']) || 'Y' != $arItem['CATALOG_SUBSCRIPTION'])
			$arItem['CATALOG_SUBSCRIPTION'] = 'N';

		CIBlockPriceTools::getLabel($arItem, $arParams['LABEL_PROP']);

		$productPictures = CIBlockPriceTools::getDoublePicturesForItem($arItem, $arParams['ADD_PICT_PROP']);
		if (empty($productPictures['PICT']))
			$productPictures['PICT'] = $arEmptyPreview;
		if (empty($productPictures['SECOND_PICT']))
			$productPictures['SECOND_PICT'] = $productPictures['PICT'];

		if ($productPictures['PICT']['ID']) {
			if ($ar = CFile::ResizeImageGet($productPictures['PICT']['ID'], ['width' => $imageWidth, 'height' => $imageHeight], BX_RESIZE_IMAGE_PROPORTIONAL, true)) {
				$productPictures['PICT']['SRC'] = $ar['src'];
				$productPictures['PICT']['WIDTH'] = $ar['width'];
				$productPictures['PICT']['HEIGHT'] = $ar['height'];
			}
		}
		if ($productPictures['SECOND_PICT']['ID']) {
			if ($ar = CFile::ResizeImageGet($productPictures['SECOND_PICT']['ID'], ['width' => $imageWidth, 'height' => $imageHeight], BX_RESIZE_IMAGE_PROPORTIONAL, true)) {
				$productPictures['SECOND_PICT']['SRC'] = $ar['src'];
				$productPictures['SECOND_PICT']['WIDTH'] = $ar['width'];
				$productPictures['SECOND_PICT']['HEIGHT'] = $ar['height'];
			}
		}

		$arItem['PREVIEW_PICTURE'] = $productPictures['PICT'];
		$arItem['PREVIEW_PICTURE_SECOND'] = $productPictures['SECOND_PICT'];
		$arItem['SECOND_PICT'] = true;
		$arItem['PRODUCT_PREVIEW'] = $productPictures['PICT'];
		$arItem['PRODUCT_PREVIEW_SECOND'] = $productPictures['SECOND_PICT'];

		if ($arResult['MODULES']['catalog']) {
			$arItem['CATALOG'] = true;
			if (!isset($arItem['CATALOG_TYPE']))
				$arItem['CATALOG_TYPE'] = CCatalogProduct::TYPE_PRODUCT;
			if (
				(CCatalogProduct::TYPE_PRODUCT == $arItem['CATALOG_TYPE'] || CCatalogProduct::TYPE_SKU == $arItem['CATALOG_TYPE'])
				&& !empty($arItem['OFFERS'])
			) {
				$arItem['CATALOG_TYPE'] = CCatalogProduct::TYPE_SKU;
			}
			switch ($arItem['CATALOG_TYPE']) {
				case CCatalogProduct::TYPE_SET:
					$arItem['OFFERS'] = [];
					$arItem['CHECK_QUANTITY'] = ('Y' == $arItem['CATALOG_QUANTITY_TRACE'] && 'N' == $arItem['CATALOG_CAN_BUY_ZERO']);
					break;
				case CCatalogProduct::TYPE_SKU:
					break;
				case CCatalogProduct::TYPE_PRODUCT:
				default:
					$arItem['CHECK_QUANTITY'] = ('Y' == $arItem['CATALOG_QUANTITY_TRACE'] && 'N' == $arItem['CATALOG_CAN_BUY_ZERO']);
					break;
			}
		} else {
			$arItem['CATALOG_TYPE'] = 0;
			$arItem['OFFERS'] = [];
		}

		global $USER;

		if ($arItem['CATALOG'] && isset($arItem['OFFERS']) && !empty($arItem['OFFERS'])) {

			// https://megre.bitrix24.ru/workgroups/group/9/tasks/task/view/18119/
			// Установим остатки торговых предложений согласно выбранному складу
			$arOfferIds = [];
			foreach ($arItem['OFFERS'] as &$arOffer) {
				$arOfferIds[$arOffer['ID']] = $arOffer['PROPERTIES']['DOWNLOAD_LINK']['VALUE'];
			}
			if (!empty($arOfferIds)) {
				// Проверим, куплен ли доступ к предложению
				$accessOfferList = array_diff($arOfferIds, ['']);
				if (!empty($accessOfferList)) {
					$date = new \Bitrix\Main\Type\DateTime();
					$r = $accessStorage::getList([
						'select' => [
							'UF_ENTITY_ID',
							'UF_DATE_FROM',
							'UF_DATE_TO',
						],
						'filter' => [
							'=UF_ENTITY_CODE' => 'PRODUCT',
							'=UF_ENTITY_ID'   => array_keys($accessOfferList),
							'=UF_USER_ID'     => $USER->GetID(),
						],
					]);
					while ($i = $r->fetch()) {
						if ($i['UF_DATE_FROM'] > $date || $i['UF_DATE_TO'] < $date) {
							$accessOfferList[$i['UF_ENTITY_ID']] = false;
							continue;
						}
						$accessOfferList[$i['UF_ENTITY_ID']] = true;
						unset($arOfferIds[$i['UF_ENTITY_ID']]);
					}
				}
				$quantityInStore = Product::getInstance()->getQuantityProductsByStoreCode(array_keys($arOfferIds), Location::MSK);
				foreach ($arItem['OFFERS'] as &$arOffer) {
					if (isset($accessOfferList[$arOffer['ID']]) && $accessOfferList[$arOffer['ID']] === true) {
						$arOffer['HAS_ACCESS_TO_PRODUCT'] = true;
						$arOffer['CATALOG_QUANTITY'] = 99999999999;
						$arOffer['PRODUCT']['QUANTITY'] = 99999999999;
						continue;
					}
					if (isset($quantityInStore[$arOffer['ID']])) {
						if (Location::getCurrentCityCode() === Location::MSK) {
							$arOffer['CATALOG_QUANTITY'] = $quantityInStore[$arOffer['ID']];
							$arOffer['PRODUCT']['QUANTITY'] = $quantityInStore[$arOffer['ID']];
						} else if ($quantityInStore[$arOffer['ID']] > -1) {
							$arOffer['CATALOG_QUANTITY'] -= $quantityInStore[$arOffer['ID']];
							$arOffer['PRODUCT']['QUANTITY'] -= $quantityInStore[$arOffer['ID']];
						}
					}
				}
			}
			unset($arOfferIds, $arOffer);

			if ('Y' == $arParams['PRODUCT_DISPLAY_MODE']) {

				$arMatrixFields = $arSKUPropKeys;
				$arMatrix = [];

				$arNewOffers = [];
				$boolSKUDisplayProperties = false;
				$arItem['OFFERS_PROP'] = false;
				$arItem['SKU_TREE_VALUES'] = [];

				$arDouble = [];
				foreach ($arItem['OFFERS'] as $keyOffer => $arOffer) {

					$arOffer['ID'] = intval($arOffer['ID']);
					if (isset($arDouble[$arOffer['ID']])) {
						continue;
					}

					$arItem["CATALOG_QUANTITY"] += $arOffer["CATALOG_QUANTITY"];

					$arRow = [];
					foreach ($arSKUPropIDs as $propkey => $strOneCode) {
						$arCell = [
							'VALUE' => 0,
							'SORT'  => PHP_INT_MAX,
							'NA'    => true,
						];
						if (isset($arOffer['DISPLAY_PROPERTIES'][$strOneCode])) {
							$arMatrixFields[$strOneCode] = true;
							$arCell['NA'] = false;
							if ('directory' == $arSKUPropList[$strOneCode]['USER_TYPE']) {
								$intValue = $arSKUPropList[$strOneCode]['XML_MAP'][$arOffer['DISPLAY_PROPERTIES'][$strOneCode]['VALUE']];
								$arCell['VALUE'] = $intValue;
							} else if ('L' == $arSKUPropList[$strOneCode]['PROPERTY_TYPE']) {
								$arCell['VALUE'] = intval($arOffer['DISPLAY_PROPERTIES'][$strOneCode]['VALUE_ENUM_ID']);
							} else if ('E' == $arSKUPropList[$strOneCode]['PROPERTY_TYPE']) {
								$arCell['VALUE'] = intval($arOffer['DISPLAY_PROPERTIES'][$strOneCode]['VALUE']);
							}
							$arCell['SORT'] = $arSKUPropList[$strOneCode]['VALUES'][$arCell['VALUE']]['SORT'];
						}
						$arRow[$strOneCode] = $arCell;
					}

					$arMatrix[$keyOffer] = $arRow;
					unset($arRow);

					foreach ($arParams['OFFER_TREE_PROPS'] as $value) {
						$arOffer['PROPERTIES'][$value]['DISPLAY_VALUE'] = $arOffer['DISPLAY_PROPERTIES'][$value]['DISPLAY_VALUE'];
					}

					CIBlockPriceTools::clearProperties($arOffer['DISPLAY_PROPERTIES'], $arParams['OFFER_TREE_PROPS']);

					CIBlockPriceTools::setRatioMinPrice($arOffer, false);

					$offerPictures = CIBlockPriceTools::getDoublePicturesForItem($arOffer, $arParams['OFFER_ADD_PICT_PROP']);

					if ($offerPictures['PICT']['ID']) {
						if ($ar = CFile::ResizeImageGet($offerPictures['PICT']['ID'], ['width' => $imageWidth, 'height' => $imageHeight], BX_RESIZE_IMAGE_PROPORTIONAL, true)) {
							$offerPictures['PICT']['SRC'] = $ar['src'];
							$offerPictures['PICT']['WIDTH'] = $ar['width'];
							$offerPictures['PICT']['HEIGHT'] = $ar['height'];
						}
					}
					if ($productPictures['SECOND_PICT']['ID']) {
						if ($ar = CFile::ResizeImageGet($offerPictures['SECOND_PICT']['ID'], ['width' => $imageWidth, 'height' => $imageHeight], BX_RESIZE_IMAGE_PROPORTIONAL, true)) {
							$offerPictures['SECOND_PICT']['SRC'] = $ar['src'];
							$offerPictures['SECOND_PICT']['WIDTH'] = $ar['width'];
							$offerPictures['SECOND_PICT']['HEIGHT'] = $ar['height'];
						}
					}

					$arOffer['OWNER_PICT'] = empty($offerPictures['PICT']);
					$arOffer['PREVIEW_PICTURE'] = false;
					$arOffer['PREVIEW_PICTURE_SECOND'] = false;
					$arOffer['SECOND_PICT'] = true;
					if (!$arOffer['OWNER_PICT']) {
						if (empty($offerPictures['SECOND_PICT']))
							$offerPictures['SECOND_PICT'] = $offerPictures['PICT'];
						$arOffer['PREVIEW_PICTURE'] = $offerPictures['PICT'];
						$arOffer['PREVIEW_PICTURE_SECOND'] = $offerPictures['SECOND_PICT'];
					}
					if ('' != $arParams['OFFER_ADD_PICT_PROP'] && isset($arOffer['DISPLAY_PROPERTIES'][$arParams['OFFER_ADD_PICT_PROP']]))
						unset($arOffer['DISPLAY_PROPERTIES'][$arParams['OFFER_ADD_PICT_PROP']]);

					$arDouble[$arOffer['ID']] = true;
					$arNewOffers[$keyOffer] = $arOffer;
				}
				unset($keyOffer, $arOffer);
				$arItem['OFFERS'] = $arNewOffers;

				$arUsedFields = [];
				$arSortFields = [];

				$matrixKeys = array_keys($arMatrix);
				foreach ($arSKUPropIDs as $propkey => $propCode) {
					$boolExist = $arMatrixFields[$propCode];
					foreach ($matrixKeys as $keyOffer) {
						if ($boolExist) {
							if (!isset($arItem['OFFERS'][$keyOffer]['TREE']))
								$arItem['OFFERS'][$keyOffer]['TREE'] = [];
							$propId = $arSKUPropList[$propCode]['ID'];
							$value = $arMatrix[$keyOffer][$propCode]['VALUE'];
							if (!isset($arItem['SKU_TREE_VALUES'][$propId]))
								$arItem['SKU_TREE_VALUES'][$propId] = [];
							$arItem['SKU_TREE_VALUES'][$propId][$value] = true;
							$arItem['OFFERS'][$keyOffer]['TREE']['PROP_' . $propId] = $value;
							$arItem['OFFERS'][$keyOffer]['SKU_SORT_' . $propCode] = $arMatrix[$keyOffer][$propCode]['SORT'];
							$arUsedFields[$propCode] = true;
							$arSortFields['SKU_SORT_' . $propCode] = SORT_NUMERIC;
							unset($value, $propId);
						} else {
							unset($arMatrix[$keyOffer][$propCode]);
						}
					}
					unset($keyOffer);
				}
				unset($propkey, $propCode);
				unset($matrixKeys);
				$arItem['OFFERS_PROP'] = $arUsedFields;
				$arItem['OFFERS_PROP_CODES'] = (!empty($arUsedFields) ? base64_encode(serialize(array_keys($arUsedFields))) : '');

				Collection::sortByColumn($arItem['OFFERS'], $arSortFields);

				$arMatrix = [];
				$intSelected = -1;
				$arItem['MIN_PRICE'] = false;
				$arItem['MIN_BASIS_PRICE'] = false;

				foreach ($arItem['OFFERS'] as $keyOffer => $arOffer) {
					if (empty($arItem['MIN_PRICE']) && $arOffer['CAN_BUY']) {
						$intSelected = $keyOffer;
						$arItem['MIN_PRICE'] = $arOffer['MIN_PRICE'];
						$arItem['MIN_BASIS_PRICE'] = $arOffer['MIN_PRICE'];
					} else if (isset($arItem['MIN_PRICE']) && $arOffer['CAN_BUY'] && $arItem['MIN_PRICE']['VALUE'] > $arOffer['MIN_PRICE']['VALUE']) {
						$intSelected = $keyOffer;
						$arItem['MIN_PRICE'] = $arOffer['MIN_PRICE'];
						$arItem['MIN_BASIS_PRICE'] = $arOffer['MIN_PRICE'];
					}

					foreach ($arOffer["PRICES"] as $offerPriceIndex => $offerPrice) {
						if (empty($arItem['MIN_PRICE']) && $arOffer['CAN_BUY']) {
							$intSelected = $keyOffer;
							$arItem['MIN_PRICE'] = $offerPrice;
						} else if ($arOffer['CAN_BUY']) {
							$arItem['MIN_PRICE'] = ($offerPrice['VALUE'] < $arItem['MIN_PRICE']['VALUE']) ? $offerPrice : $arItem['MIN_PRICE'];
						}
					}

					$arSKUProps = false;
					if (!empty($arOffer['DISPLAY_PROPERTIES'])) {
						$boolSKUDisplayProperties = true;
						$arSKUProps = [];
						foreach ($arOffer['DISPLAY_PROPERTIES'] as &$arOneProp) {
							if ('F' == $arOneProp['PROPERTY_TYPE'])
								continue;
							$arSKUProps[] = [
								'NAME'  => $arOneProp['NAME'],
								'VALUE' => $arOneProp['DISPLAY_VALUE'],
							];
						}
						unset($arOneProp);
					}

					$arOneRow = [
						'ID'                     => $arOffer['ID'],
						'NAME'                   => $arItem['~NAME'],
						'TREE'                   => $arOffer['TREE'],
						'DISPLAY_PROPERTIES'     => $arSKUProps,
						'PRICE'                  => (isset($arOffer['RATIO_PRICE']) ? $arOffer['RATIO_PRICE'] : $arOffer['MIN_PRICE']),
						'BASIS_PRICE'            => $arOffer['MIN_PRICE'],
						'SECOND_PICT'            => $arOffer['SECOND_PICT'],
						'OWNER_PICT'             => $arOffer['OWNER_PICT'],
						'PREVIEW_PICTURE'        => $arOffer['PREVIEW_PICTURE'],
						'PREVIEW_PICTURE_SECOND' => $arOffer['PREVIEW_PICTURE_SECOND'],
						'CHECK_QUANTITY'         => $arOffer['CHECK_QUANTITY'],
						'MAX_QUANTITY'           => $arOffer['CATALOG_QUANTITY'],
						'STEP_QUANTITY'          => $arOffer['CATALOG_MEASURE_RATIO'],
						'QUANTITY_FLOAT'         => is_double($arOffer['CATALOG_MEASURE_RATIO']),
						'MEASURE'                => $arOffer['~CATALOG_MEASURE_NAME'],
						'CAN_BUY'                => $arOffer['CAN_BUY'],
					];
					$arMatrix[$keyOffer] = $arOneRow;
				}

				if (-1 == $intSelected)
					$intSelected = 0;
				if (!$arMatrix[$intSelected]['OWNER_PICT']) {
//					$arItem['PREVIEW_PICTURE'] = $arMatrix[$intSelected]['PREVIEW_PICTURE'];
					$arItem['PREVIEW_PICTURE_SECOND'] = $arMatrix[$intSelected]['PREVIEW_PICTURE_SECOND'];
				}
				$arItem['JS_OFFERS'] = $arMatrix;
				$arItem['OFFERS_SELECTED'] = $intSelected;
				$arItem['OFFERS_PROPS_DISPLAY'] = $boolSKUDisplayProperties;
			} else {
				$arItem['MIN_PRICE'] = CIBlockPriceTools::getMinPriceFromOffers(
					$arItem['OFFERS'],
					$boolConvert ? $arResult['CONVERT_CURRENCY']['CURRENCY_ID'] : $strBaseCurrency
				);
			}
		}

		if (
			$arResult['MODULES']['catalog']
			&& $arItem['CATALOG']
			&&
			($arItem['CATALOG_TYPE'] == CCatalogProduct::TYPE_PRODUCT
				|| $arItem['CATALOG_TYPE'] == CCatalogProduct::TYPE_SET)
		) {
			CIBlockPriceTools::setRatioMinPrice($arItem, false);
			$arItem['MIN_BASIS_PRICE'] = $arItem['MIN_PRICE'];
		}

		if (!empty($arItem['DISPLAY_PROPERTIES'])) {
			foreach ($arItem['DISPLAY_PROPERTIES'] as $propKey => $arDispProp) {
				if ('F' == $arDispProp['PROPERTY_TYPE'])
					unset($arItem['DISPLAY_PROPERTIES'][$propKey]);
			}
		}
		$arItem['LAST_ELEMENT'] = 'N';

		if (
			$arResult['MODULES']['catalog']
			&& $arItem['CATALOG']
			&&
			($arItem['CATALOG_TYPE'] == CCatalogProduct::TYPE_PRODUCT
				|| $arItem['CATALOG_TYPE'] == CCatalogProduct::TYPE_SET)
		) {
			CIBlockPriceTools::setRatioMinPrice($arItem, false);
			$arItem['MIN_BASIS_PRICE'] = $arItem['MIN_PRICE'];
		}
		/*                if ('Y' == $arParams['USE_PRICE_COUNT']) {
							foreach ($arItem["PRICE_MATRIX"]["MATRIX"] as $price) {
								if ($arItem["DISCOUNT_PRICE"] > $price[0]["DISCOUNT_PRICE"] || !$arItem["DISCOUNT_PRICE"])
									$arItem["MIN_PRICE"] = array(
										"VALUE" => $price[0]["PRICE"],
										"PRINT_VALUE" => CurrencyFormat($price[0]["PRICE"], $price[0]["CURRENCY"]),
										"DISCOUNT_VALUE" => $price[0]["DISCOUNT_PRICE"],
										"PRINT_DISCOUNT_VALUE" => CurrencyFormat($price[0]["DISCOUNT_PRICE"], $price[0]["CURRENCY"])
									);
							}
						}*/
		$arNewItemsList[$key] = $arItem;

		$arIds[$arItem['ID']] = $arItem['ID'];
	}

	$arNewItemsList[$key]['LAST_ELEMENT'] = 'Y';
	$arResult['ITEMS'] = $arNewItemsList;

	/*start modifier bprops*/
	if (!empty($arIds)) {
		$arSectionBasketProps = Alexkova\Market\Basket::getSectionsBasketProps($arIds, $arParams["IBLOCK_ID"]);
		$arBasketProps = $arSectionBasketProps["BASKET_PROPS"];
		$arRealSectionIds = $arSectionBasketProps["REAL_SECTION_ID"];
		// Проверим, куплен ли доступ к предложению
		$accessOfferList = array_diff($arIds, ['']);
		if (!empty($accessOfferList)) {
			$date = new \Bitrix\Main\Type\DateTime();
			$r = $accessStorage::getList([
				'select' => [
					'UF_ENTITY_ID',
					'UF_DATE_FROM',
					'UF_DATE_TO',
				],
				'filter' => [
					'=UF_ENTITY_CODE' => 'PRODUCT',
					'=UF_ENTITY_ID'   => array_keys($accessOfferList),
					'=UF_USER_ID'     => $USER->GetID(),
				],
			]);
			while ($i = $r->fetch()) {
				if ($i['UF_DATE_FROM'] > $date || $i['UF_DATE_TO'] < $date) {
					$accessOfferList[$i['UF_ENTITY_ID']] = false;
					continue;
				}
				$accessOfferList[$i['UF_ENTITY_ID']] = true;
				unset($arIds[$i['UF_ENTITY_ID']]);
			}
		}
		// https://megre.bitrix24.ru/workgroups/group/9/tasks/task/view/18119/
		// Установим остаток товара согласно выбранному складу
		$quantityInStore = Product::getInstance()->getQuantityProductsByStoreCode(array_keys($arIds), Location::MSK);
		foreach ($arResult['ITEMS'] as &$arItem) {
			if (isset($accessOfferList[$arItem['ID']]) && $accessOfferList[$arItem['ID']] === true) {
				$arItem['HAS_ACCESS_TO_PRODUCT'] = true;
				$arItem['CATALOG_QUANTITY'] = 99999999999;
				$arItem['PRODUCT']['QUANTITY'] = 99999999999;
				continue;
			}
			if (isset($quantityInStore[$arItem['ID']])) {
				if (Location::getCurrentCityCode() === Location::MSK) {
					$arItem['CATALOG_QUANTITY'] = $quantityInStore[$arItem['ID']];
					$arItem['PRODUCT']['QUANTITY'] = $quantityInStore[$arItem['ID']];
				} else if ($quantityInStore[$arItem['ID']] > -1) {
					$arItem['CATALOG_QUANTITY'] -= $quantityInStore[$arItem['ID']];
					$arItem['PRODUCT']['QUANTITY'] -= $quantityInStore[$arItem['ID']];
				}
			}

			$arReq = $arBasketProps[$arRealSectionIds[$arItem["ID"]]]["REQUIRED"];
			foreach ($arReq as $pCode) {
				$arProp = $arItem["PROPERTIES"][$pCode];
				if ($arProp["MULTIPLE"] == "Y" && $arProp["VALUE"])
					$arItem["BASKET_PROPS"]["REQUIRED_CHECK"][$pCode] = [
						"ID"    => $arProp["ID"],
						"CODE"  => $arProp["CODE"],
						"NAME"  => $arProp["NAME"],
						"SORT"  => $arProp["SORT"],
						"VALUE" => $arProp["VALUE"],
					];
			}
			$arOpt = $arBasketProps[$arRealSectionIds[$arItem["ID"]]]["OPTIONAL"];
			foreach ($arOpt as $pCode) {
				$arProp = $arItem["PROPERTIES"][$pCode];
				if ($arProp["MULTIPLE"] == "Y" && $arProp["VALUE"])
					$arItem["BASKET_PROPS"]["OPTIONAL_CHECK"][$pCode] = [
						"ID"    => $arProp["ID"],
						"CODE"  => $arProp["CODE"],
						"NAME"  => $arProp["NAME"],
						"SORT"  => $arProp["SORT"],
						"VALUE" => $arProp["VALUE"],
					];
			}
		}

	}
	/*end modifier bprops*/


	$arResult['SKU_PROPS'] = $arSKUPropList;
	$arResult['DEFAULT_PICTURE'] = $arEmptyPreview;

	$arResult['CURRENCIES'] = [];
	if ($arResult['MODULES']['currency']) {
		if ($boolConvert) {
			$currencyFormat = CCurrencyLang::GetFormatDescription($arResult['CONVERT_CURRENCY']['CURRENCY_ID']);
			$arResult['CURRENCIES'] = [
				[
					'CURRENCY' => $arResult['CONVERT_CURRENCY']['CURRENCY_ID'],
					'FORMAT'   => [
						'FORMAT_STRING'     => $currencyFormat['FORMAT_STRING'],
						'DEC_POINT'         => $currencyFormat['DEC_POINT'],
						'THOUSANDS_SEP'     => $currencyFormat['THOUSANDS_SEP'],
						'DECIMALS'          => $currencyFormat['DECIMALS'],
						'THOUSANDS_VARIANT' => $currencyFormat['THOUSANDS_VARIANT'],
						'HIDE_ZERO'         => $currencyFormat['HIDE_ZERO'],
					],
				],
			];
			unset($currencyFormat);
		} else {
			$currencyIterator = CurrencyTable::getList([
				'select' => ['CURRENCY'],
			]);
			while ($currency = $currencyIterator->fetch()) {
				$currencyFormat = CCurrencyLang::GetFormatDescription($currency['CURRENCY']);
				$arResult['CURRENCIES'][] = [
					'CURRENCY' => $currency['CURRENCY'],
					'FORMAT'   => [
						'FORMAT_STRING'     => $currencyFormat['FORMAT_STRING'],
						'DEC_POINT'         => $currencyFormat['DEC_POINT'],
						'THOUSANDS_SEP'     => $currencyFormat['THOUSANDS_SEP'],
						'DECIMALS'          => $currencyFormat['DECIMALS'],
						'THOUSANDS_VARIANT' => $currencyFormat['THOUSANDS_VARIANT'],
						'HIDE_ZERO'         => $currencyFormat['HIDE_ZERO'],
					],
				];
			}
			unset($currencyFormat, $currency, $currencyIterator);
		}
	}

	$arResult['SKU_PROPS'] = $arSKUPropList;
	$arResult['DEFAULT_PICTURE'] = $arEmptyPreview;

	$temp = [];
	$i = 0;
	foreach ($arParams["MY_SORT"] as $k => $v) {
		foreach ($arResult["ITEMS"] as $kk => $vv) {
			if ($vv["ID"] == $v) {
				$temp = $arResult["ITEMS"][$i];
				$arResult["ITEMS"][$i] = $arResult["ITEMS"][$kk];
				$arResult["ITEMS"][$kk] = $temp;
				++$i;
				break;
			}
		}
	}

}