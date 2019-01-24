<?php

// get team
$args = array('post_type'     => 'slzexploore_team');
$options = array('empty'      => esc_html__( '--Choose Teams--', 'slzexploore-core' ) );
$team = Slzexploore_Core_Com::get_post_title2id( $args, $options );

$style = array(
	esc_html__('Style 1', 'slzexploore-core')  => '1',
	esc_html__('Style 2', 'slzexploore-core')  => '2',
);

$params = array(
	array(
		'type'        => 'dropdown',
		'heading'     => esc_html__( 'Style', 'slzexploore-core' ),
		'param_name'  => 'style',
		'value'       => $style,
		'description' => esc_html__( 'Choose style to display.', 'slzexploore-core' ),
	),
	array(
		'type'        => 'dropdown',
		'heading'     => esc_html__( 'Choose Team', 'slzexploore-core' ),
		'param_name'  => 'data',
		'value'       => $team,
		'description' => esc_html__( 'Choose team to display.', 'slzexploore-core' ),
	),
	array(
		'type'            => 'colorpicker',
		'heading'         => esc_html__( 'Icon Hover Color', 'slzexploore-core' ),
		'param_name'      => 'color_hover',
		'value'           => '',
		'description'     => esc_html__( 'Select hover color.', 'slzexploore-core' ),
		'dependency'     => array(
			'element'  => 'style',
			'value'    => array( '2' ),
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
	'name'               => esc_html__( 'SLZ Team Single', 'slzexploore-core' ),
	'base'               => 'slzexploore_core_team_single_sc',
	'class'              => 'slzexploore_core-sc',
	'icon'               => 'icon-slzexploore_core_team_single_sc',
	'category'           => SLZEXPLOORE_CORE_SC_CATEGORY,
	'description'        => esc_html__( 'Display single team', 'slzexploore-core' ),
	'params'             => $params
));