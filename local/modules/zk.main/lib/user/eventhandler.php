<?php
/*
 * @updated 07.12.2020, 19:16
 * @author Артамонов Денис <bitrix.developer@inbox.ru>
 * @copyright Copyright (c) 2020, Компания Webco <hello@wbc.cx>
 * @link http://wbc.cx
 */

namespace Zk\Main\User;


use CEvent;
use CUser;
use Zk\Main\Helper;
use Zk\Main\Spam\Protect;

class EventHandler
{
    /**
     * @param $arFields
     * @return bool
     */
    public function OnBeforeUserRegister(&$arFields)
    {
        // 1. Проверка пользователя по базе спама, если он присутствует в ней, отклоняем регистрацию
        if (!(new Protect())->check($arFields['USER_IP'], $arFields['EMAIL'])) {
            $GLOBALS['APPLICATION']->ThrowException('Обнаружен спам');
            return false;
        }
        return true;
    }

    /**
     * @param $arFields
     * @return bool
     */
    public function OnAfterUserRegister(&$arFields)
    {
        if (!$arFields['USER_ID']) {
            return false;
        }

        // Высылаем пользователю его регистрационную информацию
        $ar = [];
        if ($arFields['LOGIN']) {
            $ar['LOGIN'] = $arFields['LOGIN'];
        }
        if ($arFields['NAME']) {
            $ar['NAME'] = $arFields['NAME'];
        }
        if ($arFields['LAST_NAME']) {
            $ar['LAST_NAME'] = $arFields['LAST_NAME'];
        }
        if ($arFields['PASSWORD']) {
            $ar['PASSWORD'] = $arFields['PASSWORD'];
        }
        if ($arFields['EMAIL']) {
            $ar['EMAIL'] = $arFields['EMAIL'];
        }
        if ($_SERVER['SERVER_NAME']) {
            $ar['SITE_NAME'] = $_SERVER['SERVER_NAME'];
        }

        \Bitrix\Main\Mail\Event::sendImmediate([
            'EVENT_NAME' => 'MAIN_USER_INFO',
            'LID' => Helper::siteId(),
            'C_FIELDS' => $ar,
            'LANGUAGE_ID' => LANGUAGE_ID
        ]);

        // Сохраняем IP-адрес пользователя при регистрации
        if ($arFields['USER_ID'] && $_SERVER['REMOTE_ADDR']) {
            $user = new CUser;
            $user->Update($arFields['USER_ID'], ['UF_IP' => $_SERVER['REMOTE_ADDR']]);
        }
    }
}
