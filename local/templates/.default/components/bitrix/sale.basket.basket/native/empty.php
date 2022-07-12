<?php
/*
 * @updated 24.12.2020, 23:45
 * @author Артамонов Денис <bitrix.developer@inbox.ru>
 * @copyright Copyright (c) 2020, Компания Webco <hello@wbc.cx>
 * @link https://wbc.cx
 */

/**
 * @var CMain $APPLICATION
 * @var CMain $USER
 * @var integer $ID
 */

if ($USER->IsAuthorized()) {
    $APPLICATION->IncludeComponent(
        "bitrix:sale.personal.order.list",
        "basket",
        array(
            "ACTIVE_DATE_FORMAT" => "d.m.Y",
            "ALLOW_INNER" => "N",
            "CACHE_GROUPS" => "Y",
            "CACHE_TIME" => "3600",
            "CACHE_TYPE" => "A",
            "COMPOSITE_FRAME_MODE" => "A",
            "COMPOSITE_FRAME_TYPE" => "AUTO",
            "DEFAULT_SORT" => "STATUS",
            "DISALLOW_CANCEL" => "N",
            "HISTORIC_STATUSES" => array("F"),
            "ID" => $ID,
            "NAV_TEMPLATE" => "",
            "ONLY_INNER_FULL" => "N",
            "ORDERS_PER_PAGE" => "30",
            "PATH_TO_BASKET" => "/personal/basket/",
            "PATH_TO_CANCEL" => "/personal/order/",
            "PATH_TO_CATALOG" => "/catalog/",
            "PATH_TO_COPY" => "/personal/order/",
            "PATH_TO_DETAIL" => "/personal/order/",
            "PATH_TO_PAYMENT" => "/personal/order/payment/",
            "REFRESH_PRICES" => "N",
            "RESTRICT_CHANGE_PAYSYSTEM" => array("0"),
            "SAVE_IN_SESSION" => "Y",
            "SET_TITLE" => "Y",
            "STATUS_COLOR_C" => "gray",
            "STATUS_COLOR_D" => "gray",
            "STATUS_COLOR_F" => "gray",
            "STATUS_COLOR_N" => "green",
            "STATUS_COLOR_O" => "gray",
            "STATUS_COLOR_P" => "yellow",
            "STATUS_COLOR_PSEUDO_CANCELLED" => "red"
        )
    );
    return;
}
?>

<h2 style="color: #8fbb3c;">
    Ваша корзина пуста. Перейдите в <a href="/catalog/" style="text-decoration: underline">каталог</a> и добавьте товары
    в корзину.
</h2>
