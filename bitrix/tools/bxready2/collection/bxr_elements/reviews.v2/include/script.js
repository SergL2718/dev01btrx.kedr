if (window){/*
    window.bxrElementReviewsV1 = {
        resizeVerticalBlock: function(){
            var $maxHeight = [];

            // resize container

            $('.bxr-element-reviews-v1[data-resize=1]').each(function(){

                uid = $(this).data('uid');
                if (!(uid in $maxHeight)) {
                    $maxHeight[uid] = 0;
                }
                console.info($(this).height());
                
                $(this).css("height", "auto");
                if ($(this).height()>$maxHeight[uid]) $maxHeight[uid] = $(this).height();
            });

            $('.bxr-element-reviews-v1[data-resize=1]').each(function(){
                uid = $(this).data('uid');
                if ($(this).height() <= $maxHeight[uid]) {
                    //$(this).children('.bxr-section-container').height($maxHeight[uid]);
                    $(this).height($maxHeight[uid]);
                }
            });
        }
    }

    $(document).ready(function(){
        bxrElementReviewsV1.resizeVerticalBlock();
    });
    
    if (typeof BXReady.loader != 'object')
            BXReady.loader = [];
    BXReady.loader.push(bxrElementReviewsV1.resizeVerticalBlock);

    $(window).resize(function(){
        bxrElementReviewsV1.resizeVerticalBlock();
    });*/
}

