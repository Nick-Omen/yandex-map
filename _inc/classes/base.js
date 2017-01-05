/**
 * Base class for extending API objects like map, placemark etc.
 *
 * @param refObject object Inherited object.
 *
 * @returns {DPBaseClass}
 * @constructor
 */
var DPBaseClass = function(refObject){
    "use strict";

    /**
     * Add event to a referenced object.
     *
     * @param event array|string Event or list events to listen.
     * @param callback function Function to trigger once event is called.
     */
    this.addEvent = function(event, callback){

        refObject.events.add(event, callback);
    };

    return this;
};
