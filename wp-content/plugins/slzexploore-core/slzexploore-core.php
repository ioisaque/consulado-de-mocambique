<?php
/*
Plugin Name: Slzexploore Core
Plugin URI: https://themeforest.net/user/therubikthemes
Description: Slzexploore Core Plugin for exploore theme.
Version: 4.0
Author: Rubik Themes
Author URI: https://themeforest.net/user/therubikthemes
Text Domain: slzexploore-core
*/

clearstatcache();

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

// load constants
require_once( plugin_dir_path( __FILE__ ) . '/constants.php' );

/* Load plugin textdomain.*/
load_plugin_textdomain( 'slzexploore-core', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );

/* Initialization */
require_once( plugin_dir_path( __FILE__ ) . '/class-slzexploore-core.php' );
require_once( plugin_dir_path( __FILE__ ) . '/libs/class-format.php' );
require_once( plugin_dir_path( __FILE__ ) . '/libs/class-util.php' );
require_once( plugin_dir_path( __FILE__ ) . '/libs/class-com.php' );
require_once( plugin_dir_path( __FILE__ ) . '/custom-functions.php' );
require_once( plugin_dir_path( __FILE__ ) . '/framework/modules/shortcode/class-section.php' );

Slzexploore_Core::load_class( 'Helper' );
Slzexploore_Core::load_class( 'models.Custom_Post_Model' );
Slzexploore_Core::load_class( 'models.Taxonomy_Model' );
Slzexploore_Core::load_class( 'models.Video_Model' );
Slzexploore_Core::load_class( 'models.Pagination' );
Slzexploore_Core::load_class( 'shortcode.Accommodation' );
Slzexploore_Core::load_class( 'shortcode.Room' );
Slzexploore_Core::load_class( 'shortcode.Vacancy' );
Slzexploore_Core::load_class( 'shortcode.Block' );
Slzexploore_Core::load_class( 'shortcode.Gallery' );
Slzexploore_Core::load_class( 'shortcode.Testimonial' ); 
Slzexploore_Core::load_class( 'shortcode.Team' );
Slzexploore_Core::load_class( 'shortcode.Partner' );
Slzexploore_Core::load_class( 'shortcode.Tour' );
Slzexploore_Core::load_class( 'Social_Share' );
Slzexploore_Core::load_class( 'shortcode.Booking' );
Slzexploore_Core::load_class( 'shortcode.Tour_Booking' ); 
Slzexploore_Core::load_class( 'shortcode.Cruise' );
Slzexploore_Core::load_class( 'shortcode.Cabin_Type' );
Slzexploore_Core::load_class( 'shortcode.Cruise_Booking' );
Slzexploore_Core::load_class( 'shortcode.Faq' );
Slzexploore_Core::load_class( 'shortcode.Car' );
Slzexploore_Core::load_class( 'shortcode.Car_Booking' );
Slzexploore_Core::load_class( 'shortcode.Extra_Item' );
Slzexploore_Core::load_class( 'shortcode.Schedule' );


$app = Slzexploore_Core::new_object('Application');
$app->run();
if(SLZEXPLOORE_CORE_IMPORT_TAXONOMY_ACTIVE) {
	$opt = Slzexploore_Core::new_object('Options_Importer');
	$opt->instance();
}

Slzexploore_Core::load_class( 'setting.Taxonomies_Controller' );
require_once( plugin_dir_path( __FILE__ ) . '/shortcode/shortcode_function.php' );

if( ! is_admin() ) {
	add_action( 'wp_enqueue_scripts', array( 'Slzexploore_Core', '[setting.Setting_Init, dev_enqueue_scripts]' ) );
}
register_activation_hook( __FILE__, 'slzexploore_core_update_db' );