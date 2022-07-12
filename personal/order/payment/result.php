<?php
/*
 * Изменено: 04 июля 2021, воскресенье
 * Автор: Артамонов Денис <artamonov.d.i@yandex.com>
 * copyright (c) 2022
 */

die(false);
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Результат оплаты");
$APPLICATION->IncludeComponent(
    "bitrix:sale.order.payment.receive",
    "",
    array(
        "PAY_SYSTEM_ID" => "",
        "PERSON_TYPE_ID" => ""
    ),
    false
);
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");