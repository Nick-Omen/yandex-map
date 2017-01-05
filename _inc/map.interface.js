/**
 * Function triggers on Yandex map API load.
 */
function fireYandexMapLoaded(){

    jQuery(document).trigger('yandexMapLoaded');
}

/**
 * Interface for Yandex map API.
 * Create isolated objects like map, placemark etc.
 * Allow to control map, placemark etc.
 *
 * @param node object Node of the map.
 * @param mapConfig object Config for a map.
 *
 * @return {YandexMapInterface}
 * @constructor
 */
var YandexMapInterface = function(node, mapConfig){
    "use strict";

    var lib = this;
    lib.map = {};
    lib.searchControls = {};
    lib.placemarks = [];

    /**
     * Remove map loader.
     *
     * @private
     */
    var _removeLoader = function(){
        node.querySelector('.text-loading').remove();
    };

    /**
     * Set map size. Values taken from settings page.
     *
     * @private
     */
    var _setMapSize = function(){
        node.style.width = mapConfig.width;
        node.style.height = mapConfig.height;
    };

    /**
     * Create placemark for the instance of the map.
     *
     * @param position array|string Latitude and longitude or valid address string.
     * @param properties object Properties for the placemark.
     * @param options object Options for the placemark.
     *
     * @return object Placemark object
     */
    lib.createPlacemark = function(position, properties, options){

        var placemark = new DPPlacemarkClass(position, properties, options);

        lib.placemarks.push(placemark);

        return placemark;
    };

    /**
     * Set placemark on the map.
     *
     * @param placemark Placemark object
     */
    lib.setPlacemark = function(placemark){

        lib.map.dpExtended.setPlacemark(placemark);
    };

    /**
     * Remove all markers from map.
     */
    lib.clearMap = function(){

        lib.map.geoObjects.removeAll();
        lib.placemarks.length = 0;
    };

    /**
     * Initialize map interface
     * and create instance of map, search controls etc.
     */
    lib.init = function(){

        _setMapSize();

        lib.map = new DPMapClass(node, mapConfig);
        lib.searchControls = lib.map.dpExtended.getSearchControls();

        _removeLoader();
    };

    // initialize map interface
    lib.init();

    return lib;
};
