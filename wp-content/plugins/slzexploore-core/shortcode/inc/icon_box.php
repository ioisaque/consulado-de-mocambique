<?php
$style = array(
	esc_html__('Style 1', 'slzexploore-core') => '6',
	esc_html__('Style 2', 'slzexploore-core') => '2',
	esc_html__('Style 3', 'slzexploore-core') => '3',
	esc_html__('Style 4', 'slzexploore-core') => '4',
	esc_html__('Style 5', 'slzexploore-core') => '5',
);
$icon_type = Slzexploore_Core::get_params('icon_type');
$icon_ex = Slzexploore_Core::get_font_icons('font-flaticon');
$admin_icon_url = '<a href="'.admin_url( 'admin.php?page='.SLZEXPLOORE_CORE_THEME_PREFIX.'_icon' ).'" target="_blank">'.esc_html__('Icons','slzexploore-core').'</a>';


$params = array(
	array(
		'type'           => 'dropdown',
		'heading'        => esc_html__( 'Style', 'slzexploore-core' ),
		'param_name'     => 'style_icon',
		'value'          => $style,
		'std'            => '6',
		'description'    => esc_html__( 'Choose style to display.', 'slzexploore-core' ),
	),
	array(
		'type'            => 'colorpicker',
		'heading'         => esc_html__( 'Hover Color', 'slzexploore-core' ),
		'param_name'      => 'color',
		'value'           => '',
		'description'     => esc_html__( 'Select hover color.', 'slzexploore-core' ),
		'dependency'     => array(
			'element'  => 'style_icon',
			'value'    => array( '3', '5', '6' ),
		),
	),
	array(
		'type'            => 'colorpicker',
		'heading'         => esc_html__( 'Title Color', 'slzexploore-core' ),
		'param_name'      => 'title_color',
		'value'           => '',
		'description'     => esc_html__( 'Select title color.', 'slzexploore-core' ),
		'dependency'     => array(
			'element'  => 'style_icon',
			'value'    => array( '4' ),
		),
	),
	array(
		'type'            => 'colorpicker',
		'heading'         => esc_html__( 'Description Color', 'slzexploore-core' ),
		'param_name'      => 'description_color',
		'value'           => '',
		'description'     => esc_html__( 'Select description color.', 'slzexploore-core' ),
		'dependency'     => array(
			'element'  => 'style_icon',
			'value'    => array( '4' ),
		),
	),
	array(
		'type'            => 'vc_link',
		'heading'         => esc_html__( 'URL (Link)', 'slzexploore-core' ),
		'param_name'      => 'url',
		'value'           => '',
		'description'     => esc_html__( 'Add link to title.', 'slzexploore-core' ),
		'dependency'     => array(
			'element'  => 'style_icon',
			'value'    => array( '3', '5', '6' ),
		),
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
		'type'           => 'textfield',
		'heading'        => esc_html__( 'Title', 'slzexploore-core' ),
		'param_name'     => 'title',
		'description'    => esc_html__( 'Enter title.', 'slzexploore-core' ),
	),
	array(
		'type'           => 'textarea',
		'heading'        => esc_html__( 'Description', 'slzexploore-core' ),
		'param_name'     => 'description',
		'description'    => esc_html__( 'Enter description.', 'slzexploore-core' ),
		'dependency'     => array(
			'element'  => 'style_icon',
			'value'    => array( '1', '4', '5', '6' ),
		),
	),
	array(
		'type'          => 'textfield',
		'heading'       => esc_html__( 'Extra Class', 'slzexploore-core' ),
		'param_name'    => 'extra_class',
		'description'   => esc_html__( 'Enter extra class name.', 'slzexploore-core' )
	)
);
vc_map(array(
	'name'               => esc_html__( 'SLZ Icon Box', 'slzexploore-core' ),
	'base'               => 'slzexploore_core_icon_box_sc',
	'class'              => 'slzexploore_core-sc',
	'icon'               => 'icon-slzexploore_core_icon_box_sc',
	'category'           => SLZEXPLOORE_CORE_SC_CATEGORY,
	'description'        => esc_html__( 'Add Icon Box with custom options', 'slzexploore-core' ),
	'params'             => $params
));

