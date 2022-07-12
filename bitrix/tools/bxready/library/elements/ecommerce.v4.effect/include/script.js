if (window){
    var _init;
    window.catalogEcommerceV4Effect = {
        resizeVerticalBlock: function(){
            var $maxHeight = [];
            var $maxNameHeight = [];
            // resize names

            $('.bxr-ecommerce-v4-effect[data-resize=1]').each(function(){

                uid = $(this).data('uid');
                
                $nameContainer = $(this).find('.bxr-element-name');
                
                if (!(uid in $maxNameHeight)) {
                    $maxNameHeight[uid] = 0;
                }
                
                if ($nameContainer.height() > $maxNameHeight[uid]) $maxNameHeight[uid] = $nameContainer.height();
            });

            $('.bxr-ecommerce-v4-effect[data-resize=1]').each(function(){

                uid = $(this).data('uid');
                
                $nameContainer = $(this).find('.bxr-element-name');
                
                if ($nameContainer.height() <= $maxNameHeight[uid]) {
                    $nameContainer.height($maxNameHeight[uid]);
                }

            });

            // resize container

            $('.bxr-ecommerce-v4-effect[data-resize=1]').each(function(){

                uid = $(this).data('uid');
                if (!(uid in $maxHeight)) {
                    $maxHeight[uid] = 0;
                }
                if ($(this).height()>$maxHeight[uid]) $maxHeight[uid] = $(this).height();
            });

            $('.bxr-ecommerce-v4-effect[data-resize=1]').each(function(){

                uid = $(this).data('uid');
                if ($(this).height() <= $maxHeight[uid]) {
                    $(this).height($maxHeight[uid]);
                }
            });
        }
    }

    if (typeof BXReady.Market.loader != 'object')
            BXReady.Market.loader = [];
    BXReady.Market.loader.push(catalogEcommerceV4Effect.resizeVerticalBlock);

    $(window).resize(function(){
        catalogEcommerceV4Effect.resizeVerticalBlock();
    });

    $(document).on("click", ".bxr-basket-btn-form", function() {
        formBlock = $(this).closest('.bxr-basket-btn-wr').find('form');
        if (!$(formBlock).hasClass('visible-form')) {
            $(formBlock).addClass('visible-form');
            $(this).closest("li").find('.btn-tooltip').addClass('hidden-tooltip');
        } else  {
            $(formBlock).removeClass('visible-form');
            $(this).closest("li").find('.btn-tooltip').removeClass('hidden-tooltip');
        }
    });
    
    var current_offer_id;
    var trade_id;
    var trade_name;
    var trade_link;
    var formRequestMsg;
    
    $(document).on("mouseover", ".bxr-ecommerce-v4-effect .bxr-trade-request", function() {
        current_offer_id = $(this).data('offer-id');
        trade_id = $(this).data('trade-id');
        trade_name = $(this).data('trade-name');
        trade_link = $(this).data('trade-link');
        
        strParams = "";
        $(this).closest('.bxr-ecommerce-v4-effect').find('li.bx_active').each(function() {
            strParams += $(this).data('prop-name')+': '+$(this).attr('title')+', ';
        });
        strParams = strParams.slice(0,-2);
        
        formRequestMsg = $(this).data('msg').replace("#PARAMS#",strParams);
    });
}

