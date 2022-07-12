<?php
/*
 * Изменено: 27 февраля 2022, воскресенье
 * Автор: Артамонов Денис <artamonov.d.i@yandex.com>
 * copyright (c) 2022
 */

/**
 * @global CMain $APPLICATION
 * @global CUser $USER
 */

/*
 * Скрипт проверяет доступ к товару на основании токена, который выдается пользователю в виде короткой ссылки
 */

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php';
$APPLICATION->SetTitle('Проверка доступа');
$app = \Bitrix\Main\Application::getInstance();
$request = $app->getContext()->getRequest()->getRequestUri();
$request = trim($request, '/');
$token = str_replace('access/', '', $request);
if (!$token) {
    echo 'Некорректный токен доступа';
    require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php';
    die;
}

/*$r = '\AccessTable';
    if (class_exists($r)) {
        $r = $r::getList([
            'select' => [
                'UF_OPTIONS',
            ],
            'filter' => [
                '!UF_OPTIONS' => false,
            ],
            'limit' => 1,
        ]);
        if ($r->getSelectedRowsCount() > 0) {
            return json_decode($r->fetchRaw()['UF_OPTIONS'], true);
        }
    }
    return [];*/

try {
    \Bitrix\Main\Loader::includeModule('highloadblock');
    $storage = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity('Access')->getDataClass();
} catch (\Bitrix\Main\SystemException $e) {
    echo 'Токен не найден или его срок годности истёк';
    require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php';
    die;
}
$date = new \Bitrix\Main\Type\DateTime();
$access = $storage::getList([
    'select' => [
        '*',
    ],
    'filter' => [
        '=UF_TOKEN' => $token,
        '<=UF_DATE_FROM' => $date,
        '>=UF_DATE_TO' => $date,
    ],
    'order' => [
        'UF_DATE_FROM' => 'DESC',
        'ID' => 'DESC',
    ],
    'limit' => 1,
]);
if ($access->getSelectedRowsCount() === 0) {
    echo 'Токен не найден или его срок годности истёк';
    require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php';
    die;
}
$access = $access->fetchRaw();
if (!$USER->IsAdmin() && $access['UF_USER_ID'] > 0) {
    if (!$USER->IsAuthorized()) {
        echo 'Для получения доступа необходимо авторизоваться на сайте';
        require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php';
        die;
    }
    if ($USER->GetID() != $access['UF_USER_ID']) {
        echo 'Токен "' . $token . '" не может быть использован текущим пользователем';
        require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php';
        die;
    }
}
$entity = [];
switch ($access['UF_ENTITY_CODE']) {
    case 'PRODUCT':
        $entity = \CCatalogProduct::GetByIDEx($access['UF_ENTITY_ID']);
        $parents = [];
        if ($entity['PRODUCT']['TYPE'] == Bitrix\Catalog\ProductTable::TYPE_OFFER && !isset($entity['PROPERTIES']['DOWNLOAD_LINK'])) {
            if ($parent = \CCatalogSku::GetProductInfo($entity['ID'])) {
                if (!isset($parents[$parent['ID']])) {
                    $p = \CIBlockElement::GetProperty($parent['IBLOCK_ID'], $parent['ID'], ['sort' => 'asc'], ['CODE' => 'DOWNLOAD_LINK']);
                    if ($p->SelectedRowsCount() > 0) {
                        $p = $p->Fetch();
                        $parents[$parent['ID']]['PROPERTIES'][$p['CODE']] = $p;
                    }
                }
                if (isset($parents[$parent['ID']]['PROPERTIES']['DOWNLOAD_LINK'])) {
                    $entity['PROPERTIES']['DOWNLOAD_LINK'] = $parents[$parent['ID']]['PROPERTIES']['DOWNLOAD_LINK'];
                }
            }
        }
        if ($entity['PROPERTIES']['DOWNLOAD_LINK']['VALUE']) {
            try {
                $r = $storage::update($access['ID'], [
                    'UF_COUNT' => $access['UF_COUNT'] + 1,
                ]);
                if ($r->isSuccess()) {
                    LocalRedirect($entity['PROPERTIES']['DOWNLOAD_LINK']['VALUE'], true);
                } else {
                    echo 'Не удалось получить доступ';
                    require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php';
                }
                die;
            } catch (Exception $e) {
                echo 'Не удалось получить доступ';
                require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php';
                die;
            }
        }
        break;
}
require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php';