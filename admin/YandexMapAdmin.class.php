<?php

/**
 * Yandex map class.
 *
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */
class YandexMapAdmin
{
    /**
     * @var string - plugin version
     */
    public $version;

    /**
     * @var obj - My Maps table object
     */
    public $map_table;

    /**
     * var obj = My Coordinates table object
     */
    public $coordinates_object;

    /**
     * YandexMapAdmin constructor.
     */
    function __construct()
    {
        $this->version = YMAP_PLUGIN_VERSION;
        $this->init();
        //include tables
        require_once(YMAP_PLUGIN_DIR . 'admin' . YMAP_DS . 'MyMapsTable.class.php');
        $this->table_obj = new MyMapsTable();

    }

    /**
     * Initialize plugin functionality on the admin side.
     */
    public function init()
    {
        add_action('admin_menu', array($this, 'add_menu_instances'));
        add_action('admin_init', array($this, 'register_settings'));
        add_action('admin_init', array($this, 'register_scripts'));
        add_action('add_meta_boxes', array($this, 'yandex_custom_box'));
        add_action('insert_yandex_map', array($this, 'insert_yandex_map'));
        add_action( 'admin_notices', Array($this, 'show_notice'));

        add_action('admin_post_map_handler', Array($this,'map_editor_listener'));
    }

    /**
     * Showing notice after some POST actions
     */
    function show_notice()
    {
        if (isset($_GET['success']) ) {
            echo '<div class="updated"><p>Карта добавлена</p></div>';
        }

        if(isset($_GET['error'])) {
            echo '<div class="error"><p>Ошибка при сохранении или валидации</p></div>';
        }
    }

    /**
     * Post handler of the Add map form
     */
    function map_editor_listener()
    {
        $arg['lat']   = sanitize_text_field($_POST['lat']);
        $arg['lon']   = sanitize_text_field($_POST['lon']);
        $arg['zoom']  = sanitize_text_field($_POST['zoom']);
        $arg['title'] = sanitize_text_field($_POST['post_title']);

        $error = true;
        if(!$error) {

        } else {
            //todo show errors
            wp_redirect(admin_url('admin.php?page=add-yandex-map&error'));
        }
        die();
    }

    /**
     * Validate post data
     * @param $args
     */
    public function validate_map($args)
    {

    }
    /**
     * Add configuration page link in menu.
     */
    public function add_menu_instances()
    {
        add_menu_page(__('Яндекс карты', 'yandex-map'), __('Яндекс карты', 'yandex-map'), 'manage_options',
            'yandex-map',
            array($this, 'display_page_configs'));

        add_submenu_page('yandex-map', __('Мои карты', 'yandex-map'), __('Мои карты', 'yandex-map'), 'manage_options',
            'my-yandex-maps',
            array($this, 'display_my_maps'));
        add_submenu_page('yandex-map', __('Добавить карту', 'yandex-map'), __('Добавить карту', 'yandex-map'),
            'manage_options', 'add-yandex-map',
            array($this, 'display_add_map'));

        add_submenu_page('yandex-map', __('Мои маркеры', 'yandex-map'), __('Мои маркеры', 'yandex-map'),
            'manage_options', 'my-yandex-markers',
            array($this, 'display_my_markers'));
        add_submenu_page('yandex-map', __('Добавить маркер', 'yandex-map'), __('Добавить маркер', 'yandex-map'),
            'manage_options', 'add-yandex-marker',
            array($this, 'display_add_marker'));
    }

    /**
     * Register settings for a plugin.
     */
    public function register_settings()
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
    public function register_scripts()
    {
        wp_register_script('yandex-map-admin', YMAP_PLUGIN_URL . 'admin/_inc/yandex-map.admin.js',
            array('jquery', 'yandex-map', 'yandex-map-class'), null, true);
    }

    /**
     * Insert yandex map as a shortcode.
     * @param $atts - array
     */
    public function insert_yandex_map($args)
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

            var initYandexMap_admin_page = function () {
                new AdminYandexMapClass(yandexMapConfig_admin_page);
            };
            jQuery(document).on('yandexMapLoaded', function () {
                initYandexMap_admin_page();
            });
        </script>
        <div id="admin_page_map"><span class="text-loading">Загрузка карты...</span></div>
        <?php
    }

    /**
     * Render configuration page.
     */
    public function display_page_configs()
    {
        wp_enqueue_script('yandex-map-admin');
        require_once(YMAP_PLUGIN_DIR . 'admin' . YMAP_DS . 'views' . YMAP_DS . 'config.php');
    }

    /**
     * Render my maps page.
     */
    public function display_my_maps()
    {
        require_once(YMAP_PLUGIN_DIR . 'admin' . YMAP_DS . 'views' . YMAP_DS . 'my-maps.php');
    }

    /**
     * Render add map page.
     */
    public function display_add_map()
    {
        require_once(YMAP_PLUGIN_DIR . 'admin' . YMAP_DS . 'views' . YMAP_DS . 'add-map.php');
    }

    /**
     * Render my maps page.
     */
    public function display_my_markers()
    {
        require_once(YMAP_PLUGIN_DIR . 'admin' . YMAP_DS . 'views' . YMAP_DS . 'my-markers.php');
    }

    /**
     * Render add map page.
     */
    public function display_add_marker()
    {
        require_once(YMAP_PLUGIN_DIR . 'admin' . YMAP_DS . 'views' . YMAP_DS . 'add-marker.php');
    }

    public function add_yandex_post_type()
    {
        // Set UI labels for Custom Post Typ
        register_post_type('coordinates', array(
            'labels' => array(
                'name' => __('Карты', 'yandex-map'),
                'singular_name' => __('Карты', 'yandex-map'),
                'add_new' => __('Добавить', 'yandex-map'),
                'add_new_item' => __('Добавить координаты', 'yandex-map'),
                'edit' => __('Edit news', 'yandex-map'),
                'edit_item' => __('Edit news item', 'yandex-map'),
                'new_item' => __('Single news', 'yandex-map'),
                'all_items' => __('Все карты', 'yandex-map'),
                'view' => __('Посмотреть все карты', 'yandex-map'),
                'view_item' => __('Просмотр карты', 'yandex-map'),
                'search_items' => __('Поиск карт', 'yandex-map'),
                'not_found' => __('Ничего не найдено', 'yandex-map'),
            ),
            'public' => true,
            'menu_position' => 2,
            'supports' => array('title', 'ya-map'),
            'taxonomies' => array(''),
            'has_archive' => true,
            'capability_type' => 'post',
            'menu_icon' => 'dashicons-admin-site',
            'rewrite' => array('slug' => 'coordinates'),
            'register_meta_box_cb' => array($this, 'yandex_custom_box')
        ));

    }

    /**
     * Add the Events Meta Boxes
     */
    public function yandex_custom_box()
    {
        add_meta_box('yandex-box', 'Карта', array($this, 'renderYandexBox'), 'coordinates', 'advanced', 'high');
    }

    /**
     *
     */
    public function renderYandexBox()
    {
        require_once(YMAP_PLUGIN_DIR . 'admin' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'yandex-map-box.php');
    }

    /**
     * @param array $array  - array of the map data
     * @return false|int
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

    /**
     * Build json to insert/update map table
     * @param array - data
     * @param $key - key
     * @param bool $map_id - only for update function
     * @return mixed|string|void
     */
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
