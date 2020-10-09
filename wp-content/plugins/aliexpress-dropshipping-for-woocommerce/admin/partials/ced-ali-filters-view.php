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
if ( isset( $_GET['action'] ) == 'addNew' ) {
	include_once CED_ALIEXPRESS_DIRPATH . 'admin/partials/ced-ali-filters-view-addNew.php';
} elseif ( isset( $_GET['action'] ) == 'edit' ) {
	include_once CED_ALIEXPRESS_DIRPATH . 'admin/partials/ced-ali-filters-view-addNew.php';
} elseif ( isset( $_GET['action'] ) == 'delete' ) {
	include_once CED_ALIEXPRESS_DIRPATH . 'admin/partials/ced-ali-filters-delete.php';
} else {
	?>
	<div class="ced_ali_filters_view_table_wrap">
<div class="ced_wTi_button_filter_table">
	<div class="ced_ali_filter_heading">
		<h2>Apply Filter</h2>
	</div>
	<div class="ced_ali_filter_btn_wrap">
		<?php echo '<a href="' . esc_attr( get_admin_url() ) . 'admin.php?page=ced_aliexpress&section=ced-ali-filters-view&action=addNew" class="">' . esc_html( __( 'Add Filters', 'aliexpress-dropshipping-for-woocommerce' ) ) . '</a>'; ?>
	</div>
</div>
<div class="success_notice error_notice">
</div>
	<?php
	$aliexpress_filters_file = CED_ALIEXPRESS_DIRPATH . 'admin/partials/class-aliexpress-filters.php';
	if ( file_exists( $aliexpress_filters_file ) ) {
		include_once $aliexpress_filters_file;
	}
}
?>
</div>
