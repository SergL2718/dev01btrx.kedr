<?php
/**
 * Copyright (c) 2019 Артамонов Денис
 * Дата создания: 10/25/19 7:09 PM
 * Email: artamonov.ceo@gmail.com
 */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

\Bitrix\Main\UI\Extension::load('ui.notification');
?>

<?
//\Zk\Main\Helper::_print($arResult);
?>

<form name="login" action="" method="post">
    <noscript style="color: red; margin-bottom: 25px;display: block; text-align: left;">К сожалению, в Вашем браузере
        отключен Javascript. Для корректной работы формы его необходимо включить.
    </noscript>
    <? if ($arResult['ERROR']): ?>
        <div class="content" style="display: block">
            <?= $arResult['ERROR']['MESSAGE'] ?>
        </div>
        <? if ($arResult['ERROR']['TYPE'] === 'LINK' || $arResult['ERROR']['TYPE'] === 'NOT_EXIST'): ?>
            <a href="/login/" class="btn btn-primary" style="margin-top: 20px">Повторить запрос</a>
        <? endif ?>
    <? else: ?>
        <? if ($arResult['SUCCESS']): ?>
            <div class="content" style="display: block">
                <?= $arResult['SUCCESS']['MESSAGE'] ?>
            </div>
        <? else: ?>
            <? if (!$GLOBALS['USER']->isAuthorized()): ?>
                <input type="hidden" name="sessionId" value="<?= session_id() ?>">
                <div class="elements">
                    <div class="row">
                        <div class="col-sm-5">
                            <div class="block">
                                <input type="text" name="login" placeholder="Логин" autofocus autocomplete="on">
                                <input type="password" name="password" placeholder="Пароль" autocomplete="on">
                                <div class="description">
                                    Вы можете войти на сайт используя свой логин и пароль
                                </div>
                                <button class="btn btn-primary" name="loginByPassword">Войти с помощью логина</button>
                            </div>
                        </div>

                        <div class="col-sm-2"
                             style="padding: 15px 0;font-weight: bold;opacity: .4; text-transform: uppercase">
                            Или
                        </div>

                        <div class="col-sm-5">
                            <div class="block">
                                <input type="email" name="email" placeholder="E-mail" autocomplete="on">
                                <div class="description">
                                    На указанный e-mail мы отправим ссылку для входа в интернет-магазин
                                </div>
                                <button class="btn btn-primary" name="loginByEmail">Войти с помощью E-mail</button>
                            </div>
                        </div>
                    </div>

                    <div class="note">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="text-center"><b>Памятка</b></div>
                                Если вы забыли свой пароль от логина, то вы можете авторизоваться с помощью E-mail и
                                после входа на сайт установить новый пароль в личном кабинете.
                            </div>
                        </div>
                    </div>

                    <div class="social-services block">
                        <div class="row">
                            <div class="col-sm-6">
                                Также, вы можете авторизоваться на сайте через ваш аккаунт в социальных сетях
                            </div>
                            <div class="col-sm-6">
                                <div class="social">
                                    <?
                                    $APPLICATION->IncludeComponent("bitrix:socserv.auth.form", "flat",
                                        [
                                            "AUTH_SERVICES" => $arResult["AUTH_SERVICES"],
                                            "CURRENT_SERVICE" => $arResult["CURRENT_SERVICE"],
                                            "AUTH_URL" => $arResult["AUTH_URL"],
                                            "POST" => $arResult["POST"],
                                            "SHOW_TITLES" => $arResult["FOR_INTRANET"] ? 'N' : 'Y',
                                            "FOR_SPLIT" => $arResult["FOR_INTRANET"] ? 'Y' : 'N',
                                            "AUTH_LINE" => $arResult["FOR_INTRANET"] ? 'N' : 'Y',
                                        ],
                                        false,
                                        ["HIDE_ICONS" => "Y"]
                                    );
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="content"></div>

            <? else: ?>
                <div class="content" style="display: block">
                    Вы успешно авторизованы на сайте.<br>Перейти в <a href="/catalog/">каталог продукции</a>.
                </div>
            <? endif ?>
        <? endif ?>
    <? endif ?>
</form>
