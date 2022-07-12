if (window){
    window.bxrNewsVerticalV1 = {
        resizeVerticalBlock: function(){
            var $maxHeight = [];
            var $maxNameHeight = [];

            // resize names
            $('.bxr-news-vertical-v1[data-resize=1]').each(function(){

                uid = $(this).data('uid');
                $nameContainer = $(this).children('.bxr-section-container').find('.bxr-element-name');

                if (!(uid in $maxNameHeight)) {
                    $maxNameHeight[uid] = 0;
                }

                if ($nameContainer.height() > $maxNameHeight[uid]) $maxNameHeight[uid] = $nameContainer.height();

            });

            $('.bxr-news-vertical-v1[data-resize=1]').each(function(){
                uid = $(this).data('uid');
                $nameContainer = $(this).children('.bxr-section-container').find('.bxr-element-name');

                if ($nameContainer.height() <= $maxNameHeight[uid]) {
                    $nameContainer.height($maxNameHeight[uid]);
                }

            });

            // resize container

            $('.bxr-news-vertical-v1[data-resize=1]').each(function(){

                uid = $(this).data('uid');
                if (!(uid in $maxHeight)) {
                    $maxHeight[uid] = 0;
                }
                if ($(this).height()>$maxHeight[uid]) $maxHeight[uid] = $(this).height();
            });

            $('.bxr-news-vertical-v1[data-resize=1]').each(function(){
                uid = $(this).data('uid');
                if ($(this).height() <= $maxHeight[uid]) {
                    $(this).children('.bxr-section-container').height($maxHeight[uid]);
                    $(this).height($maxHeight[uid]);
                }
            });
        }
    }

    $(document).ready(function(){
        bxrNewsVerticalV1.resizeVerticalBlock();
    });
    
    if (typeof BXReady.loader != 'object')
            BXReady.loader = [];
    BXReady.loader.push(bxrNewsVerticalV1.resizeVerticalBlock);

    $(window).resize(function(){
        bxrNewsVerticalV1.resizeVerticalBlock();
    });
}

