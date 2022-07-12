<?php
/*
 * Изменено: 29 декабря 2021, среда
 * Автор: Артамонов Денис <artamonov.d.i@yandex.com>
 * copyright (c) 2022
 */

namespace Native\App\Sale;


class Discount
{
    private static ?Discount $instance = null;
    private static array $discountPercent = [];
    private static array $max = [];

    /**
     * Возвращает ID скидки по значению процента
     * @param $percent
     * @return bool|int
     */
    public function getIdByPercent($percent)
    {
        return self::$discountPercent[$percent] ?? false;
    }

    public static function getInstance(): ?Discount
    {
        if (is_null(self::$instance)) {
            self::$instance = new self;
            self::$discountPercent = [
                20 => 4
            ];
        }
        return self::$instance;
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
