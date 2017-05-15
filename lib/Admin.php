<?php
namespace NetworkPortfolio;

class Admin extends \PluginCustomizer\Plugin_Customizer implements \PluginCustomizer\Plugin_Customizer_Interface {

	function __construct() {
		$this->template_root = plugin_dir_path( __FILE__ ) . 'Customizer/assets/';
		\PluginCustomizer\Plugin_Customizer::init( array(
			'name' => 'NetworkPortfolio', // name your plugin
			'url'  => NETWORKPORTFOLIO_URL,
			'path' => NETWORKPORTFOLIO_PATH,
		) );
		$this->admin_menus();
	}

	public function admin_menus() {
		add_action( 'admin_bar_menu', array( new \NetworkPortfolio\AdminMenu( $this ), 'add_admin_bar_customizer_url' ), 500 );
		add_action( 'admin_menu', array( new \NetworkPortfolio\AdminMenu( $this ), 'register_sub_menu' ) );
	}

	/**
	 * Load default template
	 * Optionally load separate templates for the customizer sections.
	 *
	 * @author soderlind
	 * @version 1.0.0
	 */
	public function plugin_customizer_add_templates() {
		$cloud_name = \NetworkPortfolio\Helper::get_option( 'networkportfolio[cloud_name]' );
		$api_key    = \NetworkPortfolio\Helper::get_option( 'networkportfolio[api_key]' );
		$api_secret = \NetworkPortfolio\Helper::get_option( 'networkportfolio[api_secret]' );

		if ( false !== \NetworkPortfolio\Helper::is_valid_cloudinary_account( $cloud_name, $api_key, $api_secret ) ) {
			$default_template = \PluginCustomizer\Plugin_Customizer::template_url( 'customize' );
		} else {
			$default_template = \PluginCustomizer\Plugin_Customizer::template_url( 'settings' );
		}
		/**
		 * The default template used when opening the customizer
		 * @var array
		 */

		$default_url = array( 'url' => $default_template );
		/**
		 * Add a template to a section. key = section name, value = url to template.
		 *
		 * The last part, title and content in the exmple below, will translate to title.php
		 * and content.php in the templates folder.
		 */
		$section_urls = array(
			'networkportfolio_section[image]' => \PluginCustomizer\Plugin_Customizer::template_url( 'customize' ),
			'networkportfolio_section[settings]'  => \PluginCustomizer\Plugin_Customizer::template_url( 'settings' ),
		);
		\PluginCustomizer\Plugin_Customizer::add_templates( $default_url, $section_urls );
		// \PluginCustomizer\Plugin_Customizer::add_templates( $default_url );
	}

	/**
	 * Load the preview script. The script is needed sice the transport is postmessage
	 * @author soderlind
	 * @version 1.0.0
	 */
	public function plugin_customizer_previewer_postmessage_script() {

		$handle = 'boxshadow-css-hook';
		$src = plugins_url( 'Customizer/assets/js/boxshadow.js', __FILE__ );
		$deps = array( 'jquery' );
		$version = NETWORKPORTFOLIO_VERSION;
		$in_footer = 1;
		wp_enqueue_script( $handle, $src, $deps, $version , $in_footer );

		$handle = 'networkportfolio-cusomizer-init';
		$src = plugins_url( 'Customizer/assets/js/plugin-customizer-init.js', __FILE__ );
		$deps = array( 'boxshadow-css-hook', 'customize-preview', 'jquery' );
		$version = NETWORKPORTFOLIO_VERSION;
		$in_footer = 1;
		wp_enqueue_script( $handle, $src, $deps, $version , $in_footer );
	}

	/**
	 * Customizer Sections, Settings & Controls
	 */

	/**
	 * Add sections.
	 *
	 * @author soderlind
	 * @version 1.0.0
	 * @param   WP_Customize_Manager $wp_customize
	 */
	public function customizer_plugin_sections( $wp_customize ) {
		global $wp_customize;

		$wp_customize = \NetworkPortfolio\Customizer\Sections::add( $wp_customize );

		return true;
	}

	/**
	 * Add settings.
	 *
	 * @author soderlind
	 * @version 1.0.0
	 * @param   WP_Customize_Manager $wp_customize
	 */
	public function customizer_plugin_settings( $wp_customize ) {
		global $wp_customize;

		$wp_customize = \NetworkPortfolio\Customizer\Settings::add( $wp_customize );

		return true;
	}

	/**
	 * Add contronls.
	 *
	 * @author soderlind
	 * @version 1.0.0
	 * @param   WP_Customize_Manager $wp_customize
	 */
	public function customizer_plugin_controls( $wp_customize ) {
		global $wp_customize;

		$wp_customize = \NetworkPortfolio\Customizer\Controls::add( $wp_customize );

		return true;
	}
} // class
