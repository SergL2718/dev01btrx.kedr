<?php
/*
 * Изменено: 03 марта 2019, воскресенье
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php';
$APPLICATION->SetTitle('Войти на сайт');

$APPLICATION->IncludeComponent('native:login.form', '', [], false, ['HIDE_ICONS' => 'Y']);

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php';
