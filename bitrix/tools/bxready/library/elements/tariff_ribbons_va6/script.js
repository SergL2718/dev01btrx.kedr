$(window).load(function() {
    function autoResizeTariffRibbons() {
        var elements = $('.tariff_ribbons_va6');
        var elementsAdvantages = elements.find('.tariff_advantages');
        
        maxHeight = 0
        elementsAdvantages.each(function(i, e){
            if($(e).height() > maxHeight)
                maxHeight = $(e).height();
        });
        //alert(maxHeight);
        elementsAdvantages.height(maxHeight);
        elements.css('visibility', 'visible');
    }
    
    //$('.tariff_ribbons_va6 .tariff_advantages')
    
    autoResizeTariffRibbons();
    //autoResize(".tariff_ribbons_va6", 'modern_card_horizontal');
 });
