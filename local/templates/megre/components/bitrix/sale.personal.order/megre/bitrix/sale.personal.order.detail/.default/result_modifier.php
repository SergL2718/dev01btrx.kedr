<?php
/*
 * Изменено: 15 июля 2021, четверг
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

/**
 * @var array $arResult
 */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die;

foreach ($arResult['BASKET'] as $item) {
    $arResult['PRODUCTS'][$item['PRODUCT_ID']]['NAME'] = $item['NAME'];
}