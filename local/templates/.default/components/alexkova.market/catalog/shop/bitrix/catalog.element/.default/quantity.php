<?php
/*
 * @author Denis Artamonov <software.engineer@internet.ru>
 * @copyright Copyright (c) 2021
 * @updated 24.03.2021, 23:26
 */

/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */

/** @var array $arResult */

$showCatalogQty = ('Y' == $arParams["SHOW_CATALOG_QUANTITY"]);
$showCatalogQtyCnt = ('Y' == $arParams["SHOW_CATALOG_QUANTITY_CNT"]);

if (!function_exists('printAvailHtml')) {
    function printAvailHtml($qty, $measure, $params, $showCatalogQtyCnt)
    {
        // https://megre.bitrix24.ru/workgroups/group/9/tasks/task/view/20973/
        // Если товар содержит торговые предложения
        // Но, торговое предложние еще не выбрано
        // Тогда доступность товара не отображаем
        if (
            $params['CATALOG_TYPE'] == \Bitrix\Catalog\ProductTable::TYPE_SKU
            &&
            (
                !isset($params['PRODUCT_TYPE'])
                ||
                $params['PRODUCT_TYPE'] != \Bitrix\Catalog\ProductTable::TYPE_OFFER
            )
        ) {
            $html = '<div class="bxr-instock-wrap">';
            $html .= '<i class="far fa-dot-circle"></i>' . GetMessage('CHOISE_OFFER');
            $html .= '</div>';
            return $html;
        }

        $html = '<div class="bxr-instock-wrap">';
        if ($qty > 0) {
            $html .= "<i class='fa fa-check'></i>";
        } else {
            $html .= "<i class='fa fa-times'></i>";
        }
        if ($qty > 0) {
            $html .= $params["IN_STOCK"];
        } else {
            $html .= $params["NOT_IN_STOCK"];
        }
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
        $html .= '</div>';
        return $html;
    }
}

if ($showCatalogQty):
    $params = array(
        "IN_STOCK" => $arParams["IN_STOCK"],
        "NOT_IN_STOCK" => $arParams["NOT_IN_STOCK"],
        "QTY_SHOW_TYPE" => $arParams["QTY_SHOW_TYPE"],
        "QTY_MANY_GOODS_INT" => $arParams["QTY_MANY_GOODS_INT"],
        "QTY_MANY_GOODS_TEXT" => $arParams["QTY_MANY_GOODS_TEXT"],
        "QTY_LESS_GOODS_TEXT" => $arParams["QTY_LESS_GOODS_TEXT"],
        // https://megre.bitrix24.ru/workgroups/group/9/tasks/task/view/20973/
        "CATALOG_TYPE" => $arResult['CATALOG_TYPE'],
    );
    if (count($arResult["OFFERS"]) > 0) { ?>
        <div class="bxr-main-avail-wrap">
        <?php
    }

    if ($arResult['PROPERTIES']['CHANNEL_SALE']['VALUE_XML_ID'] !== \Native\App\Catalog\Product::TYPE_RETAIL) {
        echo printAvailHtml($arResult["CATALOG_QUANTITY"], $arResult["CATALOG_MEASURE_NAME"], $params, $showCatalogQtyCnt);
    }

    if (count($arResult["OFFERS"]) > 0): ?>
        </div>
        <?php foreach ($arResult["OFFERS"] as $offer): ?>
            <div class="bxr-offer-avail-wrap" data-item="<?= $offer["ID"] ?>" style="display: none;">
                <?php
                // https://megre.bitrix24.ru/workgroups/group/9/tasks/task/view/20973/
                $params['PRODUCT_TYPE'] = \Bitrix\Catalog\ProductTable::TYPE_OFFER;
                echo printAvailHtml($offer["CATALOG_QUANTITY"], $offer["CATALOG_MEASURE_NAME"], $params, $showCatalogQtyCnt);
                ?>
            </div>
        <?php endforeach ?>
    <?php endif ?>
<?php endif ?>

