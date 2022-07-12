$(function() {
    $('.bxr-element-video-card-iframe .bxr-element-video-card-iframe-img').add(".bxr-element-video-card-iframe-col .element-video-card-title a").click(function() {

        url = $(this).attr("data-url");

        if(url === undefined || url == "")
            return false;

        $.fancybox ({
            'type'              :   'iframe',
            'href'              :   url,
            'transitionIn'      :   'elastic',
            'transitionOut'     :   'elastic',
            'speedIn'           :   600,
            'speedOut'          :   200,
            'overlayShow'       :   false
        });
        return false;
    });
});
