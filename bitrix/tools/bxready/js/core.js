if (window && jQuery){

    BXRCore = {
        lgMode: 1200,
        mdMode: 960,
        smMode: 771,

        currentMode: null,

        initCurrentMode: function(){
            wMode = $(window).width();
            console.log(wMode);
            if (wMode>this.smMode){
                if (wMode>this.mdMode){
                    if (wMode>this.lgMode){
                        return 'lg';
                    }else return 'md';
                }else return 'sm';
            }else return 'xs';
        },

        verifyResponsiveArea: function(){
            $('div.bxr-include-responsive-area').each(function(){
                if (!$(this).hasClass('hidden-'+BXRCore.currentMode)){
                    if ($(this).data('load') == 0){
                        BXRCore.loadResponsiveArea($(this).attr('id'));
                    }
                }
            });
        },

        loadResponsiveArea: function(element){

            console.log(element);
            element = '#'+element;
            path = $(element).data('src');
            $.ajax({
                url: path,
                success: function(data){
                    $(element).html(data);
            }});
        },

        init : function(){
            if (BXRCore.currentMode == null)
                BXRCore.currentMode = BXRCore.initCurrentMode();
                BXRCore.verifyResponsiveArea();
        },

        resize: function(){
            BXRCore.currentMode = BXRCore.initCurrentMode();
            BXRCore.verifyResponsiveArea();
        }
    }

};
