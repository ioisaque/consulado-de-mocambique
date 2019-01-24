<?php
/**
 * Room Controller
 * 
 * @since 1.0
 */
Slzexploore_Core::load_class( 'Abstract' );
class Slzexploore_Core_Room_Controller extends Slzexploore_Core_Abstract {
	public function save() {
		global $post;
		$post_id = $post->ID;
		parent::save();
		if( isset( $_POST['slzexploore_room_meta']) ) {
			$data_meta = $_POST['slzexploore_room_meta'];
			if( empty( $data_meta['slzexploore_room_display_title'] ) ) {
				$data_meta['slzexploore_room_display_title'] = $post->post_title;
			}

			// update slzexploore_hotel_room_type meta
			$old_accommodation_id = get_post_meta ( $post_id, 'slzexploore_room_accommodation', true );
			$meta_key = 'slzexploore_hotel_room_type';
			// clear room type of old accommodation
			if( !empty( $old_accommodation_id ) ) {
				$old_room_type = get_post_meta ( $old_accommodation_id, $meta_key, true );
				$old_room_type = str_replace( $post_id . ',', '', $old_room_type);
				update_post_meta ( $old_accommodation_id, $meta_key, $old_room_type );
			}
			// add room type of new accommodation
			if( !empty( $data_meta['slzexploore_room_accommodation'] ) ) {
				$accommodation_id = $data_meta['slzexploore_room_accommodation'];
				$room_type = get_post_meta ( $accommodation_id, $meta_key, true );
				$room_type .= $post_id . ',';
				update_post_meta ( $accommodation_id, $meta_key, $room_type );
			}

			// format number
			$data_meta['slzexploore_room_price']  = Slzexploore_Core_Format::format_number( $data_meta['slzexploore_room_price'] );
			$data_meta['slzexploore_room_price_infant']  = Slzexploore_Core_Format::format_number( $data_meta['slzexploore_room_price_infant'] );
			$data_meta['slzexploore_room_max_adults']  = number_format_i18n( $data_meta['slzexploore_room_max_adults'] );
			$data_meta['slzexploore_room_max_children']  = number_format_i18n( $data_meta['slzexploore_room_max_children'] );
			$data_meta['slzexploore_room_number_room']  = number_format_i18n( $data_meta['slzexploore_room_number_room'] );

			foreach( $data_meta as $key => $value ) {
				update_post_meta ( $post_id, $key, $value );
			}
			// set post term
			wp_set_post_terms( $post_id, $data_meta['slzexploore_room_status'], 'slzexploore_room_status' );
		}
	}

	public function mbox_room_options() {
		global $post;
		$post_id = $post->ID;
		$obj_prop = new Slzexploore_Core_Room();
		$obj_prop->loop_index();
		$data_meta = $obj_prop->post_meta;

		$options = array('empty' => esc_html__( '-- None --', 'slzexploore-core' ) );
		$args = array( 'hide_empty' => false, 'orderby' => 'term_id' );
		$status = Slzexploore_Core_Com::get_tax_options_id2name( 'slzexploore_room_status', $options, $args );
		$params = array( 'status' => $status );

		$this->render( 'room', array( 'data_meta' => $data_meta, 'params' => $params ) );
	}
}