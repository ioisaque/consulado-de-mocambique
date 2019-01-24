<?php
$custom_css = '';
$url_arr = '';

if ( !empty($url) ){
	$url_arr = Slzexploore_Core_Util::get_link( $url );
}


if ( !empty($bg_transparent) ) {
	$custom_css .= sprintf( '.btn.btn-maincolor.btn-maincolor-%s{background-color:transparent;} ', esc_attr( $id ) );
}elseif ( !empty($color_button) ) {
	$custom_css .= sprintf( '.btn.btn-maincolor.btn-maincolor-%s{background-color:%s;} ', esc_attr( $id ), esc_attr( $color_button ) );
}
if ( !empty($bg_hv_transparent) ) {
	$custom_css .= sprintf( '.btn.btn-maincolor.btn-maincolor-%s:hover{background-color:transparent;} ', esc_attr( $id ) );
}elseif ($color_button_hover) {
	$custom_css .= sprintf( '.btn.btn-maincolor.btn-maincolor-%s:hover{background-color:%s; border:1px solid %s; } ', esc_attr( $id ), esc_attr( $color_button_hover ), esc_attr( $color_button_hover ) );
}
if( !empty($color_text) ) {
	$custom_css .= sprintf( '.btn.btn-maincolor.btn-maincolor-%s{color:%s;} ', esc_attr( $id ), esc_attr( $color_text ) );
}
if( !empty($color_text_hover) ) {
	$custom_css .= sprintf( '.btn.btn-maincolor.btn-maincolor-%s:hover{color:%s;} ', esc_attr( $id ), esc_attr( $color_text_hover ) );
}
if( !empty($color_border) ) {
	$custom_css .= sprintf( '.btn.btn-maincolor.btn-maincolor-%s{border:1px solid %s;} ', esc_attr( $id ), esc_attr( $color_border ) );
}
if ( !empty($custom_css) ) {
	do_action( 'slzexploore_core_add_inline_style', $custom_css );
}

if ( !empty( $text ) ){
	if( !empty( $url_arr['link'] ) ){
		printf('<a href="%s" %s %s class="slz-shortcode btn btn-maincolor btn-maincolor-%s %s">%s</a>', esc_url( $url_arr['link'] ), esc_attr( $url_arr['url_title'] ), esc_attr( $url_arr['target'] ), esc_attr( $id ), esc_attr( $extra_class ),esc_html( $text ));
	}else{
		printf('<a class="slz-shortcode btn btn-maincolor btn-maincolor-%s %s">%s</a>', esc_attr( $id ), esc_attr( $extra_class ), esc_html( $text ));
	}
}