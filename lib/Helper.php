<?php
namespace NetworkPortfolio;

if ( ! class_exists( 'NetworkPortfolio\Helper' ) ) {
	class Helper {

		protected function __construct() {}
		/**
		 * Get the value of a settings field
		 *
		 * @param string  $option  settings field name
		 * @param string  $section the section name this field belongs to
		 * @param string  $default default text if it's not found
		 * @return string
		 */
		public static function get_option( $option, $default = '' ) {
			$option = get_network_option( get_current_network_id(), $option );
			if (  $option  ) {
				return $option;
			}
			return $default;
		}

		/**
		 * Save/update the value of a settings field
		 *
		 * @param string  $option	Option name.
		 * @param string  $value	Option value.
		 */
		public static function update_option( $option, $value ) {
			$updated = update_network_option( get_current_network_id(), $option, $value );
		}


		public static function is_valid_cloudinary_account( $cloud_name, $api_key, $api_secret ) {

			// TODO: Add transient

			try {
				\Cloudinary::config(array(
					'cloud_name' => $cloud_name,
					'api_key'    => $api_key,
					'api_secret' => $api_secret,
				));
				$api = new \Cloudinary\Api();
				$result = $api->ping();
			} catch ( \Exception $e) {
				// self::write_log( $e->getMessage() );
				self::write_log( sprintf ("%s (%s): %s",$e->getFile(), $e->getLine(), $e->getMessage() ) );
				self::write_log( $e->getTraceAsString() );
				return false;
			}
			return true;
		}

		public static function esc_hex_color( $colorcode, $default = '#000000' ) {
			$colorcode = ltrim( $colorcode, '#' );
			if ( ctype_xdigit( $colorcode ) && ( 6 == strlen( $colorcode ) || 3 == strlen( $colorcode ) ) ) {
				return '#' . $colorcode;
			} else {
				return $default;
			}
		}

	    public static function write_log ( $log )  {
	        if ( true === WP_DEBUG ) {
	            if ( is_array( $log ) || is_object( $log ) ) {
	                error_log( print_r( $log, true ) );
	            } else {
	                error_log( $log );
	            }
	        }
	    }
	}
}
