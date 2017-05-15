<?php
namespace NetworkPortfolio\Customizer;

class Settings {

	public static function add( \WP_Customize_Manager $manager ) {

		/**
		 * Image Size
		 */
		$manager->add_setting(
			'networkportfolio[width]',
			array(
				'type'			=> 'site_option',
				'capability'	=> 'manage_options',
				'transport'     => 'postMessage',
				'default'		=> \NetworkPortfolio\Helper::get_option( 'networkportfolio[width]', '400' ),
			)
		);

		$manager->add_setting(
			'networkportfolio[height]',
			array(
				'type'			=> 'site_option',
				'capability'	=> 'manage_options',
				'transport'     => 'postMessage',
				'default'		=> \NetworkPortfolio\Helper::get_option( 'networkportfolio[height]', '250' ),
			)
		);

		$manager->add_setting(
			'networkportfolio[border_radius]',
			array(
				'type'			=> 'site_option',
				'capability'	=> 'manage_options',
				'transport'     => 'postMessage',
				'default'		=> \NetworkPortfolio\Helper::get_option( 'networkportfolio[border_radius]', '0' ),
			)
		);

		$manager->add_setting(
			'networkportfolio[border_color]',
			array(
				'type'			=> 'site_option',
				'capability'	=> 'manage_options',
				'transport'     => 'postMessage',
				'default'		=> \NetworkPortfolio\Helper::get_option( 'networkportfolio[border_color]', '#000000' ),
			)
		);

		$manager->add_setting(
			'networkportfolio[border_width]',
			array(
				'type'			=> 'site_option',
				'capability'	=> 'manage_options',
				'transport'     => 'postMessage',
				'default'		=> \NetworkPortfolio\Helper::get_option( 'networkportfolio[border_width]', '0' ),
			)
		);

		/**
		 * Settings
		 */

		$manager->add_setting(
			'networkportfolio[cloud_name]',
			array(
				'type'			=> 'site_option',
				'capability'	=> 'manage_options',
				'transport'     => 'postMessage',
				'default'		=> \NetworkPortfolio\Helper::get_option( 'networkportfolio[cloud_name]', '0' ),
				'validate_callback' => array( '\NetworkPortfolio\Customizer\Validate', 'cloud_name' ),
			)
		);

		$manager->add_setting(
			'networkportfolio[api_key]',
			array(
				'type'			=> 'site_option',
				'capability'	=> 'manage_options',
				'transport'     => 'postMessage',
				'default'		=> \NetworkPortfolio\Helper::get_option( 'networkportfolio[api_key]', '0' ),
				'validate_callback' => array( '\NetworkPortfolio\Customizer\Validate', 'api_key' ),
			)
		);
		$manager->add_setting(
			'networkportfolio[api_secret]',
			array(
				'type'			=> 'site_option',
				'capability'	=> 'manage_options',
				'transport'     => 'postMessage',
				'default'		=> \NetworkPortfolio\Helper::get_option( 'networkportfolio[api_secret]', '0' ),
				'validate_callback' => array( '\NetworkPortfolio\Customizer\Validate', 'api_secret' ),
			)
		);
		$manager->add_setting(
			'networkportfolio[adminbar]',
			array(
				'type'			=> 'site_option',
				'capability'	=> 'manage_options',
				'transport'     => 'postMessage',
				'default'		=> \NetworkPortfolio\Helper::get_option( 'networkportfolio[adminbar]', false ),
			)
		);

		return $manager;
	}
}
