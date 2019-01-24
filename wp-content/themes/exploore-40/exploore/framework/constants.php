<?php
/**
 * Constants.
 * 
 * @package Exploore
 * @since 1.0
 */
defined( 'SLZEXPLOORE_THEME_VER' )      || define( 'SLZEXPLOORE_THEME_VER', '2.0' );
defined( 'SLZEXPLOORE_THEME_NAME' )     || define( 'SLZEXPLOORE_THEME_NAME', 'exploore' );
defined( 'SLZEXPLOORE_THEME_PREFIX' )   || define( 'SLZEXPLOORE_THEME_PREFIX', 'slzexploore' );
defined( 'SLZEXPLOORE_THEME_CLASS' )    || define( 'SLZEXPLOORE_THEME_CLASS', 'Slzexploore' );
defined( 'SLZEXPLOORE_THEME_OPTIONS' )  || define( 'SLZEXPLOORE_THEME_OPTIONS', 'slzexploore_options' );
defined( 'SLZEXPLOORE_CORE_CLASS' )     || define( 'SLZEXPLOORE_CORE_CLASS', 'Slzexploore_Core' );

defined( 'SLZEXPLOORE_THEME_DIR' )      || define( 'SLZEXPLOORE_THEME_DIR', get_template_directory() );
defined( 'SLZEXPLOORE_THEME_URI' )      || define( 'SLZEXPLOORE_THEME_URI', get_template_directory_uri() );
defined( 'SLZEXPLOORE_FRAMEWORK_DIR' )  || define( 'SLZEXPLOORE_FRAMEWORK_DIR', SLZEXPLOORE_THEME_DIR . '/framework' );

defined( 'SLZEXPLOORE_MODULES_DIR' )    || define( 'SLZEXPLOORE_MODULES_DIR', SLZEXPLOORE_FRAMEWORK_DIR . '/modules' );
defined( 'SLZEXPLOORE_FRONT_DIR' )      || define( 'SLZEXPLOORE_FRONT_DIR', SLZEXPLOORE_FRAMEWORK_DIR . '/modules/front' );
defined( 'SLZEXPLOORE_PLUGINS_DIR' )    || define( 'SLZEXPLOORE_PLUGINS_DIR', SLZEXPLOORE_FRAMEWORK_DIR . '/plugins' );
defined( 'SLZEXPLOORE_EXTERNAL_DIR' )   || define( 'SLZEXPLOORE_EXTERNAL_DIR', SLZEXPLOORE_FRAMEWORK_DIR . '/external' );

defined( 'SLZEXPLOORE_ADMIN_URI' )      || define( 'SLZEXPLOORE_ADMIN_URI', SLZEXPLOORE_THEME_URI . '/assets/admin' );
defined( 'SLZEXPLOORE_PUBLIC_URI' )     || define( 'SLZEXPLOORE_PUBLIC_URI', SLZEXPLOORE_THEME_URI . '/assets/public' );
defined( 'SLZEXPLOORE_CORE_DIR' )       || define( 'SLZEXPLOORE_CORE_DIR', WP_PLUGIN_DIR . '/slzexploore-core/' );

defined( 'SLZEXPLOORE_COPYRIGHT' )      || define( 'SLZEXPLOORE_COPYRIGHT', '&#xA9; 2016 BY SWLABS. ALL RIGHT RESERVE.' );
defined( 'SLZEXPLOORE_LOGO' )           || define( 'SLZEXPLOORE_LOGO', SLZEXPLOORE_PUBLIC_URI . '/images/logo/logo.png' );
defined( 'SLZEXPLOORE_LOGO_2' )         || define( 'SLZEXPLOORE_LOGO_2', SLZEXPLOORE_PUBLIC_URI . '/images/logo/logo-2.png' );
defined( 'SLZEXPLOORE_LOGO_1' )         || define( 'SLZEXPLOORE_LOGO_1', SLZEXPLOORE_PUBLIC_URI . '/images/logo/logo-1.png' );
defined( 'SLZEXPLOORE_LOGO_FOOTER' )    || define( 'SLZEXPLOORE_LOGO_FOOTER', SLZEXPLOORE_PUBLIC_URI . '/images/logo/logo.png' );

//*********************Plugin***************************
//Active slzexploore-core Plugin - SLZEXPLOORE_CORE_VERSION
defined( 'SLZEXPLOORE_CORE_IS_ACTIVE' )     || define( 'SLZEXPLOORE_CORE_IS_ACTIVE', class_exists( 'Slzexploore_Core' ) );

//ReduxFrameworkPlugin
defined( 'SLZEXPLOORE_REDUX_ACTIVE' )   || define( 'SLZEXPLOORE_REDUX_ACTIVE', class_exists( 'ReduxFrameworkPlugin' ) );

//Active RS
if( defined( 'RS_PLUGIN_PATH' ) ) {
	define( 'SLZEXPLOORE_REVSLIDER_ACTIVE', defined( 'RS_PLUGIN_PATH' ) );
}
else {
	define( 'SLZEXPLOORE_REVSLIDER_ACTIVE', '' );
}

//Active ContactForm7 Plugin
if( defined( 'WPCF7_LOAD_JS' ) ) {
	define( 'SLZEXPLOORE_WPCF7_ACTIVE', defined( 'WPCF7_LOAD_JS' ) );
}
else {
	define( 'SLZEXPLOORE_WPCF7_ACTIVE', '' );
}
//Active Woocommerce Plugin
defined( 'SLZEXPLOORE_WOOCOMMERCE_ACTIVE' )     || define( 'SLZEXPLOORE_WOOCOMMERCE_ACTIVE', class_exists( 'WooCommerce' ) );

defined( 'SLZEXPLOORE_WOOCOMMERCE_WISHLIST' )   || define( 'SLZEXPLOORE_WOOCOMMERCE_WISHLIST', class_exists( 'YITH_WCWL_Shortcode' ) );

// Active Newsletter Plugin
if( defined( 'NEWSLETTER_VERSION' ) ) {
	define( 'SLZEXPLOORE_NEWSLETTER_ACTIVE', defined( 'NEWSLETTER_VERSION' ) );
}
else {
	define( 'SLZEXPLOORE_NEWSLETTER_ACTIVE', '' );
}

defined( 'SLZEXPLOORE_NO_IMG' )         || define( 'SLZEXPLOORE_NO_IMG', SLZEXPLOORE_PUBLIC_URI.'/images/thumb-no-image.gif' );
defined( 'SLZEXPLOORE_NO_IMG_URI' )     || define( 'SLZEXPLOORE_NO_IMG_URI', SLZEXPLOORE_PUBLIC_URI.'/images/no-image/' );
