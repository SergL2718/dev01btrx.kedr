<?php
/*
 * Изменено: 08 сентября 2021, среда
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

namespace Native\App\Foundation;


use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\ArgumentOutOfRangeException;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Service\GeoIp\Manager;

class Core
{
	private static ?Core $_instance = null;

	public function launch ()
	{
		require 'functions.php';
		//$this->checkLocation();
		$this->events();
		$this->js();
	}

	private function events ()
	{
		Event::getInstance()->init();
	}

	private function js ()
	{
		$GLOBALS['APPLICATION']->AddHeadScript(str_replace($_SERVER['DOCUMENT_ROOT'], '', __DIR__) . '/../js/core.min.js');
	}

	/**
	 * Метод проверяет локацию пользователя и в зависимости от этого выполняются действия
	 *
	 * @throws ArgumentNullException
	 * @throws ArgumentOutOfRangeException
	 */
	private function checkLocation ()
	{
		// Исключения для редиректов
		$whitelistIp = [
			//'93.91.162.137' => true, // 1C-server,
			'212.164.38.161' => true, // Полина Мамонова
			'5.44.168.125'   => true,
			'188.162.15.73'  => true,
		];

		$ipAddress = Manager::getRealIp();

		//pr($_SERVER);
		//die;

		if (
			isset($whitelistIp[$ipAddress]) ||
			mb_strpos($_SERVER['REQUEST_URI'], '1c_exchange') !== false ||
			mb_strpos($_SERVER['REQUEST_URI'], 'api') !== false ||
			mb_strpos($_SERVER['REQUEST_URI'], 'personal/order/payment') !== false ||
			(
				isset($_SERVER['HTTP_REFERER']) &&
				mb_strpos($_SERVER['HTTP_REFERER'], 'sberbank') !== false
			)
		) {
			return;
		}

		$landingUrl = 'https://novosibirsk.megre.ru';
		$landingCityName = 'новосибирск';
		//$landingCityName = Location::MOSCOW_CITY_TITLE_LOWER;

		$cookiePrefix = Option::get('main', 'cookie_name');
		$landingCookie = $cookiePrefix . '_LANDING';
		$geoCookie = $cookiePrefix . '_GEO';

		$landing = $_COOKIE[$landingCookie] ? unserialize($_COOKIE[$landingCookie]) : [];
		$geo = $_COOKIE[$geoCookie] ? unserialize($_COOKIE[$geoCookie]) : [];

		//$landing = [];
		//$geo = [];

		if (count($geo) > 0) {
			if ($geo['IP'] !== $ipAddress) {
				$cityName = Manager::getCityName($ipAddress, 'ru');
				$cityName = $cityName ? mb_strtolower($cityName) : 'undefined';
				$geo = [
					'IP'   => $ipAddress,
					'CITY' => [
						'NAME' => $cityName,
					],
				];
				setcookie($geoCookie, serialize($geo), time() + 86400000, '/');
			}
		} else {
			$cityName = Manager::getCityName($ipAddress, 'ru');
			$cityName = $cityName ? mb_strtolower($cityName) : 'undefined';
			$geo = [
				'IP'   => $ipAddress,
				'CITY' => [
					'NAME' => $cityName,
				],
			];
			setcookie($geoCookie, serialize($geo), time() + 86400000, '/');
		}

		if ((count($landing) === 0 || $landing[$landingCityName] !== true) && $geo['CITY']['NAME'] === $landingCityName) {
			$landing[$landingCityName] = true;
			setcookie($landingCookie, serialize($landing), time() + 86400000, '/');
			//header('Location: ' . $landingUrl);
			LocalRedirect($landingUrl);
			die;
		}
	}

	public static function getInstance ()
	{
		if (is_null(self::$_instance)) {
			self::$_instance = new self;
		}
		return self::$_instance;
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
