<?php
/*
 * Изменено: 21 сентября 2021, вторник
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

namespace Native\App\Sale;


use Bitrix\Catalog\StoreTable;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;

class Store
{
	private static ?Store $instance = null;
	private static array  $list     = [];
	private static array  $ids      = [];
	private static array  $codes    = [];

	/**
	 * Определяем склад на основании текущего региона пользователя
	 *
	 * @return int
	 */
	public function getCurrent ()
	{
		$location = Location::getCurrent();
		if (!$location['CODE']) {
			$location['CODE'] = Location::NSK;
		}
		if ($id = self::getIdByCode($location['CODE'])) {
			return $id;
		}
		return self::getIdByCode(Location::NSK);
	}

	public function getByCode (string $code)
	{
		return self::$list[self::$ids[$code]] ?? false;
	}

	public function getIdByCode (string $code)
	{
		return self::$ids[$code] ?? false;
	}

	public function getCodeById (int $id)
	{
		return self::$codes[$id] ?? false;
	}

	/**
	 * @throws ObjectPropertyException
	 * @throws SystemException
	 * @throws ArgumentException
	 */
	public static function getInstance (): ?Store
	{
		if (is_null(self::$instance)) {
			self::$instance = new self;
			$list = StoreTable::getList([
				'select' => [
					'ID',
					'CODE',
				],
				'filter' => [
					'ACTIVE' => 'Y',
				],
				'cache'  => [
					'ttl' => 2592000, // 30 суток
				],
			]);
			while ($store = $list->fetchRaw()) {
				self::$ids[$store['CODE']] = $store['ID'];
				self::$codes[$store['ID']] = $store['CODE'];
				self::$list[$store['ID']] = $store;
			}
		}
		return self::$instance;
	}

	private function __construct ()
	{
	}

	public function __call ($name, $arguments)
	{
		die('Method \'' . $name . '\' is not defined');
	}

	private function __clone ()
	{
	}
}
