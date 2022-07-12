<?php
/*
 * Изменено: 22 ноября 2021, понедельник
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

/**
 * @global CMain $APPLICATION
 */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die;
?>
<section class="main-banner-group">
    <?php $APPLICATION->IncludeComponent('native:static.block', 'banner.gift.from.taiga') ?>
    <?php $APPLICATION->IncludeComponent('native:static.block', 'banner.contactless.delivery') ?>
</section>
