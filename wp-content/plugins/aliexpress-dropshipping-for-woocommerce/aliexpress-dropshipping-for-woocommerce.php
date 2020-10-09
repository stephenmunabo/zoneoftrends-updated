<?php
/**
 * Wordpress-plugin
 * Plugin Name:       AliExpress Dropshipping for WooCommerce
 * Plugin URI:        https://cedcommerce.com
 * Description:       Aliexpress dropshipping for WooCommerce basically imports the products and resell globally with earning profits.
 * Version:           1.0.3
 * Author:            CedCommerce
 * Author URI:        https://cedcommerce.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       aliexpress-dropshipping-for-woocommerce
 * Domain Path:       /languages
 *
 * Woo: 5688567:77813387d1661091390fbfffd6b8c2ef
 * WC requires at least: 3.0
 * WC tested up to: 5.4
 *
 * @package  aliexpress-dropshipping-for-woocommerce
 * @version  1.0.1
 * @link     https://cedcommerce.com
 * @since    1.0.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'ALIEXPRESS_DROPSHIPPING_FOR_WOOCOMMERCE_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-aliexpress-dropshipping-for-woocommerce-activator.php
 */
function activate_aliexpress_dropshipping_for_woocommerce() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-aliexpress-dropshipping-for-woocommerce-activator.php';
	Aliexpress_Dropshipping_For_Woocommerce_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-aliexpress-dropshipping-for-woocommerce-deactivator.php
 */
function deactivate_aliexpress_dropshipping_for_woocommerce() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-aliexpress-dropshipping-for-woocommerce-deactivator.php';
	Aliexpress_Dropshipping_For_Woocommerce_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_aliexpress_dropshipping_for_woocommerce' );
register_deactivation_hook( __FILE__, 'deactivate_aliexpress_dropshipping_for_woocommerce' );

define( 'CED_ALIEXPRESS_LOG_DIRECTORY', wp_upload_dir()['basedir'] . '/ced_aliexpress_log_directory' );
define( 'CED_ALIEXPRESS_VERSION', '1.0.0' );
define( 'CED_ALIEXPRESS_PREFIX', 'ced_aliexpress' );
define( 'CED_ALIEXPRESS_DIRPATH', plugin_dir_path( __FILE__ ) );
define( 'CED_ALIEXPRESS_URL', plugin_dir_url( __FILE__ ) );
define( 'CED_ALIEXPRESS_ABSPATH', untrailingslashit( plugin_dir_path( dirname( __FILE__ ) ) ) );
define( 'CED_ALIEXPRESS_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-aliexpress-dropshipping-for-woocommerce.php';
require plugin_dir_path( __FILE__ ) . 'includes/Ced_ali_core_functions.php';
/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_aliexpress_dropshipping_for_woocommerce() {

	$plugin = new Aliexpress_Dropshipping_For_Woocommerce();
	$plugin->run();

}

/**
 * Ced_admin_notice_example_activation_hook_ced_aliexpress.
 *
 * @since 1.0.0
 */
function ced_admin_notice_example_activation_hook_ced_aliexpress() {
	set_transient( 'ced-aliexpress-admin-notice', true, 5 );
}

/**
 * Ced_aliexpress_admin_notice_activation.
 *
 * @since 1.0.0
 */
function ced_aliexpress_admin_notice_activation() {
	if ( get_transient( 'ced-aliexpress-admin-notice' ) ) {?>
		<div class="updated notice is-dismissible">
			<p>Aliexpress dropshipping for WooCommerce basically imports the products and resell globally with earning profits.</p>
			<a href="admin.php?page=ced_aliexpress" class ="ced_configuration_plugin_main">Connect to Aliexpress</a>
		</div>
		<?php
		delete_transient( 'ced-aliexpress-admin-notice' );
	}
}

/**
 * Check WooCommerce is Installed and Active.
 *
 * @since 1.0.0
 */
if ( ced_aliexpress_check_woocommerce_active() ) {

	run_aliexpress_dropshipping_for_woocommerce();
	register_activation_hook( __FILE__, 'ced_admin_notice_example_activation_hook_ced_aliexpress' );
	add_action( 'admin_notices', 'ced_aliexpress_admin_notice_activation' );
} else {

	add_action( 'admin_init', 'deactivate_ced_aliexpress_woo_missing' );
}
