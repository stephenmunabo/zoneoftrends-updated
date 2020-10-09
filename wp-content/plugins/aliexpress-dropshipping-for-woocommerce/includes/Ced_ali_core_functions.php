<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

function CedIfProductExist( $ced_ali_productId = '' ) {
	if ( empty($ced_ali_productId) ) {
		return false;
	}
		$all_product_ids = get_posts(
				array(
					'numberposts' => -1,
					'post_type'   => array('product','product_variation'),
					'meta_query'  => array(
						array(
							'key'     => 'ced_ali_itemId',
							'value'   =>  $ced_ali_productId,
							'compare' => '=',
						),
					),
					'fields'      => 'ids',
				)
			);
	if (!empty($all_product_ids)) {
		return true;
	} else {
		return false;
	}

} 

/**
 * Check WooCommmerce active or not.
 *
 * @since 1.0.0
 */
function ced_aliexpress_check_woocommerce_active() {
	if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
		return true;
	}
	return false;
}
/**
 * This code runs when WooCommerce is not activated,
 *
 * @since 1.0.0
 */
function deactivate_ced_aliexpress_woo_missing() {

	deactivate_plugins( CED_ALIEXPRESS_PLUGIN_BASENAME );
	add_action( 'admin_notices', 'ced_aliexpress_woo_missing_notice' );
	if ( isset( $_GET['activate'] ) ) {
		unset( $_GET['activate'] );
	}
}
/**
 * Callback function for sending notice if woocommerce is not activated.
 *
 * @since 1.0.0
 */
function ced_aliexpress_woo_missing_notice() {

	// translators: %s: search term !!
	echo '<div class="notice notice-error is-dismissible"><p>' . sprintf( esc_html( __( 'AliExpress Dropshipping for WooCommerce requires WooCommerce to be installed and active. You can download %s from here.', 'aliexpress-dropshipping-for-woocommerce' ) ), '<a href="http://www.woothemes.com/woocommerce/" target="_blank">WooCommerce</a>' ) . '</p></div>';
}

if (!function_exists('CedWad_is_aliexpress_product')) {
	function CedWad_is_aliexpress_product( $product_id) {
		$product_id = get_post_meta($product_id, 'ced_ali_itemId', true);
		if (isset($product_id) && !empty($product_id)) {
			return 'true';
		} else {
			return 'false';
		}
	}
}

add_filter( 'default_checkout_billing_country', 'ced_change_default_country' );

function ced_change_default_country() {
	return 'XX'; // country code
}
