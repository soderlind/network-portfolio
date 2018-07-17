<?php

namespace NetworkPortfolio\Shortcodes;


if ( ! class_exists( 'NetworkPortfolio\Shortcodes\Portfolio' ) ) {
	class Portfolio {

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
			add_shortcode( 'portfolio', array( $this, 'portfolio' ) );
			if ( is_network_admin() ) {
				add_action( 'admin_enqueue_scripts', array( $this, 'network_admin_scripts' ) );
				add_filter( 'wpmu_blogs_columns', array( $this, 'add_new_columns' ) );
				add_action( 'manage_sites_custom_column', array( $this, 'manage_columns' ), 10, 2 );
				add_action( 'wpmu_new_blog', array( $this, 'new_site_deletes_transient' ), 10, 6 );
				add_action(
					'plugins_loaded', function() {
						load_plugin_textdomain( 'network-portfolio', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
					}
				);
				// add_filter( 'manage_sites-network_sortable_columns', array( $this, 'portfolio_column_register_sortable' ) ); //nonworkable, yet
			}
			if ( is_admin() ) {
				add_action( 'wp_ajax_change_portfolio_status', array( $this, 'ajax_change_portfolio_status' ) );
			}
			add_action( 'wp_enqueue_scripts', array( $this, 'network_portfolio_scripts' ) );
		}

		public function portfolio( $attributes ) {
			global $wp_version;

			$attributes = shortcode_atts(
				array(
					'sites'   => 0,
					'width'   => 0,
					'height'  => 0,
					'expires' => 600, //10 minutes
					'orderby' => 'modified=DESC&title=DESC',
					'theme'   => '',
					'num'     => 0,
				), $attributes, 'networkportfolio'
			);

			//validate
			// $attributes['cols']     = filter_var( $attributes['cols'],     FILTER_VALIDATE_INT, array( 'default' => 3 ) );
			$attributes['expires'] = filter_var( $attributes['expires'], FILTER_VALIDATE_INT, array( 'default' => 600 ) );
			$attributes['orderby'] = filter_var( $attributes['orderby'], FILTER_SANITIZE_STRING, array( 'default' => 'modified=DESC&title=DESC' ) );

			$sites         = array();
			$network_blogs = array();
			if ( 0 != $attributes['sites'] ) {
				$sites = explode( ',', $attributes['sites'] );
				// \NetworkPortfolio\Helper::write_log( $sites );
				foreach ( $sites as $site ) {
					$network_blogs = array_merge(
						$network_blogs, get_sites(
							array(
								'ID'     => $site,
								'public' => true,
							)
						)
					);
				}
				// sort on last_updated, newest first
				usort(
					$network_blogs, function( $a, $b ) {
						return $a->last_updated < $b->last_updated;
					}
				);
			} else {
				if ( false === ( $network_blogs = get_site_transient( 'network_blogs' ) ) ) {
					$network_blogs = get_sites(
						array(
							'public'            => true,
							'orderby'           => 'last_updated',
							'order'             => 'DESC',
							'update_site_cache' => true,
						)
					);
					set_site_transient( 'network_blogs', $network_blogs, $attributes['expires'] );
				}
				set_site_transient( 'network_blogs', $network_blogs, $attributes['expires'] );
			}

			$current_site = get_current_blog_id();

			$thumb_settings = array(
				'width'         => ( 0 != $attributes['width'] ) ? $attributes['width'] : \NetworkPortfolio\Helper::get_option( 'networkportfolio[width]', '430' ),
				'height'        => ( 0 != $attributes['height'] ) ? $attributes['height'] : \NetworkPortfolio\Helper::get_option( 'networkportfolio[height]', '225' ),
				'border_width'  => \NetworkPortfolio\Helper::get_option( 'networkportfolio[border_width]', '0' ),
				'border_radius' => \NetworkPortfolio\Helper::get_option( 'networkportfolio[border_radius]', '0' ),
				'border_color'  => \NetworkPortfolio\Helper::get_option( 'networkportfolio[border_color]', '#000000' ),
			);

			$show_in_portfolio = get_site_option( 'network_portfolio' );
			$output_string     = '<div class="network-portfolio">';
			if ( 0 < count( (array) $network_blogs ) ) {
				$num_thumbs = 0;
				foreach ( $network_blogs as $network_blog_object ) {
					$network_blog = (array) $network_blog_object;
					if ( ! isset( $show_in_portfolio[ $network_blog['blog_id'] ] ) || 'visible' != $show_in_portfolio[ $network_blog['blog_id'] ] ) {
						continue;
					}

					$network_blog_details = get_blog_details( $network_blog['blog_id'] );

					switch_to_blog( $network_blog_details->blog_id );
					$network_blog_details->theme = get_stylesheet();
					restore_current_blog();

					if ( '' != $attributes['theme'] && $attributes['theme'] != $network_blog_details->theme ) {
						continue;
					}

					$header_image_url = '';
					$site_url         = ( function_exists( 'domain_mapping_siteurl' ) && 'NA' != domain_mapping_siteurl( 'NA' ) ) ? domain_mapping_siteurl( false ) : $network_blog_details->home;

					$site_url = $network_blog_details->home;

					$thumb_settings['url']         = $site_url;
					$thumb_settings['title']       = $network_blog_details->blogname;
					$thumb_settings['description'] = get_bloginfo( 'description' );

					if ( 0 === $attributes['num'] || $attributes['num'] > $num_thumbs ) {
						$header_image_url = $this->webshot( $thumb_settings );
						$output_string   .= $header_image_url;
						$num_thumbs++;
					}
				} // End foreach().
			} // End if().
			$output_string .= '</div>';

			switch_to_blog( $current_site );
			$GLOBALS['_wp_switched_stack'] = array();
			$GLOBALS['switched']           = false;

			return $output_string;
		}


		public function webshot( $arguments ) {

			$cloud_name = \NetworkPortfolio\Helper::get_option( 'networkportfolio[cloud_name]' );
			$api_key    = \NetworkPortfolio\Helper::get_option( 'networkportfolio[api_key]' );
			$api_secret = \NetworkPortfolio\Helper::get_option( 'networkportfolio[api_secret]' );

			if ( false !== \NetworkPortfolio\Helper::is_valid_cloudinary_account( $cloud_name, $api_key, $api_secret ) ) {
				$border = array();
				if ( 0 !== $arguments['border_width'] ) {
					$border['border'] = array(
						'width' => $arguments['border_width'],
						'color' => $arguments['border_color'],
					);
				}

				$settings = array(
					'type'         => 'url2png',
					'crop'         => 'fill',
					'gravity'      => 'north',
					'fetch_format' => 'auto',
					'width'        => $arguments['width'],
					'height'       => $arguments['height'],
					'radius'       => $arguments['border_radius'],
					'sign_url'     => true,
				);

				// fix cloudinary radius bug (makes a radis even though radius = 0. so don't send radius parameter when it's 0)
				if ( 0 === $settings['radius'] ) {
					unset( $settings['radius'] );
				}

				if ( count( $border ) ) {
					$settings = array_merge( $settings, $border );
				}

				$img_width  = $arguments['width'];
				$img_height = $arguments['height'];
				if ( 0 !== $arguments['border_width'] ) {
					$img_width  = $img_width + ( $arguments['border_width'] * 2 );
					$img_height = $img_height + ( $arguments['border_width'] * 2 );
				}

				return  sprintf(
					'<div class="network-portfolio-item" style="width:%3$spx; Xheight:%4$spx;">
									<a href="%1$s">
									<img src="%2$s" width="%3$s" height="%4$s" />
									<h2 class="network-portfolio-title">
										%5$s
									</h2>
									<p class="network-portfolio-description">%6$s</p>
									</a>
								  </div>',
					$arguments['url'],
					cloudinary_url( $arguments['url'], $settings ),
					$img_width,
					$img_height,
					$arguments['title'],
					$arguments['description']
				);
			} else {
				return sprintf( '<!--invalid_cloudinary_account %s-->', print_r( $arguments, true ) );
			} // End if().
		}


		/**
		 * Helper query "where" method, find posts only listed in menues (assuming only posts in menues are interesting)
		 * @author soderlind
		 * @version [version]
		 * @param   [type]    $where [description]
		 * @return  [type]           [description]
		 */
		function where_pages_in_menu( $where ) {
			global $wpdb;
			if ( count( $this->pages_not_in_menu ) > 0 ) {
				$where .= ' AND ' . $wpdb->posts . '.ID NOT IN(' . implode( ',', $this->pages_not_in_menu ) . ') ';
			}
			return $where;
		}

		/**
		 *   Portfolio management (show, don't show)
		 */

		/**
		 * Adding a new site deletes transient
		 *
		 * @param int     $blog_id Blog ID.
		 * @param int     $user_id User ID.
		 * @param string  $domain  Site domain.
		 * @param string  $path    Site path.
		 * @param int     $site_id Site ID. Only relevant on multi-network installs.
		 * @param array   $meta    Meta data. Used to set initial site options.
		 */
		function new_site_deletes_transient( $blog_id, $user_id, $domain, $path, $site_id, $meta ) {
			delete_site_transient( 'network_blogs' );
		}

		/**
		 * Change the portfolio status (vissible / hidden), also reset the single site post query transient
		 *
		 * @param int     $site_id
		 * @param string  $status  'vissible' or 'hidden'
		 * @return [type]          [description]
		 */
		public function change_portfolio_status( $site_id, $status ) {
			if ( false !== ( $portfolio = get_site_option( 'network_portfolio' ) ) ) {
				$portfolio[ $site_id ] = $status;
				update_site_option( 'network_portfolio', $portfolio );
			} else {
				add_site_option( 'network_portfolio', array( $site_id => $status ) );
			}
			delete_site_transient( "site_post_query_{$site_id}" );
		}

		/**
		 * Add new column function
		 *
		 * @param [type]  $columns [description]
		 */
		public function add_new_columns( $columns ) {
			$columns['portfolio'] = __( 'Portfolio', 'dss-web' );
			return $columns;
		}

		/**
		 * Render columns function
		 *
		 * @param [type]  $column_name [description]
		 * @param [type]  $site_id     [description]
		 * @return [type]              [description]
		 */
		public function manage_columns( $column_name, $site_id ) {

			if ( 'portfolio' == $column_name ) {

				$site      = get_blog_details( $site_id );
				$portfolio = get_site_option( 'network_portfolio' );

				echo '<div class="pstatus">';
				if ( '1' == $site->deleted || '2' == $site->deleted || '1' == $site->archived || '1' == $site->spam ) {
					$this->change_portfolio_status( $site_id, 'hidden' );
					echo __( 'Hidden', 'multisite-portfolio' );
				} elseif ( $portfolio && isset( $portfolio[ $site_id ] ) ) {
					switch ( $portfolio[ $site_id ] ) {
						case 'visible':
							printf(
								'<a href="#" class="ms-portfolio" title="%s" data-changeto="hidden" data-siteid="%s">%s</a>',
								__( 'Click to hide site from portfolio', 'multisite-portfolio' ),
								$site_id,
								__( 'Visible', 'multisite-portfolio' )
							);
							break;
						case 'hidden':
							printf(
								'<a href="#" class="ms-portfolio" title="%s" data-changeto="visible" data-siteid="%s">%s</a>',
								__( 'Click to add site to portfolio', 'multisite-portfolio' ),
								$site_id,
								__( 'Hidden', 'multisite-portfolio' )
							);
							break;
					}
				} else {
					printf(
						'<a href="#" class="ms-portfolio" title="%s" data-changeto="visible" data-siteid="%s">%s</a>',
						__( 'Click to add site to portfolio', 'multisite-portfolio' ),
						$site_id,
						__( 'Hidden', 'multisite-portfolio' )
					);          }
				echo '</div>';
			}
		}

		/**
		 * Register the column as sortable
		 *
		 * @param array   $columns [description]
		 * @return array          [description]
		 */
		function portfolio_column_register_sortable( $columns ) {
			$columns['portfolio'] = 'portfolio';
			return $columns;
		}


		function network_admin_scripts() {

			// multisite fix, use home_url() if domain mapped to avoid cross-domain issues
			$http_scheme = ( is_ssl() ) ? 'https' : 'http';
			if ( home_url() != site_url() ) {
				$ajaxurl = home_url( '/wp-admin/admin-ajax.php', $http_scheme );
			} else {
				$ajaxurl = site_url( '/wp-admin/admin-ajax.php', $http_scheme );
			}
			$url = plugins_url( '', __FILE__ );
			wp_enqueue_script( 'change_portfolio_status', $url . '/assets/js/network-portfolio.js', array( 'jquery', 'jquery-effects-core' ), NETWORKPORTFOLIO_VERSION );
			wp_enqueue_style( 'network-portfolio', $url . '/assets/css/network-portfolio.css', NETWORKPORTFOLIO_VERSION );
			wp_localize_script(
				'change_portfolio_status', 'network_portfolio', array(
					'nonce'   => wp_create_nonce( 'portfolio_security' ),
					'ajaxurl' => $ajaxurl,
				)
			);
		}

		function network_portfolio_scripts() {
			$url = plugins_url( '', __FILE__ );
			wp_enqueue_style( 'network-portfolio', $url . '/assets/css/network-portfolio.css', NETWORKPORTFOLIO_VERSION );
		}

		/**
		 * Ajax callback function
		 *
		 * @return json encoded string
		 */
		public function ajax_change_portfolio_status() {

			header( 'Content-type: application/json' );
			if ( check_ajax_referer( 'portfolio_security', 'security', false ) ) {
				$site_id   = filter_var( $_POST['site_id'], FILTER_VALIDATE_INT, array( 'default' => 0 ) );
				$change_to = filter_var( $_POST['change_to'], FILTER_SANITIZE_STRING, array( 'default' => 'hidden' ) );
				if ( ! $site_id ) {
					$response['data'] = 'something went wrong ...';
					echo json_encode( $response );
					die();
				}
				if ( isset( $change_to ) ) {
					$this->change_portfolio_status( $site_id, $change_to );
					if ( 'hidden' == $change_to ) {
						$response['text']      = __( 'Hidden', 'multisite-portfolio' );
						$response['change_to'] = 'visible';
					} else {
						$response['text']      = __( 'Visible', 'multisite-portfolio' );
						$response['change_to'] = 'hidden';
					}
					$response['response'] = 'success';
				} else {
					$response['response'] = 'failed';
					$response['data']     = 'something went wrong ...';
				}
			} else {
				$response['response'] = 'failed';
				$response['message']  = 'invalid nonse';
			}
			echo json_encode( $response );
			die();
		}
	}
} // End if().
