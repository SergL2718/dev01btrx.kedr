<?php
/*
 * Изменено: 08 сентября 2021, среда
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */


namespace Native\App\Server;


use Native\App\Server;

final class Request
{
	const GET    = 'GET';
	const POST   = 'POST';
	const PUT    = 'PUT';
	const DELETE = 'DELETE';
	private static ?Request $instance  = null;
	private static string   $realIp    = '';
	private static string   $uri       = '';
	private static string   $code      = '';
	private static string   $canonical = '';
	private static array    $params    = [];

	private function __construct ()
	{
	}

	public static function getInstance (Server $server): ?Request
	{
		if (is_null(self::$instance)) {
			self::$instance = new self;
			self::$canonical = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'];
			self::$uri = str_replace('?' . $_SERVER['QUERY_STRING'], '', $_SERVER['REQUEST_URI']);
			if (self::$uri === '/') {
				self::$uri = 'home';
				self::$code = 'home';
			} else {
				self::$uri = trim(self::$uri, '/');
				self::$code = str_replace('/', '-', self::$uri);
				self::$canonical .= '/' . self::$uri;
			}
			if (defined('SITE_DIR') === true && SITE_DIR !== '/') {
				self::$uri = str_replace(SITE_DIR, '', self::$uri);
			}
			self::$params = array_merge(self::$params, $_GET);
			self::$params = array_merge(self::$params, $_POST);
			if (mb_strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
				$ar = json_decode(file_get_contents('php://input'), true);
				if (is_array($ar) && !empty($ar)) {
					self::$params = array_merge(self::$params, $ar);
				}
			}
			if (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
				$_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_CF_CONNECTING_IP'];
				$_SERVER['HTTP_CLIENT_IP'] = $_SERVER['HTTP_CF_CONNECTING_IP'];
			}
			$client = @$_SERVER['HTTP_CLIENT_IP'];
			$forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
			$remote = $_SERVER['REMOTE_ADDR'];
			if (filter_var($client, FILTER_VALIDATE_IP)) {
				self::$realIp = $client;
			} else if (filter_var($forward, FILTER_VALIDATE_IP)) {
				self::$realIp = $forward;
			} else {
				self::$realIp = $remote;
			}
		}
		return self::$instance;
	}

	public function __call ($name, $arguments)
	{
	}

	public function __wakeup ()
	{
	}

	public function getRemoteAddr (): string
	{
		return self::$realIp;
	}

	public function getMethod ()
	{
		return $_SERVER['REQUEST_METHOD'];
	}

	public function getUri (): string
	{
		return self::$uri;
	}

	public function getCanonical (): string
	{
		return self::$canonical;
	}

	public function getCode (): string
	{
		return self::$code;
	}

	public function getQueryString (): string
	{
		return $_SERVER['QUERY_STRING'];
	}

	public function getQueryParams (): array
	{
		return $_GET;
	}

	public function getParams (): array
	{
		return self::$params;
	}

	public function getParam (string $code)
	{
		return self::$params[$code];
	}

	public function isJson (): bool
	{
		return mb_strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false;
	}

	public function isAjax (): bool
	{
		return (isset($_SERVER['HTTP_BX_AJAX']) && $_SERVER['HTTP_BX_AJAX'] === 'true') || ($_REQUEST['AJAX_REQUEST'] === 'Y');
	}

	private function __clone ()
	{
	}
}