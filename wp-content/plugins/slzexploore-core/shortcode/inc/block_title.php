<?php
$style = array(
	esc_html__('Style 1', 'slzexploore-core') => '1',
	esc_html__('Style 2', 'slzexploore-core') => '2',
	esc_html__('Style 3', 'slzexploore-core') => '3',
);
$icon_type = Slzexploore_Core::get_params('icon_type');
$icon_ex = Slzexploore_Core::get_font_icons('font-flaticon');
$admin_icon_url = '<a href="'.admin_url( 'admin.php?page='.SLZEXPLOORE_CORE_THEME_PREFIX.'_icon' ).'" target="_blank">'.esc_html__('Icons','slzexploore-core').'</a>';

$show_line = array(
	esc_html__('---Yes---', 'slzexploore-core') => '1',
	esc_html__('---No---', 'slzexploore-core') => '2',
);

$params = array(
	array(
		'type'           => 'dropdown',
		'heading'        => esc_html__( 'Style', 'slzexploore-core' ),
		'param_name'     => 'style_title',
		'value'          => $style,
		'description'    => esc_html__( 'Choose style to display.', 'slzexploore-core' ),
	),
	array(
		'type'          => 'textfield',
		'heading'       => esc_html__( 'Sub Title', 'slzexploore-core' ),
		'param_name'    => 'sub_title',
		'description'   => esc_html__( 'Enter sub title.', 'slzexploore-core' ),
		'dependency'     => array(
			'element'  => 'style_title',
			'value'    => array( '1' ),
		),
	),
	array(
		'type'          => 'textfield',
		'heading'       => esc_html__( 'Title', 'slzexploore-core' ),
		'param_name'    => 'title',
		'description'   => esc_html__( 'Enter title.', 'slzexploore-core' ),
	),
	array(
		'type'            => 'colorpicker',
		'heading'         => esc_html__( 'Title color', 'slzexploore-core' ),
		'param_name'      => 'title_color',
		'value'           => '',
		'description'     => esc_html__( 'Select title color.', 'slzexploore-core' ),
		'dependency'     => array(
			'element'  => 'style_title',
			'value'    => array( '2' ),
		),
	),
	array(
		'type'          => 'textfield',
		'heading'       => esc_html__( 'Title Margin Bottom', 'slzexploore-core' ),
		'param_name'    => 'margin_bottom_title',
		'value'         => '70',
		'description'   => esc_html__( 'Enter title margin bottom. Example: 70px -> input 70', 'slzexploore-core' ),
		'dependency'     => array(
			'element'  => 'style_title',
			'value'    => array( '1' ),
		),
	),
	array(
		'type'        => 'dropdown',
		'heading'     => esc_html__( 'Show Line', 'slzexploore-core' ),
		'param_name'  => 'show_line',
		'value'       => $show_line,
		'description' => esc_html__( 'Choose show line after title.', 'slzexploore-core'  ),
		'dependency'     => array(
			'element'  => 'style_title',
			'value'    => array( '2' ),
		),
	),
	array(
		'type'          => 'textarea',
		'heading'       => esc_html__( 'Description', 'slzexploore-core' ),
		'param_name'    => 'description',
		'description'   => esc_html__( 'Enter description.', 'slzexploore-core' ),
		'dependency'     => array(
			'element'  => 'style_title',
			'value'    => array( '1' ),
		),
	),
	array(
		'type'           => 'dropdown',
		'heading'        => esc_html__( 'Choose Type of Icon', 'slzexploore-core' ),
		'param_name'     => 'icon_type',
		'value'          => $icon_type,
		'description'    => esc_html__( 'Choose style to display block.', 'slzexploore-core' ),
		'dependency'     => array(
			'element'  => 'style_title',
			'value'    => array( '1' ),
		),
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
		'type'          => 'textfield',
		'heading'       => esc_html__( 'Extra Class', 'slzexploore-core' ),
		'param_name'    => 'extra_class',
		'description'   => esc_html__( 'Enter extra class name.', 'slzexploore-core' )
	)
);
vc_map(array(
	'name'               => esc_html__( 'SLZ Block Title', 'slzexploore-core' ),
	'base'               => 'slzexploore_core_block_title_sc',
	'class'              => 'slzexploore_core-sc',
	'icon'               => 'icon-slzexploore_core_block_title_sc',
	'category'           => SLZEXPLOORE_CORE_SC_CATEGORY,
	'description'        => esc_html__( 'Block Title', 'slzexploore-core' ),
	'params'             => $params
));

