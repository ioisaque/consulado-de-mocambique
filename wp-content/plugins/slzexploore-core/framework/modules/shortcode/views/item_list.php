<?php 
$list = '';
$icon = '';
$url_arr = '';
$custom_css = '';
$link = '';
$target_link = '';
$title_link = '';

if( !empty($list_item_one) ) {
	$list = urldecode($list_item_one);
	$list = json_decode($list);
}elseif( !empty($list_item_two) ) {
	$list = urldecode($list_item_two);
	$list = json_decode($list);
}elseif ( !empty($list_item_three) ) {
	$list = urldecode($list_item_three);
	$list = json_decode($list);
}elseif ( !empty($list_item_four) ) {
	$list = urldecode($list_item_four);
	$list = json_decode($list);
}elseif ( !empty($list_item_six) ) {
	$list = urldecode($list_item_six);
	$list = json_decode($list);
}

if ( $style_icon == '3' ) {

	if( !empty($color_hover_title) ){
		$custom_css .= sprintf('.item-list-%s .content-tours .wrapper-thin:hover .tours-title{color:%s;} .content-tours .wrapper-thin:hover .icon-thin{color:%s}', esc_attr($id), esc_attr( $color_hover_title ), esc_attr( $color_hover_title ));
	}
	if ( !empty($item_color) ) {
		$custom_css .= sprintf( '.item-list-%s .content-tours{color: %s}', esc_attr($id), esc_attr($item_color) );
	}

	echo '<div class="slz-shortcode about-tours item-list-style06 item-list-'.esc_attr($id).' '.esc_attr($extra_class).'"><div class="wrapper-tours"><div class="content-icon-tours">';
	if( !empty($list) ):
		foreach ( $list as $data ) {
				echo '<div class="content-tours">';

				if( !empty($data->icon_type) && $data->icon_type == '02' && !empty($data->icon_fw) ) {
					echo '<i class="icon '.esc_attr($data->icon_fw).'"></i>';
				}elseif( empty($data->icon_type) && !empty($data->icon_ex) ) {
					echo '<i class="icon '.esc_attr($data->icon_ex).'"></i>';
				}
					echo '
						<div class="wrapper-thin">
							<span class="wrapper-icon-thin">
								<i class="icon-thin fa fa-circle-thin"></i>
							</span>';
				if ( !empty( $data->info_one ) ) {
					echo '<div class="tours-title">';
					if ( !empty($data->url) ){
						$url_arr = Slzexploore_Core_Util::get_link( $data->url );
						echo '<a href="'.esc_url($url_arr['link']).'" '.esc_attr($url_arr['target']).' '.esc_attr($url_arr['url_title']).'>';
					}
					echo esc_html($data->info_one);
					if ( !empty($data->url) ){
						echo '</a>';
					}
					echo '</div>';
				}
				echo '
						</div>';
				if ( !empty($data->info_two) ) {
					echo '<div class="text">'.esc_html($data->info_two).'</div>';
				}
				echo '
					</div>
				';
		}// end foreach
	endif;
	echo '</div></div></div>';
}elseif ( $style_icon == '4' ) {
	if ( !empty($item_color) ) {
		$custom_css = sprintf( '.item-list-%s .feature-item{color:%s; border: 1px solid %s;}', esc_attr($id), esc_attr($item_color), esc_attr($item_color) );
	}
	if ( !empty($list) ) {
		if ( count($list) < $number_show ) {
			$number_show = count($list);
		}
	}
	echo '<div class="slz-shortcode item-list-style06 item-list-'.esc_attr($id).' wrapper-journey '.esc_attr($extra_class).'" data-item='.esc_attr($number_show).'>';
	if( !empty($list) ):
		foreach ($list as $data ) {
			echo '<div class="item feature-item">';
			if ( !empty($data->url) ){
				$url_arr = Slzexploore_Core_Util::get_link( $data->url );
				echo '<a href="'.esc_url($url_arr['link']).'" '.esc_attr($url_arr['target']).' '.esc_attr($url_arr['url_title']).'>';
			}

			if( !empty($data->icon_type) && $data->icon_type == '02' && !empty($data->icon_fw) ) {
				echo '<i class="icon-journey '.esc_attr($data->icon_fw).'"></i>';
			}elseif( empty($data->icon_type) && !empty($data->icon_ex) ) {
				echo '<i class="icon-journey '.esc_attr($data->icon_ex).'"></i>';
			}

			if ( !empty($data->info_main) ) {
				echo '<div class="text">'.esc_html($data->info_main).'</div>';
			}
			if ( !empty($data->url) ){
				echo '</a>';
			}
			echo '</div>';
		}// end foreach
	endif;
	echo '</div>';
}elseif ( $style_icon == '5' ) {

	if( !empty($color_hover_bg) ){
		$custom_css .= sprintf('.item-list-%s .list-continent-wrapper .continent:before{background-color:%s;}', esc_attr($id), esc_attr( $color_hover_bg ));
	}
	if ( !empty($color_bg) ) {
		$custom_css .= sprintf( '.item-list-%s .list-continent-wrapper .continent{background-color:%s;}', esc_attr($id), esc_attr($color_bg) );
	}
	if ( !empty($color_border) ) {
		$custom_css .= sprintf( '.item-list-%s .list-continent-wrapper .continent{border: 1px solid %s;}', esc_attr($id), esc_attr($color_border) );
	}
	if ( !empty($color_text) ) {
		$custom_css .= sprintf( '.item-list-%s .list-continent-wrapper .continent{color: %s;} ', esc_attr($id), esc_attr($color_text) );
	}
	if ( !empty($color_line) ) {
		$custom_css .= sprintf( '.item-list-%s .list-continent-wrapper .continent::after{border-top: 1px dashed %s;}', esc_attr($id), esc_attr($color_line) );
	}

	echo '<div class="slz-shortcode list-continents item-list-style06 item-list-'.esc_attr($id).' '.esc_attr($extra_class).'">';
	if( !empty($list) ):
		foreach ($list as $data) {
			if ( !empty($data->url) ){
				$url_arr = Slzexploore_Core_Util::get_link( $data->url );
				$link = $url_arr['link'];
			}
			if ( !empty($url_arr['target']) ) {
				$target_link = $url_arr['target'];
			}
			if ( !empty($url_arr['url_title']) ) {
				$title_link = $url_arr['url_title'];
			}
			echo '<div class="list-continent-wrapper">';

			if( !empty($data->icon_type) && $data->icon_type == '02' && !empty($data->icon_fw) ) {
				if( !empty( $data->url ) ) {
					echo '<a href="'.esc_attr($link).'" '.esc_attr($target_link).' '.esc_attr($title_link).' class="continent">';
				}else{
					echo '<div class="continent">';
				}
				echo '<i class="icons '.esc_attr($data->icon_fw).'"></i>';
				if ( !empty($data->info_main) ) {
					echo '<span class="text">'.esc_html($data->info_main).'</span>';
				}
				if ( !empty( $data->url ) ) {
					echo '</a>';
				}else{
					echo '</div>';
				}
				
			}elseif( empty($data->icon_type) && !empty($data->icon_ex) ) {
				if( !empty( $data->url ) ) {
					echo '<a href="'.esc_attr($link).'" '.esc_attr($target_link).' '.esc_attr($title_link).' class="continent">';
				}else{
					echo '<div class="continent">';
				}
				echo '<i class="icons '.esc_html($data->icon_ex).'"></i>';
				if ( !empty($data->info_main) ) {
					echo '<span class="text">'.esc_html($data->info_main).'</span>';
				}
				if ( !empty( $data->url ) ) {
					echo '</a>';
				}else{
					echo '</div>';
				}
			}
			echo '</div>';
		}//end foreach
	endif;
	echo '</div>';
} elseif ( $style_icon == '6' ) {
	if ( !empty( $color_title ) ) {
		$custom_css .= sprintf( '.item-list-%s .wrapper-car-result .car-wigdet .car-item span { color:%s; }', esc_attr($id), esc_attr($color_title) );
	}
	if ( !empty( $color_hover_title ) ) {
		$custom_css .= sprintf( '.item-list-%s .wrapper-car-result .car-wigdet .car-item:hover span { color:%s; }', esc_attr($id), esc_attr($color_hover_title) );
	}
	if ( !empty( $item_color ) ) {
		$custom_css .= sprintf( '.item-list-%s .wrapper-car-result .car-wigdet .car-icon { color:%s; }', esc_attr($id), esc_attr($item_color) );
	}
	if ( !empty( $item_color_hv ) ) {
		$custom_css .= sprintf( '.item-list-%s .wrapper-car-result .car-wigdet .car-item:hover > .car-icon { color:%s; }', esc_attr($id), esc_attr($item_color_hv) );
	}
	echo '<div class="slz-shortcode sc_item_list item-list-style06 item-list-'.esc_attr($id).' '.esc_attr($extra_class).'" ><div class="wrapper-car-result"><ul class="car-wigdet list-inline list-unstyled">';
	if( !empty( $list) ):
		foreach ( $list as $data ) {
			echo '<li class="wrapper-car-item">'; //open
			if ( !empty($data->url) ){
				$url_arr = Slzexploore_Core_Util::get_link( $data->url );
				echo '<a href="'.esc_url($url_arr['link']).'" '.esc_attr($url_arr['target']).' '.esc_attr($url_arr['url_title']).' class="car-item">';
			}
			if( !empty($data->icon_type) && $data->icon_type == '02' && !empty($data->icon_fw) ) {
				echo '<i class="car-icon '.esc_attr($data->icon_fw).'"></i>';
			}elseif( empty($data->icon_type) && !empty($data->icon_ex) ) {
				echo '<i class="car-icon '.esc_attr($data->icon_ex).'"></i>';
			}
			if ( !empty( $data->info_main ) ) {
				echo '<span>'.esc_html($data->info_main).'</span>';
			}
			if ( !empty($data->url) ){
				echo '</a>';
			}
			echo '</li>'; //close
		}// end foreach
	endif;
	echo '</ul></div></div>';
} else { // $style_icon = 1,2
	if ( $style_icon == '2' ){
		echo '<div class="slz-shortcode a-fact-list">
				<div class="group-list '.esc_attr($extra_class).'">
					<ul class="list-unstyled">';
	}
	else{
		echo '<div class="slz-shortcode group-list '.esc_attr($extra_class).'">
				<ul class="list-unstyled about-us-list">';
	}
	if( !empty($list) ):
		foreach ( $list as $data ) {
			if ( !empty($data->info_main) ) {
				echo '<li><p class="text">';
				if ( !empty($data->url) ){
					$url_arr = Slzexploore_Core_Util::get_link( $data->url );
					echo '<a href="'.esc_url($url_arr['link']).'" '.esc_attr($url_arr['target']).' '.esc_attr($url_arr['url_title']).'>';
				}
				echo esc_html($data->info_main);
				if ( !empty($data->url) ){
					echo '</a>';
				}
				echo '</p></li>';
			}
		}// end foreach
	endif;
	if ( $style_icon == '2' ){
		echo '</ul></div></div>';
	}
	else{
		echo '</ul></div>';
	}
}

if ( !empty($custom_css) ) {
	do_action( 'slzexploore_core_add_inline_style', $custom_css );
}
?>