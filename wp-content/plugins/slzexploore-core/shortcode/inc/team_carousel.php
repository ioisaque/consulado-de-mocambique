<?php
$style = array(
	esc_html__('Style 1', 'slzexploore-core')				=> '1',
	esc_html__('Style 2.', 'slzexploore-core')	=> '2',
);

// get team categories
$category = Slzexploore_Core_Com::get_tax_options2slug( 'slzexploore_team_cat', array('empty' => esc_html__( '--All Categories--', 'slzexploore-core' ) ) );

$params = array(
	array(
		'type'        => 'dropdown',
		'heading'     => esc_html__( 'Style', 'slzexploore-core' ),
		'param_name'  => 'style',
		'value'       => $style,
		'std'         => '1',
		'description' => esc_html__( 'Select style to display.', 'slzexploore-core' ),
	),
	array(
		'type'          => 'textfield',
		'heading'       => esc_html__( 'Title Group', 'slzexploore-core' ),
		'param_name'    => 'title',
		'description'   => esc_html__( 'Enter block title.', 'slzexploore-core' ),
	),
	array(
		'type'          => 'textfield',
		'heading'       => esc_html__( 'Offset Post', 'slzexploore-core' ),
		'param_name'    => 'offset_post',
		'description'   => esc_html__( 'Enter offset post.', 'slzexploore-core' ),
	),
	array(
		'type'          => 'textfield',
		'heading'       => esc_html__( 'Limit Post', 'slzexploore-core' ),
		'param_name'    => 'limit_post',
		'value'         => '5',
		'description'   => esc_html__( 'Enter limit post.', 'slzexploore-core' ),
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
				'description' => esc_html__( 'Choose special category to filter agent', 'slzexploore-core'  )
			),
		),
		'value'       => '',
		'description' => esc_html__( 'Default no filter by category.', 'slzexploore-core' ),
		'group'       => esc_html__( 'Filter By', 'slzexploore-core')
	),
	array(
		'type'            => 'colorpicker',
		'heading'         => esc_html__( 'Title Group Color', 'slzexploore-core' ),
		'param_name'      => 'color_title_group',
		'value'           => '',
		'description'     => esc_html__( 'Choose color title group for block.', 'slzexploore-core' ),
		'group'       	=> esc_html__('Custom', 'slzexploore-core'),
	),
	array(
		'type'            => 'colorpicker',
		'heading'         => esc_html__( 'Line Title Group Color', 'slzexploore-core' ),
		'param_name'      => 'color_title_group_line',
		'value'           => '',
		'description'     => esc_html__( 'Choose color line title group for block.', 'slzexploore-core' ),
		'group'       	=> esc_html__('Custom', 'slzexploore-core'),
	),
	array(
		'type'            => 'colorpicker',
		'heading'         => esc_html__( 'Box Info Background Color', 'slzexploore-core' ),
		'param_name'      => 'color_box_bg',
		'value'           => '',
		'description'     => esc_html__( 'Choose background color box info for block.', 'slzexploore-core' ),
		'group'       	=> esc_html__('Custom', 'slzexploore-core'),
	),
	array(
		'type'            => 'colorpicker',
		'heading'         => esc_html__( 'Line Box Color', 'slzexploore-core' ),
		'param_name'      => 'color_box_line',
		'value'           => '',
		'description'     => esc_html__( 'Choose color line box for block.', 'slzexploore-core' ),
		'group'       	=> esc_html__('Custom', 'slzexploore-core'),
	),
	array(
		'type'            => 'colorpicker',
		'heading'         => esc_html__( 'Title Color', 'slzexploore-core' ),
		'param_name'      => 'color_title',
		'value'           => '',
		'description'     => esc_html__( 'Choose color title for block.', 'slzexploore-core' ),
		'group'       	=> esc_html__('Custom', 'slzexploore-core'),
	),
	array(
		'type'            => 'colorpicker',
		'heading'         => esc_html__( 'Title Color Hover', 'slzexploore-core' ),
		'param_name'      => 'color_title_hv',
		'value'           => '',
		'description'     => esc_html__( 'Choose color title for block when hover.', 'slzexploore-core' ),
		'group'       	=> esc_html__('Custom', 'slzexploore-core'),
	),
	array(
		'type'            => 'colorpicker',
		'heading'         => esc_html__( 'Position Color', 'slzexploore-core' ),
		'param_name'      => 'color_postion',
		'value'           => '',
		'description'     => esc_html__( 'Choose color position for block.', 'slzexploore-core' ),
		'group'       	=> esc_html__('Custom', 'slzexploore-core'),
	),
	array(
		'type'            => 'colorpicker',
		'heading'         => esc_html__( 'Icon Color', 'slzexploore-core' ),
		'param_name'      => 'color_icon',
		'value'           => '',
		'description'     => esc_html__( 'Choose color icon for block.', 'slzexploore-core' ),
		'group'       	=> esc_html__('Custom', 'slzexploore-core'),
	),
	array(
		'type'            => 'colorpicker',
		'heading'         => esc_html__( 'Icon Color Hover', 'slzexploore-core' ),
		'param_name'      => 'color_icon_hv',
		'value'           => '',
		'description'     => esc_html__( 'Choose color icon for block when hover.', 'slzexploore-core' ),
		'group'       	=> esc_html__('Custom', 'slzexploore-core'),
	),
);
vc_map(array(
	'name'               => esc_html__( 'SLZ Team Carousel', 'slzexploore-core' ),
	'base'               => 'slzexploore_core_team_carousel_sc',
	'class'              => 'slzexploore_core-sc',
	'icon'               => 'icon-slzexploore_core_team_carousel_sc',
	'category'           => SLZEXPLOORE_CORE_SC_CATEGORY,
	'description'        => esc_html__( 'List teams in carousel', 'slzexploore-core' ),
	'params'             => $params
));