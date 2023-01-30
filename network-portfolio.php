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
 * Version: 1.1.0
 * Requires at least: 5.9
 * Requires PHP:      7.0
 * Author:      Per Soderlind
 * Author URI:  https://soderlind.no
 * Text Domain: network-portfolio
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

define( 'NETWORKPORTFOLIO_VERSION', '1.1.0' );
define( 'NETWORKPORTFOLIO_PATH', plugin_dir_path( __FILE__ ) );
define( 'NETWORKPORTFOLIO_URL', plugin_dir_url( __FILE__ ) );

require_once __DIR__ . '/vendor/autoload.php';

// launch the plugin
if ( defined( 'WPINC' ) ) {
	// if ( 1 == get_current_blog_id() ) {
		$GLOBALS['network_portfolio_admin'] = NetworkPortfolio\Admin::instance();
	// }
	add_action(
		'plugins_loaded',
		function() {
			$GLOBALS['network_portfolio_shortcode'] = NetworkPortfolio\Shortcodes\Portfolio::instance();
			$GLOBALS['network_portfolio_shortcode']->init();
			add_filter(
				'plugin_action_links_' . plugin_basename( __FILE__ ),
				function( $links ) {
					$settings_links = [
						sprintf( '<a href="%s">%s</a>', $GLOBALS['network_portfolio_admin']->get_customizer_url( 'plugins.php', 'networkportfolio_section[settings]' ), __( 'Settings', 'networkport-folio' ) ),
					];
					return array_merge( $links, $settings_links );
				}
			);

			/**
			* Handle saving of settings with custom storage type.
			*
			* @param string $value Value being saved
			* @param WP_Customize_Setting $WP_Customize_Setting The WP_Customize_Setting instance when saving is happening.
			*/
			add_action(
				'customize_update_site_option',
				function( $value, $WP_Customize_Setting ) {
					\NetworkPortfolio\Helper::update_option( $WP_Customize_Setting->id, $value );
				},
				10,
				2
			);

			add_action(
				'customize_value_site_option',
				function( $WP_Customize_Setting ) {
					return \NetworkPortfolio\Helper::get_option( $WP_Customize_Setting->id );
				},
				10,
				2
			);

			add_action(
				'customize_preview_site_option',
				function( $WP_Customize_Setting ) {
					return \NetworkPortfolio\Helper::get_option( $WP_Customize_Setting->id );
				},
				10,
				2
			);

		}
	);
}
