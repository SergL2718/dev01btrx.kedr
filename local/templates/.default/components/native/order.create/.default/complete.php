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

<div class="order-complete-content">
    <div class="order-complete-message mt-0 mt-lg-3">
        <div class="order-complete-message-text">
            <div id="order-payment-bill" style="display:none">
                Большое спасибо за ваш заказ и за оказанное нам доверие!
                <br>
                <br>
                Мы отправили вам письмо на #USER_EMAIL# со счетом на оплату. Проверьте папку спам, если ничего не
                пришло. Обычно оплата поступает на следующий рабочий день, но все зависит он банка.
                <br>
                <br>
                Если у вас возникнут вопросы по заказу или пожелания по нашей работе, пишите на почту admin@megre.ru или
                звоните по бесплатному номеру 8-800-350-0270.
            </div>
            <div id="order-payment-in-store" style="display:none">
                Большое спасибо за ваш заказ и за оказанное нам доверие!
                <br>
                <br>
                Мы отправили вам письмо на #USER_EMAIL# с подтверждением заказа #ORDER_NUMBER# (так, на всякий случай).
                Проверьте папку "спам", если ничего не пришло.
                <br>
                <br>
                В ближайшее время с вами свяжется менеджер для согласования даты и времени встречи.
                <br>
                <br>
                Если у вас возникнут вопросы по заказу или пожелания по нашей работе, пишите на почту admin@megre.ru или
                звоните по бесплатному номеру 8-800-350-0270.
            </div>
            <?// не используется -- осталось на всякий случай ?>
            <div id="order-payment-online" style="display:none">
                Большое спасибо за ваш заказ и за оказанное нам доверие!
                <br>
                <br>
                Я прослежу, чтобы ваш заказ был обработан в срок. Вы получите письмо с подтверждением
                заказа
                сейчас и еще одно, когда заказ будет собран и передан к доставке (обязательно проверьте папку «спам»
                если не
                получили письмо).
                <br>
                <br>
                Если у вас возникнут вопросы по заказу или пожелания по нашей работе, пишите на почту admin@megre.ru или
                звоните
                по бесплатному номеру 8-800-350-0270.
            </div>
        </div>
        <div class="order-complete-message-photo">
            <img src="<?= $this->__folder ?>/images/alina.jpg" alt="">
        </div>
        <div class="order-complete-message-photo-description">
            Алина, администратор megre.ru
        </div>
    </div>
    <div class="order-complete-button mt-4">
        <a href="/">На главную</a>
    </div>
</div>
