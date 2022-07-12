<?php
/*
 * Изменено: 29 декабря 2021, среда
 * Автор: Артамонов Денис <artamonov.d.i@yandex.com>
 * copyright (c) 2022
 */

use Bitrix\Main\LoaderException;
use Bitrix\Main\UI\Extension;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

global $USER;
try {
	Extension::load('ui.notification');
} catch (LoaderException $e) {
}
?>
<form name="login" action="" method="post">
    <div class="elements">
        <label>
            <input type="email" name="email" placeholder="E-mail покупателя">
        </label>
        <div class="description">
            На e-mail <b><?= $GLOBALS['USER']->GetEmail() ?></b> будет отправлена ссылка для входа в интернет-магазин по
            пользователем покупателя
        </div>
        <button class="btn btn-primary" name="send" style="margin-bottom: 20px">Выслать данные</button>
    </div>
    <div class="content"></div>
</form>
