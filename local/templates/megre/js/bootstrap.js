/*
 * Изменено: 11 сентября 2021, суббота
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

'use strict'

BX.message({'window-title': document.title})

if (typeof pr !== 'function') {
	/**
	 * Вывод информации в консоль
	 * @param data
	 */
	function pr(data) {
		console.log(data)
	}
}