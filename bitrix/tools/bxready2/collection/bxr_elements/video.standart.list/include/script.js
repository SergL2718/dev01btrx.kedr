function resizeVideoMEJsList() {
    var correlation = 1.7;
    
    var  wideoEl = $(".bxr-element-video-card-mej-list .bxr-element-video-card-mej-e");
    w = wideoEl.width();

    if(w<200)
       w = 200; 
   
    h = Math.ceil(w/correlation);

    $(".bxr-element-video-card-mej-e .mejs-container").not(".mejs-container-fullscreen").css({
        width:  w+"px",
        height: h+"px",
        "min-width":  w+"px",
        "min-height": h+"px",
    });
    
    /*if(wideoEl.attr("data-resize-width")!=w) {
        var elSize = [];
        elSize["title"] = 0;
        elSize["description"] = 0;
        
        wideoEl.find(".element-video-card-description").css({"height":"auto"});
        wideoEl.find(".element-video-card-title").css({"height":"auto"});

        wideoEl.each(function(indx, element){

            if(elSize["title"] <= $(element).find(".element-video-card-title").height()) {
                elSize["title"] = $(element).find(".element-video-card-title").height();
            }
            
            if(elSize["description"] <= $(element).find(".element-video-card-description").height())
                elSize["description"] = $(element).find(".element-video-card-description").height();
        });
        
        if(elSize["title"] != 0 || elSize["description"]!=0) {
            wideoEl.attr("data-resize-width", w);
            wideoEl.find(".element-video-card-description").height(elSize["description"]);
            wideoEl.find(".element-video-card-title").height(elSize["title"]);
        }
    }*/
}

$(function() {
    countElVideo = $(".bxr-element-video-card-mej-e").length;
    e = 0;
    $('.bxr-element-video-card-mej-list video').mediaelementplayer({
        plugins: ['flash','silverlight','youtube','vimeo'],
        success: function() {
            ++e;
            console.info(e);
            if(e==countElVideo) {
                resizeVideoMEJsList();
            }
        }
    });
});

$(window).resize(function() {
    resizeVideoMEJsList();
});
