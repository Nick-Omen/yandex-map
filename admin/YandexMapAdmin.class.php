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
        add_action('admin_menu', array('YandexMapAdmin', 'add_menu_instances'));
        add_action('admin_init', array('YandexMapAdmin', 'register_settings'));
        add_action('admin_init', array('YandexMapAdmin', 'register_scripts'));

        add_action('add_meta_boxes', array('YandexMapAdmin', 'yandex_custom_box' ));

        add_action('insert_yandex_map', array('YandexMapAdmin', 'insert_yandex_map' ));

        self::add_yandex_post_type();
    }

    /**
     * Add configuration page link in menu.
     */
    public static function add_menu_instances()
    {
        add_menu_page(__('Яндекс карты', 'yandex-map'), __('Яндекс карты', 'yandex-map'), 'manage_options', 'yandex-map',
            array('YandexMapAdmin', 'display_page_configs'));

        add_submenu_page('yandex-map', __('Мои карты', 'yandex-map'), __('Мои карты', 'yandex-map'), 'manage_options', 'my-yandex-maps',
            array('YandexMapAdmin', 'display_my_maps'));
        add_submenu_page('yandex-map', __('Добавить карту', 'yandex-map'), __('Добавить карту', 'yandex-map'), 'manage_options', 'add-yandex-map',
            array('YandexMapAdmin', 'display_add_map'));

        add_submenu_page('yandex-map', __('Мои маркеры', 'yandex-map'), __('Мои маркеры', 'yandex-map'), 'manage_options', 'my-yandex-markers',
            array('YandexMapAdmin', 'display_my_markers'));
        add_submenu_page('yandex-map', __('Добавить маркер', 'yandex-map'), __('Добавить маркер', 'yandex-map'), 'manage_options', 'add-yandex-marker',
            array('YandexMapAdmin', 'display_add_marker'));
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
        wp_register_script('yandex-map-admin', YMAP_PLUGIN_URL . 'admin/_inc/yandex-map.admin.js',
            array('jquery', 'yandex-map', 'yandex-map-class'), null, true);
    }

    /**
     * Insert yandex map as a shortcode.
     * @param $atts - array
     */
    public static function insert_yandex_map($args)
    {
        wp_enqueue_script('yandex-map-admin');
        ?>
        <script>
            var yandexMapConfig_admin_page = {
                width: "100%",
                height: "300px",
                lat: <?=$args['lat'] ?>,
                lng: <?=$args['lng'] ?>,
                zoom: <?=$args['zoom'] ?>
            };

            var initYandexMap_admin_page = function(){
                new AdminYandexMapClass(yandexMapConfig_admin_page);
            };
            jQuery(document).on('yandexMapLoaded', function(){
                initYandexMap_admin_page();
            });
        </script>
        <div id="admin_page_map"><span class="text-loading">Загрузка карты...</span></div>
        <?php
    }

    /**
     * Render configuration page.
     */
    public static function display_page_configs()
    {
        wp_enqueue_script('yandex-map-admin');
        require_once(YMAP_PLUGIN_DIR . 'admin' . YMAP_DS . 'views' . YMAP_DS . 'config.php');
    }

    /**
     * Render my maps page.
     */
    public static function display_my_maps()
    {
        require_once(YMAP_PLUGIN_DIR . 'admin' . YMAP_DS . 'views' . YMAP_DS . 'my-maps.php');
    }

    /**
     * Render add map page.
     */
    public static function display_add_map()
    {
        require_once(YMAP_PLUGIN_DIR . 'admin' . YMAP_DS . 'views' . YMAP_DS . 'add-map.php');
    }

    /**
     * Render my maps page.
     */
    public static function display_my_markers()
    {
        require_once(YMAP_PLUGIN_DIR . 'admin' . YMAP_DS . 'views' . YMAP_DS . 'my-markers.php');
    }

    /**
     * Render add map page.
     */
    public static function display_add_marker()
    {
        require_once(YMAP_PLUGIN_DIR . 'admin' . YMAP_DS . 'views' . YMAP_DS . 'add-marker.php');
    }

    public static function add_yandex_post_type()
    {
        // Set UI labels for Custom Post Typ
         register_post_type('coordinates', array(
            'labels' => array(
                'name'            => __( 'Карты','yandex-map' ),
                'singular_name'   => __( 'Карты', 'yandex-map' ),
                'add_new'         => __( 'Добавить','yandex-map'),
                'add_new_item'    => __( 'Добавить координаты','yandex-map' ),
                'edit'            => __( 'Edit news','yandex-map' ),
                'edit_item'       => __( 'Edit news item','yandex-map' ),
                'new_item'        => __( 'Single news','yandex-map'),
                'all_items'       => __( 'Все карты','yandex-map' ),
                'view'            => __( 'Посмотреть все карты', 'yandex-map' ),
                'view_item'       => __( 'Просмотр карты', 'yandex-map' ),
                'search_items'    => __( 'Поиск карт', 'yandex-map' ),
                'not_found'       => __( 'Ничего не найдено', 'yandex-map' ),
            ),
            'public' => true,
            'menu_position' => 2,
            'supports' => array( 'title', 'ya-map'),
            'taxonomies' => array( '' ),
            'has_archive' => true,
            'capability_type' => 'post',
            'menu_icon'   => 'dashicons-admin-site',
            'rewrite' => array('slug' => 'coordinates'),
            'register_meta_box_cb' => array('YandexMapAdmin','yandex_custom_box')
         ));
      
    }

    /**
     * Add the Events Meta Boxes
     */
    public  static  function yandex_custom_box()
    {
        add_meta_box('yandex-box', 'Карта', array('YandexMapAdmin','renderYandexBox'), 'coordinates', 'advanced', 'high');
    }

    /**
     *
     */
    public static function renderYandexBox()
    {
        require_once(YMAP_PLUGIN_DIR  . 'admin' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'yandex-map-box.php');
    }
}
