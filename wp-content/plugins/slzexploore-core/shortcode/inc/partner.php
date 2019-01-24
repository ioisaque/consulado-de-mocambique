<?php

$args = array(
	'post_type'        => 'slzexploore_partner',
);
$options = array('empty'      => esc_html__( '-All Partner-', 'slzexploore-core' ) );
$partner = Slzexploore_Core_Com::get_post_title2id( $args, $options );

$style = array(
	esc_html__('Style 1','slzexploore-core') => '1',
	esc_html__('Style 2','slzexploore-core') => '2',
);
$row = array(
	esc_html__('One','slzexploore-core') => '1',
	esc_html__('Two','slzexploore-core') => '2',
);
$taxonomy = 'slzexploore_partner_cat';
$params_cat = array('empty' => esc_html__( '-All Partner Categories-', 'slzexploore-core' ) );
$categories = Slzexploore_Core_Com::get_tax_options2slug( $taxonomy, $params_cat );

$params = array(
	array(
		'type'        => 'dropdown',
		'heading'     => esc_html__( 'Style', 'slzexploore-core' ),
		'param_name'  => 'style',
		'value'       => $style,
		'description' => esc_html__( 'Choose style to display.', 'slzexploore-core'  ),
	),
	array(
		'type'        => 'dropdown',
		'heading'     => esc_html__( 'Number of Rows', 'slzexploore-core' ),
		'param_name'  => 'row',
		'value'       => $row,
		'description' => esc_html__( 'Choose number of rows to display.', 'slzexploore-core'  ),
		'dependency'     => array(
			'element'  => 'style',
			'value'    => array( '1' ),
		),
	),
	array(
		'type'          => 'textfield',
		'heading'       => esc_html__( 'Title', 'slzexploore-core' ),
		'param_name'    => 'title',
		'description'   => esc_html__( 'Enter block title.', 'slzexploore-core' ),
		'dependency'     => array(
			'element'  => 'style',
			'value'    => array( '1' ),
		),
	),
	array(
		'type'        => 'dropdown',
		'heading'     => esc_html__( 'Display by', 'slzexploore-core' ),
		'param_name'  => 'method',
		'value'       => array( 'Category' => 'cat', 'Partner' => 'partner' ),
		'description' => esc_html__( 'Choose partner category or special partners to display', 'slzexploore-core' )
	),
	array(
		'type'        => 'dropdown',
		'heading'     => esc_html__( 'Category', 'slzexploore-core' ),
		'param_name'  => 'category',
		'dependency'  => array(
			'element'   => 'method',
			'value'     => array( 'cat' )
		),
		'value'       => $categories,
		'description' => esc_html__( 'Choose partner category.', 'slzexploore-core' )
	),
	array(
		'type'       => 'param_group',
		'heading'    => esc_html__( 'Partner', 'slzexploore-core' ),
		'param_name' => 'list_partner',
		'dependency'  => array(
			'element'   => 'method',
			'value'     => array( 'partner' )
		),
		'params'     => array(
			array(
				'type'        => 'dropdown',
				'admin_label' => true,
				'heading'     => esc_html__( 'Add Partner', 'slzexploore-core' ),
				'param_name'  => 'partner',
				'value'       => $partner,
				'description' => esc_html__( 'Choose partner to display.', 'slzexploore-core' ),
			),
		),
		'description' => esc_html__( 'Display content of partners.', 'slzexploore-core' ),
	),
	array(
		'type'          => 'textfield',
		'heading'       => esc_html__( 'Extra Class', 'slzexploore-core' ),
		'param_name'    => 'extra_class',
		'description'   => esc_html__( 'Enter extra class name.', 'slzexploore-core' )
	)
);
vc_map(array(
	'name'               => esc_html__( 'SLZ Partner', 'slzexploore-core' ),
	'base'               => 'slzexploore_core_partner_sc',
	'class'              => 'slzexploore_core-sc',
	'icon'               => 'icon-slzexploore_core_partner_sc',
	'category'           => SLZEXPLOORE_CORE_SC_CATEGORY,
	'description'        => esc_html__( 'List partners', 'slzexploore-core' ),
	'params'             => $params
));

