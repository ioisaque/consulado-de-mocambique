<?php
/**
 * Controller Post.
 * 
 * @since 1.0
 */

Slzexploore_Core::load_class( 'Abstract' );

class Slzexploore_Core_Post_Controller extends Slzexploore_Core_Abstract {

	public function metabox_feature_video() {
		global $post;
		$post_id = $post->ID;
		$post_meta = array();
		if( $post_id ) {
			$post_meta = get_post_meta( $post_id, 'slzexploore_feature_video', true );
		}
		$this->render( 'feature-video', array(
			'post_meta' => $post_meta
		));
	}
}