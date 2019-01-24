<?php
$contact_form_arr = array(esc_html__( '-None-', 'slzexploore-core' ) => '');
$args = array (
			'post_type'        => 'wpcf7_contact_form',
			'post_per_page'    => -1,
			'status'           => 'publish',
			'suppress_filters' => false,
		);
$post_arr = get_posts( $args );
foreach( $post_arr as $post ){
	$k = ( !empty( $post->post_title ) )? $post->post_title : $post->post_name;
	$contact_form_arr[$k] =  $post->ID ;
}

$params = array(
	array(
		'type'            => 'dropdown',
		'heading'         => esc_html__( 'Contact Form', 'slzexploore-core' ),
		'param_name'      => 'contact_form',
		'value'           => $contact_form_arr,
		'description'     => esc_html__( 'Select contact form to display.', 'slzexploore-core' ),
	),
	array(
		'type'        	=> 'textfield',
		'heading'     	=> esc_html__( 'Box Title', 'slzexploore-core' ),
		'param_name'  	=> 'title_box',
		'value'       	=> '',
		'description' 	=> esc_html__( 'Enter title of box.', 'slzexploore-core' ),
	),
	array(
		'type'           => 'textfield',
		'heading'        => esc_html__( 'Extra Class', 'slzexploore-core' ),
		'param_name'     => 'extra_class',
		'description'    => esc_html__( 'Enter extra class name.', 'slzexploore-core' ),
	),
	array(
		'type'            => 'colorpicker',
		'heading'         => esc_html__( 'Error Color', 'slzexploore-core' ),
		'param_name'      => 'color_error',
		'description'     => esc_html__( 'Choose color for error filed, notification response box.', 'slzexploore-core' ),
		'group'           => esc_html__('Custom', 'slzexploore-core'),
	),
	array(
		'type'            => 'colorpicker',
		'heading'         => esc_html__( 'Box Background', 'slzexploore-core' ),
		'param_name'      => 'background_box',
		'description'     => esc_html__( 'Choose color for box background.', 'slzexploore-core' ),
		'group'           => esc_html__('Custom', 'slzexploore-core'),
	),
	array(
		'type'            => 'colorpicker',
		'heading'         => esc_html__( 'Title Box Color', 'slzexploore-core' ),
		'param_name'      => 'color_title_box',
		'description'     => esc_html__( 'Choose color for title box.', 'slzexploore-core' ),
		'group'           => esc_html__('Custom', 'slzexploore-core'),
	),
	array(
		'type'            => 'colorpicker',
		'heading'         => esc_html__( 'Input Background', 'slzexploore-core' ),
		'param_name'      => 'background_input',
		'description'     => esc_html__( 'Choose color for input background.', 'slzexploore-core' ),
		'group'           => esc_html__('Custom', 'slzexploore-core'),
	),
	array(
		'type'            => 'colorpicker',
		'heading'         => esc_html__( 'Input Color', 'slzexploore-core' ),
		'param_name'      => 'color_input',
		'description'     => esc_html__( 'Choose color for input text.', 'slzexploore-core' ),
		'group'           => esc_html__('Custom', 'slzexploore-core'),
	),
	array(
		'type'            => 'colorpicker',
		'heading'         => esc_html__( 'Button Text Color', 'slzexploore-core' ),
		'param_name'      => 'color_text_button',
		'description'     => esc_html__( 'Choose color for button text.', 'slzexploore-core' ),
		'group'           => esc_html__('Custom', 'slzexploore-core'),
	),
	array(
		'type'            => 'colorpicker',
		'heading'         => esc_html__( 'Button Text Color Hover', 'slzexploore-core' ),
		'param_name'      => 'color_text_button_hv',
		'description'     => esc_html__( 'Choose color for button text when hover.', 'slzexploore-core' ),
		'group'           => esc_html__('Custom', 'slzexploore-core'),
	),
	array(
		'type'            => 'colorpicker',
		'heading'         => esc_html__( 'Button Background', 'slzexploore-core' ),
		'param_name'      => 'background_button',
		'description'     => esc_html__( 'Choose color for button background.', 'slzexploore-core' ),
		'group'           => esc_html__('Custom', 'slzexploore-core'),
	),	
	array(
		'type'            => 'colorpicker',
		'heading'         => esc_html__( 'Button Background Hover', 'slzexploore-core' ),
		'param_name'      => 'background_button_hv',
		'description'     => esc_html__( 'Choose color for button background when hover.', 'slzexploore-core' ),
		'group'           => esc_html__('Custom', 'slzexploore-core'),
	),	
);
vc_map(array(
	'name'        => esc_html__( 'SLZ FAQs Request', 'slzexploore-core' ),
	'base'        => 'slzexploore_core_faq_request_sc',
	'class'       => 'slzexploore_core-sc',
	'icon'        => 'icon-slzexploore_core_faq_request_sc',
	'category'    => SLZEXPLOORE_CORE_SC_CATEGORY,
	'description' => esc_html__( 'Form FAQs Request.', 'slzexploore-core' ),
	'params'      => $params
));