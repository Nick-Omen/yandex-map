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
     * @var  string plugin version
     */
    public $version;

    /**
     * @var obj of the YandexMapTable
     */
    public $table_obj;

    /**
     * YandexMapAdmin constructor.
     */
    function __construct()
    {
        $this->version = YMAP_PLUGIN_VERSION;

        $table_class = YMAP_PLUGIN_DIR . 'admin' . YMAP_DS . 'YandexPageTable.class.php';
        if (file_exists($table_class)) {
            require $table_class;

//            $option = 'per_page';
//            $args   = [
//                'label'   => 'Customers',
//                'default' => 5,
//                'option'  => 'customers_per_page'
//            ];
//
//            add_screen_option( $option, $args );
            $this->table_obj = new YandexMapTable();
        }

        $this->init();
    }

    /**
     * Initialize plugin functionality on the admin side.
     */
    public function init()
    {
        add_action('admin_menu', array($this, 'add_menu_instance'));
        add_action('admin_init', array($this, 'register_settings'));
        add_action('admin_init', array($this, 'register_scripts'));
        add_shortcode('admin_yandex_map', array($this, 'insert_yandex_map'));
        add_action('add_meta_boxes', array($this, 'yandex_custom_box'));

        add_action('admin_menu', function () {
            add_menu_page('Карты', 'Мои Карты', 'manage_options', 'yandex-maps',
                array($this, 'render_maps_page'), '', 4);
        });
    }

    /**
     *
     */
    public function render_maps_page()
    {?>
        <div class="wrap">
            <h2>WP_List_Table Class Example</h2>

            <div id="poststuff">
                <div id="post-body" class="metabox-holder columns-2">
                    <div id="post-body-content">
                        <div class="meta-box-sortables ui-sortable">
                            <form method="post">
                                <?php
                                $this->table_obj->prepare_items();
                                $this->table_obj->display();
                                ?>
                            </form>
                        </div>
                    </div>
                </div>
                <br class="clear">
            </div>
        </div>
        <?php }

    /**
     * Add configuration page link in menu.
     */
    public function add_menu_instance()
    {
        add_options_page(__('Яндекс карты', 'yandex-map'), __('Яндекс карты', 'yandex-map'), 'manage_options',
            'yandex-map-config',
            array($this, 'display_page_configs'));
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
    public static function insert_yandex_map($atts, $content, $tag)
    {
        wp_register_script('yandex-api-admin', YMAP_PLUGIN_URL . 'admin/_inc/yandex-map.admin.js',
            array('jquery', 'yandex-map-class'), null, true);

        require_once YMAP_PLUGIN_DIR . 'views' . YMAP_DS . 'yandex_map.php';

    }

    /**
     * Render configuration page.
     */
    public static function display_page_configs()
    {
        wp_enqueue_script('yandex-map-admin');
        require_once(YMAP_PLUGIN_DIR . 'admin' . YMAP_DS . 'views' . YMAP_DS . 'config.php');
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
            'register_meta_box_cb' => array('YandexMapAdmin', 'yandex_custom_box')
        ));

    }

    /**
     * Add the Events Meta Boxes
     */
    public function yandex_custom_box()
    {
        add_meta_box('yandex-box', 'Карта', array($this, 'renderYandexBox'), 'coordinates', 'advanced',
            'high');
    }

    /**
     *
     */
    public static function renderYandexBox()
    {
        require_once(YMAP_PLUGIN_DIR . 'admin' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'yandex-map-box.php');
    }
}
