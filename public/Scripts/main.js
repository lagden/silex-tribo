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
    })

    var socialUpdates = {
        facebook: {
            init: function () {
                var api = this;

                $.ajax({
                    url: 'index.php/facebook-updates',
                    beforeSend: api.loading,
                    success: api.success,
                    error: api.error
                });
            },
            success: function (res) {
                var api = socialUpdates.facebook;

                if (res.success == true) {
                    api.render(res.data.data);
                } else {
                    console.log('Fallback Facebook');
                }

                api.hideLoading();
            },
            render: function (array) {
                console.log('Render');
            },
            error: function () {
                console.log('Facebook Ajax ERROR', arguments);
            },
            loading: function () {
                // console.log('Loading');
            },
            hideLoading: function () {
                // console.log('Loading');
            }
        },
        twitter: {
            init: function () {
                console.log('Init Twitter');
            }
        }
    }

    $('.social-updates').each(function (i, e) {
        var fn = $(e).attr('data-init');

        socialUpdates[fn].init();
    });

})(window);