<?php
/*
 * Изменено: 29 декабря 2021, среда
 * Автор: Артамонов Денис <artamonov.d.i@yandex.com>
 * copyright (c) 2022
 */

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php';
$APPLICATION->SetTitle('Регистрация на сайте');

$APPLICATION->IncludeComponent('native:registration.form', '', [], false, ['HIDE_ICONS' => 'Y']);

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php';
