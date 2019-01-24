<?php
$style = array(
	esc_html__('Style 1', 'slzexploore-core') => '',
	esc_html__('Style 2', 'slzexploore-core') => '2',
	esc_html__('Style 3', 'slzexploore-core') => '3',
	esc_html__('Style 4', 'slzexploore-core') => '4',
	esc_html__('Style 5', 'slzexploore-core') => '5',
	esc_html__('Style 6.', 'slzexploore-core') => '6',
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
		'description'    => esc_html__( 'Choose style to display.', 'slzexploore-core' ),
	),
	array(
		'type'            => 'colorpicker',
		'heading'         => esc_html__( 'Background Color', 'slzexploore-core' ),
		'param_name'      => 'color_bg',
		'value'           => '',
		'description'     => esc_html__( 'Select background color.', 'slzexploore-core' ),
		'dependency'     => array(
			'element'  => 'style_icon',
			'value'    => array( '5' ),
		),
		'group'          => esc_html__( 'Custom Color', 'slzexploore-core' ),
	),
	array(
		'type'            => 'colorpicker',
		'heading'         => esc_html__( 'Background Hover Color', 'slzexploore-core' ),
		'param_name'      => 'color_hover_bg',
		'value'           => '',
		'description'     => esc_html__( 'Select background hover color.', 'slzexploore-core' ),
		'dependency'     => array(
			'element'  => 'style_icon',
			'value'    => array( '5' ),
		),
		'group'          => esc_html__( 'Custom Color', 'slzexploore-core' ),
	),
	array(
		'type'            => 'colorpicker',
		'heading'         => esc_html__( 'Border Color', 'slzexploore-core' ),
		'param_name'      => 'color_border',
		'value'           => '',
		'description'     => esc_html__( 'Select border color.', 'slzexploore-core' ),
		'dependency'     => array(
			'element'  => 'style_icon',
			'value'    => array( '5' ),
		),
		'group'          => esc_html__( 'Custom Color', 'slzexploore-core' ),
	),
	array(
		'type'            => 'colorpicker',
		'heading'         => esc_html__( 'Text Color', 'slzexploore-core' ),
		'param_name'      => 'color_text',
		'value'           => '',
		'description'     => esc_html__( 'Select text color.', 'slzexploore-core' ),
		'dependency'     => array(
			'element'  => 'style_icon',
			'value'    => array( '5' ),
		),
		'group'          => esc_html__( 'Custom Color', 'slzexploore-core' ),
	),
	array(
		'type'            => 'colorpicker',
		'heading'         => esc_html__( 'Line Color', 'slzexploore-core' ),
		'param_name'      => 'color_line',
		'value'           => '',
		'description'     => esc_html__( 'Select line color.', 'slzexploore-core' ),
		'dependency'     => array(
			'element'  => 'style_icon',
			'value'    => array( '5' ),
		),
		'group'          => esc_html__( 'Custom Color', 'slzexploore-core' ),
	),
	array(
		'type'            => 'colorpicker',
		'heading'         => esc_html__( 'Item Color', 'slzexploore-core' ),
		'param_name'      => 'item_color',
		'value'           => '',
		'description'     => esc_html__( 'Select color for all components.', 'slzexploore-core' ),
		'dependency'     => array(
			'element'  => 'style_icon',
			'value'    => array( '3', '4', '6' ),
		),
	),
	array(
		'type'            => 'colorpicker',
		'heading'         => esc_html__( 'Item Color Hover', 'slzexploore-core' ),
		'param_name'      => 'item_color_hv',
		'value'           => '',
		'description'     => esc_html__( 'Select color for all components when hover.', 'slzexploore-core' ),
		'dependency'     => array(
			'element'  => 'style_icon',
			'value'    => array( '6' ),
		),
	),
	array(
		'type'            => 'colorpicker',
		'heading'         => esc_html__( 'Title Color', 'slzexploore-core' ),
		'param_name'      => 'color_title',
		'value'           => '',
		'description'     => esc_html__( 'Select color of title.', 'slzexploore-core' ),
		'dependency'     => array(
			'element'  => 'style_icon',
			'value'    => array( '6' ),
		),
	),
	array(
		'type'            => 'colorpicker',
		'heading'         => esc_html__( 'Title Color Hover', 'slzexploore-core' ),
		'param_name'      => 'color_hover_title',
		'value'           => '',
		'description'     => esc_html__( 'Select hover color.', 'slzexploore-core' ),
		'dependency'     => array(
			'element'  => 'style_icon',
			'value'    => array( '3', '6' ),
		),
	),
	array(
		'type'          => 'textfield',
		'heading'       => esc_html__( 'Number of Items', 'slzexploore-core' ),
		'param_name'    => 'number_show',
		'value'         => '6',
		'description'   => esc_html__( 'Enter number of items show in slide.', 'slzexploore-core' ),
		'dependency'     => array(
			'element'  => 'style_icon',
			'value'    => array( '4' ),
		),
	),
	array(
		'type'       => 'param_group',
		'heading'    => esc_html__( 'Add Item', 'slzexploore-core' ),
		'param_name' => 'list_item_one',
		'params'     => array(
			array(
				'type'            => 'textfield',
				'heading'         => esc_html__( 'Infomation', 'slzexploore-core' ),
				'param_name'      => 'info_main',
				'admin_label'     => true,
				'value'           => '',
				'description'     => esc_html__( 'Enter the first infomation.', 'slzexploore-core' )
			),
			array(
				'type'            => 'vc_link',
				'heading'         => esc_html__( 'URL (Link)', 'slzexploore-core' ),
				'param_name'      => 'url',
				'value'           => '',
				'description'     => esc_html__( 'Add link to title.', 'slzexploore-core' ),
			)
		),
		'value'       => '',
		'dependency'     => array(
			'element'  => 'style_icon',
			'value'    => array( '', '2' ),
		),
	),
	array(
		'type'       => 'param_group',
		'heading'    => esc_html__( 'Add Item', 'slzexploore-core' ),
		'param_name' => 'list_item_two',
		'params'     => array(
			array(
				'type'            => 'textfield',
				'heading'         => esc_html__( 'First Infomation', 'slzexploore-core' ),
				'param_name'      => 'info_one',
				'admin_label'     => true,
				'value'           => '',
				'description'     => esc_html__( 'Enter the first infomation.', 'slzexploore-core' )
			),
			array(
				'type'            => 'textfield',
				'heading'         => esc_html__( 'Second Infomation', 'slzexploore-core' ),
				'param_name'      => 'info_two',
				'admin_label'     => true,
				'value'           => '',
				'description'     => esc_html__( 'Enter the second infomation.', 'slzexploore-core' ),
			),
			array(
				'type'            => 'vc_link',
				'heading'         => esc_html__( 'URL (Link)', 'slzexploore-core' ),
				'param_name'      => 'url',
				'value'           => '',
				'description'     => esc_html__( 'Add link to title.', 'slzexploore-core' ),
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
		),
		'value'       => '',
		'dependency'     => array(
			'element'  => 'style_icon',
			'value'    => array( '3' ),
		),
	),
	array(
		'type'       => 'param_group',
		'heading'    => esc_html__( 'Add Item', 'slzexploore-core' ),
		'param_name' => 'list_item_three',
		'params'     => array(
			array(
				'type'            => 'textfield',
				'heading'         => esc_html__( 'First Infomation', 'slzexploore-core' ),
				'param_name'      => 'info_main',
				'admin_label'     => true,
				'value'           => '',
				'description'     => esc_html__( 'Enter the first infomation.', 'slzexploore-core' )
			),
			array(
				'type'            => 'vc_link',
				'heading'         => esc_html__( 'URL (Link)', 'slzexploore-core' ),
				'param_name'      => 'url',
				'value'           => '',
				'description'     => esc_html__( 'Add link to title.', 'slzexploore-core' ),
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
		),
		'value'       => '',
		'dependency'     => array(
			'element'  => 'style_icon',
			'value'    => array( '4' ),
		),
	),
	array(
		'type'       => 'param_group',
		'heading'    => esc_html__( 'Add Item', 'slzexploore-core' ),
		'param_name' => 'list_item_four',
		'params'     => array(
			array(
				'type'            => 'textfield',
				'heading'         => esc_html__( 'First Infomation', 'slzexploore-core' ),
				'param_name'      => 'info_main',
				'admin_label'     => true,
				'value'           => '',
				'description'     => esc_html__( 'Enter the first infomation.', 'slzexploore-core' )
			),
			array(
				'type'            => 'vc_link',
				'heading'         => esc_html__( 'URL (Link)', 'slzexploore-core' ),
				'param_name'      => 'url',
				'value'           => '',
				'description'     => esc_html__( 'Add link to title.', 'slzexploore-core' ),
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
		),
		'value'       => '',
		'dependency'     => array(
			'element'  => 'style_icon',
			'value'    => array( '5' ),
		),
	),
	array(
		'type'       => 'param_group',
		'heading'    => esc_html__( 'Add Item', 'slzexploore-core' ),
		'param_name' => 'list_item_six',
		'params'     => array(
			array(
				'type'            => 'textfield',
				'heading'         => esc_html__( 'First Infomation', 'slzexploore-core' ),
				'param_name'      => 'info_main',
				'admin_label'     => true,
				'value'           => '',
				'description'     => esc_html__( 'Enter the first infomation.', 'slzexploore-core' )
			),
			array(
				'type'            => 'vc_link',
				'heading'         => esc_html__( 'URL (Link)', 'slzexploore-core' ),
				'param_name'      => 'url',
				'value'           => '',
				'description'     => esc_html__( 'Add link to title.', 'slzexploore-core' ),
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
		),
		'value'       => '',
		'dependency'     => array(
			'element'  => 'style_icon',
			'value'    => array( '6' ),
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
	'name'               => esc_html__( 'SLZ Item List', 'slzexploore-core' ),
	'base'               => 'slzexploore_core_item_list_sc',
	'class'              => 'slzexploore_core-sc',
	'icon'               => 'icon-slzexploore_core_item_list_sc',
	'category'           => SLZEXPLOORE_CORE_SC_CATEGORY,
	'description'        => esc_html__( 'List of items', 'slzexploore-core' ),
	'params'             => $params
));

