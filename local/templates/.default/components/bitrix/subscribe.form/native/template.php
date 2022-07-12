<?php
/*
 * Изменено: 14 февраля 2022, понедельник
 * Автор: Артамонов Денис <artamonov.d.i@yandex.com>
 * copyright (c) 2022
 */

/**
 * @var array $arResult
 */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die;
?>

<div class="subscription__form">
    <form role="form" method="post" action="<?= $arResult['FORM_ACTION'] ?>">
        <?= bitrix_sessid_post() ?>
        <input type="hidden" name="SENDER_SUBSCRIBE_RUB_ID[]" value="<?= $arResult['SUBSCRIBE_RUB_ID'] ?>">
        <input type="hidden" name="sender_subscription" value="add">
        <div class="subscription__form__header">
            <div>Хотите подарок с таёжного производства?</div>
            <div>Дарим мини зубную пасту с кедровой живицей за подписку на наши новости!.</div>
        </div>
        <div class="subscription__form__body">
            <div>
                <input type="email" name="SENDER_SUBSCRIBE_EMAIL" value="<?= $arResult['EMAIL'] ?>"
                       placeholder="Введите e-mail">
            </div>
            <div>
                <button>ОТПРАВИТЬ</button>
            </div>
        </div>
        <div class="subscription__form__footer">
            Нажимая кнопку «Подписаться», я даю свое согласие на обработку моих персональных данных, в соответствии с
            Федеральным законом от 27.07.2006 года №152-ФЗ «О персональных данных», на условиях и для целей,
            определенных в
            <a href="/terms-of-use/">Согласии на обработку персональных данных</a>.
        </div>
    </form>
</div>
