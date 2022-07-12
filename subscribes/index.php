<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php"); ?>

<? $APPLICATION->IncludeComponent(
    "alexkova.market:catalog.product.subscribe.list",
    "",
    [
        "CACHE_TIME"         => "3600",
        "CACHE_TYPE"         => "A",
        "LINE_ELEMENT_COUNT" => "3",
    ]
); ?>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>