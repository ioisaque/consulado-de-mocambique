<?php
$post_id = get_the_ID();
$post_type = get_post_type();
$posttype_support_header_content = array( 'slzexploore_tour', 'slzexploore_hotel', 'slzexploore_car', 'slzexploore_cruise' );
$rendered = false; // render flag

if ( $post_id && in_array( $post_type, $posttype_support_header_content ) ) {
	// case have post with post type is hotel, tour, car, cruise
	$posttype_header_options = get_post_meta( $post_id, 'slzexploore_posttype_header_options', true );

	if ( $posttype_header_options ) {
		// case have post header options
		$header_type = Slzexploore::get_value( $posttype_header_options, 'posttype_header_content_type' );

		switch ( $header_type ) {
			case 1:
				// case use revolution slider
				$slider = Slzexploore::get_value( $posttype_header_options, 'posttype_header_revolution_slider' );

				if ( ! empty( $slider ) ) {
					// case have choose slider
					$content = sprintf( '<div class="rev-container">[rev_slider_vc alias="%s"]</div>', esc_html( $slider ) );
					echo apply_filters( 'the_content', $content );
					$rendered = true;
				}
				break;
			case 2:
				// case use custom slider
				$slider_speed = Slzexploore::get_value( $posttype_header_options, 'posttype_header_slider_speed' );
				$slider_speed = ! empty( $slider_speed ) ? abs( intval( $slider_speed ) ) : 300;
				$slick_data = json_encode( array(
					'slidesToShow'   => 1,
					'slidesToScroll' => 1,
					'dots'           => true,
					'arrows'         => true,
					'autoplay'       => true,
					'infinite'       => true,
					'speed'          => $slider_speed,
					'fade'           => false,
				) );
				$height     = Slzexploore::get_value( $posttype_header_options, 'posttype_header_height' );
				$height     = ! empty( $height ) ? $height : 'inherit';
				$image_ids  = Slzexploore::get_value( $posttype_header_options, 'posttype_header_gallery_ids' );
				$image_ids  = explode( ',', $image_ids );
				$items      = '';

				foreach ( $image_ids as $id ) {
					$thumbnail = wp_get_attachment_image_src( intval( $id ), 'full' );
					if ( $thumbnail ) {
						$items .= sprintf( '<div class="items"><img src="%s" alt=""></div>', $thumbnail[0] );
					}
				}

				if ( ! empty( $items ) ) {
					echo sprintf( '<div class="wrapper-custom-slider" style="height: %s">', $height );
					printf( '<div class="posttype-header-gallery" data-slick="%2$s" >%1$s</div>', wp_kses_post( $items ), esc_attr( $slick_data ) );
					do_action( 'slzexploore_show_page_title' );
					echo '</div>';
					$rendered = true;
				}
				break;
		}
	}
}

if ( ! $rendered ) {
	// case default - show default page title
	do_action( 'slzexploore_show_page_title' );
}