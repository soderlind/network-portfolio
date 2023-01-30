<?php
/**
 * Helper class, with a bunch of helper methods.
 *
 * @package  NetworkPortfolio;
 */

namespace NetworkPortfolio;

use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Admin\AdminApi;
if ( ! class_exists( 'NetworkPortfolio\Helper' ) ) {

	/**
	 * Helper class, with a bunch of helper methods.
	 */
	class Helper {

		/**
		 * Empty constructor.
		 */
		protected function __construct() {}
		/**
		 * Get the value of a settings field
		 *
		 * @param string $option  settings field name.
		 * @param string $default default text if it's not found.
		 * @return string
		 */
		public static function get_option( string $option, string $default = '' ) : string {
			$option = get_network_option( get_current_network_id(), $option );
			if ( $option ) {
				return $option;
			}
			return $default;
		}

		/**
		 * Save/update the value of a settings field
		 *
		 * @param string $option   Option name.
		 * @param string $value    Option value.
		 */
		public static function update_option( string $option, string $value ) : void {
			$updated = update_network_option( get_current_network_id(), $option, $value );
		}


		/**
		 * Validate the Cloudinary settings.
		 *
		 * @param \WP_Error $validity   Default empty.
		 * @param string    $cloud_name Cloud Name.
		 * @param string    $api_key    API Key.
		 * @param string    $api_secret API Secret.
		 * @return \WP_Error
		 */
		public static function validate_setting( \WP_Error $validity, string $cloud_name, string $api_key, string $api_secret ) : \WP_Error {
			try {

				Configuration::instance( "cloudinary://$api_key:$api_secret@$cloud_name?secure=true" );
				$usage_obj = ( new AdminApi() )->usage();
				$usage     = (array) $usage_obj;
				if ( ! isset( $usage['url2png'] ) ) {
					return new \WP_Error( 'error', '<a href="https://cloudinary.com/console/addons#url2png" target="_blank">URL2PNG add-on</a> not enabled' );
				}
			} catch ( \Exception $e ) {
				$validity->add( 'required', $e->getMessage() );
			}
			return $validity;
		}

		/**
		 * Sanitize color code.
		 *
		 * @param string $colorcode  Colorcode to sanitize.
		 * @param string $default Default color code, will be used if the validation fails.
		 * @return string
		 */
		public static function esc_hex_color( string $colorcode, string $default = '#000000' ) : string {
			$colorcode = ltrim( $colorcode, '#' );
			if ( ctype_xdigit( $colorcode ) && ( 6 === strlen( $colorcode ) || 3 === strlen( $colorcode ) ) ) {
				return '#' . $colorcode;
			} else {
				return $default;
			}
		}

		/**
		 * Write log
		 *
		 * @param  mixed $log  Data.
		 * @return void
		 */
		public static function write_log( $log ) {
			if ( true === WP_DEBUG ) {
				//phpcs:disable
				if ( is_array( $log ) || is_object( $log ) ) {
					error_log( print_r( $log, true ) );
				} else {
					error_log( $log );
				}
				//phpcs:enable
			}
		}
	}
}
