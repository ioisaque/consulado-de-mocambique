<?php
if ( ! class_exists( 'Redux' ) ) {
	exit;
}
$redux_opt_name = SLZEXPLOORE_CORE_THEME_OPTIONS;
// All extensions placed within the extensions directory will be auto-loaded for your Redux instance.
Redux::setExtensions( 'importdemo', dirname( __FILE__ ) . '/extensions/' );

// Any custom extension configs should be placed within the configs folder.
if ( file_exists( dirname( __FILE__ ) . '/configs/' ) ) {
	$files = glob( dirname( __FILE__ ) . '/configs/*.php' );
	if ( ! empty( $files ) ) {
		foreach ( $files as $file ) {
			include ( $file );
		}
	}
}

if ( ! function_exists( 'slzexploore_core_register_custom_extension_loader' ) ) :
	function slzexploore_core_register_custom_extension_loader( $ReduxFramework ) {
		$path = dirname( __FILE__ ) . '/';
		$folders = scandir( $path, 1 );
		foreach ( $folders as $folder ) {
			
			if ( $folder === '.' or $folder === '..' or ! is_dir( $path . $folder ) ) {
				continue;
			}
			$extension_class = 'ReduxFramework_Extension_' . $folder;
			// add more "&& ( $extension_class != 'ReduxFramework_Extension_.svn' ) " to fix svn warning when dev
			if ( ! class_exists( $extension_class ) && ( $extension_class != 'ReduxFramework_Extension_.svn' ) ) {
				// In case you wanted override your override, hah.
				$class_file = $path . $folder . '/extension_' . $folder . '.php';
				$class_file = apply_filters( 'redux/extension/' . $ReduxFramework->args ['opt_name'] . '/' . $folder, $class_file );
				if ( $class_file ) {
					require_once ( $class_file );
					$extension = new $extension_class( $ReduxFramework );
				}
			}
		}
	}
	// Modify {$redux_opt_name} to match your opt_name
	add_action( "redux/extensions/{$redux_opt_name}/before", 'slzexploore_core_register_custom_extension_loader', 0 );

endif;