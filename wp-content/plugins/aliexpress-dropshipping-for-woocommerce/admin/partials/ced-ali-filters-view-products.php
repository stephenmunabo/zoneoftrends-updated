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
$filter_data = array();
if ( isset( $_GET['filterId'] ) ) {
	$get_filter_id = sanitize_text_field( $_GET['filterId'] );
	$get_filter    = get_option( 'ced_ali_filter', array() );
	$filter_data   = $get_filter[ $get_filter_id ];
}

/**
 * Aliexpress Listting Filters
 *
 * @since 1.0.0
 */

class Aliexpress_Products_View extends WP_List_Table {

	/**
	 * AliexpresFilters construct
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		parent::__construct(
			array(
				'singular' => __( 'View Products', 'aliexpress-dropshipping-for-woocommerce' ),
				'plural'   => __( 'View Products', 'aliexpress-dropshipping-for-woocommerce' ),
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

		$current_page = isset( $_GET['page_number'] ) ? sanitize_text_field( $_GET['page_number'] ) : 1;

		$page_filter_id = isset( $_GET['filterId'] ) ? sanitize_text_field( $_GET['filterId'] ) : '';
		if ( isset( $_GET['page_number'] ) ) {
			$page_number = sanitize_text_field( $_GET['page_number'] );
			echo "<div style='display:none' class='ced_ali_paginton'>";
			if ( 1 != $current_page ) {
				echo "<a style='display:none' class='ced_ali_prev' href=" . esc_url( admin_url( '/admin.php?page=ced_aliexpress&section=ced-ali-filters-view-products&filterId=' . esc_attr( $page_filter_id ) . '&page_number=' . esc_attr( $page_number - 1 ) ) ) . '> < prev </a>';
			}
			echo "<a style='display:none' class='ced_ali_next' href=" . esc_url( admin_url( '/admin.php?page=ced_aliexpress&section=ced-ali-filters-view-products&filterId=' . esc_attr( $page_filter_id ) . '&page_number=' . esc_attr( $page_number + 1 ) ) ) . '> Next > </a>';
			echo '</div>';

			$current_page = sanitize_text_field( $_GET['page_number'] );
		} else {
			echo "<div style='display:none' class='ced_ali_paginton'>";
			echo "<a style='display:none' class='ced_ali_next' href=" . esc_url( admin_url( '/admin.php?page=ced_aliexpress&section=ced-ali-filters-view-products&filterId=' . esc_attr( $page_filter_id ) . '&page_number=' . esc_attr( $current_page + 1 ) ) ) . '> Next > </a>';
			echo '</div>';
		}
		$this->items = self::ced_ali_express_filters( $current_page );
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

	/**
	 * Function for getting the product from aliexpress
	 *
	 * @ced_ali_express_filters
	 * @since 1.0.0
	 */

	public function ced_ali_express_filters( $current_page = '' ) {

		$filter_id       = isset( $_GET['filterId'] ) ? sanitize_text_field( $_GET['filterId'] ) : '';
		$filter_data_get = get_option( 'ced_ali_filter', array() );
		$filter_data_get = $filter_data_get[ $filter_id ];

		$file = CED_ALIEXPRESS_DIRPATH . 'admin/vendor/Ced_ali_get_product.php';
		if ( include_once $file ) {

			$filterData = array(
				'name'      => $filter_data_get['ced_ali_name'],
				'keyword'   => $filter_data_get['ced_ali_keyword_name'],
				'min_price' => $filter_data_get['ced_ali_min_price'],
				'max_price' => $filter_data_get['ced_ali_max_price'],

				'cat'       => $filter_data_get['category_id'],
			);

			if ( is_array( $filterData ) && ! empty( $filterData ) ) {
				$name           = isset( $filterData['name'] ) ? $filterData['name'] : false;
				$keyword        = isset( $filterData['keyword'] ) ? $filterData['keyword'] : false;
				$catId          = isset( $filterData['cat'] ) ? $filterData['cat'] : false;
				$searchMinPrice = isset( $filterData['min_price'] ) ? $filterData['min_price'] : false;
				$searchMaxPrice = isset( $filterData['max_price'] ) ? $filterData['max_price'] : false;
			}

			$bunch = new CedWadBunch( '79509' );
			$bunch->setname( $name )
			->setkeyword( $keyword )
			->setfilterId( $filter_id )
			->setcategory( $catId )
			->setpriceform( $searchMinPrice )
			->setpriceto( $searchMaxPrice )
			->setpagenumber( $current_page )
			->prepareAll()
			->getProducts();
			$products = $bunch->returnProducts();
			return $products;
		}

	}



	/**
	 * Text displayed when no data is available
	 *
	 * @since 1.0.0
	 */
	public function no_items() {
		esc_html_e( 'No Products To Show.', 'woocommerce-shopee-integration' );
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
	 * @param array $filter_product Filter Data.
	 */
	public function column_cb( $filter_product ) {

		$get_filter_id      = isset( $_GET['filterId'] ) ? sanitize_text_field( $_GET['filterId'] ) : '';
		$get_filter         = get_option( 'ced_ali_filter', array() );
		$filter_data        = $get_filter[ $get_filter_id ];
		$category           = $filter_data ['category_name'];
		$category           = explode( ' -> ', $category );
		$parent_category    = $category[0];
		$sub_category       = $category[1];
		$product_url        = $filter_product['productUrl'];
		 $ced_ali_productId = $filter_product['productId'];
		$if_product_exist   = CedIfProductExist( $ced_ali_productId );

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
			esc_attr( $filter_product['productId'] )
		);
	}


	/**
	 * Render the Image of the product
	 *
	 * @since 1.0.0
	 * @param array $filter_product Filter Data.
	 */

	public function column_image( $filter_product ) {

		if ( isset( $filter_product['imageUrl'] ) && ! empty( $filter_product['imageUrl'] ) ) {
			$name = $filter_product['imageUrl'];
			return '<img src="' . $name . '" height="100" width="100">';
		}

	}

	/**
	 * Render the Name of the product
	 *
	 * @since 1.0.0
	 * @param array $filter_product Filter Data.
	 */

	public function column_name( $filter_product ) {
		if ( isset( $filter_product['productTitle'] ) && ! empty( $filter_product['productTitle'] ) ) {
			return $filter_product['productTitle'];
		}
	}

	/**
	 * Render the category id of the product
	 *
	 * @since 1.0.0
	 * @param array $filter_product Filter Data.
	 */

	public function column_category_name( $filter_product ) {
		$get_filter_id = isset( $_GET['filterId'] ) ? sanitize_text_field( $_GET['filterId'] ) : '';
		$get_filter    = get_option( 'ced_ali_filter', array() );
		$filter_data   = $get_filter[ $get_filter_id ];
		if ( isset( $filter_data['category_name'] ) && ! empty( $filter_data['category_name'] ) ) {
			return $filter_data['category_name'];
		}
	}

	/**
	 * Render the product url
	 *
	 * @since 1.0.0
	 * @param array $filter_product Filter Data.
	 */

	public function column_product_url( $filter_product ) {
		if ( isset( $filter_product['productUrl'] ) && ! empty( $filter_product['productUrl'] ) ) {
			$product_url = $filter_product['productUrl'];
			return '<a href="' . $product_url . '" target="_blank">View On Aliexpress</a>';
		}
	}

	/**
	 * Render the original price of the product
	 *
	 * @since 1.0.0
	 * @param array $filter_product Filter Data.
	 */

	public function column_original_price( $filter_product ) {

		if ( isset( $filter_product['originalPrice'] ) && ! empty( $filter_product['originalPrice'] ) ) {
			return $filter_product['originalPrice'];
		}
	}

	/**
	 * Render the sale price of the product
	 *
	 * @since 1.0.0
	 * @param array $filter_product Filter Data.
	 */

	public function column_sale_price( $filter_product ) {

		if ( isset( $filter_product['salePrice'] ) && ! empty( $filter_product['salePrice'] ) ) {
			return $filter_product['salePrice'];
		}
	}

	/**
	 * Render the product id
	 *
	 * @since 1.0.0
	 * @param array $filter_product Filter Data.
	 */

	public function column_product_id( $filter_product ) {

		if ( isset( $filter_product['productId'] ) && ! empty( $filter_product['productId'] ) ) {
			return $filter_product['productId'];
		}
	}
	/**
	 * Render the Action of the filter
	 *
	 * @since 1.0.0
	 * @param array $filter_product Filter Data.
	 */

	public function column_action( $filter_product ) {
		$ced_ali_productId = $filter_product['productId'];
		$if_product_exist  = CedIfProductExist( $ced_ali_productId );
		if ($if_product_exist) {
			return 'Already Imported';
		}
		$get_filter_id   = isset( $_GET['filterId'] ) ? sanitize_text_field( $_GET['filterId'] ) : '';
		$get_filter      = get_option( 'ced_ali_filter', array() );
		$filter_data     = $get_filter[ $get_filter_id ];
		$category        = $filter_data ['category_name'];
		$category        = explode( ' -> ', $category );
		$parent_category = $category[0];
		$sub_category    = $category[1];
		$product_url     = $filter_product['productUrl'];
		echo '<a class="ced_ali_product_import" data-filter-id=' . esc_attr( $filter_data ['ced_ali_filters_id'] ) . ' data-product-url=' . esc_attr( $product_url ) . ' data-product-id=' . esc_attr( $filter_product ['productId'] ) . ' data-parent-category="' . esc_attr( $parent_category ) . '" data-sub-category="' . esc_attr( $sub_category ) . '" href="javascript:void(0);">Import products</a>';
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
		<div class="ced_ali_filter_view_prod_table_wrap">
			<form method="post">
				<?php
				$this->display();
				?>
			</form>
		</div>
		<?php

		/**
		 * Dispaly the pagination
		 *
		 * @since 1.0.0
		 */

		$current_page = isset( $_GET['page_number'] ) ? sanitize_text_field( $_GET['page_number'] ) : 1;

		$page_filter_id = isset( $_GET['filterId'] ) ? sanitize_text_field( $_GET['filterId'] ) : '';
		if ( isset( $_GET['page_number'] ) ) {
			$page_number = sanitize_text_field( $_GET['page_number'] );
			echo "<div class='ced_ali_paginton'>";
			if ( 1 != $current_page ) {
				echo "<a class='ced_ali_prev' href=" . esc_url( admin_url( '/admin.php?page=ced_aliexpress&section=ced-ali-filters-view-products&filterId=' . esc_attr( $page_filter_id ) . '&page_number=' . esc_attr( $page_number - 1 ) ) ) . '> &larr; </a>';
			}
			echo "<a class='ced_ali_next' href=" . esc_url( admin_url( '/admin.php?page=ced_aliexpress&section=ced-ali-filters-view-products&filterId=' . esc_attr( $page_filter_id ) . '&page_number=' . esc_attr( $page_number + 1 ) ) ) . '> &rarr; </a>';
			echo '</div>';

			$current_page = sanitize_text_field( $_GET['page_number'] );
		} else {
			echo "<div class='ced_ali_paginton'>";
			echo "<a class='ced_ali_next' href=" . esc_url( admin_url( '/admin.php?page=ced_aliexpress&section=ced-ali-filters-view-products&filterId=' . esc_attr( $page_filter_id ) . '&page_number=' . esc_attr( $current_page + 1 ) ) ) . '> &rarr; </a>';
			echo '</div>';
		}

	}
}

/**
* Creating Object
*
* @since 1.0.0
*/

$ced_aliexpress_filter_obj = new Aliexpress_Products_View();
$ced_aliexpress_filter_obj->prepareItems();
