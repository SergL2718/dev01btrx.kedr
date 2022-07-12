/*
 * @author Артамонов Денис <artamonov.ceo@gmail.com>
 * @updated 13.08.2020, 19:27
 * @copyright 2011-2020
 */

'use strict';

const ProductListComponent = {

    nodes: {
        buttons: {
            addToBasket: document.querySelectorAll('button.bxr-basket-add'),
            tradeRequest: document.querySelectorAll('button.bxr-trade-request'),
        }
    },

    checkedProducts: {}, // Складываем товары, которые уже были проверены через ajax

    run: function () {
        this.init.buttons();
    },

    init: {
        buttons: function () {
            if (ProductListComponent.nodes.buttons.length === 0) return;

            for (let code in ProductListComponent.nodes.buttons) {

                let buttons = ProductListComponent.nodes.buttons[code];

                if (buttons.length === 0) continue;

                for (let i = 0; i < buttons.length; i++) {
                    let button = buttons[i];
                    button.addEventListener('click', ProductListComponent.action.button[code], false);
                }
            }
        }
    },

    action: {
        button: {
            addToBasket: function (event) {
                const _self = this;
                const productId = _self.parentNode.querySelector('input[data-item]').dataset.item;
                if (!productId) return;

                // Проверим, имеется ли товар в уже проверенных товарах

                if (ProductListComponent.checkedProducts.hasOwnProperty(productId)) {
                    let product = ProductListComponent.checkedProducts[productId];

                    if (product.canAdd === false) {
                        event.preventDefault();
                        event.stopPropagation();

                        if (product.message) {
                            BX.UI.Notification.Center.notify({content: product.message, autoHideDelay: 600000});
                        }
                    }

                } else {
                    // Останавливаем стандартные действия
                    event.preventDefault();
                    event.stopPropagation();

                    // Проверим возможность добавления товара в корзину
                    BX.ajax.runComponentAction(
                        'native:order.create',
                        'checkAbilityAddToBasket',
                        {
                            mode: 'class',
                            data: {
                                productId: productId
                            }
                        })
                        .then(function (response) {
                            response = response.data;

                            if (response.hasOwnProperty('redirect')) {
                                window.location.href = response.redirect;
                                return;
                            }

                            ProductListComponent.checkedProducts[productId] = response;
                            if (response.canAdd === false) {
                                if (response.message) {
                                    BX.UI.Notification.Center.notify({
                                        content: response.message,
                                        autoHideDelay: 600000
                                    });
                                }

                            } else {
                                _self.click();
                            }
                        });
                }
            },

            tradeRequest: function (event) {
                const _self = this;
                const productId = _self.dataset.tradeId;
                if (!productId) return;

                event.preventDefault();
                event.stopPropagation();

                BX.ajax.runComponentAction(
                    'native:order.create',
                    'checkAbilityAddToBasket',
                    {
                        mode: 'class',
                        data: {
                            productId: productId
                        }
                    })
                    .then(function (response) {
                        response = response.data;

                        if (response.hasOwnProperty('redirect')) {
                            window.location.href = response.redirect;
                            return;
                        }

                        _self.click();
                    });
            }
        }
    }
};

ProductListComponent.run();
