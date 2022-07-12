<?php
/*
 * Изменено: 24 декабря 2020, четверг
 * Автор: Артамонов Денис <artamonov.d.i@yandex.com>
 * copyright (c) 2022
 */

namespace Native\App\Foundation;


/**
 * Используется для Битрикс24
 *
 * Class Request
 * @package Native\App\Foundation
 * @deprecated
 */
class Request
{
    private static $_instance;

    public function send($url)
    {
        return (new RequestType($url));
    }

    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    private function __construct()
    {
    }

    public function __call($name, $arguments)
    {
        die('Method \'' . $name . '\' is not defined');
    }

    private function __clone()
    {
    }
}
