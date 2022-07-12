$(function() {
    $('.bxr-element-video-card-iframe-e .bxr-element-video-card-iframe-img').add(".bxr-element-video-card-iframe-list .element-video-card-title a").click(function() {
        url = $(this).attr("data-url");

        if(url === undefined || url == "")
            return false;

        $.fancybox.open ({
            'type': 'iframe',
            'src': url
        });
        return false;
    });
});
