/**
 * Extend Yandex map functionality
 * and create isolated instance of map.
 *
 * @param node object Node of the map.
 * @param initialConfig object Initial config for the map.
 *
 * @returns map object Extended version of the Yandex map.
 * @constructor
 */
var DPMapClass = function(node, initialConfig){
    "use strict";

    // Check if yandex map api is not loaded.
    if(typeof(ymaps) == 'undefined'){
        console.warn("Yandex map wasn't loaded yet.");
        return;
    }

    // Check if yandex map api is not loaded.
    if(typeof(node) == 'undefined'){
        console.warn("Container for map wasn't specified.");
        return;
    }

    // Check if yandex map api is not loaded.
    if(typeof(initialConfig) == 'undefined'){
        console.warn("Map configs wasn't specified.");
        return;
    }

    // Initialize Yandex map
    var map = new ymaps.Map(node, {
        center: [initialConfig.lat, initialConfig.lng],
        zoom: initialConfig.zoom
    });

    // Extend from base class
    map.dpExtended = new DPBaseClass(map);


    /**
     * Get search controls of the map.
     *
     * @returns object Search controls object.
     */
    map.dpExtended.getSearchControls = function(){

        return map.controls.get('searchControl');
    };

    /**
     * Set placemark on the map.
     *
     * @param placemark object Placemark object
     */
    map.dpExtended.setPlacemark = function(placemark){

        map.geoObjects.add(placemark);
    };


    return map;
};
