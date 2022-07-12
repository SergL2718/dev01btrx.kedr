<?php
/*
 * @updated 07.12.2020, 19:16
 * @author Артамонов Денис <bitrix.developer@inbox.ru>
 * @copyright Copyright (c) 2020, Компания Webco <hello@wbc.cx>
 * @link http://wbc.cx
 */

\Zk\Main\Core::getInstance()->launch();

/**
 * @param $data
 * @deprecated
 */
/*function _print($data)
{
    \Zk\Main\Helper::_print($data);
}*/

/*function pr($data)
{
    \Zk\Main\Helper::_print($data);
}*/

// For PHP <= 7.3.0 :
if (!function_exists('array_key_last')) {
    function array_key_last($array)
    {
        if (!is_array($array) || empty($array)) {
            return NULL;
        }

        return array_keys($array)[count($array) - 1];
    }
}
?>
