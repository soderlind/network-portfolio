<?php
/**
 * Network Portfolio
 *
 * @package     Network Portfolio
 * @author      Per Soderlind
 * @copyright   2017 Per Soderlind
 * @license     GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name: Network Portfolio
 * Plugin URI:  https://github.com/soderlind/network-portfolio
 * Description:
 * Network: false
 * Version: 1.0.12
 * Author:      Per Soderlind
 * Author URI:  https://soderlind.no
 * Text Domain: network-portfolio
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */
if ( version_compare( PHP_VERSION, '5.6.0' ) < 0 ) {
	return add_action( 'admin_notices', 'networkportfolio_admin_notice_php_version' );
}
define( 'NETWORKPORTFOLIO_VERSION', '1.0.12' );
define( 'NETWORKPORTFOLIO_PATH',   plugin_dir_path( __FILE__ ) );
define( 'NETWORKPORTFOLIO_URL',   plugin_dir_url( __FILE__ ) );

// Add the PHP extension for Cloudinary
require_once  NETWORKPORTFOLIO_PATH . 'lib/cloudinary/Cloudinary.php';
require_once  NETWORKPORTFOLIO_PATH . 'lib/cloudinary/Api.php';
Cloudinary::$USER_PLATFORM = 'NetworkPortfolio/' . NETWORKPORTFOLIO_VERSION . ' github.com/soderlind/network-portfolio'; // @codingStandardsIgnoreLine

// add autoloader
require_once NETWORKPORTFOLIO_PATH . 'inc/ps-auto-loader.php';
$class_loader = new PS_Auto_Loader();
$class_loader->addNamespace( 'NetworkPortfolio', NETWORKPORTFOLIO_PATH . 'lib' );
$class_loader->addNamespace( 'PluginCustomizer', NETWORKPORTFOLIO_PATH . 'lib/plugin-customizer' );

$class_loader->register();

//launch the plugin
if ( defined( 'WPINC' ) ) {
	// if ( 1 == get_current_blog_id() ) {
		$GLOBALS['network_portfolio_admin'] = NetworkPortfolio\Admin::instance();
	// }
	add_action(	'plugins_loaded', function() {
		$GLOBALS['network_portfolio_shortcode'] = NetworkPortfolio\Shortcodes\Portfolio::instance();
		$GLOBALS['network_portfolio_shortcode']->init();
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), function( $links ) {
			$settings_links = array(
				sprintf( '<a href="%s">%s</a>',  $GLOBALS['network_portfolio_admin']->get_customizer_url( 'plugins.php', 'networkportfolio_section[settings]' ), __( 'Settings', 'networkport-folio' ) )
			);
			return array_merge( $links, $settings_links );
		} );

		/**
		* Handle saving of settings with custom storage type.
		*
		* @param string $value Value being saved
		* @param WP_Customize_Setting $WP_Customize_Setting The WP_Customize_Setting instance when saving is happening.
		*/
		add_action( 'customize_update_site_option', function( $value, $WP_Customize_Setting ) {
			\NetworkPortfolio\Helper::update_option( $WP_Customize_Setting->id, $value );
		}, 10, 2 );

		add_action( 'customize_value_site_option', function( $WP_Customize_Setting ) {
			return \NetworkPortfolio\Helper::get_option( $WP_Customize_Setting->id );
		}, 10, 2 );

		add_action( 'customize_preview_site_option', function( $WP_Customize_Setting ) {
			return \NetworkPortfolio\Helper::get_option( $WP_Customize_Setting->id );
		}, 10, 2 );

	} );
}

function networkportfolio_admin_notice_php_version() {
	$msg[] = '<div class="notice notice-error"><p>';
	$msg[] = '<strong>NetworkPortfolio</strong>: Your current PHP version is <strong>' . PHP_VERSION . '</strong>, please upgrade PHP at least to version 5.6 (PHP 7.0 or greater is reccomended). ';
	$msg[] = '<a href="https://wordpress.org/about/requirements/">Ask</a> your hosting provider for an upgrade.';
	$msg[] = '</p></div>';
	deactivate_plugins( plugin_basename( __FILE__ ) );
	echo implode( PHP_EOL, $msg );
	// disable the "Plugin activated." message by unsetting $_GET['activate']
	if ( isset( $_GET['activate'] ) ) {
		unset( $_GET['activate'] );
	}
}
