<?php
/*********************************************************************
 * @author Артамонов Денис <software.engineer@internet.ru>
 * @copyright Copyright (c) 2021
 * @modified 19 мая 2021, среда
 ********************************************************************/

namespace Native\App\Delivery;


use Bitrix\Main\Application;
use Bitrix\Main\Db\SqlQueryException;
use Bitrix\Main\Type\DateTime;

abstract class Base
{
    const SURFACE = 'SURFACE';
    const AIR = 'AVIA';

    private int $weightSimpleBox = 200;
    private int $minPrice = 300;
    private int $amountForFree = 5000;

    /**
     * Минимальная сумма для бесплатной доставки
     * @return int
     */
    public function getAmountForFree(): int
    {
        return $this->amountForFree;
    }

    /**
     * Возвращает реальный вес посылки с учетом коробки
     * @param $weight
     * @return float|int
     */
    protected function getWeight($weight)
    {
        if ($weight > 0 && $weight <= 500) {
            $weight += $this->getWeightSimpleBox();
        } else if ($weight > 500 && $weight <= 1000) {
            $weight += $this->getWeightSimpleBox() * 1.5;
        } else if ($weight > 1000 && $weight <= 1500) {
            $weight += $this->getWeightSimpleBox() * 2;
        } else if ($weight > 1500) {
            $weight += $this->getWeightSimpleBox() * 2.5;
        }
        return $weight;
    }

    protected function getData($fields)
    {
        $fields['DATE_CALCULATE'] = date('Ymd');
        if (!isset($fields['DELIVERY_METHOD'])) {
            $fields['DELIVERY_METHOD'] = self::SURFACE;
        }
        $where = '';
        foreach ($fields as $field => $value) {
            if (empty($value)) continue;
            $where .= $field . '="' . $value . '" and ';
        }
        if (empty($where) === false) {
            $where = mb_substr($where, 0, -5);
        }
        $sql = 'select PRICE, PRICE_VAT, PERIOD_MIN, PERIOD_MAX from ' . PriceTable::getTableName() . ' where ' . $where . ' order by ID desc limit 1';
        try {
            $result = Application::getConnection()->query($sql);
            if ($result->getSelectedRowsCount() > 0) {
                return $result->fetchRaw();
            }
            return false;
        } catch (SqlQueryException $e) {
            return false;
        }
    }

    protected function saveData($fields)
    {
        $fields['DATE_CREATE'] = new DateTime();
        $fields['DATE_CALCULATE'] = date('Ymd');
        if (!isset($fields['DELIVERY_METHOD'])) {
            $fields['DELIVERY_METHOD'] = self::SURFACE;
        }
        try {
            PriceTable::add($fields);
        } catch (\Exception $e) {
        }
    }

    protected function clearData()
    {
        if (Application::getConnection()->isTableExists(PriceTable::getTableName())) {
            try {
                Application::getConnection()->queryExecute('truncate table ' . PriceTable::getTableName());
            } catch (SqlQueryException $e) {
            }
        }
    }

    /**
     * Минимальная стоимость доставки
     * @link http://task.anastasia.ru/issues/3811
     * @return int
     */
    protected function getMinPrice(): int
    {
        return $this->minPrice;
    }

    /**
     * Вес простой коробки, которая добавляется к основному весу корзины
     * @return int
     */
    protected function getWeightSimpleBox(): int
    {
        return $this->weightSimpleBox;
    }
}
