<?php
if (Slzexploore::get_option('slz-header-hide') == '1') {
	return;
}
/**
 * Header Content
 */
$header_logo_url  = Slzexploore::get_option( 'slz-logo-header', 'url' );
$header_logo_transparent_url  = Slzexploore::get_option( 'slz-logo-header-transparent', 'url' );
if( empty($header_logo_url) ) {
	$header_logo_data = get_bloginfo( 'name', 'display' );
} else {
	$header_logo_data = '<img src="'. esc_url($header_logo_url).'" alt="">';
}
if( empty($header_logo_transparent_url) ) {
	$header_logo_transparent_data = get_bloginfo( 'name', 'display' );
} else {
	$header_logo_transparent_data = '<img src="'. esc_url($header_logo_transparent_url).'" alt="">';
}
/************************* Topbar Left **************************/
$topbar_left = '';
$time = Slzexploore::get_option('slz-header-other-info');
$time_arr =  array();
if ( (!empty($time)) ) {
	foreach ($time  as $value) {
		$time_arr [] = explode('/', $value);
	}
}
$other_info = Slzexploore::get_option('slz-header-other-info');

if ( Slzexploore::get_option('slz-header-dropdonw-language') == '1' ) {
	$topbar_left .= '
	<li><a href="javascript:void(0)" class="country dropdown-text"><span>'.esc_html__('English', 'exploore').'</span><i class="topbar-icon icons-dropdown fa fa-angle-down"></i></a>
		<ul class="dropdown-topbar list-unstyled hide">
			<li><a  class="link">'.esc_html__('Japan', 'exploore').'</a></li>
			<li><a  class="link">'.esc_html__('Korea', 'exploore').'</a></li>
		</ul>
	</li>';
}
if ( !empty($time_arr) ) {
	
	foreach ($time_arr as $value) {
		if (isset($value[1])){
			$topbar_left .= '
			<li><a href="javascript:void(0)" class="monney dropdown-text"><i class="topbar-icon fa '.esc_html($value[0]).'"></i><span>'.esc_html(esc_html($value[1])).'</span></a>
			</li>';
		}
		
	}
}

$header_social_active = Slzexploore::get_option('slz-header-social');
$social_map = Slzexploore::get_params('header-social');

if( Slzexploore::get_option('slz-header-social-info') == '1') {
		if($header_social_active){
			$header_social = '';
			$count = 0;
			foreach ($header_social_active['enabled'] as $key => $value) {
				if ( $count != 0 ){
					$social_key = Slzexploore::get_option('slz-social-' . $key);

					if( !empty( $social_key ) && isset( $social_map[$key] ) ) {
						$header_social .= '<li><a href="'. esc_url($social_key) .'" class="link  '.$key.'" target="_blank"><i class="fa ' . $social_map[$key] . '"></i></a></li>';
					}
				}
				$count++;
			}
			$social_more = Slzexploore::get_option('slz-topbar-more-social');
			if ( !empty($social_more) ) {
				foreach ($social_more as $value) {
					$parse_json = json_decode($value);
					if (is_array($parse_json) && isset($parse_json[1])){
						$header_social  .= '
						<li><a href="'. esc_url($parse_json[1]) .'" class="link" target="_blank"><i class="'.esc_html($parse_json[0]).'"></i></a>
						</li>';
					}
				}
			}
			$topbar_left .= '' . $header_social . '';
		}
}
$top_menu = '';
$menu_position = Slzexploore::get_option('slz-header-top-menu-position');
if( Slzexploore::get_option('slz-header-top-show-menu') == '1') {
	$menu_id = Slzexploore::get_option('slz-header-top-menu');
	if( !empty( $menu_id ) ){
		$top_menu = wp_nav_menu( array( 'menu' => $menu_id, 'echo' => false, 'depth' => 1 ) );
	}
}

/**
 * Header Text Block
 */
// Get theme setting options
$header_text_block_content = Slzexploore::get_option( 'slz-header-text-block-content' );
if ( ! empty( $header_text_block_content ) ) {
	// Case text block content not empty
	$topbar_left = sprintf( '<li class="text-block">%s</li>', wp_kses_post( $header_text_block_content ) ) . $topbar_left;
}

/************************* Layout **************************/
$template = Slzexploore::get_option('slz-style-header');

$layouts = array('one', 'two', 'three', 'four','five');
if( ! in_array( $template , $layouts)) {
	$template = 'two';
}
include(locate_template('inc/header/header-' . $template . '.php'));