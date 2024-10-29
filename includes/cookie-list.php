<?php
if (!class_exists('WP_List_Table')) {
	require_once (ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class ASD_Cookie_List extends WP_List_Table {

	private $per_pages= 10;
	private $table= 'wp_asd_cookie_list';
    private $text_domain= 'asd-cookie-consent';
	
	function __construct(){
        global $status, $page;
                
        parent::__construct( array(
            'singular'  => 'cookie',     
            'plural'    => 'cookies',   
            'ajax'      => true
        ) );
    }

    function column_default($item, $column_name){
        switch($column_name){
            case 'unique_id':
            case 'category':
            case 'cookie':
            case 'date':
                return $item[$column_name];
            default:
                return print_r($item,true);
        }
    }

    function column_cb($item){
        return sprintf(
            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            $this->_args['singular'], 
            $item['id']
        );
    }

	function get_columns(){
        $columns = array(
            'cb'       		 		=> '<input type="checkbox" />',
            'unique_id'     		=> esc_html__( 'Token', $this->text_domain ),
            'category'    	        => esc_html__( 'Accepted Categories', $this->text_domain ),
            'cookie'		        => esc_html__( 'Accepted Cookies', $this->text_domain ),
            'date'					=> esc_html__( 'Date', $text_domain)
        );
        return $columns;
    }

    function get_sortable_columns() {
        $sortable_columns = array(
            'date'			=> array('date', false),		
        );
        return $sortable_columns;
    }

    function get_bulk_actions() {
        $actions = array(
            'delete'    => 'Delete'
        );
        return $actions;
    }

    function process_bulk_action() {
        global $wpdb;

        if( 'delete'===$this->current_action() ) {
            if( isset($_REQUEST['cookie']) ) {
                foreach ($_REQUEST['cookie'] as $id) {
                    $wpdb->delete($this->table, array('id' => $id));
                }
            }
        }
    }

    function prepare_items() {
        global $wpdb;

        $per_page = $this->per_pages;
        
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        
        $this->_column_headers = array($columns, $hidden, $sortable);
        
        $this->process_bulk_action();
        
        $data = $wpdb->get_results('SELECT * FROM ' . $this->table, ARRAY_A);      

        function usort_reorder($a,$b){
            $orderby = (!empty($_REQUEST['orderby'])) ? $_REQUEST['orderby'] : 'date';
            $order = (!empty($_REQUEST['order'])) ? $_REQUEST['order'] : 'desc'; 
            $result = strcmp($a[$orderby], $b[$orderby]); 
            return ($order==='asc') ? $result : -$result; 
        }
        usort($data, 'usort_reorder');
        
        $current_page = $this->get_pagenum();
       
        $total_items = count($data);
        
        $data = array_slice($data,(($current_page-1)*$per_page),$per_page);
        
        $this->items = $data;
        
        $this->set_pagination_args( array(
            'total_items' => $total_items,                  
            'per_page'    => $per_page,                     
            'total_pages' => ceil($total_items/$per_page) 
        ) );
    }
}
