<?php

// get category
$category = Slzexploore_Core_Com::get_tax_options2slug( 'slzexploore_team_cat', array('empty' => esc_html__( '--All Categories--', 'slzexploore-core' ) ) );

$col = array(
	esc_html__('One', 'slzexploore-core')               => '1',
	esc_html__('Two', 'slzexploore-core')               => '2',
	esc_html__('Three', 'slzexploore-core')             => '3',
);

$params = array(
	array(
		'type'        => 'dropdown',
		'heading'     => esc_html__( 'Columns', 'slzexploore-core' ),
		'param_name'  => 'column',
		'value'       => $col,
		'std'         => '1',
		'description' => esc_html__( 'Choose column to display.', 'slzexploore-core' ),
	),
	array(
		'type'          => 'textfield',
		'heading'       => esc_html__( 'Offset Post', 'slzexploore-core' ),
		'param_name'    => 'offset_post',
		'description'   => esc_html__( 'Enter number of offset post.', 'slzexploore-core' )
	),
	array(
		'type'          => 'textfield',
		'heading'       => esc_html__( 'Limit Post', 'slzexploore-core' ),
		'param_name'    => 'limit_post',
		'value'         => '3',
		'description'   => esc_html__( 'Enter number of limit post.', 'slzexploore-core' )
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
		'param_name' => 'category_list',
		'params'     => array(
			array(
				'type'        => 'dropdown',
				'admin_label' => true,
				'heading'     => esc_html__( 'Select Category', 'slzexploore-core' ),
				'param_name'  => 'category_slug',
				'value'       => $category,
				'description' => esc_html__( 'Choose special category to filter team', 'slzexploore-core'  )
			),
		),
		'value'       => '',
		'description' => esc_html__( 'Default no filter by category.', 'slzexploore-core' ),
		'group'       => esc_html__( 'Filter By', 'slzexploore-core')
	),
);
vc_map(array(
	'name'               => esc_html__( 'SLZ Team List', 'slzexploore-core' ),
	'base'               => 'slzexploore_core_team_list_sc',
	'class'              => 'slzexploore_core-sc',
	'icon'               => 'icon-slzexploore_core_team_list_sc',
	'category'           => SLZEXPLOORE_CORE_SC_CATEGORY,
	'description'        => esc_html__( 'List teams.', 'slzexploore-core' ),
	'params'             => $params
));