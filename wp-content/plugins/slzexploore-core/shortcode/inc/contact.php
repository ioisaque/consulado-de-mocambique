<?php
$icon_sh = Slzexploore_Core::get_font_icons('sh-icons');
$contact_form_arr = array(esc_html__( '-None-', 'slzexploore-core' ) => '');
$style  = array(
	esc_html__('Style 1 - Contact form', 'slzexploore-core') 	=> '1',
	esc_html__('Style 2 - Contact form', 'slzexploore-core')	=> '2',
	esc_html__('Style 3 - Info and Maps', 'slzexploore-core')	=> '3',
);
$args = array (
			'post_type'     => 'wpcf7_contact_form',
			'post_per_page' => -1,
			'status'        => 'publish',
			'suppress_filters' => false,
		);
$post_arr = get_posts( $args );
foreach( $post_arr as $post ){
	$k = ( !empty( $post->post_title ) )? $post->post_title : $post->post_name;
	$contact_form_arr[$k] =  $post->ID ;
}

$param = array (
	array(
		'type'        => 'dropdown',
		'heading'     => esc_html__( 'Style', 'slzexploore-core' ),
		'param_name'  => 'style',
		'value'       => $style,
		'description' => esc_html__( 'Choose style to display.', 'slzexploore-core' )
	),
	array(
		'type'            => 'checkbox',
		'heading'         => esc_html__( 'Insert container', 'slzexploore-core' ),
		'param_name'      => 'insert_container',
		'value'           => array( esc_html__( 'Yes', 'slzexploore-core' ) => 'yes' ),
		'description' => esc_html__( 'Checked to insert container for full width.', 'slzexploore-core' ),
		'dependency' => array(
			'element' => 'style',
			'value'   => array( '1', '2' ),
		),
	),
	array(
		'type'           => 'textfield',
		'heading'        => esc_html__( 'Title', 'slzexploore-core' ),
		'param_name'     => 'title',
		'description'    => esc_html__( 'Enter title.', 'slzexploore-core' )
	),
	array(
		'type'           => 'textarea',
		'heading'        => esc_html__( 'Description', 'slzexploore-core' ),
		'param_name'     => 'description',
		'description'    => esc_html__( 'Enter description.', 'slzexploore-core' ),
		'dependency' => array(
			'element' => 'style',
			'value'   => array( '1', '2' ),
		),
	),
	array(
		'type'           => 'textfield',
		'heading'        => esc_html__( 'Extra Class', 'slzexploore-core' ),
		'param_name'     => 'extra_class',
		'description'    => esc_html__( 'Enter extra class name.', 'slzexploore-core' )
	),
	array(
		'type'           => 'dropdown',
		'heading'        => esc_html__( 'Contact Form', 'slzexploore-core' ),
		'param_name'     => 'contact_form',
		'value'          => $contact_form_arr,
		'group'          => esc_html__('Contact Form', 'slzexploore-core' ),
		'description'    => esc_html__( 'Choose contact form to display contact page.', 'slzexploore-core' ),
		'dependency' => array(
			'element' => 'style',
			'value'   => array( '1', '2' ),
		),
	),
	array(
		'type'           => 'attach_image',
		'heading'        => esc_html__( 'Image of Contact form.', 'slzexploore-core' ),
		'param_name'     => 'image',
		'group'          => esc_html__('Contact Form', 'slzexploore-core' ),
		'description'    => esc_html__( 'Choose image for contact form.', 'slzexploore-core' ),
		'dependency' => array(
			'element' => 'style',
			'value'   => array( '1', '2' ),
		),
	),	
	array(
		'type'           => 'attach_image',
		'heading'        => esc_html__( 'Backgroud Image of Contact form.', 'slzexploore-core' ),
		'param_name'     => 'background',
		'group'          => esc_html__('Contact Form', 'slzexploore-core' ),
		'description'    => esc_html__( 'Choose background image for contact form.', 'slzexploore-core' ),
		'dependency' => array(
			'element' => 'style',
			'value'   => array( '1', '2' ),
		),
	),
	array(
		'type'           => 'iconpicker',
		'heading'        => esc_html__( 'Icon of address', 'slzexploore-core' ),
		'param_name'     => 'address_icon',
		'value'          => 'fa fa-map-marker',
		'group'          => esc_html__('Contact Info', 'slzexploore-core' ),
		'description'    => esc_html__( 'Select icon for address.', 'slzexploore-core' ),
		'dependency' => array(
			'element' => 'style',
			'value'   => array( '3' ),
		),
	),
	array(
		'type'           => 'textfield',
		'heading'        => esc_html__( 'Address', 'slzexploore-core' ),
		'param_name'     => 'address',
		'group'          => esc_html__('Contact Info', 'slzexploore-core' ),
		'description'    => esc_html__( 'Enter address. Address will automatically render the google map.', 'slzexploore-core' ),
		'dependency' => array(
			'element' => 'style',
			'value'   => array( '3' ),
		),
	),
	array(
		'type'           => 'iconpicker',
		'heading'        => esc_html__( 'Icon of phone', 'slzexploore-core' ),
		'param_name'     => 'phone_icon',
		'value'          => 'fa fa-phone',
		'group'          => esc_html__('Contact Info', 'slzexploore-core' ),
		'description'    => esc_html__( 'Select icon for phone.', 'slzexploore-core' ),
		'dependency' => array(
			'element' => 'style',
			'value'   => array( '3' ),
		),
	),
	array(
		'type'           => 'textfield',
		'heading'        => esc_html__( 'Phone', 'slzexploore-core' ),
		'param_name'     => 'phone',
		'group'          => esc_html__('Contact Info', 'slzexploore-core' ),
		'description'    => esc_html__( 'Enter phone.', 'slzexploore-core' ),
		'dependency' => array(
			'element' => 'style',
			'value'   => array( '3' ),
		),
	),
	array(
		'type'           => 'iconpicker',
		'heading'        => esc_html__( 'Icon of email', 'slzexploore-core' ),
		'param_name'     => 'email_icon',
		'value'          => 'fa fa-envelope',
		'group'          => esc_html__('Contact Info', 'slzexploore-core' ),
		'description'    => esc_html__( 'Select icon for email.', 'slzexploore-core' ),
		'dependency' => array(
			'element' => 'style',
			'value'   => array( '3' ),
		),
	),
	array(
		'type'           => 'textfield',
		'heading'        => esc_html__( 'Email', 'slzexploore-core' ),
		'param_name'     => 'email',
		'group'          => esc_html__('Contact Info', 'slzexploore-core' ),
		'description'    => esc_html__( 'Enter email.', 'slzexploore-core' ),
		'dependency' => array(
			'element' => 'style',
			'value'   => array( '3' ),
		),
	),
);

vc_map(array(
	'name'        => esc_html__( 'SLZ Contact', 'slzexploore-core' ),
	'base'        => 'slzexploore_core_contact_sc',
	'class'       => 'slzexploore_core-sc',
	'icon'        => 'icon-slzexploore_core_contact_sc',
	'category'    => SLZEXPLOORE_CORE_SC_CATEGORY,
	'description' => esc_html__( 'Contact information.', 'slzexploore-core' ),
	'params'      => $param 
	)
);