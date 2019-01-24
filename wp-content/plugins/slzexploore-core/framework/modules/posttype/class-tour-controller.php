<?php
/**
 * Tour Controller
 * 
 * @since 1.0
 */
Slzexploore_Core::load_class( 'Abstract' );
class Slzexploore_Core_Tour_Controller extends Slzexploore_Core_Abstract {
	public function save() {

		global $post;
		$post_id = $post->ID;
		parent::save();
		
		if( isset( $_POST['slzexploore_tour_meta']) ) {
			$data_meta = $_POST['slzexploore_tour_meta'];
			$prefix = 'slzexploore_tour_';
			$data_meta['slzexploore_tour_available_seat'] = Slzexploore_Core_Format::format_number( $data_meta['slzexploore_tour_available_seat'] );
			$data_meta['slzexploore_tour_price_adult']    = Slzexploore_Core_Format::format_number( $data_meta['slzexploore_tour_price_adult'] );
			$data_meta['slzexploore_tour_price_child']    = Slzexploore_Core_Format::format_number( $data_meta['slzexploore_tour_price_child'] );
			$data_meta['slzexploore_tour_discount_rate']  = Slzexploore_Core_Format::format_number( $data_meta['slzexploore_tour_discount_rate'] );
			$data_meta['slzexploore_tour_deposit_amount']  = Slzexploore_Core_Format::format_number( $data_meta['slzexploore_tour_deposit_amount'] );
			$data_meta['slzexploore_tour_price_infant']  = Slzexploore_Core_Format::format_number( $data_meta['slzexploore_tour_price_infant'] );
			$data_meta['slzexploore_tour_available_seat']  = number_format_i18n( $data_meta['slzexploore_tour_available_seat'] );
			if( empty( $data_meta['slzexploore_tour_display_title'] ) ) {
				$data_meta['slzexploore_tour_display_title'] = $post->post_title;
			}
			if( empty($data_meta['slzexploore_tour_is_discount']) ) {
				$data_meta['slzexploore_tour_discount_rate'] = '';
				$data_meta['slzexploore_tour_discount_text'] = '';
			}

			$chk_fields = array( 'slzexploore_tour_is_discount', 'slzexploore_tour_date_type', 'slzexploore_tour_show_team', 'slzexploore_tour_is_featured');
			foreach( $chk_fields as $field ) {
				if( ! isset( $data_meta[$field] ) ) {
					$data_meta[$field] = '';
				}
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

			if( ! isset( $data_meta[$prefix.'hide_is_full'] ) ) {
				$data_meta[$prefix.'hide_is_full'] = '';
			}
			
			foreach( $data_meta as $key => $value ) {
				update_post_meta ( $post_id, $key, $value ); 
			}
			$start_date = $data_meta['slzexploore_tour_start_date'];
			$end_date = $data_meta['slzexploore_tour_end_date'];
			if( !empty($start_date) && !empty($end_date) && !Slzexploore_Core_Format::compare_date( $start_date, $end_date ) ){
				update_post_meta ( $post_id, 'slzexploore_tour_end_date', $start_date );
			}
			// set post term
			wp_set_post_terms( $post_id, $data_meta['slzexploore_tour_status'], 'slzexploore_tour_status' );
		}
		// check post meta slzexploore_tour_rating & slzexploore_tour_booked_seat
		$rating = get_post_meta ( $post_id, 'slzexploore_tour_rating', true );
		if( empty( $rating ) ){
			update_post_meta ( $post_id, 'slzexploore_tour_rating', 0 );
		}
		$booked_seat = get_post_meta ( $post_id, 'slzexploore_tour_booked_seat', true );
		if( empty( $booked_seat ) ){
			update_post_meta ( $post_id, 'slzexploore_tour_booked_seat', 0 );
		}
		do_action( SLZEXPLOORE_CORE_THEME_PREFIX .'_save_page', $post_id );
	}
	public function mbox_tour() {
		global $post;
		$post_id = $post->ID;
		
		$obj_prop = new Slzexploore_Core_Tour();
		$obj_prop->loop_index();
		$data_meta = $obj_prop->post_meta;
		if(!empty($data_meta['weekly'])){
			$data_meta['weekly'] = explode(',', $data_meta['weekly']);
		}
		if(!empty($data_meta['monthly'])){
			$data_meta['monthly'] = explode(',', $data_meta['monthly']);
		}
		
		$args = array(
			'post_type'        => 'slzexploore_team',
		);
		$options = array( 'empty' => esc_html__('--All Travel Guide--', 'slzexploore-core' ) );
		$teams = Slzexploore_Core_Com::get_post_name2title( $args, $options );
		
		$options = array('empty' => esc_html__( '-- None --', 'slzexploore-core' ) );
		$args = array('hide_empty' => false);
		$tour_status = Slzexploore_Core_Com::get_tax_options_id2name( 'slzexploore_tour_status', $options, $args );
		
		$params = array(
			'team'          => $teams,
			'tour_status'   => $tour_status,
		);
		
		$this->render( 'tour',
				array(
					'params' => $params,
					'data_meta' => $data_meta,
					'attachment_ids' => $data_meta['attachment_ids']
				)
		);
	}
}