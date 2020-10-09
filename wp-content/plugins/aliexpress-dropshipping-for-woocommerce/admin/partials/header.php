<?php
/**
 * Header of the extensiom
 *
 * @package  Aliexpress_Dropshipping_For_Woocommerce
 * @version  1.0.0
 * @link     https://cedcommerce.com
 * @since    1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

$section = isset( $_GET['section'] ) ? sanitize_text_field( $_GET['section'] ) : '';
?>
<div class="ced_aliexpress_loader">
	<img src="<?php echo esc_url( CED_ALIEXPRESS_URL . 'admin/images/loading.gif' ); ?>" width="50px" height="50px" class="ced_aliexpress_loading_img" >
</div>
<div class="success-admin-notices is-dismissible"></div>
<div class="navigation-wrapper ced_ali_navigation_wrap">
	<ul class="navigation ced_ali_navigation">
		<li>
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=ced_aliexpress&section=ced-ali-hotselling-product-view' ) ); ?>" class="
								<?php
								if ( 'ced-ali-hotselling-product-view' == $section ) {
									echo 'active ced_ali_navigation_active';
								}
								?>
			"><?php esc_html_e( 'Product', 'aliexpress-dropshipping-for-woocommerce' ); ?></a>
		</li>
		<li>
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=ced_aliexpress&section=ced-ali-filters-view' ) ); ?>" class="
								<?php
								if ( 'ced-ali-filters-view' == $section ) {
									echo 'active ced_ali_navigation_active';
								}
								?>
			"><?php esc_html_e( 'Filters', 'aliexpress-dropshipping-for-woocommerce' ); ?></a>
		</li>
		<li>
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=ced_aliexpress&section=ced-ali-global-setting' ) ); ?>" class="
								<?php
								if ( 'ced-ali-global-setting' == $section ) {
									echo 'active ced_ali_navigation_active';
								}
								?>
			"><?php esc_html_e( 'Global setting', 'aliexpress-dropshipping-for-woocommerce' ); ?></a>
		</li>
		<li>
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=ced_aliexpress&section=ced-ali-schedule' ) ); ?>" class="
								<?php
								if ( 'ced-ali-schedule' == $section ) {
									echo 'active ced_ali_navigation_active';
								}
								?>
			"><?php esc_html_e( 'Scheduler', 'aliexpress-dropshipping-for-woocommerce' ); ?></a>
		</li>

	</ul>
	<?php
		$redirect_url = esc_url( admin_url( '/admin.php?page=ced_aliexpress' ) );
		$url_red      = 'https://bigcommerce.cedcommerce.com/marketplace-integration/aliexpressauth/authorise?platform=wooc&ver_code=3251b321asdbj&request_type=dropship&redirect_uri=' . $redirect_url;
		// delete_transient('ced_aliexpress_token_value');
		$tra_res = get_transient( 'ced_aliexpress_token_value' );
	if ( isset( $tra_res ) && ! empty( $tra_res ) ) {

		$tran = json_decode( $tra_res, true );
		?>
			<div class="ced_ali_connect_wrap" style="background-color: green;">
				<a href="javascript:void(0);" class="ced_ali_connect_store"><?php esc_html_e( 'Connected to store successfully', 'aliexpress-dropshipping-for-woocommerce' ); ?></a>
			</div>  

		<?php
	} else {
		?>
			   <div class="ced_ali_connect_wrap">
				<a href="<?php echo esc_attr( $url_red ); ?>" class="ced_ali_connect_store"><?php esc_html_e( 'Connect to Store', 'aliexpress-dropshipping-for-woocommerce' ); ?></a>
			</div>    	
			<?php
	}
	?>

</div>
<div class="ced_contact_menu_wrap">
	<input type="checkbox" href="#" class="ced_menu_open" name="menu-open" id="menu-open" />
	<label class="ced_menu_button" for="menu-open">
		<img src="<?php echo esc_url( CED_ALIEXPRESS_URL . 'admin/images/icon.png' ); ?>" alt="" title="Click to Chat">
	</label>
	<a href="https://join.skype.com/UHRP45eJN8qQ" class="ced_menu_content ced_skype" target="_blank"> <i class="fa fa-skype" aria-hidden="true"></i> </a>
	<a href="https://chat.whatsapp.com/BcJ2QnysUVmB1S2wmwBSnE" class="ced_menu_content ced_whatsapp" target="_blank"> <i class="fa fa-whatsapp" aria-hidden="true"></i> </a>
</div>
