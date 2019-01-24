<?php
/**
 * Theme init
 *
 * @author Swlabs
 * @package Exploore
 * @since 1.0
 */
// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Theme setup
add_action('after_setup_theme', array( 'Slzexploore', '[theme.Theme_Init, theme_setup]' ) );

require_once( SLZEXPLOORE_FRAMEWORK_DIR . '/class-slzexploore.php' );
require_once( SLZEXPLOORE_THEME_DIR . '/admin/admin-init.php' );

/**
 * Theme option function
 */
require_once( SLZEXPLOORE_FRAMEWORK_DIR . '/slz-theme-option.php' );

// Setup plugins
require SLZEXPLOORE_FRAMEWORK_DIR . '/slz-tgm.php';

// Load class
Slzexploore::load_class( 'Breadcrumb' );

/* Remove Admin bar in frontend when dev */
add_action('get_header', 'slzexploore_remove_admin_login_header');
function slzexploore_remove_admin_login_header() {
	remove_action('wp_head', '_admin_bar_bump_cb');
}

/**
 * Register sidebars
 */
add_action( 'widgets_init', array('Slzexploore', '[widget.Widget_Init, widgets_init]') );

/**
 * Add scripts && css front-end
 */
if( ! is_admin() ) {
	add_action( 'wp_enqueue_scripts', array( 'Slzexploore', '[theme.Theme_Init, public_enqueue]' ) );
}

require_once SLZEXPLOORE_FRAMEWORK_DIR . '/slz-functions.php';
require_once SLZEXPLOORE_FRAMEWORK_DIR . '/slz-menu.php';

// default
/**
 * Customizer additions.
 */
require SLZEXPLOORE_THEME_DIR . '/inc/customizer.php';