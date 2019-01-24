<?php
$img = '';
$icon = '';
$photo = '';
$url_arr = '';
$custom_css = '';
$title_link = '';
$target = '';
$col = '';

if( !empty($image) ) {
	$img = wp_get_attachment_url( $image );
}
$col = empty($img) ? 'full' : '';

if ( !empty($url) ){
	$url_arr = Slzexploore_Core_Util::get_link( $url );
}
if ( !empty($url_arr['target']) ) {
	$target = $url_arr['target'];
}
if ( !empty($url_arr['url_title']) ) {
	$title_link = $url_arr['url_title'];
}

if( !empty( $img ) ) :
	$photo = '
	<div class="timeline-custom-col image-col">
		<div class="timeline-image-block">
			<img src="'.esc_url( $img ).'" />
		</div>
	</div>';
endif;

if ( !empty($icon_type) && $icon_type == '02' && !empty( $icon_fw ) ) {
	$icon = $icon_fw;

}
elseif ( empty($icon_type) && !empty( $icon_ex ) ) {
	$icon = $icon_ex;
}


echo '<div class="slz-shortcode icon-box-'.esc_attr($id).' '.esc_attr($extra_class).'">';

if( $style_icon == '2' ) {
	if ( !empty($icon) || !empty($title) ) {
		echo '<div class="item feature-item">';
			echo '<i class="icon-journey '.esc_attr( $icon ).'"></i>';
			if ( !empty($title) ) {
				echo '<div class="text">'.esc_html( $title ).'</div>';
			}
		echo '</div>';
	}
}
elseif ( $style_icon == '3' ) {

	if( !empty($color) ){
		$custom_css .= sprintf('.icon-box-%s .list-continent-wrapper .continent:before{background-color:%s;}', esc_attr($id), esc_attr( $color ));
	}
	if ( !empty($title) || !empty($icon) ) :
		echo '<div class="list-continent-wrapper">';
		if ( !empty($url_arr['link']) )
		{
			echo '
				<a href="'.esc_url($url_arr['link']).'" '.esc_attr($title_link).' '.esc_attr($target).' class="continent">
					<i class="icons '.esc_attr($icon).'"></i>
					<span class="text">'.esc_html($title).'</span>
				</a>
			';
		}
		else{
			echo '
				<span class="continent">
					<i class="icons '.esc_attr($icon).'"></i>
					<span class="text">'.esc_html($title).'</span>
				</span>
			';
		}
		echo '</div>';
	endif;
}
elseif ( $style_icon == '4' ) {
	echo '<div class="our-content">';
		if ( !empty($icon) ) {
			echo '<i class="our-icon '.esc_attr($icon).'"></i>';
		}
		echo '<div class="main-our">';
		if ( !empty($title) ) {
			echo '<p class="our-title">'.esc_html($title).'</p>';
		}
		if ( !empty($description) ) {
			echo '<div class="text">'.wp_kses_post($description).'</div>';
		}
		echo '</div>';
	echo '</div>';
	if ( !empty($title_color) ) {
		$custom_css .= sprintf( '.icon-box-%s .our-content .our-title{color: %s}', esc_attr($id), esc_attr($title_color) );
	}
	if ( !empty($description_color) ) {
		$custom_css .= sprintf( '.icon-box-%s .our-content .text{color: %s}', esc_attr($id), esc_attr($description_color) );
	}
}
elseif ( $style_icon == '5' ) {

	if( !empty($color) ){
		$custom_css .= sprintf('.icon-box-%s .box-media:hover .icons{background:%s;}', esc_attr($id), esc_attr( $color ));
	}

	echo '<div class="media box-media">';
	if( !empty($url_arr['link']) )
	{
		echo '
			<div class="media-left">
				<a href="'.esc_url($url_arr['link']).'" '.esc_attr($title_link).' '.esc_attr($target).' class="icons">
					<i class="'.esc_attr($icon).'"></i>
				</a>
			</div>
			<div class="media-right">
				<a href="'.esc_url($url_arr['link']).'" class="title">'.esc_html($title).'</a>
				<div class="text">'.wp_kses_post($description).'</div>
			</div>
		';
	}else{
		echo '
			<div class="media-left">
				<span class="icons">
					<i class="'.esc_attr($icon).'"></i>
				</span>
			</div>
			<div class="media-right">
				<span class="title">'.esc_html($title).'</span>
				<div class="text">'.wp_kses_post($description).'</div>
			</div>
		';
	}
	
	echo '</div>';
}
elseif ( $style_icon == '6' ) {

	if( !empty($color) ){
		$custom_css .= sprintf('.icon-box-%s .contact-list-media:hover .icons{background:%s;}', esc_attr($id), esc_attr( $color ));
	}

	echo '<div class="media contact-list-media">';
	if( !empty($url_arr['link']) )
	{
		echo '
			<div class="media-left">
				<a href="'.esc_url($url_arr['link']).'" '.esc_attr($title_link).' '.esc_attr($target).' class="icons">
					<i class="'.esc_attr($icon).'"></i>
				</a>
			</div>
			<div class="media-right">
				<a href="'.esc_url($url_arr['link']).'" '.esc_attr($title_link).' '.esc_attr($target).' class="title">'.esc_html($title).'</a>
				<div class="text">'.wp_kses_post($description).'</div>
			</div>
		';
	}else{
		echo '
			<div class="media-left">
				<span class="icons">
					<i class="'.esc_attr($icon).'"></i>
				</span>
			</div>
			<div class="media-right">
				<span class="title">'.esc_html($title).'</span>
				<div class="text">'.wp_kses_post($description).'</div>
			</div>
		';
	}
	
	echo '</div>';
}
else{
	echo '<div class="row">';
	echo '
			<div class="'.esc_attr($col).' timeline-custom-col content-col">
				<div class="timeline-location-block">
					<p class="location-name">
						'.esc_attr($title).'
						<i class="'.esc_attr($icon).' icon-marker"></i>
					</p>
					<div class="description">'.wp_kses_post($description).'</div>
				</div>
			</div>
			'.wp_kses_post($photo).'
		';
	echo '</div>';
}
echo '</div>';

if ( !empty($custom_css) ) {
	do_action( 'slzexploore_core_add_inline_style', $custom_css );
}