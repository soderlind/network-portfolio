<?php
/**
 * Sanitize customizer settings.
 *
 * @package NetworkPortfolio\Customizer
 */

namespace NetworkPortfolio\Customizer;

if ( ! class_exists( '\NetworkPortfolio\Customizer\Validate' ) ) {
	/**
	 * Validate customizer settings.
	 */
	class Validate {

		/**
		 * Validate the Cloudinary Cloud Name.
		 *
		 * @param \WP_Error $validity Default empty of errors.
		 * @param string    $cloud_name  Value been sanitized.
		 * @return \WP_Error
		 */
		public static function cloud_name( \WP_Error $validity, string $cloud_name ): \WP_Error {

			return \NetworkPortfolio\Helper::validate_setting(
				$validity,
				$cloud_name,
				\NetworkPortfolio\Helper::get_option( 'networkportfolio[api_key]' ),
				\NetworkPortfolio\Helper::get_option( 'networkportfolio[api_secret]' )
			);
		}

		/**
		 * Validate the Cloudinary API key.
		 *
		 * @param \WP_Error $validity Default empty of errors.
		 * @param string    $api_key Value been sanitized.
		 * @return \WP_Error
		 */
		public static function api_key( \WP_Error $validity, string $api_key ) : \WP_Error {

			return \NetworkPortfolio\Helper::validate_setting(
				$validity,
				\NetworkPortfolio\Helper::get_option( 'networkportfolio[cloud_name]' ),
				$api_key,
				\NetworkPortfolio\Helper::get_option( 'networkportfolio[api_secret]' )
			);
		}

		/**
		 * Validate the Cloudinary API secret.
		 *
		 * @param \WP_Error $validity Default empty of errors.
		 * @param string    $api_secret Value been sanitized.
		 * @return \WP_Error
		 */
		public static function api_secret( \WP_Error $validity, string $api_secret ) : \WP_Error {

			return \NetworkPortfolio\Helper::validate_setting(
				$validity,
				\NetworkPortfolio\Helper::get_option( 'networkportfolio[cloud_name]' ),
				\NetworkPortfolio\Helper::get_option( 'networkportfolio[api_key]' ),
				$api_secret
			);
		}
	}
}
