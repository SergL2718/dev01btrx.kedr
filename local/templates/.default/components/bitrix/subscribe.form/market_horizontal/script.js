/*
 * Изменено: 21 January 2022, Friday
 * Автор: Артамонов Денис <artamonov.d.i@yandex.com>
 * copyright (c) 2022
 */

var checkbox = document.getElementById('confirm-subscribe-main-page');

checkbox.onchange = function () {
    var buttonSubscribe = document.querySelector('button[data-id="confirm-subscribe-main-page"]');
    if (checkbox.checked === true) {
        buttonSubscribe.removeAttribute('disabled');
    } else {
        buttonSubscribe.setAttribute('disabled', 'disabled');
    }
};