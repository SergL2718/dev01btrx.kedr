/*
 * Изменено: 24 January 2022, Monday
 * Автор: Артамонов Денис <artamonov.d.i@yandex.com>
 * copyright (c) 2022
 */

'use strict'

const FullwidthSubscribeForm = {
    button: {}
}

FullwidthSubscribeForm.run = function (params = {}) {
    FullwidthSubscribeForm.button.subscribe = document.querySelector('.static-banner-wrapper[data-code="fullwidth-subscribe-form"] a')
    FullwidthSubscribeForm.button.subscribe.onclick = FullwidthSubscribeForm.subscribe
    FullwidthSubscribeForm.param = params
}

FullwidthSubscribeForm.subscribe = function () {
    const email = document.querySelector('.static-banner-wrapper[data-code="fullwidth-subscribe-form"] input[type="email"]').value || ''
    FullwidthSubscribeForm.button.subscribe.innerHTML = '<span class="loader-dots-wrapper"><span class="loader-dots"><span></span><span></span><span></span></span></span>'
    BX.ajax.post(
        SITE_TEMPLATE_PATH + '/ajax/subscribe.php',
        {
            action: 'subscribe',
            EMAIL: email
        },
        function (response) {
            response = JSON.parse(response)
            FullwidthSubscribeForm.button.subscribe.innerText = 'Подписаться'
            if (response.status !== 'success') {
                if (response.hasOwnProperty('error')) {
                    BX.UI.Notification.Center.notify({
                        content: response.error,
                        autoHideDelay: 2000
                    })
                }
                return
            }
            if (response.hasOwnProperty('message')) {
                BX.UI.Notification.Center.notify({
                    content: response.message,
                    autoHideDelay: 4000
                })
            }
        }
    )
}

FullwidthSubscribeForm.run()