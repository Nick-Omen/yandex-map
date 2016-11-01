<?php

/**
 * Class YandexMapTable
 * URL: https://www.sitepoint.com/using-wp_list_table-to-create-wordpress-admin-tables/
 */
class YandexMapTable extends WP_List_Table
{
    /**
     * YandexMapTable constructor.
     */
    public function __construct()
    {
        parent::__construct([
            'singular' => __('Карта', 'yandex-map'), //singular name of the listed records
            'plural' => __('Карты', 'yandex-map'), //plural name of the listed records
            'ajax' => false //should this table support ajax?
        ]);
    }

    /**
     * @param int $per_page - maps row per page
     * @param int $page_number - page number in pagination
     */
    function get_customers($per_page = 5, $page_number = 1)
    {
        global $wpdb;

        $sql = "SELECT * FROM {$wpdb->prefix}dp_maps";

        if ( ! empty( $_REQUEST['orderby'] ) ) {
            $sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
            $sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC';
        }

        $sql .= " LIMIT $per_page";
        $sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;
        $result = $wpdb->get_results( $sql, 'ARRAY_A' );

        return $result;
    }

    /**
     * If maps does not found
     */
    public function no_items()
    {
        _e('Нет добавленных карт', 'yandex-map');
    }

    /**
     * Map's records count
     */
    public function record_count()
    {

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
            [ 'ID' => $id ],
            [ '%d' ]
        );
    }

    /**
     * Method for name column
     *
     * @param array $item an array of DB data
     *
     * @return string
     */
    function column_name($item)
    {

        // create a nonce
        $delete_nonce = wp_create_nonce('ya_map_delete');

        $title = '<strong>' . $item['Title'] . '</strong>';

        $actions = [
            'delete' => sprintf('<a href="?page=%s&action=%s&map=%s">Удалить</a>',
                esc_attr($_REQUEST['page']), 'delete', absint($item['ID'])),
            'edit' => sprintf('<a href="?page=%s&action=%s&map=%s">Edit</a>', $_REQUEST['page'], 'edit', $item['ID']),
        ];

        return $title . $this->row_actions($actions);
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
     * @param object $item
     * @param string $column_name
     * @return mixed
     */
    public function column_default($item, $column_name)
    {
        switch ($column_name) {
            case 'address':
            case 'city':
                return $item[$column_name];
            default:
                return print_r($item, true); //Show the whole array for troubleshooting purposes
        }
    }

    /**
     *  Associative array of columns
     *
     * @return array
     */
    function get_columns()
    {
        $columns = [
            'cb' => '<input type="checkbox" />',
            'name' => __('Name', 'sp'),
            'address' => __('Shordcode', 'sp'),
            'city' => __('City', 'sp')
        ];

        return $columns;
    }

    /**
     * Columns to make sortable.
     *
     * @return array
     */
    public function get_sortable_columns()
    {
        $sortable_columns = array(
            'name' => array('name', true),
            'city' => array('city', false)
        );

        return $sortable_columns;
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

    /**
     * Handles data query and filter, sorting, and pagination.
     */
    public function prepare_items()
    {

        $this->_column_headers = $this->get_column_info();

        /** Process bulk action */
        $this->process_bulk_action();

        $per_page = $this->get_items_per_page('customers_per_page', 5);
        $current_page = $this->get_pagenum();
        $total_items = self::record_count();

        $this->set_pagination_args([
            'total_items' => $total_items, //WE have to calculate the total number of items
            'per_page' => $per_page //WE have to determine how many items to show on a page
        ]);


        $this->items = self::get_customers($per_page, $current_page);
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

}