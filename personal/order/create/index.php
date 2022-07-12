<?php
/*
 * Изменено: 04 июля 2021, воскресенье
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

/**
 * @var $APPLICATION
 * @var $USER
 */

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

global $APPLICATION, $USER;

$APPLICATION->SetTitle('Оформление заказа');

$APPLICATION->IncludeComponent('native:order.create', '', [], false, ['HIDE_ICONS' => 'Y']);

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
