document.addEventListener('DOMContentLoaded', function () {
    $('[data-modal]').on('click', openModal);

    $('[data-video]').on('click', function () {
        let modal = $(this).data("target"),
            videoSRC = $(this).attr("data-video"),
            videoSRCauto = videoSRC + "?&autoplay=1";
        $(modal + ' iframe').attr('src', videoSRCauto);
    });

    function openModal(e) {
        e.preventDefault();
        e.stopPropagation();
        let scroll = calcScroll();
        let name = $(this).attr('data-modal');

        if(name == "modal-code"){
            if(!$('input[name="AUTH_START_IN"]').val()){
                return false;
            }
        }

        $('.modal').fadeOut('fast');
        $('.modal-container').removeClass('active');
        $('#' + name).each(function () {
            if($(window).width() > 767) {
                $(this).fadeIn('fast');
            } else {
                $(this).fadeIn('fast');
                $(this).find('.modal-container').addClass('active');
            }
            $(this).fadeIn('fast');
            if ($('.modal-overlay').length ) {

            } else {
                $('body').addClass('modal-open').append('<div class="modal-overlay"></div>');
            }

            document.body.style.paddingRight = `${scroll}px`;
        })
    }

    function closeModal() {
        $('.modal').removeClass('show').fadeOut();
        $('.modal-container').removeClass('active');
        setTimeout(function () {
            $('body').find('.modal-overlay').addClass('out');
        }, 100)
        setTimeout(function () {
            $('body').removeClass('modal-open').removeAttr("style").find('.modal-overlay').remove();
        }, 300)
        setTimeout(function () {
            $('#modal-video').find('iframe').attr('src', '');
        }, 300)
    }

    $('[data-modal-close]').on('click', function(){
        closeModal();
    });
    $('.modal-container').on('click', function (e) {
        e.stopPropagation();
    })
    $(document).on('click', function () {
        closeModal();
    })
    function calcScroll() {
        let div = document.createElement('div');

        div.style.width = '50px';
        div.style.height = '50px';
        div.style.overflowY = 'scroll';
        div.style.visibility = 'hidden';

        document.body.appendChild(div);

        let scrollWidth = div.offsetWidth - div.clientWidth;
        div.remove();

        return scrollWidth;
    }
})
