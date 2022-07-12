/*
 * @author Артамонов Денис <artamonov.ceo@gmail.com>
 * @updated 28.05.2020, 10:02
 * @copyright 2011-2020
 */

var formRequestMsg;
var trade_name;
var trade_id;
var trade_link;
var current_offer_id = 0;

$(document).on(
    'click',
    '.quantity-button-offer-minus, .quantity-button-offer-plus',
    function () {
        if ($(this).hasClass('quantity-button-offer-minus')) {
            id = parseInt($(this).attr('id').replace('quantity-button-minus-', ''));
            valOld = parseInt($('#quantity-offer-' + id).val());
            valNew = valOld - 1;
            if (valNew < 1) valNew = 1;
            $('#quantity-offer-' + id).val(valNew);
        }
        ;
        if ($(this).hasClass('quantity-button-offer-plus')) {
            id = parseInt($(this).attr('id').replace('quantity-button-plus-', ''));
            valOld = parseInt($('#quantity-offer-' + id).val());
            valNew = valOld + 1;
            $('#quantity-offer-' + id).val(valNew);
        }
        ;
    }
);

function setBasketIds(id) {
    $('#bxr-market-detail-basket-btn-wrap .offers-btn-wrap').hide();
    $('#bxr-market-detail-basket-btn-wrap .offers-btn-wrap[data-item="' + id + '"]').show();
}

$(document).on('click', '.bxr-detail-tabs li', function () {
    var tabCode = $(this).data('tab');
    $('.bxr-detail-tabs li').removeClass('active');
    $(this).addClass('active');
    $('.bxr-detail-tab').hide();
    $('.bxr-detail-tab[data-tab="' + tabCode + '"]').show();

    if (tabCode == "element-video")
        $(window).trigger('resize');
});

$(document).on('click', '.bxr-detail-top-tabs li', function () {
    var tabCode = $(this).data('tab');
    $('.bxr-detail-tab').hide();
    $('.bxr-detail-tabs li').removeClass('active');
    $('.bxr-detail-tabs li[data-tab="' + tabCode + '"]').addClass('active');
    $('.bxr-detail-tab[data-tab="' + tabCode + '"]').show();
    var tabOffset = 0;
    if ($('.bxr-menuline').hasClass('affix') || $('.bxr-menuline').hasClass('affix-top')) tabOffset = $('.bxr-menuline').height();
    window.BXReady.scrollTo('.bxr-detail-tabs', {offsetTop: 2 * tabOffset});

    if (tabCode == "element-video")
        $(window).trigger('resize');
});

$(document).on('click', '.bxr-share-group', function () {
    $('.bxr-share-icon-wrap').toggle();
});

function resizeVideo() {
    if ($("#container-video-mej").length > 0) {
        var correlation = 1.7;
        w = $(".mej").parent("div").width();

        if (w < 200)
            w = 200;

        $(".mej .mejs-container").not(".mejs-container-fullscreen").css({
            width: w + "px",
            height: (w / correlation) + "px"
        });
    }
}

$(window).resize(function () {
    resizeVideo();
});

$(document).ready(function () {
    $($('.bxr-detail-tabs li')[0]).click();

    if ($("#container-video-iframe").length > 0) {
        $('.element-video-card-iframe .video-img').click(function () {
            url = $(this).attr("data-url");

            if (url === undefined || url == "")
                return false;

            if ((url.indexOf('youtu.be') + 1) || (url.indexOf('youtube.com/watch') + 1)) {
                url = url.replace(new RegExp("https:", "i"), '');
                url = url.replace(new RegExp("http:", "i"), '');
                url = url.replace(new RegExp("\\/\\/youtu.be\\/", "i"), 'http://www.youtube.com/v/');
                url = url.replace(new RegExp("watch\\?v=", "i"), 'v/');
            }

            $.fancybox({
                'type': 'iframe',
                'href': url,
                'transitionIn': 'elastic',
                'transitionOut': 'elastic',
                'speedIn': 600,
                'speedOut': 200,
                'overlayShow': false
            });
            return false;
        });
    }

    if ($("#container-video-mej").length > 0) {
        $('video').mediaelementplayer({
            plugins: ['flash', 'silverlight', 'youtube', 'vimeo'],
            success: function () {
                resizeVideo();
            }
        });
    }
});

(function (window) {
    if (!!window.JCShareButtons) {
        return;
    }

    window.JCShareButtons = function (containerId) {
        if (containerId) {
            var container = BX(containerId);
            if (container) {
                this.shareButtons = BX.findChildren(container, {tagName: 'LI'}, true);
                if (this.shareButtons && this.shareButtons.length >= 1) {
                    BX.bind(this.shareButtons[this.shareButtons.length - 1], 'click', BX.delegate(this.alterVisibility, this));
                }
            }
        }
    };

    window.JCShareButtons.prototype.alterVisibility = function () {
        if (this.shareButtons && this.shareButtons.length >= 1) {
            for (var i = 0; i < this.shareButtons.length - 1; i++) {
                var li = this.shareButtons[i];
                li.style.display = li.style.display == "none" ? "" : "none";
            }
        }
    };
})(window);

function __function_exists(function_name) {
    if (typeof function_name == 'string') {
        return (typeof window[function_name] == 'function');
    } else {
        return (function_name instanceof Function);
    }
}


const ProductComponent = {

    nodes: {
        buttons: {
            addToBasket: document.querySelectorAll('button.bxr-basket-add')
        }
    },

    checkedProducts: {}, // Складываем товары, которые уже были проверены через ajax

    run: function () {
        this.init.buttons();
    },

    init: {
        buttons: function () {
            if (ProductComponent.nodes.buttons.length === 0) return;

            for (let code in ProductComponent.nodes.buttons) {

                let buttons = ProductComponent.nodes.buttons[code];

                if (buttons.length === 0) continue;

                for (let i = 0; i < buttons.length; i++) {
                    let button = buttons[i];
                    button.addEventListener('click', ProductComponent.action.button[code], false);
                }
            }
        }
    },

    action: {
        button: {
            addToBasket: function (event) {
                let _self = this;
                let productId = _self.parentNode.querySelector('input[data-item]').dataset.item;
                if (!productId) return;

                // Проверим, имеется ли товар в уже проверенных товарах

                if (ProductComponent.checkedProducts.hasOwnProperty(productId)) {
                    let product = ProductComponent.checkedProducts[productId];

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

                            ProductComponent.checkedProducts[productId] = response;
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
            }
        }
    }
};

ProductComponent.run();
