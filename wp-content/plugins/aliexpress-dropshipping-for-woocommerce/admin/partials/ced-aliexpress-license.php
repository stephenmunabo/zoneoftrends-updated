<div class="ced_aliexpress_loader">
	<img src="<?php echo esc_url( CED_ALIEXPRESS_URL . 'admin/images/loading.gif' ); ?>" width="50px" height="50px" class="ced_aliexpress_loading_img" >
</div>
<div class="success-admin-notices" ></div>
<div class="ced_aliexpress_wrap">
	<h2 class="ced_aliexpress_setting_header ced_aliexpress_bottom_margin"><?php esc_html_e( 'ALIEXPRESS LICENSE CONFIGURATION', 'woocommerce-aliexpress-integration' ); ?></h2>
	<div class="ced_aliexpress_license_divs">
		<form method="post">
			<table class="wp-list-table widefat fixed striped ced_aliexpress_config_table">
				<tbody>
					<tr>
						<th class="manage-column">
							<label><b><?php esc_html_e( 'Enter License Key', 'woocommerce-aliexpress-integration' ); ?></b></label>
							<input type="text" value="" class="ced_aliexpress_inputs" id="ced_aliexpress_license_key">
						</th>
						<td>
							<input type="button" value="Validate" class="ced_aliexpress_custom_button" id="ced_aliexpress_save_license" class="button button-ced_aliexpress">
						</td>
					</tr>
				</tbody>
			</table>
		</form>
	</div>
<div>
