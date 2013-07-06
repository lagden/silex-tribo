// Packery
(function(window) {

    'use strict';

    var $ = window.jQuery,
        $container = $('.packery');

    // Init
    $container.packery();

    var $packeryDestaquesHome = $('#packeryDestaquesHome'),
        packeryDestaquesHome = $packeryDestaquesHome.data('packery');

})(window);


// Home
(function(window) {

    'use strict';

    var $ = window.jQuery,
        $packeryDestaquesHome = $('#packeryDestaquesHome'),
        packeryDestaquesHome = $packeryDestaquesHome.data('packery');

    $('#loadmore-destaque').on('click.loadmore', function(ev){
        ev = ev || event;
        if (ev) {
            ev.preventDefault();
            ev.stopPropagation();
        }
        $.get(this.getAttribute('data-url'), function(r) {
            if (r.success) {
                var elems = [];
                var $elems = $(r.html);
                $elems.each(function(k, v){
                    elems.push(v);
                });

                $packeryDestaquesHome.append($elems);
                packeryDestaquesHome.appended(elems);
            }
        }, 'json');
    });

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

    if ( $('.flexslider.banners').length > 0 ) {
        $('.flexslider.banners').flexslider({
            directionNav: false,
            slideshowSpeed: 4000,
            useCSS: false,
            animation: 'slide'
        });
    }

})(window);