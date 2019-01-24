<?php
$modelVideo = new Slzexploore_Core_Video_Model();
$block_cls = 'banner-'.esc_attr($id).' '.esc_attr($extra_class);
$link =  $custom_css =  $btn = $link_img_video  = '';
if( !empty($image_video) ) :	
	$get_attached_file = get_attached_file($image_video);
	if ( file_exists($get_attached_file) ) {
		$link_img_video = wp_get_attachment_image_src( $image_video, 'large' );
		if ( !empty($link_img_video) ) {
			$link_img_video = $link_img_video[0];
		}
	}
endif;
if ( !empty($image_bg) ) {
	$img1 = wp_get_attachment_url($image_bg);
}

if ( $video_type == '' && !empty($id_youtube) ) {
	$link = 'https://www.youtube.com/embed/'.esc_attr($id_youtube).'?rel=0';
	if ( empty($link_img_video) ) {
		$link_img_video = $modelVideo->get_video_thumb_general( 'youtube', esc_attr($id_youtube) );	
	}
}elseif ( $video_type == '2' && !empty($id_vimeo) ) {
	$link = 'https://player.vimeo.com/video/'.esc_attr($id_vimeo).'?rel=0';
	if ( empty($link_img_video) ) {
		$link_img_video = $modelVideo->get_video_thumb_general( 'vimeo', esc_attr($id_vimeo) );	
	}
}
if ( !empty($image_bg) ) {
	$custom_css .= sprintf( '.banner-%s { background-image: url("%s"); }', esc_attr($id), esc_url($img1) );
	$custom_css .= sprintf( '.banner-%s .about-tours { background-image: url("%s"); }', esc_attr($id), esc_url($img1) );
}
if ( !empty($color_text) ) {
	$custom_css .= sprintf( '', esc_attr($id) );
}
//background btn color
if( !empty($bg_transparent) ) {
	$custom_css .= sprintf( '.banner-%s .btn.btn-maincolor { background-color: transparent;}', esc_attr($id));
}elseif ( !empty($color_button) ) {
	$custom_css .= sprintf( '.banner-%s .btn.btn-maincolor { background-color: %s;}', esc_attr($id), esc_attr($color_button) );
}
//hover background color
if ( !empty($bg_hv_transparent) ) {
	$custom_css .= sprintf( '.banner-%s .btn.btn-maincolor:hover { background-color: transparent;}', esc_attr($id));
}elseif ( !empty($color_button_hover) ) {
	$custom_css .= sprintf( '.banner-%s .btn.btn-maincolor:hover{background-color: %s; border: 1px solid %s;}', esc_attr($id), esc_attr($color_button_hover), esc_attr($color_button_hover) );
}
//color text + color text hover
if ( !empty($color_text) ) {
	$custom_css .= sprintf( '.banner-%s .btn.btn-maincolor { color: %s;}', esc_attr($id), esc_attr($color_text) );
}
if ( !empty($color_text_hover) ) {
	$custom_css .= sprintf( '.banner-%s .btn.btn-maincolor:hover { color: %s;}', esc_attr($id), esc_attr($color_text_hover) );
}
//color border
if ( !empty($color_border) ) {
	$custom_css .= sprintf( '.banner-%s .btn.btn-maincolor { border-color: %s;}', esc_attr($id), esc_attr($color_border) );
}
if ( !empty($color_border_hover) ) {
	$custom_css .= sprintf( '.banner-%s .btn.btn-maincolor:hover { border-color: %s;}', esc_attr($id), esc_attr($color_border_hover) );
}

if ( !empty($button_txt) ) {
	if ( !empty($url_btn['link']) ) {
		$btn = '<a href="'.esc_url($url_btn['link']).'" '.esc_attr($url_btn['url_title']).' '.esc_attr($url_btn['target']).' class="btn btn-maincolor">'.esc_attr($button_txt).'</a>';
	}else{
		$btn = '<a href="" class="btn btn-maincolor">'.esc_attr($button_txt).'</a>';
	}
	
}

if ( $style == '3' ) {
	echo '<div class="slz-shortcode sc-banner layout-3 '.esc_attr($block_cls).'"><div class="about-tours">';
	if ( $is_container == 'yes' ) {
		echo '<div class="container">';
	}
	echo '<div class="team-purchase">';
	if ( !empty($content) ) : echo wp_kses_post($content); endif;
	if ( !empty($btn) ) : echo wp_kses_post($btn); endif;
	echo '</div>';
	if ( $is_container == 'yes' ) {
		echo '</div>';
	}
	echo '</div></div>';
	
} elseif ( $style == '2' ) {
	echo '<div class="slz-shortcode videos layout-2 padding-top '.esc_attr($block_cls).'"><div class="container"><div class="row">';
		echo '<div class="col-md-8 col-md-offset-2"><div class="video-wrapper">';
			if ( !empty($content) ) {
				echo wp_kses_post($content);
			}
			if ( !empty($link) ) {
				echo '<div class="video-thumbnail">';
					echo '
					<div class="video-bg">
						<img src="'.esc_url($link_img_video).'" alt="" class="img-responsive">
					</div>
					<div class="video-button-play"><i class="icons fa fa-play"></i></div>
					<div class="video-button-close"></div>
					<iframe src="'.esc_url($link).'" allowfullscreen="allowfullscreen" class="video-embed"></iframe>
					';
				echo '</div>';
			}
		echo '</div></div>';
	echo '</div></div></div>';
} elseif ( $style == '1' ) {
	echo '<div class="slz-shortcode videos layout-1 '.esc_attr($block_cls).'"><div class="container"><div class="row">';
		echo '<div class="col-md-5"><div class="video-wrapper padding-top padding-bottom">';
			if ( !empty($content) ) {
				echo '<div class="video-wrapper-title">'.wp_kses_post($content).'</div>';
			}
			if( !empty($btn) ) :
				echo wp_kses_post($btn);
			endif;
		echo '</div></div>';
		echo '<div class="col-md-7">';
			if( !empty($link) ) :
				echo '<div class="video-thumbnail">';
					echo '<div class="video-bg"><img src="'.esc_url($link_img_video).'" alt="" class="img-responsive"></div>';
					echo '<div class="video-button-play"><i class="icons fa fa-play"></i></div>';
					echo '<div class="video-button-close"></div>';
					echo '<iframe src="'.esc_url($link).'" allowfullscreen="allowfullscreen" class="video-embed"></iframe>';
				echo '</div>';
			endif;
		echo '</div>';
	echo '</div></div></div>';
	
}
if ( !empty($custom_css) ) {
	do_action( 'slzexploore_core_add_inline_style', $custom_css );
}
?>