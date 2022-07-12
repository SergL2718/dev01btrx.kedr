if (window){
    window.bxrM2EcommerceV1 = {
        resizeVerticalBlock: function(isLazy){
            var $maxHeight = [];
            var $maxAvailHeight = [];
            var $maxNameHeight = [];
            var $maxPriceHeight = [];

            //clear last settings
            $('.bxr-m2-ecommerce-v1[data-resize=1]').each(function() {
                if ($(this).data('resize') == 2 && isLazy != "true") 
                    return;
                
                $nameContainer = $(this).children('.bxr-element-container').children('.bxr-element-name');
                $availContainer = $(this).children('.bxr-element-container').children('.bxr-element-avail-wrap');
                $priceContainer = $(this).children('.bxr-element-container').children('.bxr-element-price');
                
                $(this).height('auto');
                $(this).children('.bxr-element-container').height('auto');
                
                $nameContainer.height('auto');
                $availContainer.height('auto');
                $priceContainer.height('auto');

                $minus = $(this).children('.bxr-element-container').find('.bxr-quantity-button-minus');
                $qty = $(this).children('.bxr-element-container').find('.bxr-quantity-text');
                $plus = $(this).children('.bxr-element-container').find('.bxr-quantity-button-plus');
                $basket = $(this).children('.bxr-element-container').find('.bxr-basket-add');

                $containerWidth = $(this).children('.bxr-element-container').width() - 28;

                $minusWidth = $minus.width();
                $qtyWidth = $qty.width();
                $plusWidth = $plus.width();
                $basketWidth = $basket.width();
                $basketActionWidth = $minusWidth + $qtyWidth + $plusWidth + $basketWidth;

                if ($basketActionWidth > $containerWidth || $containerWidth < 174) {
                    $basket.find('.fa').addClass("bxr-fa-small");
                    $basket.find('.basket-text').html("");
                }
            });

            // resize img, names, prices
            $('.bxr-m2-ecommerce-v1[data-resize=1]').each(function(){
                uid = $(this).data('uid');

                if ($(this).data('resize') == 2 && isLazy != "true") 
                    return;

                $nameContainer = $(this).children('.bxr-element-container').children('.bxr-element-name');
                $availContainer = $(this).children('.bxr-element-container').children('.bxr-element-avail-wrap');
                $priceContainer = $(this).children('.bxr-element-container').children('.bxr-element-price');

                if (!(uid in $maxNameHeight))
                    $maxNameHeight[uid] = 0;

                if (!(uid in $maxAvailHeight))
                    $maxAvailHeight[uid] = 0;

                if (!(uid in $maxPriceHeight))
                    $maxPriceHeight[uid] = 0;

                if ($nameContainer.height() > $maxNameHeight[uid]) $maxNameHeight[uid] = $nameContainer.height();
                if ($availContainer.height() > $maxAvailHeight[uid]) $maxAvailHeight[uid] = $availContainer.height();
                if ($priceContainer.height() > $maxPriceHeight[uid]) $maxPriceHeight[uid] = $priceContainer.height();

            });

            $('.bxr-m2-ecommerce-v1[data-resize=1]').each(function(){
                uid = $(this).data('uid');

                if ($(this).data('resize') == 2 && isLazy != "true") 
                    return;

                $nameContainer = $(this).children('.bxr-element-container').children('.bxr-element-name');
                $availContainer = $(this).children('.bxr-element-container').children('.bxr-element-avail-wrap');
                $priceContainer = $(this).children('.bxr-element-container').children('.bxr-element-price');

                if ($nameContainer.height() <= $maxNameHeight[uid])
                    $nameContainer.height($maxNameHeight[uid]);

                if ($availContainer.height() <= $maxAvailHeight[uid])
                    $availContainer.height($maxAvailHeight[uid]);
                
                if ($priceContainer.height() <= $maxPriceHeight[uid])
                    $priceContainer.height($maxPriceHeight[uid]);
            });

            // resize container
            $('.bxr-m2-ecommerce-v1[data-resize=1]').each(function(){
                uid = $(this).data('uid');

                if ($(this).data('resize') == 2 && isLazy != "true") 
                    return;

                if (!(uid in $maxHeight)) 
                    $maxHeight[uid] = 0;
                if ($(this).height()>$maxHeight[uid]) $maxHeight[uid] = parseInt($(this).css("height"));
            });

            $('.bxr-m2-ecommerce-v1[data-resize=1]').each(function(){
                uid = $(this).data('uid');

                if ($(this).data('resize') == 2 && isLazy != "true") {
                    return ;
                }

                if ($(this).height() <= $maxHeight[uid]) {
                    $(this).height($maxHeight[uid]);
                    $(this).children('.bxr-element-container').height($maxHeight[uid]-22);
                }
            });
        },
        
        setHoverTop: function(isLazy) {
            $('.bxr-m2-ecommerce-v1').each(function(){
                if ($(this).data('resize') == 2 && isLazy != "true") 
                    return;
                
                $(this).data('resize', '2');

                if ($(this).find('.bxr-element-hover').length) {
                    pricePos = $(this).find('.bxr-element-price').position().top;
                    hoverHeight = $(this).find('.bxr-element-hover').height();
                    hoverTop = pricePos - hoverHeight;
                    $(this).find('.bxr-element-hover').css('top', hoverTop + 'px');
                }
            });
        },
        
        setMobileClasses: function() {
            if (jQuery.browser.mobile) {
                $('.bxr-m2-ecommerce-v1 .bxr-element-container .bxr-indicator-item-compare').addClass('always-show');
                $('.bxr-m2-ecommerce-v1 .bxr-element-container .bxr-indicator-item-favor').addClass('always-show');
            }
        },
        
        init: function(isLazy) {
            bxrM2EcommerceV1.resizeVerticalBlock(isLazy);
            bxrM2EcommerceV1.setHoverTop(isLazy);
            bxrM2EcommerceV1.setMobileClasses();
            $("img.lazy").lazyload();
        }
    };

    $(document).on("mouseover", ".bxr-m2-ecommerce-v1", function () {
        dots = $(this).find('.slick-dots');
        if (dots.length && $(this).find('.bxr-element-offers').length) {
            $(dots).css('position', 'relative');
            $(this).find('.bxr-element-offers').prepend(dots);
        }
    });

    $(document).on("mouseout", ".bxr-m2-ecommerce-v1", function () {
        dots = $(this).find('.slick-dots');
        if (dots.length && $(this).find('.slick-list').length) {
            $(dots).css('position', 'absolute');
            $(this).find('.slick-list').after(dots);
        }
    });

    $(window).on("load", function() {
        bxrM2EcommerceV1.init();    
    });
    
    window.JCCatalogECommerce = function (arParams)
    {
        this.productType = 0;
        this.showQuantity = true;
        this.showAbsent = true;
        this.secondPict = false;
        this.showOldPrice = false;
        this.showPercent = false;
        this.showSkuProps = false;
        this.basketAction = 'ADD';
        this.showClosePopup = false;
        this.useCompare = false;
        this.visual = {
            ID: '',
            PICT_ID: '',
            SECOND_PICT_ID: '',
            PICT_SLIDER_ID: '',
            QUANTITY_ID: '',
            QUANTITY_UP_ID: '',
            QUANTITY_DOWN_ID: '',
            PRICE_ID: '',
            DSC_PERC: '',
            SECOND_DSC_PERC: '',
            DISPLAY_PROP_DIV: '',
            BASKET_PROP_DIV: ''
        };
        this.product = {
            checkQuantity: false,
            maxQuantity: 0,
            stepQuantity: 1,
            isDblQuantity: false,
            canBuy: true,
            canSubscription: true,
            name: '',
            pict: {},
            id: 0,
            addUrl: '',
            buyUrl: ''
        };

        this.basketMode = '';
        this.basketData = {
            useProps: false,
            emptyProps: false,
            quantity: 'quantity',
            props: 'prop',
            basketUrl: '',
            sku_props: '',
            sku_props_var: 'basket_props',
            add_url: '',
            buy_url: ''
        };

        this.compareData = {
            compareUrl: '',
            comparePath: ''
        };

        this.defaultPict = {
            pict: null,
            secondPict: null
        };

        this.defaultSliderOptions = {
            interval: 3000,
            wrap: true
        };
        this.slider = {
            options: {},
            items: [],
            active: null,
            sliding: null,
            paused: null,
            interval: null,
            progress: null
        };

        this.checkQuantity = false;
        this.maxQuantity = 0;
        this.stepQuantity = 1;
        this.isDblQuantity = false;
        this.canBuy = true;
        this.currentBasisPrice = {};
        this.canSubscription = true;
        this.precision = 6;
        this.precisionFactor = Math.pow(10, this.precision);

        this.offers = [];
        this.offerNum = 0;
        this.treeProps = [];
        this.obTreeRows = [];
        this.showCount = [];
        this.showStart = [];
        this.selectedValues = {};

        this.obProduct = null;
        this.obQuantity = null;
        this.obQuantityUp = null;
        this.obQuantityDown = null;
        this.obPict = null;
        this.obSecondPict = null;
        this.obPrice = null;
        this.obTree = null;
        this.obBuyBtn = null;
        this.obBasketActions = null;
        this.obNotAvail = null;
        this.obDscPerc = null;
        this.obSecondDscPerc = null;
        this.obSkuProps = null;
        this.obMeasure = null;
        this.obCompare = null;

        this.obPopupWin = null;
        this.basketUrl = '';
        this.basketParams = {};

        this.treeRowShowSize = 5;
        this.treeEnableArrow = {display: '', cursor: 'pointer', opacity: 1};
        this.treeDisableArrow = {display: '', cursor: 'default', opacity: 0.2};

        this.lastElement = false;
        this.containerHeight = 0;

        this.useEnhancedEcommerce = false;
        this.dataLayerName = 'dataLayer';
        this.brandProperty = false;

        this.errorCode = 0;
        if ('object' === typeof arParams)
        {
            this.productType = parseInt(arParams.PRODUCT_TYPE, 10);
            this.showQuantity = arParams.SHOW_QUANTITY;
            this.showAbsent = arParams.SHOW_ABSENT;
            this.secondPict = !!arParams.SECOND_PICT;
            this.showOldPrice = !!arParams.SHOW_OLD_PRICE;
            this.showPercent = !!arParams.SHOW_DISCOUNT_PERCENT;
            this.showSkuProps = !!arParams.SHOW_SKU_PROPS;
            if (!!arParams.ADD_TO_BASKET_ACTION)
            {
                this.basketAction = arParams.ADD_TO_BASKET_ACTION;
            }
            this.showClosePopup = !!arParams.SHOW_CLOSE_POPUP;
            this.useCompare = !!arParams.DISPLAY_COMPARE;
            this.viewMode = 'CARD'; //arParams.VIEW_MODE || 'CARD';

            this.useEnhancedEcommerce = arParams.USE_ENHANCED_ECOMMERCE === 'Y';
            this.dataLayerName = arParams.DATA_LAYER_NAME;
            this.brandProperty = arParams.BRAND_PROPERTY;
            this.SLIDER_INTERVAL = arParams.SLIDER_INTERVAL;

            this.visual = arParams.VISUAL;
            switch (this.productType)
            {
                case 0://no catalog
                case 1://product
                case 2://set
                    if (!!arParams.PRODUCT && 'object' === typeof (arParams.PRODUCT))
                    {
                        if (this.showQuantity)
                        {
                            this.product.checkQuantity = arParams.PRODUCT.CHECK_QUANTITY;
                            this.product.isDblQuantity = arParams.PRODUCT.QUANTITY_FLOAT;
                            if (this.product.checkQuantity)
                            {
                                this.product.maxQuantity = (this.product.isDblQuantity ? parseFloat(arParams.PRODUCT.MAX_QUANTITY) : parseInt(arParams.PRODUCT.MAX_QUANTITY, 10));
                            }
                            this.product.stepQuantity = (this.product.isDblQuantity ? parseFloat(arParams.PRODUCT.STEP_QUANTITY) : parseInt(arParams.PRODUCT.STEP_QUANTITY, 10));

                            this.checkQuantity = this.product.checkQuantity;
                            this.isDblQuantity = this.product.isDblQuantity;
                            this.maxQuantity = this.product.maxQuantity;
                            this.stepQuantity = this.product.stepQuantity;
                            if (this.isDblQuantity)
                            {
                                this.stepQuantity = Math.round(this.stepQuantity * this.precisionFactor) / this.precisionFactor;
                            }
                        }
                        this.product.canBuy = arParams.PRODUCT.CAN_BUY;
                        if (arParams.PRODUCT.MORE_PHOTO_COUNT)
                        {
                            this.product.morePhotoCount = arParams.PRODUCT.MORE_PHOTO_COUNT;
                            this.product.morePhoto = arParams.PRODUCT.MORE_PHOTO;
                        }
                        this.product.canSubscription = arParams.PRODUCT.SUBSCRIPTION;
                        if (!!arParams.PRODUCT.BASIS_PRICE)
                        {
                            this.currentBasisPrice = arParams.PRODUCT.BASIS_PRICE;
                        }

                        this.canBuy = this.product.canBuy;
                        this.canSubscription = this.product.canSubscription;

                        this.product.name = arParams.PRODUCT.NAME;
                        this.product.pict = arParams.PRODUCT.PICT;
                        this.product.id = arParams.PRODUCT.ID;
                        if (!!arParams.PRODUCT.ADD_URL)
                        {
                            this.product.addUrl = arParams.PRODUCT.ADD_URL;
                        }
                        if (!!arParams.PRODUCT.BUY_URL)
                        {
                            this.product.buyUrl = arParams.PRODUCT.BUY_URL;
                        }
                        if (!!arParams.BASKET && 'object' === typeof (arParams.BASKET))
                        {
                            this.basketData.useProps = !!arParams.BASKET.ADD_PROPS;
                            this.basketData.emptyProps = !!arParams.BASKET.EMPTY_PROPS;
                        }
                    } else
                    {
                        this.errorCode = -1;
                    }
                    break;
                case 3://sku
                    if (!!arParams.OFFERS && BX.type.isArray(arParams.OFFERS))
                    {
                        if (!!arParams.PRODUCT && 'object' === typeof (arParams.PRODUCT))
                        {
                            this.product.name = arParams.PRODUCT.NAME;
                            this.product.id = arParams.PRODUCT.ID;
                        }

                        $.each(arParams.OFFERS, function (offerId) {
                            offerId = this.ID;
                            if (!BXReady.Market.basketValues[offerId])
                                BXReady.Market.basketValues[offerId] = this.BASKET_VALUES;
                        });

                        this.offers = arParams.OFFERS;
                        this.offerNum = 0;
                        if (!!arParams.OFFER_SELECTED)
                        {
                            this.offerNum = parseInt(arParams.OFFER_SELECTED, 10);
                        }
                        if (isNaN(this.offerNum))
                        {
                            this.offerNum = 0;
                        }
                        if (!!arParams.TREE_PROPS)
                        {
                            this.treeProps = arParams.TREE_PROPS;
                        }
                        if (!!arParams.DEFAULT_PICTURE)
                        {
                            this.defaultPict.pict = arParams.DEFAULT_PICTURE.PICTURE;
                            this.defaultPict.secondPict = arParams.DEFAULT_PICTURE.PICTURE_SECOND;
                        }
                    }
                    break;
                default:
                    this.errorCode = -1;
            }
            if (!!arParams.BASKET && 'object' === typeof (arParams.BASKET))
            {
                if (!!arParams.BASKET.QUANTITY)
                {
                    this.basketData.quantity = arParams.BASKET.QUANTITY;
                }
                if (!!arParams.BASKET.PROPS)
                {
                    this.basketData.props = arParams.BASKET.PROPS;
                }
                if (!!arParams.BASKET.BASKET_URL)
                {
                    this.basketData.basketUrl = arParams.BASKET.BASKET_URL;
                }
                if (3 === this.productType)
                {
                    if (!!arParams.BASKET.SKU_PROPS)
                    {
                        this.basketData.sku_props = arParams.BASKET.SKU_PROPS;
                    }
                }
                if (!!arParams.BASKET.ADD_URL_TEMPLATE)
                {
                    this.basketData.add_url = arParams.BASKET.ADD_URL_TEMPLATE;
                }
                if (!!arParams.BASKET.BUY_URL_TEMPLATE)
                {
                    this.basketData.buy_url = arParams.BASKET.BUY_URL_TEMPLATE;
                }
//                            if (this.basketData.add_url === '' && this.basketData.buy_url === '')
//                            {
//                                console.log(this);
//                                    this.errorCode = -1024;
//                            }
            }
            if (this.useCompare)
            {
                if (!!arParams.COMPARE && typeof (arParams.COMPARE) === 'object')
                {
                    if (!!arParams.COMPARE.COMPARE_PATH)
                    {
                        this.compareData.comparePath = arParams.COMPARE.COMPARE_PATH;
                    }
                    if (!!arParams.COMPARE.COMPARE_URL_TEMPLATE)
                    {
                        this.compareData.compareUrl = arParams.COMPARE.COMPARE_URL_TEMPLATE;
                    } else
                    {
                        this.useCompare = false;
                    }
                } else
                {
                    this.useCompare = false;
                }
            }

            this.lastElement = (!!arParams.LAST_ELEMENT && 'Y' === arParams.LAST_ELEMENT);
        }

        if (0 === this.errorCode)
        {
            BX.ready(BX.delegate(this.Init, this));
        }
    };

    window.JCCatalogECommerce.prototype.Init = function ()
    {
        var i = 0,
                strPrefix = '',
                TreeItems = null;

        this.obProduct = BX(this.visual.ID);
        if (!this.obProduct)
        {
            this.errorCode = -1;
        }
        this.obPict = BX(this.visual.PICT_ID);
        if (!this.obPict)
        {
            this.errorCode = -2;
        }
        if (this.secondPict && !!this.visual.SECOND_PICT_ID)
        {
            this.obSecondPict = BX(this.visual.SECOND_PICT_ID);
        }
        this.obPrice = BX(this.visual.PRICE_ID);
        if (!this.obPrice)
        {
            this.errorCode = -16;
        }
        if (this.showQuantity && !!this.visual.QUANTITY_ID)
        {
            this.obQuantity = BX(this.visual.QUANTITY_ID);
            if (!!this.visual.QUANTITY_UP_ID)
            {
                this.obQuantityUp = BX(this.visual.QUANTITY_UP_ID);
            }
            if (!!this.visual.QUANTITY_DOWN_ID)
            {
                this.obQuantityDown = BX(this.visual.QUANTITY_DOWN_ID);
            }
        }
        if (3 === this.productType && this.offers.length > 0)
        {
            if (!!this.visual.TREE_ID)
            {
                this.obTree = BX(this.visual.TREE_ID);
                if (!this.obTree)
                {
                    this.errorCode = -256;
                }
                strPrefix = this.visual.TREE_ITEM_ID;
                for (i = 0; i < this.treeProps.length; i++)
                {
                    this.obTreeRows[i] = {
                        LEFT: BX(strPrefix + this.treeProps[i].ID + '_left'),
                        RIGHT: BX(strPrefix + this.treeProps[i].ID + '_right'),
                        LIST: BX(strPrefix + this.treeProps[i].ID + '_list'),
                        CONT: BX(strPrefix + this.treeProps[i].ID + '_cont')
                    };
                    if (!this.obTreeRows[i].LEFT || !this.obTreeRows[i].RIGHT || !this.obTreeRows[i].LIST || !this.obTreeRows[i].CONT)
                    {
                        this.errorCode = -512;
                        break;
                    }
                }
            }
            if (!!this.visual.QUANTITY_MEASURE)
            {
                this.obMeasure = BX(this.visual.QUANTITY_MEASURE);
            }
        }

        this.obBasketActions = BX(this.visual.BASKET_ACTIONS_ID);
        if (!!this.obBasketActions)
        {
            if (!!this.visual.BUY_ID)
            {
                this.obBuyBtn = BX(this.visual.BUY_ID);
            }
        }
        this.obNotAvail = BX(this.visual.NOT_AVAILABLE_MESS);

        if (this.showPercent)
        {
            if (!!this.visual.DSC_PERC)
            {
                this.obDscPerc = BX(this.visual.DSC_PERC);
            }
            if (this.secondPict && !!this.visual.SECOND_DSC_PERC)
            {
                this.obSecondDscPerc = BX(this.visual.SECOND_DSC_PERC);
            }
        }

        if (this.showSkuProps)
        {
            if (!!this.visual.DISPLAY_PROP_DIV)
            {
                this.obSkuProps = BX(this.visual.DISPLAY_PROP_DIV);
            }
        }

        if (this.showQuantity)
        {
            if (!!this.obQuantityUp)
            {
                BX.bind(this.obQuantityUp, 'click', BX.delegate(this.QuantityUp, this));
            }
            if (!!this.obQuantityDown)
            {
                BX.bind(this.obQuantityDown, 'click', BX.delegate(this.QuantityDown, this));
            }
            if (!!this.obQuantity)
            {
                BX.bind(this.obQuantity, 'change', BX.delegate(this.QuantityChange, this));
            }
        }

        $('#'+ this.visual.PICT_SLIDER_ID).on('init', function(event, slick, currentSlide, nextSlide){
            $(event.currentTarget).show();
        });

        $('#'+ this.visual.PICT_SLIDER_ID).slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            speed: 300,
            dots: true,
            arrows: false,
            infinite: true,
            lazyLoad: 'ondemand',
            fade: true,
            focusOnSelect: true,
            autoplay: (this.SLIDER_INTERVAL>0) ? true : false,
            autoplaySpeed: (this.SLIDER_INTERVAL>0) ? this.SLIDER_INTERVAL : 0
        });
//        $('.bxr-slider').show();
        switch (this.productType)
        {
            case 0: // no catalog
            case 1: // product
            case 2: // set
                if (parseInt(this.product.morePhotoCount) > 1 && this.obPictSlider)
                {
                    this.initializeSlider();
                }

                //this.checkQuantityControls();
                break;

            case 3://sku
                if (this.offers.length > 0)
                {
                    TreeItems = BX.findChildren(this.obTree, {tagName: 'li'}, true);
                    if (!!TreeItems && 0 < TreeItems.length)
                    {
                        for (i = 0; i < TreeItems.length; i++)
                        {
                            BX.bind(TreeItems[i], 'click', BX.delegate(this.SelectOfferProp, this));
                        }
                    }
                    for (i = 0; i < this.obTreeRows.length; i++)
                    {
                        BX.bind(this.obTreeRows[i].LEFT, 'click', BX.delegate(this.RowLeft, this));
                        BX.bind(this.obTreeRows[i].RIGHT, 'click', BX.delegate(this.RowRight, this));
                    }
                    this.SetCurrent();
                } else if (parseInt(this.product.morePhotoCount) > 1 && this.obPictSlider)
                {
                    this.initializeSlider();
                }
                break;
        }
        if (!!this.obBuyBtn)
        {
            if (this.basketAction === 'ADD')
            {
                BX.bind(this.obBuyBtn, 'click', BX.delegate(this.Add2Basket, this));
            } else
            {
                BX.bind(this.obBuyBtn, 'click', BX.delegate(this.BuyBasket, this));
            }
        }
        if (this.lastElement)
        {
//            this.containerHeight = parseInt(this.obProduct.parentNode.offsetHeight, 10);
//            if (isNaN(this.containerHeight))
//            {
//                this.containerHeight = 0;
//            }
//                            this.setHeight();
//                            BX.bind(window, 'resize', BX.delegate(this.checkHeight, this));
//                            BX.bind(this.obProduct.parentNode, 'mouseover', BX.delegate(this.setHeight, this));
//                            BX.bind(this.obProduct.parentNode, 'mouseout', BX.delegate(this.clearHeight, this));
        }
        if (this.useCompare)
        {
            this.obCompare = BX(this.visual.COMPARE_LINK_ID);
            if (!!this.obCompare)
            {
                BX.bind(this.obCompare, 'click', BX.proxy(this.Compare, this));
            }
        }       
    };

    window.JCCatalogECommerce.prototype.SelectOfferProp = function ()
    {
        var i = 0,
                value = '',
                strTreeValue = '',
                arTreeItem = [],
                RowItems = null,
                target = BX.proxy_context;

        if (!!target && target.hasAttribute('data-treevalue'))
        {
            strTreeValue = target.getAttribute('data-treevalue');
            arTreeItem = strTreeValue.split('_');
            if (this.SearchOfferPropIndex(arTreeItem[0], arTreeItem[1]))
            {
                RowItems = BX.findChildren(target.parentNode, {tagName: 'li'}, false);
                if (!!RowItems && 0 < RowItems.length)
                {
                    for (i = 0; i < RowItems.length; i++)
                    {
                        value = RowItems[i].getAttribute('data-onevalue');
                        if (value === arTreeItem[1])
                        {
                            BX.addClass(RowItems[i], 'bx_active');
                        } else
                        {
                            BX.removeClass(RowItems[i], 'bx_active');
                        }
                    }
                }
            }
        }
    };

    window.JCCatalogECommerce.prototype.SearchOfferPropIndex = function (strPropID, strPropValue)
    {
        var strName = '',
                arShowValues = false,
                i, j,
                arCanBuyValues = [],
                allValues = [],
                index = -1,
                arFilter = {},
                tmpFilter = [];

        for (i = 0; i < this.treeProps.length; i++)
        {
            if (this.treeProps[i].ID === strPropID)
            {
                index = i;
                break;
            }
        }

        if (-1 < index)
        {
            for (i = 0; i < index; i++)
            {
                strName = 'PROP_' + this.treeProps[i].ID;
                arFilter[strName] = this.selectedValues[strName];
            }
            strName = 'PROP_' + this.treeProps[index].ID;
            arShowValues = this.GetRowValues(arFilter, strName);
            if (!arShowValues)
            {
                return false;
            }
            if (!BX.util.in_array(strPropValue, arShowValues))
            {
                return false;
            }
            arFilter[strName] = strPropValue;
            for (i = index + 1; i < this.treeProps.length; i++)
            {
                strName = 'PROP_' + this.treeProps[i].ID;
                arShowValues = this.GetRowValues(arFilter, strName);
                if (!arShowValues)
                {
                    return false;
                }
                allValues = [];
                if (this.showAbsent)
                {
                    arCanBuyValues = [];
                    tmpFilter = [];
                    tmpFilter = BX.clone(arFilter, true);
                    for (j = 0; j < arShowValues.length; j++)
                    {
                        tmpFilter[strName] = arShowValues[j];
                        allValues[allValues.length] = arShowValues[j];
                        if (this.GetCanBuy(tmpFilter))
                            arCanBuyValues[arCanBuyValues.length] = arShowValues[j];
                    }
                } else
                {
                    arCanBuyValues = arShowValues;
                }
                if (!!this.selectedValues[strName] && BX.util.in_array(this.selectedValues[strName], arCanBuyValues))
                {
                    arFilter[strName] = this.selectedValues[strName];
                } else
                {
                    if (this.showAbsent)
                        arFilter[strName] = (arCanBuyValues.length > 0 ? arCanBuyValues[0] : allValues[0]);
                    else
                        arFilter[strName] = arCanBuyValues[0];
                }
                this.UpdateRow(i, arFilter[strName], arShowValues, arCanBuyValues);
            }
            this.selectedValues = arFilter;
            this.ChangeInfo();
        }
        return true;
    };

    window.JCCatalogECommerce.prototype.RowLeft = function ()
    {
        var i = 0,
                strTreeValue = '',
                index = -1,
                target = BX.proxy_context;

        if (!!target && target.hasAttribute('data-treevalue'))
        {
            strTreeValue = target.getAttribute('data-treevalue');
            for (i = 0; i < this.treeProps.length; i++)
            {
                if (this.treeProps[i].ID === strTreeValue)
                {
                    index = i;
                    break;
                }
            }
            if (-1 < index && this.treeRowShowSize < this.showCount[index])
            {
                if (0 > this.showStart[index])
                {
                    this.showStart[index]++;
                    BX.adjust(this.obTreeRows[index].LIST, {style: {marginLeft: this.showStart[index] * 20 + '%'}});
                    BX.adjust(this.obTreeRows[index].RIGHT, {style: this.treeEnableArrow});
                }

                if (0 <= this.showStart[index])
                {
                    BX.adjust(this.obTreeRows[index].LEFT, {style: this.treeDisableArrow});
                }
            }
        }
    };

    window.JCCatalogECommerce.prototype.RowRight = function ()
    {
        var i = 0,
                strTreeValue = '',
                index = -1,
                target = BX.proxy_context;

        if (!!target && target.hasAttribute('data-treevalue'))
        {
            strTreeValue = target.getAttribute('data-treevalue');
            for (i = 0; i < this.treeProps.length; i++)
            {
                if (this.treeProps[i].ID === strTreeValue)
                {
                    index = i;
                    break;
                }
            }
            if (-1 < index && this.treeRowShowSize < this.showCount[index])
            {
                if ((this.treeRowShowSize - this.showStart[index]) < this.showCount[index])
                {
                    this.showStart[index]--;
                    BX.adjust(this.obTreeRows[index].LIST, {style: {marginLeft: this.showStart[index] * 20 + '%'}});
                    BX.adjust(this.obTreeRows[index].LEFT, {style: this.treeEnableArrow});
                }

                if ((this.treeRowShowSize - this.showStart[index]) >= this.showCount[index])
                {
                    BX.adjust(this.obTreeRows[index].RIGHT, {style: this.treeDisableArrow});
                }
            }
        }
    };

    window.JCCatalogECommerce.prototype.UpdateRow = function (intNumber, activeID, showID, canBuyID)
    {
        var i = 0,
                showI = 0,
                value = '',
                countShow = 0,
                strNewLen = '',
                obData = {},
                pictMode = false,
                extShowMode = false,
                isCurrent = false,
                selectIndex = 0,
                obLeft = this.treeEnableArrow,
                obRight = this.treeEnableArrow,
                currentShowStart = 0,
                RowItems = null;

        if (-1 < intNumber && intNumber < this.obTreeRows.length)
        {
            RowItems = BX.findChildren(this.obTreeRows[intNumber].LIST, {tagName: 'li'}, false);
            if (!!RowItems && 0 < RowItems.length)
            {
                pictMode = ('PICT' === this.treeProps[intNumber].SHOW_MODE);
                countShow = showID.length;
                extShowMode = this.treeRowShowSize < countShow;
                strNewLen = (extShowMode ? (100 / countShow) + '%' : '20%');
                obData = {
                    props: {className: ''},
                    style: {
//                                            width: strNewLen
                    }
                };
                if (pictMode)
                {
                    obData.style.paddingTop = strNewLen;
                }
                for (i = 0; i < RowItems.length; i++)
                {
                    value = RowItems[i].getAttribute('data-onevalue');
                    isCurrent = (value === activeID);
//                                    if (BX.util.in_array(value, canBuyID))
//                                    {
                    obData.props.className = (isCurrent ? 'bx_active' : '');
//                                    }
//                                    else
//                                    {
//                                            obData.props.className = (isCurrent ? 'bx_active bx_missing' : 'bx_missing');
//                                    }
                    obData.style.display = 'none';
                    if (BX.util.in_array(value, showID))
                    {
                        obData.style.display = '';
                        if (isCurrent)
                        {
                            selectIndex = showI;
                        }
                        showI++;
                    }
                    BX.adjust(RowItems[i], obData);
                }

                obData = {
                    style: {
//                                            width: (extShowMode ? 20*countShow : 100)+'%',
                        marginLeft: '0%'
                    }
                };
                if (pictMode)
                {
                    BX.adjust(this.obTreeRows[intNumber].CONT, {props: {className: (extShowMode ? 'bx_item_detail_scu full' : 'bx_item_detail_scu')}});
                } else
                {
                    BX.adjust(this.obTreeRows[intNumber].CONT, {props: {className: (extShowMode ? 'bx_item_detail_size full' : 'bx_item_detail_size')}});
                }
                if (extShowMode)
                {
                    if (selectIndex + 1 === countShow)
                    {
                        obRight = this.treeDisableArrow;
                    }
                    if (this.treeRowShowSize <= selectIndex)
                    {
                        currentShowStart = this.treeRowShowSize - selectIndex - 1;
                        obData.style.marginLeft = currentShowStart * 20 + '%';
                    }
                    if (0 === currentShowStart)
                    {
                        obLeft = this.treeDisableArrow;
                    }
                    BX.adjust(this.obTreeRows[intNumber].LEFT, {style: obLeft});
                    BX.adjust(this.obTreeRows[intNumber].RIGHT, {style: obRight});
                } else
                {
                    BX.adjust(this.obTreeRows[intNumber].LEFT, {style: {display: 'none'}});
                    BX.adjust(this.obTreeRows[intNumber].RIGHT, {style: {display: 'none'}});
                }
                BX.adjust(this.obTreeRows[intNumber].LIST, obData);
                this.showCount[intNumber] = countShow;
                this.showStart[intNumber] = currentShowStart;
            }
        }
    };

    window.JCCatalogECommerce.prototype.GetRowValues = function (arFilter, index)
    {
        var i = 0,
                j,
                arValues = [],
                boolSearch = false,
                boolOneSearch = true;

        if (0 === arFilter.length)
        {
            for (i = 0; i < this.offers.length; i++)
            {
                if (!BX.util.in_array(this.offers[i].TREE[index], arValues))
                {
                    arValues[arValues.length] = this.offers[i].TREE[index];
                }
            }
            boolSearch = true;
        } else
        {
            for (i = 0; i < this.offers.length; i++)
            {
                boolOneSearch = true;
                for (j in arFilter)
                {
                    if (arFilter[j] !== this.offers[i].TREE[j])
                    {
                        boolOneSearch = false;
                        break;
                    }
                }
                if (boolOneSearch)
                {
                    if (!BX.util.in_array(this.offers[i].TREE[index], arValues))
                    {
                        arValues[arValues.length] = this.offers[i].TREE[index];
                    }
                    boolSearch = true;
                }
            }
        }
        return (boolSearch ? arValues : false);
    };

    window.JCCatalogECommerce.prototype.GetCanBuy = function (arFilter)
    {
        var i = 0,
                j,
                boolSearch = false,
                boolOneSearch = true;

        for (i = 0; i < this.offers.length; i++)
        {
            boolOneSearch = true;
            for (j in arFilter)
            {
                if (arFilter[j] !== this.offers[i].TREE[j])
                {
                    boolOneSearch = false;
                    break;
                }
            }
            if (boolOneSearch)
            {
                if (this.offers[i].CAN_BUY)
                {
                    boolSearch = true;
                    break;
                }
            }
        }
        return boolSearch;
    };

    window.JCCatalogECommerce.prototype.SetCurrent = function ()
    {
        var i = 0,
                j = 0,
                arCanBuyValues = [],
                strName = '',
                arShowValues = false,
                arFilter = {},
                tmpFilter = [],
                current = this.offers[this.offerNum].TREE;

        for (i = 0; i < this.treeProps.length; i++)
        {
            strName = 'PROP_' + this.treeProps[i].ID;
            arShowValues = this.GetRowValues(arFilter, strName);
            if (!arShowValues)
            {
                break;
            }
            if (BX.util.in_array(current[strName], arShowValues))
            {
                arFilter[strName] = current[strName];
            } else
            {
                arFilter[strName] = arShowValues[0];
                this.offerNum = 0;
            }
            if (this.showAbsent)
            {
                arCanBuyValues = [];
                tmpFilter = [];
                tmpFilter = BX.clone(arFilter, true);
                for (j = 0; j < arShowValues.length; j++)
                {
                    tmpFilter[strName] = arShowValues[j];
                    if (this.GetCanBuy(tmpFilter))
                    {
                        arCanBuyValues[arCanBuyValues.length] = arShowValues[j];
                    }
                }
            } else
            {
                arCanBuyValues = arShowValues;
            }
            this.UpdateRow(i, arFilter[strName], arShowValues, arCanBuyValues);
        }
        this.selectedValues = arFilter;
        this.ChangeInfo();
    };

    window.JCCatalogECommerce.prototype.ChangeInfo = function ()
    {
        var i = 0,
                j,
                index = -1,
                boolOneSearch = true;

        for (i = 0; i < this.offers.length; i++)
        {
            boolOneSearch = true;
            for (j in this.selectedValues)
            {
                if (this.selectedValues[j] !== this.offers[i].TREE[j])
                {
                    boolOneSearch = false;
                    break;
                }
            }
            if (boolOneSearch)
            {
                index = i;
                break;
            }
        }
        if (-1 < index)
        {

            if (parseInt(this.offers[index].MORE_PHOTO_COUNT) > 1 && this.obPictSlider)
            {
                // hide pict and second_pict containers
                if (this.obPict)
                {
                    this.obPict.style.display = 'none';
                }

                if (this.obSecondPict)
                {
                    this.obSecondPict.style.display = 'none';
                }

                // clear slider container
                BX.cleanNode(this.obPictSlider);

                // fill slider container with slides
                for (i in this.offers[index].MORE_PHOTO)
                {
                    if (this.offers[index].MORE_PHOTO.hasOwnProperty(i))
                    {
                        this.obPictSlider.appendChild(
                                BX.create('SPAN', {
                                    props: {className: 'product-item-image-slide item' + (i == 0 ? ' active' : '')},
                                    style: {backgroundImage: 'url(' + this.offers[index].MORE_PHOTO[i].SRC + ')'}
                                })
                                );
                    }
                }

                // fill slider indicator if exists
                if (this.obPictSliderIndicator)
                {
                    BX.cleanNode(this.obPictSliderIndicator);

                    for (i in this.offers[index].MORE_PHOTO)
                    {
                        if (this.offers[index].MORE_PHOTO.hasOwnProperty(i))
                        {
                            this.obPictSliderIndicator.appendChild(
                                    BX.create('DIV', {
                                        attrs: {'data-go-to': i},
                                        props: {className: 'product-item-image-slider-control' + (i == 0 ? ' active' : '')}
                                    })
                                    );
                            this.obPictSliderIndicator.appendChild(document.createTextNode(' '));
                        }
                    }

                    this.obPictSliderIndicator.style.display = '';
                }

                if (this.obPictSliderProgressBar)
                {
                    this.obPictSliderProgressBar.style.display = '';
                }

                // show slider container
                this.obPictSlider.style.display = '';
                this.initializeSlider();
            } else
            {
                // hide slider container
                if (this.obPictSlider)
                {
                    this.obPictSlider.style.display = 'none';
                }

                if (this.obPictSliderIndicator)
                {
                    this.obPictSliderIndicator.style.display = 'none';
                }

                if (this.obPictSliderProgressBar)
                {
                    this.obPictSliderProgressBar.style.display = 'none';
                }

                // show pict and pict_second containers
                if (this.obPict)
                {
                    if (this.offers[index].PREVIEW_PICTURE)
                    {
                        BX.adjust(this.obPict, {style: {backgroundImage: 'url(' + this.offers[index].PREVIEW_PICTURE.SRC + ')'}});
                    } else
                    {
                        BX.adjust(this.obPict, {style: {backgroundImage: 'url(' + this.defaultPict.pict.SRC + ')'}});
                    }

                    this.obPict.style.display = '';
                }

                if (this.secondPict && this.obSecondPict)
                {
                    if (this.offers[index].PREVIEW_PICTURE_SECOND)
                    {
                        BX.adjust(this.obSecondPict, {style: {backgroundImage: 'url(' + this.offers[index].PREVIEW_PICTURE_SECOND.SRC + ')'}});
                    } else if (this.offers[index].PREVIEW_PICTURE.SRC)
                    {
                        BX.adjust(this.obSecondPict, {style: {backgroundImage: 'url(' + this.offers[index].PREVIEW_PICTURE.SRC + ')'}});
                    } else if (this.defaultPict.secondPict)
                    {
                        BX.adjust(this.obSecondPict, {style: {backgroundImage: 'url(' + this.defaultPict.secondPict.SRC + ')'}});
                    } else
                    {
                        BX.adjust(this.obSecondPict, {style: {backgroundImage: 'url(' + this.defaultPict.pict.SRC + ')'}});
                    }

                    this.obSecondPict.style.display = '';
                }
            }
            if (this.showSkuProps && !!this.obSkuProps)
            {
                if (0 === this.offers[index].DISPLAY_PROPERTIES.length)
                {
                    BX.adjust(this.obSkuProps, {style: {display: 'none'}, html: ''});
                } else
                {
                    BX.adjust(this.obSkuProps, {style: {display: ''}, html: this.offers[index].DISPLAY_PROPERTIES});
                }
            }

            $('#'+this.visual.AVAIL_ID+'>div').hide();
            $('#'+this.visual.AVAIL_ID).children('#bxr-offer-avail-'+this.offers[index].ID).show();
            $('#'+this.visual.PRICE_ID+'>div').hide();
            $('#'+this.visual.PRICE_ID).children('#bxr-offer-price-'+this.offers[index].ID).show();
            if (this.offers[index].NAME !== '') {
                $('#'+this.visual.NAME).html(this.offers[index].NAME);
                $('#'+this.visual.NAME).attr('title', this.offers[index].NAME);
            }
            $('#'+this.visual.ID+" .bxr-counter-item-basket").data("item", this.offers[index].ID);
            $('#'+this.visual.ID+" .bxr-indicator-item-basket").data("item", this.offers[index].ID);
            $('#'+this.visual.ID+" .bxr-counter-item-basket").attr("data-item", this.offers[index].ID);
            $('#'+this.visual.ID+" .bxr-indicator-item-basket").attr("data-item", this.offers[index].ID);
            basket = window.BXReady.Market.Basket;
            if (basket != undefined) {
                if (basket.list.ITEMS != undefined && basket.list.ALL != undefined) {
                    $('#'+this.visual.ID+" .bxr-counter-item-basket").html(basket.list.ALL[this.offers[index].ID]);
                    if (basket.list.ALL[this.offers[index].ID] > 0)
                        $('#'+this.visual.ID+" .bxr-indicator-item-basket").addClass("bxr-indicator-item-active");
                } else 
                    $('#'+this.visual.ID+" .bxr-indicator-item-basket").removeClass("bxr-indicator-item-active");
            }
            if ($('#'+this.visual.FAST_VIEW_ID).length > 0) 
                $('#'+this.visual.FAST_VIEW_ID).data("offer-id", this.offers[index].ID);
            if (useSkuLinks == "Y") {
                $('#'+this.visual.NAME).attr('href', this.offers[index].BASKET_VALUES.LINK);
                $('#'+this.visual.FAST_VIEW_ID).data('element-url', this.offers[index].BASKET_VALUES.LINK);
                $('#'+this.visual.ID+" .bxr-element-image a").attr("href", this.offers[index].BASKET_VALUES.LINK);
            }
            BXReady.Market.setBasketIds(this.offers[index].ID, '', '#'+this.visual.BASKET_ACTIONS_ID);
            $('#'+this.visual.PICT_SLIDER_ID).slick('slickFilter','[data-item="'+this.offers[index].ID+'"], [data-item="'+this.product.id+'"]');
            var offerSlide = $('#'+this.visual.PICT_SLIDER_ID).find('[data-item="'+this.product.id+'"]').length;
            $('#'+this.visual.PICT_SLIDER_ID).slick('slickGoTo', offerSlide);
            this.offerNum = index;
            $('#'+this.visual.PICT_SLIDER_ID).slick('slickPause');
        }
    };
    window.JCCatalogECommerce.prototype.setAnalyticsDataLayer = function (action)
    {
        if (!this.useEnhancedEcommerce || !this.dataLayerName)
            return;

        var item = {},
            info = {},
            variants = [],
            i, k, j, propId, skuId, propValues;

        switch (this.productType)
        {
            case 0: //no catalog
            case 1: //product
            case 2: //set
                    item = {
                        'id': this.product.id,
                        'name': this.product.name,
                        'price': this.currentPrices[this.currentPriceSelected] && this.currentPrices[this.currentPriceSelected].PRICE,
                        'brand': BX.type.isArray(this.brandProperty) ? this.brandProperty.join('/') : this.brandProperty
                    };
                    break;
            case 3: //sku
                for (i in this.offers[this.offerNum].TREE)
                {
                    if (this.offers[this.offerNum].TREE.hasOwnProperty(i))
                    {
                        propId = i.substring(5);
                        skuId = this.offers[this.offerNum].TREE[i];

                        for (k in this.treeProps)
                        {
                            if (this.treeProps.hasOwnProperty(k) && this.treeProps[k].ID == propId)
                            {
                                for (j in this.treeProps[k].VALUES)
                                {
                                    propValues = this.treeProps[k].VALUES[j];
                                    if (propValues.ID == skuId)
                                    {
                                        variants.push(propValues.NAME);
                                        break;
                                    }
                                }

                            }
                        }
                    }
                }

                item = {
                    'id': this.offers[this.offerNum].ID,
                    'name': this.offers[this.offerNum].NAME,
                    'price': this.currentPrices[this.currentPriceSelected] && this.currentPrices[this.currentPriceSelected].PRICE,
                    'brand': BX.type.isArray(this.brandProperty) ? this.brandProperty.join('/') : this.brandProperty,
                    'variant': variants.join('/')
                };
                break;
        }

        switch (action)
        {
            case 'addToCart':
                info = {
                    'event': 'addToCart',
                    'ecommerce': {
                        'currencyCode': this.currentPrices[this.currentPriceSelected] && this.currentPrices[this.currentPriceSelected].CURRENCY || '',
                        'add': {
                            'products': [{
                                'name': item.name || '',
                                'id': item.id || '',
                                'price': item.price || 0,
                                'brand': item.brand || '',
                                'category': item.category || '',
                                'variant': item.variant || '',
                                'quantity': this.showQuantity && this.obQuantity ? this.obQuantity.value : 1
                            }]
                        }
                    }
                };
                break;
        }

        window[this.dataLayerName] = window[this.dataLayerName] || [];
        window[this.dataLayerName].push(info);
    }

}