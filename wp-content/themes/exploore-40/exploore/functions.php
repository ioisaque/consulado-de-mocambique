<?php
/**
 * Exploore functions and definitions
 *
 * @author Swlabs
 * @package Exploore
 * @since 1.0
 * */
clearstatcache();

if( ! isset( $content_width ) ) {
	$content_width = 1024;
}
// load constants
require_once ( get_template_directory() . '/framework/constants.php' );

// load textdomain
load_theme_textdomain( 'exploore', SLZEXPLOORE_THEME_DIR . '/languages' );

/* Theme Initialization */
require_once( SLZEXPLOORE_FRAMEWORK_DIR . '/slz-init.php' );

$app = Slzexploore::new_object('Application');
$app->run();