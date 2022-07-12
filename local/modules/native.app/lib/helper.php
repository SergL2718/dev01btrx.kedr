<?php
/*
 * Изменено: 08 сентября 2021, среда
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

namespace Native\App;


class Helper
{
	private static ?Helper $instance = null;
	const SITE_ID         = 'zg';
	const TYPE_INTERNET   = 'internet';
	const TYPE_RETAIL     = 'retail';
	const TYPE_COMBINE    = 'combine';
	const TYPE_MOSCOW     = 'moscow';
	const SUFFIX_INTERNET = 'INT';
	const SUFFIX_RETAIL   = 'RTL';
	const SUFFIX_COMBINE  = 'CMB';
	const SUFFIX_MOSCOW   = 'MSK';
	const MEGRE           = 'megre';
	const PHYSICAL_ID     = 3;
	const LEGAL_ID        = 4;

	/**
	 * @param $data
	 *
	 * @deprecated since 2021-09-08
	 */
	public function _print ($data)
	{
		if (is_array($data) || is_object($data)) {
			echo '<pre style="font-size: 13px; text-align: left; margin: 20px 0; border: 1px solid #eee;padding: 20px 22px;border-radius: 5px;box-shadow: 0 0 10px 0 #eee;background-color: #fff;">';
			echo print_r($data, true);
			echo '</pre>';
		} else if ($data) {
			echo $data . '<br>';
		}
	}

	public function clearQuotes ($string): string
	{
		return htmlspecialchars_decode($string, ENT_QUOTES);
	}

	public function isAdminSection (): bool
	{
		return defined('ADMIN_SECTION');
	}

	public function isWeekend ($date): bool
	{
		return (date('N', strtotime($date)) >= 6);
	}

	public static function getInstance (): ?Helper
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
