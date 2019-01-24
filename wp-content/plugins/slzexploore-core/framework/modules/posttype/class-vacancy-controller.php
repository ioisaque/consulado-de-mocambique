<?php
/**
 * Vacancy Controller
 * 
 * @since 1.0
 */
Slzexploore_Core::load_class( 'Abstract' );
class Slzexploore_Core_Vacancy_Controller extends Slzexploore_Core_Abstract {
	public function save() {
		global $post;
		$post_id = $post->ID;
		parent::save();
		if( isset( $_POST['slzexploore_vacancy_meta']) ) {
			$data_meta = $_POST['slzexploore_vacancy_meta'];
			if( empty( $data_meta['slzexploore_vacancy_date_from'] ) ) {
				$data_meta['slzexploore_vacancy_date_from'] = current_time('Y-m-d');
			}
			$old_accommodation_id = get_post_meta ( $post_id, 'slzexploore_vacancy_accommodation', true );
			$old_room_id = get_post_meta ( $post_id, 'slzexploore_vacancy_room_type', true );
			
			$data_meta['slzexploore_vacancy_price']  = Slzexploore_Core_Format::format_number( $data_meta['slzexploore_vacancy_price'] );

			foreach( $data_meta as $key => $value ) {
				update_post_meta ( $post_id, $key, $value );
			}
			//update meta slzexploore_hotel_has_vacancy
			$args = array(
						'post_type' => 'slzexploore_vacancy',
						'meta_key' => 'slzexploore_vacancy_accommodation',
						'meta_value' => $old_accommodation_id
					);
			$found_post = Slzexploore_Core_Com::get_post_id2title( $args, array(), false );
			if( empty( $found_post ) ) {
				update_post_meta ( $old_accommodation_id, 'slzexploore_hotel_has_vacancy', 0 );
			}
			if( !empty( $data_meta['slzexploore_vacancy_accommodation'] ) ){
				update_post_meta ( $data_meta['slzexploore_vacancy_accommodation'], 'slzexploore_hotel_has_vacancy', 1 );
			}
			if( !empty($old_room_id) ){
				slzexploore_core_upd_room_allow_booking($old_room_id);
			}
			else{
				$old_room_ids  = get_post_meta ( $old_accommodation_id, 'slzexploore_hotel_room_type', true );
				if(!empty($old_room_ids)){
					$old_room_ids = explode(',', trim($old_room_ids, ','));
					foreach($old_room_ids as $id){
						slzexploore_core_upd_room_allow_booking( $id );
					}
				}
			}
			if(!empty($data_meta['slzexploore_vacancy_room_type'])){
				slzexploore_core_upd_room_allow_booking( $data_meta['slzexploore_vacancy_room_type'] );
			}
			else{
				$hotel_id  = $data_meta['slzexploore_vacancy_accommodation'];
				$room_ids  = get_post_meta ( $hotel_id, 'slzexploore_hotel_room_type', true );
				if(!empty($room_ids)){
					$room_ids = explode(',', trim($room_ids, ','));
					foreach($room_ids as $id){
						slzexploore_core_upd_room_allow_booking( $id );
					}
				}
			}
			
		}
	}
	public function before_delete( $post_id ) {
		global $post_type, $vacancy_hotel_id, $vacancy_room_id;
		if( $post_type == 'slzexploore_vacancy' ){
			$vacancy_hotel_id = get_post_meta ( $post_id, 'slzexploore_vacancy_accommodation', true );
			$vacancy_room_id  = get_post_meta ( $post_id, 'slzexploore_vacancy_room_type', true );
		}
	}
	
	public function delete( $post_id ) {
		global $post_type, $vacancy_hotel_id, $vacancy_room_id;
		if( $post_type == 'slzexploore_vacancy' ){
			if( !empty( $vacancy_hotel_id ) ){
				$args = array(
							'post_type' => $post_type,
							'meta_key' => 'slzexploore_vacancy_accommodation',
							'meta_value' => $vacancy_hotel_id
						);
				$found_post = Slzexploore_Core_Com::get_post_id2title( $args, array(), false );
				if( empty( $found_post ) ) {
					update_post_meta ( $vacancy_hotel_id, 'slzexploore_hotel_has_vacancy', 0 );
				}
			}
			if( !empty( $vacancy_room_id ) ){
				slzexploore_core_upd_room_allow_booking( $vacancy_room_id );
			}
			else{
				$room_ids  = get_post_meta ( $vacancy_hotel_id, 'slzexploore_hotel_room_type', true );
				if(!empty($room_ids)){
					$room_ids = explode(',', trim($room_ids, ','));
					foreach($room_ids as $id){
						slzexploore_core_upd_room_allow_booking( $id );
					}
				}
			}
		}
	}

	public function mbox_vacancy_options() {
		global $post;
		$post_id = $post->ID;
		$obj_prop = new Slzexploore_Core_Vacancy();
		$obj_prop->loop_index();
		$data_meta = $obj_prop->post_meta;
		$this->render( 'vacancy', array(
			'data_meta' => $data_meta
		));
	}

	public function ajax_get_room_type(){
		$accommodation_id  = $_POST['params']['accommodation_id'];
		$room_params = array('empty' => esc_html__( '-- All Room Types --', 'slzexploore-core' ) );
		$room_args = array('post_type' => 'slzexploore_room');
		if( !empty( $accommodation_id ) ) {
			$room_args['meta_key'] = 'slzexploore_room_accommodation';
			$room_args['meta_value'] = $accommodation_id;
		}
		$arr_room_type = Slzexploore_Core_Com::get_post_id2title( $room_args, $room_params );
		echo ( $this->drop_down_list('slzexploore_vacancy_meta[slzexploore_vacancy_room_type]',
										'',
										$arr_room_type,
										array('class' => 'slz-block-half') ) );
		exit;
	}
}