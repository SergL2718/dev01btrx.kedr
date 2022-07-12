<?php
/*
 * @author Артамонов Денис <artamonov.ceo@gmail.com>
 * @updated 30.09.2020, 16:12
 * @copyright 2011-2020
 */

/**
 * @var $USER
 */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die;

$arResult['SUBSCRIBE_RUB_ID'] = 4;

if ($USER->isAuthorized()) {
    $arResult['EMAIL'] = $USER->GetEmail();
}
