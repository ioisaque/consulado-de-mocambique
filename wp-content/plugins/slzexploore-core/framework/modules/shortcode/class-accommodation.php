<?php
class Slzexploore_Core_Accommodation extends Slzexploore_Core_Custom_Post_Model {

	private $post_type = 'slzexploore_hotel';
	private $post_taxonomy = array( 'cat', 'facility', 'location' );
	private $html_format;

	public function __construct() {
		$this->meta_attributes();
		$this->set_meta_attributes();
		$this->post_meta_prefix = $this->post_type . '_';
		$this->set_default_options();
		$this->uniq_id = 'block-' . Slzexploore_Core::make_id();
		$this->set_sort_key();
		$this->taxonomy_cat = 'slzexploore_hotel_cat';
		$this->post_rating_key = $this->post_type . '_rating';
	}
	public function meta_attributes() {
		$meta_atts = array(
			'display_title'        => '',
			'status'               => '',
			'thumbnail'            => '',
			'check_in_time'        => '',
			'check_out_time'       => '',
			'tax_rate'             => '',
			'hotel_rating'         => '',
			'average_price'        => '',
			'price_subfix'         => '',
			'short_des'            => '',
			'address'              => '',
			'location'             => '',
			'email'                => '',
			'phone'                => '',
			'disable_room_type'    => '',
			'room_type'            => '',
			'mail_cc'              => '',
			'mail_bcc'             => '',
			'is_featured'          => '',
			'discount'             => '',
			'discount_rate'        => '',
			'discount_text'        => '',
			'discount_start_date'  => '',
			'discount_end_date'    => '',
			'attachment_ids'       => '',
			'rating'               => '',
			'is_deposit'           => '',
			'deposit_type'         => '',
			'deposit_amount'       => '',
			'address'              => '',
		    'featured_text'        => '',
		);
		$this->post_meta_atts = $meta_atts;
	}
	public function set_meta_attributes() {
		$meta_arr = array();
		$meta_label_arr = array();
		foreach( $this->post_meta_atts as $att => $name ){
			$key = $att;
			$meta_arr[$key] = '';
			$meta_label_arr[$key] = $name;
		}
		$this->post_meta_def = $meta_arr;
		$this->post_meta = $meta_arr;
		$this->post_meta_label = $meta_label_arr;
	}
	public function init( $atts = array(), $query_args = array() ) {
		// set attributes
		$default_atts = array(
			'layout'               => 'accommodation',
			'limit_post'           => '-1',
			'author'               => '',
			'offset_post'          => '0',
			'sort_by'              => '',
			'pagination'           => '',
			'category_slug'        => '',
			'facility_slug'        => '',
			'location_slug'        => '',
			'columns'              => '1',
			'paged'                => '',
			'cur_limit'            => '',
			'show_views'           => '1',
			'show_reviews'         => '1',
			'show_wishlist'        => '1',
			'show_share_post'      => '1',
			'show_address'         => '1',
			'post_in'              => '',
		);
		$atts = array_merge( $default_atts, $atts );
		$atts['taxonomy_slug'] = $this->get_taxonomy_params( $this->post_type, $this->post_taxonomy, $atts );
		$atts['offset_post'] = absint( $atts['offset_post'] );
		if( isset( $atts['featured_filter'] ) && !empty( $atts['featured_filter'] ) ) {
			$atts['meta_key'] = array(
				'slzexploore_hotel_is_featured' => $atts['featured_filter']
			);
		}
		// check if have address, show post in Map Location shortcode
		if( isset($atts['allow_address_empty']) && !empty($atts['allow_address_empty']) ){
			$atts['meta_key_compare'][] = array(
						'key'     => 'slzexploore_hotel_address',
						'value'   => '',
						'compare' => '!=',
					);
		}
		$this->attributes = $atts;

		// query
		$default_args = array(
			'post_type' => $this->post_type,
		);
		$query_args = array_merge( $default_args, $query_args );
		
		// setting
		$this->setting( $query_args);
	}
	public function set_sort_key(){
		$this->sort_meta_key['price']        = $this->post_meta_prefix . 'average_price';
		$this->sort_meta_key['discount']     = $this->post_meta_prefix . 'discount_rate';
		$this->sort_meta_key['star_rating']  = $this->post_meta_prefix . 'hotel_rating';
		$this->sort_meta_key['review_rating']= $this->post_meta_prefix . 'rating';
	}
	public function setting( $query_args ){
		if( !isset( $this->attributes['uniq_id'] ) ) {
			$this->attributes['uniq_id'] = 'block-'.Slzexploore_Core::make_id();
		}
		$this->post_view_key = SLZEXPLOORE_CORE_POST_VIEWS;
		// disable status
		$disable_status = Slzexploore_Core::get_theme_option('slz-hotel-disable-status');
		if( !empty( $disable_status ) ){
			$this->attributes['meta_key_compare'][] = array(
				'relation' => 'OR',
				array(
					'key'     => 'slzexploore_hotel_status',
					'value'   => $disable_status,
					'compare' => 'NOT IN'
				),
				array(
					'key'     => 'slzexploore_hotel_status',
					'compare' => 'NOT EXISTS'
				)
			);
		}
		
		// query
		$this->query = $this->get_query( $query_args, $this->attributes );
		$this->post_count = 0;
		if( $this->query->have_posts() ) {
			$this->post_count = $this->query->post_count;
		}
		// image size
		$this->get_thumb_size();
		$this->set_responsive_class();
		$this->set_meta_info();
	}
	public function reset(){
		wp_reset_postdata();
	}
	public function set_responsive_class( $atts = array() ) {
		$class = '';
		$column = $this->attributes['columns'];
		if( isset($atts['res_class']) ) {
			$class = $atts['res_class'];
		}
		$def = array(
			'1' => 'col-md-12',
			'2' => 'col-md-6',
			'3' => 'col-md-4 col-sm-6 col-xs-12',
			'4' => 'col-md-3',
		);;
		
		if( $column && isset($def[$column])) {
			$this->attributes['responsive-class'] = $def[$column];
		} else {
			$this->attributes['responsive-class'] = $def['1'];
		}
	}

	public function render_widget( $html_options = array() ) {
		$this->set_default_options( $html_options );
		while ( $this->query->have_posts() ) {
			$this->query->the_post();
			$this->loop_index();
			printf( $html_options['html_format'],
					$this->get_title(),
					$this->permalink,
					$this->get_featured_image(),
					$this->get_price(),
					$this->get_post_class()
			);
		}
		$this->reset();
	}
    
   	public function render_gallery_widget( $html_options = array() ) {
		$this->set_default_options( $html_options );
		$params = Slzexploore_Core::get_params( 'block-image-size', 'wg_gallery' );
		$this->attributes['thumb-size'] = Slzexploore_Core_Util::get_thumb_size( $params, $this->attributes );
		while ( $this->query->have_posts() ) {
			$this->query->the_post();
			$this->loop_index();
			printf( $html_options['html_format'],
					$this->get_featured_image( array(), 'small' ),
					$this->permalink
			);
		}
		$this->reset();
	}
	public function render_gallery_widget_style_2( $html_options = array() ) {
		$this->set_default_options( $html_options );
		$params = Slzexploore_Core::get_params( 'block-image-size', 'wg_gallery' );
		$this->attributes['thumb-size'] = Slzexploore_Core_Util::get_thumb_size( $params, $this->attributes );
		while ( $this->query->have_posts() ) {
			$this->query->the_post();
			$this->loop_index();
			if( has_post_thumbnail() ){
				$post_id      = $this->post_id;
				$cls_position = 'glry-relative';
				$thumbnail_id = get_post_thumbnail_id( $post_id );
				$image_url    = wp_get_attachment_image_src( $thumbnail_id, $this->attributes['thumb-size']['large'] );
				$image        = $this->get_featured_image( array(), 'small' );
				$thumbnail    = sprintf( $html_options['gallery_item'], $image_url[0], $post_id, $cls_position, $image );
				printf( $html_options['html_format'], $thumbnail);
			}
		}
		$this->reset();
	}
	/* -------------------- << Render Html >> ------------------------- */
	/**
	 * Render html by list.
	 *
	 * @param array $html_options
	 * @format: 1$ - img, 2$ - price, 3$ - title, 4$ - star, 5$ - content, 6$ - social, 7$ - responsive
	 */
	public function render_list( $html_options = array() ) {
		$this->set_default_options( $html_options );
		$count = $row_count = 0;
		$columns = absint($this->attributes['columns']);
		while ( $this->query->have_posts() ) {
			$this->query->the_post();
			$this->loop_index();
			$count ++;
			if( isset( $html_options['open_row'] ) && $columns > 1 && $count > $columns ) {
				echo ( $html_options['close_row'] . $html_options['open_row'] );
				$count = 1;
			}
			$price_class = '';
			$price = Slzexploore_Core_Format::format_number( $this->post_meta['average_price'] );
			if( ($columns == 1 && $price > 999999) || ($columns == 2 && $price > 999) ) {
				$price_class = 'style-2';
			}
			printf( $html_options['html_format'],
					$this->get_thumbnail_image(),
					$this->get_discount(),
					$this->get_display_title(),
					$this->get_star_rating(),
					$this->get_item_content(),
					$this->get_meta_info(),
					$this->attributes['responsive-class'].' '.$this->get_post_class(),
					$price_class,
					$this->get_feature_ribbon()
			);
		}
		$this->reset();
	}
	public function render_carousel( $html_options = array() ) {
		$this->set_default_options( $html_options );
		$count = 0;
		while ( $this->query->have_posts() ) {
			$this->query->the_post();
			$this->loop_index();
			$count ++;
			printf( $html_options['html_format'],
					$this->get_featured_image( $html_options ),
					$this->get_title( $html_options ),
					$this->get_post_class(),
					$this->get_feature_ribbon()
			);
		}
		$this->reset();
	}

	public function get_thumbnail_image(){
		$thumb_id = $this->post_meta['thumbnail'];
		if( !empty( $thumb_id ) ) {
			$format = $this->html_format['image_format'];
			$thumb_class = Slzexploore_Core::get_value( $this->html_format, 'thumb_class', 'img-responsive' );
			$thumb_size = $this->attributes['thumb-size']['large'];
			$helper = new Slzexploore_Core_Helper();
			$helper->regenerate_attachment_sizes($thumb_id, $thumb_size);
			$thumb_img = wp_get_attachment_image( $thumb_id, $thumb_size, false, array('class' => $thumb_class ) );
			$output = sprintf( $format, $thumb_img, esc_url( $this->permalink ) );
			return $output;
		}
		else {
			return $this->get_featured_image( $this->html_format );
		}
	}
	public function get_discount() {
		if( $this->post_password ) return '';
		$is_discount = $this->post_meta['discount'];
		if( $is_discount ) {
			$format = $this->html_format['discount_format'];
			$discount_rate = $this->post_meta['discount_rate'];
			$discount_text = $this->post_meta['discount_text'];
			if( empty( $discount_text ) ){
				$discount_text = esc_html__('sale', 'slzexploore-core');
			}
			$discount_text = '<p class="text">' . $discount_text . '</p>';
			if( !empty( $discount_rate ) ) {
				$discount_rate = '<p class="number">' . $discount_rate . '%</p>';
			}
			$discount_text = $discount_text . $discount_rate;
			
			$start_date = $this->post_meta['discount_start_date'];
			$end_date = $this->post_meta['discount_end_date'];
			$today = date( 'Y-m-d' );
			if( !empty( $start_date ) && ($start_date > $today) ) {
				return '';
			}
			if( !empty( $end_date ) && ($end_date < $today) ) {
				return '';
			}
			return sprintf($format, $discount_text);
		}
	}
	public function get_star_rating() {
		$out = '';
		if( !empty( $this->post_meta['hotel_rating'] ) ) {
			$format = $this->html_format['star_rating'];
			$out = sprintf( $format, esc_html( $this->post_meta['hotel_rating'] ) );
		}
		return $out;
	}
	public function get_item_content() {
		$price = $this->post_meta['average_price'];
		$short_desc = sprintf( $this->html_format['short_desc_format'], wp_kses_post( $this->post_meta['short_des'] ) );
		$btn_book = '';
		if(isset($this->attributes['btn_book']) && !empty($this->attributes['btn_book']) ) {
			$btn_url = $this->permalink;
			if(isset($this->attributes['btn_link']) && !empty($this->attributes['btn_link']) ) {
				$arr_link = Slzexploore_Core_Util::get_link( $this->attributes['btn_link'] );
				if( !empty( $arr_link['link'] ) ) {
					$btn_url = $arr_link['link'];
				}
			}
			$btn_book = sprintf( $this->html_format['btn_book_format'],
								esc_url( $btn_url ),
								esc_html( $this->attributes['btn_book'] )
							);
		}
		
		$out = sprintf( '%1$s %2$s %3$s',
						$this->get_price(),
						$short_desc,
						$btn_book
					);
		return $out;
	}
	public function get_price() {
		if( $this->post_password ) return '';
		$out = '';
		$format = $this->html_format['price_format'];
		$price = Slzexploore_Core_Format::format_number( $this->post_meta['average_price'] );
		$sign = sprintf( $this->html_format['sign_price_format'],
						esc_html( Slzexploore_Core::get_theme_option('slz-currency-sign') ) );
		if( !empty( $price ) ) {
			$duration = $this->post_meta['price_subfix'];
			$decimal = Slzexploore_Core::get_theme_option('slz-currency-decimal');
			$price = '<span class="number">' . number_format_i18n( $price, $decimal ) .'</span>';
			$sign_position = Slzexploore_Core::get_theme_option('slz-symbol-currency-position');
			if( $sign_position == 'before' ) {
				$price = $sign .$price;
			} else {
				$price = $price .$sign;
			}
			if( !empty($duration) ) {
				$duration = sprintf($this->html_format['price_subfix'], esc_html( $duration ) );
			}
			$out = sprintf($format, $price, $duration );
		}
		return $out;
	}
	public function get_column_price() {
		$out = '';
		$format = '%1$s';
		$price = Slzexploore_Core_Format::format_number( $this->post_meta['average_price'] );
		$sign = sprintf( $this->html_format['sign_price_format'],
				esc_html( Slzexploore_Core::get_theme_option('slz-currency-sign') ) );
		if( !empty( $price ) ) {
			$decimal = Slzexploore_Core::get_theme_option('slz-currency-decimal');
			$price = '<span class="number">' . number_format_i18n( $price, $decimal ) .'</span>';
			$sign_position = Slzexploore_Core::get_theme_option('slz-symbol-currency-position');
			if( $sign_position == 'before' ) {
				$price = $sign .$price;
			} else {
				$price = $price .$sign;
			}
			$out = sprintf($format, $price );
		}
		return $out;
	}
	private function set_meta_info(){
		$meta_enabled = Slzexploore::get_option('slz-hotel-list-info', 'enabled');
		if( !isset( $meta_enabled['comment'] ) ) {
			$this->attributes['show_reviews'] = 0;
		}
		if( !isset( $meta_enabled['view'] ) ) {
			$this->attributes['show_views'] = 0;
		}
		if( !isset( $meta_enabled['list-wist'] ) ) {
			$this->attributes['show_wishlist'] = 0;
		}
		if( !isset( $meta_enabled['address'] ) ) {
			$this->attributes['show_address'] = 0;
		}
		if( !isset( $meta_enabled['share'] ) ) {
			$this->attributes['show_share_post'] = 0;
		}
	}
	public function get_meta_info() {
		$info = array();
		if( $this->attributes['show_views'] ) {
			$info[] = $this->get_views( $this->html_format );
		}
		if( $this->attributes['show_wishlist'] ) {
			$info[] = $this->get_wishlist();
		}
		if( $this->attributes['show_reviews'] ) {
			$info[] = $this->get_reviews( $this->html_format );
		}
		if( $this->attributes['show_share_post'] ) {
			$info[] = $this->get_share_post();
		}
		if( $this->attributes['show_address'] ) {
			$info[] = $this->get_address();
		}
		return sprintf($this->html_format['meta_format'], implode('', $info));
	}
	public function get_wishlist() {
		$class = '';
		$href = 'javascript:void(0);';
		if( is_user_logged_in() ){
			$class = 'slz_add_wish_list';
			$user_meta = get_user_meta( get_current_user_id(), 'slzexploore_hotel_wish_list' );
			if( in_array( $this->post_id, $user_meta ) ){
				$class = 'added_wish_list';
			}
		}
		else{
			$login_page_id = get_option( 'slzexploore_login_page_id' );
			$href = esc_url( get_permalink( $login_page_id ) );
		}
		$wlist_number = get_post_meta( $this->post_id, 'slzexploore_wish_list_number', true );
		if( empty( $wlist_number ) ){
			$wlist_number = 0;
		}
		return sprintf($this->html_format['wishlist_format'], esc_html( $wlist_number ), esc_attr( $class ),
						esc_attr( $this->post_id ), $href );
	}
	public function get_share_post() {
		$share_link = slzexploore_core_get_share_link();
		return sprintf($this->html_format['share_format'], $share_link );
	}
	public function get_address() {
		$address = $this->post_meta['address'];
		return sprintf($this->html_format['address_format'], esc_html( $address ) );
	}
	public function get_address_info() {
		$output = array();
		$count = 0;
		while ( $this->query->have_posts() ) {
			$this->query->the_post();
			$this->loop_index();
			$address = $this->post_meta['address'];
			if(!empty($address)){
				$output[$count]['address']= $address;
			}
			//Location
			$location  = get_post_meta( $this->post_id, 'slzexploore_hotel_location', true );
			if( !empty($address) && !empty( $location ) ){
				$location_arr = explode( ',', $location );
				if( count( $location_arr ) >= 2 ) {
					$output[$count]['lat'] = $location_arr[0];
					$output[$count]['lng'] = $location_arr[1];
				}
				$output[$count]['title'] = $this->get_title();
				$output[$count]['star']  = substr($this->get_reviews(),0,1);
				$output[$count]['href']  = $this->permalink;
				$output[$count]['image'] = '';
				if( has_post_thumbnail() ){
					$output[$count]['image'] = get_the_post_thumbnail_url( $this->post_id, 'slzexploore-thumb-116x63' );
				}
				$count++;
			}
			
		}
		$this->reset();
		return $output;

	}
	public function get_display_title() {
		$format = $this->html_format['title_format'];
		$title = !empty($this->post_meta['display_title']) ? $this->post_meta['display_title'] : $this->title;
		$output = sprintf( $format, esc_html( $title ), esc_url( $this->permalink ) );
		return $output;
	}
	public function get_feature_ribbon(){
		$out = '';
		$format = $this->html_format['feature_ribbon_format'];
		if( !empty($this->post_meta['is_featured']) && $this->post_meta['featured_text']){
			$out = sprintf($format,$this->post_meta['featured_text']);
		}
		return $out;
	}
	public function get_rating_number( $post_id ){
		$comments = get_comments( array('post_id' => $post_id) );
		$cmt_number = 0;
		$rate_number = 0;
		foreach($comments as $cmt){
			$rating = get_comment_meta( $cmt->comment_ID, 'slzexploore_hotel_rating', true);
			if($rating){
				$rate_number += intval($rating);
				$cmt_number ++;
			}
		}
		if($cmt_number == 0){
			$cmt_number = 1;
		}
		$rate_number = $rate_number/$cmt_number;
		$rate_number = round($rate_number, 1);
		$sub_rate = substr($rate_number,2);
		if($sub_rate){
			if(intval($sub_rate) < 5){
				$rate_number = substr($rate_number, 0, 1);
			}
			else{
				$rate_number = intval(substr($rate_number, 0, 1)) + 1;
			}
		}
		return $rate_number;
	}
	/* ------------------- << Post Infomations >> ------------------- */

	public function set_default_options( &$html_options = array() ) {
		$defaults = array(
			'image_format'       => '<a class="link" href="%2$s">%1$s</a>',
			'discount_format'    => '<div class="label-sale">%1$s</div>',
			'title_format'       => '<a href="%2$s" class="title">%1$s</a>',
			'star_rating'        => '<div class="stars stars%1$s">%1$s</div>',
			'price_format'       => '<div class="title"><div class="price">%1$s</div>%2$s</div>',
			'sign_price_format'  => '<sup>%1$s</sup>',
			'price_subfix'       => '<p class="for-price">%1$s</p>',
			'meta_format'        => '<ul class="list-info list-unstyle">%1$s</ul>',
			'view_format'        => '<li class="view"><a href="%2$s" class="link"><i class="icons hidden-icon fa fa-eye"></i><span class="number">%1$s</span></a></li>',
			'wishlist_format'    => '<li class="wishlist"><a href="%4$s" class="link %2$s" data-item="%3$s"><i class="icons hidden-icon fa fa-heart"></i><span class="text number">%1$s</span></a></li>',
			'comment_format'     => '<li><a href="javascript:void(0);" class="link"><i class="icons hidden-icon fa fa-comment"></i><span class="number">%1$s</span></a></li>',
			'review_format'      => '<li class="comment"><a href="%3$s" class="link"><i class="icons hidden-icon fa fa-comment"></i><span class="number">%1$s/%2$s</span></a></li>',
			'share_format'       => '<li class="share"><a href="javascript:void(0);" class="link"><i class="icons fa fa-share-alt"></i></a>%1$s</li>',
			'address_format'     => '<li class="address"><a href="javascript:void(0);" class="link" title="%1$s"><i class="icons fa fa-map-marker"></i></a></li>',
			'short_desc_format'  => '<div class="text">%1$s</div>',
			'btn_book_format'    => '<div class="group-btn-tours"><a href="%1$s" class="left-btn">%2$s</a></div>',
			'thumb_href_class'    => '',
			'feature_ribbon_format'=>'<div class="ribbon-sale"><i class="fa fa-bolt"></i><span>%1$s</span></div>'

		);
		$html_options = array_merge( $defaults, $html_options );
		$this->html_format = $html_options;
		return $html_options;
	}
	public function get_thumb_size() {
		$params = Slzexploore_Core::get_params( 'block-image-size', $this->attributes['layout'] );
		$this->attributes['thumb-size'] = Slzexploore_Core_Util::get_thumb_size( $params, $this->attributes );
	}
	// get meta_value from mate_key
	public function get_meta_value_by_meta_key( $meta_key ) {
		global $wpdb;
		$output = array();
		$results = $wpdb->get_results( $wpdb->prepare("SELECT DISTINCT meta_value FROM $wpdb->postmeta WHERE meta_key = %s " , $meta_key), ARRAY_A );
		if( $results ) {
			foreach( $results as $res ) {
				if( !empty( $res['meta_value'] ) ) {
					$output[] = $res['meta_value'];
				}
			}
		}
		return $output;
	}
	// count number hotel by hotel star
	public function count_hotel_by_star( $number_star = '' ) {
		global $wpdb;
		$result = $wpdb->get_results( $wpdb->prepare("SELECT * FROM $wpdb->postmeta WHERE meta_key = 'slzexploore_hotel_hotel_rating' AND meta_value = '%s' " , $number_star), ARRAY_A );
		return count($result);
	}
	
	public function get_post_by_rating( $rate_number ){
		$args = array(
					'post_type'  => $this->post_type,
					'meta_key'   => $this->post_meta_prefix . 'rating',
					'meta_value' => $rate_number
				);
		$output = Slzexploore_Core_Com::get_post_id2title( $args, array(), false );
		return $output;
	}
	// get link search
	public function get_search_link() {
		$post_type = get_post_type_object( 'slzexploore_hotel' );
		$slug = $post_type->rewrite['slug'];
		$link = esc_url( home_url() ).'/'.$slug;
		return esc_url($link);
	}
	// get accommodation id from search atts
	public function get_accommodation_id( $search_data ){
		$accommodation_id = array();
		if( !isset( $search_data['location'] ) ) {
			return $accommodation_id;
		}
		//Search hotel by room
		$room_model = new Slzexploore_Core_Room();
		$room_atts = array();
		if( !empty( $search_data['adults'] ) ) {
			$room_atts['meta_key_compare'][] = array(
				'relation' => 'OR',
				array(
					'key'     => 'slzexploore_room_max_adults',
					'value'   => $search_data['adults'],
					'compare' => '>='
				),
				array(
					'key'     => 'slzexploore_room_max_adults',
					'value'   => ''
				)
			);
		}
		if( !empty( $search_data['children'] ) ) {
			$room_atts['meta_key_compare'][] = array(
				'relation' => 'OR',
				array(
					'key'     => 'slzexploore_room_max_children',
					'value'   => ''
				),
				array(
					'key'     => 'slzexploore_room_max_children',
					'value'   => $search_data['children'],
					'compare' => '>='
				)
			);
		}
		if( !empty( $search_data['date_from'] ) ) {
			$date_string = Slzexploore_Core_Util::join_date_string($search_data['date_from'], $search_data['date_to']);
			$room_atts['meta_key_compare'][] = array(
				'relation' => 'OR',
				array(
					'key'     => 'slzexploore_room_allow_booking',
					'value'   => ''
				),
				array(
					'key'     => 'slzexploore_room_allow_booking',
					'value'   => $date_string,
					'compare' => 'LIKE'
				)
			);
		}
		$room_model->init( $room_atts );
		$accommodation_id = $room_model->get_accommodation_id( $search_data['date_from'], $search_data['date_to'] );

		// get hotel no room
		$args = array(
			'post_type'       => 'slzexploore_hotel',
			'meta_key'        => 'slzexploore_hotel_room_type',
			'meta_value'      => ' '
		);
		$results = Slzexploore_Core_Com::get_post_id2title( $args, array(), false );
		if(!empty($results)){
			$accommodation_id = array_merge( $accommodation_id, array_keys($results) );
		}
		return $accommodation_id;
	}
	// get search atts
	public function get_search_atts( &$atts, &$query_args, $search_data ){
		$accommodation_id = $this->get_accommodation_id( $search_data );
		if( !empty($accommodation_id) ){
			$atts['post_id'] = $accommodation_id;
		}
		
		// search by keyword
		if( isset($search_data['keyword']) && !empty($search_data['keyword']) ){
			$query_args['s'] = $search_data['keyword'];
		}
		// search hotel by location
		if( isset($search_data['location']) && !empty($search_data['location']) ){
			$atts['location_slug'] = $search_data['location'];
		}
		
		// search hotel by type
		if( isset($search_data['accommodation']) && !empty($search_data['accommodation']) ){
			$atts['cat_slug'] = $search_data['accommodation'];
		}
		
		// search hotel by facility
		if( isset($search_data['facilities']) && !empty($search_data['facilities']) ){
			$atts['facility_slug'] = $search_data['facilities'];
		}
		
		// search hotel by rating
		if( isset($search_data['rating']) && !empty($search_data['rating']) ){
			if( is_array($search_data['rating']) && in_array( '0', $search_data['rating']) ){
				$atts['meta_key_compare'][] = array(
					'relation' => 'OR',
					array(
						'key'     => 'slzexploore_hotel_hotel_rating',
						'value'   => $search_data['rating'],
						'compare' => 'IN'
					),
					array(
						'key'     => 'slzexploore_hotel_hotel_rating',
						'value'   => ''
					)
				);
			}
			else {
				$atts['meta_key_compare'][] = array(
							'key'     => 'slzexploore_hotel_hotel_rating',
							'value'   => $search_data['rating'],
							'compare' => 'IN',
					);
			}
		}
		// search hotel by review_rating
		if( isset($search_data['review_rating']) && !empty($search_data['review_rating']) ){
			$atts['meta_key']['slzexploore_hotel_rating'] = $search_data['review_rating'];
		}
		// search hotel by price
		if( isset($search_data['average_price']) && !empty($search_data['average_price']) ){
			$average_price = explode( ',', $search_data['average_price'] );
			$atts['meta_key_compare'][] = array(
						'key'     => 'slzexploore_hotel_average_price',
						'value'   => $average_price,
						'type'    => 'numeric',
						'compare' => 'BETWEEN',
					);
		}
		// sort hotel
		if( isset($search_data['sort_by']) && !empty($search_data['sort_by']) ) {
			$atts['sort_by'] = $search_data['sort_by'];
		}
	}
	// parse search data array to string
	public function parse_search_atts( $data = array(), $is_archive = false ){
		$output = '';
		$format = '<span class="%1$s">%2$s<i class="fa fa-times"></i></span>';
		if( isset( $data['keyword'] ) && !empty( $data['keyword'] ) ){
			$output .= sprintf( $format, 'keyword', $data['keyword']);
		}
		if( isset( $data['location'] ) && !empty( $data['location'] ) ){
			if( is_array( $data['location'] ) ){
				$arr_locations = array_filter( array_unique( $data['location'] ) );
				if( count( $arr_locations ) > 2 ){
					$output .= sprintf( $format, 'location',
										count( $arr_locations ) . esc_html__( ' Locations', 'slzexploore-core' ) );
				}
				else{
					$location = array();
					foreach( $arr_locations as $k => $slug ){
						$term = Slzexploore_Core_Com::get_tax_options_by_slug( $slug, 'slzexploore_hotel_location' );
						if( $term ){
							$location[] = $term->name;
						}
					}
					if( !empty( $location ) ){
						$output .= sprintf( $format, 'location', implode( ' or ', $location ) );
					}
				}
			}
			else{
				$term = Slzexploore_Core_Com::get_tax_options_by_slug( $data['location'], 'slzexploore_hotel_location' );
				$output .= sprintf( $format, 'location', $term->name );
			}
		}
		if( isset( $data['date_from'] ) && !empty( $data['date_from'] ) ){
			$output .= sprintf( $format, 'date_from', $data['date_from'] );
		}
		if( isset( $data['date_to'] ) && !empty( $data['date_to'] ) ){
			$output .= sprintf( $format, 'date_to', $data['date_to'] );
		}
		if( isset( $data['adults'] ) && !empty( $data['adults'] ) ){
			$output .= sprintf( $format, 'adults', $data['adults'] . esc_html__( ' Adult(s)', 'slzexploore-core' ) );
		}
		if( isset( $data['children'] ) && !empty( $data['children'] ) ){
			$output .= sprintf( $format, 'children', $data['children'] . esc_html__( ' Children', 'slzexploore-core' ) );
		}
		// price
		if( isset($data['average_price']) && !empty($data['average_price']) ){
			$max_value = Slzexploore::get_option('slz-hotel-price-max');
			$default_price = '0,' . $max_value;
			if( $data['average_price'] != $default_price ){
				$price = str_replace( ',', ' ~ ', $data['average_price'] );
				$sign = Slzexploore_Core::get_theme_option('slz-currency-sign');
				$output .= sprintf( $format, 'average_price', $price . $sign );
			}
		}
		if( isset( $data['rating'] ) && !empty( $data['rating'] ) ){
			if( is_array( $data['rating'] ) ){
				$rating = implode( ' or ', $data['rating'] );
			}
			else{
				$rating = $data['rating'];
			}
			$output .= sprintf( $format, 'rating', $rating . esc_html__( ' Star(s)', 'slzexploore-core' ) );
		}
		if( isset( $data['review_rating'] ) && !empty( $data['review_rating'] ) ){
			if( is_array( $data['review_rating'] ) ){
				$rating = implode( ' or ', $data['review_rating'] );
			}
			else{
				$rating = $data['review_rating'];
			}
			$output .= sprintf( $format, 'review_rating', $rating . esc_html__( ' Review Star(s)', 'slzexploore-core' ) );
		}
		if( isset( $data['accommodation'] ) && !empty( $data['accommodation'] ) ){
			if( is_array( $data['accommodation'] ) ){
				$categories = array_unique( $data['accommodation'] );
				if( count( $categories ) > 2 ){
					$output .= sprintf( $format, 'accommodation',
										count( $categories ) . esc_html__( ' Categories', 'slzexploore-core' ) );
				}
				else{
					$cat_names = '';
					foreach( $categories as $k => $slug ){
						$term = Slzexploore_Core_Com::get_tax_options_by_slug( $slug, 'slzexploore_hotel_cat' );
						if( $k != 0 ){
							$cat_names .= ' or ';
						}
						$cat_names .= $term->name;
					}
					if( !empty( $cat_names ) ){
						$output .= sprintf( $format, 'accommodation', $cat_names );
					}
				}
			}
			else{
				$term = Slzexploore_Core_Com::get_tax_options_by_slug( $data['accommodation'], 'slzexploore_hotel_cat' );
				$output .= sprintf( $format, 'accommodation', $term->name );
			}
		}
		if( isset( $data['facilities'] ) && !empty( $data['facilities'] ) ){
			if( is_array( $data['facilities'] ) ){
				$arr_facilities = array_filter( array_unique( $data['facilities'] ) );
				if( count( $arr_facilities ) > 2 ){
					$output .= sprintf( $format, 'facilities',
										count( $arr_facilities ) . esc_html__( ' Facilities', 'slzexploore-core' ) );
				}
				else{
					$facilities = array();
					foreach( $arr_facilities as $k => $slug ){
						$term = Slzexploore_Core_Com::get_tax_options_by_slug( $slug, 'slzexploore_hotel_facility' );
						if( $term ){
							$facilities[] = $term->name;
						}
					}
					if( !empty( $facilities ) ){
						$output .= sprintf( $format, 'facilities', implode( ' or ', $facilities ) );
					}
				}
			}
			else{
				$term = Slzexploore_Core_Com::get_tax_options_by_slug( $data['facilities'], 'slzexploore_hotel_facility' );
				$output .= sprintf( $format, 'facilities', $term->name );
			}
		}
		if( !empty( $output ) ){
			$clear_all = sprintf('<a href="javascript:void(0);" class="btn-reset-all">%s</a>',
									esc_html__( 'Clear All', 'slzexploore-core' ));
			if( $is_archive ){
				$archive_link = get_post_type_archive_link( 'slzexploore_hotel' );
				$clear_all = sprintf('<a href="%1$s" class="btn-clear-all">%2$s</a>',
										esc_url( $archive_link ),
										esc_html__( 'Clear All', 'slzexploore-core' ));
			}
			$output .= $clear_all;
		}
		return $output;
	}
	public function display_results_found( $search_data, $is_archive = false ){
		$str_search_atts = $this->parse_search_atts( $search_data, $is_archive );
		$for_text = '';
		if( !empty( $str_search_atts ) ){
			$for_text = esc_html__( ' for ', 'slzexploore-core' );
		}
		if( $this->post_count <= 0 ){
			printf('<div class="hide results-found"><span class="search-result-title">%1$s %2$s</span> %3$s</div>',
					esc_html__( 'No results', 'slzexploore-core' ),
					$for_text,
					$str_search_atts
				);
		}
		else{
			$start = intval( $this->query->query['offset'] ) + 1;
			$end   = intval( $this->query->query['offset'] ) + intval( $this->post_count );
			$total = $this->query->found_posts;
			printf('<div class="hide results-found"><span class="search-result-title">%1$s %2$s-%3$s %4$s %5$s %6$s %7$s</span> %8$s</div>',
					esc_html__( 'Showing', 'slzexploore-core' ),
					esc_html( $start ),
					esc_html( $end ),
					esc_html__( 'of', 'slzexploore-core' ),
					esc_html( $total ),
					_n('result', 'results ', $total, 'slzexploore-core'),
					$for_text,
					$str_search_atts
				);
		}
	}
	/* Get discount number */
	public function get_discount_number( $post_id, $check_date ){
		$is_discount   = get_post_meta( $post_id, 'slzexploore_hotel_discount', true );
		$discount_rate = get_post_meta ( $post_id, 'slzexploore_hotel_discount_rate', true );
		if( $is_discount && $discount_rate ){
			$start_date = get_post_meta( $post_id, 'slzexploore_hotel_discount_start_date', true );
			$end_date   = get_post_meta ( $post_id, 'slzexploore_hotel_discount_end_date', true );
			if(
				( $start_date && $end_date && ( $start_date <= $check_date ) && ( $check_date <= $end_date ) )
				|| ( !$start_date && ( $check_date <= $end_date ) )
				|| ( !$end_date && ( $start_date <= $check_date ) )
				|| ( !$start_date && !$end_date )
			){
				return $discount_rate;
			}
		}
		return '';
	}

}