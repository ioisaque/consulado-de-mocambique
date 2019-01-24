<?php
$permalinks = get_option( 'slzexploore_permalinks' );
$default_slug = array(
	'car'             => 'car_rent',
	'car_cat'         => 'car-category',
	'car_location'    => 'car-location',
	'cruise'          => 'cruises',
	'cruise_cat'      => 'cruise-category',
	'cruise_location' => 'cruise-location',
	'cruise_facility' => 'cruise-facility',
	'hotel'           => 'accommodations',
	'hotel_cat'       => 'accommodation-category',
	'hotel_facility'  => 'facility',
	'hotel_location'  => 'location',
	'tour'            => 'tours',
	'tour_cat'        => 'tour-category',
	'tour_location'   => 'tour-location',
	'team'            => 'teams',
	'team_cat'        => 'team-category'
);
$custom_slug = Slzexploore_Core_Util::merge_array( $default_slug, $permalinks );

$default_labels    = array(
	'car'             => esc_html__( 'Car Rent', 'slzexploore-core' ),
	'car_cat'         => esc_html__( 'Car Categories', 'slzexploore-core' ),
	'car_location'    => esc_html__( 'Car Locations', 'slzexploore-core' ),
	'cruise'          => esc_html__( 'Cruises', 'slzexploore-core' ),
	'cruise_cat'      => esc_html__( 'Cruise Categories', 'slzexploore-core' ),
	'cruise_location' => esc_html__( 'Cruise Locations', 'slzexploore-core' ),
	'cruise_facility' => esc_html__( 'Cruise Facilities', 'slzexploore-core' ),
	'hotel'           => esc_html__( 'Accommodations', 'slzexploore-core' ),
	'hotel_cat'       => esc_html__( 'Accommodation Categories', 'slzexploore-core' ),
	'hotel_facility'  => esc_html__( 'Facilities', 'slzexploore-core' ),
	'hotel_location'  => esc_html__( 'Locations', 'slzexploore-core' ),
	'tour'            => esc_html__( 'Tours', 'slzexploore-core' ),
	'tour_cat'        => esc_html__( 'Tour Categories', 'slzexploore-core' ),
	'tour_location'   => esc_html__( 'Tour Locations', 'slzexploore-core' ),
	'team'            => esc_html__( 'Teams', 'slzexploore-core' ),
	'team_cat'        => esc_html__( 'Team Categories', 'slzexploore-core' )
);
$breadcrumb_labels    = array(
	'car'             => Slzexploore_Core::get_theme_option('slz-breadcrumb-labels-car'),
	'car_cat'         => Slzexploore_Core::get_theme_option('slz-breadcrumb-labels-car-cat'),
	'car_location'    => Slzexploore_Core::get_theme_option('slz-breadcrumb-labels-car-location'),
	'cruise'          => Slzexploore_Core::get_theme_option('slz-breadcrumb-labels-cruise'),
	'cruise_cat'      => Slzexploore_Core::get_theme_option('slz-breadcrumb-labels-cruise-cat'),
	'cruise_location' => Slzexploore_Core::get_theme_option('slz-breadcrumb-labels-cruise-location'),
	'cruise_facility' => Slzexploore_Core::get_theme_option('slz-breadcrumb-labels-cruise-facility'),
	'hotel'           => Slzexploore_Core::get_theme_option('slz-breadcrumb-labels-hotel'),
	'hotel_cat'       => Slzexploore_Core::get_theme_option('slz-breadcrumb-labels-hotel-cat'),
	'hotel_facility'  => Slzexploore_Core::get_theme_option('slz-breadcrumb-labels-hotel-facility'),
	'hotel_location'  => Slzexploore_Core::get_theme_option('slz-breadcrumb-labels-hotel-location'),
	'tour'            => Slzexploore_Core::get_theme_option('slz-breadcrumb-labels-tour'),
	'tour_cat'        => Slzexploore_Core::get_theme_option('slz-breadcrumb-labels-tour-cat'),
	'tour_location'   => Slzexploore_Core::get_theme_option('slz-breadcrumb-labels-tour-location'),
	'team'            => Slzexploore_Core::get_theme_option('slz-breadcrumb-labels-team'),
	'team_cat'        => Slzexploore_Core::get_theme_option('slz-breadcrumb-labels-team-cat')
);
$singular_labels = Slzexploore_Core_Util::merge_array( $default_labels, $breadcrumb_labels);

return array(
	'init' => array(
		array( 'add_action', 'slzexploore_core_add_inline_style',      array( SLZEXPLOORE_CORE_CLASS, '[setting.Setting_Init, add_inline_style]' ) ),
		array( 'add_action', 'slzexploore_core_result_filter',         array( SLZEXPLOORE_CORE_CLASS, '[top.Top_Controller, show_result_filter]' ),'',2 ),
		array( 'add_action', 'wpcf7_before_send_mail',                 array( SLZEXPLOORE_CORE_CLASS, '[top.Top_Controller, save_form_faq]' ) ),
		array( 'add_action', 'slzexploore_core_car_booking',           array( SLZEXPLOORE_CORE_CLASS, '[booking.Booking_Controller, show_car_booking]' ) ),
		array( 'add_action', 'slzexploore_core_cruise_booking',        array( SLZEXPLOORE_CORE_CLASS, '[booking.Booking_Controller, show_cruise_booking]' ) ),
		array( 'add_action', 'slzexploore_core_tour_booking',          array( SLZEXPLOORE_CORE_CLASS, '[booking.Booking_Controller, show_tour_booking]' ) ),
		array( 'add_filter', 'slzexploore_booking_price',              array( SLZEXPLOORE_CORE_CLASS, '[booking.Booking_Controller, show_booking_price]' ), 10, 2 ),
		// Woocommer Cart Item
		array( 'add_filter', 'woocommerce_is_sold_individually',             array( SLZEXPLOORE_CORE_CLASS, '[booking.Booking_Controller, is_sold_individually]' ), 10, 2 ),
		array( 'add_filter', 'woocommerce_cart_item_name',             array( SLZEXPLOORE_CORE_CLASS, '[booking.Booking_Controller, cart_item_name]' ), 20, 3 ),
		array( 'add_filter', 'woocommerce_cart_item_thumbnail',        array( SLZEXPLOORE_CORE_CLASS, '[booking.Booking_Controller, cart_item_thumbnail]' ) , 20, 3),
		array( 'add_filter', 'woocommerce_variation_is_purchasable',   array( SLZEXPLOORE_CORE_CLASS, '[booking.Booking_Controller, variation_is_purchasable]' ), 20, 2 ),
		array( 'add_filter', 'woocommerce_cart_item_price',            array( SLZEXPLOORE_CORE_CLASS, '[booking.Booking_Controller, display_item_price]' ), 10, 3 ),
		array( 'add_filter', 'woocommerce_cart_item_subtotal',         array( SLZEXPLOORE_CORE_CLASS, '[booking.Booking_Controller, display_item_subtotal]' ), 10, 3 ),
		array( 'add_filter', 'woocommerce_email_headers',              array( SLZEXPLOORE_CORE_CLASS, '[booking.Booking_Controller, add_email_headers]' ), 10, 3 ),
		array( 'add_action', 'woocommerce_add_order_item_meta',        array( SLZEXPLOORE_CORE_CLASS, '[booking.Booking_Controller, add_order_item_meta]' ), 10, 3 ),
		array( 'add_action', 'woocommerce_before_calculate_totals',    array( SLZEXPLOORE_CORE_CLASS, '[booking.Booking_Controller, add_custom_total_price]' ), 20, 1 ),
		array( 'add_action', 'woocommerce_before_checkout_process',    array( SLZEXPLOORE_CORE_CLASS, '[booking.Booking_Controller, before_checkout_process]' ) ),
		array( 'add_action', 'woocommerce_checkout_order_processed',   array( SLZEXPLOORE_CORE_CLASS, '[booking.Booking_Controller, checkout_order_processed]' ), 10, 2 ),
		array( 'add_action', 'woocommerce_cancelled_order',            array( SLZEXPLOORE_CORE_CLASS, '[booking.Booking_Controller, cancelled_order]' ), 10, 1 ),
		// Update notices
		array( 'add_action', 'admin_notices', array( SLZEXPLOORE_CORE_CLASS, '[setting.Setting_Init, update_notices]') ),

		/*******************************************************************/
		// Accommodation post type
		/*******************************************************************/
		array( 'register_post_type', 'slzexploore_hotel', array(
			'public'                => true,
			'has_archive'           => true,
			'menu_position'         => 21,
			'rewrite'               => array( 'slug' => $custom_slug['hotel'] ),
			'query_var'             => true,
			'menu_icon'             => 'dashicons-building',
			'supports'              => array( 'title', 'editor', 'thumbnail', 'comments'),
			'labels'                => array(
				'name'                  => esc_html__( 'Accommodations',          'slzexploore-core' ),
				'singular_name'         => $singular_labels['hotel'],
				'all_items'             => esc_html__( 'All Accommodations',      'slzexploore-core' ),
				'menu_name'             => esc_html__( 'Accommodations',          'slzexploore-core' ),
				'add_new'               => esc_html__( 'Add New',                 'slzexploore-core' ),
				'add_new_item'          => esc_html__( 'Add New Accommodation',   'slzexploore-core' ),
				'edit_item'             => esc_html__( 'Edit Accommodation',      'slzexploore-core' ),
			),
		)),
		array( 'register_taxonomy', 'slzexploore_hotel_cat', array( 'slzexploore_hotel' ), array(
			'hierarchical'       => true,
			'rewrite'            => array( 'slug' => $custom_slug['hotel_cat'] ),
			'query_var'          => true,
			'labels'             => array(
				'name'                  => esc_html__( 'Accommodation Categories',  'slzexploore-core' ),
				'singular_name'         => $singular_labels['hotel_cat'],
				'menu_name'             => esc_html__( 'Accommodation Categories',  'slzexploore-core' ),
				'add_new'               => esc_html__( 'Add New',                   'slzexploore-core' ),
				'add_new_item'          => esc_html__( 'Add New Category',          'slzexploore-core' ),
				'edit_item'             => esc_html__( 'Edit Category',             'slzexploore-core' ),
				'parent_item'           => esc_html__( 'Parent Category',           'slzexploore-core' ),
				'search_items'          => esc_html__( 'Search Categories',         'slzexploore-core' ),
			),
		)),
		array( 'register_taxonomy', 'slzexploore_hotel_facility', array( 'slzexploore_hotel' ), array(
			'hierarchical'       => true,
			'rewrite'            => array( 'slug' => $custom_slug['hotel_facility'] ),
			'query_var'          => true,
			'labels'             => array(
				'name'                  => esc_html__( 'Facilities',              'slzexploore-core' ),
				'singular_name'         => $singular_labels['hotel_facility'],
				'menu_name'             => esc_html__( 'Facilities',              'slzexploore-core' ),
				'add_new'               => esc_html__( 'Add New',                 'slzexploore-core' ),
				'add_new_item'          => esc_html__( 'Add New Facility',        'slzexploore-core' ),
				'edit_item'             => esc_html__( 'Edit Facility',           'slzexploore-core' ),
				'parent_item'           => esc_html__( 'Parent Facility',         'slzexploore-core' ),
				'search_items'          => esc_html__( 'Search Facilities',       'slzexploore-core' ),
			),
		)),
		array( 'register_taxonomy', 'slzexploore_hotel_location', array( 'slzexploore_hotel' ), array(
			'hierarchical'       => true,
			'rewrite'            => array( 'slug' => $custom_slug['hotel_location'] ),
			'query_var'          => true,
			'labels'             => array(
				'name'                  => esc_html__( 'Locations',               'slzexploore-core' ),
				'singular_name'         => $singular_labels['hotel_location'],
				'menu_name'             => esc_html__( 'Locations',               'slzexploore-core' ),
				'add_new'               => esc_html__( 'Add New',                 'slzexploore-core' ),
				'add_new_item'          => esc_html__( 'Add New Location',        'slzexploore-core' ),
				'edit_item'             => esc_html__( 'Edit Location',           'slzexploore-core' ),
				'parent_item'           => esc_html__( 'Parent Location',         'slzexploore-core' ),
				'search_items'          => esc_html__( 'Search Locations',        'slzexploore-core' ),
			),
		)),
		array( 'register_taxonomy', 'slzexploore_hotel_status', array( 'slzexploore_hotel' ), array(
			'public'             => false,
			'show_ui'            => true,
			'hierarchical'       => true,
			'rewrite'            => array( 'slug' => 'hotel-status' ),
			'show_in_menu'       => false,
			'query_var'          => true,
			'labels'             => array(
				'name'                  => esc_html__( 'Accommodation Status',                 'slzexploore-core' ),
				'singular_name'         => esc_html__( 'Accommodation Status',                 'slzexploore-core' ),
				'menu_name'             => esc_html__( 'Accommodation Status',                 'slzexploore-core' ),
				'add_new'               => esc_html__( 'Add New',                              'slzexploore-core' ),
				'add_new_item'          => esc_html__( 'Add New Accommodation Status',         'slzexploore-core' ),
				'edit_item'             => esc_html__( 'Edit Accommodation Status',            'slzexploore-core' ),
				'parent_item'           => esc_html__( 'Parent Accommodation Status',          'slzexploore-core' ),
				'search_items'          => esc_html__( 'Search Accommodation Status',          'slzexploore-core' ),
			),
		)),
		/*******************************************************************/
		// Rooms post type
		/*******************************************************************/
		array( 'register_post_type', 'slzexploore_room', array(
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => false,
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => false,
			'exclude_from_search'   => true,
			'has_archive'           => true,
			'rewrite'               => array( 'slug' => 'room_types' ),
			'query_var'             => true,
			'supports'              => array( 'title', 'editor', 'excerpt', 'thumbnail' ),
			'labels'                => array(
				'name'                  => esc_html__( 'All Room Types',            'slzexploore-core' ),
				'singular_name'         => esc_html__( 'All Room Types',            'slzexploore-core' ),
				'menu_name'             => esc_html__( 'All Room Types',            'slzexploore-core' ),
				'add_new'               => esc_html__( 'Add New',                   'slzexploore-core' ),
				'add_new_item'          => esc_html__( 'Add New Room Type',         'slzexploore-core' ),
				'edit_item'             => esc_html__( 'Edit Room Type',            'slzexploore-core' ),
			),
		)),
		array( 'register_taxonomy', 'slzexploore_room_cat', array( 'slzexploore_room' ), array(
			'hierarchical'       => true,
			'rewrite'            => array( 'slug' => 'room-category' ),
			'query_var'          => true,
			'labels'             => array(
				'name'                  => esc_html__( 'Room Type Categories',        'slzexploore-core' ),
				'singular_name'         => esc_html__( 'Room Type Categories',        'slzexploore-core' ),
				'menu_name'             => esc_html__( 'Room Type Categories',        'slzexploore-core' ),
				'add_new'               => esc_html__( 'Add New',                     'slzexploore-core' ),
				'add_new_item'          => esc_html__( 'Add New Room Type Category',  'slzexploore-core' ),
				'edit_item'             => esc_html__( 'Edit Room Type Category',     'slzexploore-core' ),
				'parent_item'           => esc_html__( 'Parent Room Type Category',   'slzexploore-core' ),
				'search_items'          => esc_html__( 'Search Room Type Categories', 'slzexploore-core' ),
			),
		)),
		array( 'register_taxonomy', 'slzexploore_room_status', array( 'slzexploore_room' ), array(
			'public'             => false,
			'show_ui'            => true,
			'hierarchical'       => true,
			'rewrite'            => array( 'slug' => 'room-status' ),
			'show_in_menu'       => false,
			'query_var'          => true,
			'labels'             => array(
				'name'                  => esc_html__( 'Room Status',            'slzexploore-core' ),
				'singular_name'         => esc_html__( 'Room Status',            'slzexploore-core' ),
				'menu_name'             => esc_html__( 'Room Status',            'slzexploore-core' ),
				'add_new'               => esc_html__( 'Add New',                'slzexploore-core' ),
				'add_new_item'          => esc_html__( 'Add New Room Status',    'slzexploore-core' ),
				'edit_item'             => esc_html__( 'Edit Room Status',       'slzexploore-core' ),
				'parent_item'           => esc_html__( 'Parent Room Status',     'slzexploore-core' ),
				'search_items'          => esc_html__( 'Search Room Status',     'slzexploore-core' )
			)
		)),
		/*******************************************************************/
		// Vacancy post type
		/*******************************************************************/
		array( 'register_post_type', 'slzexploore_vacancy', array(
			'public'                => false,
			'show_ui'               => true,
			'show_in_menu'          => false,
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => false,
			'exclude_from_search'   => true,
			'has_archive'           => true,
			'rewrite'               => array( 'slug' => 'vacancy' ),
			'query_var'             => true,
			'supports'              => array( '' ),
			'labels'                => array(
				'name'                  => esc_html__( 'All Vacancies',        'slzexploore-core' ),
				'singular_name'         => esc_html__( 'All Vacancies',        'slzexploore-core' ),
				'menu_name'             => esc_html__( 'All Vacancies',        'slzexploore-core' ),
				'add_new'               => esc_html__( 'Add New',              'slzexploore-core' ),
				'add_new_item'          => esc_html__( 'Add New Vacancy',      'slzexploore-core' ),
				'edit_item'             => esc_html__( 'Edit Vacancy',         'slzexploore-core' ),
			),
		)),
		/*******************************************************************/
		// Accomodation Booking post type
		/*******************************************************************/
		array( 'register_post_type', 'slzexploore_book', array(
			'public'                => false,
			'show_ui'               => true,
			'show_in_menu'          => false,
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => false,
			'exclude_from_search'   => true,
			'has_archive'           => true,
			'rewrite'               => array( 'slug' => 'booking' ),
			'query_var'             => true,
			'supports'              => array( '' ),
			'labels'                => array(
				'name'                  => esc_html__( 'All Bookings',          'slzexploore-core' ),
				'singular_name'         => esc_html__( 'All Bookings',          'slzexploore-core' ),
				'menu_name'             => esc_html__( 'All Bookings',          'slzexploore-core' ),
				'add_new'               => esc_html__( 'Add New',               'slzexploore-core' ),
				'add_new_item'          => esc_html__( 'Add New Booking',       'slzexploore-core' ),
				'edit_item'             => esc_html__( 'Edit Booking',          'slzexploore-core' ),
			),
		)),
		array( 'register_taxonomy', 'slzexploore_book_status', array( 'slzexploore_book' ), array(
			'public'             => false,
			'show_ui'            => true,
			'hierarchical'       => true,
			'rewrite'            => array( 'slug' => 'booking-status' ),
			'query_var'          => true,
			'labels'             => array(
				'name'                  => esc_html__( 'Booking Status',             'slzexploore-core' ),
				'singular_name'         => esc_html__( 'Booking Status',             'slzexploore-core' ),
				'menu_name'             => esc_html__( 'Booking Status',             'slzexploore-core' ),
				'add_new'               => esc_html__( 'Add New Booking Status',     'slzexploore-core' ),
				'add_new_item'          => esc_html__( 'Add New Booking Status',     'slzexploore-core' ),
				'edit_item'             => esc_html__( 'Edit Booking Status',        'slzexploore-core' ),
				'parent_item'           => esc_html__( 'Parent Booking Status',      'slzexploore-core' ),
				'search_items'          => esc_html__( 'Search Booking Status',      'slzexploore-core' ),
			),
		)),
		
		/*******************************************************************/
		// Tour post type
		/*******************************************************************/
		array( 'register_post_type', 'slzexploore_tour', array(
			'public'                => true,
			'has_archive'           => true,
			'menu_position'         => 22,
			'rewrite'               => array( 'slug' => $custom_slug['tour'] ),
			'query_var'             => true,
			'menu_icon'             => 'dashicons-calendar',
			'supports'              => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments' ),
			'labels'                => array(
				'name'                  => esc_html__( 'Tours',           'slzexploore-core' ),
				'singular_name'         => $singular_labels['tour'],
				'all_items'             => esc_html__( 'All Tours',       'slzexploore-core' ),
				'menu_name'             => esc_html__( 'Tours',           'slzexploore-core' ),
				'add_new'               => esc_html__( 'Add New',         'slzexploore-core' ),
				'add_new_item'          => esc_html__( 'Add New Tour',    'slzexploore-core' ),
				'edit_item'             => esc_html__( 'Edit Tour',       'slzexploore-core' ),
			),
		)),
		array( 'register_taxonomy', 'slzexploore_tour_cat', array( 'slzexploore_tour' ), array(
			'hierarchical'       => true,
			'rewrite'            => array( 'slug' => $custom_slug['tour_cat'] ),
			'query_var'          => true,
			'labels'             => array(
				'name'                  => esc_html__( 'Tour Categories',         'slzexploore-core' ),
				'singular_name'         => $singular_labels['tour_cat'],
				'menu_name'             => esc_html__( 'Tour Categories',         'slzexploore-core' ),
				'add_new'               => esc_html__( 'Add New',                 'slzexploore-core' ),
				'add_new_item'          => esc_html__( 'Add New Category',        'slzexploore-core' ),
				'edit_item'             => esc_html__( 'Edit Category',           'slzexploore-core' ),
				'parent_item'           => esc_html__( 'Parent Category',         'slzexploore-core' ),
				'search_items'          => esc_html__( 'Search Categories',       'slzexploore-core' ),
			),
		)), 
		array( 'register_taxonomy', 'slzexploore_tour_location', array( 'slzexploore_tour' ), array(
			'hierarchical'       => true,
			'rewrite'            => array( 'slug' => $custom_slug['tour_location'] ),
			'query_var'          => true,
			'labels'             => array(
				'name'                  => esc_html__( 'Tour Locations',         'slzexploore-core' ),
				'singular_name'         => $singular_labels['tour_location'],
				'menu_name'             => esc_html__( 'Tour Locations',         'slzexploore-core' ),
				'add_new'               => esc_html__( 'Add New Tour Location',  'slzexploore-core' ),
				'add_new_item'          => esc_html__( 'Add New Tour Location',  'slzexploore-core' ),
				'edit_item'             => esc_html__( 'Edit Tour Location',     'slzexploore-core' ),
				'parent_item'           => esc_html__( 'Parent Tour Location',   'slzexploore-core' ),
				'search_items'          => esc_html__( 'Search Tour Locations',  'slzexploore-core' ),
			),
		)),
		array( 'register_taxonomy', 'slzexploore_tour_tag', array( 'slzexploore_tour' ), array(
			'hierarchical'       => false,
			'rewrite'            => array( 'slug' => 'tour-tag' ),
			'query_var'          => true,
			'labels'             => array(
				'name'                  => esc_html__( 'Tour Tags',          'slzexploore-core' ),
				'singular_name'         => esc_html__( 'Tour Tags',          'slzexploore-core' ),
				'menu_name'             => esc_html__( 'Tour Tags',          'slzexploore-core' ),
				'add_new'               => esc_html__( 'Add New',            'slzexploore-core' ),
				'add_new_item'          => esc_html__( 'Add New Amenity',    'slzexploore-core' ),
				'edit_item'             => esc_html__( 'Edit Amenity',       'slzexploore-core' ),
				'parent_item'           => esc_html__( 'Parent Tag',         'slzexploore-core' ),
				'search_items'          => esc_html__( 'Search Tags',        'slzexploore-core' ), 
			),
		)),
		array( 'register_taxonomy', 'slzexploore_tour_status', array( 'slzexploore_tour' ), array(
			'public'             => false,
			'show_ui'            => true,
			'hierarchical'       => true,
			'rewrite'            => array( 'slug' => 'tour-status' ),
			'show_in_menu'       => false,
			'query_var'          => true,
			'labels'             => array(
				'name'                  => esc_html__( 'Tour Status',                 'slzexploore-core' ),
				'singular_name'         => esc_html__( 'Tour Status',                 'slzexploore-core' ),
				'menu_name'             => esc_html__( 'Tour Status',                 'slzexploore-core' ),
				'add_new'               => esc_html__( 'Add New',                     'slzexploore-core' ),
				'add_new_item'          => esc_html__( 'Add New Tour Status',         'slzexploore-core' ),
				'edit_item'             => esc_html__( 'Edit Tour Status',            'slzexploore-core' ),
				'parent_item'           => esc_html__( 'Parent Tour Status',          'slzexploore-core' ),
				'search_items'          => esc_html__( 'Search Tour Status',          'slzexploore-core' ),
			),
		)),
		/*******************************************************************/
		// Tour Booking post type
		/*******************************************************************/
		array( 'register_post_type', 'slzexploore_tbook', array(
			'public'                => false,
			'show_ui'               => true,
			'show_in_menu'          => false,
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => false,
			'exclude_from_search'   => true,
			'has_archive'           => true,
			'rewrite'               => array( 'slug' => 'tour_booking' ),
			'query_var'             => true,
			'supports'              => array( '' ),
			'labels'                => array(
				'name'                  => esc_html__( 'Tour Bookings',        'slzexploore-core' ),
				'singular_name'         => esc_html__( 'Tour Bookings',        'slzexploore-core' ),
				'menu_name'             => esc_html__( 'Tour Bookings',        'slzexploore-core' ),
				'add_new'               => esc_html__( 'Add New',              'slzexploore-core' ),
				'add_new_item'          => esc_html__( 'Add New Tour Booking', 'slzexploore-core' ),
				'edit_item'             => esc_html__( 'Edit Tour Booking',    'slzexploore-core' ),
			),
		)),
		array( 'register_taxonomy', 'slzexploore_tbook_status', array( 'slzexploore_tbook' ), array(
			'public'             => false,
			'show_ui'            => true,
			'hierarchical'       => true,
			'rewrite'            => array( 'slug' => 'tour-booking-status' ),
			'query_var'          => true,
			'labels'             => array(
				'name'                  => esc_html__( 'Tour Booking Status',                 'slzexploore-core' ),
				'singular_name'         => esc_html__( 'Tour Booking Status',                 'slzexploore-core' ),
				'menu_name'             => esc_html__( 'Tour Booking Status',                 'slzexploore-core' ),
				'add_new'               => esc_html__( 'Add New',                             'slzexploore-core' ),
				'add_new_item'          => esc_html__( 'Add New Tour Booking Status',         'slzexploore-core' ),
				'edit_item'             => esc_html__( 'Edit Tour Booking Status',            'slzexploore-core' ),
				'parent_item'           => esc_html__( 'Parent Tour Booking Status',          'slzexploore-core' ),
				'search_items'          => esc_html__( 'Search Tour Booking Status',          'slzexploore-core' ),
			),
		)),
		/*******************************************************************/
		// Schedule post type
		/*******************************************************************/
		array( 'register_post_type', 'slzexploore_schedule', array(
			'public'                => false,
			'show_ui'               => true,
			'show_in_menu'          => false,
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => false,
			'exclude_from_search'   => true,
			'has_archive'           => true,
			'rewrite'               => array( 'slug' => 'schedule' ),
			'query_var'             => true,
			'supports'              => array( '' ),
			'labels'                => array(
				'name'                  => esc_html__( 'All Schedule',        'slzexploore-core' ),
				'singular_name'         => esc_html__( 'All Schedule',        'slzexploore-core' ),
				'menu_name'             => esc_html__( 'All Schedule',        'slzexploore-core' ),
				'add_new'               => esc_html__( 'Add New',             'slzexploore-core' ),
				'add_new_item'          => esc_html__( 'Add New Schedule',    'slzexploore-core' ),
				'edit_item'             => esc_html__( 'Edit Schedule',       'slzexploore-core' ),
			),
		)),
		/*******************************************************************/
		// Extra Items post type
		/*******************************************************************/
		array( 'register_post_type', 'slzexploore_exitem', array(
			'public'                => true,
			'has_archive'           => true,
			'rewrite'               => array( 'slug' => 'extra_item' ),
			'query_var'             => true,
			'supports'              => array( 'title', 'editor' ),
			'labels'                => array(
				'name'                  => esc_html__( 'Extra Items',        'slzexploore-core' ),
				'singular_name'         => esc_html__( 'Extra Items',        'slzexploore-core' ),
				'all_items'             => esc_html__( 'All Extra Items',    'slzexploore-core' ),
				'menu_name'             => esc_html__( 'Extra Items',        'slzexploore-core' ),
				'add_new'               => esc_html__( 'Add New',            'slzexploore-core' ),
				'add_new_item'          => esc_html__( 'Add New Item',       'slzexploore-core' ),
				'edit_item'             => esc_html__( 'Edit Item',          'slzexploore-core' ),
			),
		)),
		/*******************************************************************/
		// Team post type
		/*******************************************************************/
		array( 'register_post_type', 'slzexploore_team', array(
			'public'                => true,
			'has_archive'           => true,
			'rewrite'               => array( 'slug' => $custom_slug['team'] ),
			'query_var'             => true,
			'menu_icon'             => 'dashicons-groups',
			'supports'              => array( 'title', 'editor', 'thumbnail'),
			'labels'                => array(
				'name'                  => esc_html__( 'Teams',           'slzexploore-core' ),
				'singular_name'         => $singular_labels['team'],
				'all_items'             => esc_html__( 'All Teams',       'slzexploore-core' ),
				'menu_name'             => esc_html__( 'Teams',           'slzexploore-core' ),
				'add_new'               => esc_html__( 'Add New',         'slzexploore-core' ),
				'add_new_item'          => esc_html__( 'Add New Team',    'slzexploore-core' ),
				'edit_item'             => esc_html__( 'Edit Team',       'slzexploore-core' ),
			),
		)),
		array( 'register_taxonomy', 'slzexploore_team_cat', array( 'slzexploore_team' ), array(
			'hierarchical'       => true,
			'rewrite'            => array( 'slug' => $custom_slug['team_cat'] ),
			'query_var'          => true,
			'labels'             => array(
				'name'                  => esc_html__( 'Team Categories',         'slzexploore-core' ),
				'singular_name'         => $singular_labels['team_cat'],
				'menu_name'             => esc_html__( 'Team Categories',         'slzexploore-core' ),
				'add_new'               => esc_html__( 'Add New',                 'slzexploore-core' ),
				'add_new_item'          => esc_html__( 'Add New Category',        'slzexploore-core' ),
				'edit_item'             => esc_html__( 'Edit Category',           'slzexploore-core' ),
			),
		)),
		/*******************************************************************/
		// Partners post type
		/*******************************************************************/
		array( 'register_post_type', 'slzexploore_partner', array(
			'public'                => true,
			'has_archive'           => true,
			'rewrite'               => array( 'slug' => 'partners' ),
			'query_var'             => true,
			'menu_icon'             => 'dashicons-groups',
			'supports'              => array( 'title', 'thumbnail' ),
			'labels'                => array(
				'name'                  => esc_html__( 'Partners',           'slzexploore-core' ),
				'singular_name'         => esc_html__( 'Partners',           'slzexploore-core' ),
				'menu_name'             => esc_html__( 'Partners',           'slzexploore-core' ),
				'add_new'               => esc_html__( 'Add New',                      'slzexploore-core' ),
				'add_new_item'          => esc_html__( 'Add New Partner',    'slzexploore-core' ),
				'edit_item'             => esc_html__( 'Edit Partner',       'slzexploore-core' ),
			),
		)),
		array( 'register_taxonomy', 'slzexploore_partner_cat', array( 'slzexploore_partner' ), array(
			'hierarchical'       => true,
			'rewrite'            => array( 'slug' => 'partner-category' ),
			'query_var'          => true,
			'labels'             => array(
				'name'                  => esc_html__( 'Partner Categories',         'slzexploore-core' ),
				'singular_name'         => esc_html__( 'Partner Categories',         'slzexploore-core' ),
				'menu_name'             => esc_html__( 'Partner Categories',         'slzexploore-core' ),
				'add_new'               => esc_html__( 'Add New',                 'slzexploore-core' ),
				'add_new_item'          => esc_html__( 'Add New Category',        'slzexploore-core' ),
				'edit_item'             => esc_html__( 'Edit Category',           'slzexploore-core' ),
			),
		)),
		/*******************************************************************/
		// Gallery post type
		/*******************************************************************/
		array( 'register_post_type', 'slzexploore_gallery', array(
			'public'                => true,
			'has_archive'           => true,
			'rewrite'               => array( 'slug' => 'gallery' ),
			'query_var'             => true,
			'menu_icon'             => 'dashicons-format-gallery',
			'supports'              => array( 'title', 'thumbnail' ),
			'labels'                => array(
				'name'                  => esc_html__( 'Gallery',            'slzexploore-core' ),
				'singular_name'         => esc_html__( 'Gallery',            'slzexploore-core' ),
				'menu_name'             => esc_html__( 'Gallery',            'slzexploore-core' ),
				'add_new'               => esc_html__( 'Add New',            'slzexploore-core' ),
				'add_new_item'          => esc_html__( 'Add New Gallery',    'slzexploore-core' ),
				'edit_item'             => esc_html__( 'Edit Gallery',       'slzexploore-core' ),
			),
		)),
		array( 'register_taxonomy', 'slzexploore_gallery_cat', array( 'slzexploore_gallery' ), array(
			'hierarchical'       => true,
			'rewrite'            => array( 'slug' => 'gallery-category' ),
			'query_var'          => true,
			'labels'             => array(
				'name'                  => esc_html__( 'Gallery Categories',      'slzexploore-core' ),
				'singular_name'         => esc_html__( 'Gallery Categories',      'slzexploore-core' ),
				'menu_name'             => esc_html__( 'Gallery Categories',      'slzexploore-core' ),
				'add_new'               => esc_html__( 'Add New',                 'slzexploore-core' ),
				'add_new_item'          => esc_html__( 'Add New Category',        'slzexploore-core' ),
				'edit_item'             => esc_html__( 'Edit Category',           'slzexploore-core' ),
			),
		)),
		/*******************************************************************/
		// Testimonial post type
		/*******************************************************************/
		array( 'register_post_type', 'slzexploore_testi', array(
			'public'                => true,
			'has_archive'           => true,
			'rewrite'               => array( 'slug' => 'testimonials' ),
			'query_var'             => true,
			'menu_icon'             => 'dashicons-editor-quote',
			'supports'              => array( 'title', 'editor', 'thumbnail'),
			'labels'                => array(
				'name'                  => esc_html__( 'Testimonials',           'slzexploore-core' ),
				'singular_name'         => esc_html__( 'Testimonials',           'slzexploore-core' ),
				'menu_name'             => esc_html__( 'Testimonials',           'slzexploore-core' ),
				'add_new'               => esc_html__( 'Add New',                'slzexploore-core' ),
				'add_new_item'          => esc_html__( 'Add New Testimonial',    'slzexploore-core' ),
				'edit_item'             => esc_html__( 'Edit Testimonial',       'slzexploore-core' ),
			),
		)),
		/*******************************************************************/
		// FAQs post type
		/*******************************************************************/
		array( 'register_post_type', 'slzexploore_faq', array(
			'public'                => true,
			'has_archive'           => true,
			'rewrite'               => array( 'slug' => 'faqs' ),
			'query_var'             => true,
			'menu_icon'             => 'dashicons-format-chat',
			'supports'              => array( 'title', 'editor' ),
			'labels'                => array(
				'name'                  => esc_html__( 'FAQs',           'slzexploore-core' ),
				'singular_name'         => esc_html__( 'FAQs',           'slzexploore-core' ),
				'menu_name'             => esc_html__( 'FAQs',           'slzexploore-core' ),
				'add_new'               => esc_html__( 'Add New',        'slzexploore-core' ),
				'add_new_item'          => esc_html__( 'Add New FAQ',    'slzexploore-core' ),
				'edit_item'             => esc_html__( 'Edit FAQ',       'slzexploore-core' ),
			),
		)),
		array( 'register_taxonomy', 'slzexploore_faq_cat', array( 'slzexploore_faq' ), array(
			'hierarchical'       => true,
			'rewrite'            => array( 'slug' => 'faq-category' ),
			'query_var'          => true,
			'labels'             => array(
				'name'                  => esc_html__( 'FAQ Categories',         'slzexploore-core' ),
				'singular_name'         => esc_html__( 'FAQ Categories',         'slzexploore-core' ),
				'menu_name'             => esc_html__( 'FAQ Categories',         'slzexploore-core' ),
				'add_new'               => esc_html__( 'Add New',                'slzexploore-core' ),
				'add_new_item'          => esc_html__( 'Add New Category',       'slzexploore-core' ),
				'edit_item'             => esc_html__( 'Edit Category',          'slzexploore-core' ),
				'parent_item'           => esc_html__( 'Parent Category',        'slzexploore-core' ),
				'search_items'          => esc_html__( 'Search Categories',      'slzexploore-core' ),
			),
		)),
		/*******************************************************************/
		// Car Rent post type
		/*******************************************************************/
		array( 'register_post_type', 'slzexploore_car', array(
			'public'                => true,
			'has_archive'           => true,
			'menu_position'         => 23,
			'rewrite'               => array( 'slug' => $custom_slug['car'] ),
			'query_var'             => true,
			'supports'              => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments' ),
			'labels'                => array(
				'name'                  => esc_html__( 'Car Rent',           'slzexploore-core' ),
				'singular_name'         => $singular_labels['car'],
				'all_items'             => esc_html__( 'All Car Rents',      'slzexploore-core' ),
				'menu_name'             => esc_html__( 'Car Rents',          'slzexploore-core' ),
				'add_new'               => esc_html__( 'Add New',            'slzexploore-core' ),
				'add_new_item'          => esc_html__( 'Add New Car',        'slzexploore-core' ),
				'edit_item'             => esc_html__( 'Edit Car',           'slzexploore-core' ),
			),
		)),
		array( 'register_taxonomy', 'slzexploore_car_cat', array( 'slzexploore_car' ), array(
			'hierarchical'       => true,
			'rewrite'            => array( 'slug' => $custom_slug['car_cat'] ),
			'query_var'          => true,
			'labels'             => array(
				'name'                  => esc_html__( 'Car Categories',            'slzexploore-core' ),
				'singular_name'         => $singular_labels['car_cat'],
				'menu_name'             => esc_html__( 'Car Categories',            'slzexploore-core' ),
				'add_new'               => esc_html__( 'Add New Car Category',      'slzexploore-core' ),
				'add_new_item'          => esc_html__( 'Add New Car Category',      'slzexploore-core' ),
				'edit_item'             => esc_html__( 'Edit Car Category',         'slzexploore-core' ),
				'parent_item'           => esc_html__( 'Parent Car Category',       'slzexploore-core' ),
				'search_items'          => esc_html__( 'Search Car Categories',     'slzexploore-core' ),
			),
		)),
		array( 'register_taxonomy', 'slzexploore_car_location', array( 'slzexploore_car' ), array(
			'hierarchical'       => true,
			'rewrite'            => array( 'slug' => $custom_slug['car_location'] ),
			'query_var'          => true,
			'labels'             => array(
				'name'                  => esc_html__( 'Car Locations',         'slzexploore-core' ),
				'singular_name'         => $singular_labels['car_location'],
				'menu_name'             => esc_html__( 'Car Locations',         'slzexploore-core' ),
				'add_new'               => esc_html__( 'Add New',               'slzexploore-core' ),
				'add_new_item'          => esc_html__( 'Add New Car Location',  'slzexploore-core' ),
				'edit_item'             => esc_html__( 'Edit Car Location',     'slzexploore-core' ),
				'parent_item'           => esc_html__( 'Parent Car Location',   'slzexploore-core' ),
				'search_items'          => esc_html__( 'Search Car Locations',  'slzexploore-core' ),
			),
		)),
		array( 'register_taxonomy', 'slzexploore_car_status', array( 'slzexploore_car' ), array(
			'public'             => false,
			'show_ui'            => true,
			'hierarchical'       => true,
			'rewrite'            => array( 'slug' => 'car-status' ),
			'show_in_menu'       => false,
			'query_var'          => true,
			'labels'             => array(
				'name'                  => esc_html__( 'Car Status',                 'slzexploore-core' ),
				'singular_name'         => esc_html__( 'Car Status',                 'slzexploore-core' ),
				'menu_name'             => esc_html__( 'Car Status',                 'slzexploore-core' ),
				'add_new'               => esc_html__( 'Add New',                    'slzexploore-core' ),
				'add_new_item'          => esc_html__( 'Add New Car Status',         'slzexploore-core' ),
				'edit_item'             => esc_html__( 'Edit Car Status',            'slzexploore-core' ),
				'parent_item'           => esc_html__( 'Parent Car Status',          'slzexploore-core' ),
				'search_items'          => esc_html__( 'Search Car Status',          'slzexploore-core' ),
			),
		)),
		/*******************************************************************/
		// Car Booking post type
		/*******************************************************************/
		array( 'register_post_type', 'slzexploore_cbook', array(
			'public'                => false,
			'show_ui'               => true,
			'show_in_menu'          => false,
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => false,
			'exclude_from_search'   => true,
			'has_archive'           => true,
			'rewrite'               => array( 'slug' => 'car_booking' ),
			'query_var'             => true,
			'supports'              => array( '' ),
			'labels'                => array(
				'name'                  => esc_html__( 'Car Bookings',         'slzexploore-core' ),
				'singular_name'         => esc_html__( 'Car Bookings',         'slzexploore-core' ),
				'menu_name'             => esc_html__( 'Car Bookings',         'slzexploore-core' ),
				'add_new'               => esc_html__( 'Add New',              'slzexploore-core' ),
				'add_new_item'          => esc_html__( 'Add New Car Booking',  'slzexploore-core' ),
				'edit_item'             => esc_html__( 'Edit Car Booking',     'slzexploore-core' ),
			),
		)),
		array( 'register_taxonomy', 'slzexploore_cbook_status', array( 'slzexploore_cbook' ), array(
			'public'             => false,
			'show_ui'            => true,
			'hierarchical'       => true,
			'rewrite'            => array( 'slug' => 'car-booking-status' ),
			'query_var'          => true,
			'labels'             => array(
				'name'                  => esc_html__( 'Car Booking Status',                 'slzexploore-core' ),
				'singular_name'         => esc_html__( 'Car Booking Status',                 'slzexploore-core' ),
				'menu_name'             => esc_html__( 'Car Booking Status',                 'slzexploore-core' ),
				'add_new'               => esc_html__( 'Add New',                            'slzexploore-core' ),
				'add_new_item'          => esc_html__( 'Add New Car Booking Status',         'slzexploore-core' ),
				'edit_item'             => esc_html__( 'Edit Car Booking Status',            'slzexploore-core' ),
				'parent_item'           => esc_html__( 'Parent Car Booking Status',          'slzexploore-core' ),
				'search_items'          => esc_html__( 'Search Car Booking Status',          'slzexploore-core' ),
			),
		)),
		/*******************************************************************/
		// Cruises post type
		/*******************************************************************/
		array( 'register_post_type', 'slzexploore_cruise', array(
			'public'                => true,
			'has_archive'           => true,
			'menu_position'         => 24,
			'rewrite'               => array( 'slug' => $custom_slug['cruise'] ),
			'query_var'             => true,
			'menu_icon'             => 'dashicons-admin-post',
			'supports'              => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments' ),
			'labels'                => array(
				'name'                  => esc_html__( 'Cruises',           'slzexploore-core' ),
				'singular_name'         => $singular_labels['cruise'],
				'all_items'             => esc_html__( 'All Cruises',       'slzexploore-core' ),
				'menu_name'             => esc_html__( 'Cruises',           'slzexploore-core' ),
				'add_new'               => esc_html__( 'Add New',           'slzexploore-core' ),
				'add_new_item'          => esc_html__( 'Add New Cruise',    'slzexploore-core' ),
				'edit_item'             => esc_html__( 'Edit Cruise',       'slzexploore-core' ),
			),
		)),
		array( 'register_taxonomy', 'slzexploore_cruise_cat', array( 'slzexploore_cruise' ), array(
			'hierarchical'       => true,
			'rewrite'            => array( 'slug' => $custom_slug['cruise_cat'] ),
			'query_var'          => true,
			'labels'             => array(
				'name'                  => esc_html__( 'Cruise Categories',           'slzexploore-core' ),
				'singular_name'         => $singular_labels['cruise_cat'],
				'menu_name'             => esc_html__( 'Cruise Categories',           'slzexploore-core' ),
				'add_new'               => esc_html__( 'Add New',                     'slzexploore-core' ),
				'add_new_item'          => esc_html__( 'Add New Category',            'slzexploore-core' ),
				'edit_item'             => esc_html__( 'Edit Category',               'slzexploore-core' ),
				'parent_item'           => esc_html__( 'Parent Category',             'slzexploore-core' ),
				'search_items'          => esc_html__( 'Search Categories',           'slzexploore-core' ),
			),
		)), 
		array( 'register_taxonomy', 'slzexploore_cruise_location', array( 'slzexploore_cruise' ), array(
			'hierarchical'       => true,
			'rewrite'            => array( 'slug' => $custom_slug['cruise_location'] ),
			'query_var'          => true,
			'labels'             => array(
				'name'                  => esc_html__( 'Cruise Locations',         'slzexploore-core' ),
				'singular_name'         => $singular_labels['cruise_location'],
				'menu_name'             => esc_html__( 'Cruise Locations',         'slzexploore-core' ),
				'add_new'               => esc_html__( 'Add New',                  'slzexploore-core' ),
				'add_new_item'          => esc_html__( 'Add New Cruise Location',  'slzexploore-core' ),
				'edit_item'             => esc_html__( 'Edit Cruise Location',     'slzexploore-core' ),
				'parent_item'           => esc_html__( 'Parent Cruise Location',   'slzexploore-core' ),
				'search_items'          => esc_html__( 'Search Cruise Locations',  'slzexploore-core' ),
			),
		)),
		array( 'register_taxonomy', 'slzexploore_cruise_facility', array( 'slzexploore_cruise' ), array(
			'hierarchical'       => true,
			'rewrite'            => array( 'slug' => $custom_slug['cruise_facility'] ),
			'query_var'          => true,
			'labels'             => array(
				'name'                  => esc_html__( 'Cruise Facilities',           'slzexploore-core' ),
				'singular_name'         => $singular_labels['cruise_facility'],
				'menu_name'             => esc_html__( 'Cruise Facilities',           'slzexploore-core' ),
				'add_new'               => esc_html__( 'Add New',                     'slzexploore-core' ),
				'add_new_item'          => esc_html__( 'Add New Facility',            'slzexploore-core' ),
				'edit_item'             => esc_html__( 'Edit Facility',               'slzexploore-core' ),
				'parent_item'           => esc_html__( 'Parent Facility',             'slzexploore-core' ),
				'search_items'          => esc_html__( 'Search Facilities',           'slzexploore-core' ),
			),
		)),
		array( 'register_taxonomy', 'slzexploore_cruise_status', array( 'slzexploore_cruise' ), array(
			'public'             => false,
			'show_ui'            => true,
			'hierarchical'       => true,
			'rewrite'            => array( 'slug' => 'cruise-status' ),
			'show_in_menu'       => false,
			'query_var'          => true,
			'labels'             => array(
				'name'                  => esc_html__( 'Cruise Status',                 'slzexploore-core' ),
				'singular_name'         => esc_html__( 'Cruise Status',                 'slzexploore-core' ),
				'menu_name'             => esc_html__( 'Cruise Status',                 'slzexploore-core' ),
				'add_new'               => esc_html__( 'Add New',                       'slzexploore-core' ),
				'add_new_item'          => esc_html__( 'Add New Cruise Status',         'slzexploore-core' ),
				'edit_item'             => esc_html__( 'Edit Cruise Status',            'slzexploore-core' ),
				'parent_item'           => esc_html__( 'Parent Cruise Status',          'slzexploore-core' ),
				'search_items'          => esc_html__( 'Search Cruise Status',          'slzexploore-core' ),
			),
		)),
		/*******************************************************************/
		// Cabin Types post type
		/*******************************************************************/
		array( 'register_post_type', 'slzexploore_cabin', array(
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => false,
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => false,
			'exclude_from_search'   => true,
			'has_archive'           => true,
			'rewrite'               => array( 'slug' => 'cabin_types' ),
			'query_var'             => true,
			'supports'              => array( 'title', 'editor', 'excerpt', 'thumbnail' ),
			'labels'                => array(
				'name'                  => esc_html__( 'All Cabin Types',            'slzexploore-core' ),
				'singular_name'         => esc_html__( 'All Cabin Types',            'slzexploore-core' ),
				'menu_name'             => esc_html__( 'All Cabin Types',            'slzexploore-core' ),
				'add_new'               => esc_html__( 'Add New',                    'slzexploore-core' ),
				'add_new_item'          => esc_html__( 'Add New Cabin Type',         'slzexploore-core' ),
				'edit_item'             => esc_html__( 'Edit Cabin Type',            'slzexploore-core' ),
			),
		)),
		array( 'register_taxonomy', 'slzexploore_cabin_cat', array( 'slzexploore_cabin' ), array(
			'hierarchical'       => true,
			'rewrite'            => array( 'slug' => 'cabin-category' ),
			'query_var'          => true,
			'labels'             => array(
				'name'                  => esc_html__( 'Cabin Type Categories',        'slzexploore-core' ),
				'singular_name'         => esc_html__( 'Cabin Type Categories',        'slzexploore-core' ),
				'menu_name'             => esc_html__( 'Cabin Type Categories',        'slzexploore-core' ),
				'add_new'               => esc_html__( 'Add New',                      'slzexploore-core' ),
				'add_new_item'          => esc_html__( 'Add New Cabin Type Category',  'slzexploore-core' ),
				'edit_item'             => esc_html__( 'Edit Cabin Type Category',     'slzexploore-core' ),
				'parent_item'           => esc_html__( 'Parent Cabin Type Category',   'slzexploore-core' ),
				'search_items'          => esc_html__( 'Search Cabin Type Categories', 'slzexploore-core' ),
			),
		)),
		/*******************************************************************/
		// Cruise Booking post type
		/*******************************************************************/
		array( 'register_post_type', 'slzexploore_crbook', array(
			'public'                => false,
			'show_ui'               => true,
			'show_in_menu'          => false,
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => false,
			'exclude_from_search'   => true,
			'has_archive'           => true,
			'rewrite'               => array( 'slug' => 'cruise_booking' ),
			'query_var'             => true,
			'supports'              => array( '' ),
			'labels'                => array(
				'name'                  => esc_html__( 'Cruise Bookings',         'slzexploore-core' ),
				'singular_name'         => esc_html__( 'Cruise Bookings',         'slzexploore-core' ),
				'menu_name'             => esc_html__( 'Cruise Bookings',         'slzexploore-core' ),
				'add_new'               => esc_html__( 'Add New',                 'slzexploore-core' ),
				'add_new_item'          => esc_html__( 'Add New Cruise Booking',  'slzexploore-core' ),
				'edit_item'             => esc_html__( 'Edit Cruise Booking',     'slzexploore-core' ),
			),
		)),
		array( 'register_taxonomy', 'slzexploore_crbook_status', array( 'slzexploore_crbook' ), array(
			'public'             => false,
			'show_ui'            => true,
			'hierarchical'       => true,
			'rewrite'            => array( 'slug' => 'cruise-booking-status' ),
			'query_var'          => true,
			'labels'             => array(
				'name'                  => esc_html__( 'Cruise Booking Status',                 'slzexploore-core' ),
				'singular_name'         => esc_html__( 'Cruise Booking Status',                 'slzexploore-core' ),
				'menu_name'             => esc_html__( 'Cruise Booking Status',                 'slzexploore-core' ),
				'add_new'               => esc_html__( 'Add New',                               'slzexploore-core' ),
				'add_new_item'          => esc_html__( 'Add New Cruise Booking Status',         'slzexploore-core' ),
				'edit_item'             => esc_html__( 'Edit Cruise Booking Status',            'slzexploore-core' ),
				'parent_item'           => esc_html__( 'Parent Cruise Booking Status',          'slzexploore-core' ),
				'search_items'          => esc_html__( 'Search Cruise Booking Status',          'slzexploore-core' ),
			),
		)),
		

		array( 'add_action', 'wp_ajax_slzexploore_core', array( SLZEXPLOORE_CORE_CLASS, '[Application, ajax]' ) ),
		array( 'add_action', 'wp_ajax_nopriv_slzexploore_core', array( SLZEXPLOORE_CORE_CLASS, '[Application, ajax]' ) ),
		// add sub menu page
		array( 'add_action', 'admin_menu', array( SLZEXPLOORE_CORE_CLASS, '[setting.Setting_Init, add_submenu_pages]' ) ),
		// remove custom metabox
		array( 'add_action', 'admin_menu', array( SLZEXPLOORE_CORE_CLASS, '[setting.Setting_Init, remove_custom_metabox]' ) ),
	),

	'admin_init' => array(
		// add action
		array( 'add_action', 'save_post', array( SLZEXPLOORE_CORE_CLASS, '[Application, save]' ) ),
		array( 'add_action', 'before_delete_post', array( SLZEXPLOORE_CORE_CLASS, '[posttype.Vacancy_Controller, before_delete]' ) ),
		array( 'add_action', 'delete_post', array( SLZEXPLOORE_CORE_CLASS, '[posttype.Vacancy_Controller, delete]' ) ),
		array( 'add_action', 'admin_enqueue_scripts', array( SLZEXPLOORE_CORE_CLASS, '[setting.Setting_Init, enqueue]' ) ),

		array( 'add_action', 'slzexploore_core_custom_colums', array( SLZEXPLOORE_CORE_CLASS, '[setting.Setting_Init, manage_custom_columns]' ) ),
		array( 'add_action', 'slzexploore_core_add_feature_video', array( SLZEXPLOORE_CORE_CLASS, '[setting.Setting_Init, add_metabox_feature_video]' ) ),
		array( 'add_action', 'current_screen', array( SLZEXPLOORE_CORE_CLASS, '[setting.Setting_Init, add_permalink_settings]' ) ),

		array( 'do_action', 'slzexploore_core_add_feature_video'),
		array( 'do_action', 'slzexploore_core_custom_colums'),

		// save feature video
		array( 'add_action', 'slzexploore_core_save_feature_video', array( SLZEXPLOORE_CORE_CLASS, '[setting.Setting_Init, save_feature_video]' ) ),
		// Accommodation - metaboxs
		array( 'add_meta_box', 'slzexploore_core_mbox_accommodation', 'Accommodation Options', array( SLZEXPLOORE_CORE_CLASS, '[posttype.Accommodation_Controller, mbox_accommodation]' ), 'slzexploore_hotel', 'normal' ),
		array( 'add_meta_box', 'slzexploore_core_mbox_room', 'Room Options', array( SLZEXPLOORE_CORE_CLASS, '[posttype.Room_Controller, mbox_room_options]' ), 'slzexploore_room', 'normal' ),
		array( 'add_meta_box', 'slzexploore_core_mbox_vacancy', 'Vacancy Options', array( SLZEXPLOORE_CORE_CLASS, '[posttype.Vacancy_Controller, mbox_vacancy_options]' ), 'slzexploore_vacancy', 'normal' ),	
		//Testimonial - metaboxs
		array( 'add_meta_box', 'slz_mbox_testimonial_option', 'Testimonial Options', array( SLZEXPLOORE_CORE_CLASS, '[posttype.Testimonial_Controller, meta_box_option]' ), 'slzexploore_testi', 'normal' ),
		// Partner - metaboxs
		array( 'add_meta_box', 'slzexploore_core_mbox_partner_url', 'Partner Options', array( SLZEXPLOORE_CORE_CLASS, '[posttype.Partner_Controller, meta_box_url]' ), 'slzexploore_partner', 'normal' ),
		// Gallery - metaboxs
		array( 'add_meta_box', 'slzexploore_core_mbox_gallery', 'Gallery Options', array( SLZEXPLOORE_CORE_CLASS, '[posttype.Partner_Controller, meta_box_gallery]' ), 'slzexploore_gallery', 'normal' ),
		// Team - metaboxs
		array( 'add_meta_box', 'slzexploore_core_mbox_team_icon', 'Team', array( SLZEXPLOORE_CORE_CLASS, '[posttype.Team_Controller, meta_box_option]' ), 'slzexploore_team', 'normal' ),

		// Tour - metaboxs
		array( 'add_meta_box', 'slzexploore_core_mbox_tour', 'Tour Information', array( SLZEXPLOORE_CORE_CLASS, '[posttype.Tour_Controller, mbox_tour]' ), 'slzexploore_tour', 'normal' ),
		// Accommodation Booking - metaboxs
		array( 'add_meta_box', 'slzexploore_core_mbox_booking', 'Booking Options', array( SLZEXPLOORE_CORE_CLASS, '[posttype.Booking_Controller, mbox_booking]' ), 'slzexploore_book', 'normal' ),
		// Tour Booking - metaboxs
		array( 'add_meta_box', 'slzexploore_core_mbox_tour_booking', 'Tour Booking Options', array( SLZEXPLOORE_CORE_CLASS, '[posttype.Tour_Booking_Controller, mbox_tour_booking]' ), 'slzexploore_tbook', 'normal' ),

		// Car - metaboxs
		array( 'add_meta_box', 'slzexploore_core_mbox_car', 'Car Information', array( SLZEXPLOORE_CORE_CLASS, '[posttype.Car_Controller, mbox_car]' ), 'slzexploore_car', 'normal' ),
		// Car Booking - metaboxs
		array( 'add_meta_box', 'slzexploore_core_mbox_car_booking', 'Car Booking Options', array( SLZEXPLOORE_CORE_CLASS, '[posttype.Car_Controller, mbox_car_booking]' ), 'slzexploore_cbook', 'normal' ),

		// Cruise - metaboxs
		array( 'add_meta_box', 'slzexploore_core_mbox_cruise', 'Cruise Information', array( SLZEXPLOORE_CORE_CLASS, '[posttype.Cruise_Controller, mbox_cruise]' ), 'slzexploore_cruise', 'normal' ),
		array( 'add_meta_box', 'slzexploore_core_mbox_cruise_booking', 'Cruise Booking Options', array( SLZEXPLOORE_CORE_CLASS, '[posttype.Cruise_Controller, mbox_cruise_booking]' ), 'slzexploore_crbook', 'normal' ),
		// Cabin Type - metaboxs
		array( 'add_meta_box', 'slzexploore_core_mbox_cabin_type', 'Cabin Types Information', array( SLZEXPLOORE_CORE_CLASS, '[posttype.Cruise_Controller, mbox_cabin_type]' ), 'slzexploore_cabin', 'normal' ),
		
		// FAQs - metaboxs
		array( 'add_meta_box', 'slz_metabox_faq_options', 'FAQs Options', array( SLZEXPLOORE_CORE_CLASS, '[posttype.Faq_Controller, metabox_faq_options]' ), 'slzexploore_faq', 'normal' ),

		// Extra Items - metaboxs
		array( 'add_meta_box', 'slz_metabox_extra_item_options', 'Extra Item Options', array( SLZEXPLOORE_CORE_CLASS, '[posttype.Extra_Item_Controller, metabox_extra_item_options]' ), 'slzexploore_exitem', 'normal' ),
		// Schedule - metaboxs
		array( 'add_meta_box', 'slzexploore_core_mbox_schedule', 'Schedule Options', array( SLZEXPLOORE_CORE_CLASS, '[posttype.Schedule_Controller, mbox_schedule_options]' ), 'slzexploore_schedule', 'normal' )

	),
	'save_post' => array(
		'slzexploore_tour'   => array( 'posttype.Tour_Controller', 'save' ),
		'slzexploore_partner'=> array( 'posttype.Partner_Controller', 'save' ),
		'slzexploore_testi'  => array( 'posttype.Testimonial_Controller', 'save' ),
		'post'               => array( 'posttype.Post_Controller', 'save' ), 
		'slzexploore_hotel'  => array( 'posttype.Accommodation_Controller', 'save' ),
		'slzexploore_room'   => array( 'posttype.Room_Controller', 'save' ),
		'slzexploore_vacancy'=> array( 'posttype.Vacancy_Controller', 'save' ),
		'slzexploore_team'   => array( 'posttype.Team_Controller','save' ),
		'slzexploore_book'   => array( 'posttype.Booking_Controller','save' ),
		'slzexploore_tbook'  => array( 'posttype.Tour_Booking_Controller','save' ),
		'slzexploore_car'    => array( 'posttype.Car_Controller', 'save' ),
		'slzexploore_cbook'  => array( 'posttype.Car_Controller', 'save_booking' ),
		'slzexploore_cruise' => array( 'posttype.Cruise_Controller', 'save' ),
		'slzexploore_cabin'  => array( 'posttype.Cruise_Controller', 'save_cabin_type' ),
		'slzexploore_crbook' => array( 'posttype.Cruise_Controller', 'save_booking' ),
		'slzexploore_faq'    => array( 'posttype.Faq_Controller', 'save' ),
		'slzexploore_exitem' => array( 'posttype.Extra_Item_Controller', 'save' ),
		'slzexploore_gallery'=> array( 'posttype.Partner_Controller', 'save_gallery' ),
		'slzexploore_schedule'=> array( 'posttype.Schedule_Controller', 'save' )
	),
	'shortcode' => array( 
		'slzexploore_core_banner_sc'           => 'banner',
		'slzexploore_core_block_title_sc'      => 'block_title',
		'slzexploore_core_button_sc'           => 'button',
		'slzexploore_core_contact_sc'          => 'contact',
		'slzexploore_core_discount_box_sc'     => 'discount_box',
		'slzexploore_core_faqs_sc'             => 'faqs',
		'slzexploore_core_faq_request_sc'      => 'faq_request',
		'slzexploore_core_gallery_sc'          => 'gallery',
		'slzexploore_core_icon_box_sc'         => 'icon_box',
		'slzexploore_core_item_list_sc'        => 'item_list',
		'slzexploore_core_partner_sc'          => 'partner',
		'slzexploore_core_blog_sc'             => 'blog',
		'slzexploore_core_recent_news_sc'      => 'recent_news',
		'slzexploore_core_post_carousel_sc'    => 'post_carousel',
		'slzexploore_core_accommodation_grid_sc'    => 'accommodation_grid',
		'slzexploore_core_accommodation_search_sc'  => 'accommodation_search',
		'slzexploore_core_room_type_sc'        => 'room_type',
		'slzexploore_core_search_sc'           => 'search',
		'slzexploore_core_team_carousel_sc'    => 'team_carousel',
		'slzexploore_core_team_list_sc'        => 'team_list',
		'slzexploore_core_team_single_sc'      => 'team_single',
		'slzexploore_core_tour_carousel_sc'    => 'tour_carousel',
		'slzexploore_core_tour_grid_sc'        => 'tour_grid',
		'slzexploore_core_tour_schedule_sc'    => 'tour_schedule',
		'slzexploore_core_tour_search_sc'      => 'tour_search',
		'slzexploore_core_tour_category_sc'    => 'tour_category',
		'slzexploore_core_car_grid_sc'         => 'car_grid',
		'slzexploore_core_car_search_sc'       => 'car_search',
		'slzexploore_core_cruise_search_sc'    => 'cruise_search',
		'slzexploore_core_cruise_grid_sc'      => 'cruise_grid',
		'slzexploore_core_tabs_sc'             => 'tabs',
		'slzexploore_core_tab_sc'              => 'tab',
		'slzexploore_core_testimonial_sc'      => 'testimonial',
		'slzexploore_core_toggle_box_sc'       => 'toggle_box',
		'slzexploore_core_map_location_sc'     => 'map_location',
	),
	'post_type' => array(
		'custom_column' => array(
			'slzexploore_partner', 'slzexploore_testi', 'slzexploore_tour', 'slzexploore_tbook','slzexploore_team',
			'slzexploore_hotel', 'slzexploore_room', 'slzexploore_book', 'slzexploore_vacancy',
			'slzexploore_car', 'slzexploore_cbook', 'slzexploore_cruise', 'slzexploore_crbook',
			'slzexploore_cabin', 'slzexploore_exitem', 'slzexploore_faq', 'slzexploore_gallery',
			'slzexploore_schedule'
		),
		'feature_video' => array( 'post' ),
	),
	'custom_taxonomies' => array(
	),
);