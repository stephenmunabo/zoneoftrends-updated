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
if ( isset( $_POST['save_schedule_settings'] ) ) {
	if ( ! isset( $_POST['schedule_settings_submit'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['schedule_settings_submit'] ) ), 'schedule_settings' ) ) {
		return;
	}
	$sync_schedule       = isset( $_POST['ced_ali_sync_scheduler'] ) ? sanitize_text_field( $_POST['ced_ali_sync_scheduler'] ) : '';
	$autoimport_schedule = isset( $_POST['ced_ali_auto_import_scheduler'] ) ? sanitize_text_field( $_POST['ced_ali_auto_import_scheduler'] ) : '';

	if ( ! empty( $sync_schedule ) ) {
		wp_clear_scheduled_hook( 'ced_ali_sync_inventory' );
		wp_schedule_event( time(), $sync_schedule, 'ced_ali_sync_inventory' );
		update_option( 'ced_ali_sync_inventory', $sync_schedule );
	} else {
		wp_clear_scheduled_hook( 'ced_ali_sync_inventory' );
		delete_option( 'ced_ali_sync_inventory' );
	}

	///Auto import
	if ( ! empty( $autoimport_schedule ) ) {
		wp_clear_scheduled_hook( 'ced_ali_auto_import_product' );
		wp_schedule_event( time(), $autoimport_schedule, 'ced_ali_auto_import_product' );
		update_option( 'ced_ali_auto_import_product', $autoimport_schedule );
	} else {
		wp_clear_scheduled_hook( 'ced_ali_auto_import_product' );
		delete_option( 'ced_ali_auto_import_product' );
	}
}
$sync_schedule_get       = get_option( 'ced_ali_sync_inventory', '' );
$autoimport_schedule_get = get_option( 'ced_ali_auto_import_product', '' );
?>

<form method="post" action="">
	<?php wp_nonce_field( 'schedule_settings', 'schedule_settings_submit' ); ?>
	<div class="ced_schedule_table_wrap">
		<table class="widefat stripped wp-list-table fixed ced_schedule_table">
			<tbody>
				<tr>
					<th class=""><?php esc_html_e( 'Auto Sync Price and Stock From Aliexpress' ); ?></th>
					<td>
						<select name="ced_ali_sync_scheduler" class="ced_ali_schedule_select">
							<option <?php echo ( '0' == $sync_schedule_get ) ? 'selected' : ''; ?> value="0"><?php esc_html_e( 'Disabled' ); ?></option>
							<option <?php echo ( 'ced_ali_10min' == $sync_schedule_get ) ? 'selected' : ''; ?> value="ced_ali_10min"><?php esc_html_e( 'Every 10 minutes' ); ?></option>
							<option <?php echo ( 'ced_ali_15min' == $sync_schedule_get ) ? 'selected' : ''; ?> value="ced_ali_15min"><?php esc_html_e( 'Every 15 minutes' ); ?></option>
							<option <?php echo ( 'ced_ali_30min' == $sync_schedule_get ) ? 'selected' : ''; ?> value="ced_ali_30min"><?php esc_html_e( 'Every 30 minutes' ); ?></option>
							<option <?php echo ( 'ced_ali_60min' == $sync_schedule_get ) ? 'selected' : ''; ?> value="ced_ali_60min"><?php esc_html_e( 'Every 60 minutes' ); ?></option>
							<option <?php echo ( 'twicedaily' == $sync_schedule_get ) ? 'selected' : ''; ?> value="twicedaily"><?php esc_html_e( 'Twice Daily' ); ?></option>
							<option <?php echo ( 'daily' == $sync_schedule_get ) ? 'selected' : ''; ?>value="daily"><?php esc_html_e( 'Daily' ); ?></option>
						</select>
					</td>
				</tr>
				<tr>
					<th class=""><?php esc_html_e( 'Auto Import Product From Aliexpress' ); ?></th>
					<td>
						<select name="ced_ali_auto_import_scheduler" class="ced_ali_auto_import_scheduler">
							<option <?php echo ( '0' == $autoimport_schedule_get ) ? 'selected' : ''; ?> value="0"><?php esc_html_e( 'Disabled' ); ?></option>
							<option <?php echo ( 'ced_ali_10min' == $autoimport_schedule_get ) ? 'selected' : ''; ?> value="ced_ali_10min"><?php esc_html_e( 'Every 10 minutes' ); ?></option>
							<option <?php echo ( 'ced_ali_15min' == $autoimport_schedule_get ) ? 'selected' : ''; ?> value="ced_ali_15min"><?php esc_html_e( 'Every 15 minutes' ); ?></option>
							<option <?php echo ( 'ced_ali_30min' == $autoimport_schedule_get ) ? 'selected' : ''; ?> value="ced_ali_30min"><?php esc_html_e( 'Every 30 minutes' ); ?></option>
							<option <?php echo ( 'ced_ali_60min' == $autoimport_schedule_get ) ? 'selected' : ''; ?> value="ced_ali_60min"><?php esc_html_e( 'Every 60 minutes' ); ?></option>
							<option <?php echo ( 'twicedaily' == $autoimport_schedule_get ) ? 'selected' : ''; ?> value="twicedaily"><?php esc_html_e( 'Twice Daily' ); ?></option>
							<option <?php echo ( 'daily' == $autoimport_schedule_get ) ? 'selected' : ''; ?>value="daily"><?php esc_html_e( 'Daily' ); ?></option>
						</select>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="ced-ali-filters-save-wrap">
		<button  name="save_schedule_settings" class="save_schedule_settings" ><?php esc_html_e( 'Save Changes', 'aliexpress-dropshipping-for-woocommerce' ); ?></button>
	</div>
</form>
