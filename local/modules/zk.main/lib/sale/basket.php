<?php
/*
 * Изменено: 04 июля 2021, воскресенье
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

namespace Zk\Main\Sale;


use Zk\Main\Helper;
use \Bitrix\Main\Loader;

/**
 * @deprecated
 * Данный класс не нужно использовать
 * Вся логика была вынесена в шаблон компнента
 * Путь к актуальному классу: /local/templates/.default/components/bitrix/sale.basket.basket/market/basket.php
 *
 * Доработка произведена в файле result_modifier.php
 * Путь к актуальному классу: /local/templates/.default/components/bitrix/sale.basket.basket/market/result_modifier.php
 *
 * Class Basket
 * @package Zk\Main\Sale
 */

return;

class Basket
{
    private static $_instance;
    private $basket;
    private $basketProducts;
    private $rules;
    private $gifts;
    private $reloadPage = false;

    private $storeRulesName = 'BITRIX_BASKET_RULES';
    private $storeRulesPath = '/personal/basket';
    private $storeRulesTime = 8640000;

    private $run = false;

    private $map = [
        'PROPERTIES' => [
            'ACTION' => [
                2491 => 'gift',
                2492 => 'gift_each_product',
                // 2493 => 'discount',
                // 2494 => 'discount_each_product',
                // 2495 => 'markup',
                // 2496 => 'markup_each_product',
            ]
        ]
    ];

    /*
     * Проверка дополнительных правил корзины
     */
    public function checkRules($ID, $arFields)
    {
        // Don't use
        return;

        if ($this->run === true) return;

        if (mb_strpos($_SERVER['REQUEST_URI'], 'personal/basket') === false) return;


        Helper::_print($ID);
        Helper::_print($arFields);
        Helper::_print($_SERVER);
        Helper::_print('---------');
        //return;

        if (count($this->getRules()) === 0) return;

        foreach ($this->getRules() as $rule) {
            $this->checkRuleCondition($rule);
        }

        $this->getBasket()->save();

        //if ($this->reloadPage === true) header('Refresh:0');

        $this->run = true;
    }

    // Rules

    private function getRules()
    {
        if ($this->rules) return $this->rules;

        Loader::includeModule('iblock');

        $select = [
            'ID',
            'NAME',
            'PROPERTY_TOTAL_PRICE',
            'PROPERTY_LIST_PRODUCTS',
            'PROPERTY_COUNT_PRODUCTS',
            'PROPERTY_ACTION',
            'PROPERTY_DISCOUNT',
            'PROPERTY_MARKUP',
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

        $rules = \CIblockElement::GetList(['SORT' => 'ASC', 'DATE_ACTIVE_FROM' => 'ASC'], $filter, false, false, $select);

        while ($rule = $rules->fetch()) {
            $temp = [];
            $temp['ID'] = $rule['ID'];
            $temp['NAME'] = $rule['NAME'];

            // ------- Conditions
            // Price
            if (!empty($rule['PROPERTY_TOTAL_PRICE_VALUE'])) {
                $conditionPrice =& $rule['PROPERTY_TOTAL_PRICE_VALUE'];
                if (mb_strpos($rule['PROPERTY_TOTAL_PRICE_VALUE'], '>') !== false) {
                    $temp['CONDITION']['PRICE']['COMPARISON'] = '>';
                    $temp['CONDITION']['PRICE']['VALUE'] = str_replace('>', '', $conditionPrice);
                } else if (mb_strpos($rule['PROPERTY_TOTAL_PRICE_VALUE'], '<') !== false) {
                    $temp['CONDITION']['PRICE']['COMPARISON'] = '<';
                    $temp['CONDITION']['PRICE']['VALUE'] = str_replace('<', '', $conditionPrice);
                } else {
                    $temp['CONDITION']['PRICE']['COMPARISON'] = '=';
                    $temp['CONDITION']['PRICE']['VALUE'] = $conditionPrice;
                }
            }
            // Products
            if (count($rule['PROPERTY_LIST_PRODUCTS_VALUE']) > 0) {
                foreach ($rule['PROPERTY_LIST_PRODUCTS_VALUE'] as $index => $productId) {
                    $temp['CONDITION']['PRODUCT'][$index]['ID'] = $productId;
                    $quantity = $rule['PROPERTY_COUNT_PRODUCTS_VALUE'][$index];
                    if (mb_strpos($quantity, '>') !== false) {
                        $temp['CONDITION']['PRODUCT'][$index]['COMPARISON'] = '>';
                        $temp['CONDITION']['PRODUCT'][$index]['QUANTITY'] = str_replace('>', '', $quantity);
                    } else if (mb_strpos($quantity, '<') !== false) {
                        $temp['CONDITION']['PRODUCT'][$index]['COMPARISON'] = '<';
                        $temp['CONDITION']['PRODUCT'][$index]['QUANTITY'] = str_replace('<', '', $quantity);
                    } else if (!empty($quantity)) {
                        $temp['CONDITION']['PRODUCT'][$index]['COMPARISON'] = '=';
                        $temp['CONDITION']['PRODUCT'][$index]['QUANTITY'] = $quantity;
                    } else {
                        $temp['CONDITION']['PRODUCT'][$index]['COMPARISON'] = '>';
                        $temp['CONDITION']['PRODUCT'][$index]['QUANTITY'] = 0;
                    }
                }
            }

            // ------- Actions
            // Action type
            $temp['ACTION']['CODE'] = $this->map['PROPERTIES']['ACTION'][$rule['PROPERTY_ACTION_ENUM_ID']];
            // Gift
            if (($temp['ACTION']['CODE'] === 'gift' || $temp['ACTION']['CODE'] === 'gift_each_product') && count($rule['PROPERTY_GIFTS_VALUE']) > 0) {
                foreach ($rule['PROPERTY_GIFTS_VALUE'] as $index => $productId) {
                    $temp['ACTION']['GIFT'][$index]['ID'] = $productId;
                    $temp['ACTION']['GIFT'][$index]['QUANTITY'] = !empty($rule['PROPERTY_COUNT_GIFTS_VALUE'][$index]) ? $rule['PROPERTY_COUNT_GIFTS_VALUE'][$index] : 1;
                    $this->gifts[$productId] = [];
                }
            }

            if (!isset($temp['CONDITION']) || (($temp['ACTION']['CODE'] === 'gift' || $temp['ACTION']['CODE'] === 'gift_each_product') && !isset($temp['ACTION']['GIFT']))) {
                continue;
            }

            $this->rules[$rule['ID']] = $temp;
        }

        // Get gifts data
        if (count($this->gifts) > 0) {
            $gifts = \CIblockElement::GetList([], ['ID' => array_keys($this->gifts)], false, false, ['ID', 'NAME', 'DETAIL_PAGE_URL']);
            while ($gift = $gifts->GetNext()) {
                $gift['NAME'] = str_replace('&quot;', '"', $gift['NAME']);
                $this->gifts[$gift['ID']] = $gift;
            }
        }

        return $this->rules;
    }

    private function checkRuleCondition($rule)
    {
        $condition =& $rule['CONDITION'];
        $code =& $rule['ACTION']['CODE'];
        $apply =& $this->getAppliedRules()[$rule['ID']]['APPLY'];
        $action = false;
        $actionByPrice = false;
        $actionByProduct = false;

        if (isset($condition['PRICE'])) {
            $price =& $condition['PRICE']['VALUE'];
            $comparison =& $condition['PRICE']['COMPARISON'];
            $basketPrice =& $this->getBasketPrice();
            if (
                ($comparison === '>' && $basketPrice > $price) ||
                ($comparison === '<' && $basketPrice < $price) ||
                ($comparison === '=' && $basketPrice == $price)
            ) {
                $actionByPrice = $apply === true ? false : 'apply';
            } else if ($apply === true) {
                $actionByPrice = 'cancel';
            }
        }

        if (isset($condition['PRODUCT']) && $actionByPrice !== 'cancel') {
            $products =& $condition['PRODUCT'];
            $basketProducts =& $this->getBasketProducts();
            foreach ($products as $index => $product) {
                $actionByProduct = false;
                if (isset($basketProducts[$product['ID']])) {
                    $actionByProduct = $apply === true ? false : 'apply';
                    if (isset($product['COMPARISON'])) {
                        $actionByProduct = false;
                        $comparison = $product['COMPARISON'];
                        $quantity = $product['QUANTITY'];
                        $basketProduct =& $basketProducts[$product['ID']];

                        if ($code === 'gift_each_product') {
                            $issue = intval($basketProduct['QUANTITY'] / $product['QUANTITY']);
                            $issued = $this->getAppliedRules()[$rule['ID']]['GIFTS'][$rule['ACTION']['GIFT'][$index]['ID']];

                            if ($issue > 0) {
                                $quantity = $issue * $product['QUANTITY'];
                            }

                            if ($apply === true && $issue > $issued) {
                                $apply = false;
                            }

                            if ($issue >= $issued) {
                                $comparison = '>';
                            } else if ($issue < $issued) {
                                $comparison = '<';
                            }

                            $quantity = $quantity - 1;
                        }

                        if (
                            ($comparison === '>' && $basketProduct['QUANTITY'] > $quantity) ||
                            ($comparison === '<' && $basketProduct['QUANTITY'] < $quantity) ||
                            ($comparison === '=' && $basketProduct['QUANTITY'] == $quantity)
                        ) {
                            $actionByProduct = $apply === true ? false : 'apply';
                        } else {
                            if ($apply === true) {
                                $actionByProduct = 'cancel';
                            }
                            break;
                        }
                    }
                } else {
                    if ($apply === true) {
                        $actionByProduct = 'cancel';
                    }
                    break;
                }
            }
        }

        if ($actionByPrice === 'apply' && $actionByProduct === 'apply') {
            $action = 'apply';
        } else if ($actionByPrice === 'cancel' || $actionByProduct === 'cancel') {
            $action = 'cancel';
        } else if (isset($condition['PRICE']) && !isset($condition['PRODUCT'])) {
            $action = $actionByPrice;
        } else if (isset($condition['PRODUCT']) && !isset($condition['PRICE'])) {
            $action = $actionByProduct;
        }

        if ($action === false) return;

        $action .= 'RuleAction';
        $this->$action($rule['ID'], $rule['ACTION']);
        $this->reloadPage = true;
    }

    private function applyRuleAction($ruleId, $action)
    {
        $code =& $action['CODE'];
        $storageGifts = [];
        if ($code === 'gift' || $code === 'gift_each_product') {
            $gifts =& $action['GIFT'];

            foreach ($gifts as $index => $gift) {

                $giftId = $gift['ID'];
                $product =& $this->getBasketProducts()[$giftId];

                $issue = $gift['QUANTITY'];
                $issued = $this->getAppliedRules()[$ruleId]['GIFTS'][$giftId];

                if ($code === 'gift_each_product') {
                    if (!$conditionProduct =& $this->getRules()[$ruleId]['CONDITION']['PRODUCT'][$index]) continue;
                    if (!$basketProduct =& $this->getBasketProducts()[$conditionProduct['ID']]) continue;

                    $issue = intval($basketProduct['QUANTITY'] / $conditionProduct['QUANTITY']) * $gift['QUANTITY'];
                }

                $quantity = $issue - $issued;

                if ($product) {
                    $row = $this->getBasket()->getItemById($product['ROW_ID']);
                    $row->setField('QUANTITY', $row->getField('QUANTITY') + $quantity);
                    $row->save();
                } else {
                    $gift =& $this->gifts[$gift['ID']];
                    $row = $this->getBasket()->createItem('catalog', $gift['ID']);
                    $row->setFields([
                        'QUANTITY' => $quantity,
                        'CURRENCY' => \Bitrix\Currency\CurrencyManager::getBaseCurrency(),
                        'LID' => \Bitrix\Main\Context::getCurrent()->getSite(),
                        'PRICE' => 0,
                        'CUSTOM_PRICE' => 'Y',
                        'NAME' => $gift['NAME'],
                        'DETAIL_PAGE_URL' => $gift['DETAIL_PAGE_URL'],
                        'XML_ID' => $gift['EXTERNAL_ID'],
                    ]);
                    $row->save();
                    $this->basketProducts[$giftId] = [
                        'ROW_ID' => $row->getId(),
                        'PRODUCT_ID' => $giftId,
                        'PRICE' => 0,
                        'BASE_PRICE' => 0,
                        'QUANTITY' => $quantity,
                        'AMOUNT' => 0,
                    ];
                }
                $storageGifts[$giftId] = $issued + $quantity;
            }
        }
        $this->setAppliedRule($ruleId, true, $storageGifts);
    }

    private function cancelRuleAction($ruleId, $action)
    {
        $code =& $action['CODE'];
        $storageGifts = [];
        if ($code === 'gift' || $code === 'gift_each_product') {
            $gifts =& $action['GIFT'];

            foreach ($gifts as $index => $gift) {

                if ($product =& $this->getBasketProducts()[$gift['ID']]) {

                    $row = $this->getBasket()->getItemById($product['ROW_ID']);
                    $issued = $this->getAppliedRules()[$ruleId]['GIFTS'][$product['PRODUCT_ID']];
                    $return = $gift['QUANTITY'];
                    $quantity = $row->getField('QUANTITY');

                    if ($code === 'gift_each_product') {
                        $conditionProduct = $this->getRules()[$ruleId]['CONDITION']['PRODUCT'][$index];
                        $basketProduct = $this->getBasketProducts()[$conditionProduct['ID']];
                        $return = intval($basketProduct['QUANTITY'] / $conditionProduct['QUANTITY']) * $gift['QUANTITY'];
                    }

                    if ($issued > 0) {
                        $return = $return === 0 ? 1 : $return;
                        $issued = $issued - $return;
                        $quantity = $quantity - $return;
                    }

                    if ($quantity > 0) {
                        $row->setField('QUANTITY', $quantity);
                    } else {
                        $row->delete();
                    }
                    $storageGifts[$gift['ID']] = $issued;
                }
            }
        }
        $this->setAppliedRule($ruleId, false, $storageGifts);
    }

    private function getAppliedRules()
    {
        return unserialize($_COOKIE[$this->storeRulesName]);
    }

    private function setAppliedRule($ruleId, $mode, $gifts = [])
    {
        $rules = $this->getAppliedRules();
        $rules[$ruleId]['APPLY'] = $mode === true ? true : false;
        $rules[$ruleId]['GIFTS'] = $gifts;
        setcookie($this->storeRulesName, serialize($rules), time() + $this->storeRulesTime, $this->storeRulesPath);
    }

    // Basket

    private function getBasket()
    {
        if (!$this->basket) {
            $basket = \Bitrix\Sale\Basket::loadItemsForFUser(\Bitrix\Sale\Fuser::getId(), \Bitrix\Main\Context::getCurrent()->getSite());
            $basket->price = $this->getBasketCurrentPrice($basket); // Цена с учетом скидок
            $basket->basePrice = $basket->getBasePrice(); // Цена без учета скидок
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
        if ($basket->count() == 0) {
            return 0;
        }
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
