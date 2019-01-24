<?php

$params = array(
	array(
		'type'       => 'param_group',
		'heading'    => esc_html__( 'Toggle Content', 'slzexploore-core' ),
		'param_name' => 'toggle_content',
		'params'     => array(
			array(
				'type'        => 'textfield',
				'holder'      => 'div',
				'class'       => '',
				'admin_label' => true,
				'heading'     => esc_html__( 'Title', 'slzexploore-core' ),
				'param_name'  => 'title',
				'description' => esc_html__( 'Enter title here', 'slzexploore-core' )
			),
			array(
				'type'        => 'textarea',
				'holder'      => 'div',
				'class'       => '',
				'heading'     => esc_html__( 'Content', 'slzexploore-core' ),
				'param_name'  => 'body_content',
				'description' => esc_html__( 'Enter content here', 'slzexploore-core' )
			)
		)
	),
	array(
		'type'            => 'textfield',
		'heading'         => esc_html__( 'Extra Class', 'slzexploore-core' ),
		'param_name'      => 'extra_class',
		'description'     => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'slzexploore-core' )
	)
);

vc_map(
	array(
		'name'			=> esc_html__( 'SLZ Toggle Box', 'slzexploore-core' ),
		'base'			=> 'slzexploore_core_toggle_box_sc',
		'class'			=> 'slzexploore_core-sc',
		'category'		=> SLZEXPLOORE_CORE_SC_CATEGORY,
		'icon'			=> 'icon-slzexploore_core_toggle_box_sc',
		'description'	=> esc_html__( 'Toggle Box', 'slzexploore-core' ),
		'params'		=> $params
	)
);
