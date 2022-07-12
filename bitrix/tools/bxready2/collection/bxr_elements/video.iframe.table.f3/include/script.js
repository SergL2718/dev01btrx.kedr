$(function() {
    $('.bxr-element-video-card-iframe .bxr-element-video-card-iframe-img').add(".bxr-element-video-card-iframe-col .element-video-card-title a").click(function() {
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