<?php
if( is_search() ) return;
$post_id = get_the_ID();

if( $post_id ) {
	$slider_page_settings = get_post_meta( $post_id, 'slzexploore_page_options', true );
	if ( $slider_page_settings ){
		$header_style = Slzexploore::get_option( 'slz-style-header');
		if ( $header_style == 'one' ){
			$header_class = 'homepage-default';
		}elseif ($header_style == 'two') {
			$header_class = 'homepage-02';
		}elseif ($header_style == 'three') {
			$header_class = 'homepage-03';
		}elseif ($header_style == 'four'){
			$header_class = 'homepage-04';
		}else{
			$header_class = 'homepage-05';
		}
		$header_type  = Slzexploore::get_option('slz-header-content-type');
		if ( $header_type == '1'){
			$slider = Slzexploore::get_option('slz-header-slider');
			if ( !empty( $slider ) ){
				echo '<div class="rev-container">'.do_shortcode( '[rev_slider_vc alias="'.($slider).'"]' ).'</div>';
			}
		}else if( $header_type == '2'){
			$video_html = $button = $video_btn_content = '';
			$caption_1  =  Slzexploore::get_option('slz-header-caption-1');
			$caption_2  =  Slzexploore::get_option('slz-header-caption-2');
			$header_button  =  Slzexploore::get_option('slz-header-button');
			$header_button_hover  =  Slzexploore::get_option('slz-header-button-hover');
			$header_button_link = Slzexploore::get_option('slz-header-button-link');
			$caption_fontsize_1  =  Slzexploore::get_option('slz-header-caption-fontsize-1');
			$caption_fontsize_2  =  Slzexploore::get_option('slz-header-caption-fontsize-1');
			$show_search_form  =  Slzexploore::get_option('slz-header-search-form');
			$video  =  Slzexploore::get_option('slz-header-bg-video');
			$bg_image_id  =  Slzexploore::get_option('slz-header-bg-image');
			$header_bg_image  = wp_get_attachment_image( $bg_image_id, 'full', false, array('class' => 'img-responsive') );
			$logo_home4 = Slzexploore::get_option('slz-logo-header-04','url');
			if (!empty($bg_image_id )){
				$custom_css = '.main-content .page-banner-2.homepage-02,.main-content .page-banner.homepage-default,.main-content .page-banner-2.homepage-03{background-image: url('.wp_get_attachment_url($bg_image_id).');}';
				if( $custom_css ) {
					do_action( 'slzexploore_add_inline_style', $custom_css );
				}
			}
			if ( !empty($video) ){
				$video_html =  '
					<div class="homepage-hero-module">
						<div class="video-container">
							<div class="filter">
								<video autoplay="" loop="" controls="controls" muted="muted" class="fillWidth">
									<source src="'.$video.'" type="video/mp4">
								</video>
							</div>
						</div>
					</div>
				';
				$video_btn_content =''.
				'<div class="btn-video">
					<div class="btn-click btn-play show-video"><i class="icons fa fa-play"></i></div>
					<div class="btn-click btn-pause "><i class="icons fa fa-pause"></i></div>
				</div>';
			}
			
			if (!empty($header_button)){
				$button  = ''.
				'<div class="group-btn">
					<a href="'.esc_url($header_button_link).'" data-hover="'.esc_attr($header_button_hover).'" class="btn-click"><span class="text">'.esc_html($header_button).'</span>
						<span class="icons fa fa-long-arrow-right"></span>
					</a>
				</div>';
			}
			/*layout*/
			if ( $header_class =='homepage-02'){
				echo '<section  class="page-banner-2 homepage-02">';
					echo '<div class="container"><div class="row"><div class="col-md-6">
							<div class="group-title">
									<h1 class="banner">'.esc_html($caption_1).'</h1>
									<h4 class="sub-banner">'.esc_html($caption_2).'</h4>
								</div>'.$button;
							if (!empty($show_search_form)){
								echo do_shortcode('[slzexploore_core_search_sc layout="02"]');
							}
					echo '</div></div></div>';
				echo '</section>';
			}else if ( $header_class =='homepage-03'){
				echo '<section  class="page-banner-2 homepage-03">';
					echo '<div class="container">
							<div class="group-title">
								<h1 class="banner">'.esc_html($caption_1).'</h1>
								<h4 class="sub-banner">'.esc_html($caption_2).'</h4>
							</div>'.$button;
							if (!empty($show_search_form)){
								echo do_shortcode('[slzexploore_core_search_sc layout="03"]');
							}
					echo '</div>';
				echo '</section>';
			}else if ( $header_class =='homepage-04'){
				echo '<section  class="page-banner homepage-04">
					'.$video_html.'
						<div class="homepage-banner-warpper">
							<div class="homepage-banner-content">
								<div class="bg-image show-video">'.$header_bg_image .'</div>';
								echo '<div class="group-logo">';
								if (!empty($logo_home4)){
									echo '<img src="'.esc_url($logo_home4).'" alt="logo" class="img-logo">';
								}
								echo '</div>';
								echo '<div class="group-title">
											<h1 class="banner title">'.esc_html($caption_1).'</h1>
											<h4 class="sub-banner text">'.esc_html($caption_2).'</h4>
										</div>'.$button.$video_btn_content;  
						echo '</div></div>';
				echo '</section>';
				
			}else{
				echo '<section  class="page-banner homepage-default"><div class="homepage-banner-warpper"><div class="homepage-banner-content">';
					echo '<div class="group-title">
							<h1 class="banner title">'.esc_html($caption_1).'</h1>
							<h4 class="sub-banner">'.esc_html($caption_2).'<span class="boder"></span></h4>
						</div>'.$button;
				echo '</div></div></section>';
				if (!empty($show_search_form)){
					//echo '<section class="slz-header-sc">';
					//echo do_shortcode('[slzexploore_core_search_sc]');
					//echo '</section>';
				}

			}
		}
	}
}