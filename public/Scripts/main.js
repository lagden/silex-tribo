// Rotas
(function(window) {

    'use strict';

    var $ = window.jQuery,
        routes = [
            'home'
        ];

    // setup crossroads
    for(var i in routes)
    {
        crossroads.addRoute(routes[i]);
    }
    crossroads.routed.add(scrolla);

    function scrolla(req, data)
    {
        console.log('request',req);
        console.log('route:', data.route, 'params:', data.params, 'isFirst:', data.isFirst);
        var i = $('#' + req);
        TweenLite.to(window, 2, {scrollTo: {y: i.offset().top}, ease:Power4.easeInOut});
    }

    // setup hasher
    function parseHash(newHash, oldHash) {
        console.log(newHash, oldHash)
        crossroads.parse(newHash);
    }

    hasher.initialized.add(parseHash);
    hasher.changed.add(parseHash);
    hasher.init();

    var $container = $('.packery');
    // var $theCombo = $('.theCombo');

    // Inicializa os boxes
    // $container.packery();

    // Google Maps
    /*
    var $mapaCanvas = $('#mapaCanvas');
    if($mapaCanvas) google.maps.event.addDomListener(window, 'load', initializeMaps);

    var titleInitMap = "<h3>FrontInSampa 2013</h3>",
        htmlInitMap = "<p>Rua Augusta, 446 - Centro<br>São Paulo, 09715-295, Brazil</p>",
        map,
        infowindow,
        marker,
        markers = [];

    function initializeMaps()
    {
        // Google Maps Style
        var styledMapJson = [{"featureType":"landscape","stylers":[{"color":"#f4f4f4"}]},{"featureType":"road.arterial","elementType":"geometry.fill","stylers":[{"color":"#888587"}]},{"featureType":"road.arterial","elementType":"labels.text.fill","stylers":[{"color":"#f4f5f4"},{"visibility":"off"}]},{"featureType":"road.arterial","elementType":"labels.text.stroke","stylers":[{"visibility":"on"},{"color":"#ffffff"}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#888788"}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#ffffff"}]},{"featureType":"road.arterial","elementType":"geometry.stroke","stylers":[{"color":"#ffffff"}]},{"featureType":"road.highway","elementType":"labels.text.stroke","stylers":[{"visibility":"off"}]},{"featureType":"road.highway","elementType":"labels.text.fill","stylers":[{"color":"#ffffff"}]},{"featureType":"water","stylers":[{"color":"#3c82a0"}]},{"featureType":"poi",}];
        var styledMap = new google.maps.StyledMapType(styledMapJson,{name: "FRONT IN SAMPA"});

        var initLL = new google.maps.LatLng(-23.512446, -47.460294)

        var mapOptions = {
            center: initLL,
            zoom: 15,
            mapTypeControlOptions: {
                mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'map_style']
            }
        };

        map = new google.maps.Map($mapaCanvas.get(0),mapOptions);
        map.mapTypes.set('map_style', styledMap);
        map.setMapTypeId('map_style');

        infowindow = new google.maps.InfoWindow();

        var docRoot = '';
        var markerIcons = {
            "point": docRoot + 'images/icon_marker.png',
        };

        // Initial Marker
        makeMarker(initLL, markerIcons.point, titleInitMap, htmlInitMap);
    }

    function makeMarker( latlng, image, label, html )
    {
        var contentString = '<b>'+label+'</b><br>'+html;
        marker = new google.maps.Marker({
                position: latlng,
                draggable: false, 
                map: map,
                // icon: image,
                title: label,
                zIndex: Math.round(latlng.lat()*-100000)<<5
        });
        marker.myname = label;
        markers.push(marker);
        google.maps.event.addListener(marker, 'click', function(){
            infowindow.setContent(contentString); 
            infowindow.open(map,marker);
        });
        return marker;
    }
    //*/

})(window);