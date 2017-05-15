<?php

namespace NetworkPortfolio\Shortcodes;


if ( ! class_exists( 'NetworkPortfolio\Shortcodes\Shortcode' ) ) {
	class Shortcode {
		/**
		 * Singleton from: from http://stackoverflow.com/a/15870364/1434155
		 */
		private static $instances = array();
		protected function __construct() {}
		protected function __clone() {}
		public function __wakeup() {
			throw new Exception( 'Cannot unserialize singleton' );
		}

		public static function instance() {
			$class = get_called_class(); // late-static-bound class name
			if ( ! isset( self::$instances[ $class ] ) ) {
				self::$instances[ $class ] = new static();
			}
			return self::$instances[ $class ];
		}

		public function init() {
			add_shortcode( 'networkportfolio', array( $this, 'networkportfolio' ) );
		}

		public function networkportfolio( $attributes ) {
			// normalize attribute keys, lowercase
			$attributes = array_change_key_case( (array) $attributes, CASE_LOWER );
			$attributes = shortcode_atts( array(
				'url'           => home_url( '/' ),
				'width'         => \NetworkPortfolio\Helper::get_option( 'width', 'networkportfolio', '430' ),
				'height'        => \NetworkPortfolio\Helper::get_option( 'height', 'networkportfolio', '225' ),
				'border_width'  => \NetworkPortfolio\Helper::get_option( 'border_width', 'networkportfolio', '0' ),
				'border_radius' => \NetworkPortfolio\Helper::get_option( 'border_radius', 'networkportfolio', '0' ),
				'border_color'  => \NetworkPortfolio\Helper::get_option( 'border_color', 'networkportfolio', '#000000' ),
			), $attributes, 'networkportfolio' );

			//harden attributes
			$attributes['url']           = esc_url_raw( $attributes['url'] );
			$attributes['width']         = (int) $attributes['width'];
			$attributes['height']        = (int) $attributes['height'];
			$attributes['border_width']  = (int) $attributes['border_width'];
			$attributes['border_radius'] = (int) $attributes['border_radius'];
			$attributes['border_color']  = \NetworkPortfolio\Helper::esc_hex_color( $attributes['border_color'] );

			return $this->webshot( $attributes );
		}




	}
}
