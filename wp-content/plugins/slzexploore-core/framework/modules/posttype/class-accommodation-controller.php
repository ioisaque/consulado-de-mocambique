<?php
/**
 * Accommodation Controller
 * 
 * @since 1.0
 */
Slzexploore_Core::load_class( 'Abstract' );
class Slzexploore_Core_Accommodation_Controller extends Slzexploore_Core_Abstract {
	public function save() {
		global $post;
		$post_id = $post->ID;
		parent::save();
		if( isset( $_POST['slzexploore_hotel_meta']) ) {
			$data_meta = $_POST['slzexploore_hotel_meta'];
			$data_meta['slzexploore_hotel_deposit_amount']  = Slzexploore_Core_Format::format_number( $data_meta['slzexploore_hotel_deposit_amount'] );
			// check video data
			if( empty($data_meta['slzexploore_hotel_discount']) ) {
				$data_meta['slzexploore_hotel_discount_rate'] = '';
				$data_meta['slzexploore_hotel_discount_text'] = '';
				$data_meta['slzexploore_hotel_discount_start_date'] = '';
				$data_meta['slzexploore_hotel_discount_end_date'] = '';
			}
			if( empty( $data_meta['slzexploore_hotel_display_title'] ) ) {
				$data_meta['slzexploore_hotel_display_title'] = $post->post_title;
			}

			// format number
			$data_meta['slzexploore_hotel_average_price']  = Slzexploore_Core_Format::format_number( $data_meta['slzexploore_hotel_average_price'] );

			// update accommodation of room type
			$old_room_type = explode( ',', get_post_meta ( $post_id, 'slzexploore_hotel_room_type', true ) );
			$new_room_type = explode( ',', $data_meta['slzexploore_hotel_room_type'] );
			$room_type = array_diff( $old_room_type, $new_room_type );
			if( !empty($room_type) ){
				foreach( $room_type as $room_id ){
					update_post_meta ( $room_id, 'slzexploore_room_accommodation', '' );
				}
			}
			// set checkbox data
			$arr_checkbox = array(
							'slzexploore_hotel_disable_room_type',
							'slzexploore_hotel_is_featured',
							'slzexploore_hotel_discount'
						);
			foreach( $arr_checkbox as $name ) {
				if( !isset( $_POST[$name] ) ) {
					update_post_meta ( $post_id, $name, '' );
				}
			}
			foreach( $data_meta as $key => $value ) {
				update_post_meta ( $post_id, $key, $value );
			}
			// check post meta slzexploore_hotel_rating and slzexploore_hotel_has_vacancy
			$has_vacancy = get_post_meta ( $post_id, 'slzexploore_hotel_has_vacancy', true );
			if( empty( $has_vacancy ) ){
				update_post_meta ( $post_id, 'slzexploore_hotel_has_vacancy', 0 );
			}
			$rating = get_post_meta ( $post_id, 'slzexploore_hotel_rating', true );
			if( empty( $rating ) ){
				update_post_meta ( $post_id, 'slzexploore_hotel_rating', 0 );
			}
			// set post term
			wp_set_post_terms( $post_id, $data_meta['slzexploore_hotel_status'], 'slzexploore_hotel_status' );
		}
		do_action( SLZEXPLOORE_CORE_THEME_PREFIX .'_save_page', $post_id );
	}

	public function mbox_accommodation() {
		global $post;
		$post_id = $post->ID;
		$obj_prop = new Slzexploore_Core_Accommodation();
		$obj_prop->loop_index();
		$data_meta = $obj_prop->post_meta;
		
		$options = array('empty' => esc_html__( '-- None --', 'slzexploore-core' ) );
		$args = array( 'hide_empty' => false, 'orderby' => 'term_id' );
		$status = Slzexploore_Core_Com::get_tax_options_id2name( 'slzexploore_hotel_status', $options, $args );
		$params = array( 'status' => $status );
		
		$this->render( 'accommodation', array( 'data_meta' => $data_meta, 'params' => $params ) );
	}
}