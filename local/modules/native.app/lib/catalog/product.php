<?php
/*
 * Изменено: 08 ноября 2021, понедельник
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */


namespace Native\App\Catalog;


use Bitrix\Catalog\Model\Price;
use Bitrix\Catalog\ProductTable;
use Bitrix\Catalog\StoreProductTable;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\ObjectNotFoundException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;
use Native\App\Helper;
use Native\App\Sale\Store;

class Product
{
	const TYPE_INTERNET = Helper::TYPE_INTERNET;
	const TYPE_RETAIL   = Helper::TYPE_RETAIL;
	const TYPE_COMBINE  = Helper::TYPE_COMBINE;
	private static ?Product $instance = null;
	private static array    $products = [];
	/**
	 * @deprecated since 2021-09-21
	 */
	private array $cache = [];

	/**
	 * @param int $id
	 *
	 * @return array|false|mixed
	 * @throws ArgumentException
	 * @throws ObjectNotFoundException
	 * @throws ObjectPropertyException
	 * @throws SystemException
	 * @throws LoaderException
	 */
	public static function getById (int $id)
	{
		if (isset(self::$products[$id]['ID'])) {
			return self::$products[$id];
		}
		Loader::includeModule('iblock');
		Loader::includeModule('catalog');
		$product = \Bitrix\Iblock\Elements\ElementCatalogTable::getList([
			'select' => [
				'ID',
				'NAME',
				'CODE',
				'PREVIEW_PICTURE',
				'DETAIL_PICTURE',
				'DETAIL_PAGE_URL'         => 'IBLOCK.DETAIL_PAGE_URL',
				'SECTION_CODE'            => 'IBLOCK_SECTION.CODE',
				'PROPERTY_NUMBER_BONUSES' => 'NUMBER_BONUSES.VALUE',
				'PROPERTY_PROIZVODITEL'   => 'PROIZVODITEL.ITEM.VALUE',
			],
			'filter' => [
				'=ID' => $id,
			],
			'limit'  => 1,
			'cache'  => [
				'ttl' => 600,
			],
		])->fetchRaw();
		$product['DETAIL_PAGE_URL'] = str_replace(['#SECTION_CODE#', '#ELEMENT_CODE#'], [$product['SECTION_CODE'], $product['CODE']], $product['DETAIL_PAGE_URL']);
		$product['PREVIEW_PICTURE'] = $product['PREVIEW_PICTURE'] > 0 ? $product['PREVIEW_PICTURE'] : $product['DETAIL_PICTURE'];
		unset(
			$product['DETAIL_PICTURE'],
			$product['SECTION_CODE'],
			$product['UALIAS_0'],
			$product['UALIAS_1'],
			$product['UALIAS_2'],
			$product['UALIAS_3'],
			$product['UALIAS_4'],
			$product['UALIAS_5']
		);
		$product['QUANTITY'] = 0;
		$r = StoreProductTable::getList([
			'select' => [
				'PRODUCT_ID',
				'AMOUNT',
				'TYPE'         => 'PRODUCT.TYPE',
				'CAN_BUY_ZERO' => 'PRODUCT.CAN_BUY_ZERO',
				'AVAILABLE'    => 'PRODUCT.AVAILABLE',
			],
			'filter' => [
				'=PRODUCT_ID' => $product['ID'],
				'=STORE_ID'   => Store::getInstance()->getCurrent(),
			],
			'limit'  => 1,
			'cache'  => [
				'ttl' => 600,
			],
		]);
		$r = $r->fetchRaw();
		$product['TYPE'] = $r['TYPE'];
		$product['CAN_BUY_ZERO'] = $r['CAN_BUY_ZERO'];
		$product['AVAILABLE'] = $r['AVAILABLE'];
		switch ($r['TYPE']) {
			case ProductTable::TYPE_PRODUCT:
			case ProductTable::TYPE_OFFER:
				$product['QUANTITY'] = $r['AMOUNT'];
				break;
			case ProductTable::TYPE_SET:
				$product['QUANTITY'] = [];
				$r = \CCatalogProductSet::GetList(
					[],
					[
						'TYPE'     => ProductTable::TYPE_PRODUCT,
						'OWNER_ID' => $product['ID'],
						'!ITEM_ID' => $product['ID'],
					],
					false,
					false,
					[
						'ITEM_ID',
						'QUANTITY',
					]);
				while ($a = $r->Fetch()) {
					// Если хотя бы одного товара в комплекте нет, тогда считаем, что комплект продать не можем
					if ($a['QUANTITY'] <= 0) {
						$product['QUANTITY'] = 0;
						unset($ids);
						break;
					}
					if (isset(self::$products[$a['ITEM_ID']]['QUANTITY'])) {
						$product['QUANTITY'][] = self::$products[$a['ITEM_ID']]['QUANTITY'];
					} else {
						// Если ранее не получали остаток по товару, получим позже
						$ids[] = $a['ITEM_ID'];
					}
				}
				if (!empty($ids)) {
					$r = StoreProductTable::getList([
						'select' => [
							'PRODUCT_ID',
							'AMOUNT',
						],
						'filter' => [
							'=PRODUCT_ID'   => $ids,
							'=STORE_ID'     => Store::getInstance()->getCurrent(),
							'=STORE.ACTIVE' => 'Y',
						],
						'cache'  => [
							'ttl' => 600,
						],
					]);
					while ($a = $r->fetchRaw()) {
						self::$products[$a['PRODUCT_ID']]['QUANTITY'] = $a['AMOUNT'];
						$product['QUANTITY'][] = $a['AMOUNT'];
					}
				}
				$product['QUANTITY'] = min($product['QUANTITY']);
				break;
			case ProductTable::TYPE_SKU:
				$r = \CCatalogSku::getOffersList($product['ID']);
				$r = current($r);
				// Проверим, быть может мы уже ранее получали остаток для этих предложений
				$c = array_intersect_key(self::$products, $r);
				if (!empty($c)) {
					$product['QUANTITY'] = min($c);
					break;
				}
				// Иначе, получим остаток по предложениям
				$product['QUANTITY'] = [];
				$r = StoreProductTable::getList([
					'select' => [
						'PRODUCT_ID',
						'AMOUNT',
					],
					'filter' => [
						'=PRODUCT_ID'   => array_keys($r),
						'=STORE_ID'     => Store::getInstance()->getCurrent(),
						'=STORE.ACTIVE' => 'Y',
					],
					'cache'  => [
						'ttl' => 600,
					],
				]);
				while ($a = $r->fetchRaw()) {
					self::$products[$a['PRODUCT_ID']]['QUANTITY'] = $a['AMOUNT'];
					$product['QUANTITY'][] = $a['AMOUNT'];
				}
				$product['QUANTITY'] = min($product['QUANTITY']);
				break;
		}
		$r = Price::getList([
			'select' => [
				'PRICE',
				'CURRENCY',
			],
			'filter' => [
				'PRODUCT_ID' => $id,
			],
			'limit'  => 1,
			'cache'  => [
				'ttl' => 600,
			],
		]);
		$product = array_merge($product, $r->fetch());
		$product['PRICE_FORMATTED'] = \CCurrencyLang::CurrencyFormat($product['PRICE'], $product['CURRENCY']);
		// https://megre.bitrix24.ru/workgroups/group/9/tasks/task/view/39067/
		if (!$product['PROPERTY_NUMBER_BONUSES'] && $product['PROPERTY_PROIZVODITEL']) {
			$vendor = mb_strtolower($product['PROPERTY_PROIZVODITEL']);
			if ($vendor === 'ооо мегре') {
				$product['PROPERTY_NUMBER_BONUSES'] = $product['PRICE'] * 0.11;
			} else if (mb_strpos($vendor, 'срп ') === false && mb_strpos($vendor, 'рп ') !== false) {
				$product['PROPERTY_NUMBER_BONUSES'] = $product['PRICE'] * 0.06;
			} else {
				$product['PROPERTY_NUMBER_BONUSES'] = $product['PRICE'] * 0.07;
			}
		}
		if ($product['PROPERTY_NUMBER_BONUSES'] > 0) {
			$product['PROPERTY_NUMBER_BONUSES'] = ceil($product['PROPERTY_NUMBER_BONUSES']);
		}
		if (isset(self::$products[$id])) {
			self::$products[$id] = array_merge(self::$products[$id], $product);
		} else {
			self::$products[$id] = $product;
		}
		return self::$products[$id];
	}

	/**
	 * @throws LoaderException
	 * @throws ArgumentException
	 * @throws ObjectNotFoundException
	 * @throws ObjectPropertyException
	 * @throws SystemException
	 */
	public static function getNumberBonuses (int $productId)
	{
		return self::getById($productId)['PROPERTY_NUMBER_BONUSES'];
	}

	/**
	 * @param int $productId
	 * @param int $storeId
	 *
	 * @return mixed
	 * @throws ArgumentException
	 * @throws ObjectPropertyException
	 * @throws SystemException
	 */
	public static function getQuantityByStore (int $productId, int $storeId)
	{
		if (!isset(self::$products[$productId]['QUANTITY'])) {
			self::$products[$productId]['QUANTITY'] = 0;
			$r = StoreProductTable::getList([
				'select' => [
					'PRODUCT_ID',
					'AMOUNT',
					'TYPE' => 'PRODUCT.TYPE',
					//'CAN_BUY_ZERO' => 'PRODUCT.CAN_BUY_ZERO',
				],
				'filter' => [
					'=PRODUCT_ID'   => $productId,
					'=STORE_ID'     => $storeId,
					'=STORE.ACTIVE' => 'Y',
				],
				'cache'  => [
					'ttl' => 600,
				],
			]);
			if ($r->getSelectedRowsCount() === 0) {
				return self::$products[$productId]['QUANTITY'];
			}
			$r = $r->fetchRaw();
			switch ($r['TYPE']) {
				case ProductTable::TYPE_PRODUCT:
				case ProductTable::TYPE_OFFER:
					self::$products[$productId]['QUANTITY'] = $r['AMOUNT'];
					break;
				case ProductTable::TYPE_SET:
					self::$products[$productId]['QUANTITY'] = [];
					$r = \CCatalogProductSet::GetList(
						[],
						[
							'TYPE'     => ProductTable::TYPE_PRODUCT,
							'OWNER_ID' => $productId,
							'!ITEM_ID' => $productId,
						],
						false,
						false,
						[
							'ITEM_ID',
							'QUANTITY',
						]);
					while ($a = $r->Fetch()) {
						// Если хотя бы одного товара в комплекте нет, тогда считаем, что комплект продать не можем
						if ($a['QUANTITY'] <= 0) {
							self::$products[$productId]['QUANTITY'] = 0;
							unset($ids);
							break;
						}
						if (isset(self::$products[$a['ITEM_ID']]['QUANTITY'])) {
							self::$products[$productId]['QUANTITY'][] = self::$products[$a['ITEM_ID']]['QUANTITY'];
						} else {
							// Если ранее не получали остаток по товару, получим позже
							$ids[] = $a['ITEM_ID'];
						}
					}
					if (!empty($ids)) {
						$r = StoreProductTable::getList([
							'select' => [
								'PRODUCT_ID',
								'AMOUNT',
							],
							'filter' => [
								'=PRODUCT_ID'   => $ids,
								'=STORE_ID'     => $storeId,
								'=STORE.ACTIVE' => 'Y',
							],
							'cache'  => [
								'ttl' => 600,
							],
						]);
						while ($a = $r->fetchRaw()) {
							self::$products[$a['PRODUCT_ID']]['QUANTITY'] = $a['AMOUNT'];
							self::$products[$productId]['QUANTITY'][] = $a['AMOUNT'];
						}
					}
					self::$products[$productId]['QUANTITY'] = min(self::$products[$productId]['QUANTITY']);
					break;
				case ProductTable::TYPE_SKU:
					$r = \CCatalogSku::getOffersList($r['PRODUCT_ID']);
					$r = current($r);
					// Проверим, быть может мы уже ранее получали остаток для этих предложений
					$c = array_intersect_key(self::$products, $r);
					if (!empty($c)) {
						self::$products[$productId]['QUANTITY'] = min($c);
						break;
					}
					// Иначе, получим остаток по предложениям
					self::$products[$productId]['QUANTITY'] = [];
					$r = StoreProductTable::getList([
						'select' => [
							'PRODUCT_ID',
							'AMOUNT',
						],
						'filter' => [
							'=PRODUCT_ID'   => array_keys($r),
							'=STORE_ID'     => $storeId,
							'=STORE.ACTIVE' => 'Y',
						],
						'cache'  => [
							'ttl' => 600,
						],
					]);
					while ($a = $r->fetchRaw()) {
						self::$products[$a['PRODUCT_ID']]['QUANTITY'] = $a['AMOUNT'];
						self::$products[$productId]['QUANTITY'][] = $a['AMOUNT'];
					}
					self::$products[$productId]['QUANTITY'] = min(self::$products[$productId]['QUANTITY']);
					break;
			}
			self::$products[$productId]['QUANTITY'] = self::$products[$productId]['QUANTITY'] > 0 ? self::$products[$productId]['QUANTITY'] : 0;
		}
		return self::$products[$productId]['QUANTITY'];
	}

	/* Код использовался в старом шаблоне */

	/**
	 * @param $productIds
	 * @param $storeCode
	 *
	 * @return array
	 * @throws ArgumentException
	 * @throws ObjectPropertyException
	 * @throws SystemException
	 * @deprecated since 2021-09-21
	 */
	public function getQuantityProductsByStoreCode ($productIds, $storeCode): array
	{
		$productId = !is_array($productIds) && $productIds > 0 ? $productIds : false;
		// Проверим наличие остатка товаров в кеше
		if (isset($this->cache['QUANTITY'][$storeCode])) {
			// Если получаем остаток по одному товару
			if ($productId) {
				if ($this->cache['QUANTITY'][$storeCode][$productId]) {
					return $this->cache['QUANTITY'][$storeCode][$productId];
				}
				// Проверим массив товаров в кеше
			} else {
				$cache = array_intersect_key($this->cache['QUANTITY'][$storeCode], array_flip($productIds));
				// Если все товары имеются в кеше, тогда возвратим данные из кеша
				if (count($cache) === count($productIds)) {
					return $cache;
				}
			}
		}
		$storeId = Store::getInstance()->getIdByCode($storeCode);
		if (!$storeId) {
			return [];
		}
		$quantity = [];
		$productList = StoreProductTable::getList([
			'select' => [
				'PRODUCT_ID',
				'AMOUNT',
				'PRODUCT.TYPE',
			],
			'filter' => [
				'=PRODUCT_ID'   => $productIds,
				'=STORE_ID'     => $storeId,
				'=STORE.ACTIVE' => 'Y',
			],
		]);
		while ($item = $productList->fetchRaw()) {
			$total = $this->getAmountByStoreCode($item, $storeCode);
			$quantity[$item['PRODUCT_ID']] = $total;
			$this->cache['QUANTITY'][$storeCode][$item['PRODUCT_ID']] = $total;
		}
		return $quantity;
	}

	/**
	 * @param $product
	 * @param $storeCode
	 *
	 * @return array|float|int|mixed
	 * @throws ArgumentException
	 * @throws ObjectPropertyException
	 * @throws SystemException
	 * @deprecated since 2021-09-21
	 */
	private function getAmountByStoreCode ($product, $storeCode)
	{
		if (!$product['PRODUCT_ID']) {
			return 0;
		}
		$productId =& $product['PRODUCT_ID'];
		$productType =& $product['CATALOG_STORE_PRODUCT_PRODUCT_TYPE'];
		$productList = [];
		switch ($productType) {
			case ProductTable::TYPE_PRODUCT:
			case ProductTable::TYPE_OFFER:
				return $product['AMOUNT'];
			case ProductTable::TYPE_SET:
				$productList = $this->getSet($productId);
				break;
			case ProductTable::TYPE_SKU:
				$productList = $this->getOffers($productId);
				break;
		}
		if (count($productList) === 0) {
			return 0;
		}
		$quantity = $this->getQuantityProductsByStoreCode($productList, $storeCode);
		switch ($productType) {
			case ProductTable::TYPE_SET:
				$quantity = min($quantity);
				break;
			case ProductTable::TYPE_SKU:
				$quantity = array_sum($quantity);
				break;
		}
		if (!$quantity) {
			$quantity = 0;
		}
		return $quantity;
	}

	/**
	 * @param $productId
	 *
	 * @return array
	 * @deprecated since 2021-09-21
	 */
	public function getOffers ($productId): array
	{
		if (isset($this->cache['OFFERS'][$productId])) {
			return $this->cache['OFFERS'][$productId];
		}
		$this->cache['OFFERS'][$productId] = [];
		$productList = \CCatalogSku::getOffersList($productId);
		if ($productList[$productId] && count($productList[$productId]) > 0) {
			foreach ($productList[$productId] as $offer) {
				$this->cache['OFFERS'][$productId][] = $offer['ID'];
			}
		}
		return $this->cache['OFFERS'][$productId];
	}

	public function getSet ($productId): array
	{
		if (isset($this->cache['SET'][$productId])) {
			return $this->cache['SET'][$productId];
		}
		$this->cache['SET'][$productId] = [];
		$productList = \CCatalogProductSet::GetList(
			[],
			[
				'TYPE'     => ProductTable::TYPE_PRODUCT,
				'OWNER_ID' => $productId,
				'!ITEM_ID' => $productId,
			],
			false,
			false,
			[
				'ITEM_ID',
			]);
		while ($product = $productList->Fetch()) {
			$this->cache['SET'][$productId][] = $product['ITEM_ID'];
		}
		return $this->cache['SET'][$productId];
	}

	/**
	 * @return Product|null
	 * @deprecated since 2021-09-21
	 */
	public static function getInstance (): ?Product
	{
		if (is_null(self::$instance)) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	/**
	 * @deprecated since 2021-09-21
	 */
	private function __construct ()
	{
	}

	/**
	 * @param $name
	 * @param $arguments
	 *
	 * @deprecated since 2021-09-21
	 */
	public function __call ($name, $arguments)
	{
		die('Method \'' . $name . '\' is not defined');
	}

	/**
	 * @deprecated since 2021-09-21
	 */
	private function __clone ()
	{
	}
}
