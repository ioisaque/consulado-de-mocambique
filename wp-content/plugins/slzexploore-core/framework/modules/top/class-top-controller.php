<?php
/**
 * Controller Top.
 * 
 * @since 1.0
 */
Slzexploore_Core::load_class( 'Abstract' );

class Slzexploore_Core_Top_Controller extends Slzexploore_Core_Abstract {

	public function ajax_accommodation_pagination(){
		$atts  = $_POST['params']['atts'];
		$data  = $_POST['params']['data'];
		$page  = $_POST['params']['page'];
		$base  = $_POST['params']['base'];
		$atts['paged'] = $page;
		$this->render( 'ajax-accommodation-list', array( 'atts' => $atts, 'query_args' => array(), 'data' => $data, 'base' => $base ) );
		exit;
	}
	
	public function ajax_search_accommodation(){
		$params  = $_POST['params']['data'];
		$base    = isset($_POST['params']['base'])? $_POST['params']['base']: '';
		$atts_more = array();
		if(isset($_POST['params']['atts_more'])){
			$atts_more = $_POST['params']['atts_more'];
		}
		$data = array();
		foreach( $params as $param ) {
			$multi_value = array( 'accommodation', 'location', 'facilities', 'rating', 'review_rating' );
			if( in_array( $param['name'], $multi_value ) ) {
				$data[$param['name']][] = $param['value'];
			}
			else {
				$data[$param['name']] = $param['value'];
			}
		}
		$atts = array();
		$query_args = array();
		$model = new Slzexploore_Core_Accommodation();
		$atts['pagination'] = 'yes';
		
		if(!empty($atts_more)){
			$atts = array_merge($atts,$atts_more);
		}else{
			$atts['btn_book']   = esc_html__( 'Book Now', 'slzexploore-core' );
			$atts['limit_post'] = Slzexploore::get_option('slz-hotel-posts');
		}
		$model->get_search_atts( $atts, $query_args, $data );
		if(!empty($atts_more)){
			$this->render( 'ajax-map-location', array( 'atts' => $atts, 'query_args' => $query_args));
		}
		$this->render( 'ajax-accommodation-list', array('atts' => $atts, 'query_args' => $query_args, 'data' => $data, 'base' => $base ));
		exit;
	}
	
	public function show_result_filter( $sort_type = '', $enable_type = '' ){
		$this->render( 'result_filter', array( 'sort_type' =>$sort_type,'enable_type' => $enable_type));
	}
	
	public function ajax_result_filter(){
		$sort_value = $_POST['params']['sort_value'];
		$search_params  = $_POST['params']['search_data'];
		$sort_type = $_POST['params']['sort_type'];
		$base = $_POST['params']['base'];
		$atts_more = array();
		if(isset($_POST['params']['atts_more'])){
			$atts_more = $_POST['params']['atts_more'];
		}
		$data = array();
		$data['sort_by'] = $sort_value;
		foreach( $search_params as $param ) {
			$multi_value = array( 'accommodation', 'facilities', 'rating', 'location', 'category', 'facility' );
			if( in_array( $param['name'], $multi_value ) ) {
				$data[$param['name']][] = $param['value'];
			}
			else {
				$data[$param['name']] = $param['value'];
			}
		}
		if( $sort_type == 'hotel' ) {
			$atts = array();
			$query_args = array();
			$atts['pagination'] = 'yes';
			if(!empty($atts_more)){
				$atts = array_merge($atts,$atts_more);
			}else{
				if( isset( $data['column'] ) && !empty( $data['column'] ) ){
					$atts['columns'] = $data['column'];
				}
				$atts['limit_post'] = Slzexploore::get_option('slz-hotel-posts');
				$atts['btn_book']   = esc_html__( 'Book Now', 'slzexploore-core' );
			}
			$model = new Slzexploore_Core_Accommodation();
			$model->get_search_atts( $atts, $query_args, $data );
			$this->render( 'ajax-accommodation-list', array( 'atts' => $atts,
															'query_args' => $query_args,
															'data' => $data,
															'base' => $base ));
		}
		elseif( $sort_type == 'tour' ) {
			$atts = $query_args = array();
			$atts['pagination'] = 'yes';
			if(!empty($atts_more)){
				$atts = array_merge($atts,$atts_more);
			}else{
				$atts['columns']    = 2;
				if( isset( $data['column'] ) && !empty( $data['column'] ) ){
					$atts['columns'] = $data['column'];
				}
				$atts['limit_post'] = Slzexploore::get_option('slz-tour-posts');
				$atts['btn_book']   = esc_html__( 'Book Now', 'slzexploore-core' );
			}
			// sort tour
			if( !empty($data['sort_by']) ) {
				$atts['sort_by'] = $data['sort_by'];
			}
			$model = new Slzexploore_Core_Tour();
			$model->get_search_atts( $atts, $query_args, $data );
			$this->render( 'ajax-tour-list', array( 'atts' => $atts, 'query_args' => $query_args, 'data' => $data, 'base' => $base ));
		}
		elseif( $sort_type == 'car' ) {
			$atts = $query_args = array();
			$atts['pagination'] = 'yes';
			if(!empty($atts_more)){
				$atts = array_merge($atts,$atts_more);
			}else{
				if( isset( $data['column'] ) && !empty( $data['column'] ) ){
				$atts['columns'] = $data['column'];
				}
				$atts['limit_post'] = Slzexploore::get_option('slz-car-posts');
				$atts['btn_book']   = esc_html__( 'Book Now', 'slzexploore-core' );
			}
			$model = new Slzexploore_Core_Car();
			$model->get_search_atts( $atts, $query_args, $data );
			
			// sort car
			if( !empty($data['sort_by']) ) {
				$atts['sort_by'] = $data['sort_by'];
			}
			$this->render( 'ajax-car-list', array( 'atts' => $atts, 'query_args' => $query_args, 'data' => $data, 'base' => $base ));
		}
		else {
			$atts = $query_args = array();
			$atts['pagination'] = 'yes';
			if(!empty($atts_more)){
				$atts = array_merge($atts,$atts_more);
			}else{
				$atts['columns'] = 2;
				if( isset( $data['column'] ) && !empty( $data['column'] ) ){
					$atts['columns'] = $data['column'];
				}
				$atts['limit_post'] = Slzexploore::get_option('slz-cruises-posts');
				$atts['btn_book']   = esc_html__( 'Book Now', 'slzexploore-core' );
			}
			$model = new Slzexploore_Core_Cruise();
			$model->get_search_atts( $atts, $query_args, $data );
			
			// sort car
			if( !empty($data['sort_by']) ) {
				$atts['sort_by'] = $data['sort_by'];
			}
			$this->render( 'ajax-cruises-list', array( 'atts' => $atts, 'query_args' => $query_args, 'data' => $data, 'base' => $base ));
		}
		exit;
	}
	
	public function ajax_tour_pagination(){
		$atts  = $_POST['params']['atts'];
		$data  = $_POST['params']['data'];
		$page  = $_POST['params']['page'];
		$base  = $_POST['params']['base'];
		$atts['paged'] = $page;
		$this->render( 'ajax-tour-list', array( 'atts' => $atts, 'query_args' => array(), 'data' => $data, 'base' => $base ) );
		exit;
	}
	public function ajax_search_tour(){
		$params  = $_POST['params']['data'];
		$base    = isset($_POST['params']['base']) ? $_POST['params']['base'] : '';
		$data = array();
		$atts_more = array();
		if(isset($_POST['params']['atts_more'])){
			$atts_more = $_POST['params']['atts_more'];
		}
		foreach( $params as $param ) {
			if( $param['name'] == 'location' || $param['name'] == 'category' || $param['name'] == 'review_rating' ) {
				$data[$param['name']][] = $param['value'];
			}
			else {
				$data[$param['name']] = $param['value'];
			}
		}
		$atts = $query_args = array();
		$atts['pagination'] = 'yes';
		if(!empty($atts_more)){
			$atts = array_merge($atts,$atts_more);
		}else{
			$atts['limit_post'] = Slzexploore::get_option('slz-tour-posts');
			$atts['btn_book']   = esc_html__( 'Book Now', 'slzexploore-core' );
			$atts['columns']    = 2;
		}
		
		$model = new Slzexploore_Core_Tour();
		$model->get_search_atts( $atts, $query_args, $data );
		if(!empty($atts_more)){
			$this->render( 'ajax-map-location', array( 'atts' => $atts, 'query_args' => $query_args));
		}
		$this->render( 'ajax-tour-list', array( 'atts' => $atts,
												'query_args' => $query_args,
												'data' => $data,
												'base' => $base ));
		exit;
	}
	/**
	 * Add tour wish list
	 */
	public function ajax_add_tour_wish_list() {
		$param  = $_POST['params'];
		if( isset( $param['post_id'] ) ) {
			$post_id = intval( $param['post_id'] );
			$user_id = get_current_user_id();
			if( $post_id > 0 && $user_id > 0 ){
				$user_meta = get_user_meta( $user_id, 'slzexploore_tour_wish_list' );
				if( !in_array( $post_id, $user_meta ) ){ // not have user meta
					add_user_meta( $user_id, 'slzexploore_tour_wish_list', $post_id );
					$wlist_number = get_post_meta( $post_id, 'slzexploore_wish_list_number', true );
					if( !empty( $wlist_number ) ){
						$wlist_number ++;
					}
					else{
						$wlist_number = 1;
					}
					update_post_meta( $post_id, 'slzexploore_wish_list_number', $wlist_number );
					printf('<a href="javascript:void(0);" class="link added_wish_list">
								<i class="icons fa fa-heart"></i><span class="text number">%s</span>
							</a>',
							esc_html( $wlist_number )
						);
					exit;
				}
			}
		}
		echo 'fail';
		exit;
	}
	/**
	 * Add hotel wish list
	 */
	public function ajax_add_hotel_wish_list() {
		$param  = $_POST['params'];
		if( isset( $param['post_id'] ) ) {
			$post_id = intval( $param['post_id'] );
			$user_id = get_current_user_id();
			if( $post_id > 0 && $user_id > 0 ){
				$user_meta = get_user_meta( $user_id, 'slzexploore_hotel_wish_list' );
				if( !in_array( $post_id, $user_meta ) ){ // not have user meta
					add_user_meta( $user_id, 'slzexploore_hotel_wish_list', $post_id );
					$wlist_number = get_post_meta( $post_id, 'slzexploore_wish_list_number', true );
					if( !empty( $wlist_number ) ){
						$wlist_number ++;
					}
					else{
						$wlist_number = 1;
					}
					update_post_meta( $post_id, 'slzexploore_wish_list_number', $wlist_number );
					printf('<a href="javascript:void(0);" class="link added_wish_list">
								<i class="icons hidden-icon fa fa-heart"></i><span class="text number">%s</span>
							</a>',
							esc_html( $wlist_number )
						);
					exit;
				}
			}
		}
		echo 'fail';
		exit;
	}
	
	/**
	 * get Tour price
	 */
	public function get_tour_booking_price( $tour_id, $adults, $children ){
		$price_adult = get_post_meta ( $tour_id, 'slzexploore_tour_price_adult', true );
		$price_child = get_post_meta ( $tour_id, 'slzexploore_tour_price_child', true );
		$total_price = ( intval( $price_adult ) * intval( $adults ) ) + ( intval( $price_child ) * intval( $children ) );
		$is_discount = get_post_meta ( $tour_id, 'slzexploore_tour_is_discount', true );
		$discount_rate = get_post_meta ( $tour_id, 'slzexploore_tour_discount_rate', true );
		if( $is_discount && $discount_rate ) {
			$total_price = ( $total_price * intval( $discount_rate ) ) / 100 ;
		}
		return $total_price;
	}
	
	/* get room price */
	public function get_room_price( $room_id, $number ){
		$room_price    = get_post_meta ( $room_id, 'slzexploore_room_price', true );
		$hotel_id      = get_post_meta ( $room_id, 'slzexploore_room_accommodation', true );
		$is_discount   = get_post_meta ( $hotel_id, 'slzexploore_hotel_discount', true );
		$discount_rate = get_post_meta ( $hotel_id, 'slzexploore_hotel_discount_rate', true );
		$total_price = intval( $room_price ) * intval( $number );
		if( $is_discount && $discount_rate ) {
			$total_price = ( $total_price * intval( $discount_rate ) ) / 100 ;
		}
		return $total_price;
	}
	
	/* Get hotel total price */
	public function get_hotel_total_price( $hotel_id, $room_price, $booking_data ){
		$dates = ( strtotime( $booking_data['check_out_date'] ) - strtotime( $booking_data['check_in_date'] ) ) / 86400;
		$total_price = intval($room_price);
		if( $dates ){
			$total_price *= intval($dates);
		}
		if( !empty( $booking_data['number_room'] ) ){
			$total_price *= intval( $booking_data['number_room'] );
		}
		return $total_price;
	}

	/* Save data fields form faq */
	public function save_form_faq( $wpcf7 ) {
		/*
		Note: since version 3.9 Contact Form 7 has removed $wpcf7->posted_data
		and now we use an API to get the posted data.
		*/
		$submission = WPCF7_Submission::get_instance();		
		if ( empty($submission) ) {	
			return;
		}
		$posted_data = $submission->get_posted_data();
		if ( empty($posted_data) || empty($posted_data['_wpcf7_slzexploore_faq_form']) ) {
			return;
		}

		foreach ( $posted_data as $key => $value ) {
			if ( '_wpcf7' == substr( $key, 0, 6 ) ) {
				unset( $posted_data[$key] );
			}
		}
		
		$meta = array();
		$special_mail_tags = array( 'remote_ip', 'user_agent', 'url', 'date', 'time', 'post_id' );

		foreach ( $special_mail_tags as $smt ) {
			$meta[$smt] = apply_filters( 'wpcf7_special_mail_tags','', '_' . $smt, false );
		}

		$post_type = 'slzexploore_faq';
		$post_title = esc_html__( 'FAQs Request', 'slzexploore-core' );
		$post_content = implode( "|", $posted_data );
		$post_status = 'pending';
		$postarr = array(
			'post_type' 		=> $post_type,
			'post_status' 		=> $post_status,
			'post_title' 		=> $post_title,
			'post_content' 		=> $post_content,
			'comment_status' 	=> 'closed',
			'ping_status' 		=> 'closed',
		);
		$post_id = wp_insert_post( $postarr );

		$fields = array();
		if ( $post_id ) {
			$titleNew = $post_title. '-' .$post_id;

			foreach ( $posted_data as $key => $value ) {
				$meta_key = sanitize_key( $post_type . '_' . $key );
				update_post_meta( $post_id, $meta_key, $value );
				$fields[$key] = $value;
				// $fields[$key] = null;
				if ( $key == 'your-subject' || $key == 'your-request' || $key == 'request' ) {
					$titleNew = $value;
				}
			}
			
			wp_update_post(
				array(
					'ID' 			=> $post_id,
					'post_title'	=> $titleNew
				)
			);
			update_post_meta( $post_id, $post_type . '_' . 'fields', $fields );
			update_post_meta( $post_id, $post_type . '_' . 'meta', $meta );
		}
	}
	
	// Car Pagination
	public function ajax_car_pagination(){
		$atts  = $_POST['params']['atts'];
		$data  = $_POST['params']['data'];
		$page  = $_POST['params']['page'];
		$base  = $_POST['params']['base'];
		$atts['paged'] = $page;
		
		$this->render( 'ajax-car-list', array( 'atts' => $atts, 'query_args' => array(), 'data' => $data, 'base' => $base ) );
		exit;
	}
	// Car search
	public function ajax_search_car(){
		$params  = $_POST['params']['data'];
		$base    = isset($_POST['params']['base'])? $_POST['params']['base'] : '';
		$atts_more = array();
		if(isset($_POST['params']['atts_more'])){
			$atts_more = $_POST['params']['atts_more'];
		}
		$search_data = array();
		foreach( $params as $param ) {
			if( $param['name'] == 'location' || $param['name'] == 'category' || $param['name'] == 'rating' ) {
				$search_data[$param['name']][] = $param['value'];
			}
			else {
				$search_data[$param['name']] = $param['value'];
			}
		}
		$atts = $query_args = array();
		$atts['pagination'] = 'yes';
		if(!empty($atts_more)){
			$atts = array_merge($atts,$atts_more);
		}else{
			$atts['limit_post'] = Slzexploore::get_option('slz-car-posts');
			$atts['btn_book']   = esc_html__( 'Book Now', 'slzexploore-core' );
		}
		$model = new Slzexploore_Core_Car();
		$model->get_search_atts( $atts, $query_args, $search_data );
		if(!empty($atts_more)){
			$this->render( 'ajax-map-location', array( 'atts' => $atts, 'query_args' => $query_args));
		}
		$this->render( 'ajax-car-list', array( 'atts' => $atts, 'query_args' => $query_args, 'data' => $search_data, 'base' => $base ));
		exit;
	}
	
	// Cruises Pagination
	public function ajax_cruises_pagination(){
		$atts  = $_POST['params']['atts'];
		$data  = $_POST['params']['data'];
		$page  = $_POST['params']['page'];
		$base  = $_POST['params']['base'];
		$atts['paged'] = $page;
		$this->render( 'ajax-cruises-list', array( 'atts' => $atts, 'query_args' => array(), 'data' => $data, 'base' => $base ) );
		exit;
	}
	// Cuises search
	public function ajax_search_cruise(){
		$params  = $_POST['params']['data'];
		$base    = isset($_POST['params']['base']) ? $_POST['params']['base'] : '';
		$atts_more = array();
		if(isset($_POST['params']['atts_more'])){
			$atts_more = $_POST['params']['atts_more'];
		}
		$search_data = array();
		foreach( $params as $param ) {
			$multi_value = array( 'category', 'location', 'facility', 'rating', 'star_rating' );
			if( in_array( $param['name'], $multi_value ) ) {
				$search_data[$param['name']][] = $param['value'];
			}
			else {
				$search_data[$param['name']] = $param['value'];
			}
		}
		$atts = $query_args = array();
		$atts['pagination'] = 'yes';
		$atts['limit_post'] = Slzexploore::get_option('slz-cruises-posts');
		if(!empty($atts_more)){
			$atts = array_merge($atts,$atts_more);
		}else{
			$atts['limit_post'] = Slzexploore::get_option('slz-car-posts');
			$atts['btn_book']   = esc_html__( 'Book Now', 'slzexploore-core' );
			$atts['columns']    = 2;
		}
		$model = new Slzexploore_Core_Cruise();
		$model->get_search_atts( $atts, $query_args, $search_data );
		if(!empty($atts_more)){
			$this->render( 'ajax-map-location', array( 'atts' => $atts, 'query_args' => $query_args));
		}
		$this->render( 'ajax-cruises-list', array( 'atts' => $atts, 'query_args' => $query_args,'data' => $search_data, 'base' => $base ));
		exit;
	}

	public function ajax_update_products(){
		$args = array(
				'post_type'  => 'product',
				'meta_query' => array(
					'relation'  => 'OR',
					array(
						'key'      => '_sku',
						'value'    => 'hotel',
						'compare'  => 'LIKE'
					),
					array(
						'key'      => '_sku',
						'value'    => 'tour',
						'compare'  => 'LIKE'
					),
					array(
						'key'      => '_sku',
						'value'    => 'car_rent',
						'compare'  => 'LIKE'
					),
					array(
						'key'      => '_sku',
						'value'    => 'cruise',
						'compare'  => 'LIKE'
					)
				),
				'tax_query'  => array(
			        array(
			            'taxonomy' => 'product_type',
			            'field'    => 'slug',
			            'terms'    => 'variable'
			        )
			    )
			);
		$products = Slzexploore_Core_Com::get_post_id2title( $args, array(), false );
		$visibility_ids = wc_get_product_visibility_term_ids();
		$product_visibility = array();
		if( isset( $visibility_ids['exclude-from-catalog'] ) && isset( $visibility_ids['exclude-from-search'] ) ){
			$product_visibility = array(
										$visibility_ids['exclude-from-catalog'],
										$visibility_ids['exclude-from-search']
									);
			
		}
		if( !empty( $products ) && !empty( $product_visibility ) ){
			foreach( $products as $product_id => $product_title ){
				wp_set_object_terms( $product_id, $product_visibility, 'product_visibility' );
			}
		}
		update_option( 'slzexploore_upd_wc_db', true );
		echo 'success';
		exit;
	}

}