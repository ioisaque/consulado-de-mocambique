<?php
/**
 * Footer Main
 */

$footer_css 	= '';
$footer_c1_css  = '';
$footer_c2_css  = '';
$footer_c3_css  = '';
$footer_c4_css  = '';
$footer_bt	  = '';

$footer_stt = '1';
$footer_stt = Slzexploore::get_option('slz-footer');

$footer_style = Slzexploore::get_option('slz-footer-style');

if( empty( $footer_style ) ) {
	$footer_css = '';
} else {
	if ( $footer_style == 'light' ) {
		$footer_css = 'bg-transparent';
	}
}

$footerbt_stt = '1';
$footerbt_stt = Slzexploore::get_option('slz-footerbt-show');

$footer_col = Slzexploore::get_option('slz-footer-col');
if( empty( $footer_col ) ) {
	$footer_col = '4';
}

if ( $footer_col == '11' ) {
	$footer_c1_css = 'col-md-12 col-sm-12 text-center';
	$footer_c2_css = 'hide';
	$footer_c3_css = 'hide';
	$footer_c4_css = 'hide';
}
if ( $footer_col == '1' ) {
	$footer_c1_css = 'col-md-12 col-sm-12';
	$footer_c2_css = 'hide';
	$footer_c3_css = 'hide';
	$footer_c4_css = 'hide';
}
if ( $footer_col == '2' ) {
	$footer_c1_css = 'col-md-6 col-sm-6';
	$footer_c2_css = 'col-md-6 col-sm-6';
	$footer_c3_css = 'hide';
	$footer_c4_css = 'hide';
}
if ( $footer_col == '3' ) {
	$footer_c1_css = 'col-md-4 col-sm-4';
	$footer_c2_css = 'col-md-4 col-sm-4';
	$footer_c3_css = 'col-md-4 col-sm-4';
	$footer_c4_css = 'hide';
}
if ( $footer_col == '4' ) {
	$footer_c1_css = 'col-md-3 prl col-sm-6';
	$footer_c2_css = 'col-md-3 pll prl col-sm-6';
	$footer_c3_css = 'col-md-3 pll col-sm-6';
	$footer_c4_css = 'col-md-3 prl col-sm-6';
}

/**
 * Footer Bottom
 */
/************************* Logo  *********************************/
$footer_logo_data  			= Slzexploore::get_option( 'slz-logo-footer' );
$footer_logo_light_data  	= Slzexploore::get_option( 'slz-logo-footer-light' );

/************************* Copyright  *********************************/
$copyright = '<span>' . Slzexploore::get_option('slz-footerbt-text') . '</span>';

/************************* Social  *********************************/
$footer_social_show = Slzexploore::get_option('slz-footerbt-social-content');
$footer_social_active = Slzexploore::get_option('slz-footer-social');
$footer_social = '';
$social_map = Slzexploore::get_params( 'footer-social');
if( $footer_social_active && isset( $footer_social_active['enabled'] ) ) {
	foreach ($footer_social_active['enabled'] as $key => $value) {
		$social_key = 'slz-social-' . $key;
		$social_option = Slzexploore::get_option($social_key);
		if( !empty( $social_option ) && isset( $social_map[$key] ) ) {
			$footer_social .= '<li><a href="'. esc_url($social_option) .'" class="link '. esc_attr($key) .'" target="_blank"><i class="fa ' . esc_attr($social_map[$key]) . '"></i></a></li>';
		}
	}
	$social_more = Slzexploore::get_option('slz-footer-more-social');
	if ( !empty($social_more) ) {
		foreach ($social_more as $value) {
			$parse_json = json_decode($value);
			if (is_array($parse_json) && isset($parse_json[1])){
				$footer_social  .= '
				<li><a href="'. esc_url($parse_json[1]) .'" class="link" target="_blank"><i class="'.esc_html($parse_json[0]).'"></i></a>
				</li>';
			}
		}
	}
}

/************************* Slides  *********************************/
$cat = Slzexploore::get_option('slz-footerbt-partner-cat');
if(!empty($cat)){
	foreach ($cat as $value) {
		$categories[] = get_the_category_by_ID( $value);
	}
}
if(!empty($categories)){
	$cat_id = implode(",", $categories);
}
if( shortcode_exists('slzexploore_core_partner_sc')) {
	if(!empty($cat_id)){
		$slides = do_shortcode('[slzexploore_core_partner_sc style="2" category="'.esc_attr($cat_id).'"]');
	}
	else{
		$slides = do_shortcode('[slzexploore_core_partner_sc style="2"]');
	}
}

/************************* Widget Content  ****************************/
$footer_sidebar_arr = array();
for( $i= 1; $i<=5; $i++){
	$footer_sidebar_id = 'slzexploore-sidebar-footer-' . $i;
	$select_sidebar = Slzexploore::get_option('slz-sidebar-footer-id-' . $i);
	if( !empty( $select_sidebar ) ) {
		$footer_sidebar_id = $select_sidebar;
	}
	$footer_sidebar_arr['sidebar_footer_'.$i] = $footer_sidebar_id;
}

extract($footer_sidebar_arr);
?>
<?php if ( $footer_stt == '1' ) {?>
	<?php if ( $footer_col == '5' ) { ?>
		<div class="footer-main-container">
			<div class="footer-main padding-top padding-bottom <?php echo esc_attr($footer_css); ?>">
				<div class="container">
					<div class="footer-main-wrapper">
						<?php if ( Slzexploore::get_option('slz-footerbt-logo-show') == '1' ) { ?>
							<?php if ( $footer_style == 'light' ) { ?>
								<a href="<?php echo esc_url(site_url()); ?>" class="logo-footer"><img src="<?php echo esc_url($footer_logo_light_data['url']); ?>" alt="<?php esc_attr_e( 'Logo Footer', 'exploore' );?>" class="img-responsive" /></a>
							<?php } else { ?>
								<a href="<?php echo esc_url(site_url()); ?>" class="logo-footer"><img src="<?php echo esc_url($footer_logo_data['url']); ?>" alt="<?php esc_attr_e( 'Logo Footer', 'exploore' );?>" class="img-responsive" /></a>
							<?php } ?>
						<?php } ?>
						<div class="row">
							<div class="col-2">
								<div id="footer_c1" class="footer-area col-md-3 col-xs-5">
									<?php dynamic_sidebar( $sidebar_footer_1 ); ?>
								</div>
								<div id="footer_c2" class="footer-area col-md-2 col-xs-3">
									<?php dynamic_sidebar( $sidebar_footer_2 ); ?>
								</div>
								<div id="footer_c3" class="footer-area col-md-2 col-xs-4">
									<?php dynamic_sidebar( $sidebar_footer_3); ?>
								</div>
							</div>
							<div class="col-2">
								<div id="footer_c4" class="footer-area col-md-2 col-sm-5 col-xs-6">
									<?php dynamic_sidebar( $sidebar_footer_4 ); ?>
								</div>
								<div id="footer_c5" class="footer-area col-md-3 col-sm-7 col-xs-6">
									<?php dynamic_sidebar( $sidebar_footer_5 ); ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php } else { ?>
		<div class="footer-main-container">
			<div class="footer-main padding-top padding-bottom-70 <?php echo esc_attr($footer_css); ?>">
				<div class="container">
					<div class="footer-main-wrapper">
						<?php if ( Slzexploore::get_option('slz-footerbt-logo-show') == '1' ) { ?>
							<?php if ( $footer_css == 'light' ) { ?>
								<a href="<?php echo esc_url(site_url()); ?>" class="logo-footer"><img src="<?php echo esc_url($$footer_logo_light_data['url']); ?>" alt="" class="img-responsive" /></a>
							<?php } else { ?>
								<a href="<?php echo esc_url(site_url()); ?>" class="logo-footer"><img src="<?php echo esc_url($footer_logo_data['url']); ?>" alt="" class="img-responsive" /></a>
							<?php } ?>
						<?php } ?>
						<div class="row">
							<div id="footer_c1" class="footer-area <?php echo esc_attr( $footer_c1_css ); ?>">
								<?php dynamic_sidebar( $sidebar_footer_1 ); ?>
							</div>
							<div id="footer_c2" class="footer-area <?php echo esc_attr( $footer_c2_css ); ?>">
								<?php dynamic_sidebar( $sidebar_footer_2 ); ?>
							</div>
							<div id="footer_c3" class="footer-area <?php echo esc_attr( $footer_c3_css ); ?>">
								<?php dynamic_sidebar( $sidebar_footer_3 ); ?>
							</div>
							<div id="footer_c4" class="footer-area <?php echo esc_attr( $footer_c4_css ); ?>">
								<?php dynamic_sidebar( $sidebar_footer_4 ); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php } ?>
	<?php if ( $footerbt_stt == '1' ) { ?>
		<div class="hyperlink <?php echo esc_attr($footer_css); ?>">
			<div class="container">
				<?php if ( Slzexploore::get_option('slz-footerbt-partner') == '1' && !empty($slides) ) {
					echo ($slides);
				} ?>
				
				<?php if ( $footer_social_show == '1' && $footer_social ) {
					echo '<div class="social-footer"><ul class="list-inline list-unstyled">'. ($footer_social) . '</ul></div>';
				} ?>
				<div class="name-company"><?php echo wp_kses_post($copyright); ?></div>
			</div>
		</div>
	<?php } ?>
<?php } ?>