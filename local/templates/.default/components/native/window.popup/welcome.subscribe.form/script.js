/*
 * Изменено: 20 January 2022, Thursday
 * Автор: Артамонов Денис <artamonov.d.i@yandex.com>
 * copyright (c) 2022
 */

let _wrapper = document.querySelector('.window__popup__wrapper');
let _window = _wrapper.querySelector('.window__popup');
let _close = _window.querySelector('.window__popup__close i');
let classShow = 'show';
let notShow = 'not-show-welcome-subscribe';
let timeout = 1;

setTimeout(function () {
    if (!BX.getCookie(notShow)) {
        _wrapper.classList.add(classShow);
    }
}, timeout);

_close.onclick = function () {
    let _notShow = _window.querySelector('input[name="welcome-subscribe"]').checked;
    if (_notShow) {
        BX.setCookie(notShow, true, {expires: 864000000});
    }
    _wrapper.classList.remove(classShow);
};