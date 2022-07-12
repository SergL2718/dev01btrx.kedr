if (window){
    window.catalogEcommerceV3LiteColor = {
        resizeVerticalBlock: function(){
            var $maxHeight = [];
            var $maxNameHeight = [];
            var $maxRatingHeight = [];
            var $maxPriceHeight = [];
            var $maxBActionHeight = [];
            var $maxBottomHeight = [];
            // resize names

            $('.bxr-ecommerce-v3-lite-color[data-resize=1]').each(function(){

                uid = $(this).data('uid');
                $nameContainer = $(this).children('.bxr-element-container').find('.bxr-element-name');
                $ratingContainer = $(this).children('.bxr-element-container').find('.bxr-ra-string');
                $priceContainer = $(this).children('.bxr-element-container').find('.bxr-element-price');
                $bActionContainer = $(this).children('.bxr-element-container').find('.bxr-element-action');
                $bottomContainer = $(this).children('.bxr-element-container').find('.bxr-bottom-block');
                
                if (!(uid in $maxNameHeight)) {
                    $maxNameHeight[uid] = 0;
                }
                
                if (!(uid in $maxRatingHeight)) {
                    $maxRatingHeight[uid] = 0;
                }
                
                if (!(uid in $maxPriceHeight)) {
                    $maxPriceHeight[uid] = 0;
                }
                
                if (!(uid in $maxBActionHeight)) {
                    $maxBActionHeight[uid] = 0;
                }
                
                if (!(uid in $maxBottomHeight)) {
                    $maxBottomHeight[uid] = 0;
                }
                
                if ($nameContainer.height() > $maxNameHeight[uid]) $maxNameHeight[uid] = $nameContainer.height();
                if ($ratingContainer.height() > $maxRatingHeight[uid]) $maxRatingHeight[uid] = $ratingContainer.height();
                if ($priceContainer.height() > $maxPriceHeight[uid]) $maxPriceHeight[uid] = $priceContainer.height();
                if ($bActionContainer.height() > $maxBActionHeight[uid]) $maxBActionHeight[uid] = $bActionContainer.height();
                if ($bottomContainer.height() > $maxBottomHeight[uid]) $maxBottomHeight[uid] = $bottomContainer.height();
            });

            $('.bxr-ecommerce-v3-lite-color[data-resize=1]').each(function(){

                uid = $(this).data('uid');
                $nameContainer = $(this).children('.bxr-element-container').find('.bxr-element-name');
                $ratingContainer = $(this).children('.bxr-element-container').find('.bxr-ra-string');
                $priceContainer = $(this).children('.bxr-element-container').find('.bxr-element-price');
                $bActionContainer = $(this).children('.bxr-element-container').find('.bxr-element-action');
                $bottomContainer = $(this).children('.bxr-element-container').find('.bxr-bottom-block');
                
                if ($nameContainer.height() <= $maxNameHeight[uid]) {
                    $nameContainer.height($maxNameHeight[uid]);
                }

                if ($ratingContainer.height() <= $maxRatingHeight[uid]) {
                    $ratingContainer.height($maxRatingHeight[uid]);
                }
                
                if ($priceContainer.height() <= $maxPriceHeight[uid]) {
                    $priceContainer.height($maxPriceHeight[uid]);
                }

                if ($bActionContainer.height() <= $maxBActionHeight[uid]) {
                    $bActionContainer.height($maxBActionHeight[uid]);
                }

                if ($bottomContainer.height() <= $maxBottomHeight[uid]) {
                    $bottomContainer.height($maxBottomHeight[uid]);
                }

            });

            // resize container

            $('.bxr-ecommerce-v3-lite-color[data-resize=1]').each(function(){

                uid = $(this).data('uid');
                if (!(uid in $maxHeight)) {
                    $maxHeight[uid] = 0;
                }
                if ($(this).height()>$maxHeight[uid]) $maxHeight[uid] = $(this).height();
            });

            $('.bxr-ecommerce-v3-lite-color[data-resize=1]').each(function(){

                uid = $(this).data('uid');
                if ($(this).height() <= $maxHeight[uid]) {
                    $(this).children('.bxr-element-container').height($maxHeight[uid]-2);
                    $(this).height($maxHeight[uid]);
                }
            });
        }
    }
    
    if (typeof BXReady.Market.loader != 'object')
            BXReady.Market.loader = [];
    BXReady.Market.loader.push(catalogEcommerceV3LiteColor.resizeVerticalBlock);

    $(window).resize(function(){
        catalogEcommerceV3LiteColor.resizeVerticalBlock();
    });
    
    var current_offer_id;
    var trade_id;
    var trade_name;
    var trade_link;
    var formRequestMsg;
    
    $(document).on("mouseover", ".bxr-ecommerce-v3-lite-color .bxr-trade-request", function() {
        current_offer_id = $(this).data('offer-id');
        trade_id = $(this).data('trade-id');
        trade_name = $(this).data('trade-name');
        trade_link = $(this).data('trade-link');
        
        strParams = "";
        $(this).closest('.bxr-ecommerce-v3-lite-color').find('li.bx_active').each(function() {
            strParams += $(this).data('prop-name')+': '+$(this).attr('title')+', ';
        });
        strParams = strParams.slice(0,-2);
        
        formRequestMsg = $(this).data('msg').replace("#PARAMS#",strParams);
    });

}

