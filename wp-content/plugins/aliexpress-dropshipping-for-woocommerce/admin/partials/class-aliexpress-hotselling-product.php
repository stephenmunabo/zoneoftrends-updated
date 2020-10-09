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

class Aliexpress_HotSelling_Product extends WP_List_Table {

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
	public function hotselling_prepareItems() {

		$per_page              = apply_filters( 'ced_aliexpress_hotselling_product_per_page', 10 );
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
		$current_page = isset( $_GET['page_number'] ) ? sanitize_text_field( $_GET['page_number'] ) : 1;

			$page_filter_id = isset( $_GET['filterId'] ) ? sanitize_text_field( $_GET['filterId'] ) : '';
		$get_total_pages    = get_option( 'ced_total_pages', '' );
		if ( isset( $_GET['page_number'] ) ) {
			$page_number = sanitize_text_field( $_GET['page_number'] );
			echo "<div style='display:none' class='ced_ali_paginton'>";
			if ( 1 != $current_page ) {
				echo "<a style='display:none' class='ced_ali_prev' href=" . esc_url( admin_url( '/admin.php?page=ced_aliexpress&section=ced-ali-filters-view-products&filterId=' . esc_attr( $page_filter_id ) . '&page_number=' . esc_attr( $page_number - 1 ) ) ) . '> < prev </a>';
			}
			if ( $get_total_pages != $current_page ) {
				echo "<a style='display:none' class='ced_ali_next' href=" . esc_url( admin_url( '/admin.php?page=ced_aliexpress&section=ced-ali-filters-view-products&filterId=' . esc_attr( $page_filter_id ) . '&page_number=' . esc_attr( $page_number + 1 ) ) ) . '> Next > </a>';
				echo '</div>';
			}
			$current_page = sanitize_text_field( $_GET['page_number'] );
		} else {
			echo "<div style='display:none' class='ced_ali_paginton'>";
			echo "<a style='display:none' class='ced_ali_next' href=" . esc_url( admin_url( '/admin.php?page=ced_aliexpress&section=ced-ali-filters-view-products&filterId=' . esc_attr( $page_filter_id ) . '&page_number=' . esc_attr( $current_page + 1 ) ) ) . '> Next > </a>';
			echo '</div>';
		}
		$this->items = self::ced_aliexpress_hotselling_product_filters( $current_page );
		if ( ! $this->current_action() || true ) {

			$this->renderHTML();
		}
	}
	/**
	 * Function for preparing data
	 *
	 * @get_bulk_actions
	 * @since 1.0.0
	 */

	public function get_bulk_actions() {

		return array(
			'Import' => __( 'Import Product', 'your-textdomain' ),
		);
	}

	/**
	 * Function for setting
	 *
	 * @process_bulk_action
	 * @since 1.0.0
	 */

	protected function bulk_actions( $which = '' ) {

		if ( 'top' == $which ) :
			if ( is_null( $this->_actions ) ) {
				$this->_actions = $this->get_bulk_actions();
					/**
					* Filters the list table Bulk Actions drop-down.
					*
					* The dynamic portion of the hook name, `$this->screen->id`, refers
					* to the ID of the current screen, usually a string.
					*
					* This filter can currently only be used to remove bulk actions.
					*
					* @since 3.5.0
					*
					* @param array $actions An array of the available bulk actions.
					*/
					$this->_actions = apply_filters( "bulk_actions-{$this->screen->id}", $this->_actions );
					$two            = '';
			} else {
				$two = '2';
			}

			if ( empty( $this->_actions ) ) {
				return;
			}

				echo '<label for="bulk-action-selector-' . esc_attr( $which ) . '" class="screen-reader-text">' . esc_html( __( 'Select bulk action' ) ) . '</label>';
				echo '<select name="action' . esc_attr( $two ) . '" class="bulk-action-selector ">';
				echo '<option value="-1">' . esc_html( __( 'Bulk Operations' ) ) . "</option>\n";

			foreach ( $this->_actions as $name => $title ) {
				$class = 'edit' === $name ? ' class="hide-if-no-js"' : '';

				echo "\t" . '<option value="' . esc_attr( $name ) . '"' . esc_attr( $class ) . '>' . esc_attr( $title ) . "</option>\n";
			}

				echo "</select>\n";
				echo "<input type='button' class='ced_ali_bulk_operation button' value='Apply'>";
				echo "\n";
			endif;
	}

	public function ced_aliexpress_hotselling_product_filters( $current_page = '' ) {

		$get_filter = get_option( 'ced_ali_hot_selling_product', array() );
		if (! isset ($get_filter ['ced_aliexpress_hotselling_category'])) {
			$get_filter['ced_aliexpress_hotselling_category'] = 'Automobiles & Motorcycles -> Car Wash & Maintenance';
			update_option('ced_ali_hot_selling_product', $get_filter);
		}
		$aliexpress_filters_url = get_option( 'ced_ali_hot_selling_product', array() );
		require_once CED_ALIEXPRESS_DIRPATH . 'admin/vendor/lib/class-class-ced-aliexpress-send-http-request.php';
		$http_instance = new Class_Ced_Aliexpress_Send_Http_Request();
		$categoryId    = ! empty( $aliexpress_filters_url['category_id'] ) ? $aliexpress_filters_url['category_id'] : '200260142';
		$action        = 'gethotsellingProductList/79509?categoryId=' . $categoryId . '&locale=global&pageNo=' . $current_page . '&pageSize=20';
		$response      = $http_instance->send_http_request( $action );
		$total_pages   = isset( $response['result']['totalResults'] ) ? $response['result']['totalResults'] : '';
		$total_pages   = ceil( (int) $total_pages / 20 );
		update_option( 'ced_total_pages', $total_pages );
		$response = isset( $response['result']['hotSellingProducts'] ) ? $response['result']['hotSellingProducts'] : '';
		if ( empty( $response ) ) {
			$action   = 'gethotsellingProductList/79509?categoryId=200260142&locale=global&pageNo=' . $current_page . '&pageSize=20';
			$response = $http_instance->send_http_request( $action );
			$response = isset( $response['result']['hotSellingProducts'] ) ? $response['result']['hotSellingProducts'] : '';
		}
		return $response;
	}

	/**
	 * Text displayed when no data is available
	 *
	 * @since 1.0.0
	 */
	public function no_items() {
		esc_html_e( 'No Hot selling product to show.', 'aliexpress-dropshipping-for-woocommerce' );
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
	 * Render the category id of the product
	 *
	 * @since 1.0.0
	 * @param array $filter_hotselling Filter Data.
	 */

	public function column_category_name( $filter_hotselling ) {
		$get_filter = get_option( 'ced_ali_hot_selling_product', array() );
		if ( isset( $get_filter['ced_aliexpress_hotselling_category'] ) && ! empty( $get_filter['ced_aliexpress_hotselling_category'] ) ) {
			return $get_filter['ced_aliexpress_hotselling_category'];
		}
	}

	/**
	 * Render the bulk edit checkbox
	 *
	 * @since 1.0.0
	 * @param array $filter_hotselling Filter Data.
	 */
	public function column_cb( $filter_hotselling ) {
		$get_filter        = get_option( 'ced_ali_hot_selling_product', array() );
		$category          = isset( $get_filter ['ced_aliexpress_hotselling_category'] ) ? $get_filter ['ced_aliexpress_hotselling_category'] : 'Automobiles & Motorcycles -> Car Wash & Maintenance' ;
		$get_filter_id     = '';
		$category          = explode( ' -> ', $category );
		$parent_category   = $category[0];
		$sub_category      = $category[1];
		$product_url       = $filter_hotselling['productUrl'];
		$ced_ali_productId = $filter_hotselling['productId'];
		$if_product_exist  = CedIfProductExist( $ced_ali_productId );

		if ($if_product_exist) {
			$image_url = CED_ALIEXPRESS_URL . 'admin/images/check.png'; 
			return '<img src="' . esc_url( $image_url ) . '" height="20" width="20">';
		}
		return sprintf(
			'<input data-filter-id="%s" data-parent-category="%s" data-sub-category="%s" data-product-url="%s" type="checkbox" name="aliexpress_product_ids[]" class="aliexpress_product_id" value="%s" />',
			$get_filter_id,
			$parent_category,
			$sub_category,
			$product_url,
			esc_attr( $filter_hotselling['productId'] )
		);
	}

	
	/**
	 * Render the Image of the product
	 *
	 * @since 1.0.0
	 * @param array $filter_hotselling Filter Data.
	 */

	public function column_image( $filter_hotselling ) {

		if ( isset( $filter_hotselling['imageUrl'] ) && ! empty( $filter_hotselling['imageUrl'] ) ) {
			$name = $filter_hotselling['imageUrl'];
			return '<img src="' . $name . '" height="100" width="100">';
		}

	}

	/**
	 * Render the product id
	 *
	 * @since 1.0.0
	 * @param array $filter_hotselling Filter Data.
	 */

	public function column_product_id( $filter_hotselling ) {

		if ( isset( $filter_hotselling['productId'] ) && ! empty( $filter_hotselling['productId'] ) ) {
			return $filter_hotselling['productId'];
		}
	}

	/**
	 * Render the Name of the filter
	 *
	 * @since 1.0.0
	 * @param array $filter_hotselling Filter Data.
	 */

	public function column_name( $filter_hotselling ) {
		if ( isset( $filter_hotselling['productTitle'] ) && ! empty( $filter_hotselling['productTitle'] ) ) {
			$title = '<strong>' . esc_attr( $filter_hotselling['productTitle'] ) . '</strong>';
			return $title;
		}
	}

	/**
	 * Render the product url
	 *
	 * @since 1.0.0
	 * @param array $filter_hotselling Filter Data.
	 */

	public function column_product_url( $filter_hotselling ) {
		if ( isset( $filter_hotselling['productUrl'] ) && ! empty( $filter_hotselling['productUrl'] ) ) {
			$product_url = $filter_hotselling['productUrl'];
			return '<a href="' . $product_url . '" target="_blank">View On Aliexpress</a>';
		}
	}

	/**
	 * Render the original price of the product
	 *
	 * @since 1.0.0
	 * @param array $filter_hotselling Filter Data.
	 */

	public function column_original_price( $filter_hotselling ) {

		if ( isset( $filter_hotselling['originalPrice'] ) && ! empty( $filter_hotselling['originalPrice'] ) ) {
			return $filter_hotselling['originalPrice'];
		}
	}

	/**
	 * Render the sale price of the product
	 *
	 * @since 1.0.0
	 * @param array $filter_hotselling Filter Data.
	 */

	public function column_sale_price( $filter_hotselling ) {

		if ( isset( $filter_hotselling['salePrice'] ) && ! empty( $filter_hotselling['salePrice'] ) ) {
			return $filter_hotselling['salePrice'];
		}
	}

	/**
	 * Render the Action of the filter
	 *
	 * @since 1.0.0
	 * @param array $filter_hotselling Filter Data.
	 */

	public function column_action( $filter_hotselling ) {

		$ced_ali_productId = $filter_hotselling['productId'];
		$if_product_exist  = CedIfProductExist( $ced_ali_productId );

		if ($if_product_exist) {
			return 'Already Imported';
		}

		$get_filter      = get_option( 'ced_ali_hot_selling_product', array() );
		$category        = isset( $get_filter ['ced_aliexpress_hotselling_category'] ) ? $get_filter ['ced_aliexpress_hotselling_category'] : 'Automobiles & Motorcycles -> Car Wash & Maintenance' ;
		$category        = explode( ' -> ', $category );
		$parent_category = $category[0];
		$sub_category    = $category[1];

		 echo '<a class="ced_ali_product_import" data-product-id=' . esc_attr( $filter_hotselling ['productId'] ) . ' data-parent-category="' . esc_attr( $parent_category ) . '" data-sub-category="' . esc_attr( $sub_category ) . '" href="javascript:void(0);">Import products</a>';
		 return;
	}

	/**
	 * Associative array of columns
	 *
	 * @since 1.0.0
	 */
	public function get_columns() {

		$columns = array(
			'cb'             => '<input type="checkbox">',
			'image'          => __( 'Image', 'aliexpress-dropshipping-for-woocommerce' ),
			'name'           => __( 'Name', 'aliexpress-dropshipping-for-woocommerce' ),
			'original_price' => __( 'Original Price', 'aliexpress-dropshipping-for-woocommerce' ),
			'sale_price'     => __( 'Sale Price', 'aliexpress-dropshipping-for-woocommerce' ),
			'product_id'     => __( 'Product ID', 'aliexpress-dropshipping-for-woocommerce' ),
			'category_name'  => __( 'Category Name', 'aliexpress-dropshipping-for-woocommerce' ),
			'product_url'    => __( 'Product URL', 'aliexpress-dropshipping-for-woocommerce' ),
			'action'         => __( 'Actions', 'aliexpress-dropshipping-for-woocommerce' ),
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
		/**
		 * Dispaly the pagination
		 *
		 * @since 1.0.0
		 */
		$get_total_pages = get_option( 'ced_total_pages', '' );
		$current_page    = isset( $_GET['page_number'] ) ? sanitize_text_field( $_GET['page_number'] ) : 1;
		if ( isset( $_GET['page_number'] ) ) {
			$page_number = sanitize_text_field( $_GET['page_number'] );
			echo "<div class='ced_ali_paginton'>";
			if ( 1 != $current_page ) {
				echo "<a class='ced_ali_prev' href=" . esc_url( admin_url( '/admin.php?page=ced_aliexpress&section=ced-ali-hotselling-product-view&page_number=' . esc_attr( $page_number - 1 ) ) ) . '> &larr; </a>';
			}
			if ( $get_total_pages != $current_page ) {
				echo "<a class='ced_ali_next' href=" . esc_url( admin_url( '/admin.php?page=ced_aliexpress&section=ced-ali-hotselling-product-view&page_number=' . esc_attr( $page_number + 1 ) ) ) . '> &rarr; </a>';
				echo '</div>';
			}

			$current_page = sanitize_text_field( $_GET['page_number'] );
		} else {
			echo "<div class='ced_ali_paginton'>";
			echo "<a class='ced_ali_next' href=" . esc_url( admin_url( '/admin.php?page=ced_aliexpress&section=ced-ali-hotselling-product-view&page_number=' . esc_attr( $current_page + 1 ) ) ) . '> &rarr; </a>';
			echo '</div>';
		}
	}
}

/**
* Creating Object
*
* @since 1.0.0
*/

$ced_aliexpress_filter_obj = new Aliexpress_HotSelling_Product();
$ced_aliexpress_filter_obj->hotselling_prepareItems();
