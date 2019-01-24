<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @author Swlabs
 * @package Exploore
 * @since 1.0
 */
$title            = Slzexploore::get_option('slz-404-title');
$description      = Slzexploore::get_option('slz-404-desc');
$home_link_text   = Slzexploore::get_option('slz-404-backhome');
$button_link_text = Slzexploore::get_option('slz-404-button-02');
$button_link      = Slzexploore::get_option('slz-404-button-02-link');
$button_link_custom  = Slzexploore::get_option('slz-404-button-02-link-custom');
$link = '';
if(!empty($button_link_custom)){
	$link = $button_link_custom;
}else{
	if ( !empty($button_link) ) {
		$link = get_page_link($button_link);
	}
}
$header_logo_data   = Slzexploore::get_option('slz-logo-header-transparent');
/* 404 Setting */
add_action('wp_head', 'slzexploore_404_css');
function slzexploore_404_css() {
	$page_404_bg_image = Slzexploore::get_option('slz-404-bg', 'background-image');
	$page_404_bg_repeat = Slzexploore::get_option('slz-404-bg', 'background-repeat');
	$page_404_bg_attachment = Slzexploore::get_option('slz-404-bg', 'background-attachment');
	$page_404_bg_position = Slzexploore::get_option('slz-404-bg', 'background-position');
	$page_404_bg_size = Slzexploore::get_option('slz-404-bg', 'background-size');
	$page_404_bg_color = Slzexploore::get_option('slz-404-bg', 'background-color');
	$bg_image = '';
	if( $page_404_bg_image ) {
		$bg_image = 'background-image: url("' .$page_404_bg_image. '");';
	} else {
		$page_404_bg_color = '#d8d8d8';
	}
	
	$content = '.page-404{background-color: ' .$page_404_bg_color. ';'. $bg_image .'background-repeat: ' .$page_404_bg_repeat. ';background-attachment: ' .$page_404_bg_attachment. ';background-position:' .$page_404_bg_position. ';background-size:'.$page_404_bg_size.';}';
		
	echo '<style>header,.page-banner,footer{display:none;}'. wp_kses_post($content) .'</style>';
}
?>

<?php get_header(); ?>
<section class="page-404">
	<div class="page-clouds-1"></div>
	<div class="page-clouds-2"></div>
	<div class="page-wrapper">
		<div class="page-content">
			<h1 class="title-1"><?php echo esc_html($title); ?></h1>
			<h2 class="title-2"><?php echo esc_html($description); ?></h2>
			<div class="group-button">
				<a href="<?php echo esc_url(site_url()); ?>" class="btn btn-maincolor"><?php echo esc_html($home_link_text); ?></a>
				<a href="<?php echo esc_url( $link );?>" class="btn btn-transparent"><?php echo esc_html($button_link_text); ?></a>
			</div>
		</div>
	</div>
	<div class="page-clouds-3"></div>
</section>
<?php get_footer(); ?>