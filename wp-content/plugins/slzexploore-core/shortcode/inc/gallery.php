<?php
$column = array(
		esc_html__('Three', 'slzexploore-core') 	=> '3',
		esc_html__('Four', 'slzexploore-core')   	=> '4',
		esc_html__('Five', 'slzexploore-core')   	=> '5',
	);
$style  = array(
	esc_html__('Masonry Grid', 'slzexploore-core') 	=> 'masonry_grid',
	esc_html__('Image Slider', 'slzexploore-core')	=> 'image_slider',
	esc_html__('Image Grid', 'slzexploore-core')	=> 'image_grid',
	esc_html__('Image Regions', 'slzexploore-core')	=> 'image_regions',
);
$arrows = array(
	esc_html__('Hide', 'slzexploore-core')	=> 'false',
	esc_html__('Show', 'slzexploore-core') 	=> 'true',
);
$thumbsize = array(
	esc_html__('Medium', 'slzexploore-core')    => 'medium',
	esc_html__('Large', 'slzexploore-core')     => 'large',
);
$icon_type = Slzexploore_Core::get_params('icon_type');
$params = array(
	array(
		'type'        => 'dropdown',
		'heading'     => esc_html__( 'Style', 'slzexploore-core' ),
		'param_name'  => 'style',
		'value'       => $style,
		'description' => esc_html__( 'Choose style to display.', 'slzexploore-core' )
	),
	array(
		'type'        => 'textfield',
		'heading'     => esc_html__( 'Title', 'slzexploore-core' ),
		'param_name'  => 'title',
		'value'       => '',
		'description' => esc_html__( 'Enter title of gallery image', 'slzexploore-core' ),
		'dependency' => array(
			'element' => 'style',
			'value'   => array( 'masonry_grid' ),
		),
	),
	array(
		'type'        => 'dropdown',
		'heading'     => esc_html__( 'Arrows?', 'slzexploore-core' ),
		'param_name'  => 'arrows',
		'value'       => $arrows,
		'dependency' => array(
			'element' => 'style',
			'value'   => array( 'image_slider' ),
		),
		'description' => esc_html__( 'Choose show/hide arrows on slider.', 'slzexploore-core' )
	),
	array(
		'type'        => 'dropdown',
		'heading'     => esc_html__( 'Select Image Size', 'slzexploore-core' ),
		'param_name'  => 'image_size',
		'value'       => $thumbsize,
		'std'         => 'medium',
		'dependency' => array(
			'element' => 'style',
			'value'   => array( 'image_slider' ),
		),
		'description' => esc_html__( 'Choose thumb size of image.', 'slzexploore-core' )
	),
	array(
		'type'        => 'dropdown',
		'heading'     => esc_html__( 'Column', 'slzexploore-core' ),
		'param_name'  => 'column',
		'value'       => $column,
		'std'		  => '4',
		'description' => esc_html__( 'Selecting the columns to display', 'slzexploore-core' ),
		'dependency' => array(
			'element' => 'style',
			'value'   => array( 'image_grid' ),
		),
	),
	array(
		'type'            => 'attach_images',
		'heading'         => esc_html__( 'Image', 'slzexploore-core' ),
		'param_name'      => 'images',
		'description'     => esc_html__( 'Add images. For Masonry style, you should choose the 5 photos.', 'slzexploore-core' ),
		'dependency' => array(
			'element' => 'style',
			'value'   => array( 'masonry_grid', 'image_slider', 'image_grid' ),
		),
	),
	array(
		'type'       => 'param_group',
		'heading'    => esc_html__( 'Add Item', 'slzexploore-core' ),
		'param_name' => 'images_all',
		'params'     => array(
			array(
				'type'           => 'dropdown',
				'heading'        => esc_html__( 'Choose Type of Icon', 'slzexploore-core' ),
				'param_name'     => 'icon_type',
				'value'          => $icon_type,
				'description'    => esc_html__( 'Choose style to display block.', 'slzexploore-core' ),
			),
			array(
				'type'           => 'iconpicker',
				'heading'        => esc_html__( 'Choose Icon', 'slzexploore-core' ),
				'param_name'     => 'icon_fw',
				'dependency'     => array(
					'element'  => 'icon_type',
					'value'    => array('02')),
				'description'    => esc_html__( 'Choose icon to display above title.', 'slzexploore-core' ),
			),
			array(
				'type'           => 'dropdown',
				'heading'        => esc_html__( 'Choose Icon', 'slzexploore-core' ),
				'param_name'     => 'icon_ex',
				'value'          => $icon_ex,
				'dependency'     => array(
					'element'  => 'icon_type',
					'value'    => array('')),
				'description'    => sprintf(__( 'Please go on "Exploore->%s" to referentce about icons of our theme.', 'slzexploore-core' ), $admin_icon_url ),
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Icon Position Top', 'slzexploore-core' ),
				'param_name'  => 'position_top',
				'value'       => '',
				'description' => esc_html__( 'Enter a number to represent the distance from the container. The unit used is %. Just enter the number. Example: For the distance to the top is 50%, you enter the number 50.', 'slzexploore-core' ),
				'dependency' => array(
					'element' => 'style',
					'value'   => array( 'image_regions' ),
				),
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Icon Position Left', 'slzexploore-core' ),
				'param_name'  => 'position_left',
				'value'       => '',
				'description' => esc_html__( 'Enter a number to represent the distance from the container. The unit used is %. Just enter the number. Example: For the distance to the left is 50%, you enter the number 50.', 'slzexploore-core' ),
				'dependency' => array(
					'element' => 'style',
					'value'   => array( 'image_regions' ),
				),
			),
			array(
				'type'           => 'attach_image',
				'heading'        => esc_html__( 'Choose Image', 'slzexploore-core' ),
				'param_name'     => 'img',
				'description'    => esc_html__( 'Choose single image to display.', 'slzexploore-core' ),
			),
			array(
				'type'            => 'vc_link',
				'heading'         => esc_html__( 'URL (Link)', 'slzexploore-core' ),
				'param_name'      => 'url',
				'value'           => '',
				'description'     => esc_html__( 'Add link to image.', 'slzexploore-core' )
			)
		),
		'value'       => '',
		'dependency'     => array(
			'element'  => 'style',
			'value'    => array( 'image_regions' ),
		),
	),
	array(
		'type'        => 'textfield',
		'heading'     => esc_html__( 'Extra Class', 'slzexploore-core' ),
		'param_name'  => 'extra_class',
		'description' => esc_html__( 'Enter extra class.', 'slzexploore-core' )
	),
);
vc_map(array(
	'name'            => esc_html__( 'SLZ Image Gallery', 'slzexploore-core' ),
	'base'            => 'slzexploore_core_gallery_sc',
	'class'           => 'slzexploore_core-sc',
	'icon'            => 'icon-slzexploore_core_blog_sc',
	'category'        => SLZEXPLOORE_CORE_SC_CATEGORY,
	'description'     => esc_html__( 'Display image gallery.', 'slzexploore-core' ),
	'params'          => $params
	)
);