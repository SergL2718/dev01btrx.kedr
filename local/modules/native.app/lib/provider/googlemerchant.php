<?php
/*
 * Изменено: 24 августа 2021, вторник
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

final class GoogleMerchant
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
		$filter = [
			'=ACTIVE'     => 'Y',
			'=IBLOCK_ID'  => self::$arParams['IBLOCK_ID'],
			'=SECTION_ID' => array_keys(self::$arSections),
		];
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
			$a['CATEGORY_ID'] = self::$arSections[$a['IBLOCK_SECTION_ID']]['ID'];
			$a['CATEGORY_NAME'] = self::$arSections[$a['IBLOCK_SECTION_ID']]['NAME'];
			self::$arProducts[$a['ID']] = $a;
		}
		$r = StoreProductTable::getlist([
			'select' => [
				'PRODUCT_ID',
				'AMOUNT',
				'STORE_ID',
			],
			'filter' => [
				'=PRODUCT_ID' => array_keys(self::$arProducts),
				'=STORE_ID'   => self::$arParams['STORE_ID'],
			],
		]);
		if ($r->getSelectedRowsCount() === 0) {
			return false;
		}
		while ($a = $r->fetch()) {
			$price = \CCatalogProduct::GetOptimalPrice($a['PRODUCT_ID']);
			self::$arProducts[$a['PRODUCT_ID']]['PRICE'] = sprintf('%.2f', $price['RESULT_PRICE']['BASE_PRICE']);
			if ($price['RESULT_PRICE']['BASE_PRICE'] != $price['RESULT_PRICE']['DISCOUNT_PRICE']) {
				self::$arProducts[$a['PRODUCT_ID']]['SALE_PRICE'] = sprintf('%.2f', $price['RESULT_PRICE']['DISCOUNT_PRICE']);
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
		$xml = '<rss xmlns:g="http://base.google.com/ns/1.0" version="2.0">';
		$xml .= '<channel>';
		$xml .= '<title>Звенящие Кедры России - интернет-магазин продукции родовых поместий</title>';
		$xml .= '<link>https://' . $_SERVER['SERVER_NAME'] . '</link>';
		$xml .= '<description>Звенящие Кедры России - интернет-магазин продукции родовых поместий. Узнать подробнее, посмотреть цены и купить продукцию с доставкой по России можете на сайте.</description>';
		foreach (self::$arProducts as $item) {
			$xml .= '<item>';
			$xml .= '<link>' . $item['DETAIL_PAGE_URL'] . '</link>';
			$xml .= '<g:id>' . $item['ID'] . '</g:id>';
			$xml .= '<title>' . $item['NAME'] . '</title>';
			$xml .= '<g:price>' . $item['PRICE'] . ' ' . $item['CURRENCY'] . '</g:price>';
			if ($item['SALE_PRICE'] > 0) {
				$xml .= '<g:sale_price>' . $item['SALE_PRICE'] . ' ' . $item['CURRENCY'] . '</g:sale_price>';
			}
			$xml .= '<g:image_link>' . $item['DETAIL_PICTURE'] . '</g:image_link>';
			$xml .= '<g:description>' . self::prepareDescription($item['DETAIL_TEXT'], $item['DETAIL_TEXT_TYPE']) . '</g:description>';
			$xml .= '<g:product_type>Главная > ' . $item['CATEGORY_NAME'] . '</g:product_type>';
			$xml .= '<g:availability>' . ($item['STORE'] > 0 ? 'in_stock' : 'out_of_stock') . '</g:availability>';
			$xml .= '<g:shipping_weight>' . $item['WEIGHT'] . ' g</g:shipping_weight>';
			$xml .= '<g:shipping_length>' . ($item['LENGTH'] / 10) . ' cm</g:shipping_length>';
			$xml .= '<g:shipping_width>' . ($item['WIDTH'] / 10) . ' cm</g:shipping_width>';
			$xml .= '<g:shipping_height>' . ($item['HEIGHT'] / 10) . ' cm</g:shipping_height>';
			$xml .= '<g:ships_from_country>RU</g:ships_from_country>';
			$xml .= '<g:condition>new</g:condition>';
			$xml .= '<g:identifier_exists>no</g:identifier_exists>';
			$xml .= '</item>';
		}
		$xml .= '</channel>';
		$xml .= '</rss>';
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
}