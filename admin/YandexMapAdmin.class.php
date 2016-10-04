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

    public static function init()
    {
        add_action('admin_menu', array('YandexMapAdmin', 'add_menu_instance'));
    }

    public static function add_menu_instance()
    {
        add_options_page(__('Яндекс карты', 'yandex-map'), __('Яндекс карты', 'yandex-map'), 'manage_options', 'yandex-map-config',
            array('YandexMapAdmin', 'display_page_configs'));
    }

    public static function display_page_configs()
    {
        require_once YMAP_PLUGIN_DIR . 'admin' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'config.php';
    }
}
