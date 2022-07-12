<?php
/*
 * Изменено: 12 июля 2021, понедельник
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

/**
 * @var $APPLICATION
 * @var $USER
 * @var $arParams
 * @var $arResult
 */

use Native\App\Provider\Boxberry;
use Native\App\Provider\RussianPost;
use Native\App\Sale\Location;
use Native\App\Sale\Order;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die;

$stepCode = str_replace('.php', '', basename(__FILE__));
?>

<?php if ($arResult['FREE_DELIVERY']['CAN_USE'] === true): ?>
    <div class="mb-5" style="color: #838383; font-size: 14px;">Суммы Вашего заказа достаточно для бесплатной доставки
        Почтой России или Boxberry (пункт выдачи)
    </div>
<?php endif ?>

<div class="<?= $arResult['FORM'][$stepCode]['code'] ?>">
    <form action="" name="<?= $arResult['FORM'][$stepCode]['code'] ?>">

        <?php /*
        <input type="hidden" name="latitude" value="<?= $arResult['ORDER']['LAST']['LOCATION']['LATITUDE'] ?>">
        <input type="hidden" name="longitude" value="<?= $arResult['ORDER']['LAST']['LOCATION']['LONGITUDE'] ?>">
        */ ?>

        <div class="order-form-field">
            <input type="text" required name="fullAddress" id="fullAddress"
                <?php if ($arResult['LOCATION']['INPUT']['ACTIVE'] === 'N'): ?>
                    disabled
                <?php else: ?>
                    data-code="Yandex"
                <?php endif ?>
                   value="<?= $arResult['ORDER']['LAST']['LOCATION']['FULL'] ?>"
                   placeholder="Начните вводить свою улицу или город">
            <label for="fullAddress" class="mb-4">Укажите адрес
                доставки<?php if (Location::getCurrentCityCode() === Location::MSK): ?><span style="color: #9f9f9f"> – регион Москва</span><?php endif ?>
            </label>
        </div>

        <fieldset class="<?= $arResult['FORM'][$stepCode]['code'] ?>-address"
                  data-visibility="<?= $arResult['ORDER']['LAST']['LOCATION']['CITY']['VALUE'] ? 'Y' : 'N' ?>">

            <div class="order-form-field">
                <select name="countryCode" id="countryCode" required
                        <?php if ($arResult['LOCATION']['INPUT']['ACTIVE'] === 'N'): ?>disabled<?php endif ?>>
                    <option value="">не выбрано</option>
                    <?php foreach ($arParams['COUNTRY']['LIST'] as $code => $country): ?>
                        <option
                                value="<?= $code ?>"
                            <?php if ($arResult['ORDER']['LAST']['LOCATION']['COUNTRY']['CODE'] === $code || $arParams['COUNTRY']['CLIENT']['CODE'] === $code): ?>
                                selected
                            <?php endif ?>
                        >
                            <?= $country['name'] ?>
                        </option>
                    <?php endforeach ?>
                </select>
                <label for="countryCode">Страна</label>
            </div>

            <div class="order-form-field">
                <input type="text" required name="city" id="city" maxlength="40"
                       value="<?= $arResult['ORDER']['LAST']['LOCATION']['CITY']['VALUE'] ?>"
                       <?php if ($arResult['LOCATION']['INPUT']['ACTIVE'] === 'N'): ?>disabled<?php endif ?>>
                <label for="city">Город</label>
            </div>
            <div class="order-form-field">
                <input type="text" required name="zip" id="zip" minlength="6" maxlength="7"
                       value="<?= $arResult['ORDER']['LAST']['LOCATION']['ZIP'] ?>">
                <label for="zip">Индекс</label>
            </div>
            <div class="order-form-field">
                <input type="text" required name="street" id="street"
                       value="<?= $arResult['ORDER']['LAST']['LOCATION']['STREET'] ?>">
                <label for="street">Улица</label>
            </div>
            <div class="order-form-field">
                <input type="text" required name="building" id="building"
                       value="<?= $arResult['ORDER']['LAST']['LOCATION']['BUILDING'] ?>">
                <label for="building">Дом/строение</label>
            </div>
            <div class="order-form-field">

                <input type="text" required name="room" id="room"
                       value="<?= $arResult['ORDER']['LAST']['LOCATION']['ROOM'] ?>">
                <label for="room">Квартира/офис</label>
            </div>

            <fieldset
                <?php if (
                    (
                        !empty($arResult['ORDER']['LAST']['DELIVERY']['CODE']) &&
                        (
                            $arResult['ORDER']['LAST']['DELIVERY']['CODE'] !== Boxberry::POINT &&
                            $arResult['ORDER']['LAST']['DELIVERY']['CODE'] !== Boxberry::POINT_FREE &&
                            $arResult['ORDER']['LAST']['DELIVERY']['CODE'] !== 'cdek-store-to-store' &&
                            $arResult['ORDER']['LAST']['DELIVERY']['CODE'] !== 'cdek-store-to-store-free'
                        )
                    )
                    ||
                    (
                        (
                            $arResult['ORDER']['LAST']['DELIVERY']['CODE'] === Boxberry::POINT ||
                            $arResult['ORDER']['LAST']['DELIVERY']['CODE'] === Boxberry::POINT_FREE
                        ) &&
                        !empty($arResult['ORDER']['LAST']['BOXBERRY']['POINT']['ID'])
                    )
                    ||
                    (
                        (
                            $arResult['ORDER']['LAST']['DELIVERY']['CODE'] === 'cdek-store-to-store' ||
                            $arResult['ORDER']['LAST']['DELIVERY']['CODE'] === 'cdek-store-to-store-free'
                        ) &&
                        !empty($arResult['ORDER']['LAST']['CDEK']['POINT']['ID'])
                    )
                ): ?>
                    class="<?= $arResult['FORM'][$stepCode]['code'] ?>-list valid"
                <?php elseif (
                    (
                        (
                            $arResult['ORDER']['LAST']['DELIVERY']['CODE'] === Boxberry::POINT ||
                            $arResult['ORDER']['LAST']['DELIVERY']['CODE'] === Boxberry::POINT_FREE
                        ) &&
                        empty($arResult['ORDER']['LAST']['BOXBERRY']['POINT']['ID'])
                    )
                    ||
                    (
                        (
                            $arResult['ORDER']['LAST']['DELIVERY']['CODE'] === 'cdek-store-to-store' ||
                            $arResult['ORDER']['LAST']['DELIVERY']['CODE'] === 'cdek-store-to-store-free'
                        ) &&
                        empty($arResult['ORDER']['LAST']['CDEK']['POINT']['ID'])
                    )
                ): ?>
                    class="<?= $arResult['FORM'][$stepCode]['code'] ?>-list invalid"
                <?php else: ?>
                    class="<?= $arResult['FORM'][$stepCode]['code'] ?>-list"
                <?php endif ?>
                    data-visibility="<?= $arResult['ORDER']['LAST']['LOCATION']['CITY']['VALUE'] ? 'Y' : 'N' ?>">
                <div class="deliveries-title">Выберите способ доставки</div>
                <?php $show = false // показать список служб доставок ?>
                <?php foreach ($arParams['DELIVERY'] as $item): ?>
                    <?php if (!$item['CODE']) continue ?>
                    <div class="order-form-field">
                        <div class="delivery"
                             data-code="<?= $item['CODE'] ?>"
                            <?php
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
                                    isset($item['restriction']['city'][$arResult['ORDER']['LAST']['LOCATION']['CITY']['LOWER']])
                                    &&
                                    $item['restriction']['city'][$arResult['ORDER']['LAST']['LOCATION']['CITY']['LOWER']] !== true
                                )
                            ): ?>
                                data-visibility="N"
                            <?php else: ?>
                                data-visibility="Y"
                                <?php $show = true ?>
                            <?php endif ?>
                        >
                            <div class="delivery-image">
                                <img src="<?= $item['IMAGE'] ?>" alt="">
                            </div>
                            <div class="delivery-data">
                                <input type="radio" required
                                       name="delivery"
                                       id="delivery-<?= $item['CODE'] ?>"
                                       value="<?= $item['CODE'] ?>"
                                       <?php if ($arResult['ORDER']['LAST']['DELIVERY']['CODE'] === $item['CODE']): ?>checked<?php endif ?>
                                >
                                <label for="delivery-<?= $item['CODE'] ?>">
                                    <div class="delivery-title">
                                        <span class="delivery-price">
                                            <?php if ($item['price'] === 'free' || $item['CODE'] === RussianPost::CLASSIC_FREE): ?>
                                                БЕСПЛАТНО&nbsp;|&nbsp;
                                            <?php elseif ($item['price'] === 'refine'): ?>
                                                УТОЧНЯЕТСЯ&nbsp;|&nbsp;
                                            <?php endif ?>
                                        </span><?= $item['NAME'] ?>
                                    </div>
                                    <?php if ($item['DESCRIPTION']): ?>
                                        <div class="delivery-description"><?= $item['DESCRIPTION'] ?></div>
                                    <?php endif ?>
                                </label>
                            </div>
                        </div>
                    </div>
                <?php endforeach ?>
                <div class="deliveries-empty" data-visibility="<?= $show === true ? 'N' : 'Y' ?>">
                    Для выбранного адреса отсутствуют способы доставки.
                    <?php if (
                        $arParams['ORDER']['TYPE'] === Order::TYPE_RETAIL ||
                        $arParams['ORDER']['TYPE'] === Order::TYPE_COMBINE
                    ): ?>
                        <br>
                        <br>
                        В вашей корзине есть товары, доступные только в магазине "Мегре" в Новосибирске. Если вы хотите заказать товары с доставкой в другой город, пожалуйста удалите их из корзины.
                        <br>
                        <br>
                        <a href="/personal/basket/">Удалите их из корзины</a>
                    <?php endif ?>
                </div>
            </fieldset>

        </fieldset>
    </form>
</div>
<div id="yandex-map-boxberry-point" class="mt-5" data-visibility="N">
    <div id="yandex-map-loader-boxberry-point">
        <div><img src="<?= $this->__folder ?>/images/loading.gif" alt=""></div>
        <div>Ищем пункты выдачи ...</div>
    </div>
</div>
<div id="yandex-map-boxberry-point-free" class="mt-5" data-visibility="N">
    <div id="yandex-map-loader-boxberry-point-free">
        <div><img src="<?= $this->__folder ?>/images/loading.gif" alt=""></div>
        <div>Ищем пункты выдачи ...</div>
    </div>
</div>
<div id="yandex-map-cdek" class="mt-5" data-visibility="N">
    <div id="yandex-map-loader-cdek">
        <div><img src="<?= $this->__folder ?>/images/loading.gif" alt=""></div>
        <div>Ищем пункты выдачи ...</div>
    </div>
</div>
