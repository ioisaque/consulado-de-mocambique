<?php
/**
 * FAQs Controller
 * 
 * @since 1.0
 */
Slzexploore_Core::load_class( 'Abstract' );
class Slzexploore_Core_Faq_Controller extends Slzexploore_Core_Abstract {
	public function save() {
		global $post;
		$post_id = $post->ID;
		parent::save();
		if( isset( $_POST['slzexploore_faq_meta']) ) {
			$data_meta = $_POST['slzexploore_faq_meta'];
			foreach( $data_meta as $key => $value ) {
				update_post_meta ( $post_id, $key, $value );
			}
		}
	}

	public function metabox_faq_options() {
		global $post;
		$post_id = $post->ID;
		$obj_prop = new Slzexploore_Core_Faq();
		$obj_prop->loop_index();
		$data_meta = $obj_prop->post_meta;
		$this->render( 'faq', array( 'data_meta' => $data_meta ) );
	}

}