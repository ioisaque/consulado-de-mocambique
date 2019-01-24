<?php
// Load Redux extensions - MUST be loaded before your options are set
if ( SLZEXPLOORE_REDUX_ACTIVE && SLZEXPLOORE_CORE_IS_ACTIVE && file_exists( SLZEXPLOORE_CORE_DIR.'/extensions/extensions-init.php')) {
	require_once( SLZEXPLOORE_CORE_DIR.'/extensions/extensions-init.php' );
}

// Load the theme/plugin options
if ( SLZEXPLOORE_REDUX_ACTIVE && SLZEXPLOORE_CORE_IS_ACTIVE && file_exists( SLZEXPLOORE_THEME_DIR.'/admin/options-init.php')) {
	require_once( SLZEXPLOORE_THEME_DIR.'/admin/options-init.php' );
}