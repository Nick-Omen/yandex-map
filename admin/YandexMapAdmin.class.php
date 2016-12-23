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
     * @var
     */
    public  $markers;


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

        //include Markers
        require_once(YMAP_PLUGIN_DIR . 'admin' . YMAP_DS . 'AdminMarkers.class.php');
        $this->markers = new AdminMarkers();

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
        add_action('admin_notices', Array($this, 'show_notice'));

        add_action('admin_post_add_map', Array($this, 'map_editor_listener'));
        add_action('admin_post_edit_map', Array($this, 'map_editor_listener'));

        add_action('admin_post_add_marker', Array($this, 'marker_editor_listener'));
    }

    /**
     * Showing notice after some POST actions
     */
    function show_notice()
    {
        if (isset($_GET['success'])) {
            echo '<div class="updated"><p>Карта добавлена</p></div>';
        }

        if (isset($_GET['error'])) {
            echo '<div class="error"><p>Ошибка при сохранении или валидации</p></div>';
        }
    }

    /**
     * Post handler of the Add map form
     */
    function map_editor_listener()
    {
        $action = sanitize_text_field($_POST['action']);
        $args['lat'] = sanitize_text_field($_POST['lat']);
        $args['lon'] = sanitize_text_field($_POST['lon']);
        $args['zoom'] = sanitize_text_field($_POST['zoom']);
        $args['title'] = sanitize_text_field($_POST['post_title']);
        $errors = $this->validate_data($args);
        if (!$errors) {
            if ($action === 'add_map') {
                $this->add_map($args);
            } else {
                $map_id = sanitize_text_field($_POST['map']);
                $this->edit_map($args, $map_id);
            }
            wp_redirect(admin_url('admin.php?page=add-yandex-map&success'));
        } else {
            //todo show errors
            wp_redirect(admin_url('admin.php?page=add-yandex-map&error'));
        }
        die();
    }

    public function marker_editor_listener()
    {
        $action = sanitize_text_field($_POST['action']);
        $args['title'] = sanitize_text_field($_POST['lat']);
        $args['lat'] = sanitize_text_field($_POST['lat']);
        $args['lon'] = sanitize_text_field($_POST['lon']);
        $args['map_id'] = sanitize_text_field($_POST['map_id']);
        $args['description'] = sanitize_text_field($_POST['description']);

        $errors = $this->validate_data($args);
        
        if(!$errors) {
            if($action === 'add_marker') {
                $this->markers->add_marker($args);
            } else {
                $marker_id = sanitize_text_field($_POST['marker']);
                $this->markers->edit_marker($marker_id, $args);
            }
        } else {

        }
    }

    /**
     * Map data validation method.
     *
     * @param array $args - data of the map.
     */
    public function validate_data($args)
    {
        $tmpErrors = array();
        foreach ($args as $key => $value) {
            if (!$value) {
                $tmpErrors[] = "{$key} doesn't filled";
            }
        }

        return $tmpErrors;
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
     *
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
        if ($_GET['action'] == 'edit' && $_GET['map']) {
            $map_id = sanitize_text_field($_GET['map']);
            $map_data = $this->get_map($map_id);
            $lat = $map_data['coordinates']['lat'];
            $lon = $map_data['coordinates']['lon'];
            $zoom = $map_data['Zoom'];
            $title = $map_data['Title'];
            $action = 'edit';
        } else {
            $lat = esc_attr(get_option('yandex_map_default_lat', 0));
            $lon = esc_attr(get_option('yandex_map_default_lng', 0));
            $zoom = esc_attr(get_option('yandex_map_default_zoom', 7));
            $action = 'add';
        }

        require_once(YMAP_PLUGIN_DIR . 'admin' . YMAP_DS . 'views' . YMAP_DS . 'add-map.php');
        unset($lat,$lon,$zoom,$action, $title);
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
        if($_GET['action'] == 'edit') {

        } else {
            $lat = esc_attr(get_option('yandex_map_default_lat', 0));
            $lon = esc_attr(get_option('yandex_map_default_lng', 0));
            $zoom = esc_attr(get_option('yandex_map_default_zoom', 7));
            $action = 'add';
            $maps = $this->table_obj->get_maps();
        }

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
     * Prepare Map data to Insert/Edit into database
     *
     * @param array $data - map array
     * @return array $map - prepared map data
     */
    public function prepare_map_data($data)
    {
        $coordinates = array(
            'lat' => $data['lat'],
            'lon' => $data['lon']
        );
        $json = self::built_map_json($coordinates, 'coordinates');

        $map = array(
            'Zoom' => $data['zoom'] ?: 13,
            'Title' => $data['title'],
            'Json' => $json,
            'Shordcode' => $this->generate_shordcode($data),
        );

        return $map;
    }

    /**
     * Edit map.
     *
     * @param array $data   - new map data
     * @param  int  $map_id - ID of the map which you want to edit
     */
    public function edit_map(array $data, $map_id)
    {
        global $wpdb;

        $map_table = $wpdb->prefix . YMAP_TABLE_PREFIX . "maps";
        $data = $this->prepare_map_data($data);
        $format = array('%d', '%s', '%s', '%s');
        return $wpdb->update($map_table, $data, array('ID' => $map_id), $format, array('%d'));
    }

    /**
     * @param array $array - array of the map data
     * @return false|int
     */
    public function add_map(array $array)
    {
        global $wpdb;

        $map_table = $wpdb->prefix . YMAP_TABLE_PREFIX . "maps";
        $data = $this->prepare_map_data($array);
        $format = array('%d', '%s', '%s', '%s');
        return $wpdb->insert($map_table, $data, $format);
    }

    /**
     * Generate shortcode for displaying in the front-end.
     *
     * @param  array $data - Data of the map
     * @return string $shordcode - Generated shordcode
     */
    public function generate_shordcode($data)
    {
        $args = '';
        foreach ($data as $key => $value) {
            $args .= "{$key}='{$value}' ";
        }
        $args = rtrim($args);
        $shordcode = "[dp_yandex {$args}]";

        return $shordcode;
    }

    /**
     * Build json to insert/update map table.
     *
     * @param  array  $data   - data to build
     * @param  string $key    - key of the map (on edit action)
     * @param  bool   $map_id - only for update function
     * @return $json
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

    /**
     * Return map data.
     *
     * @param $map_id - ID of the needed map
     * @return  array $map  - Map data
     */
    public function get_map($map_id)
    {
        global $wpdb;

        $table = $wpdb->prefix . YMAP_TABLE_PREFIX . "maps";
        $sql = "SELECT * FROM  {$table} WHERE `ID` = {$map_id}";
        $map = $wpdb->get_row($sql, ARRAY_A);
        if ($map['Json']) {
            $map['coordinates'] = $this->parse_json($map['Json'], 'coordinates');
        }

        return $map;
    }

    /**
     * Get value from Map json data.
     *
     * @param json $map_json - Json string of the map
     * @param bool $key      - Key in the map json
     * @return array $result - parsed array
     */
    public function parse_json($map_json, $key = false)
    {
        $decoded = json_decode($map_json, true);
        $result = $key ? $decoded[$key] : $decoded;

        return $result;
    }
}
