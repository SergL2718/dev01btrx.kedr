if (window){

    window.brandListV1 = {

        resizeVerticalBlock: function(){

            var $maxHeight = [];
            var $maxElementHeight = [];

            elementBlock = ['image'];
            elemenID = '.bxr-brand-list-v1[data-resize=1]';

            for (i=0; i<elementBlock.length; i++){

                $maxElementHeight = [];

                $(elemenID).each(function(){

                    uid = $(this).data('uid');
                    $nameContainer = $(this).children('.bxr-element-container').children('.bxr-element-'+elementBlock[i]);

                    if (!(uid in $maxElementHeight)) {
                        $maxElementHeight[uid] = 0;
                    }

                    if ($nameContainer.height() > $maxElementHeight[uid]) $maxElementHeight[uid] = $nameContainer.height();

                });

                $(elemenID).each(function(){

                    uid = $(this).data('uid');
                    $nameContainer = $(this).children('.bxr-element-container').children('.bxr-element-'+elementBlock[i]);
                    $nameContainer.height($maxElementHeight[uid]);
                    $nameContainer.css('line-height', $maxElementHeight[uid]+'px');
                });
            }

            console.log($maxElementHeight);
            // resize container

            $('.bxr-brand-list-v1[data-resize=1]').each(function(){

                uid = $(this).data('uid');
                if (!(uid in $maxHeight)) {
                    $maxHeight[uid] = 0;
                }
                if ($(this).height()>$maxHeight[uid]) $maxHeight[uid] = $(this).height();
                //console.log($(this));
            });

            $('.bxr-brand-list-v1[data-resize=1]').each(function(){

                uid = $(this).data('uid');
                $(this).children('.bxr-element-container').height($maxHeight[uid]);
                $(this).height($maxHeight[uid]);
            });

        }
    }


    $(window).on('load',function(){

       // brandListV1.resizeVerticalBlock();

    });

    $(window).resize(function(){
       // brandListV1.resizeVerticalBlock();
    });
}


