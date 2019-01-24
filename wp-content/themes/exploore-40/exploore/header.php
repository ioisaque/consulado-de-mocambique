<?php
/**
 * Header template.
 * 
 * @author Swlabs
 * @since 1.0
 */

//body class
$body_class = Slzexploore::get_option('slz-body-extra-class');
// Page classes
$page_class = '';
//Layout boxed
if ( Slzexploore::get_option('slz-layout') == '2' ) {
	$page_class .= 'layout-boxed';
}
//header
$template = Slzexploore::get_option('slz-style-header');
$template_class = 'header-'.$template;

// check if this is page login/register template to add class
$no_header_page = false;
if(is_page_template ( 'page-templates/page-login.php' )
	|| is_page_template ( 'page-templates/page-register.php' )){
	add_filter( 'body_class', 'slzexploore_add_body_class' );
	$no_header_page = true;
}

function slzexploore_add_body_class( $classes)  {
	$classes[] = 'template-login';
	return $classes;
}

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo('charset'); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="profile" href="http://gmpg.org/xfn/11">
		<?php if ( is_singular() && pings_open( get_queried_object() ) ) : ?>
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
		<?php endif; ?>
		<?php wp_head(); ?>
	</head>

	<body <?php body_class(); ?> data-class="<?php echo esc_attr($template_class);?>">
		<div class="body-wrapper <?php echo esc_attr( $body_class ).' '.esc_attr( $page_class ).' '.esc_attr($template_class);?>">
			<!-- MENU MOBILE-->
			<div class="wrapper-mobile-nav">
				<div class="header-topbar">
					<div class="topbar-search search-mobile">
						<?php get_search_form();?>
					</div>
				</div>


				<div class="header-main">
					<div class="menu-mobile">
						<?php slzexploore_show_main_menu(); ?>
					</div>
					<?php do_action( 'slzexploore_login_link' ); 
						if(has_action('wpml_add_language_selector')) {
							$show_laguage_switcher = Slzexploore::get_option('slz-language-switcher');
							if($show_laguage_switcher == '1'){
								echo '<div class="wpml-language">';
								do_action('wpml_add_language_selector');
								echo '</div>';
							}
						}
					?>
				</div>
			</div>
			<!-- WRAPPER CONTENT-->
			<div class="wrapper-content">
				<!-- HEADER-->
			   <?php do_action('slzexploore_show_header');?>
				<!-- WRAPPER-->
				<div id="wrapper-content">
					<!-- MAIN CONTENT-->
					<div class="main-content">
						<?php
						$arr_posttype = array( 'slzexploore_tour', 'slzexploore_hotel', 'slzexploore_car', 'slzexploore_cruise' );
						if ( in_array( get_post_type(), $arr_posttype ) ) {
							// case post type is hotel, tour, car, cruise
                            do_action( 'slzexploore_show_posttype_header_content' );
						} else {
							// case others
							do_action( 'slzexploore_show_slider' );
							if ( ! is_front_page() && ! $no_header_page ) {
								do_action( 'slzexploore_show_page_title' );
							}
						}
						?>
