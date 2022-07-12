<?php
/*
 * Изменено: 08 сентября 2021, среда
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */


namespace Native\App;


use Native\App\Server\Request;
use Native\App\Server\Response;

final class Server
{
	private static ?Server $instance = null;
	private static string  $url      = '';

	private function __construct ()
	{
	}

	public function __call ($name, $arguments)
	{
	}

	public function __wakeup ()
	{
	}

	public function getName (): string
	{
		return $_SERVER['SERVER_NAME'];
	}

	public function getPort (): string
	{
		return $_SERVER['SERVER_PORT'];
	}

	public function getUrl (): ?string
	{
		return self::$url;
	}

	public function getRequest (): ?Request
	{
		return Request::getInstance(self::getInstance());
	}

	public static function getInstance (): ?Server
	{
		if (is_null(self::$instance)) {
			self::$instance = new self;
			$_SERVER['REQUEST_SCHEME'] = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443 ? 'https' : 'http';
			self::$url = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'];
		}
		return self::$instance;
	}

	public function getResponse (): ?Response
	{
		return Response::getInstance(self::getInstance());
	}

	private function __clone ()
	{
	}
}
