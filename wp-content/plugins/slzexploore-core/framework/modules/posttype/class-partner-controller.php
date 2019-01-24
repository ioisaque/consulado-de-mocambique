<?php
/**
 * Partner Controller
 * 
 * @since 1.0
 */
Slzexploore_Core::load_class( 'Abstract' );
class Slzexploore_Core_Partner_Controller extends Slzexploore_Core_Abstract {
	public function save() {
		global $post;
		$post_id = $post->ID;
		parent::save();
		if( isset( $_POST['slzexploore_partner_meta']) ) {
			$data_meta = $_POST['slzexploore_partner_meta'];
			foreach( $data_meta as $key => $value ) {
				update_post_meta ( $post_id, $key, $value );
			}
		}
	}

	public function meta_box_url() {
		$obj_prop = new Slzexploore_Core_Partner();
		$obj_prop->loop_index();
		$post_meta = $obj_prop->post_meta;
		$this->render( 'partner', array( 'post_meta' => $post_meta ) );
	}

	public function save_gallery() {
		global $post;
		$post_id = $post->ID;
		parent::save();
		if( isset( $_POST['slzexploore_gallery_meta']) ) {
			$data_meta = $_POST['slzexploore_gallery_meta'];
			foreach( $data_meta as $key => $value ) {
				update_post_meta ( $post_id, $key, $value );
			}
		}
	}

	public function meta_box_gallery() {
		$obj_prop = new Slzexploore_Core_Gallery();
		$obj_prop->loop_index();
		$post_meta = $obj_prop->post_meta;
		$this->render( 'gallery', array( 'post_meta' => $post_meta ) );
	}
}