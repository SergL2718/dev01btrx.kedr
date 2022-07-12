/*
 * Изменено: 29 сентября 2021, среда
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

'use strict'

const UserBar = {
	init: {},
	popup: {},
	favorites: {},
	basket: {},
}

UserBar.run = function (params = {}) {
	UserBar.param = params
	UserBar.bxPanel = document.getElementById('bx-panel')
	UserBar.header = document.querySelector('header.header')
	UserBar.basket.total = document.querySelector('.header-user-bar [data-code="shopping-bag"] .header-user-bar-count')
	UserBar.init.favorites()
	UserBar.init.search()
}

UserBar.init.favorites = function () {
	UserBar.favorites.icon = document.querySelector('.header-user-bar [data-code="favorites"]')
	UserBar.favorites.total = document.querySelector('.header-user-bar [data-code="favorites"] .header-user-bar-count')
	UserBar.favorites.popup = document.querySelector('.user-bar-popup[data-code="favorites"]')
	UserBar.favorites.popupContent = UserBar.favorites.popup.querySelector('.user-bar-popup-content')
	UserBar.favorites.popupClose = document.querySelector('.user-bar-popup[data-code="favorites"] .user-bar-popup-header-close')
	UserBar.favorites.icon.onclick = function () {
		if (/*Object.keys(FAVORITES).length > 0 &&*/ UserBar.popup.favorites.displayed === false) {
			UserBar.popup.favorites.show()
		} else {
			UserBar.popup.favorites.hide()
		}
	}
	UserBar.favorites.popupClose.onclick = function () {
		UserBar.popup.favorites.hide()
	}
}

UserBar.init.search = function () {
	document.querySelector('.header-user-bar [data-code="search"]').onclick = function () {
		if (Search.displayed === true) {
			Search.hide()
		} else {
			Search.show()
		}
	}
}

UserBar.basket.render = function () {
	if (typeof BASKET === 'undefined') {
		return
	}
	let total = Object.keys(BASKET).length
	if (total === 0) {
		UserBar.basket.total.style.display = 'none'
		return
	}
	UserBar.basket.total.innerText = total
	UserBar.basket.total.style.display = 'flex'
}

UserBar.basket.add = function (id) {
	const quantity = document.querySelector('.user-bar-popup-product-wrapper[data-id="' + id + '"] .user-bar-popup-product-change-quantity span')
	let total = +quantity.innerText || 1
	BX.ajax.post(
		SITE_TEMPLATE_PATH + '/ajax/product.php',
		{
			action: 'addToBasket',
			ID: id,
			QUANTITY: total,
		},
		function (response) {
			response = JSON.parse(response)
			if (response.status !== 'success') {
				if (response.hasOwnProperty('error')) {
					for (let i = 0, l = response.error.length; i < l; i++) {
						BX.UI.Notification.Center.notify({
							content: response.error[i],
							autoHideDelay: 2000
						})
					}
				}
				return
			}

			const items = document.querySelectorAll('.product-preview-wrapper[data-id="' + id + '"]')
			if (!BASKET.hasOwnProperty(id)) {
				BASKET[id] = {
					ITEM_ID: response['itemId'],
					ID: id,
					QUANTITY: total,
				}
				if (items.length > 0) {
					for (let i = 0; i < items.length; i++) {
						items[i].setAttribute('data-in-basket', 'Y')
						items[i].querySelector('.product-added-wrapper span').innerText = total
					}
				}
				UserBar.basket.render()
				return
			}
			BASKET[id]['QUANTITY'] = +BASKET[id]['QUANTITY'] + total
			if (items.length > 0) {
				for (let i = 0; i < items.length; i++) {
					items[i].querySelector('.product-added-wrapper span').innerText = BASKET[id]['QUANTITY']
				}
			}
		}
	)
}

UserBar.basket.changeQuantity = function (id, direction) {
	const quantity = document.querySelector('.user-bar-popup-product-wrapper[data-id="' + id + '"] .user-bar-popup-product-change-quantity span')
	let total = +quantity.innerText || 1
	if (direction === 'reduce') {
		if (total > 1) {
			total -= 1
		}
	} else {
		total += 1
	}
	quantity.innerText = total
}

UserBar.favorites.render = function () {
	if (typeof FAVORITES === 'undefined') {
		return
	}
	let total = Object.keys(FAVORITES).length
	if (total === 0) {
		UserBar.favorites.total.style.display = 'none'
		UserBar.popup.favorites.hide()
		return
	}
	UserBar.favorites.total.innerText = total
	UserBar.favorites.total.style.display = 'flex'
	const items = UserBar.favorites.popup.querySelectorAll('[data-id]')
	const exists = {}
	for (let i = 0; i < items.length; i++) {
		if (FAVORITES.hasOwnProperty(items[i].dataset.id)) {
			exists[items[i].dataset.id] = true
			continue
		}
		items[i].remove()
	}
	for (let id in FAVORITES) {
		if (exists.hasOwnProperty(id)) {
			continue
		}
		const product = document.createElement('div')
		BX.ajax.post(
			UserBar.param['TEMPLATE_PATH'] + '/ajax.php',
			{
				action: 'getTemplate',
				ID: id
			},
			function (response) {
				product.innerHTML = response
				UserBar.favorites.popupContent.appendChild(product)
			}
		)
	}
}

UserBar.favorites.delete = function (id) {
	console.log('favorites.delete');
	BX.ajax.post(
		SITE_TEMPLATE_PATH + '/ajax/product.php',
		{
			action: 'deleteFromFavorites',
			ID: id
		},
		function (response) {
			response = JSON.parse(response)
			if (response.status !== 'success') {
				return
			}
			$('.user-bar-popup-product-wrapper[data-id="'+id+'"]').remove() ;
			delete FAVORITES[id]
			UserBar.favorites.render()
			const items = document.querySelectorAll('[data-id="' + id + '"][data-in-favorites="Y"]')
			if (items.length > 0) {
				for (let i = 0; i < items.length; i++) {
					items[i].removeAttribute('data-in-favorites')
				}
			}
		}
	)
}

UserBar.popup.favorites = {
	displayed: false,
	show: function () {
		UserBar.favorites.popup.style.display = 'block'
		UserBar.popup.favorites.displayed = true
	},
	hide: function () {
		UserBar.favorites.popup.style.display = 'none'
		UserBar.popup.favorites.displayed = false
	},
}
