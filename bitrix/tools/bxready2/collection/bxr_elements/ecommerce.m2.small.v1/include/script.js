if (window){
    window.bxrM2EcommerceSmallV1 = {
        resizeVerticalBlock: function(isLazy, imgContainerHeight){
            var $maxHeight = [];
            var $maxNameHeight = [];
            var $maxPriceHeight = [];
            var $maxOldPriceHeight = [];

            //clear last settings
            $('.bxr-m2-ecommerce-small-v1[data-resize=1]').each(function(){
                if ($(this).data('resize') == 2 && isLazy != "true") 
                    return;

                $nameContainer = $(this).children('.bxr-element-container').children('.bxr-element-name');
                $oldPriceContainer = $(this).children('.bxr-element-container').find('.bxr-market-old-price');
                $priceContainer = $(this).children('.bxr-element-container').children('.bxr-element-price');
                
                $(this).height('auto');
                $(this).children('.bxr-element-container').height('auto');

                $nameContainer.height('auto');
                $oldPriceContainer.height('auto');
                $priceContainer.height('auto');
            });

            // resize names, prices            
            $('.bxr-m2-ecommerce-small-v1[data-resize=1]').each(function(){
                uid = $(this).data('uid');

                if ($(this).data('resize') == 2 && isLazy != "true") 
                    return;
                
                $nameContainer = $(this).children('.bxr-element-container').children('.bxr-element-name');
                $oldPriceContainer = $(this).children('.bxr-element-container').find('.bxr-market-old-price');
                $priceContainer = $(this).children('.bxr-element-container').children('.bxr-element-price');
                
                if (!(uid in $maxNameHeight))
                    $maxNameHeight[uid] = 0;

                if (!(uid in $maxOldPriceHeight))
                    $maxOldPriceHeight[uid] = 0;
                
                if (!(uid in $maxPriceHeight))
                    $maxPriceHeight[uid] = 0;

                if ($nameContainer.height() > $maxNameHeight[uid]) $maxNameHeight[uid] = $nameContainer.height();
                if ($oldPriceContainer.height() > $maxOldPriceHeight[uid]) $maxOldPriceHeight[uid] = $oldPriceContainer.height();
                if ($priceContainer.height() > $maxPriceHeight[uid]) $maxPriceHeight[uid] = $priceContainer.height();

            });

            $('.bxr-m2-ecommerce-small-v1[data-resize=1]').each(function(){
                uid = $(this).data('uid');

                if ($(this).data('resize') == 2 && isLazy != "true") 
                    return;

                $nameContainer = $(this).children('.bxr-element-container').children('.bxr-element-name');
                $oldPriceContainer = $(this).children('.bxr-element-container').find('.bxr-market-old-price');
                $priceContainer = $(this).children('.bxr-element-container').children('.bxr-element-price');
                                
                if ($nameContainer.height() <= $maxNameHeight[uid])
                    $nameContainer.height($maxNameHeight[uid]);

                if ($oldPriceContainer.height() <= $maxOldPriceHeight[uid])
                    $oldPriceContainer.height($maxOldPriceHeight[uid]);
                
                if ($priceContainer.height() <= $maxPriceHeight[uid])
                    $priceContainer.height($maxPriceHeight[uid]);
            });

            // resize container
            $('.bxr-m2-ecommerce-small-v1[data-resize=1]').each(function(){
                uid = $(this).data('uid');

                if ($(this).data('resize') == 2 && isLazy != "true") 
                    return;

                if (!(uid in $maxHeight)) 
                    $maxHeight[uid] = 0;
                if ($(this).height()>$maxHeight[uid]) $maxHeight[uid] = parseInt($(this).css("height"));
            });

            $('.bxr-m2-ecommerce-small-v1[data-resize=1]').each(function(){
                uid = $(this).data('uid');

                if ($(this).data('resize') == 2 && isLazy != "true") {
                    return ;
                }

                $(this).data('resize', '2');

                if ($(this).height() <= $maxHeight[uid]) {
                    $(this).height($maxHeight[uid]);
                    $(this).children('.bxr-element-container').height($maxHeight[uid]-22);
                }
            });
        },

        setMobileClasses: function() {
            if (jQuery.browser.mobile) {
                $('.bxr-m2-ecommerce-small-v1 .bxr-element-container .bxr-indicator-item-compare').addClass('always-show');
                $('.bxr-m2-ecommerce-small-v1 .bxr-element-container .bxr-indicator-item-favor').addClass('always-show');
            }
        },
        
        init: function(isLazy, imgContainerHeight) {
            bxrM2EcommerceSmallV1.resizeVerticalBlock(isLazy, imgContainerHeight);
            bxrM2EcommerceSmallV1.setMobileClasses();
            $("img.lazy").lazyload();
        },
    };
    
    $(window).on("load", function() {
        bxrM2EcommerceSmallV1.init();    
    });
}