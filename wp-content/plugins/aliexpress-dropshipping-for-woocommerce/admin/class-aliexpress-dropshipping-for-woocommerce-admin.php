<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://cedcommerce.com
 * @since      1.0.0
 *
 * @package    Aliexpress_Dropshipping_For_Woocommerce
 * @subpackage Aliexpress_Dropshipping_For_Woocommerce/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Aliexpress_Dropshipping_For_Woocommerce
 * @subpackage Aliexpress_Dropshipping_For_Woocommerce/admin
 * @author     CedCommerce <plugins@cedcommerce.com>
 */
class Aliexpress_Dropshipping_For_Woocommerce_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		require_once CED_ALIEXPRESS_DIRPATH . 'admin/vendor/lib/class-class-ced-aliexpress-send-http-request.php';
		$this->http_instance = new Class_Ced_Aliexpress_Send_Http_Request();
		
		add_action( 'wp_ajax_get_aliexpress_products', array( $this, 'get_aliexpress_products' ) );
		add_action( 'wp_ajax_get_aliexpress_categories', array( $this, 'get_aliexpress_categories' ) );
		add_action( 'wp_ajax_Ced_ali_createBunch', array( $this, 'Ced_ali_createBunch' ) );
		add_action( 'wp_ajax_ced_aliexpress_process_bulk_action', array( $this, 'ced_aliexpress_process_bulk_action' ) );
		add_action( 'wp_ajax_get_aliexpress_auto_import', array( $this, 'get_aliexpress_auto_import' ) );
		add_action( 'wp_ajax_ced_update_inventory_using_action', array( $this, 'ced_update_inventory_using_action' ) );
		add_action( 'admin_init', array( $this, 'ced_schedules' ) );
		add_action( 'ced_ali_chrome_data_import', array( $this, 'ced_ali_chrome_data_import' ) );
		$tra_res = get_transient( 'ced_aliexpress_token_value' );
		if (!$tra_res) {
			
			add_action( 'admin_init', array( $this, 'set_aliexpress_request' ) );
			
		}
		add_action( 'manage_edit-product_columns' , array( $this , 'ced_ali_add_table_columns' ) );
		add_action( 'manage_product_posts_custom_column' , array( $this , 'ced_ali_manage_table_columns' ) , 10 , 2 );
		
	}


	public function ced_ali_add_table_columns( $columns ) {
		$columns['additional'] = 'Actions';
		return $columns;
	}

	public function ced_ali_manage_table_columns( $column_name, $post_id ) {

		$productId = get_post_meta( $post_id , 'ced_ali_itemId' , true );
		$classname = 'ced_spinner_' . $post_id . ' spinner';
		$url       = 'https://www.aliexpress.com/item/' . $productId . '.html';
		if ( 'additional' == $column_name &&  ! empty( $productId ) ) {
			?>

			<div class="ced_ali_extra_actions">
				<ul>
					<li>
						<a href="javascript:void(0)" class="" id="ced_ali_instant_sync_inventory" data-id="<?php echo esc_attr( $productId ); ?>" data-post_id="<?php echo esc_attr( $post_id ); ?>"><?php esc_html_e( 'Sync Inventory' , 'aliexpress-dropshipping-for-woocommerce' ); ?></a>
						<span class="<?php echo esc_attr( $classname ); ?>"></span>
					</li>
					<li>
						<a href="<?php echo esc_url( $url ); ?>" target="_blank" class=""><?php esc_html_e( 'View on Aliexpress' , 'aliexpress-dropshipping-for-woocommerce' ); ?></a>
					</li>
				</ul>
			</div>

			<?php
		}
	}


	/**
	 * AliExpress_Dropshipping_for_WooCommerce_Admin ced_ali_cron_schedules.
	 *
	 * @since 1.0.0
	 * @param array $schedules Cron Schedules.
	 */
	public function ced_ali_cron_schedules( $schedules ) {

		if ( ! isset( $schedules['ced_ali_2min'] ) ) {
			$schedules['ced_ali_2min'] = array(
				'interval' => 2 * 60,
				'display'  => __( 'Once every 2 minutes' ),
			);
		}
		if ( ! isset( $schedules['ced_ali_6min'] ) ) {
			$schedules['ced_ali_6min'] = array(
				'interval' => 6 * 60,
				'display'  => __( 'Once every 6 minutes' ),
			);
		}
		if ( ! isset( $schedules['ced_ali_10min'] ) ) {
			$schedules['ced_ali_10min'] = array(
				'interval' => 10 * 60,
				'display'  => __( 'Once every 10 minutes' ),
			);
		}
		if ( ! isset( $schedules['ced_ali_15min'] ) ) {
			$schedules['ced_ali_15min'] = array(
				'interval' => 15 * 60,
				'display'  => __( 'Once every 15 minutes' ),
			);
		}
		if ( ! isset( $schedules['ced_ali_30min'] ) ) {
			$schedules['ced_ali_30min'] = array(
				'interval' => 30 * 60,
				'display'  => __( 'Once every 30 minutes' ),
			);
		}
		if ( ! isset( $schedules['ced_ali_60min'] ) ) {
			$schedules['ced_ali_60min'] = array(
				'interval' => 60 * 60,
				'display'  => __( 'Once every 60 minutes' ),
			);
		}
		return $schedules;
	}

	public function get_aliexpress_authorize() {

		$redirect_url = esc_url( admin_url( '/admin.php?page=ced_aliexpress' ) );
		$url_red      = 'https://bigcommerce.cedcommerce.com/marketplace-integration/aliexpressauth/authorise?platform=wooc&ver_code=3251b321asdbj&request_type=dropship&redirect_uri="' . $redirect_url . '"';

		$postfields = array(
			'response_type' => 'code',
			'client_id'     => '28302878',
			'redirect_uri'  => $url_red,
			'state'         => '122',
			'sp'            => 'ae',
		);
		ksort( $postfields );
		$post_data = '';
		foreach ( $postfields as $key => $value ) {
			$post_data .= "$key=" . urlencode( $value ) . '&';
		}
		$url      = 'https://oauth.aliexpress.com/authorize';
		$response = $this->http_instance->send_http_request( $url, $post_data );
		$response = json_decode( $response, true );
	}

	public function set_aliexpress_request() {
		if ( isset( $_GET['code'] ) && ! empty( $_GET['code'] ) ) {
			$redirect_url = esc_url( admin_url( '/admin.php?page=ced_aliexpress' ) );
			$postfields   = array(
				'grant_type'    => 'authorization_code',
				'client_id'     => '28302878',
				'client_secret' => '2df683ce13c5944ed5446a360ccdbcc5',
				'code'          => sanitize_text_field( $_GET['code'] ),
				'sp'            => 'ae',
				'redirect_uri'  => $redirect_url,
			);
			$post_data    = '';
			foreach ( $postfields as $key => $value ) {
				$post_data .= "$key=" . urlencode( $value ) . '&';
			}

			$action   = 'https://oauth.aliexpress.com/token';
			$response = $this->http_instance->send_http_request( $action, $post_data );
			if (isset($response['expire_time'])) {
				set_transient( 'ced_aliexpress_token_value', json_encode( $response ), $response['expire_time'] );
			}

			$tra_res        = get_transient( 'ced_aliexpress_token_value' );
			$response_trans = json_decode( $tra_res, true );
		}
	}

	public function ced_ali_createProductOnWooStore( $productData = array(), $filterId = '', $aliexpressparentcategory = '', $aliexpresssubCategory = '', $get_filter_setting_post_status = '', $ced_ali_createProductOnWooStore = '' ) {

		if ( empty( $productData ) ) {
			return false;
		}

		$aliexpressProductId = isset( $productData['product_id'] ) ? $productData['product_id'] : '';

		$wooProductId = wp_insert_post(
			array(
				'post_title'   => isset( $productData['subject'] ) ? $productData['subject'] : 'Aliexpress Product - ' . $aliexpressProductId,
				'post_status'  => isset( $get_filter_setting_post_status ) ? $get_filter_setting_post_status : 'draft',
				'post_type'    => 'product',
				'post_content' => isset( $productData['detail'] ) ? $productData['detail'] : '',
			)
		);

		if ( empty( $wooProductId ) ) {
			return false;
		}

		$imageUrls = isset( $productData['image_u_r_ls'] ) ? explode( ';', $productData['image_u_r_ls'] ) : array();
		if ( ! empty( $imageUrls ) ) {
			$this->createProductImages( $wooProductId, $imageUrls );
		}

		update_post_meta( $wooProductId, 'ced_ali_last_updated', gmdate( 'd/m/Y' ) );
		update_post_meta( $wooProductId, 'ced_ali_productData', $productData );

		$this->ced_ali_createProductCategory( $wooProductId, $aliexpressparentcategory, $aliexpresssubCategory );
		update_post_meta( $wooProductId, 'ced_ali_productUrl', $ced_ali_createProductOnWooStore );

		update_post_meta( $wooProductId, '_visibility', 'visible' );
		update_post_meta( $wooProductId, '_sku', $aliexpressProductId );
		update_post_meta( $wooProductId, 'ced_ali_itemId', $aliexpressProductId );

		update_post_meta( $wooProductId, '_weight', $productData['gross_weight'] );
		update_post_meta( $wooProductId, '_length', $productData['package_length'] );
		update_post_meta( $wooProductId, '_height', $productData['package_width'] );
		update_post_meta( $wooProductId, '_width', $productData['gross_weight'] );

		update_post_meta( $wooProductId, '_manage_stock', 'yes' );
		update_post_meta( $wooProductId, '_low_stock_amount', 0 );
		if ( isset( $productData['total_available_stock'] ) ) {
			if ( $productData['total_available_stock'] > 0 ) {
				update_post_meta( $wooProductId, '_stock_status', 'instock' );
				update_post_meta( $wooProductId, '_stock', $productData['total_available_stock'] );
			} else {
				update_post_meta( $wooProductId, '_stock_status', 'outofstock' );
				update_post_meta( $wooProductId, '_stock', 0 );
			}
		}
		if ( isset( $productData['aeop_ae_product_s_k_us']['aeop_ae_product_sku'][0] ) ) {
			wp_set_object_terms( $wooProductId, 'variable', 'product_type' );
			$variableProductData = $productData['aeop_ae_product_s_k_us']['aeop_ae_product_sku'];
			$this->createAsVariableProduct( $wooProductId, $variableProductData, $filterId );
		} else {
			wp_set_object_terms( $wooProductId, 'simple', 'product_type' );
			$simpleProductData = $productData['aeop_ae_product_s_k_us']['aeop_ae_product_sku'];
			if (isset($productData['item_offer_site_sale_price'])) {
				$simpleProductData['item_offer_site_sale_price'] = $productData['item_offer_site_sale_price'];
			}
			$this->createAsSimpleProduct( $wooProductId, $simpleProductData, $filterId );
		}

		if ( isset( $productData['aeop_ae_product_propertys']['aeop_ae_product_property'] ) && ! empty( $productData['aeop_ae_product_propertys']['aeop_ae_product_property'] ) ) {
			$attributes = $productData['aeop_ae_product_propertys']['aeop_ae_product_property'];
			$this->createProductAttributes( $wooProductId, $attributes );
		}

		$wooStoreProduct = wc_get_product( $wooProductId );
		$wooStoreProduct->save();
		return $wooProductId;
	}
	public function ced_ali_createProductCategory( $wooProductId = '', $aliexpressparentcategory = '', $aliexpresssubCategory = '' ) {

		$categories = array( $aliexpressparentcategory, $aliexpresssubCategory );

		if ( is_array( $categories ) && ! empty( $categories ) ) {
			$parent_id = '';
			foreach ( $categories as $key => $value ) {
				if ( empty( $value ) ) {
					continue;
				}

				$term = wp_insert_term(
					$value,
					'product_cat',
					array(
						'description' => $value,
						'parent'      => $parent_id,
					)
				);

				if ( isset( $term->error_data['term_exists'] ) ) {

					$term_id = $term->error_data['term_exists'];

				} elseif ( isset( $term['errors'] ) ) {
					continue;
				} elseif ( isset( $term['term_id'] ) ) {

					$term_id = $term['term_id'];
				}

				$parent_id = ! empty( $term_id ) ? $term_id : '';
			}
			if ( isset( $term_id ) && ! empty( $term_id ) ) {

				wp_set_object_terms( $wooProductId, $term_id, 'product_cat' );

			}
		}
	}

	public function createAsVariableProduct( $wooProductId = '', $variableProductData = array(), $filterId = '' ) {
		if ( empty( $wooProductId ) || empty( $variableProductData ) ) {
			return false;
		}

		$variationAttributes     = array();
		$attributes              = array();
		$variationAttributeName  = array();
		$variationAttributeValue = array();
		$data                    = array();

		foreach ( $variableProductData as $key => $value ) {
			if ( ! isset( $value['aeop_s_k_u_propertys']['aeop_sku_property'][0] ) ) {
				$attributeProperties[] = $value['aeop_s_k_u_propertys']['aeop_sku_property'];
			} else {
				$attributeProperties = $value['aeop_s_k_u_propertys']['aeop_sku_property'];
			}

			if ( ! empty( $attributeProperties ) ) {
				foreach ( $attributeProperties as $key1 => $value1 ) {
					$variationAttributeName[ $value1['sku_property_id'] ]    = $value1['sku_property_name'];
					$variationAttributeValue[ $value1['sku_property_id'] ][] = $value1['sku_property_value'];
				}
			}
		}

		if ( ! empty( $variationAttributeName ) ) {
			$count = 1;
			foreach ( $variationAttributeName as $key => $value ) {
				$data['attribute_names'][]      = $value;
				$data['attribute_position'][]   = $count;
				$data['attribute_values'][]     = implode( '|', array_unique( $variationAttributeValue[ $key ] ) );
				$data['attribute_visibility'][] = 1;
				$data['attribute_variation'][]  = 0;
				++$count;
			}

			if ( isset( $data['attribute_names'], $data['attribute_values'] ) ) {
				$attribute_names         = $data['attribute_names'];
				$attribute_values        = $data['attribute_values'];
				$attribute_visibility    = isset( $data['attribute_visibility'] ) ? $data['attribute_visibility'] : array();
				$attribute_variation     = isset( $data['attribute_variation'] ) ? $data['attribute_variation'] : array();
				$attribute_position      = $data['attribute_position'];
				$attribute_names_max_key = max( array_keys( $attribute_names ) );
				for ( $i = 0; $i <= $attribute_names_max_key; $i++ ) {
					if ( empty( $attribute_names[ $i ] ) || ! isset( $attribute_values[ $i ] ) ) {
						continue;
					}
					$attribute_id   = 0;
					$attribute_name = wc_clean( $attribute_names[ $i ] );
					if ( 'pa_' === substr( $attribute_name, 0, 3 ) ) {
						$attribute_id = wc_attribute_taxonomy_id_by_name( $attribute_name );
					}
					$options = isset( $attribute_values[ $i ] ) ? $attribute_values[ $i ] : '';
					if ( is_array( $options ) ) {
						// Term ids sent as array.
						$options = wp_parse_id_list( $options );
					} else {
						$options = wc_get_text_attributes( $options );
					}

					if ( empty( $options ) ) {
						continue;
					}
					$attribute = new WC_Product_Attribute();
					$attribute->set_id( $attribute_id );
					$attribute->set_name( $attribute_name );
					$attribute->set_options( $options );
					$attribute->set_position( $attribute_position[ $i ] );
					$attribute->set_visible( isset( $attribute_visibility[ $i ] ) );
					$attribute->set_variation( isset( $attribute_variation[ $i ] ) );
					$attributes[] = $attribute;
				}
			}
			$product_type = 'variable';
			$classname    = WC_Product_Factory::get_product_classname( $wooProductId, $product_type );
			$product      = new $classname( $wooProductId );
			$product->set_attributes( $attributes );
			$product->save();

			$attributeProperties = array();
			$thedata             = array();

			foreach ( $variableProductData as $key => $variation ) {
				$sku_code       = isset( $variation['sku_code'] ) ? $variation['sku_code'] : '';
				$variation_post = array(
					'post_title'  => 'Variation - ' . $sku_code,
					'post_name'   => 'product-' . $wooProductId . '-variation-' . $key,
					'post_status' => 'publish',
					'post_parent' => $wooProductId,
					'post_type'   => 'product_variation',
					'guid'        => home_url() . '/?product_variation=product-' . $wooProductId . '-variation-' . $key,
				);

				$variation_post_id = wp_insert_post( $variation_post );

				/*assign variation image*/

				$variation_img_data = isset( $variation['aeop_s_k_u_propertys']['aeop_sku_property'] ) ? $variation['aeop_s_k_u_propertys']['aeop_sku_property'] : array();
				if ( ! isset( $variation_img_data['0'] ) && ! empty( $variation_img_data ) ) {
					$variation_img_data = array( $variation_img_data );
				}

				$images = array();
				if ( is_array( $variation_img_data ) && ! empty( $variation_img_data ) ) {
					foreach ( $variation_img_data as $key => $img_value ) {
						if ( isset( $img_value['sku_image'] ) ) {
							$images[] = $img_value['sku_image'];
						}
					}
				}

				$this->createProductImages( $variation_post_id, $images );

				if ( ! isset( $variation['aeop_s_k_u_propertys']['aeop_sku_property'][0] ) ) {
					$attributeProperties[] = $variation['aeop_s_k_u_propertys']['aeop_sku_property'];
				} else {
					$attributeProperties = $variation['aeop_s_k_u_propertys']['aeop_sku_property'];
				}

				if ( ! empty( $attributeProperties ) ) {
					foreach ( $attributeProperties as $key1 => $value1 ) {
						wp_set_object_terms( $variation_post_id, $value1['sku_property_value'], $value1['sku_property_name'] );

						$attribute = strtolower( $value1['sku_property_name'] );
						$attribute = str_replace( ' ', '-', $attribute );
						update_post_meta( $variation_post_id, 'attribute_' . $attribute, $value1['sku_property_value'] );

						$thedata = array(
							$attribute => array(
								'name'         => $value1['sku_property_value'],
								'value'        => '',
								'is_visible'   => '1',
								'is_variation' => '1',
								'is_taxonomy'  => '1',
							),
						);
						update_post_meta( $variation_post_id, '_product_attributes', $thedata );
					}
				}

				update_post_meta( $variation_post_id, '_manage_stock', 'yes' );
				if ( isset( $variation['sku_stock'] ) && true == $variation['sku_stock']) {
					update_post_meta( $variation_post_id, '_stock_status', 'instock' );
					update_post_meta( $variation_post_id, '_stock', $variation['s_k_u_available_stock'] );
				}
				$sku_code = isset( $variation['sku_code'] ) ? $variation['sku_code'] : '';
				update_post_meta( $variation_post_id, '_sku', $sku_code );
				update_post_meta( $variation_post_id, '_ced_ali_sku_id', $variation['id'] );

				$simplePrice = $variation['sku_price'];

				$newprice = $this->SetPriceMarkup( $filterId, $wooProductId, $simplePrice );

				update_post_meta( $variation_post_id, '_price', $newprice );
				update_post_meta( $variation_post_id, '_regular_price', $newprice );
				
				$offer_price = isset( $variation['offer_sale_price'] ) ? $variation['offer_sale_price'] : '';

				if (!empty($offer_price )) {
					$offer_price = $this->SetPriceMarkup( $filterId, $wooProductId, $offer_price );
					update_post_meta( $variation_post_id, '_price', $offer_price );
					update_post_meta( $variation_post_id, '_sale_price', $offer_price );
				}
				
				$wooVariationProduct = wc_get_product( $variation_post_id );
				$wooVariationProduct->save();
			}

			$wooProduct = wc_get_product( $wooProductId );
			$wooProduct->save();
		}
	}

	public function createAsSimpleProduct( $wooProductId = '', $simpleProductData = array(), $filterId = '' ) {
		if ( empty( $wooProductId ) || empty( $simpleProductData ) ) {
			return false;
		}

		if ( isset( $simpleProductData['sku_stock'] ) && true == $simpleProductData['sku_stock'] ) {
			update_post_meta( $wooProductId, '_stock_status', 'instock' );
			update_post_meta( $wooProductId, '_manage_stock', 'yes' );
			update_post_meta( $wooProductId, '_stock', $simpleProductData['s_k_u_available_stock'] );
		}
		$simplePrice = $simpleProductData['sku_price'];
		$newprice    = $this->SetPriceMarkup( $filterId, $wooProductId, $simplePrice );

		update_post_meta( $wooProductId, '_price', $newprice );
		update_post_meta( $wooProductId, '_regular_price', $newprice );
		$offer_price = isset( $simpleProductData['offer_sale_price'] ) ? $simpleProductData['offer_sale_price'] : '';
		if (empty($offer_price)) {

			$offer_price = isset( $simpleProductData['item_offer_site_sale_price'] ) ? $simpleProductData['item_offer_site_sale_price'] : '';

		}
		if (!empty($offer_price )) {
			$offer_price = $this->SetPriceMarkup( $filterId, $wooProductId, $offer_price );
			update_post_meta( $wooProductId, '_price', $offer_price );
			update_post_meta( $wooProductId, '_sale_price', $offer_price );
		}
		update_post_meta( $wooProductId, '_ced_ali_sku_id', $simpleProductData['id'] );
	}

	public function SetPriceMarkup( $filterId = '', $wooProductId = '', $simplePrice = '' ) {
		$get_filters_global = get_option( 'ced_ali_global_setting_filter', array() );
		$get_filter         = get_option( 'ced_ali_filter', array() );
		$filter_data        = isset( $get_filter[ $filterId ] ) ? $get_filter[ $filterId ] : array();
		$flag               = false;
		if ( isset( $filter_data['ced_ali_filter_url_data'] ) && is_array( $filter_data['ced_ali_filter_url_data'] ) && ! empty( $filter_data['ced_ali_filter_url_data'] ) ) {

			foreach ( $filter_data['ced_ali_filter_url_data'] as $key => $value ) {

				if ( ( $simplePrice >= $value['ced_ali_min_price_markup'] ) && ( $simplePrice <= $value['ced_ali_max_price_markup'] ) ) {

					if (  'fixed_price_markup' == $value['ced_aliexpress_price_markup'] ) {

						$simplePrice = $simplePrice + $value['ced_ali_markup_aplied'];
						$flag        = true;
					} elseif ( 'percantage_increase' == $value['ced_aliexpress_price_markup'] ) {

						$simplePrice = ( ( $value['ced_ali_markup_aplied'] / 100 ) * $simplePrice ) + $simplePrice;
						$flag        = true;
					}
				}
			}
		}
		if ( ! $flag ) {
			if ( isset( $get_filters_global['ced_ali_global_price_markup'] ) && ! empty( $get_filters_global['ced_ali_global_price_markup'] ) && is_array( $get_filters_global['ced_ali_global_price_markup'] ) ) {

				foreach ( $get_filters_global['ced_ali_global_price_markup'] as $key => $value ) {

					if ( ( $simplePrice >= $value['ced_ali_min_price_markup'] ) && ( $simplePrice <= $value['ced_ali_max_price_markup'] ) ) {
						if ( 'fixed_price_markup' == $value['ced_aliexpress_price_markup'] ) {

							$simplePrice = $simplePrice + $value['ced_ali_markup_aplied'];
							return $simplePrice;
						} elseif ( 'percantage_increase' == $value['ced_aliexpress_price_markup'] ) {

							$simplePrice = ( ( $value['ced_ali_markup_aplied'] / 100 ) * $simplePrice ) + $simplePrice;
							return $simplePrice;
						}
					}
				}
				return $simplePrice;
			} else {
				return $simplePrice;
			}
		} else {
			return $simplePrice;
		}
	}

	public function createProductAttributes( $wooProductId = '', $attributes = array() ) {
		if ( empty( $wooProductId ) || empty( $attributes ) ) {
			return false;
		}

		$product_attributes = array();

		foreach ( $attributes as $key => $value ) {
			if ( isset( $value['attr_name'] ) && isset( $value['attr_value'] ) ) {
				$product_attributes[ $key ] = array(
					'name'        => htmlspecialchars( stripslashes( $value['attr_name'] ) ),
					'value'       => $value['attr_value'],
					'is_visible'  => 1,
					'is_taxonomy' => 0,
				);
			}
		}

		if ( ! empty( $product_attributes ) ) {
			$thedata = get_post_meta( $wooProductId, '_product_attributes', true );
			if ( empty( $thedata ) ) {
				$thedata = array();
			}
			update_post_meta( $wooProductId, '_product_attributes', array_merge( $thedata, $product_attributes ) );
		}
	}

	public function ced_aliexpress_process_bulk_action() {

		$sanitized_array          = filter_input_array( INPUT_POST, FILTER_SANITIZE_STRING );
		$operation                = isset( $sanitized_array['operation_performed'] ) ?
		$sanitized_array['operation_performed'] : '';
		$productId                = isset( $sanitized_array['productId'] ) ? $sanitized_array['productId'] : '';
		$filterId                 = isset( $sanitized_array['filterId'] ) ? $sanitized_array['filterId'] : '';
		$aliexpressparentcategory = isset( $sanitized_array['parentcategory'] ) ? $sanitized_array['parentcategory'] : '';
		$aliexpresssubCategory    = isset( $sanitized_array['subCategory'] ) ? $sanitized_array['subCategory'] : '';
		$aliexpresssubproductUrl  = isset( $sanitized_array['productUrl'] ) ? $sanitized_array['productUrl'] : '';

		$get_filters_setting_data       = get_option( 'ced_ali_global_setting_filter', array() );
		$get_filter_setting_post_status = isset( $get_filters_setting_data['ced_ali_default_status'] ) ? $get_filters_setting_data['ced_ali_default_status'] : 'Draft';

		$store_product = array();
		$store_product = get_posts(
			array(
				'numberposts'  => -1,
				'post_type'    => 'product',
				'meta_key'     => 'ced_ali_itemId',
				'meta_value'   => $productId,
				'meta_compare' => '=',
			)
		);
		$tra_res       = get_transient( 'ced_aliexpress_token_value' );
		if ( isset( $tra_res ) && ! empty( $tra_res ) ) {

			$tran = json_decode( $tra_res, true );
		} else {
			$response = $this->ced_import_from_scrapping( $productId );
			if ( $response ) {
				echo json_encode(array(
					'status'  => 200,
					'message' => 'Product Imported Successfully',
				));
				die;
			} 
			echo json_encode(
				array(
					'status'  => 400,
					'message' => 'Token Expired Please reauthorize your account',
				)
			);
			die;
		}
		$store_product = wp_list_pluck( $store_product, 'ID' );
		if ( empty( $store_product ) ) {
			require_once CED_ALIEXPRESS_DIRPATH . 'admin/vendor/dropshippingsdk/TopSdk.php';
			$c            = new TopClient();
			$c->appkey    = '28302878';
			$c->secretKey = '2df683ce13c5944ed5446a360ccdbcc5';
			$req          = new AliexpressPostproductRedefiningFindaeproductbyidfordropshipperRequest();
			$req->setProductId( $productId );
			$resp = $c->execute( $req, $tran['access_token'] );
			$resp = json_decode( json_encode( $resp ), true );
			if (  isset( $resp['result']['error_code'] ) && ! empty( $resp['result']['error_code'] )  ) {
				$response = $this->ced_import_from_scrapping( $productId );
				if ( $response ) {
					echo json_encode(array(
						'status'  => 200,
						'message' => 'Product Imported Successfully',
					));
					die;
				} else {
					echo json_encode(
						array(
							'status'  => 400,
							'message' => $resp['result']['error_message'] ,
						)
					);
					die;
				}
			} elseif ( isset( $resp['result'] ) && ! empty( $resp['result'] ) ) {
				$productData   = $resp['result'];
				$response_data = $this->ced_ali_createProductOnWooStore( $productData, $filterId, $aliexpressparentcategory, $aliexpresssubCategory, $get_filter_setting_post_status, $aliexpresssubproductUrl );

				if ( $response_data ) {
					echo json_encode(
						array(
							'status'  => 200,
							'message' => 'Product Imported Successfully',
						)
					);
					die;
				} else {
					echo json_encode(
						array(
							'status'  => 400,
							'message' => 'Product Not Imported',
						)
					);
					die;
				}
			}
		} else {
			echo json_encode(
				array(
					'status'  => 400,
					'message' => 'Product Already Imported',
				)
			);
			die;
		}

	}

	public function createProductImages( $productId = '', $imageUrls = array(), $append = '') {
		if ( empty( $productId ) ) {
			return false;
		}
		$set_post_thumbnail = false;
		$image_ids          = array();
		foreach ( $imageUrls as $index => $image_url ) {
			$image_name = explode( '/', $image_url );
			$image_name = $image_name[ count( $image_name ) - 1 ];
			if (!empty($append)) {
				$index = $append;
			}
			$upload_dir = wp_upload_dir(); // Set upload folder
			$image_url  = str_replace( 'https', 'http', $image_url );
			$image_data = file_get_contents( $image_url ); // Get image data

			$unique_file_name = wp_unique_filename( $upload_dir['path'], $image_name . $index ); // Generate unique name
			$filename         = basename( $unique_file_name ); // Create image file name
			$filename         = $filename . '.jpeg';

			// Check folder permission and define file location
			if ( wp_mkdir_p( $upload_dir['path'] ) ) {
				$file = $upload_dir['path'] . '/' . $filename;
			} else {
				$file = $upload_dir['basedir'] . '/' . $filename;
			}

			// Create the image  file on the server
			file_put_contents( $file, $image_data );
			copy( $image_url, $file );
			// Check image file type
			$wp_filetype = wp_check_filetype( $filename, null );

			// Set attachment data
			$attachment = array(
				'post_mime_type' => $wp_filetype['type'],
				'post_title'     => sanitize_file_name( $filename ),
				'post_content'   => '',
				'post_status'    => 'inherit',
			);

			// Create the attachment
			$attach_id = wp_insert_attachment( $attachment, $file, $productId );
			// Include image.php
			require_once ABSPATH . 'wp-admin/includes/image.php';

			// Define attachment metadata
			$attach_data = wp_generate_attachment_metadata( $attach_id, $file );

			// Assign metadata to attachment
			wp_update_attachment_metadata( $attach_id, $attach_data );

			// And finally assign featured image to post
			if ( ! $set_post_thumbnail ) {
				set_post_thumbnail( $productId, $attach_id );
				$set_post_thumbnail = true;
			} else {
				$image_ids[] = $attach_id;
			}
			update_post_meta( $productId, '_product_image_gallery', implode( ',', $image_ids ) );
		}
	}

	public function get_aliexpress_products( $result = array(), $productSku = '' ) {

		$sanitized_array          = filter_input_array( INPUT_POST, FILTER_SANITIZE_STRING );
		$productId                = isset( $sanitized_array['productId'] ) ? $sanitized_array['productId'] : '';
		$filterId                 = isset( $sanitized_array['filterId'] ) ? $sanitized_array['filterId'] : '';
		$aliexpressparentcategory = isset( $sanitized_array['parentcategory'] ) ? $sanitized_array['parentcategory'] : '';
		$aliexpresssubCategory    = isset( $sanitized_array['subCategory'] ) ? $sanitized_array['subCategory'] : '';
		$aliexpresssubproductUrl  = isset( $sanitized_array['productUrl'] ) ? $sanitized_array['productUrl'] : '';

		$get_filters_setting_data       = get_option( 'ced_ali_global_setting_filter', array() );
		$get_filter_setting_post_status = isset( $get_filters_setting_data['ced_ali_default_status'] ) ? $get_filters_setting_data['ced_ali_default_status'] : 'Draft';

		$store_product = array();
		$store_product = get_posts(
			array(
				'numberposts'  => -1,
				'post_type'    => 'product',
				'meta_key'     => 'ced_ali_itemId',
				'meta_value'   => $productId,
				'meta_compare' => '=',
			)
		);
		$tra_res       = get_transient( 'ced_aliexpress_token_value' );
		if ( isset( $tra_res ) && ! empty( $tra_res ) ) {

			$tran = json_decode( $tra_res, true );
		} else {
			$response = $this->ced_import_from_scrapping( $productId );
			if ( $response ) {
				echo json_encode(array(
					'status'  => 200,
					'message' => 'Product Imported Successfully',
				));
				die;
			} 
			echo json_encode(
				array(
					'status'  => 400,
					'message' => 'Token Expired Please reauthorize your account',
				)
			);
			die;
		}

		$store_product = wp_list_pluck( $store_product, 'ID' );
		if ( empty( $store_product ) ) {
			require_once CED_ALIEXPRESS_DIRPATH . 'admin/vendor/dropshippingsdk/TopSdk.php';
			$c            = new TopClient();
			$c->appkey    = '28302878';
			$c->secretKey = '2df683ce13c5944ed5446a360ccdbcc5';
			$req          = new AliexpressPostproductRedefiningFindaeproductbyidfordropshipperRequest();
			$req->setProductId( $productId );
			$resp = $c->execute( $req, $tran['access_token'] );
			$resp = json_decode( json_encode( $resp ), true );
			if (  isset( $resp['result']['error_code'] ) && ! empty( $resp['result']['error_code'] )  ) {
				$response = $this->ced_import_from_scrapping( $productId );
				if ( $response ) {
					echo json_encode(array(
						'status'  => 200,
						'message' => 'Product Imported Successfully',
					));
					die;
				} else {
					echo json_encode(
						array(
							'status'  => 400,
							'message' => $resp['result']['error_message'] ,
						)
					);
					die;
				}
			} elseif ( isset( $resp['result'] ) && ! empty( $resp['result'] ) ) {
				$productData = $resp['result'];
				$response    = $this->ced_ali_createProductOnWooStore( $productData, $filterId, $aliexpressparentcategory, $aliexpresssubCategory, $get_filter_setting_post_status, $aliexpresssubproductUrl );

				if ( $response ) {
					echo json_encode(
						array(
							'status'  => 200,
							'message' => ' Product Imported Successfully',
						)
					);
					die;
				} else {
					echo json_encode(
						array(
							'status'  => 400,
							'message' => 'Product Not Imported',
						)
					);
					die;
				}
			} else {
				echo json_encode(
					array(
						'status'  => 400,
						'message' => 'Result not found',
					)
				);
				die;
			}
		}
		echo json_encode(
			array(
				'status'  => 400,
				'message' => 'Something went wrong',
			)
		);

		wp_die();

	}
	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Aliexpress_Dropshipping_For_Woocommerce_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Aliexpress_Dropshipping_For_Woocommerce_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( 'ced-boot-css', 'https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css', array(), '2.0.0', 'all' );

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/aliexpress-dropshipping-for-woocommerce-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Aliexpress_Dropshipping_For_Woocommerce_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Aliexpress_Dropshipping_For_Woocommerce_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/aliexpress-dropshipping-for-woocommerce-admin.js', array( 'jquery' ), $this->version, false );
		$ajax_nonce     = wp_create_nonce( 'ced-ali-ajax-seurity-string' );
		$localize_array = array(
			'ajax_url'   => admin_url( 'admin-ajax.php' ),
			'ajax_nonce' => $ajax_nonce,
		);
		wp_localize_script( $this->plugin_name, 'Ced_Aliexpress_action_handler', $localize_array );
	}

	/**
	 * Aliexpress_dropshipping_for_woocommerce_admin ced_aliexpress_add_menus.
	 *
	 * @since 1.0.0
	 */
	public function ced_aliexpress_add_menus() {
		global $submenu;
		if ( empty( $GLOBALS['admin_page_hooks']['cedcommerce-integrations'] ) ) {
			add_menu_page( __( 'CedCommerce', 'aliexpress-dropshipping-for-woocommerce' ), __( 'CedCommerce', 'aliexpress-dropshipping-for-woocommerce' ), 'manage_woocommerce', 'cedcommerce-integrations', array( $this, 'ced_marketplace_listing_page' ), plugins_url( 'aliexpress-dropshipping-for-woocommerce/admin/images/logo1.png' ), 12 );
			$menus = apply_filters( 'ced_add_marketplace_menus_array', array() );
			if ( is_array( $menus ) && ! empty( $menus ) ) {
				foreach ( $menus as $key => $value ) {
					add_submenu_page( 'cedcommerce-integrations', $value['name'], $value['name'], 'manage_woocommerce', $value['menu_link'], array( $value['instance'], $value['function'] ) );
				}
			}
		}
	}

	/**
	 * Aliexpress_dropshipping_for_woocommerce_admin ced_marketplace_listing_page.
	 *
	 * @since 1.0.0
	 */
	public function ced_marketplace_listing_page() {
		$active_marketplaces = apply_filters( 'ced_add_marketplace_menus_array', array() );
		if ( is_array( $active_marketplaces ) && ! empty( $active_marketplaces ) ) {
			require CED_ALIEXPRESS_DIRPATH . 'admin/partials/marketplaces.php';
		}
	}

	/**
	 * Aliexpress_dropshipping_for_woocommerce_admin ced_aliexpress_add_marketplace_menus_to_array.
	 *
	 * @since 1.0.0
	 * @param array $menus Marketplace menus.
	 */
	public function ced_aliexpress_add_marketplace_menus_to_array( $menus = array() ) {
		$menus[] = array(
			'name'            => 'Aliexpress',
			'slug'            => 'aliexpress-dropshipping-for-woocommerce',
			'menu_link'       => 'ced_aliexpress',
			'instance'        => $this,
			'function'        => 'ced_aliexpress_accounts_page',
			'card_image_link' => CED_ALIEXPRESS_URL . 'admin/images/aliexpress.png',
		);
		return $menus;
	}


		/**
		 * Aliexpress_dropshipping_for_woocommerce_admin ced_aliexpress_accounts_page.
		 *
		 * @since 1.0.0
		 */
	public function ced_aliexpress_accounts_page() {
		$file_accounts = CED_ALIEXPRESS_DIRPATH . 'admin/partials/class-ced-aliexpress-configuration-details.php';
		if ( isset( $_GET['section'] ) ) {
			include_once CED_ALIEXPRESS_DIRPATH . 'admin/partials/' . sanitize_text_field( $_GET['section'] ) . '.php';
		} elseif ( true ) {
			include_once CED_ALIEXPRESS_DIRPATH . 'admin/partials/ced-ali-hotselling-product-view.php';
		} else {
			do_action( 'ced_aliexpress_license_panel' );
		}
	}

	/**
	 * Aliexpress_dropshipping_for_woocommerce_admin ced_aliexpress_license_panel.
	 *
	 * @since 1.0.0
	 */
	public function ced_aliexpress_license_panel() {
		$file_license = CED_ALIEXPRESS_DIRPATH . 'admin/partials/ced-aliexpress-license.php';
		if ( file_exists( $file_license ) ) {
			include_once $file_license;
		}
	}
	/**
	 * Aliexpress_dropshipping_for_woocommerce_admin ced_ali_sync_inventory.
	 *
	 * @since 1.0.0
	 */
	public function ced_ali_sync_inventory() {
		$product_ids = get_option( 'ced_ali_products_to_be_sync', array() );
		if ( empty( $product_ids ) ) {
			$all_product_ids = get_posts(
				array(
					'numberposts' => -1,
					'post_type'   => 'product',
					'meta_query'  => array(
						array(
							'key'     => 'ced_ali_itemId',
							'compare' => 'EXISTS',
						),
					),
					'fields'      => 'ids',
				)
			);
			if ( is_array( $all_product_ids ) && ! empty( $all_product_ids ) ) {
				$product_ids = array_chunk( $all_product_ids, 5 );
			}
		}
		$tran    =array();
		$tra_res = get_transient( 'ced_aliexpress_token_value' );
		if ( isset( $tra_res ) && ! empty( $tra_res ) ) {

			$tran = json_decode( $tra_res, true );
		} 
		if ( isset( $product_ids[0] ) && ! empty( $product_ids ) ) {
			foreach ( $product_ids[0] as $key => $product_id ) {
				$aliexpress_productid = get_post_meta( $product_id, 'ced_ali_itemId', true );

				require_once CED_ALIEXPRESS_DIRPATH . 'admin/vendor/dropshippingsdk/TopSdk.php';
				$c            = new TopClient();
				$c->appkey    = '28302878';
				$c->secretKey = '2df683ce13c5944ed5446a360ccdbcc5';
				$req          = new AliexpressPostproductRedefiningFindaeproductbyidfordropshipperRequest();
				$req->setProductId( $aliexpress_productid );
				if ( isset($tran['access_token']) ) {
					$resp = $c->execute( $req, $tran['access_token'] );
					$resp = json_decode( json_encode( $resp ), true );
				}
				if ( isset($resp['result']['error_code']) || !$tra_res ) {
					require_once CED_ALIEXPRESS_DIRPATH . 'admin/scraping/xgo.php';
					$xgo   = new Xgo();
					$value = $xgo->ProductDetailsFromUrl($aliexpress_productid);
					if (!isset($value['data'])) {
						continue;
					}
					$this->update_inventory_using_chrome_data( $value['data'] , $product_id);
				} elseif ( isset($resp['result']['aeop_ae_product_s_k_us']) ) {
					$this->CedAliUpdateProductData( $resp, $product_id );
				}

				$_product = wc_get_product( $product_id );
				$_product->save();
			}
			unset($product_ids[0]);
			$product_ids = array_values($product_ids);
			update_option('ced_ali_products_to_be_sync', $product_ids);
		}

	}
	public function ced_update_inventory_using_action() {
		$check_ajax = check_ajax_referer( 'ced-ali-ajax-seurity-string', 'ajax_nonce' );
		if ( $check_ajax ) {
			$ali_product_id = isset( $_POST['ali_product_id'] ) ?sanitize_text_field( $_POST['ali_product_id'] ) : 0;
			$post_id        = isset( $_POST['post_id'] ) ?sanitize_text_field( $_POST['post_id'] ) : 0;
			if (!empty($ali_product_id)) {
				require_once CED_ALIEXPRESS_DIRPATH . 'admin/scraping/xgo.php';
				$xgo   = new Xgo();
				$value = $xgo->ProductDetailsFromUrl($ali_product_id);
				if (isset($value['data'])) {
					$this->update_inventory_using_chrome_data( $value['data'] , $ali_product_id );
					$_product = wc_get_product( $post_id );
					$_product->save();
				}
			}
			wp_die();
		}
	}

	public function update_inventory_using_chrome_data( $chrome_product_data = array(), $product_id = 0 ) {
		if (!empty($chrome_product_data)) {
			$variations = isset( $chrome_product_data['variants'] ) ? $chrome_product_data['variants'] : array();
			if ( count($variations) > 1 ) {
				foreach ($variations as $key => $variation) {
					$pro_id = wc_get_product_id_by_sku( $variation['sku_id'] );
					if ( $pro_id ) {
						$this->ced_update_inventory( $pro_id , $variation );
					}
				}
			} else {
				$pro_id = wc_get_product_id_by_sku( $product_id );
				if ( $pro_id ) {
					$simplechromeProductData = $variations[0];
					$this->ced_update_inventory( $pro_id , $simplechromeProductData );
				}
			}
		}
	}

	public function ced_update_inventory( $pro_id = 0, $inventory_data = array() ) {

		if ( isset( $inventory_data['avalaibility'] ) ) {
			if ( $inventory_data['avalaibility'] > 0 ) {
				update_post_meta( $pro_id, '_stock_status', 'instock' );
				update_post_meta( $pro_id, '_manage_stock', 'yes' );
				update_post_meta( $pro_id, '_stock', $inventory_data['avalaibility'] );
			} else {
				update_post_meta( $pro_id, '_stock_status', 'outofstock' );
				update_post_meta( $pro_id, '_stock', 0 );
			}
		}

		$simplePrice = $inventory_data['calculated_price'];
		$sale_price  = $inventory_data['sale_price'];
		$final_price = $this->SetPriceMarkup( '', $pro_id, $simplePrice );
		update_post_meta( $pro_id, '_price', $final_price );
		update_post_meta( $pro_id, '_regular_price', $final_price );
		if (!empty($sale_price)) {
			$sale_price = $this->SetPriceMarkup( '', $pro_id, $sale_price );
			update_post_meta( $pro_id, '_price', $sale_price );
			update_post_meta( $pro_id, '_sale_price', $sale_price );
		}

	}

	public function CedAliUpdateProductData( $productData, $product_id ) {
		$product_data = isset( $productData['result']['aeop_ae_product_s_k_us']['aeop_ae_product_sku'] ) ? $productData['result']['aeop_ae_product_s_k_us']['aeop_ae_product_sku'] : array();
		if ( isset( $product_data[0] ) ) {
			foreach ( $product_data as $key => $value ) {
				if ( isset( $value['sku_code'] ) ) {
					$variation_id = wc_get_product_id_by_sku( $value['sku_code'] );
					if ( $variation_id ) {
						$variation_price = isset( $value['sku_price'] ) ? $value['sku_price'] : '';
						$variation_price = $this->SetPriceMarkup( '', $variation_id, $variation_price );

						update_post_meta( $product_id, '_price', $variation_price );
						update_post_meta( $product_id, '_regular_price', $variation_price );

						$offer_price = isset( $value['offer_sale_price'] ) ? $value['offer_sale_price'] : '';
						
						if (!empty($offer_price )) {
							$offer_price = $this->SetPriceMarkup( '', $variation_id, $offer_price );
							update_post_meta( $variation_id, '_price', $offer_price );
							update_post_meta( $variation_id, '_sale_price', $offer_price );
						}

						$stock = isset( $value['s_k_u_available_stock'] ) ? $value['s_k_u_available_stock'] : '';

						if ( $stock > 0 ) {
							update_post_meta( $variation_id, '_stock_status', 'instock' );
							update_post_meta( $variation_id, '_manage_stock', 'yes' );
							update_post_meta( $variation_id, '_stock', $stock );
						} else {
							update_post_meta( $variation_id, '_stock_status', 'outofstock' );
							update_post_meta( $variation_id, '_stock', 0 );
						}
					}
				}
			}
		} else {
			$price_data = $productData['result']['aeop_ae_product_s_k_us']['aeop_ae_product_sku'];
			$price      = isset( $price_data['sku_price'] ) ? $price_data['sku_price'] : '';
			$price      = $this->SetPriceMarkup( '', $product_id, $price );

			update_post_meta( $product_id, '_price', $price );
			update_post_meta( $product_id, '_regular_price', $price );

			$offer_price = isset( $price_data['offer_sale_price'] ) ? $price_data['offer_sale_price'] : '';
			if (empty($offer_price)) {
				$offer_price = isset( $productData['result']['item_offer_site_sale_price'] ) ? $productData['result']['item_offer_site_sale_price'] : '';
			}
			if (!empty($offer_price )) {
				$offer_price = $this->SetPriceMarkup( '', $product_id, $offer_price );
				update_post_meta( $product_id, '_price', $offer_price );
				update_post_meta( $product_id, '_sale_price', $offer_price );
			}
			$stock = isset( $productData['result']['aeop_ae_product_s_k_us']['aeop_ae_product_sku']['s_k_u_available_stock'] ) ? $productData['result']['aeop_ae_product_s_k_us']['aeop_ae_product_sku']['s_k_u_available_stock'] : '';

			if ( $stock > 0 ) {
				update_post_meta( $product_id, '_stock_status', 'instock' );
				update_post_meta( $product_id, '_manage_stock', 'yes' );
				update_post_meta( $product_id, '_stock', $stock );
			} else {
				update_post_meta( $product_id, '_stock_status', 'outofstock' );
				update_post_meta( $product_id, '_stock', 0 );
			}
		}

	}

	public function get_aliexpress_auto_import() {
		$check_ajax = check_ajax_referer( 'ced-ali-ajax-seurity-string', 'ajax_nonce' );
		if ( $check_ajax ) {
			$filterId 		  = isset($_POST['filterId']) ? sanitize_text_field( $_POST['filterId'] ) : '';
			$numberOfProducts = isset( $_POST['numberOfProducts'] ) ? sanitize_text_field( $_POST['numberOfProducts'] ) : array();
			if ( '' != $filterId ) {
				$get_option_autoimport                               = get_option( 'ced_ali_auto_import', array() );
				$get_option_autoimport[$filterId]['numberOfProduct'] = $numberOfProducts;
				$get_option_autoimport[$filterId]['filter_id']       = $filterId;
				update_option( 'ced_ali_auto_import', $get_option_autoimport );
			}
			echo json_encode(
				array(
					'status'  => 200,
					'message' => 'Product imported in queue',
				)
			);
			die;
		}
	}
	public function ced_ali_productid_to_be_imported() {
		
		$current_filter = get_option( 'ced_ali_current_filter', array() );

		if (empty($current_filter)) {

			$filter = get_option( 'ced_ali_auto_import', array() );

			if (empty($filter)) {
				return;
			}
			$current_filter = array_chunk($filter, 1);
		}

		if (isset($current_filter[0]) && is_array($current_filter[0])) {
			$filter_id          = $current_filter[0][0]['filter_id'];
			$use_current_filter = true;
			$current_param      = get_option('ced_ali_current_parameter', array());
			$page               = isset($current_param[$filter_id]['page_no']) ? $current_param[$filter_id]['page_no'] : '';
			if (empty($page)) {
				$page = 1;
			}
			
			$numberOfProduct = $current_filter[0][0]['numberOfProduct'];

			$get_filter  = get_option( 'ced_ali_filter', array() );
			$filter_data = $get_filter[ $filter_id ];
			$file        = CED_ALIEXPRESS_DIRPATH . 'admin/vendor/Ced_ali_get_product.php';
			if ( include_once $file ) {
				$filterData = array(
					'name'      => $filter_data['ced_ali_name'],
					'keyword'   => $filter_data['ced_ali_keyword_name'],
					'min_price' => $filter_data['ced_ali_min_price'],
					'max_price' => $filter_data['ced_ali_max_price'],

					'cat'       => $filter_data['category_id'],
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
				->setpagenumber( $page )
				->prepareAll()
				->getProducts();
				$products = $bunch->getProducts();

				$count = isset($current_param[$filter_id]['count']) ? $current_param[$filter_id]['count'] : '';



				if (isset($products->result['result']['products']) && !empty($products->result['result']['products'])) {

					$count       = (int) $count + count($products->result['result']['products']);
					$productData = $products->result['result']['products'];

					
					$auto_import_productid = get_option('ced_ali_product_tobe_auto_import', array());

					$product_ids =array();
					foreach ($productData as $key => $value) {
						if (CedIfProductExist($value['productId'])) {

							continue;
						}
						$product_ids[] = $value['productId'];

					}
					++$page;

					if (isset($current_param[$filter_id]['product_ids'] )&& !empty($current_param[$filter_id]['product_ids'])) {
						$filter_product_ids = $current_param[$filter_id]['product_ids'];

						$filter_product_ids = array_merge($filter_product_ids, $product_ids);
					} else {
						$filter_product_ids =$product_ids;
					}
					$current_param[$filter_id]['page_no']       = $page;
					$current_param[$filter_id]['count']         = $count;
					$current_param[$filter_id]['total_product'] = $numberOfProduct;
					$current_param[$filter_id]['product_ids']   = $filter_product_ids;

					update_option('ced_ali_current_parameter', $current_param);


					if ($count >= (int) $numberOfProduct) {

						$use_current_filter = false;
						
					} 
					$auto_import_productid = array_merge($auto_import_productid, $product_ids);
					$auto_import_productid = array_unique($auto_import_productid);

					update_option('ced_ali_product_tobe_auto_import', $auto_import_productid);
				} else {
					$use_current_filter = false;
				}
			}
		}
		if (!$use_current_filter) {
			unset($current_filter[0]);
			$filter = get_option( 'ced_ali_auto_import', array() );
			unset($filter[$filter_id]);
			update_option('ced_ali_auto_import', $filter);
			$current_filter = array_values($current_filter);
		}
		update_option('ced_ali_current_filter', $current_filter);
	}
	public function ced_ali_auto_import_product() {

		$current_param = get_option('ced_ali_current_parameter', array());

		$chunk_product = get_option('ced_ali_chunk_to_import', array());

		if (empty($chunk_product)) {

			$auto_import_productid = get_option('ced_ali_product_tobe_auto_import', array());
			$auto_import_productid = array_unique($auto_import_productid);
			$chunk_product 		   = array_chunk($auto_import_productid, 3);
		}
		$tran    = array();
		$tra_res = get_transient( 'ced_aliexpress_token_value' );
		if ( isset( $tra_res ) && ! empty( $tra_res ) ) {

			$tran = json_decode( $tra_res, true );
		} 

		if (isset($chunk_product[0]) && !empty ($chunk_product[0])) {

			foreach ($chunk_product[0] as $key => $value) {
				$filter_id_to_use ='';
				foreach ($current_param as $filter_id => $filter_data) {

					$id = in_array($value, $filter_data['product_ids']);
					if ($id) {
						$filter_id_to_use = $filter_id;
						break;
					}
				}
				if (CedIfProductExist( $value )) {

					continue;
				}

				$get_filters_global             = get_option( 'ced_ali_global_setting_filter', array() ); 
				$get_filter                     = get_option( 'ced_ali_filter', array() );
				$filter_data                    = $get_filter[ $filter_id_to_use ];
				$filterId                       = $filter_id_to_use; 
				$category_value             	= explode( '->', $filter_data['category_name'] );
				$aliexpressparentcategory   	= $category_value[0];
				$aliexpresssubCategory      	= $category_value[1];
				$get_filter_setting_post_status = $get_filters_global['ced_ali_default_status'];
				$aliexpresssubproductUrl        = '';

				require_once CED_ALIEXPRESS_DIRPATH . 'admin/vendor/dropshippingsdk/TopSdk.php';
				$c            = new TopClient();
				$c->appkey    = '28302878';
				$c->secretKey = '2df683ce13c5944ed5446a360ccdbcc5';
				$req          = new AliexpressPostproductRedefiningFindaeproductbyidfordropshipperRequest();
				$req->setProductId( $value );
				$resp = $c->execute( $req, $tran['access_token'] );
				$resp = json_decode( json_encode( $resp ), true );
				if (isset($resp['result']['error_code']) || !$tra_res) {
					$response = $this->ced_import_from_scrapping( $value );
				} elseif ( $resp['result'] ) {
					$productData = $resp['result'];

					$response_data = $this->ced_ali_createProductOnWooStore( $productData, $filterId, $aliexpressparentcategory, $aliexpresssubCategory, $get_filter_setting_post_status, $aliexpresssubproductUrl );
				}
			}
			unset($chunk_product[0]);
			$chunk_product = array_values($chunk_product);
			update_option('ced_ali_chunk_to_import', $chunk_product);

		}
	}

	public function ced_schedules() {
		$filter = get_option( 'ced_ali_auto_import', array() );
		if ( ! empty( $filter ) ) {
			$isShceduled = wp_get_schedule( 'ced_ali_productid_to_be_imported' );
			if ( ! $isShceduled ) {
				wp_schedule_event( time(), 'ced_ali_6min', 'ced_ali_productid_to_be_imported' );
			}
		} else {
			wp_clear_scheduled_hook( 'ced_ali_productid_to_be_imported' );
		}

		if ( ! wp_get_schedule( 'ced_ali_chrome_data_import' ) ) {
			wp_schedule_event( time(), 'ced_ali_2min', 'ced_ali_chrome_data_import' );
		} 

	}

	public function ced_ali_chrome_data_import() {
		$chrome_data_to_import = get_option('ced_ali_chrome_data_import' , array());
		if (empty($chrome_data_to_import)) {
			$chrome_data_json_file     = WP_CONTENT_DIR . '/ced-ali-chrome-data.json';
			$chrome_data_json_file_put = '';
			if ( file_exists( $chrome_data_json_file ) ) {
				$chrome_data_json_file_put = file_get_contents( $chrome_data_json_file );
				$chrome_data_json_file_put = json_decode( $chrome_data_json_file_put, true );
			}
			if ( empty( $chrome_data_json_file_put) ) {
				return false;
			}
			$chrome_data_to_import = array_chunk($chrome_data_json_file_put, 1);
			file_put_contents($chrome_data_json_file, '');
		} else {
			$chrome_data_to_import = json_decode($chrome_data_to_import, true);			
		}
		if (isset($chrome_data_to_import[0]) && !empty($chrome_data_to_import[0])) {
			foreach ($chrome_data_to_import[0] as $key => $data) {
				$aliexpress_productid = $data['product_id'];
				$this->ced_import_from_scrapping( $aliexpress_productid );
			}
			unset($chrome_data_to_import[0]);
			$chrome_data_to_import = array_values($chrome_data_to_import);
			if (empty($chrome_data_to_import)) {
				update_option('ced_ali_chrome_data_import', array());
			} else {
				update_option('ced_ali_chrome_data_import', json_encode($chrome_data_to_import));
			}
		}
	}

	public function ced_import_from_scrapping( $aliexpress_productid = 0 ) {
		if (CedIfProductExist( $aliexpress_productid )) {
			return true;
		}
		require_once CED_ALIEXPRESS_DIRPATH . 'admin/scraping/xgo.php';
		$xgo   = new Xgo();
		$value = $xgo->ProductDetailsFromUrl($aliexpress_productid);
		if (!isset($value['data'])) {
			return false;
		}

		$value           = $value['data'];
		$description_url = isset($value['description_url']) ? urldecode($value['description_url']) : '';
		$description     = '';
		if (!empty($description_url)) {
			$description = file_get_contents($description_url);
		}
		if (empty($description)) {
			$description = isset( $value['short_description'] ) ? $value['short_description'] : '';				
		}

		$chrome_wooProductId = wp_insert_post(
			array(
				'post_title'   => isset( $value['name'] ) ? $value['name'] : 'Aliexpress Product - ' . $key,
				'post_status'  => 'publish',
				'post_type'    => 'product',
				'post_content' => $description,
			)
		);
		if ( empty( $chrome_wooProductId ) ) {
			return false;
		}

		$imageUrls = isset( $value['all_images'] ) ? $value['all_images']  : array();

		if ( ! empty( $imageUrls ) ) {
			$this->createProductImages( $chrome_wooProductId, $imageUrls );
		}
		update_post_meta( $chrome_wooProductId, 'ced_ali_last_updated', gmdate( 'd/m/Y' ) );
		update_post_meta( $chrome_wooProductId, 'ced_ali_productData', $value );
		update_post_meta( $chrome_wooProductId , 'ced_ali_itemId' , $aliexpress_productid );
		update_post_meta( $chrome_wooProductId , '_sku' , $aliexpress_productid );

		if ( isset( $value['specifications'] ) && ! empty( $value['specifications'] ) ) {
			$attributes = $value['specifications'];
			$this->createchromeProductAttributes( $chrome_wooProductId, $attributes );
		}
		
		if ( isset( $value['variants'][0] ) && count($value['variants']) > 1 ) {
			wp_set_object_terms( $chrome_wooProductId, 'variable', 'product_type' );
			$variablechromeProductData = $value['variants'];
			$this->createAsVariablechromeProduct( $chrome_wooProductId, $variablechromeProductData );

			update_post_meta($chrome_wooProductId, '_stock_status', 'instock');
			$_product = wc_get_product($chrome_wooProductId);
			$_product->save();
		} else {
			wp_set_object_terms( $chrome_wooProductId, 'simple', 'product_type' );
			$simplechromeProductData = $value['variants'][0];
			$this->createAsSimplechromeProduct( $chrome_wooProductId, $simplechromeProductData );
		}


		$category = isset($value['keyword']) ? explode(',', $value['keyword']) : array();
		$category = isset($category[0]) ? $category[0] : '';
		if (!empty($category)) {
			$term = wp_insert_term(
				$category,
				'product_cat',
				array(
					'description' => $category,
					'parent'      => '',
				)
			);

			if ( isset( $term->error_data['term_exists'] ) ) {

				$term_id = $term->error_data['term_exists'];

			} elseif ( isset( $term['errors'] ) ) {
				$term_id = false;
			} elseif ( isset( $term['term_id'] ) ) {

				$term_id = $term['term_id'];
			}

			if ( isset( $term_id ) && ! empty( $term_id ) ) {

				wp_set_object_terms( $chrome_wooProductId, $term_id, 'product_cat' );

			}
		}
		if ( $chrome_wooProductId ) {
			return $chrome_wooProductId;
		}
		return false;

	}

	public function createchromeProductAttributes( $chrome_wooProductId = '', $attributes = array() ) {

		if ( empty( $chrome_wooProductId ) || empty( $attributes ) ) {
			return false;
		}

		$product_attributes = array();

		foreach ( $attributes as $key => $value ) {
			if ( isset( $value['attr_name'] ) && isset( $value['attr_value'] ) ) {
				$product_attributes[ $key ] = array(
					'name'        => htmlspecialchars( stripslashes( $value['attr_name'] ) ),
					'value'       => $value['attr_value'],
					'is_visible'  => 1,
					'is_taxonomy' => 0,
				);
			}
		}

		if ( ! empty( $product_attributes ) ) {
			$thedata = get_post_meta( $chrome_wooProductId, '_product_attributes', true );
			if ( empty( $thedata ) ) {
				$thedata = array();
			}
			update_post_meta( $chrome_wooProductId, '_product_attributes', array_merge( $thedata, $product_attributes ) );
		}

	}

	public function createAsSimplechromeProduct( $chrome_wooProductId = '', $simplechromeProductData = array() ) {
		if ( empty( $chrome_wooProductId ) || empty( $simplechromeProductData ) ) {
			return false;
		}

		if ( isset( $simplechromeProductData['avalaibility'] ) ) {
			if ( $simplechromeProductData['avalaibility'] > 0 ) {
				update_post_meta( $chrome_wooProductId, '_stock_status', 'instock' );
				update_post_meta( $chrome_wooProductId, '_manage_stock', 'yes' );
				update_post_meta( $chrome_wooProductId, '_stock', $simplechromeProductData['avalaibility'] );
			} else {
				update_post_meta( $chrome_wooProductId, '_stock_status', 'outofstock' );
				update_post_meta( $chrome_wooProductId, '_stock', 0 );
			}
		}


		$simplePrice = $simplechromeProductData['calculated_price'];
		$sale_price  = isset($simplechromeProductData['sale_price']) ? $simplechromeProductData['sale_price'] : 0;
		$final_price = $this->SetPriceMarkup( '', $chrome_wooProductId, $simplePrice );
		update_post_meta( $chrome_wooProductId, '_price', $final_price );
		update_post_meta( $chrome_wooProductId, '_regular_price', $final_price );
		if (!empty($sale_price)) {
			$sale_price = $this->SetPriceMarkup( '', $chrome_wooProductId, $sale_price );
			update_post_meta( $chrome_wooProductId, '_price', $sale_price );
			update_post_meta( $chrome_wooProductId, '_sale_price', $sale_price );
		}

		update_post_meta( $chrome_wooProductId, '_ced_ali_sku_id', $simplechromeProductData['sku_attr'] );
	}


	public function createAsVariablechromeProduct( $chrome_wooProductId = '', $variablechromeProductData = array()) {

		
		if ( empty( $chrome_wooProductId ) || empty( $variablechromeProductData ) ) {
			return false;
		}

		$variationAttributes     = array();
		$attributes              = array();
		$variationAttributeName  = array();
		$variationAttributeValue = array();
		$data                    = array();


		foreach ( $variablechromeProductData as $key => $attributeProperties ) {

			if ( ! empty( $attributeProperties['linked_data']['0'] ) ) {
				foreach ( $attributeProperties['linked_data'] as $key1 => $value1 ) {
					$variationAttributeValue[ $value1['propertyName'] ][] = $value1['l_data']['name'];
				}
			}
		}
		if ( ! empty( $variationAttributeValue ) ) {
			$count = 1;
			foreach ( $variationAttributeValue as $key => $value ) {
				$used_for_variation           = 1;
				$values                       = array_unique( $value );
				$values                       = array_values( $values );
				$key                          = sanitize_title($key);
				$key                          = trim( $key );
				$data['attribute_names'][]    = $key;
				$data['attribute_position'][] = $count;
				$ImplodeValues                = array();
				foreach ( $values as $key => $valuev ) {
					$trimValue       = trim( $valuev );
					$value1          = str_replace( '_', ' ', $trimValue );
					$value1          = trim($value1);
					$ImplodeValues[] = $value1;
				}
				$data['attribute_values'][]     = implode( '|', $ImplodeValues );
				$data['attribute_visibility'][] = 1;
				$data['attribute_variation'][]  = $used_for_variation;
				++$count;                          
			}


			if ( isset( $data['attribute_names'], $data['attribute_values'] ) ) {
				$attribute_names         = $data['attribute_names'];
				$attribute_values        = $data['attribute_values'];
				$attribute_visibility    = isset( $data['attribute_visibility'] ) ? $data['attribute_visibility'] : array();
				$attribute_variation     = isset( $data['attribute_variation'] ) ? $data['attribute_variation'] : array();
				$attribute_position      = $data['attribute_position'];
				$attribute_names_max_key = max( array_keys( $attribute_names ) );
				for ( $i = 0; $i <= $attribute_names_max_key; $i++ ) {
					if ( empty( $attribute_names[ $i ] ) || ! isset( $attribute_values[ $i ] ) ) {
						continue;
					}
					$attribute_id   = 0;
					$attribute_name = wc_clean( $attribute_names[ $i ] );
					if ( 'pa_' === substr( $attribute_name, 0, 3 ) ) {
						$attribute_id = wc_attribute_taxonomy_id_by_name( $attribute_name );
					}
					$options = isset( $attribute_values[ $i ] ) ? $attribute_values[ $i ] : '';
					if ( is_array( $options ) ) {
						// Term ids sent as array.
						$options = wp_parse_id_list( $options );
					} else {
						$options = wc_get_text_attributes( $options );
					}

					if ( empty( $options ) ) {
						continue;
					}
					$attribute = new WC_Product_Attribute();
					$attribute->set_id( $attribute_id );
					$attribute->set_name( $attribute_name );
					$attribute->set_options( $options );
					$attribute->set_position( $attribute_position[ $i ] );
					$attribute->set_visible( isset( $attribute_visibility[ $i ] ) );
					$attribute->set_variation( isset( $attribute_variation[ $i ] ) );
					$attributes[] = $attribute;
				}
			}
			$product_type = 'variable';
			$classname    = WC_Product_Factory::get_product_classname( $chrome_wooProductId, $product_type );
			$product      = new $classname( $chrome_wooProductId );
			$product->set_attributes( $attributes );
			$product->save();

			$attributeProperties = array();
			$thedata             = array();

			foreach ( $variablechromeProductData as $key => $variation ) {
				$sku_code       = isset( $variation['sku_id'] ) ? $variation['sku_id'] : '';
				$variation_post = array(
					'post_title'  => 'Variation - ' . $sku_code,
					'post_name'   => 'product-' . $chrome_wooProductId . '-variation-' . $key,
					'post_status' => 'publish',
					'post_parent' => $chrome_wooProductId,
					'post_type'   => 'product_variation',
					'guid'        => home_url() . '/?product_variation=product-' . $chrome_wooProductId . '-variation-' . $key,
				);

				$variation_post_id = wp_insert_post( $variation_post );

				/*assign variation image*/
				$image = isset($variation['linked_data']['0']['l_data']['image']) ? array($variation['linked_data']['0']['l_data']['image']) : array();
				if (!empty($image)) {
					$this->createProductImages( $variation_post_id, $image , time() );
				}

				if ( ! empty( $variation['linked_data']['0'] ) ) {
					foreach ( $variation['linked_data'] as $key1 => $value1 ) {
						$variationAttributeName[ $value1['propertyName']] = $value1['l_data']['name'];
						wp_set_object_terms( $variation_post_id, $value1['l_data']['name'], $value1['propertyName'] );
						$attribute =  trim( sanitize_title($value1['propertyName']) );
						update_post_meta( $variation_post_id, 'attribute_' . $attribute, $value1['l_data']['name'] );

						$thedata = array(
							$attribute => array(
								'name'         => $attribute,
								'value'        => $value1['l_data']['name'],
								'is_visible'   => '1',
								'is_variation' => '1',
								'is_taxonomy'  => '1',
							),
						);
						update_post_meta( $variation_post_id, '_product_attributes', $thedata );
					}
				}

				update_post_meta( $variation_post_id, '_manage_stock', 'yes' );
				if ( isset( $variation['inventory'] ) && true == $variation['inventory']) {
					update_post_meta( $variation_post_id, '_stock_status', 'instock' );
					update_post_meta( $variation_post_id, '_stock', $variation['inventory'] );
				}
				$sku_code = isset( $variation['sku_id'] ) ? $variation['sku_id'] : '';
				update_post_meta( $variation_post_id, '_sku', $sku_code );
				update_post_meta( $variation_post_id, '_ced_ali_sku_id', $variation['sku_attr'] );

				$simplePrice = $variation['calculated_price'];
				$sale_price  = isset($variation['sale_price']) ? $variation['sale_price'] : 0;
				$final_price = $this->SetPriceMarkup( '', $variation_post_id, $simplePrice );
				update_post_meta( $variation_post_id, '_price', $final_price );
				update_post_meta( $variation_post_id, '_regular_price', $final_price );

				if (!empty($sale_price)) {
					$sale_price = $this->SetPriceMarkup( '', $variation_post_id, $sale_price );
					update_post_meta( $variation_post_id, '_price', $sale_price );
					update_post_meta( $variation_post_id, '_sale_price', $sale_price );
				}

				$wooVariationProduct = wc_get_product( $variation_post_id );
				$wooVariationProduct->save();
			}

			$wooProduct = wc_get_product( $chrome_wooProductId );
			$wooProduct->save();
		}

	}
}
