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
define('YMAP_PLUGIN_URL', plugins_url() . YMAP_DS . YMAP_FOLDER_NAME . YMAP_DS);
define('YMAP_PLUGIN_VERSION', '0.1');

// Initialize a plugin
require_once(YMAP_PLUGIN_DIR . 'YandexMap.class.php');
add_action('init', array('YandexMap', 'init'));

if (is_admin()) {
    // Initialize admin size of the plugin
    require_once YMAP_PLUGIN_DIR . 'admin' . YMAP_DS . 'YandexMapAdmin.class.php';
    add_action('init', array('YandexMapAdmin', 'init'));
}
