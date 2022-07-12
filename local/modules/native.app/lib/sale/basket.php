<?php
/*
 * Изменено: 24 сентября 2021, пятница
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

namespace Native\App\Sale;


use Bitrix\Main\ArgumentException;
use Bitrix\Main\Context;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\ObjectNotFoundException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\Result;
use Bitrix\Main\SystemException;
use Bitrix\Sale\Fuser;
use Bitrix\Sale\Internals\BasketTable;

class Basket
{
	private static bool $init  = false;
	private static      $count = null;

	/**
	 * @return array
	 * @throws LoaderException
	 */
	public static function getList (): array
	{
		if (self::$init !== true) {
			self::init();
		}
		return $_SESSION['BASKET'];
	}

	/**
	 * @return bool
	 * @throws LoaderException
	 */
	public static function init (): bool
	{
		Loader::includeModule('sale');
		self::$init = true;
		try {
			$r = BasketTable::getList([
				'select' => [
					'ID',
					'PRODUCT_ID',
					'NAME',
					'QUANTITY',
				],
				'filter' => [
					'=FUSER_ID'      => Fuser::getId(),
					'=LID'           => Context::getCurrent()->getSite(),
					'=ORDER_ID'      => false,
					'=SET_PARENT_ID' => false,
				],
			]);
			while ($a = $r->fetchRaw()) {
				$_SESSION['BASKET'][$a['PRODUCT_ID']] = [
					'ITEM_ID'  => $a['ID'],
					'ID'       => $a['PRODUCT_ID'],
					'QUANTITY' => round($a['QUANTITY']),
				];
			}
		}
		catch (ObjectPropertyException | SystemException $e) {
		}
		return true;
	}

	/**
	 * @param int $id
	 * @param int $quantity
	 *
	 * @return Result
	 * @throws LoaderException
	 * @throws ObjectNotFoundException
	 */
	public static function add (int $id, int $quantity = 1): Result
	{
		if($id && $quantity>0) {
			Loader::includeModule('catalog');
			$r = \Bitrix\Catalog\Product\Basket::addProduct([
				'PRODUCT_ID' => $id,
				'QUANTITY' => $quantity,
			]);
			if ($r->isSuccess()) {
				if ($_SESSION['BASKET'][$id]) {
					$_SESSION['BASKET'][$id]['QUANTITY'] += $quantity;
				} else {
					$_SESSION['BASKET'][$id] = [
						'ITEM_ID' => $r->getData()['ID'],
						'ID' => $id,
						'QUANTITY' => $quantity,
					];
				}
			}
			
		}
		return $r;
	}

	/**
	 * @param int $id
	 * @param int $quantity
	 *
	 * @return bool
	 * @throws ArgumentException
	 * @throws LoaderException
	 * @throws ObjectPropertyException
	 * @throws SystemException
	 */
	public static function delete (int $id, int $quantity = 1): bool
	{
		Loader::includeModule('sale');
		$_ITEM_ID = null;
		$_QUANTITY = null;
		if ($_SESSION['BASKET'][$id]) {
			$_ITEM_ID = $_SESSION['BASKET'][$id]['ITEM_ID'];
			$_QUANTITY = $_SESSION['BASKET'][$id]['QUANTITY'];
		} else {
			$r = BasketTable::getList([
				'select' => [
					'ID',
					'QUANTITY',
				],
				'filter' => [
					'=PRODUCT_ID'    => $id,
					'=FUSER_ID'      => Fuser::getId(),
					'=LID'           => Context::getCurrent()->getSite(),
					'=ORDER_ID'      => false,
					'=SET_PARENT_ID' => false,
				],
				'limit'  => 1,
			]);
			if ($r->getSelectedRowsCount() > 0) {
				$r = $r->fetchRaw();
				$_ITEM_ID = $r['ID'];
				$_QUANTITY = $r['QUANTITY'];
			}
		}
		if ($_ITEM_ID > 0) {
			if ($_QUANTITY > 0) {
				$_QUANTITY -= $quantity;
			}
			$r = \CSaleBasket::Update($_ITEM_ID, ['QUANTITY' => $_QUANTITY]);
			if ($r === true) {
				if ($_SESSION['BASKET'][$id]) {
					if ($_QUANTITY === 0) {
						unset($_SESSION['BASKET'][$id]);
					} else {
						$_SESSION['BASKET'][$id]['QUANTITY'] = $_QUANTITY;
					}
				}
				return true;
			}
		}
		return false;
	}

	/**
	 * @return int
	 * @throws ArgumentException
	 * @throws ObjectPropertyException
	 * @throws SystemException
	 */
	public static function count (): int
	{
		if (!empty($_SESSION['BASKET'])) {
			self::$count = count($_SESSION['BASKET']);
		} else {
			$r = BasketTable::getList([
				'select' => [
					'ID',
				],
				'filter' => [
					'=FUSER_ID'      => Fuser::getId(),
					'=LID'           => Context::getCurrent()->getSite(),
					'=ORDER_ID'      => false,
					'=SET_PARENT_ID' => false,
				],
			]);
			self::$count = $r->getSelectedRowsCount();
		}
		return self::$count;
	}
}
