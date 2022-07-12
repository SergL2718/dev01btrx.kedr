<?php
/*
 * @updated 07.12.2020, 19:16
 * @author Артамонов Денис <bitrix.developer@inbox.ru>
 * @copyright Copyright (c) 2020, Компания Webco <hello@wbc.cx>
 * @link http://wbc.cx
 */

namespace Native\App\Sale;


use Bitrix\Main\Application;
use Bitrix\Main\Db\SqlQueryException;


/**
 * Class Delivery
 * @package Native\App\Sale
 * @deprecated since 2021-02-22
 */
class Delivery
{
    private static ?Delivery $instance = null;
    private static array $deliveryId = [];
    private static array $deliveryCode = [];
    private static array $deliveryName = [];

    /**
     * Минимальная сумма доставки
     * @return int
     */
    public function getMinimumAmount(): int
    {
        return 300;
    }

    /**
     * Минимальная сумма для бесплатной доставки
     * @return int
     */
    public function getAmountForFree(): int
    {
        return 5000;
    }

    /**
     * Возвращает ID службы доставки по ее коду
     * @param $code
     * @return integer|null
     */
    public function getIdByCode($code)
    {
        return isset(self::$deliveryId[$code]) ? self::$deliveryId[$code] : null;
    }

    /**
     * Возвращает код службы доставки по ее ID
     * @param $id
     * @return string|bool
     */
    public function getCodeById($id)
    {
        return isset(self::$deliveryCode[$id]) ? self::$deliveryCode[$id] : false;
    }

    /**
     * Возвращает название службы доставки по ее ID
     * @param $id
     * @return string|bool
     */
    public function getNameById($id)
    {
        return isset(self::$deliveryName[$id]) ? self::$deliveryName[$id] : false;
    }

    public static function getInstance(): ?Delivery
    {
        if (is_null(self::$instance)) {
            self::$instance = new self;
            try {
                $rows = Application::getConnection()->query('select ID, XML_ID, NAME from b_sale_delivery_srv where XML_ID!=""');
                while ($row = $rows->fetch()) {
                    self::$deliveryId[$row['XML_ID']] = $row['ID'];
                    self::$deliveryCode[$row['ID']] = $row['XML_ID'];
                    self::$deliveryName[$row['ID']] = $row['NAME'];
                }
            } catch (SqlQueryException $e) {
            }
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
