<?php

/**
 * Yandex Map.
 * Author: Nikita Nikitin <nikita.omen666@gmail.com>
 * Author URI: http://www.nick-omen.com/
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */
$ymapWidthTmp = isset($atts['width']) ? $atts['width'] : esc_attr(get_option('yandex_map_default_width'));
$ymapHeightTmp = isset($atts['height']) ? $atts['height'] : esc_attr(get_option('yandex_map_default_height'));
?>
<script>
    var yandexMapConfig_<?php echo $tag; ?> = {
        width: "<?php echo $ymapWidthTmp == 0 ? "100%" : $ymapWidthTmp . 'px' ?>",
        height: "<?php echo $ymapHeightTmp == 0 ? "100%" : $ymapHeightTmp . 'px' ?>",
        lat: <?php echo isset($atts['lat']) ? $atts['lat'] : esc_attr(get_option('yandex_map_default_lat', 0)) ?>,
        lng: <?php echo isset($atts['lng']) ? $atts['lng'] : esc_attr(get_option('yandex_map_default_lng', 0)) ?>,
        zoom: <?php echo isset($atts['zoom']) ? $atts['zoom'] : esc_attr(get_option('yandex_map_default_zoom', 13)) ?>
    };

    var initYandexMap_<?php echo $tag; ?> = function(){
        var map = new YandexMapClass(document.getElementById("<?php echo $tag; ?>"), yandexMapConfig_<?php echo $tag; ?>);
    };
    jQuery(document).on('yandexMapLoaded', function(){
        initYandexMap_<?php echo $tag; ?>();
    });
</script>
<div id="<?php echo $tag; ?>"><span class="text-loading">Загрузка карты...</span></div>
