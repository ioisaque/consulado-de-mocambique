<?php
$category = Slzexploore_Core_Com::get_tax_options2slug( 'slzexploore_hotel_cat', array('empty' => esc_html__( '--All Categories--', 'slzexploore-core' ) ) );
$location = Slzexploore_Core_Com::get_tax_options2slug( 'slzexploore_hotel_location', array('empty' => esc_html__( '--All Locations--', 'slzexploore-core' ) ) );
$facility = Slzexploore_Core_Com::get_tax_options2slug( 'slzexploore_hotel_facility', array('empty' => esc_html__( '--All Facilities--', 'slzexploore-core' ) ) );
$author = Slzexploore_Core_Com::get_user_login2id(array(), array('empty' => esc_html__( '-All Authors-', 'slzexploore-core' ) ) );

$sort_by = Slzexploore_Core::get_params('sort-accomodation');
$featured_filter = Slzexploore_Core::get_params('featured-filter');
$paging = array(
	esc_html__('No', 'slzexploore-core')  => '',
	esc_html__('Yes', 'slzexploore-core') => 'yes',
);
$columns = array(
	esc_html__('One', 'slzexploore-core')   => '1',
	esc_html__('Two', 'slzexploore-core')   => '2',
	esc_html__('Three', 'slzexploore-core') => '3'
);

$params = array(
	array(
		'type'        => 'dropdown',
		'heading'     => esc_html__( 'Columns', 'slzexploore-core' ),
		'param_name'  => 'columns',
		'value'       => $columns,
		'std'         => '2',
		'description' => esc_html__( 'Select column to display list accommodations.', 'slzexploore-core' ),
	),
	array(
		'type'        => 'textfield',
		'heading'     => esc_html__( 'Limit posts', 'slzexploore-core' ),
		'param_name'  => 'limit_post',
		'value'       => '4',
		'description' => esc_html__( 'Add limit posts per page. Set -1 to show all.', 'slzexploore-core' )
	),
	array(
		'type'        => 'textfield',
		'heading'     => esc_html__( 'Offset post', 'slzexploore-core' ),
		'param_name'  => 'offset_post',
		'value'       => '',
		'description' => esc_html__( 'Enter offset to pass over posts. If you want to start on record 6, using offset 5', 'slzexploore-core' )
	),
	array(
		'type'        => 'dropdown',
		'heading'     => esc_html__( 'Sort by', 'slzexploore-core' ),
		'param_name'  => 'sort_by',
		'value'       => $sort_by,
		'description' => esc_html__( 'Select order to display list accomodations.', 'slzexploore-core' ),
	),
	array(
		'type'        => 'dropdown',
		'heading'     => esc_html__( 'Show pagination', 'slzexploore-core' ),
		'param_name'  => 'pagination',
		'value'       => $paging,
		'std'         => 'yes',
		'description' => esc_html__( 'Show or hide pagination.', 'slzexploore-core' ),
	),
	array(
		'type'        => 'colorpicker',
		'heading'     => esc_html__( 'Background Color', 'slzexploore-core' ),
		'param_name'  => 'bg_color',
		'value'       => '',
		'description' => esc_html__( 'Enter background color', 'slzexploore-core' ),
	),
	array(
		'type'        => 'textfield',
		'heading'     => esc_html__( 'Extra Class', 'slzexploore-core' ),
		'param_name'  => 'extra_class',
		'value'       => '',
		'description' => esc_html__( 'Add extra class to block', 'slzexploore-core' )
	),
	array(
		'type'        => 'dropdown',
		'heading'     => esc_html__( 'Featured Accommodation', 'slzexploore-core' ),
		'param_name'  => 'featured_filter',
		'value'       => $featured_filter,
		'description' => esc_html__( 'Filter by featured accommodation or none.', 'slzexploore-core' ),
		'group'       => esc_html__( 'Filter By', 'slzexploore-core')
	),
	array(
		'type'       => 'param_group',
		'heading'    => esc_html__( 'Category', 'slzexploore-core' ),
		'param_name' => 'category_list',
		'params'     => array(
			array(
				'type'        => 'dropdown',
				'admin_label' => true,
				'heading'     => esc_html__( 'Add Category', 'slzexploore-core' ),
				'param_name'  => 'category_slug',
				'value'       => $category,
				'description' => esc_html__( 'Choose special category to filter', 'slzexploore-core'  )
			),
		),
		'value'       => '',
		'description' => esc_html__( 'Default no filter by category.', 'slzexploore-core' ),
		'group'       => esc_html__( 'Filter By', 'slzexploore-core')
	),
	array(
		'type'       => 'param_group',
		'heading'    => esc_html__( 'Location', 'slzexploore-core' ),
		'param_name' => 'location_list',
		'params'     => array(
			array(
				'type'        => 'dropdown',
				'admin_label' => true,
				'heading'     => esc_html__( 'Select Location', 'slzexploore-core' ),
				'param_name'  => 'location_slug',
				'value'       => $location,
				'description' => esc_html__( 'Choose special location to filter accommodation', 'slzexploore-core'  )
			),
		),
		'value'       => '',
		'description' => esc_html__( 'Default no filter by location.', 'slzexploore-core' ),
		'group'       => esc_html__( 'Filter By', 'slzexploore-core')
	),
	array(
		'type'       => 'param_group',
		'heading'    => esc_html__( 'Facility', 'slzexploore-core' ),
		'param_name' => 'facility_list',
		'params'     => array(
			array(
				'type'        => 'dropdown',
				'admin_label' => true,
				'heading'     => esc_html__( 'Select Facility', 'slzexploore-core' ),
				'param_name'  => 'facility_slug',
				'value'       => $facility,
				'description' => esc_html__( 'Choose special facility to filter accommodation', 'slzexploore-core'  )
			),
		),
		'value'       => '',
		'description' => esc_html__( 'Default no filter by facility.', 'slzexploore-core' ),
		'group'       => esc_html__( 'Filter By', 'slzexploore-core')
	),
	array(
		'type'       => 'param_group',
		'heading'    => esc_html__( 'Author', 'slzexploore-core' ),
		'param_name' => 'author_list',
		'params'     => array(
			array(
				'type'        => 'dropdown',
				'admin_label' => true,
				'heading'     => esc_html__( 'Add Author', 'slzexploore-core' ),
				'param_name'  => 'author',
				'value'       => $author,
				'description' => esc_html__( 'Choose special author to filter', 'slzexploore-core'  )
			),
		),
		'value'       => '',
		'description' => esc_html__( 'Default no filter by author.', 'slzexploore-core' ),
		'group'       => esc_html__( 'Filter By', 'slzexploore-core')
	),
	// Read more button
	array(
		'type'        => 'textfield',
		'heading'     => esc_html__( 'Button Content', 'slzexploore-core' ),
		'param_name'  => 'btn_book',
		'value'       => '',
		'description' => esc_html__( 'Enter content to button', 'slzexploore-core' ),
		'group'       => esc_html__( 'Read More Buttons', 'slzexploore-core'),
	),
	array(
		'type'        => 'vc_link',
		'heading'     => esc_html__( 'URL (Link)', 'slzexploore-core' ),
		'param_name'  => 'btn_link',
		'value'       => '',
		'description' => esc_html__( 'Add link to button.', 'slzexploore-core' ),
		'group'       => esc_html__( 'Read More Buttons', 'slzexploore-core'),
	),
	array(
		'type'        => 'colorpicker',
		'heading'     => esc_html__( 'Text Color', 'slzexploore-core' ),
		'param_name'  => 'btn_book_color',
		'value'       => '',
		'description' => esc_html__( 'Enter text color to button', 'slzexploore-core' ),
		'group'       => esc_html__( 'Read More Buttons', 'slzexploore-core'),
	),
	array(
		'type'        => 'colorpicker',
		'heading'     => esc_html__( 'Background Color', 'slzexploore-core' ),
		'param_name'  => 'btn_book_bg_color',
		'value'       => '',
		'description' => esc_html__( 'Enter background button color to button', 'slzexploore-core' ),
		'group'       => esc_html__( 'Read More Buttons', 'slzexploore-core'),
	),
	array(
		'type'        => 'colorpicker',
		'heading'     => esc_html__( 'Hover Background Color', 'slzexploore-core' ),
		'param_name'  => 'btn_book_hover_bg_color',
		'value'       => '',
		'description' => esc_html__( 'Enter background button color to button', 'slzexploore-core' ),
		'group'       => esc_html__( 'Read More Buttons', 'slzexploore-core'),
	),
);
vc_map(array(
	'name'        => esc_html__( 'SLZ Hotel Grid', 'slzexploore-core' ),
	'base'        => 'slzexploore_core_accommodation_grid_sc',
	'class'       => 'slzexploore_core-sc',
	'icon'        => 'icon-slzexploore_core_accommodation_grid_sc',
	'category'    => SLZEXPLOORE_CORE_SC_CATEGORY,
	'description' => esc_html__( 'List accommodations in grid', 'slzexploore-core' ),
	'params'      => $params
));