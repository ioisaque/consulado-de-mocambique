<?php
$style = Slzexploore_Core::get_params( 'item_list_style' );

$yes_no = array(
	esc_html__( 'Yes', 'slzexploore-core' ) => '1',
	esc_html__( 'No', 'slzexploore-core' )  => '0'
);

$animation = array(
	esc_html__( 'Slide', 'slzexploore-core' ) => '0',
	esc_html__( 'Fade', 'slzexploore-core' )  => '1'
);

$params = array(
	array(
		'type'        => 'textfield',
		'heading'     => esc_html__( 'Block Title', 'slzexploore-core' ),
		'param_name'  => 'block_title',
		'value'       => $style,
		'description' => esc_html__( 'Enter title for block.', 'slzexploore-core' )
	),
	array(
		'type'        => 'param_group',
		'heading'     => esc_html__( 'Tour Schedule', 'slzexploore-core' ),
		'param_name'  => 'tour_schedule_list',
		'params'      => array(
			array(
				'type'        => 'textfield',
				'admin_label' => true,
				'heading'     => esc_html__( 'Title', 'slzexploore-core' ),
				'param_name'  => 'title',
				'value'       => '',
				'description' => esc_html__( 'Enter title for each item.', 'slzexploore-core' )
			),
			array(
				'type'        => 'iconpicker',
				'heading'     => esc_html__( 'Icon', 'slzexploore-core' ),
				'param_name'  => 'icon',
				'value'       => '',
				'description' => esc_html__( 'Select icon for each item.', 'slzexploore-core' )
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Information 1', 'slzexploore-core' ),
				'param_name'  => 'subtitle',
				'value'       => '',
				'description' => esc_html__( 'Enter information for each item.', 'slzexploore-core' )
			),
			array(
				'type'        => 'textarea',
				'heading'     => esc_html__( 'Information 2', 'slzexploore-core' ),
				'param_name'  => 'description',
				'description' => esc_html__( 'Enter information for each item.', 'slzexploore-core' ),
			),
			array(
				'type'        => 'attach_images',
				'heading'     => esc_html__( 'Add Images', 'slzexploore-core' ),
				'param_name'  => 'image',
				'description' => esc_html__( 'Choose images for each item.', 'slzexploore-core' ),
			),
		),
		'value'       => '',
		'description' => esc_html__( 'Add item for tour schedule block.', 'slzexploore-core' ),
	),
	array(
		'type'        => 'textfield',
		'heading'     => esc_html__( 'Extra Class', 'slzexploore-core' ),
		'param_name'  => 'extra_class',
		'description' => esc_html__( 'Enter extra class name.', 'slzexploore-core' )
	),
);

$custom_slide = array(
	array(
		'type'        => 'dropdown',
		'heading'     => esc_html__( 'Show Dots ?', 'slzexploore-core' ),
		'param_name'  => 'show_dots',
		'value'       => $yes_no,
		'std'         => '1',
		'description' => esc_html__( 'If choose Yes, block will be show dots.', 'slzexploore-core' ),
		'group'       => esc_html__( 'Slide Custom', 'slzexploore-core' )
	),
	array(
		'type'        => 'dropdown',
		'heading'     => esc_html__( 'Show Arrow ?', 'slzexploore-core' ),
		'param_name'  => 'show_arrows',
		'value'       => $yes_no,
		'std'         => '1',
		'description' => esc_html__( 'If choose Yes, block will be show arrow.', 'slzexploore-core' ),
		'group'       => esc_html__( 'Slide Custom', 'slzexploore-core' )
	),
	array(
		'type'        => 'dropdown',
		'heading'     => esc_html__( 'Is Auto Play ?', 'slzexploore-core' ),
		'param_name'  => 'slide_autoplay',
		'value'       => $yes_no,
		'std'         => '1',
		'description' => esc_html__( 'Choose YES to slide auto play.', 'slzexploore-core' ),
		'group'       => esc_html__( 'Slide Custom', 'slzexploore-core' )
	),
	array(
		'type'        => 'dropdown',
		'heading'     => esc_html__( 'Is Loop Infinite ?', 'slzexploore-core' ),
		'param_name'  => 'slide_infinite',
		'value'       => $yes_no,
		'std'         => '1',
		'description' => esc_html__( 'Choose YES to slide loop infinite.', 'slzexploore-core' ),
		'group'       => esc_html__( 'Slide Custom', 'slzexploore-core' )
	),
	array(
		'type'        => 'textfield',
		'heading'     => esc_html__( 'Speed Slide', 'slzexploore-core' ),
		'param_name'  => 'slide_speed',
		'value'       => '',
		'description' => esc_html__( 'Enter number value. Unit is millisecond. Example: 600.', 'slzexploore-core' ),
		'group'       => esc_html__( 'Slide Custom', 'slzexploore-core' )
	),
	array(
		'type'        => 'dropdown',
		'heading'     => esc_html__( 'Animation?', 'slzexploore-core' ),
		'param_name'  => 'slide_animation',
		'value'       => $animation,
		'std'         => '0',
		'description' => esc_html__( 'Choose a animation', 'slzexploore-core' ),
		'group'       => esc_html__( 'Slide Custom', 'slzexploore-core' )
	)
);

vc_map( array(
	'name'        => esc_html__( 'SLZ Tour Schedule', 'slzexploore-core' ),
	'base'        => 'slzexploore_core_tour_schedule_sc',
	'class'       => 'slzexploore_core-sc',
	'icon'        => 'icon-slzexploore_core_tour_schedule_sc',
	'category'    => SLZEXPLOORE_CORE_SC_CATEGORY,
	'description' => esc_html__( 'List schedules in the tour.', 'slzexploore-core' ),
	'params'      => array_merge( $params, $custom_slide )
) );