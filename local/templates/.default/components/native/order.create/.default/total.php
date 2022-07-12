<?php
/*
 * @updated 15.02.2021, 19:37
 * @author Артамонов Денис <bitrix.developer@inbox.ru>
 * @copyright Copyright (c) 2021, Компания Webco <hello@wbc.cx>
 * @link https://wbc.cx
 */

/**
 * @var CMain $APPLICATION
 * @var CUser $USER
 * @var array $arParams
 * @var array $arResult
 */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die;

$stepCode = str_replace('.php', '', basename(__FILE__));

$fullName = implode(' ', [
    $arResult['ORDER']['LAST']['CUSTOMER']['LAST_NAME'],
    $arResult['ORDER']['LAST']['CUSTOMER']['NAME'],
    $arResult['ORDER']['LAST']['CUSTOMER']['SECOND_NAME'],
]);

$deliveryAddress = implode(', ', [
    $arResult['ORDER']['LAST']['LOCATION']['ZIP'],
    $arParams['COUNTRY']['LIST'][$arResult['ORDER']['LAST']['LOCATION']['COUNTRY']['CODE']]['name'],
    $arResult['ORDER']['LAST']['LOCATION']['CITY']['VALUE'],
    $arResult['ORDER']['LAST']['LOCATION']['STREET'],
    $arResult['ORDER']['LAST']['LOCATION']['BUILDING'],
    $arResult['ORDER']['LAST']['LOCATION']['ROOM']
]);
?>

<div class="<?= $arResult['FORM'][$stepCode]['code'] ?>">
    <form action="" name="<?= $arResult['FORM'][$stepCode]['code'] ?>">
        <div style="font-size: 14px; font-weight: 700;">Пожалуйста, проверьте свой заказ и контактную информацию.</div>

        <fieldset class="<?= $arResult['FORM'][$stepCode]['code'] ?>-section mt-4">
            <div class="<?= $arResult['FORM'][$stepCode]['code'] ?>-section-label mt-3">Контактная информация:</div>
            <ul class="<?= $arResult['FORM'][$stepCode]['code'] ?>-section-data mt-3">
                <li><input type="text" readonly required name="fullName" value="<?= $fullName ?>"></li>
                <li><input type="email" readonly required name="email"
                           value="<?= $arResult['ORDER']['LAST']['CUSTOMER']['EMAIL'] ?>"></li>
                <li><input type="text" readonly required name="phone"
                           value="<?= $arResult['ORDER']['LAST']['CUSTOMER']['PHONE'] ?>"></li>
            </ul>
            <div class="<?= $arResult['FORM'][$stepCode]['code'] ?>-section-link mt-3"><a href="javascript:void(0)"
                                                                                          data-controller="setStep"
                                                                                          data-code="customer">Изменить</a>
            </div>
        </fieldset>

        <fieldset class="<?= $arResult['FORM'][$stepCode]['code'] ?>-section mt-4">
            <div class="<?= $arResult['FORM'][$stepCode]['code'] ?>-section-label mt-3">Способ доставки:</div>
            <ul class="<?= $arResult['FORM'][$stepCode]['code'] ?>-section-data mt-3">
                <li><input type="text" readonly required name="delivery"
                           value="<?= $arParams['DELIVERY'][$arResult['ORDER']['LAST']['DELIVERY']['CODE']]['NAME'] ? $arParams['DELIVERY'][$arResult['ORDER']['LAST']['DELIVERY']['CODE']]['NAME'] : '...' ?>">
                </li>
            </ul>
            <div class="<?= $arResult['FORM'][$stepCode]['code'] ?>-section-link mt-3"><a href="javascript:void(0)"
                                                                                          data-controller="setStep"
                                                                                          data-code="delivery">Изменить</a>
            </div>
        </fieldset>

        <fieldset class="<?= $arResult['FORM'][$stepCode]['code'] ?>-section mt-4">
            <div class="<?= $arResult['FORM'][$stepCode]['code'] ?>-section-label mt-3">Адрес доставки:</div>
            <ul class="<?= $arResult['FORM'][$stepCode]['code'] ?>-section-data mt-3">
                <li><input type="text" readonly required name="deliveryAddress" value="<?= $deliveryAddress ?>"></li>
            </ul>
            <div class="<?= $arResult['FORM'][$stepCode]['code'] ?>-section-link mt-3"><a href="javascript:void(0)"
                                                                                          data-controller="setStep"
                                                                                          data-code="delivery">Изменить</a>
            </div>
        </fieldset>

        <fieldset class="<?= $arResult['FORM'][$stepCode]['code'] ?>-section mt-4">
            <div class="<?= $arResult['FORM'][$stepCode]['code'] ?>-section-label mt-3">Стоимость доставки:</div>
            <ul class="<?= $arResult['FORM'][$stepCode]['code'] ?>-section-data mt-3">
                <li><input type="text" readonly required name="deliveryPrice" value="..."></li>
            </ul>
        </fieldset>

        <fieldset class="<?= $arResult['FORM'][$stepCode]['code'] ?>-section mt-4">
            <div class="<?= $arResult['FORM'][$stepCode]['code'] ?>-section-label mt-3">Способ оплаты:</div>
            <ul class="<?= $arResult['FORM'][$stepCode]['code'] ?>-section-data mt-3">
                <li><input type="text" readonly required name="payment"
                           value="<?= $arParams['PAYMENT'][$arResult['ORDER']['LAST']['PAYMENT']['CODE']]['NAME'] ? $arParams['PAYMENT'][$arResult['ORDER']['LAST']['PAYMENT']['CODE']]['NAME'] : '...' ?>">
                </li>
            </ul>
            <div class="<?= $arResult['FORM'][$stepCode]['code'] ?>-section-link mt-3"><a href="javascript:void(0)"
                                                                                          data-controller="setStep"
                                                                                          data-code="payment">Изменить</a>
            </div>
        </fieldset>

        <?/*
        <fieldset class="<?= $arResult['FORM'][$stepCode]['code'] ?>-section mt-4">
            <div class="<?= $arResult['FORM'][$stepCode]['code'] ?>-section-label mt-3">Комментарий:</div>
            <ul class="<?= $arResult['FORM'][$stepCode]['code'] ?>-section-data mt-3">
                <li>
                    <div class="order-form-field">
                        <textarea name="comment" cols="30" rows="10" placeholder="Текст комментария"></textarea>
                    </div>
                </li>
            </ul>
        </fieldset>
        */ ?>

        <fieldset class="<?= $arResult['FORM'][$stepCode]['code'] ?>-section mt-4">
            <div class="<?= $arResult['FORM'][$stepCode]['code'] ?>-section-label mt-3">Ваш заказ</div>
            <div class="<?= $arResult['FORM'][$stepCode]['code'] ?>-section-product-list mt-3">

                <div class="basket-block">
                    <div class="basket-rows">
                        <div class="header mb-3">
                            <div class="column">Товар</div>
                            <div class="column">Цена</div>
                            <div class="column">Количество</div>
                            <div class="column">Сумма</div>
                        </div>

                        <div class="products desktop">
                            <?php foreach ($arResult['ORDER']['BASKET'] as $rowId => $product): ?>
                                <div class="product<?= $product['GIFT'] === 'Y' ? ' gift' : '' ?>"
                                     data-row="<?= $rowId ?>">
                                    <div class="column">
                                        <div class="product-name">
                                            <div class="product-image mr-4"
                                                 style="background-image: url(<?= $product['DETAIL_PICTURE_SRC'] ? $product['DETAIL_PICTURE_SRC'] : $this->__folder . '/images/no-photo.png' ?>)">
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
                                                <div class="product-quantity-value">
                                                    <?= $product['QUANTITY'] ?>
                                                </div>
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
                                </div>
                            <?php endforeach ?>
                        </div>

                        <div class="products mobile">
                            <?php foreach ($arResult['ORDER']['BASKET'] as $rowId => $product): ?>
                                <div class="product<?= $product['GIFT'] === 'Y' ? ' gift' : '' ?>"
                                     data-row="<?= $rowId ?>">

                                    <div class="product-image mr-4"
                                         style="background-image: url(<?= $product['DETAIL_PICTURE_SRC'] ? $product['DETAIL_PICTURE_SRC'] : $this->__folder . '/images/no-photo.png' ?>)">
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
                                                <div class="product-current-price"><?= $product['PRICE']['WITHOUT_CURRENCY'] ?></div>
                                                <div class="product-old-price mx-4"><?= $product['PRICE']['BASE']['WITHOUT_CURRENCY'] ?></div>
                                                <div class="product-discount">
                                                    Скидка <span
                                                            class="product-discount-percent"><?= $product['PRICE']['DISCOUNT']['PERCENT']['FORMATTED'] ?></span>
                                                </div>
                                            </div>
                                            <div class="product-quantity">
                                                <div class="product-quantity-value">
                                                    <?= $product['QUANTITY'] ?>
                                                </div>
                                            </div>
                                            <div class="product-amount">
                                                <?= $product['AMOUNT']['WITHOUT_CURRENCY'] ?>
                                            </div>
                                        <?php endif ?>
                                    </div>
                                </div>
                            <?php endforeach ?>
                        </div>
                    </div>
                </div>


            </div>
            <div class="<?= $arResult['FORM'][$stepCode]['code'] ?>-section-link mt-3"><a href="javascript:void(0)"
                                                                                          data-controller="goToBasket">Изменить</a>
            </div>
        </fieldset>
    </form>
</div>
