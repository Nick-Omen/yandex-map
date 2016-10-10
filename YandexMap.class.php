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
        wp_register_script('yandex-map-class', YMAP_PLUGIN_URL . '_inc' . YMAP_DS . 'yandex-map.class.js', array('jquery'), null,
            true);
        wp_register_script('yandex-map', YMAP_PLUGIN_URL . '_inc' . YMAP_DS . 'yandex-map.js', array('jquery', 'yandex-map-class'), null,
            true);
    }
}
