jQuery(document).ready(function($){
    "use strict";

    /**
     * Change select method for marker.
     */
    $('[name="use_map_coordinates"]').change(function(){
        $('.coordinates').hide().filter('.' + $(this).val()).show();
    });
});

/**
 * Class to control API from admin side.
 *
 * @param initialConfig object Initial config for a map
 * @constructor
 */
var AdminYandexMapClass = function(initialConfig){
    "use strict";

    var lib = this;

    // Initialize map interface
    var mapInterface = new YandexMapInterface(document.getElementById("admin_page_map"), initialConfig);

    /**
     * Listen to placemark position changes.
     *
     * @param event object Event
     */
    lib.placemarkPositionChanged = function(event){

        var placemark = event.get('target');
        var placemarkPosition = placemark.geometry.getCoordinates();

        lib.applyValuesToInput({
            lat: placemarkPosition[0].toString(),
            lng: placemarkPosition[1].toString(),
            zoom: mapInterface.map.getZoom().toString()
        });
    };

    /**
     * Set initial marker on the map
     * and add listeners to it.
     *
     * @param position object Latitude and longitude. Can be undefined.
     */
    lib.setMarkerOnMap = function(position){

        mapInterface.clearMap();

        if(!position){
            position = {
                lat: initialConfig.lat,
                lng: initialConfig.lng
            }
        }

        var placemark = mapInterface.createPlacemark([position.lat, position.lng], {
            hintContent: 'Координаты по-умолчанию.'
        }, {
            draggable: true
        });

        mapInterface.setPlacemark(placemark);
        placemark.dpExtended.addEvent('dragend', lib.placemarkPositionChanged);
    };

    /**
     * Initialize search controls handlers.
     */
    lib.searchControlsHandler = function(){
        mapInterface.searchControls.events.add('resultselect', lib.searchControlUsed);
    };

    /**
     * Initialize map handlers.
     */
    lib.mapHandler = function(){

        mapInterface.map.dpExtended.addEvent('boundschange', function(){
            document.querySelector('input.zoom').setAttribute('value', mapInterface.map.getZoom().toString());
        });
    };

    /**
     * Apply values to inputs.
     *
     * @param values object Map of key - value pairs for inputs.
     */
    lib.applyValuesToInput = function(values){

        for(var key in values){

            if(values.hasOwnProperty(key) && document.querySelector('input.' + key).length !== 0){
                document.querySelector('input.' + key).setAttribute('value', values[key]);
            }
        }
    };

    /**
     * Set marker and change map position when
     * item from search results is selected.
     */
    lib.searchControlUsed = function(result){

        var mapCenter = mapInterface.searchControls.getResultsArray()[result.get('index')].geometry.getCoordinates();

        mapInterface.searchControls.clear();

        lib.setMarkerOnMap({
            lat: (mapCenter[0] || initialConfig.lat),
            lng: (mapCenter[1] || initialConfig.lng)
        });

        lib.applyValuesToInput({
            lat: (mapCenter[0] || initialConfig.lat),
            lng: (mapCenter[1] || initialConfig.lng),
            zoom: mapInterface.map.getZoom().toString()
        });
    };

    /**
     * Initialize admin class and create handlers.
     */
    lib.init = function(){

        lib.setMarkerOnMap();
        lib.searchControlsHandler();
        lib.mapHandler();
    };

    // Initialize class functionality
    lib.init();
};