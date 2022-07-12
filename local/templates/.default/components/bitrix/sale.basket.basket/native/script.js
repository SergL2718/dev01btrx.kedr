/*
 * Изменено: 03 ноября 2021, среда
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

const BasketComponent = {
    sidebar: {
        amount: document.querySelector('.basket-sidebar-amount')
    },
    coupon: {
        node: {
            input: document.getElementById('coupon'),
            message: document.querySelector('.basket-coupon .basket-coupon-message'),
            copy: document.querySelector('[data-controller=copyCouponToBuffer]'),
        }
    },
    /*informer: {
        discount: {
            node: document.querySelector('.discount-informer'),
            percent: document.querySelector('.discount-informer .discount-informer-percent'),
            amount: document.querySelector('.discount-informer .discount-informer-amount'),
        }
    },*/
    product: {
        node: {
            list: document.querySelector('.basket-rows')
        }
    },
    get: {},
    set: {},
    //params: {},
    init: {},
    controller: {},
}

// Инициализация компонента
BasketComponent.init.component = function (data) {
    BasketComponent.data = data;
    BasketComponent.init.controllers();
    BasketComponent.init.sidebar();
    BasketComponent.init.fields();
    //BasketComponent.controller.showCouponWindow('TEST_COUPON');
};
BasketComponent.init.controllers = function () {
    const controllers = document.querySelectorAll('a[href^="javascript:void(0)"][data-controller]');
    if (controllers.length === 0) return;
    for (let i = 0; i < controllers.length; i++) {
        const node = controllers[i];
        const controller = node.dataset.controller;
        if (typeof BasketComponent.controller[controller] === 'undefined') {
            continue;
        }
        node.addEventListener('click', BasketComponent.controller[controller]);
    }
};
BasketComponent.init.sidebar = function () {
    const sidebar = document.querySelector('.basket-sidebar');
    const contentOffsetTop = App.util.getOffset(document.querySelector('.basket-content')).top - 20;
    if (window.pageYOffset > contentOffsetTop) {
        sidebar.classList.add('fixed');
    } else {
        sidebar.classList.remove('fixed');
    }
    window.addEventListener('scroll', function () {
        if (window.pageYOffset > contentOffsetTop) {
            sidebar.classList.add('fixed');
        } else {
            sidebar.classList.remove('fixed');
        }
    }, false);
};
BasketComponent.init.fields = function () {
    if (document.forms['basket-popup-register-form']) {
        const emailConfirm = document.forms['basket-popup-register-form'].elements['emailConfirm'];
        const passwordConfirm = document.forms['basket-popup-register-form'].elements['passwordConfirm'];

        if (emailConfirm) {
            emailConfirm.addEventListener('contextmenu', function (event) {
                event.preventDefault();
            });
            emailConfirm.addEventListener('paste', function (event) {
                event.preventDefault();
            });
        }
        if (passwordConfirm) {
            passwordConfirm.addEventListener('contextmenu', function (event) {
                event.preventDefault();
            });
            passwordConfirm.addEventListener('paste', function (event) {
                event.preventDefault();
            });
        }
    }

    if (BasketComponent.get.coupon.node.copy()) {
        BasketComponent.get.coupon.node.copy().addEventListener('click', function () {
            const _self = this;
            const coupon = _self.innerText;
            if (coupon) {
                navigator.clipboard.writeText(coupon).then(function () {
                    _self.style.color = '#e3e3e3';
                    _self.style.borderColor = '#f0f0f0';
                    alert('Купон ' + coupon + ' скопирован в буфер');
                });
            }
        });
    }
};

// POPUP - окна
BasketComponent.controller.showLoginWindow = function () {
    const content = document.querySelector('[data-popup-code=basket-popup-login-form]');
    const pageTitle = document.title;
    const popup = BX.Main.PopupManager.create('popup-login-form', null, {
        closeIcon: true,
        autoHide: true,
        closeByEsc: false,
        lightShadow: false,
        overlay: {
            backgroundColor: 'black',
            opacity: 40
        }
    });

    document.title = 'Авторизация на сайте';

    popup.setContent(content);
    popup.setAnimation('scale');
    popup.show();

    popup.handleOverlayClick = function (event) {
        document.title = pageTitle;
        event.stopPropagation();
        popup.close();
    };
}
BasketComponent.controller.showRegisterWindow = function () {
    const content = document.querySelector('[data-popup-code=basket-popup-register-form]');
    const pageTitle = document.title;
    const popup = BX.Main.PopupManager.create('popup-register-form', null, {
        closeIcon: true,
        autoHide: true,
        closeByEsc: false,
        lightShadow: false,
        overlay: {
            backgroundColor: 'black',
            opacity: 40
        }
    });

    document.title = 'Регистрация на сайте';

    popup.setContent(content);
    popup.setAnimation('scale');
    popup.show();

    popup.handleOverlayClick = function (event) {
        document.title = pageTitle;
        event.stopPropagation();
        popup.close();
    };
};

/**
 * @deprecated since 2020-09-04
 * @link https://megre.bitrix24.ru/workgroups/group/9/tasks/task/view/5107/
 */
/*BasketComponent.controller.showCouponWindow = function (coupon) {
    if (!BasketComponent.get.coupon.node.copy()) return;
    const content = document.querySelector('[data-popup-code=basket-popup-coupon-form]');
    const pageTitle = document.title;
    const popup = BX.Main.PopupManager.create('popup-coupon-form', null, {
        closeIcon: true,
        autoHide: true,
        closeByEsc: false,
        lightShadow: false,
        overlay: {
            backgroundColor: 'black',
            opacity: 40
        }
    });

    document.title = 'Спасибо за регистрацию';

    BasketComponent.set.coupon.copy(coupon);

    popup.setContent(content);
    popup.setAnimation('scale');
    popup.show();

    popup.handleOverlayClick = function (event) {
        document.title = pageTitle;
        event.stopPropagation();
        //location.reload();
        //popup.close();
        window.location.href = BasketComponent.get.data('PARAM').url.order;
    };
}*/

// Авторизация и регистрация
BasketComponent.controller.login = function () {
    BX.showWait();

    const login = document.forms['basket-popup-login-form'].elements['login'];
    const password = document.forms['basket-popup-login-form'].elements['password'];
    const remember = document.forms['basket-popup-login-form'].elements['remember'];
    const errorList = document.querySelector('.basket-popup-login-form .basket-popup-form-error-list');

    errorList.style.display = '';

    BX.ajax.runComponentAction(
        'native:login.form',
        'loginByLoginOrEmailAndPassword',
        {
            mode: 'class',
            data: {
                request: {
                    login: login.value,
                    password: password.value,
                    remember: remember.checked,
                }
            }
        })
        .then(function (response) {
            BX.closeWait();
            if (response.status !== 'success') {
                return;
            }

            response = response.data;

            if (response.error === true) {
                if (response.message) {
                    showErrors(response.message);
                }
                return;
            }

            if (response.success === true) {
                window.location.href = BasketComponent.get.data('PARAM').url.order;
            }
        });

    function showErrors(errors) {
        if (typeof errors === 'object') {
            errors = errors.join('<br>');
        }
        errorList.innerHTML = errors
        errorList.style.display = 'block';
    }
};
BasketComponent.controller.register = function () {
    BX.showWait();

    const email = document.forms['basket-popup-register-form'].elements['email'];
    const emailConfirm = document.forms['basket-popup-register-form'].elements['emailConfirm'];
    const password = document.forms['basket-popup-register-form'].elements['password'];
    const passwordConfirm = document.forms['basket-popup-register-form'].elements['passwordConfirm'];
    const errorList = document.querySelector('.basket-popup-register-form .basket-popup-form-error-list');

    errorList.style.display = '';

    const request = {
        email: email.value,
        emailConfirm: emailConfirm.value,
        password: password.value,
        passwordConfirm: passwordConfirm.value,
    }

    BX.ajax.runComponentAction(
        'native:registration.form',
        'registerByEmailAndPassword',
        {
            mode: 'class',
            data: {
                request: request
            }
        })
        .then(function (response) {
            BX.closeWait();


            if (response.status !== 'success') {
                return;
            }

            response = response.data;

            if (response.error === true) {
                if (response.message) {
                    showErrors(response.message);
                }
                return;
            }

            window.location.href = BasketComponent.get.data('PARAM').url.order;

            /**
             * @deprecated since 2020-09-04
             * @link https://megre.bitrix24.ru/workgroups/group/9/tasks/task/view/5107/
             */
            /*if (response.success === true && response.hasOwnProperty('coupon')) {
                BasketComponent.controller.showCouponWindow(response.coupon);
            }*/
        });

    function showErrors(errors) {
        if (typeof errors === 'object') {
            errors = errors.join('<br>');
        }
        errorList.innerHTML = errors
        errorList.style.display = 'block';
    }
};

// Действия над списком товаров
BasketComponent.controller.updateQuantity = function (node) {
    node = node.type === 'text' ? node : this;
    const product = BasketComponent.get.product.data(node.parentNode.parentNode.parentNode);

    let direction;
    let quantity = +product.QUANTITY.VALUE;

    if (node.type !== 'text') {
        direction = this.innerText === '+' ? 'up' : 'down';
    }

    const row = product.ROW;
    const hasEvent = product.hasOwnProperty('HAS_EVENT') ? product['HAS_EVENT'] : false;
    const min = +product.QUANTITY.MIN;
    const max = +product.QUANTITY.MAX;
    const ratio = +product.QUANTITY['RATIO'];
    const price = parseFloat(product.PRICE.VALUE);
    const priceBase = parseFloat(product.PRICE['BASE'].VALUE);
    //const excludeFromDiscount = product['EXCLUDE_FROM_DISCOUNT'];

    let amount = 0;

    // Получим новое количество
    if (node.type === 'text') {
        if (node.value === 0) {
            quantity = ratio;
        } else {
            if (Number.isInteger(node.value / ratio)) {
                quantity = node.value;
            } else {
                quantity = ratio;
            }
        }
    } else {
        quantity = direction === 'up' ? quantity + ratio : quantity - ratio;
    }
    if (quantity > max) {
        quantity = max;

        BX.UI.Notification.Center.notify({
            content: 'Достигнут лимит по остатку товара',
            autoHideDelay: 3000
        });

    } else if (quantity < min) {
        quantity = min;
    }
    if (quantity === min) {
        return;
    }

    // Получим новую сумму
    amount = Math.round((price * quantity) * 100) / 100;
    amount = {
        value: amount,
        formatted: BasketComponent.get.amount.format(amount, true),
        base: {
            value: Math.round((priceBase * quantity) * 100) / 100
        }
    }

    // Обновим данные по товару
    BasketComponent.set.product.data(product, {quantity: quantity, amount: amount});

    // Обновим данные по корзине
    BasketComponent.controller.recalculate();

    // Создаем отложенный запрос на обновление количества в базе
    // hasEvent необходимо для того, чтобы не регистрировать множество слушателей события
    if (node.type === 'text') {
        BX.ajax.post(
            BasketComponent.get.data('PARAM').url.ajax,
            {
                action: 'updateQuantity',
                rowId: row,
                quantity: product.QUANTITY.VALUE,
                //excludeFromDiscount: excludeFromDiscount
            }, function () {
                BasketComponent.set.product.last.updated({
                    row: false,
                    quantity: false,
                    //excludeFromDiscount: false,
                });
            }
        );
    } else {
        if (hasEvent === false) {
            node.addEventListener('mouseout', updateQuantity);

            // Отправим запрос на обновление данных в базе
            function updateQuantity() {
                BX.ajax.post(
                    BasketComponent.get.data('PARAM').url.ajax,
                    {
                        action: 'updateQuantity',
                        rowId: row,
                        quantity: product.QUANTITY.VALUE,
                        //excludeFromDiscount: excludeFromDiscount
                    }, function () {

                        // МОЖНО ПЕРЕПИСАТЬ СОБЫТИЕ ТАКИМ ОБРАЗОМ, ЧТОБЫ СЛУШАТЕЛЬ САМ УДАЛЯЛСЯ ПОСЛЕ ОДНОГО ЗАПУСКА
                        // Через options - once
                        // https://developer.mozilla.org/ru/docs/Web/API/EventTarget/addEventListener

                        // Удалим событие на отправку обновления количества в базе
                        BasketComponent.set.product.event(row, false);
                        node.removeEventListener('mouseout', updateQuantity);
                        // Очистим последний обновленный товар, так как запрос прошел успешно
                        BasketComponent.set.product.last.updated({
                            row: false,
                            quantity: false,
                            //excludeFromDiscount: false,
                        });
                    }
                );
            }
        }
    }
};
BasketComponent.controller.deleteRow = function () {
    const product = BasketComponent.get.product.data(this.parentNode.parentNode.parentNode);

    if (product['GIFT'] === 'Y' && confirm('Вы уверены, что хотите удалить подарок?') === false) {
        return;
    }

    delete BasketComponent.get.data('PRODUCT')['LIST'][product.ROW];
    BasketComponent.set.product.last.deleted({
        row: product.ROW,
        //excludeFromDiscount: product['EXCLUDE_FROM_DISCOUNT']
    });
    product.NODE.row.style.opacity = '0.4';

    if (Object.keys(BasketComponent.get.data('PRODUCT')['LIST']).length > 0) {
        BX.ajax.post(
            BasketComponent.get.data('PARAM').url.ajax,
            {
                action: 'deleteRow',
                rowId: product.ROW,
                //excludeFromDiscount: product['EXCLUDE_FROM_DISCOUNT']
            }, function () {
                product.NODE.row.remove();
                BasketComponent.set.product.last.deleted({
                    row: false,
                    //excludeFromDiscount: false,
                });
            }
        );
    } else {
        BX.showWait();
        BX.ajax.post(
            BasketComponent.get.data('PARAM').url.ajax,
            {
                action: 'deleteRow',
                rowId: product.ROW,
                //excludeFromDiscount: product['EXCLUDE_FROM_DISCOUNT']
            }, function () {
                location.reload();
            }
        );
    }

    BasketComponent.controller.recalculate();
};

// Купоны
BasketComponent.controller.coupon = function () {
    const button = this;

    if (
        !BasketComponent.get.coupon.node.input() ||
        BasketComponent.get.coupon.last.value() === BasketComponent.get.coupon.value()
    ) return;

    BX.showWait();
    button.style.opacity = '0.4';
    BasketComponent.get.product.node.list().style.opacity = '0.4';

    BX.ajax.post(
        BasketComponent.get.data('PARAM').url.ajax,
        {
            action: 'setCoupon',
            coupon: {
                value: BasketComponent.get.coupon.value(),
                last: {
                    value: BasketComponent.get.coupon.last.value(),
                }
            },
        }, function (response) {
            response = JSON.parse(response);

            if (response.deleted === true) {
                BasketComponent.set.coupon.message(response.message);
                location.reload();
                return;
            }

            if (
                response.error === true ||
                (
                    response.success === true && response['isExist'] === true && response['added'] === false
                ) &&
                response.message
            ) {
                BasketComponent.set.coupon.message(response.message);
                button.style.opacity = '1';
                BasketComponent.get.product.node.list().style.opacity = '1';
                BX.closeWait();
                return;
            }

            if (BasketComponent.get.coupon.value() === BasketComponent.get.coupon.last.value()) {
                button.style.opacity = '1';
                BasketComponent.get.product.node.list().style.opacity = '1';
                BX.closeWait();
            } else {
                BasketComponent.set.coupon.message(response.message);
                location.reload();
            }
        });
}

// Пересчет суммы корзины
BasketComponent.controller.recalculate = function () {
    const products = BasketComponent.get.product.list();
    let amount = {
        value: 0,
        base: {
            value: 0
        }
    };

    let existOfflineProduct = false; // флаг наличия офлайн-товаров в корзине

    for (let row in products) {
        const product = products[row];

        if (product['IS_OFFLINE'] === 'Y') {
            existOfflineProduct = true;
        }

        // Исключаем товар из расчета общей скидки - 3%, 5%, 7%, 20%
        //if (product['EXCLUDE_FROM_DISCOUNT'] === 'Y') continue;

        amount.value = amount.value + parseFloat(product['AMOUNT']['VALUE']);

        // Увеличим базовую сумму корзины для последующих расчетов скидок
        amount.base.value = amount.base.value + parseFloat(product['AMOUNT']['BASE']['VALUE']);
    }

    BasketComponent.set.amount.value(amount.value);
    BasketComponent.set.amount.base.value(amount.base.value);
    BasketComponent.set.amount.total(amount.value);

    // Перепроверим условия для скидки
    BasketComponent.controller.reloadDiscount();

    // Поправим видимость информера об офлайн-товарах
    // В списке были офлайн-товары и их все удалили
    // Тогда скроем информер
    if (BasketComponent.get.data('PARAM')['hasOfflineProduct'] === 'Y' && existOfflineProduct === false) {
        document.getElementById('has-offline-product').style.display = 'none'
    }
};

// Пересчет скидки
BasketComponent.controller.reloadDiscount = function () {
    const amount = {
        /*previous: +BasketComponent.get.discount.previous()['VALUE'],
        current: +BasketComponent.get.discount.current()['VALUE'],
        next: +BasketComponent.get.discount.next()['VALUE'],
        last: +BasketComponent.get.discount.last()['VALUE'],*/
        basket: {
            base: {
                // Для расчёта скидки используем Базовую суммы корины, то есть без учета скидок
                value: BasketComponent.get.amount.base.value()
            }
        }
    }

    if (amount.next === 0 && amount.basket.base.value <= amount.last) {
        amount.next = amount.last;
    }

    // Если достигли нового уровня скидки
    // Тогда обновим страницу для применения скидки
    if (
        amount.next !== 0 &&
        (
            (amount.basket.base.value < amount.previous) ||
            (amount.basket.base.value < amount.current) ||
            (amount.basket.base.value > amount.next)
        )
    ) {
        BX.showWait();
        BasketComponent.get.product.node.list().style.opacity = '0.4';
        // Если имеется товар с несохраненным количеством в базе
        // Или может быть имеется товар, который надо удалить перед перезагрузкой страницы
        if (BasketComponent.get.product.last.updated()['QUANTITY'] > 0) {
            BX.ajax.post(
                BasketComponent.get.data('PARAM').url.ajax,
                {
                    action: 'updateQuantity',
                    rowId: BasketComponent.get.product.last.updated()['ROW'],
                    quantity: BasketComponent.get.product.last.updated()['QUANTITY'],
                    //excludeFromDiscount: BasketComponent.get.product.last.updated()['EXCLUDE_FROM_DISCOUNT']
                }, function () {
                    location.reload();
                }
            );
        } else if (BasketComponent.get.product.last.deleted()['ROW'] > 0) {
            BX.ajax.post(
                BasketComponent.get.data('PARAM').url.ajax,
                {
                    action: 'deleteRow',
                    rowId: BasketComponent.get.product.last.deleted()['ROW'],
                    //excludeFromDiscount: BasketComponent.get.product.last.deleted()['EXCLUDE_FROM_DISCOUNT']
                }, function () {
                    location.reload();
                }
            );
        } else {
            location.reload();
        }
    }

    /*if (amount.basket.base.value > amount.last) {
        BasketComponent.set.discount.informer.visibility('hide');
    } else {
        const remain = Math.round((amount.next - amount.basket.base.value) * 100) / 100;
        const percent = BasketComponent.get.discount.next().hasOwnProperty('PERCENT') ? BasketComponent.get.discount.next()['PERCENT']['FORMATTED'] : 0;
        if (remain > 0 && percent !== 0) {
            BasketComponent.set.discount.informer.amount(BasketComponent.get.amount.format(remain));
            BasketComponent.set.discount.informer.percent(percent);
            BasketComponent.set.discount.informer.visibility('show');
        }
    }*/
}

// Геттеры
BasketComponent.get.data = function (code) {
    return BasketComponent.data.hasOwnProperty(code) ? BasketComponent.data[code] : BasketComponent.data;
};
BasketComponent.get.amount = {
    value: function () {
        return parseFloat(BasketComponent.get.data('BASKET')['AMOUNT']['VALUE']);
    },
    base: {
        value: function () {
            return parseFloat(BasketComponent.get.data('BASKET')['AMOUNT']['BASE']['VALUE']);
        }
    },
    format: function (amount, withoutCurrency) {
        withoutCurrency = withoutCurrency || false;
        if (parseInt(amount) !== amount) {
            amount = new Intl.NumberFormat('ru-RU', {minimumFractionDigits: 2}).format(amount);
            amount = amount.replace(',', '.');
        } else {
            amount = new Intl.NumberFormat('ru-RU').format(amount);
        }
        if (withoutCurrency === false) {
            amount = amount + ' ' + BasketComponent.get.currency.formatted();
        }
        return amount;
    }
};
BasketComponent.get.currency = {
    formatted: function () {
        return BasketComponent.get.data('BASKET')['CURRENCY']['FORMATTED'];
    }
};
BasketComponent.get.product = {
    data: function (productRowNode) {
        let data = BasketComponent.get.data('PRODUCT')['LIST'];

        data = data[productRowNode.dataset.row];
        data['ROW'] = productRowNode.dataset.row;
        data['NODE'] = {
            row: productRowNode,
            amount: productRowNode.querySelector('.product-amount'),
            quantity: productRowNode.querySelector('.product-quantity-value'),
        };

        return data;
    },
    list: function () {
        return BasketComponent.get.data('PRODUCT')['LIST'];
    },
    node: {
        list: function () {
            return BasketComponent.product.node.list;
        }
    },
    last: {
        updated: function () {
            return BasketComponent.get.data('PRODUCT')['LAST_UPDATED'];
        },
        deleted: function () {
            return BasketComponent.get.data('PRODUCT')['LAST_DELETED'];
        }
    }
};
/*BasketComponent.get.discount = {
    previous: function () {
        return BasketComponent.get.data('BASKET')['DISCOUNT']['PREVIOUS'];
    },
    current: function () {
        return BasketComponent.get.data('BASKET')['DISCOUNT']['CURRENT'];
    },
    next: function () {
        return BasketComponent.get.data('BASKET')['DISCOUNT']['NEXT'];
    },
    last: function () {
        return BasketComponent.get.data('BASKET')['DISCOUNT']['LAST'];
    },
};*/
BasketComponent.get.coupon = {
    value: function () {
        return BasketComponent.coupon.node.input ? BasketComponent.coupon.node.input.value : false;
    },
    node: {
        input: function () {
            return BasketComponent.coupon.node.input;
        },
        message: function () {
            return BasketComponent.coupon.node.message;
        },
        copy: function () {
            return BasketComponent.coupon.node.copy;
        }
    },
    last: {
        value: function () {
            return BasketComponent.get.data('COUPON')['VALUE'];
        }
    }
};

// Сеттеры
BasketComponent.set.amount = {
    value: function (value) {
        if (value > 0) {
            value = Math.round(value * 100) / 100
        } else {
            value = 0;
        }
        BasketComponent.data['BASKET']['AMOUNT']['VALUE'] = value;
    },
    base: {
        value: function (value) {
            if (value > 0) {
                value = Math.round(value * 100) / 100
            } else {
                value = 0;
            }
            BasketComponent.data['BASKET']['AMOUNT']['BASE']['VALUE'] = value;
        }
    },
    total: function (value) {
        if (value < 0) value = 0;
        BasketComponent.sidebar.amount.innerText = BasketComponent.get.amount.format(value);
    }
};
BasketComponent.set.product = {
    data: function (product, data) {
        // Обновим визуальную часть
        product.NODE.quantity.value = data.quantity;
        product.NODE.amount.innerText = data.amount.formatted;
        // Обновим данные
        BasketComponent.set.product.quantity(product.ROW, data.quantity);
        BasketComponent.set.product.amount.value(product.ROW, data.amount.value);
        BasketComponent.set.product.amount.base.value(product.ROW, data.amount.base.value);
        BasketComponent.set.product.event(product.ROW, true);
        // Запомним последний обновленный товар
        data.row = product.ROW;
        //data.excludeFromDiscount = product['EXCLUDE_FROM_DISCOUNT'];
        BasketComponent.set.product.last.updated(data);
    },
    quantity: function (row, value) {
        BasketComponent.get.data('PRODUCT')['LIST'][row]['QUANTITY']['VALUE'] = value;
    },
    amount: {
        value: function (row, value) {
            BasketComponent.get.data('PRODUCT')['LIST'][row]['AMOUNT']['VALUE'] = value;
        },
        base: {
            value: function (row, value) {
                BasketComponent.get.data('PRODUCT')['LIST'][row]['AMOUNT']['BASE']['VALUE'] = value;
            }
        }
    },
    event: function (row, value) {
        BasketComponent.get.data('PRODUCT')['LIST'][row]['HAS_EVENT'] = value;
    },
    last: {
        updated: function (data) {
            if (data.hasOwnProperty('row')) {
                BasketComponent.get.data('PRODUCT')['LAST_UPDATED']['ROW'] = data.row;
            }
            if (data.hasOwnProperty('quantity')) {
                BasketComponent.get.data('PRODUCT')['LAST_UPDATED']['QUANTITY'] = data.quantity;
            }
            /*if (data.hasOwnProperty('excludeFromDiscount')) {
                BasketComponent.get.data('PRODUCT')['LAST_UPDATED']['EXCLUDE_FROM_DISCOUNT'] = data.excludeFromDiscount;
            }*/
        },
        deleted: function (data) {
            if (data.hasOwnProperty('row')) {
                BasketComponent.get.data('PRODUCT')['LAST_DELETED']['ROW'] = data.row;
            }
            /*if (data.hasOwnProperty('excludeFromDiscount')) {
                BasketComponent.get.data('PRODUCT')['LAST_DELETED']['EXCLUDE_FROM_DISCOUNT'] = data.excludeFromDiscount;
            }*/
        }
    },
};
/*BasketComponent.set.discount = {
    informer: {
        amount: function (value) {
            BasketComponent.informer.discount.amount.innerText = value;
        },
        percent: function (value) {
            BasketComponent.informer.discount.percent.innerText = value;
        },
        visibility: function (state) {
            if (state === 'show') {
                BasketComponent.informer.discount.node.style.display = 'block';
            } else {
                BasketComponent.informer.discount.node.style.display = 'none';
            }
        }
    }
};*/
BasketComponent.set.coupon = {
    copy: function (coupon) {
        BasketComponent.get.coupon.node.copy().innerText = coupon;
    },
    message: function (message) {
        BasketComponent.get.coupon.node.message().innerText = message;
    },
};
