<?php
/*
 * @updated 09.12.2020, 18:38
 * @author Артамонов Денис <bitrix.developer@inbox.ru>
 * @copyright Copyright (c) 2020, Компания Webco <hello@wbc.cx>
 * @link http://wbc.cx
 */

/**
 * @var $APPLICATION
 * @var $USER
 * @var $templateFolder
 * @var $arParams
 * @var $arResult
 */
?>

<?php if (!$USER->IsAuthorized()): ?>
    <div data-popup-code="basket-popup-login-form">
        <div class="basket-popup-form">
            <div class="basket-popup-login-form">
                <div class="mb-2">
                    <div class="basket-popup-login-form-have-account mb-3">У Вас уже есть аккаунт?</div>
                    <form name="basket-popup-login-form" action="">
                        <div class="basket-popup-form-field mb-3">
                            <label for="login">Логин или E-mail</label>
                            <input type="text" name="login" id="login" maxlength="80">
                        </div>
                        <div class="basket-popup-form-field mb-3">
                            <label for="password">Пароль</label>
                            <input type="password" name="password" id="password" maxlength="80">
                        </div>
                        <div class="basket-popup-form-field mb-1">
                            <input type="checkbox" name="remember" id="remember">
                            <label for="remember">Сохранить пароль</label>
                        </div>
                        <div>
                            <a href="/login/" class="basket-popup-login-form-forgot">Забыи пароль?</a>
                        </div>
                        <div class="basket-popup-form-error-list mt-4"></div>
                        <div class="mt-4">
                            <a href="javascript:void(0)" class="basket-popup-form-button"
                               data-controller="login">Авторизация</a>
                        </div>
                    </form>
                    <?php if (count($arResult['LOGIN_BY_SOCIAL']['SERVICES']) > 0): ?>
                        <div class="my-4" style="color: #969696; font-size: 14px; text-align: center">или</div>
                        <div class="basket-popup-login-form-by-social">
                            <?php $APPLICATION->IncludeComponent('bitrix:socserv.auth.form', 'flat',
                                [
                                    'AUTH_SERVICES' => $arResult['LOGIN_BY_SOCIAL']['SERVICES'],
                                    'CURRENT_SERVICE' => $arResult['LOGIN_BY_SOCIAL']['CURRENT_SERVICE'],
                                    'AUTH_URL' => $arResult['LOGIN_BY_SOCIAL']['AUTH_URL'],
                                    'POST' => $arResult['POST'],
                                    'SHOW_TITLES' => 'N',
                                    'FOR_SPLIT' => 'N',
                                    'AUTH_LINE' => 'Y',
                                ],
                                false,
                                ['HIDE_ICONS' => 'Y']
                            ) ?>
                        </div>
                    <?php endif ?>
                </div>

                <div class="my-5">
                    <div class="basket-popup-login-form-maybe-register">
                        В первый раз на нашем сайте?

                        <?/*
                        Устарело с 2020-09-04
                        https://megre.bitrix24.ru/workgroups/group/9/tasks/task/view/5107/
                        <br>
                        Тогда с нас подарок <img src="<?= $templateFolder ?>/images/gift.png" alt=""
                                                 style="transform: translate(1px, -1px);">
                            */?>
                    </div>
                    <div class="mt-4">
                        <a href="javascript:void(0)" class="basket-popup-form-button"
                           data-controller="showRegisterWindow">Создать аккаунт</a>
                    </div>
                </div>

                <div class="mt-2">
                    <div class="basket-popup-login-form-not-account mb-4">Нет аккаунта?</div>
                    <div>
                        <a href="<?= $arParams['PATH_TO_ORDER'] ?>" class="go-to-next-stage-as-guest">Продолжить как
                            гость<i
                                    class="fas fa-chevron-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div data-popup-code="basket-popup-register-form">
        <div class="basket-popup-form">
            <div class="basket-popup-register-form">
                <div class="basket-popup-form-title mb-4">Заполните контактные данные</div>
                <div>
                    <form name="basket-popup-register-form" action="">
                        <div class="basket-popup-form-field mb-3">
                            <label for="email">E-mail</label>
                            <input type="text" name="email" id="email" maxlength="80">
                        </div>
                        <div class="basket-popup-form-field mb-3">
                            <label for="emailConfirm">Подтверждение E-mail</label>
                            <input type="text" name="emailConfirm" id="emailConfirm" maxlength="80">
                        </div>
                        <div class="basket-popup-form-field mb-3">
                            <label for="password">Пароль</label>
                            <input type="password" name="password" id="password" maxlength="80">
                        </div>
                        <div class="basket-popup-form-field">
                            <label for="passwordConfirm">Подтверждение пароля</label>
                            <input type="password" name="passwordConfirm" id="passwordConfirm" maxlength="80">
                        </div>
                        <div class="basket-popup-form-error-list mt-4"></div>
                        <div class="mt-4">
                            <a href="javascript:void(0)" class="basket-popup-form-button"
                               data-controller="register">Применить</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div data-popup-code="basket-popup-coupon-form">
        <div class="basket-popup-form">
            <div class="basket-popup-coupon-form">
                <div class="basket-popup-form-title mb-4">Спасибо за регистрацию!</div>
                <div style="font-size: 14px; color: #4d4d4d; text-align: center">
                    Мы дарим Вам в подарок молочно-медовое мыло «Кедра».<br>
                    Нужно совершить первую покупку на сайте megre.ru на сумму не меньше 1500 рублей без учета доставки и
                    ввести промо-код в корзине перед оплатой.
                </div>
                <div class="my-4">
                    <img src="<?= $templateFolder ?>/images/coupon-image.png" alt="">
                </div>
                <div>
                    <div class="basket-popup-coupon-form-code" data-controller="copyCouponToBuffer">COUPON</div>
                </div>
                <div class="mt-2" style="font-size: 14px; color: #969696; text-align: center">
                    Нажмите, чтобы скопировать купон
                </div>
                <div class="mt-4">
                    <a href="<?= $arParams['PATH_TO_ORDER'] ?>" class="basket-popup-form-button">Оформить заказ</a>
                </div>
            </div>
        </div>
    </div>
<?php endif ?>
