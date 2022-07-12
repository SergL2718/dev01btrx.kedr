<?php
/*
 * Изменено: 29 декабря 2021, среда
 * Автор: Артамонов Денис <artamonov.d.i@yandex.com>
 * copyright (c) 2022
 */

namespace Native\App\Sale;


class Person
{
	private static ?Person $instance       = null;
	private static array   $personTypeId   = [];
	private static array   $personTypeCode = [];
	const PHYSICAL_CODE = 'personal';
	const LEGAL_CODE    = 'legal';

	/**
	 * Возвращает ID типа покупателя по коду
	 *
	 * @param $code
	 *
	 * @return integer|null
	 */
	public function getIdByCode ($code): ?int
	{
		return self::$personTypeId[$code] ?? null;
	}

	/**
	 * Возвращает код типа покупателя по ID
	 *
	 * @param $id
	 *
	 * @return string|bool
	 */
	public function getCodeById ($id)
	{
		return self::$personTypeCode[$id] ?? false;
	}

	public static function getInstance (): ?Person
	{
		if (is_null(self::$instance)) {
			self::$instance = new self;
			self::$personTypeId = [
				self::PHYSICAL_CODE => 3,
				self::LEGAL_CODE    => 4,
			];
			self::$personTypeCode = [
				3 => self::PHYSICAL_CODE,
				4 => self::LEGAL_CODE,
			];
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
