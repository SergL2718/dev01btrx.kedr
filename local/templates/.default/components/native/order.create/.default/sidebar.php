<?php
/*
 * Изменено: 29 декабря 2021, среда
 * Автор: Артамонов Денис <artamonov.d.i@yandex.com>
 * copyright (c) 2021
 */

/**
 * @var $APPLICATION
 * @var $USER
 * @var $arResult
 */

use Native\App\Sale\Location;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die;
?>

<div class="order-sidebar">

    <div class="order-sidebar-content">
        <div class="order-sidebar-header">
            <div>Всего</div>
            <div class="order-sidebar-basket-total-price"><?= $arResult['ORDER']['TOTAL']['AMOUNT']['WITHOUT_CURRENCY'] ?>
                <span>руб.</span></div>
        </div>

        <div class="order-sidebar-delivery mt-4">
            <div class="order-sidebar-delivery-label">Стоимость доставки</div>
            <div class="order-sidebar-delivery-price">-</div>
        </div>

        <div class="order-sidebar-total mt-4"
             <?php if ($arResult['STEP']['current'] === 'total'): ?>data-visibility="N"<?php endif ?>>
            <div>Итого</div>
            <div class="order-sidebar-total-products">
                <?php $showLinkList = false ?>
                <?php $maxCountList = 2 ?>
                <?php $counter = 0 ?>
                <?php $isListBig = count($arResult['ORDER']['BASKET']) > $maxCountList ?>

                <?php if ($isListBig === false): ?>
                    <?php foreach ($arResult['ORDER']['BASKET'] as $product): ?>
                        <div class="order-sidebar-product">
                            <a href="<?= $product['GIFT'] === 'Y' ? 'javascript:void(0)' : $product['DETAIL_PAGE_URL'] ?>">
                                <div class="order-sidebar-product-image">
                                    <img src="<?= $product['DETAIL_PICTURE_SRC'] ?>" alt="">
                                </div>
                                <div class="order-sidebar-product-title"><?= $product['NAME'] ?></div>

                                <div class="order-sidebar-product-price">
                                    <?php if ($product['GIFT'] !== 'Y') echo $product['AMOUNT']['WITHOUT_CURRENCY'] ?>
                                </div>
                            </a>
                        </div>
                    <?php endforeach ?>
                <?php else: ?>

                    <?php foreach ($arResult['ORDER']['BASKET'] as $index => $product): ?>

                        <?php if ($counter < $maxCountList): ?>
                            <div class="order-sidebar-product">
                                <a href="<?= $product['GIFT'] === 'Y' ? 'javascript:void(0)' : $product['DETAIL_PAGE_URL'] ?>">
                                    <div class="order-sidebar-product-image">
                                        <img src="<?= $product['DETAIL_PICTURE_SRC'] ?>" alt="">
                                    </div>
                                    <div class="order-sidebar-product-title"><?= $product['NAME'] ?></div>

                                    <div class="order-sidebar-product-price">
                                        <?php if ($product['GIFT'] !== 'Y') echo $product['AMOUNT']['WITHOUT_CURRENCY'] ?>
                                    </div>
                                </a>
                            </div>
                            <?php unset($arResult['ORDER']['BASKET'][$index]) ?>
                        <?php endif ?>
                        <?php $counter++ ?>
                    <?php endforeach ?>

                    <a href="javascript:void(0)" class="order-sidebar-total-products-change-visibility"
                       data-controller="changeVisibilityFullProductList">Развернуть корзину ..</a>

                    <div class="order-sidebar-total-products-full-list" data-visibility="N">
                        <?php foreach ($arResult['ORDER']['BASKET'] as $index => $product): ?>
                            <div class="order-sidebar-product">
                                <a href="<?= $product['GIFT'] === 'Y' ? 'javascript:void(0)' : $product['DETAIL_PAGE_URL'] ?>">
                                    <div class="order-sidebar-product-image">
                                        <img src="<?= $product['DETAIL_PICTURE_SRC'] ?>" alt="">
                                    </div>
                                    <div class="order-sidebar-product-title"><?= $product['NAME'] ?></div>

                                    <div class="order-sidebar-product-price">
                                        <?php if ($product['GIFT'] !== 'Y') echo $product['AMOUNT']['WITHOUT_CURRENCY'] ?>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach ?>
                    </div>

                <?php endif ?>
            </div>
        </div>

        <?php if ($arResult['ORDER']['COUPON']): ?>
            <div class="order-sidebar-footer mt-4">
                <div>Применен промокод: <b><?= $arResult['ORDER']['COUPON'] ?></b></div>
            </div>
        <?php endif ?>
    </div>

    <div class="order-change-step mt-5">
        <div>
            <a href="javascript:void(0)" data-controller="nextStep"
               data-visibility="<?= $arResult['STEP']['current'] === 'total' ? 'N' : 'Y' ?>">Далее<i
                        class="fas fa-chevron-right"></i></a>
            <a href="javascript:void(0)" data-controller="order"
               data-visibility="<?= $arResult['STEP']['current'] === 'total' ? 'Y' : 'N' ?>">Оформить<i
                        class="fas fa-chevron-right"></i></a>
        </div>

        <div class="mt-3">
            <a href="javascript:void(0)" data-controller="goToCatalog"
               data-visibility="<?= $arResult['STEP']['current'] === 'customer' ? 'Y' : 'N' ?>"><i
                        class="fas fa-chevron-left"></i> Продолжить покупки</a>
            <a href="javascript:void(0)" data-controller="previousStep"
               data-visibility="<?= $arResult['STEP']['current'] === 'customer' ? 'N' : 'Y' ?>"><i
                        class="fas fa-chevron-left"></i> Назад</a>
        </div>

        <?php if (time() < strtotime('10.01.2022 00:00:00')): ?>
            <div id="notification"
                 <?php if ($arResult['STEP']['current'] !== 'delivery' && $arResult['STEP']['current'] !== 'total'): ?>style="display: none"<?php endif ?>>
                <div class="text-center p-3 mt-3"
                     style="background-color: #ffec6e;font-size: 13px;color: #000; border-radius: 4px;">
                    Заказы, оформленные с 30 декабря по 9 января, будут отгружены 10 января. С Новым годом ♥️
                </div>
            </div>
        <?php endif ?>
    </div>
</div>
