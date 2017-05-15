<?php
namespace NetworkPortfolio\Customizer;

class Controls {

	public static function add( \WP_Customize_Manager $manager ) {

		/**
		 * Image Size
		 */

		$manager->add_control( new \NetworkPortfolio\Customizer\CustomControls\Customizer_RangeValue_Control( $manager, 'width', array(
		    'type'     => 'range-value',
		    'section'  => 'networkportfolio_section[image]',
		    'settings' => 'networkportfolio[width]',
		    'label'    => __( 'Width', 'networkportfolio' ),
		    'input_attrs' => array(
		        'min'     => 5,
		        'max'     => 1000,
		        'step'    => 5,
				'suffix'  => 'px',
		    ),
		) ) );

		$manager->add_control( new \NetworkPortfolio\Customizer\CustomControls\Customizer_RangeValue_Control( $manager, 'height', array(
		    'type'     => 'range-value',
		    'section'  => 'networkportfolio_section[image]',
		    'settings' => 'networkportfolio[height]',
		    'label'    => __( 'Height', 'networkportfolio' ),
		    'input_attrs' => array(
		        'min'     => 5,
		        'max'     => 1000,
		        'step'    => 5,
				'suffix'  => 'px',
		    ),
		) ) );

		$manager->add_control( new \NetworkPortfolio\Customizer\CustomControls\Customizer_RangeValue_Control( $manager, 'border_width', array(
			'type'     => 'range-value',
			'section'  => 'networkportfolio_section[image]',
			'settings' => 'networkportfolio[border_width]',
			'label'    => __( 'Border Width', 'networkportfolio' ),
			'input_attrs' => array(
				 'min'     => 0,
				 'max'     => 50,
				 'step'    => 1,
				 'suffix'  => 'px',
			 ),
		) ) );

		$manager->add_control( new \NetworkPortfolio\Customizer\CustomControls\Customizer_RangeValue_Control( $manager, 'radius', array(
		    'type'     => 'range-value',
		    'section'  => 'networkportfolio_section[image]',
		    'settings' => 'networkportfolio[border_radius]',
		    'label'    => __( 'Border Radius', 'networkportfolio' ),
		    'input_attrs' => array(
		        'min'     => 0,
		        'max'     => 100,
		        'step'    => 1,
				'suffix'  => 'px',
		    ),
		) ) );

		$manager->add_control( new \WP_Customize_Color_Control( $manager, 'border_color', array(
			'label' => __( 'Border Color', 'networkportfolio' ),
			'section'  => 'networkportfolio_section[image]',
			'settings' => 'networkportfolio[border_color]',
		) ) );

		/**
		 * Settings
		 */

		$manager->add_control(  new \WP_Customize_Control(
			$manager,
			'domain',
			array(
				'label'    => __( 'Cloud Name', 'networkportfolio' ),
				'type'     => 'text',
				'section'  => 'networkportfolio_section[settings]',
				'settings' => 'networkportfolio[cloud_name]',
			)
		) );

		$manager->add_control(  new \WP_Customize_Control(
			$manager,
			'key',
			array(
				'label'    => __( 'API Key', 'networkportfolio' ),
				'type'     => 'text',
				'section'  => 'networkportfolio_section[settings]',
				'settings' => 'networkportfolio[api_key]',
			)
		) );

		$manager->add_control(  new \WP_Customize_Control(
			$manager,
			'secret',
			array(
				'label'    => __( 'API Secret', 'networkportfolio' ),
				'type'     => 'text',
				'section'  => 'networkportfolio_section[settings]',
				'settings' => 'networkportfolio[api_secret]',
			)
		) );

		$manager->add_control(  new \WP_Customize_Control(
			$manager,
			'adminbar',
			array(
				'label'    => __( 'Add to admin bar', 'networkportfolio' ),
				'type'     => 'checkbox',
				'description' => __( 'Add the NetworkPortfolio customize link to the admin bar.', 'networkportfolio' ),
				'section'  => 'networkportfolio_section[settings]',
				'settings' => 'networkportfolio[adminbar]',
			)
		) );

		return $manager;
	}
}
