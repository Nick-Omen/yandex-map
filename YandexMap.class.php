<?php

/**
 * Yandex map class.
 *
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */
class YandexMap
{

    public  function  __construct()
    {
        $this->init();
    }

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
        wp_register_script('yandex-map',
            "https://api-maps.yandex.ru/2.1/?lang=" . get_locale() . "&onload=fireYandexMapLoaded", false, null, true);
        wp_register_script('yandex-map-js', YMAP_PLUGIN_URL . '_inc/yandex-map.js',
            array('jquery', 'yandex-map', 'yandex-map-class'), null,
            true);
    }


    /**
     * Plugin activation
     */
    public static function yamap_activation()
    {
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();
        $map_table = $wpdb->prefix . YMAP_TABLE_PREFIX . "maps";
        $marker_table = $wpdb->prefix . YMAP_TABLE_PREFIX . "markers";

        $sql = "CREATE TABLE $map_table (
          ID mediumint(9) NOT NULL AUTO_INCREMENT,
          Zoom tinyint(1) NOT NULL,
          Title varchar(255)NOT NULL,
          Marker_IDs mediumint(9) NOT NULL,
          Json mediumtext NOT NULL,
          PRIMARY KEY (`ID`)
        ) $charset_collate;";
        dbDelta($sql);

        $sql = "CREATE TABLE $marker_table (
          ID mediumint(9) NOT NULL AUTO_INCREMENT,
          Icon varchar(100) NOT NULL,
          Title varchar(255)NOT NULL,
          Content text NOT NULL,
          Type varchar(100) NOT NULL,
          Preset varchar(100) NOT NULL,
          PRIMARY KEY (`ID`)
        ) $charset_collate;";
        dbDelta($sql);
    }

    /**
     * Plugin deactivation
     */
    public static function yamap_deactivate()
    {
        global $wpdb;
        $map_table = $wpdb->prefix . YMAP_TABLE_PREFIX . "maps";
        $marker_table = $wpdb->prefix . YMAP_TABLE_PREFIX . "markers";

        $sql = "DROP TABLE IF EXISTS $map_table";
        $wpdb->query($sql);
        $sql = "DROP TABLE IF EXISTS $marker_table";
        $wpdb->query($sql);
    }

    /**
     *  Add map
     */
    public static function add_map(array $array)
    {
        global $wpdb;

        $map_table = $wpdb->prefix . YMAP_TABLE_PREFIX . "maps";

        $coordinates = array(
            'lat' => $array['lat'],
            'lon' => $array['lon']
        );
        $json = self::built_map_json($coordinates, 'coordinates');

        $data = array('Zoom' => $array['zoom'] ?: 13, 'Title' => $array['title'], 'Json' => $json);
        $format = array('%d','%s', '%s');

        return $wpdb->insert($map_table,$data,$format);
    }

    public static function built_map_json(array $data, $key, $map_id = false)
    {
        $out = array();
        if (!$map_id) {
            $out[$key] = $data;
        }

        $json = json_encode($out, true);

        return $json;
    }
}
