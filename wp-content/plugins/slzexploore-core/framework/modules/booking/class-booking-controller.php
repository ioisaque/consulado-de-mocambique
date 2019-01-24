<?php
/**
 * Controller Booking.
 * 
 * @since 1.0
 */
Slzexploore_Core::load_class( 'Abstract' );

class Slzexploore_Core_Booking_Controller extends Slzexploore_Core_Abstract {

	/**
	 * Book Tour with contact form 7
	 */
	public function cf7_ajax_book(){

		$params  = $_POST['params'][0];
		$key = $params['post_type'];
		$data = array();
		
			switch ($key) {
				case 'car':
					$post_type = 'slzexploore_cbook';
					$data[$params['post_type'].'_id'] = $params['id'];
					break;
				case 'cruise':
					$post_type = 'slzexploore_crbook';
					$data[$params['post_type'].'_id'] = $params['id'];
					$data['cabin_type_id'] = $params['cabin_id'];
					break;
				case 'tour':
					$post_type = 'slzexploore_tbook';
					$data[$params['post_type']] = $params['id'];
					break;
				case 'accommodation':
					$post_type = 'slzexploore_book';
					$data['room_type'] = $params['room_type'];
					$data[$params['post_type']] = $params['id'];
					break;
				default:
					$post_type = 'slzexploore_tbook';
					break;
			}
		// add new tour booking data
		$url            = esc_url( get_permalink( $params['id']  ) );
		$name           = get_post_meta( $params['id'], 'slzexploore_'.$key.'_display_title', true );

		$post_arr = array(
			'post_status' => 'publish',
			'post_type' => $post_type
		);
		
		$post_id = wp_insert_post( $post_arr, true );
		if( !is_wp_error( $post_id ) ){
			foreach ($data as $key => $value) {
				$meta_key = $post_type.'_' .$key;
				update_post_meta ( $post_id, $meta_key, $value);
			}
		}
		else{
			echo esc_attr( $post_id->get_error_message() );
		}
		
	}
	// Ajax show hotel booking
	public function ajax_hotel_booking_form(){
		$post_id = $_POST['params']['id'];
		$this->render( 'hotel-booking', array( 'post_id' => $post_id ) );
		exit;
	}
	/**
	 * Hotel Booking
	 */
	/* Get hotel discount */
	public function get_hotel_discount( $hotel_id, $check_in_date = '' ){
		$discount = '';
		$is_discount   = get_post_meta( $hotel_id, 'slzexploore_hotel_discount', true );
		$discount_rate = get_post_meta ( $hotel_id, 'slzexploore_hotel_discount_rate', true );
		if( $is_discount && $discount_rate ){
			$start_date = get_post_meta( $hotel_id, 'slzexploore_hotel_discount_start_date', true );
			$end_date   = get_post_meta ( $hotel_id, 'slzexploore_hotel_discount_end_date', true );
			if(
				( $start_date && $end_date && ( $start_date <= $check_in_date ) && ( $check_in_date <= $end_date ) )
				|| ( !$start_date && ( $check_in_date <= $end_date ) )
				|| ( !$end_date && ( $start_date <= $check_in_date ) )
				|| ( !$start_date && !$end_date )
			){
				return $discount_rate;
			}
		}
		return $discount;
	}
	/**
	 * Check Hotel Booking
	 */
	public function ajax_check_hotel_booking(){
		$params  = $_POST['params']['data'];
		$post_id = $_POST['params']['id'];
		$data = array();
		foreach( $params as $param ) {
			$data[$param['name']] = $param['value'];
		}
		$args = array(
					'post_type' => 'slzexploore_book',
					'meta_query' => array(
						'relation' => 'AND',
						array(
							'key'     => 'slzexploore_book_room_type',
							'value'   => $post_id
						),
						array(
							'key'     => 'slzexploore_book_check_in_date',
							'value'   => $data['check_out_date'],
							'type'    => 'date',
							'compare' => '<',
						),
						array(
							'key'     => 'slzexploore_book_check_out_date',
							'value'   => $data['check_in_date'],
							'type'    => 'date',
							'compare' => '>',
						)
					),
				);
		$found_post = Slzexploore_Core_Com::get_post_id2title( $args, array(), false );
		$reserve_room = 0;
		foreach( $found_post as $booking_id ){
			$number = get_post_meta( $booking_id, 'slzexploore_book_number_room', true );
			if( $number ){
				$reserve_room += $number;
			}
		}
		
		$max_adults     = get_post_meta( $post_id, 'slzexploore_room_max_adults', true );
		$max_children   = get_post_meta( $post_id, 'slzexploore_room_max_children', true );
		$number_room    = get_post_meta( $post_id, 'slzexploore_room_number_room', true );
		$available_room = intval($number_room) - intval($reserve_room);
		$available_adults = intval( $data['number_room'] ) * $max_adults;
		$available_children = intval( $data['number_room'] ) * $max_children;
		$msg_person = '';
		if( $max_adults ) {
			$msg_person = sprintf( _n('%s adult', '%s adults ', $max_adults, 'slzexploore-core'), $max_adults );
		}
		if( $max_children ) {
			$msg_person .= $max_children . ' children';
		}
		if( $msg_person ) {
			$msg_person = $msg_person . '/room';
		}
		$msg_room = sprintf( _n('%s available room', '%s available rooms ', $available_room, 'slzexploore-core'), $available_room );
		if( ( $data['adults'] > $available_adults ) || ( $data['children'] > $available_children )
				|| ( $data['number_room'] > $available_room ) ){
			printf( esc_html__( 'Sorry, we not enough rooms to your booking (%s and %s). Please edit your booking again.', 'slzexploore-core' ), $msg_room, $msg_person );
			exit;
		}
		// check booking date in vacancy if have
		$room_allow_booking = get_post_meta( $post_id, 'slzexploore_room_allow_booking', true );
		$date_string = Slzexploore_Core_Util::join_date_string($data['check_in_date'], $data['check_out_date']);
		if( !empty($room_allow_booking) ){
			$last_date = trim(end(explode(',', $room_allow_booking)));
			$today = str_replace('-', '', date('Y-m-d'));
			if( ( $last_date >= $today ) && strpos( $room_allow_booking, $date_string ) === false){
				esc_html_e( 'Sorry, This room is not available for your booking date. Please edit your booking again.', 'slzexploore-core' );
				exit;
			}
		}
		echo 'success';
		exit;
	}
	/**
	 * Book Hotel
	 */
	public function ajax_book_hotel(){
		$params  = $_POST['params'];
		$data = array();
		foreach( $params as $param ) {
			if( !empty( $param['value'] ) ){
				$data[$param['name']] = $param['value'];
			}
		}
		// add new tour booking data
		$post_arr = array(
						'post_status' => 'publish',
						'post_type'   => 'slzexploore_book'
					);
		$post_id = wp_insert_post( $post_arr, true );
		if( !is_wp_error( $post_id ) ){
			// Hotel Booking information
			$hotel_id = get_post_meta ( $data['room_type'], 'slzexploore_room_accommodation', true );
			if( $hotel_id ){
				update_post_meta ( $post_id, 'slzexploore_book_accommodation', $hotel_id );
			}
			// Customer infomation
			$arr_number = array( 'room_price', 'extra_price', 'total' );
			foreach( $data as $key=>$value ){
				$meta_key = 'slzexploore_book_' . $key;
				if( in_array( $key, $arr_number ) ){
					$value = str_replace( ',', '', $value );
				}
				if( $key == 'description' ){
					$value = str_replace( array( '<span>', '</span>' ), '', $value );
					// extra item - not display in admin, only use to send mail
					update_post_meta ( $post_id, 'slzexploore_book_extra_item', $value );
				}
				update_post_meta ( $post_id, $meta_key, $value );
			}
			// deposit calculate
			$future_payment = 0;
			$deposit_amount = $total_price = str_replace( ',', '', $data['total'] );
			if( isset( $data['deposit_method'] ) && $data['deposit_method'] == 'deposit' ){
				if( $data['deposit_type'] == 'percent' ){
					$deposit_amount = intval( $total_price ) * intval( $data['deposit_amount'] ) / 100;
				}
				else{
					$diff_day = abs( strtotime( $data['check_out_date'] ) - strtotime( $data['check_in_date'] ) );
					$numer_days = floor( $diff_day / (60*60*24) );
					if( $numer_days < 1 ){
						$numer_days = 1;
					}
					$deposit_amount = intval( $numer_days ) * intval( $data['number_room'] ) * intval( $data['deposit_amount'] );
				}
				$future_payment = floatval( $total_price ) - floatval( $deposit_amount );
			}
			update_post_meta ( $post_id, 'slzexploore_book_deposit_amount', $deposit_amount );
			update_post_meta ( $post_id, 'slzexploore_book_future_payment', $future_payment );
			// booking status
			$status = wp_set_object_terms( $post_id, esc_html__( 'On Hold', 'slzexploore-core' ), 'slzexploore_book_status' );
			if( isset( $status[0] ) && !empty( $status[0] ) ){
				update_post_meta( $post_id, 'slzexploore_book_status', $status[0] );
			}
			// send mail
			$this->send_hotel_confirmation_email( $post_id );

			// output
			$redirect_page = Slzexploore::get_option('slz-booking-redirect-page');
			if(empty($redirect_page)){
				echo '[SUCCESS]'.esc_html( $post_id );
			}
			else{
				echo '[REDIRECT]'.get_page_link($redirect_page);
			}
		}
		else{
			echo esc_html( $post_id->get_error_message() );
		}
		exit;
	}
	
	/**
	 * Send Hotel Confirmation Email
	 */
	public function send_hotel_confirmation_email( $post_id ){
		$room_id = get_post_meta( $post_id, 'slzexploore_book_room_type', true );
		if ( ! empty( $room_id ) ) {

			// server variables
			$home_url    = esc_url( home_url() );
			$site_name   = $_SERVER['SERVER_NAME'];

			// theme options
			$to_email    = Slzexploore::get_option('slz-hotel-confirm-email-to');
			$from_email  = Slzexploore::get_option('slz-hotel-confirm-email-from');
			$subject     = Slzexploore::get_option('slz-hotel-confirm-email-subject');
			$header      = Slzexploore::get_option('slz-hotel-confirm-email-header');
			$description = Slzexploore::get_option('slz-hotel-confirm-email-description');
			$sign        = Slzexploore::get_option('slz-currency-sign');
			$header_logo = Slzexploore::get_option('slz-logo-header');
			$logo_url    = esc_url( $header_logo['url'] );
			if( empty( $from_email ) ){
				$from_email = get_option('admin_email');
			}

			// tour info
			$room_object        = get_post( $room_id );
			$hotel_id           = get_post_meta( $post_id, 'slzexploore_book_accommodation', true );
			$hotel_url          = esc_url( get_permalink( $hotel_id ) );
			$room_thumbnail     = get_the_post_thumbnail( $room_id, 'thumbnail' );
			$hotel_name         = get_post_meta( $hotel_id, 'slzexploore_hotel_display_title', true );
			$room_name          = get_post_meta( $room_id, 'slzexploore_room_display_title', true );
			$room_description   = $room_object->post_content;

			// booking info
			$booking_no          = $post_id;
			$booking_adults      = get_post_meta( $post_id, 'slzexploore_book_adults', true );
			$booking_children    = get_post_meta( $post_id, 'slzexploore_book_children', true );
			$number_room         = get_post_meta( $post_id, 'slzexploore_book_number_room', true );
			$booking_price       = get_post_meta( $post_id, 'slzexploore_book_total', true );
			$deposit_amount      = get_post_meta( $post_id, 'slzexploore_book_deposit_amount', true );
			$future_payment      = get_post_meta( $post_id, 'slzexploore_book_future_payment', true );
			$booking_extra_price = get_post_meta( $post_id, 'slzexploore_book_extra_price', true );
			$booking_extra_item  = get_post_meta( $post_id, 'slzexploore_book_extra_item', true );
			$check_in_date       = get_post_meta( $post_id, 'slzexploore_book_check_in_date', true );
			$check_out_date      = get_post_meta( $post_id, 'slzexploore_book_check_out_date', true );
			$check_in_date       = date( 'l, d M Y', strtotime( $check_in_date ) );
			$check_out_date      = date( 'l, d M Y', strtotime( $check_out_date ) );
			$booking_total_price = $sign . $booking_price;
			$deposit_amount      = $sign . $deposit_amount;
			$future_payment      = $sign . $future_payment;
			$booking_extra_price = $sign . intval( $booking_extra_price );

			// customer info
			$customer_first_name = get_post_meta( $post_id, 'slzexploore_book_first_name', true );
			$customer_last_name  = get_post_meta( $post_id, 'slzexploore_book_last_name', true );
			$customer_email      = get_post_meta( $post_id, 'slzexploore_book_email', true );
			$customer_phone      = get_post_meta( $post_id, 'slzexploore_book_phone', true );
			$customer_address    = get_post_meta( $post_id, 'slzexploore_book_address', true );
			$customer_notes      = get_post_meta( $post_id, 'slzexploore_book_customer_des', true );

			$variables = array( 'home_url',
								'site_name',
								'logo_url',
								'from_email',
								'hotel_name',
								'hotel_url',
								'room_name',
								'room_description',
								'room_thumbnail',
								'check_in_date',
								'check_out_date',
								'number_room',
								'booking_no',
								'booking_adults',
								'booking_children',
								'booking_extra_item',
								'booking_extra_price',
								'booking_total_price',
								'customer_first_name',
								'customer_last_name',
								'customer_email',
								'customer_phone',
								'customer_address',
								'deposit_amount',
								'future_payment',
								'customer_notes'
							);

			/* mailing function to customer */
			foreach ( $variables as $variable ) {
				$subject = str_replace( "[" . $variable . "]", $$variable, $subject );
				$header = str_replace( "[" . $variable . "]", $$variable, $header );
				$description = str_replace( "[" . $variable . "]", $$variable, $description );
			}

			// Additional Headers
			$additional_headers   = array();
			$additional_headers[] = "From: ".$site_name." <".$from_email.">";
			$additional_headers[] = "Reply-To: ".$header;
			if( !empty( $to_email ) && is_email( $to_email ) ){
				$additional_headers[] = "Bcc: " . $to_email;
			}
			$send_owner_email = Slzexploore::get_option('slz-hotel-send-owner-email');
			if( $send_owner_email ){
				$mail_cc  = get_post_meta( $hotel_id, 'slzexploore_hotel_mail_cc', true );
				$mail_bcc = get_post_meta( $hotel_id, 'slzexploore_hotel_mail_bcc', true );
				if( !empty( $mail_cc ) ){
					$mail_cc = slzexploore_check_valid_mail( $mail_cc );
					$additional_headers[] = "Cc: " . $mail_cc;
				}
				if( !empty( $mail_bcc ) ){
					$mail_bcc = slzexploore_check_valid_mail( $mail_bcc );
					$additional_headers[] = "Bcc: " . $mail_bcc;
				}
			}
			$description = $this->get_extra_item( $description, $booking_extra_items );
			slzexploore_send_mail( $customer_email, $subject, $description, $additional_headers );

			return true;
		}
		return false;
	}
	/**
	 * Check Hotel Availabel
	 */
	private function check_hotel_available( $room_id, $check_in_date, $check_out_date ){
		$args = array(
					'post_type' => 'slzexploore_book',
					'meta_query' => array(
						'relation' => 'AND',
						array(
							'key'     => 'slzexploore_book_room_type',
							'value'   => $room_id
						),
						array(
							'key'     => 'slzexploore_book_check_in_date',
							'value'   => $check_out_date,
							'type'    => 'date',
							'compare' => '<',
						),
						array(
							'key'     => 'slzexploore_book_check_out_date',
							'value'   => $check_in_date,
							'type'    => 'date',
							'compare' => '>',
						)
					),
				);
		$found_post = Slzexploore_Core_Com::get_post_id2title( $args, array(), false );
		$reserve_room = 0;
		foreach( $found_post as $booking_id=>$title ){
			$number = get_post_meta( $booking_id, 'slzexploore_book_number_room', true );
			if( $number ){
				$reserve_room += $number;
			}
		}
		$number_room    = get_post_meta( $room_id, 'slzexploore_room_number_room', true );
		$available_room = intval($number_room) - intval($reserve_room);
		return $available_room;
	}
	public function ajax_check_hotel_available(){
		$room_id        = $_POST['params']['room_id'];
		$check_in_date   = $_POST['params']['check_in_date'];
		$check_out_date  = $_POST['params']['check_out_date'];
		$available_room = $this->check_hotel_available( $room_id, $check_in_date, $check_out_date );
		echo esc_html( $available_room );
		exit;
	}
	public function ajax_calculate_hotel_summary(){
		$params         = $_POST['params'];
		$post_data = array();
		foreach( $params as $param ) {
			if(strpos($param['name'], 'quantity') !== false){
				$item_id = str_replace( 'quantity_', '', $param['name']);
				$post_data['extra_item'][$item_id] = $param['value'];
			}
			else{
				$post_data[$param['name']] = $param['value'];
			}
		}

		$booking_total   = 0;
		$extra_total     = 0;
		$total           = 0;
		$room_id 	     = $post_data['room_id'];
		$number          = $post_data['number_room'];
		$adults          = $post_data['adults'];
		$children        = $post_data['children'];
		$infant          = $post_data['infant'];
		$start_date      = $post_data['check_in_date'];
		$end_date        = $post_data['check_out_date'];
		$hotel_id        = get_post_meta( $room_id, 'slzexploore_room_accommodation', true );
		$is_price_person = get_post_meta( $room_id, 'slzexploore_room_is_price_person', true );
		$price_infant    = get_post_meta( $room_id, 'slzexploore_room_price_infant', true );
		$booking_price   = $this->get_room_booking_price( $room_id, $start_date, $end_date );
		$discount        = $this->get_hotel_discount( $hotel_id, $check_in_date );

		$decimal 		 =  intval( Slzexploore_Core::get_theme_option('slz-currency-decimal') );
		$price_infant    = round( $price_infant, $decimal );
		$booking_price   = round( $booking_price, $decimal );

		if( intval( $booking_price ) && intval( $number ) && !intval( $is_price_person ) ) {
			$booking_total = floatval( $booking_price ) * intval( $number );
		}
		else if( intval( $booking_price ) && intval( $is_price_person ) ){
			$booking_total = floatval( $booking_price ) * ( intval( $adults ) + intval( $children ) );
			if( intval( $infant ) && intval( $price_infant ) ){
				$booking_total += intval( $infant ) * floatval( $price_infant );
			}
		}
		if( isset($post_data['extra_item']) && !empty($post_data['extra_item']) ){
			foreach( $post_data['extra_item'] as $item_id=>$value ){
				$item_price = get_post_meta( $item_id, 'slzexploore_exitem_price', true );
				$is_person  = get_post_meta( $item_id, 'slzexploore_exitem_is_price_person', true );
				$is_day     = get_post_meta( $item_id, 'slzexploore_exitem_is_price_day', true );
				$item_price = round( $item_price, $decimal );
				$item_total = floatval( $item_price ) * intval( $value );

				if( $is_person ){
					$item_total *= ( intval( $adults ) + intval( $children ) );
				}
				else{
					$item_total *= intval( $number );
				}
				if( $is_day ) {
					$diff_day = abs( strtotime( $start_date ) - strtotime( $end_date ) );
					$number_days = floor( $diff_day / (60*60*24) );
					if( $number_days > 1 ) {
						$item_total *= intval( $number_days );
					}
				}
				$extra_total += $item_total;
			}
		}
		$total = $booking_total + $extra_total;
		if( intval( $discount ) ){
			$total = $total * ( 100 - intval( $discount ) ) / 100;
		}

		$return_data = array(
							'booking_total' => number_format_i18n( $booking_total, $decimal ),
							'extra_total'   => number_format_i18n( $extra_total, $decimal ),
							'total'         => number_format_i18n( $total, $decimal )
						);
		echo json_encode($return_data);
		exit;
	}
	public function get_room_booking_price( $room_id, $check_in_date, $check_out_date ){
		if( $check_in_date != $check_out_date ){
			$date_string = Slzexploore_Core_Util::join_date_string( $check_in_date, $check_out_date );
			$arr_dates = explode(',', $date_string);
			array_pop($arr_dates);
			$booking_price = array();
			foreach( $arr_dates as $date ){
				if(!empty($date)){
					$booking_price[] = floatval( $this->get_room_price_by_date($room_id, $date) );

				}
			}
			return array_sum($booking_price);
		}
		$booking_price = floatval( $this->get_room_price_by_date($room_id, $check_in_date) );
		return $booking_price;
	}
	public function get_room_price_by_date( $room_id, $date ){
		$room_price = get_post_meta( $room_id, 'slzexploore_room_price', true );
		$hotel_id = get_post_meta( $room_id, 'slzexploore_room_accommodation', true );
		$args = array(
			'post_type'  => 'slzexploore_vacancy',
			'orderby'    => 'meta_value',
			'order'      => 'ASC',
			'meta_key'   => 'slzexploore_vacancy_date_from',
			'meta_query' => array(
				array(
					'relation' => 'OR',
					array(
						'key'     => 'slzexploore_vacancy_room_type',
						'value'   => $room_id
					),
					array(
						array(
							'key'     => 'slzexploore_vacancy_room_type',
							'value'   => ''
						),
						array(
							'key'     => 'slzexploore_vacancy_accommodation',
							'value'   => $hotel_id
						)
					)
				),
				array(
					'key'     => 'slzexploore_vacancy_date_from',
					'value'   => date('Y-m-d', strtotime($date)),
					'compare' => '<='
				),
				array(
					'relation' => 'OR',
					array(
						'key'     => 'slzexploore_vacancy_date_to',
						'value'   => ''
					),
					array(
						'key'     => 'slzexploore_vacancy_date_to',
						'value'   => date('Y-m-d', strtotime($date)),
						'compare' => '>='
					)
				)
			)
		);
		$the_query = new WP_Query( $args );
		if ( $the_query->have_posts() ) {
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				$price = get_post_meta( get_the_ID(), 'slzexploore_vacancy_price', true);
				if( !empty( $price ) ){
					$room_price = $price;
					break;
				}
			}
			wp_reset_postdata();
		}
		return $room_price;
	}

	/**
	 * Tour Booking
	 */
	 // Check Tour Booking
	public function ajax_check_tour_booking(){
		$params  = $_POST['params'];
		$data = array();
		foreach( $params as $param ) {
			$data[$param['name']] = $param['value'];
		}
		if( empty($data['start_date']) ){
			esc_html_e( 'Please choose a departure date.', 'slzexploore-core' );
			exit;
		}
		$allow_date = $this->check_available_booking_date( $data['tour_id'], $data['start_date'] );
		if( !$allow_date ){
			esc_html_e( 'Your booking date is not available. Please edit your booking again.', 'slzexploore-core' );
			exit;
		}
		$args = array(
			'post_type'  => 'slzexploore_tbook',
			'meta_query' => array(
				array(
					'key'     => 'slzexploore_tbook_tour',
					'value'   => $data['tour_id']
				),
				array(
					'key'     => 'slzexploore_tbook_tour_date',
					'value'   => $data['start_date']
				)
			)
		);
		$booked_person = 0;
		$booked_tour  = Slzexploore_Core_Com::get_post_id2title( $args, array(), false );
		if( !empty( $booked_tour ) ){
			foreach( $booked_tour as $post_id => $post_name ){
				$booked_person  += intval( get_post_meta( $post_id, 'slzexploore_tbook_adults', true ) );
				$booked_person  += intval( get_post_meta( $post_id, 'slzexploore_tbook_children', true ) );
			}
		}
		$available_seat = $this->get_tour_schedule_seats( $data['tour_id'], $data['start_date'] );
		$allow_seats = intval( $available_seat ) - intval( $booked_person );
		$booking_person = intval($data['adults']) + intval($data['children']) ;
		if( intval( $booking_person ) > intval( $allow_seats ) ){
			printf( esc_html__( 'Sorry, we do not enough available seats to your booking (%s available seats). Please edit your booking again.', 'slzexploore-core' ), intval( $allow_seats ) );
			exit;
		}
		echo 'success';
		exit;
	}
	private function check_available_booking_date( $post_id, $check_date ){
		$posttype = get_post_type( $post_id );
		if( $posttype && !empty( $check_date ) ){
			$date_type = get_post_meta( $post_id, $posttype . '_date_type', true );
			if( !empty( $date_type ) ){
				$frequency = get_post_meta( $post_id, $posttype . '_frequency', true );
				if( $frequency == 'monthly' ){
					$monthly = get_post_meta( $post_id, $posttype . '_monthly', true );
					$j_check_date = date( 'j', strtotime( $check_date ) ); // 1 -> 31
					$d_check_date = date( 'd', strtotime( $check_date ) ); // 01 -> 31
					if( $j_check_date != $monthly && strpos($monthly, $d_check_date) === false ){
						return false;
					}
				}
				elseif( $frequency == 'weekly' ){
					$weekly = get_post_meta( $post_id, $posttype . '_weekly', true );
					$check_date = date( 'N', strtotime( $check_date ) ); // 1 -> 7, 1: Mon
					if( !empty( $weekly ) && strpos($weekly, $check_date) === false ){
						return false;
					}
				}
				elseif( $frequency == 'season' ){
					$start_date  = get_post_meta( $post_id, $posttype . '_start_date', true );
					$end_date    = get_post_meta( $post_id, $posttype . '_end_date', true );
					if( ( !empty( $start_date ) && $check_date < $start_date ) || ( !empty( $end_date ) && $check_date > $end_date ) ){
						return false;
					}
				}
				else{
					// specific date
					$start_date  = get_post_meta( $post_id, $posttype . '_start_date', true );
					if( !empty( $start_date ) && $check_date != $start_date ){
						return false;
					}
				}
			}
		}
		return true;
	}
	public function get_tour_schedule_seats( $post_id, $compare_date ) {
		$available_seat = get_post_meta( $post_id, 'slzexploore_tour_available_seat', true );
		$args = array(
			'post_type'       => 'slzexploore_schedule',
			'orderby'         => 'meta_value',
			'order'           => 'ASC',
			'meta_key'        => 'slzexploore_schedule_start_date',
			'meta_query'      => array(
				array(
					'key'     => 'slzexploore_schedule_tour',
					'value'   => $post_id
				),
				array(
					'key'     => 'slzexploore_schedule_start_date',
					'value'   => $compare_date,
					'compare' => '<='
				),
				array(
					'key'     => 'slzexploore_schedule_end_date',
					'value'   => $compare_date,
					'compare' => '>='
				)
			)
		);
		$results = Slzexploore_Core_Com::get_post_id2title( $args, array(), false );
		if(!empty($results)){
			foreach($results as $id => $title){
				$sch_seats = get_post_meta( $id, 'slzexploore_schedule_maximum_seat', true );
				if( trim($sch_seats) != '' ){
					$available_seat = $sch_seats;
					break;
				}
			}
		}
		return $available_seat;
	}
	public function ajax_calculate_tour_summary(){
		$params         = $_POST['params'];
		$post_data = array();

		foreach( $params as $param ) {
			if(strpos($param['name'], 'quantity') !== false){
				$item_id = str_replace( 'quantity_', '', $param['name']);
				$post_data['extra_item'][$item_id] = $param['value'];
			}
			else{
				$post_data[$param['name']] = $param['value'];
			}
		}

		$booking_total   = 0;
		$extra_total     = 0;
		$total           = 0;
		$tour_id 	     = $post_data['tour_id'];
		$start_date      = $post_data['start_date'];
		$adults          = $post_data['adults'];
		$children        = $post_data['children'];
		$infant          = $post_data['infant'];

		$decimal 		 = intval( Slzexploore_Core::get_theme_option('slz-currency-decimal') );
		$is_discount     = get_post_meta( $tour_id, 'slzexploore_tour_is_discount', true );
		$discount        = get_post_meta( $tour_id, 'slzexploore_tour_discount_rate', true );
		$price_infant    = get_post_meta( $tour_id, 'slzexploore_tour_price_infant', true );
		$schedule_price  = $this->get_tour_schedule_price( $tour_id, $start_date );
		$price_adult     = round( $schedule_price['price_adult'], $decimal );
		$price_child     = round( $schedule_price['price_child'], $decimal );

		if( !empty( $price_adult ) && !empty( $adults ) ) {
			$booking_total = floatval( $booking_total ) + floatval( $price_adult ) * intval( $adults );
		}
		if( !empty( $price_child ) && !empty( $children ) ) {
			$booking_total = floatval( $booking_total ) + floatval( $price_child ) * intval( $children );
		}
		if( !empty( $price_infant ) && !empty( $infant ) ) {
			$booking_total = floatval( $booking_total ) + floatval( $price_infant ) * intval( $infant );
		}
		if( isset( $post_data['extra_item'] ) && !empty( $post_data['extra_item'] ) ){
			foreach( $post_data['extra_item'] as $item_id => $value ){
				$item_price = get_post_meta( $item_id, 'slzexploore_exitem_price', true );
				$is_person  = get_post_meta( $item_id, 'slzexploore_exitem_is_price_person', true );
				$item_price = round( $item_price, $decimal );
				$item_total = floatval( $item_price ) * intval( $value );
				if( $is_person ){
					$item_total *= ( intval( $adults ) + intval( $children ) );
				}
				$extra_total += $item_total;
			}
		}
		$total = $booking_total + $extra_total;
		if( $is_discount && intval( $discount ) ){
			$total = $total * ( 100 - intval( $discount ) ) / 100;
		}
		$return_data = array(
							'booking_total' => number_format_i18n( $booking_total, $decimal ),
							'extra_total'   => number_format_i18n( $extra_total, $decimal ),
							'total'         => number_format_i18n( $total, $decimal )
						);
		echo json_encode($return_data);
		exit;
	}
	public function get_tour_schedule_price( $post_id, $compare_date ) {
		$price_adult     = get_post_meta( $post_id, 'slzexploore_tour_price_adult', true );
		$price_child     = get_post_meta( $post_id, 'slzexploore_tour_price_child', true );
		$args = array(
			'post_type'       => 'slzexploore_schedule',
			'orderby'         => 'meta_value',
			'order'           => 'ASC',
			'meta_key'        => 'slzexploore_schedule_start_date',
			'meta_query'      => array(
				array(
					'key'     => 'slzexploore_schedule_tour',
					'value'   => $post_id
				),
				array(
					'key'     => 'slzexploore_schedule_start_date',
					'value'   => $compare_date,
					'compare' => '<='
				),
				array(
					'key'     => 'slzexploore_schedule_end_date',
					'value'   => $compare_date,
					'compare' => '>='
				)
			)
		);
		$results = Slzexploore_Core_Com::get_post_id2title( $args, array(), false );
		if(!empty($results)){
			foreach($results as $id => $title){
				$sch_price_adult = get_post_meta( $id, 'slzexploore_schedule_price_adult', true );
				$sch_price_child = get_post_meta( $id, 'slzexploore_schedule_price_child', true );
				if( trim($sch_price_adult) != '' || trim($sch_price_child) != '' ){
					$price_adult = $sch_price_adult;
					$price_child = $sch_price_child;
					break;
				}
			}
		}
		$output = array(
			'price_adult' => $price_adult,
			'price_child' => $price_child
		);
		return $output;
	}
	/**
	 * Book Tour
	 */
	public function ajax_book_tour(){
		$params    = $_POST['params'];
		$data = array();
		$tour_id = $data['tour'];
		foreach( $params as $param ) {
			if( !empty( $param['value'] ) ){
				$data[$param['name']] = $param['value'];
			}
		}
		// add new tour booking data
		$post_arr = array(
						'post_status' => 'publish',
						'post_type' => 'slzexploore_tbook'
					);
		$post_id = wp_insert_post( $post_arr, true );
		if( !is_wp_error( $post_id ) ){
			$arr_number = array( 'tour_price', 'extra_price', 'total_price' );
			// Tour Booking information
			foreach( $data as $key=>$value ){
				$meta_key = 'slzexploore_tbook_' . $key;
				if( $key == 'start_date' ){
					$meta_key = 'slzexploore_tbook_tour_date';
				}
				if( in_array( $key, $arr_number ) ){
					$value = str_replace( ',', '', $value );
				}
				if( $key == 'description' ){
					$value = str_replace( array( '<span>', '</span>' ), '', $value );
					// extra item - not display in admin, only use to send mail
					update_post_meta ( $post_id, 'slzexploore_tbook_extra_item', $value );
				}
				update_post_meta ( $post_id, $meta_key, $value );
			}
			// deposit calculate
			$future_payment = 0;
			$deposit_amount = $total_price = str_replace( ',', '', $data['total_price'] );
			if( isset( $data['deposit_method'] ) && $data['deposit_method'] == 'deposit' ){
				if( $data['deposit_type'] == 'percent' ){
					$deposit_amount = intval( $total_price ) * intval( $data['deposit_amount'] ) / 100;
				}
				else{
					$deposit_amount = intval( $data['adults'] ) * intval( $data['deposit_amount'] );
				}
				$future_payment = floatval( $total_price ) - floatval( $deposit_amount );
			}
			update_post_meta ( $post_id, 'slzexploore_tbook_deposit_amount', $deposit_amount );
			update_post_meta ( $post_id, 'slzexploore_tbook_future_payment', $future_payment );
			// booking status
			$status = wp_set_object_terms( $post_id, esc_html__( 'On Hold', 'slzexploore-core' ), 'slzexploore_tbook_status' );
			if( isset( $status[0] ) && !empty( $status[0] ) ){
				update_post_meta( $post_id, 'slzexploore_tbook_status', $status[0] );
			}
			
			// send mail
			$this->send_tour_confirmation_email( $post_id );

			// output
			$redirect_page = Slzexploore::get_option('slz-booking-redirect-page');
			if(empty($redirect_page)){
				echo '[SUCCESS]'.esc_html( $post_id );
			}
			else{
				echo '[REDIRECT]'.get_page_link($redirect_page);
			}
		}
		else{
			echo esc_attr( $post_id->get_error_message() );
		}
		exit;
	}
	/**
	 * Send Tour Confirmation Email
	 */
	public function send_tour_confirmation_email( $post_id ){
		$tour_id = get_post_meta( $post_id, 'slzexploore_tbook_tour', true );
		if ( ! empty( $tour_id ) ) {

			// server variables
			$home_url    = esc_url( home_url() );
			$site_name   = $_SERVER['SERVER_NAME'];

			// theme options
			$to_email    = Slzexploore::get_option('slz-tour-confirm-email-to');
			$from_email  = Slzexploore::get_option('slz-tour-confirm-email-from');
			$subject     = Slzexploore::get_option('slz-tour-confirm-email-subject');
			$header      = Slzexploore::get_option('slz-tour-confirm-email-header');
			$description = Slzexploore::get_option('slz-tour-confirm-email-description');
			$sign        = Slzexploore::get_option('slz-currency-sign');
			$header_logo = Slzexploore::get_option('slz-logo-header');
			$logo_url    = esc_url( $header_logo['url'] );
			if( empty( $from_email ) ){
				$from_email = get_option('admin_email');
			}

			// tour info
			$tour_url            = esc_url( get_permalink( $tour_id ) );
			$tour_thumbnail      = get_the_post_thumbnail( $tour_id, 'thumbnail' );
			$tour_name           = get_post_meta( $tour_id, 'slzexploore_tour_display_title', true );
			$tour_destination    = get_post_meta( $tour_id, 'slzexploore_tour_destination', true );
			$tour_duration       = get_post_meta( $tour_id, 'slzexploore_tour_duration', true );
			$tour_description    = get_post_meta( $tour_id, 'slzexploore_tour_description', true );

			// booking info
			$booking_no          = $post_id;
			$tour_date           = get_post_meta( $post_id, 'slzexploore_tbook_tour_date', true );
			$booking_adults      = get_post_meta( $post_id, 'slzexploore_tbook_adults', true );
			$booking_children    = get_post_meta( $post_id, 'slzexploore_tbook_children', true );
			$booking_price       = get_post_meta( $post_id, 'slzexploore_tbook_total_price', true );
			$deposit_amount      = get_post_meta( $post_id, 'slzexploore_tbook_deposit_amount', true );
			$future_payment      = get_post_meta( $post_id, 'slzexploore_tbook_future_payment', true );
			$booking_extra_price = get_post_meta( $post_id, 'slzexploore_tbook_extra_price', true );
			$booking_extra_item  = get_post_meta( $post_id, 'slzexploore_tbook_extra_item', true );
			$tour_date           = date( 'l, d M Y', strtotime( $tour_date ) );
			$booking_total_price = $sign . $booking_price;
			$deposit_amount      = $sign . $deposit_amount;
			$future_payment      = $sign . $future_payment;
			$booking_extra_price = $sign . intval( $booking_extra_price );

			// customer info
			$customer_first_name = get_post_meta( $post_id, 'slzexploore_tbook_first_name', true );
			$customer_last_name  = get_post_meta( $post_id, 'slzexploore_tbook_last_name', true );
			$customer_email      = get_post_meta( $post_id, 'slzexploore_tbook_email', true );
			$customer_phone      = get_post_meta( $post_id, 'slzexploore_tbook_phone', true );
			$customer_address    = get_post_meta( $post_id, 'slzexploore_tbook_address', true );
			$customer_notes      = get_post_meta( $post_id, 'slzexploore_tbook_customer_des', true );

			$variables = array( 'home_url',
								'site_name',
								'logo_url',
								'from_email',
								'tour_name',
								'tour_url',
								'tour_description',
								'tour_thumbnail',
								'tour_date',
								'tour_duration',
								'tour_destination',
								'booking_no',
								'booking_adults',
								'booking_children',
								'booking_total_price',
								'booking_extra_item',
								'booking_extra_price',
								'customer_first_name',
								'customer_last_name',
								'customer_email',
								'customer_phone',
								'customer_address',
								'future_payment',
								'deposit_amount',
								'customer_notes'
							);

			/* mailing function to customer */
			foreach ( $variables as $variable ) {
				$subject = str_replace( "[" . $variable . "]", $$variable, $subject );
				$header = str_replace( "[" . $variable . "]", $$variable, $header );
				$description = str_replace( "[" . $variable . "]", $$variable, $description );
			}

			// Additional Headers
			$additional_headers   = array();
			$additional_headers[] = "From: ".$site_name." <".$from_email.">";
			$additional_headers[] = "Reply-To: ".$header;
			if( !empty( $to_email ) && is_email( $to_email ) ){
				$additional_headers[] = "Bcc: " . $to_email;
			}
			$send_owner_email = Slzexploore::get_option('slz-tour-send-owner-email');
			if( $send_owner_email ){
				$mail_cc  = get_post_meta( $tour_id, 'slzexploore_tour_mail_cc', true );
				$mail_bcc = get_post_meta( $tour_id, 'slzexploore_tour_mail_bcc', true );
				if( !empty( $mail_cc ) ){
					$mail_cc = slzexploore_check_valid_mail( $mail_cc );
					$additional_headers[] = "Cc: " . $mail_cc;
				}
				if( !empty( $mail_bcc ) ){
					$mail_bcc = slzexploore_check_valid_mail( $mail_bcc );
					$additional_headers[] = "Bcc: " . $mail_bcc;
				}
			}
			$description = $this->get_extra_item( $description, $booking_extra_items );
			slzexploore_send_mail( $customer_email, $subject, $description, $additional_headers );

			return true;
		}
		return false;
	}
	private function get_extra_item( $description, $extra_items ){
		$output = esc_html__( 'No extra items.', 'slzexploore-core' );
		$extra_items = trim( str_replace( ',,', ',', trim( $extra_items ) ), ',' );
		if( !empty( $extra_items ) ){
			$arr_items = explode( ',', $extra_items );
			$output = '<p>'.implode('</p><p>', $arr_items).'</p>';
		}
		$output = str_replace( "[booking_extra_item]", $output, $description );
		return $output;
	}
	// Check Availabel Seat
	private function check_tour_available( $tour_id, $tour_date ){
		$args = array(
			'post_type'  => 'slzexploore_tbook',
			'meta_query' => array(
				array(
					'key'     => 'slzexploore_tbook_tour',
					'value'   => $tour_id
				),
				array(
					'key'     => 'slzexploore_tbook_tour_date',
					'value'   => $tour_date
				)
			)
		);
		$booked_person = 0;
		$booked_tour  = Slzexploore_Core_Com::get_post_id2title( $args, array(), false );
		if( !empty( $booked_tour ) ){
			foreach( $booked_tour as $post_id => $post_name ){
				$booked_person  += intval( get_post_meta( $post_id, 'slzexploore_tbook_adults', true ) );
				$booked_person  += intval( get_post_meta( $post_id, 'slzexploore_tbook_children', true ) );
			}
		}
		$maximum_seat = $this->get_tour_schedule_seats( $tour_id, $tour_date );
		//$maximum_seat = get_post_meta( $tour_id, 'slzexploore_tour_available_seat', true );
		$available_seat = intval( $maximum_seat ) - intval( $booked_person );
		return $available_seat;
	}
	public function ajax_check_tour_available(){
		$tour_id    = $_POST['params']['tour_id'];
		$tour_date  = $_POST['params']['tour_date'];
		$available_seat = $this->check_tour_available( $tour_id, $tour_date );
		echo esc_html( $available_seat );
		exit;
	}

	/**
	 * Car Booking
	 */
	/* Check Car Booking */
	public function ajax_check_car_booking(){
		$data  = $_POST['params'];
		$args = array(
					'post_type' => 'slzexploore_cbook',
					'meta_query' => array(
						array(
							'key'     => 'slzexploore_cbook_car_id',
							'value'   => $data['id']
						),
						array(
							'key'     => 'slzexploore_cbook_date_from',
							'value'   => $data['return_date'],
							'type'    => 'date',
							'compare' => '<'
						),
						array(
							'key'     => 'slzexploore_cbook_date_to',
							'value'   => $data['start_date'],
							'type'    => 'date',
							'compare' => '>'
						)
					),
				);
		$booked_car  = Slzexploore_Core_Com::get_post_id2title( $args, array(), false );
		$booked_number = 0;
		if( !empty( $booked_car ) ){
			foreach( $booked_car as $post_id => $post_name ){
				$booked_number  += intval( get_post_meta( $post_id, 'slzexploore_cbook_number', true ) );
			}
		}
		$car_number = get_post_meta( $data['id'], 'slzexploore_car_number', true );
		$available_number = intval( $car_number ) - intval( $booked_number );
		if( intval( $available_number ) < intval( $data['number'] ) ){
			printf( esc_html__( 'Sorry, we do not enough available cars to your booking (%s available cars). Please edit your booking again.', 'slzexploore-core' ), intval( $available_number ) );
			exit;
		}
		echo 'success';
		exit;
	}
	/* Book Car */
	public function ajax_book_car(){
		$params    = $_POST['params'];
		$post_meta = array();
		foreach( $params as $param ) {
			$key = $param['name'];
			if( $key == 'location' ){
				$key = 'drop_off_location';
			}
			$post_meta[$key] = $param['value'];
		}
		// add new car booking
		$post_arr = array(
						'post_status' => 'publish',
						'post_type'   => 'slzexploore_cbook'
					);
		$post_id = wp_insert_post( $post_arr, true );
		if( !is_wp_error( $post_id ) ){
			$arr_number = array( 'price', 'extra_price', 'total_price' );
			// add post meta
			foreach( $post_meta as $key=>$value ){
				$meta_key = 'slzexploore_cbook_' . $key;
				if( $key == 'drop_off_location' ){
					$location = Slzexploore_Core_Com::get_tax_options_by_slug( $value, 'slzexploore_car_location' );
					if( !empty( $location ) ){
						$value = $location->name;
					}
				}
				if( in_array( $key, $arr_number ) ){
					$value = str_replace( ',', '', $value );
				}
				if( $key == 'description' ){
					$value = str_replace( array( '<span>', '</span>' ), '', $value );
					// extra item - not display in admin, only use to send mail
					update_post_meta ( $post_id, 'slzexploore_cbook_extra_item', $value );
				}
				update_post_meta ( $post_id, $meta_key, $value );
			}
			// deposit calculate
			$future_payment = 0;
			$deposit_amount = $total_price = str_replace( ',', '', $post_meta['total_price'] );
			if( isset( $post_meta['deposit_method'] ) && $post_meta['deposit_method'] == 'deposit' ){
				if( $post_meta['deposit_type'] == 'percent' ){
					$deposit_amount = intval( $total_price ) * intval( $post_meta['deposit_amount'] ) / 100;
				}
				else{
					$diff_day = abs( strtotime( $post_meta['date_to'] ) - strtotime( $post_meta['date_from'] ) );
					$numer_days = floor( $diff_day / (60*60*24) );
					if( $numer_days < 1 ){
						$numer_days = 1;
					}
					$deposit_amount = intval( $numer_days ) * intval( $post_meta['number'] ) * intval( $post_meta['deposit_amount'] );
				}
				$future_payment = floatval( $total_price ) - floatval( $deposit_amount );
			}
			update_post_meta ( $post_id, 'slzexploore_cbook_deposit_amount', $deposit_amount );
			update_post_meta ( $post_id, 'slzexploore_cbook_future_payment', $future_payment );
			// booking status
			$status = wp_set_object_terms( $post_id, esc_html__( 'On Hold', 'slzexploore-core' ), 'slzexploore_cbook_status' );
			if( isset( $status[0] ) && !empty( $status[0] ) ){
				update_post_meta( $post_id, 'slzexploore_cbook_status', $status[0] );
			}
			
			// send mail
			$this->send_car_confirmation_email( $post_id );
			
			// output
			$redirect_page = Slzexploore::get_option('slz-booking-redirect-page');
			if(empty($redirect_page)){
				echo '[SUCCESS]'.esc_html( $post_id );
			}
			else{
				echo '[REDIRECT]'.get_page_link($redirect_page);
			}
		}
		else{
			echo esc_html( $post_id->get_error_message() );
		}
		exit;
	}
	/**
	 * Send Car Confirmation Email
	 */
	public function send_car_confirmation_email( $post_id ){
		$car_id = get_post_meta( $post_id, 'slzexploore_cbook_car_id', true );
		if ( ! empty( $car_id ) ) {

			// server variables
			$home_url    = esc_url( home_url() );
			$site_name   = $_SERVER['SERVER_NAME'];

			// theme options
			$to_email    = Slzexploore::get_option('slz-car-confirm-email-to');
			$from_email  = Slzexploore::get_option('slz-car-confirm-email-from');
			$subject     = Slzexploore::get_option('slz-car-confirm-email-subject');
			$header      = Slzexploore::get_option('slz-car-confirm-email-header');
			$description = Slzexploore::get_option('slz-car-confirm-email-description');
			$sign        = Slzexploore::get_option('slz-currency-sign');
			$header_logo = Slzexploore::get_option('slz-logo-header');
			$logo_url    = esc_url( $header_logo['url'] );
			if( empty( $from_email ) ){
				$from_email = get_option('admin_email');
			}

			// car info
			$car_url            = esc_url( get_permalink( $car_id ) );
			$car_thumbnail      = get_the_post_thumbnail( $car_id, 'thumbnail' );
			$car_name           = get_post_meta( $car_id, 'slzexploore_car_display_title', true );
			$availabel_car      = get_post_meta( $car_id, 'slzexploore_car_number', true );
			$max_people         = get_post_meta( $car_id, 'slzexploore_car_max_people', true );
			$car_description    = get_post_meta( $car_id, 'slzexploore_car_description', true );

			// booking info
			$booking_no          = $post_id;
			$booking_number      = get_post_meta( $post_id, 'slzexploore_cbook_number', true );
			$booking_location    = get_post_meta( $post_id, 'slzexploore_cbook_drop_off_location', true );
			$booking_start_date  = get_post_meta( $post_id, 'slzexploore_cbook_date_from', true );
			$booking_return_date = get_post_meta( $post_id, 'slzexploore_cbook_date_to', true );
			$booking_price       = get_post_meta( $post_id, 'slzexploore_cbook_total_price', true );
			$deposit_amount      = get_post_meta( $post_id, 'slzexploore_cbook_deposit_amount', true );
			$future_payment      = get_post_meta( $post_id, 'slzexploore_cbook_future_payment', true );
			$booking_extra_price = get_post_meta( $post_id, 'slzexploore_cbook_extra_price', true );
			$booking_extra_item  = get_post_meta( $post_id, 'slzexploore_cbook_extra_item', true );
			$booking_extra_price = $sign . intval( $booking_extra_price );
			$deposit_amount      = $sign . $deposit_amount;
			$future_payment      = $sign . $future_payment;
			$booking_total_price = $sign . $booking_price;
			$booking_start_date  = date( 'l, d M Y', strtotime( $booking_start_date ) );
			$booking_return_date = date( 'l, d M Y', strtotime( $booking_return_date ) );

			// customer info
			$customer_first_name = get_post_meta( $post_id, 'slzexploore_cbook_first_name', true );
			$customer_last_name  = get_post_meta( $post_id, 'slzexploore_cbook_last_name', true );
			$customer_email      = get_post_meta( $post_id, 'slzexploore_cbook_email', true );
			$customer_phone      = get_post_meta( $post_id, 'slzexploore_cbook_phone', true );
			$customer_address    = get_post_meta( $post_id, 'slzexploore_cbook_address', true );
			$customer_notes      = get_post_meta( $post_id, 'slzexploore_cbook_customer_des', true );

			$variables = array( 'home_url',
								'site_name',
								'logo_url',
								'from_email',
								'car_url',
								'car_thumbnail',
								'car_name',
								'availabel_car',
								'max_people',
								'car_description',
								'booking_no',
								'booking_number',
								'booking_location',
								'booking_start_date',
								'booking_return_date',
								'booking_extra_item',
								'booking_extra_price',
								'booking_total_price',
								'customer_first_name',
								'customer_last_name',
								'customer_email',
								'customer_phone',
								'customer_address',
								'deposit_amount',
								'future_payment',
								'customer_notes'
							);

			/* mailing function to customer */
			foreach ( $variables as $variable ) {
				$subject = str_replace( "[" . $variable . "]", $$variable, $subject );
				$header = str_replace( "[" . $variable . "]", $$variable, $header );
				$description = str_replace( "[" . $variable . "]", $$variable, $description );
			}

			// Additional Headers
			$additional_headers   = array();
			$additional_headers[] = "From: ".$site_name." <".$from_email.">";
			$additional_headers[] = "Reply-To: ".$header;
			if( !empty( $to_email ) && is_email( $to_email ) ){
				$additional_headers[] = "Bcc: " . $to_email;
			}
			$send_owner_email = Slzexploore::get_option('slz-car-send-owner-email');
			if( $send_owner_email ){
				$mail_cc  = get_post_meta( $car_id, 'slzexploore_car_mail_cc', true );
				$mail_bcc = get_post_meta( $car_id, 'slzexploore_car_mail_bcc', true );
				if( !empty( $mail_cc ) ){
					$mail_cc = slzexploore_check_valid_mail( $mail_cc );
					$additional_headers[] = "Cc: " . $mail_cc;
				}
				if( !empty( $mail_bcc ) ){
					$mail_bcc = slzexploore_check_valid_mail( $mail_bcc );
					$additional_headers[] = "Bcc: " . $mail_bcc;
				}
			}
			$description = $this->get_extra_item( $description, $booking_extra_items );
			slzexploore_send_mail( $customer_email, $subject, $description, $additional_headers );

			return true;
		}
		return false;
	}
	/* Check Car Availabel */
	private function check_car_available( $car_id, $date_from, $date_to ){
		$args = array(
				'post_type' => 'slzexploore_cbook',
				'meta_query' => array(
					array(
						'key'     => 'slzexploore_cbook_car_id',
						'value'   => $car_id
					),
					array(
						'key'     => 'slzexploore_cbook_date_from',
						'value'   => $date_to,
						'type'    => 'date',
						'compare' => '<'
					),
					array(
						'key'     => 'slzexploore_cbook_date_to',
						'value'   => $date_from,
						'type'    => 'date',
						'compare' => '>'
					)
				),
			);
		$booked_car  = Slzexploore_Core_Com::get_post_id2title( $args, array(), false );
		$booked_number = 0;
		if( !empty( $booked_car ) ){
			foreach( $booked_car as $post_id => $post_name ){
				$booked_number  += intval( get_post_meta( $post_id, 'slzexploore_cbook_number', true ) );
			}
		}
		$car_number = get_post_meta( $car_id, 'slzexploore_car_number', true );
		$available_number = intval( $car_number ) - intval( $booked_number );
		return $available_number;
	}
	public function ajax_check_car_available(){
		$car_id    = $_POST['params']['car_id'];
		$date_from = $_POST['params']['date_from'];
		$date_to   = $_POST['params']['date_to'];
		$available_number = $this->check_car_available( $car_id, $date_from, $date_to );
		echo esc_html( $available_number );
		exit;
	}
	public function ajax_calculate_car_summary(){
		$params         = $_POST['params'];
		$post_data = array();

		foreach( $params as $param ) {
			if(strpos($param['name'], 'quantity') !== false){
				$item_id = str_replace( 'quantity_', '', $param['name']);
				$post_data['extra_item'][$item_id] = $param['value'];
			}
			else{
				$post_data[$param['name']] = $param['value'];
			}
		}
		$booking_total   = 0;
		$extra_total     = 0;
		$total           = 0;
		$car_id 	     = $post_data['car_id'];
		$start_date      = $post_data['date_from'];
		$end_date        = $post_data['date_to'];
		$number          = $post_data['number'];

		$decimal 		 = intval( Slzexploore_Core::get_theme_option('slz-currency-decimal') );
		$car_price       = round( get_post_meta( $car_id, 'slzexploore_car_price', true ), $decimal );
		$diff_day        = abs( strtotime( $end_date ) - strtotime( $start_date ) );
		$number_day      = floor( $diff_day / (60*60*24) );
		if( $number_day < 1 ) {
			$number_day  = 1;
		}

		if( !empty( $car_price ) && !empty( $number ) ) {
			$booking_total = floatval( $car_price ) * intval( $number ) * intval( $number_day );
		}
		if( isset( $post_data['extra_item'] ) && !empty( $post_data['extra_item'] ) ){
			foreach( $post_data['extra_item'] as $item_id => $value ){
				$item_price = get_post_meta( $item_id, 'slzexploore_exitem_price', true );
				$is_day     = get_post_meta( $item_id, 'slzexploore_exitem_is_price_day', true );
				$item_price = round( $item_price, $decimal );
				$item_total = floatval( $item_price ) * intval( $value );
				if( $is_day && $intval > 1 ){
					$item_total *= intval( $number_day );
				}
				$extra_total += $item_total;
			}
		}
		$total = $booking_total + $extra_total;
		$model = new Slzexploore_Core_Car();
		$discount   = $model->get_discount_number( $car_id, date('Y-m-d') );
		if( !empty( $discount ) ){
			$total = $total * ( 100 - intval( $discount ) ) / 100;
		}
		$return_data = array(
							'booking_total' => number_format_i18n( $booking_total, $decimal ),
							'extra_total'   => number_format_i18n( $extra_total, $decimal ),
							'total'         => number_format_i18n( $total, $decimal )
						);
		echo json_encode($return_data);
		exit;
	}

	/**
	 * Cruise Booking
	 */
	/* Check Cruise Booking */
	public function ajax_check_cruise_booking() {
		$params = $_POST['params'];
		$data   = array();
		foreach ( $params as $param ) {
			$data[ $param['name'] ] = $param['value'];
		}
		if ( empty( $data['start_date'] ) ) {
			esc_html_e( 'Please choose a departure date.', 'slzexploore-core' );
			exit;
		}
		if ( ! empty( $data['cabin_type_id'] ) ) {
			$cruise_id = get_post_meta( $data['cabin_type_id'], 'slzexploore_cabin_cruise_id', true );
		} else {
			$cruise_id = $data['cruise_id'];
		}
		$allow_date = $this->check_available_booking_date( $cruise_id, $data['start_date'] );
		if ( ! $allow_date ) {
			esc_html_e( 'Your booking date is not available. Please edit your booking again.', 'slzexploore-core' );
			exit;
		}
		// check number of cabin
		$available_number = $this->check_cruise_available( $data['start_date'], $cruise_id, $data['cabin_type_id'] );
		if ( ! empty( $data['cabin_type_id'] ) ) {
			if ( intval( $available_number ) < intval( $data['number'] ) ) {
				printf( esc_html__( 'Sorry, we do not enough available cabins to your booking (%s available cabins). Please edit your booking again.', 'slzexploore-core' ), intval( $available_number ) );
				exit;
			}
			// check adults
			$max_adults = get_post_meta( $data['cabin_type_id'], 'slzexploore_cabin_max_adults', true );
			if ( ! empty( $max_adults ) ) {
				$allow_adults = intval( $max_adults ) * intval( $data['number'] );
				if ( intval( $data['adults'] ) > $allow_adults ) {
					printf( esc_html__( 'Sorry, Your booking have exceeded allowed adults (%s allowed adults). Please edit your booking again.', 'slzexploore-core' ), intval( $allow_adults ) );
					exit;
				}
			}
			// check children
			$max_children = get_post_meta( $data['cabin_type_id'], 'slzexploore_cabin_max_children', true );
			if ( ! empty( $max_children ) ) {
				$allow_children = intval( $max_children ) * intval( $data['number'] );
				if ( intval( $data['children'] ) > $allow_children ) {
					printf( esc_html__( 'Sorry, Your booking have exceeded allowed children (%s allowed children). Please edit your booking again.', 'slzexploore-core' ), intval( $allow_children ) );
					exit;
				}
			}
		} else {
			$available_number = $available_number < 0 ? 0 : $available_number;
			if ( intval( $available_number ) < ( intval( $data['adults'] ) + intval( $data['children'] ) ) ) {
				printf( esc_html__( 'Sorry, we do not enough available seats for your booking (%s available seats). Please edit your booking again.', 'slzexploore-core' ), intval( $available_number ) );
				exit;
			}
		}
		echo 'success';
		exit;
	}
	/* Book Cruise */
	public function ajax_book_cruise(){
		$params    = $_POST['params'];
		$post_meta = array();
		foreach( $params as $param ) {
			$post_meta[$param['name']] = $param['value'];
		}
		// add new cruise booking
		$post_arr = array(
						'post_status' => 'publish',
						'post_type'   => 'slzexploore_crbook'
					);
		$post_id = wp_insert_post( $post_arr, true );
		if( !is_wp_error( $post_id ) ){
			$arr_number = array( 'price', 'extra_price', 'total_price' );
			// add post meta
			foreach( $post_meta as $key=>$value ){
				$meta_key = 'slzexploore_crbook_' . $key;
				if( in_array( $key, $arr_number ) ){
					$value = str_replace( ',', '', $value );
				}
				if( $key == 'description' ){
					$value = str_replace( array( '<span>', '</span>' ), '', $value );
					// extra item - not display in admin, only use to send mail
					update_post_meta ( $post_id, 'slzexploore_crbook_extra_item', $value );
				}
				update_post_meta ( $post_id, $meta_key, $value );
			}
			// deposit calculate
			$future_payment = 0;
			$deposit_amount = $total_price = str_replace( ',', '', $post_meta['total_price'] );
			if( isset( $post_meta['deposit_method'] ) && $post_meta['deposit_method'] == 'deposit' ){
				if( $post_meta['deposit_type'] == 'percent' ){
					$deposit_amount = intval( $total_price ) * intval( $post_meta['deposit_amount'] ) / 100;
				}
				else{
					$deposit_amount = intval( $post_meta['number'] ) * intval( $post_meta['deposit_amount'] );
				}
				$future_payment = floatval( $total_price ) - floatval( $deposit_amount );
			}
			update_post_meta ( $post_id, 'slzexploore_crbook_deposit_amount', $deposit_amount );
			update_post_meta ( $post_id, 'slzexploore_crbook_future_payment', $future_payment );
			// booking status
			$status = wp_set_object_terms( $post_id, esc_html__( 'On Hold', 'slzexploore-core' ), 'slzexploore_crbook_status' );
			if( isset( $status[0] ) && !empty( $status[0] ) ){
				update_post_meta( $post_id, 'slzexploore_crbook_status', $status[0] );
			}
			
			// send mail
			$this->send_cruise_confirmation_email( $post_id );
			
			// output
			$redirect_page = Slzexploore::get_option('slz-booking-redirect-page');
			if(empty($redirect_page)){
				echo '[SUCCESS]'.esc_html( $post_id );
			}
			else{
				echo '[REDIRECT]'.get_page_link($redirect_page);
			}
		}
		else{
			echo esc_html( $post_id->get_error_message() );
		}
		exit;
	}
	/**
	 * Send Cruise Confirmation Email
	 */
	public function send_cruise_confirmation_email( $post_id ){
		$cruise_id = get_post_meta( $post_id, 'slzexploore_crbook_cruise_id', true );
		if ( ! empty( $cruise_id ) ) {

			// server variables
			$home_url    = esc_url( home_url() );
			$site_name   = $_SERVER['SERVER_NAME'];

			// theme options
			$to_email    = Slzexploore::get_option('slz-cruise-confirm-email-to');
			$from_email  = Slzexploore::get_option('slz-cruise-confirm-email-from');
			$subject     = Slzexploore::get_option('slz-cruise-confirm-email-subject');
			$header      = Slzexploore::get_option('slz-cruise-confirm-email-header');
			$description = Slzexploore::get_option('slz-cruise-confirm-email-description');
			$sign        = Slzexploore::get_option('slz-currency-sign');
			$header_logo = Slzexploore::get_option('slz-logo-header');
			$logo_url    = esc_url( $header_logo['url'] );
			if( empty( $from_email ) ){
				$from_email = get_option('admin_email');
			}

			// cruise info
			$cruise_url            = esc_url( get_permalink( $cruise_id ) );
			$cruise_thumbnail      = get_the_post_thumbnail( $cruise_id, 'thumbnail' );
			$cruise_name           = get_post_meta( $cruise_id, 'slzexploore_cruise_display_title', true );
			$cruise_duration       = get_post_meta( $cruise_id, 'slzexploore_cruise_duration', true );
			$cruise_seats          = get_post_meta( $cruise_id, 'slzexploore_cruise_available_seat', true );
			$cruise_destination    = get_post_meta( $cruise_id, 'slzexploore_cruise_destination', true );
			$cruise_description    = get_post_meta( $cruise_id, 'slzexploore_cruise_description', true );

			// booking info
			$booking_no          = $post_id;
			$cabin_id            = get_post_meta( $post_id, 'slzexploore_crbook_cabin_type_id', true );;
			$booking_cabin       = get_post_meta( $cabin_id, 'slzexploore_cabin_display_title', true );
			$booking_start_date  = get_post_meta( $post_id, 'slzexploore_crbook_start_date', true );
			$booking_number      = get_post_meta( $post_id, 'slzexploore_crbook_number', true );
			$booking_adults      = get_post_meta( $post_id, 'slzexploore_crbook_adults', true );
			$booking_children    = get_post_meta( $post_id, 'slzexploore_crbook_children', true );
			$booking_price       = get_post_meta( $post_id, 'slzexploore_crbook_price', true );
			$booking_extra_price = get_post_meta( $post_id, 'slzexploore_crbook_extra_price', true );
			$booking_total_price = get_post_meta( $post_id, 'slzexploore_crbook_total_price', true );
			$deposit_amount      = get_post_meta( $post_id, 'slzexploore_crbook_deposit_amount', true );
			$future_payment      = get_post_meta( $post_id, 'slzexploore_crbook_future_payment', true );
			$booking_extra_item  = get_post_meta( $post_id, 'slzexploore_crbook_extra_item', true );
			$deposit_amount      = $sign . $deposit_amount;
			$future_payment      = $sign . $future_payment;
			$booking_price       = $sign . $booking_price;
			$booking_extra_price = $sign . $booking_extra_price;
			$booking_total_price = $sign . $booking_total_price;
			$booking_start_date  = date( 'l, d M Y', strtotime( $booking_start_date ) );

			// customer info
			$customer_first_name = get_post_meta( $post_id, 'slzexploore_crbook_first_name', true );
			$customer_last_name  = get_post_meta( $post_id, 'slzexploore_crbook_last_name', true );
			$customer_email      = get_post_meta( $post_id, 'slzexploore_crbook_email', true );
			$customer_phone      = get_post_meta( $post_id, 'slzexploore_crbook_phone', true );
			$customer_address    = get_post_meta( $post_id, 'slzexploore_crbook_address', true );
			$customer_notes      = get_post_meta( $post_id, 'slzexploore_crbook_customer_des', true );

			$variables = array( 'home_url',
								'site_name',
								'logo_url',
								'from_email',
								'cruise_url',
								'cruise_thumbnail',
								'cruise_name',
								'cruise_duration',
								'cruise_seats',
								'cruise_destination',
								'cruise_description',
								'booking_no',
								'booking_cabin',
								'booking_start_date',
								'booking_number',
								'booking_adults',
								'booking_children',
								'booking_price',
								'booking_extra_item',
								'booking_extra_price',
								'booking_total_price',
								'customer_first_name',
								'customer_last_name',
								'customer_email',
								'customer_phone',
								'customer_address',
								'deposit_amount',
								'future_payment',
								'customer_notes'
							);

			/* mailing function to customer */
			foreach ( $variables as $variable ) {
				$subject = str_replace( "[" . $variable . "]", $$variable, $subject );
				$header = str_replace( "[" . $variable . "]", $$variable, $header );
				$description = str_replace( "[" . $variable . "]", $$variable, $description );
			}

			// Additional Headers
			$additional_headers   = array();
			$additional_headers[] = "From: ".$site_name." <".$from_email.">";
			$additional_headers[] = "Reply-To: ".$header;
			if( !empty( $to_email ) && is_email( $to_email ) ){
				$additional_headers[] = "Bcc: " . $to_email;
			}
			$send_owner_email = Slzexploore::get_option('slz-cruise-send-owner-email');
			if( $send_owner_email ){
				$mail_cc  = get_post_meta( $cruise_id, 'slzexploore_cruise_mail_cc', true );
				$mail_bcc = get_post_meta( $cruise_id, 'slzexploore_cruise_mail_bcc', true );
				if( !empty( $mail_cc ) ){
					$mail_cc = slzexploore_check_valid_mail( $mail_cc );
					$additional_headers[] = "Cc: " . $mail_cc;
				}
				if( !empty( $mail_bcc ) ){
					$mail_bcc = slzexploore_check_valid_mail( $mail_bcc );
					$additional_headers[] = "Bcc: " . $mail_bcc;
				}
			}
			$description = $this->get_extra_item( $description, $booking_extra_items );
			slzexploore_send_mail( $customer_email, $subject, $description, $additional_headers );

			return true;
		}
		return false;
	}
	/* Check Cruise Availabel */
	private function check_cruise_available( $start_date, $cruise_id = 0, $cabin_type_id = 0 ) {
		$available_number = 0;
		$booked_number    = 0;
		if ( ! empty( $start_date ) ) {
			if ( ! empty( $cabin_type_id ) ) { // case with choose cabin type
				$args         = array(
					'post_type'  => 'slzexploore_crbook',
					'meta_query' => array(
						array(
							'key'   => 'slzexploore_crbook_cabin_type_id',
							'value' => $cabin_type_id
						),
						array(
							'key'   => 'slzexploore_crbook_start_date',
							'value' => $start_date
						)
					)
				);
				$booked_cabin = Slzexploore_Core_Com::get_post_id2title( $args, array(), false );
				if ( ! empty( $booked_cabin ) ) {
					foreach ( $booked_cabin as $cabin_id => $cabin_name ) {
						$booked_number += intval( get_post_meta( $cabin_id, 'slzexploore_crbook_number', true ) );
					}
				}
				// check number of cabin
				$cabin_number     = get_post_meta( $cabin_type_id, 'slzexploore_cabin_number', true );
				$available_number = intval( $cabin_number ) - intval( $booked_number );
			} else { // case without choose cabin type
				$args          = array(
					'post_type'  => 'slzexploore_crbook',
					'meta_query' => array(
						array(
							'key'   => 'slzexploore_crbook_cruise_id',
							'value' => $cruise_id
						),
						array(
							'key'   => 'slzexploore_crbook_start_date',
							'value' => $start_date
						)
					)
				);
				$booked_cruise = Slzexploore_Core_Com::get_post_id2title( $args, array(), false );
				if ( ! empty( $booked_cruise ) ) {
					foreach ( $booked_cruise as $crbook_id => $cruise_name ) {
						$booked_number += intval( get_post_meta( $crbook_id, 'slzexploore_crbook_adults', true ) );
						$booked_number += intval( get_post_meta( $crbook_id, 'slzexploore_crbook_children', true ) );
					}
				}
				// check number of cabin
				$seat_number      = get_post_meta( $cruise_id, 'slzexploore_cruise_available_seat', true );
				$seat_number      = filter_var( $seat_number, FILTER_SANITIZE_NUMBER_INT );
				$available_number = intval( $seat_number ) - intval( $booked_number );
			}
		}

		return $available_number;
	}

	public function ajax_check_cruise_available() {
		$cruise_id        = $_POST['params']['cruise_id'];
		$cabin_type_id    = $_POST['params']['cabin_id'];
		$start_date       = $_POST['params']['start_date'];
		$available_number = $this->check_cruise_available( $start_date, $cruise_id, $cabin_type_id );
		$available_number = $available_number < 0 ? 0 : $available_number;
		if ( ! empty( $cabin_type_id ) ) {
			$out = array( 'type' => 'cabin', 'available_number' => $available_number );
			echo json_encode( $out );
		} else {
			$out = array( 'type' => 'seat', 'available_number' => $available_number );
			echo json_encode( $out );
		}
		exit;
	}
	public function ajax_calculate_cruise_summary(){
		$params         = $_POST['params'];
		$post_data = array();

		foreach( $params as $param ) {
			if(strpos($param['name'], 'quantity') !== false){
				$item_id = str_replace( 'quantity_', '', $param['name']);
				$post_data['extra_item'][$item_id] = $param['value'];
			}
			else{
				$post_data[$param['name']] = $param['value'];
			}
		}
		$booking_total   = 0;
		$extra_total     = 0;
		$total           = 0;
		$cruise_id 	     = $post_data['cruise_id'];
		$cabin_id 	     = $post_data['cabin_type_id'];
		$start_date      = $post_data['start_date'];
		$number          = $post_data['number'];
		$adults          = $post_data['adults'];
		$children        = $post_data['children'];
		$infant          = $post_data['infant'];

		$decimal 		 = intval( Slzexploore_Core::get_theme_option('slz-currency-decimal') );
		$is_discount     = get_post_meta( $cruise_id, 'slzexploore_cruise_is_discount', true );
		$discount        = get_post_meta( $cruise_id, 'slzexploore_cruise_discount_rate', true );
		$is_price_person = get_post_meta( $cabin_id, 'slzexploore_cabin_is_price_person', true );
		$cabin_price     = get_post_meta( $cabin_id, 'slzexploore_cabin_price', true );
		$price_infant    = get_post_meta( $cabin_id, 'slzexploore_cabin_price_infant', true );
		$cabin_price     = round( $cabin_price, $decimal );
		$price_infant    = round( $price_infant, $decimal );

		if ( ! empty( $cabin_id ) ) {
			if ( $is_price_person ) {
				$booking_total = floatval( $cabin_price ) * ( intval( $adults ) + intval( $children ) );
				if ( ! empty( $infant ) && ! empty( $price_infant ) ) {
					$booking_total += floatval( $price_infant ) * intval( $infant );
				}
			} elseif ( ! empty( $number ) ) {
				$booking_total = floatval( $cabin_price ) * intval( $number );
			}
		} else {
			$price_per_adult = get_post_meta( $cruise_id, 'slzexploore_cruise_price_adult', true );
			$price_per_child = get_post_meta( $cruise_id, 'slzexploore_cruise_price_child', true );
			$booking_total   = floatval( $price_per_adult ) * intval( $adults ) + floatval( $price_per_child ) * intval( $children );
		}

		if( isset( $post_data['extra_item'] ) && !empty( $post_data['extra_item'] ) ){
			foreach( $post_data['extra_item'] as $item_id => $value ){
				$item_price = get_post_meta( $item_id, 'slzexploore_exitem_price', true );
				$is_person  = get_post_meta( $item_id, 'slzexploore_exitem_is_price_person', true );
				$item_price = round( $item_price, $decimal );
				$item_total = floatval( $item_price ) * intval( $value );
				if( $is_person ){
					$item_total *= ( intval( $adults ) + intval( $children ) );
				}
				else{
					$item_total *= ( intval( $number ) );
				}
				$extra_total += $item_total;
			}
		}
		$total = $booking_total + $extra_total;
		if( $is_discount && intval( $discount ) ){
			$total = $total * ( 100 - intval( $discount ) ) / 100;
		}
		$return_data = array(
							'booking_total' => number_format_i18n( $booking_total, $decimal ),
							'extra_total'   => number_format_i18n( $extra_total, $decimal ),
							'total'         => number_format_i18n( $total, $decimal )
						);
		echo json_encode($return_data);
		exit;
	}
	// show car booking
	public function show_car_booking() {
		$this->render( 'car-booking');
	}
	// show cruise booking
	public function show_cruise_booking() {
		$this->render( 'cruise-booking');
	}
	// show tour booking
	public function show_tour_booking() {
		$this->render( 'tour-booking');
	}
	// show booking price
	public function show_booking_price( $price = '', $is_span = true ) {
		$output = '';
		$format = '%1$s %2$s';
		$sign   = Slzexploore_Core::get_theme_option('slz-currency-sign');
		$sign_position = Slzexploore_Core::get_theme_option('slz-symbol-currency-position');
		$decimal = Slzexploore_Core::get_theme_option('slz-currency-decimal');
		$price   = number_format_i18n( Slzexploore_Core_Format::format_number($price), $decimal );
		if( $sign_position == 'before' ) {
			if( $is_span ){
				$format = '%1$s <span>%2$s</span>';
			}
			$output = sprintf( $format, esc_html( $sign ), esc_html( $price ) );
		} else {
			if( $is_span ){
				$format = '<span>%1$s</span> %2$s';
			}
			$output = sprintf( $format, esc_html( $price ), esc_html( $sign ) );
		}
		return $output;
	}

	// add hotel to cart
	public function ajax_add_hotel_to_cart() {
		global $woocommerce, $wp_locale;
		$thousands_sep = $wp_locale->number_format['thousands_sep'];
		$decimal_point = $wp_locale->number_format['decimal_point'];
		$localeconv    = localeconv ();
		$params    = $_POST['params'];
		$post_meta = array();
		$arr_number = array( 'room_price', 'extra_price', 'total' );
		foreach( $params as $param ) {
			if( in_array( $param['name'], $arr_number ) ){
				$param['value'] = str_replace( $thousands_sep, '', $param['value'] );
				$param['value'] = str_replace( $decimal_point, $localeconv['decimal_point'], $param['value'] );
			}
			if( $param['name'] == 'description' ){
				$param['value'] = str_replace( array( '<span>', '</span>' ), '', $param['value'] );
				// extra item - not display in admin, only use to send mail
				$post_meta['extra_item'] = $param['value'];
			}
			$post_meta[$param['name']] = $param['value'];
		}
		$room_id = $post_meta['room_type'];
		$hotel_id = get_post_meta ( $room_id, 'slzexploore_room_accommodation', true );
		if( $hotel_id ){
			$post_meta['accommodation'] = $hotel_id;
		}
		// deposit calculate
		$future_payment = 0;
		$deposit_amount = $post_meta['total'];
		if( isset( $post_meta['deposit_method'] ) && $post_meta['deposit_method'] == 'deposit' ){
			if( $post_meta['deposit_type'] == 'percent' ){
				$deposit_amount = intval( $post_meta['total'] ) * intval( $post_meta['deposit_amount'] ) / 100;
			}
			else{
				$diff_day = abs( strtotime( $post_meta['check_out_date'] ) - strtotime( $post_meta['check_in_date'] ) );
				$numer_days = floor( $diff_day / (60*60*24) );
				if( $numer_days < 1 ){
					$numer_days = 1;
				}
				$deposit_amount = intval( $numer_days ) * intval( $post_meta['number_room'] ) * intval( $post_meta['deposit_amount'] );
			}
			$future_payment = floatval( $post_meta['total'] ) - floatval( $deposit_amount );
		}
		$post_meta['deposit_amount'] = $deposit_amount;
		$post_meta['future_payment'] = $future_payment;
		// booking description
		$price_room    = get_post_meta( $room_id,  'slzexploore_room_price', true );
		$is_discount   = get_post_meta( $hotel_id, 'slzexploore_hotel_discount', true );
		$discount_rate = get_post_meta( $hotel_id, 'slzexploore_hotel_discount_rate', true );
		$post_meta['description'] .= esc_html__( 'Room Price : ', 'slzexploore-core' ). intval( $price_room );
		if( !empty( $is_discount ) && !empty( $discount_rate ) ){
			$post_meta['description'] .= ', '. esc_html__( 'Discount : ', 'slzexploore-core' ). $discount_rate . '%';
		}
		// add to woocommerce cart
		$prefix       = 'hotel';
		$hotel_title  = get_the_title( $hotel_id );
		$hotel_slug   = Slzexploore_Core_Com::get_post_id2name( $hotel_id );
		
		$product_id = Slzexploore_Core_Com::get_post_name2id( $hotel_slug, 'product' );
		if (!isset($product_id) || empty($product_id)) {
			$product_cat = $this->update_product_categories( $hotel_id, 'slzexploore_hotel_cat', esc_html__( 'Accommodations Products', 'slzexploore-core' ) );
			$atts = array();
			if( has_post_thumbnail( $hotel_id ) ){
				$atts['thumbnail_id'] = get_post_thumbnail_id( $hotel_id );
			}
			$product_id  = $this->create_woocommerce_product( $prefix, $hotel_title, $hotel_slug, $product_cat, $atts );
		}
		else{
			$this->update_available_product_categories( $hotel_id, 'slzexploore_hotel_cat', $product_id, 'Accommodations' );
		}
		
		$variation_args = array(
							'post_type'   => 'product_variation',
							'post_parent' => $product_id,
							'post_name'   => $hotel_slug
						);
		$variation_obj  = get_posts($variation_args);
		if( !empty( $variation_obj ) ){
			$variation_id   = $variation_obj[0]->ID;
		}
		if (!isset($variation_id) || empty($variation_id)) {
			$variation_id = $this->create_woocommerce_product_variation( $prefix, $product_id, $hotel_title, $hotel_slug, $hotel_id );
		}
		if ($product_id > 0 && $variation_id > 0) {
			$cart_item_key = $woocommerce->cart->add_to_cart( $product_id, 1, $variation_id, null, null);
			
			if (!is_user_logged_in()) {
				$woocommerce->session->set_customer_session_cookie(true);
			}
			$woocommerce->session->set( 'slzexploore_book_session_key_' . $cart_item_key,
										array( 'booking_type'  => 'accommodation',
												'booking_data' => $post_meta ));
		}
		echo '[SUCCESS]'.esc_url( get_permalink( get_option( 'woocommerce_cart_page_id' ) ) );
		exit;
	}
	
	// add tour to cart
	public function ajax_add_tour_to_cart() {
		global $woocommerce, $wp_locale;
		$thousands_sep = $wp_locale->number_format['thousands_sep'];
		$decimal_point = $wp_locale->number_format['decimal_point'];
		$localeconv    = localeconv ();
		$params    = $_POST['params'];
		$post_meta = array();
		$arr_number = array( 'tour_price', 'extra_price', 'total_price' );
		foreach( $params as $param ) {
			$key   = $param['name'];
			$value = $param['value'];
			if( $key == 'start_date' ){
				$key = 'tour_date';
			}
			if( in_array( $key, $arr_number ) ){
				$value = str_replace( $thousands_sep, '', $value );
				$value = str_replace( $decimal_point, $localeconv['decimal_point'], $value );
			}
			if( $key == 'description' ){
				$value = str_replace( array( '<span>', '</span>' ), '', $value );
				// extra item - not display in admin, only use to send mail
				$post_meta['extra_item'] = $value;
			}
			$post_meta[$key] = $value;
		}
		// deposit calculate
		$future_payment = 0;
		$deposit_amount = $post_meta['total_price'];
		if( isset( $post_meta['deposit_method'] ) && $post_meta['deposit_method'] == 'deposit' ){
			if( $post_meta['deposit_type'] == 'percent' ){
				$deposit_amount = intval( $post_meta['total_price'] ) * intval( $post_meta['deposit_amount'] ) / 100;
			}
			else{
				$deposit_amount = intval( $post_meta['adults'] ) * intval( $post_meta['deposit_amount'] );
			}
			$future_payment = floatval( $post_meta['total_price'] ) - floatval( $deposit_amount );
		}
		$post_meta['deposit_amount'] = $deposit_amount;
		$post_meta['future_payment'] = $future_payment;
		
		$tour_id     = $post_meta['tour'];
		// booking description
		$decimal 		 =  intval( Slzexploore_Core::get_theme_option('slz-currency-decimal') );
		$schedule_price  = $this->get_tour_schedule_price( $tour_id, $post_meta['tour_date'] );
		$price_adult     = round( $schedule_price['price_adult'], $decimal );
		$price_child     = round( $schedule_price['price_child'], $decimal );

		$is_discount   = get_post_meta( $tour_id, 'slzexploore_tour_is_discount', true );
		$discount_rate = get_post_meta( $tour_id, 'slzexploore_tour_discount_rate', true );
		$post_meta['description'] .= esc_html__( 'Adults Price : ', 'slzexploore-core' ).
								floatval( $price_adult ) * intval( $post_meta['adults'] ) . ', '.
								esc_html__( 'Children Price : ', 'slzexploore-core' ).
								floatval( $price_child ) * intval( $post_meta['children'] );
		if( !empty( $is_discount ) && !empty( $discount_rate ) ){
			$post_meta['description'] .= ', '. esc_html__( 'Discount : ', 'slzexploore-core' ).
										$discount_rate . '%';
		}
		// add to woocommerce cart
		$prefix     = 'tour';
		$tour_title = get_the_title( $tour_id );
		$tour_slug  = Slzexploore_Core_Com::get_post_id2name( $tour_id );
		
		$product_id = Slzexploore_Core_Com::get_post_name2id( $tour_slug, 'product' );
		if (!isset($product_id) || empty($product_id)) {
			$product_cat = $this->update_product_categories( $tour_id, 'slzexploore_tour_cat', esc_html__( 'Tours Products', 'slzexploore-core' ) );
			$atts = array();
			if( has_post_thumbnail( $tour_id ) ){
				$atts['thumbnail_id'] = get_post_thumbnail_id( $tour_id );
			}
			$product_id  = $this->create_woocommerce_product( $prefix, $tour_title, $tour_slug, $product_cat, $atts );
		}
		else{
			$this->update_available_product_categories( $tour_id, 'slzexploore_tour_cat', $product_id, 'Tours' );
		}
		
		$variation_args = array(
							'post_type'   => 'product_variation',
							'post_parent' => $product_id,
							'post_name'   => $tour_slug
						);
		$variation_obj  = get_posts($variation_args);
		if( !empty( $variation_obj ) ){
			$variation_id   = $variation_obj[0]->ID;
		}
		if (!isset($variation_id) || empty($variation_id)) {
			$variation_id = $this->create_woocommerce_product_variation( $prefix, $product_id, $tour_title, $tour_slug, $tour_id );
		}
		if ($product_id > 0 && $variation_id > 0) {
			$cart_item_key = $woocommerce->cart->add_to_cart( $product_id, 1, $variation_id, null, null);
			
			if (!is_user_logged_in()) {
				$woocommerce->session->set_customer_session_cookie(true);
			}
			$woocommerce->session->set( 'slzexploore_book_session_key_' . $cart_item_key,
										array( 'booking_type'  => 'tour',
												'booking_data' => $post_meta ));
		}
		echo '[SUCCESS]'.esc_url( get_permalink( get_option( 'woocommerce_cart_page_id' ) ) );
		exit;
	}
	
	// add car rent to cart
	public function ajax_add_car_rent_to_cart() {
		global $woocommerce, $wp_locale;
		$thousands_sep = $wp_locale->number_format['thousands_sep'];
		$decimal_point = $wp_locale->number_format['decimal_point'];
		$localeconv    = localeconv ();
		$params    = $_POST['params'];
		$post_meta = array();
		$arr_change_key = array('location' => 'drop_off_location');
		$arr_number     = array( 'price', 'extra_price', 'total_price' );
		foreach( $params as $param ) {
			$key   = $param['name'];
			$value = $param['value'];
			if( isset( $arr_change_key[$key] ) ){
				$key = $arr_change_key[$key];
				$location = Slzexploore_Core_Com::get_tax_options_by_slug( $value, 'slzexploore_car_location' );
				if( !empty( $location ) ){
					$value = $location->name;
				}
			}
			if( in_array( $key, $arr_number ) ){
				$value = str_replace( $thousands_sep, '', $value );
				$value = str_replace( $decimal_point, $localeconv['decimal_point'], $value );
			}
			if( $key == 'description' ){
				$value = str_replace( array( '<span>', '</span>' ), '', $value );
				// extra item - not display in admin, only use to send mail
				$post_meta['extra_item'] = $value;
			}
			$post_meta[$key] = $value;
		}
		// deposit calculate
		$future_payment = 0;
		$deposit_amount = $post_meta['total_price'];
		if( isset( $post_meta['deposit_method'] ) && $post_meta['deposit_method'] == 'deposit' ){
			if( $post_meta['deposit_type'] == 'percent' ){
				$deposit_amount = intval( $post_meta['total_price'] ) * intval( $post_meta['deposit_amount'] ) / 100;
			}
			else{
				$diff_day = abs( strtotime( $post_meta['date_to'] ) - strtotime( $post_meta['date_from'] ) );
				$numer_days = floor( $diff_day / (60*60*24) );
				if( $numer_days < 1 ){
					$numer_days = 1;
				}
				$deposit_amount = intval( $numer_days ) * intval( $post_meta['number'] ) * intval( $post_meta['deposit_amount'] );
			}
			$future_payment = floatval( $post_meta['total_price'] ) - floatval( $deposit_amount );
		}
		$post_meta['deposit_amount'] = $deposit_amount;
		$post_meta['future_payment'] = $future_payment;
		
		$car_id = $post_meta['car_id'];
		// booking description
		$price_car     = get_post_meta( $car_id, 'slzexploore_car_price', true );
		$is_discount   = get_post_meta( $car_id, 'slzexploore_car_is_discount', true );
		$discount_rate = get_post_meta( $car_id, 'slzexploore_car_discount_rate', true );
		$post_meta['description'] .= esc_html__( 'Car Rent Price : ', 'slzexploore-core' ). intval( $price_car );
		if( !empty( $is_discount ) && !empty( $discount_rate ) ){
			$post_meta['description'] .= ', '. esc_html__( 'Discount : ', 'slzexploore-core' ). $discount_rate . '%';
		}
		// add to woocommerce cart
		$prefix = 'car_rent';
		$car_title = get_the_title( $car_id );
		$car_slug  = Slzexploore_Core_Com::get_post_id2name( $car_id );
		
		$product_id = Slzexploore_Core_Com::get_post_name2id( $car_slug, 'product' );
		if (!isset($product_id) || empty($product_id)) {
			$product_cat = $this->update_product_categories( $car_id, 'slzexploore_car_cat', esc_html__( 'Cars Products', 'slzexploore-core' ) );
			$atts = array();
			if( has_post_thumbnail( $car_id ) ){
				$atts['thumbnail_id'] = get_post_thumbnail_id( $car_id );
			}
			$product_id  = $this->create_woocommerce_product( $prefix, $car_title, $car_slug, $product_cat, $atts );
		}
		else{
			$this->update_available_product_categories( $car_id, 'slzexploore_car_cat', $product_id, 'Cars' );
		}
		
		$variation_args = array(
							'post_type'   => 'product_variation',
							'post_parent' => $product_id,
							'post_name'   => $car_slug
						);
		$variation_obj  = get_posts($variation_args);
		if( !empty( $variation_obj ) ){
			$variation_id   = $variation_obj[0]->ID;
		}
		if (!isset($variation_id) || empty($variation_id)) {
			$variation_id = $this->create_woocommerce_product_variation( $prefix, $product_id, $car_title, $car_slug, $car_id);
		}
		if ($product_id > 0 && $variation_id > 0) {
			$cart_item_key = $woocommerce->cart->add_to_cart( $product_id, 1, $variation_id, null, null);
			
			if (!is_user_logged_in()) {
				$woocommerce->session->set_customer_session_cookie(true);
			}
			$woocommerce->session->set( 'slzexploore_book_session_key_' . $cart_item_key,
										array( 'booking_type'  => 'car_id',
												'booking_data' => $post_meta ));
		}
		echo '[SUCCESS]'.esc_url( get_permalink( get_option( 'woocommerce_cart_page_id' ) ) );
		exit;
	}
	
	// add cruise to cart
	public function ajax_add_cruise_to_cart() {
		global $woocommerce, $wp_locale;
		$thousands_sep = $wp_locale->number_format['thousands_sep'];
		$decimal_point = $wp_locale->number_format['decimal_point'];
		$localeconv    = localeconv ();
		$params    = $_POST['params'];
		$post_meta = array();
		$arr_number = array( 'price', 'extra_price', 'total_price' );
		foreach( $params as $param ) {
			if( in_array( $param['name'], $arr_number ) ){
				$param['value'] = str_replace( $thousands_sep, '', $param['value'] );
				$param['value'] = str_replace( $decimal_point, $localeconv['decimal_point'], $param['value'] );
			}
			if( $param['name'] == 'description' ){
				$param['value'] = str_replace( array( '<span>', '</span>' ), '', $param['value'] );
				// extra item - not display in admin, only use to send mail
				$post_meta['extra_item'] = $param['value'];
			}
			$post_meta[$param['name']] = $param['value'];
		}
		// deposit calculate
		$future_payment = 0;
		$deposit_amount = $post_meta['total_price'];
		if( isset( $post_meta['deposit_method'] ) && $post_meta['deposit_method'] == 'deposit' ){
			if( $post_meta['deposit_type'] == 'percent' ){
				$deposit_amount = intval( $post_meta['total_price'] ) * intval( $post_meta['deposit_amount'] ) / 100;
			}
			else{
				$deposit_amount = intval( $post_meta['number'] ) * intval( $post_meta['deposit_amount'] );
			}
			$future_payment = floatval( $post_meta['total_price'] ) - floatval( $deposit_amount );
		}
		$post_meta['deposit_amount'] = $deposit_amount;
		$post_meta['future_payment'] = $future_payment;

		$cruise_id     = $post_meta['cruise_id'];
		$cabin_id      = $post_meta['cabin_type_id'];
		$is_discount   = get_post_meta( $cruise_id, 'slzexploore_cruise_is_discount', true );
		$discount_rate = get_post_meta( $cruise_id, 'slzexploore_cruise_discount_rate', true );
		// booking description
		if ( ! empty( $cabin_id ) ) {
			$price_cabin = get_post_meta( $cabin_id, 'slzexploore_cabin_price', true );
			$post_meta['description'] .= esc_html__( 'Cruise Price : ', 'slzexploore-core' ) . intval( $price_cabin );
		} else {
			$price_per_adult = get_post_meta( $cruise_id, 'slzexploore_cruise_price_adult', true );
			$price_per_child = get_post_meta( $cruise_id, 'slzexploore_cruise_price_child', true );
			$post_meta['description'] .= esc_html__( 'Adult Price : ', 'slzexploore-core' ) . intval( $price_per_adult );
			$post_meta['description'] .= esc_html__( ', Children Price : ', 'slzexploore-core' ) . intval( $price_per_child );
		}
		if ( ! empty( $is_discount ) && ! empty( $discount_rate ) ) {
			$post_meta['description'] .= ', ' . esc_html__( 'Discount : ', 'slzexploore-core' ) . $discount_rate . '%';
		}
		// add to woocommerce cart
		$prefix       = 'cruise';
		$cruise_title = get_the_title( $cruise_id );
		$cruise_slug  = Slzexploore_Core_Com::get_post_id2name( $cruise_id );
		
		$product_id = Slzexploore_Core_Com::get_post_name2id( $cruise_slug, 'product' );
		if (!isset($product_id) || empty($product_id)) {
			$product_cat = $this->update_product_categories( $cruise_id, 'slzexploore_cruise_cat', esc_html__( 'Cruises Products', 'slzexploore-core' ) );
			$atts = array();
			if( has_post_thumbnail( $cruise_id ) ){
				$atts['thumbnail_id'] = get_post_thumbnail_id( $cruise_id );
			}
			$product_id  = $this->create_woocommerce_product( $prefix, $cruise_title, $cruise_slug, $product_cat, $atts );
		}
		else{
			$this->update_available_product_categories( $cruise_id, 'slzexploore_cruise_cat', $product_id, 'Cruises' );
		}
		
		$variation_args = array(
							'post_type'   => 'product_variation',
							'post_parent' => $product_id,
							'post_name'   => $cruise_slug
						);
		$variation_obj  = get_posts($variation_args);
		if( !empty( $variation_obj ) ){
			$variation_id   = $variation_obj[0]->ID;
		}
		if (!isset($variation_id) || empty($variation_id)) {
			$variation_id = $this->create_woocommerce_product_variation($prefix, $product_id, $cruise_title, $cruise_slug, $cruise_id);
		}
		if ($product_id > 0 && $variation_id > 0) {
			$cart_item_key = $woocommerce->cart->add_to_cart( $product_id, 1, $variation_id, null, null);
			
			if (!is_user_logged_in()) {
				$woocommerce->session->set_customer_session_cookie(true);
			}
			$woocommerce->session->set( 'slzexploore_book_session_key_' . $cart_item_key,
										array( 'booking_type' => 'cruise_id',
												'booking_data' => $post_meta ));
		}
		echo '[SUCCESS]'.esc_url( get_permalink( get_option( 'woocommerce_cart_page_id' ) ) );
		exit;
	}

	public function update_product_categories( $post_id, $taxonomy, $parent ){
		$product_cat = array();
		$parent_exists = term_exists( $parent, 'product_cat' );
		if( ! $parent_exists ){
			$parent_exists = wp_insert_term( $parent, 'product_cat' );
		}
		if( isset( $parent_exists['term_id'] ) && !empty( $parent_exists['term_id'] ) ){
			$parent_id = $parent_exists['term_id'];
			$terms = get_the_terms( $post_id, $taxonomy );
			if ($terms && ! is_wp_error($terms)) {
				foreach ($terms as $term) {
					$term_exists = term_exists( $term->name, 'product_cat', $parent_id );
					if( ! $term_exists ){
						$term_exists = wp_insert_term( $term->name, 'product_cat', array( 'parent'=> $parent_id ) );
					}
					if( isset( $term_exists['term_id'] ) && !empty( $term_exists['term_id'] ) ){
						$product_cat[] = intval( $term_exists['term_id'] );
					}
				}
			}
			else{
				$product_cat[] = intval( $parent_id );
			}
		}
		return $product_cat;
	}

	public function update_available_product_categories( $post_id, $taxonomy, $product_id, $parent ){
		$product_cat = array();
		$parent_exists = term_exists( $parent, 'product_cat' );
		if( isset( $parent_exists['term_id'] ) && !empty( $parent_exists['term_id'] ) ){
			$parent_id = $parent_exists['term_id'];
			$terms = get_the_terms( $post_id, $taxonomy );
			if ($terms && ! is_wp_error($terms)) {
				foreach ($terms as $term) {
					$term_exists = term_exists( $term->name, 'product_cat', $parent_id );
					if( ! $term_exists ){
						$term_exists = wp_insert_term( $term->name, 'product_cat', array( 'parent'=> $parent_id ) );
					}
					if( isset( $term_exists['term_id'] ) && !empty( $term_exists['term_id'] ) ){
						$product_cat[] = intval( $term_exists['term_id'] );
					}
				}
			}
			else{
				$product_cat[] = intval( $parent_id );
			}
			wp_set_object_terms( $product_id, $product_cat, 'product_cat' );
		}
	}
	
	public function create_woocommerce_product( $prefix, $product_title, $product_slug, $product_cat, $atts = array() ) {
		$new_post = array(
			'post_title' 		=> $product_title,
			'post_content' 		=> esc_html__('This is a variable product used for booking processed with WooCommerce', 'slzexploore-core'),
			'post_status' 		=> 'publish',
			'post_name' 		=> $product_slug,
			'post_type' 		=> 'product',
			'comment_status' 	=> 'closed'
		);
		$product_id 			= wp_insert_post( $new_post );
		$sku					= $this->random_sku( $prefix, 6 );
		update_post_meta( $product_id, '_sku', $sku );
		wp_set_object_terms( $product_id, 'variable', 'product_type' );
		wp_set_object_terms( $product_id, $product_cat, 'product_cat' );

		// hide this product in front end
		$visibility_ids = wc_get_product_visibility_term_ids();
		if( isset( $visibility_ids['exclude-from-catalog'] ) && isset( $visibility_ids['exclude-from-search'] ) ){
			$product_visibility = array(
										$visibility_ids['exclude-from-catalog'],
										$visibility_ids['exclude-from-search']
									);
			wp_set_object_terms( $product_id, $product_visibility, 'product_visibility' );
		}

		if( isset( $atts['thumbnail_id'] ) && !empty( $atts['thumbnail_id'] ) ){
			 set_post_thumbnail( $product_id, $atts['thumbnail_id'] );
		}
		
		$product_attributes = array(
			$prefix   => array(
				'name'			=> $prefix,
				'value'			=> '',
				'is_visible' 	=> '1',
				'is_variation' 	=> '1',
				'is_taxonomy' 	=> '0'
			)
		);
		update_post_meta( $product_id, '_product_attributes', $product_attributes);

		return $product_id;
	}
	public function random_sku($prefix = '', $len = 6) {
		$str = '';
		for ($i = 0; $i < $len; $i++) {
			$str .= rand(0, 9);
		}
		return $prefix . $str;
	}
	public function create_woocommerce_product_variation( $prefix, $product_id, $title, $slug, $id ) {
		$new_post = array(
			'post_title' 		=> $title,
			'post_content' 		=> esc_html__('This is a product variation', 'slzexploore-core'),
			'post_status' 		=> 'publish',
			'post_type' 		=> 'product_variation',
			'post_parent'		=> $product_id,
			'post_name' 		=> $slug,
			'comment_status' 	=> 'closed'
		);
		$variation_id 			= wp_insert_post($new_post);
		update_post_meta($variation_id, '_stock_status', 		'instock');
		update_post_meta($variation_id, '_sold_individually', 	'yes');
		update_post_meta($variation_id, '_virtual', 			'yes');
		update_post_meta($variation_id, '_manage_stock', 'no' );
		update_post_meta($variation_id, '_downloadable', 'no' );
		update_post_meta($variation_id, 'attribute_' . $prefix, $id);
		return $variation_id;
	}
	
	public function variation_is_purchasable( $purchasable, $product_variation ) {
		$object_class = get_class($product_variation);
		if( $object_class == 'WC_Product_Variation' ) {
			$purchasable = true;
		}
		return $purchasable;
	}
	public function is_sold_individually( $return, $instance ) {
		if( isset( $instance->post_type ) && $instance->post_type == 'product_variation' ) {
			return true;
		}
		return false; // allow change quantity
	}
	public function cart_item_thumbnail( $image, $cart_item, $cart_item_key ) {
		if (isset($cart_item['data'])) {
			$object_class = get_class($cart_item['data']);
			if ($object_class == 'WC_Product_Variation' && isset($cart_item['data']) ) {
				global $woocommerce;
				$cart_item_meta = $woocommerce->session->get('slzexploore_book_session_key_' . $cart_item_key);
				if ( isset( $cart_item_meta['booking_data'] ) && isset( $cart_item_meta['booking_type'] ) ) {
					$post_id = $cart_item_meta['booking_data'][$cart_item_meta['booking_type']];
					if ( !empty( $post_id ) && has_post_thumbnail( $post_id ) ) {
							$image = get_the_post_thumbnail( $post_id, 'thumbnail' );
					}
				}
			}
		}
		return $image;
	}
	public function cart_item_name($product_title, $cart_item, $cart_item_key){
		global $woocommerce;
		if (isset($cart_item['data'])) {
			$item_data = $cart_item['data'];
			$object_class = get_class($item_data);
			if ( !$item_data || !$item_data->get_id() || $object_class != 'WC_Product_Variation') {
				return $product_title;
			}
			$cart_item_meta = $woocommerce->session->get('slzexploore_book_session_key_' . $cart_item_key);
			
			if ( isset($cart_item_meta['booking_data']) && isset( $cart_item_meta['booking_type'] ) ) {
				$booking_type = $cart_item_meta['booking_type'];
				$booking_data = $cart_item_meta['booking_data'];
				if ( $booking_type == 'accommodation' ) {
					$prefix         = 'slzexploore_book_';
					$hotel_id       = $booking_data['accommodation'];
					$hotel_title    = get_the_title( $hotel_id );
					$hotel_url      = get_permalink( $hotel_id );
					$room_id        = $booking_data['room_type'];
					$room_type      = get_the_title( $room_id );
					$number_room    = $booking_data['number_room'];
					$date_format    = get_option('date_format');
					$check_in       = $booking_data['check_in_date'];
					$check_in       = date($date_format, strtotime($check_in));
					$check_out      = $booking_data['check_out_date'];
					$check_out      = date($date_format, strtotime($check_out));
					$adults         = $booking_data['adults'];
					$children       = $booking_data['children'];
					
					$product_title  = '';
					$product_title .= sprintf('<a href="%1$s">%2$s</a>',
												esc_url( $hotel_url ), esc_html( $hotel_title ) ) . '<br/>';
					$product_title .= sprintf( esc_html__('Room Type: %s', 'slzexploore-core'),
												esc_html( $room_type ) ) . '<br/>';
					$product_title .= sprintf( esc_html__('Number room: %s', 'slzexploore-core'),
												esc_html( $number_room ) ) . '<br/>';
					$product_title .= sprintf( esc_html__('Check in date: %s', 'slzexploore-core'),
												esc_html( $check_in ) ) . '<br/>';
					$product_title .= sprintf( esc_html__('Check out date: %s', 'slzexploore-core'),
												esc_html( $check_out ) ) . '<br/>';
					$product_title .= sprintf (esc_html__('Adults: %s', 'slzexploore-core'),
												esc_html( $adults ) ) . '<br/>';
					if( !empty( $children ) ){
						$product_title .= sprintf(esc_html__('Children: %s', 'slzexploore-core'),
													esc_html( $children ) ) . '<br/>';
					}
				}
				elseif ( $booking_type == 'tour' ) {
					$prefix       = 'slzexploore_tbook_';
					$tour_id      = $booking_data['tour'];
					$tour_title   = get_the_title( $tour_id );
					$tour_url     = get_permalink( $tour_id );
					$date_format  = get_option('date_format');
					$tour_date    = date($date_format, strtotime($booking_data['tour_date']));
					$adults       = $booking_data['adults'];
					$children     = $booking_data['children'];
					
					$product_title  = '';
					$product_title .= sprintf('<a href="%1$s">%2$s</a>',
												esc_url( $tour_url ), esc_html( $tour_title ) ) . '<br/>';
					$product_title .= sprintf( esc_html__('Departure date: %s', 'slzexploore-core'),
												esc_html( $tour_date ) ) . '<br/>';
					$product_title .= sprintf (esc_html__('Adults: %s', 'slzexploore-core'),
												esc_html( $adults ) ) . '<br/>';
					if( !empty( $children ) ){
						$product_title .= sprintf(esc_html__('Children: %s', 'slzexploore-core'),
													esc_html( $children ) ) . '<br/>';
					}
				}
				elseif ( $booking_type == 'car_id' ) {
					$prefix      = 'slzexploore_cbook_';
					$car_id      = $booking_data['car_id'];
					$car_title   = get_the_title( $car_id );
					$car_url     = get_permalink( $car_id );
					$number      = $booking_data['number'];
					$date_format = get_option('date_format');
					$start_date  = date($date_format, strtotime($booking_data['date_from']));
					$end_date    = date($date_format, strtotime($booking_data['date_to']));
					$drop_off    = $booking_data['drop_off_location'];
					
					$product_title  = '';
					$product_title .= sprintf('<a href="%1$s">%2$s</a>', esc_url( $car_url ), esc_html( $car_title ) ) . '<br/>';
					$product_title .= sprintf(esc_html__('Number: %s', 'slzexploore-core'), esc_html( $number ) ) . '<br/>';
					$product_title .= sprintf(esc_html__('Start date: %s', 'slzexploore-core'), esc_html( $start_date ) ) . '<br/>';
					$product_title .= sprintf(esc_html__('Return date: %s', 'slzexploore-core'), esc_html( $end_date ) ) . '<br/>';
					if( !empty( $drop_off ) ){
						$product_title .= sprintf(esc_html__('Drop off location: %s', 'slzexploore-core'), esc_html( $drop_off ) ) . '<br/>';
					}
				}
				elseif ( $booking_type == 'cruise_id' ) {
					$prefix        = 'slzexploore_crbook_';
					$cruise_id     = $booking_data['cruise_id'];
					$cruise_title  = get_the_title( $cruise_id );
					$cruise_url    = get_permalink( $cruise_id );
					$number        = $booking_data['number'];
					$date_format   = get_option('date_format');
					$start_date    = date($date_format, strtotime($booking_data['start_date']));
					$adults        = $booking_data['adults'];
					$children      = $booking_data['children'];
					$cabin_type_id = $booking_data['cabin_type_id'];
					$cabin_type    = get_the_title( $cabin_type_id );
					
					$product_title  = '';
					$product_title .= sprintf('<a href="%1$s">%2$s</a>',
												esc_url( $cruise_url ), esc_html( $cruise_title ) ) . '<br/>';
					$product_title .= sprintf( esc_html__('Departure Date: %s', 'slzexploore-core'),
							esc_html( $start_date ) ) . '<br/>';
					if ( ! empty( $cabin_type_id ) ) {
						$product_title .= sprintf( esc_html__('Cabin Type : %s', 'slzexploore-core'),
								esc_html( $cabin_type ) ) . '<br/>';
						$product_title .= sprintf( esc_html__('Number: %s', 'slzexploore-core'), esc_html( $number ) ) . '<br/>';
					}
					$product_title .= sprintf( esc_html__('Adults: %s', 'slzexploore-core'),
												esc_html( $adults ) ) . '<br/>';
					if( !empty( $children ) ){
						$product_title .= sprintf( esc_html__('Children: %s', 'slzexploore-core'),
													esc_html( $children ) ) . '<br/>';
					}
				}
			}
			return $product_title;
		}
	}
	public function add_custom_total_price($cart_object) {
		global $woocommerce;
		foreach ( $cart_object->cart_contents as $key => $value ) {
			$cart_item_meta = $woocommerce->session->get('slzexploore_book_session_key_' . $key);
			$product = $value['data'];
			if ( isset( $cart_item_meta['booking_data']['deposit_amount'] ) ) {
				$deposit_amount = $cart_item_meta['booking_data']['deposit_amount'];
				if( !empty( $deposit_amount ) ){
					$product->set_price( $deposit_amount );
				}
			}
		}
	}
	public function display_item_price( $output, $cart_item, $cart_item_key ) {
		global $woocommerce;
		if (isset($cart_item['data'])) {
			$item_data = $cart_item['data'];
			$object_class = get_class($item_data);
			if ( !$item_data || !$item_data->get_id() || $object_class != 'WC_Product_Variation') {
				return $output;
			}
			$cart_item_meta = $woocommerce->session->get('slzexploore_book_session_key_' . $cart_item_key);
			if ( isset($cart_item_meta['booking_data']) && isset( $cart_item_meta['booking_type'] ) ) {
				$arr_meta = array(
					'accommodation'  => 'total',
					'tour'           => 'total_price',
					'car_id'         => 'total_price',
					'cruise_id'      => 'total_price'
				);
				$key = $arr_meta[$cart_item_meta['booking_type']];
				$total_price = $cart_item_meta['booking_data'][$key];
				if ( !empty( $total_price ) ) {
					$output = wc_price( $total_price );
				}
			}
		}
		return $output;
	}
	public function display_item_subtotal( $output, $cart_item, $cart_item_key ) {
		global $woocommerce;
		if (isset($cart_item['data'])) {
			$item_data = $cart_item['data'];
			$object_class = get_class($item_data);
			if ( !$item_data || !$item_data->get_id() || $object_class != 'WC_Product_Variation') {
				return $output;
			}
			$cart_item_meta = $woocommerce->session->get('slzexploore_book_session_key_' . $cart_item_key);
			if ( isset($cart_item_meta['booking_data']) && isset( $cart_item_meta['booking_type'] ) ) {
				$arr_meta = array(
					'accommodation'  => 'total',
					'tour'           => 'total_price',
					'car_id'         => 'total_price',
					'cruise_id'      => 'total_price'
				);
				$key = $arr_meta[$cart_item_meta['booking_type']];
				$total_price = $cart_item_meta['booking_data'][$key];
				$deposit_amount = $cart_item_meta['booking_data']['deposit_amount'];
				if ( !empty( $total_price ) && $total_price != $deposit_amount ) {
					$output .= '<br/><small>' . sprintf( esc_html__( '%s payable in total', 'slzexploore-core' ), wc_price( $total_price ) ) . '</small>';
				}
			}
		}
		return $output;
	}
	public function checkout_order_processed( $order_id, $posted ) {
		global $woocommerce;
		$order = new WC_Order( $order_id );
		if ($order != null) {
			$status = $order->get_status();
			if ($woocommerce->cart != null) {
				foreach ( $woocommerce->cart->cart_contents as $key => $value ) {
					$post_meta = array( 'first_name', 'last_name', 'email', 'company', 'phone', 'address', 'country',
										'city', 'postcode', 'customer_des' );
					$post_type = '';
					$cart_item_meta = $woocommerce->session->get('slzexploore_book_session_key_' . $key);
					if ( isset( $cart_item_meta['booking_data'] ) && isset( $cart_item_meta['booking_type'] ) ) {
						$booking_data = $cart_item_meta['booking_data'];
						$booking_type = $cart_item_meta['booking_type'];
						if ( $booking_type == 'accommodation' ){
							$post_type   = 'slzexploore_book';
						}
						elseif ( $booking_type == 'tour' ){
							$post_type   = 'slzexploore_tbook';
						}
						elseif ( $booking_type == 'car_id' ){
							$post_type   = 'slzexploore_cbook';
						}
						elseif ( $booking_type == 'cruise_id' ){
							$post_type   = 'slzexploore_crbook';
						}
						$post_args = array(
							'post_status' => 'publish',
							'post_type'   => $post_type
						);
						$booking_id = wp_insert_post( $post_args, true );
						if( !is_wp_error( $booking_id ) ){
							foreach( $booking_data as $key=>$value ){
								update_post_meta( $booking_id, $post_type . '_' . $key, $value );
							}
							foreach( $post_meta as $key ){
								if( $key == 'address' ){
									$post_key = 'billing_address_1';
								}
								elseif( $key == 'customer_des' ){
									$post_key = 'order_comments';
								}
								else{
									$post_key = 'billing_' . $key;
								}
								if( isset( $posted[$post_key] ) && !empty( $posted[$post_key] ) ){
									update_post_meta( $booking_id, $post_type . '_' . $key, $posted[$post_key] );
								}
							}
							// update payment methods
							$payment_method = get_post_meta( $order_id, '_payment_method_title', true );
							if( !empty( $payment_method ) ){
								$description = get_post_meta( $booking_id, $post_type . '_description', true );
								$description .= ', ' . esc_html__( 'Payment Method : ', 'slzexploore-core' ) . esc_html( $payment_method );
								update_post_meta( $booking_id, $post_type . '_description', $description );
							}
							// update order, sku
							$product_slug  = Slzexploore_Core_Com::get_post_id2name( $booking_data[$booking_type] );
							$product_id = Slzexploore_Core_Com::get_post_name2id( $product_slug, 'product' );
							$sku = get_post_meta( $product_id, '_sku', true );
							update_post_meta( $booking_id, $post_type . '_order', $order_id );
							update_post_meta( $booking_id, $post_type . '_sku', $sku );
							// status
							$status = wp_set_object_terms( $booking_id, esc_html__( 'On Hold', 'slzexploore-core' ), $post_type . '_status' );
							if( isset( $status[0] ) && !empty( $status[0] ) ){
								update_post_meta( $booking_id, $post_type . '_status', $status[0] );
							}
							// customer ip
							$customer_ip = get_post_meta( $order_id, '_customer_ip_address', true );
							if( !empty( $customer_ip ) ){
								update_post_meta( $booking_id, $post_type . '_customer_ip', $customer_ip );
							}
						}
					}
				}
			}
		}
	}
	public function before_checkout_process(){
		global $woocommerce;
		if ($woocommerce->cart != null) {
			$message = '';
			foreach ( $woocommerce->cart->cart_contents as $key => $value ) {
				$cart_item_meta = $woocommerce->session->get('slzexploore_book_session_key_' . $key);
				if ( isset( $cart_item_meta['booking_data'] ) && isset( $cart_item_meta['booking_type'] ) ) {
					$booking_data = $cart_item_meta['booking_data'];
					$booking_type = $cart_item_meta['booking_type'];
					if ( $booking_type == 'accommodation' ){
						$available_room = $this->check_hotel_available( $booking_data['room_type'], $booking_data['check_in_date'], $booking_data['check_out_date'] );
						$booking_room = intval($booking_data['number_room']);
						if( intval($booking_room) > intval($available_room) ){
							$message = sprintf( esc_html__( 'Sorry, we do not enough available rooms to your hotel booking (%s available rooms).', 'slzexploore-core' ), intval( $available_room ) );
							break;
						}
					}
					elseif ( $booking_type == 'tour' ){
						$available_seat = $this->check_tour_available( $booking_data['tour'], $booking_data['tour_date'] );
						$booking_person = intval($booking_data['adults']) + intval($booking_data['children']);
						if( intval($booking_person) > intval($available_seat) ){
							$message = sprintf( esc_html__( 'Sorry, we do not enough available seats to your tour booking (%s available seats).', 'slzexploore-core' ), intval( $available_seat ) );
							break;
						}
					}
					elseif ( $booking_type == 'car_id' ){
						$available_car = $this->check_car_available( $booking_data['car_id'], $booking_data['date_from'], $booking_data['date_to'] );
						$booking_number   = intval($booking_data['number']) ;
						if( intval($booking_number) > intval($available_car) ){
							$message = sprintf( esc_html__( 'Sorry, we do not enough available cars to your booking (%s available cars).', 'slzexploore-core' ), intval( $available_car ) );
							break;
						}
					}
					elseif ( $booking_type == 'cruise_id' ){
						$available_seat = $this->check_cruise_available( $booking_data['start_date'], $booking_data['cruise_id'], $booking_data['cabin_type_id'] );
						$available_seat = $available_seat < 0 ? 0 : $available_seat;
						$booking_number = intval( $booking_data['number'] );
						if ( ! empty( $booking_data['cabin_type_id'] ) ) {
							if ( intval( $booking_number ) > intval( $available_seat ) ) {
								$message = sprintf( esc_html__( 'Sorry, we do not enough available cabins to your cruise booking (%s available cabins).', 'slzexploore-core' ), intval( $available_seat ) );
								break;
							}
						} else {
							if ( ( intval( $booking_data['adults'] ) + intval( $booking_data['children'] ) ) > intval( $available_seat ) ) {
								$message = sprintf( esc_html__( 'Sorry, we do not enough available seats to your cruise booking (%s available seats).', 'slzexploore-core' ), intval( $available_seat ) );
								break;
							}
						}
					}
				}
			}
			if( !empty( $message ) ){
				$url = get_permalink( get_option( 'woocommerce_cart_page_id' ) );
				$message .= '<a class="button" href="'.esc_url( $url ).'">'. esc_html__( 'View Cart', 'slzexploore-core') .'</a>';
				wc_add_notice( $message, 'error' );
			}
		}
	}
	public function cancelled_order( $order_id ) {
		if( $order_id ){
			$arr_post_type = array( 'slzexploore_book', 'slzexploore_tbook', 'slzexploore_cbook', 'slzexploore_crbook' );
			foreach( $arr_post_type as $post_type ){
				$args = array(
							'post_type'      => $post_type,
							'post_status'    => 'publish',
							'posts_per_page' => -1,
							'meta_key'       => $post_type . '_order',
							'meta_value'     => $order_id
						);
				$query_posts = get_posts( $args );
				if( $query_posts ){
					foreach ( $query_posts as $post ){
						$status = wp_set_object_terms( $post->ID, esc_html__( 'Cancelled', 'slzexploore-core' ), $post_type . '_status' );
						if( isset( $status[0] ) && !empty( $status[0] ) ){
							update_post_meta( $post->ID, $post_type . '_status', $status[0] );
						}
					}
					wp_reset_postdata();
				}
			}
		}
	}
	public function add_order_item_meta( $item_id, $values, $cart_item_key ){
		global $woocommerce;
		$cart_item_meta = $woocommerce->session->get('slzexploore_book_session_key_' . $cart_item_key);
		if ( isset($cart_item_meta['booking_data']) && isset( $cart_item_meta['booking_type'] ) ) {
			$booking_type = $cart_item_meta['booking_type'];
			$booking_data = $cart_item_meta['booking_data'];
			if ( $booking_type == 'accommodation' ) {
				$room_type      = get_the_title( $booking_data['room_type'] );
				$number_room    = $booking_data['number_room'];
				$date_format    = get_option('date_format');
				$check_in       = date( $date_format, strtotime( $booking_data['check_in_date'] ) );
				$check_out      = date( $date_format, strtotime( $booking_data['check_out_date'] ) );
				$adults         = $booking_data['adults'];
				$children       = $booking_data['children'];
				
				wc_add_order_item_meta( $item_id, esc_html__('Room Type', 'slzexploore-core'), esc_attr( $room_type ), true);
				wc_add_order_item_meta( $item_id, esc_html__('Number room', 'slzexploore-core'), esc_attr( $number_room ), true);
				wc_add_order_item_meta( $item_id, esc_html__('Check in date', 'slzexploore-core'), esc_attr( $check_in ), true);
				wc_add_order_item_meta( $item_id, esc_html__('Check out date', 'slzexploore-core'), esc_attr( $check_out ), true);
				wc_add_order_item_meta( $item_id, esc_html__('Adults', 'slzexploore-core'), esc_attr( $adults ), true);
				if( !empty( $children ) ){
					wc_add_order_item_meta( $item_id, esc_html__('Children', 'slzexploore-core'), esc_attr( $children ), true);
				}
			}
			elseif ( $booking_type == 'car_id' ) {
				$number      = $booking_data['number'];
				$date_format = get_option('date_format');
				$start_date  = date($date_format, strtotime($booking_data['date_from']));
				$end_date    = date($date_format, strtotime($booking_data['date_to']));
				$drop_off    = $booking_data['drop_off_location'];
				
				wc_add_order_item_meta( $item_id, esc_html__('Number', 'slzexploore-core'), esc_attr( $number ), true);
				wc_add_order_item_meta( $item_id, esc_html__('Start Date', 'slzexploore-core'), esc_attr( $start_date ), true);
				wc_add_order_item_meta( $item_id, esc_html__('Return Date', 'slzexploore-core'), esc_attr( $end_date ), true);
				if( !empty( $drop_off ) ){
					wc_add_order_item_meta( $item_id, esc_html__('Drop Off Location', 'slzexploore-core'), esc_attr( $drop_off ), true);
				}
			}
			elseif ( $booking_type == 'cruise_id' ) {
				$cabin_type    = get_the_title( $booking_data['cabin_type_id'] );
				$number        = $booking_data['number'];
				$date_format   = get_option('date_format');
				$start_date    = date($date_format, strtotime($booking_data['start_date']));
				$adults        = $booking_data['adults'];
				$children      = $booking_data['children'];

				if( ! empty( $booking_data['cabin_type_id'] ) ) {
					wc_add_order_item_meta( $item_id, esc_html__('Cabin Type', 'slzexploore-core'), esc_attr( $cabin_type ), true);
				}
				wc_add_order_item_meta( $item_id, esc_html__('Departure Date', 'slzexploore-core'), esc_attr( $start_date ), true);
				wc_add_order_item_meta( $item_id, esc_html__('Number', 'slzexploore-core'), esc_attr( $number ), true);
				wc_add_order_item_meta( $item_id, esc_html__('Adults', 'slzexploore-core'), esc_attr( $adults ), true);
				if( !empty( $children ) ){
					wc_add_order_item_meta( $item_id, esc_html__('Children', 'slzexploore-core'), esc_attr( $children ), true);
				}
			}
			elseif ( $booking_type == 'tour' ) {
				$date_format  = get_option('date_format');
				$tour_date    = date($date_format, strtotime($booking_data['tour_date']));
				$adults       = $booking_data['adults'];
				$children     = $booking_data['children'];
				
				wc_add_order_item_meta( $item_id, esc_html__('Departure Date', 'slzexploore-core'), esc_attr( $tour_date ), true);
				wc_add_order_item_meta( $item_id, esc_html__('Adults', 'slzexploore-core'), esc_attr( $adults ), true);
				if( !empty( $children ) ){
					wc_add_order_item_meta( $item_id, esc_html__('Children', 'slzexploore-core'), esc_attr( $children ), true);
				}
			}
		}
	}
	public function add_email_headers( $headers, $object, $order ) {
		$order_id = $order->id;
		$send_owner_email = Slzexploore::get_option('slz-cruise-send-owner-email');
		if( $order_id && $send_owner_email ){
			$headers  = array( "MIME-Version: 1.0", "Content-type:text/html;charset=UTF-8" );
			$arr_post_type = array( 'slzexploore_book', 'slzexploore_tbook', 'slzexploore_cbook', 'slzexploore_crbook' );
			$mail_cc  = array();
			$mail_bcc = array();
			$to_email = '';
			foreach( $arr_post_type as $post_type ){
				$args = array(
							'post_type'      => $post_type,
							'post_status'    => 'publish',
							'posts_per_page' => -1,
							'meta_key'       => $post_type . '_order',
							'meta_value'     => $order_id
						);
				$query_posts = get_posts( $args );
				if( $query_posts ){
					foreach ( $query_posts as $post ){
						if( $post_type == 'slzexploore_book' ){
							$hotel_id   = get_post_meta( $post->ID, $post_type . '_accommodation', true );
							$mail_cc[]  = get_post_meta( $hotel_id, 'slzexploore_hotel_mail_cc', true );
							$mail_bcc[] = get_post_meta( $hotel_id, 'slzexploore_hotel_mail_bcc', true );
							$to_email   = Slzexploore::get_option('slz-hotel-confirm-email-to');
						}
						elseif( $post_type == 'slzexploore_cbook' ){
							$car_id     = get_post_meta( $post->ID, $post_type . '_car_id', true );
							$mail_cc[]  = get_post_meta( $car_id, 'slzexploore_car_mail_cc', true );
							$mail_bcc[] = get_post_meta( $car_id, 'slzexploore_car_mail_bcc', true );
							$to_email   = Slzexploore::get_option('slz-car-confirm-email-to');
						}
						elseif( $post_type == 'slzexploore_crbook' ){
							$cruise_id  = get_post_meta( $post->ID, $post_type . '_cruise_id', true );
							$mail_cc[]  = get_post_meta( $cruise_id, 'slzexploore_cruise_mail_cc', true );
							$mail_bcc[] = get_post_meta( $cruise_id, 'slzexploore_cruise_mail_bcc', true );
							$to_email   = Slzexploore::get_option('slz-cruise-confirm-email-to');
						}
						elseif( $post_type == 'slzexploore_tbook' ){
							$tour_id    = get_post_meta( $post->ID, $post_type . '_tour', true );
							$mail_cc[]  = get_post_meta( $tour_id, 'slzexploore_tour_mail_cc', true );
							$mail_bcc[] = get_post_meta( $tour_id, 'slzexploore_tour_mail_bcc', true );
							$to_email   = Slzexploore::get_option('slz-tour-confirm-email-to');
						}
					}
					wp_reset_postdata();
				}
			}
			if( !empty( $to_email ) && is_email( $to_email ) ){
				$mail_bcc[] = $to_email;
			}
			if( !empty( $mail_cc ) ){
				$mail_cc   = implode( ',', $mail_cc );
				$mail_cc   = slzexploore_check_valid_mail( $mail_cc );
				$headers[] = "Cc: " . $mail_cc;
			}
			if( !empty( $mail_bcc ) ){
				$mail_bcc  = implode( ',', $mail_bcc );
				$mail_bcc  = slzexploore_check_valid_mail( $mail_bcc );
				$headers[] = "Bcc: " . $mail_bcc;
			}
		}
		return $headers;
	}

}