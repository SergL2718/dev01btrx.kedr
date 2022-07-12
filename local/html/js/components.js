document.addEventListener('DOMContentLoaded', function () {
	const swiperMain = new Swiper('.block-main .swiper', {
		loop: true,
		autoplay: true,
		navigation: {
			nextEl: '.swiper-arrow-next',
			prevEl: '.swiper-arrow-prev',
		},
	});

////// catalog filter //////
	$('.catalog-filter__row.drop .catalog-filter__link').on('click', function (e) {
		e.preventDefault();
		$(this).parent().toggleClass('active');
		$(this).next().slideToggle('fast');
	})

	$('.catalog-filter__card.drop .catalog-filter__card-title').on('click', function () {
		$(this).parent().toggleClass('active');
		$(this).next().slideToggle('fast');
	})

	$('.catalog-filter__button').on('click', function () {
		$('.catalog-filter__wrapper').addClass('active');
		$('body').addClass('overflow-hidden');
	})

	$('.catalog-filter__close').on('click', function () {
		$('.catalog-filter__wrapper').removeClass('active');
		$('body').removeClass('overflow-hidden');
	})

	/*if ($(window).width() < 767) {
		$('.catalog-filter__menu').append($('.catalog-sorting .custom-select'))
	}*/
////// ---catalog filter--- //////

////// catalog about //////
	$('.catalog-about__show-more').on('click', function () {
		$(this).prev().removeClass('collapsed');
		$(this).remove();
	})
////// ---catalog about--- //////

////// range slider //////
	let rangeSlider1 = $(".range-slider-input-1");
	let rangeSlider2 = $(".range-slider-input-2");
	let rangeSliderNum1 = $(".range-slider-number-1 span");
	let rangeSliderNum2 = $(".range-slider-number-2 span");

	const toInt = input => {
		let val = Number(input);

		if (Number.isInteger(val)) {
			return Number(val);
		} else {
			return 0;
		}
	};
	$(".range-slider").slider({
		range: true,
		min: 200,
		max: 5000,
		step: 10,
		animate: "fast",
		values: [ 200, 3000 ],
		slide: function( event, ui ) {
			rangeSliderNum1.html( ui.values[0] );
			rangeSliderNum2.html( ui.values[1] );
		},
		create: function() {
			let values = $(this).slider("option", "values");
			rangeSliderNum1.html(values[0]);
			rangeSliderNum2.html(values[1]);
		}
	});
	/*rangeSlider1.on('keyup', function() {
		if ( toInt(rangeSlider1.val()) >= toInt(rangeSlider2.val()) ) {
			toInt(rangeSlider1.val(toInt(rangeSlider2.val())));
		}
		$(".range-slider").slider("values" , 0, $(this).val());
	});
	rangeSlider2.on('blur', function() {
		if ( toInt(rangeSlider2.val()) <= toInt(rangeSlider1.val()) ) {
			toInt(rangeSlider2.val(toInt(rangeSlider1.val())));
		}
		$(".range-slider").slider("values" , 1, $(this).val());
	});*/
////// ---range slider--- //////

	$('.catalog-filter__sort').each(function () {
		$(this).find('.catalog-filter__sort-button').on('click', function (e) {
			$(document).on('click',function() {
				$(".catalog-filter__sort-options").slideUp('fast');
				$('.catalog-filter__sort-button').removeClass('active');
			});

			$(this).toggleClass('active')
			$(this).next().slideToggle('fast');

			e.stopPropagation();
		})

		$(this).find('.catalog-filter__sort-option').on('click', function (e) {
			$(this).parents('.catalog-filter__sort-options').slideToggle('fast');
			$(this).parents('.catalog-filter__sort-options').prev().toggleClass('active');
			$(this).parents('.catalog-filter__sort').find('.catalog-filter__sort-button').html($(this).html())

			e.stopPropagation();
		})
	})

////// product slider //////
	const swiperThumbNav = new Swiper('.swiper-thumb-nav', {
		loop: false,
		direction: "vertical",
		spaceBetween: 10,
		breakpoints: {
			540: {
				slidesPerView: 2,
			},
			767: {
				slidesPerView: 'auto',
			}
		}
	});
	const swiperThumb = new Swiper('.swiper-thumb', {
		loop: false,
		thumbs: {
			swiper: swiperThumbNav,
		},
	});
////// ---product slider--- //////

////// button like //////
	$('.button-like').on('click', function () {
		$(this).toggleClass('active');
		$(this).parents('a').one('click', function (e) {
			e.preventDefault();
		})
	})
////// ---button like--- //////

////// product value button //////
	$('.product-about__value-button').on('click', function () {
		$(this).parent().children().removeClass('active');
		$(this).addClass('active');
	})
////// ---product value button--- //////

////// product buy with //////
	const swiperBuyWith = new Swiper('.product-more .swiper', {
		loop: false,
		navigation: {
			nextEl: '.swiper-arrow-next',
			prevEl: '.swiper-arrow-prev',
		},
		spaceBetween: 30,
		breakpoints: {
			300: {
				slidesPerView: 2,
				spaceBetween: 15,
			},
			767: {
				slidesPerView: '3',
				spaceBetween: 20,
			},
			999: {
				slidesPerView: '4',
				spaceBetween: 30,
			}
		}
	});
////// ---product buy with--- //////

////// tabs //////
	$('.tabs-container').each(function() {
		let ths = $(this);
		ths.find('.tabs-item').not(':first').hide();
		ths.find('.tabs-name').on('click', function() {
			ths.find('.tabs-name').removeClass('active').eq($(this).index()).addClass('active');
			ths.find('.tabs-item').hide().eq($(this).index()).fadeIn()
		}).eq(0).addClass('active');
	});
////// ---tabs--- //////

////// rating //////
	let $ratingStar = $('.rating-star')
	$ratingStar.on('mouseover', function(){
		let onStar = parseInt($(this).data('value'), 10); // The star currently mouse on

		// Now highlight all the stars that's not after the current hovered star
		$(this).parent().children('.rating-star').each(function(e){
			if (e < onStar) {
				$(this).addClass('rating-star_hover');
			}
			else {
				$(this).removeClass('rating-star_hover');
			}
		});

	}).on('mouseout', function(){
		$(this).parent().children('.rating-star').each(function(e){
			$(this).removeClass('rating-star_hover');
		});
	});
	$ratingStar.on('click', function() {
		let onStar = parseInt($(this).data('value'), 10); // The star currently selected
		let stars = $(this).parent().children('.rating-star');

		for (i = 0; i < stars.length; i++) {
			$(stars[i]).removeClass('rating-star_selected');
		}

		for (i = 0; i < onStar; i++) {
			$(stars[i]).addClass('rating-star_selected');
		}
	})
////// ---rating--- //////

	if ($(window).width() < 767) {
		$('#modal-feedback .modal-body').append($('.feedback'));
	}

////// modal recode help //////
	$('.modal-recode__send').on('click', function () {
		$(this).parent().find('.modal-recode__help').fadeIn('fast');
	})
	$('.modal-recode__help-close').on('click', function () {
		$(this).parents('.modal-recode__help').fadeOut('fast');
	})
	$('.modal-recode__help-body .link').on('click', function () {
		$(this).parents('.modal-recode__help').fadeOut('fast');
	})
////// ---modal recode help-- //////

////// share page //////
	document.addEventListener('DOMContentLoaded', function() {
		const createShareHtml = function(title, shareData) {
			let anchorList = [];
			const anchorTemplate = function(item) {
				return '<a href="' + item.href + '" class="social-share__item social-share__item_' + item.name +'" title="' + item.title + '" onclick="' + item.onclick + '"></a>';
			}
			const wrapperTemplate = function(title, anchorList) {
				return '<div class="social-share__title">' + title + '</div><div class="social-share__items">' + anchorList.join('') + '</div>';
			}
			shareData.forEach(function(item) {
				anchorList.push(anchorTemplate(item));
			})
			return wrapperTemplate(title, anchorList);
		}
		const $socialShare = document.querySelector('.social-share');
		if ($socialShare) {
			const url = encodeURIComponent(location.protocol + '//' + location.host + location.pathname);
			const title = encodeURIComponent(document.title);
			const twitterUserName = 'Кедры';
			const shareData = [
				{
					name: 'twitter',
					title: 'Twitter',
					href: 'https://twitter.com/intent/tweet?text=' + title + '&url=' + url + '&via=' + twitterUserName,
					onclick: 'window.open(this.href, \'Twitter\', \'width=800,height=300,resizable=yes,toolbar=0,status=0\'); return false'
				},
				{
					name: 'fb',
					title: 'Facebook',
					href: 'https://www.facebook.com/sharer/sharer.php?u=' + url,
					onclick: 'window.open(this.href, \'Facebook\', \'width=640,height=436,toolbar=0,status=0\'); return false'
				},
				{
					name: 'vk',
					title: 'ВКонтакте',
					href: 'https://vk.com/share.php?url=' + url,
					onclick: 'window.open(this.href, \'ВКонтакте\', \'width=800,height=300,toolbar=0,status=0\'); return false'
				},
				{
					name: 'telegram',
					title: 'Telegram',
					href: 'https://t.me/share/url?url=' + url + '&title=' + title,
					onclick: 'window.open(this.href, \'Telegram\', \'width=800,height=300,toolbar=0,status=0\'); return false'
				},
				{
					name: 'Одноклассники',
					title: 'Одноклассники',
					href: 'https://t.me/share/url?url=' + url + '&title=' + title,
					onclick: 'window.open(this.href, \'Одноклассники\', \'width=800,height=300,toolbar=0,status=0\'); return false'
				}
			];
			const $html = createShareHtml('Поделиться', shareData);
			$socialShare.innerHTML = $html;
		}
	});
////// ---share page--- //////

////// //////
	if ($(window).width() < 767) {
		$('.filter-select').each(function () {
			$(this).find('.filter-select__selected').on('click', function (e) {
				$(document).on('click',function() {
					$(".filter-select__options").slideUp('fast');
					$('.filter-select__selected').removeClass('active');
				});

				$(this).toggleClass('active')
				$(this).next().slideToggle('fast');

				e.stopPropagation();
			})

			$(this).find('.filter-select__option').on('click', function (e) {
				$(this).parent().parent().slideToggle('fast');
				$(this).parent().prev().toggleClass('active');
				$(this).parents('.filter-select').find('.filter-select__selected').html($(this).html())

				e.stopPropagation();
			})
		})
	}
////// blog slider //////
	const swiperBlogSlider = new Swiper('.blog-slider .swiper', {
		loop: false,
		spaceBetween: 30,
		navigation: {
			nextEl: '.swiper-arrow-next',
			prevEl: '.swiper-arrow-prev',
		},
		breakpoints: {
			300: {
				slidesPerView: 1,
				spaceBetween: 15,
			},
			767: {
				slidesPerView: '2',
				spaceBetween: 20,
			},
			999: {
				slidesPerView: '3',
				spaceBetween: 30,
			}
		}
	});
////// ---blog slider--- //////

////// faq //////
	$('.faq-card__title').on('click', function () {
		if ($(this).hasClass('active')) {
			$(this).removeClass('active');
			$(this).next().slideUp('fast');
		} else {
			$('.faq-card__title').removeClass('active');
			$('.faq-card__content').slideUp('fast');
			$(this).toggleClass('active');
			$(this).next().slideToggle('fast');
		}
	})
////// ---faq--- //////

	$('.manufacturer-page .link-more').on('click', function () {
		if ($(this).hasClass('active')) {
			$(this).html('ПОКАЗАТЬ БОЛЬШЕ');
			$(this).removeClass('active');
			$('.manufacturer-page-more').slideUp('fast')
		} else {
			$(this).html('СВЕРНУТЬ ИНФОРМАЦИЮ');
			$(this).addClass('active');
			$('.manufacturer-page-more').slideDown('fast')
		}
	})

////// order make steps //////
	if (document.querySelector('.make')) {
		const progress = document.querySelector(".make-steps__progress-line");
		const prev = document.getElementById("prev");
		const next = document.querySelector("#next-step");
		const done = document.querySelector("#order-done");
		const makeOrderContact = document.querySelector("#change-order-contacts");
		const makeOrderDelivery = document.querySelector("#change-order-delivery");
		const steps = document.querySelectorAll(".make-steps__step");
		const makeOrderList = document.querySelector(".order-total .make-orders");
		const makeOrderBonus = document.querySelector(".order-total .order-total__total-bonus");
		const makeOrderCheck = document.querySelector(".order-total .order-total__total-check");
		const makeOrderHelp = document.querySelector(".order-total .make-order-help");

		let currentStep = 1;

		next.addEventListener("click", () => {
			currentStep++;
			if (currentStep > steps.length) {
				currentStep = steps.length;
			}
			update();
		});

		makeOrderDelivery.addEventListener("click", () => {
			currentStep = 2;
			if (currentStep > steps.length) {
				currentStep = steps.length;
			}
			update();
		});

		makeOrderContact.addEventListener("click", () => {
			currentStep = 1;
			if (currentStep > steps.length) {
				currentStep = steps.length;
			}
			update();
		});

		/*prev.addEventListener("click", () => {
			currentStep--;
			if (currentStep < 1) {
				currentStep = 1;
			}
			update();
		});*/

		function update() {
			steps.forEach((step, i) => {
				if (i < currentStep) {
					step.classList.add("active");
				} else {
					step.classList.remove("active");
				}
			});

			if (currentStep) {
				document.querySelectorAll(".step-content").forEach(function(e) {
					e.classList.remove("active");
				})
				document.getElementById("step-" + currentStep).classList.add("active");
			}

			const activeSteps = document.querySelectorAll(".make-steps__step.active");

			progress.style.width = ((activeSteps.length) / (steps.length)) * 100 + "%";

			if (currentStep === steps.length) {
				next.style.display = 'none';

				done.style.display = 'flex';
				makeOrderBonus.style.display = 'flex';
				makeOrderCheck.style.display = 'flex';
				makeOrderHelp.style.display = 'flex';
				makeOrderList.style.display = 'none';
			} else {
				next.style.display = 'flex';

				done.style.display = 'none';
				makeOrderBonus.style.display = 'none';
				makeOrderCheck.style.display = 'none';
				makeOrderHelp.style.display = 'none';
				makeOrderList.style.display = 'block';
			}
		}
	}
////// ---order make steps--- //////

////// order total help //////
	$('.make-order-help__title').on('click', function (e) {
		$(this).next().slideToggle('fast');
		e.stopPropagation();

		$('body').one('click', function () {
			$('.make-order-help__content').slideUp('fast');
		})
	})
////// ---order total help--- //////

////// order view call //////
	if ($(window).width() < 999) {
		$('.order-container').append($('.order-view-call'));
	}
////// ---order view call--- //////

////// order //////
	$('.order-delivery-type .radio__input').on('change', function () {
		let val = $(this).val();

		let tabs = $('.order-delivery-address__card');
		let targetTab = $('.order-delivery-address__card[data-delivery="' + val + '"]');

		tabs.removeClass('active');
		targetTab.addClass('active');
	});

	$('.order-delivery-legal .checkbox').on('click', function () {
		if ($(this).find('input:checked').length) {
			$('.order-delivery-legal__container').slideToggle('fast');
		}
	})
////// ---order--- //////

	////// swipe
	!function(t,e){"use strict";"function"!=typeof t.CustomEvent&&(t.CustomEvent=function(t,n){n=n||{bubbles:!1,cancelable:!1,detail:void 0};var a=e.createEvent("CustomEvent");return a.initCustomEvent(t,n.bubbles,n.cancelable,n.detail),a},t.CustomEvent.prototype=t.Event.prototype),e.addEventListener("touchstart",function(t){if("true"===t.target.getAttribute("data-swipe-ignore"))return;s=t.target,r=Date.now(),n=t.touches[0].clientX,a=t.touches[0].clientY,u=0,i=0},!1),e.addEventListener("touchmove",function(t){if(!n||!a)return;var e=t.touches[0].clientX,r=t.touches[0].clientY;u=n-e,i=a-r},!1),e.addEventListener("touchend",function(t){if(s!==t.target)return;var e=parseInt(l(s,"data-swipe-threshold","20"),10),o=parseInt(l(s,"data-swipe-timeout","500"),10),c=Date.now()-r,d="",p=t.changedTouches||t.touches||[];Math.abs(u)>Math.abs(i)?Math.abs(u)>e&&c<o&&(d=u>0?"swiped-left":"swiped-right"):Math.abs(i)>e&&c<o&&(d=i>0?"swiped-up":"swiped-down");if(""!==d){var b={dir:d.replace(/swiped-/,""),touchType:(p[0]||{}).touchType||"direct",xStart:parseInt(n,10),xEnd:parseInt((p[0]||{}).clientX||-1,10),yStart:parseInt(a,10),yEnd:parseInt((p[0]||{}).clientY||-1,10)};s.dispatchEvent(new CustomEvent("swiped",{bubbles:!0,cancelable:!0,detail:b})),s.dispatchEvent(new CustomEvent(d,{bubbles:!0,cancelable:!0,detail:b}))}n=null,a=null,r=null},!1);var n=null,a=null,u=null,i=null,r=null,s=null;function l(t,n,a){for(;t&&t!==e.documentElement;){var u=t.getAttribute(n);if(u)return u;t=t.parentNode}return a}}(window,document);
	////// ---swipe
	$('.order-total').on('touchstart', function () {
		$('body').addClass('overflow-hidden');
	})
	$('.order-total').on('touchend', function () {
		$('body').removeClass('overflow-hidden');
	})

	document.addEventListener('swiped-up', function(e) {
		if (e.target === document.querySelector('.order-total')) {
			$('.order-total__content, .order-total__bonus').slideDown('fast');
		}
	});

	document.addEventListener('swiped-down', function(e) {
		if (e.target === document.querySelector('.order-total')) {
			$('.order-total__content, .order-total__bonus').slideUp('fast');
		}
	});

////// delivery map //////
// Функция ymaps.ready() будет вызвана, когда
// загрузятся все компоненты API, а также когда будет готово DOM-дерево.
	ymaps.ready(init);
	function init(){
		// Создание карты.
		let map = new ymaps.Map("map", {
			// Координаты центра карты.
			// Порядок по умолчанию: «широта, долгота».
			// Чтобы не определять координаты центра карты вручную,
			// воспользуйтесь инструментом Определение координат.
			center: [55.76, 37.64],
			// Уровень масштабирования. Допустимые значения:
			// от 0 (весь мир) до 19.
			zoom: 10,
			controls: []
		});

		loadObjects(map);

		map.geoObjects.events.add('click', function (e) {
			let target = e.get('target');
			let id = target.properties._data.id;

			scrollToContent(id);
		});


		// Центрирование карты при клике на элемент сайдбара
		$(document).on('click', '.shop-card', function () {
			let xpos = $(this).data('xpos');
			let ypos = $(this).data('ypos');

			zoomOnPoint(map, xpos, ypos);
		});
	}


	function zoomOnPoint(map, xpos, ypos) {
		map.setCenter([xpos, ypos], 15, {
			checkZoomRange: true
		});
	}


	function scrollToContent(id) {
		let content = $('.shop-card[data-id="' + id + '"]');

		$(".shop-map__list").animate(
			{
				scrollTop: content.position().top + $('.shop-map__list').scrollTop() - content.outerHeight() + 25
			},
			800 //speed
		);
	}


	function loadObjects(map) {
		// Перебираем массив объектов
		$.each(points, function (key, item) {
			addPoint(map, item);
			addPointContent(item);
		});
	}

// Добавление точки на карту
	function addPoint(map, item) {
		// Создаем точку
		let placemark = new ymaps.Placemark(
			[
				item.coords[0],
				item.coords[1],
			],
		);

		// Задаем точке id (для отслеживания куда мы кликаем)
		placemark.properties.set(
			{
				id: item.id
			}
		);

		// Добавляем точку на карту
		map.geoObjects.add(placemark);
	}

// Добавление контента точки
	function addPointContent(item) {
		$('.shop-map__list').append('' +
			'<div class="shop-card" data-id="' + item.id + '" data-xpos="' + item.coords[0] + '" data-ypos="' + item.coords[1] + '">\n' +
			'   <div class="shop-card__logo"></div>\n' +
			'   <div class="shop-card__name">' + item.name + '</div>\n' +
			'   <div class="shop-card__address">' + item.address + '</div>\n' +
			'   <div class="shop-card__time">' + item.time + '</div>\n' +
			'   <div class="shop-card__price">' + item.price + '</div>\n' +
			'   <div class="shop-card__choose">' +
			'       <label class="radio" for="' + item.id + '">' +
			'       	<input class="radio__input" type="radio" id="' + item.id + '" name="shops" value="' + item.id + '"/>' +
			'       	<div class="radio__container">' +
			'		        <div class="radio__icon"></div>' +
			'       		<div class="radio__label">Выбрать МАГАЗИН</div>' +
			'       	</div>' +
			'       </label>' +
			'   </div>\n' +
			'</div>');
	}
////// ---delivery map--- //////
})