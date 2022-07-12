<?php
/*
 * Изменено: 15 декабря 2021, среда
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

if (!function_exists('pr')) {
	function pr ($data = '')
	{
		if (empty($data)) {
			echo '<div style="color: red;">function pr(): no data</div>';
			return;
		}
		if (is_array($data) || is_object($data)) {
			echo '<pre style="position:relative;z-index:1000;color:#000 !important;white-space: break-spaces;word-break: break-word;font-size: 13px; text-align: left; margin: 20px 0; border: 1px solid #eee;padding: 20px 22px;border-radius: 5px;box-shadow: 0 0 10px 0 #eee;background-color: #fff;">';
			echo print_r($data, true);
			echo '</pre>';
		} else if ($data) {
			echo $data . '<br>';
		}
	}
}

if (!function_exists('array_key_first')) {
	function array_key_first (array $arr)
	{
		foreach ($arr as $key => $unused) {
			return $key;
		}
		return null;
	}
}

// For PHP <= 7.3.0 :
if (!function_exists('array_key_last')) {
	function array_key_last (array $array)
	{
		if (!is_array($array) || empty($array)) {
			return null;
		}
		return array_keys($array)[count($array) - 1];
	}
}

if (!function_exists('generate_token')) {
	function generate_token (string $salt = ''): string
	{
		return md5(microtime() . $salt . time());
	}
}

if (!function_exists('yandex_replace_special')) {
	function yandex_replace_special ($arg)
	{
		if (in_array($arg[0], ['&quot;', '&amp;', '&lt;', '&gt;']))
			return $arg[0];
		else
			return ' ';
	}
}