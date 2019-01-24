<header>
	<div class="bg-transparent header-01">
		<div class="header-topbar">
			<div class="container">
			<?php
				// top bar left
				echo '<ul class="topbar-left list-unstyled list-inline pull-left">';
				$show_laguage_switcher = Slzexploore::get_option('slz-language-switcher');
				if( $show_laguage_switcher == '1' && has_action('wpml_add_language_selector')) {
					echo '<div class="wpml-language">';
					do_action('wpml_add_language_selector');
					echo '</div>';
				}
				echo wp_kses_post( $topbar_left );
				echo '</ul>';
				if( !empty( $top_menu ) && $menu_position == 'left' ){
					echo '<div class="topbar-left pull-left">';
					echo wp_kses_post( $top_menu );
					echo '</div>';
				}
				// top bar right
				do_action( 'slzexploore_login_link' );
				if( !empty( $top_menu ) && $menu_position == 'right' ){
					echo '<div class="topbar-right pull-right">';
					echo wp_kses_post( $top_menu );
					echo '</div>';
				}
			?>
			</div>
		</div>
		<div class="header-main">
			<div class="container">
				<div class="header-main-wrapper">
					<div class="hamburger-menu">
						<div class="hamburger-menu-wrapper">
							<div class="icons"></div>
						</div>
					</div>
					<div class="navbar-header">
						<div class="logo">
							<a href="<?php echo esc_url(site_url()); ?>" class="header-logo">
								<?php echo wp_kses_post($header_logo_transparent_data);?>
							</a>
						</div>
					</div>
					<nav class="navigation"><?php 
						slzexploore_show_main_menu();
						if ( Slzexploore::get_option('slz-header-search-icon') == '1' ) {?>
							<div class="button-search"><span class="main-menu"><i class="fa fa-search"></i></span></div>
							<div class="nav-search hide">
								<?php get_search_form(true);?>
							</div>
						<?php } ?>
					</nav>
					<div class="clearfix"></div>
				</div>
			</div>
		</div>
	</div>
</header>