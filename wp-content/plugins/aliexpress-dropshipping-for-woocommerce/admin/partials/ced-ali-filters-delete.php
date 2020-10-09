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

$get_action = isset( $_GET['action'] ) ? sanitize_text_field( $_GET['action'] ) : '';
if ( 'delete' == $get_action ) {
	$get_filter_id = isset( $_GET['filterId'] ) ? sanitize_text_field( $_GET['filterId'] ) : '';
	if ( '' != $get_filter_id ) {
		$get_filter = get_option( 'ced_ali_filter', array() );
		unset( $get_filter[ $get_filter_id ] );
		update_option( 'ced_ali_filter', $get_filter );
	}
}
$redirect_url = admin_url( 'admin.php?page=ced_aliexpress&section=ced-ali-filters-view' );
header( "Location: $redirect_url" );

