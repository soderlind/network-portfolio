<?php
namespace NetworkPortfolio\Customizer;

class Sections {

	public static function add( \WP_Customize_Manager $manager ) {
		$manager->add_section(
			'networkportfolio_section[image]',
			[
				'title'       => __( 'Image', 'networkportfolio' ),
				'description' => __( 'Customize your NetworkPortfolio image', 'networkportfolio' ),
				'priority'    => 5,
				'capability'  => 'manage_options',
			]
		);
		$manager->add_section(
			'networkportfolio_section[settings]',
			[
				'title'      => __( 'Settings', 'networkportfolio' ),
				'priority'   => 15,
				'capability' => 'manage_options',
			]
		);

		return $manager;
	}
}
