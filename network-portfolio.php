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
 * Version: 2.0.0
 * Requires at least: 5.9
 * Requires PHP:      7.0
 * Author:      Per Soderlind
 * Author URI:  https://soderlind.no
 * Text Domain: network-portfolio
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

define( 'NETWORKPORTFOLIO_VERSION', '2.0.0' );
define( 'NETWORKPORTFOLIO_PATH', plugin_dir_path( __FILE__ ) );
define( 'NETWORKPORTFOLIO_URL', plugin_dir_url( __FILE__ ) );

require_once __DIR__ . '/vendor/autoload.php';

// Launch the plugin.
if ( defined( 'WPINC' ) ) {
	add_action(
		'plugins_loaded',
		function () {
			$GLOBALS['network_portfolio_shortcode'] = NetworkPortfolio\Shortcodes\Portfolio::instance();
			$GLOBALS['network_portfolio_shortcode']->init();
		}
	);
}
