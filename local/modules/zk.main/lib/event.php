<?php
/*
 * @updated 07.12.2020, 19:16
 * @author Артамонов Денис <bitrix.developer@inbox.ru>
 * @copyright Copyright (c) 2020, Компания Webco <hello@wbc.cx>
 * @link http://wbc.cx
 */

namespace Zk\Main;


use Bitrix\Main\EventManager;

class Event
{
    private static $_instance;

    public function check()
    {
        //EventManager::getInstance()->addEventHandler('sale', 'OnAfterSaleOrderFinalAction', ['\\Zk\\Main\\Sale\\EventHandler', 'OnSaleBasketItemRefreshData']);
        //EventManager::getInstance()->addEventHandler('sale', 'OnBeforeBasketUpdate', ['\\Zk\\Main\\Sale\\EventHandler', 'OnBeforeBasketUpdate']);
        //EventManager::getInstance()->addEventHandler('sale', 'OnBasketUpdate', ['\\Zk\\Main\\Sale\\EventHandler', 'OnBasketUpdate']);

        // Дорабатываем форму просмотра Заказа
        //EventManager::getInstance()->addEventHandler('main', 'OnAdminSaleOrderView', ['\\Zk\\Main\\Main\\EventHandler', 'OnAdminSaleOrderView']);

        // Дорабатываем форму Отгрузки
        //EventManager::getInstance()->addEventHandler('main', 'OnAdminTabControlBegin', ['\\Zk\\Main\\Main\\EventHandler', 'OnAdminTabControlBegin']);

        // Обработка оплаты заказов
        // deprecated since 2020-11-12
        //EventManager::getInstance()->addEventHandler('sale', 'OnSalePayOrder', ['\\Zk\\Main\\Sale\\EventHandler', 'OnSalePayOrder']);
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
