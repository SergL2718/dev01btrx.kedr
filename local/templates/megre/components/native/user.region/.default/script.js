/*
 * Изменено: 18 сентября 2021, суббота
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

const UserRegionComponent = {
	node: {},
	get: {},
	set: {},
}

UserRegionComponent.run = function (params = {}) {
	UserRegionComponent.param = params
	UserRegionComponent.init.selection()
}

UserRegionComponent.init = {
	selection: function () {
		document.getElementById('region-selection:' + UserRegionComponent.get.id()).onclick = function () {
			UserRegionComponent.controller.popup.show()
		}
	},
	popup: function () {
		UserRegionComponent.popup = {}
		UserRegionComponent.popup.object = BX.PopupWindowManager.create('native-popup:' + UserRegionComponent.get.id(), null, {
			closeByEsc: true,
			autoHide: true,
			lightShadow: false,
			overlay: {
				backgroundColor: 'black',
				opacity: 50
			}
		})
		UserRegionComponent.popup.object.setContent(document.querySelector('[data-popup-code="' + UserRegionComponent.get.id() + '"]'))
		UserRegionComponent.popup.object.handleOverlayClick = UserRegionComponent.controller.popup.close
		document.getElementById('popup-close:' + UserRegionComponent.get.id()).onclick = UserRegionComponent.controller.popup.close
	},
	countries: function () {
		const nodes = document.querySelectorAll('[data-code="country-list:' + UserRegionComponent.get.id() + '"] [data-country-code]')
		for (let i = 0, l = nodes.length; i < l; i++) {
			nodes[i].onclick = function () {
				if (!UserRegionComponent.node['country-city-list-' + nodes[i].dataset.countryCode]) {
					UserRegionComponent.node['country-city-list-' + nodes[i].dataset.countryCode] = document.querySelector('[data-country-city-list="' + nodes[i].dataset.countryCode + '"]')
				}
				const currentCountry = document.querySelector('[data-code="country-list:' + UserRegionComponent.get.id() + '"] [data-country-code].current')
				const currentList = document.querySelector('[data-country-city-list].current')
				if (currentCountry) {
					currentCountry.classList.remove('current')
				}
				if (currentList) {
					currentList.classList.remove('current')
				}
				UserRegionComponent.set.current.country.code(nodes[i].dataset.countryCode)
				UserRegionComponent.node['country-city-list-' + nodes[i].dataset.countryCode].classList.add('current')
				nodes[i].classList.add('current')
				UserRegionComponent.search.result.close.click()
			}
		}
	},
	search: function () {
		UserRegionComponent.search = {
			input: document.getElementById('user-region-search:' + UserRegionComponent.get.id()),
			result: {
				wrapper: document.getElementById('search-result:' + UserRegionComponent.get.id()),
				content: document.getElementById('search-result-content:' + UserRegionComponent.get.id()),
				close: document.getElementById('search-result-close:' + UserRegionComponent.get.id()),
			}
		}
		UserRegionComponent.search.input.onfocus = UserRegionComponent.controller.search.result.show
		UserRegionComponent.search.input.onchange = UserRegionComponent.controller.search.find
		UserRegionComponent.search.input.oninput = UserRegionComponent.controller.search.find
		UserRegionComponent.search.input.onpaste = UserRegionComponent.controller.search.find
		UserRegionComponent.search.input.oncut = UserRegionComponent.controller.search.result.close
		UserRegionComponent.search.result.close.onclick = UserRegionComponent.controller.search.result.close
		UserRegionComponent.search.input.onmouseout = function () {
			this.blur()
		}
	},
	locations: function () {
		const nodes = document.querySelectorAll('[data-country-city-list] a')
		for (let i = 0, l = nodes.length; i < l; i++) {
			nodes[i].onclick = function () {
				UserRegionComponent.set.current.location(nodes[i].dataset.id, nodes[i].parentNode.dataset.countryCityList)
			}
		}
	}
}

UserRegionComponent.controller = {
	popup: {
		show: function () {
			if (typeof UserRegionComponent.popup === 'undefined') {
				UserRegionComponent.init.countries()
				UserRegionComponent.init.search()
				UserRegionComponent.init.locations()
				UserRegionComponent.init.popup()
			}
			document.title = UserRegionComponent.get.popup.title()
			UserRegionComponent.popup.object.show()
		},
		close: function () {
			document.title = BX.message('window-title')
			UserRegionComponent.popup.object.close()
		}
	},
	search: {
		find: function () {
			if (!UserRegionComponent.search.input.value) {
				return
			}
			const search = UserRegionComponent.search.input.value.toUpperCase()
			const result = []
			for (let i = 0; i < UserRegionComponent.get.search.length(); i++) {
				if (UserRegionComponent.get.search.index()[i].indexOf(search) !== -1) {
					result.push(UserRegionComponent.get.search.data()[UserRegionComponent.get.search.index()[i]])
				}
			}
			UserRegionComponent.controller.search.result.render(result)
		},
		result: {
			show: function () {
				UserRegionComponent.search.result.wrapper.style.display = 'flex'
			},
			close: function () {
				UserRegionComponent.search.input.value = ''
				UserRegionComponent.search.result.wrapper.style.display = ''
				UserRegionComponent.search.result.content.innerText = UserRegionComponent.get.search.notFound()
			},
			render: function (data) {
				if (data.length === 0) {
					UserRegionComponent.search.result.content.innerText = UserRegionComponent.get.search.notFound()
					return
				}
				UserRegionComponent.search.result.content.innerHTML = ''
				for (let i in data) {
					const location = document.createElement('a')
					location.href = 'javascript:void(0)'
					location.innerText = data[i]['NAME']
					location.onclick = function () {
						UserRegionComponent.set.current.location(data[i]['ID'], data[i]['COUNTRY']['CODE'])
					}
					UserRegionComponent.search.result.content.appendChild(location)
				}
			}
		},
	}
}

UserRegionComponent.get = {
	id: function () {
		return UserRegionComponent.param['UNIQUE_ID']
	},
	cookie: {
		code: function () {
			return UserRegionComponent.param['COOKIE']
		}
	},
	location: {
		country: function (code) {
			return UserRegionComponent.param['LOCATION']['COUNTRY'][code]
		},
		city: function (country, id) {
			return UserRegionComponent.param['LOCATION']['COUNTRY'][country]['CITY'][id]
		},
		other: function () {
			return UserRegionComponent.param['LOCATION'][UserRegionComponent.param['OTHER']]
		}
	},
	search: {
		length: function () {
			return UserRegionComponent.param['SEARCH']['LENGTH'][UserRegionComponent.get.current.country.code()]
		},
		index: function () {
			return UserRegionComponent.param['SEARCH']['INDEX'][UserRegionComponent.get.current.country.code()]
		},
		data: function () {
			return UserRegionComponent.param['SEARCH']['DATA'][UserRegionComponent.get.current.country.code()]
		},
		notFound: function () {
			return UserRegionComponent.param['SEARCH_NOT_FOUND_TITLE']
		}
	},
	current: {
		country: {
			code: function () {
				return UserRegionComponent.param['CURRENT_COUNTRY'] || 'RU'
			}
		}
	},
	popup: {
		title: function () {
			return UserRegionComponent.param['POPUP_TITLE']
		}
	}
}

UserRegionComponent.set = {
	current: {
		country: {
			code: function (code) {
				return UserRegionComponent.param['CURRENT_COUNTRY'] = code
			}
		},
		location: function (id, country) {
			BX.showWait()
			BX.setCookie(UserRegionComponent.get.cookie.code(), JSON.stringify({
				'ID': id ? id : UserRegionComponent.get.location.other()['ID'],
				'CODE': id ? UserRegionComponent.get.location.city(country, id)['ISO_CODE'] : UserRegionComponent.get.location.other()['ISO_CODE'],
				'NAME': id ? UserRegionComponent.get.location.city(country, id)['NAME'] : UserRegionComponent.get.location.other()['NAME'],
				'COUNTRY': {
					'ID': UserRegionComponent.get.location.country(country)['ID'],
					'CODE': UserRegionComponent.get.location.country(country)['ISO_CODE']
				}
			}), {
				expires: 864000000,
				path: '/'
			})
			window.location.reload()
		}
	},
}