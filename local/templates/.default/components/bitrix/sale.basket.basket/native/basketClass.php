<?php
/*
 * @updated 09.12.2020, 18:38
 * @author Артамонов Денис <bitrix.developer@inbox.ru>
 * @copyright Copyright (c) 2020, Компания Webco <hello@wbc.cx>
 * @link http://wbc.cx
 */


use Zk\Main\Helper;
use \Bitrix\Main\Loader;

/**
 * Класс используется для дополнительной обработки корзины
 * Класс вызывается из файла /local/templates/.default/components/bitrix/sale.basket.basket/native/result_modifier.php
 *
 * С 28.05.20 вызов класса отключен, так как логика была реализована, но она до сих пор не протетсирована
 * И в работе не применяется
 *
 * Class BasketResultModifier
 *
 * @deprecated since 2020-05-28
 */
class BasketResultModifier
{
    private $arResult;
    private $basket;
    private $basketProducts;
    private $products;
    private $blank;

    private $map = [
        'PROPERTIES' => [
            'ACTION' => [
                2491 => 'gift',
                2492 => 'gift_each_product'
            ]
        ]
    ];

    /**
     * BasketResultModifier constructor.
     * Основные методы модификации результирующего массива корзины
     *
     * @param $arResult
     */
    public function __construct($arResult)
    {
        $this->arResult = $arResult;
    }

    public function getResult()
    {
        return $this->arResult;
    }

    public function checkRules()
    {
        // Удалим все подарки из корзины
        // И позже, добавим их заново, согласно действующим правилам
        $this->clearGifts();

        // Получим актуальные правила
        // Проверим их условия и действия
        // Сформируем массив товаров-подарков, которые будет необходимо выдать
        $this->getRules();

        // Выдаём товары-подарки, которые полагаются согласно действующим правилам
        $this->giveGifts();

        // Сохраняем все изменения в корзине
        $this->getBasket()->save();
    }

    /**
     * Удалим все подарки из корзины
     * И позже, добавим их заново, согласно действующим правилам
     */
    private function clearGifts()
    {
        foreach ($this->getBasketProducts() as $productId => $ar) {
            if ($ar['PRICE'] > 0) continue;
            $this->deleteBasketProduct(['ROW_ID' => $ar['ROW_ID']]);
        }
    }

    /**
     * Получим актуальные правила
     * Проверим их условия и действия
     * Сформируем массив товаров-подарков, которые будет необходимо выдать
     */
    private function getRules()
    {
        Loader::includeModule('iblock');

        // Товары, которые участвуют в правилах
        $products =& $this->products;

        $order = [
            'SORT' => 'ASC',
            'DATE_ACTIVE_FROM' => 'ASC'
        ];

        $select = [
            'ID',
            'NAME',
            'PROPERTY_TOTAL_PRICE',
            'PROPERTY_LIST_PRODUCTS',
            'PROPERTY_COUNT_PRODUCTS',
            'PROPERTY_ACTION',
            'PROPERTY_GIFTS',
            'PROPERTY_COUNT_GIFTS'
        ];

        $filter = [
            'IBLOCK_ID' => Helper::IBLOCK_BASKET_RULES_ID,
            'ACTIVE' => 'Y',
            'ACTIVE_DATE' => 'Y',
            '!PROPERTY_ACTION' => false,
            [
                'LOGIC' => 'OR',
                ['!PROPERTY_GIFTS' => false],
                ['!PROPERTY_COUNT_GIFTS' => false],
            ],
            [
                'LOGIC' => 'OR',
                ['!PROPERTY_TOTAL_PRICE' => false],
                ['!PROPERTY_LIST_PRODUCTS' => false],
                ['!PROPERTY_COUNT_PRODUCTS' => false],
            ]
        ];

        $rules = \CIblockElement::GetList($order, $filter, false, false, $select);
        while ($rule = $rules->fetch()) {
            // Триггер зависящий от условий правила
            // И если он активирован, тогда будут применеы действия правила
            $apply = false;
            $applyByPrice = false;
            $applyByProduct = false;
            // Тип действия
            $actionCode = $this->map['PROPERTIES']['ACTION'][$rule['PROPERTY_ACTION_ENUM_ID']];

            // --------------------------------------------------------------- Проверяем условия
            // По сумме корзины
            if (!empty($rule['PROPERTY_TOTAL_PRICE_VALUE'])) {
                $basketPrice =& $this->getBasketPrice();
                $price =& $rule['PROPERTY_TOTAL_PRICE_VALUE'];
                if (mb_strpos($price, '>') !== false) {
                    $price = str_replace('>', '', $price);
                    if ($basketPrice > $price) {
                        $applyByPrice = true;
                    }
                } else if (mb_strpos($price, '<') !== false) {
                    $price = str_replace('<', '', $price);
                    if ($basketPrice < $price) {
                        $applyByPrice = true;
                    }
                } else if ($basketPrice == $price) {
                    $applyByPrice = true;
                }

            }

            // По наличию и количеству товаров
            if (count($rule['PROPERTY_LIST_PRODUCTS_VALUE']) > 0) {
                $list =& $rule['PROPERTY_LIST_PRODUCTS_VALUE'];
                $count =& $rule['PROPERTY_COUNT_PRODUCTS_VALUE'];
                $applyByProduct = [];

                foreach ($list as $index => $productId) {

                    if (!isset($this->getBasketProducts()[$productId])) continue;

                    $basketQuantity =& $this->getBasketProducts()[$productId]['QUANTITY'];
                    $quantity = $count[$index];

                    switch ($actionCode) {
                        case 'gift':
                            if (mb_strpos($quantity, '>') !== false) {
                                $quantity = str_replace('>', '', $quantity);
                                if ($basketQuantity > $quantity) {
                                    $applyByProduct[] = true;
                                }
                            } else if (mb_strpos($quantity, '<') !== false) {
                                if ($basketQuantity < $quantity) {
                                    $applyByProduct[] = true;
                                }
                            } else if ($basketQuantity == $quantity) {
                                $applyByProduct[] = true;
                            } else if (empty($quantity) && $basketQuantity > 0) {
                                $applyByProduct[] = true;
                            }
                            break;
                        case 'gift_each_product':
                            $quantity = str_replace(['>', '<'], '', $quantity);
                            $quantity = $quantity - 1;
                            if ($basketQuantity > $quantity) {
                                $applyByProduct[] = true;
                            }
                            break;
                    }
                }

                $applyByProduct = count($applyByProduct) == count($list) ? true : false;
            }

            if ($applyByPrice === true && $applyByProduct === true) {
                $apply = true;
            } else if (!empty($rule['PROPERTY_TOTAL_PRICE_VALUE']) && count($rule['PROPERTY_LIST_PRODUCTS_VALUE']) === 0) {
                $apply = $applyByPrice;
            } else if (empty($rule['PROPERTY_TOTAL_PRICE_VALUE']) && count($rule['PROPERTY_LIST_PRODUCTS_VALUE']) > 0) {
                $apply = $applyByProduct;
            }

            if ($apply === false) continue;

            // --------------------------------------------------------------- Обработаем действия

            // Подарки
            $gifts =& $rule['PROPERTY_GIFTS_VALUE'];
            if (($actionCode === 'gift' || $actionCode === 'gift_each_product') && count($gifts) === 0) continue;
            $count =& $rule['PROPERTY_COUNT_GIFTS_VALUE'];
            switch ($actionCode) {
                case 'gift':
                    foreach ($gifts as $index => $productId) {
                        // Количество штук, которое нужно предоставить в качестве подарка
                        $issue = !empty($count[$index]) ? $count[$index] : 1;
                        $products[$productId]['QUANTITY'] += $issue;
                    }
                    break;
                case 'gift_each_product':
                    foreach ($gifts as $index => $productId) {
                        if (!isset($rule['PROPERTY_COUNT_PRODUCTS_VALUE'][$index])) break;
                        // Делитель - для получения кратности
                        $counter = str_replace(['>', '<'], '', $rule['PROPERTY_COUNT_PRODUCTS_VALUE'][$index]);
                        // Текущее количество товара в корзине
                        $basketQuantity =& $this->getBasketProducts()[$rule['PROPERTY_LIST_PRODUCTS_VALUE'][$index]]['QUANTITY'];
                        // Количество штук, которое нужно предоставить в качестве подарка
                        $issue = !empty($count[$index]) ? $count[$index] : 1;
                        $issue = intval($basketQuantity / $counter) * $issue;
                        $products[$productId]['QUANTITY'] += $issue;
                    }
                    break;
            }
        }

        // Получим недостающую информацию по товарам-подаркам
        if (count($products) > 0) {
            $result = \CIblockElement::GetList([], ['ID' => array_keys($products)], false, false, ['ID', 'NAME', 'DETAIL_PAGE_URL', 'PREVIEW_TEXT', 'DETAIL_PICTURE']);
            while ($ar = $result->GetNext()) {
                $ar['NAME'] = str_replace('&quot;', '"', $ar['NAME']);
                $ar['DETAIL_PICTURE'] = CFile::GetPath($ar['DETAIL_PICTURE']);
                $ar['QUANTITY'] = $products[$ar['ID']]['QUANTITY'];
                // Обновим данные по товару
                $products[$ar['ID']] = $ar;
            }
        }
    }

    /**
     * Выдаём товары-подарки, которые полагаются согласно действующим правилам
     */
    private function giveGifts()
    {
        if (count($this->products) === 0) return;
        $products =& $this->products;
        foreach ($products as $product) {
            $this->addBasketProduct($product);
        }
    }

    // Методы по работе с корзиной

    private function getBasket()
    {
        if (!$this->basket) {
            $basket = \Bitrix\Sale\Basket::loadItemsForFUser(\Bitrix\Sale\Fuser::getId(), \Bitrix\Main\Context::getCurrent()->getSite());
            $basket->price = $this->getBasketCurrentPrice($basket); // Цена с учетом скидок
            $this->basket = $basket;
        }
        return $this->basket;
    }

    private function getBasketPrice()
    {
        return $this->getBasket()->price;
    }

    private function getBasketProducts()
    {
        if ($this->basketProducts) return $this->basketProducts;
        $products = $this->getBasket()->getBasketItems();
        foreach ($products as $product) {
            $this->basketProducts[$product->getProductId()] = [
                'ROW_ID' => $product->getId(),
                'PRODUCT_ID' => $product->getProductId(),
                'PRICE' => $product->getPrice(),
                'BASE_PRICE' => $product->getBasePrice(),
                'QUANTITY' => $product->getQuantity(),
                'AMOUNT' => $product->getFinalPrice(),
            ];
        }
        return $this->basketProducts;
    }

    private function getBasketCurrentPrice($basket)
    {
        if ($basket->count() == 0) return 0;

        \Bitrix\Sale\DiscountCouponsManager::freezeCouponStorage();
        $discounts = \Bitrix\Sale\Discount::loadByBasket($basket);
        $basket->refreshData(['PRICE', 'COUPONS']);
        $discounts->calculate();
        $discountResult = $discounts->getApplyResult();
        \Bitrix\Sale\DiscountCouponsManager::unFreezeCouponStorage();
        if (empty($discountResult['PRICES']['BASKET'])) {
            return 0;
        }
        $result = 0;
        $discountResult = $discountResult['PRICES']['BASKET'];
        /** @var BasketItem $basketItem */
        foreach ($basket as $basketItem) {
            if (!$basketItem->canBuy()) {
                continue;
            }
            $code = $basketItem->getBasketCode();
            if (!empty($discountResult[$code])) {
                $result += $discountResult[$code]['PRICE'] * $basketItem->getQuantity();
            }
            unset($code);
        }
        unset($basketItem, $discountResult);
        return $result;
    }

    // Методы по работе со строками корзины

    private function addBasketProduct($product)
    {
        $rows =& $this->arResult['GRID']['ROWS'];

        if (!$this->blank) {
            $this->blank = $rows[key($rows)];
        }

        $new = $this->getBasket()->createItem('catalog', $product['ID']);
        $new->setFields([
            'QUANTITY' => $product['QUANTITY'],
            'CURRENCY' => \Bitrix\Currency\CurrencyManager::getBaseCurrency(),
            'LID' => \Bitrix\Main\Context::getCurrent()->getSite(),
            'PRICE' => 0,
            'CUSTOM_PRICE' => 'Y',
            'NAME' => $product['NAME'],
            'DETAIL_PAGE_URL' => $product['DETAIL_PAGE_URL'],
            'XML_ID' => $product['EXTERNAL_ID'],
        ]);
        $new->save();

        $row = $this->blank;
        $row['ID'] = $new->getId();

        $row['PRODUCT_ID'] = $row['~PRODUCT_ID'] = $product['ID'];
        $row['NAME'] = $row['~NAME'] = $product['NAME'];

        $row['DETAIL_PAGE_URL'] = $product['DETAIL_PAGE_URL'];
        $row['PRODUCT_XML_ID'] = $product['EXTERNAL_ID'];
        $row['PREVIEW_TEXT'] = $product['PREVIEW_TEXT'];

        $row['DETAIL_PICTURE_SRC'] = $product['DETAIL_PICTURE'];
        $row['DETAIL_PICTURE_SRC_2X'] = $product['DETAIL_PICTURE'];
        $row['DETAIL_PICTURE_SRC_ORIGINAL'] = $product['DETAIL_PICTURE'];
        $row['DETAIL_PICTURE'] = $product['DETAIL_PICTURE'];

        $row['QUANTITY'] = $row['~QUANTITY'] = $product['QUANTITY'];
        $row['PRICE'] = 0;
        $row['PRICE_FORMATED'] = '0 руб.';
        $row['SUM'] = '0 руб.';
        $row['AVAILABLE_QUANTITY'] = 0;
        $row['DISCOUNT_PRICE_PERCENT'] = 0;
        $row['DISCOUNT_PRICE_PERCENT_FORMATED'] = '0%';

        $rows[$row['ID']] = $row;
    }

    private function deleteBasketProduct($product)
    {
        $row = $this->getBasket()->getItemById($product['ROW_ID']);
        unset($this->arResult['GRID']['ROWS'][$product['ROW_ID']], $this->basketProducts[$row->getProductId()]);
        $row->delete();
    }
}
