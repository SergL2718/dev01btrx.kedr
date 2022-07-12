<?php
/*
 * Изменено: 08 сентября 2021, среда
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */


namespace Native\App\Server;


use Native\App\Server;

final class Response
{
	private static ?Response $instance = null;

	private function __construct ()
	{
	}

	public static function getInstance (Server $server): ?Response
	{
		if (is_null(self::$instance)) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	public function __call ($name, $arguments)
	{
	}

	public function __wakeup ()
	{
	}

	public function json (array $data)
	{
		header('Content-Type: application/json; charset=' . SITE_CHARSET);
		echo json_encode($data, JSON_UNESCAPED_UNICODE);
		die;
	}

	private function __clone ()
	{
	}
}