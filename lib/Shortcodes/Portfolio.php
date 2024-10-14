<?php
/**
 * Portfolio Shortcode Class
 *
 * @package NetworkPortfolio
 */

namespace NetworkPortfolio\Shortcodes;

if ( ! class_exists( 'NetworkPortfolio\Shortcodes\Portfolio' ) ) {
	/**
	 * Class Portfolio
	 *
	 * Handles the portfolio shortcode functionality.
	 *
	 * @package NetworkPortfolio\Shortcodes
	 */
	class Portfolio {

		/**
		 * Singleton from: from http://stackoverflow.com/a/15870364/1434155
		 *
		 * @var array
		 */
		private static $instances = [];
		/**
		 * Constructor.
		 *
		 * Protected constructor to prevent creating a new instance of the
		 * singleton via the `new` operator from outside of this class.
		 */
		protected function __construct() {}
		/**
		 * Prevent cloning of the instance.
		 *
		 * @return void
		 */
		protected function __clone() {}
		/**
		 * Prevent unserializing of the instance.
		 *
		 * @throws \Exception When attempting to unserialize a singleton.
		 */
		public function __wakeup() {
			throw new \Exception( 'Cannot unserialize singleton' );
		}

		/**
		 * Get the items in the collection that are not present in the given items, using the callback.
		 *
		 * @return static
		 */
		public static function instance() {
			$class = get_called_class(); // Late-static-bound class name.
			if ( ! isset( self::$instances[ $class ] ) ) {
				self::$instances[ $class ] = new static();
			}
			return self::$instances[ $class ];
		}

		/**
		 * Add the network portfolio shortcode.
		 *
		 * @return void
		 */
		public function init() {
			add_shortcode( 'portfolio', [ $this, 'portfolio' ] );
		}


		/**
		 * Portfolio shortcode.
		 *
		 * @param mixed $attributes Shortcode attributes.
		 * @return string
		 */
		public function portfolio( $attributes ): string {
			global $wp_version;

			$attributes            = shortcode_atts(
				[
					'sites'   => 0,
					'expires' => 600, // 10 minutes
					'orderby' => 'modified=DESC&title=DESC',
					'theme'   => '',
					'num'     => 0,
					'all'     => true,
					'noshow'  => [],
				],
				$attributes,
				'portfolio'
			);
			$attributes['expires'] = filter_var( $attributes['expires'], FILTER_VALIDATE_INT, [ 'default' => 600 ] );
			$attributes['orderby'] = filter_var( $attributes['orderby'], FILTER_SANITIZE_FULL_SPECIAL_CHARS, [ 'default' => 'modified=DESC&title=DESC' ] );
			$attributes['noshow']  = ( is_array( $attributes['noshow'] ) && 0 !== count( $attributes['noshow'] ) ) ? explode( ',', $attributes['noshow'] ) : [];

			$shortcode_transient_id = 'network_portfolio' . md5( wp_json_encode( $attributes ) );// Create unique transient id pr shortcode used.
			$network_blogs          = get_site_transient( $shortcode_transient_id );
			if ( false === $network_blogs ) {
				$sites         = [];
				$network_blogs = [];
				if ( 0 !== $attributes['sites'] ) {
					$sites = explode( ',', $attributes['sites'] );
					foreach ( $sites as $site ) {
						$network_blogs = array_merge(
							$network_blogs,
							get_sites(
								[
									'ID'       => $site,
									'public'   => true,
									'deleted'  => false,
									'mature'   => false,
									'spam'     => false,
									'archived' => false,
								]
							)
						);
					}
					// Sort on last_updated, newest first.
					usort(
						$network_blogs,
						function ( $a, $b ) {
							return $a->last_updated < $b->last_updated;
						}
					);
				} else {
					$network_blogs = get_sites(
						[
							'public'            => true,
							'deleted'           => false,
							'mature'            => false,
							'spam'              => false,
							'archived'          => false,
							'orderby'           => 'last_updated',
							'order'             => 'DESC',
							'update_site_cache' => true,
							'site__not_in'      => $attributes['noshow'],

						]
					);
				}
				set_site_transient( $shortcode_transient_id, $network_blogs, $attributes['expires'] );
			}

			$current_site = get_current_blog_id();

			$show_in_portfolio = get_site_option( 'network_portfolio' );
			$output_string     = '<ul class="network-portfolio-list">';
			if ( 0 < count( (array) $network_blogs ) ) {
				$num_thumbs = 0;
				$list_sites = [];
				foreach ( $network_blogs as $network_blog_object ) {
					$network_blog = (array) $network_blog_object;
					if ( false === $attributes['all'] && ( ! isset( $show_in_portfolio[ $network_blog['blog_id'] ] ) || 'visible' !== $show_in_portfolio[ $network_blog['blog_id'] ] ) ) {
						continue;
					}

					$network_blog_details = get_blog_details( $network_blog['blog_id'] );
					if ( false === $network_blog_details ) {
						continue;
					}

					switch_to_blog( $network_blog_details->blog_id );
					$network_blog_details->theme       = get_stylesheet();
					$site_url                          = ( function_exists( 'domain_mapping_siteurl' ) && 'NA' !== domain_mapping_siteurl( 'NA' ) ) ? domain_mapping_siteurl( false ) : $network_blog_details->home;
					$network_blog_details->site_url    = $site_url;
					$network_blog_details->blog_public = get_option( 'blog_public', 1 );
					restore_current_blog();

					if ( 2 === $network_blog_details->blog_public ) {
						// Restricted Site Access plug-in is blocking public access to this site.
						continue;
					}

					if ( '' !== $attributes['theme'] && $attributes['theme'] !== $network_blog_details->theme ) {
						continue;
					}

					if ( 0 === $attributes['num'] || $attributes['num'] > $num_thumbs ) {

						$list_sites[] = $network_blog_details;
						++$num_thumbs;
					}
				}
			}

			if ( ! empty( $list_sites ) ) {
				// Sort on blogname, ascending order.
				usort(
					$list_sites,
					function ( $a, $b ) {
						return strcmp( strtolower( $a->blogname ), strtolower( $b->blogname ) );
					}
				);
				foreach ( (array) $list_sites as $list_site ) {
					$output_string .= sprintf( '<li><a href="%s">%s</a></li>', $list_site->site_url, $list_site->blogname );
				}
			}

			$output_string .= '</ul>';

			switch_to_blog( $current_site );
			$GLOBALS['_wp_switched_stack'] = [];
			$GLOBALS['switched']           = false;

			return $output_string;
		}
	} // End Class Portfolio.
} // End If Class Exists.
