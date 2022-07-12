$(document).ready(function(){

    function resizeVerticalBlock(){

        var $maxHeight = [];


        $('.bxr_example_element[data-resize=1]').each(function(){

            uid = $(this).data('uid');

            if (!(uid in $maxHeight)) {
                $maxHeight[uid] = 0;
            }
            if ($(this).height()>$maxHeight[uid]) $maxHeight[uid] = $(this).height();
        });

        $('.bxr_example_element[data-resize=1]').each(function(){

            uid = $(this).data('uid');
            if ($(this).height() < $maxHeight[uid])  $(this).height($maxHeight[uid]);

        });

    }

    resizeVerticalBlock();
});


