<?php
/*
 * @updated 07.12.2020, 19:16
 * @author Артамонов Денис <bitrix.developer@inbox.ru>
 * @copyright Copyright (c) 2020, Компания Webco <hello@wbc.cx>
 * @link http://wbc.cx
 */

namespace Zk\Main;


class Core
{
    private static $_instance;

    public function launch()
    {
        Event::getInstance()->check();
        // if (Helper::isAdminSection()) return;
        // $this->checkClientCountry();
    }

    private function checkClientCountry()
    {
        if (in_array($_SERVER['REMOTE_ADDR'], $this->ipWhiteList())) return;

        $clientCountry = geoip_country_code_by_name($_SERVER['REMOTE_ADDR']);

        if ($clientCountry !== 'RU' && $clientCountry !== 'BY' && $clientCountry !== 'KZ' && $clientCountry !== 'UA') {
            header('Location: https://megrellc.com');
            die;
        }
    }

    private function ipWhiteList()
    {
        return [
            '108.170.217.160',
            '108.170.217.161',
            '108.170.217.162',
            '108.170.217.163',
            '108.170.217.164',
            '108.170.217.165',
            '108.170.217.166',
            '108.170.217.167',
            '108.170.217.168',
            '108.170.217.169',
            '108.170.217.170',
            '108.170.217.171',
        ];
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
