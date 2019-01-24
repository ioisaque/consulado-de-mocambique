<?php
/**
 * Controller Testimonial class.
 * 
 * @since 1.0
 */

Slzexploore_Core::load_class( 'Abstract' );

class Slzexploore_Core_Testimonial_Controller extends Slzexploore_Core_Abstract {

	public function save() {
		global $post;
		$post_id = $post->ID;
		parent::save();
		if( isset( $_POST['slzexploore_testi_meta']) ) {
			$data_meta = $_POST['slzexploore_testi_meta'];
			foreach( $data_meta as $key => $value ) {
				update_post_meta ( $post_id, $key, $value );
			}
		}
	}

	public function meta_box_option() {
		global $post;
		$post_id = $post->ID;
		
		$obj_prop = new Slzexploore_Core_Testimonial();
		$obj_prop->loop_index();
		$data_meta = $obj_prop->post_meta;
		
		$args = array(
			'post_type'        => 'slzexploore_testi',
		);
		
		$this->render( 'testimonial',
				array(
					'data_meta' => $data_meta,
				)
		);
	}
}