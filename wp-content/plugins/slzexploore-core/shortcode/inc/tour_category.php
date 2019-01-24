<?php
$category = Slzexploore_Core_Com::get_tax_options2slug( 'slzexploore_tour_cat', array('empty' => esc_html__( '--All Categories--', 'slzexploore-core' ) ) );
$params = array(
	array(
		'type'       => 'param_group',
		'heading'    => esc_html__( 'Choose Category', 'slzexploore-core' ),
		'param_name' => 'category_list',
		'params'     => array(
			array(
				'type'        => 'dropdown',
				'admin_label' => true,
				'heading'     => esc_html__( 'Add Category', 'slzexploore-core' ),
				'param_name'  => 'category_slug',
				'value'       => $category,
				'description' => esc_html__( 'Choose special category to display', 'slzexploore-core' )
			),
		),
		'value'       => '',
		'description' => esc_html__( 'Default display all categories.', 'slzexploore-core' )
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
	'name'        => esc_html__( 'SLZ Tour Categories', 'slzexploore-core' ),
	'base'        => 'slzexploore_core_tour_category_sc',
	'class'       => 'slzexploore_core-sc',
	'icon'        => 'icon-slzexploore_core_tour_category_sc',
	'category'    => SLZEXPLOORE_CORE_SC_CATEGORY,
	'description' => esc_html__( 'List tour categories.', 'slzexploore-core' ),
	'params'      => $params
));