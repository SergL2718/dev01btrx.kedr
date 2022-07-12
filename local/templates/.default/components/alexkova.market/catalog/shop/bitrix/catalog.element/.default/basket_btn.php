<!--basket-btns block-->
<?php
if (count($arResult["OFFERS"]) > 0) {
    foreach ($arResult["OFFERS"] as $offer): ?>
        <div class="offers-btn-wrap" style="display: none" data-item="<?= $offer["ID"] ?>">
            <?php if ($offer['HAS_ACCESS_TO_PRODUCT']): ?>
                <div style="margin: 3px 0 10px">
                    <a href="<?= $arResult['PROPERTIES']['DOWNLOAD_LINK']['VALUE'] ?>"
                       class="bxr-color-button bxr-basket-add"
                       style="display: block;max-width: 170px;text-decoration: none;font-weight: bold;text-align: center;text-transform: uppercase;"
                       target="_blank">Скачать</a>
                </div>
            <?php else: ?>
                <?php if ($offer["CATALOG_QUANTITY"] <= 0 && $offer["CATALOG_CAN_BUY_ZERO"] == "N"): ?>
                    <button class="bxr-color-button bxr-trade-request" value="<?= $offer["ID"] ?>">
                        <?= GetMessage("REQUEST_BTN") ?>
                    </button>
                <?php else: ?>

                    <?php
                    $qtyMax = $offer["CATALOG_CAN_BUY_ZERO"] === 'Y' ? 0 : $offer["CATALOG_QUANTITY"];
                    if ($offer['PROPERTIES']['DOWNLOAD_LINK']['VALUE'] || $arResult['PROPERTIES']['DOWNLOAD_LINK']['VALUE']) {
                        $qtyMax = 1;
                    }
                    ?>
                    <form class="bxr-basket-action bxr-basket-group bxr-currnet-torg" action="">
                        <input type="button" class="bxr-quantity-button-minus" value="-"
                               data-item="<?= $offer["ID"] ?>">
                        <input type="text" name="quantity" value="<?= 1 * $offer['CATALOG_MEASURE_RATIO']; ?>"
                               class="bxr-quantity-text" data-item="<?= $offer["ID"] ?>">
                        <input type="button" class="bxr-quantity-button-plus" value="+" data-item="<?= $offer["ID"] ?>"
                               data-max="<?= $qtyMax ?>">
                        <button class="bxr-color-button bxr-basket-add"
                                onclick="ga ('send', 'event', 'basket', 'addCard '); yaCounter47420761.reachGoal('addCard'); return true;">
                            <!--<span class="fa fa-shopping-cart"></span>-->
                            <?= GetMessage("TO_BASKET") ?>
                        </button>
                        <input class="bxr-basket-item-id" type="hidden" name="item" value="<?= $offer["ID"] ?>">
                        <input type="hidden" name="action" value="add">
                    </form>
                    <!--one click buy block-->
                    <div class="bxr-basket-action">
                        <button class="bxr-color-button bxr-one-click-buy" data-item="<?= $offer["ID"] ?>"
                                onclick=" ga ('send', 'event', 'buy', 'oneClick '); yaCounter47420761.reachGoal('oneClick'); return true;">
                            <?= GetMessage("ONE_CLICK_BUY") ?>
                        </button>
                    </div>
                    <div class="clearfix"></div>
                <?php endif ?>
            <?php endif ?>
        </div>
    <?php endforeach ?>
    <div class="bxr-detail-torg-btn">
        <!--share block-->
        <div class="bxr-share-group">
            <span class="fa fa-share-alt hidden-md"></span>
            <?= GetMessage("SHARE") ?>
        </div>

        <!--compare block-->
        <?php
        if ($useCompare) {
            ?>
            <div class="bxr-basket-group">
                <button class="bxr-indicator-item white bxr-indicator-item-compare bxr-compare-button" value=""
                        data-item="<?= $arResult["ID"] ?>">
                    <span class="fa fa-bar-chart hidden-md" aria-hidden="true"></span>
                    <?= GetMessage("COMPARE") ?>
                </button>
            </div>
        <?php } ?>

        <!--favor block-->
        <form class="bxr-basket-action bxr-basket-group" action="">
            <button class="bxr-indicator-item white bxr-indicator-item-favor bxr-basket-favor"
                    data-item="<?= $arResult["ID"] ?>" tabindex="0">
                <span class="fa fa-heart-o hidden-md" style="color: #8fbb3c !important;"></span>
                <?= GetMessage("FAVORITES") ?>
            </button>
            <input type="hidden" name="item" value="<?= $arResult["ID"] ?>" tabindex="0">
            <input type="hidden" name="action" value="favor" tabindex="0">
            <input type="hidden" name="favor" value="yes">
        </form>
        <div class="clearfix"></div>
    </div>
<?php } else { ?>

    <?php if ($arResult['HAS_ACCESS_TO_PRODUCT']): ?>
        <div style="margin: 3px 0 10px">
            <a href="<?= $arResult['PROPERTIES']['DOWNLOAD_LINK']['VALUE'] ?>"
               class="bxr-color-button bxr-basket-add"
               style="display: block;max-width: 170px;text-decoration: none;font-weight: bold;text-align: center;text-transform: uppercase;"
               target="_blank">Скачать</a>
        </div>
    <?php else: ?>
        <script>
            trade_name = "<?=$arResult['NAME']?>";
            trade_id = "<?=$arResult['ID']?>";
            trade_link = "<?=$arResult['DETAIL_PAGE_URL']?>";
            formRequestMsg = "<?=GetMessage('TRADE_REQUEST_MSG')?>";
            formRequestMsg = formRequestMsg.replace("#TRADE_NAME#", '<?=$arResult['NAME']?>');
        </script>
        <?php if ($arResult["CATALOG_QUANTITY"] <= 0 && $arResult["CATALOG_CAN_BUY_ZERO"] == "N") { ?>
            <button class="bxr-color-button bxr-trade-request" value="<?= $arResult["ID"] ?>">
                <?= GetMessage("REQUEST_BTN") ?>
            </button>
        <?php } else { ?>


            <?php
            $qtyMax = $arResult["CATALOG_CAN_BUY_ZERO"] === 'Y' ? 0 : $arResult["CATALOG_QUANTITY"];
            if ($arResult['PROPERTIES']['DOWNLOAD_LINK']['VALUE']) {
                $qtyMax = 1;
            }
            ?>
            <form class="bxr-basket-action bxr-basket-group bxr-currnet-torg" action="">
                <input type="button" class="bxr-quantity-button-minus" value="-" data-item="<?= $arResult["ID"] ?>"
                       data-ratio="<?= $arResult['CATALOG_MEASURE_RATIO'] ?>">
                <input type="text" name="quantity" value="<?= 1 * $arResult['CATALOG_MEASURE_RATIO']; ?>"
                       class="bxr-quantity-text" data-item="<?= $arResult["ID"] ?>">
                <input type="button" class="bxr-quantity-button-plus" value="+" data-item="<?= $arResult["ID"] ?>"
                       data-ratio="<?= $arResult['CATALOG_MEASURE_RATIO'] ?>" data-max="<?= $qtyMax ?>">
                <button class="bxr-color-button bxr-basket-add"
                        onclick="ga ('send', 'event', 'basket', 'addCard '); yaCounter47420761.reachGoal('addCard'); return true;">
                    <!--<span class="fa fa-shopping-cart"></span>-->
                    <?= GetMessage("TO_BASKET") ?>
                </button>
                <input class="bxr-basket-item-id" type="hidden" name="item" value="<?= $arResult["ID"] ?>">
                <input type="hidden" name="action" value="add">
            </form>

            <!--one click buy block-->
            <div class="bxr-basket-action">
                <div class="bxr-color-button btn-one-click-buy"
                     onclick="ga ('send', 'event', 'buy', 'oneClick ');yaCounter47420761.reachGoal('oneClick');return true;">
                    <?= GetMessage("ONE_CLICK_BUY") ?>
                </div>

                <?php $productName = str_replace(['&quot;', '"'], '"', $arResult['NAME']) ?>

                <script id="bx24_form_link" data-skip-moving="true">
                    (function (w, d, u, b) {
                        w['Bitrix24FormObject'] = b;
                        w[b] = w[b] || function () {
                            arguments[0].ref = u;
                            (w[b].forms = w[b].forms || []).push(arguments[0])
                        };
                        if (w[b]['forms']) return;
                        var s = d.createElement('script');
                        s.async = 1;
                        s.src = u + '?' + (1 * new Date());
                        var h = d.getElementsByTagName('script')[0];
                        h.parentNode.insertBefore(s, h);
                    })(window, document, 'https://megre.bitrix24.ru/bitrix/js/crm/form_loader.js', 'b24form');
                </script>

                <?php if ($arResult['PROPERTIES']['CHANNEL_SALE']['VALUE_XML_ID'] === \Native\App\Catalog\Product::TYPE_RETAIL): ?>

                    <script data-skip-moving="true">
                        b24form({
                            'id': '25',
                            'lang': 'ru',
                            'sec': '1ei7jf',
                            'type': 'link',
                            'click': document.querySelector('div.bxr-color-button.btn-one-click-buy'),
                            'fields': {
                                'values': {
                                    'LEAD_NAME': '<?=$USER->GetFullName()?>',
                                    'LEAD_EMAIL': '<?=$USER->GetEmail()?>',
                                    'LEAD_COMMENTS': 'Я хочу приобрести товар: <?=$productName?>',
                                }
                            }
                        });
                    </script>

                <?php else: ?>

                    <script data-skip-moving="true">
                        b24form({
                            'id': '7',
                            'lang': 'ru',
                            'sec': 'jwa5kd',
                            'type': 'link',
                            'click': document.querySelector('div.bxr-color-button.btn-one-click-buy'),
                            'fields': {
                                'values': {
                                    'LEAD_NAME': '<?=$USER->GetFullName()?>',
                                    'LEAD_EMAIL': '<?=$USER->GetEmail()?>',
                                    'LEAD_COMMENTS': 'Я хочу приобрести товар: <?=$productName?>',
                                }
                            }
                        });
                    </script>

                <?php endif ?>
            </div>

            <div class="clearfix"></div>


            <?php if ($arResult['ITEM_MEASURE']['TITLE'] === 'г' || $arResult['ITEM_MEASURE']['TITLE'] === 'кг'): ?>
                <div class="only-after-processed-manager-notification">
                    Вес и конечная стоимость товара будут зафиксированы только после обработки заказа менеджером.
                </div>
                <div class="clearfix"></div>
            <?php endif ?>


        <?php } ?>
    <?php endif ?>
    <div class="bxr-detail-torg-btn">
        <!--share block-->
        <div class="bxr-share-group">
            <span class="fa fa-share-alt hidden-md"></span>
            <?= GetMessage("SHARE") ?>
        </div>

        <!--compare block-->
        <?php
        if ($useCompare) {
            ?>
            <div class="bxr-basket-group">
                <button class="bxr-indicator-item white bxr-indicator-item-compare bxr-compare-button" value=""
                        data-item="<?= $arResult["ID"] ?>">
                    <span class="fa fa-bar-chart hidden-md" aria-hidden="true"></span>
                    <?= GetMessage("COMPARE") ?>
                </button>
            </div>
        <?php } ?>
        <!--favor block-->
        <form class="bxr-basket-action bxr-basket-group" action="">
            <button class="bxr-indicator-item white bxr-indicator-item-favor bxr-basket-favor"
                    data-item="<?= $arResult["ID"] ?>" tabindex="0">
                <span class="fa fa-heart-o hidden-md" style="color: #8fbb3c !important;"></span>
                <?= GetMessage("FAVORITES") ?>
            </button>
            <input type="hidden" name="item" value="<?= $arResult["ID"] ?>" tabindex="0">
            <input type="hidden" name="action" value="favor" tabindex="0">
            <input type="hidden" name="favor" value="yes">
        </form>
        <div class="clearfix"></div>
    </div>
<?php } ?>

<div class="bxr-share-icon-wrap">
    <?php $APPLICATION->IncludeComponent(
        "bitrix:main.share",
        "element_detail",
        array(
            "COMPONENT_TEMPLATE" => ".default",
            "HANDLERS" => $arParams["HANDLERS"],
            "HIDE" => "N",
            "PAGE_TITLE" => $arResult["NAME"],
            "PAGE_URL" => $arResult["DETAIL_PAGE_URL"],
            "SHORTEN_URL_KEY" => "",
            "SHORTEN_URL_LOGIN" => ""
        ),
        false,
        array("HIDE_ICONS" => "Y")
    ); ?>
</div>
