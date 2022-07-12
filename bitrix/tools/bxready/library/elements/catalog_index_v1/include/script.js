if (window){
    window.catalogIndexV1 = {
        resizeVerticalBlock: function(){

        var $maxHeight = [];
        var $maxNameHeight = [];

        // resize names

        $('.bxr-catalog-index-v1[data-resize=1]').each(function(){

            uid = $(this).data('uid');
            $nameContainer = $(this).children('.bxr-element-container').children('.bxr-element-name');

            if (!(uid in $maxNameHeight)) {
                $maxNameHeight[uid] = 0;
            }

            if ($nameContainer.height() > $maxNameHeight[uid]) $maxNameHeight[uid] = $nameContainer.height();

        });

        $('.bxr-catalog-index-v1[data-resize=1]').each(function(){

            uid = $(this).data('uid');
            $nameContainer = $(this).children('.bxr-element-container').children('.bxr-element-name');

            if ($nameContainer.height() <= $maxNameHeight[uid]) {
                $nameContainer.height($maxNameHeight[uid]);
            }

        });

        // resize container

        $('.bxr-catalog-index-v1[data-resize=1]').each(function(){

            uid = $(this).data('uid');
            if (!(uid in $maxHeight)) {
                $maxHeight[uid] = 0;
            }
            if ($(this).height()>$maxHeight[uid]) $maxHeight[uid] = $(this).height();
        });

        $('.bxr-catalog-index-v1[data-resize=1]').each(function(){

            uid = $(this).data('uid');
            if ($(this).height() <= $maxHeight[uid]) {
                $(this).children('.bxr-element-container').height($maxHeight[uid]);
                $(this).height($maxHeight[uid]);
            }
        });

    }
    }


    $(document).ready(function(){

        //catalogIndexV1.resizeVerticalBlock();

    });

    $(window).resize(function(){
        //catalogIndexV1.resizeVerticalBlock();
    });
}


