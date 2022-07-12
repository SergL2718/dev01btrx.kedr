<?php
/*
 * Изменено: 27 января 2022, четверг
 * Автор: Артамонов Денис <artamonov.d.i@yandex.com>
 * copyright (c) 2022
 */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arResult['SUBSCRIPTION']['FORMAT'] = 'html';

if (!empty($arResult["RUBRICS"]) && is_array($arResult["RUBRICS"]) && !empty($_REQUEST["SENDER_SUBSCRIBE_RUB_ID"])) {
    foreach ($arResult["RUBRICS"] as $k => $v) {
        if (in_array($v["ID"], $_REQUEST["SENDER_SUBSCRIBE_RUB_ID"])) {
            $arResult["RUBRICS"][$k]["CHECKED"] = "true";
        }
    }
}

$arResult["REQUEST"]["RUBRICS_PARAM"] = $_REQUEST["SENDER_SUBSCRIBE_RUB_ID"];
if (isset($_REQUEST["SENDER_SUBSCRIBE_EMAIL"]) && !empty($_REQUEST["SENDER_SUBSCRIBE_EMAIL"]))
    $arResult["REQUEST"]["EMAIL"] = $_REQUEST["SENDER_SUBSCRIBE_EMAIL"];

// Подпишем емаил на новости
$_POST['SENDER_SUBSCRIBE_EMAIL'] = trim($_POST['SENDER_SUBSCRIBE_EMAIL']);
if ($_POST['SENDER_SUBSCRIBE_EMAIL']) {
    $arFields = [
        'USER_ID' => $USER->IsAuthorized() ? $USER->GetID() : false,
        'FORMAT' => 'html',
        'EMAIL' => $_POST['SENDER_SUBSCRIBE_EMAIL'],
        'ACTIVE' => 'Y',
        'CONFIRMED' => 'Y',
        'RUB_ID' => [4],
        'SEND_CONFIRM' => 'N'
    ];
    $CSubscription = new CSubscription;
    $id = $CSubscription->Add($arFields);
    if ($id > 0) {
        // Отошлем уведомление
        $arFields = [
            'EMAIL' => $_POST['SENDER_SUBSCRIBE_EMAIL'],
            'DATE' => date('d.m.Y H:i')
        ];
        CEvent::Send('SENDER_SUBSCRIBE_CONFIRM', \Zk\Main\Helper::siteId(), $arFields, 'N', 135);
        $arResult['NEW_SUBSCRIBE'] = true;
    } else {
        $arResult['SUBSCRIPTION_MESSAGE'] = $CSubscription->LAST_ERROR;
    }
}
// Подписанные емаилы пользователя
if ($USER->IsAuthorized()) {
    $dbResult = CSubscription::GetList(['DATE_INSERT' => 'DESC'], ['USER_ID' => $USER->GetID(), 'CONFIRMED' => 'Y', 'ACTIVE' => 'Y']);
    while ($subscription = $dbResult->fetch()) {
        $arResult['SUBSCRIPTIONS'][$subscription['ID']] = $subscription;
    }
}
// Если пользователь решил отписать свои емаилы
if ($USER->IsAuthorized() && $_POST['UNSUBSCRIBE_SUBSCRIPTIONS'] && is_array($_POST['UNSUBSCRIBE_SUBSCRIPTIONS'])) {
    foreach ($_POST['UNSUBSCRIBE_SUBSCRIPTIONS'] as $subscriptionId) {
        CSubscription::Delete($subscriptionId);
    }
    LocalRedirect('');
}
