<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?
/**
 * @author Артамонов Денис <artamonov.ceo@gmail.com>
 * @updated 28.05.2020, 10:03
 * @copyright 2011-2020
 */

if (!function_exists('printAvailHtmlV1Table')) {
    function printAvailHtmlV1Table($qty, $measure, $params, $showCatalogQtyCnt)
    {
        if ($params["SHOW_CATALOG_QUANTITY"] == "Y") {
            if ($params["SHOW_CATALOG_QUANTITY_CNT"] == "Y") {
                $html = '<div class="bxr-instock-wrap 555">';
                if ($qty > 0) {
                    if ($params["QTY_SHOW_TYPE"] == "NUM" || $params["QTY_SHOW_TYPE"] == "") {
                        $qtyText = $qty;
                    } elseif ($qty > $params["QTY_MANY_GOODS_INT"]) {
                        $qtyText = $params["QTY_MANY_GOODS_TEXT"];
                    } else {
                        $qtyText = $params["QTY_LESS_GOODS_TEXT"];
                    };
                    $html .= "<i class='fa fa-check'> " . $qtyText . "</i>";
                } else {
                    $html .= "<i class='fa fa-times'> 0" . $qtyText . "</i>";
                }
                $html .= '</div>';
            } else {
                $html = '<div class="bxr-instock-wrap 555">';
                if ($qty > 0) {
                    $html .= "<i class='fa fa-check'></i>";
                    $html .= $params["IN_STOCK"];
                } else {
                    $html .= "<i class='fa fa-times'></i>";
                    $html .= $params["NOT_IN_STOCK"];
                }
                $html .= '</div>';
            }
            return $html;
        }
    }
}
// echo "<pre>";
// print_r($arElementParams);
// echo "</pre>";
$params = array(
    "IN_STOCK" => $arElementParams["IN_STOCK"],
    "NOT_IN_STOCK" => $arElementParams["NOT_IN_STOCK"],
    "QTY_SHOW_TYPE" => $arElementParams["QTY_SHOW_TYPE"],
    "QTY_MANY_GOODS_INT" => $arElementParams["QTY_MANY_GOODS_INT"],
    "QTY_MANY_GOODS_TEXT" => $arElementParams["QTY_MANY_GOODS_TEXT"],
    "QTY_LESS_GOODS_TEXT" => $arElementParams["QTY_LESS_GOODS_TEXT"],
    "SHOW_CATALOG_QUANTITY_CNT" => $arElementParams["SHOW_CATALOG_QUANTITY_CNT"],
    "SHOW_CATALOG_QUANTITY" => $arElementParams["SHOW_CATALOG_QUANTITY"],
);
?>

<? if ($arElement['PROPERTIES']['CHANNEL_SALE']['VALUE_XML_ID'] !== \Native\App\Catalog\Product::TYPE_RETAIL): ?>
    <?= printAvailHtmlV1Table($arElement["CATALOG_QUANTITY"], $arElement["CATALOG_MEASURE_NAME"], $params, $showCatalogQtyCnt) ?>
<?php endif ?>
