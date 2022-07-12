<?php
/*
 * @updated 15.02.2021, 19:37
 * @author Артамонов Денис <bitrix.developer@inbox.ru>
 * @copyright Copyright (c) 2021, Компания Webco <hello@wbc.cx>
 * @link https://wbc.cx
 */

/**
 * @var $APPLICATION
 * @var $USER
 * @var $arParams
 * @var $arResult
 */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die;

$stepCode = str_replace('.php', '', basename(__FILE__));
?>

<div class="<?= $arResult['FORM'][$stepCode]['code'] ?>">
    <form action="" name="<?= $arResult['FORM'][$stepCode]['code'] ?>">

        <div class="payment-list-title mb-4" data-valid="">Выберите способ оплаты</div>
        <div class="<?= $arResult['FORM'][$stepCode]['code'] ?>-list">
            <? $show = false // показать список способов оплаты ?>
            <? foreach ($arParams['PAYMENT'] as $item): ?>
                <div class="payment"
                     data-code="<?= $item['CODE'] ?>"
                    <?
                    if (
                        (
                            isset($item['restriction']['country'])
                            &&
                            (
                                count($item['restriction']['country']) === 0
                                ||
                                $item['restriction']['country'][$arResult['ORDER']['LAST']['LOCATION']['COUNTRY']['CODE']]['access'] !== true
                            )
                        )
                        ||
                        (
                            isset($item['restriction']['city-deny'][$arResult['ORDER']['LAST']['LOCATION']['CITY']['LOWER']])
                            &&
                            $item['restriction']['city-deny'][$arResult['ORDER']['LAST']['LOCATION']['CITY']['LOWER']] === true
                        )
                        ||
                        (
                            isset($item['restriction']['city'][$arResult['ORDER']['LAST']['LOCATION']['CITY']['LOWER']]) &&
                            $item['restriction']['city'][$arResult['ORDER']['LAST']['LOCATION']['CITY']['LOWER']] !== true
                        )
                        ||
                        (
                            !empty($arResult['ORDER']['LAST']['DELIVERY']['CODE'])
                            &&
                            isset($item['restriction']['delivery'])
                            &&
                            !in_array($arResult['ORDER']['LAST']['DELIVERY']['CODE'], $item['restriction']['delivery'])
                        )
                    ): ?>
                        data-visibility="N"
                    <? else: ?>
                        data-visibility="Y"
                        <? $show = true ?>
                    <? endif ?>
                >
                    <div class="order-form-field">
                        <input type="radio" required
                               name="payment"
                               id="payment-<?= $item['CODE'] ?>"
                               value="<?= $item['CODE'] ?>"
                               <? if ($arResult['ORDER']['LAST']['PAYMENT']['CODE'] === $item['CODE']): ?>checked<? endif ?>
                        >
                        <label for="payment-<?= $item['CODE'] ?>">
                            <div class="payment-image"><img src="<?= $item['IMAGE'] ?>" alt=""></div>
                            <div class="payment-title"><?= $item['NAME'] ?></div>
                        </label>
                    </div>
                </div>
            <? endforeach ?>
            <div class="payments-empty" data-visibility="<?= $show === true ? 'N' : 'Y' ?>">Для выбранного адреса
                отсутствуют способы оплаты
            </div>
        </div>

        <div class="<?= $arResult['FORM'][$stepCode]['code'] ?>-description-list">
            <? foreach ($arParams['PAYMENT'] as $item): ?>
                <? if ($item['DESCRIPTION']): ?>
                    <div class="payment-description" data-payment-code="<?= $item['CODE'] ?>" data-visibility="N">
                        <div class="payment-description-text">
                            <?= $item['DESCRIPTION'] ?>
                        </div>
                        <? if ($item['CODE'] === 'bill'): ?>
                            <div class="payment-description-image">
                                <img src="<?= $this->__folder ?>/images/alina.jpg" alt="">
                            </div>
                            <div class="payment-description-footer">
                                Алина, администратор megre.ru
                            </div>
                        <? endif ?>
                    </div>
                <? endif ?>
            <? endforeach ?>
        </div>
    </form>
</div>
