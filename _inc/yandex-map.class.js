var fireYandexMapLoaded = function(){
    jQuery(document).trigger('yandexMapLoaded');
};

var YandexMapClass = function(node, mapConfig){
    var lib = this, map = map || {};

    this.changeMarkerPosition = function(){};

    if(typeof(ymaps) == 'undefined') {
        console.warn('\'ymaps\' class wasn\'t loaded but class was called');
        return;
    }

    var removeLoader = function(){
        node.querySelector('.text-loading').remove();
    };

    var setMapSize = function(){
        node.style.width = mapConfig.width;
        node.style.height = mapConfig.height;
    };

    this.setMarker = function(data){
        var placemark = new ymaps.Placemark([data.lat, data.lng], data.properties || {}, data.options || {});
        
        placemark.events.add('dragend', this.changeMarkerPosition);

        map.geoObjects.add(placemark);
    };

    lib.init = function(){
        setMapSize();
        map = new ymaps.Map(node, {
            center: [
                mapConfig.lat, mapConfig.lng
            ],
            zoom: mapConfig.zoom
        });
        removeLoader();
    };

    lib.init();
};
