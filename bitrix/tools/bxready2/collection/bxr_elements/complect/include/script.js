if (window){
    window.bxrM2Complect = {
        
        resizeVerticalBlock: function(){
            var $maxHeight = [];
            var $maxNameHeight = [];
            var $maxPriceHeight = [];

            //clear last settings
            $('.bxr-m2-complect[data-resize=1]').each(function(){     
                
                $nameContainer = $(this).children('.bxr-element-container').children('.bxr-element-name');
                $priceContainer = $(this).children('.bxr-element-container').children('.bxr-element-price');
                
                $(this).height('auto');
                $(this).children('.bxr-element-container').height('auto');                
                $nameContainer.height('auto');                    
                $priceContainer.height('auto');
            });

            // resize names, prices            
            $('.bxr-m2-complect[data-resize=1]').each(function(){
                uid = $(this).data('uid');
                $nameContainer = $(this).children('.bxr-element-container').children('.bxr-element-name');
                $priceContainer = $(this).children('.bxr-element-container').children('.bxr-element-price');
                
                if (!(uid in $maxNameHeight))
                    $maxNameHeight[uid] = 0;
                
                if (!(uid in $maxPriceHeight))
                    $maxPriceHeight[uid] = 0;

                if ($nameContainer.height() > $maxNameHeight[uid]) $maxNameHeight[uid] = $nameContainer.height();
                if ($priceContainer.height() > $maxPriceHeight[uid]) $maxPriceHeight[uid] = $priceContainer.height();

            });

            $('.bxr-m2-complect[data-resize=1]').each(function(){
                uid = $(this).data('uid');
                $nameContainer = $(this).children('.bxr-element-container').children('.bxr-element-name');
                $priceContainer = $(this).children('.bxr-element-container').children('.bxr-element-price');

                if ($nameContainer.height() <= $maxNameHeight[uid])
                    $nameContainer.height($maxNameHeight[uid]);
                
                if ($priceContainer.height() <= $maxPriceHeight[uid])
                    $priceContainer.height($maxPriceHeight[uid]);
            });

            // resize container
            $('.bxr-m2-complect[data-resize=1]').each(function(){
                uid = $(this).data('uid');
                if (!(uid in $maxHeight)) 
                    $maxHeight[uid] = 0;
                if ($(this).height()>$maxHeight[uid]) $maxHeight[uid] = parseInt($(this).height());
            });

            $('.bxr-m2-complect[data-resize=1]').each(function() {
                uid = $(this).data('uid');
                if ($(this).height() <= $maxHeight[uid]) {
                    $(this).height($maxHeight[uid]);
                    $(this).children('.bxr-element-container').height($maxHeight[uid]);
                }
            });
        },
    }

    $(document).ready(function(){
        bxrM2Complect.resizeVerticalBlock();
    });
    
    $(window).resize(function() {
    });
    
    $(window).on("load", function() {
        bxrM2Complect.resizeVerticalBlock();
    })
}

