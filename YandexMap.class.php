<?php

/**
 * Yandex map class.
 *
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */
class YandexMap
{
    /**
     * Initialize plugin functionality.
     */
    public static function init()
    {
        self::register_scripts();
    }

    /**
     * Register scripts for plugin.
     */
    public static function register_scripts()
    {
        wp_register_script('yandex-map-class', YMAP_PLUGIN_URL . '_inc/yandex-map.class.js', array('jquery'), null,
            true);
        wp_register_script('yandex-map', "https://api-maps.yandex.ru/2.1/?lang=" . get_locale() . "&onload=fireYandexMapLoaded", false, null, true);
        wp_register_script('yandex-map-js', YMAP_PLUGIN_URL . '_inc/yandex-map.js', array('jquery', 'yandex-map', 'yandex-map-class'), null,
            true);
    }
}
