<?php
/*
 * Изменено: 24 сентября 2021, пятница
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

namespace Native\App\Sale;


use Bitrix\Highloadblock\HighloadBlockTable;
use Bitrix\Main\Application;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\SystemException;
use Bitrix\Sale\Fuser;

class Favorites
{
	private static bool $init  = false;
	private static      $count = null;

	/**
	 * @return bool
	 * @throws ArgumentException
	 * @throws LoaderException
	 * @throws ObjectPropertyException
	 * @throws SystemException
	 */
	public static function init (): bool
	{
		Loader::includeModule('sale');
		self::$init = true;
		$r = self::getStorage()::getList([
			'select' => [
				'UF_ID',
				'UF_TYPE',
			],
			'filter' => [
				'=UF_FUSER_ID' => Fuser::getId(),
			],
		]);
		while ($a = $r->fetchRaw()) {
			$_SESSION['FAVORITES'][$a['UF_ID']]['ID'] = $a['UF_ID'];
		}
		return true;
	}

	/**
	 * @return array
	 * @throws ArgumentException
	 * @throws LoaderException
	 * @throws ObjectPropertyException
	 * @throws SystemException
	 */
	public static function getList (): array
	{
		if (self::$init !== true) {
			self::init();
		}
		return $_SESSION['FAVORITES'];
	}

	/**
	 * @param int $id
	 *
	 * @return false|int
	 */
	public static function add (int $id)
	{
		try {
			Loader::includeModule('sale');
			$storage = self::getStorage();
			$r = $storage::getList([
				'select' => [
					'ID',
				],
				'filter' => [
					'=UF_FUSER_ID' => Fuser::getId(),
					'=UF_ID'       => $id,
					'=UF_TYPE'     => 'PRODUCT',
				],
				'limit'  => 1,
			]);
			if ($r->getSelectedRowsCount() > 0) {
				$_SESSION['FAVORITES'][$id]['ID'] = $id;
				return self::count();
			}
			$r = $storage::add([
				'UF_FUSER_ID' => Fuser::getId(),
				'UF_ID'       => $id,
				'UF_TYPE'     => 'PRODUCT',
			]);
			if ($r->isSuccess()) {
				$_SESSION['FAVORITES'][$id]['ID'] = $id;
				return self::count();
			}
		}
		catch (ObjectPropertyException | SystemException | LoaderException | \Exception $e) {
			return false;
		}
		return false;
	}

	/**
	 * @param int $id
	 *
	 * @return false|int
	 * @throws LoaderException
	 */
	public static function delete (int $id)
	{
		Loader::includeModule('sale');
		try {
			$storage = self::getStorage();
			$r = $storage::getList([
				'select' => [
					'ID',
				],
				'filter' => [
					'=UF_FUSER_ID' => Fuser::getId(),
					'=UF_ID'       => $id,
					'=UF_TYPE'     => 'PRODUCT',
				],
				'limit'  => 1,
			]);
			if ($r->getSelectedRowsCount() > 0) {
				$r = $storage::delete($r->fetchRaw()['ID']);
				if ($r->isSuccess()) {
					unset($_SESSION['FAVORITES'][$id]);
					return self::count();
				}
			}
		}
		catch (ObjectPropertyException | SystemException | \Exception $e) {
			return false;
		}
		return false;
	}

	/**
	 * @param int $id
	 *
	 * @return bool
	 * @throws ArgumentException
	 * @throws LoaderException
	 * @throws ObjectPropertyException
	 * @throws SystemException
	 */
	public static function isExists (int $id): bool
	{
		if (isset($_SESSION['FAVORITES'][$id])) {
			return true;
		}
		Loader::includeModule('sale');
		$r = self::getStorage()::getList([
			'select' => [
				'ID',
			],
			'filter' => [
				'=UF_FUSER_ID' => Fuser::getId(),
				'=UF_ID'       => $id,
				'=UF_TYPE'     => 'PRODUCT',
			],
			'limit'  => 1,
		]);
		if ($r->getSelectedRowsCount() > 0) {
			$_SESSION['FAVORITES'][$id]['ID'] = $id;
			return true;
		}
		return false;
	}

	/**
	 * @return int
	 * @throws ArgumentException
	 * @throws LoaderException
	 * @throws ObjectPropertyException
	 * @throws SystemException
	 */
	public static function count (): int
	{
		if (!empty($_SESSION['FAVORITES'])) {
			self::$count = count($_SESSION['FAVORITES']);
		}
		if (self::$count === null) {
			Loader::includeModule('sale');
			$r = self::getStorage()::getList([
				'select' => [
					'ID',
				],
				'filter' => [
					'=UF_FUSER_ID' => Fuser::getId(),
				],
			]);
			self::$count = $r->getSelectedRowsCount();
		}
		return self::$count;
	}

	/**
	 * @return DataManager|false
	 * @throws ArgumentException
	 * @throws LoaderException
	 * @throws ObjectPropertyException
	 * @throws SystemException
	 */
	private static function getStorage ()
	{
		if (Application::getInstance()->getContext()->getRequest()->isAjaxRequest()) {
			Loader::includeModule('highloadblock');
			$r = HighloadBlockTable::getList([
				'select' => [
					'ID',
				],
				'filter' => [
					'=NAME' => 'Favorites',
				],
				'limit'  => 1,
				'cache'  => [
					'ttl' => 86400000,
				],
			]);
			return HighloadBlockTable::compileEntity($r->fetchRaw()['ID'])->getDataClass();
		}
		if (!empty($_SESSION['FAVORITES_STORAGE'])) {
			return $_SESSION['FAVORITES_STORAGE'];
		}
		Loader::includeModule('highloadblock');
		$r = HighloadBlockTable::getList([
			'select' => [
				'ID',
			],
			'filter' => [
				'=NAME' => 'Favorites',
			],
			'limit'  => 1,
			'cache'  => [
				'ttl' => 86400000,
			],
		]);
		if ($r->getSelectedRowsCount() > 0) {
			$_SESSION['FAVORITES_STORAGE'] = HighloadBlockTable::compileEntity($r->fetchRaw()['ID'])->getDataClass();
		}
		return $_SESSION['FAVORITES_STORAGE'];
	}
}
