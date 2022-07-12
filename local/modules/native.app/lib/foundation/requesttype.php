<?php
/*
 * @updated 07.12.2020, 19:16
 * @author Артамонов Денис <bitrix.developer@inbox.ru>
 * @copyright Copyright (c) 2020, Компания Webco <hello@wbc.cx>
 * @link http://wbc.cx
 */

namespace Native\App\Foundation;

/**
 * Используется для Битрикс24
 *
 * Class RequestType
 * @package Native\App\Foundation
 * @deprecated
 */
class RequestType
{
    private $url;

    public function __construct($url)
    {
        $this->url = $url;
    }

    public function get($path)
    {
        return json_decode(file_get_contents($this->url . '/' . $path), true)['result'];
    }

    public function post($path, $params)
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $this->url . '/' . $path,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_POST => 1,
            CURLOPT_HEADER => 0,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_POSTFIELDS => http_build_query($params)
        ]);
        $result = curl_exec($curl);
        curl_close($curl);
        return json_decode($result, true)['result'];
    }
}
