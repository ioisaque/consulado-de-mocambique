<?php
$custom_css = '';
$img = '';
$btn = '';
$text_right = '';
$link1 = '';
$link2 = '';
$target1 = '';
$target2 = '';
$title1 = '';
$title2 = '';
$txt = '';
if (!empty($buttontext1)) {
	if ( !empty($url1['link']) ) {
		$link1 = $url1['link'];
	}
	if ( !empty($url1['url_title']) ) {
		$title1 = $url1['url_title'];
	}
	if ( !empty($url1['target']) ) {
		$target1 = $url1['target'];
	}
}
if (!empty($buttontext2)) {
	if ( !empty($url2['link']) ) {
		$link2 = $url2['link'];
	}
	if ( !empty($url2['url_title']) ) {
		$title2 = $url2['url_title'];
	}
	if ( !empty($url2['target']) ) {
		$target2 = $url2['target'];
	}
}



if ( !empty($usebutton) ) {

	$btn = '<div class="group-button">';
	if ( $usebutton == '1' ) {
		if (!empty($buttontext1)) {
			$btn .= '<a href="'.esc_url($link1).'" '.esc_attr($title1).' '.esc_attr($target1).' class="btn btn-maincolor btn-maincolor1-'.esc_attr($id).'">'.esc_html($buttontext1).'</a>';
		}
		
		if( !empty($btn_text_1) ) {
			$custom_css .= sprintf( '.btn.btn-maincolor1-%s{color: %s;}', esc_attr($id), esc_attr($btn_text_1) );
		}
		if ( !empty($btn_text_hover_1) ) {
			$custom_css .= sprintf( '.btn.btn-maincolor1-%s:hover{color: %s;}', esc_attr($id), esc_attr($btn_text_hover_1) );
		}
		if ( !empty($bg_transparent1) ) {
			$custom_css .= sprintf( '.btn.btn-maincolor1-%s{background-color: transparent; border: 1px solid %s;}', esc_attr($id), esc_attr($btn_bg_1) );
		}elseif ( !empty($btn_bg_1) ) {
			$custom_css .= sprintf( '.btn.btn-maincolor1-%s{background-color: %s; border: 1px solid %s;}', esc_attr($id), esc_attr($btn_bg_1), esc_attr($btn_bg_1) );
		}
		if ( !empty($bg_hv_transparent1) ) {
			$custom_css .= sprintf( '.btn.btn-maincolor1-%s:hover{background-color: transparent; border: 1px solid %s;}', esc_attr($id), esc_attr($btn_bg_hover_1) );
		}elseif ( !empty($btn_bg_hover_1) ) {
			$custom_css .= sprintf( '.btn.btn-maincolor1-%s:hover{background-color: %s; border: 1px solid %s;}', esc_attr($id), esc_attr($btn_bg_hover_1), esc_attr($btn_bg_hover_1) );
		}
		if ( !empty($color_border1) ) {
			$custom_css .= sprintf( '.btn.btn-maincolor1-%s{border: 1px solid %s;}', esc_attr($id), esc_attr($color_border1) );
		}
	}
	if( $usebutton == '2' ) {

		$btn .= '<a href="'.esc_url($link1).'" '.esc_attr($title1).' '.esc_attr($target1).' class="btn btn-maincolor btn-maincolor1-'.esc_attr($id).'">'.esc_html($buttontext1).'</a>';
		if( !empty($btn_text_1) ) {
			$custom_css .= sprintf( '.btn.btn-maincolor1-%s{color: %s;}', esc_attr($id), esc_attr($btn_text_1) );
		}
		if ( !empty($btn_text_hover_1) ) {
			$custom_css .= sprintf( '.btn.btn-maincolor1-%s:hover{color: %s;}', esc_attr($id), esc_attr($btn_text_hover_1) );
		}
		if ( !empty($bg_transparent1) ) {
			$custom_css .= sprintf( '.btn.btn-maincolor1-%s{background-color: transparent; border: 1px solid %s;}', esc_attr($id), esc_attr($btn_bg_1) );
		}elseif ( !empty($btn_bg_1) ) {
			$custom_css .= sprintf( '.btn.btn-maincolor1-%s{background-color: %s; border: 1px solid %s;}', esc_attr($id), esc_attr($btn_bg_1), esc_attr($btn_bg_1) );
		}
		if ( !empty($bg_hv_transparent1) ) {
			$custom_css .= sprintf( '.btn.btn-maincolor1-%s:hover{background-color: transparent; border: 1px solid %s;}', esc_attr($id), esc_attr($btn_bg_hover_1) );
		}elseif ( !empty($btn_bg_hover_1) ) {
			$custom_css .= sprintf( '.btn.btn-maincolor1-%s:hover{background-color: %s; border: 1px solid %s;}', esc_attr($id), esc_attr($btn_bg_hover_1), esc_attr($btn_bg_hover_1) );
		}
		if ( !empty($color_border1) ) {
			$custom_css .= sprintf( '.btn.btn-maincolor1-%s{border: 1px solid %s;}', esc_attr($id), esc_attr($color_border1) );
		}

		if ( !empty($buttontext2) ) {
			$btn .= '<a href="'.esc_url($link2).'" '.esc_attr($title2).' '.esc_attr($target2).' class="btn btn-maincolor btn-maincolor2-'.esc_attr($id).'">'.esc_html($buttontext2).'</a>';
		}
		
		if( !empty($btn_text_2) ) {
			$custom_css .= sprintf( '.btn.btn-maincolor2-%s{color: %s;}', esc_attr($id), esc_attr($btn_text_2) );
		}
		if ( !empty($btn_text_hover_2) ) {
			$custom_css .= sprintf( '.btn.btn-maincolor2-%s:hover{color: %s;}', esc_attr($id), esc_attr($btn_text_hover_2) );
		}
		if ( !empty($bg_transparent2) ) {
			$custom_css .= sprintf( '.btn.btn-maincolor2-%s{background-color: transparent; border: 1px solid %s;}', esc_attr($id), esc_attr($btn_bg_2) );
		}elseif ( !empty($btn_bg_2) ) {
			$custom_css .= sprintf( '.btn.btn-maincolor2-%s{background-color: %s; border: 1px solid %s;}', esc_attr($id), esc_attr($btn_bg_2), esc_attr($btn_bg_2) );
		}
		if ( !empty($bg_hv_transparent2) ) {
			$custom_css .= sprintf( '.btn.btn-maincolor2-%s:hover{background-color: transparent; border: 1px solid %s;}', esc_attr($id), esc_attr($btn_bg_hover_2) );
		}elseif ( !empty($btn_bg_hover_2) ) {
			$custom_css .= sprintf( '.btn.btn-maincolor2-%s:hover{background-color: %s; border: 1px solid %s;}', esc_attr($id), esc_attr($btn_bg_hover_2), esc_attr($btn_bg_hover_2) );
		}
		if ( !empty($color_border2) ) {
			$custom_css .= sprintf( '.btn.btn-maincolor2-%s{border: 1px solid %s;}', esc_attr($id), esc_attr($color_border2) );
		}
	}

	$btn .= '</div>';
}


$text_right = '<div class="text-right">';
if ( !empty($content) ) {
	$text_right .= $content;
}
if( !empty($usebutton) ) :
	$text_right .= $btn;
endif;
$text_right .= '</div>';

if ( $style == '2' ) {
	echo '
		<div class="slz-shortcode banner-sale-3 new-style '.esc_attr($extra_class).'">
			<div class="container">
				<div class="text-salebox">
					<div class="text-left">
						<div class="sale-box">
							<div class="sale-box-top">
								<h2 class="number">'.esc_attr($discount_percent).'</h2>';
								if ( !empty($discount_percent) ) {
									echo '<span class="sup-1">%</span>';
								}else{
									echo '<span class="sup-1"></span>';
								}
								
	echo '
								<span class="sup-2">'.esc_html($dicount_text1).'</span>
							</div>
							<h2 class="text-sale">'.esc_html($dicount_text2).'</h2>
						</div>
					</div>
					'.wp_kses_post($text_right).'
				</div>
			</div>
		</div>
	';
	if ( !empty($height) ) {
		$custom_css .= sprintf( '.banner-sale-3 {height: %spx;}', esc_attr($height) );
	}
	if ( !empty($image) ) {
		$img = wp_get_attachment_url( $image );
		$custom_css .= sprintf('.banner-sale-3{background-image:url("%s");}', esc_attr( $img ) );
	}
}elseif ( $style == '3' ) {
	echo '<div class="slz-shortcode banner-sale-1 '.esc_attr($extra_class).'">';
	if( !empty($left_text) || !empty($left_text2) || !empty($left_text3) ) :
		echo '
				<div class="banner-left">
					<div class="title-box">
						<h2 class="title-overlay title-1 padding-top">'.esc_html($left_text).'</h2>
						<h2 class="title-overlay title-2">'.esc_html($left_text2).'</h2>
						<h3 class="title-overlay title-3 padding-bottom">'.esc_html($left_text3).' </h3>
					</div>
				</div>
		';
	endif;
		echo '
				<div class="banner-right">
					<div class="text-salebox">
						<div class="text-left">
							<div class="sale-box">
								<div class="sale-box-top">
									<h2 class="number">'.esc_html($discount_percent).'</h2>';
									if ( !empty($discount_percent) ) {
										echo '<span class="sup-1">%</span>';
									}else{
										echo '<span class="sup-1"></span>';
									}
		echo '
									<span class="sup-2">'.esc_html($dicount_text1).'</span>
								</div>
								<h2 class="text-sale">'.esc_html($dicount_text2).'</h2>
							</div>
						</div>
						'.wp_kses_post($text_right).'
					</div>
				</div>
			</div>
		';
	if ( !empty($height) ) {
		$custom_css .= sprintf( '.banner-sale-1 {height: %spx;}', esc_attr($height) );
	}
	if ( !empty($image) ) {
		$img = wp_get_attachment_url( $image );
		$custom_css .= sprintf('.banner-sale-1{background-image:url("%s");} .banner-sale-1 .title-box{background-image:url("%s");}', esc_attr( $img ), esc_attr( $img ) );
	}
}elseif ( $style == '4' ) {
	echo '<div class="slz-shortcode text-salebox '.esc_attr($extra_class).'">';
		echo '
			<div class="text-left">
				<div class="sale-box">
					<div class="sale-box-top">
						<h2 class="number">'.esc_html($discount_percent).'</h2>';
						if ( !empty($discount_percent) ) {
							echo '<span class="sup-1">%</span>';
						}else{
							echo '<span class="sup-1"></span>';
						}
		echo '
						<span class="sup-2">'.esc_html($dicount_text1).'</span>
					</div>
					<h2 class="text-sale">'.esc_html($dicount_text2).'</h2>
				</div>
			</div>
		';
		echo '
			'.wp_kses_post($text_right).'
		';
	echo '</div>';
	
}elseif( $style == '' ) {
	echo '<div class="slz-shortcode banner-sale-2 '.esc_html($extra_class).'">';
	if( !empty($left_text) ) :
		echo '
				<div class="banner-left">
					<div class="title-box text-parallax">
						<h2 class="title-overlay title-1">
							<span class="text">'.esc_html($left_text).'</span>
						</h2>
					</div>
				</div>
		';
	endif;
		echo '
				<div class="banner-right">
					<div class="text-salebox">
						<div class="text-left">
							<div class="sale-box">
								<div class="sale-box-top">
									<h2 class="number">'.esc_html($discount_percent).'</h2>';
									if ( !empty($discount_percent) ) {
										echo '<span class="sup-1">%</span>';
									}else{
										echo '<span class="sup-1"></span>';
									}
		echo '
									<span class="sup-2">'.esc_html($dicount_text1).'</span>
								</div>
								<h2 class="text-sale">'.esc_html($dicount_text2).'</h2>
							</div>
						</div>
						'.wp_kses_post( $text_right ).'
					</div>
				</div>';
	echo '</div>';
	if ( !empty($height) ) {
		$custom_css .= sprintf( '.banner-sale-2 {height: %spx;}', esc_attr($height) );
	}
	if ( !empty($image) ) {
		$img = wp_get_attachment_url( $image );
		$custom_css .= sprintf('.banner-sale-2{background-image:url("%s");}', esc_attr( $img ) );
	}
}
if ( !empty($custom_css) ) {
	do_action( 'slzexploore_core_add_inline_style', $custom_css );
}