<?php
$post_type = array(
	esc_html__('Accommodation', 'slzexploore-core') => 'slzexploore_hotel',
	esc_html__('Car', 'slzexploore-core')           => 'slzexploore_car',
	esc_html__('Cruise', 'slzexploore-core')        => 'slzexploore_cruise',
	esc_html__('Tour', 'slzexploore-core')          => 'slzexploore_tour'
);
$sort_hotel  = Slzexploore_Core::get_params('sort-accomodation');
$sort_tour   = Slzexploore_Core::get_params('sort-tour');
$sort_car    = Slzexploore_Core::get_params('sort-car');
$sort_cruise = Slzexploore_Core::get_params('sort-cruise');
$columns = array(
	esc_html__('Two', 'slzexploore-core')     => '2',
	esc_html__('Three', 'slzexploore-core')   => '3',
);

$params = array(
	array(
		'type'           => 'dropdown',
		'heading'        => esc_html__( 'Choose Post Type', 'slzexploore-core' ),
		'param_name'     => 'post_type',
		'value'          => $post_type,
		'description'    => esc_html__( 'Choose style to display.', 'slzexploore-core' ),
	),
	array(
		'type'        => 'dropdown',
		'heading'     => esc_html__( 'Post Columns', 'slzexploore-core' ),
		'param_name'  => 'columns',
		'value'       => $columns,
		'std'         => '2',
		'description' => esc_html__( 'Choose column to display grid post.', 'slzexploore-core' ),
	),
	array(
		'type'          => 'textfield',
		'heading'       => esc_html__( 'Offset Post', 'slzexploore-core' ),
		'param_name'    => 'offset_post',
		'description'   => esc_html__( 'Enter number of offset post.', 'slzexploore-core' )
	),
	array(
		'type'        => 'dropdown',
		'heading'     => esc_html__( 'Sort by', 'slzexploore-core' ),
		'param_name'  => 'sort_hotel',
		'value'       => $sort_hotel,
		'description' => esc_html__( 'Select order to display list posts.', 'slzexploore-core' ),
		'dependency'     => array(
			'element'  => 'post_type',
			'value'    => array( 'slzexploore_hotel' )
		)
	),
	array(
		'type'        => 'dropdown',
		'heading'     => esc_html__( 'Sort by', 'slzexploore-core' ),
		'param_name'  => 'sort_tour',
		'value'       => $sort_tour,
		'description' => esc_html__( 'Select order to display list posts.', 'slzexploore-core' ),
		'dependency'     => array(
			'element'  => 'post_type',
			'value'    => array( 'slzexploore_tour' )
		)
	),
	array(
		'type'        => 'dropdown',
		'heading'     => esc_html__( 'Sort by', 'slzexploore-core' ),
		'param_name'  => 'sort_car',
		'value'       => $sort_car,
		'description' => esc_html__( 'Select order to display list posts.', 'slzexploore-core' ),
		'dependency'     => array(
			'element'  => 'post_type',
			'value'    => array( 'slzexploore_car' )
		)
	),
	array(
		'type'        => 'dropdown',
		'heading'     => esc_html__( 'Sort by', 'slzexploore-core' ),
		'param_name'  => 'sort_cruise',
		'value'       => $sort_cruise,
		'description' => esc_html__( 'Select order to display list posts.', 'slzexploore-core' ),
		'dependency'     => array(
			'element'  => 'post_type',
			'value'    => array( 'slzexploore_cruise' )
		)
	),
	array(
		'type'          => 'textfield',
		'heading'       => esc_html__( 'Limit Post', 'slzexploore-core' ),
		'param_name'    => 'limit_post',
		'value'         => '6',
		'description'   => esc_html__( 'Enter number of limit post.', 'slzexploore-core' )
	),
	array(
		'type'            => 'checkbox',
		'heading'         => esc_html__( 'Insert Container?', 'slzexploore-core' ),
		'param_name'      => 'is_container',
		'value'           => array( esc_html__( 'Yes', 'slzexploore-core' ) => 'yes' ),
		'description' => esc_html__( 'Checked to insert container for full width (when select "Stretch row and content" on vc_row)', 'slzexploore-core' ),
	),
	array(
		"type"        => "attach_image",
		"heading"     => esc_html__( 'Map Marker Image', 'slzexploore-core' ),
		"param_name"  => "map_marker",
		'value'       => '',
		'group' =>esc_html__( 'Map Settings', 'slzexploore-core' ),
	),
	array(
		"type"        => "attach_image",
		"heading"     => esc_html__( 'Clusterer Image', 'slzexploore-core' ),
		"param_name"  => "cluster_image",
		'value'       => '',
		'group' =>esc_html__( 'Map Settings', 'slzexploore-core' ),
	),
	array(
		'type'           => 'textfield',
		'heading'        => esc_html__( 'Zoom', 'slzexploore-core' ),
		'param_name'     => 'zoom',
		'value'          => '14',
		'description'    => esc_html__( 'Enter zoom number of map. Number between 0 (farthest) and 22 that sets the zoom level of the map.', 'slzexploore-core' ),
		'group' =>esc_html__( 'Map Settings', 'slzexploore-core' ),
	),
	array(
		'type'           => 'textfield',
		'heading'        => esc_html__( 'Map Height', 'slzexploore-core' ),
		'param_name'     => 'height',
		'description'    => esc_html__( 'Enter height for map(Unit is px).', 'slzexploore-core' ),
		'group' =>esc_html__( 'Map Settings', 'slzexploore-core' ),
	),	
	array(
		'type'          => 'textfield',
		'heading'       => esc_html__( 'Extra Class', 'slzexploore-core' ),
		'param_name'    => 'extra_class',
		'description'   => esc_html__( 'Enter extra class name.', 'slzexploore-core' )
	)
);
vc_map(array(
	'name'               => esc_html__( 'SLZ Map Location', 'slzexploore-core' ),
	'base'               => 'slzexploore_core_map_location_sc',
	'class'              => 'slzexploore_core-sc',
	'icon'               => 'icon-slzexploore_core_map_location_sc',
	'category'           => SLZEXPLOORE_CORE_SC_CATEGORY,
	'description'        => esc_html__( 'Map Location', 'slzexploore-core' ),
	'params'             => $params
));

