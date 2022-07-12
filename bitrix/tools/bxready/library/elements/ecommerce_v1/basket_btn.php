<?
/**
 * @author Артамонов Денис <artamonov.ceo@gmail.com>
 * @updated 28.05.2020, 10:03
 * @copyright 2011-2020
 */

//--basket-btns block-->
global $ratio_settings, $bxr_ratio_prop_code;
$showSubscribeBtn = ($arElement['CATALOG_SUBSCRIBE'] == 'Y') ? true : false;
if (count($arElement["OFFERS"]) > 0) {
    foreach ($arElement["OFFERS"] as $offer) { ?>
        <div class="offers-btn-wrap" id="offers-btn-<?= $offer["ID"] ?>" style="display: none"
             data-item="<?= $offer["ID"] ?>">
            <?php if (
                    ($offer['PROPERTIES']['DOWNLOAD_LINK']['VALUE'] || $arElement['PROPERTIES']['DOWNLOAD_LINK']['VALUE'])
                    && ($offer['HAS_ACCESS_TO_PRODUCT'] || $arElement['HAS_ACCESS_TO_PRODUCT']))
                : ?>
                <a href="<?= $offer['PROPERTIES']['DOWNLOAD_LINK']['VALUE'] ?? $arElement['PROPERTIES']['DOWNLOAD_LINK']['VALUE'] ?>" class="bxr-color-button" target="_blank">Скачать</a>
            <?php else: ?>

            <?
            if ($offer["CATALOG_QUANTITY"] <= 0 && $offer["CATALOG_CAN_BUY_ZERO"] == "N" || !$offer["MIN_PRICE"]['VALUE']) { ?>
                <? if ($showSubscribeBtn) { ?>
                    <div class="bxr-subscribe-wrap">
                        <? include_once 'subscribe_script.php';

                        $APPLICATION->includeComponent('alexkova.market:catalog.product.subscribe', '',
                            array(
                                'PRODUCT_ID' => $offer['ID'],
                                'BUTTON_ID' => 'bxr-ev1-' . $UID . '-' . $offer['ID'] . '-subscribe',
                                'BUTTON_CLASS' => 'bxr-color-button bxr-subscribe',
                            ),
                            false, array('HIDE_ICONS' => 'Y')
                        ); ?>
                    </div>
                <? } else { ?>
                    <button class="bxr-color-button bxr-trade-request" value="<?= $offer["ID"] ?>"
                            data-offer-id="<?= $offer["ID"] ?>"
                            data-trade-id="<?= $arElement["ID"] ?>"
                            data-trade-name="<?= $arElement["NAME"] ?>"
                            data-trade-link="<?= $arElement["DETAIL_PAGE_URL"] ?>"
                            data-msg="<?= str_replace('#TRADE_NAME#', $arElement["NAME"], GetMessage('OFFER_REQUEST_MSG')) ?>">
                        <?= GetMessage("REQUEST_BTN") ?>
                    </button>
                    <?
                }
            } else {
                $qtyMax = $offer["CATALOG_CAN_BUY_ZERO"] === 'Y' ? 0 : $offer["CATALOG_QUANTITY"];
                if ($offer['PROPERTIES']['DOWNLOAD_LINK']['VALUE'] || $arElement['PROPERTIES']['DOWNLOAD_LINK']['VALUE']) {
                    $qtyMax = 1;
                }
                $ratio = 1;
                $ratio_settings = 'base'; // нужно чтобы для весовых товаров считался корректный коэффициент

                if ($ratio_settings == "own_prop" && strlen($bxr_ratio_prop_code) > 0
                    && isset($offer["PROPERTIES"][$bxr_ratio_prop_code]["VALUE"]) && is_numeric($offer["PROPERTIES"][$bxr_ratio_prop_code]["VALUE"]))
                    $ratio = $offer["PROPERTIES"][$bxr_ratio_prop_code]["VALUE"];
                elseif ($ratio_settings == "base")
                    $ratio = $offer["CATALOG_MEASURE_RATIO"];

                $quantity = ($ratio > $offer["CATALOG_QUANTITY"] && $offer["CATALOG_QUANTITY"] > 0) ? $offer["CATALOG_QUANTITY"] : $ratio;
                ?>
                <form class="bxr-basket-action bxr-basket-group bxr-currnet-torg">
                    <input type="button" class="bxr-quantity-button-minus" value="-" data-item="<?= $offer["ID"] ?>"
                           data-ratio="<?= $ratio; ?>">
                    <input type="text" name="quantity" value="<?= $quantity; ?>" class="bxr-quantity-text"
                           data-item="<?= $offer["ID"] ?>">
                    <input type="button" class="bxr-quantity-button-plus" value="+" data-item="<?= $offer["ID"] ?>"
                           data-ratio="<?= $ratio; ?>" data-max="<?= $qtyMax ?>">
                    <button class="bxr-color-button bxr-color-button-small-only-icon bxr-basket-add"
                            onclick="ga ('send', 'event', 'basket', 'addList');  yaCounter47420761.reachGoal('addList'); return true;">
                        <span class="fa fa-shopping-cart"></span>
                    </button>
                    <input class="bxr-basket-item-id" type="hidden" name="item" value="<?= $offer["ID"] ?>">
                    <input type="hidden" name="action" value="add">
                </form>
                <div class="clearfix"></div>
                <?} ?>
            <?php endif ?>
        </div>
    <? }
} else {
    if ($arElement["CATALOG_QUANTITY"] <= 0 && $arElement["CATALOG_CAN_BUY_ZERO"] == "N" || !$arElement["MIN_PRICE"]['VALUE']) {
        if ($showSubscribeBtn) { ?>
            <div class="bxr-subscribe-wrap">
                <? include_once 'subscribe_script.php';

                $APPLICATION->includeComponent('alexkova.market:catalog.product.subscribe', '',
                    array(
                        'PRODUCT_ID' => $arElement['ID'],
                        'BUTTON_ID' => 'bxr-ev1-' . $UID . '-' . $arElement['ID'] . '-subscribe',
                        'BUTTON_CLASS' => 'bxr-color-button bxr-subscribe',
                    ),
                    $component, array('HIDE_ICONS' => 'Y')
                ); ?>
            </div>
        <? } else { ?>
            <button class="bxr-color-button bxr-trade-request" value="<?= $arElement["ID"] ?>"
                    data-trade-id="<?= $arElement["ID"] ?>"
                    data-trade-name="<?= $arElement["NAME"] ?>"
                    data-trade-link="<?= $arElement["DETAIL_PAGE_URL"] ?>"
                    data-msg="<?= str_replace('#TRADE_NAME#', $arElement["NAME"], GetMessage('TRADE_REQUEST_MSG')) ?>">
                <?= GetMessage("REQUEST_BTN") ?>
            </button>
            <?
        }
    } else { ?>
        <?php if ($arElement['PROPERTIES']['DOWNLOAD_LINK']['VALUE'] && $arElement['HAS_ACCESS_TO_PRODUCT']): ?>
            <a href="<?= $arElement['PROPERTIES']['DOWNLOAD_LINK']['VALUE'] ?>" class="bxr-color-button"
               target="_blank">Скачать</a>
        <?php else: ?>

            <?php
            if (is_array($arElement["BASKET_PROPS"]["REQUIRED_CHECK"]) || is_array($arElement["BASKET_PROPS"]["OPTIONAL_CHECK"])) { ?>
                <a href="<?= $arElement["DETAIL_PAGE_URL"] ?>" class="bxr-color-button"
                   id="<?= $arItemIDs["BUY_LINK"] ?>">
                    <?= GetMessage("MORE_INFO_TITLE") ?>
                </a>
            <? } else { ?>
                <?
                $qtyMax = $arElement["CATALOG_CAN_BUY_ZERO"] === 'Y' ? 0 : $arElement["CATALOG_QUANTITY"];
                if ($arElement['PROPERTIES']['DOWNLOAD_LINK']['VALUE']) {
                    $qtyMax = 1;
                }
                $ratio = 1;
                $ratio_settings = 'base'; // нужно чтобы для весовых товаров считался корректный коэффициент

                if ($ratio_settings == "own_prop" && strlen($bxr_ratio_prop_code) > 0
                    && isset($arElement["PROPERTIES"][$bxr_ratio_prop_code]["VALUE"]) && is_numeric($arElement["PROPERTIES"][$bxr_ratio_prop_code]["VALUE"]))
                    $ratio = $arElement["PROPERTIES"][$bxr_ratio_prop_code]["VALUE"];
                elseif ($ratio_settings == "base")
                    $ratio = $arElement["CATALOG_MEASURE_RATIO"];

                $quantity = ($ratio > $arElement["CATALOG_QUANTITY"] && $arElement["CATALOG_QUANTITY"] > 0) ? $arElement["CATALOG_QUANTITY"] : $ratio;
                ?>
                <form class="bxr-basket-action bxr-basket-group bxr-currnet-torg">
                    <input type="button" class="bxr-quantity-button-minus" value="-" data-item="<?= $arElement["ID"] ?>"
                           data-ratio="<?= $ratio; ?>">
                    <input type="text" name="quantity" value="<?= $quantity; ?>" class="bxr-quantity-text"
                           data-item="<?= $arElement["ID"] ?>">
                    <input type="button" class="bxr-quantity-button-plus" value="+" data-item="<?= $arElement["ID"] ?>"
                           data-ratio="<?= $ratio; ?>" data-max="<?= $qtyMax ?>">
                    <button class="bxr-color-button bxr-color-button-small-only-icon bxr-basket-add"
                            onclick="ga ('send', 'event', 'basket', 'addList');  yaCounter47420761.reachGoal('addList'); return true;">
                        <span class="fa fa-shopping-cart"></span>
                    </button>
                    <input class="bxr-basket-item-id" type="hidden" name="item" value="<?= $arElement["ID"] ?>">
                    <input type="hidden" name="action" value="add">
                </form>
                <div class="clearfix"></div>
            <? } ?>
        <?php endif ?>
    <?php }
}
