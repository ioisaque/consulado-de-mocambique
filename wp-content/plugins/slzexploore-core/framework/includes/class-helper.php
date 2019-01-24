<?php
/**
 * Helper class
 *
 */
class Slzexploore_Core_Helper {
	/**
	 * Regenerate old image width new image sizes
	 *
	 * @param int $attachment_id Attachment Id to process.
	 * @param string $file Filepath of the Attached image.
	 */
	public function regenerate_attachment_sizes( $attachment_id, $thumb_size ) {
		if( empty( $attachment_id ) ) return;
		if( function_exists('image_get_intermediate_size' ) && function_exists( 'file_is_displayable_image' ) ) {
			//check size exists
			if ( image_get_intermediate_size($attachment_id, $thumb_size) ) {
				return;
			}
			// thumb size is not exists
			$file = get_attached_file( $attachment_id );
			$attachment = get_post( $attachment_id );

			$metadata = array ();
			if ( preg_match( '!^image/!', get_post_mime_type( $attachment ) ) && file_is_displayable_image( $file ) ) {
				$imagesize = getimagesize( $file );
				$metadata ['width'] = $imagesize [0];
				$metadata ['height'] = $imagesize [1];
				list ( $uwidth, $uheight ) = wp_constrain_dimensions( $metadata ['width'], $metadata ['height'], 128, 96 );
				$metadata ['hwstring_small'] = "height='$uheight' width='$uwidth'";
			
				// Make the file path relative to the upload dir
				$metadata ['file'] = _wp_relative_upload_path( $file );
			
				// make thumbnails and other intermediate sizes
				global $_wp_additional_image_sizes;
			
				foreach ( get_intermediate_image_sizes() as $s ) {
					$sizes [$s] = array (
						'width' => '',
						'height' => '',
						'crop' => FALSE
					);
					if ( isset( $_wp_additional_image_sizes [$s] ['width'] ) ) {
						// For theme-added sizes
						$sizes [$s] ['width'] = intval( $_wp_additional_image_sizes [$s] ['width'] );
					} else {
						// For default sizes set in options
						$sizes [$s] ['width'] = get_option( "{$s}_size_w" );
					}
					if ( isset( $_wp_additional_image_sizes [$s] ['height'] ) ) {
						// For theme-added sizes
						$sizes [$s] ['height'] = intval( $_wp_additional_image_sizes [$s] ['height'] );
					} else {
						// For default sizes set in options
						$sizes [$s] ['height'] = get_option( "{$s}_size_h" );
					}
					if ( isset( $_wp_additional_image_sizes [$s] ['crop'] ) ) {
						// For theme-added sizes
						$sizes [$s] ['crop'] = intval( $_wp_additional_image_sizes [$s] ['crop'] );
					} else {
						// For default sizes set in options
						$sizes [$s] ['crop'] = get_option( "{$s}_crop" );
					}
				}
			
				$sizes = apply_filters( 'intermediate_image_sizes_advanced', $sizes );
			
				// Only generate image if it does not already exist
				$attachment_meta = wp_get_attachment_metadata( $attachment_id );
			
				foreach ( $sizes as $size => $size_data ) {
					if ( isset( $attachment_meta ['sizes'] [$size] ) ) {
						// Size already exists
						$metadata ['sizes'] [$size] = $attachment_meta ['sizes'] [$size];
					} else {
						// Generate new image
						$resized = image_make_intermediate_size( $file, $size_data ['width'], $size_data ['height'], $size_data ['crop'] );
						if ( $resized ) {
							$metadata ['sizes'] [$size] = $resized;
						}
					}
				}
			
				if ( $attachment_meta ['image_meta'] ) {
					$metadata ['image_meta'] = $attachment_meta ['image_meta'];
				}
				$attachment_metadata = apply_filters( 'wp_generate_attachment_metadata', $metadata, $attachment_id );
				wp_update_attachment_metadata( $attachment_id, $attachment_metadata );
			}
		}
	}
}