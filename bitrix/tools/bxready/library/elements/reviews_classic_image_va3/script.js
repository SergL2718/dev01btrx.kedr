$(window).load(function() {    
    function autoResizeReviews(element) {
        element = $(element);
        var maxHeight = 0;
        
        element.each(function(i, e){
            if(maxHeight < $(e).height())
                maxHeight = $(e).height();
        });
        console.info(maxHeight);
        element.each(function(i, e){
            $(e).height(maxHeight);
        });
    };
    
    //autoResizeReviews(".reviews_classic_image_va3");
 });