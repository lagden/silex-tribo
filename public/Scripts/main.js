(function(window) {

    'use strict';

    var $ = window.jQuery,
        $container = $('.packery');

    // Packery
    $container.packery();

    // Adiciona caixas no Packery
    $('.loadmore').on('click.loadmore', function(ev) {
        ev = ev || event;
        if (ev) {
            ev.preventDefault();
            ev.stopPropagation();
        }
        var url = this.getAttribute('data-url');
        var container = this.getAttribute('data-container');
        $.get(url, function(r) {
            if (r.success) {
                var elems = [];
                var $elems = $(r.html);
                $elems.each(function(k, v) {
                    elems.push(v);
                });

                $packery = $('#' + container);
                if ($packery.length > 0) {
                    $packery.append($elems);
                    packery = $packery.data('packery');
                    packery.appended(elems);
                }
            }
        }, 'json');
    });

    // Slider da Home
    if ( $('.flexslider.banners').length > 0 ) {
        $('.flexslider.banners').flexslider({
            directionNav: false,
            slideshowSpeed: 4000,
            useCSS: false,
            animation: 'slide'
        });
    }

    // Slider da Últimas
    if ( $('.flexslider.galeria').length > 0 ) {
        $('#galeria-de-fotos-thumbs').flexslider({
            controlNav: false,
            animation: 'slide',
            animationLoop: false,
            multipleKeyboard: false,
            maxItems: 4,
            itemWidth: 130,
            itemMargin: -5,
            useCSS: false,
            asNavFor: '#galeria-de-fotos'
        });

        $('#galeria-de-fotos').flexslider({
            controlNav: false,
            slideshowSpeed: 4000,
            useCSS: false,
            multipleKeyboard: false,
            animationLoop: false,
            animation: 'slide',
            sync: '#galeria-de-fotos-thumbs'
        });
    }

})(window);