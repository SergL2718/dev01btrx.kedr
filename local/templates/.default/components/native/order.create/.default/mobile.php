<?php
/*
 * @updated 15.02.2021, 19:37
 * @author Артамонов Денис <bitrix.developer@inbox.ru>
 * @copyright Copyright (c) 2021, Компания Webco <hello@wbc.cx>
 * @link https://wbc.cx
 */

/**
 * @var $arResult
 */
?>

<div class="order-mobile-panel-content">

    <? // Свернутая панель ?>

    <div class="order-mobile-panel-collapsed" data-visibility="N">
        <div class="order-mobile-panel-collapsed-header">
            Итого с доставкой <span
                    class="order-mobile-panel-collapsed-total-price-value"><?= $arResult['ORDER']['TOTAL']['AMOUNT']['WITHOUT_CURRENCY'] ?></span>&nbsp;<span>руб.</span>
        </div>
        <div class="order-mobile-panel-collapsed-footer">
            <a href="javascript:void(0)" data-controller="expandMobilePanel">Смотреть корзину</a>
        </div>
    </div>

    <? // Развернутая панель ?>

    <div class="order-mobile-panel-expanded" data-visibility="Y">
        <div class="order-mobile-panel-expanded-header">
            <div>Всего</div>
            <div class="order-sidebar-basket-total-price"><span
                        class="order-mobile-panel-expanded-total-price-value"><?= $arResult['ORDER']['TOTAL']['AMOUNT']['WITHOUT_CURRENCY'] ?></span>&nbsp;<span>руб.</span>
            </div>
        </div>

        <div class="order-mobile-panel-expanded-delivery mt-4">
            <div class="order-mobile-panel-expanded-delivery-label">Стоимость доставки</div>
            <div class="order-mobile-panel-expanded-delivery-price">-</div>
        </div>

        <div class="order-mobile-panel-expanded-total mt-4">
            <div>Итого</div>
            <div class="order-mobile-panel-expanded-total-products">
                <?php foreach ($arResult['ORDER']['BASKET'] as $product): ?>
                    <div class="order-mobile-panel-expanded-product">
                        <a href="<?= $product['GIFT'] === 'Y' ? 'javascript:void(0)' : $product['DETAIL_PAGE_URL'] ?>">
                            <div class="order-mobile-panel-expanded-product-image">
                                <img src="<?= $product['DETAIL_PICTURE_SRC'] ?>" alt="">
                            </div>
                            <div class="order-mobile-panel-expanded-product-title"><?= $product['NAME'] ?></div>

                            <div class="order-mobile-panel-expanded-product-price">
                                <? if ($product['GIFT'] !== 'Y') echo $product['AMOUNT']['WITHOUT_CURRENCY'] ?>
                            </div>
                        </a>
                    </div>
                <?php endforeach ?>
            </div>
        </div>

        <div class="order-mobile-panel-expanded-footer mt-4">
            <? if ($arResult['ORDER']['COUPON']): ?>
                <div class="order-mobile-panel-expanded-footer-coupon">Применен промокод:
                    <b><?= $arResult['ORDER']['COUPON'] ?></b></div>
            <? endif ?>
            <div class="mt-4">
                <a href="javascript:void(0)" data-controller="collapseMobilePanel">Свернуть корзину</a>
            </div>
        </div>
    </div>

</div>

<div class="order-mobile-panel-change-step">
    <div>
        <a href="javascript:void(0)" data-controller="goToCatalog"
           data-visibility="<?= $arResult['STEP']['current'] === 'customer' ? 'Y' : 'N' ?>"><i
                    class="fas fa-chevron-left"></i>Магазин</a>
        <a href="javascript:void(0)" data-controller="previousStep"
           data-visibility="<?= $arResult['STEP']['current'] === 'customer' ? 'N' : 'Y' ?>"><i
                    class="fas fa-chevron-left"></i>Назад</a>
    </div>
    <div>
        <a href="javascript:void(0)" data-controller="nextStep"
           data-visibility="<?= $arResult['STEP']['current'] === 'total' ? 'N' : 'Y' ?>">Далее<i
                    class="fas fa-chevron-right"></i></a>
        <a href="javascript:void(0)" data-controller="order"
           data-visibility="<?= $arResult['STEP']['current'] === 'total' ? 'Y' : 'N' ?>">Оформить<i
                    class="fas fa-chevron-right"></i></a>
    </div>
</div>
