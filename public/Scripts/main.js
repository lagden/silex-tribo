// Rotas
/*
(function(window) {

    'use strict';

    crossroads.normalizeFn = crossroads.NORM_AS_OBJECT;

    var routes = [
            'home'
        ];

    // Setup crossroads
    for(var i in routes)
        crossroads.addRoute(routes[i]);

    crossroads.addRoute('carrega-mais-destaque', function(req, data){
        //can access captured values as object properties
        console.log(req, data);
    });

    // crossroads.routed.add(scrolla);



    // function scrolla(req, data)
    // {
    //     console.log('request',req);
    //     console.log('route:', data.route, 'params:', data.params, 'isFirst:', data.isFirst);
    //     // var i = $('#' + req);
    //     // TweenLite.to(window, 2, {scrollTo: {y: i.offset().top}, ease:Power4.easeInOut});
    // }

    // Setup hasher
    function parseHash(newHash, oldHash) {
        crossroads.parse(newHash);
    }

    // Hashbang
    hasher.prependHash = '!';
    hasher.initialized.add(parseHash);
    hasher.changed.add(parseHash);
    hasher.init();

})(window);
//*/

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