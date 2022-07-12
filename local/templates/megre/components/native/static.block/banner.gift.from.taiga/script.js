/*
 * Изменено: 09 декабря 2021, четверг
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

'use strict'

const BannerGiftFromTaiga = {
	button: {}
}

BannerGiftFromTaiga.run = function (params = {}) {
	BannerGiftFromTaiga.button.subscribe = document.querySelector('.static-banner-wrapper[data-code="gift-from-taiga"] a')
	BannerGiftFromTaiga.button.subscribe.onclick = BannerGiftFromTaiga.subscribe
	BannerGiftFromTaiga.param = params
}

BannerGiftFromTaiga.subscribe = function () {
	const email = document.querySelector('.static-banner-wrapper[data-code="gift-from-taiga"] input[type="email"]').value || ''
	BannerGiftFromTaiga.button.subscribe.innerHTML = '<span class="loader-dots-wrapper"><span class="loader-dots"><span></span><span></span><span></span></span></span>'
	BX.ajax.post(
		SITE_TEMPLATE_PATH + '/ajax/subscribe.php',
		{
			action: 'subscribe',
			EMAIL: email
		},
		function (response) {
			response = JSON.parse(response)
			BannerGiftFromTaiga.button.subscribe.innerText = 'Подписаться'
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

BannerGiftFromTaiga.run()