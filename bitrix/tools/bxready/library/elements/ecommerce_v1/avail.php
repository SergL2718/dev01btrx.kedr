<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?
/**
 * @author Артамонов Денис <artamonov.ceo@gmail.com>
 * @updated 28.05.2020, 10:03
 * @copyright 2011-2020
 */

$showCatalogQtyCnt = ('Y' == $arElementParams["SHOW_CATALOG_QUANTITY_CNT"]);

if (!function_exists('printAvailHtml')) {
    function printAvailHtml($qty, $measure, $params, $showCatalogQtyCnt)
    {
        $html = '<!--noindex--><div class="bxr-instock-wrap">';
        if ($qty > 0) {
            $html .= "<i class='fa fa-check'></i>";
        } else {
            $html .= "<i class='fa fa-times'></i>";
        };
        if ($qty > 0) {
            $html .= $params["IN_STOCK"];
        } else {
            $html .= $params["NOT_IN_STOCK"];
        };
        if ($showCatalogQtyCnt && $qty > 0) {
            if ($params["QTY_SHOW_TYPE"] == "NUM") {
                $qtyText = $qty . " " . $measure;
            } elseif ($qty > $params["QTY_MANY_GOODS_INT"]) {
                $qtyText = $params["QTY_MANY_GOODS_TEXT"];
            } else {
                $qtyText = $params["QTY_LESS_GOODS_TEXT"];
            }
            $html .= ' (' . $qtyText . ')';
        }
        $html .= '</div><!--/noindex-->';

        return $html;
    }
}

$params = array(
    "IN_STOCK" => $arElementParams["IN_STOCK"],
    "NOT_IN_STOCK" => $arElementParams["NOT_IN_STOCK"],
    "QTY_SHOW_TYPE" => $arElementParams["QTY_SHOW_TYPE"],
    "QTY_MANY_GOODS_INT" => $arElementParams["QTY_MANY_GOODS_INT"],
    "QTY_MANY_GOODS_TEXT" => $arElementParams["QTY_MANY_GOODS_TEXT"],
    "QTY_LESS_GOODS_TEXT" => $arElementParams["QTY_LESS_GOODS_TEXT"]
);
if (count($arElement["OFFERS"]) > 0) { ?>
<div class="bxr-main-avail-wrap">
    <?
    }

    if ($arElement['PROPERTIES']['CHANNEL_SALE']['VALUE_XML_ID'] !== \Native\App\Catalog\Product::TYPE_RETAIL):
        echo printAvailHtml($arElement["CATALOG_QUANTITY"], $arElement["CATALOG_MEASURE_NAME"], $params, $showCatalogQtyCnt);
    else:?>
        <div class="only-online-store-notification">
            ДОСТУПНО ТОЛЬКО В МАГАЗИНЕ «МЕГРЕ» в Новосибирске
        </div>
    <?php endif ?>

    <?php
    if (count($arElement["OFFERS"]) > 0) { ?>
</div>
<? foreach ($arElement["OFFERS"] as $offer) { ?>
    <div class="bxr-offer-avail-wrap" id="bxr-offer-avail-<?= $offer["ID"] ?>" data-item="<?= $offer["ID"] ?>"
         style="display: none;">
        <? echo printAvailHtml($offer["CATALOG_QUANTITY"], $offer["CATALOG_MEASURE_NAME"], $params, $showCatalogQtyCnt); ?>
    </div>
<? } ?>
<? } ?>

