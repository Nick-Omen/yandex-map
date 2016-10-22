jQuery(document).ready(function($){
    "use strict";

    $('[name="use_map_coordinates"]').change(function(){
        $('.coordinates').hide().filter('.' + $(this).val()).show();
    });
});

var AdminYandexMapClass = function(initialConfig){
    "use strict";

    var placemark = placemark || {};
    var searchControl = searchControl || {};
    var map = map || {};
    var lib = this;

    lib.init = function(){

        map = new YandexMapClass(document.getElementById("admin_page_map"), yandexMapConfig_admin_page);
        searchControl = map.getSearchControls();
        lib.setInitialMarker();
        lib.searchControlsHandler();
        lib.mapHandler();
    };

    lib.markerPositionChanged = function(){

        var marker = this;
        var markerPosition = marker.geometry.getCoordinates();
        var mapZoom = map.getZoom();

        lib.applyValuesToInput({
            lat: markerPosition[0],
            lng: markerPosition[1],
            zoom: mapZoom.toString()
        });
    };

    lib.setInitialMarker = function(position){

        if(!position) {
            position = {
                lat: initialConfig.lat,
                lng: initialConfig.lng
            }
        }

        placemark = map.createMarker({
            lat: position.lat,
            lng: position.lng,
            options: {
                draggable: true
            }
        });

        placemark._addEvents({
            'dragend': lib.markerPositionChanged
        });

        map.placeMarker(placemark);
    };

    lib.searchControlsHandler = function(){

        searchControl.events.add('resultshow', lib.searchControlsUsed)
    };

    lib.mapHandler = function(){

        map._addEvents({
            'boundschange': function(){
                document.querySelector('input.zoom').setAttribute('value', map.getZoom().toString());
            }
        });
    };

    lib.applyValuesToInput = function(values){

        document.querySelector('input.lat').setAttribute('value', values.lat || '');
        document.querySelector('input.lng').setAttribute('value', values.lng || '');
        document.querySelector('input.zoom').setAttribute('value', values.zoom || '');
    };

    lib.searchControlsUsed = function(){

        searchControl.clear();
        var mapCenter = map.getCenter();
        lib.setInitialMarker({
            lat: (mapCenter[0] || initialConfig.lat),
            lng: (mapCenter[1] || initialConfig.lng)
        });
        lib.applyValuesToInput({
            lat: (mapCenter[0] || initialConfig.lat),
            lng: (mapCenter[1] || initialConfig.lng),
            zoom: map.getZoom().toString()
        });
    };

    lib.init();
};