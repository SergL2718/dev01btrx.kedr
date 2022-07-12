<?php
/*
 * @updated 07.12.2020, 19:16
 * @author Артамонов Денис <bitrix.developer@inbox.ru>
 * @copyright Copyright (c) 2020, Компания Webco <hello@wbc.cx>
 * @link http://wbc.cx
 */

namespace Zk\Main\Main\Agent;


use Bitrix\Main\Loader;
use CIBlockElement;
use Zk\Main\Helper;
use Zk\Main\IBlock\Property;

class CheckingReceivedGoods extends Base
{
    private $goods;

    public function __construct()
    {
        $this->function = '(new \Zk\Main\Main\Agent\CheckingReceivedGoods())->run();';
    }

    /**
     * Если пользователь подписывался на уведомление о поступлении товара
     * Тогда, если товар появится в магазине, отошлем ему уведомление на емаил
     *
     * @return string
     */
    public function run()
    {
        Loader::includeModule('iblock');
        $sent = false;
        $description = [];
        $arFilter = [
            'IBLOCK_ID' => Helper::IBLOCK_APPLICATIONS_PRODUCT,
            'PROPERTY_NOTIFICATION_SENT' => false,
            '!PROPERTY_TRADE_ID_HIDDEN' => 109597,
        ];
        $arSelect = [
            'DATE_CREATE',
            'PROPERTY_TRADE_ID_HIDDEN',
            'PROPERTY_TRADE_NAME_HIDDEN',
            'PROPERTY_TRADE_LINK_HIDDEN',
            'PROPERTY_USER_NAME',
            'PROPERTY_USER_MAIL'
        ];
        $res = \CIBlockElement::GetList(['ID' => 'ASC'], $arFilter, false, false, $arSelect);
        while ($ar = $res->fetch()) {
            if (!$ar['PROPERTY_TRADE_ID_HIDDEN_VALUE'] || $this->getQuantity($ar['PROPERTY_TRADE_ID_HIDDEN_VALUE']) <= 0) continue;
            $ar['DATE_CREATE'] = \DateTime::createFromFormat('d.m.Y H:i:s', $ar['DATE_CREATE'])->format('d.m.Y');
            $this->sendEmail($ar);
            $this->updateDateNotification($ar['ID']);
            $sent = true;
            $description[] = $ar['PROPERTY_USER_MAIL_VALUE'] . ': ' . $ar['PROPERTY_TRADE_NAME_HIDDEN_VALUE'];
        }
        if ($sent) {
            sort($description);
            array_unshift($description, 'Уведомления отправлены');
            $description = implode('<br>', $description);
            $this->log('Checking', 'Products', $description);
        }
        return $this->function;
    }

    private function getQuantity($productId)
    {
        if (!$this->goods[$productId]) {
            $this->goods[$productId] = 0;
            $active = \CIBlockElement::GetByID($productId)->fetch()['ACTIVE'];
            if ($active === 'Y') {
                $this->goods[$productId] = \CCatalogProduct::GetByID($productId)['QUANTITY'];
            }
        }
        return $this->goods[$productId];
    }

    private function sendEmail($ar)
    {
        \CEvent::Send('SALE_SUBSCRIBE_PRODUCT', Helper::siteId(), [
            'DATE_APPLICATION' => $ar['DATE_CREATE'],
            'USER_NAME' => $ar['PROPERTY_USER_NAME_VALUE'],
            'EMAIL' => $ar['PROPERTY_USER_MAIL_VALUE'],
            'NAME' => $ar['PROPERTY_TRADE_NAME_HIDDEN_VALUE'],
            'PAGE_URL' => $ar['PROPERTY_TRADE_LINK_HIDDEN_VALUE']
        ], 'N');
    }

    private function updateDateNotification($noticeId)
    {
        Property::set($noticeId, 'NOTIFICATION_SENT', date('Y-m-d H:i:s'));
    }
}

