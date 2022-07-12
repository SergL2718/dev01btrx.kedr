<?php
/*
 * @updated 07.12.2020, 19:16
 * @author Артамонов Денис <bitrix.developer@inbox.ru>
 * @copyright Copyright (c) 2020, Компания Webco <hello@wbc.cx>
 * @link http://wbc.cx
 */

namespace Zk\Main\IBlock;


use Bitrix\Catalog\ProductTable;
use CEvent;
use CIBlockElement;
use Zk\Main\Helper;

class Element
{
    private $arFields;
    private $id;
    private $iBlockId;
    private $fields;
    private $properties;
    private $minimumPrice;

    public function __construct($arFields)
    {
        $this->id = $arFields['ID'];
        $this->iBlockId = $arFields['IBLOCK_ID'];
        $this->properties = $arFields['PROPERTY_VALUES'];
        $this->arFields = $arFields;
    }

    // MAIN METHODS

    public function setMinimumPrice()
    {
        Property::set($this->getId(), Property::MINIMUM_PRICE, $this->getMinimumPrice());
    }

    public function splitProperties()
    {
        $res = \CIBlockElement::GetProperty($this->getIBlockId(), $this->getId(), ['sort' => 'asc'], ['CODE' => Property::CML2_TRAITS]);

        while ($ar = $res->fetch()) {
            $code = $ar['CODE'] . '_' . \CUtil::translit($ar['DESCRIPTION'], LANG, ['change_case' => 'U']);
            if ($code == Property::CML2_TRAITS_VES) {
                $ar['VALUE'] .= Helper::MEASURE_KG;
            }
            Property::set($this->getId(), $code, $ar['VALUE']);
        }
    }

    public function handleMultipleProperties1C($properties)
    {
        // Возникает какая-то нездоровая ситуация
        // Массово обновляются элементы инфоблоков
        // В связи с этим, на данный момент (12.11.20) текущую функцию отключаю
        // https://megre.bitrix24.ru/workgroups/group/9/tasks/task/view/8203/
        return true;

        $delimiter = '^';
        $obProperty = new \CIBlockProperty;
        // Проверим свойства на наличие множественного значения
        // Пример значение: 123^456^789
        foreach ($this->getProperties() as $id => $arValues) {
            // Проверим значение
            // Убедимся, что свойства со множественными значениями являются строковыми
            if (is_array($arValues) && count($arValues) > 0) {
                foreach ($arValues as $values) {
                    if (mb_strpos($values['VALUE'], $delimiter) !== false && $properties[$id]['PROPERTY_TYPE'] === 'S' && $properties[$id]['MULTIPLE'] == 'N' && $properties[$id]['XML_ID']) {
                        $obProperty->Update($id, ['MULTIPLE' => 'Y', 'MULTIPLE_CNT' => 5]);
                    }
                }
            }
        }
    }

    public function handleMultiplePropertiesValues1C()
    {
        $arResult = [];
        $delimiter = '^';
        // Проверим свойства на наличие множественного значения
        // Пример значение: 123^456^789
        foreach ($this->getProperties() as $id => $arValues) {
            if (is_array($arValues) && count($arValues) > 0) {
                foreach ($arValues as $values) {
                    if (mb_strpos($values['VALUE'], $delimiter) !== false) {
                        $ar = explode($delimiter, $values['VALUE']);

                        if (is_array($ar)) {
                            foreach ($ar as $value) {
                                if ($value = trim($value)) {
                                    $arResult[$id][] = [
                                        'VALUE' => $value
                                    ];
                                }
                            }
                        }
                    }
                }
            }
        }

        if ($arResult) {
            foreach ($arResult as $id => $arValues) {
                Property::set($this->getId(), $id, $arValues);
            }
        }
    }

    /**
     * Функция перенесена в агент
     * \Zk\Main\Main\Agent\CheckingReceivedGoods
     *
     * @return bool
     * @deprecated
     */
    /*public function checkRemainder()
    {
        if ($this->getIBlockId() != Helper::IBLOCK_SHOP_ID) {
            return false;
        }
        // Проверим, остаток товара. Если положительный - отошлем уведомление пользователям
        $quantity = \CCatalogProduct::GetByID($this->getId())['QUANTITY'];
        if ($quantity > 0):
            // Проверим, имеются ли пользователи, которые ожидают уведомления по данному товару
            $arFilter = [
                'IBLOCK_ID' => Helper::IBLOCK_APPLICATIONS_PRODUCT,
                'PROPERTY_TRADE_ID_HIDDEN' => $this->getId(),
                'PROPERTY_NOTIFICATION_SENT' => false
            ];
            $arSelect = [
                'PROPERTY_TRADE_NAME_HIDDEN',
                'PROPERTY_TRADE_LINK_HIDDEN',
                'PROPERTY_USER_NAME',
                'PROPERTY_USER_MAIL'
            ];
            $res = CIBlockElement::GetList(['ID' => 'ASC'], $arFilter, false, false, $arSelect);
            while ($ar = $res->fetch()) {
                \CEvent::Send('SALE_SUBSCRIBE_PRODUCT', Helper::siteId(), [
                    'USER_NAME' => $ar['PROPERTY_USER_NAME_VALUE'],
                    'EMAIL' => $ar['PROPERTY_USER_MAIL_VALUE'],
                    'NAME' => $ar['PROPERTY_TRADE_NAME_HIDDEN_VALUE'],
                    'PAGE_URL' => $ar['PROPERTY_TRADE_LINK_HIDDEN_VALUE']
                ], 'N');
                Property::set($ar['ID'], 'NOTIFICATION_SENT', date('Y-m-d H:i:s'));
            }
        endif;
    }*/


    /**
     * Установим товарам из инфоблоков "Производители" и "Полный перечень"
     * Индекс сортировки такой же, как и у товаров из инфоблока "Интернет-магазин"
     *
     * http://task.anastasia.ru/issues/4260
     */
    public function setSortIndex()
    {
        // Возникает какая-то нездоровая ситуация
        // Массово обновляются элементы инфоблоков
        // В связи с этим, на данный момент (12.11.20) текущую функцию отключаю
        // https://megre.bitrix24.ru/workgroups/group/9/tasks/task/view/8203/
        return true;

        if ($this->getIBlockId() != Helper::IBLOCK_SHOP_ID) return;
        $filter = ['!IBLOCK_ID' => Helper::IBLOCK_SHOP_ID, '=XML_ID' => $this->arFields['XML_ID']];
        $select = ['ID'];
        $res = CIBlockElement::GetList(['ID' => 'ASC'], $filter, false, false, $select);
        while ($ar = $res->fetch()) {
            $entity = new CIBlockElement;
            $entity->Update($ar['ID'], ['SORT' => $this->arFields['SORT']]);
        }
    }

    // ADDITIONAL METHODS

    private function getMinimumPrice()
    {
        if (!$this->minimumPrice) {
            switch ($this->getFields()['TYPE']) {
                case ProductTable::TYPE_PRODUCT:
                    $this->minimumPrice = \CCatalogProduct::GetOptimalPrice($this->getId())['DISCOUNT_PRICE'];
                    break;
                case ProductTable::TYPE_SKU:
                    $prices = [];
                    $offers = \CCatalogSku::getOffersList($this->getId())[$this->getId()];
                    foreach ($offers as $offer) {
                        $prices[] = \CCatalogProduct::GetOptimalPrice($offer['ID'])['DISCOUNT_PRICE'];
                    }
                    sort($prices);
                    $this->minimumPrice = $prices[0];
                    break;
            }
        }
        return ($this->minimumPrice) ? (int)$this->minimumPrice : 0;
    }

    private function getId()
    {
        return $this->id;
    }

    private function getIBlockId()
    {
        return $this->iBlockId;
    }

    private function getFields()
    {
        if (!$this->fields) {
            $this->fields = ProductTable::getById($this->getId())->fetch();
        }
        return $this->fields;
    }

    private function getProperties()
    {
        return $this->properties;
    }
}
