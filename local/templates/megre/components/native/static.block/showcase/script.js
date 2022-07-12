/*
 * Изменено: 23 ноября 2021, вторник
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

'use strict'

const ShowcaseComponent = {
	init: {},
	controller: {},
	slider: {
		maxSlideProductsCount: 8, // количество товаров на одном слайде - если превышает, показываем стрелки
		oneSlideItemsCount: 4, // количество элементов на одном слайде - один элемент = два товара, в два этажа
		oneSlideItemsLevel: 2, // количество этажей товаров на одном слайде
		node: {},
		timer: {},
		sizeItem: false, // ширина одного элемента на слайде - для расчета сдвига
		firstItem: {},
		maxStep: {},
		currentStep: {},
		play: {},
	},
}

ShowcaseComponent.run = function (params = {}) {
	ShowcaseComponent.params = params
	ShowcaseComponent.init.slider('bestsellers')
	ShowcaseComponent.init.slider('new')
}

ShowcaseComponent.init.slider = function (code) {
	if (!ShowcaseComponent.params.hasOwnProperty(code) || !ShowcaseComponent.params[code]['count'] || ShowcaseComponent.params[code]['count'] <= 0) {
		return
	}
	ShowcaseComponent.slider.node[code] = document.getElementById('showcase-' + code)
	document.querySelector('.showcase-switcher[data-code="' + code + '"]').onclick = function () {
		ShowcaseComponent.controller.switchSlider(code, this)
	}
	ShowcaseComponent.params[code]['count'] = +ShowcaseComponent.params[code]['count']
	if (ShowcaseComponent.params[code]['count'] <= ShowcaseComponent.slider.maxSlideProductsCount) {
		return
	}
	let prev = document.querySelectorAll('#showcase-' + code + ' .slider-nav-prev')
	let next = document.querySelectorAll('#showcase-' + code + ' .slider-nav-next')
	if (prev && next) {
		if (ShowcaseComponent.slider.sizeItem === false) {
			ShowcaseComponent.slider.sizeItem = document.querySelector('.showcase-products-wrapper .showcase-slider-wrapper .slider-item')
			if (ShowcaseComponent.slider.sizeItem) {
				ShowcaseComponent.slider.sizeItem = ShowcaseComponent.slider.sizeItem.offsetWidth
			}
		}
		ShowcaseComponent.slider.maxStep[code] = Math.ceil(ShowcaseComponent.params[code]['count'] / 2) - ShowcaseComponent.slider.oneSlideItemsCount
		ShowcaseComponent.slider.firstItem[code] = document.querySelector('#showcase-' + code + ' .slider-items .slider-item:first-child')
		ShowcaseComponent.slider.currentStep[code] = 0
		ShowcaseComponent.slider.play[code] = true
		for (let i = 0; i < prev.length; i++) {
			prev[i].onclick = function () {
				ShowcaseComponent.controller.switchSlide(code, 'prev')
			}
		}
		for (let i = 0; i < prev.length; i++) {
			next[i].onclick = function () {
				ShowcaseComponent.controller.switchSlide(code, 'next')
			}
		}
		ShowcaseComponent.controller.runTimer(code)
	}
}

ShowcaseComponent.controller.switchSlider = function (code, node) {
	let currentActiveSwitch = document.querySelector('.showcase-switcher.active[data-code]:not([data-code="' + code + '"])')
	let currentActiveSlider = document.querySelector('.showcase-slider-wrapper.active')
	if (currentActiveSwitch) {
		currentActiveSwitch.classList.remove('active')
	} else {
		currentActiveSwitch = document.querySelector('.showcase-switcher:first-child')
		currentActiveSwitch.classList.add('initial')
	}
	if (currentActiveSlider) {
		currentActiveSlider.classList.remove('active')
	} else {
		currentActiveSlider = document.querySelector('.showcase-slider-wrapper:first-child')
		currentActiveSlider.classList.add('initial')
	}
	node.classList.add('active')
	ShowcaseComponent.slider.node[code].classList.add('active')
}

ShowcaseComponent.controller.switchSlide = function (slider, direction) {
	let marginLeft
	if (direction === 'prev') {
		if (ShowcaseComponent.slider.currentStep[slider] === 0) {
			ShowcaseComponent.slider.currentStep[slider] = ShowcaseComponent.slider.maxStep[slider]
			marginLeft = ShowcaseComponent.slider.maxStep[slider] * ShowcaseComponent.slider.sizeItem
		} else if (ShowcaseComponent.slider.currentStep[slider] <= ShowcaseComponent.slider.maxStep[slider]) {
			ShowcaseComponent.slider.currentStep[slider]--
			marginLeft = ShowcaseComponent.slider.currentStep[slider] * ShowcaseComponent.slider.sizeItem
		}

	} else {
		ShowcaseComponent.slider.currentStep[slider]++
		if (ShowcaseComponent.slider.currentStep[slider] > ShowcaseComponent.slider.maxStep[slider]) {
			ShowcaseComponent.slider.currentStep[slider] = 0
		}
		marginLeft = ShowcaseComponent.slider.currentStep[slider] * ShowcaseComponent.slider.sizeItem
	}
	ShowcaseComponent.slider.firstItem[slider].style.marginLeft = '-' + marginLeft + 'px'
}

ShowcaseComponent.controller.runTimer = function (slider) {
	if (ShowcaseComponent.params[slider]['autoplay'] === true && ShowcaseComponent.slider.play[slider] === true) {
		if (!ShowcaseComponent.params[slider]['autoplay']) {
			ShowcaseComponent.params[slider]['autoplayTimeout'] = 3000
		}
		ShowcaseComponent.slider.timer[slider] = setInterval(function () {
			ShowcaseComponent.controller.switchSlide(slider, 'next')
		}, ShowcaseComponent.params[slider]['autoplayTimeout']);
		ShowcaseComponent.slider.node[slider].onmouseover = function () {
			clearInterval(ShowcaseComponent.slider.timer[slider])
		};
		ShowcaseComponent.slider.node[slider].onmouseout = function () {
			ShowcaseComponent.slider.timer[slider] = setInterval(function () {
				ShowcaseComponent.controller.switchSlide(slider, 'next')
			}, ShowcaseComponent.params[slider]['autoplayTimeout'])
		}
	}
}