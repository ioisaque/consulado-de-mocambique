<?php
$sort_by = Slzexploore_Core::get_params('sort-other');

$args = array('post_type'     => 'slzexploore_faq');
$options = array('empty'      => esc_html__( '-All FAQs-', 'slzexploore-core' ) );
$faq = Slzexploore_Core_Com::get_post_title2id( $args, $options );

$method = array(
	esc_html__( 'Category', 'slzexploore-core' )  => 'cat',
	esc_html__( 'FAQ', 'slzexploore-core' )       => 'faq'
);

// get service categories
$taxonomy = 'slzexploore_faq_cat';
$params_cat = array('empty'   => esc_html__( '-All FAQs Categories-', 'slzexploore-core' ) );
$categories = Slzexploore_Core_Com::get_tax_options2slug( $taxonomy, $params_cat );

$params = array(
	array(
		'type'            => 'textfield',
		'heading'         => esc_html__( 'Limit Posts', 'slzexploore-core' ),
		'param_name'      => 'limit_post',
		'value'           => '',
		'description'     => esc_html__( 'Add limit posts per page. Set -1 or empty to show all.', 'slzexploore-core' )
	),
	array(
		'type'            => 'textfield',
		'heading'         => esc_html__( 'Offset Posts', 'slzexploore-core' ),
		'param_name'      => 'offset_post',
		'value'           => '0',
		'description'     => esc_html__( 'Enter offset to pass over posts. If you want to start on record 6, using offset 5', 'slzexploore-core' )
	),
	array(
		'type'            => 'dropdown',
		'heading'         => esc_html__( 'Sort By', 'slzexploore-core' ),
		'param_name'      => 'sort_by',
		'value'           => $sort_by,
		'description'     => esc_html__( 'Select order to display list properties.', 'slzexploore-core' ),
	),
	array(
		'type'			  => 'dropdown',
		'heading'		  => esc_html__( 'Display By', 'slzexploore-core' ),
		'param_name'	  => 'method',
		'value'			  => $method,
		'description'	  => esc_html__( 'Choose FAQ category or special FAQ to display', 'slzexploore-core' ),
	),
	array(
		'type'            => 'param_group',
		'heading'         => esc_html__( 'FAQ', 'slzexploore-core' ),
		'param_name'      => 'list_faq',
		'params'          => array(
			array(
				'type'        => 'dropdown',
				'admin_label' => true,
				'heading'     => esc_html__( 'Add FAQ', 'slzexploore-core' ),
				'param_name'  => 'faq',
				'value'       => $faq,
				'description' => esc_html__( 'Choose special FAQ to show',  'slzexploore-core'  )
			),
			
		),
		'value'           => '',
		'dependency'  => array(
			'element'   => 'method',
			'value'     => array( 'faq' )
		),
		'description'     => esc_html__( 'Default display all FAQ if no FAQ is selected and Number FAQ is empty.', 'slzexploore-core' )
	),
	array(
		'type'            => 'param_group',
		'heading'         => esc_html__( 'FAQ Category', 'slzexploore-core' ),
		'param_name'      => 'list_cat',
		'params'          => array(
			array(
				'type'        => 'dropdown',
				'admin_label' => true,
				'heading'     => esc_html__( 'Add Category', 'slzexploore-core' ),
				'param_name'  => 'faq_category',
				'value'       => $categories,
				'description' => esc_html__( 'Choose special FAQ category to show',  'slzexploore-core'  )
			),
			
		),
		'value'           => '',
		'dependency'  => array(
			'element'   => 'method',
			'value'     => array( 'cat' )
		),
		'description'     => esc_html__( 'Default display all FAQ if no FAQ is selected and Number FAQ is empty.', 'slzexploore-core' )
	),
	array(
		'type'            => 'textfield',
		'heading'         => esc_html__( 'Extra Class', 'slzexploore-core' ),
		'param_name'      => 'extra_class',
		'description'     => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'slzexploore-core' )
	)
);
vc_map(array(
	'name'        => esc_html__( 'SLZ FAQs', 'slzexploore-core' ),
	'base'        => 'slzexploore_core_faqs_sc',
	'class'       => 'slzexploore_core-sc',
	'icon'        => 'icon-slzexploore_core_faqs_sc',
	'category'    => SLZEXPLOORE_CORE_SC_CATEGORY,
	'description' => esc_html__( 'List of FAQs.', 'slzexploore-core' ),
	'params'      => $params
));