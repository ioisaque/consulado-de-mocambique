<?php
$params = array(
	array(
		'type'           => 'textfield',
		'heading'        => esc_html__( 'Extra class', 'slzexploore-core' ),
		'param_name'     => 'extra_class',
		'description'    => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'slzexploore-core' )
	),
);
vc_map(array(
	'name'               => esc_html__( 'SLZ Search Car', 'slzexploore-core' ),
	'base'               => 'slzexploore_core_car_search_sc',
	'class'              => 'slzexploore_core-sc',
	'icon'               => 'icon-slzexploore_core_car_search_sc',
	'category'           => SLZEXPLOORE_CORE_SC_CATEGORY,
	'description'        => esc_html__( 'A car search form.', 'slzexploore-core' ),
	'params'             => $params
	)
);