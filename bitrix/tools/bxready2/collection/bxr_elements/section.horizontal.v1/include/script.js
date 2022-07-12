if (window){
    window.bxrSectionVerticalV1 = {
        resizeVerticalBlock: function(){
            var $maxHeight = [];
            var $maxNameHeight = [];

            // resize names
            $('.bxr-section-horizontal-v1[data-resize=1]').each(function(){

                uid = $(this).data('uid');
                $nameContainer = $(this).children('.bxr-section-container').find('.bxr-element-name');

                if (!(uid in $maxNameHeight)) {
                    $maxNameHeight[uid] = 0;
                }

                if ($nameContainer.height() > $maxNameHeight[uid]) $maxNameHeight[uid] = $nameContainer.height();

            });

            $('.bxr-section-horizontal-v1[data-resize=1]').each(function(){
                uid = $(this).data('uid');
                $nameContainer = $(this).children('.bxr-section-container').find('.bxr-element-name');

                if ($nameContainer.height() <= $maxNameHeight[uid]) {
                    $nameContainer.height($maxNameHeight[uid]);
                }

            });

            // resize container

            $('.bxr-section-horizontal-v1[data-resize=1]').each(function(){

                uid = $(this).data('uid');
                if (!(uid in $maxHeight)) {
                    $maxHeight[uid] = 0;
                }
                if ($(this).height()>$maxHeight[uid]) $maxHeight[uid] = $(this).height();
            });

            $('.bxr-section-horizontal-v1[data-resize=1]').each(function(){
                uid = $(this).data('uid');
                if ($(this).height() <= $maxHeight[uid]) {
                    $(this).children('.bxr-section-container').height($maxHeight[uid]);
                    $(this).height($maxHeight[uid]);
                }
            });
        }
    }

    $(document).ready(function(){
        bxrSectionVerticalV1.resizeVerticalBlock();
    });
    
    if (typeof BXReady.loader != 'object')
            BXReady.loader = [];
    BXReady.loader.push(bxrSectionVerticalV1.resizeVerticalBlock);

    $(window).resize(function(){
        bxrSectionVerticalV1.resizeVerticalBlock();
    });
}

