<?php
/*
 * @updated 09.12.2020, 18:38
 * @author Артамонов Денис <bitrix.developer@inbox.ru>
 * @copyright Copyright (c) 2020, Компания Webco <hello@wbc.cx>
 * @link http://wbc.cx
 */

/**
 * @var $USER
 */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die;
?>

<div class="basket-sidebar">
    <div class="basket-block">
        <div class="basket-sidebar-title">
            ИТОГО без учета доставки
        </div>
        <div class="basket-sidebar-amount">
            <?= $arResult['AMOUNT']['WITH_CURRENCY'] ?>
        </div>
        <?php/* if ($arResult['AMOUNT']['VALUE'] !== $arResult['AMOUNT']['BASE']['VALUE']): ?>
            <div class="basket-sidebar-base-amount">
                Итого без скидки: <?= $arResult['AMOUNT']['BASE']['WITH_CURRENCY'] ?>
            </div>
        <?php endif */?>
        <div class="basket-coupon">
            <div class="basket-coupon-message">
                <?php if (isset($arResult['COUPON']['COUPON'])): ?>
                    <?php if ($arResult['COUPON']['STATUS'] === 'SUCCESS'): ?>
                        Промокод <?= $arResult['COUPON']['COUPON'] ?> успешно применен!
                    <?php else: ?>
                        Данный промокод не найден!
                    <?php endif ?>
                <?php endif ?>
            </div>
            <div class="basket-coupon-field">
                <label for="coupon">Промокод при наличии</label>
                <input type="text" name="coupon" id="coupon" value="" maxlength="30" autocomplete="off">
            </div>
            <div class="text-right">
                <a href="javascript:void(0)" class="basket-coupon-button" data-controller="coupon">Применить</a>
            </div>
        </div>
    </div>
    <div class="basket-sidebar-button">
        <?php if ($USER->IsAuthorized()): ?>
            <a href="<?= $arParams['PATH_TO_ORDER'] ?>" class="go-to-next-stage">Далее<i
                        class="fas fa-chevron-right"></i></a>
        <?php else: ?>
            <a href="javascript:void(0)" class="go-to-next-stage" data-controller="showLoginWindow">Далее<i
                        class="fas fa-chevron-right"></i></a>
        <?php endif ?>
    </div>
</div>
