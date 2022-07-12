<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if ($USER->IsAuthorized()) {
    $user = CUser::GetList(($by='ID'), ($order='DESC'), ['ID' => $USER->GetID()], ['FIELDS' => ['ID', 'NAME', 'LAST_NAME', 'PERSONAL_PHONE', 'EMAIL']])->fetch();
    foreach ($arResult['PROPERTY_LIST_FULL'] as $code => &$ar) {
        switch ($ar['CODE']) {
            case 'USER_NAME':
                $ar['DEFAULT_VALUE'] = trim($user['NAME'].' '.$user['LAST_NAME']);
                break;
            case 'USER_PHONE':
                $ar['DEFAULT_VALUE'] = trim($user['PERSONAL_PHONE']);
                break;
            case 'USER_MAIL':
                $ar['DEFAULT_VALUE'] = trim($user['EMAIL']);
                break;
        }
    }
}
