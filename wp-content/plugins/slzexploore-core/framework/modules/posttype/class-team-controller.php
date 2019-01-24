<?php
/**
 * Controller Travel Guide class.
 * 
 * @since 1.0
 */

Slzexploore_Core::load_class( 'Abstract' );

class Slzexploore_Core_Team_Controller extends Slzexploore_Core_Abstract {

	public function save() {
		global $post;
		$post_id = $post->ID;
		parent::save();
		if( isset( $_POST['slzexploore_team_meta']) ) {
			$data_meta = $_POST['slzexploore_team_meta'];
			foreach( $data_meta as $key => $value ) {
				update_post_meta ( $post_id, $key, $value );
			}
		}
		do_action( SLZEXPLOORE_CORE_THEME_PREFIX .'_save_page', $post_id );
	}

	public function meta_box_option() {
		global $post;
		$post_id = $post->ID;
		
		$obj_prop = new Slzexploore_Core_Team();
		$obj_prop->loop_index();
		$data_meta = $obj_prop->post_meta;
		$args = array(
			'post_type'        => 'slzexploore_team',
		);
		
		$this->render( 'team',
				array(
					'data_meta' => $data_meta,
				)
		);
	}
}