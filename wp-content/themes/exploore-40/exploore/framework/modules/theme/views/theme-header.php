	<?php
	$arr_pages = array(
		'requirement' => esc_html__( "Requirements & Recommendations", 'exploore' ),
		'plugin'      => esc_html__( "Plugins", 'exploore' ),
		'icon'        => esc_html__( "Exploore Icons", 'exploore' ),
		'changelog'   => esc_html__( "Changes Log", 'exploore' )
	);
	$screen = get_current_screen();
	$args = explode('_', $screen->id);
	$id_page = array_pop($args);
	?>
	<h1><?php esc_html_e( "Welcome to Exploore!", 'exploore' ); ?></h1>
	<div class="about-text">
		<?php esc_html_e( "Exploore is now installed and ready to use!  Get ready to build something beautiful. Please register your purchase to get support and automatic theme updates. Read below for additional information. We hope you enjoy it!", 'exploore' ); ?>
	</div>
	<h2 class="nav-tab-wrapper">
		<?php 
		foreach ( $arr_pages as $id => $name ) {
			$active = '';
			if( $id == $id_page ) {
				$active = 'nav-tab-active';
			}
			if( $id == 'icon' && ! SLZEXPLOORE_CORE_IS_ACTIVE ){
				continue;
			}
			printf( '<a href="%1$s" class="nav-tab %3$s">%2$s</a>',
					esc_url( admin_url( 'admin.php?page='.SLZEXPLOORE_THEME_PREFIX . '_' . $id ) ),
					$name,
					$active );
		}
		?>
	</h2>