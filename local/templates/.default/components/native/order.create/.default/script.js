/*
 * Изменено: 27 августа 2021, пятница
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

/**
 * @var Yandex
 */

const OrderComponent = {
    params: {},
    storage: {},
    cache: {},
    init: {},
    controller: {},
    get: {},
    set: {},
    order: {},
    isCreatingOrder: false, // флаг отображающий, создается ли заказ в настоящее время
    notification: document.getElementById('notification'),
}

// Инициализация компонента
OrderComponent.init.component = function (params) {
    OrderComponent.params = OrderComponent.prepare.params(params)
    OrderComponent.init.storage()
    OrderComponent.init.cache()
    OrderComponent.get.delivery.location.fullAddress.node().setAttribute('data-code', 'Yandex')
    OrderComponent.init.step()
    OrderComponent.init.sidebar()
    OrderComponent.init.mobile()
    OrderComponent.init.controllers()
    OrderComponent.init.delivery()
    OrderComponent.init.payment()
}
OrderComponent.prepare = {
    params: function (params) {
        params = params || {}
        if (params['basket']['amount']) {
            params['basket']['amount'] = +params['basket']['amount']
        }
        return params
    },
}
OrderComponent.init.storage = function () {
    OrderComponent.storage.data = JSON.parse(OrderComponent.get.cookie(OrderComponent.get.storage.code())) || {}
}
OrderComponent.init.cache = function () {
    OrderComponent.set.cache('go-to-catalog', document.querySelector('.order-change-step a[data-controller="goToCatalog"]'))
    OrderComponent.set.cache('go-to-previous-step', document.querySelector('.order-change-step a[data-controller="previousStep"]'))
    OrderComponent.set.cache('go-to-next-step', document.querySelector('.order-change-step a[data-controller="nextStep"]'))
    OrderComponent.set.cache('go-to-order', document.querySelector('.order-change-step a[data-controller="order"]'))
    OrderComponent.set.cache('mobile-go-to-catalog', document.querySelector('.mobile-panel .order-mobile-panel-change-step a[data-controller="goToCatalog"]'))
    OrderComponent.set.cache('mobile-go-to-previous-step', document.querySelector('.mobile-panel .order-mobile-panel-change-step a[data-controller="previousStep"]'))
    OrderComponent.set.cache('mobile-go-to-next-step', document.querySelector('.mobile-panel .order-mobile-panel-change-step a[data-controller="nextStep"]'))
    OrderComponent.set.cache('mobile-go-to-order', document.querySelector('.mobile-panel .order-mobile-panel-change-step a[data-controller="order"]'))
    OrderComponent.set.cache('sidebar-product-list', document.querySelector('.order-sidebar-total'))
}
OrderComponent.init.step = function () {
    OrderComponent.step.activate(OrderComponent.get.step.current())
}
OrderComponent.init.sidebar = function () {
    if (!OrderComponent.get.sidebar.node()) return

    const contentOffsetTop = App.util.getOffset(document.querySelector('.order-content')).top - 20;

    if (OrderComponent.get.sidebar.node().offsetHeight > (window.innerHeight - 100)) {
        OrderComponent.get.sidebar.node().classList.remove('fixed');
        return
    }
    if (window.pageYOffset > contentOffsetTop) {
        OrderComponent.get.sidebar.node().classList.add('fixed');
    } else {
        OrderComponent.get.sidebar.node().classList.remove('fixed');
    }

    window.addEventListener('scroll', function () {
        if (OrderComponent.get.sidebar.node().offsetHeight > (window.innerHeight - 100)) {
            OrderComponent.get.sidebar.node().classList.remove('fixed');
            return
        }
        if (window.pageYOffset > contentOffsetTop) {
            OrderComponent.get.sidebar.node().classList.add('fixed');
        } else {
            OrderComponent.get.sidebar.node().classList.remove('fixed');
        }
    });
}
OrderComponent.init.mobile = function () {
    OrderComponent.mobile.panel.collapse();
}
OrderComponent.init.controllers = function () {
    const controllers = document.querySelectorAll('a[href^="javascript:void(0)"][data-controller]');
    if (controllers.length === 0) return
    for (const node of controllers) {
        const controller = node.dataset.controller;
        if (typeof OrderComponent.controller[controller] === 'undefined') {
            continue;
        }
        node.addEventListener('click', OrderComponent.controller[controller], false)
    }
}
OrderComponent.init.delivery = function () {
    OrderComponent.delivery.list.show()
    if (OrderComponent.get.delivery.current()) {
        OrderComponent.delivery.activate(OrderComponent.get.delivery.current())
    } else if (OrderComponent.get.delivery.location.zip.current()) {
        OrderComponent.delivery.calculate()
    }
}
OrderComponent.init.payment = function () {
    if (OrderComponent.get.payment.current()) {
        OrderComponent.payment.activate(OrderComponent.get.payment.current());
    }
}

// Шаги
OrderComponent.step = {
    activate: function (code) {
        // Установим текущий шаг
        OrderComponent.set.step.current(code);
        // Активируем форму для текущего шага
        OrderComponent.form.activate(code);
        // Обновим состояние маркеров
        for (const item of OrderComponent.get.step.node.marker.list()) {
            if (item.dataset.code === OrderComponent.get.step.current()) {
                item.classList.add('active');
            } else {
                item.classList.remove('active');
            }
        }
        // Обновим состояние контента
        for (const item of OrderComponent.get.step.node.data.list()) {
            if (item.dataset.code === OrderComponent.get.step.current()) {
                item.classList.add('active');
            } else {
                item.classList.remove('active');
            }
        }
        // Поменяем состояние кнопки Продолжить покупки-Назад
        if (OrderComponent.get.step.current() === 'customer') {

            OrderComponent.get.cache('go-to-catalog').setAttribute('data-visibility', 'Y');
            OrderComponent.get.cache('go-to-previous-step').setAttribute('data-visibility', 'N');
            OrderComponent.get.cache('go-to-next-step').setAttribute('data-visibility', 'Y');
            OrderComponent.get.cache('go-to-order').setAttribute('data-visibility', 'N');

            OrderComponent.get.cache('mobile-go-to-catalog').setAttribute('data-visibility', 'Y');
            OrderComponent.get.cache('mobile-go-to-previous-step').setAttribute('data-visibility', 'N');
            OrderComponent.get.cache('mobile-go-to-next-step').setAttribute('data-visibility', 'Y');
            OrderComponent.get.cache('mobile-go-to-order').setAttribute('data-visibility', 'N');

            OrderComponent.get.cache('sidebar-product-list').setAttribute('data-visibility', 'Y');

        } else if (OrderComponent.get.step.current() === 'total') {

            OrderComponent.get.cache('go-to-catalog').setAttribute('data-visibility', 'N');
            OrderComponent.get.cache('go-to-previous-step').setAttribute('data-visibility', 'Y');
            OrderComponent.get.cache('go-to-next-step').setAttribute('data-visibility', 'N');
            OrderComponent.get.cache('go-to-order').setAttribute('data-visibility', 'Y');

            OrderComponent.get.cache('mobile-go-to-catalog').setAttribute('data-visibility', 'N');
            OrderComponent.get.cache('mobile-go-to-previous-step').setAttribute('data-visibility', 'Y');
            OrderComponent.get.cache('mobile-go-to-next-step').setAttribute('data-visibility', 'N');
            OrderComponent.get.cache('mobile-go-to-order').setAttribute('data-visibility', 'Y');

            OrderComponent.get.cache('sidebar-product-list').setAttribute('data-visibility', 'N');

        } else {

            OrderComponent.get.cache('go-to-catalog').setAttribute('data-visibility', 'N');
            OrderComponent.get.cache('go-to-previous-step').setAttribute('data-visibility', 'Y');
            OrderComponent.get.cache('go-to-next-step').setAttribute('data-visibility', 'Y');
            OrderComponent.get.cache('go-to-order').setAttribute('data-visibility', 'N');

            OrderComponent.get.cache('mobile-go-to-catalog').setAttribute('data-visibility', 'N');
            OrderComponent.get.cache('mobile-go-to-previous-step').setAttribute('data-visibility', 'Y');
            OrderComponent.get.cache('mobile-go-to-next-step').setAttribute('data-visibility', 'Y');
            OrderComponent.get.cache('mobile-go-to-order').setAttribute('data-visibility', 'N');

            OrderComponent.get.cache('sidebar-product-list').setAttribute('data-visibility', 'Y');
        }
        // Попытаемся получить адрес по местоположению пользователя
        // Если он даст разрешение на это
        if (
            OrderComponent.get.step.current() === 'delivery'
        ) {
            // Подключим сервис определения геолокации
            // Если не был заполнен город - то есть адрес не был задан ранее
            if (
                !OrderComponent.get.param('last')['location']['city']['value'] &&
                !OrderComponent.get.delivery.location.city.current()
            ) {
                Yandex.ready(function () {
                    const geolocation = Yandex.geolocation.get()
                    geolocation.then(function (response) {
                        response = {
                            coordinates: response.geoObjects.get(0).geometry['_coordinates'],
                            location: response.geoObjects.get(0).properties['_data'].metaDataProperty.GeocoderMetaData.Address
                        }
                        OrderComponent.set.delivery.location(response)
                    })
                })
            }
        }

        if (OrderComponent.hasOwnProperty('notification') && OrderComponent.notification) {
            if (
                OrderComponent.get.step.current() === 'delivery'
                ||
                OrderComponent.get.step.current() === 'total'
            ) {
                OrderComponent.notification.style.display = 'block'
            } else {
                OrderComponent.notification.style.display = 'none'
            }
        }

        // Сохраним данные в локальную память устройства
        OrderComponent.set.storage.data('currentStep', code)
    }
}

// Формы
OrderComponent.form = {
    activate: function (code) {
        if (!OrderComponent.get.form.data(code) || OrderComponent.get.cache('form-' + code + '-activated') === true) return
        for (const field of OrderComponent.get.form.data(code).elements) {

            if (code === 'total') {
                if (field.name === 'phone') {
                    // Маска, точнее форматирование внешнего вида номера телефона
                    new BX.PhoneNumber.Input({
                        node: field,
                        forceLeadingPlus: false,
                        defaultCountry: 'ru'
                    })
                }
                continue
            }

            switch (field.type) {
                case 'text':
                    // Обработка поля с номером телефона
                    if (field.name === 'phone') {
                        // Маска для поля
                        new BX.PhoneNumber.Input({
                            node: field,
                            forceLeadingPlus: false,
                            defaultCountry: 'ru',
                            onChange: function (result) {
                                const number = result['value'];
                                if (number.indexOf('+') !== -1) {
                                    if (number.length === 12) {
                                        field.style.borderColor = '#8bc34a'; // status ok
                                    } else {
                                        field.style.borderColor = '#dcdcdc'; // status default
                                    }
                                } else {
                                    if (number.length === 11) {
                                        field.style.borderColor = '#8bc34a'; // status ok
                                    } else {
                                        field.style.borderColor = '#dcdcdc'; // status default
                                    }
                                }

                                // Сохраним данные в локальную память устройства
                                OrderComponent.set.storage.data(field.name, result['formattedValue']);
                            }
                        });
                        // Если уже был указан телефон
                        if (field.value.length > 4) {
                            const number = OrderComponent.get.phone.clear(field.value);
                            if (number.indexOf('+') !== -1) {
                                if (number.length === 12) {
                                    field.style.borderColor = '#8bc34a'; // status ok
                                } else {
                                    field.style.borderColor = '#dcdcdc'; // status default
                                }
                            } else {
                                if (number.length === 11) {
                                    field.style.borderColor = '#8bc34a'; // status ok
                                } else {
                                    field.style.borderColor = '#dcdcdc'; // status default
                                }
                            }
                        }

                    } else if (field.name === 'zip') {
                        if (field.value.length < 5) {
                            field.style.borderColor = '#dcdcdc' // status default
                        } else {
                            field.style.borderColor = '#8bc34a' // status ok
                        }
                    } else if (field.dataset.code === 'Yandex') {
                        // Подключим сервис подсказок
                        Yandex.ready(function () {
                            const SuggestView = new Yandex.SuggestView(OrderComponent.get.delivery.location.fullAddress.node())
                            SuggestView.events.add('select', function (event) {
                                const location = event.get('item')
                                if (!location.value) return
                                // Подключим сервис геокодинга
                                Yandex.geocode(location.value, {
                                    json: true,
                                    results: 1,
                                    kind: 'house'
                                }).then(function (response) {
                                    response = {
                                        coordinates: [
                                            response.GeoObjectCollection.metaDataProperty.GeocoderResponseMetaData.Point.coordinates[1],
                                            response.GeoObjectCollection.metaDataProperty.GeocoderResponseMetaData.Point.coordinates[0]
                                        ],
                                        location: response.GeoObjectCollection.featureMember[0].GeoObject.metaDataProperty.GeocoderMetaData.Address
                                    }
                                    OrderComponent.set.delivery.location(response)
                                })
                            })
                        })
                    }

                    field.addEventListener('change', function () {

                        field.classList.remove('invalid')

                        if (
                            field.name === 'fullAddress' ||
                            field.name === 'city' /*||
                            field.name === 'street'*/
                        ) {
                            if (field.value.length === 0) {
                                OrderComponent.delivery.location.hide()
                                OrderComponent.delivery.list.hide()
                                OrderComponent.delivery.location.clear()
                                OrderComponent.delivery.clear()

                            } else {

                                if (field.name === 'city') {
                                    OrderComponent.delivery.list.show()
                                    OrderComponent.boxberry.map.reload()
                                    OrderComponent.cdek.map.reload()
                                    OrderComponent.payment.list.reload()
                                }

                                if (field.name !== 'fullAddress') {
                                    OrderComponent.get.form.data('delivery').elements['fullAddress'].value = ''

                                    if (OrderComponent.get.delivery.location.country.current()) {
                                        OrderComponent.get.form.data('delivery').elements['fullAddress'].value = OrderComponent.get.form.data('delivery').elements['countryCode'].options[OrderComponent.get.form.data('delivery').elements['countryCode'].selectedIndex].text
                                    }
                                    if (OrderComponent.get.delivery.location.city.current()) {
                                        OrderComponent.get.form.data('delivery').elements['fullAddress'].value += ', ' + OrderComponent.get.delivery.location.city.current()
                                    }
                                    if (OrderComponent.get.form.data('delivery').elements['street'].value) {
                                        OrderComponent.get.form.data('delivery').elements['fullAddress'].value += ', ' + OrderComponent.get.form.data('delivery').elements['street'].value
                                    }

                                    OrderComponent.set.storage.data('fullAddress', OrderComponent.get.form.data('delivery').elements['fullAddress'].value)
                                }
                            }

                        } else if (field.name === 'zip') {
                            if (field.value.length < 5) {
                                field.style.borderColor = 'red' // status error
                            } else {
                                field.style.borderColor = '#8bc34a' // status ok
                                // Обновим цены на доставку
                                OrderComponent.delivery.calculate()
                            }
                        }

                        // Сохраним данные в локальную память устройства
                        if (field.name !== 'phone') {
                            OrderComponent.set.storage.data(field.name, field.value)
                        }
                    }, false)
                    break;
                case 'email':
                    field.addEventListener('change', function () {
                        field.classList.remove('invalid')
                        // Сохраним данные в локальную память устройства
                        OrderComponent.set.storage.data(field.name, field.value)
                    }, false)
                    break
                case 'checkbox':
                    field.addEventListener('click', function () {
                        field.classList.remove('invalid')
                        // Сохраним данные в локальную память устройства
                        OrderComponent.set.storage.data(field.name, field.checked)
                    }, false)
                    break
                case 'radio':
                    field.addEventListener('click', function () {
                        // Переключение тип покупателя
                        if (field.name === 'customerType') {
                            OrderComponent.get.form.data('customer').setAttribute('data-type', field.value)
                        }
                        // Выбор способа доставки
                        else if (field.name === 'delivery') {
                            // Активируем службу доставки
                            OrderComponent.delivery.activate(field.value)
                        } // Выбор способа оплаты
                        else if (field.name === 'payment') {
                            // Активируем способ оплаты
                            OrderComponent.payment.activate(field.value)
                        }
                        // Сохраним данные в локальную память устройства
                        OrderComponent.set.storage.data(field.name, field.value)
                    }, false)
                    break
                case 'select-one':
                    field.addEventListener('change', function () {
                        field.classList.remove('invalid')
                        if (field.name === 'countryCode') {
                            OrderComponent.delivery.list.show()
                            OrderComponent.delivery.calculate()
                            OrderComponent.payment.list.reload()
                            OrderComponent.boxberry.map.hide(OrderComponent.get.delivery.current())
                            OrderComponent.cdek.map.hide()
                        }
                        // Сохраним данные в локальную память устройства
                        OrderComponent.set.storage.data(field.name, field.value)
                    }, false)
                    break
                default:
                    break
            }
        }
        // Запомним активацию формы, чтобы повторно её уже не активировать
        OrderComponent.set.cache('form-' + code + '-activated', true)
    }
}

// Товары
OrderComponent.product = {
    list: {
        show: function () {
            if (!OrderComponent.get.product.list()) return
            OrderComponent.get.product.list().setAttribute('data-visibility', 'Y')
            OrderComponent.get.product.link().innerText = 'Свернуть корзину ..'
        },
        hide: function () {
            if (!OrderComponent.get.product.list()) return
            OrderComponent.get.product.list().setAttribute('data-visibility', 'N')
            OrderComponent.get.product.link().innerText = 'Развернуть корзину ..'
        }
    }
}

// Доставка
OrderComponent.delivery = {
    /**
     * Включить/выбрать службу доставки
     * Включает стоимость доставки в итоговоу сумму
     * @param code
     */
    activate: function (code) {

        const delivery = OrderComponent.get.delivery.data(code)

        if (!delivery) {
            // Настроим валидность у всего списка служб доставок
            OrderComponent.get.delivery.list.node().classList.add('invalid')
            OrderComponent.get.delivery.list.node().classList.remove('valid')
            return
        }

        // Настроим валидность у всего списка служб доставок
        OrderComponent.set.delivery.list.valid()

        // Отключим предыдущую службу доставки
        if (OrderComponent.get.delivery.current()) {
            // Удалим стоимость доставки и обновим общую сумму корзины (вернем оригинальную сумму)
            OrderComponent.set.delivery.price.total(false)
            OrderComponent.set.basket.price.total(OrderComponent.get.basket.price.total())
            // Сохраним данные в локальную память устройства
            OrderComponent.set.storage.data('delivery', false)
        }

        // Настроим обязательность полей адреса для доставки
        if (
            code === 'without-delivery' ||
            code === 'boxberry-point' ||
            code === 'boxberry-point-free' ||
            code === 'cdek-store-to-store' ||
            code === 'pickup-nsk' ||
            code === 'courier-nsk' ||
            code === 'courier-nsk-free' ||
            code === 'courier-berdsk-free' ||
            code === 'pickup-msk-novoslobodskaya'
        ) {
            // Отключаем обязательность полей адреса доставки
            for (const field of OrderComponent.get.form.data('delivery').elements) {
                if (field.name !== 'zip' && field.name !== 'street' && field.name !== 'building' && field.name !== 'room') {
                    continue;
                }
                field.required = false
            }
        } else {
            // Включаем обязательность полей адреса доставки
            for (const field of OrderComponent.get.form.data('delivery').elements) {
                if (field.name !== 'zip' && field.name !== 'street' && field.name !== 'building' && field.name !== 'room') {
                    continue
                }
                field.required = true
            }
        }

        // Если текущий способ доставки Боксбери пункты выдачи - тогда отобразим карту
        // Иначе, скроем карту с пунктами выдачи
        if (code === 'boxberry-point' || code === 'boxberry-point-free') {
            OrderComponent.cdek.deactivate()
            OrderComponent.boxberry.activate(code)
            return
        } else if (code === 'cdek-store-to-store') {
            OrderComponent.boxberry.deactivate('boxberry-point')
            OrderComponent.boxberry.deactivate('boxberry-point-free')
            OrderComponent.cdek.activate()
            return
        } else {
            OrderComponent.boxberry.deactivate('boxberry-point')
            OrderComponent.boxberry.deactivate('boxberry-point-free')
            OrderComponent.cdek.deactivate()
        }

        OrderComponent.message.send({
            content: 'Активирован способ доставки: ' + OrderComponent.get.delivery.name.value(code),
            type: 'success'
        })

        if (delivery.calculated === false) {
            OrderComponent.delivery.calculate(code)
            return
        } else {
            if (delivery.calculated.hasOwnProperty('period') && delivery.calculated.price.value === 0) {
                OrderComponent.message.send({
                    content: 'Не удалось получить стоимость доставки до индекса: ' + OrderComponent.get.delivery.location.zip.current(),
                    type: 'error'
                    //category: 'calculate',
                })
            }
        }

        // Обновим стоимость доставки и общую сумму корзины
        const price = {
            delivery: 0,
            basket: OrderComponent.get.basket.price.total(),
        }
        price.delivery = delivery.calculated.price.value > 0 ? delivery.calculated.price.value : 0
        price.basket = price.basket + price.delivery

        // Обновим DOM
        // Если после калькуляции было обновлено название для службы доставки - тогда обновим его
        if (delivery.calculated.title) {
            if (price.delivery === 0 && delivery.calculated.price.value !== 'free' && delivery.calculated.price.value !== 'refine') {
                delivery.calculated.title = ''
            }
            OrderComponent.set.delivery.price.title(code, delivery.calculated.title)
        }
        // Если после калькуляции было обновлено описание для службы доставки - тогда обновим его
        if (delivery.calculated.description) {
            OrderComponent.set.delivery.description(code, delivery.calculated.description)
        }

        // Установим новые суммы корзины и доставки
        OrderComponent.set.delivery.price.total(price.delivery)
        OrderComponent.set.basket.price.total(price.basket)

        // Обновим данные на итоговом шаге
        if (OrderComponent.delivery.isRefine(code)) {
            OrderComponent.get.form.data('total').elements['deliveryPrice'].value = 'УТОЧНЯЕТСЯ'
        } else {
            OrderComponent.get.form.data('total').elements['deliveryPrice'].value = price.delivery > 0 ? App.util.format.price.withCurrency(price.delivery) : 'БЕСПЛАТНО'
        }
        OrderComponent.get.form.data('total').elements['deliveryPrice'].parentNode.parentNode.parentNode.classList.remove('invalid')

        // Сохраним данные в локальную память устройства
        OrderComponent.set.storage.data('delivery', code)

        // Обновим список платежных систем, так как у способов имеется зависимость от служб доставок
        OrderComponent.payment.list.reload()
    },
    /**
     * Отключить службу доставки
     * Отменяет выбор указанной службы доставки
     * Исключает стоимость доставки из итоговой суммы
     * @param code
     */
    deactivate: function (code) {
        if (OrderComponent.get.delivery.switcher(code).checked === false) return

        //const delivery = OrderComponent.get.delivery.data(code);

        // Отменим активность выбранной службы доставки
        OrderComponent.get.delivery.switcher(code).checked = false;

        // Настроим валидность у всего списка служб доставок
        OrderComponent.get.delivery.list.node().classList.remove('valid');
        OrderComponent.get.delivery.list.node().classList.add('invalid');

        // Удалим стоимость доставки и обновим общую сумму корзины (вернем оригинальную сумму)
        OrderComponent.set.delivery.price.total(false)
        OrderComponent.set.basket.price.total(OrderComponent.get.basket.price.total())

        // Сохраним данные в локальную память устройства
        OrderComponent.set.storage.data('delivery', false)
    },
    /**
     * Метод удляем все цены у служб доставок
     * Например, делаем это при смене города
     */
    clear: function () {
        // Восстановим название служб доставок
        for (let code in OrderComponent.get.delivery.list.data()) {
            const delivery = OrderComponent.get.delivery.list.data()[code]
            OrderComponent.get.delivery.switcher(code).checked = false
            if (!OrderComponent.delivery.isFree(code)) {
                OrderComponent.set.delivery.name(code, delivery['NAME'])
                OrderComponent.set.delivery.description(code, delivery['DESCRIPTION'])
            }
            if (code === 'boxberry-point' || code === 'boxberry-point-free') {
                OrderComponent.boxberry.deactivate(code);
            } else if (code === 'cdek-store-to-store') {
                OrderComponent.cdek.deactivate();
            }
        }
        // Удалим стоимость доставки и обновим общую сумму корзины (вернем оригинальную сумму)
        OrderComponent.set.delivery.price.total(false)
        OrderComponent.set.basket.price.total(OrderComponent.get.basket.price.total())

        // Настроим валидность у всего списка служб доставок
        OrderComponent.set.delivery.list.clear()

        // Сохраним данные в локальную память устройства
        OrderComponent.set.storage.data('delivery', false)
    },
    /**
     * Визуально отображает службу доставки
     * @param code
     */
    show: function (code) {
        if (!OrderComponent.get.delivery.node(code)) return
        OrderComponent.get.delivery.node(code).setAttribute('data-visibility', 'Y')
    },
    /**
     * Визуально скрывает службу доставки
     * @param code
     */
    hide: function (code) {
        if (!OrderComponent.get.delivery.node(code)) return
        OrderComponent.get.delivery.node(code).setAttribute('data-visibility', 'N')
    },
    /**
     * Список служб доставок
     */
    list: {
        show: function () {
            const country = OrderComponent.get.delivery.location.country.current()
            const list = OrderComponent.get.delivery.list.data()
            let city = OrderComponent.get.delivery.location.city.current();

            city = city.toLowerCase().replace('г.', city).replace('г ', city).trim()

            let show = false; // показывать ли список доставок

            // Проверим ограничения у служб доставок
            for (let code in list) {

                if (!list.hasOwnProperty(code)) continue

                const item = list[code];

                // Проверим ограничения для службы доставки
                if (!item.hasOwnProperty('restriction')) {
                    show = true
                    continue
                }

                if (
                    // Если имеется ограничение по стране
                    (
                        item['restriction'].hasOwnProperty('country') &&
                        (
                            item['restriction']['country'].hasOwnProperty(country) === false
                            ||
                            typeof item['restriction']['country'][country] === 'undefined'
                            ||
                            item['restriction']['country'][country]['access'] !== true
                            ||
                            (
                                item['restriction']['country'][country].hasOwnProperty('minPrice') &&
                                item['restriction']['country'][country].hasOwnProperty('maxPrice') &&
                                (
                                    OrderComponent.get.param('basket')['amount'] < item['restriction']['country'][country]['minPrice'] &&
                                    OrderComponent.get.param('basket')['amount'] >= item['restriction']['country'][country]['maxPrice']
                                )
                            )
                            ||
                            (
                                item['restriction']['country'][country].hasOwnProperty('minPrice') &&
                                !item['restriction']['country'][country].hasOwnProperty('maxPrice') &&
                                (
                                    OrderComponent.get.param('basket')['amount'] < item['restriction']['country'][country]['minPrice']
                                )
                            )
                            ||
                            (
                                item['restriction']['country'][country].hasOwnProperty('maxPrice') &&
                                !item['restriction']['country'][country].hasOwnProperty('minPrice') &&
                                (
                                    OrderComponent.get.param('basket')['amount'] >= item['restriction']['country'][country]['maxPrice']
                                )
                            )


                            /*(
                                item['restriction']['country'][country].hasOwnProperty('minPrice') &&
                                (
                                    OrderComponent.get.param('basket')['amount'] < item['restriction']['country'][country]['minPrice']
                                )
                            )
                            ||
                            (
                                item['restriction']['country'][country].hasOwnProperty('maxPrice') &&
                                (
                                    OrderComponent.get.param('basket')['amount'] >= item['restriction']['country'][country]['maxPrice']
                                )
                            )*/

                        )
                    )
                    ||
                    // Если имеется ограничение по городу
                    (
                        ( // Проверим запрет службы в городе
                            item['restriction'].hasOwnProperty('city-deny')
                            &&
                            item['restriction']['city-deny'].hasOwnProperty(city)
                            &&
                            item['restriction']['city-deny'][city] === true // служба запрещена в городе
                        )
                        ||
                        ( // Проверим активность службы в разрещенных городах
                            item['restriction'].hasOwnProperty('city')
                            &&
                            (
                                !item['restriction']['city'].hasOwnProperty(city)
                                ||
                                item['restriction']['city'][city] !== true // служба неактивна в разрешенном городе
                            )
                        )
                    )
                ) {
                    OrderComponent.delivery.hide(item['CODE'])
                    OrderComponent.delivery.deactivate(item['CODE'])
                    continue
                }

                // Если все условия обработаны и ничего не запрешает отобразить службу доставки
                // Тогда отобразим службу доставки
                OrderComponent.delivery.show(item['CODE'])

                show = true
            }

            // Если нет ни одной службы доставки для отображения
            // Тогда отобразим уведомление об этом
            if (show === false) {
                OrderComponent.delivery.list.empty.show()
            } else {
                OrderComponent.delivery.list.empty.hide()
            }

            // Отобразим блок со списком служб доставок
            if (OrderComponent.get.delivery.list.node()) {
                OrderComponent.get.delivery.list.node().setAttribute('data-visibility', 'Y')
            }
        },
        hide: function () {
            OrderComponent.get.delivery.list.node().setAttribute('data-visibility', 'N')
        },
        empty: {
            show: function () {
                OrderComponent.get.delivery.list.empty.node().setAttribute('data-visibility', 'Y')
            },
            hide: function () {
                OrderComponent.get.delivery.list.empty.node().setAttribute('data-visibility', 'N')
            },
        },
    },
    /**
     * Список полей адреса/локации
     */
    location: {
        show: function () {
            OrderComponent.get.delivery.location.list.node().setAttribute('data-visibility', 'Y')
        },
        hide: function () {
            OrderComponent.get.delivery.location.list.node().setAttribute('data-visibility', 'N')
        },
        clear: function () {
            OrderComponent.get.form.data('delivery').elements['fullAddress'].value = ''
            OrderComponent.get.form.data('delivery').elements['countryCode'].value = ''
            OrderComponent.get.form.data('delivery').elements['zip'].value = ''
            OrderComponent.get.form.data('delivery').elements['city'].value = ''
            OrderComponent.get.form.data('delivery').elements['street'].value = ''
            OrderComponent.get.form.data('delivery').elements['building'].value = ''
            //OrderComponent.get.form.data('delivery').elements['room'].value = '';

            OrderComponent.set.storage.data('fullAddress', false)
            OrderComponent.set.storage.data('countryCode', false)
            OrderComponent.set.storage.data('zip', false)
            OrderComponent.set.storage.data('city', false)
            OrderComponent.set.storage.data('street', false)
            OrderComponent.set.storage.data('building', false)
            //OrderComponent.set.storage.data('room', false)
        }
    },
    isFree: function (code) {
        if (!OrderComponent.get.param('delivery')['list'].hasOwnProperty(code)) {
            return false
        }
        return code === 'without-delivery' ||
            code === 'russian-post-free' ||
            code === 'boxberry-point-free' ||
            code === 'courier-nsk' ||
            code === 'courier-nsk-free' ||
            code === 'courier-berdsk-free' ||
            code === 'cdek-store-to-store-free' ||
            OrderComponent.get.param('delivery')['list'][code].hasOwnProperty('price') && OrderComponent.get.delivery.list.data()[code]['price'] === 'free'
    },
    isRefine: function (code) {
        if (!OrderComponent.get.param('delivery')['list'].hasOwnProperty(code)) {
            return false
        }
        return code === 'courier-nsk' ||
            OrderComponent.get.param('delivery')['list'][code].hasOwnProperty('price') &&
            OrderComponent.get.delivery.list.data()[code]['price'] === 'refine'
    },
    isChecked: function (code) {
        return OrderComponent.get.delivery.switcher(code).checked
    },
    isSelected: function (code) {
        return OrderComponent.get.storage.data('delivery') === code
    },
    isEstimatedTime: function (code) {
        return code === 'without-delivery' ||
            code === 'boxberry-courier' ||
            code === 'boxberry-point' ||
            code === 'boxberry-point-free' ||
            code === 'russian-post' ||
            code === 'russian-post-free' ||
            code === 'russian-post-ems' ||
            code === 'russian-post-air' ||
            code === 'russian-post-surface' ||
            code === 'cdek-store-to-door' ||
            code === 'cdek-store-to-store' ||
            code === 'cdek-store-to-store-free'
    },
    /**
     * Расчет стоимости доставки для все доступных способов
     */
    calculate: function (code) {
        const request = {
            deliveries: [],
            location: {
                country: {
                    code: OrderComponent.get.delivery.location.country.current()
                },
                zip: OrderComponent.get.delivery.location.zip.current(),
                city: OrderComponent.get.delivery.location.city.current(),
            }
        };
        if (!request.location.country.code) {
            OrderComponent.message.send({
                content: 'Не указана страна',
                type: 'error'
                //category: 'calculate',
            });
            return
        }
        if (request.location.zip.length < 5) {
            OrderComponent.message.send({
                content: 'Не указан индекс',
                type: 'error',
                //category: 'calculate',
            });
            return
        }
        if (!request.location.city) {
            OrderComponent.message.send({
                content: 'Не указан город',
                type: 'error',
                //category: 'calculate',
            });
            return
        }

        OrderComponent.message.send({
            content: 'Ожидайте, получаем стоимость доставки до индекса: ' + request.location.zip,
            //autoHide: false,
            //category: 'calculate',
        });

        // Если не был передан код службы доставки
        // Тогда выбираем все платные и активные на данный момент службы доставки
        if (code) {
            request.deliveries.push(OrderComponent.get.delivery.data(code))
        } else {
            // Также исключим Боксбери пункты выдачи, так как стоимость считается иначе
            OrderComponent.get.delivery.list.current().forEach(function (delivery) {
                if (
                    delivery !== 'boxberry-point' &&
                    delivery !== 'boxberry-point-free' &&
                    delivery !== 'cdek-store-to-store'
                ) {
                    delivery = OrderComponent.get.delivery.data(delivery)
                    request.deliveries.push(delivery)
                }
            })
        }

        if (!request.deliveries || request.deliveries.length === 0) {
            OrderComponent.message.send({
                content: 'Не удалось получить стоимость доставки до индекса: ' + request.location.zip,
                type: 'error',
                //category: 'calculate',
            })
            return
        }

        /*console.log('request')
        console.log(request)*/

        BX.ajax.runComponentAction(
            OrderComponent.get.param('component'),
            OrderComponent.get.param('delivery')['calculate']['method'],
            {
                mode: 'class',
                data: {
                    request: request
                },
                timeout: 10
            })
            .then(function (response) {
                response = response.data

                let hasPrices = false // флаг указывающий на получение хотя бы одной цены доставки

                // Проверим, если цены получить удалось, тогда обновим рассчитанную информации по ценам
                for (const deliveryCode in response) {
                    if (!response.hasOwnProperty(deliveryCode)) continue;

                    let delivery = {
                        calculated: response[deliveryCode]
                    }

                    if (delivery.calculated !== null && delivery.calculated.hasOwnProperty('errors') && delivery.calculated.errors.hasOwnProperty('message')) {
                        //console.error(delivery.calculated.errors.message)
                        OrderComponent.message.send({
                            content: delivery.calculated.errors.message,
                            type: 'error',
                            autoHideDelay: 6000,
                            //category: 'calculate',
                        })
                    }

                    // Если не удалось получить стоимость
                    if (delivery.calculated === null || !delivery.calculated.hasOwnProperty('price') || delivery.calculated.price === 0) {
                        // Проверим, возможно у способа доставки уже была установлена цена
                        // Тогда надо отменить все данные по доставке
                        delivery = {
                            calculated: {
                                title: OrderComponent.get.delivery.name.value(deliveryCode),
                                description: OrderComponent.get.delivery.description.value(deliveryCode),
                                period: false,
                                price: {
                                    value: 0,
                                },
                            },
                        };

                        // Сохраним данные по рассчетам в службу доставки
                        OrderComponent.set.delivery.calculated(deliveryCode, delivery.calculated);

                        // Обновим DOM
                        // Если после калькуляции было обновлено название для службы доставки - тогда обновим его
                        if (delivery.calculated.title) {
                            OrderComponent.set.delivery.price.title(deliveryCode, '');
                        }
                        // Если после калькуляции было обновлено описание для службы доставки - тогда обновим его
                        if (delivery.calculated.description) {
                            OrderComponent.set.delivery.description(deliveryCode, delivery.calculated.description);
                        }

                        // Если был указан конкретный код доставки, для которой нужно было рассчитать стоимость
                        if (OrderComponent.delivery.isChecked(deliveryCode)) {
                            OrderComponent.delivery.deactivate(deliveryCode)
                        }
                        continue;
                    }

                    hasPrices = true

                    if (delivery.calculated.period) {
                        if (delivery.calculated.period.hasOwnProperty('min') && delivery.calculated.period.hasOwnProperty('max')) {
                            delivery.calculated.period = delivery.calculated.period.min + '-' + delivery.calculated.period.max + ' ' + App.util.declension(delivery.calculated.period.max, 'день', 'дней', 'дня')
                        } else {
                            delivery.calculated.period = delivery.calculated.period + ' ' + App.util.declension(delivery.calculated.period, 'день', 'дней', 'дня')
                        }
                    } else {
                        delivery.calculated.period = 'необходимо уточнить у менеджера'
                    }

                    if (delivery.calculated.price > 0) {
                        delivery.calculated.title = App.util.format.price.withoutCurrency(delivery.calculated.price) + '<img src="' + OrderComponent.get.param('path')['image']['ruble'] + '">&nbsp;|&nbsp;'
                    } else if (delivery.calculated.price === 'free') {
                        delivery.calculated.title = 'БЕСПЛАТНО&nbsp;|&nbsp;'
                    } else if (delivery.calculated.price === 'refine') {
                        delivery.calculated.title = 'УТОЧНЯЕТСЯ&nbsp;|&nbsp;'
                    }

                    delivery = {
                        calculated: {
                            title: delivery.calculated.title,
                            //description: 'Индекс: ' + request.location.zip + ', срок доставки ' + delivery.calculated.period,
                            period: delivery.calculated.period,
                            price: {
                                value: delivery.calculated.price,
                            },
                            parcels: delivery.calculated.parcels || []
                        },
                    }

                    if (!OrderComponent.delivery.isRefine(deliveryCode)) {
                        let deliveryTime = 'срок доставки'
                        if (OrderComponent.delivery.isEstimatedTime(deliveryCode)) {
                            deliveryTime += ', ориентировочно,'
                        }
                        deliveryTime += ' ' + delivery.calculated.period
                        delivery.calculated.description = 'Индекс: ' + request.location.zip + ', ' + deliveryTime
                    }

                    // Сохраним данные по рассчетам в службу доставки
                    OrderComponent.set.delivery.calculated(deliveryCode, delivery.calculated)

                    // Обновим стоимость доставки и общую сумму корзины
                    const price = {
                        delivery: 0,
                        basket: OrderComponent.get.basket.price.total(),
                    }
                    price.delivery = delivery.calculated.price.value > 0 ? delivery.calculated.price.value : 0
                    price.basket = price.basket + price.delivery
                    // Обновим DOM
                    // Если после калькуляции было обновлено название для службы доставки - тогда обновим его
                    if (delivery.calculated.title) {
                        OrderComponent.set.delivery.price.title(deliveryCode, delivery.calculated.title);
                    }
                    // Если после калькуляции было обновлено описание для службы доставки - тогда обновим его
                    if (delivery.calculated.description) {
                        OrderComponent.set.delivery.description(deliveryCode, delivery.calculated.description);
                    }

                    // Если был указан конкретный код доставки, для которой нужно было рассчитать стоимость
                    if (OrderComponent.delivery.isChecked(deliveryCode)) {
                        // Установим новые суммы корзины и доставки
                        OrderComponent.set.delivery.price.total(price.delivery);
                        OrderComponent.set.basket.price.total(price.basket);

                        // Обновим данные на итоговом шаге
                        if (
                            price.delivery === 0 &&
                            (
                                deliveryCode === 'without-delivery' ||
                                deliveryCode === 'russian-post-free' ||
                                deliveryCode === 'boxberry-point-free' ||
                                deliveryCode === 'pickup-nsk' ||
                                deliveryCode === 'courier-nsk' ||
                                deliveryCode === 'courier-nsk-free' ||
                                deliveryCode === 'courier-berdsk-free'
                            )
                        ) {
                            OrderComponent.get.form.data('total').elements['deliveryPrice'].value = 'БЕСПЛАТНО'
                        } else if (OrderComponent.delivery.isRefine(deliveryCode)) {
                            OrderComponent.get.form.data('total').elements['deliveryPrice'].value = 'УТОЧНЯЕТСЯ'
                        } else {
                            OrderComponent.get.form.data('total').elements['deliveryPrice'].value = App.util.format.price.withCurrency(price.delivery)
                        }
                        OrderComponent.get.form.data('total').elements['deliveryPrice'].parentNode.parentNode.parentNode.classList.remove('invalid')

                        // Сохраним данные в локальную память устройства
                        OrderComponent.set.storage.data('delivery', deliveryCode)
                    }
                }

                let balloonContent = 'Получены стоимость и срок доставки, до индекса: '
                let balloonType = 'success'
                if (hasPrices !== true) {
                    balloonContent = 'Не удалось получить стоимость доставки до индекса: '
                    balloonType = 'error'
                }
                balloonContent += request.location.zip
                OrderComponent.message.send({content: balloonContent, type: balloonType, autoHideDelay: 5000})

            }).catch(function (error) {
            if (error.hasOwnProperty('status') && error.status === 'error') {
                OrderComponent.message.send({
                    content: 'Не удалось получить стоимость доставки до индекса: ' + request.location.zip,
                    type: 'error',
                    autoHideDelay: 5000
                })
            }
        })
    }
}

// Оплата
OrderComponent.payment = {
    /**
     * Включить/выбрать платежную систему
     * Включает стоимость доставки в итоговоу сумму
     * @param code
     */
    activate: function (code) {

        OrderComponent.set.payment.current(code);
        OrderComponent.payment.description.list.reload();
        OrderComponent.set.payment.list.valid();

        const payment = OrderComponent.get.payment.data(code)

        if (payment) {
            OrderComponent.message.send({
                content: 'Активирован способ оплаты: ' + payment['NAME'],
                type: 'success',
            });
        }
    },

    deactivate: function (code) {
        if (OrderComponent.get.payment.switcher(code).checked === false) return

        OrderComponent.set.payment.current(false)
        OrderComponent.payment.description.list.reload();
        OrderComponent.set.payment.list.invalid();

        // Отменим активность выбранной службы доставки
        OrderComponent.get.payment.switcher(code).checked = false;
    },
    show: function (code) {
        if (!OrderComponent.get.payment.node(code)) return
        OrderComponent.get.payment.node(code).setAttribute('data-visibility', 'Y');
    },
    hide: function (code) {
        if (!OrderComponent.get.payment.node(code)) return
        OrderComponent.get.payment.node(code).setAttribute('data-visibility', 'N');
    },

    list: {
        // Обновим список платежных способов согласно
        reload: function () {
            const country = OrderComponent.get.delivery.location.country.current();
            let city = OrderComponent.get.delivery.location.city.current();
            const list = OrderComponent.get.payment.list.data();

            city = city.toLowerCase().replace('г.', city).replace('г ', city).trim()

            let show = false // показывать ли список

            // Проверим ограничения у способов оплат
            for (let code in list) {

                if (!list.hasOwnProperty(code)) continue;

                const item = list[code];

                // Проверим ограничения для способов оплат
                if (!item.hasOwnProperty('restriction')) {
                    show = true
                    continue
                }

                if (
                    // Если имеется ограничение по стране
                    (
                        item['restriction'].hasOwnProperty('country') &&
                        (
                            (
                                item['restriction']['country'].hasOwnProperty(country) === true &&
                                item['restriction']['country'][country]['access'] !== true
                            )
                            ||
                            item['restriction']['country'].hasOwnProperty(country) === false
                        )
                    )
                    ||
                    // Если имеется ограничение по городу
                    (
                        ( // Проверим запрет службы в городе
                            item['restriction'].hasOwnProperty('city-deny')
                            &&
                            item['restriction']['city-deny'].hasOwnProperty(city)
                            &&
                            item['restriction']['city-deny'][city] === true // служба запрещена в городе
                        )
                        ||
                        ( // Проверим активность службы в разрещенных городах
                            item['restriction'].hasOwnProperty('city')
                            &&
                            (
                                !item['restriction']['city'].hasOwnProperty(city)
                                ||
                                item['restriction']['city'][city] !== true // служба неактивна в разрешенном городе
                            )
                        )
                    )
                    ||
                    // Если имеется ограничение по доставке
                    (
                        OrderComponent.get.delivery.current() &&
                        item['restriction'].hasOwnProperty('delivery') &&
                        item['restriction']['delivery'].indexOf(OrderComponent.get.delivery.current()) === -1 // служба доставки не найдена
                    )
                ) {
                    OrderComponent.payment.hide(item['CODE'])
                    OrderComponent.payment.deactivate(item['CODE'])
                    continue;
                }

                // Если все условия обработаны и ничего не запрешает отобразить способ оплаты
                // Тогда отобразим способ оплаты
                OrderComponent.payment.show(item['CODE']);

                show = true;
            }

            // Если нет ни одного способа оплаты для отображения
            // Тогда отобразим уведомление об этом
            if (show === false) {
                OrderComponent.payment.list.empty.show()
            } else {
                OrderComponent.payment.list.empty.hide()
            }
        },

        // Текст-пояснение для случая, когда отсутствует какой-либо способ
        empty: {
            show: function () {
                OrderComponent.get.payment.list.empty.node().setAttribute('data-visibility', 'Y')
            },
            hide: function () {
                OrderComponent.get.payment.list.empty.node().setAttribute('data-visibility', 'N')
            },
        },
    },

    /**
     * Описание платежных систем
     */
    description: {
        /**
         * Список описаний платежных систем
         */
        list: {
            // Спрячем все описания, но отобразим только для текущей платежной системы
            reload: function () {
                if (OrderComponent.get.payment.description.list.node().length === 0) return
                for (const node of OrderComponent.get.payment.description.list.node()) {
                    if (node.dataset.paymentCode === OrderComponent.get.payment.current()) {
                        node.setAttribute('data-visibility', 'Y')
                    } else {
                        node.setAttribute('data-visibility', 'N')
                    }
                }
            },
        },
    },
}

// Для мобильных
OrderComponent.mobile = {
    panel: {
        expand: function () {
            OrderComponent.mobile.panel.collapsed.hide();
            OrderComponent.mobile.panel.expanded.show();
            // Настроим свойства панели, чтобы она полностью входила в размеры окна
            if (OrderComponent.get.mobile.node.panel.wrapper().offsetHeight > window.innerHeight) {
                OrderComponent.get.mobile.node.panel.wrapper().style.height = window.innerHeight + 'px';
                OrderComponent.get.mobile.node.panel.wrapper().style.overflowY = 'scroll';
            } else {
                OrderComponent.get.mobile.node.panel.wrapper().style.height = 'auto';
                OrderComponent.get.mobile.node.panel.wrapper().style.overflowY = 'auto';
            }
        },
        collapse: function () {
            OrderComponent.mobile.panel.expanded.hide();
            OrderComponent.mobile.panel.collapsed.show();
            // Настроим свойства панели, чтобы она полностью входила в размеры окна
            if (OrderComponent.get.mobile.node.panel.wrapper().offsetHeight > window.innerHeight) {
                OrderComponent.get.mobile.node.panel.wrapper().style.height = window.innerHeight + 'px';
                OrderComponent.get.mobile.node.panel.wrapper().style.overflowY = 'scroll';
            } else {
                OrderComponent.get.mobile.node.panel.wrapper().style.height = 'auto';
                OrderComponent.get.mobile.node.panel.wrapper().style.overflowY = 'auto';
            }
        },
        collapsed: {
            show: function () {
                OrderComponent.get.mobile.node.panel.collapsed().setAttribute('data-visibility', 'Y');
            },
            hide: function () {
                OrderComponent.get.mobile.node.panel.collapsed().setAttribute('data-visibility', 'N');
            },
        },
        expanded: {
            show: function () {
                OrderComponent.get.mobile.node.panel.expanded().setAttribute('data-visibility', 'Y');
            },
            hide: function () {
                OrderComponent.get.mobile.node.panel.expanded().setAttribute('data-visibility', 'N');
            },
        },
    }
}

// Ошибки
OrderComponent.error = {
    check: function (step) {
        const fields = OrderComponent.get.form.data(step).elements;

        if (!fields || fields.length === 0) return true;

        let check = true; // пройдена ли проверка ошибок

        if (step !== 'total') {
            let skipAddressFields = false; // пропустить поля адреса при проверке ошибок

            if (
                OrderComponent.get.delivery.current() === 'boxberry-point' ||
                OrderComponent.get.delivery.current() === 'boxberry-point-free' ||
                OrderComponent.get.delivery.current() === 'cdek-store-to-store' ||
                OrderComponent.get.delivery.current() === 'pickup-nsk' ||
                OrderComponent.get.delivery.current() === 'courier-nsk' ||
                OrderComponent.get.delivery.current() === 'courier-nsk-free' ||
                OrderComponent.get.delivery.current() === 'courier-berdsk-free' ||
                OrderComponent.get.delivery.current() === 'pickup-msk-novoslobodskaya'
            ) {
                skipAddressFields = true
            }

            if (OrderComponent.delivery.isChecked('boxberry-point')) {
                OrderComponent.set.storage.data('delivery', 'boxberry-point')
            } else if (OrderComponent.delivery.isChecked('boxberry-point-free')) {
                OrderComponent.set.storage.data('delivery', 'boxberry-point-free')
            } else if (OrderComponent.delivery.isChecked('cdek-store-to-store')) {
                OrderComponent.set.storage.data('delivery', 'cdek-store-to-store')
            }

            if (
                OrderComponent.get.step.current() === 'delivery' &&
                (
                    !OrderComponent.get.delivery.current()
                    ||
                    (
                        OrderComponent.get.delivery.current() !== 'without-delivery' &&
                        OrderComponent.get.delivery.current() !== 'russian-post-free' &&
                        OrderComponent.get.delivery.current() !== 'pickup-nsk' &&
                        OrderComponent.get.delivery.current() !== 'courier-nsk' &&
                        OrderComponent.get.delivery.current() !== 'courier-nsk-free' &&
                        OrderComponent.get.delivery.current() !== 'courier-berdsk-free' &&
                        OrderComponent.get.delivery.current() !== 'pickup-msk-novoslobodskaya' &&
                        OrderComponent.get.delivery.current() !== 'boxberry-point-free' &&
                        OrderComponent.get.delivery.current() !== 'courier-msk-inside-mkad-free' &&
                        OrderComponent.get.delivery.current() !== 'courier-msk-outside-mkad' &&
                        (
                            OrderComponent.delivery.isFree('cdek-store-to-store') &&
                            OrderComponent.get.delivery.current() !== 'cdek-store-to-store'
                        )
                        &&
                        !OrderComponent.get.delivery.price.total.value()
                    )
                )
            ) {
                check = false
                OrderComponent.get.delivery.list.node().classList.add('invalid')
                if (
                    OrderComponent.get.delivery.current() !== 'boxberry-point' &&
                    OrderComponent.get.delivery.current() !== 'boxberry-point-free' &&
                    OrderComponent.get.delivery.current() !== 'cdek-store-to-store'
                ) {
                    OrderComponent.delivery.deactivate(OrderComponent.get.delivery.current())
                }
            }

            for (const field of fields) {

                if (skipAddressFields === true && (/*field.name === 'zip' ||*/ field.name === 'street' || field.name === 'building' || field.name === 'room')) {
                    /*if (field.name === 'zip') {
                        field.style.borderColor = '#8bc34a'; // status ok
                    }*/
                    continue
                }

                const customerType = field.dataset['customerType'] || false

                if (customerType !== false && customerType !== OrderComponent.get.customer.type.current()) {
                    continue
                }

                switch (field.type) {
                    case 'email':
                        if (field.required === true) {
                            /*if (
                                (field.name === 'email' && OrderComponent.get.form.data(step).elements['confirmEmail'] && field.value !== OrderComponent.get.form.data(step).elements['confirmEmail'].value) ||
                                (field.name === 'confirmEmail' && field.value !== OrderComponent.get.form.data(step).elements['email'].value)
                            ) {
                                check = false;
                                OrderComponent.get.form.data(step).elements['email'].classList.add('invalid');
                                OrderComponent.get.form.data(step).elements['confirmEmail'].classList.add('invalid');
                                break
                            }*/
                            const reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
                            if (field.value === '' || field.value.indexOf('@') === -1 || reg.test(field.value) === false) {
                                check = false;
                                field.classList.add('invalid');
                                break
                            }
                            OrderComponent.get.form.data(step).elements['email'].classList.remove('invalid');
                            /*if (OrderComponent.get.form.data(step).elements['confirmEmail']) {
                                OrderComponent.get.form.data(step).elements['confirmEmail'].classList.remove('invalid');
                            }*/
                        }
                        break
                    case 'text':
                        if (field.required === true) {
                            if (field.name === 'phone') {
                                const number = OrderComponent.get.phone.clear(field.value)
                                if (number.indexOf('+') !== -1 && number.length < 12) {
                                    check = false
                                    field.style.borderColor = 'red'  // status error
                                } else if (number.length < 11) {
                                    check = false
                                    field.style.borderColor = 'red'  // status error
                                }
                            } else if (field.name === 'zip' && field.value.length < 5) {
                                check = false
                                field.style.borderColor = 'red'  // status error
                            } else if (field.value === '') {
                                check = false
                                field.classList.add('invalid')
                            }
                        }
                        break
                    case 'checkbox':
                        if (field.required === true && field.checked !== true) {
                            check = false
                            field.classList.add('invalid')
                        }
                        break
                    case 'radio':
                        if (field.name === 'delivery') {
                            if (OrderComponent.get.delivery.list.node() && OrderComponent.get.delivery.list.node().dataset.visibility === 'Y') {
                                if (
                                    (
                                        field.checked === true &&
                                        !OrderComponent.delivery.isFree(OrderComponent.get.delivery.current()) &&
                                        OrderComponent.get.delivery.price.total.value() <= 0
                                    )
                                    ||
                                    (
                                        field.checked !== true && !OrderComponent.get.delivery.list.isValid()
                                    )
                                    ||
                                    (
                                        OrderComponent.get.delivery.current() === 'boxberry-point' &&
                                        OrderComponent.get.delivery.calculated('boxberry-point') === false
                                    )
                                    ||
                                    (
                                        OrderComponent.get.delivery.current() === 'boxberry-point-free' &&
                                        OrderComponent.get.delivery.calculated('boxberry-point-free') === false
                                    )
                                    ||
                                    (
                                        OrderComponent.get.delivery.current() === 'cdek-store-to-store' &&
                                        OrderComponent.get.delivery.calculated('cdek-store-to-store') === false
                                    )
                                ) {
                                    check = false
                                    OrderComponent.set.delivery.list.invalid()
                                } else {
                                    OrderComponent.set.delivery.list.valid()
                                }
                            }

                        } else if (field.name === 'payment') {
                            if (field.checked !== true && !OrderComponent.get.payment.list.isValid()) {
                                check = false
                                OrderComponent.set.payment.list.invalid()
                            } else {
                                OrderComponent.set.payment.list.valid()
                            }
                        }
                        break;
                    case 'select-one':
                        if (field.required === true && field.value === '') {
                            check = false
                            field.classList.add('invalid')
                        }
                        break
                    default:
                        break
                }
            }
            if (step === 'delivery') {
                OrderComponent.delivery.location.show()
            }
        }
        // Шаг ИТОГО обработаем иначе
        else {
            for (const field of fields) {
                if (field.type === 'fieldset' || field.name === 'comment' || (field.value && field.value !== '...' && typeof field.value !== 'undefined')) continue
                check = false
                field.parentNode.parentNode.parentNode.classList.add('invalid')
            }
        }

        if (check === false) {
            OrderComponent.message.send({
                content: 'Недостаточно данных',
                type: 'error',
                autoHideDelay: 4000
            })
        }

        if (step !== 'customer') {
            let currentCity = OrderComponent.get.storage.data('city')
            if (currentCity) {
                currentCity = currentCity.toLowerCase()
                if (currentCity === 'москва' && BX.getCookie(OrderComponent.location.cookie()) !== 'MSK') {
                    check = false
                    if (window.confirm("В качестве города Вы указали Москву, но установленный регион на сайте отличается.\nПри продолжении действия, будет обновлена страница и установлен соответствующий регион.\nПродолжить?")) {
                        BX.setCookie(OrderComponent.location.cookie(), 'MSK', {
                            expires: 864000000,
                            path: '/'
                        })
                        window.location.reload()
                    }
                }
            }
        }

        return check
    },
}

// Системные сообщения
OrderComponent.message = {
    send: function (params) {
        params = params || {};
        params.type = params.type || 'wait'
        params.content = params.content || 'Нет текста'

        if (params.content === 'Нет текста') {
            params.type = 'error'
        }

        if (params.type === 'wait') {
            params.content = '<i class="fas fa-exclamation-circle" style="color:rgb(80, 141, 233)"></i>&nbsp;&nbsp;' + params.content
        } else if (params.type === 'success') {
            params.content = '<i class="fas fa-check-circle" style="color:#8bc34a"></i>&nbsp;&nbsp;' + params.content
        } else if (params.type === 'error') {
            params.content = '<i class="fas fa-exclamation-circle" style="color:rgb(227, 85, 85)"></i>&nbsp;&nbsp;' + params.content
        }

        BX.UI.Notification.Center.notify({
            content: params.content,
            autoHideDelay: params.autoHideDelay || 3000,
            autoHide: params.autoHide || true,
            category: params.category || '',
        });
    }
}

// Контроллеры
OrderComponent.controller.setStep = function () {
    const index = {
        current: false,
        previous: false
    };
    index.current = OrderComponent.get.step.list().indexOf(OrderComponent.get.step.current());
    index.previous = index.current - 1;

    if (
        OrderComponent.get.step.current() === this.dataset.code ||
        (
            OrderComponent.get.step.current() !== 'total' &&
            !OrderComponent.error.check(OrderComponent.get.step.current()) &&
            OrderComponent.get.step.list()[index.previous] !== this.dataset.code
        )
    ) return

    OrderComponent.step.activate(this.dataset.code);
    OrderComponent.product.list.hide();
    OrderComponent.mobile.panel.collapse();
}
OrderComponent.controller.nextStep = function () {
    if (!OrderComponent.error.check(OrderComponent.get.step.current())) return
    const index = {
        current: false,
        next: false
    }
    index.current = OrderComponent.get.step.list().indexOf(OrderComponent.get.step.current())
    index.next = index.current + 1;
    if (typeof OrderComponent.get.step.list()[index.next] === 'undefined') return false
    OrderComponent.step.activate(OrderComponent.get.step.list()[index.next])
    OrderComponent.product.list.hide()
    OrderComponent.mobile.panel.collapse()
}
OrderComponent.controller.previousStep = function () {
    const index = {
        current: false,
        previous: false
    };
    index.current = OrderComponent.get.step.list().indexOf(OrderComponent.get.step.current());
    index.previous = index.current - 1;
    if (typeof OrderComponent.get.step.list()[index.previous] === 'undefined') return false;
    OrderComponent.step.activate(OrderComponent.get.step.list()[index.previous]);
    OrderComponent.product.list.hide();
    OrderComponent.mobile.panel.collapse();
}
OrderComponent.controller.goToCatalog = function () {
    window.location.href = OrderComponent.get.param('path')['catalog'];
}
OrderComponent.controller.goToBasket = function () {
    window.location.href = OrderComponent.get.param('path')['basket'];
}
OrderComponent.controller.changeVisibilityFullProductList = function () {
    if (OrderComponent.get.product.list().dataset.visibility === 'N') {
        OrderComponent.product.list.show();
        if (OrderComponent.get.sidebar.node().offsetHeight > (window.innerHeight - 100)) {
            OrderComponent.get.sidebar.node().classList.remove('fixed');
        }
    } else {
        OrderComponent.product.list.hide();
    }
}
OrderComponent.controller.expandMobilePanel = function () {
    OrderComponent.mobile.panel.expand()
}
OrderComponent.controller.collapseMobilePanel = function () {
    OrderComponent.mobile.panel.collapse();
}
OrderComponent.controller.order = function () {

    // Проверим заполненость обязательных данных
    if (!OrderComponent.error.check('total')) return

    if (OrderComponent.isCreatingOrder === true) {
        OrderComponent.message.send({
            content: 'Ожидайте, заказ еще формируется',
        })
        return
    }

    OrderComponent.isCreatingOrder = true;

    OrderComponent.message.send({
        content: 'Ожидайте, формируем заказ',
    })

    const data = document.getElementById('order-component-data')

    data.style.opacity = '0.3'

    const request = {
        order: {
            type: OrderComponent.get.param('order')['type'],
        },
        customer: {
            type: OrderComponent.get.storage.data('customerType'),
            name: OrderComponent.get.storage.data('name'),
            lastName: OrderComponent.get.storage.data('lastName'),
            secondName: OrderComponent.get.storage.data('secondName'),
            companyName: OrderComponent.get.storage.data('companyName'),
            companyAddress: OrderComponent.get.storage.data('companyAddress'),
            inn: OrderComponent.get.storage.data('inn'),
            kpp: OrderComponent.get.storage.data('kpp'),
        },
        contacts: {
            email: OrderComponent.get.storage.data('email') === OrderComponent.get.form.data('total').elements['email'].value ? OrderComponent.get.storage.data('email') : OrderComponent.get.form.data('total').elements['email'].value,
            phone: OrderComponent.get.storage.data('phone'),
        },
        location: {
            country: {
                code: OrderComponent.get.storage.data('countryCode'),
            },
            zip: OrderComponent.get.storage.data('zip'),
            city: OrderComponent.get.storage.data('city'),
            street: OrderComponent.get.storage.data('street'),
            building: OrderComponent.get.storage.data('building'),
            room: OrderComponent.get.storage.data('room'),
        },
        delivery: {
            id: OrderComponent.get.delivery.data(OrderComponent.get.storage.data('delivery'))['ID'],
            code: OrderComponent.get.delivery.data(OrderComponent.get.storage.data('delivery'))['CODE'],
            parcels: OrderComponent.get.delivery.data(OrderComponent.get.storage.data('delivery'))['calculated']['parcels'] || [],
            price: OrderComponent.get.cache('delivery-price'),
            period: OrderComponent.get.delivery.data(OrderComponent.get.storage.data('delivery'))['calculated']['period'] || [],
        },
        payment: {
            id: OrderComponent.get.payment.data(OrderComponent.get.storage.data('payment'))['ID'],
            code: OrderComponent.get.payment.data(OrderComponent.get.storage.data('payment'))['CODE'],
        },
        boxberry: {
            point: {
                id: OrderComponent.get.storage.data('boxberryPointId'),
                address: OrderComponent.get.storage.data('boxberryPointAddress'),
            }
        },
        cdek: {
            point: {
                id: OrderComponent.get.storage.data('cdekPointId'),
                address: OrderComponent.get.storage.data('cdekPointAddress'),
            }
        },
        agreement: {
            processingPersonalData: OrderComponent.get.storage.data('agreementProcessingPersonalData'),
            subscribe: OrderComponent.get.storage.data('agreementSubscribe') || false
        },
        comment: OrderComponent.get.form.data('total')['elements']['comment'] ? OrderComponent.get.form.data('total')['elements']['comment'].value : ''
    }

    /*if (OrderComponent.params['userId'] === '101225') {
        pr(request['delivery'])
        return
    }*/

    BX.ajax.runComponentAction(
        OrderComponent.get.param('component'),
        OrderComponent.get.param('order')['create']['method'],
        {
            mode: 'class',
            data: {
                request: request
            },
            timeout: 30
        })
        .then(function (response) {
            response = response.data

            if (response.hasOwnProperty('error')) {
                data.style.opacity = '1'
                if (response.hasOwnProperty('message')) {
                    OrderComponent.message.send({
                        content: response.message,
                        type: 'error',
                    });
                }
                OrderComponent.isCreatingOrder = false
                return
            }

            if (response.hasOwnProperty('success')) {

                // Отправим информацию в яндекс метрику
                dataLayer.push({
                    ecommerce: {
                        currencyCode: OrderComponent.get.param('yandex')['currencyCode'],
                        purchase: {
                            actionField: {
                                id: response['orderId'],
                                coupon: OrderComponent.get.param('yandex')['coupon'],
                                goal_id: '138047974', // достижение цели - Заказ оформлен
                            },
                            products: OrderComponent.get.param('yandex')['products']
                        }
                    }
                })

                if (response.hasOwnProperty('redirect')) {
                    if (response.hasOwnProperty('message')) {
                        OrderComponent.message.send({
                            content: response.message,
                            type: 'success',
                        });
                    }
                    //window.location.replace(response.redirect)
                    setTimeout(function () {
                        window.location.replace(response.redirect)
                    }, 20)
                    return
                }

                //data.style.opacity = '1'

                // Скроем основной контент и отобразим финальный контент
                const complete = document.getElementById('order-component-complete')
                let payment

                if (request.payment.code === 'bill') {
                    payment = document.getElementById('order-payment-bill')
                    payment.innerHTML = payment.innerHTML.replace('#USER_EMAIL#', request.contacts.email)
                } else if (request.payment.code === 'in-store') {
                    payment = document.getElementById('order-payment-in-store')
                    payment.innerHTML = payment.innerHTML.replace('#ORDER_NUMBER#', response['orderId']).replace('#USER_EMAIL#', request.contacts.email)
                } else {
                    // не используется, так как при оплате картой, редирект идет сразу на страницу банка
                    payment = document.getElementById('order-payment-online')
                    payment.innerHTML = payment.innerHTML.replace('#ORDER_NUMBER#', response['orderId'])
                }

                data.style.display = 'none'
                payment.style.display = 'block'
                complete.style.display = 'block'

                if (response.hasOwnProperty('message')) {
                    OrderComponent.message.send({
                        content: response.message,
                        type: 'success',
                    });
                }

                return
            }

            data.style.opacity = '1'

            OrderComponent.message.send({
                content: 'Не удалось оформить заказ',
                type: 'error',
            });

            OrderComponent.isCreatingOrder = false

        }).catch(function (error) {
        if (error.hasOwnProperty('status') && error.status === 'error') {
            data.style.opacity = '1'
            OrderComponent.message.send({
                content: 'Не удалось оформить заказ',
                type: 'error',
            });
            OrderComponent.isCreatingOrder = false
        }
    });
}

// Геттеры
OrderComponent.get.cookie = function (code) {
    const matches = document.cookie.match(new RegExp(
        "(?:^|; )" + code.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
    ));
    return matches ? decodeURIComponent(matches[1]) : false;
}
OrderComponent.get.param = function (code) {
    return OrderComponent.params[code] || false
}
OrderComponent.get.cache = function (code) {
    return OrderComponent.cache[code] || false;
}
OrderComponent.get.storage = {
    code: function () {
        return OrderComponent.get.param('storage')['code'];
    },
    data: function (code) {
        return OrderComponent.storage.data.hasOwnProperty(code) ? OrderComponent.storage.data[code] : false;
    },
    list: function () {
        return OrderComponent.storage.data
    },
}
OrderComponent.get.sidebar = {
    node: function () {
        if (!OrderComponent.get.cache('sidebar')) {
            OrderComponent.set.cache('sidebar', document.querySelector('.order-sidebar'));
        }
        return OrderComponent.get.cache('sidebar');
    },
};
OrderComponent.get.form = {
    data: function (code) {
        if (!OrderComponent.get.param('form')[code] || typeof OrderComponent.get.param('form')[code] === 'undefined') return false
        const formObject = OrderComponent.get.param('form')[code]
        if (typeof formObject['code'] === 'undefined') return false
        return document.forms[formObject['code']] || false
    },
    code: function (code) {
        return OrderComponent.get.param('form').hasOwnProperty(code) ? OrderComponent.get.param('form')[code]['code'] : false;
    }
};
OrderComponent.get.step = {
    current: function () {
        return OrderComponent.get.param('step')['current'];
    },
    initial: function () {
        return OrderComponent.get.param('step')['initial'];
    },
    final: function () {
        return OrderComponent.get.param('step')['final'];
    },
    list: function () {
        return OrderComponent.get.param('step')['list'];
    },
    node: {
        marker: {
            list: function () {
                if (!OrderComponent.get.cache('step-node-marker-list')) {
                    OrderComponent.set.cache('step-node-marker-list', document.querySelectorAll('.order-steps .order-step[data-code]'));
                }
                return OrderComponent.get.cache('step-node-marker-list');
            }
        },
        data: {
            list: function () {
                if (!OrderComponent.get.cache('step-node-data-list')) {
                    OrderComponent.set.cache('step-node-data-list', document.querySelectorAll('.order-content .order-data[data-code]'))
                }
                return OrderComponent.get.cache('step-node-data-list')
            }
        }
    },
};
OrderComponent.get.product = {
    list: function () {
        if (!OrderComponent.get.cache('products-full-list')) {
            OrderComponent.set.cache('products-full-list', document.querySelector('.order-sidebar-total-products-full-list[data-visibility]'))
        }
        return OrderComponent.get.cache('products-full-list') || false
    },
    link: function () {
        if (!OrderComponent.get.cache('products-change-visibility')) {
            OrderComponent.set.cache('products-change-visibility', document.querySelector('.order-sidebar-total-products-change-visibility'))
        }
        return OrderComponent.get.cache('products-change-visibility') || false
    }
};
OrderComponent.get.customer = {
    type: {
        current: function () {
            return OrderComponent.get.form.data('customer').elements['customerType'].value
        },
        personal: function () {
            return OrderComponent.get.param('form')['customer']['type']['personal']
        },
        legal: function () {
            return OrderComponent.get.param('form')['customer']['type']['legal']
        }
    },
};
OrderComponent.get.phone = {
    /**
     * Метод очищает номер телефона от лишних символов
     * @param number
     * @returns {string}
     */
    clear: function (number) {
        return number.split(' ').join('').replace(/[.?*^$[\]\\(){}|-]/g, '')
    },
};
OrderComponent.get.delivery = {

    current: function () {
        // Проверим сначала локальную память - быть может в ней имеется последняя выбранная служба доставки
        // Если в локальной памяти службы нет, тогда прооверим информацию по последнему заказу
        return OrderComponent.get.storage.data('delivery') /*|| OrderComponent.get.param('last')['delivery']['code']*/ || false
    },

    node: function (code) {
        if (!OrderComponent.get.cache('delivery-node-' + code)) {
            OrderComponent.set.cache('delivery-node-' + code, document.querySelector('.' + OrderComponent.get.form.code('delivery') + '-list .delivery[data-code="' + code + '"]'))
        }
        return OrderComponent.get.cache('delivery-node-' + code);
    },

    data: function (code) {
        if (OrderComponent.get.delivery.list.data()[code]) {
            OrderComponent.get.delivery.list.data()[code]['calculated'] = OrderComponent.get.delivery.calculated(code);
            return OrderComponent.get.delivery.list.data()[code]
        }
        return false
    },

    restriction: function (code) {
        return OrderComponent.get.param('delivery')['list'].hasOwnProperty(code) && OrderComponent.get.param('delivery')['list'][code].hasOwnProperty('restriction') ? OrderComponent.get.param('delivery')['list'][code]['restriction'] : false;
    },

    name: {
        node: function (code) {
            if (!OrderComponent.get.cache('delivery-name-node-' + code)) {
                if (OrderComponent.get.delivery.node(code)) {
                    OrderComponent.set.cache('delivery-name-node-' + code, OrderComponent.get.delivery.node(code).querySelector('.delivery-title'));
                } else {
                    OrderComponent.set.cache('delivery-name-node-' + code, false)
                }
            }
            return OrderComponent.get.cache('delivery-name-node-' + code);
        },
        value: function (code) {
            return OrderComponent.get.delivery.data(code)['NAME'];
        },
    },

    price: {
        node: function (code) {
            if (!OrderComponent.get.cache('delivery-price-node-' + code)) {
                OrderComponent.set.cache('delivery-price-node-' + code, OrderComponent.get.delivery.node(code).querySelector('.delivery-price'));
            }
            return OrderComponent.get.cache('delivery-price-node-' + code);
        },
        // Сумма доставки в сайдбаре
        total: {
            node: function () {
                if (!OrderComponent.get.cache('delivery-total-price')) {
                    OrderComponent.set.cache('delivery-total-price', document.querySelector('.order-sidebar-delivery-price'));
                }
                return OrderComponent.get.cache('delivery-total-price');
            },
            value: function () {
                return OrderComponent.get.cache('delivery-price') > 0 ? OrderComponent.get.cache('delivery-price') : 0
            },
        },
    },

    description: {
        node: function (code) {
            if (!OrderComponent.get.cache('delivery-description-node-' + code)) {
                OrderComponent.set.cache('delivery-description-node-' + code, OrderComponent.get.delivery.node(code).querySelector('.delivery-description'));
            }
            return OrderComponent.get.cache('delivery-description-node-' + code);
        },
        value: function (code) {
            return OrderComponent.get.delivery.data(code)['DESCRIPTION'];
        },
    },

    list: {
        node: function () {
            if (!OrderComponent.get.cache('deliveries')) {
                OrderComponent.set.cache('deliveries', document.querySelector('.' + OrderComponent.get.form.code('delivery') + '-list[data-visibility]'));
            }
            return OrderComponent.get.cache('deliveries');
        },
        isValid: function () {
            return OrderComponent.get.delivery.list.node().classList.contains('valid')
        },
        data: function () {
            return OrderComponent.get.param('delivery')['list']
        },
        // Список актуальных способов доставки
        current: function () {
            const nodes = OrderComponent.get.delivery.list.node().querySelectorAll('.delivery[data-code][data-visibility="Y"]');
            const list = [];
            for (const node of nodes) {
                list.push(node.dataset.code);
            }
            return list;
        },
        empty: {
            node: function () {
                if (!OrderComponent.get.cache('deliveries-empty')) {
                    OrderComponent.set.cache('deliveries-empty', document.querySelector('.deliveries-empty[data-visibility]'));
                }
                return OrderComponent.get.cache('deliveries-empty');
            },
        },
    },

    location: {
        list: {
            node: function () {
                if (!OrderComponent.get.cache('delivery-address-fields')) {
                    OrderComponent.set.cache('delivery-address-fields', document.querySelector('.' + OrderComponent.get.form.code('delivery') + '-address[data-visibility]'));
                }
                return OrderComponent.get.cache('delivery-address-fields');
            },
        },
        fullAddress: {
            node: function () {
                if (!OrderComponent.get.cache('fullAddress')) {
                    OrderComponent.set.cache('fullAddress', document.getElementById('fullAddress'))
                }
                return OrderComponent.get.cache('fullAddress')
            },
        },
        country: {
            current: function () {
                return OrderComponent.get.form.data('delivery').elements['countryCode'].value;
            },
        },
        zip: {
            current: function () {
                return OrderComponent.get.form.data('delivery').elements['zip'].value;
            },
        },
        city: {
            current: function () {
                return OrderComponent.get.form.data('delivery').elements['city'].value;
            },
        },
    },

    switcher: function (code) {
        if (!OrderComponent.get.cache('delivery-' + code + '-active-node')) {
            OrderComponent.set.cache('delivery-' + code + '-active-node', document.getElementById('delivery-' + code));
        }
        return OrderComponent.get.cache('delivery-' + code + '-active-node');
    },

    // Получение рассчитанных данных для службы доставки
    calculated: function (code) {
        // Если служба доставки бесплатная, тогда возращаем иные данные
        if (code !== 'boxberry-point-free' && OrderComponent.delivery.isFree(code)) {
            if (
                code === 'cdek-store-to-store'
            ) {
                if (typeof OrderComponent.get.delivery.list.data()[code]['calculated'] !== 'object') {
                    OrderComponent.get.delivery.list.data()[code]['calculated'] = false
                }
            } else {
                OrderComponent.get.delivery.list.data()[code]['calculated'] = {
                    price: {
                        value: 0,
                    }
                }
            }

            /*if (code === 'boxberry-point-free') {
                if (!OrderComponent.get.delivery.list.data()[code]['calculated'].hasOwnProperty('city')) {
                    OrderComponent.get.delivery.list.data()[code]['calculated']['city'] = {
                        name: false
                    }
                }
            }*/

            return OrderComponent.get.delivery.list.data()[code]['calculated']
        }
        // Если данные по калькуляции уже имеются, тогда сразу возвращаем
        if (OrderComponent.get.delivery.list.data()[code].hasOwnProperty('calculated')) {
            return OrderComponent.get.delivery.list.data()[code]['calculated']
        }
        // В ином случае, будем подгружать данные из сервисов
        return false
    }
};
OrderComponent.get.payment = {
    current: function () {
        return OrderComponent.get.storage.data('payment') || false
    },

    node: function (code) {
        if (!OrderComponent.get.cache('payment-node-' + code)) {
            OrderComponent.set.cache('payment-node-' + code, document.querySelector('.' + OrderComponent.get.form.code('payment') + '-list .payment[data-code="' + code + '"]'));
        }
        return OrderComponent.get.cache('payment-node-' + code);
    },

    data: function (code) {
        return OrderComponent.get.payment.list.data()[code] || false
    },

    list: {
        node: function () {
            if (!OrderComponent.get.cache('payment-list-title')) {
                OrderComponent.set.cache('payment-list-title', document.querySelector('.payment-list-title'));
            }
            return OrderComponent.get.cache('payment-list-title');
        },
        isValid: function () {
            return OrderComponent.get.payment.list.node().dataset.valid === 'Y'
        },
        data: function () {
            return OrderComponent.get.param('payment')['list']
        },
        empty: {
            node: function () {
                if (!OrderComponent.get.cache('payments-empty')) {
                    OrderComponent.set.cache('payments-empty', document.querySelector('.payments-empty[data-visibility]'));
                }
                return OrderComponent.get.cache('payments-empty');
            },
        },
    },

    description: {
        list: {
            node: function () {
                if (!OrderComponent.get.cache('order-payment-description-list')) {
                    OrderComponent.set.cache('order-payment-description-list', document.querySelectorAll('.order-payment-description-list .payment-description'))
                }
                return OrderComponent.get.cache('order-payment-description-list')
            }
        },
    },

    switcher: function (code) {
        if (!OrderComponent.get.cache('payment-' + code + '-active-node')) {
            OrderComponent.set.cache('payment-' + code + '-active-node', document.getElementById('payment-' + code));
        }
        return OrderComponent.get.cache('payment-' + code + '-active-node');
    },
}
OrderComponent.get.basket = {
    node: {
        price: {
            total: function () {
                if (!OrderComponent.get.cache('order-sidebar-basket-total-price')) {
                    OrderComponent.set.cache('order-sidebar-basket-total-price', OrderComponent.get.sidebar.node().querySelector('.order-sidebar-basket-total-price'));
                }
                return OrderComponent.get.cache('order-sidebar-basket-total-price');
            },
        }
    },
    price: {
        total: function () {
            return parseFloat(OrderComponent.get.param('basket')['amount']);
        }
    }
}
OrderComponent.get.mobile = {
    node: {
        panel: {
            wrapper: function () {
                if (!OrderComponent.get.cache('order-component-mobile-panel')) {
                    OrderComponent.set.cache('order-component-mobile-panel', document.querySelector('.order-component .mobile-panel'));
                }
                return OrderComponent.get.cache('order-component-mobile-panel');
            },
            collapsed: function () {
                if (!OrderComponent.get.cache('order-mobile-panel-collapsed')) {
                    OrderComponent.set.cache('order-mobile-panel-collapsed', document.querySelector('.order-mobile-panel-collapsed'));
                }
                return OrderComponent.get.cache('order-mobile-panel-collapsed');
            },
            expanded: function () {
                if (!OrderComponent.get.cache('order-mobile-panel-expanded')) {
                    OrderComponent.set.cache('order-mobile-panel-expanded', document.querySelector('.order-mobile-panel-expanded'));
                }
                return OrderComponent.get.cache('order-mobile-panel-expanded');
            }
        },
        price: {
            collapsed: {
                total: function () {
                    if (!OrderComponent.get.cache('order-mobile-panel-collapsed-total-price-value')) {
                        OrderComponent.set.cache('order-mobile-panel-collapsed-total-price-value', document.querySelector('.order-mobile-panel-collapsed-total-price-value'));
                    }
                    return OrderComponent.get.cache('order-mobile-panel-collapsed-total-price-value');
                }
            },
            expanded: {
                total: function () {
                    if (!OrderComponent.get.cache('order-mobile-panel-expanded-total-price-value')) {
                        OrderComponent.set.cache('order-mobile-panel-expanded-total-price-value', document.querySelector('.order-mobile-panel-expanded-total-price-value'));
                    }
                    return OrderComponent.get.cache('order-mobile-panel-expanded-total-price-value');
                },
                delivery: function () {
                    if (!OrderComponent.get.cache('order-mobile-panel-expanded-delivery-price')) {
                        OrderComponent.set.cache('order-mobile-panel-expanded-delivery-price', document.querySelector('.order-mobile-panel-expanded-delivery-price'));
                    }
                    return OrderComponent.get.cache('order-mobile-panel-expanded-delivery-price');
                },
            }
        }
    },
}

// Сеттеры
OrderComponent.set.cookie = function (code, value, options) {
    options = options || {};
    options.path = options.path || '/';
    options['max-age'] = options['max-age'] || 8640000;
    if (options.expires && options.expires.toUTCString) {
        options.expires = options.expires.toUTCString();
    }
    let cookie = encodeURIComponent(code) + '=' + encodeURIComponent(value);
    for (let optionKey in options) {
        cookie += '; ' + optionKey;
        let optionValue = options[optionKey];
        if (optionValue !== true) {
            cookie += '=' + optionValue;
        }
    }
    document.cookie = cookie;
}
OrderComponent.set.cache = function (code, data) {
    if (!OrderComponent.cache.hasOwnProperty(code)) {
        OrderComponent.cache[code] = {}
    }
    if (data === 0) {
        data = 0
    }
    OrderComponent.cache[code] = data
}
OrderComponent.set.storage = {
    /**
     * Метод сохраняет введенные данные в память браузера
     * Чтобы если пользователь ушел со страницы
     * То чтобы после, по его возвращению, все данные были уже подставлены
     * @param code
     * @param value
     */
    data: function (code, value) {
        if (OrderComponent.storage.data.hasOwnProperty(code) && OrderComponent.storage.data[code] === value) return
        OrderComponent.storage.data[code] = value;
        OrderComponent.set.cookie(OrderComponent.get.storage.code(), JSON.stringify(OrderComponent.storage.data));

        // Обновим данные на итоговом шаге
        if (
            code === 'lastName' ||
            code === 'name' ||
            code === 'secondName'
        ) {
            const fullName = [
                OrderComponent.get.form.data('customer').elements['lastName'].value,
                OrderComponent.get.form.data('customer').elements['name'].value,
                OrderComponent.get.form.data('customer').elements['secondName'].value
            ];
            OrderComponent.get.form.data('total').elements['fullName'].value = fullName.join(' ');
            OrderComponent.get.form.data('total').elements['fullName'].parentNode.parentNode.parentNode.classList.remove('invalid')

        } else if (code === 'email') {
            OrderComponent.get.form.data('total').elements['email'].value = value
            OrderComponent.get.form.data('total').elements['email'].parentNode.parentNode.parentNode.classList.remove('invalid')

        } else if (code === 'phone') {
            OrderComponent.get.form.data('total').elements['phone'].value = value
            OrderComponent.get.form.data('total').elements['phone'].parentNode.parentNode.parentNode.classList.remove('invalid')

        } else if (code === 'delivery') {

            OrderComponent.get.form.data('total').elements['delivery'].value = OrderComponent.get.delivery.data(value)['NAME'] || '...'
            OrderComponent.get.form.data('total').elements['delivery'].parentNode.parentNode.parentNode.classList.remove('invalid')

            if (
                value === 'pickup-nsk'
                ||
                value === 'courier-nsk'
                ||
                value === 'courier-nsk-free'
                ||
                value === 'courier-berdsk-free'
                ||
                value === 'pickup-msk-novoslobodskaya'
            ) {
                const delivery = OrderComponent.get.delivery.data(value);
                if (delivery.hasOwnProperty('location')) {
                    const fullAddress = [
                        delivery.location.zip,
                        delivery.location.country.name,
                        delivery.location.city,
                        delivery.location.street,
                        delivery.location.building,
                        delivery.location.room
                    ];
                    OrderComponent.get.form.data('total').elements['deliveryAddress'].value = delivery.location.description + ' ' + [
                        delivery.location.street,
                        delivery.location.building,
                        delivery.location.room
                    ].join(', ')
                    OrderComponent.set.storage.data('fullAddress', fullAddress.join(', '));
                    OrderComponent.set.storage.data('countryCode', delivery.location.country.code);
                    OrderComponent.set.storage.data('zip', delivery.location.zip);
                    OrderComponent.set.storage.data('city', delivery.location.city);
                    OrderComponent.set.storage.data('street', delivery.location.street);
                    OrderComponent.set.storage.data('building', delivery.location.building);
                    OrderComponent.set.storage.data('room', delivery.location.room);
                }
            } else if (
                value !== false &&
                value !== 'boxberry-point' &&
                value !== 'boxberry-point-free' &&
                value !== 'cdek-store-to-store'
            ) {
                const fullAddress = [
                    OrderComponent.get.form.data('delivery').elements['zip'].value,
                    OrderComponent.get.form.data('delivery').elements['countryCode'].options[OrderComponent.get.form.data('delivery').elements['countryCode'].selectedIndex].text,
                    OrderComponent.get.form.data('delivery').elements['city'].value,
                    OrderComponent.get.form.data('delivery').elements['street'].value,
                    OrderComponent.get.form.data('delivery').elements['building'].value,
                    OrderComponent.get.form.data('delivery').elements['room'].value
                ];
                OrderComponent.get.form.data('total').elements['deliveryAddress'].value = fullAddress.join(', ')
            } else if (
                (
                    (value === 'boxberry-point' || value === 'boxberry-point-free') && OrderComponent.get.storage.data('boxberryPointAddress')
                )
                ||
                (
                    value === 'cdek-store-to-store' && OrderComponent.get.storage.data('cdekPointAddress')
                )
            ) {
                const fullAddress = [
                    OrderComponent.get.form.data('delivery').elements['zip'].value,
                    OrderComponent.get.form.data('delivery').elements['countryCode'].options[OrderComponent.get.form.data('delivery').elements['countryCode'].selectedIndex].text,
                    OrderComponent.get.form.data('delivery').elements['city'].value
                ]

                if (value === 'boxberry-point' || value === 'boxberry-point-free') {
                    fullAddress.push(OrderComponent.get.storage.data('boxberryPointAddress'))
                } else if (value === 'cdek-store-to-store') {
                    fullAddress.push(OrderComponent.get.storage.data('cdekPointAddress'))
                }

                OrderComponent.get.form.data('total').elements['deliveryAddress'].value = fullAddress.join(', ')
            }

            OrderComponent.get.form.data('total').elements['deliveryAddress'].parentNode.parentNode.parentNode.classList.remove('invalid')

        } else if (
            code === 'zip' ||
            code === 'city' ||
            code === 'street' ||
            code === 'building'
        ) {
            if (
                OrderComponent.get.delivery.current() === 'pickup-nsk'
                ||
                OrderComponent.get.delivery.current() === 'courier-nsk'
                ||
                OrderComponent.get.delivery.current() === 'courier-nsk-free'
                ||
                OrderComponent.get.delivery.current() === 'courier-berdsk-free'
                ||
                OrderComponent.get.delivery.current() === 'pickup-msk-novoslobodskaya'
            ) {
                const delivery = OrderComponent.get.delivery.data(OrderComponent.get.delivery.current())
                if (delivery.hasOwnProperty('location')) {
                    const fullAddress = [
                        delivery.location.zip,
                        delivery.location.country.name,
                        delivery.location.city,
                        delivery.location.street,
                        delivery.location.building,
                        delivery.location.room
                    ];
                    OrderComponent.get.form.data('total').elements['deliveryAddress'].value = delivery.location.description + ' ' + [
                        delivery.location.street,
                        delivery.location.building
                    ].join(', ')
                    OrderComponent.set.storage.data('fullAddress', fullAddress.join(', '));
                    OrderComponent.set.storage.data('countryCode', delivery.location.country.code);
                    OrderComponent.set.storage.data('zip', delivery.location.zip);
                    OrderComponent.set.storage.data('city', delivery.location.city);
                    OrderComponent.set.storage.data('street', delivery.location.street);
                    OrderComponent.set.storage.data('building', delivery.location.building);
                    OrderComponent.set.storage.data('room', delivery.location.room);
                }

            } else {
                const fullAddress = [
                    OrderComponent.get.form.data('delivery').elements['zip'].value,
                    OrderComponent.get.form.data('delivery').elements['countryCode'].options[OrderComponent.get.form.data('delivery').elements['countryCode'].selectedIndex].text,
                    OrderComponent.get.form.data('delivery').elements['city'].value,
                    OrderComponent.get.form.data('delivery').elements['street'].value,
                    OrderComponent.get.form.data('delivery').elements['building'].value,
                    OrderComponent.get.form.data('delivery').elements['room'].value
                ]
                OrderComponent.get.form.data('total').elements['deliveryAddress'].value = fullAddress.join(', ')
            }

            OrderComponent.get.form.data('total').elements['deliveryAddress'].parentNode.parentNode.parentNode.classList.remove('invalid')

        } else if (code === 'payment') {
            OrderComponent.get.form.data('total').elements['payment'].value = OrderComponent.get.payment.data(value)['NAME'] || '...'
            OrderComponent.get.form.data('total').elements['payment'].parentNode.parentNode.parentNode.classList.remove('invalid')
        } else if (code === 'boxberryPointAddress' || code === 'cdekPointAddress') {

            if (!value) return

            const fullAddress = [
                OrderComponent.get.form.data('delivery').elements['zip'].value,
                OrderComponent.get.form.data('delivery').elements['countryCode'].options[OrderComponent.get.form.data('delivery').elements['countryCode'].selectedIndex].text,
                OrderComponent.get.form.data('delivery').elements['city'].value,
                value
            ]
            OrderComponent.get.form.data('total').elements['deliveryAddress'].value = fullAddress.join(', ')
            OrderComponent.get.form.data('total').elements['deliveryAddress'].parentNode.parentNode.parentNode.classList.remove('invalid')
        }
    }
}
OrderComponent.set.step = {
    current: function (code) {
        OrderComponent.params.step.current = code;
    },
}
OrderComponent.set.delivery = {
    name: function (code, value) {
        OrderComponent.get.delivery.name.node(code).innerHTML = '<div class="delivery-title"><span class="delivery-price"></span>' + value + '</div>';
    },
    description: function (code, value) {
        OrderComponent.get.delivery.description.node(code).innerHTML = value;
    },
    list: {
        valid: function () {
            OrderComponent.get.delivery.list.node().classList.remove('invalid');
            OrderComponent.get.delivery.list.node().classList.add('valid');
        },
        invalid: function () {
            OrderComponent.get.delivery.list.node().classList.remove('valid');
            OrderComponent.get.delivery.list.node().classList.add('invalid');
        },
        clear: function () {
            OrderComponent.get.delivery.list.node().classList.remove('valid');
            OrderComponent.get.delivery.list.node().classList.remove('invalid');
        },
    },
    // Заполнение полей адреса доставки
    location: function (data) {
        const fullAddress = OrderComponent.get.form.data('delivery').elements['fullAddress']
        const countryCode = OrderComponent.get.form.data('delivery').elements['countryCode']
        const zip = OrderComponent.get.form.data('delivery').elements['zip']
        const city = OrderComponent.get.form.data('delivery').elements['city']
        const street = OrderComponent.get.form.data('delivery').elements['street']
        const building = OrderComponent.get.form.data('delivery').elements['building']
        //const room = OrderComponent.get.form.data('delivery').elements['room'];

        fullAddress.value = data['location']['formatted']
        countryCode.value = data['location']['country_code']
        zip.value = data['location']['postal_code'] || ''
        city.value = ''
        street.value = ''
        building.value = ''
        //room.value = ''

        OrderComponent.boxberry.map.hide(OrderComponent.get.delivery.current())
        OrderComponent.cdek.map.hide()

        if (countryCode.value === '') {
            countryCode.classList.add('invalid')
        } else {
            countryCode.classList.remove('invalid')
        }

        // Сохраним данные в локальную память устройства
        OrderComponent.set.storage.data('fullAddress', fullAddress.value)
        OrderComponent.set.storage.data('countryCode', countryCode.value)
        OrderComponent.set.storage.data('zip', zip.value)
        OrderComponent.set.storage.data('city', city.value)
        OrderComponent.set.storage.data('street', street.value)
        OrderComponent.set.storage.data('building', building.value)
        //OrderComponent.set.storage.data('room', room.value);

        for (const item of data['location']['Components']) {
            switch (item.kind) {
                case 'locality':
                    city.value = item.name;
                    if (city.value.length > 0) {
                        // Если был указан город Москва, тогда обновим регион пользователя
                        // Предварительно предупредив его
                        if (city.value.toLowerCase() === 'москва') {
                            if (window.confirm("В качестве города, был установлен - Москва.\nНо установленный регион на сайте отличается.\nПри продолжении действия, будет обновлена страница и установлен соответствующий регион.\nПродолжить?")) {
                                BX.setCookie(OrderComponent.location.cookie(), 'MSK', {
                                    expires: 864000000,
                                    path: '/'
                                })
                                window.location.reload()
                            }
                        }
                        OrderComponent.delivery.list.show()
                        OrderComponent.payment.list.reload()
                    } else {
                        OrderComponent.delivery.list.hide()
                    }
                    OrderComponent.boxberry.map.reload(OrderComponent.get.delivery.current())
                    OrderComponent.cdek.map.reload()
                    OrderComponent.delivery.calculate()
                    // Сохраним данные в локальную память устройства
                    OrderComponent.set.storage.data('city', city.value)
                    break
                case 'street':
                    street.value = item.name;
                    // Сохраним данные в локальную память устройства
                    OrderComponent.set.storage.data('street', street.value)
                    break
                case 'house':
                    building.value = item.name;
                    // Сохраним данные в локальную память устройства
                    OrderComponent.set.storage.data('building', building.value)
                    break
            }
        }

        if (zip.value.length < 5) {
            zip.style.borderColor = '#dcdcdc' // status default
        } else {
            zip.style.borderColor = '#8bc34a' // status ok
            // Обновим цены на доставку
            OrderComponent.delivery.calculate()
        }

        OrderComponent.delivery.location.show()
    },
    price: {
        title: function (code, value) {
            OrderComponent.get.delivery.name.node(code).innerHTML = '<div class="delivery-title"><span class="delivery-price">' + value + '</span>' + OrderComponent.get.delivery.data(code)['NAME'] + '</div>'
        },
        // Сумма доставки в сайдбаре
        total: function (amount) {
            amount = parseFloat(amount)
            amount = amount > 0 ? amount : 0

            // Сохраним в кэш
            OrderComponent.set.cache('delivery-price', amount)

            // Обновим визуальную часть
            amount = amount > 0 ? App.util.format.price.withCurrency(amount) : '-'
            OrderComponent.get.delivery.price.total.node().innerText = amount
            OrderComponent.get.mobile.node.price.expanded.delivery().innerHTML = amount
        },
    },
    // Сохранение рассчитанных данных для службы доставки
    calculated: function (code, data) {

        if (code === 'boxberry-point' || code === 'boxberry-point-free') {
            if (data === false) {
                OrderComponent.set.storage.data('boxberryPointId', false)
                OrderComponent.set.storage.data('boxberryPointAddress', false)
            } else {
                OrderComponent.set.storage.data('boxberryPointId', data.point.id)
                if (data.city.address) {
                    OrderComponent.set.storage.data('boxberryPointAddress', data.city.address);
                }
            }
        } else if (code === 'cdek-store-to-store') {
            if (data === false) {
                OrderComponent.set.storage.data('cdekPointId', false)
                OrderComponent.set.storage.data('cdekPointAddress', false)
            } else {
                OrderComponent.set.storage.data('cdekPointId', data.point.id)
                if (data.city.address) {
                    OrderComponent.set.storage.data('cdekPointAddress', data.city.address);
                }
            }
        }

        OrderComponent.get.delivery.list.data()[code]['calculated'] = data
    }
}
OrderComponent.set.payment = {
    current: function (code) {
        OrderComponent.set.storage.data('payment', code);
    },
    list: {
        valid: function () {
            OrderComponent.get.payment.list.node().setAttribute('data-valid', 'Y')
        },
        invalid: function () {
            OrderComponent.get.payment.list.node().setAttribute('data-valid', 'N')
        },
    },
};
OrderComponent.set.basket = {
    price: {
        total: function (amount) {
            amount = Math.round(amount * 100) / 100;
            amount = App.util.format.price.withoutCurrency(amount);
            OrderComponent.get.basket.node.price.total().innerHTML = amount + '&nbsp;<span>руб.</span>';
            OrderComponent.get.mobile.node.price.collapsed.total().innerHTML = amount;
            OrderComponent.get.mobile.node.price.expanded.total().innerHTML = amount;
        }
    }
}

// Boxberry
OrderComponent.boxberry = {
    activate: function (deliveryCode) {
        OrderComponent.boxberry.map.show(deliveryCode)

        const delivery = OrderComponent.get.delivery.data(deliveryCode)

        if (delivery.calculated === false) {
            OrderComponent.message.send({
                content: 'Активирован способ доставки: ' + OrderComponent.get.delivery.name.value(deliveryCode),
                type: 'success',
            });
            return
        } else if (!OrderComponent.get.storage.data('boxberryPointId')) {
            OrderComponent.message.send({
                content: 'Активирован способ доставки: ' + OrderComponent.get.delivery.name.value(deliveryCode),
                type: 'success',
            })
        }

        // Установим новые суммы корзины и доставки
        if (delivery.calculated.hasOwnProperty('price')) {
            if (delivery.calculated.price.value > 0) {
                OrderComponent.set.delivery.price.total(delivery.calculated.price.value);
                OrderComponent.set.basket.price.total(OrderComponent.get.basket.price.total() + delivery.calculated.price.value)
                OrderComponent.get.form.data('total').elements['deliveryPrice'].value = App.util.format.price.withCurrency(delivery.calculated.price.value)
            } else {
                OrderComponent.get.form.data('total').elements['deliveryPrice'].value = 'БЕСПЛАТНО'
            }

            // Обновим данные на итоговом шаге
            OrderComponent.get.form.data('total').elements['deliveryPrice'].parentNode.parentNode.parentNode.classList.remove('invalid')
        }

        if (delivery.calculated.hasOwnProperty('title')) {
            OrderComponent.set.delivery.price.title(deliveryCode, delivery.calculated.title);
        }
        if (delivery.calculated.hasOwnProperty('description')) {
            OrderComponent.set.delivery.description(deliveryCode, delivery.calculated.description);
        }

        // Сохраним данные в локальную память устройства
        if (delivery.calculated.hasOwnProperty('point')) {
            OrderComponent.set.storage.data('boxberryPointId', delivery.calculated.point.id)
        }
        if (delivery.calculated.hasOwnProperty('city')) {
            OrderComponent.set.storage.data('boxberryPointAddress', delivery.calculated.city.address)
        }

        OrderComponent.set.storage.data('delivery', deliveryCode)
    },
    deactivate: function (deliveryCode) {
        // Если ранее карта была отображена
        // А сейчас мы ее скрываем, тогда очистим все данные по доставке
        if (OrderComponent.boxberry.map.isShown(deliveryCode)) {
            // Установим новые суммы корзины и доставки

            OrderComponent.set.delivery.price.total(false)
            OrderComponent.set.basket.price.total(OrderComponent.get.basket.price.total())

            if (!OrderComponent.delivery.isFree(deliveryCode)) {
                OrderComponent.set.delivery.name(deliveryCode, OrderComponent.get.delivery.data(deliveryCode)['NAME'])
            }

            OrderComponent.set.delivery.description(deliveryCode, OrderComponent.get.delivery.data(deliveryCode)['DESCRIPTION'])

            // Сохраним данные в локальную память устройства
            OrderComponent.set.storage.data('boxberryPointId', false)
            OrderComponent.set.storage.data('boxberryPointAddress', false)
            OrderComponent.set.storage.data('delivery', false)
        }
        OrderComponent.boxberry.map.hide(deliveryCode)
    },
    map: {
        node: function (deliveryCode) {
            if (!OrderComponent.get.cache('yandex-map-' + deliveryCode)) {
                OrderComponent.set.cache('yandex-map-' + deliveryCode, document.getElementById('yandex-map-' + deliveryCode))
            }
            return OrderComponent.get.cache('yandex-map-' + deliveryCode)
        },
        show: function (deliveryCode) {
            if (OrderComponent.boxberry.map.isShown(deliveryCode)) return

            if (!deliveryCode) {
                deliveryCode = OrderComponent.get.delivery.current()
            }

            const cityName = OrderComponent.get.delivery.location.city.current()

            // Сразу отобразим блок с картой
            OrderComponent.boxberry.map.node(deliveryCode).setAttribute('data-visibility', 'Y')

            if (!cityName) {
                OrderComponent.message.send({
                    content: 'Не указан город',
                    type: 'error',
                });
                return
            }

            // Получим объект карты, для последующей работы с картой
            if (!OrderComponent.get.cache('yandex-map-object-' + deliveryCode)) {
                Yandex.ready(function () {
                    const Map = new Yandex.Map(OrderComponent.boxberry.map.node(deliveryCode), {
                        center: ['54.183376', '37.573169'],
                        zoom: 9,
                        //checkZoomRange: true,
                        controls: [
                            //'geolocationControl',
                            'routeButtonControl',
                            'trafficControl',
                            //'fullscreenControl',
                            'zoomControl',
                            'rulerControl',
                        ],
                    });
                    Map.copyrights.add('© <a href="https://megre.ru" target="_blank" style="color:#000;">Megre.ru</a>')
                    OrderComponent.set.cache('yandex-map-object-' + deliveryCode, Map)
                    // Перезапустим данный метод, так как карта уже сформирована
                    OrderComponent.boxberry.map.show(deliveryCode)
                })
                return
            }

            // Получим пункты выдачи в городе
            if (!OrderComponent.get.cache(deliveryCode + '-points-' + cityName)) {
                BX.ajax.runComponentAction(
                    OrderComponent.get.param('component'),
                    OrderComponent.get.delivery.data(deliveryCode)['provider'],
                    {
                        mode: 'class',
                        data: {
                            request: {
                                method: OrderComponent.get.delivery.data(deliveryCode)['method']['getPointsOfCity'],
                                location: {
                                    city: {
                                        value: cityName
                                    }
                                }
                            }
                        },
                        timeout: 30
                    })
                    .then(function (points) {
                        points = points.data
                        if (points) {
                            Yandex.ready(function () {
                                // Добавим пункты на карту
                                for (let pointCode in points) {
                                    const point = points[pointCode];
                                    OrderComponent.get.cache('yandex-map-object-' + deliveryCode).geoObjects.add(new Yandex.Placemark(
                                        [point['LONGITUDE'], point['LATITUDE']],
                                        {
                                            balloonContentHeader: point['CITY_NAME'] + ', ' + point['ADDRESS_REDUCE'],
                                            //balloonContentBody: 'Доставка заказа будет произведена в данный пункт выдачи',
                                            balloonContentBody: point['PHONE'] + '<br>' + point['WORK_SCHEDULE'],
                                            balloonContentFooter: '<span class="yandex-point-select" onclick="OrderComponent.boxberry.point.select(\'' + cityName + '\', \'' + pointCode + '\', \'' + deliveryCode + '\')">Выбрать пункт выдачи</span>',
                                            hintContent: point['CITY_NAME'] + ', ' + point['ADDRESS_REDUCE'],
                                            iconContent: '<i class="fas fa-tree" style="color: #8bc34a"></i>'
                                        },
                                        {
                                            preset: 'islands#circleIcon',
                                            iconColor: '#3d7341'
                                        }))
                                }
                                // Сохраним данные по точкам в кэш
                                OrderComponent.set.cache(deliveryCode + '-points-' + cityName, points)
                                // Перезапустим метод, так как уже подгрузили данные по точкам
                                OrderComponent.boxberry.map.show(deliveryCode)
                            })
                        } else {
                            OrderComponent.set.cache(deliveryCode + '-points-' + cityName, false)
                        }
                    })
                return
            }

            // Если имеются пункты выдачи заказов, тогда отобразим их на карте
            // Установи центр для карты по первой точке
            //const point = App.object.get.key.first(OrderComponent.get.cache(deliveryCode + '-points-' + cityName))['value']

            OrderComponent.get.cache('yandex-map-object-' + deliveryCode).setBounds(OrderComponent.get.cache('yandex-map-object-' + deliveryCode).geoObjects.getBounds(), {
                checkZoomRange: true,
                zoomMargin: 9
            })

            // Отобразим карту
            OrderComponent.boxberry.map.loader.node(deliveryCode).style.display = 'none'
            OrderComponent.set.cache(deliveryCode + '-map-rendered', true)

            // Если обновляли старницу - то есть ПВЗ уже был выбран ранее
            if (cityName && OrderComponent.get.storage.data('boxberryPointId')) {
                OrderComponent.boxberry.point.select(cityName, OrderComponent.get.storage.data('boxberryPointId'), deliveryCode)
            }
        },
        hide: function (deliveryCode) {
            if (!deliveryCode || (deliveryCode !== 'boxberry-point' && deliveryCode !== 'boxberry-point-free')) {
                OrderComponent.boxberry.map.node('boxberry-point').setAttribute('data-visibility', 'N')
                OrderComponent.boxberry.map.node('boxberry-point-free').setAttribute('data-visibility', 'N')
                OrderComponent.set.cache('boxberry-point-map-rendered', false)
                OrderComponent.set.cache('boxberry-point-free-map-rendered', false)
                return
            }
            OrderComponent.boxberry.map.node(deliveryCode).setAttribute('data-visibility', 'N')
            if (OrderComponent.boxberry.map.isHidden(deliveryCode)) return
            OrderComponent.set.cache(deliveryCode + '-map-rendered', false)
        },
        loader: {
            node: function (deliveryCode) {
                if (!OrderComponent.get.cache('yandex-map-loader-' + deliveryCode)) {
                    OrderComponent.set.cache('yandex-map-loader-' + deliveryCode, document.getElementById('yandex-map-loader-' + deliveryCode))
                }
                return OrderComponent.get.cache('yandex-map-loader-' + deliveryCode)
            },
            width: function (deliveryCode) {
                if (!OrderComponent.get.cache('yandex-map-loader-width-' + deliveryCode)) {
                    OrderComponent.set.cache('yandex-map-loader-width-' + deliveryCode, OrderComponent.boxberry.map.loader.node(deliveryCode).offsetWidth)
                }
                return OrderComponent.get.cache('yandex-map-loader-width-' + deliveryCode)
            },
            height: function (deliveryCode) {
                if (!OrderComponent.get.cache('yandex-map-loader-height-' + deliveryCode)) {
                    OrderComponent.set.cache('yandex-map-loader-height-' + deliveryCode, OrderComponent.boxberry.map.loader.node(deliveryCode).offsetHeight)
                }
                return OrderComponent.get.cache('yandex-map-loader-height-' + deliveryCode)
            }
        },
        isShown: function (deliveryCode) {
            return OrderComponent.get.cache(deliveryCode + '-map-rendered') === true
        },
        isHidden: function (deliveryCode) {
            return OrderComponent.get.cache(deliveryCode + '-map-rendered') === false
        },
        reload: function (deliveryCode) {
            if (!deliveryCode) {
                OrderComponent.boxberry.map.hide(deliveryCode)
                return
            }
            if (OrderComponent.boxberry.map.isShown(deliveryCode)) {
                OrderComponent.boxberry.map.hide(deliveryCode)
                OrderComponent.boxberry.map.show(deliveryCode)
            }
        }
    },
    point: {
        // Выбор пункта выдачи заказов на яндекс карте
        select: function (cityName, pointCode, deliveryCode) {
            if (!deliveryCode) {
                deliveryCode = OrderComponent.get.delivery.current()
            }

            // Рассчитанная стоимость и параметры доставки
            const calculated = OrderComponent.get.delivery.calculated(deliveryCode)

            // Если для пункта выдачи еще не считали стоимость доставки - тогда посчитаем
            if (!calculated) {
                const point = OrderComponent.get.cache(deliveryCode + '-points-' + cityName)[pointCode];
                if (!point) {
                    OrderComponent.set.storage.data('boxberryPointId', false)
                    OrderComponent.set.storage.data('boxberryPointAddress', false)
                    OrderComponent.set.storage.data('delivery', false)
                    return
                }
                OrderComponent.message.send({
                    content: 'Ожидайте, получаем стоимость доставки до пункта выдачи',
                    //autoHide: false,
                    //category: 'calculate',
                });

                // Получим стоимость доставки от склада продавца
                // До пункта выдачи заказов, который указал покупатель
                BX.ajax.runComponentAction(
                    OrderComponent.get.param('component'),
                    OrderComponent.get.delivery.data(deliveryCode)['provider'],
                    {
                        mode: 'class',
                        data: {
                            request: {
                                method: OrderComponent.get.delivery.data(deliveryCode)['method']['getDeliveryPrice'],
                                delivery: deliveryCode,
                                point: point
                            }
                        },
                        timeout: 30
                    })
                    .then(function (delivery) {
                        delivery = delivery.data

                        delivery.period = delivery.period + ' ' + App.util.declension(delivery.period, 'день', 'дней', 'дня')

                        if (delivery.price > 0) {
                            delivery.title = App.util.format.price.withoutCurrency(delivery.price) + '<img src="' + OrderComponent.get.param('path')['image']['ruble'] + '">&nbsp;|&nbsp;'
                        } else if (delivery.price === 'free') {
                            delivery.title = 'БЕСПЛАТНО&nbsp;|&nbsp;'
                            delivery.price = 0
                        } else if (delivery.price === 'refine') {
                            delivery.title = 'УТОЧНЯЕТСЯ&nbsp;|&nbsp;'
                            delivery.price = 0
                        }

                        let deliveryTime = 'срок доставки'
                        if (OrderComponent.delivery.isEstimatedTime(deliveryCode)) {
                            deliveryTime += ', ориентировочно,'
                        }
                        deliveryTime += ' ' + delivery.period

                        delivery = {
                            title: delivery.title,
                            description: point['CITY_NAME'] + ', ' + point['ADDRESS_REDUCE'] + ', ' + deliveryTime,
                            period: delivery.period,
                            price: {
                                value: delivery['price'],
                                withCurrency: App.util.format.price.withCurrency(delivery['price']),
                            },
                            city: {
                                code: point['CITY_CODE'],
                                name: point['CITY_NAME'],
                                address: point['ADDRESS_REDUCE'],
                            },
                            point: {
                                id: point['POINT_ID'],
                            },
                        }

                        // Сохраним данные по рассчетам в службу доставки
                        OrderComponent.set.delivery.calculated(deliveryCode, delivery)
                        // Перезапустим метод, чтобы обработать полученные данные
                        OrderComponent.boxberry.point.select(cityName, pointCode, deliveryCode)
                    })
                    .catch(function (error) {

                    })
                return
            }

            // Проверим, изменился ли адрес пункта вывоза заказа
            // Если изменился, тогда обновим данные
            if ((calculated.hasOwnProperty('point') && calculated.point.id !== pointCode) || OrderComponent.boxberry.point.selected().id === false) {

                // Если сменился город, тогда полностью пересчитаем данные для доставки
                if (calculated.city.name !== cityName) {
                    // Сохраним данные по рассчетам в службу доставки
                    OrderComponent.set.delivery.calculated(deliveryCode, false)
                    // Перезапустим метод, чтобы обработать полученные данные
                    OrderComponent.boxberry.point.select(cityName, pointCode, deliveryCode)
                }

                const point = OrderComponent.get.cache(deliveryCode + '-points-' + cityName)[pointCode]

                let deliveryTime = 'срок доставки'
                if (OrderComponent.delivery.isEstimatedTime(deliveryCode)) {
                    deliveryTime += ', ориентировочно,'
                }
                deliveryTime += ' ' + calculated.period

                calculated['description'] = point['CITY_NAME'] + ', ' + point['ADDRESS_REDUCE'] + ', ' + deliveryTime
                calculated.city.code = point['CITY_CODE']
                calculated.city.name = point['CITY_NAME']
                calculated.city.address = point['ADDRESS_REDUCE']
                calculated.point.id = point['POINT_ID']

                if (!OrderComponent.get.form.data('delivery').elements['zip'].value && point['ZIP']) {
                    document.forms[OrderComponent.get.param('form')['delivery']['code']].elements['zip'].value = point['ZIP']
                    OrderComponent.set.storage.data('zip', point['ZIP'])
                    OrderComponent.get.form.data('delivery').elements['zip'].style.borderColor = '#8bc34a'; // status ok
                }

                // Сохраним данные по рассчетам в службу доставки
                OrderComponent.set.delivery.calculated(deliveryCode, calculated)
            }

            // Активируем службу доставки повторно, чтобы произошло обновление стоимости
            OrderComponent.delivery.activate(deliveryCode)

            let balloonContent = ''
            balloonContent += calculated.city.name + ', ' + calculated.city.address + '<br>'
            if (deliveryCode === 'boxberry-point-free') {
                balloonContent += 'Стоимость доставки: БЕСПЛАТНО<br>'
            } else {
                balloonContent += 'Стоимость доставки: ' + calculated.price.withCurrency + '<br>'
            }
            balloonContent += 'Срок доставки'
            if (OrderComponent.delivery.isEstimatedTime(deliveryCode)) {
                balloonContent += ', ориентировочно,'
            }
            balloonContent += ': ' + calculated.period

            OrderComponent.message.send({content: balloonContent, type: 'success', autoHideDelay: 5000})
        },
        selected: function () {
            return {
                id: OrderComponent.get.storage.data('boxberryPointId') || false,
                address: OrderComponent.get.storage.data('boxberryPointAddress') || false,
            }
        }
    },
}

// CDEK
OrderComponent.cdek = {
    activate: function () {
        OrderComponent.cdek.map.show()

        const delivery = OrderComponent.get.delivery.data('cdek-store-to-store')

        if (delivery.calculated === false) {
            OrderComponent.message.send({
                content: 'Активирован способ доставки: ' + OrderComponent.get.delivery.name.value('cdek-store-to-store'),
                type: 'success',
            });
            return
        } else if (!OrderComponent.get.storage.data('cdekPointId')) {
            OrderComponent.message.send({
                content: 'Активирован способ доставки: ' + OrderComponent.get.delivery.name.value('cdek-store-to-store'),
                type: 'success',
            });
        }

        // Установим новые суммы корзины и доставки
        if (delivery.calculated.hasOwnProperty('price')) {
            OrderComponent.set.delivery.price.total(delivery.calculated.price.value)
            OrderComponent.set.basket.price.total(OrderComponent.get.basket.price.total() + delivery.calculated.price.value)

            // Обновим данные на итоговом шаге
            OrderComponent.get.form.data('total').elements['deliveryPrice'].value = delivery.calculated.price.value > 0 ? App.util.format.price.withCurrency(delivery.calculated.price.value) : 'БЕСПЛАТНО'
            OrderComponent.get.form.data('total').elements['deliveryPrice'].parentNode.parentNode.parentNode.classList.remove('invalid')
        }

        if (delivery.calculated.hasOwnProperty('title')) {
            OrderComponent.set.delivery.price.title('cdek-store-to-store', delivery.calculated.title)
        }
        if (delivery.calculated.hasOwnProperty('description')) {
            OrderComponent.set.delivery.description('cdek-store-to-store', delivery.calculated.description)
        }

        // Сохраним данные в локальную память устройства
        if (delivery.calculated.hasOwnProperty('point')) {
            OrderComponent.set.storage.data('cdekPointId', delivery.calculated.point.id)
        }
        if (delivery.calculated.hasOwnProperty('city')) {
            OrderComponent.set.storage.data('cdekPointAddress', delivery.calculated.city.address)
        }
        OrderComponent.set.storage.data('delivery', 'cdek-store-to-store')
    },
    deactivate: function () {
        // Если ранее карта была отображена
        // А сейчас мы ее скрываем, тогда очистим все данные по доставке
        if (OrderComponent.cdek.map.isShown()) {
            // Установим новые суммы корзины и доставки

            OrderComponent.set.delivery.price.total(false)
            OrderComponent.set.basket.price.total(OrderComponent.get.basket.price.total())

            if (!OrderComponent.delivery.isFree('cdek-store-to-store')) {
                OrderComponent.set.delivery.name('cdek-store-to-store', OrderComponent.get.delivery.data('cdek-store-to-store')['NAME'])
            }

            OrderComponent.set.delivery.description('cdek-store-to-store', OrderComponent.get.delivery.data('cdek-store-to-store')['DESCRIPTION'])

            // Сохраним данные в локальную память устройства
            OrderComponent.set.storage.data('cdekPointId', false)
            OrderComponent.set.storage.data('cdekPointAddress', false)
            OrderComponent.set.storage.data('delivery', false)
        }
        OrderComponent.cdek.map.hide()
    },
    map: {
        node: function () {
            if (!OrderComponent.get.cache('yandex-map-cdek')) {
                OrderComponent.set.cache('yandex-map-cdek', document.getElementById('yandex-map-cdek'));
            }
            return OrderComponent.get.cache('yandex-map-cdek');
        },
        show: function () {
            if (OrderComponent.cdek.map.isShown()) return

            const cityName = OrderComponent.get.delivery.location.city.current();

            // Сразу отобразим блок с картой
            OrderComponent.cdek.map.node().setAttribute('data-visibility', 'Y');

            if (!cityName) {
                OrderComponent.message.send({
                    content: 'Не указан город',
                    type: 'error',
                });
                return
            }

            // Получим объект карты, для последующей работы с картой
            if (!OrderComponent.get.cache('yandex-map-object-cdek')) {
                Yandex.ready(function () {
                    const Map = new Yandex.Map(OrderComponent.cdek.map.node(), {
                        center: ['54.183376', '37.573169'],
                        zoom: 9,
                        //checkZoomRange: true,
                        controls: [
                            //'geolocationControl',
                            'routeButtonControl',
                            'trafficControl',
                            //'fullscreenControl',
                            'zoomControl',
                            'rulerControl',
                        ],
                    });
                    Map.copyrights.add('© <a href="https://megre.ru" target="_blank" style="color:#000;">Megre.ru</a>');
                    OrderComponent.set.cache('yandex-map-object-cdek', Map);
                    // Перезапустим данный метод, так как карта уже сформирована
                    OrderComponent.cdek.map.show();
                });
                return
            }

            // Получим пункты выдачи в городе
            if (!OrderComponent.get.cache('cdek-points-' + cityName)) {
                BX.ajax.runComponentAction(
                    OrderComponent.get.param('component'),
                    OrderComponent.get.delivery.data('cdek-store-to-store')['provider'],
                    {
                        mode: 'class',
                        data: {
                            request: {
                                method: OrderComponent.get.delivery.data('cdek-store-to-store')['method']['getPointsOfCity'],
                                location: {
                                    city: {
                                        value: cityName
                                    }
                                }
                            }
                        },
                        timeout: 30
                    })
                    .then(function (points) {
                        points = points.data;
                        if (points) {
                            Yandex.ready(function () {
                                // Добавим пункты на карту
                                for (let pointCode in points) {
                                    const point = points[pointCode];
                                    OrderComponent.get.cache('yandex-map-object-cdek').geoObjects.add(new Yandex.Placemark(
                                        [point['LONGITUDE'], point['LATITUDE']],
                                        {
                                            balloonContentHeader: point['CITY_NAME'] + ', ' + point['ADDRESS_REDUCE'],
                                            //balloonContentBody: 'Доставка заказа будет произведена в данный пункт выдачи',
                                            balloonContentBody: point['PHONE'] + '<br>' + point['WORK_SCHEDULE'],
                                            balloonContentFooter: '<span class="yandex-point-select" onclick="OrderComponent.cdek.point.select(\'' + cityName + '\', \'' + pointCode + '\')">Выбрать пункт выдачи</span>',
                                            hintContent: point['CITY_NAME'] + ', ' + point['ADDRESS_REDUCE'],
                                            iconContent: '<i class="fas fa-tree" style="color: #8bc34a"></i>'
                                        },
                                        {
                                            preset: 'islands#circleIcon',
                                            iconColor: '#3d7341'
                                        }));
                                }
                                // Сохраним данные по точкам в кэш
                                OrderComponent.set.cache('cdek-points-' + cityName, points);
                                // Перезапустим метод, так как уже подгрузили данные по точкам
                                OrderComponent.cdek.map.show();
                            });
                        } else {
                            OrderComponent.set.cache('cdek-points-' + cityName, false)
                        }
                    });
                return
            }

            // Если имеются пункты выдачи заказов, тогда отобразим их на карте
            // Установим центр для карты по первой точке
            OrderComponent.get.cache('yandex-map-object-cdek').setBounds(OrderComponent.get.cache('yandex-map-object-cdek').geoObjects.getBounds(), {
                checkZoomRange: true,
                zoomMargin: 9
            })

            // Отобразим карту
            OrderComponent.cdek.map.loader.node().style.display = 'none'
            OrderComponent.set.cache('cdek-map-rendered', true)

            // Если обновляли старницу - то есть ПВЗ уже был выбран ранее
            if (cityName && OrderComponent.get.storage.data('cdekPointId')) {
                OrderComponent.cdek.point.select(cityName, OrderComponent.get.storage.data('cdekPointId'))
            }
        },
        hide: function () {
            OrderComponent.cdek.map.node().setAttribute('data-visibility', 'N')
            if (OrderComponent.cdek.map.isHidden()) return
            OrderComponent.set.cache('cdek-map-rendered', false)
        },
        loader: {
            node: function () {
                if (!OrderComponent.get.cache('yandex-map-loader-cdek')) {
                    OrderComponent.set.cache('yandex-map-loader-cdek', document.getElementById('yandex-map-loader-cdek'));
                }
                return OrderComponent.get.cache('yandex-map-loader-cdek')
            },
            width: function () {
                if (!OrderComponent.get.cache('yandex-map-loader-width-cdek')) {
                    OrderComponent.set.cache('yandex-map-loader-width-cdek', OrderComponent.cdek.map.loader.node().offsetWidth);
                }
                return OrderComponent.get.cache('yandex-map-loader-width-cdek')
            },
            height: function () {
                if (!OrderComponent.get.cache('yandex-map-loader-height-cdek')) {
                    OrderComponent.set.cache('yandex-map-loader-height-cdek', OrderComponent.cdek.map.loader.node().offsetHeight);
                }
                return OrderComponent.get.cache('yandex-map-loader-height-cdek')
            }
        },
        isShown: function () {
            return OrderComponent.get.cache('cdek-map-rendered') === true
        },
        isHidden: function () {
            return OrderComponent.get.cache('cdek-map-rendered') === false
        },
        reload: function () {
            if (OrderComponent.cdek.map.isShown()) {
                OrderComponent.cdek.map.hide()
                OrderComponent.cdek.map.show()
            }
        }
    },
    point: {
        // Выбор пункта выдачи заказов на яндекс карте
        select: function (cityName, pointCode) {
            // Рассчитанная стоимость и параметры доставки
            const calculated = OrderComponent.get.delivery.calculated('cdek-store-to-store')

            // Если для пункта выдачи еще не считали стоимость доставки - тогда посчитаем
            if (!calculated) {
                const point = OrderComponent.get.cache('cdek-points-' + cityName)[pointCode];

                if (!point) {
                    OrderComponent.set.storage.data('cdekPointId', false)
                    OrderComponent.set.storage.data('cdekPointAddress', false)
                    OrderComponent.set.storage.data('delivery', false)
                    return
                }
                OrderComponent.message.send({
                    content: 'Ожидайте, получаем стоимость доставки до пункта выдачи',
                    //autoHide: false,
                    //category: 'calculate',
                })

                // Получим стоимость доставки от склада продавца
                // До пункта выдачи заказов, который указал покупатель
                BX.ajax.runComponentAction(
                    OrderComponent.get.param('component'),
                    OrderComponent.get.delivery.data('cdek-store-to-store')['provider'],
                    {
                        mode: 'class',
                        data: {
                            request: {
                                method: OrderComponent.get.delivery.data('cdek-store-to-store')['method']['getDeliveryPrice'],
                                delivery: 'cdek-store-to-store',
                                point: point
                            }
                        },
                        timeout: 30
                    })
                    .then(function (delivery) {
                        delivery = delivery.data

                        if (delivery.period.hasOwnProperty('min') && delivery.period.hasOwnProperty('max')) {
                            delivery.period = delivery.period.min + '-' + delivery.period.max + ' ' + App.util.declension(delivery.period.max, 'день', 'дней', 'дня');
                        } else {
                            delivery.period = delivery.period + ' ' + App.util.declension(delivery.period, 'день', 'дней', 'дня');
                        }

                        if (OrderComponent.delivery.isFree('cdek-store-to-store')) {
                            delivery.title = 'БЕСПЛАТНО&nbsp;|&nbsp;'
                            delivery.price = 0
                        } else {
                            delivery.title = App.util.format.price.withoutCurrency(delivery['price']) + '<img src="' + OrderComponent.get.param('path')['image']['ruble'] + '">&nbsp;|&nbsp;'
                        }

                        let deliveryTime = 'срок доставки ' + delivery.period
                        if (OrderComponent.delivery.isEstimatedTime('cdek-store-to-store')) {
                            deliveryTime += ', ориентировочно,'
                        }
                        deliveryTime += ' ' + delivery.period

                        delivery = {
                            title: delivery.title,
                            description: point['CITY_NAME'] + ', ' + point['ADDRESS_REDUCE'] + ', ' + deliveryTime,
                            period: delivery.period,
                            price: {
                                value: delivery['price'],
                                withCurrency: App.util.format.price.withCurrency(delivery['price']),
                            },
                            city: {
                                code: point['CITY_CODE'],
                                name: point['CITY_NAME'],
                                address: point['ADDRESS_REDUCE'],
                            },
                            point: {
                                id: point['POINT_ID'],
                            },
                        }

                        // Сохраним данные по рассчетам в службу доставки
                        OrderComponent.set.delivery.calculated('cdek-store-to-store', delivery)
                        // Перезапустим метод, чтобы обработать полученные данные
                        OrderComponent.cdek.point.select(cityName, pointCode)
                    })
                return
            }

            // Проверим, изменился ли адрес пункта вывоза заказа
            // Если изменился, тогда обновим данные
            if (calculated.point.id !== pointCode || OrderComponent.cdek.point.selected().id === false) {

                // Если сменился город, тогда полностью пересчитаем данные для доставки
                if (calculated.city.name !== cityName) {
                    // Сохраним данные по рассчетам в службу доставки
                    OrderComponent.set.delivery.calculated('cdek-store-to-store', false)
                    // Перезапустим метод, чтобы обработать полученные данные
                    OrderComponent.cdek.point.select(cityName, pointCode)
                }

                const point = OrderComponent.get.cache('cdek-points-' + cityName)[pointCode]

                let deliveryTime = 'срок доставки ' + calculated.period
                if (OrderComponent.delivery.isEstimatedTime('cdek-store-to-store')) {
                    deliveryTime += ', ориентировочно,'
                }
                deliveryTime += ' ' + calculated.period

                calculated['description'] = point['CITY_NAME'] + ', ' + point['ADDRESS_REDUCE'] + ', ' + deliveryTime
                calculated.city.code = point['CITY_CODE']
                calculated.city.name = point['CITY_NAME']
                calculated.city.address = point['ADDRESS_REDUCE']
                calculated.point.id = point['POINT_ID']

                if (!OrderComponent.get.form.data('delivery').elements['zip'].value && point['ZIP']) {
                    document.forms[OrderComponent.get.param('form')['delivery']['code']].elements['zip'].value = point['ZIP']
                    OrderComponent.set.storage.data('zip', point['ZIP'])
                    OrderComponent.get.form.data('delivery').elements['zip'].style.borderColor = '#8bc34a'; // status ok
                }

                // Сохраним данные по рассчетам в службу доставки
                OrderComponent.set.delivery.calculated('cdek-store-to-store', calculated)
            }

            // Активируем службу доставки повторно, чтобы произошло обновление стоимости
            OrderComponent.delivery.activate('cdek-store-to-store')

            let balloonContent = ''
            balloonContent += calculated.city.name + ', ' + calculated.city.address + '<br>'
            if (OrderComponent.delivery.isFree('cdek-store-to-store')) {
                balloonContent += 'Стоимость доставки: БЕСПЛАТНО<br>'
            } else {
                balloonContent += 'Стоимость доставки: ' + calculated.price.withCurrency + '<br>'
            }
            balloonContent += 'Срок доставки'
            if (OrderComponent.delivery.isEstimatedTime('cdek-store-to-store')) {
                balloonContent += ', ориентировочно,'
            }
            balloonContent += ': ' + calculated.period

            OrderComponent.message.send({content: balloonContent, type: 'success', autoHideDelay: 5000})
        },
        selected: function () {
            return {
                id: OrderComponent.get.storage.data('cdekPointId') || false,
                address: OrderComponent.get.storage.data('cdekPointAddress') || false,
            }
        }
    },
}

// Location
OrderComponent.location = {
    cookie: function () {
        return OrderComponent.get.param('location')['cookie']
    },
    current: function () {
        return OrderComponent.get.param('location')['current']
    },
    check: {
        msk: function () {
            /*if (BX.getCookie(OrderComponent.location.cookie()) !== 'MSK') {
                BX.setCookie(OrderComponent.location.cookie(), 'MSK', {
                    expires: 864000000,
                    path: '/'
                })
                window.location.reload()
            }*/
        }
    },
}
