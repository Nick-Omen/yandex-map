<?php
/**
 * Plugin Name: Yandex Map
 * Description: Добавляйте карты Яндекс с геотегами у себя на сайте.
 * Version: 0.1
 * Text Domain: yandex-map
 *
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

if (!function_exists('add_action')) {
    echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
    exit;
}

define('YMAP_DS', DIRECTORY_SEPARATOR);
define('YMAP_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('YMAP_FOLDER_NAME', dirname(plugin_basename(__FILE__)));
define('YMAP_PLUGIN_URL', plugins_url() . '/' . YMAP_FOLDER_NAME . '/');
define('YMAP_PLUGIN_VERSION', '0.1');
define('YMAP_TABLE_PREFIX', 'dp_');

// Initialize a plugin
require_once(YMAP_PLUGIN_DIR . 'YandexMap.class.php');
add_action('init', array('YandexMap', 'init'));
register_activation_hook(__FILE__, array('YandexMap', 'yamap_activation'));
register_deactivation_hook(__FILE__, array('YandexMap', 'yamap_deactivate'));

if (is_admin()) {
    // Initialize admin size of the plugin
    if (!class_exists('WP_List_Table')) {
        require_once( ABSPATH . 'wp-admin/includes/class-wp-screen.php' );
        require_once( ABSPATH . 'wp-admin/includes/screen.php' );
        require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
        require_once( ABSPATH . 'wp-admin/includes/template.php' );
        require_once YMAP_PLUGIN_DIR . 'admin' . YMAP_DS . 'YandexMapAdmin.class.php';
        new YandexMapAdmin();
    }
}

