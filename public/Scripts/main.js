(function(window) {

    'use strict';

    var $ = window.jQuery,
        $container = $('.packery');

    // Isotope
    $container.isotope({
        itemSelector: '.element'
    });

    // Combo Isotope Filter
    var $theComboFilter = $('.theComboFilter');
    $theComboFilter.on('change.filterIsotope', function(ev) {
        ev = ev || event;
        if (ev) {
            ev.preventDefault();
            ev.stopPropagation();
        }
        var selector = this.value
        cleanup(this, selector);
        $container
            .isotope({
                filter: selector
            })
            .isotope('reLayout');
    });

    // The Combo
    var combo = $('.theCombo').theCombo();

    var currentLang = $('body').data('lingua') || 'pt-BR';

    // Scroll infinito
    $container.infinitescroll({
            navSelector: '#page_nav',
            nextSelector: '#page_nav a',
            itemSelector: '.element',
            maxPage: $container.data('maxPage'),
            loading: {
                msgText: (currentLang == 'pt-BR') ? "<em>Carregando...</em>" : "<em>Loading the next set of posts...</em>",
                finishedMsg: (currentLang == 'pt-BR') ? 'Não há mais páginas para carregar.' : 'No more pages to load.',
                img: 'http://i.imgur.com/qkKy8.gif'
            }
        },
        function(newElements) {
            $container.isotope('appended', $(newElements));
            $theComboFilter.trigger('change.filterIsotope');
        });

    // Caption dos Boxes
    $('.cap').on('click.caption', function(ev) {
        ev = ev || event;
        if (ev) {
            ev.preventDefault();
            ev.stopPropagation();
        }
        var $this = $(this);
        var url = $(this).data('url');
        if (url) window.location = url;
    });

    // Media
    $('.fancybox-media').fancybox({
        openEffect: 'elastic',
        closeEffect: 'elastic',
        swf: {
            wmode: 'transparent',
            allowfullscreen: 'true',
            allowscriptaccess: 'always'
        },
        ajax: {
            headers: {
                'X-fancyBox': true
            }
        }
    });

    // Slider da Home
    if ($('.flexslider.banners').length > 0) {
        $('.flexslider.banners').flexslider({
            directionNav: false,
            slideshowSpeed: 4000,
            useCSS: false,
            animation: 'slide'
        });
    }

    // Slider da Últimas
    if ($('.flexslider.galeria').length > 0) {
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

    function cleanup(that, value){
        $theComboFilter.val('*');
        $(that).val(value);
        if(combo) combo.theCombo('change');
    }

    window.$container = $container;

})(window);