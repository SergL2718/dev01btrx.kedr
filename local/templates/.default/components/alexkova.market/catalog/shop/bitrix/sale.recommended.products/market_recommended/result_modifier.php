<?php
/*
 * Изменено: 11 августа 2021, среда
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */

/** @global CDatabase $DB */

use Native\App\Catalog\Product;
use Native\App\Sale\Location;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die;

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

$arParams['TEMPLATE_THEME'] = (string)($arParams['TEMPLATE_THEME']);
if ('' != $arParams['TEMPLATE_THEME']) {
    $arParams['TEMPLATE_THEME'] = preg_replace('/[^a-zA-Z0-9_\-\(\)\!]/', '', $arParams['TEMPLATE_THEME']);
    if ('site' == $arParams['TEMPLATE_THEME']) {
        $arParams['TEMPLATE_THEME'] = COption::GetOptionString('main', 'wizard_eshop_adapt_theme_id', 'blue', SITE_ID);
    }
    if ('' != $arParams['TEMPLATE_THEME']) {
        if (!is_file($_SERVER['DOCUMENT_ROOT'] . $this->GetFolder() . '/themes/' . $arParams['TEMPLATE_THEME'] . '/style.css'))
            $arParams['TEMPLATE_THEME'] = '';
    }
}
if ('' == $arParams['TEMPLATE_THEME'])
    $arParams['TEMPLATE_THEME'] = 'blue';

if (isset($arResult['ITEMS']) && !empty($arResult['ITEMS'])) {

    $arEmptyPreview = false;
    $strEmptyPreview = $this->GetFolder() . '/images/no_photo.png';
    if (file_exists($_SERVER['DOCUMENT_ROOT'] . $strEmptyPreview)) {
        $arSizes = getimagesize($_SERVER['DOCUMENT_ROOT'] . $strEmptyPreview);
        if (!empty($arSizes)) {
            $arEmptyPreview = array(
                'SRC' => $strEmptyPreview,
                'WIDTH' => intval($arSizes[0]),
                'HEIGHT' => intval($arSizes[1])
            );
        }
        unset($arSizes);
    }
    unset($strEmptyPreview);

    $arSKUPropList = array();
    $arSKUPropIDs = array();
    $arSKUPropKeys = array();
    $boolSKU = false;
    $strBaseCurrency = '';
    $boolConvert = isset($arResult['CONVERT_CURRENCY']['CURRENCY_ID']);

    //
    $skuPropList = array(); // array("id_catalog" => array(...))
    $skuPropIds = array(); // array("id_catalog" => array(...))
    $skuPropKeys = array(); // array("id_catalog" => array(...))

    if (!$boolConvert)
        $strBaseCurrency = CCurrency::GetBaseCurrency();

    $catalogs = array();
    foreach ($arResult['CATALOGS'] as $catalog) {
        $offersCatalogId = (int)$catalog['OFFERS_IBLOCK_ID'];
        $offersPropId = (int)$catalog['OFFERS_PROPERTY_ID'];
        $catalogId = (int)$catalog['IBLOCK_ID'];
        $sku = false;
        if ($offersCatalogId > 0 && $offersPropId > 0)
            $sku = array("IBLOCK_ID" => $offersCatalogId, "SKU_PROPERTY_ID" => $offersPropId, "PRODUCT_IBLOCK_ID" => $catalogId);


        if (!empty($sku) && is_array($sku)) {
            $skuPropList[$catalogId] = CIBlockPriceTools::getTreeProperties(
                $sku,
                $arParams['OFFER_TREE_PROPS'][$offersCatalogId],
                array(
                    'PICT' => $arEmptyPreview,
                    'NAME' => '-'
                )
            );

            $needValues = array();
            CIBlockPriceTools::getTreePropertyValues($skuPropList[$catalogId], $needValues);

            $skuPropIds[$catalogId] = array_keys($skuPropList[$catalogId]);
            if (!empty($skuPropIds[$catalogId]))
                $skuPropKeys[$catalogId] = array_fill_keys($skuPropIds[$catalogId], false);
        }
    }

    $arNewItemsList = array();
    $filter = [];

    foreach ($arResult['ITEMS'] as $key => $arItem) {

        // Доработка функционала
        // Скрываем товар из каталога
        // https://megre.bitrix24.ru/workgroups/group/9/tasks/task/view/10803/
        if ($arItem['PROPERTIES']['HIDDEN']['VALUE_XML_ID'] === 'Y') {
            unset($arResult['ITEMS'][$key]);
            continue;
        }

        // https://megre.bitrix24.ru/workgroups/group/9/tasks/task/view/7595/
        if ($arItem['CATALOG_CAN_BUY_ZERO'] === 'Y' && $arItem['CATALOG_QUANTITY'] <= 0) {
            $arItem['CATALOG_QUANTITY'] = 9999999;
        }

        if (mb_strpos($arItem['DETAIL_PAGE_URL'], 'skrytyy_razdel') !== false) {
            $filter[$key] = $arItem['NAME'];
        }

        $arItem['CATALOG_QUANTITY'] = (
        0 < $arItem['CATALOG_QUANTITY'] && is_float($arItem['CATALOG_MEASURE_RATIO'])
            ? floatval($arItem['CATALOG_QUANTITY'])
            : intval($arItem['CATALOG_QUANTITY'])
        );
        $arItem['CATALOG'] = false;
        $arItem['LABEL'] = false;
        if (!isset($arItem['CATALOG_SUBSCRIPTION']) || 'Y' != $arItem['CATALOG_SUBSCRIPTION'])
            $arItem['CATALOG_SUBSCRIPTION'] = 'N';

        // Item Label Properties
        $itemIblockId = $arItem['IBLOCK_ID'];
        $propertyName = isset($arParams['LABEL_PROP'][$itemIblockId]) ? $arParams['LABEL_PROP'][$itemIblockId] : false;

        if ($propertyName && isset($arItem['PROPERTIES'][$propertyName])) {
            $property = $arItem['PROPERTIES'][$propertyName];

            if (!empty($property['VALUE'])) {
                if ('N' == $property['MULTIPLE'] && 'L' == $property['PROPERTY_TYPE'] && 'C' == $property['LIST_TYPE']) {
                    $arItem['LABEL_VALUE'] = $property['NAME'];
                } else {
                    $arItem['LABEL_VALUE'] = (is_array($property['VALUE'])
                        ? implode(' / ', $property['VALUE'])
                        : $property['VALUE']
                    );
                }
                $arItem['LABEL'] = true;

                if (isset($arItem['DISPLAY_PROPERTIES'][$propertyName]))
                    unset($arItem['DISPLAY_PROPERTIES'][$propertyName]);
            }
            unset($property);
        }
        // !Item Label Properties

        // item double images
        $productPictures = array(
            "PICT" => false,
            "SECOND_PICT" => false
        );

        if (isset($arParams['ADDITIONAL_PICT_PROP'][$itemIblockId])) {
            $productPictures = CIBlockPriceTools::getDoublePicturesForItem($arItem, $arParams['ADDITIONAL_PICT_PROP'][$itemIblockId]);
        } else {
            $productPictures = CIBlockPriceTools::getDoublePicturesForItem($arItem, false);
        }
        if (empty($productPictures['PICT']))
            $productPictures['PICT'] = $arEmptyPreview;
        if (empty($productPictures['SECOND_PICT']))
            $productPictures['SECOND_PICT'] = $productPictures['PICT'];
        $arItem['PREVIEW_PICTURE'] = $productPictures['PICT'];
        $arItem['PREVIEW_PICTURE_SECOND'] = $productPictures['SECOND_PICT'];
        $arItem['SECOND_PICT'] = true;
        $arItem['PRODUCT_PREVIEW'] = $productPictures['PICT'];
        $arItem['PRODUCT_PREVIEW_SECOND'] = $productPictures['SECOND_PICT'];
        // !item double images

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
                $arItem['OFFERS'] = array();
                $arItem['CATALOG_MEASURE_RATIO'] = 1;
                $arItem['CATALOG_QUANTITY'] = 0;
                $arItem['CHECK_QUANTITY'] = false;
                break;
            case CCatalogProduct::TYPE_SKU:
                break;
            case CCatalogProduct::TYPE_PRODUCT:
            default:
                $arItem['CHECK_QUANTITY'] = ('Y' == $arItem['CATALOG_QUANTITY_TRACE'] && 'N' == $arItem['CATALOG_CAN_BUY_ZERO']);
                break;
        }

        // Offers
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
                            '=UF_ENTITY_ID' => array_keys($accessOfferList),
                            '=UF_USER_ID' => $USER->GetID(),
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

            $arSKUPropIDs = isset($skuPropIds[$arItem['IBLOCK_ID']]) ? $skuPropIds[$arItem['IBLOCK_ID']] : array();
            $arSKUPropList = isset($skuPropList[$arItem['IBLOCK_ID']]) ? $skuPropList[$arItem['IBLOCK_ID']] : array();
            $arSKUPropKeys = isset($skuPropKeys[$arItem['IBLOCK_ID']]) ? $skuPropKeys[$arItem['IBLOCK_ID']] : array();

            $arMatrixFields = $arSKUPropKeys;
            $arMatrix = array();

            $arNewOffers = array();
            $boolSKUDisplayProperties = false;
            $arItem['OFFERS_PROP'] = false;

            foreach ($arItem['OFFERS'] as $keyOffer => $arOffer) {
                $arRow = array();
                foreach ($arSKUPropIDs as $propkey => $strOneCode) {
                    $arCell = array(
                        'VALUE' => 0,
                        'SORT' => PHP_INT_MAX,
                        'NA' => true
                    );

                    if (isset($arOffer['DISPLAY_PROPERTIES'][$strOneCode])) {
                        $arMatrixFields[$strOneCode] = true;
                        $arCell['NA'] = false;
                        if ('directory' == $arSKUPropList[$strOneCode]['USER_TYPE']) {
                            $intValue = $arSKUPropList[$strOneCode]['XML_MAP'][$arOffer['DISPLAY_PROPERTIES'][$strOneCode]['VALUE']];
                            $arCell['VALUE'] = $intValue;
                        } elseif ('L' == $arSKUPropList[$strOneCode]['PROPERTY_TYPE']) {
                            $arCell['VALUE'] = intval($arOffer['DISPLAY_PROPERTIES'][$strOneCode]['VALUE_ENUM_ID']);
                        } elseif ('E' == $arSKUPropList[$strOneCode]['PROPERTY_TYPE']) {
                            $arCell['VALUE'] = intval($arOffer['DISPLAY_PROPERTIES'][$strOneCode]['VALUE']);
                        }
                        $arCell['SORT'] = $arSKUPropList[$strOneCode]['VALUES'][$arCell['VALUE']]['SORT'];
                    }
                    $arRow[$strOneCode] = $arCell;
                }
                $arMatrix[$keyOffer] = $arRow;

                $newOfferProps = array();
                if (!empty($arParams['PROPERTY_CODE'][$arOffer['IBLOCK_ID']])) {
                    foreach ($arParams['PROPERTY_CODE'][$arOffer['IBLOCK_ID']] as $propName)
                        $newOfferProps[$propName] = $arOffer['DISPLAY_PROPERTIES'][$propName];
                }
                $arOffer['DISPLAY_PROPERTIES'] = $newOfferProps;

                $arOffer['CHECK_QUANTITY'] = ('Y' == $arOffer['CATALOG_QUANTITY_TRACE'] && 'N' == $arOffer['CATALOG_CAN_BUY_ZERO']);
                if (!isset($arOffer['CATALOG_MEASURE_RATIO']))
                    $arOffer['CATALOG_MEASURE_RATIO'] = 1;
                if (!isset($arOffer['CATALOG_QUANTITY']))
                    $arOffer['CATALOG_QUANTITY'] = 0;
                $arOffer['CATALOG_QUANTITY'] = (
                0 < $arOffer['CATALOG_QUANTITY'] && is_float($arOffer['CATALOG_MEASURE_RATIO'])
                    ? floatval($arOffer['CATALOG_QUANTITY'])
                    : intval($arOffer['CATALOG_QUANTITY'])
                );
                $arOffer['CATALOG_TYPE'] = CCatalogProduct::TYPE_OFFER;
                CIBlockPriceTools::setRatioMinPrice($arOffer);

                $offerPictures = CIBlockPriceTools::getDoublePicturesForItem($arOffer, $arParams['ADDITIONAL_PICT_PROP'][$arOffer['IBLOCK_ID']]);
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
                $arNewOffers[$keyOffer] = $arOffer;
            }
            $arItem['OFFERS'] = $arNewOffers;

            $arUsedFields = array();
            $arSortFields = array();

            foreach ($arSKUPropIDs as $propkey => $strOneCode) {
                $boolExist = $arMatrixFields[$strOneCode];
                foreach ($arMatrix as $keyOffer => $arRow) {
                    if ($boolExist) {
                        if (!isset($arItem['OFFERS'][$keyOffer]['TREE']))
                            $arItem['OFFERS'][$keyOffer]['TREE'] = array();
                        $arItem['OFFERS'][$keyOffer]['TREE']['PROP_' . $arSKUPropList[$strOneCode]['ID']] = $arMatrix[$keyOffer][$strOneCode]['VALUE'];
                        $arItem['OFFERS'][$keyOffer]['SKU_SORT_' . $strOneCode] = $arMatrix[$keyOffer][$strOneCode]['SORT'];
                        $arUsedFields[$strOneCode] = true;
                        $arSortFields['SKU_SORT_' . $strOneCode] = SORT_NUMERIC;
                    } else {
                        unset($arMatrix[$keyOffer][$strOneCode]);
                    }
                }
            }
            $arItem['OFFERS_PROP'] = $arUsedFields;

            \Bitrix\Main\Type\Collection::sortByColumn($arItem['OFFERS'], $arSortFields);

            // Find Selected offer
            foreach ($arItem['OFFERS'] as $ind => $offer)
                if ($offer['SELECTED']) {
                    $arItem['OFFERS_SELECTED'] = $ind;
                    break;
                }

            $arMatrix = array();
            $intSelected = -1;
            $arItem['MIN_PRICE'] = false;
            foreach ($arItem['OFFERS'] as $keyOffer => $arOffer) {
                if (empty($arItem['MIN_PRICE']) && $arOffer['CAN_BUY']) {
                    $intSelected = $keyOffer;
                    $arItem['MIN_PRICE'] = (isset($arOffer['RATIO_PRICE']) ? $arOffer['RATIO_PRICE'] : $arOffer['MIN_PRICE']);
                }
                $arSKUProps = false;
                if (!empty($arOffer['DISPLAY_PROPERTIES'])) {
                    $boolSKUDisplayProperties = true;
                    $arSKUProps = array();
                    foreach ($arOffer['DISPLAY_PROPERTIES'] as &$arOneProp) {
                        if ('F' == $arOneProp['PROPERTY_TYPE'])
                            continue;
                        $arSKUProps[] = array(
                            'NAME' => $arOneProp['NAME'],
                            'VALUE' => $arOneProp['DISPLAY_VALUE']
                        );
                    }
                    unset($arOneProp);
                }

                $arOneRow = array(
                    'ID' => $arOffer['ID'],
                    'NAME' => $arOffer['~NAME'],
                    'TREE' => $arOffer['TREE'],
                    'DISPLAY_PROPERTIES' => $arSKUProps,
                    'PRICE' => (isset($arOffer['RATIO_PRICE']) ? $arOffer['RATIO_PRICE'] : $arOffer['MIN_PRICE']),
                    'SECOND_PICT' => $arOffer['SECOND_PICT'],
                    'OWNER_PICT' => $arOffer['OWNER_PICT'],
                    'PREVIEW_PICTURE' => $arOffer['PREVIEW_PICTURE'],
                    'PREVIEW_PICTURE_SECOND' => $arOffer['PREVIEW_PICTURE_SECOND'],
                    'CHECK_QUANTITY' => $arOffer['CHECK_QUANTITY'],
                    'MAX_QUANTITY' => $arOffer['CATALOG_QUANTITY'],
                    'STEP_QUANTITY' => $arOffer['CATALOG_MEASURE_RATIO'],
                    'QUANTITY_FLOAT' => is_double($arOffer['CATALOG_MEASURE_RATIO']),
                    'MEASURE' => $arOffer['~CATALOG_MEASURE_NAME'],
                    'CAN_BUY' => $arOffer['CAN_BUY'],
                    'BUY_URL' => $arOffer['~BUY_URL'],
                    'ADD_URL' => $arOffer['~ADD_URL'],
                );
                $arMatrix[$keyOffer] = $arOneRow;
            }

            if (-1 == $intSelected)
                $intSelected = 0;
            if (!$arMatrix[$intSelected]['OWNER_PICT']) {
                $arItem['PREVIEW_PICTURE'] = $arMatrix[$intSelected]['PREVIEW_PICTURE'];
                $arItem['PREVIEW_PICTURE_SECOND'] = $arMatrix[$intSelected]['PREVIEW_PICTURE_SECOND'];
            }
            $arItem['JS_OFFERS'] = $arMatrix;
            //$arItem['OFFERS_SELECTED'] = $intSelected;
            $arItem['OFFERS_PROPS_DISPLAY'] = $boolSKUDisplayProperties;
        }

        if ($arItem['CATALOG'] && CCatalogProduct::TYPE_PRODUCT == $arItem['CATALOG_TYPE']) {
            CIBlockPriceTools::setRatioMinPrice($arItem, true);
        }

        if (!empty($arItem['DISPLAY_PROPERTIES'])) {
            foreach ($arItem['DISPLAY_PROPERTIES'] as $propKey => $arDispProp) {
                if ('F' == $arDispProp['PROPERTY_TYPE'])
                    unset($arItem['DISPLAY_PROPERTIES'][$propKey]);
            }
        }
        $arItem['LAST_ELEMENT'] = 'N';
        $arNewItemsList[$key] = $arItem;
        $arIds[$arItem['ID']] = $arItem['ID'];
    }

    $arNewItemsList[$key]['LAST_ELEMENT'] = 'Y';
    $arResult['ITEMS'] = $arNewItemsList;
    $arResult['SKU_PROPS'] = $skuPropList;
    $arResult['DEFAULT_PICTURE'] = $arEmptyPreview;

    if (!empty($arIds)) {
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
                    '=UF_ENTITY_ID' => array_keys($accessOfferList),
                    '=UF_USER_ID' => $USER->GetID(),
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
        }
    }

    if ($filter) {
        $arResult['ITEMS'] = array_diff_key($arResult['ITEMS'], $filter);
    }
}
