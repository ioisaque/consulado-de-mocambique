<?php
$load_more = array(
	esc_html__('Yes', 'slzexploore-core') => '',
	esc_html__('No', 'slzexploore-core')  => 'no',
);
$params = array(
	array(
		'type'            => 'textfield',
		'heading'         => esc_html__( 'Title Top', 'slzexploore-core' ),
		'param_name'      => 'title_top',
		'description'     => esc_html__( 'Enter title top.', 'slzexploore-core' )
	),
	array(
		'type'           => 'dropdown',
		'heading'        => esc_html__( 'Choose Type of Icon', 'slzexploore-core' ),
		'param_name'     => 'icon_type',
		'value'          => $icon_type,
		'description'    => esc_html__( 'Choose style to display block.', 'slzexploore-core' )
	),
	array(
		'type'           => 'iconpicker',
		'heading'        => esc_html__( 'Choose Icon', 'slzexploore-core' ),
		'param_name'     => 'icon_fw',
		'dependency'     => array(
			'element'  => 'icon_type',
			'value'    => array('02')),
		'description'    => esc_html__( 'Choose icon to display above title.', 'slzexploore-core' )
	),
	array(
		'type'           => 'dropdown',
		'heading'        => esc_html__( 'Choose Icon', 'slzexploore-core' ),
		'param_name'     => 'icon_ex',
		'value'          => $icon_ex,
		'dependency'     => array(
			'element'  => 'icon_type',
			'value'    => array('')),
		'description'    => sprintf(__( 'Please go on "Exploore->%s" to referentce about icons of our theme.', 'slzexploore-core' ), $admin_icon_url )
	),
	array(
		'type'            => 'textfield',
		'heading'         => esc_html__( 'Title Main', 'slzexploore-core' ),
		'param_name'      => 'title_main',
		'description'     => esc_html__( 'Enter title main.', 'slzexploore-core' )
	),
	array(
		'type'           => 'attach_image',
		'heading'        => esc_html__( 'Add Background Image', 'slzexploore-core' ),
		'param_name'     => 'image',
		'description'    => esc_html__( 'Choose background image to add.', 'slzexploore-core' ),
	),
	array(
		'type'            => 'textfield',
		'heading'         => esc_html__( 'Limit posts', 'slzexploore-core' ),
		'param_name'      => 'limit_post',
		'value'           => '',
		'description'     => esc_html__( 'Add limit posts per page. Set -1 to show all.', 'slzexploore-core' )
	),
	array(
		'type'            => 'textfield',
		'heading'         => esc_html__( 'Offset post', 'slzexploore-core' ),
		'param_name'      => 'offset_post',
		'value'           => '',
		'description'     => esc_html__( 'Enter offset to pass over posts. If you want to start on record 6, using offset 5', 'slzexploore-core' )
	),
	array(
		'type'           => 'textfield',
		'heading'        => esc_html__( 'Extra Class', 'slzexploore-core' ),
		'param_name'     => 'extra_class',
		'description'    => esc_html__( 'Enter extra class name.', 'slzexploore-core' ),
	),
);
vc_map(array(
	'name'        => esc_html__( 'SLZ Testimonial', 'slzexploore-core' ),
	'base'        => 'slzexploore_core_testimonial_sc',
	'class'       => 'slzexploore_core-sc',
	'icon'        => 'icon-slzexploore_core_testimonial_sc',
	'category'    => SLZEXPLOORE_CORE_SC_CATEGORY,
	'description' => esc_html__( 'List of testimonials.', 'slzexploore-core' ),
	'params'      => $params
));