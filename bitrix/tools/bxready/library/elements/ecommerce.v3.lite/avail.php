<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
$showCatalogQtyCnt = ('Y' == $arElementParams["SHOW_CATALOG_QUANTITY_CNT"]);
if (!function_exists('printAvailHtmlV3Lite'))
{
    function printAvailHtmlV3Lite($qty, $measure, $params, $showCatalogQtyCnt) {
        if ($qty > 0) {
            $html = '<div class="bxr-instock-wrap">';
            if ($params["QTY_SHOW_TYPE"] == "NUM" || $params["QTY_SHOW_TYPE"] == "") {
                    $qtyText = $qty;
            } elseif ($qty > $params["QTY_MANY_GOODS_INT"]) {
                $qtyText = $params["QTY_MANY_GOODS_TEXT"];
            } else {
                $qtyText = $params["QTY_LESS_GOODS_TEXT"];
            };
            $html .= "<i class='fa fa-check'> ";
            $html .= ($showCatalogQtyCnt) ? $qtyText : "";
            $html .= "</i>";
            $html .= "</div>";
        }
        
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

echo printAvailHtmlV3Lite($arElement["CATALOG_QUANTITY"], $arElement["CATALOG_MEASURE_NAME"], $params, $showCatalogQtyCnt);?>
