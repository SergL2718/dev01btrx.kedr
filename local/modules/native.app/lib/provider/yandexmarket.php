<?php
/*
 * Изменено: 03 декабря 2021, пятница
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */


namespace Native\App\Provider;


use Bitrix\Catalog\StoreProductTable;
use Bitrix\Catalog\StoreTable;
use Bitrix\Iblock\IblockTable;
use Bitrix\Iblock\SectionTable;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;

final class YandexMarket
{
	private static array $arParams   = [];
	private static array $arSections = [];
	private static array $arProducts = [];

	/**
	 * @throws LoaderException
	 * @throws ArgumentException
	 * @throws ObjectPropertyException
	 * @throws SystemException
	 */
	public static function export (array $arParams): bool
	{
		Loader::includeModule('iblock');
		Loader::includeModule('catalog');
		self::$arParams = $arParams;
		$r = IblockTable::getList([
			'select' => [
				'ID',
			],
			'filter' => [
				'=CODE' => 'catalog',
			],
			'limit'  => 1,
		]);
		if ($r->getSelectedRowsCount() === 0) {
			return false;
		}
		self::$arParams['IBLOCK_ID'] = $r->fetchRaw()['ID'];
		if (self::$arParams['STORE_CODE']) {
			$r = StoreTable::getList([
				'select' => [
					'ID',
				],
				'filter' => [
					'=CODE' => self::$arParams['STORE_CODE'],
				],
				'limit'  => 1,
			]);
			if ($r->getSelectedRowsCount() === 0) {
				return false;
			}
			self::$arParams['STORE_ID'] = $r->fetchRaw()['ID'];
		}
		if (self::$arParams['SECTION_CODE']) {
			$r = SectionTable::getList([
				'select' => [
					'ID',
					'NAME',
				],
				'filter' => [
					'=IBLOCK_ID' => self::$arParams['IBLOCK_ID'],
					'=CODE'      => self::$arParams['SECTION_CODE'],
				],
				'order'  => [
					'NAME' => 'asc',
				],
			]);
			if ($r->getSelectedRowsCount() === 0) {
				return false;
			}
			while ($a = $r->fetchRaw()) {
				self::$arSections[$a['ID']] = $a;
			}
		}
		$filter = [
			'=ACTIVE'    => 'Y',
			'=IBLOCK_ID' => self::$arParams['IBLOCK_ID'],
		];
		if (self::$arParams['ELEMENT_ID']) {
			$filter['=ID'] = self::$arParams['ELEMENT_ID'];
		}
		if (self::$arSections) {
			$filter['=SECTION_ID'] = array_keys(self::$arSections);
		}
		if (self::$arParams['EXCLUDE_ELEMENT_CODE']) {
			$filter['!CODE'] = self::$arParams['EXCLUDE_ELEMENT_CODE'];
		}
		$r = \CIBlockElement::GetList(
			[
				'NAME' => 'ASC',
			],
			$filter,
			false,
			false,
			[
				'ID',
				'NAME',
				'IBLOCK_SECTION_ID',
				'DETAIL_TEXT',
				'DETAIL_PICTURE',
				'DETAIL_PAGE_URL',
				'WEIGHT',
				'WIDTH',
				'LENGTH',
				'HEIGHT',
				'PROPERTY_PROIZVODITEL',
				//'PROPERTY_VENDOR_CODE',
				'PROPERTY_MODEL',
				'PROPERTY_SALES_NOTES',
			]
		);
		if ($r->SelectedRowsCount() === 0) {
			return false;
		}
		while ($a = $r->GetNext()) {
			if ($a['DETAIL_TEXT']) {
				unset($a['DETAIL_TEXT_TYPE']);
			}
			$a['DETAIL_PICTURE'] = 'https://' . $_SERVER['SERVER_NAME'] . \CFile::GetPath($a['DETAIL_PICTURE']);
			$a['DETAIL_PAGE_URL'] = 'https://' . $_SERVER['SERVER_NAME'] . $a['DETAIL_PAGE_URL'];
			self::$arProducts[$a['ID']] = [
				'ID'              => $a['ID'],
				'NAME'            => $a['NAME'],
				'DETAIL_TEXT'     => $a['DETAIL_TEXT'],
				'DETAIL_PICTURE'  => $a['DETAIL_PICTURE'],
				'DETAIL_PAGE_URL' => $a['DETAIL_PAGE_URL'],
				'WEIGHT'          => $a['WEIGHT'],
				'WIDTH'           => $a['WIDTH'],
				'LENGTH'          => $a['LENGTH'],
				'HEIGHT'          => $a['HEIGHT'],
				'VENDOR'          => $a['PROPERTY_PROIZVODITEL_VALUE'] ?? 'ООО Звенящие Кедры',
				//'VENDOR_CODE'     => $a['PROPERTY_VENDOR_CODE_VALUE'],
				'MODEL'           => $a['PROPERTY_MODEL_VALUE'],
				'SALES_NOTES'     => $a['PROPERTY_SALES_NOTES_VALUE'],
				'CATEGORY_ID'     => self::getSection($a['IBLOCK_SECTION_ID'])['ID'],
				'CATEGORY_NAME'   => self::getSection($a['IBLOCK_SECTION_ID'])['NAME'],
			];
		}
		$filter = [
			'=PRODUCT_ID' => array_keys(self::$arProducts),
		];
		if (self::$arParams['STORE_ID']) {
			$filter['=STORE_ID'] = self::$arParams['STORE_ID'];
		}
		$r = StoreProductTable::getlist([
			'select' => [
				'PRODUCT_ID',
				'AMOUNT',
				'STORE_ID',
			],
			'filter' => $filter,
		]);
		if ($r->getSelectedRowsCount() === 0) {
			return false;
		}
		while ($a = $r->fetch()) {
			$price = \CCatalogProduct::GetOptimalPrice($a['PRODUCT_ID']);
			self::$arProducts[$a['PRODUCT_ID']]['PRICE'] = $price['RESULT_PRICE']['BASE_PRICE'];
			if ($price['RESULT_PRICE']['BASE_PRICE'] != $price['RESULT_PRICE']['DISCOUNT_PRICE']) {
				// Скидка в процентах не меньше 5% и не больше 95%. Процент округляется до целого числа.
				$discountPercent = $price['RESULT_PRICE']['DISCOUNT_PRICE'] / $price['RESULT_PRICE']['BASE_PRICE'];
				$discountPercent = round(100 - (($discountPercent) * 100));
				if ($discountPercent > 4 && $discountPercent < 96) {
					self::$arProducts[$a['PRODUCT_ID']]['SALE_PRICE'] = $price['RESULT_PRICE']['DISCOUNT_PRICE'];
				}
			}
			self::$arProducts[$a['PRODUCT_ID']]['CURRENCY'] = $price['RESULT_PRICE']['CURRENCY'];
			self::$arProducts[$a['PRODUCT_ID']]['STORE'] = $a['AMOUNT'];
		}
		header('Content-Type: application/xml; charset=utf-8');
		echo self::getFeed();
		return true;
	}

	private static function getFeed ()
	{
		if (empty(self::$arProducts)) {
			return false;
		}
		$xml = '<?xml version="1.0" encoding="UTF-8"?>';
		$xml .= '<yml_catalog date="' . date('Y-m-d H:i') . '">';
		$xml .= '<shop>';
		$xml .= '<name>Звенящие Кедры России - интернет-магазин продукции родовых поместий</name>';
		$xml .= '<company>ООО Звенящие Кедры</company>';
		$xml .= '<url>https://' . $_SERVER['SERVER_NAME'] . '</url>';
		$xml .= '<platform>1C-Bitrix</platform>';
		$xml .= '<currencies><currency id="RUB" rate="1"/></currencies>';
		$xml .= '<categories>';
		foreach (self::$arSections as $section) {
			$xml .= '<category id="' . $section['ID'] . '">' . $section['NAME'] . '</category>';
		}
		$xml .= '</categories>';
		$xml .= '<offers>';
		foreach (self::$arProducts as $item) {
			$xml .= '<offer id="' . $item['ID'] . '" type="vendor.model" available="' . ($item['STORE'] > 0 ? 'true' : 'false') . '">';
			$xml .= '<url>' . $item['DETAIL_PAGE_URL'] . '?r1=yandext</url>';
			$xml .= '<categoryId>' . $item['CATEGORY_ID'] . '</categoryId>';
			if ($item['CURRENCY']) $xml .= '<currencyId>' . $item['CURRENCY'] . '</currencyId>';
			if ($item['SALE_PRICE'] > 0) {
				$xml .= '<price>' . $item['SALE_PRICE'] . '</price>';
				$xml .= '<oldprice>' . $item['PRICE'] . '</oldprice>';
			} else {
				$xml .= '<price>' . $item['PRICE'] . '</price>';
			}
			if ($item['DETAIL_PICTURE']) $xml .= '<picture>' . $item['DETAIL_PICTURE'] . '</picture>';
			if ($item['CATEGORY_NAME']) $xml .= '<typePrefix>' . $item['CATEGORY_NAME'] . '</typePrefix>';
			if ($item['VENDOR']) $xml .= '<vendor>' . $item['VENDOR'] . '</vendor>';
			//if ($item['VENDOR_CODE']) $xml .= '<vendorCode>' . $item['VENDOR_CODE'] . '</vendorCode>'; ---- Артикул товара
			if ($item['MODEL']) $xml .= '<model>' . $item['MODEL'] . '</model>';
			if ($item['SALES_NOTES']) $xml .= '<sales_notes>' . $item['SALES_NOTES'] . '</sales_notes>';
			$xml .= '<description>' . self::prepareDescription($item['DETAIL_TEXT'], $item['DETAIL_TEXT_TYPE'], 3000) . '</description>';
			if ($item['WEIGHT']) {
				$xml .= '<weight>' . ($item['WEIGHT'] / 1000) . '</weight>';
				$xml .= '<param name="Вес" unit="г">' . $item['WEIGHT'] . '</param>';
			}
			if ($item['WIDTH']) $xml .= '<param name="Ширина" unit="мм">' . $item['WIDTH'] . '</param>';
			if ($item['LENGTH']) $xml .= '<param name="Глубина" unit="мм">' . $item['LENGTH'] . '</param>';
			if ($item['HEIGHT']) $xml .= '<param name="Высота" unit="мм">' . $item['HEIGHT'] . '</param>';
			if ($item['WIDTH'] && $item['LENGTH'] && $item['HEIGHT']) {
				$xml .= '<dimensions>' . ($item['WIDTH'] / 10) . '/' . ($item['LENGTH'] / 10) . '/' . ($item['HEIGHT'] / 10) . '</dimensions>';
			}
			$xml .= '</offer>';
		}
		$xml .= '</offers>';
		$xml .= '</shop>';
		$xml .= '</yml_catalog>';
		return $xml;
	}

	private static function prepareDescription ($text, $type = 'text', $maxLength = 4750, $tags = '')
	{
		$text = \CTextParser::clearAllTags($text);
		$text = str_replace([chr(13), chr(10), chr(9)], ' ', $text);
		if ($type == 'html') {
			$text = strip_tags(preg_replace_callback("'&[^;]*;'", 'yandex_replace_special', $text), $tags);
		} else {
			$text = preg_replace_callback("'&[^;]*;'", 'yandex_replace_special', $text);
		}
		$text = TruncateText($text, $maxLength);
		return self::text2xml($text, (!$tags));
	}

	private static function text2xml ($text = '', $bHSC = false, $bDblQuote = false)
	{
		global $APPLICATION;
		$bHSC = true == $bHSC;
		$bDblQuote = true == $bDblQuote;
		if ($bHSC) {
			$text = htmlspecialcharsbx($text);
			if ($bDblQuote)
				$text = str_replace('&quot;', '"', $text);
		}
		$text = preg_replace("/[\x1-\x8\xB-\xC\xE-\x1F]/", "", $text);
		$text = str_replace("'", "&apos;", $text);
		return $APPLICATION->ConvertCharset($text, LANG_CHARSET, 'utf-8');
	}

	private static function getSection ($id)
	{
		if (self::$arSections[$id]) {
			return self::$arSections[$id];
		}
		if (!self::$arParams['IBLOCK_ID']) {
			return [];
		}
		$r = SectionTable::getList([
			'select' => [
				'ID',
				'NAME',
			],
			'filter' => [
				'=IBLOCK_ID' => self::$arParams['IBLOCK_ID'],
				'=ID'        => $id,
			],
			'limit'  => 1,
		]);
		if ($r->getSelectedRowsCount() === 0) {
			return [];
		}
		$r = $r->fetchRaw();
		self::$arSections[$r['ID']] = $r;
		return self::$arSections[$id];
	}
}