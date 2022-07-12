<?php
/*
 * @updated 07.12.2020, 19:16
 * @author Артамонов Денис <bitrix.developer@inbox.ru>
 * @copyright Copyright (c) 2020, Компания Webco <hello@wbc.cx>
 * @link http://wbc.cx
 */

namespace Native\App;


class Request
{
    private static ?Request $instance = null;

    public function curl($url, $method, $headers, $data = [])
    {
        $curl = curl_init();

        if ($method === 'POST') {
            curl_setopt($curl, CURLOPT_POST, true);
        }

        if ($method === 'GET') {
            if ($data) {
                $data = http_build_query($data);
                $url .= '?' . $data;
            }
        } else {
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }

        curl_setopt($curl, CURLOPT_URL, $url);

        if (count($headers) > 0) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        }

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    public function fileGetContents($url, $data)
    {
        if ($data) {
            $data = http_build_query($data);
            $url .= '?' . $data;
        }
        return file_get_contents($url);
    }

    public static function getInstance(): ?Request
    {
        if (is_null(self::$instance)) {
            self::$instance = new self;
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
