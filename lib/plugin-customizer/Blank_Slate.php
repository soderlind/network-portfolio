<?php
/**
 * Code from https://github.com/xwp/wp-customizer-blank-slate
 *
 * Learn more at: https://make.xwp.co/2016/09/11/resetting-the-customizer-to-a-blank-slate/
 * Copyright (c) 2016 XWP (https://make.xwp.co/)
 */
namespace PluginCustomizer;

if ( ! class_exists( 'PluginCustomizer\Blank_Slate' ) ) {
	class Blank_Slate {

		private $slug;
		private $plugin_url;
		private $plugin_root;

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

		function init( $slug = '', $plugin_url, $plugin_root ) {
			// self::$param_name = sanitize_title( get_called_class() , 'plugin_customizer' );
			if ( '' == $slug ) {
				wp_die( 'slug missing', $title = 'BlankSlate::init' );
			}
			$this->slug = $slug;
			$this->plugin_url = $plugin_url;
			$this->plugin_root = $plugin_root;
			add_filter( 'customize_loaded_components', function() {

				$priority = 1;
				add_action( 'wp_loaded', function() {

					global $wp_customize;
					remove_all_actions( 'customize_register' );
					$wp_customize->register_panel_type( 'WP_Customize_Panel' );
					$wp_customize->register_section_type( 'WP_Customize_Section' );
					$wp_customize->register_section_type( 'WP_Customize_Sidebar_Section' );
					$wp_customize->register_control_type( 'WP_Customize_Color_Control' );
					$wp_customize->register_control_type( 'WP_Customize_Media_Control' );
					$wp_customize->register_control_type( 'WP_Customize_Upload_Control' );
					$wp_customize->register_control_type( 'WP_Customize_Image_Control' );
					$wp_customize->register_control_type( 'WP_Customize_Background_Image_Control' );
					$wp_customize->register_control_type( 'WP_Customize_Cropped_Image_Control' );
					$wp_customize->register_control_type( 'WP_Customize_Site_Icon_Control' );
					$wp_customize->register_control_type( 'WP_Customize_Theme_Control' );
				}, $priority );
				$components = array();

				return $components;
			} );
			add_action( 'customize_controls_init', function() {
				global $wp_customize;
				$wp_customize->set_preview_url(
					add_query_arg(
						array( $this->slug => 'on' ),
						$wp_customize->get_preview_url()
					)
				);
			} );

			add_action( 'customize_controls_enqueue_scripts', function() {
				$handle = 'plugin-customizer-blank-slate';
				$src = $this->plugin_url . 'assets/js/plugin-customizer-blank-slate.js';
				$deps = array( 'customize-controls' );
				$ver = false;
				$in_footer = true;
				wp_enqueue_script( $handle, $src, $deps, $ver, $in_footer );

				$args = array(
					'queryParamName' => $this->slug,
					'queryParamValue' => 'on',
				);
				wp_add_inline_script(
					$handle,
					sprintf( 'PluginCustomizerBlankSlate.init( %s );', wp_json_encode( $args ) ),
					'after'
				);
			} );
		}
	}
}
