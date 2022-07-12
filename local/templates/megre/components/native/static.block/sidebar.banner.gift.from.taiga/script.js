/*
 * Изменено: 09 декабря 2021, четверг
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

'use strict'

const SidebarBannerGiftFromTaiga = {
	button: {}
}

SidebarBannerGiftFromTaiga.run = function (params = {}) {
	SidebarBannerGiftFromTaiga.button.subscribe = document.querySelector('.static-banner-wrapper[data-code="sidebar-gift-from-taiga"] a')
	SidebarBannerGiftFromTaiga.button.subscribe.onclick = SidebarBannerGiftFromTaiga.subscribe
	SidebarBannerGiftFromTaiga.param = params
}

SidebarBannerGiftFromTaiga.subscribe = function () {
	const email = document.querySelector('.static-banner-wrapper[data-code="sidebar-gift-from-taiga"] input[type="email"]').value || ''
	SidebarBannerGiftFromTaiga.button.subscribe.innerHTML = '<span class="loader-dots-wrapper"><span class="loader-dots"><span></span><span></span><span></span></span></span>'
	BX.ajax.post(
		SITE_TEMPLATE_PATH + '/ajax/subscribe.php',
		{
			action: 'subscribe',
			EMAIL: email
		},
		function (response) {
			response = JSON.parse(response)
			SidebarBannerGiftFromTaiga.button.subscribe.innerText = 'Подписаться'
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

SidebarBannerGiftFromTaiga.run()