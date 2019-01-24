<?php
/**
 * Cruise Controller
 * 
 * @since 1.0
 */
Slzexploore_Core::load_class( 'Abstract' );
class Slzexploore_Core_Cruise_Controller extends Slzexploore_Core_Abstract {
	
	public function save() {
		global $post;
		$post_id = $post->ID;
		parent::save();
		if( isset( $_POST['slzexploore_cruise_meta']) ) {
			$data_meta = $_POST['slzexploore_cruise_meta'];
			$prefix = 'slzexploore_cruise_';

			// format number
			$arr_number = array( 'price_adult', 'price_child', 'discount_rate', 'deposit_amount' );
			foreach( $arr_number as $key ){
				$meta_key = $prefix . $key;
				$data_meta[$meta_key] = Slzexploore_Core_Format::format_number( $data_meta[$meta_key] );
			}
			$data_meta[$prefix.'available_seat'] = number_format_i18n( $data_meta[$prefix.'available_seat'] );

			if( empty( $data_meta[$prefix.'display_title'] ) ) {
				$data_meta[$prefix.'display_title'] = $post->post_title;
			}

			// set empty value for checkbox
			$chk_fields = array( 'date_type', 'is_discount', 'show_gallery', 'show_cabin_type', 'is_featured' ,'hide_is_full');
			foreach( $chk_fields as $field ) {
				if( ! isset( $data_meta[$prefix.$field] ) ) {
					$data_meta[$prefix.$field] = '';
				}
			}

			// set end_date = startdate if startdate > end_date
			$start_date = $data_meta[$prefix.'start_date'];
			$end_date = $data_meta[$prefix.'end_date'];
			if( !empty($start_date) && !empty($end_date) && !Slzexploore_Core_Format::compare_date( $start_date, $end_date ) ){
				$data_meta[$prefix.'end_date'] = $start_date;
			}

			// set discount rate and text empty if is_discount is false
			if( empty( $data_meta[$prefix.'is_discount'] ) ){
				$data_meta[$prefix.'discount_rate'] = '';
				$data_meta[$prefix.'discount_text'] = '';
			}

			// set empty value
			if( empty( $data_meta[$prefix.'date_type'] ) ){
				$data_meta[$prefix.'frequency'] = 'weekly';
				$data_meta[$prefix.'weekly'] = '1';
				$data_meta[$prefix.'monthly'] = '';
				$data_meta[$prefix.'start_date'] = '';
				$data_meta[$prefix.'end_date'] = '';
			}
			else{
				if( $data_meta[$prefix.'frequency'] == 'monthly' ){
					if(!empty($data_meta[$prefix.'monthly'])){
						$data_meta[$prefix.'monthly'] = implode(',', $data_meta[$prefix.'monthly']);
					}
					$data_meta[$prefix.'weekly'] = '';
					$data_meta[$prefix.'start_date'] = '';
					$data_meta[$prefix.'end_date'] = '';
				}
				elseif( $data_meta[$prefix.'frequency'] == 'weekly' ){
					if(!empty($data_meta[$prefix.'weekly'])){
						$data_meta[$prefix.'weekly'] = implode(',', $data_meta[$prefix.'weekly']);
					}
					$data_meta[$prefix.'monthly'] = '';
					$data_meta[$prefix.'start_date'] = '';
					$data_meta[$prefix.'end_date'] = '';
				}
				else{
					// season or specific day
					if( $data_meta[$prefix.'frequency'] == 'other' ){
						$data_meta[$prefix.'end_date'] = '';
					}
					elseif(empty($data_meta[$prefix.'end_date'])){
						$data_meta[$prefix.'end_date'] = $data_meta[$prefix.'start_date'];
					}
					$data_meta[$prefix.'weekly'] = '';
					$data_meta[$prefix.'monthly'] = '';
				}
			}

			// update cabin type
			$cabin_type = get_post_meta ( $post_id, $prefix.'cabin_type', true );
			$deleted_cabin = explode( ',', str_replace( $data_meta[$prefix.'cabin_type'], '', $cabin_type ) );
			if( !empty( $deleted_cabin ) ){
				foreach( $deleted_cabin as $cabin_id ){
					if( !empty( $cabin_id ) ){
						update_post_meta ( $cabin_id, 'slzexploore_cabin_cruise_id', '' );
					}
				}
			}
			

			foreach( $data_meta as $key => $value ) {
				update_post_meta ( $post_id, $key, $value ); 
			}

			// set post term
			wp_set_post_terms( $post_id, $data_meta[$prefix.'status'], $prefix.'status' );
			
			// add rating
			$rating = get_post_meta ( $post_id, $prefix.'rating', true );
			if( empty( $rating ) ){
				update_post_meta ( $post_id, $prefix.'rating', 0 );
			}
		}
		update_post_meta( $post_id, 'slzexploore_cruise_meta_info', isset( $_POST['slzexploore_cruise_meta_info'] ) ? $_POST['slzexploore_cruise_meta_info'] : '' );
		do_action( SLZEXPLOORE_CORE_THEME_PREFIX .'_save_page', $post_id );
	}

	public function mbox_cruise() {
		global $post;
		$post_id = $post->ID;
		
		$obj_prop = new Slzexploore_Core_Cruise();
		$obj_prop->loop_index();
		$data_meta = $obj_prop->post_meta;
		if(!empty($data_meta['weekly'])){
			$data_meta['weekly'] = explode(',', $data_meta['weekly']);
		}
		if(!empty($data_meta['monthly'])){
			$data_meta['monthly'] = explode(',', $data_meta['monthly']);
		}
		
		$options = array('empty' => esc_html__( '-- None --', 'slzexploore-core' ) );
		$args = array( 'hide_empty' => false, 'orderby' => 'term_id' );
		$cruise_status = Slzexploore_Core_Com::get_tax_options_id2name( 'slzexploore_cruise_status', $options, $args );
		$params = array( 'cruise_status' => $cruise_status );
		if( $post_id ) {
			$info = get_post_meta( $post_id, 'slzexploore_cruise_meta_info', true );
		}
		$this->render( 'cruise',
						array(
							'params'         => $params,
							'data_meta'      => $data_meta,
							'info'           => $info,
							'attachment_ids' => $data_meta['attachment_ids']
						)
		);
	}

	public function save_cabin_type() {
		global $post;
		$post_id = $post->ID;
		parent::save();
		if( isset( $_POST['slzexploore_cabin_meta']) ) {
			$data_meta = $_POST['slzexploore_cabin_meta'];
			$prefix = 'slzexploore_cabin_';
			if( empty( $data_meta[$prefix.'display_title'] ) ) {
				$data_meta[$prefix.'display_title'] = $post->post_title;
			}
			// format value
			$data_meta[$prefix.'price'] = Slzexploore_Core_Format::format_number( $data_meta[$prefix.'price'] );
			$data_meta[$prefix.'price_infant'] = Slzexploore_Core_Format::format_number( $data_meta[$prefix.'price_infant'] );
			$data_meta[$prefix.'number'] = number_format_i18n( $data_meta[$prefix.'number'] );
			$data_meta[$prefix.'max_adults'] = number_format_i18n( $data_meta[$prefix.'max_adults'] );
			$data_meta[$prefix.'max_children'] = number_format_i18n( $data_meta[$prefix.'max_children'] );

			// update slzexploore_cruise_cabin_type meta
			$old_cruise_id = get_post_meta ( $post_id, $prefix.'cruise_id', true );
			$meta_key = 'slzexploore_cruise_cabin_type';
			// clear cabin_type of old cruise
			if( !empty( $old_cruise_id ) ) {
				$old_cruise_cabin_type = get_post_meta ( $old_cruise_id, $meta_key, true );
				$old_cruise_cabin_type = str_replace( $post_id . ',', '', $old_cruise_cabin_type);
				update_post_meta ( $old_cruise_id, $meta_key, $old_cruise_cabin_type );
			}
			// add cabin_type of new cruise
			if( !empty( $data_meta[$prefix.'cruise_id'] ) ) {
				$cruise_id = $data_meta[$prefix.'cruise_id'];
				$new_cabin_type = get_post_meta ( $cruise_id, $meta_key, true );
				$new_cabin_type .= $post_id . ',';
				update_post_meta ( $cruise_id, $meta_key, $new_cabin_type );
			}

			foreach( $data_meta as $key => $value ) {
				update_post_meta ( $post_id, $key, $value );
			}
		}
	}

	public function mbox_cabin_type() {
		global $post;
		$post_id = $post->ID;
		$obj_prop = new Slzexploore_Core_Cabin_Type();
		$obj_prop->loop_index();
		$data_meta = $obj_prop->post_meta;
		
		$options    = array( 'empty' => esc_html__( '-- All Cruises --', 'slzexploore-core' ) );
		$args       = array( 'post_type' => 'slzexploore_cruise' );
		$arr_cruise = Slzexploore_Core_Com::get_post_id2title( $args, $options );
		$params     = array( 'cruises' => $arr_cruise );
		
		$this->render( 'cabin_type',
						array(
							'params' => $params,
							'data_meta' => $data_meta
						)
		);
	}

	public function save_booking() {
		global $post;
		$post_id = $post->ID;
		parent::save();
		if( isset( $_POST['slzexploore_crbook_meta']) ) {
			$data_meta = $_POST['slzexploore_crbook_meta'];
			foreach( $data_meta as $key => $value ) {
				update_post_meta ( $post_id, $key, $value );
			}
			// set post term
			wp_set_post_terms( $post_id, $data_meta['slzexploore_crbook_status'], 'slzexploore_crbook_status' );
		}
	}

	public function mbox_cruise_booking() {
		global $post;
		$post_id = $post->ID;
		$obj_prop = new Slzexploore_Core_Cruise_Booking();
		$obj_prop->loop_index();
		$data_meta = $obj_prop->post_meta;
		
		// Booking Status
		$status_empty   = array( 'empty' => esc_html__( '-- None --', 'slzexploore-core' ) );
		$status_args    = array( 'hide_empty' => false, 'orderby' => 'term_id' );
		$booking_status = Slzexploore_Core_Com::get_tax_options_id2name( 'slzexploore_crbook_status', $status_empty, $status_args );
		
		// Cruise
		$cruise_empty   = array('empty' => esc_html__( '-- All Cruises --', 'slzexploore-core' ) );
		$cruise_args    = array('post_type' => 'slzexploore_cruise');
		$arr_cruise     = Slzexploore_Core_Com::get_post_id2title( $cruise_args, $cruise_empty );
		
		// Cruise
		$cabin_empty    = array('empty' => esc_html__( '-- All Cabin Types --', 'slzexploore-core' ) );
		$cabin_args     = array('post_type' => 'slzexploore_cabin');
		$arr_cabin      = Slzexploore_Core_Com::get_post_id2title( $cabin_args, $cabin_empty );
		
		$params         = array(
							'booking_status'   => $booking_status,
							'cruises'          => $arr_cruise,
							'cabin_types'          => $arr_cabin
						);
		
		$this->render( 'cruise_booking',
						array(
							'params' => $params,
							'data_meta' => $data_meta
						)
		);
	}
}