<header>
	<div class="bg-white header-03">
		<div class="header-topbar">
			<div class="container">
				<div class="row">
					<div class="col-xs-4 topbar-left">
						<div class="btn-menu"><i class="icons fa fa-align-left"></i>
							<p class="text"><?php echo esc_html__('Menu', 'exploore');?></p>
						</div>
						<?php 
						if(has_action('wpml_add_language_selector')) {
							$show_laguage_switcher = Slzexploore::get_option('slz-language-switcher');
							if($show_laguage_switcher == '1'){
								echo '<div class="wpml-language">';
								do_action('wpml_add_language_selector');
								echo '</div>';
							}
						}
						if( !empty( $top_menu ) && $menu_position == 'left' ){
							echo wp_kses_post( $top_menu );
						}
						?>
						<div class="hamburger-menu">
							<div class="hamburger-menu-wrapper">
								<div class="icons"></div>
							</div>
						</div>
					</div>
					<div class="col-xs-4 topbar-center">
						<a href="<?php echo esc_url(site_url()); ?>" class="header-logo">
							<?php echo wp_kses_post($header_logo_data);?>
						</a>
					</div>
					<div class="col-xs-4 topbar-right">
						<?php
							do_action( 'slzexploore_login_link' );
							if( !empty( $top_menu ) && $menu_position == 'right' ){
								echo wp_kses_post( $top_menu );
							}
						?>
					</div>
				</div>
			</div>
		</div>
		<div class="header-main">
			<div class="container">
				<div class="header-main-wrapper">
					<div class="navbar-header">
						<div class="logo">
							<a href="<?php echo esc_url(site_url()); ?>" class="header-logo">
								<?php echo wp_kses_post($header_logo_data);?>
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