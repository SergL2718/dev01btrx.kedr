<?php
/*
 * @updated 09.12.2020, 18:38
 * @author Артамонов Денис <bitrix.developer@inbox.ru>
 * @copyright Copyright (c) 2020, Компания Webco <hello@wbc.cx>
 * @link http://wbc.cx
 */

/** @var array $arParams */
/** @var array $arResult */

/** @var string $templateFolder */

use Bitrix\Main\Localization\Loc;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die;

$products =& $arResult['GRID']['ROWS'];
$google_tag_products = [];
?>

<div class="basket-block">
    <div class="basket-rows">
        <div class="header mb-3">
            <div class="column">Товар</div>
            <div class="column">Цена</div>
            <div class="column">Количество</div>
            <div class="column">Сумма</div>
            <div class="column">&nbsp;</div>
        </div>
        <div class="products desktop">
            <?php foreach ($products as $product): ?>
                <?php $google_tag_products[] = $product['PRODUCT_ID'] ?>
                <div class="product<?= $product['GIFT'] === 'Y' ? ' gift' : '' ?>" data-row="<?= $product['ID'] ?>">
                    <div class="column">
                        <div class="product-name">
                            <div class="product-image mr-4"
                                 style="background-image: url(<?= $product['DETAIL_PICTURE_SRC'] ? $product['DETAIL_PICTURE_SRC'] : $templateFolder . '/images/no-photo.png' ?>)">
                                <a href="<?= $product['GIFT'] !== 'Y' ? $product['DETAIL_PAGE_URL'] : 'javascript:void(0)' ?>"
                                   title="<?= $product['NAME'] ?>">
                                </a>
                            </div>
                            <div class="product-title">
                                <div>
                                    <a href="<?= $product['GIFT'] !== 'Y' ? $product['DETAIL_PAGE_URL'] : 'javascript:void(0)' ?>"
                                       title="<?= $product['NAME'] ?>">
                                        <?= $product['NAME'] ?>
                                    </a>
                                </div>
                                <?php if ($product['PROPERTIES']['CHANNEL_SALE']['VALUE_XML_ID'] === \Native\App\Catalog\Product::TYPE_RETAIL): ?>
                                    <div class="only-online-store-notification">
                                        Доступно только<br>
                                        для Новосибирска
                                    </div>
                                <?php endif ?>
                            </div>
                        </div>
                    </div>
                    <div class="column">
                        <?php if ($product['GIFT'] !== 'Y'): ?>
                            <div class="product-price"
                                 data-percent="<?= $product['PRICE']['DISCOUNT']['PERCENT']['VALUE'] ?>">
                                <div class="product-current-price"><?= $product['PRICE']['WITHOUT_CURRENCY'] ?></div>
                                <div class="product-old-price"><?= $product['PRICE']['BASE']['WITHOUT_CURRENCY'] ?></div>
                                <div class="product-discount">
                                    Скидка <span
                                            class="product-discount-percent"><?= $product['PRICE']['DISCOUNT']['PERCENT']['FORMATTED'] ?></span>
                                </div>
                            </div>
                        <?php endif ?>
                    </div>
                    <div class="column">
                        <?php if ($product['GIFT'] !== 'Y'): ?>
                            <div class="product-quantity">
                                <a href="javascript:void(0);" class="product-quantity-change"
                                   data-controller="updateQuantity">-</a>
                                <input
                                        type="text"
                                        size="3"
                                        maxlength="18"
                                        min="0"
                                        value="<?= $product['QUANTITY'] ?>"
                                        class="product-quantity-value"
                                        onchange="BasketComponent.controller.updateQuantity(this)"
                                >
                                <a href="javascript:void(0);" class="product-quantity-change"
                                   data-controller="updateQuantity">+</a>
                            </div>
                        <?php endif ?>
                    </div>
                    <div class="column">
                        <?php if ($product['GIFT'] !== 'Y'): ?>
                            <div class="product-amount">
                                <?= $product['AMOUNT']['WITHOUT_CURRENCY'] ?>
                            </div>
                        <?php endif ?>
                    </div>
                    <div class="column">
                        <div class="product-delete">
                            <a href="javascript:void(0);" data-controller="deleteRow">
                                <img src="<?= $templateFolder ?>/images/delete.png" alt="">
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
        </div>


        <div class="products mobile">
            <?php foreach ($products as $product): ?>
                <div class="product<?= $product['GIFT'] === 'Y' ? ' gift' : '' ?>" data-row="<?= $product['ID'] ?>">

                    <div class="product-image mr-4"
                         style="background-image: url(<?= $product['DETAIL_PICTURE_SRC'] ? $product['DETAIL_PICTURE_SRC'] : $templateFolder . '/images/no-photo.png' ?>)">
                        <a href="<?= $product['GIFT'] !== 'Y' ? $product['DETAIL_PAGE_URL'] : 'javascript:void(0)' ?>"
                           title="<?= $product['NAME'] ?>">
                        </a>
                    </div>

                    <div class="product-data">
                        <div class="product-title">
                            <div>
                                <a href="<?= $product['GIFT'] !== 'Y' ? $product['DETAIL_PAGE_URL'] : 'javascript:void(0)' ?>"
                                   title="<?= $product['NAME'] ?>">
                                    <?= $product['NAME'] ?>
                                </a>
                            </div>
                            <?php if ($product['PROPERTIES']['CHANNEL_SALE']['VALUE_XML_ID'] === \Native\App\Catalog\Product::TYPE_RETAIL): ?>
                                <div class="only-online-store-notification">
                                    Доступно только<br>
                                    для Новосибирска
                                </div>
                            <?php endif ?>
                        </div>

                        <?php if ($product['GIFT'] !== 'Y'): ?>
                            <div class="product-price"
                                 data-percent="<?= $product['PRICE']['DISCOUNT']['PERCENT']['VALUE'] ?>">
                                <span class="product-current-price"><?= $product['PRICE']['WITHOUT_CURRENCY'] ?></span>
                                <span class="product-old-price mx-4"><?= $product['PRICE']['BASE']['WITHOUT_CURRENCY'] ?></span>
                                <span class="product-discount">
                                        Скидка <span
                                            class="product-discount-percent"><?= $product['PRICE']['DISCOUNT']['PERCENT']['FORMATTED'] ?></span>
                                    </span>
                            </div>
                            <div class="product-quantity">
                                <a href="javascript:void(0);" class="product-quantity-change"
                                   data-controller="updateQuantity">-</a>
                                <input
                                        type="text"
                                        size="3"
                                        maxlength="18"
                                        min="0"
                                        value="<?= $product['QUANTITY'] ?>"
                                        class="product-quantity-value"
                                        onchange="BasketComponent.controller.updateQuantity(this)"
                                >
                                <a href="javascript:void(0);" class="product-quantity-change"
                                   data-controller="updateQuantity">+</a>
                            </div>
                            <div class="product-amount">
                                <?= $product['AMOUNT']['WITHOUT_CURRENCY'] ?>
                            </div>
                        <?php endif ?>

                        <div class="product-delete">
                            <a href="javascript:void(0);" data-controller="deleteRow">
                                Удалить товар
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    </div>
</div>

<script>
    let google_tag_params = {
        ecomm_prodid: [<?= implode(', ', $google_tag_products) ?>],
        ecomm_pagetype: 'cart',
        ecomm_totalvalue: <?= $arResult['AMOUNT']['VALUE'] ?>
    };
</script>
