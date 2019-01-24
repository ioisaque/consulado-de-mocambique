<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

$parent_tag = vc_post_param( 'parent_tag', '' );
$include_icon_params = ( 'vc_tta_pageable' !== $parent_tag );
$icon_type = Slzexploore_Core::get_params('icon_type');
$icon_ex = Slzexploore_Core::get_font_icons('font-flaticon');
$admin_icon_url = '<a href="'.admin_url( 'admin.php?page='.SLZEXPLOORE_CORE_THEME_PREFIX.'_icon' ).'" target="_blank">'.esc_html__('Icons','slzexploore-core').'</a>';

if ( $include_icon_params ) {
	require_once vc_path_dir( 'CONFIG_DIR', 'content/vc-icon-element.php' );
	$icon_params = array(
		array(
			'type' => 'checkbox',
			'param_name' => 'add_icon',
			'heading' => esc_html__( 'Add icon?', 'slzexploore-core' ),
			'description' => esc_html__( 'Add icon next to section title.', 'slzexploore-core' ),
		),
		array(
			'type' => 'dropdown',
			'param_name' => 'i_position',
			'value' => array(
				esc_html__( 'Before title', 'slzexploore-core' ) => 'left',
				esc_html__( 'After title', 'slzexploore-core' )  => 'right',
			),
			'dependency' => array(
				'element' => 'add_icon',
				'value' => 'true',
			),
			'heading' => esc_html__( 'Icon position', 'slzexploore-core' ),
			'description' => esc_html__( 'Select icon position.', 'slzexploore-core' ),
		),
		array(
			'type'           => 'dropdown',
			'heading'        =>  esc_html__( 'Choose Type of Icon', 'slzexploore-core' ),
			'param_name'     => 'icon_type',
			'value'          => $icon_type,
			'dependency' => array(
				'element' => 'add_icon',
				'value' => 'true',
			),
			'description'    => esc_html__( 'Choose style to display block.', 'slzexploore-core' )
			),
		array(
			'type'           => 'dropdown',
			'heading'        => esc_html__( 'Choose Exploore Icons', 'slzexploore-core' ),
			'param_name'     => 'icon_ex',
			'value'          => $icon_ex,
			'dependency' => array(
					'element' => 'add_icon',
					'value' => 'true',
				),
			'description'    => sprintf(__( 'Please go on "Exploore->%s" to referentce about icons of our theme.', 'slzexploore-core' ), $admin_icon_url )
		),
	);
	$icon_params = array_merge( $icon_params, (array) vc_map_integrate_shortcode( vc_icon_element_params(), 'i_', '', array(
			// we need only type, icon_fontawesome, icon_.., NOT color and etc
			'include_only_regex' => '/^(type|icon_\w*)/',
		), array(
			'element' => 'add_icon',
			'value' => 'true',
		) ) );
} else {
	$icon_params = array();
}

$params = array_merge( array(
	array(
		'type'        => 'textfield',
		'param_name'  => 'title',
		'heading'     => esc_html__( 'Title', 'slzexploore-core' ),
		'description' => esc_html__( 'Enter section title (Note: you can leave it empty).', 'slzexploore-core' ),
	),
	array(
		'type'       => 'el_id',
		'param_name' => 'tab_id',
		'settings' => array(
			'auto_generate' => true,
		),
		'heading'     => esc_html__( 'Section ID', 'slzexploore-core' ),
		'description' => esc_html__( 'Enter section ID .', 'slzexploore-core' ),
	),		
), $icon_params);

vc_map(array(
	'name' => esc_html__( 'Slz Section', 'slzexploore-core' ),
	'base' => 'slzexploore_core_tab_sc', 
	'allowed_container_element' => 'vc_row',
	'is_container' => true,
	'content_element' => false,
	'category'    => SLZEXPLOORE_CORE_SC_CATEGORY,
	'description' => esc_html__( 'Section for Tabs.', 'slzexploore-core' ),
	'params' => $params,
	'js_view' =>  'VcIconTabView'
));
