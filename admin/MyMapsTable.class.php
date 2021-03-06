<?php

/**
 * Class YandexMapTable
 * URL: https://www.sitepoint.com/using-wp_list_table-to-create-wordpress-admin-tables/
 */
class MyMapsTable extends WP_List_Table
{
    public function __construct()
    {
        parent::__construct([
            'singular' => __('Карта', 'yandex-map'),
            'plural' => __('Карты', 'yandex-map'),
            'ajax' => false
        ]);
    }

    /**
     * Prepare table data
     */
    public function prepare_items()
    {
        $this->_column_headers = $this->get_column_info();
        /** Process bulk action */
        $this->process_bulk_action();
        $per_page = $this->get_items_per_page('maps_per_page', 5);
        $current_page = $this->get_pagenum();
        $total_items = self::record_count();

        $this->set_pagination_args([
            'total_items' => $total_items, //WE have to calculate the total number of items
            'per_page' => $per_page //WE have to determine how many items to show on a page
        ]);
        $this->items = self::get_maps($per_page, $current_page);
    }

    /**
     * Maps counts
     */
    public function record_count()
    {

    }

    /**
     * Getting maps from database
     * @param int $per_page - maps row per page
     * @param int $page_number - page number in pagination
     */
    function get_maps($per_page = 5, $page_number = 1)
    {
        global $wpdb;

        $sql = "SELECT * FROM {$wpdb->prefix}dp_maps";

        if (!empty($_REQUEST['orderby'])) {
            $sql .= ' ORDER BY ' . esc_sql($_REQUEST['orderby']);
            $sql .= !empty($_REQUEST['order']) ? ' ' . esc_sql($_REQUEST['order']) : ' ASC';
        }

        $sql .= " LIMIT $per_page";
        $sql .= ' OFFSET ' . ($page_number - 1) * $per_page;
        $result = $wpdb->get_results($sql, 'ARRAY_A');
        return $result;
    }

    /**
     *  Associative array of columns
     * @return array of the columns
     */
    function get_columns()
    {
        $columns = [
            'cb' => '<input type="checkbox" />',
            'Title' => __('Название', 'yandex-map'),
            'Shordcode' => __('Шордкод', 'yandex-map'),
            'Shordcode_use' => __('Использование', 'yandex-map'),
            'coordinates' => __('Кординаты', 'yandex-map'),
            'Date' => __('Дата', 'yandex-map')
        ];

        return $columns;
    }

    /**
     * If maps does not found
     */
    public function no_items()
    {
        _e('Нет добавленных карт', 'yandex-map');
    }

    /**
     * Processing a bulk action
     */
    public function process_bulk_action()
    {
        //Detect when a bulk action is being triggered...
        if ('delete' === $this->current_action()) {
            $this->delete_map(absint($_GET['map']));
        }
        // If the delete bulk action is triggered
        if ((isset($_POST['action']) && $_POST['action'] == 'bulk-delete')
            || (isset($_POST['action2']) && $_POST['action2'] == 'bulk-delete')
        ) {
            $delete_ids = esc_sql($_POST['bulk-delete']);
            // loop over the array of record IDs and delete them
            foreach ($delete_ids as $id) {
                $this->delete_map($id);
            }
        }
    }

    /**
     * @param $id - id of the row in the grid
     *
     */
    public function delete_map($id)
    {
        global $wpdb;

        $wpdb->delete(
            "{$wpdb->prefix}dp_maps",
            ['ID' => $id],
            ['%d']
        );
    }

    /**
     * Method for name column
     *
     * @param array $item an array of DB data
     *
     * @return string
     */
    function column_Title($item)
    {
        $title = '<strong>' . $item['Title'] . '</strong>';

        $actions = [
            'delete' => sprintf('<a href="?page=%s&action=%s&map=%s">%s</a>',
                esc_attr($_REQUEST['page']), 'delete', absint($item['ID']), __('Удалить', 'yandex-map')),
            'edit' => sprintf('<a href="?page=%s&action=%s&map=%s">%s</a>', 'add-yandex-map', 'edit', $item['ID'],
                __('Редактировать', 'yandex-map')),
        ];
        return $title . $this->row_actions($actions);
    }

    /**
     * @param object $item
     * @param string $column_name
     * @return mixed
     */
    public function column_default($item, $column_name)
    {
        switch ($column_name) {
            case 'Title':
            case 'Shordcode':
            case 'coordinates':
            case 'Date':
            case 'city':
                return $item[$column_name];
            case 'Shordcode_use':
                $name = $item[$column_name] ? __('Да', 'yandex-map') : __('Нет', 'yandex-map');
                return $name;
                break;
            default:
                return print_r($item, true); //Show the whole array for troubleshooting purposes
        }
    }

    /**
     * Render the bulk edit checkbox
     *
     * @param array $item
     *
     * @return string
     */
    function column_cb($item)
    {
        return sprintf(
            '<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['ID']
        );
    }

    /**
     * Returns an associative array containing the bulk action
     *
     * @return array
     */
    public function get_bulk_actions()
    {
        $actions = [
            'bulk-delete' => 'Delete'
        ];

        return $actions;
    }
}