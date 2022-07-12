if (window){
    
    var current_offer_id;
    var trade_id;
    var trade_name;
    var trade_link;
    var formRequestMsg;
    
    $(document).on("mouseover", ".bxr-ecommerce-v1-table .bxr-trade-request", function() {
        current_offer_id = $(this).data('offer-id');
        trade_id = $(this).data('trade-id');
        trade_name = $(this).data('trade-name');
        trade_link = $(this).data('trade-link');
        
        strParams = "";
        $(this).closest('.bxr-ecommerce-v1-table').find('li.bx_active').each(function() {
            strParams += $(this).data('prop-name')+': '+$(this).attr('title')+', ';
        });
        strParams = strParams.slice(0,-2);
        
        formRequestMsg = $(this).data('msg').replace("#PARAMS#",strParams);
    });
}




