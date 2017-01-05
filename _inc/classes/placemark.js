/**
 * Extend Yandex placemark functionality
 * and create isolated instance of placemark.
 *
 * @param position array|string Latitude and longitude or valid address string.
 * @param properties object Properties for the placemark.
 * @param options object Options for the placemark.
 *
 * @return object Extended placemark
 * @constructor
 */
var DPPlacemarkClass = function(position, properties, options){
    "use strict";

    // Check for position
    if(!position || (typeof(position) !== 'string' && typeof(position) !== 'object')) {
        console.warn("No position for marker specified or format is invalid.");
        return;
    }

    // Create isolated placemark.
    var placemark = new ymaps.Placemark(position, properties, options);

    // Extend placemark functionality from base class.
    placemark.dpExtended = new DPBaseClass(placemark);

    return placemark;
};
