<?php
$layout = array(
	esc_html__('Horizontal', 'slzexploore-core')           => '01',
	esc_html__('Vertical', 'slzexploore-core')             => '02',
	esc_html__('Transparent', 'slzexploore-core')          => '03'
);
$params = array(
	array(
		'type'        => 'dropdown',
		'heading'     => esc_html__( 'Layout', 'slzexploore-core' ),
		'param_name'  => 'layout',
		'value'       => $layout,
		'description' => esc_html__( 'Choose layout for search form.', 'slzexploore-core'  ),
	),
	array(
		'type'           => 'textfield',
		'heading'        => esc_html__( 'Extra class', 'slzexploore-core' ),
		'param_name'     => 'extra_class',
		'description'    => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'slzexploore-core' )
	),
);
vc_map(array(
	'name'               => esc_html__( 'SLZ Search', 'slzexploore-core' ),
	'base'               => 'slzexploore_core_search_sc',
	'class'              => 'slzexploore_core-sc',
	'icon'               => 'icon-slzexploore_core_search_sc',
	'category'           => SLZEXPLOORE_CORE_SC_CATEGORY,
	'description'        => esc_html__( 'A search form.', 'slzexploore-core' ),
	'params'             => $params
	)
);