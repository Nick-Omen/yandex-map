<?php

/**
 * Yandex map class.
 *
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */
class YandexMapAdmin
{
    public $version;

    function __construct()
    {
        $this->version = YMAP_PLUGIN_VERSION;
    }

    /**
     * Initialize plugin functionality on the admin side.
     */
    public static function init()
    {
        add_action('admin_menu', array('YandexMapAdmin', 'add_menu_instance'));
        add_action('admin_init', array('YandexMapAdmin', 'register_settings'));
        add_action('admin_init', array('YandexMapAdmin', 'register_scripts'));
    }

    /**
     * Add configuration page link in menu.
     */
    public static function add_menu_instance()
    {
        add_options_page(__('Яндекс карты', 'yandex-map'), __('Яндекс карты', 'yandex-map'), 'manage_options', 'yandex-map-config',
            array('YandexMapAdmin', 'display_page_configs'));
    }

    /**
     * Register settings for a plugin.
     */
    public static function register_settings()
    {
        register_setting('yandex-map-settings', 'yandex_map_default_width');
        register_setting('yandex-map-settings', 'yandex_map_default_height');
        register_setting('yandex-map-settings', 'yandex_map_default_lat');
        register_setting('yandex-map-settings', 'yandex_map_default_lng');
        register_setting('yandex-map-settings', 'yandex_map_default_zoom');
    }

    /**
     * Register scripts for admin side.
     */
    public static function register_scripts()
    {
        wp_register_script('yandex-map-admin', YMAP_PLUGIN_URL . 'admin' . YMAP_DS . '_inc' . YMAP_DS . 'yandex-map.admin.js',
            array('jquery', 'yandex-map-class'), null, true);
    }

    /**
     * Render configuration page.
     */
    public static function display_page_configs()
    {
        wp_enqueue_script('yandex-map-admin');
        require_once(YMAP_PLUGIN_DIR . 'admin' . YMAP_DS . 'views' . YMAP_DS . 'config.php');
    }
}
