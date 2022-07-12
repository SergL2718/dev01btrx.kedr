<?
$bAJAXMode = (isset($_REQUEST['bxr_ajax']) && ($_REQUEST['bxr_ajax']=='yes') || isset($_REQUEST['mode']) && ($_REQUEST['mode']=='ajax'));
if ($bAJAXMode) {
    define("NO_KEEP_STATISTIC", TRUE);?>
    <script src="/bitrix/components/alexkova.market/catalog.product.subscribe/templates/.default/script.js" type="text/javascript"></script>
    <link href="/bitrix/components/alexkova.market/catalog.product.subscribe/templates/.default/style.css" type="text/css" rel="stylesheet">
<?} else {
    $elementDraw->setAdditionalFile("JS", "/bitrix/components/alexkova.market/catalog.product.subscribe/templates/.default/script.js", true);
    $elementDraw->setAdditionalFile("CSS", "/bitrix/components/alexkova.market/catalog.product.subscribe/templates/.default/style.css", false);
}
?>