<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if ($showCatalogQty) {
    $params = array(
        "SHOW_MAX_QUANTITY" => $arElementParams["SHOW_MAX_QUANTITY"],
        "MESS_SHOW_MAX_QUANTITY" => $arElementParams["MESS_SHOW_MAX_QUANTITY"],
        "RELATIVE_QUANTITY_FACTOR" => $arElementParams["RELATIVE_QUANTITY_FACTOR"],
        "MESS_RELATIVE_QUANTITY_MANY" => $arElementParams["MESS_RELATIVE_QUANTITY_MANY"],
        "MESS_RELATIVE_QUANTITY_FEW" => $arElementParams["MESS_RELATIVE_QUANTITY_FEW"],
        "QUANTITY_IN_STOCK" => $arElementParams["QUANTITY_IN_STOCK"],
        "QUANTITY_OUT_STOCK" => $arElementParams["QUANTITY_OUT_STOCK"],
    );
    ?><div class="bxr-element-avail" id="<?=$arItemIDs["AVAIL"]?>"><?
if (count($arElement["OFFERS"]) > 0) {?>
    <div class="bxr-main-avail-wrap">
<?}
    echo \Alexkova\Market2\Core::printAvailHtmlV2Lite($arElement["CATALOG_QUANTITY"], $arElement["CATALOG_MEASURE_NAME"], $params);
if (count($arElement["OFFERS"]) > 0) {?>
    </div>
<?  foreach ($arElement["OFFERS"] as $offer) {?>
    <div class="bxr-offer-avail-wrap" id="bxr-offer-avail-<?=$offer["ID"]?>" data-item="<?=$offer["ID"]?>" style="display: none;">
        <?echo \Alexkova\Market2\Core::printAvailHtmlV2Lite($offer["CATALOG_QUANTITY"], $offer["CATALOG_MEASURE_NAME"], $params);?>
    </div>
<?}?>
<?}?>
    </div>
<?}