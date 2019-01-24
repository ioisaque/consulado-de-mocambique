<?php
/**
 * Car Controller
 * 
 * @since 1.0
 */
Slzexploore_Core::load_class( 'Abstract' );
class Slzexploore_Core_Car_Controller extends Slzexploore_Core_Abstract {

	public function save() {
		global $post;
		$post_id = $post->ID;
		parent::save();
		if( isset( $_POST['slzexploore_car_meta']) ) {
			$data_meta = $_POST['slzexploore_car_meta'];
			$prefix = 'slzexploore_car_';
			if( empty( $data_meta[$prefix.'display_title'] ) ) {
				$data_meta[$prefix.'display_title'] = $post->post_title;
			}

			$data_meta[$prefix.'price']          = Slzexploore_Core_Format::format_number( $data_meta[$prefix.'price'] );
			$data_meta[$prefix.'deposit_amount'] = Slzexploore_Core_Format::format_number( $data_meta[$prefix.'deposit_amount'] );
			$data_meta[$prefix.'number']      = number_format_i18n( $data_meta[$prefix.'number'] );
			$data_meta[$prefix.'max_people']  = number_format_i18n( $data_meta[$prefix.'max_people'] );
			if( ! isset( $data_meta[$prefix.'is_discount'] ) ) {
				$data_meta[$prefix.'is_discount'] = '';
				$data_meta[$prefix.'discount_rate'] = '';
				$data_meta[$prefix.'discount_text'] = '';
				$data_meta[$prefix.'discount_start_date'] = '';
				$data_meta[$prefix.'discount_end_date'] = '';
			}
			if( ! isset( $data_meta[$prefix.'hide_is_full'] ) ) {
				$data_meta[$prefix.'hide_is_full'] = '';
			}
			foreach( $data_meta as $key => $value ) {
				update_post_meta ( $post_id, $key, $value );
			}
			
			$rating = get_post_meta ( $post_id, $prefix.'rating', true );
			if( empty( $rating ) ){
				update_post_meta ( $post_id, $prefix.'rating', 0 );
			}
			// set post term
			wp_set_post_terms( $post_id, $data_meta[$prefix.'status'], $prefix.'status' );
		}
		do_action( SLZEXPLOORE_CORE_THEME_PREFIX .'_save_page', $post_id );
	}

	public function mbox_car() {
		global $post;
		$post_id = $post->ID;
		
		$obj_prop = new Slzexploore_Core_Car();
		$obj_prop->loop_index();
		$data_meta = $obj_prop->post_meta;
		
		$options = array('empty' => esc_html__( '-- None --', 'slzexploore-core' ) );
		$args = array( 'hide_empty' => false, 'orderby' => 'term_id' );
		$car_status = Slzexploore_Core_Com::get_tax_options_id2name( 'slzexploore_car_status', $options, $args );
		$params = array( 'car_status' => $car_status );
		
		$this->render( 'car', array(
								'params' => $params,
								'data_meta' => $data_meta,
								'attachment_ids' => $data_meta['attachment_ids']
							));
	}

	public function save_booking() {
		global $post;
		$post_id = $post->ID;
		parent::save();
		if( isset( $_POST['slzexploore_cbook_meta']) ) {
			$data_meta = $_POST['slzexploore_cbook_meta'];
			if( empty( $data_meta['slzexploore_cbook_date_from'] ) ){
				$data_meta['slzexploore_cbook_date_from'] = date( 'Y-m-d' );
			}
			if( empty( $data_meta['slzexploore_cbook_date_to'] ) ){
				$data_meta['slzexploore_cbook_date_to'] = date( 'Y-m-d' );
			}
			foreach( $data_meta as $key => $value ) {
				update_post_meta ( $post_id, $key, $value );
			}
			// set post term
			wp_set_post_terms( $post_id, $data_meta['slzexploore_cbook_status'], 'slzexploore_cbook_status' );
		}
	}

	public function mbox_car_booking() {
		global $post;
		$post_id = $post->ID;
		$obj_prop = new Slzexploore_Core_Car_Booking();
		$obj_prop->loop_index();
		$data_meta = $obj_prop->post_meta;
		
		// Booking Status
		$status_empty   = array( 'empty' => esc_html__( '-- None --', 'slzexploore-core' ) );
		$status_args    = array( 'hide_empty' => false, 'orderby' => 'term_id' );
		$booking_status = Slzexploore_Core_Com::get_tax_options_id2name( 'slzexploore_cbook_status', $status_empty, $status_args );
		
		// Car
		$car_empty   = array('empty' => esc_html__( '-- All Cars --', 'slzexploore-core' ) );
		$car_args    = array('post_type' => 'slzexploore_car');
		$arr_car     = Slzexploore_Core_Com::get_post_id2title( $car_args, $car_empty );
		
		$params      = array(
							'booking_status'   => $booking_status,
							'cars'             => $arr_car
						);
		
		$this->render( 'car_booking', array( 'params' => $params, 'data_meta' => $data_meta ) );
	}
}