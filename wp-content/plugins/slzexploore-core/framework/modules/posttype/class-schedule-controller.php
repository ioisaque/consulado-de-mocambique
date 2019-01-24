<?php
/**
 * Vacancy Controller
 * 
 * @since 1.0
 */
Slzexploore_Core::load_class( 'Abstract' );
class Slzexploore_Core_Schedule_Controller extends Slzexploore_Core_Abstract {

	public function save() {
		global $post;
		$post_id = $post->ID;
		parent::save();
		if( isset( $_POST['slzexploore_schedule_meta']) ) {
			$data_meta = $_POST['slzexploore_schedule_meta'];
			$prefix = 'slzexploore_schedule_';
			if( empty( $data_meta[$prefix.'start_date'] ) ) {
				$data_meta[$prefix.'start_date'] = current_time('Y-m-d');
			}
			if( empty( $data_meta[$prefix.'end_date'] ) || ( $data_meta[$prefix.'end_date'] < $data_meta[$prefix.'start_date'] ) ) {
				$data_meta[$prefix.'end_date'] = $data_meta[$prefix.'start_date'];
			}
			
			$data_meta[$prefix.'price_adult']  = Slzexploore_Core_Format::format_number( $data_meta[$prefix.'price_adult'] );
			$data_meta[$prefix.'price_child']  = Slzexploore_Core_Format::format_number( $data_meta[$prefix.'price_child'] );

			foreach( $data_meta as $key => $value ) {
				update_post_meta ( $post_id, $key, $value );
			}	
		}
	}

	public function mbox_schedule_options() {
		global $post;
		$post_id = $post->ID;
		$obj_prop = new Slzexploore_Core_Schedule();
		$obj_prop->loop_index();
		$data_meta = $obj_prop->post_meta;
		$this->render( 'schedule', array( 'data_meta' => $data_meta ));
	}
}