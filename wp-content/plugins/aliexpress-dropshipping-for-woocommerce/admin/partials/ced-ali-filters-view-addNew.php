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
if ( isset( $_POST['save_filters_settings'] ) ) {

	if ( ! isset( $_POST['filter_add_new_settings_submit'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['filter_add_new_settings_submit'] ) ), 'filter_add_new_settings' ) ) {
		return;
	}

	$filters                                = get_option( 'ced_ali_filter', array() );
	$sanitized_array                        = filter_input_array( INPUT_POST, FILTER_SANITIZE_STRING );
	$filter_id                              = isset( $sanitized_array['ced_ali_filters']['ced_ali_filters_id'] ) ? $sanitized_array['ced_ali_filters']['ced_ali_filters_id'] : 0;
	$category_value                         = isset( $sanitized_array['ced_ali_filters']['ced_aliexpress_category'] ) ? $sanitized_array['ced_ali_filters']['ced_aliexpress_category'] : '';
	$category_value                         = explode( ',', $category_value );
	$category_id                            = $category_value[0];
	$category_name                          = $category_value[1];
	$filters[ $filter_id ]                  = isset( $sanitized_array ['ced_ali_filters'] ) ? $sanitized_array ['ced_ali_filters'] : array();
	$filters[ $filter_id ]['category_id']   = $category_id;
	$filters[ $filter_id ]['category_name'] = $category_name;
	$filters_pricemarkup_data               = isset( $sanitized_array ['ced_ali_filter_url_data'] ) ? $sanitized_array ['ced_ali_filter_url_data'] : array();
	$filters[ $filter_id ]['ced_ali_filter_url_data'] = $filters_pricemarkup_data;

	update_option( 'ced_ali_filter', $filters );
	$url = admin_url( '/admin.php?page=ced_aliexpress&section=ced-ali-filters-view' );
	wp_redirect( $url );
}
$filter_data = array();
if ( isset( $_GET['filterId'] ) ) {
	$get_filter_id = sanitize_text_field( $_GET['filterId'] );
	$get_filter    = get_option( 'ced_ali_filter', array() );
	$filter_data   = $get_filter[ $get_filter_id ];
}

?>
<form method="post" action="">
	<?php wp_nonce_field( 'filter_add_new_settings', 'filter_add_new_settings_submit' ); ?>
	<div class="filters-wrapper">
		<div class="ced-heading-wrap">
			<h2><?php esc_html_e( 'Filters', 'aliexpress-dropshipping-for-woocommerce' ); ?></h2>
		</div>
		<table class="ced_aliexpress_filters">
			<tbody>
				<tr>
					<th class="">
						<label class="basic_heading">
							<?php esc_html_e( 'Name', 'aliexpress-dropshipping-for-woocommerce' ); ?>
						</label>
					</th>
					<td>
						<input value="<?php echo isset( $filter_data['ced_ali_filters_id'] ) ? esc_attr( $filter_data['ced_ali_filters_id'] ) : esc_attr( rand( 1, 100 ) ); ?>" type="hidden" name="ced_ali_filters[ced_ali_filters_id]">
						<input placeholder="Enter Name" type="text" name="ced_ali_filters[ced_ali_name]" class="ced_ali_name" id="ced_ali_name" value="<?php echo isset( $filter_data['ced_ali_name'] ) ? esc_attr( $filter_data['ced_ali_name'] ) : ''; ?>">
					</td>
				</tr>
				<tr>
					<th class="">
						<label class="basic_heading">
							<?php esc_html_e( 'Keyword Name', 'aliexpress-dropshipping-for-woocommerce' ); ?>
						</label>
					</th>
					<td>
						<input placeholder="Enter Keyword Name" type="text" name="ced_ali_filters[ced_ali_keyword_name]" class="ced_ali_keyword_name" id="ced_ali_keyword_name" value="<?php echo isset( $filter_data['ced_ali_keyword_name'] ) ? esc_attr( $filter_data['ced_ali_keyword_name'] ) : ''; ?>">
					</td>
				</tr>							
				<tr>
					<th class="">
						<label class="basic_heading">
							<?php esc_html_e( 'Price range', 'aliexpress-dropshipping-for-woocommerce' ); ?>
						</label>
					</th>
					<td>
						<input placeholder="Min price" type="text" name="ced_ali_filters[ced_ali_min_price]" class="ced_ali_min_price" id="ced_ali_min_price" value="<?php echo isset( $filter_data['ced_ali_min_price'] ) ? esc_attr( $filter_data['ced_ali_min_price'] ) : ''; ?>">
						<input placeholder="Max price" type="text" name="ced_ali_filters[ced_ali_max_price]" class="ced_ali_max_price" id="ced_ali_max_price" value="<?php echo isset( $filter_data['ced_ali_max_price'] ) ? esc_attr( $filter_data['ced_ali_max_price'] ) : ''; ?>">
					</td>
				</tr>
				<tr>
					<th class="">
						<label class="basic_heading">
							<?php esc_html_e( 'Select Category', 'aliexpress-dropshipping-for-woocommerce' ); ?>
						</label>
					</th>
					<td>
						<?php
						$category_json_file = CED_ALIEXPRESS_DIRPATH . 'admin/vendor/lib/json/category.json';
						if ( file_exists( $category_json_file ) ) {
							$category_file_put = file_get_contents( $category_json_file );
							$category_file_put = json_decode( $category_file_put, true );
						}

						?>
						<select name="ced_ali_filters[ced_aliexpress_category]" class="ced_aliexpress_category" id="ced_aliexpress_category">
						<option value="">--Select category--</option>

						<?php
						$category_data = isset( $filter_data['ced_aliexpress_category'] ) ? $filter_data['ced_aliexpress_category'] : '';
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
					</td>
				</tr>
				<tr>
					<th class="">
						<label class="basic_heading">
							<?php esc_html_e( 'Vendor Rating', 'aliexpress-dropshipping-for-woocommerce' ); ?>
						</label>
					</th>
					<td>
						<input placeholder="Vendor Rating" type="text" name="ced_ali_filters[ced_ali_vendor_rating]" class="ced_ali_vendor_rating" id="ced_ali_vendor_rating" value="<?php echo isset( $filter_data['ced_ali_vendor_rating'] ) ? esc_attr( $filter_data['ced_ali_vendor_rating'] ) : ''; ?>">
					</td>
				</tr>
		</table>
		<div class="ced-heading-wrap">
			<h2><?php esc_html_e( 'Price Markup Settings', 'aliexpress-dropshipping-for-woocommerce' ); ?></h2>
		</div>
		
		<table class="ced_aliexpress_price_markup_wrap">
				<?php
				if ( is_array( $filter_data ) && ! empty( $filter_data ) ) {
					foreach ( $filter_data as $key => $value ) {
						if ( 'ced_ali_filter_url_data' == $key ) {
							foreach ( $value as $key1 => $values ) {
								?>
					<tr class="ced_setting_price_markup_row">
						<th>
										<label class="basic_heading">
									<?php esc_html_e( 'Price Range', 'aliexpress-dropshipping-for-woocommerce' ); ?>
										</label>
									</th>
					<td>
						<input placeholder="min price" type="text" name="ced_ali_filter_url_data[<?php echo esc_attr( $key1 ); ?>][ced_ali_min_price_markup]" class="ced_ali_min_price_markup" id="ced_ali_min_price_markup" value="<?php echo isset( $values['ced_ali_min_price_markup'] ) ? esc_attr( $values ['ced_ali_min_price_markup'] ) : ''; ?>">
					</td>
					<td>
						<input placeholder="max price" type="text" name="ced_ali_filter_url_data[<?php echo esc_attr( $key1 ); ?>][ced_ali_max_price_markup]" class="ced_ali_max_price_markup" id="ced_ali_max_price_markup" value="<?php echo isset( $values ['ced_ali_max_price_markup'] ) ? esc_attr( $values['ced_ali_max_price_markup'] ) : ''; ?>">
					</td>
					<td>
								<?php
									$markup_data = isset( $values ['ced_aliexpress_price_markup'] ) ? $values ['ced_aliexpress_price_markup'] : '';
								?>
						<select name="ced_ali_filter_url_data[<?php echo esc_attr( $key1 ); ?>][ced_aliexpress_price_markup]" class="ced_aliexpress_price_markup" id="ced_aliexpress_price_markup">
							<option value="">--Select Markup--</option>
							<option value="fixed_price_markup" <?php selected( $markup_data, 'fixed_price_markup' ); ?>><?php esc_html_e( 'Fixed Price Markup', 'aliexpress-dropshipping-for-woocommerce' ); ?></option>
							<option value="percantage_increase" <?php selected( $markup_data, 'percantage_increase' ); ?>><?php esc_html_e( 'Percantage Increase', 'aliexpress-dropshipping-for-woocommerce' ); ?></option>
						</select>
					</td>
					<td>
						<input placeholder="Enter markup to be applied" type="text" name="ced_ali_filter_url_data[<?php echo esc_attr( $key1 ); ?>][ced_ali_markup_aplied]" class="ced_ali_markup_aplied" id="ced_ali_markup_aplied" value="<?php echo isset( $values ['ced_ali_markup_aplied'] ) ? esc_attr( $values ['ced_ali_markup_aplied'] ) : ''; ?>">
					</td>
					<td>
						<div class="ced-wTi-add-markup-row-wrapper">
							<button type="button" class="ced-ali-add-markup-row">+</button>
							<button type="button" class="ced-ali-remove-markup-row">-</button>
						</div>
					</td>
					</tr>
								<?php
							}
						}
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
										<input placeholder="min price" type="text" name="ced_ali_filter_url_data[0][ced_ali_min_price_markup]" class="ced_ali_min_price_markup" id="ced_ali_min_price_markup" value="">
									</td>
									<td>
										<input placeholder="max price" type="text" name="ced_ali_filter_url_data[0][ced_ali_max_price_markup]" class="ced_ali_max_price_markup" id="ced_ali_max_price_markup" value="">
									</td>
									<td>
										<select name="ced_ali_filter_url_data[0][ced_aliexpress_price_markup]" class="ced_aliexpress_price_markup" id="ced_aliexpress_price_markup">
											<option value="">--Select Markup--</option>
											<option value="fixed_price_markup" ><?php esc_html_e( 'Fixed Price Markup', 'aliexpress-dropshipping-for-woocommerce' ); ?></option>
											<option value="percantage_increase" ><?php esc_html_e( 'Percantage Increase', 'aliexpress-dropshipping-for-woocommerce' ); ?></option>
										</select>
									</td>
									<td>
										<input placeholder="Enter markup to be applied" type="text" name="ced_ali_filter_url_data[0][ced_ali_markup_aplied]" class="ced_ali_markup_aplied" id="ced_ali_markup_aplied" value="">
									</td>
									<td>
										<div class="ced-wTi-add-markup-row-wrapper">
											<button type="button" class="ced-ali-add-markup-row">+</button>
											<button type="button" class="ced-ali-remove-markup-row">-</button>
										</div>
									</td>
								</tr>

					<?php
				}
				?>
				

			</table>
	</div>
	<div class="ced-ali-filters-save-wrap">
		<button type="submit" id="save_filters_settings"  name="save_filters_settings" class="ced-ali-filters-save-btn save_filters_settings" ><?php esc_html_e( 'Save Changes', 'aliexpress-dropshipping-for-woocommerce' ); ?></button>
	</div>
</form>
