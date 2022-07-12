<?php
/*
 * @updated 07.12.2020, 19:16
 * @author Артамонов Денис <bitrix.developer@inbox.ru>
 * @copyright Copyright (c) 2020, Компания Webco <hello@wbc.cx>
 * @link http://wbc.cx
 */

namespace Zk\Main;


use Bitrix\Main\Diag\Debug;
use Bitrix\Main\Loader;
use CIBlockElement;
use CSaleOrderUserProps;
use Zk\Main\IBlock\Property;

class Helper
{
    const MODULE_ID = 'zk.main';

    const SITE_ID = 'zg';

    const MEDIUM = 'medium';
    const HARD = 'hard';

    const GROUP_ID_USER_REGISTERED = 5;

    const IBLOCK_SHOP_ID = 37;
    const IBLOCK_OFFERS_SHOP_ID = 38;

    const IBLOCK_PRODUCTION_ID = 39;
    const IBLOCK_MANUFACTURERS_ID = 41;
    const IBLOCK_BASKET_RULES_ID = 62;

    const IBLOCK_FORM_CONTACT_ID = 47;

    const IBLOCK_APPLICATIONS_PRODUCT = 25;

    const IBLOCK_FORM_CONTACT_PROPERTY_IP = 934;
    const IBLOCK_FORM_CONTACT_PROPERTY_EMAIL = 812;

    const DEALERS_IBLOCK_ID = 53;
    const DEALERS_RUSSIA_ID = 1023;
    const DEALERS_BELARUS_ID = 1146;
    const DEALERS_UKRAINE_ID = 1175;
    const DEALERS_KAZAKHSTAN_ID = 1160;

    const LOTTERY_IBLOCK_ID = 55;
    const LOTTERY_COUPON_SECTION_ID = 1214;
    const LOTTERY_COUPON_TYPE_OFFLINE = 2091;
    const LOTTERY_COUPON_TYPE_ONLINE = 2092;
    const LOTTERY_COUPON_PROCESSED_NO = 2095;
    const LOTTERY_COUPON_PROCESSED_YES = 2096;

    const MEASURE_KG = 'кг';

    const CARD_PAY_SYSTEM_ID = 27;
    const BILL_PAY_SYSTEM_ID = 9;
    const RECEIPT_PAY_SYSTEM_ID = 21;
    const PERSON_TYPE_PHYSICAL = 3;
    const PERSON_TYPE_LEGAL = 4;

    const BOX_150 = 9650;
    const BOX_225 = 9651;
    const BOX_300 = 9652;

    public static function _print($data)
    {
        if (is_array($data) || is_object($data)) {
            echo '<pre style="text-align: left; margin: 20px 0; border: 1px solid #eee;padding: 20px 22px;border-radius: 5px;box-shadow: 0 0 10px 0 #eee;">';
            echo print_r($data, true);
            echo '</pre>';
        } elseif ($data) {
            echo $data . '<br>';
        }
    }

    /**
     * Распечатка массива
     * @param $ar
     * @deprecated
     */
    public static function printArray($ar)
    {
        if ($ar) {
            echo '<pre>' . print_r($ar, 1) . '</pre>';
        }
    }

    /**
     * Логирует данные в файл /__bx_log.log
     * @param $data
     */
    public static function log($data)
    {
        Debug::writeToFile(date('d.m.Y H:i:s'));
        Debug::writeToFile($data);
        Debug::writeToFile('=====================================');
    }

    /**
     * Идентификатор сайта
     * @return string
     */
    public static function siteId()
    {
        return self::SITE_ID;
    }

    /**
     * Удаляем все профили покупателя пользователя
     *
     * @param $userId
     */
    public static function deleteUserProfiles($userId)
    {
        Loader::includeModule('sale');
        $res = CSaleOrderUserProps::GetList(['ID' => 'DESC'], ['USER_ID' => $userId]);
        while ($ar = $res->fetch()) {
            CSaleOrderUserProps::Delete($ar['ID']);
        }
    }

    /**
     * Установить товарам случайный рейтинг в диапазоне
     *
     * @param $min
     * @param $max
     * @return int
     */
    public static function setRatingProducts($min, $max)
    {
        Loader::includeModule('iblock');

        if ($min == 0 || $min < 0) {
            $min = 1;
        }
        if ($max == 0 || $max < 0) {
            $max = 1;
        }
        if ($min > 5) {
            $min = 5;
        }
        if ($max > 5) {
            $max = 5;
        }
        $counter = 0;
        $res = CIBlockElement::GetList(['ID' => 'ASC'], ['IBLOCK_ID' => Helper::IBLOCK_SHOP_ID, 'ACTIVE' => 'Y'], false, false, ['ID']);
        while ($ar = $res->fetch()) {
            $rating = rand($min, $max);
            Property::set($ar['ID'], 'rating', $rating);
            $counter++;
        }
        return $counter;
    }

    public static function isAdminSection()
    {
        return defined('ADMIN_SECTION');
    }

    /**
     * @param int $length
     * @param string $level
     * @return string
     */
    public static function generateUniqueCode($length = 10, $level = '')
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        if ($level === self::MEDIUM) {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        } else if ($level === self::HARD) {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ&%$#@+?^*';
        }
        $charactersLength = strlen($characters);
        $code = '';
        for ($i = 0; $i < $length; $i++) {
            $code .= $characters[rand(0, $charactersLength - 1)];
        }
        return $code;
    }

}
