var fireYandexMapLoaded = function(){
    jQuery(document).trigger('yandexMapLoaded');
};

var YandexMapClass = function(node, mapConfig){
    "use strict";

    // Reference to this.
    var lib = this;

    // Global map object.
    var map = map || {};

    // Check if yandex map api is not loaded.
    if(typeof(ymaps) == 'undefined'){
        console.warn('\'ymaps\' class wasn\'t loaded but class was called');
        return;
    }

    /**
     * Remove map loader's placeholder.
     * @private
     */
    var _removeLoader = function(){
        node.querySelector('.text-loading').remove();
    };

    /**
     * Set map size. Values taken from settings page.
     * @private
     */
    var _setMapSize = function(){
        node.style.width = mapConfig.width;
        node.style.height = mapConfig.height;
    };

    /**
     * Get map zoom.
     * @return number - value of the current map zoom.
     */
    lib.getZoom = function(){

        return map.getZoom();
    };

    /**
     * Get map center.
     * @return array - value of the current map zoom.
     */
    lib.getCenter = function(){

        return map.getCenter();
    };

    /**
     * Set marker to a map and apply event listeners to it.
     * @param data - object - marker data.
     */
    lib.createMarker = function(data){

        var placemark = new ymaps.Placemark([data.lat, data.lng], data.properties || {}, data.options || {});

        /**
         * Bind events to placemark.
         * @param events - object - events set to a marker.
         * @private
         */
        placemark._addEvents = function(events){
            if(typeof(events) != 'undefined'){

                for(var key in events){
                    if(events.hasOwnProperty(key)){

                        placemark.events.add(key, events[key].bind(placemark));
                    }
                }
            }
        };

        return placemark;
    };

    lib._addEvents = function(events){
        if(typeof(events) != 'undefined'){

            for(var key in events){
                if(events.hasOwnProperty(key)){

                    map.events.add(key, events[key].bind(map));
                }
            }
        }
    };

    lib.placeMarker = function(placemark){

        map.geoObjects.add(placemark);
    };

    lib.init = function(){

        _setMapSize();

        map = new ymaps.Map(node, {
            center: [
                mapConfig.lat, mapConfig.lng
            ],
            zoom: mapConfig.zoom
        });

        _removeLoader();
    };

    lib.getSearchControls = function(){

        return map.controls.get('searchControl');
    };

    lib.init();
};
