<?php
/*
 * @updated 07.12.2020, 19:16
 * @author Артамонов Денис <bitrix.developer@inbox.ru>
 * @copyright Copyright (c) 2020, Компания Webco <hello@wbc.cx>
 * @link http://wbc.cx
 */

namespace Native\App\Foundation;


use Bitrix\Main\EventManager;

class Event
{
    private static ?Event $_instance = null;

    public function init()
    {
        $this->adminSection();
        $this->event();
        $this->iBlock();
        $this->sale();
        $this->subscribe();
    }

    private function adminSection()
    {
        EventManager::getInstance()->addEventHandler('main', 'OnAdminSaleOrderView', ['\\Native\\App\\Sale\\EventHandler', 'OnAdminSaleOrderView']);
    }

    private function event()
    {
        EventManager::getInstance()->addEventHandler('main', 'OnBeforeEventAdd', ['\\Native\\App\\Main\\EventHandler', 'OnBeforeEventAdd']);
        EventManager::getInstance()->addEventHandler('main', 'OnBeforeEventSend', ['\\Native\\App\\Main\\EventHandler', 'OnBeforeEventSend']);
    }

    private function iBlock()
    {
        EventManager::getInstance()->addEventHandler('iblock', 'OnBeforeIBlockElementAdd', ['\\Native\\App\\IBlock\\EventHandler', 'OnBeforeIBlockElementAdd']);
        EventManager::getInstance()->addEventHandler('iblock', 'OnBeforeIBlockElementUpdate', ['\\Native\\App\\IBlock\\EventHandler', 'OnBeforeIBlockElementUpdate']);
        EventManager::getInstance()->addEventHandler('iblock', 'OnAfterIBlockElementUpdate', ['\\Native\\App\\IBlock\\EventHandler', 'OnAfterIBlockElementUpdate']);
        EventManager::getInstance()->addEventHandler('iblock', 'OnBeforeIBlockSectionUpdate', ['\\Native\\App\\IBlock\\EventHandler', 'OnBeforeIBlockSectionUpdate']);
    }

    private function sale()
    {
        EventManager::getInstance()->addEventHandler('sale', 'OnSalePayOrder', ['\\Native\\App\\Sale\\EventHandler', 'OnSalePayOrder']);
        EventManager::getInstance()->addEventHandler('sale', 'OnSaleOrderSaved', ['\\Native\\App\\Sale\\EventHandler', 'OnSaleOrderSaved']);
    }

    private function subscribe()
    {
        EventManager::getInstance()->addEventHandler('subscribe', 'OnStartSubscriptionAdd', ['\\Native\\App\\Subscribe\\Event', 'OnStartSubscriptionAdd']);
        EventManager::getInstance()->addEventHandler('subscribe', 'OnStartSubscriptionUpdate', ['\\Native\\App\\Subscribe\\Event', 'OnStartSubscriptionUpdate']);
    }

    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self;
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
