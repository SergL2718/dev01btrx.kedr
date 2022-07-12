$(function () {
    function getTopCart() {

        $.ajax({
            url: '/local/ajax/getTopCart.php',
            success: function(data) {
                if(data>0){
                    $('[data-code="shopping-bag"]').find('.header-user-bar-count').show().find('span').text(data);
                }
                else{
                    $('[data-code="shopping-bag"]').find('.header-user-bar-count').hide().find('span').text(data);
                }
            }
        });

        /*$('[data-code="shopping-bag"]').find('.header-user-bar-count').show().find('span').load('/local/ajax/getTopCart.php');
        if($('[data-code="shopping-bag"]').find('.header-user-bar-count').find('span').text() == 0){
           // $('[data-code="shopping-bag"]').find('.header-user-bar-count').hide();
        }*/
    }

    function getFreeDelivery() {
        $('.product-about__delivery-info').find('span').load('/local/ajax/getFreeDelivery.php');
    }

    function getTopFavorites(count) {
        if(count>0)
            $('[data-code="favorites"]').find('.header-user-bar-count').show().find('span').text(count);
        else
            $('[data-code="favorites"]').find('.header-user-bar-count').hide().find('span').text(count);
    }

    function setDetailFavorite(){
        let id = $('.button-like').attr('data-id');
        if($('.user-bar-popup-product-wrapper[data-id="'+id+'"]').length){
            $('.button-like').addClass('active');
        }
        else{
            $('.button-like').removeClass('active');
        }
    }

    function breadCrumbsAndSmartFilter(){
        let url = document.location.pathname;
        let hrefRoot = $('.catalog-filter__card').attr('data-href');
        if(url.indexOf('filter/specialoffer-is') !=-1){
            $('.breadcrumbs-container').find('.breadcrumbs__item span').wrap('<a href="'+hrefRoot+'"></a>')
            $('.breadcrumbs-container').append('<li class="breadcrumbs__item"><span>Скидки</span></li>');
        }
        if(url.indexOf('filter/newproduct-is') !=-1){
            $('.breadcrumbs-container').find('.breadcrumbs__item span').wrap('<a href="'+hrefRoot+'"></a>')
            $('.breadcrumbs-container').append('<li class="breadcrumbs__item"><span>Новинки</span></li>');
        }
        if(url.indexOf('filter/saleleader-is') !=-1){
            $('.breadcrumbs-container').find('.breadcrumbs__item span').wrap('<a href="'+hrefRoot+'"></a>')
            $('.breadcrumbs-container').append('<li class="breadcrumbs__item"><span>Бестселлеры</span></li>');
        }
        //console.log(url);
    }

    function orderCartUpdate(){
        $.ajax({
            type: "POST",
            url: "/local/ajax/orderCartUpdate.php"
        }).done(function( msg ) {
            if(msg){

                let data  = JSON.parse(msg);
                $('.order-total__bonus-inner').find('b').text(data['POINTS_SUM']+" зкр");
                $('.order-total__title_value').text(data['PRICE_SUM']+" руб.");
                $('.price__sum').find('b').text(data['PRICE_SUM']+" руб.");
                $('.price__sale').find('b').text(data['POINTS_full']-data['POINTS_SUM']);
                //console.log(data);
            }
        });
    }

    function createPageNav(sizePage){
        //let hiddenCnt = $('.product-preview-wrapper.hidden').length;
        let hiddenCnt = $('.product-preview-wrapper').length;
        //let pageNum = Math.ceil(hiddenCnt/sizePage);
        let pageNum = $('.product-preview-wrapper:last').attr('data-page');
        if(pageNum==1 && hiddenCnt<sizePage)pageNum++;
       /* console.log(hiddenCnt);
        console.log(pageNum);
        console.log(sizePage);*/
        if(pageNum>0){
            $('.catalog-sorting').after('<ul class="pagination">\n' +
                '                        <li class="prev"></li>\n' +
                '                        <li class="current"><a href="#">1</a></li>\n' +
                '                        <li class="next"></li>\n' +
                '                    </ul>');
            for(let i = 2; i<=pageNum; i++){
                if(i>3){
                    $('.pagination .next').before('<li class="hidden"><a href="#">' + i + '</a></li>');
                }
                else {
                    $('.pagination .next').before('<li><a href="#">' + i + '</a></li>');
                }
            }
        }
    }

    function toggleShowMoreProducts(current){
        if( $('.product-preview-wrapper.hidden:last').attr('data-page')*1 > current*1) {
            //console.log('catalog-list-more SHOW PAGEN');
            $('.catalog-list-more').show();
        }
        else{
            //console.log('catalog-list-more HIDE');
            $('.catalog-list-more').hide();
        }
    }

    getTopCart();
    $('body').on('click', '[data-controller="addToBasket"]', function () {
        let obj = $(this);
        let id = $(this).attr('data-id');
        let url = $(this).attr('data-url');
        let quantity = 1;
        if ($('input[name="QUANTITY"]').val()) quantity = $('input[name="QUANTITY"]').val();
        $.ajax({
            type: "POST",
            url: url + '?action=ADD2BASKET&id=' + id,
            data: {quantity: quantity, ajax_basket: 'Y'},
            success: function (data) {
                //$(obj).html('Добавлено').attr('disabled','disabled');
                $('[data-controller="addToBasket"]').html('Добавлено').attr('disabled','disabled');
                getTopCart();
                getFreeDelivery();
            }
        });
    });

    $('body').on('click', '[data-controller="addToFavorites"]', function () {
        let id = $(this).attr('data-id');
        if (id) {
            let actionCode = "deleteFromFavorites";
            if($(this).is('.active')) actionCode = "addToFavorites";
            //console.log(actionCode);
            $.ajax({
                type: "POST",
                url: '/local/templates/megre/ajax/product.php',
                data: {action: actionCode, ID: id},
                success: function (data) {
                    getTopFavorites(data.count);
                    if(actionCode == "addToFavorites") {
                        $.ajax({
                            type: "POST",
                            url: '/local/templates/megre/components/bitrix/menu/header.user.bar/ajax.php',
                            data: {action: 'getTemplate', ID: id},
                            success: function (response) {
                                //console.log(response);
                                $('.user-bar-popup[data-code="favorites"] .user-bar-popup-content').append(response);
                            }
                        });
                    }
                    else{
                        $('.user-bar-popup-product-wrapper[data-id="'+id+'"]').remove();
                    }

                }
            });
        }
    });

    $('body').on('click', '.link-more', function () {

        if($('.product-review__more').length) {
            $(this).closest('.product-review__more').prev().find('.hidden').removeClass('hidden');
            $('.product-review__more').find('.link-more').remove();
        }
        else{
            $(this).closest('.manufacturers-item').find('.hidden').removeClass('hidden');
        }

        //$(this).remove();
    })
    $('body').on('click', '.link-more.change-phone__detail_order', function () {
        $(this).parents('#order-call').hide();
        $(this).closest('.order-view-call__wrap').next().show();
    })
    $('body').on('click', '.button_primary.change-phone__detail_order', function () {
        let NEW_PHONE = $(this).closest('.order-view-call__wrap').find('input').val();
        if(NEW_PHONE){
            $.ajax({
                type: "POST",
                url: '/local/ajax/editPhone.php',
                data: {PHONE: NEW_PHONE},
                success: function (data) {
                    if(data){
                        $('.order-view-call__formed p').text(data);
                        $('.button_primary.change-phone__detail_order').closest('.order-view-call__wrap').hide();
                        $('#call-me-button').removeAttr('disabled').html('Связаться со мной');
                        $('#order-call').show();
                    }
                    //$(obj).find('button').attr('disabled', 'disabled').html('Готово');
                }
            });
        }
    })


    $('body').on('change', '#sort_reviews', function () {
        let value = $(this).val();
        window.location.href = '?order=' + value;
    })
    $('body').on('click', '#call-me-button', function () {
        $.ajax({
            type: "POST",
            url: '/local/ajax/callMe.php',
            success: function (data) {
                $('#call-me-button').attr('disabled', 'disabled').html('Готово');
                $('#order-call .order-view-call__help').show();
                setTimeout(function () {
                    $('#order-call .order-view-call__help').hide();
                }, 5000)
            }
        });
    })


    $('body').on('submit', 'form.subscribe_form', function () {
        let obj = $(this);
        let EMAIL = $(this).find('input[name="EMAIL"]').val();

        if (EMAIL) {
            $.ajax({
                type: "POST",
                url: '/local/ajax/subscribe.php',
                data: {EMAIL: EMAIL},
                success: function (data) {
                    $(obj).find('button').attr('disabled', 'disabled').html('Готово');
                }
            });
        }
        return false;
    })

    $('body').on('submit', 'form.return-form', function () {
        let obj = $(this);
        let data = $(this).serialize();

        if (data) {
            $.ajax({
                type: "POST",
                url: '/local/ajax/return.php',
                data: data,
                success: function (data) {
                    $(obj).find('input[type="submit"]').attr('disabled', 'disabled').val('Готово');
                }
            });
        }
        return false;
    })



    $('.product-about__action-add').on('click', '.icon-plus, .icon-minus', function () {
        let quantityObj = $(this).closest('.product-about__action-add').find('input');
        let quantity = $(quantityObj).val() * 1;
        if ($(this).is('.icon-plus')) {
            quantity = quantity + 1;
        } else {
            if (quantity > 1) quantity = quantity - 1;
        }
        $(quantityObj).val(quantity);

        let productId = $(this).closest('.product-about__action').find('[data-controller="addToBasket"]').attr('data-id');
        $.ajax({
            type: "POST",
            url: '/local/ajax/editCart.php',
            data: {PRODUCT_ID: productId, QUANTITY: quantity},
            success: function (data) {
                getFreeDelivery();
                //console.log(data);
                //$(obj).find('button').attr('disabled', 'disabled').html('Ваш отзыв добавлен');
            }
        });
    })

    $('body').on('change', 'select[name="form_dropdown_THEME"]',function(){
        let value = $(this).val();
        if(value == 1){
            $('.form-item-row-ORDER_NUMBER').css('display', 'flex');
        }
        else{
            $('.form-item-row-ORDER_NUMBER').hide();
        }
    })

    $('.return-form').on('click', 'input[type="submit"]', function(){
        let error = false;
        $(this).closest('.form-footer').find('.error_text').remove();
        $('.return-form').find('input').each(function(i, obj){
            $(obj).removeClass('error');
            $(obj).closest('.form-item-input').find('.error_text').remove('');

            if($(obj).prop('required') && !$(obj).val()){
                $(obj).addClass('error');
                $(obj).after('<span class="error_text">Ошибка: заполните поле!</span>');
                error = true;
            }
        });
        if(error){
            $(this).after('<span class="error_text">Заполните все обязательные поля!</span>');
        }
    });

    $('.product-about__value-button:first').trigger('click');

    if($('.catalog-filter__card').length){
        $('.header-menu-item').each(function(i, obj){
            let catalog_filter__link = $(obj).children('a').text();
            let header_menu_item_detail_url = $(obj).find('.header-menu-item-detail-url').attr('href');
            if(catalog_filter__link == "Подарки" || catalog_filter__link == "О нас" ){}
            else {
                let catalog_filter__link_sub = $(obj).find('ul').html();
                catalog_filter__link_sub = '<li><a href="'+header_menu_item_detail_url+'">Посмотреть все</a></li>' +
                    catalog_filter__link_sub;


                $('.start__append').append('<div class="catalog-filter__row drop">\n' +
'    <div class="catalog-filter__link">' + catalog_filter__link + '</div>\n' +
'    <div class="catalog-filter__drop">\n' +
'        <div class="catalog-filter__drop-wrapper">\n' +
catalog_filter__link_sub +
'        </div>\n' +
'    </div>\n' +
'</div>');
            }
        });

        $('.catalog-filter__drop-wrapper .header-menu-item-detail-url').closest('li').remove();
    }

    $('body').on('change', '#catalog_sort', function(){
        let sort = $(this).val();
        location.href='?sort='+sort;
    });


    $('.catalog-list').find('.product-preview-wrapper:gt(7)').addClass('hidden');
    let page = 2;
    let key = 0;
    $('.catalog-list').find('.product-preview-wrapper.hidden').each(function(i, obj){
        if(key == 9){
            key =0;
            page = page+1;
        }
        //if((i%9)==0)page = page+1;
        $(obj).attr('data-page', page);
        key++;
    });

    if($('.product-preview-wrapper.hidden').length){

        createPageNav(9);
        let pageNav = location.search;
        if(pageNav){
            var params = window
                .location
                .search
                .replace('?','')
                .split('&')
                .reduce(
                    function(p,e){
                        var a = e.split('=');
                        p[ decodeURIComponent(a[0])] = decodeURIComponent(a[1]);
                        return p;
                    },
                    {}
                );
            if(params['PAGEN_1']){
                //let current = pageNav.replace('?PAGEN_1=', '');
                let current = params['PAGEN_1'];
                if(current>1) {
                    $('.catalog-list').find('.product-preview-wrapper').addClass('hidden');
                    setTimeout(function () {
                        //$('.product-preview-wrapper[data-page="'+current+'"]').removeClass('hidden');

                        //$('.pagination').find('current').removeClass('current');
                        $('.pagination').find('a:contains("'+current+'"):first').addClass('current').trigger('click');
                        toggleShowMoreProducts(current);

                    }, 1000)
                }

            }
            else{
                //console.log('catalog-list-more SHOW');
                $('.catalog-list-more').show();
            }
        }else{
            //console.log('catalog-list-more SHOW');
            $('.catalog-list-more').show();
        }
    }
    $('body').on('click', '.catalog-list-more', function(){

        /*$('.product-preview-wrapper.hidden:lt(8)').removeClass('hidden');
        $('.catalog-nav.s-none').find('li.current').removeClass('current').next().addClass('current');
        let current = $('.catalog-nav.s-none').find('li.current a').text();
        let url = location.pathname + '?PAGEN_1='+current;
        window.history.pushState({}, null, url);*/
        let currentOpenFirst = "";
        $('.product-preview-wrapper:not(".hidden")').each(function(i, obj){
            if(!currentOpenFirst)currentOpenFirst = $(obj).attr('data-page');
        });
        //console.log(currentOpenFirst);
        /*let currentOpenFirst = $('.product-preview-wrapper').not(".hidden");
        console.log(currentOpenFirst);*/
        $('.catalog-nav.s-none').find('li.current').next().find('a').trigger('click');
        let current = $('.catalog-nav.s-none').find('li.current a').text();
        for(let i=current; i>=currentOpenFirst; i--){
            $(' [data-page = "'+i+'"] ').removeClass('hidden');
        }
        if(current>1 && $(window).width() > 1024) {
            $('.catalog-banner__toggle').show();
        }
       /* if(!$('.product-preview-wrapper.hidden').length){
            $('.catalog-list-more').hide();
        }*/
    });

    if($('.button-like').length){
        setDetailFavorite();

    }
    setInterval(setDetailFavorite, 500);
    $('body').on('click', '.order-card__quantity .block-quantity .icon', function(){
        let inputObj = $(this).closest('.block-quantity').find('.block-quantity__num');
        let quantity = $(inputObj).text()*1;
        let cartId = $(inputObj).attr('data-id');
        if(cartId && quantity>=1){
            if($(this).is('.icon-plus'))quantity = quantity+1;
            else if(quantity>1) quantity = quantity-1;
            $(inputObj).text(quantity);

            $.ajax({
                type: "POST",
                url: "/local/ajax/editCart.php",
                data: { ID: cartId, QUANTITY: quantity }
            }).done(function( msg ) {
                orderCartUpdate();
                getFreeDelivery();
            });

        }
    })
    $('body').on('click', '.icon.icon-close', function(){
        let inputObj = $(this);
        let cartId = $(inputObj).attr('data-id');
        if(cartId){
            $.ajax({
                type: "POST",
                url: "/local/ajax/editCart.php",
                data: { ID: cartId, QUANTITY: 0 }
            }).done(function( msg ) {
                $(inputObj).closest('.order-card').remove();
                orderCartUpdate();
                getFreeDelivery();
            });

        }
    })

    if($('.product-about__delivery-info').length){
        setInterval(function(){
            getFreeDelivery();
        }, 1000)
    }

    $('body').on('click', '.pagination a', function(){
        $('.pagination li').removeClass('current');
        $(this).closest('li').addClass('current');
        $('.product-preview-wrapper').addClass('hidden');
        let current = $(this).text();
        if(current>0){
            $('[data-page="'+current+'"]').removeClass('hidden');
            //$('[data-page="'+current+'"]:last').prevAll('.product-preview-wrapper').removeClass('hidden');

                $('.pagination li').not('.prev, .next').addClass('hidden');
                $(this).closest('li').removeClass('hidden');

                if($(this).closest('li').next('li').not('.next').length) {
                    $(this).closest('li').prev().removeClass('hidden');//.prev().removeClass('hidden');
                    $(this).closest('li').next().removeClass('hidden');
                }
                else{
                    //console.log('extreme last');
                    $(this).closest('li').prev().removeClass('hidden').prev().removeClass('hidden');
                }

                if($(this).closest('li').prev('li').not('.prev').length) {
                    $(this).closest('li').next().removeClass('hidden');//.next().removeClass('hidden');
                    $(this).closest('li').prev().removeClass('hidden');
                }
                else{
                    //console.log('extreme first');
                    $(this).closest('li').next().removeClass('hidden').next().removeClass('hidden');
                }

                if(current>1 && $(window).width() > 1024){
                    $('.catalog-banner__toggle').hide();
                }
                else{
                    //$('.catalog-banner__toggle').show();
                }
        }
        let copyObj = $(this).closest('.pagination');
        $('.catalog-nav.catalog-nav_top, .catalog-nav.s-none').find('.pagination').remove();
        $('.catalog-nav.catalog-nav_top, .catalog-nav.s-none').find('.catalog-sorting').after(copyObj);
        let sortValue = $('#catalog_sort').val();
        let url = location.pathname + '?PAGEN_1='+current;
        if(sortValue != "sort")url = url + "&sort="+sortValue;
        window.history.pushState({}, null, url);
        //scroll
        /*let scrollObj = $('article[data-page="'+current+'"]:first');
        let destination = $(scrollObj).offset().top;
        $("html,body").animate({scrollTop: destination}, 500);*/
        toggleShowMoreProducts(current);
        return false;
    });
    $('body').on('click', '.pagination .next', function(){
        $('li.current').next().find('a').trigger('click');
    });
    $('body').on('click', '.pagination .prev', function(){
        $('li.current').prev().find('a').trigger('click');
    });

    breadCrumbsAndSmartFilter();

    function createAutoComplete(result){
        if(result){
            $('#LOCATION_LIST').html('');
            let resultArr = JSON.parse(result);
            for(let i in resultArr["suggestions"]){
                $('#LOCATION_LIST').append('<option>'+resultArr["suggestions"][i]['unrestricted_value']+'</option>');
            }
            return resultArr;
        }
    }


    /*
    var token = "4b411b8c9ed9a6eac5dafc8aabd0f73e535ef422";
    function join(arr) {
        var separator = arguments.length > 1 ? arguments[1] : ", ";
        return arr.filter(function(n){return n}).join(separator);
    }
    function geoQuality(qc_geo) {
        var localization = {
            "0": "точные",
            "1": "ближайший дом",
            "2": "улица",
            "3": "населенный пункт",
            "4": "город"
        };
        return localization[qc_geo] || qc_geo;
    }
    function geoLink(address) {
        return join(["<a target=\"_blank\" href=\"",
            "https://maps.yandex.ru/?text=",
            address.geo_lat, ",", address.geo_lon, "\">",
            address.geo_lat, ", ", address.geo_lon, "</a>"], "");
    }
    function showPostalCode(address) {
        $("#postal_code").val(address.postal_code);
    }
    function showRegion(address) {
        $("#region").val(join([
            join([address.region_type, address.region], " "),
            join([address.area_type, address.area], " ")
        ]));
    }
    function showCity(address) {
        $("#city").val(join([
            join([address.city_type, address.city], " "),
            join([address.settlement_type, address.settlement], " ")
        ]));
    }
    function showStreet(address) {
        $("#street").val(
            join([address.street_type, address.street], " ")
        );
    }
    function showHouse(address) {
        $("#house").val(join([
            join([address.house_type, address.house], " "),
            join([address.block_type, address.block], " ")
        ]));
    }
    function showFlat(address) {
        $("#flat").val(
            join([address.flat_type, address.flat], " ")
        );
    }
    function showGeo(address) {
        if (address.qc_geo != "5") {
            var geo = geoLink(address) + " (" + geoQuality(address.qc_geo) + ")";
            $("#geo").html(geo);
        }
    }
    function showSelected(suggestion) {
        var address = suggestion.data;
        showPostalCode(address);
        showRegion(address);
        showCity(address);
        showStreet(address);
        showHouse(address);
        showFlat(address);
        showGeo(address);
    }

    if (typeof suggestions == 'function') {
        $('input[name="LOCATION"]').suggestions({
            token: token,
            type: "ADDRESS",
            onSelect: showSelected
        });
    }
    */


    if($('.product-composition').length){
        if($('.product-composition').find('.composition-card').length>2){
            $('.product-composition').find('.composition-card:gt(1)').addClass('hidden');
            $('.product-composition__all').removeClass('hidden');
        }
    }
    $('body').on('click', '.product-composition__all', function(){
        $('.composition-card').removeClass('hidden');
        $(this).addClass('hidden');
    })

    if($('.catalog-banner-mobile').length){
        let posNum = Math.floor($('[data-page=1]').length/2)-1;
        $('.product-preview-wrapper:eq('+posNum+')').after($('.catalog-banner-mobile'));
    }
    /**/
});
