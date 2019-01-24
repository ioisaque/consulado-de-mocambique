<?php
$args = array(
			'post_type'   =>'slzexploore_room',
			'orderby'     => 'title'
		);
		
$room_type = Slzexploore_Core_Com::get_post_title2id( $args, array('empty' => esc_html__( '--All Room Types--', 'slzexploore-core' ) ) );
$categories = Slzexploore_Core_Com::get_tax_options2slug( 'slzexploore_room_cat', array('empty' => esc_html__( '--All Categories--', 'slzexploore-core' ) ) );
$filter_by = array(
					esc_html__( 'Room Types', 'slzexploore-core' ) => 'room_type',
					esc_html__( 'Room Type Categories', 'slzexploore-core' ) => 'category'
				);
$params = array(
	array(
		'type'        => 'textfield',
		'heading'     => esc_html__( 'Title', 'slzexploore-core' ),
		'param_name'  => 'title',
		'value'       => '',
		'description' => esc_html__( 'Enter title of block', 'slzexploore-core' ),
	),
	array(
		'type'        => 'dropdown',
		'heading'     => esc_html__( 'Filter By', 'slzexploore-core' ),
		'param_name'  => 'filter_by',
		'value'       => $filter_by,
		'description' => esc_html__( 'Choose criteria to display', 'slzexploore-core'  )
	),
	array(
		'type'       => 'param_group',
		'heading'    => esc_html__( 'Choose Room Types', 'slzexploore-core' ),
		'param_name' => 'room_list',
		'params'     => array(
			array(
				'type'        => 'dropdown',
				'admin_label' => true,
				'heading'     => esc_html__( 'Add Room Type', 'slzexploore-core' ),
				'param_name'  => 'room_id',
				'value'       => $room_type,
				'description' => esc_html__( 'Choose special room to display', 'slzexploore-core'  )
			),
		),
		'value'       => '',
		'description' => esc_html__( 'Default no room is displayed.', 'slzexploore-core' ),
		'dependency' => array(
			'element' => 'filter_by',
			'value'   => 'room_type',
		),
	),
	array(
		'type'       => 'param_group',
		'heading'    => esc_html__( 'Choose Room Type Categories', 'slzexploore-core' ),
		'param_name' => 'category_list',
		'params'     => array(
			array(
				'type'        => 'dropdown',
				'admin_label' => true,
				'heading'     => esc_html__( 'Add Category', 'slzexploore-core' ),
				'param_name'  => 'category_slug',
				'value'       => $categories,
				'description' => esc_html__( 'Choose special category to display', 'slzexploore-core'  )
			),
		),
		'value'       => '',
		'description' => esc_html__( 'Default no room is displayed.', 'slzexploore-core' ),
		'dependency' => array(
			'element' => 'filter_by',
			'value'   => 'category',
		),
	),
	// Read more button
	array(
		'type'        => 'textfield',
		'heading'     => esc_html__( 'Read More Text', 'slzexploore-core' ),
		'param_name'  => 'btn_book',
		'value'       => '',
		'description' => esc_html__( 'Enter content to button', 'slzexploore-core' ),
	),
	array(
		'type'        => 'colorpicker',
		'heading'     => esc_html__( 'Button Text Color', 'slzexploore-core' ),
		'param_name'  => 'btn_book_color',
		'value'       => '',
		'description' => esc_html__( 'Enter text color to button', 'slzexploore-core' ),
	),
	array(
		'type'        => 'colorpicker',
		'heading'     => esc_html__( 'Button Background Color', 'slzexploore-core' ),
		'param_name'  => 'btn_book_bg_color',
		'value'       => '',
		'description' => esc_html__( 'Enter background button color to button', 'slzexploore-core' ),
	),
	array(
		'type'        => 'colorpicker',
		'heading'     => esc_html__( 'Button Hover Background Color', 'slzexploore-core' ),
		'param_name'  => 'btn_book_hover_bg_color',
		'value'       => '',
		'description' => esc_html__( 'Enter background button color to button', 'slzexploore-core' ),
	),
	array(
		'type'        => 'textfield',
		'heading'     => esc_html__( 'Extra Class', 'slzexploore-core' ),
		'param_name'  => 'extra_class',
		'value'       => '',
		'description' => esc_html__( 'Add extra class to block', 'slzexploore-core' )
	),
);
vc_map(array(
	'name'        => esc_html__( 'SLZ Room Types', 'slzexploore-core' ),
	'base'        => 'slzexploore_core_room_type_sc',
	'class'       => 'slzexploore_core-sc',
	'icon'        => 'icon-slzexploore_core_room_type_sc',
	'category'    => SLZEXPLOORE_CORE_SC_CATEGORY,
	'description' => esc_html__( 'A room type list.', 'slzexploore-core' ),
	'params'      => $params
));