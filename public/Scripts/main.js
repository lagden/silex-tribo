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
        var $this = $(this);
        var url = $this.data('url');
        var container = $this.data('container');
        var pagina = $this.data('pagina');
        var selector = $this.data('selector');
        $.post(url, {"page": pagina}, function(r) {
            if (r.success) {
                var elems = [];
                var $elems = $(r.html);
                $elems.filter( selector ).each(function(k, v) {
                    elems.push(v);
                });

                if(r.pagina < r.paginas)
                    $this.data('pagina', r.pagina + 1);
                else
                    $this.hide();

                var $currContainer = $('#' + container);
                if ($currContainer.length > 0) {
                    $currContainer.append($elems);
                    var packery = $currContainer.data('packery');
                    if(packery)
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