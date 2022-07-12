<?php
/**
 * Copyright (c) 2019 Артамонов Денис
 * Дата создания: 10/25/19 9:16 PM
 * Email: artamonov.ceo@gmail.com
 */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

\Bitrix\Main\UI\Extension::load('ui.notification');
?>

<?
//\Zk\Main\Helper::_print($arResult);
?>

<form name="registration" action="" method="post">
    <noscript style="color: red; margin-bottom: 25px;display: block; text-align: left;">К сожалению, в Вашем браузере
        отключен Javascript. Для корректной работы формы его необходимо включить.
    </noscript>
    <? if ($arResult['ERROR']): ?>
        <div class="content" style="display: block">
            <?= $arResult['ERROR']['MESSAGE'] ?>
        </div>
        <? if ($arResult['ERROR']['TYPE'] === 'LINK'): ?>
            <a href="/registration/" class="btn btn-primary" style="margin-top: 20px">Повторить запрос</a>
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

                    <div class="block">
                        <div class="row">
                            <div class="col-sm-6">
                                <input type="text" name="name" placeholder="Имя">
                                <input type="text" name="lastName" placeholder="Фамилия">
                                <input type="email" name="email" placeholder="E-mail">
                            </div>
                            <div class="col-sm-6">
                                <input type="text" name="login" placeholder="Логин">
                                <input type="password" name="password" placeholder="Пароль">
                                <input type="password" name="confirmPassword" placeholder="Подтверждение пароля">
                            </div>
                        </div>

                        <div class="row" style="margin-top: 10px">
                            <div class="col-sm-12">
                                <button class="btn btn-primary" name="register">
                                    Зарегистрироваться на сайте
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="social-services block">
                        <div class="row">
                            <div class="col-sm-6">
                                Также, вы можете зарегистрироваться на сайте через ваш аккаунт в социальных сетях
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
