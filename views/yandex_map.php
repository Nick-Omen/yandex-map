<?php

/**
 * Yandex Map.
 * Author: Nikita Nikitin <nikita.omen666@gmail.com>
 * Author URI: http://www.nick-omen.com/
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

$yandexMapWidth = isset($atts['width']) ? $atts['width'] == 0 ? "100%" : "{$atts['width']}px" : "100%";
$yandexMapHeight = isset($atts['height']) ? $atts['height'] == 0 ? "100%" : "{$atts['height']}px" : "100%";
$yandexMapLat = isset($atts['lat']) ? $atts['lat'] : esc_attr(get_option('yandex_map_default_lat', 42.8768536));
$yandexMapLng = isset($atts['lng']) ? $atts['lng'] : esc_attr(get_option('yandex_map_default_lng', 74.5218208));
$yandexMapZoom = isset($atts['zoom']) ? $atts['zoom'] : esc_attr(get_option('yandex_map_default_zoom', 13));
?>
<div id="omen-maps-yandex"
     style="width: <?php echo $yandexMapWidth; ?>; height: <?php echo $yandexMapHeight; ?>;"></div>
<script>
    var omenMapsYandex;

    var omenMapsInitYandex = function(){
        var initialPosition = [
            <?php echo $yandexMapLat; ?>,
            <?php echo $yandexMapLng; ?>
        ];
        omenMapsYandex = new ymaps.Map("omen-maps-yandex", {
            center: initialPosition,
            zoom: <?php echo $yandexMapZoom; ?>
        });
        var placemark = new ymaps.GeoObject({
            geometry: {
                type: "Point",
                coordinates: initialPosition
            },
            properties: {
                iconContent: "<?php echo $yandexMapTitle; ?>"
            }
        }, {
            preset: 'islands#blackStretchyIcon'
        });
        omenMapsYandex.geoObjects.add(placemark)
    }
</script>
