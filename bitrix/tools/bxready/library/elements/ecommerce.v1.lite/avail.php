<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$showCatalogQtyCnt = ('Y' == $arElementParams["SHOW_CATALOG_QUANTITY_CNT"]);

if (!function_exists('printAvailHtmlV1Lite'))
{
    function printAvailHtmlV1Lite($qty, $measure, $params, $showCatalogQtyCnt) {
        $html = '<div class="bxr-instock-wrap">';
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
                    $qtyText = $qty." ".$measure;
            } elseif ($qty > $params["QTY_MANY_GOODS_INT"]) {
                $qtyText = $params["QTY_MANY_GOODS_TEXT"];
            } else {
                $qtyText = $params["QTY_LESS_GOODS_TEXT"];
            }
            $html .= ' ('.$qtyText.')';
        }
        $html .= '</div>';

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

echo printAvailHtmlV1Lite($arElement["CATALOG_QUANTITY"], $arElement["CATALOG_MEASURE_NAME"], $params, $showCatalogQtyCnt);


