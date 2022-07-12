<?php
/*
 * Изменено: 29 декабря 2021, среда
 * Автор: Артамонов Денис <artamonov.d.i@yandex.com>
 * copyright (c) 2022
 */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

?>

<h2>Панель менеджера</h2>
<p>
    Данная панель видна лишь пользователям относящимся к группе: Администраторы интернет-магазина.
</p>

<?
$APPLICATION->IncludeComponent('native:manager.login.form', '', [], false, ['HIDE_ICONS' => 'Y']);
?>
