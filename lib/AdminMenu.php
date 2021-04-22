<?php
namespace NetworkPortfolio;

if ( ! class_exists( 'NetworkPortfolio\AdminMenu' ) ) {
	class AdminMenu {

		private $plugin_customizer;

		function __construct( \PluginCustomizer\Plugin_Customizer $plugin_customizer ) {
			$this->plugin_customizer = $plugin_customizer;
		}
		/**
		* Create submenu
		* @author soderlind
		* @version 1.0.0
		*/
		public function register_sub_menu() {
			add_options_page( __( 'Network Portfolio', 'networkportfolio' ), __( 'Network Portfolio', 'networkportfolio' ), 'manage_options', 'networkportfolio-template', '__return_null' );
			// add_submenu_page( 'settings.php', __( 'NetworkPortfolio', 'networkportfolio' ), __( 'NetworkPortfolio', 'networkportfolio' ), 'manage_options', 'networkportfolio-template', '__return_null' );
			$this->add_sub_menu_customizer_url();
		}

		/**
		* Replace the 'plugin-template' string, in the submenu added by register_sub_menu(),
		* with the customizer url.
		*
		* @link http://wordpress.stackexchange.com/a/131214/14546
		*
		* @author soderlind
		* @version 1.0.0
		*/
		protected function add_sub_menu_customizer_url( $parent = 'options-general.php' ) {
			global $submenu;

			if ( ! isset( $submenu[ $parent ] ) ) {
				return;
			}
			foreach ( $submenu[ $parent ] as $k => $d ) {
				if ( 'networkportfolio-template' == $d['2'] ) {
					$submenu[ $parent ][ $k ]['2'] = $this->plugin_customizer->get_customizer_url( $parent );
					break;
				}
			}
		}

		/**
		* [add_admin_bar_customizer_url description]
		* @author soderlind
		* @version 1.0.0
		* @param   [type]	$wp_admin_bar [description]
		*/
		public function add_admin_bar_customizer_url( $wp_admin_bar ) {
			global $post;
			if ( Helper::get_option( 'networkportfolio[adminbar]' ) ) {
				if ( is_admin() ) {
					$return_url = self::_get_current_admin_page_url();
				} elseif ( is_object( $post ) ) {
					$return_url = get_permalink( $post->ID );
				} else {
					$return_url = esc_url( home_url( '/' ) );
				}
				$args = array(
				   'id'    => 'plugin-customizer-link2',
				   'title' => __( 'Network Portfolio', 'networkportfolio' ),
				   'href'  => $this->plugin_customizer->get_customizer_url( $return_url, 'networkportfolio_section[image]' ),
				);

				$wp_admin_bar->add_node( $args );
			}
		}

		/**
		 * Find the current admin url.
		 *
		 * @link https://core.trac.wordpress.org/ticket/27888
		 *
		 * @author soderlind
		 * @version 1.0.0
		 * @return  String|Bool    The URL to the current admin page, or false if not in wp-admin.
		 */
		protected function _get_current_admin_page_url() {
			if ( ! is_admin() ) {
				return false;
			}
			global $pagenow;

			$url = $pagenow;
			$query_string = $_SERVER['QUERY_STRING'];

			if ( ! empty( $query_string ) ) {
				$url .= '?' . $query_string;
			}
			return $url;
		}
	}
}
