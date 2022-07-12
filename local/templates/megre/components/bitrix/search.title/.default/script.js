/*
 * Изменено: 15 декабря 2021, среда
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

'use strict'

const Search = {
	displayed: false, // используется для иконки в user bar
	wrapper: false,
	show: function () {
		if (Search.wrapper === false) {
			Search.wrapper = document.getElementById('search')
			Search.input = document.getElementById('title-search-input')
			Search.close = document.getElementById('searchClose')
			Search.result = document.querySelector('div.title-search-result')
			Search.close.onclick = Search.hide
		}
		Search.wrapper.style.display = 'block'
		Search.input.focus()
		Search.displayed = true
	},
	hide: function () {
		Search.input.blur()
		Search.result.style.display = 'none'
		Search.wrapper.style.display = 'none'
		Search.displayed = false
	}
}