<?php
$category = Slzexploore_Core_Com::get_category2slug_array();

$cat = array(
	esc_html__('Accommodation','slzexploore-core') => 'slzexploore_hotel',
	esc_html__('Tour', 'slzexploore-core') => 'slzexploore_tour',
	esc_html__('Car', 'slzexploore-core') => 'slzexploore_car',
	esc_html__('Cruise', 'slzexploore-core') => 'slzexploore_cruise',
	esc_html__('Post', 'slzexploore-core') => 'post',
);

$taxonomy_accommodation = 'slzexploore_hotel_cat';
$taxonomy_tour = 'slzexploore_tour_cat';
$taxonomy_car = 'slzexploore_car_cat';
$taxonomy_cruise = 'slzexploore_cruise_cat';
$params_cat = array( 'empty' => esc_html__( '--All Categories--', 'slzexploore-core' ) );

$cat_accommodation = Slzexploore_Core_Com::get_tax_options2slug( $taxonomy_accommodation, $params_cat );
$cat_tour = Slzexploore_Core_Com::get_tax_options2slug( $taxonomy_tour, $params_cat );
$cat_car = Slzexploore_Core_Com::get_tax_options2slug( $taxonomy_car, $params_cat );
$cat_cruise = Slzexploore_Core_Com::get_tax_options2slug( $taxonomy_cruise, $params_cat );

$params = array(
	array(
		'type'          => 'textfield',
		'heading'       => esc_html__( 'Title', 'slzexploore-core' ),
		'param_name'    => 'title',
		'description'   => esc_html__( 'Enter block title.', 'slzexploore-core' ),
	),
	array(
		'type'        => 'dropdown',
		'heading'     => esc_html__( 'Choose Post Type', 'slzexploore-core' ),
		'param_name'  => 'posttype',
		'value'       => $cat,
		'std'         => 'slzexploore_hotel',
		'description' => esc_html__( 'Choose post type to display.', 'slzexploore-core'  ),
	),
	array(
		'type'          => 'textfield',
		'heading'       => esc_html__( 'Title Length', 'slzexploore-core' ),
		'param_name'    => 'title_length',
		'description'   => esc_html__( 'Enter length of title to display. Leave blank if display all.', 'slzexploore-core' ),
	),
	array(
		'type'          => 'textfield',
		'heading'       => esc_html__( 'Offset Post', 'slzexploore-core' ),
		'param_name'    => 'offset_post',
		'description'   => esc_html__( 'Enter offset post.', 'slzexploore-core' ),
	),
	array(
		'type'          => 'textfield',
		'heading'       => esc_html__( 'Limit Post', 'slzexploore-core' ),
		'value'         => '5',
		'param_name'    => 'limit_post',
		'description'   => esc_html__( 'Enter limit post.', 'slzexploore-core' ),
	),
	array(
		'type'          => 'textfield',
		'heading'       => esc_html__( 'Extra Class', 'slzexploore-core' ),
		'param_name'    => 'extra_class',
		'description'   => esc_html__( 'Enter extra class name.', 'slzexploore-core' )
	),
	array(
		'type'       => 'param_group',
		'heading'    => esc_html__( 'Categories', 'slzexploore-core' ),
		'param_name' => 'category_accommodation',
		'params'     => array(
			array(
				'type'        => 'dropdown',
				'admin_label' => true,
				'heading'     => esc_html__( 'Select Category', 'slzexploore-core' ),
				'param_name'  => 'category_slug',
				'value'       => $cat_accommodation,
				'description' => esc_html__( 'Choose special category to filter accommodation', 'slzexploore-core'  )
			),
		),
		'value'       => '',
		'description' => esc_html__( 'Default no filter by category.', 'slzexploore-core' ),
		'group'       => esc_html__( 'Filter By', 'slzexploore-core'),
		'dependency'     => array(
			'element'  => 'posttype',
			'value'    => array( 'slzexploore_hotel' ),
		),
	),
	array(
		'type'       => 'param_group',
		'heading'    => esc_html__( 'Categories', 'slzexploore-core' ),
		'param_name' => 'category_tour',
		'params'     => array(
			array(
				'type'        => 'dropdown',
				'admin_label' => true,
				'heading'     => esc_html__( 'Select Category', 'slzexploore-core' ),
				'param_name'  => 'category_slug',
				'value'       => $cat_tour,
				'description' => esc_html__( 'Choose special category to filter tour', 'slzexploore-core'  )
			),
		),
		'value'       => '',
		'description' => esc_html__( 'Default no filter by category.', 'slzexploore-core' ),
		'group'       => esc_html__( 'Filter By', 'slzexploore-core'),
		'dependency'     => array(
			'element'  => 'posttype',
			'value'    => array( 'slzexploore_tour' ),
		),
	),
	array(
		'type'       => 'param_group',
		'heading'    => esc_html__( 'Categories', 'slzexploore-core' ),
		'param_name' => 'category_car',
		'params'     => array(
			array(
				'type'        => 'dropdown',
				'admin_label' => true,
				'heading'     => esc_html__( 'Select Category', 'slzexploore-core' ),
				'param_name'  => 'category_slug',
				'value'       => $cat_car,
				'description' => esc_html__( 'Choose special category to filter car', 'slzexploore-core'  )
			),
		),
		'value'       => '',
		'description' => esc_html__( 'Default no filter by category.', 'slzexploore-core' ),
		'group'       => esc_html__( 'Filter By', 'slzexploore-core'),
		'dependency'     => array(
			'element'  => 'posttype',
			'value'    => array( 'slzexploore_car' ),
		),
	),
	array(
		'type'       => 'param_group',
		'heading'    => esc_html__( 'Categories', 'slzexploore-core' ),
		'param_name' => 'category_cruise',
		'params'     => array(
			array(
				'type'        => 'dropdown',
				'admin_label' => true,
				'heading'     => esc_html__( 'Select Category', 'slzexploore-core' ),
				'param_name'  => 'category_slug',
				'value'       => $cat_cruise,
				'description' => esc_html__( 'Choose special category to filter cruise', 'slzexploore-core'  )
			),
		),
		'value'       => '',
		'description' => esc_html__( 'Default no filter by category.', 'slzexploore-core' ),
		'group'       => esc_html__( 'Filter By', 'slzexploore-core'),
		'dependency'     => array(
			'element'  => 'posttype',
			'value'    => array( 'slzexploore_cruise' ),
		),
	),
	array(
		'type'       => 'param_group',
		'heading'    => esc_html__( 'Categories', 'slzexploore-core' ),
		'param_name' => 'category_post',
		'params'     => array(
			array(
				'type'        => 'dropdown',
				'admin_label' => true,
				'heading'     => esc_html__( 'Select Category', 'slzexploore-core' ),
				'param_name'  => 'category_slug',
				'value'       => $category,
				'description' => esc_html__( 'Choose special category to filter post', 'slzexploore-core'  )
			),
		),
		'value'       => '',
		'description' => esc_html__( 'Default no filter by category.', 'slzexploore-core' ),
		'group'       => esc_html__( 'Filter By', 'slzexploore-core'),
		'dependency'     => array(
			'element'  => 'posttype',
			'value'    => array( 'post' ),
		),
	),
);
vc_map(array(
	'name'               => esc_html__( 'SLZ Post Carousel', 'slzexploore-core' ),
	'base'               => 'slzexploore_core_post_carousel_sc',
	'class'              => 'slzexploore_core-sc',
	'icon'               => 'icon-slzexploore_core_post_carousel_sc',
	'category'           => SLZEXPLOORE_CORE_SC_CATEGORY,
	'description'        => esc_html__( 'List posts or custom posts in carousel.', 'slzexploore-core' ),
	'params'             => $params
));

