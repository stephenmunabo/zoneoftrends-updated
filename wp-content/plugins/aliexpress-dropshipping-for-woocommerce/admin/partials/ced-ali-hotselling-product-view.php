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
if ( isset( $_POST['save_filters_settings_hotselling'] ) ) {

	if ( ! isset( $_POST['filter_hotelling_settings_submit'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['filter_hotelling_settings_submit'] ) ), 'filter_selling_settings' ) ) {
		return;
	}

	$filters                                       = get_option( 'ced_ali_hot_selling_product', array() );
	$sanitized_array                               = filter_input_array( INPUT_POST, FILTER_SANITIZE_STRING );
	$category_value                                = isset( $sanitized_array['ced_ali_hotselling_products']['ced_aliexpress_hotselling_category'] ) ? $sanitized_array['ced_ali_hotselling_products']['ced_aliexpress_hotselling_category'] : '';
	$category_value                                = explode( ',', $category_value );
	$category_id                                   = ! empty( $category_value[0] ) ? $category_value[0] : '200260142';
	$category_name                                 = ! empty( $category_value[1] ) ? $category_value[1] : 'Automobiles & Motorcycles -> Car Wash & Maintenance';
	$filters['category_id']                        = $category_id;
	$filters['ced_aliexpress_hotselling_category'] = $category_name;

	update_option( 'ced_ali_hot_selling_product', $filters );

}
	$filter_data_hotselling = get_option( 'ced_ali_hot_selling_product', array() );
?>
<div class="ced_ali_hot_selling_product_table_wrap">
<form method="post" action="">
	<?php wp_nonce_field( 'filter_selling_settings', 'filter_hotelling_settings_submit' ); ?>
	<div class="ced_ali_hotselling_div_wrap">
		<div class="ced_ali_sub_wrap">
		<div class="ced_heading_wrap">
			<h2><?php esc_html_e( 'Hot Selling Product', 'aliexpress-dropshipping-for-woocommerce' ); ?></h2>
		</div>
		<div class="ced_ali_get_all_category">
			<?php
			$category_json_file = CED_ALIEXPRESS_DIRPATH . 'admin/vendor/lib/json/category.json';
			if ( file_exists( $category_json_file ) ) {
				$category_file_put = file_get_contents( $category_json_file );
				$category_file_put = json_decode( $category_file_put, true );
			}
			?>

			<select name="ced_ali_hotselling_products[ced_aliexpress_hotselling_category]" class="ced_aliexpress_hotselling_category" id="ced_aliexpress_hotselling_category">
			<option value="">--Select category--</option>
			<?php
			$category_data = ! empty( $filter_data_hotselling['category_id'] ) ? $filter_data_hotselling['category_id'] : '200260142';
			if ( is_array( $category_file_put ) ) {
				foreach ( $category_file_put as $key => $category_file_put_value ) {
					?>
					<option value="<?php echo esc_attr( $key . ',' . $category_file_put_value ); ?>"
						<?php
						if ( $category_data == $key ) {
							echo ' selected="selected"';
						}
						?>
						>
						<?php
						echo esc_attr( $category_file_put_value );
						?>
					</option>
					<?php
				}
			}
			?>
			</select>
		</div>
		<div class="ced_ali_hotselling_submit">
			<button type="submit" id="save_filters_settings_hotselling"  name="save_filters_settings_hotselling" class="save_filters_settings_hotselling" ><?php esc_html_e( 'Filter', 'aliexpress-dropshipping-for-woocommerce' ); ?></button>
		</div>
	</div>
	</div>
</form>

<div class="success_notice error_notice">
</div>
	<?php
	$aliexpress_filtersurl_file = CED_ALIEXPRESS_DIRPATH . 'admin/partials/class-aliexpress-hotselling-product.php';
	if ( file_exists( $aliexpress_filtersurl_file ) ) {
		include_once $aliexpress_filtersurl_file;
	}
	?>
</div>
