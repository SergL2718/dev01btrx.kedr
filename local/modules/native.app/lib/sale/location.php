<?php
/*
 * Изменено: 17 сентября 2021, пятница
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

namespace Native\App\Sale;


use Bitrix\Main\Config\Option;
use Bitrix\Sale\Location\LocationTable;

class Location
{
	private static ?Location $instance = null;
	private static array     $current  = [];
	private static array     $other    = [];
	const MSK                                                  = 'MSK';
	const MOSCOW_CITY_TITLE_NORMAL                             = 'Москва';
	const MOSCOW_CITY_TITLE_LOWER                              = 'москва';
	const NSK                                                  = 'NSK';
	const NOVOSIBIRSK_CITY_TITLE_NORMAL                        = 'Новосибирск';
	const NOVOSIBIRSK_CITY_TITLE_LOWER                         = 'новосибирск';
	const NOVOSIBIRSK_EUROPEAN_COTTAGE_SETTLEMENT_TITLE_NORMAL = 'Коттеджный посёлок Европейский';
	const NOVOSIBIRSK_EUROPEAN_COTTAGE_SETTLEMENT_TITLE_LOWER  = 'коттеджный посёлок европейский';
	const BERDSK                                               = 'BERDSK';
	const BERDSK_CITY_TITLE_NORMAL                             = 'Бердск';
	const BERDSK_CITY_TITLE_LOWER                              = 'бердск';
	const OTHER                                                = 'OTHER';
	const OTHER_CITY_TITLE_NORMAL                              = 'Другой город';
	const OTHER_CITY_TITLE_LOWER                               = 'другой город';
	const RUSSIA                                               = 'Россия';
	const UKRAINE                                              = 'Украина';
	const BELARUS                                              = 'Беларусь';
	const KAZAKHSTAN                                           = 'Казахстан';
	const RU                                                   = 'RU';
	const UA                                                   = 'UA';
	const BY                                                   = 'BY';
	const KZ                                                   = 'KZ';
	const COOKIE_CITY_CODE                                     = 'LOCATION_CITY_CODE';

	//const COOKIE_COUNTRY_CODE                                  = 'LOCATION_COUNTRY_CODE';

	public static function getCookieName (): string
	{
		return Option::get('main', 'cookie_name') . '_USER_LOCATION';
	}

	public static function getCurrent ()
	{
		if (empty(self::$current) && $_COOKIE[self::getCookieName()]) {
			self::$current = json_decode($_COOKIE[self::getCookieName()], true) ?? [];
		}
		return self::$current;
	}

	public static function getOther (): array
	{
		if (empty(self::$other)) {
			$r = LocationTable::getList([
				'select' => [
					'ID',
					'CODE',
					'NAME_VALUE' => 'NAME.NAME',
					'ISO_CODE'   => 'NAME.SHORT_NAME',
					'COUNTRY_ID',
				],
				'filter' => [
					'=CODE'             => self::OTHER,
					'=NAME.LANGUAGE_ID' => LANGUAGE_ID,
				],
				'limit'  => 1,
			]);
			if ($r->getSelectedRowsCount() > 0) {
				$r = $r->fetchRaw();
				$r['NAME'] = $r['NAME_VALUE'];
				unset($r['NAME_VALUE']);
				self::$other = $r;
			}
		}
		return self::$other;
	}

	/**
	 * @return \string[][]
	 * @deprecated since 2021-09-16
	 *             use Bitrix\Sale\Location\LocationTable
	 */
	public static function getCurrentCityCode (): string
	{
		return $_COOKIE[self::COOKIE_CITY_CODE] ? : self::OTHER;
	}

	/**
	 * @return \string[][]
	 * @deprecated since 2021-09-16
	 *             use Bitrix\Sale\Location\LocationTable
	 */
	public static function getList (): array
	{
		return [
			self::MSK    => [
				'CODE'  => self::MSK,
				'TITLE' => self::MOSCOW_CITY_TITLE_NORMAL,
				'LOWER' => self::MOSCOW_CITY_TITLE_LOWER,
			],
			self::NSK    => [
				'CODE'  => self::NSK,
				'TITLE' => self::NOVOSIBIRSK_CITY_TITLE_NORMAL,
				'LOWER' => self::NOVOSIBIRSK_CITY_TITLE_LOWER,
			],
			self::BERDSK => [
				'CODE'  => self::BERDSK,
				'TITLE' => self::BERDSK_CITY_TITLE_NORMAL,
				'LOWER' => self::BERDSK_CITY_TITLE_LOWER,
			],
			self::OTHER  => [
				'CODE'  => self::OTHER,
				'TITLE' => self::OTHER_CITY_TITLE_NORMAL,
				'LOWER' => self::OTHER_CITY_TITLE_LOWER,
			],
		];
	}

	/**
	 * @return \string[][]
	 * @deprecated since 2021-09-16
	 *             use Bitrix\Sale\Location\LocationTable
	 */
	public static function getCountryList (): array
	{
		return [
			self::RU => [
				'CODE'  => self::RU,
				'TITLE' => self::RUSSIA,
			],
			self::UA => [
				'CODE'  => self::UA,
				'TITLE' => self::UKRAINE,
			],
			self::BY => [
				'CODE'  => self::BY,
				'TITLE' => self::BELARUS,
			],
			self::KZ => [
				'CODE'  => self::KZ,
				'TITLE' => self::KAZAKHSTAN,
			],
		];
	}

	public static function getInstance (): ?Location
	{
		if (is_null(self::$instance)) {
			self::$instance = new self;
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
