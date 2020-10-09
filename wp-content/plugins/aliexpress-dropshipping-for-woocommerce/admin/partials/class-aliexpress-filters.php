<?php
/**
 * Global settings section
 *
 * @package  Aliexpress_Dropshipping_For_Woocommerce
 * @version  1.0.0
 * @link     https://cedcommerce.com
 * @since    1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
}
$file = CED_ALIEXPRESS_DIRPATH . 'admin/partials/header.php';
if ( file_exists( $file ) ) {
	include_once $file;
}
if ( ! class_exists( 'WP_List_Table' ) ) {
	include_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * Aliexpress Listting Filters
 *
 * @since 1.0.0
 */

class Aliexpress_Filters extends WP_List_Table {

	/**
	 * AliexpresFilters construct
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		parent::__construct(
			array(
				'singular' => __( 'Filter', 'aliexpress-dropshipping-for-woocommerce' ),
				'plural'   => __( 'Filters', 'aliexpress-dropshipping-for-woocommerce' ),
				'ajax'     => true,
			)
		);

	}
	/**
	 * Function for preparing data to be displayed
	 *
	 * @since 1.0.0
	 */
	public function prepareItems() {

		$per_page              = apply_filters( 'ced_aliexpress_filters_per_page', 10 );
		$columns               = $this->get_columns();
		$hidden                = array();
		$sortable              = $this->getSortableColumns();
		$this->_column_headers = array( $columns, $hidden, $sortable );

		$current_page = $this->get_pagenum();
		if ( 1 < $current_page ) {
			$offset = $per_page * ( $current_page - 1 );
		} else {
			$offset = 0;
		}

		$this->items = self::ced_ali_express_filters( $per_page, $current_page );
		$count       = self::get_count( $per_page, $current_page );
		$this->set_pagination_args(
			array(
				'total_items' => $count,
				'per_page'    => $per_page,
				'total_pages' => ceil( $count / $per_page ),
			)
		);
		if ( ! $this->current_action() || true ) {
			$this->items = self::ced_ali_express_filters( $per_page, $current_page );
			$this->renderHTML();
		} else {
			$this->process_bulk_action();
		}
	}
	public function ced_ali_express_filters( $per_page = '', $page_number = '' ) {

		$aliexpress_filters = get_option( 'ced_ali_filter', array() );
		return $aliexpress_filters;

	}

	/**
	 * Text displayed when no data is available
	 *
	 * @since 1.0.0
	 */
	public function no_items() {
		esc_html_e( 'No Filters To Show.', 'aliexpress-dropshipping-for-woocommerce' );
	}

	/**
	 * Columns to make sortable.
	 *
	 * @since 1.0.0
	 */
	public function getSortableColumns() {

		$sortable_columns = array();
		return $sortable_columns;
	}

	/**
	 * Render the bulk edit checkbox
	 *
	 * @since 1.0.0
	 * @param array $filter Filter Data.
	 */
	public function column_cb( $filter ) {
		return sprintf(
			'<input type="checkbox" name="ced_ali_products_id[]" class="ced_ali_products_id" value="%s" />',
			$filter['ced_ali_filters_id']
		);
	}

	/**
	 * Render the Minimum price
	 *
	 * @since 1.0.0
	 * @param array $filter Filter Data.
	 */

	public function column_min_price( $filter ) {
			echo esc_attr( $filter['ced_ali_min_price'] );
	}

	/**
	 * Render the Maximum price
	 *
	 * @since 1.0.0
	 * @param array $filter Filter Data.
	 */

	public function column_max_price( $filter ) {
		echo esc_attr( $filter['ced_ali_max_price'] );
	}

	/**
	 * Render the Name of the filter
	 *
	 * @since 1.0.0
	 * @param array $filter Filter Data.
	 */

	public function column_name( $filter ) {
		$title        = '<strong>' . esc_attr( $filter['ced_ali_name'] ) . '</strong>';
		$request_page = isset( $_REQUEST['page'] ) ? sanitize_text_field( $_REQUEST['page'] ) : '';
		$actions      = array(
			'edit'   => sprintf( '<a href="?page=%s&section=%s&action=%s&filterId=%s">Edit</a>', esc_attr( $request_page ), 'ced-ali-filters-view', 'edit', $filter['ced_ali_filters_id'] ),
			'delete' => sprintf( '<a href="?page=%s&section=%s&action=%s&filterId=%s">Delete</a>', esc_attr( $request_page ), 'ced-ali-filters-delete', 'delete', $filter['ced_ali_filters_id'] ),
		);
		return $title . $this->row_actions( $actions );
	}

	/**
	 * Render the keyword Name of the filter
	 *
	 * @since 1.0.0
	 * @param array $filter Filter Data.
	 */

	public function column_keyword_name( $filter ) {
		echo esc_attr( $filter['ced_ali_keyword_name'] );
	}

	/**
	 * Render the Category Name of the filter
	 *
	 * @since 1.0.0
	 * @param array $filter Filter Data.
	 */

	public function column_category_name( $filter ) {
		echo esc_attr( $filter['category_name'] );
	}

	/**
	 * Render the Action of the filter
	 *
	 * @since 1.0.0
	 * @param array $filter Filter Data.
	 */

	public function column_action( $filter ) {
		echo "<a class='ced_ali_button' href=" . esc_url( admin_url( '/admin.php?page=ced_aliexpress&section=ced-ali-filters-view-products&filterId=' . $filter['ced_ali_filters_id'] ) ) . '>' . esc_html( __( 'View Products', 'aliexpress-dropshipping-for-woocommerce' ) ) . '</a>';
		return;
	}


	/**
	 * Render the Auto Importe filter
	 *
	 * @since 1.0.0
	 * @param array $filter Filter Data.
	 */

	public function column_auto_import( $filter ) {
		$get_filter_id     = isset($filter['ced_ali_filters_id']) ? sanitize_text_field($filter['ced_ali_filters_id']) : '';
		$autoImportFilters = get_option( 'ced_ali_auto_import', array());
		$autoImportFilters = isset($autoImportFilters[ $get_filter_id ]['numberOfProduct']) ? $autoImportFilters[ $get_filter_id ]['numberOfProduct'] : '';
		echo '<input value="' . esc_attr( $autoImportFilters ) . '" placeholder="no of product to import" type="number" name="ced_ali_auto_import_input" class="ced_ali_auto_import_' . esc_attr( $get_filter_id ) . '" style="width:100%;">

			<a href="javascript:void(0);" ced-filter-id="' . esc_attr( $get_filter_id ) . '" class="ced_auto_import_btn">' . esc_html( __( 'Auto Import', 'aliexpress-dropshipping-for-woocommerce' ) ) . '</a>';
		return;
	}

	/**
	 * Associative array of columns
	 *
	 * @since 1.0.0
	 */
	public function get_columns() {

		$columns = array(
			'cb'            => '<input type="checkbox">',
			'name'          => __( 'Name', 'aliexpress-dropshipping-for-woocommerce' ),
			'keyword_name'  => __( 'Keyword Name', 'aliexpress-dropshipping-for-woocommerce' ),
			'min_price'     => __( 'Min Price', 'aliexpress-dropshipping-for-woocommerce' ),
			'max_price'     => __( 'Max Price', 'aliexpress-dropshipping-for-woocommerce' ),
			'category_name' => __( 'Category Name', 'aliexpress-dropshipping-for-woocommerce' ),
			'action'        => __( 'Actions', 'aliexpress-dropshipping-for-woocommerce' ),
			'auto_import'        => __( 'Auto Impprt', 'aliexpress-dropshipping-for-woocommerce' ),
		);
		return $columns;
	}

	/**
	 * Dispaly the form data
	 *
	 * @since 1.0.0
	 */

	public function renderHTML(){ ?>
		<form method="post">
			<?php
			$this->display();
			?>
		</form>
		<?php
	}
}

/**
* Creating Object
*
* @since 1.0.0
*/

$ced_aliexpress_filter_obj = new Aliexpress_Filters();
$ced_aliexpress_filter_obj->prepareItems();

