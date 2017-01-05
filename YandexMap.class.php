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
     * Register scripts on the frontend pages when map is used.
     */
    public static function register_scripts()
    {
        wp_enqueue_script('yandex-map-api', "https://api-maps.yandex.ru/2.1/?lang=" . get_locale() . "&onload=fireYandexMapLoaded",
            false, null, true);

        wp_enqueue_script('yandex-base-class', YMAP_PLUGIN_URL . '_inc/classes/base.js', false, null, true);
        wp_enqueue_script('yandex-map-class', YMAP_PLUGIN_URL . '_inc/classes/map.js', array('yandex-base-class'), null, true);
        wp_enqueue_script('yandex-placemark-class', YMAP_PLUGIN_URL . '_inc/classes/placemark.js', array('yandex-base-class'), null, true);

        wp_enqueue_script('yandex-map-interface', YMAP_PLUGIN_URL . '_inc/map.interface.js', array(
            'jquery',
            'yandex-map-api',
            'yandex-map-class',
            'yandex-placemark-class'
        ), null, true);

        wp_enqueue_script('yandex-map-js', YMAP_PLUGIN_URL . '_inc/yandex-map.js', array('yandex-map-interface'), null,
            true);
    }

    /**
     * Plugin activation
     */
    public function yamap_activation()
    {
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();
        $map_table = $wpdb->prefix . YMAP_TABLE_PREFIX . "maps";
        $marker_table = $wpdb->prefix . YMAP_TABLE_PREFIX . "markers";

        $sql = "CREATE TABLE $map_table (
          `ID` mediumint(9) NOT NULL AUTO_INCREMENT,
          `Zoom` tinyint(1) NOT NULL,
          `Title` VARCHAR (255)NOT NULL,
          `Marker_IDs` mediumint(9) NOT NULL DEFAULT 0,
          `Shordcode` VARCHAR(100) NOT NULL,
          `Shordcode_use` ENUM('0','1') NOT NULL DEFAULT '0',
          `Json` mediumtext NOT NULL,
          `Date` datetime  DEFAULT CURRENT_TIMESTAMP,
          PRIMARY KEY (`ID`)
        ) $charset_collate;";
        dbDelta($sql);

        $sql = "CREATE TABLE $marker_table (
          `ID` mediumint(9) NOT NULL AUTO_INCREMENT,
          `Icon` varchar(100) NOT NULL,
          `Title` varchar(255)NOT NULL,
          `Content` text NOT NULL,
          `Type` varchar(100) NOT NULL,
          `Preset` varchar(100) NOT NULL,
          PRIMARY KEY (`ID`)
        ) $charset_collate;";
        dbDelta($sql);
    }

    /**
     * Plugin deactivation
     */
    public function yamap_deactivate()
    {
        global $wpdb;
        $map_table = $wpdb->prefix . YMAP_TABLE_PREFIX . "maps";
        $marker_table = $wpdb->prefix . YMAP_TABLE_PREFIX . "markers";

        $sql = "DROP TABLE IF EXISTS $map_table";
        $wpdb->query($sql);
        $sql = "DROP TABLE IF EXISTS $marker_table";
        $wpdb->query($sql);
    }
}
