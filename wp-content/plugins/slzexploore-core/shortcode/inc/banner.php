<?php
$style = array(
	esc_html__( 'Style 1', 'slzexploore-core' )         => '1',
	esc_html__( 'Style 2', 'slzexploore-core' )         => '2',
	esc_html__( 'Style 3', 'slzexploore-core' )         => '3',
);
$video_type = array(
	esc_html__('Youtube', 'slzexploore-core')         => '1',
	esc_html__('Vimeo', 'slzexploore-core')           => '2',
);

$params = array(
	array(
		'type'        => 'dropdown',
		'heading'     => esc_html__( 'Style', 'slzexploore-core' ),
		'param_name'  => 'style',
		'value'       => $style,
		'std'		  => '1',
		'description' => esc_html__( 'Choose style to display.', 'slzexploore-core' ),
	),
	array(
		'type'            => 'checkbox',
		'heading'         => esc_html__( 'Insert Container', 'slzexploore-core' ),
		'param_name'      => 'is_container',
		'value'           => array( esc_html__( 'Yes', 'slzexploore-core' ) => 'yes' ),
		'description' => esc_html__( 'Checked to insert container for full width.', 'slzexploore-core' ),
		'dependency'     => array(
			'element'  => 'style',
			'value'    => array( '3' ),
		),
	),
	array(
		'type'          => 'textarea_html',
		'heading'       => esc_html__( 'Title', 'slzexploore-core' ),
		'param_name'    => 'content',
		'value'         => '<strong>Enter text here</strong>',
		'description'   => esc_html__( 'Enter title with HTMl tags.', 'slzexploore-core' ),
	),
	array(
		'type'           => 'attach_image',
		'heading'        => esc_html__( 'Add Background Image', 'slzexploore-core' ),
		'param_name'     => 'image_bg',
		'description'    => esc_html__( 'Choose background image to add.', 'slzexploore-core' ),
	),
	array(
		'type'          => 'textfield',
		'heading'       => esc_html__( 'Extra Class', 'slzexploore-core' ),
		'param_name'    => 'extra_class',
		'description'   => esc_html__( 'Enter extra class name.', 'slzexploore-core' )
	),
	array(
		'type'           => 'attach_image',
		'heading'        => esc_html__( 'Add Video Background Image', 'slzexploore-core' ),
		'param_name'     => 'image_video',
		'description'    => esc_html__( 'Choose background image to add.', 'slzexploore-core' ),
		'group'       => esc_html__('Video', 'slzexploore-core'),
		'dependency'     => array(
			'element'  => 'style',
			'value'    => array( '1', '2' ),
		),
	),
	array(
		'type'        => 'dropdown',
		'heading'     => esc_html__( 'Video Type', 'slzexploore-core' ),
		'param_name'  => 'video_type',
		'value'       => $video_type,
		'group'       => esc_html__('Video', 'slzexploore-core'),
		'description' => esc_html__( 'Choose Type of Video.', 'slzexploore-core' ),
		'dependency'     => array(
			'element'  => 'style',
			'value'    => array( '1', '2' ),
		),
	),
	array(
		'type'          => 'textfield',
		'heading'       => esc_html__( 'Youtube ID', 'slzexploore-core' ),
		'param_name'    => 'id_youtube',
		'description'   => esc_html__( 'For example the Video ID for http://www.youtube.com/v/8OBfr46Y0cQ is 8OBfr46Y0cQ.', 'slzexploore-core' ),
		'group'       => esc_html__('Video', 'slzexploore-core'),
		'dependency'     => array(
			'element' => 'video_type',
			'value'   => array( '1' ),
		),
	),
	array(
		'type'          => 'textfield',
		'heading'       => esc_html__( 'Vimeo ID', 'slzexploore-core' ),
		'param_name'    => 'id_vimeo',
		'description'   => esc_html__( 'For example the Video ID for http://vimeo.com/86323053 is 86323053.', 'slzexploore-core' ),
		'group'       => esc_html__('Video', 'slzexploore-core'),
		'dependency'     => array(
			'element' => 'video_type',
			'value'   => array( '2' ),
		),
	),

	array(
		'type'          => 'textfield',
		'heading'       => esc_html__( 'Button Content', 'slzexploore-core' ),
		'param_name'    => 'button_txt',
		'description'   => esc_html__( 'Enter button text.', 'slzexploore-core' ),
		'group'       => esc_html__('Button', 'slzexploore-core'),
		'dependency'     => array(
			'element' => 'style',
			'value'   => array( '1', '3' ),
		),
	),
	array(
		'type'            => 'vc_link',
		'heading'         => esc_html__( 'URL (Link)', 'slzexploore-core' ),
		'param_name'      => 'url_btn',
		'value'           => '',
		'description'     => esc_html__( 'Add link to button.', 'slzexploore-core' ),
		'group'       => esc_html__('Button', 'slzexploore-core'),
		'dependency'     => array(
			'element' => 'style',
			'value'   => array( '1', '3' ),
		),
	),
	array(
		'type'            => 'checkbox',
		'heading'         => esc_html__( 'Background Transparent', 'slzexploore-core' ),
		'param_name'      => 'bg_transparent',
		'value'           => array( 'Transparent' => 'transparent' ),
		'group'       => esc_html__('Button', 'slzexploore-core'),
		'dependency'     => array(
			'element' => 'style',
			'value'   => array( '1', '3' ),
		),
	),
	array(
		'type'            => 'colorpicker',
		'heading'         => esc_html__( 'Background Color', 'slzexploore-core' ),
		'param_name'      => 'color_button',
		'value'           => '',
		'description'     => esc_html__( 'Select button background color.', 'slzexploore-core' ),
		'group'       => esc_html__('Button', 'slzexploore-core'),
		'dependency'     => array(
			'element' => 'style',
			'value'   => array( '1', '3' ),
		),
	),
	array(
		'type'            => 'checkbox',
		'heading'         => esc_html__( 'Background Hover Transparent', 'slzexploore-core' ),
		'param_name'      => 'bg_hv_transparent',
		'value'           => array( 'Transparent' => 'transparent_hv' ),
		'group'       => esc_html__('Button', 'slzexploore-core'),
		'dependency'     => array(
			'element' => 'style',
			'value'   => array( '1', '3' ),
		),
	),
	array(
		'type'            => 'colorpicker',
		'heading'         => esc_html__( 'Hover Color', 'slzexploore-core' ),
		'param_name'      => 'color_button_hover',
		'value'           => '',
		'description'     => esc_html__( 'Select button backgroud hover color.', 'slzexploore-core' ),
		'group'       => esc_html__('Button', 'slzexploore-core'),
		'dependency'     => array(
			'element' => 'style',
			'value'   => array( '1', '3' ),
		),
	),
	array(
		'type'            => 'colorpicker',
		'heading'         => esc_html__( 'Text Color', 'slzexploore-core' ),
		'param_name'      => 'color_text',
		'value'           => '',
		'description'     => esc_html__( 'Select text color.', 'slzexploore-core' ),
		'group'       => esc_html__('Button', 'slzexploore-core'),
		'dependency'     => array(
			'element' => 'style',
			'value'   => array( '1', '3' ),
		),
	),
	array(
		'type'            => 'colorpicker',
		'heading'         => esc_html__( 'Text Hover Color', 'slzexploore-core' ),
		'param_name'      => 'color_text_hover',
		'value'           => '',
		'description'     => esc_html__( 'Select text hover color.', 'slzexploore-core' ),
		'group'       => esc_html__('Button', 'slzexploore-core'),
		'dependency'     => array(
			'element' => 'style',
			'value'   => array( '1', '3' ),
		),
	),
	array(
		'type'            => 'colorpicker',
		'heading'         => esc_html__( 'Border Color', 'slzexploore-core' ),
		'param_name'      => 'color_border',
		'value'           => '',
		'description'     => esc_html__( 'Select border color.', 'slzexploore-core' ),
		'group'       => esc_html__('Button', 'slzexploore-core'),
		'dependency'     => array(
			'element' => 'style',
			'value'   => array( '1', '3' ),
		),
	),
	array(
		'type'            => 'colorpicker',
		'heading'         => esc_html__( 'Border Color Hover', 'slzexploore-core' ),
		'param_name'      => 'color_border_hover',
		'value'           => '',
		'description'     => esc_html__( 'Select border color when hover.', 'slzexploore-core' ),
		'group'       => esc_html__('Button', 'slzexploore-core'),
		'dependency'     => array(
			'element' => 'style',
			'value'   => array( '1', '3' ),
		),
	),
);
vc_map(array(
	'name'               => esc_html__( 'SLZ Banner', 'slzexploore-core' ),
	'base'               => 'slzexploore_core_banner_sc',
	'class'              => 'slzexploore_core-sc',
	'icon'               => 'icon-slzexploore_core_banner_sc',
	'category'           => SLZEXPLOORE_CORE_SC_CATEGORY,
	'description'        => esc_html__( 'Banner with video', 'slzexploore-core' ),
	'params'             => $params
));