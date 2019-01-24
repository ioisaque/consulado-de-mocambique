<?php

$params = array(
	array(
		'type'          => 'textfield',
		'heading'       => esc_html__( 'Button Text', 'slzexploore-core' ),
		'param_name'    => 'text',
		'description'   => esc_html__( 'Enter block title.', 'slzexploore-core' )
	),
		array(
		'type'            => 'vc_link',
		'heading'         => esc_html__( 'URL (Link)', 'slzexploore-core' ),
		'param_name'      => 'url',
		'value'           => '',
		'description'     => esc_html__( 'Add link to title.', 'slzexploore-core' ),
	),
	array(
		'type'            => 'checkbox',
		'heading'         => esc_html__( 'Background Transparent', 'slzexploore-core' ),
		'param_name'      => 'bg_transparent',
		'value'           => array( 'Transparent' => 'transparent' ),
	),
	array(
		'type'            => 'colorpicker',
		'heading'         => esc_html__( 'Background Color', 'slzexploore-core' ),
		'param_name'      => 'color_button',
		'value'           => '',
		'description'     => esc_html__( 'Select background color.', 'slzexploore-core' ),
	),
	array(
		'type'            => 'checkbox',
		'heading'         => esc_html__( 'Background Hover Transparent', 'slzexploore-core' ),
		'param_name'      => 'bg_hv_transparent',
		'value'           => array( 'Transparent' => 'transparent_hv' ),
	),
	array(
		'type'            => 'colorpicker',
		'heading'         => esc_html__( 'Background Hover Color', 'slzexploore-core' ),
		'param_name'      => 'color_button_hover',
		'value'           => '',
		'description'     => esc_html__( 'Select background hover color.', 'slzexploore-core' ),
	),
	array(
		'type'            => 'colorpicker',
		'heading'         => esc_html__( 'Text Color', 'slzexploore-core' ),
		'param_name'      => 'color_text',
		'value'           => '',
		'description'     => esc_html__( 'Select text color.', 'slzexploore-core' ),
	),
	array(
		'type'            => 'colorpicker',
		'heading'         => esc_html__( 'Text Hover Color', 'slzexploore-core' ),
		'param_name'      => 'color_text_hover',
		'value'           => '',
		'description'     => esc_html__( 'Select text hover color.', 'slzexploore-core' ),
	),
	array(
		'type'            => 'colorpicker',
		'heading'         => esc_html__( 'Border Color', 'slzexploore-core' ),
		'param_name'      => 'color_border',
		'value'           => '',
		'description'     => esc_html__( 'Select border color.', 'slzexploore-core' ),
	),
	array(
		'type'          => 'textfield',
		'heading'       => esc_html__( 'Extra Class', 'slzexploore-core' ),
		'param_name'    => 'extra_class',
		'description'   => esc_html__( 'Enter extra class name.', 'slzexploore-core' )
	)
);
vc_map(array(
	'name'               => esc_html__( 'SLZ Button', 'slzexploore-core' ),
	'base'               => 'slzexploore_core_button_sc',
	'class'              => 'slzexploore_core-sc',
	'icon'               => 'icon-slzexploore_core_button_sc',
	'category'           => SLZEXPLOORE_CORE_SC_CATEGORY,
	'description'        => esc_html__( 'Add Button with custom options', 'slzexploore-core' ),
	'params'             => $params
));

