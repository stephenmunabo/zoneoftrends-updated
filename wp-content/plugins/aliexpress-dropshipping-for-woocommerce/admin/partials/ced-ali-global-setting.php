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
if ( isset( $_POST['save_globalfilters_settings'] ) ) {
	if ( ! isset( $_POST['global_filter_add_new_settings_submit'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['global_filter_add_new_settings_submit'] ) ), 'global_filter_add_new_settings' ) ) {
		return;
	}
	$sanitized_array = filter_input_array( INPUT_POST, FILTER_SANITIZE_STRING );
	$filters_global  = isset( $sanitized_array ['ced_ali_global_setting'] ) ? $sanitized_array ['ced_ali_global_setting'] : array();

	update_option( 'ced_ali_global_setting_filter', $filters_global );

}
$get_filters_global = get_option( 'ced_ali_global_setting_filter', array() );
?>
<form method="post" action=''>
	<?php wp_nonce_field( 'global_filter_add_new_settings', 'global_filter_add_new_settings_submit' ); ?>
	<div class="filters-wrapper">
		<div class="ced-heading-wrap">
			<h2><?php esc_html_e( 'Setting', 'aliexpress-dropshipping-for-woocommerce' ); ?></h2>
		</div>
		<table class="ced_aliexpress_filters">
			<tbody>
				<tr>
					<th>
						<label class="basic_heading">
						<?php esc_html_e( 'Select Post Status', 'aliexpress-dropshipping-for-woocommerce' ); ?>
						</label>
					</th>
					<?php
					$post_status_data = isset( $get_filters_global['ced_ali_default_status'] ) ? $get_filters_global['ced_ali_default_status'] : '';
					?>
					<td>
						<select name="ced_ali_global_setting[ced_ali_default_status]" class="ced_ali_default_status" id="ced_ali_default_status">
							<option value="">--Select Post Status--</option>
							<option value="draft" <?php selected( $post_status_data, 'draft' ); ?>><?php esc_html_e( 'Draft', 'aliexpress-dropshipping-for-woocommerce' ); ?></option>
							<option value="publish" <?php selected( $post_status_data, 'publish' ); ?>><?php esc_html_e( 'Publish', 'aliexpress-dropshipping-for-woocommerce' ); ?></option>
						</select>
					</td>
				</tr>
		</table>
		<h2><?php esc_html_e( 'Price Markup Settings', 'aliexpress-dropshipping-for-woocommerce' ); ?></h2>
		<table class="ced_aliexpress_price_markup_wrap">
			<?php

			if ( isset( $get_filters_global['ced_ali_global_price_markup'] ) && is_array( $get_filters_global['ced_ali_global_price_markup'] ) && ! empty( $get_filters_global['ced_ali_global_price_markup'] ) ) {
				foreach ( $get_filters_global['ced_ali_global_price_markup'] as $key => $value ) {
					?>
					<tr class="ced_setting_price_markup_row">
					<th>
						<label class="basic_heading">
						<?php esc_html_e( 'Price Range', 'aliexpress-dropshipping-for-woocommerce' ); ?>
						</label>
					</th>
					<td>
						<input placeholder="min price" type="text" name="ced_ali_global_setting[ced_ali_global_price_markup][<?php echo esc_attr( $key ); ?>][ced_ali_min_price_markup]" class="ced_ali_min_price_markup" id="ced_ali_min_price_markup" value="<?php echo isset( $value['ced_ali_min_price_markup'] ) ? esc_attr( $value ['ced_ali_min_price_markup'] ) : ''; ?>">
					</td>
					<td>
						<input placeholder="max price" type="text" name="ced_ali_global_setting[ced_ali_global_price_markup][<?php echo esc_attr( $key ); ?>][ced_ali_max_price_markup]" class="ced_ali_max_price_markup" id="ced_ali_max_price_markup" value="<?php echo isset( $value ['ced_ali_max_price_markup'] ) ? esc_attr( $value['ced_ali_max_price_markup'] ) : ''; ?>">
					</td>
					<td>
						<?php
						$markup_data = isset( $value ['ced_aliexpress_price_markup'] ) ? $value ['ced_aliexpress_price_markup'] : '';
						?>
						<select name="ced_ali_global_setting[ced_ali_global_price_markup][<?php echo esc_attr( $key ); ?>][ced_aliexpress_price_markup]" class="ced_aliexpress_price_markup" id="ced_aliexpress_price_markup">
							<option value="">--Select Markup--</option>
							<option value="fixed_price_markup" <?php selected( $markup_data, 'fixed_price_markup' ); ?>><?php esc_html_e( 'Fixed Price Markup', 'aliexpress-dropshipping-for-woocommerce' ); ?></option>
							<option value="percantage_increase" <?php selected( $markup_data, 'percantage_increase' ); ?>><?php esc_html_e( 'Percantage Increase', 'aliexpress-dropshipping-for-woocommerce' ); ?></option>
						</select>
					</td>
					<td>
						<input placeholder="Enter markup to be applied" type="text" name="ced_ali_global_setting[ced_ali_global_price_markup][<?php echo esc_attr( $key ); ?>][ced_ali_markup_aplied]" class="ced_ali_markup_aplied" id="ced_ali_markup_aplied" value="<?php echo isset( $value ['ced_ali_markup_aplied'] ) ? esc_attr( $value ['ced_ali_markup_aplied'] ) : ''; ?>">
					</td>
					<td>
						<div class="ced-wTi-add-markup-row-wrapper">
							<button type="button" class="ced-setting-add-markup-row">+</button>
							<button type="button" class="ced-setting-remove-markup-row">-</button>
						</div>
					</td>
					</tr>
					<?php
				}
			} else {
				?>
					<tr class="ced_setting_price_markup_row">
					<th>
						<label class="basic_heading">
						<?php esc_html_e( 'Price Range', 'aliexpress-dropshipping-for-woocommerce' ); ?>
						</label>
					</th>
					<td>
						<input placeholder="min price" type="text" name="ced_ali_global_setting[ced_ali_global_price_markup][0][ced_ali_min_price_markup]" class="ced_ali_min_price_markup" id="ced_ali_min_price_markup" value="">
					</td>
					<td>
						<input placeholder="max price" type="text" name="ced_ali_global_setting[ced_ali_global_price_markup][0][ced_ali_max_price_markup]" class="ced_ali_max_price_markup" id="ced_ali_max_price_markup" value="">
					</td>
					<td>
						<select name="ced_ali_global_setting[ced_ali_global_price_markup][0][ced_aliexpress_price_markup]" class="ced_aliexpress_price_markup" id="ced_aliexpress_price_markup">
							<option value="">--Select Markup--</option>
							<option value="fixed_price_markup" ><?php esc_html_e( 'Fixed Price Markup', 'aliexpress-dropshipping-for-woocommerce' ); ?></option>
							<option value="percantage_increase" ><?php esc_html_e( 'Percantage Increase', 'aliexpress-dropshipping-for-woocommerce' ); ?></option>
						</select>
					</td>
					<td>
						<input placeholder="Enter markup to be applied" type="text" name="ced_ali_global_setting[ced_ali_global_price_markup][0][ced_ali_markup_aplied]" class="ced_ali_markup_aplied" id="ced_ali_markup_aplied" value="">
					</td>
					<td>
						<div class="ced-wTi-add-markup-row-wrapper">
							<button type="button" class="ced-setting-add-markup-row">+</button>
							<button type="button" class="ced-setting-remove-markup-row">-</button>
						</div>
					</td>
					</tr>
				<?php
			}
			?>
		</table>
	</div>
	<div class="ced-ali-filters-save-wrap">
		<button type="submit" id="save_globalfilters_settings"  name="save_globalfilters_settings" class="save_globalfilters_settings ced-ali-filters-save-btn" ><?php esc_html_e( 'Save Changes', 'aliexpress-dropshipping-for-woocommerce' ); ?></button>
	</div>
</form>
