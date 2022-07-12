<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? if (count($arResult["BASKET_ITEMS"]["CAN_BUY"]) > 0) { ?>

    <div class="basket-body-title">
        <div class="pull-right">
				<span><?= GetMessage('BASKET_PRODUCTS') ?>:
				<span class="gray-line"><b><?= count($arResult["BASKET_ITEMS"]["CAN_BUY"]) ?></b></span>
                    <?= GetMessage('BASKET_SUM') ?>
                    <span class="gray-line"><b><span class="bxr-format-price"><?= $arResult["FORMAT_SUMM"] ?></span></b></span>
					<a href="<?= $arParams["PATH_TO_BASKET"] ?>" class="bxr-color-button"
                       <? if ($arResult["SUMM"] < $arResult["MIN_ORDER_PRICE"]) { ?>style="display: none;"<? } ?>>
						<span class="fa fa-check-square-o" aria-hidden="true"></span>
                        <?= GetMessage('BASKET_TO_ORDER') ?></a>
                                    <div class="clearfix"></div>
                                </span>
        </div>
        <div class="clearfix"></div>
    </div>

    <table width="100%">
        <tr>
            <th colspan="2" class="first"><?= GetMessage('BASKET_TD_NAME') ?></th>
            <th style="text-align: center"><?= GetMessage('BASKET_TD_PRICE') ?></th>
            <th style="text-align: center"><?= GetMessage('BASKET_TD_QTY') ?></th>
            <th style="text-align: right"><?= GetMessage('BASKET_TD_SUM') ?></th>
            <th class="last">&nbsp;</th>
        </tr>
        <? foreach ($arResult["BASKET_ITEMS"]["CAN_BUY"] as $arBasketItem):
            $img = $arBasketItem["PICTURE"];
            $img = (strlen($img) > 0)
                ? '<a href="' . $arBasketItem["URL"] . '"
						style="background: url(' . $img . ') no-repeat center center;
						background-size: contain;
						" title="' . $arBasketItem["NAME"] . '" alt="' . $arBasketItem["NAME"] . '"></a>'
                : "&nbsp;";
            ?>
            <tr data-item-id="<?= $arBasketItem['ID'] ?>">
                <td class="basket-image first">
                    <?= $img ?>
                </td>
                <td class="basket-name xs-hide">
                    <a href="<?= $arBasketItem["URL"] ?>" class="bxr-font-hover-light"><?= $arBasketItem["NAME"] ?></a>
                    <? foreach ($arBasketItem["PROPS"] as $prop) { ?>
                        <div class="bxr-bsmall-prop"><?= $prop["NAME"] ?>: <?= $prop["VALUE"] ?></div>
                    <? } ?>
                </td>
                <td class="basket-price bxr-format-price">
                    <? if ($arBasketItem['PRICE'] != 0): ?>
                        <?= $arBasketItem["FORMAT_PRICE"] ?>
                    <? endif ?>
                </td>
                <td class="basket-line-qty xs-hide sm-hide">
                    <div class="bxr-basket-group">
                        <input type="text" value="<?= $arBasketItem["QUANTITY"] ?>" class="bxr-quantity-text"
                               name="quantity" data-item="" style="float: none; margin: 0 auto">
                    </div>

                </td>
                <td class="basket-summ bxr-format-price">
        <? if ($arBasketItem['PRICE'] != 0): ?>
                    <?= $arBasketItem["FORMAT_SUMM"] ?>
        <? endif ?>
        </td>

                <? /*<td class="basket-action last">
                    <button id="button-delay-<?= $arBasketItem["ID"] ?>" class="icon-button-delay" value=""
                            data-item="<?= $arBasketItem["ID"] ?>" title="<?= GetMessage("SALE_DELAY") ?>">
                        <span class="fa fa-bookmark-o" aria-hidden="true"></span>
                    </button>
                    <button id="button-delay-<?= $arBasketItem["ID"] ?>" class="icon-button-delete" value=""
                            data-item="<?= $arBasketItem["ID"] ?>"
                            data-product-id="<?= $arBasketItem["PRODUCT_ID"] ?>"
                            data-product-name="<?= $arBasketItem["NAME"] ?>"
                            data-product-base-price="<?= $arBasketItem["BASE_PRICE"] ?>"
                            title="<?= GetMessage("SALE_DELETE") ?>">
                        <span class="fa fa-close" aria-hidden="true"></span>
                    </button>

                </td>*/
                ?>
            </tr>
        <? endforeach; ?>
    </table>

    <div class="basket-body-title">
        <div class="pull-right">
            <button class="btn btn-default bxr-close-basket bxr-corns">
                <span class="fa fa-power-off" aria-hidden="true"></span>
                <?= GetMessage('BASKET_CLOSE') ?></button>
        </div>
    </div>

<? } else { ?>
    <p class="bxr-helper bg-info">
        <?= GetMessage('BASKET_DROP_EMPTY') ?>
    </p>
<? } ?>
<div class="icon-close"></div>
