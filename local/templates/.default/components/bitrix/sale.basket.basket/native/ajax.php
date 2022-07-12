<?php
/*
 * Изменено: 30 июня 2021, среда
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

define('STOP_STATISTICS', true);
define('NO_KEEP_STATISTIC', 'Y');
define('NO_AGENT_STATISTIC', 'Y');
define('DisableEventsCheck', true);
define('BX_SECURITY_SHOW_MESSAGE', true);
define('NOT_CHECK_PERMISSIONS', true);

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die;

(new Ajax($_POST))->response();

class Ajax
{
    private $request;

    public function __construct($request)
    {
        header('Content-Type: application/json');
        $this->request = $request;
    }

    public function response()
    {
        $action = $this->request['action'];
        if (!$action) {
            die;
        }
        unset($this->request['action']);
        echo json_encode($this->$action());
        die;
    }

    private function updateQuantity()
    {
        Bitrix\Main\Loader::includeModule('sale');
        $request =& $this->request;
        CSaleBasket::Update($request['rowId'], ['QUANTITY' => $request['quantity']]);
        return [
            'success' => true
        ];
    }

    private function deleteRow()
    {
        Bitrix\Main\Loader::includeModule('sale');
        $request =& $this->request;
        CSaleBasket::Delete($request['rowId']);
        return [
            'success' => true
        ];
    }

    private function setCoupon()
    {
        $request =& $this->request;
        $coupon =& $request['coupon'];

        Bitrix\Main\Loader::includeModule('sale');

        // Удалим ранее примененные купоны
        \Bitrix\Sale\DiscountCouponsManager::clear(true);

        // Добавим новый купон
        $exist = \Bitrix\Sale\DiscountCouponsManager::isExist($coupon['value']);
        if ($exist !== false) {

            $statuses = [
                \Bitrix\Sale\DiscountCouponsManager::STATUS_ENTERED => 'ENTERED',
                \Bitrix\Sale\DiscountCouponsManager::STATUS_APPLYED => 'APPLYED',
                \Bitrix\Sale\DiscountCouponsManager::STATUS_FREEZE => 'FREEZE',
            ];

            \Bitrix\Sale\DiscountCouponsManager::add($exist['COUPON']);

            $added = false;
            $message = '';

            $entered = \Bitrix\Sale\DiscountCouponsManager::getEnteredCoupon($exist['COUPON'], false);

            if ($statuses[$entered['STATUS']] === 'ENTERED') {
                $message = 'Промокод ' . $exist['COUPON'] . ' успешно применен!';
                $added = true;
            } else if ($statuses[$entered['STATUS']] === 'FREEZE') {
                $message = 'Промокод ' . $exist['COUPON'] . ' не может быть использован';
                \Bitrix\Sale\DiscountCouponsManager::clear(true);
                if (!empty($coupon['last']['value'])) {
                    \Bitrix\Sale\DiscountCouponsManager::add($coupon['last']['value']);
                }
            }

            return [
                'success' => true,
                'isExist' => true,
                'added' => $added,
                'message' => $message,
            ];

        } else {
            if (!empty($coupon['last']['value']) && !empty($coupon['value'])) {
                \Bitrix\Sale\DiscountCouponsManager::add($coupon['last']['value']);
            }
            if (empty($coupon['value'])) {
                return [
                    'error' => true,
                    'isExist' => false,
                    'deleted' => true,
                    'message' => 'Промокод удален',
                ];
            } else {
                return [
                    'error' => true,
                    'isExist' => false,
                    'message' => 'Промокод ' . $coupon['value'] . ' не найден!',
                ];
            }
        }
    }
}
