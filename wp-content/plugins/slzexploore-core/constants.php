<?php
/**
 * Constants.
 * 
 * @author Swlabs
 * @package Slzexploore-Core
 * @since 1.0
 */

defined( 'SLZEXPLOORE_CORE_VERSION' )        || define( 'SLZEXPLOORE_CORE_VERSION', '2.0' );
defined( 'SLZEXPLOORE_CORE_SC_CATEGORY' )    || define( 'SLZEXPLOORE_CORE_SC_CATEGORY', 'Exploore Core' );
defined( 'SLZEXPLOORE_CORE_CLASS' )          || define( 'SLZEXPLOORE_CORE_CLASS', 'Slzexploore_Core' );

defined( 'SLZEXPLOORE_CORE_URI' )            || define( 'SLZEXPLOORE_CORE_URI', plugin_dir_url( __FILE__ ) );
defined( 'SLZEXPLOORE_CORE_DIR' )            || define( 'SLZEXPLOORE_CORE_DIR', dirname( __FILE__ ) );

defined( 'SLZEXPLOORE_CORE_ASSET_URI' )      || define( 'SLZEXPLOORE_CORE_ASSET_URI', SLZEXPLOORE_CORE_URI . 'assets' );

defined( 'SLZEXPLOORE_CORE_CF_DIR' )         || define( 'SLZEXPLOORE_CORE_CF_DIR', SLZEXPLOORE_CORE_DIR . '/config' );
defined( 'SLZEXPLOORE_CORE_SHORTCODE_DIR' )  || define( 'SLZEXPLOORE_CORE_SHORTCODE_DIR', SLZEXPLOORE_CORE_DIR . '/shortcode' );
defined( 'SLZEXPLOORE_CORE_VENDOR_SUPPORT' ) || define( 'SLZEXPLOORE_CORE_VENDOR_SUPPORT', SLZEXPLOORE_CORE_DIR . '/extensions/vendor_support/' );


// Active ContactForm7 Plugin 
defined( 'SLZEXPLOORE_CORE_WPCF7_ACTIVE' )           || define( 'SLZEXPLOORE_CORE_WPCF7_ACTIVE', is_plugin_active( 'contact-form-7/wp-contact-form-7.php' ) );
//Active VC Plugin
defined( 'SLZEXPLOORE_CORE_VC_ACTIVE' )              || define( 'SLZEXPLOORE_CORE_VC_ACTIVE', is_plugin_active( 'js_composer/js_composer.php' ) );
//Active Woocommerce Plugin
defined( 'SLZEXPLOORE_CORE_WOOCOMMERCE_ACTIVE' )     || define( 'SLZEXPLOORE_CORE_WOOCOMMERCE_ACTIVE', is_plugin_active( 'woocommerce/woocommerce.php' ) );
defined( 'SLZEXPLOORE_CORE_REVSLIDER_ACTIVE' )       || define( 'SLZEXPLOORE_CORE_REVSLIDER_ACTIVE', is_plugin_active( 'revslider/revslider.php' ) );

// Default Image
defined( 'SLZEXPLOORE_CORE_NO_IMG_REC' )         || define( 'SLZEXPLOORE_CORE_NO_IMG_REC', SLZEXPLOORE_CORE_ASSET_URI.'/images/no-image/thumb-rectangle.gif' );
defined( 'SLZEXPLOORE_CORE_NO_IMG_SQUARE' )      || define( 'SLZEXPLOORE_CORE_NO_IMG_SQUARE', SLZEXPLOORE_CORE_ASSET_URI.'/images/no-image/thumb-square.gif' );
defined( 'SLZEXPLOORE_CORE_NO_IMG_URI' )         || define( 'SLZEXPLOORE_CORE_NO_IMG_URI', SLZEXPLOORE_CORE_ASSET_URI.'/images/no-image/' );
defined( 'SLZEXPLOORE_CORE_NO_IMG_DIR' )         || define( 'SLZEXPLOORE_CORE_NO_IMG_DIR', SLZEXPLOORE_CORE_DIR.'/assets/images/no-image/' );
defined( 'SLZEXPLOORE_CORE_MAP_MAKER' )          || define( 'SLZEXPLOORE_CORE_MAP_MAKER', SLZEXPLOORE_CORE_ASSET_URI.'/images/marker.png' );

defined( 'SLZEXPLOORE_CORE_HOTEL_MAP_MAKER' )          || define( 'SLZEXPLOORE_CORE_HOTEL_MAP_MAKER', SLZEXPLOORE_CORE_ASSET_URI.'/images/hotel_marker.png' );
defined( 'SLZEXPLOORE_CORE_CAR_MAP_MAKER' )          || define( 'SLZEXPLOORE_CORE_CAR_MAP_MAKER', SLZEXPLOORE_CORE_ASSET_URI.'/images/car_marker.png' );
defined( 'SLZEXPLOORE_CORE_CRUISE_MAP_MAKER' )          || define( 'SLZEXPLOORE_CORE_CRUISE_MAP_MAKER', SLZEXPLOORE_CORE_ASSET_URI.'/images/cruise_marker.png' );
defined( 'SLZEXPLOORE_CORE_TOUR_MAP_MAKER' )          || define( 'SLZEXPLOORE_CORE_TOUR_MAP_MAKER', SLZEXPLOORE_CORE_ASSET_URI.'/images/tour_marker.png' );
defined( 'SLZEXPLOORE_CORE_MAKER_CLUSTER' )          || define( 'SLZEXPLOORE_CORE_MAKER_CLUSTER', SLZEXPLOORE_CORE_ASSET_URI.'/images/clusterer_m2.png' );

// Options
defined( 'SLZEXPLOORE_CORE_THEME_CLASS' )        || define( 'SLZEXPLOORE_CORE_THEME_CLASS', 'Slzexploore' );
defined( 'SLZEXPLOORE_CORE_THEME_PREFIX' )       || define( 'SLZEXPLOORE_CORE_THEME_PREFIX', 'slzexploore' );
defined( 'SLZEXPLOORE_CORE_THEME_OPTIONS' )      || define( 'SLZEXPLOORE_CORE_THEME_OPTIONS', 'slzexploore_options' );
defined( 'SLZEXPLOORE_CORE_POST_VIEWS' )         || define( 'SLZEXPLOORE_CORE_POST_VIEWS', SLZEXPLOORE_CORE_THEME_PREFIX . '_postview_number' );
defined( 'SLZEXPLOORE_CORE_POST_RATES' )         || define( 'SLZEXPLOORE_CORE_POST_RATES', SLZEXPLOORE_CORE_THEME_PREFIX . '_postrate_number' );
defined( 'SLZEXPLOORE_CORE_TAXONOMY_CUS' )       || define( 'SLZEXPLOORE_CORE_TAXONOMY_CUS', 'slzexploore_taxonomy_cus' );
defined( 'SLZEXPLOORE_CORE_POST_OPTIONS' )       || define( 'SLZEXPLOORE_CORE_POST_OPTIONS', SLZEXPLOORE_CORE_THEME_PREFIX . '_post_options' );
// import taxonomy
defined( 'SLZEXPLOORE_CORE_IMPORT_TAXONOMY_ACTIVE' )       || define( 'SLZEXPLOORE_CORE_IMPORT_TAXONOMY_ACTIVE', false );
