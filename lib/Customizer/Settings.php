<?php
/**
 * Customizer Settings.
 *
 * @package NetworkPortfolio\Customizer
 */

namespace NetworkPortfolio\Customizer;

/**
 * Settings Class.
 */
class Settings {

	/**
	 * Add settings.
	 *
	 * @param \WP_Customize_Manager $manager Settings manager.
	 * @return \WP_Customize_Manager
	 */
	public static function add( \WP_Customize_Manager $manager ) {

		/**
		 * Image Size
		 */
		$manager->add_setting(
			'networkportfolio[width]',
			[
				'type'       => 'site_option',
				'capability' => 'manage_options',
				'transport'  => 'postMessage',
				'default'    => \NetworkPortfolio\Helper::get_option( 'networkportfolio[width]', '400' ),
			]
		);

		$manager->add_setting(
			'networkportfolio[height]',
			[
				'type'       => 'site_option',
				'capability' => 'manage_options',
				'transport'  => 'postMessage',
				'default'    => \NetworkPortfolio\Helper::get_option( 'networkportfolio[height]', '250' ),
			]
		);

		$manager->add_setting(
			'networkportfolio[border_radius]',
			[
				'type'       => 'site_option',
				'capability' => 'manage_options',
				'transport'  => 'postMessage',
				'default'    => \NetworkPortfolio\Helper::get_option( 'networkportfolio[border_radius]', '0' ),
			]
		);

		$manager->add_setting(
			'networkportfolio[border_color]',
			[
				'type'       => 'site_option',
				'capability' => 'manage_options',
				'transport'  => 'postMessage',
				'default'    => \NetworkPortfolio\Helper::get_option( 'networkportfolio[border_color]', '#000000' ),
			]
		);

		$manager->add_setting(
			'networkportfolio[border_width]',
			[
				'type'       => 'site_option',
				'capability' => 'manage_options',
				'transport'  => 'postMessage',
				'default'    => \NetworkPortfolio\Helper::get_option( 'networkportfolio[border_width]', '0' ),
			]
		);

		/**
		 * Settings
		 */

		$manager->add_setting(
			'networkportfolio[cloud_name]',
			[
				'type'              => 'site_option',
				'capability'        => 'manage_options',
				'transport'         => 'postMessage',
				'default'           => \NetworkPortfolio\Helper::get_option( 'networkportfolio[cloud_name]', '0' ),
				'validate_callback' => [ '\NetworkPortfolio\Customizer\Validate', 'cloud_name' ],
			]
		);

		$manager->add_setting(
			'networkportfolio[api_key]',
			[
				'type'              => 'site_option',
				'capability'        => 'manage_options',
				'transport'         => 'postMessage',
				'default'           => \NetworkPortfolio\Helper::get_option( 'networkportfolio[api_key]', '0' ),
				'validate_callback' => [ '\NetworkPortfolio\Customizer\Validate', 'api_key' ],
			]
		);
		$manager->add_setting(
			'networkportfolio[api_secret]',
			[
				'type'              => 'site_option',
				'capability'        => 'manage_options',
				'transport'         => 'postMessage',
				'default'           => \NetworkPortfolio\Helper::get_option( 'networkportfolio[api_secret]', '0' ),
				'validate_callback' => [ '\NetworkPortfolio\Customizer\Validate', 'api_secret' ],
			]
		);
		$manager->add_setting(
			'networkportfolio[adminbar]',
			[
				'type'       => 'site_option',
				'capability' => 'manage_options',
				'transport'  => 'postMessage',
				'default'    => \NetworkPortfolio\Helper::get_option( 'networkportfolio[adminbar]', false ),
			]
		);

		return $manager;
	}
}
