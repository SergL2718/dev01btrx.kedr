/*
 * Изменено: 26 декабря 2021, воскресенье
 * Автор: Артамонов Денис <artamonov.d.i@yandex.com>
 * copyright (c) 2021
 */

'use strict'

const StoreListComponent = {
	construct: function (params = {}) {
		StoreListComponent.searchData = params
		StoreListComponent.cache = {}
		StoreListComponent.searchText = document.forms['StoreList'].elements['searchText']
		StoreListComponent.searchButton = document.forms['StoreList'].elements['search']
		StoreListComponent.searchButtonLoader = document.getElementById('button-loader')
		StoreListComponent.hintStartSearch = document.getElementById('hint-start-search')
		StoreListComponent.notFound = document.getElementById('not-found')
		StoreListComponent.found = document.getElementById('found')
		StoreListComponent.searchString = false
		document.forms['StoreList'].onsubmit = function (event) {
			event.preventDefault()
			event.stopPropagation()
			StoreListComponent.submit()
		}
		StoreListComponent.searchButton.onclick = function (event) {
			event.preventDefault()
			event.stopPropagation()
			StoreListComponent.submit()
		}
		StoreListComponent.searchText.addEventListener('input', function () {
			StoreListComponent.clear()
		})
	},
	submit: function () {
		StoreListComponent.searchButton.style.display = 'none'
		StoreListComponent.searchButtonLoader.style.display = 'flex'
		setTimeout(function () {
			StoreListComponent.search()
			StoreListComponent.searchButtonLoader.style.display = 'none'
			StoreListComponent.searchButton.style.display = 'block'
		}, 150)
	},
	clear: function () {
		StoreListComponent.searchString = StoreListComponent.searchText.value.toLowerCase().trim()
		if (StoreListComponent.searchString.length <= 3) {
			StoreListComponent.hintStartSearch.style.display = 'block'
			StoreListComponent.notFound.style.display = 'none'
			StoreListComponent.found.style.display = 'none'
		}
	},
	search: function () {
		StoreListComponent.searchString = StoreListComponent.searchText.value.toLowerCase().trim()
		if (StoreListComponent.searchString.length <= 3) {
			StoreListComponent.hintStartSearch.style.display = 'block'
			StoreListComponent.notFound.style.display = 'none'
			StoreListComponent.found.style.display = 'none'
		} else {
			if (!StoreListComponent.cache.hasOwnProperty(StoreListComponent.searchString)) {
				StoreListComponent.cache[StoreListComponent.searchString] = StoreListComponent.find('country', StoreListComponent.searchString) || StoreListComponent.find('city', StoreListComponent.searchString) || false
			}
			StoreListComponent.render()
		}
	},
	find: function (type, search) {
		for (let value in StoreListComponent.searchData[type]) {
			if (value.indexOf(search) !== -1) {
				return StoreListComponent.searchData[type][value]
			}
		}
		return false
	},
	render: function () {
		StoreListComponent.hintStartSearch.style.display = 'none'
		if (!StoreListComponent.cache[StoreListComponent.searchString]) {
			StoreListComponent.found.style.display = 'none'
			StoreListComponent.notFound.style.display = 'block'
			return
		}
		let code = StoreListComponent.searchString
		let list = document.querySelector('.form-result .store-list')
		let items = document.querySelectorAll('.form-result .store-list-item[data-code]')
		if (items.length > 0) {
			for (let i = 0; i < items.length; i++) {
				let item = items[i]
				item.style.display = 'none'
			}
		}
		for (let k in StoreListComponent.cache[StoreListComponent.searchString]) {
			let entity = StoreListComponent.cache[StoreListComponent.searchString][k]
			code += '_' + entity['ID']
			let item = document.querySelector('.store-list-item[data-code="' + code + '"]')
			if (item) {
				item.style.display = 'block'
				continue
			}
			let node = ''
			item = document.createElement('li')
			item.classList.add('store-list-item')
			item.setAttribute('data-code', code)
			if (entity['NAME']) {
				node = document.createElement('h3')
				node.innerText = entity['NAME']
				item.appendChild(node)
			}
			if (entity['CITY']) {
				node = document.createElement('div')
				node.innerHTML = '<span>Город:</span>' + entity['CITY']
				item.appendChild(node)
			}
			if (entity['ADDRESS']) {
				node = document.createElement('div')
				node.innerHTML = '<span>Адрес:</span>' + entity['ADDRESS']
				item.appendChild(node)
			}
			if (entity['SHOPPING_CENTER']) {
				node = document.createElement('div')
				node.innerHTML = '<span>Торговый центр:</span>' + entity['SHOPPING_CENTER']
				item.appendChild(node)
			}
			if (entity['SCHEDULE']) {
				node = document.createElement('div')
				node.innerHTML = '<span>Время работы:</span>' + entity['SCHEDULE']
				item.appendChild(node)
			}
			if (entity['DESCRIPTION']) {
				node = document.createElement('p')
				node.innerHTML = entity['DESCRIPTION']
				item.appendChild(node)
			}
			if (entity['EMAIL'] || entity['PHONE'] || entity['WWW']) {
				node = document.createElement('hr')
				item.appendChild(node)
			}
			if (entity['EMAIL']) {
				node = document.createElement('div')
				node.innerHTML = '<a href="mailto:' + entity['EMAIL'] + '" class="link">' + entity['EMAIL'] + '</a>'
				item.appendChild(node)
			}
			if (entity['PHONE']) {
				for (let i = 0; i < entity['PHONE'].length; i++) {
					node = document.createElement('div')
					if (entity['PHONE'][i].hasOwnProperty('VALUE')) {
						node.innerHTML = '<a href="tel:' + entity['PHONE'][i]['VALUE'] + '" target="_blank">' + entity['PHONE'][i]['FORMATTED'] + '</a>'
					} else {
						node.innerHTML = entity['PHONE'][i]['FORMATTED']
					}
					if (entity['PHONE'][i]['DESCRIPTION']) {
						node.innerHTML += ' – ' + entity['PHONE'][i]['DESCRIPTION']
					}
					item.appendChild(node)
				}
			}
			if (entity['WWW']) {
				node = document.createElement('div')
				node.innerHTML = '<a href="' + entity['WWW'] + '" target="_blank" class="link">' + entity['WWW'] + '</a>'
				item.appendChild(node)
			}
			list.appendChild(item)
		}
		StoreListComponent.notFound.style.display = 'none'
		StoreListComponent.found.style.display = 'block'
	}
}
