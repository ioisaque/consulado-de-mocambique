<?php

$style = array(
	esc_html__( 'Style 1', 'slzexploore-core' )         => '1',
	esc_html__( 'Style 2', 'slzexploore-core' )         => '2',
	esc_html__( 'Style 3', 'slzexploore-core' )         => '3',
	esc_html__( 'Style 4', 'slzexploore-core' )         => '4',
);

$usebutton = array(
	esc_html__('--Choose number--', 'slzexploore-core') => '',
	esc_html__('One', 'slzexploore-core')           => '1',
	esc_html__('Two', 'slzexploore-core')           => '2',
);

$params = array(
	array(
		'type'        => 'dropdown',
		'heading'     => esc_html__( 'Style', 'slzexploore-core' ),
		'param_name'  => 'style',
		'value'       => $style,
		'description' => esc_html__( 'Choose style to display.', 'slzexploore-core'  ),
	),
	array(
		'type'          => 'textfield',
		'heading'       => esc_html__( 'Left Content 1', 'slzexploore-core' ),
		'param_name'    => 'left_text',
		'description'   => esc_html__( 'Enter content 1', 'slzexploore-core' ),
		'dependency'     => array(
			'element' => 'style',
			'value'   => array( '1', '3' ),
		),
	),
	array(
		'type'          => 'textfield',
		'heading'       => esc_html__( 'Left Content 2', 'slzexploore-core' ),
		'param_name'    => 'left_text2',
		'description'   => esc_html__( 'Enter left content 2', 'slzexploore-core' ),
		'dependency'     => array(
			'element' => 'style',
			'value'   => array( '3' ),
		),
	),
	array(
		'type'          => 'textfield',
		'heading'       => esc_html__( 'Left Content 3', 'slzexploore-core' ),
		'param_name'    => 'left_text3',
		'description'   => esc_html__( 'Enter left content 3', 'slzexploore-core' ),
		'dependency'     => array(
			'element' => 'style',
			'value'   => array( '3' ),
		),
	),
	array(
		'type'          => 'textfield',
		'heading'       => esc_html__( 'Discount (%)', 'slzexploore-core' ),
		'param_name'    => 'discount_percent',
		'description'   => esc_html__( 'Enter discount percent', 'slzexploore-core' ),
	),
	array(
		'type'          => 'textfield',
		'heading'       => esc_html__( 'Discount Text 1', 'slzexploore-core' ),
		'param_name'    => 'dicount_text1',
		'description'   => esc_html__( 'Enter discount text 1', 'slzexploore-core' ),
	),
	array(
		'type'          => 'textfield',
		'heading'       => esc_html__( 'Discount Text 2', 'slzexploore-core' ),
		'param_name'    => 'dicount_text2',
		'description'   => esc_html__( 'Enter discount text 1', 'slzexploore-core' ),
	),
	array(
		'type'          => 'textarea_html',
		'heading'       => esc_html__( 'Info Right', 'slzexploore-core' ),
		'param_name'    => 'content',
		'value'         => '<strong>Enter text here</strong>',
		'description'   => esc_html__( 'Enter info right with HTMl tags.', 'slzexploore-core' ),
	),
	array(
		'type'           => 'attach_image',
		'heading'        => esc_html__( 'Add Image', 'slzexploore-core' ),
		'param_name'     => 'image',
		'description'    => esc_html__( 'Choose image to add.', 'slzexploore-core' ),
		'dependency'     => array(
			'element' => 'style',
			'value'   => array( '1', '2', '3' ),
		),
	),
	array(
		'type'           => 'textfield',
		'heading'        => esc_html__( 'Height of Discount Box', 'slzexploore-core' ),
		'param_name'     => 'height',
		'value'          => '500',
		'description'    => esc_html__( 'Enter height of discount box.', 'slzexploore-core' ),
		'dependency'     => array(
			'element' => 'style',
			'value'   => array( '1', '2', '3' ),
		),
	),
	array(
		'type'        => 'dropdown',
		'heading'     => esc_html__( 'Use button', 'slzexploore-core' ),
		'param_name'  => 'usebutton',
		'value'       => $usebutton,
		'description' => esc_html__( 'Choose how many button appear in shortcode', 'slzexploore-core' ),
		'group'       => esc_html__('Button', 'slzexploore-core')
	),
	array(
		'type'          => 'textfield',
		'heading'       => esc_html__( 'Button 1 Text', 'slzexploore-core' ),
		'param_name'    => 'buttontext1',
		'description'   => esc_html__( 'Enter button text', 'slzexploore-core' ),
		'group'       => esc_html__('Button', 'slzexploore-core'),
		'dependency'     => array(
			'element' => 'usebutton',
			'value'   => array( '1', '2' ),
		),
	),
	array(
		'type'            => 'vc_link',
		'heading'         => esc_html__( 'URL (Link)', 'slzexploore-core' ),
		'param_name'      => 'url1',
		'value'           => '',
		'description'     => esc_html__( 'Add link to title.', 'slzexploore-core' ),
		'dependency'     => array(
			'element' => 'usebutton',
			'value'   => array( '1', '2' ),
		),
		'group'       => esc_html__('Button', 'slzexploore-core')
	),
	array(
		'type'            => 'colorpicker',
		'heading'         => esc_html__( 'Text Color', 'slzexploore-core' ),
		'param_name'      => 'btn_text_1',
		'value'           => '',
		'description'     => esc_html__( 'Select text color.', 'slzexploore-core' ),
		'dependency'     => array(
			'element'  => 'usebutton',
			'value'    => array( '1', '2' ),
		),
		'group'       => esc_html__('Button', 'slzexploore-core')
	),
	array(
		'type'            => 'colorpicker',
		'heading'         => esc_html__( 'Text Hover Color', 'slzexploore-core' ),
		'param_name'      => 'btn_text_hover_1',
		'value'           => '',
		'description'     => esc_html__( 'Select text hover color.', 'slzexploore-core' ),
		'dependency'     => array(
			'element'  => 'usebutton',
			'value'    => array( '1', '2' ),
		),
		'group'       => esc_html__('Button', 'slzexploore-core')
	),
	array(
		'type'            => 'checkbox',
		'heading'         => esc_html__( 'Button Background Transparent', 'slzexploore-core' ),
		'param_name'      => 'bg_transparent1',
		'value'           => array( 'Transparent' => 'transparent' ),
		'group'       => esc_html__('Button', 'slzexploore-core'),
		'dependency'     => array(
			'element'  => 'usebutton',
			'value'    => array( '1', '2' ),
		),
	),
	array(
		'type'            => 'colorpicker',
		'heading'         => esc_html__( 'Button Backgroud Color ', 'slzexploore-core' ),
		'param_name'      => 'btn_bg_1',
		'value'           => '',
		'description'     => esc_html__( 'Select button backgroud color.', 'slzexploore-core' ),
		'dependency'     => array(
			'element'  => 'usebutton',
			'value'    => array( '1', '2' ),
		),
		'group'       => esc_html__('Button', 'slzexploore-core')
	),
	array(
		'type'            => 'checkbox',
		'heading'         => esc_html__( 'Button Background Hover Transparent', 'slzexploore-core' ),
		'param_name'      => 'bg_hv_transparent1',
		'value'           => array( 'Transparent' => 'transparent_hv' ),
		'group'       => esc_html__('Button', 'slzexploore-core'),
		'dependency'     => array(
			'element'  => 'usebutton',
			'value'    => array( '1', '2' ),
		),
	),
	array(
		'type'            => 'colorpicker',
		'heading'         => esc_html__( 'Button Hover Backgroud Color ', 'slzexploore-core' ),
		'param_name'      => 'btn_bg_hover_1',
		'value'           => '',
		'description'     => esc_html__( 'Select button hover backgroud color.', 'slzexploore-core' ),
		'dependency'     => array(
			'element'  => 'usebutton',
			'value'    => array( '1', '2' ),
		),
		'group'       => esc_html__('Button', 'slzexploore-core')
	),
	array(
		'type'            => 'colorpicker',
		'heading'         => esc_html__( 'Button Border Color', 'slzexploore-core' ),
		'param_name'      => 'color_border1',
		'value'           => '',
		'description'     => esc_html__( 'Select button border color.', 'slzexploore-core' ),
		'dependency'     => array(
			'element'  => 'usebutton',
			'value'    => array( '1', '2' ),
		),
		'group'       => esc_html__('Button', 'slzexploore-core')
	),
	array(
		'type'          => 'textfield',
		'heading'       => esc_html__( 'Button 2 Text', 'slzexploore-core' ),
		'param_name'    => 'buttontext2',
		'description'   => esc_html__( 'Enter button text', 'slzexploore-core' ),
		'group'       => esc_html__('Button', 'slzexploore-core'),
		'dependency'     => array(
			'element' => 'usebutton',
			'value'   => array( '2' ),
		),
	),
	array(
		'type'            => 'vc_link',
		'heading'         => esc_html__( 'URL (Link)', 'slzexploore-core' ),
		'param_name'      => 'url2',
		'value'           => '',
		'description'     => esc_html__( 'Add link to title.', 'slzexploore-core' ),
		'dependency'     => array(
			'element' => 'usebutton',
			'value'   => array( '2' ),
		),
		'group'       => esc_html__('Button', 'slzexploore-core')
	),
	array(
		'type'            => 'colorpicker',
		'heading'         => esc_html__( 'Text Color', 'slzexploore-core' ),
		'param_name'      => 'btn_text_2',
		'value'           => '',
		'description'     => esc_html__( 'Select text color.', 'slzexploore-core' ),
		'dependency'     => array(
			'element'  => 'usebutton',
			'value'    => array( '2' ),
		),
		'group'       => esc_html__('Button', 'slzexploore-core')
	),
	array(
		'type'            => 'colorpicker',
		'heading'         => esc_html__( 'Text Hover Color', 'slzexploore-core' ),
		'param_name'      => 'btn_text_hover_2',
		'value'           => '',
		'description'     => esc_html__( 'Select text hover color.', 'slzexploore-core' ),
		'dependency'     => array(
			'element'  => 'usebutton',
			'value'    => array( '2' ),
		),
		'group'       => esc_html__('Button', 'slzexploore-core')
	),
	array(
		'type'            => 'checkbox',
		'heading'         => esc_html__( 'Button Background Transparent', 'slzexploore-core' ),
		'param_name'      => 'bg_transparent2',
		'value'           => array( 'Transparent' => 'transparent' ),
		'group'       => esc_html__('Button', 'slzexploore-core'),
		'dependency'     => array(
			'element'  => 'usebutton',
			'value'    => array( '2' ),
		),
	),
	array(
		'type'            => 'colorpicker',
		'heading'         => esc_html__( 'Button Backgroud Color ', 'slzexploore-core' ),
		'param_name'      => 'btn_bg_2',
		'value'           => '',
		'description'     => esc_html__( 'Select button backgroud color.', 'slzexploore-core' ),
		'dependency'     => array(
			'element'  => 'usebutton',
			'value'    => array( '2' ),
		),
		'group'       => esc_html__('Button', 'slzexploore-core')
	),
	array(
		'type'            => 'checkbox',
		'heading'         => esc_html__( 'Button Background Hover Transparent', 'slzexploore-core' ),
		'param_name'      => 'bg_hv_transparent2',
		'value'           => array( 'Transparent' => 'transparent_hv' ),
		'group'       => esc_html__('Button', 'slzexploore-core'),
		'dependency'     => array(
			'element'  => 'usebutton',
			'value'    => array( '2' ),
		),
	),
	array(
		'type'            => 'colorpicker',
		'heading'         => esc_html__( 'Button Hover Backgroud Color ', 'slzexploore-core' ),
		'param_name'      => 'btn_bg_hover_2',
		'value'           => '',
		'description'     => esc_html__( 'Select button hover backgroud color.', 'slzexploore-core' ),
		'dependency'     => array(
			'element'  => 'usebutton',
			'value'    => array( '2' ),
		),
		'group'       => esc_html__('Button', 'slzexploore-core')
	),
	array(
		'type'            => 'colorpicker',
		'heading'         => esc_html__( 'Button Border Color ', 'slzexploore-core' ),
		'param_name'      => 'color_border2',
		'value'           => '',
		'description'     => esc_html__( 'Select button border color.', 'slzexploore-core' ),
		'dependency'     => array(
			'element'  => 'usebutton',
			'value'    => array( '2' ),
		),
		'group'       => esc_html__('Button', 'slzexploore-core')
	),
	array(
		'type'          => 'textfield',
		'heading'       => esc_html__( 'Extra Class', 'slzexploore-core' ),
		'param_name'    => 'extra_class',
		'description'   => esc_html__( 'Enter extra class name.', 'slzexploore-core' )
	)
);
vc_map(array(
	'name'               => esc_html__( 'SLZ Discount Box', 'slzexploore-core' ),
	'base'               => 'slzexploore_core_discount_box_sc',
	'class'              => 'slzexploore_core-sc',
	'icon'               => 'icon-slzexploore_core_discount_box_sc',
	'category'           => SLZEXPLOORE_CORE_SC_CATEGORY,
	'description'        => esc_html__( 'Show discount number with backgroud picture', 'slzexploore-core' ),
	'params'             => $params
));

