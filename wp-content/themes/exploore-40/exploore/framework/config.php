<?php
return array(
	'load' => array(
		// Register widgets
		array( 'add_action', 'widgets_init', array( SLZEXPLOORE_THEME_CLASS, '[widget.Widget_Init, load]') ),
		// action inline css
		array( 'add_action', SLZEXPLOORE_THEME_PREFIX . '_add_inline_style', array( SLZEXPLOORE_THEME_CLASS, '[theme.Theme_Init, add_inline_style]') ),
		// get page options
		array( 'add_action', SLZEXPLOORE_THEME_PREFIX . '_page_options',      array( SLZEXPLOORE_THEME_CLASS, '[theme.Theme_Init, get_page_options]') ),
		// show index content
		array( 'add_action', SLZEXPLOORE_THEME_PREFIX . '_show_index',        array( SLZEXPLOORE_THEME_CLASS, '[front.Top_Controller, show_post_index]') ),
		// Frontend actions

		array( 'add_action', SLZEXPLOORE_THEME_PREFIX . '_show_header',       array( SLZEXPLOORE_THEME_CLASS, '[front.Top_Controller, header]') ),
		array( 'add_action', SLZEXPLOORE_THEME_PREFIX . '_show_footerbottom', array( SLZEXPLOORE_THEME_CLASS, '[front.Top_Controller, footerbottom]') ),
		array( 'add_action', SLZEXPLOORE_THEME_PREFIX . '_show_breadcrumb',   array( SLZEXPLOORE_THEME_CLASS, '[front.Top_Controller, breadcrumb]') ),
		array( 'add_action', SLZEXPLOORE_THEME_PREFIX . '_entry_thumbnail',   array( SLZEXPLOORE_THEME_CLASS, '[front.Top_Controller, show_post_entry_thumbnail]') ),
		array( 'add_action', SLZEXPLOORE_THEME_PREFIX . '_entry_video',       array( SLZEXPLOORE_THEME_CLASS, '[front.Top_Controller, show_post_entry_video]') ),
		array( 'add_action', SLZEXPLOORE_THEME_PREFIX . '_entry_meta',        array( SLZEXPLOORE_THEME_CLASS, '[front.Top_Controller, show_post_entry_meta]') ),
		array( 'add_action', SLZEXPLOORE_THEME_PREFIX . '_tags_meta',         array( SLZEXPLOORE_THEME_CLASS, '[front.Top_Controller, show_post_tags_meta]') ),
		array( 'add_action', SLZEXPLOORE_THEME_PREFIX . '_categories_meta',         array( SLZEXPLOORE_THEME_CLASS, '[front.Top_Controller, show_post_categories_meta]') ),
		array( 'add_action', SLZEXPLOORE_THEME_PREFIX . '_show_page_title',   array( SLZEXPLOORE_THEME_CLASS, '[front.Top_Controller, show_page_title]') ),
		array( 'add_action', SLZEXPLOORE_THEME_PREFIX . '_show_slider',       array( SLZEXPLOORE_THEME_CLASS, '[front.Top_Controller, show_slider]') ),
		array( 'add_action', SLZEXPLOORE_THEME_PREFIX . '_show_searchform',   array( SLZEXPLOORE_THEME_CLASS, '[front.Top_Controller, show_searchform]') ),
		array( 'add_action', SLZEXPLOORE_THEME_PREFIX . '_post_author',       array( SLZEXPLOORE_THEME_CLASS, '[front.Top_Controller, show_post_author]') ),
		array( 'add_action', SLZEXPLOORE_THEME_PREFIX . '_show_posttype_header_content',       array( SLZEXPLOORE_THEME_CLASS, '[front.Top_Controller, show_posttype_header_content]') ),

		// share post
		array( 'add_action', SLZEXPLOORE_THEME_PREFIX . '_share_link',        array( SLZEXPLOORE_THEME_CLASS, '[front.Top_Controller, get_share_link]') ),
		// share hotel, tour, car and cruise
		array( 'add_action', SLZEXPLOORE_THEME_PREFIX . '_share_custom_post', array( SLZEXPLOORE_THEME_CLASS, '[front.Top_Controller, share_custom_post]') ),

		// login
		array( 'add_action', SLZEXPLOORE_THEME_PREFIX . '_login_link',        array( SLZEXPLOORE_THEME_CLASS, '[front.Top_Controller, show_login_link]') ),

		array( 'add_action', 'comment_post',                 array( SLZEXPLOORE_THEME_CLASS, '[front.Top_Controller, add_comment_rating]') ),
		array( 'add_action', 'deleted_comment',              array( SLZEXPLOORE_THEME_CLASS, '[front.Top_Controller, update_review_rating]' ) ),
		array( 'add_action', 'trashed_comment',              array( SLZEXPLOORE_THEME_CLASS, '[front.Top_Controller, update_review_rating]' ) ),
		array( 'add_action', 'untrashed_comment',            array( SLZEXPLOORE_THEME_CLASS, '[front.Top_Controller, update_review_rating]' ) ),
		// User Controller
		array( 'add_action', 'after_setup_theme',                             array( SLZEXPLOORE_THEME_CLASS, '[profile.User_Controller, create_default_pages]') ),
		array( 'add_action', 'wp_loaded',                                     array( SLZEXPLOORE_THEME_CLASS, '[profile.User_Controller, process_login]'), 20 ),
		array( 'add_action', 'wp_loaded',                                     array( SLZEXPLOORE_THEME_CLASS, '[profile.User_Controller, process_registration]'), 20 ),
		array( 'add_action', SLZEXPLOORE_THEME_PREFIX . '_print_notices',     array( SLZEXPLOORE_THEME_CLASS, '[profile.User_Controller, print_notices]') ),

	),
	'init' => array(
		// Regist Menu
		array( 'register_nav_menu', 'main-nav',   esc_html__( 'Main Navigation', 'exploore' ) ),
		array( 'register_nav_menu', 'page-404-nav', esc_html__( '404 Pages', 'exploore' ) ),

		// Ajax
		array( 'add_action', 'wp_ajax_slzexploore',        array( SLZEXPLOORE_THEME_CLASS, '[Application, ajax]' ) ),
		array( 'add_action', 'wp_ajax_nopriv_slzexploore', array( SLZEXPLOORE_THEME_CLASS, '[Application, ajax]' ) ),

		// Welcome page
		array( 'add_action', 'admin_menu', array( SLZEXPLOORE_THEME_CLASS, '[theme.Theme_Controller, add_welcome]' ) ),
		array( 'add_action', 'admin_init', array( SLZEXPLOORE_THEME_CLASS, '[theme.Theme_Controller, call_tgm_plugin_action]' ) ),

		// Add sidebar area
		array( 'add_action', 'admin_print_scripts',                    array( SLZEXPLOORE_THEME_CLASS, '[theme.Widget_Init, add_widget_field]' ) ),
		array( 'add_action', 'load-widgets.php',                       array( SLZEXPLOORE_THEME_CLASS, '[widget.Widget_Init, add_sidebar_area]' ) ),
		array( 'add_action', 'wp_ajax_slzexploore_del_custom_sidebar', array( SLZEXPLOORE_THEME_CLASS, '[widget.Widget_Init, delete_custom_sidebar]' ) ),
	),
	'front_init' => array(),

	'admin_init' => array(
		// add action
		array( 'add_action', 'save_post',             array( SLZEXPLOORE_THEME_CLASS, '[Application, save]' ) ),
		array( 'add_action', 'admin_enqueue_scripts', array( SLZEXPLOORE_THEME_CLASS, '[theme.Theme_Init, admin_enqueue]' ) ),

		// init page options
		array( 'add_action', SLZEXPLOORE_THEME_PREFIX . '_init_page_setting',  array( SLZEXPLOORE_THEME_CLASS, '[theme.Theme_Init, init_page_setting]' ) ),
		array( 'do_action',  SLZEXPLOORE_THEME_PREFIX . '_init_page_setting'),

		// save_page
		array( 'add_action', SLZEXPLOORE_THEME_PREFIX . '_save_page',          array( SLZEXPLOORE_THEME_CLASS, '[theme.Theme_Init, save_page]') ),

		// add mbox page options
		array( 'add_action', SLZEXPLOORE_THEME_PREFIX . '_metabox_pageoption',       array( SLZEXPLOORE_THEME_CLASS, '[theme.Theme_Init, add_page_options]' ) ),
		array( 'do_action',  SLZEXPLOORE_THEME_PREFIX . '_metabox_pageoption' ),
		
		array( 'add_action', SLZEXPLOORE_THEME_PREFIX . '_get_theme_header',          array( SLZEXPLOORE_THEME_CLASS, '[theme.Theme_Controller, get_theme_header]') ),

	),

	'save_post' => array(
		'page'             => array( 'theme.Theme_Init', 'save_page' ),
		'post'             => array( 'theme.Theme_Init', 'save_post' ),
		'product'          => array( 'theme.Theme_Init', 'save_product' ),
	),
	'page_options' => array(
		'post_types' => array( 'post', 'page', 'slzexploore_hotel', 'slzexploore_tour', 'slzexploore_team', 'slzexploore_car', 'slzexploore_cruise', 'product' ),
	),
	'mapping' => array(
		'special_options' => array(
			'header_layout', 'header_top_show', 'header_logo_show', 'header_sticky_enable',
			'footer_show', 'footer_top_show', 'footer_bottom_show',
			'page_title_show', 'breadcrumb_show', 'title_show', 'sub_title_show'
		),
		'no-default-options' => array( 'no_default' ),
		'options' => array(
			'header' => array(
				'header_layout'                => 'slz-style-header',
				'header_sticky_enable'         => 'slz-sticky-enable',
				'header_top_show_menu'         => 'slz-header-top-show-menu',
				'header_top_menu_position'     => 'slz-header-top-menu-position',
				'header_top_menu'              => 'slz-header-top-menu'
			),
			'general' => array(
				'background_transparent'   => array( 'slz-layout-boxed-bg', 'background-color' ),
				'background_color'         => array( 'slz-layout-boxed-bg', 'background-color' ),
				'background_repeat'        => array( 'slz-layout-boxed-bg', 'background-repeat' ),
				'background_attachment'    => array( 'slz-layout-boxed-bg', 'background-attachment' ),
				'background_position'      => array( 'slz-layout-boxed-bg', 'background-position' ),
				'background_size'          => array( 'slz-layout-boxed-bg', 'background-size' ),
				'background_image'         => array( 'slz-layout-boxed-bg', 'background-image' ),
				'background_image_id'      => array( 'slz-layout-boxed-bg', 'media', 'id' ),
			),
			'page_title' => array(
				'page_title_show'          => 'slz-page-title-show',
				'breadcrumb_show'          => 'slz-show-breadcrumb',
				'title_show'               => 'slz-show-title',
				'title_custom_content'     => '',
				'pt_background_color'      => array( 'slz-page-title-bg', 'background-color' ),
				'pt_background_repeat'     => array( 'slz-page-title-bg', 'background-repeat' ),
				'pt_background_attachment' => array( 'slz-page-title-bg', 'background-attachment' ),
				'pt_background_position'   => array( 'slz-page-title-bg', 'background-position' ),
				'pt_background_size'       => array( 'slz-page-title-bg', 'background-size' ),
				'pt_background_image'      => array( 'slz-page-title-bg', 'background-image' ),
				'pt_background_image_id'   => array( 'slz-page-title-bg', 'media', 'id' ),
				'pt_height'                => array( 'slz-page-title-height', 'height' ),
			),
			'footer' => array(
				'footer_show'              => 'slz-footer',
				'show_newsletter'          => 'slz-subcribe',
				'footer_bottom_show'       => 'slz-footerbt-show',
				'footer_style'             => 'slz-footer-style',
			),
			'sidebar' => array(
				'sidebar_layout'           => 'slz-sidebar-layout',
				'sidebar_id'               => 'slz-sidebar',
				'sidebar_post_layout'      => 'slz-blog-sidebar-layout',
				'sidebar_post_id'          => 'slz-blog-sidebar',
				'sidebar_tour_layout'      => 'slz-tour-sidebar-layout',
				'sidebar_tour_id'          => 'slz-tour-sidebar',
				'sidebar_hotel_layout'     => 'slz-hotel-sidebar-layout',
				'sidebar_hotel_id'         => 'slz-hotel-sidebar',
				'sidebar_shop_layout'      => 'slz-shop-sidebar-layout',
				'sidebar_shop_id'          => 'slz-shop-sidebar',
				'sidebar_car_layout'       => 'slz-car-sidebar-layout',
				'sidebar_car_id'           => 'slz-car-sidebar',
				'sidebar_cruise_layout'    => 'slz-cruises-sidebar-layout',
				'sidebar_cruise_id'        => 'slz-cruises-sidebar',
			),
			'no_default' => array(
				'body_extra_class'         => 'slz-body-extra-class',
				'ct_padding_top'           => 'slz-content-padding-top',
				'ct_padding_bottom'        => 'slz-content-padding-bottom',
				'show_page_contact'        => 'slz-contact-section-1',
				'slider-header-fixed'      => 'slz-header-hide',
				'header_content_type'      => 'slz-header-content-type',
				'header_revolution_slider' => 'slz-header-slider',
				'header_search_form'       => 'slz-header-search-form',
				'header_bg_image'          => 'slz-header-bg-image',
				'header_bg_video'          => 'slz-header-bg-video',
				'header_caption_1'         => 'slz-header-caption-1',
				'font_size_1'              => 'slz-header-caption-fontsize-1',
				'header_caption_2'         => 'slz-header-caption-2',
				'font_size_2'              => 'slz-header-caption-fontsize-2',
				'header_button'            => 'slz-header-button',
				'header_button_hover'      => 'slz-header-button-hover',
				'button_link'              => 'slz-header-button-link',
			),
		),
		'archive' => array(
			'slzexploore_tour' => array(
				'slz-tour-page-title-show'    => 'slz-page-title-show',
				'slz-tour-page-title-bg'      => 'slz-page-title-bg',
			),
			'slzexploore_hotel' => array(
				'slz-hotel-page-title-show'    => 'slz-page-title-show',
				'slz-hotel-page-title-bg'      => 'slz-page-title-bg',
			),
			'slzexploore_car' => array(
				'slz-car-page-title-show'    => 'slz-page-title-show',
				'slz-car-page-title-bg'      => 'slz-page-title-bg',
			),
			'slzexploore_cruise' => array(
				'slz-cruises-page-title-show'    => 'slz-page-title-show',
				'slz-cruises-page-title-bg'      => 'slz-page-title-bg',
			),
		),
		'product' => array(
			'slz-shop-page-title-show'    => 'slz-page-title-show',
			'slz-shop-page-title-bg'      => 'slz-page-title-bg',
		),
	),
	'theme_options' => array(
		// default theme options
		'slz-style-header'         =>'two',
		'slz-dropdownmenu-align'   =>'right',
		'slz-page-title-show'      => '1',
		'slz-show-title'           => '1',
		'slz-show-breadcrumb'      => '1',
		'slz-footer'               => '1',
		'slz-footerbt-show'        => '1',
		'slz-footerbt-text'        => esc_html__('&copy; 2016 BY SWLABS. ALL RIGHT RESERVE.', 'exploore'),
		'slz-sidebar-layout'       => 'left',
		'slz-blog-sidebar-layout'  => 'right',
		'slz-404-title'             => esc_html__('404', 'exploore'),
		'slz-404-desc'             => esc_html__('Page not found.', 'exploore'),
		'slz-404-backhome'         => esc_html__('Go Back', 'exploore'),
		'slz-404-button-02'        => esc_html__('Get Help', 'exploore'),
		'slz-blog-show-title'      => 'category',
		'slz-header-account'       => 'hide',
	),
	'image_sizes' => array(
		// SLZEXPLOORE_THEME_PREFIX + name
		'-thumb-1140x515'        => array( 'width' => 1140, 'height' => 515 ),
		'-thumb-900x500'         => array( 'width' => 900, 'height' => 500 ),
		'-thumb-800x540'         => array( 'width' => 800, 'height' => 540 ),
		'-thumb-750x350'         => array( 'width' => 750, 'height' => 350 ),
		'-thumb-670x440'         => array( 'width' => 670, 'height' => 440 ),
		'-thumb-600x270'         => array( 'width' => 600, 'height' => 270 ),
		'-thumb-560x420'         => array( 'width' => 560, 'height' => 420 ),
		'-thumb-400x270'         => array( 'width' => 400, 'height' => 270 ),
		'-thumb-400x200'         => array( 'width' => 400, 'height' => 200 ),
		'-thumb-342x257'         => array( 'width' => 342, 'height' => 257 ),
		'-thumb-260x365'         => array( 'width' => 260, 'height' => 365 ),
		'-thumb-200x200'         => array( 'width' => 200, 'height' => 200 ),
		'-thumb-100x100'         => array( 'width' => 100, 'height' => 100 ),
		'-thumb-100x80'          => array( 'width' => 100, 'height' => 80 ),
		'-thumb-116x63'          => array( 'width' => 116, 'height' => 63 ),
		
		
	),
);