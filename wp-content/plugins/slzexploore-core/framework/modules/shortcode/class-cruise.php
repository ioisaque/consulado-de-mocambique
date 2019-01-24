<?php
class Slzexploore_Core_Cruise extends Slzexploore_Core_Custom_Post_Model {

	private $post_type = 'slzexploore_cruise';
	private $post_taxonomy = array( 'cat', 'location', 'facility' );
	public $html_format;
	
	public function __construct( $options = array() ) {
		$this->meta_attributes();
		$this->set_meta_attributes();
		$this->post_meta_prefix = $this->post_type . '_';
		$this->set_default_options( $options );
		$this->uniq_id = 'block-' . Slzexploore_Core::make_id();
		$this->sort_meta_key['price'] = $this->post_type . '_price_adult';
		$this->sort_meta_key['discount'] = $this->post_type . '_discount_rate';
		$this->sort_meta_key['star_rating'] = $this->post_type . '_star_rating';
		$this->sort_meta_key['review_rating'] = $this->post_type . '_rating';
		$this->sort_meta_key['start_date'] = $this->post_type . '_start_date';
		$this->taxonomy_cat = 'slzexploore_cruise_cat';
		$this->post_rating_key = $this->post_type . '_rating';
	}
	public function meta_attributes() {
		$meta_atts = array(
			'display_title'     => '',
			'status'            => '',
			'description'       => '',
			'date_type'         => '',
			'frequency'         => '',
			'weekly'            => '',
			'monthly'           => '',
			'start_date'        => '',
			'end_date'          => '',
			'duration'          => '',
			'available_seat'    => '',
			'price_adult'       => '',
			'price_child'       => '',
			'destination'       => '',
			'is_discount'       => '',
			'discount_rate'     => '',
			'discount_text'     => '',
			'show_gallery'      => '',
			'gallery_box_title' => '',
			'gallery_ids'       => '',
			'gallery_backg'     => '',
			'show_cabin_type'   => '',
			'cabin_type'        => '',
			'mail_cc'           => '',
			'mail_bcc'          => '',
			'is_featured'       => '',
			'attachment_ids'    => '',
			'rating'            => '',
			'star_rating'       => '',
			'meta_info'         => '',
			'is_deposit'        => '',
			'deposit_type'      => '',
			'deposit_amount'    => '',
			'address'           => '',
			'location'			=> '',
			'hide_is_full'      => '',
			'featured_text'     => '',
			'booking_method'    => ''
		);
		$this->post_meta_atts = $meta_atts;
	}
	public function set_meta_attributes() {
		$meta_arr = array();
		$meta_label_arr = array();
		$meta_value_def = array(
			'date_type'       => '1',
			'frequency'       => 'weekly',
			'weekly'          => '1',
			'show_gallery'    => '1',
			'show_cabin_type' => '1'
		);
		foreach( $this->post_meta_atts as $att => $name ){
			$key = $att;
			$meta_arr[$key] = (isset($meta_value_def[$key]) ? $meta_value_def[$key] : '');
			$meta_label_arr[$key] = $name;
		}
		$this->post_meta_def = $meta_arr;
		$this->post_meta = $meta_arr;
		$this->post_meta_label = $meta_label_arr;
	}
	public function init( $atts = array(), $query_args = array() ) {
		// set attributes
		$default_atts = array(
			'layout'               => 'cruise',
			'limit_post'           => '-1',
			'author'               => '',
			'offset_post'          => '0',
			'sort_by'              => '',
			'pagination'           => '',
			'location_slug'        => '',
			'category_slug'        => '',
			'facility_slug'        => '',
			'columns'              => '1',
			'paged'                => '',
			'cur_limit'            => '',
			'content_display'      => '',
			'bg_image'             => '',
			'post_in'              => '',
			'show_reviews'         => '1',
			'show_star_rating'     => '1',
		);
		$atts = array_merge( $default_atts, $atts );
		$atts['taxonomy_slug'] = $this->get_taxonomy_params( $this->post_type, $this->post_taxonomy, $atts );
		$atts['offset_post'] = absint( $atts['offset_post'] );
		if( isset( $atts['f_cruise_slug'] ) && !empty( $atts['f_cruise_slug'] ) ) {
			$atts['post_slug'] = $atts['f_cruise_slug'];
		}
		if( isset( $atts['cruise_slug'] ) && !empty( $atts['cruise_slug'] ) ) {
			$atts['post_slug'] = $atts['cruise_slug'];
		}
		if( isset( $atts['featured_filter'] ) && !empty( $atts['featured_filter'] ) ) {
			$atts['meta_key'] = array(
				'slzexploore_cruise_is_featured' => $atts['featured_filter']
			);
		}
		// check if have address, show post in Map Location shortcode
		if( isset($atts['allow_address_empty']) && !empty($atts['allow_address_empty']) ){
			$atts['meta_key_compare'][] = array(
						'key'     => 'slzexploore_cruise_address',
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

		//check booked
		$query_booked = $this->check_cruise_booked();
		if(!empty($query_booked)){
			$query_args = array_merge( $query_booked, $query_args );
		}
		// setting
		$this->setting( $query_args);
	}
	public function setting( $query_args ){
		if( !isset( $this->attributes['uniq_id'] ) ) {
			$this->attributes['uniq_id'] = 'block-'.Slzexploore_Core::make_id();
		}
		$this->post_view_key = SLZEXPLOORE_CORE_POST_VIEWS;
		// disable status
		$disable_status = Slzexploore_Core::get_theme_option('slz-cruises-disable-status');
		if( !empty( $disable_status ) ){
			$this->attributes['meta_key_compare'][] = array(
				'relation' => 'OR',
				array(
					'key'     => 'slzexploore_cruise_status',
					'value'   => $disable_status,
					'compare' => 'NOT IN'
				),
				array(
					'key'     => 'slzexploore_cruise_status',
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
			'1' => 'col-sm-12',
			'2' => 'col-sm-6',
			'3' => 'col-sm-4',
			'4' => 'col-sm-3',
		);;
	
		if( $column && isset($def[$column])) {
			$this->attributes['responsive-class'] = $def[$column];
		} else {
			$this->attributes['responsive-class'] = $def['1'];
		}
	}

	//-------------------- Render Html << -------------------------

	/**
	 * Render html by list.
	 *
	 * @param array $html_options
	 * @format: 1$ - img, 2$ - title, 3$ - discount, 4$ - price, 5$ - excerpt, 6$ - button, 7$ - responsive, 8$ - meta info
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
			$html_options['post_description'] = $this->post_meta['description'];
			printf( $html_options['html_format'],
					$this->get_featured_image( $this->html_format ),
					$this->get_title( $this->html_format ),
					$this->get_discount(),
					$this->get_price(),
					$this->get_short_des(),
					$this->get_button( $count ),
					$this->attributes['responsive-class'].' '.$this->get_post_class(),
					$this->get_meta_info(),
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
			$html_options['post_description'] = $this->post_meta['description'];
			printf( $html_options['html_format'],
					$this->get_featured_image( $html_options ),
					$this->get_title( $html_options ),
					$this->get_post_class(),
					$this->get_feature_ribbon()
			);
		}
		$this->reset();
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
			printf( $html_options['html_format'],
					$this->render_post_images( $this->post_id, $html_options['gallery_item'] )
			);
		}
		$this->reset();
	}
	public function render_post_images( $post_id, $format ){
		$output = '';
		$image_ids = array();
		$gallery_ids = get_post_meta( $post_id, $this->post_meta_prefix . 'gallery_ids', true );
		if( !empty( $gallery_ids ) ){
			$image_ids = explode( ',', rtrim( $gallery_ids, ',' ) );
		}
		$thumbnail_id = get_post_thumbnail_id( $post_id );
		if( !empty( $thumbnail_id ) ){
			array_unshift( $image_ids, $thumbnail_id );
		}
		if( !empty( $image_ids ) ){
			foreach( $image_ids as $k => $image_id ){
				if( wp_attachment_is_image( $image_id ) ){
					$cls_position = 'glry-absolute';
					if( $k == 0 ){
						$cls_position = 'glry-relative';
					}
					$image_url = wp_get_attachment_image_src( $image_id, $this->attributes['thumb-size']['large'] );
					$image     = wp_get_attachment_image( $image_id, $this->attributes['thumb-size']['small'], false,
														array( "class" => "img-responsive" ) );
					$output .= sprintf( $format, $image_url[0], $post_id, $cls_position, $image );
				}
			}
		}
		return $output;
	}
	//-------------------- Render Html >> -------------------------
	//------------------- Post Infomations << -------------------
	public function get_feature_ribbon(){
		$out = '';
		$format = $this->html_format['feature_ribbon_format'];
		if( !empty($this->post_meta['is_featured']) && $this->post_meta['featured_text']){
			$out = sprintf($format,$this->post_meta['featured_text']);
		}
		return $out;
	}
	public function get_meta_info() {
		$info = array();
		if( $this->attributes['show_star_rating'] ) {
			$info[] = $this->get_star_rating();
		}
		if( $this->attributes['show_reviews'] ) {
			$info[] = $this->get_reviews( $this->html_format );
		}
		if( $info ) {
			$info = implode('', $info);
			if( $info ){
				return sprintf($this->html_format['meta_format'], $info );
			}
		}
	}
	public function get_button( $index ) {
		$btn = '';
		if(isset($this->attributes['btn_book']) && $this->attributes['btn_book'] ) {
			$btn = sprintf($this->html_format['btn_book_format'],
							esc_html( $this->attributes['btn_book'] ),
							esc_url( $this->permalink )
						);
		}
		if(isset($this->attributes['btn_custom']) && $this->attributes['btn_custom'] ) {
			$btn_url = 'javascript:void(0);';
			if( isset($this->attributes['btn_link']) && !empty($this->attributes['btn_link']) ) {
				$arr_link = Slzexploore_Core_Util::get_link( $this->attributes['btn_link'] );
				if( !empty( $arr_link['link'] ) ) {
					$btn_url = esc_url( $arr_link['link'] );
				}
			}
			$btn .= sprintf($this->html_format['btn_book_format'],
					esc_html( $this->attributes['btn_custom'] ),
					$btn_url
			);
		}
		$btn .= $this->get_share_post();
		if( $btn ){
			$btn = sprintf($this->html_format['btn_group'], $btn);
		}
		return $btn;
	}
	public function get_share_post() {
		$show_share = Slzexploore::get_option('slz-cruises-show-share');
		$share_link = slzexploore_core_get_share_link();
		if( $show_share && !empty( $share_link ) ){
			return sprintf($this->html_format['share_format'], $share_link );
		}
		return '';
	}
	public function get_price() {
		if( $this->post_password ) return '';
		$out = '';
		$format = $this->html_format['price_format'];
		$price = Slzexploore_Core_Format::format_number( $this->post_meta['price_adult'] );
		$sign = sprintf( $this->html_format['sign_price_format'], Slzexploore_Core::get_theme_option('slz-currency-sign') );
		if( !empty( $price ) ) {
			$duration = $this->post_meta['duration'];
			$decimal = Slzexploore_Core::get_theme_option('slz-currency-decimal');
			$price = '<span class="number">' . number_format_i18n( $price, $decimal ) .'</span>';
			$sign_position = Slzexploore_Core::get_theme_option('slz-symbol-currency-position');
			if( $sign_position == 'before' ) {
				$price = $sign .$price;
			} else {
				$price = $price .$sign;
			}
			if( !empty($duration) ) {
				$duration = sprintf($this->html_format['duration_format'], $duration);
			}
			$out = sprintf($format, $price, $duration );
		}
		return $out;
	}
	public function get_column_price() {
		$out = '';
		$format = '%1$s';
		$price = Slzexploore_Core_Format::format_number( $this->post_meta['price_adult'] );
		$sign = sprintf( $this->html_format['sign_price_format'], Slzexploore_Core::get_theme_option('slz-currency-sign') );
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
	public function get_discount() {
		if( $this->post_password ) return '';
		$is_discount = $this->post_meta['is_discount'];
		if( $is_discount ) {
			$format = $this->html_format['discount_fotmat'];
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
	
			return sprintf($format, $discount_text);
		}
	}
	public function get_star_rating() {
		$out = '';
		if( !empty( $this->post_meta['star_rating'] ) ) {
			$format = $this->html_format['star_format'];
			$out = sprintf( $format, esc_html( $this->post_meta['star_rating'] ) );
		}
		return $out;
	}
	public function get_short_des() {
		if( $this->post_password ) return '';
		$description = $this->post_meta['description'];
		
		if( empty( $description ) ){
			$description = $this->post_meta['meta_info'];
		}
		if( empty( $description ) ){
			$description = get_the_excerpt();
		}
		$out = sprintf( $this->html_format['short_desc_format'], wp_kses_post( $description ) );
		return $out;
	}
	public function get_address_info() {
		$output = array();
		$i = 0;
		while ( $this->query->have_posts() ) {
			$this->query->the_post();
			$this->loop_index();
			$address = $this->post_meta['address'];
			if(!empty($address)){
				$output[$i]['address']= $address;

			}
			//Location
			$location  = get_post_meta( $this->post_id, 'slzexploore_cruise_location', true );
			if( !empty($address) && !empty( $location ) ){
				$location_arr = explode( ',', $location );
				if( count( $location_arr ) >= 2 ) {
					$output[$i]['lat'] = $location_arr[0];
					$output[$i]['lng'] = $location_arr[1];
				}
				$output[$i]['title'] = $this->get_title();
				$output[$i]['star'] = substr($this->get_reviews(),0,1);
				$output[$i]['href'] = $this->permalink;
				$output[$i]['image'] = '';
				if( has_post_thumbnail() ){
					$output[$i]['image'] = get_the_post_thumbnail_url( $this->post_id, 'slzexploore-thumb-116x63' );
				}
				$i++;
			}
		
		}
		$this->reset();
		return $output;
	}
	public function set_custom_css() {
		$custom_css = '';
		$css_defaults = array(
			'btn_color'          => '.%1$s .cruises-layout .content-wrapper > .content .group-btn-cruises .left-btn, .cruises-layout .content-wrapper > .content .group-btn-cruises .right-btn{ color: %2$s; }' . "\n",
			'btn_bg_color'       => '.%1$s .cruises-layout .content-wrapper > .content .group-btn-cruises .left-btn, .cruises-layout .content-wrapper > .content .group-btn-cruises .right-btn{background-color: %3$s;}' . "\n" .
									'.%1$s .cruises-layout .content-wrapper > .content .group-btn-cruises .left-btn:hover, .cruises-layout .content-wrapper > .content .group-btn-cruises .right-btn:hover{color: %3$s;}' . "\n",
			'btn_hover_bg_color' => '.%1$s .cruises-layout .content-wrapper > .content .group-btn-cruises .left-btn:hover, .cruises-layout .content-wrapper > .content .group-btn-cruises .right-btn:hover{background-color: %4$s;}',
		);
		foreach( $css_defaults  as $key => $css ) {
			if( isset($this->attributes[$key]) && $this->attributes[$key] ) {
				$custom_css .= $css;
				$css_defaults[$key] = $this->attributes[$key];
			} else {
				$css_defaults[$key] = '';
			}
		}
		extract($css_defaults);
		if( $custom_css ){
			
			$custom_css = sprintf($custom_css, $this->uniq_id, $btn_color, $btn_bg_color, $btn_hover_bg_color );
			do_action( 'slzexploore_core_add_inline_style', $custom_css );
		}
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
	// count number of cruise by cruise star
	public function get_post_count_by_star( $number_star = '' ) {
		global $wpdb;
		$result = $wpdb->get_results( $wpdb->prepare("SELECT * FROM $wpdb->postmeta WHERE meta_key = 'slzexploore_cruise_star_rating' AND meta_value = '%s' " , $number_star), ARRAY_A );
		return count($result);
	}
	//------------------- Post Infomations >> -------------------
	private function get_thumb_size() {
		$params = Slzexploore_Core::get_params( 'block-image-size', $this->attributes['layout'] );
		$this->attributes['thumb-size'] = Slzexploore_Core_Util::get_thumb_size( $params, $this->attributes );
	}
	public function set_default_options( &$html_options = array() ) {
		$defaults = array(
			'title_format'      => '<a href="%2$s" class="title">%1$s</a>',
			'star_format'       => '<div class="stars stars%1$s"><strong class="rating">%1$s</strong></div>',
			'image_format'      => '<a class="link" href="%2$s">%1$s</a>',
			'short_desc_format' => '<div class="text">%1$s</div>',
			'price_format'      => '<div class="price">%1$s%2$s</div>',
			'sign_price_format' => '<sup>%1$s</sup>',
			'duration_format'   => '<p class="for-price">%1$s</p>',
			'discount_fotmat'   => '<div class="label-sale">%1$s</div>',
			'btn_book_format'   => '<a href="%2$s" class="btn btn-gray">%1$s</a>',
			'share_format'      => '<div class="btn-share-social"><a href="javascript:void(0)" class="btn-share"><i class="icons fa fa-share-alt"></i></a>%1$s</div>',
			'btn_group'         => '<div class="group-button">%1$s</div>',
			'review_format'     => '<div class="guest-rating">'.esc_html__('Review Ratings: %1$s/%2$s', 'slzexploore-core').'</div>',
			'meta_format'       => '<div class="cruises-meta-info star-ratings">%1$s<div class="clearfix"></div></div>',
			'feature_ribbon_format'=>'<div class="ribbon-sale"><i class="fa fa-bolt"></i><span>%1$s</span></div>'
		);
		
		$html_options = array_merge( $defaults, $html_options );
		$this->html_format = $html_options;
		return $html_options;
	}
	// get link search
	public function get_search_link() {
		$post_type = get_post_type_object( 'slzexploore_cruise' );
		$slug = $post_type->rewrite['slug'];
		$link = home_url( '/' ) . $slug;
		return esc_url($link);
	}
	// get search atts
	public function get_search_atts( &$atts, &$query_args, $search_data ){
		// search by keyword
		if( isset($search_data['keyword']) && !empty($search_data['keyword']) ){
			$query_args['s'] = $search_data['keyword'];
		}
		// search cruise by price
		if( isset($search_data['price']) && !empty($search_data['price']) ){
			$price = explode( ',', $search_data['price'] );
			$atts['meta_key_compare'][] = array(
						'key'     => 'slzexploore_cruise_price_adult',
						'value'   => $price,
						'type'    => 'numeric',
						'compare' => 'BETWEEN'
					);
		}
		// search cruise by location
		if( isset($search_data['location']) && !empty($search_data['location']) ){
			$atts['location_slug'] = $search_data['location'];
		}
		// search cruise by category
		if( isset($search_data['category']) && !empty($search_data['category']) ){
			$atts['category_slug'] = $search_data['category'];
		}
		// search cruise by facility
		if( isset($search_data['facility']) && !empty($search_data['facility']) ){
			$atts['facility_slug'] = $search_data['facility'];
		}
		// search cruise by rating
		if( isset($search_data['rating']) && !empty($search_data['rating']) ){
			$atts['meta_key']['slzexploore_cruise_rating'] = $search_data['rating'];
		}
		// search cruise by star rating
		if( isset($search_data['star_rating']) && !empty($search_data['star_rating']) ){
			$atts['meta_key']['slzexploore_cruise_star_rating'] = $search_data['star_rating'];
		}
		// search by date
		if( isset($search_data['start_date']) && !empty( $search_data['start_date'] ) ) {
			$date_weekly = date( 'N', strtotime( $search_data['start_date'] ) );
			$monthly_d = date( 'd', strtotime( $search_data['start_date'] ) );
			$monthly_j = date( 'j', strtotime( $search_data['start_date'] ) );
			$atts['meta_key_compare'][] = array(
						'relation' => 'OR',
						// find cruise that have date_type empty
						array(
							'key'     => $this->post_meta_prefix . 'date_type',
							'value'   => ''
						),
						// find cruise that frequency = weekly and weekly = start_date
						array(
							'key'     => $this->post_meta_prefix . 'weekly',
							'value'   => $date_weekly,
							'compare' => 'LIKE'
						),
						// find cruise that frequency = monthly and monthly = start_date
						array(
							'key'     => $this->post_meta_prefix . 'monthly',
							'value'   => $monthly_d,
							'compare' => 'LIKE'
						),
						array(
							'key'     => $this->post_meta_prefix . 'monthly',
							'value'   => $monthly_j
						),
						// find cruise that frequency = other and start_date = start_date
						array(
							'key'     => $this->post_meta_prefix . 'start_date',
							'value'   =>  $search_data['start_date']
						),
						array(
							array(
								'key'     => $this->post_meta_prefix . 'start_date',
								'value'   => '',
								'compare' => '!='
							),
							array(
								'key'     => $this->post_meta_prefix . 'start_date',
								'value'   =>  $search_data['start_date'],
								'compare' => '<'
							),
							array(
								'key'     => $this->post_meta_prefix . 'end_date',
								'value'   =>  $search_data['start_date'],
								'compare' => '>='
							)
						)
					);
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
										count( $arr_locations ) . esc_html__( ' Locations', 'slzexploore-core' ));
				}
				else{
					$location = array();
					foreach( $arr_locations as $k => $slug ){
						$term = Slzexploore_Core_Com::get_tax_options_by_slug( $slug, 'slzexploore_cruise_location' );
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
				$term = Slzexploore_Core_Com::get_tax_options_by_slug( $data['location'], 'slzexploore_cruise_location' );
				$output .= sprintf( $format, 'location', $term->name );
			}
		}
		if( isset( $data['start_date'] ) && !empty( $data['start_date'] ) ){
			$output .= sprintf( $format, 'start_date', $data['start_date']);
		}
		if( isset($data['price']) && !empty($data['price']) ){
			$max_value = Slzexploore::get_option('slz-cruises-price-max');
			$default_price = '0,' . $max_value;
			if( $data['price'] != $default_price ){
				$price = str_replace( ',', ' ~ ', $data['price'] );
				$sign = Slzexploore_Core::get_theme_option('slz-currency-sign');
				$output .= sprintf( $format, 'price', $price . $sign );
			}
		}
		if( isset( $data['rating'] ) && !empty( $data['rating'] ) ){
			if( is_array( $data['rating'] ) ){
				$rating = implode( ' or ', $data['rating'] );
			}
			else{
				$rating = $data['rating'];
			}
			$output .= sprintf( $format, 'rating', $rating . esc_html__( ' Review Star(s)', 'slzexploore-core' ) );
		}
		if( isset( $data['star_rating'] ) && !empty( $data['star_rating'] ) ){
			if( is_array( $data['star_rating'] ) ){
				$rating = implode( ' or ', $data['star_rating'] );
			}
			else{
				$rating = $data['star_rating'];
			}
			$output .= sprintf( $format, 'star_rating', $rating . esc_html__( ' Cruise Star(s)', 'slzexploore-core' ) );
		}
		if( isset( $data['category'] ) && !empty( $data['category'] ) ){
			if( is_array( $data['category'] ) ){
				$categories = array_unique( $data['category'] );
				if( count( $categories ) > 2 ){
						$output .= sprintf( $format, 'category',
											count( $categories ) . esc_html__( ' Categories', 'slzexploore-core' ));
				}
				else{
					$cat_names = '';
					foreach( $categories as $k => $slug ){
						$term = Slzexploore_Core_Com::get_tax_options_by_slug( $slug, 'slzexploore_cruise_cat' );
						if( $k != 0 ){
							$cat_names .= ' or ';
						}
						$cat_names .= $term->name;
					}
					if( !empty( $cat_names ) ){
						$output .= sprintf( $format, 'category', $cat_names );
					}
				}
			}
			else{
				$term = Slzexploore_Core_Com::get_tax_options_by_slug( $data['category'], 'slzexploore_cruise_cat' );
				$output .= sprintf( $format, 'category', $term->name );
			}
		}
		if( isset( $data['facility'] ) && !empty( $data['facility'] ) ){
			if( is_array( $data['facility'] ) ){
				$arr_facilities = array_filter( array_unique( $data['facility'] ) );
				if( count( $arr_facilities ) > 2 ){
						$output .= sprintf( $format, 'facility',
											count( $arr_facilities ) . esc_html__( ' Facilities', 'slzexploore-core' ));
				}
				else{
					$facilities = array();
					foreach( $arr_facilities as $k => $slug ){
						$term = Slzexploore_Core_Com::get_tax_options_by_slug( $slug, 'slzexploore_cruise_facility' );
						if( $term ){
							$facilities[] = $term->name;
						}
					}
					if( !empty( $facilities ) ){
						$output .= sprintf( $format, 'facility', implode( ' or ', $facilities ) );
					}
				}
			}
			else{
				$term = Slzexploore_Core_Com::get_tax_options_by_slug( $data['facility'], 'slzexploore_cruise_facility' );
				$output .= sprintf( $format, 'facility', $term->name );
			}
		}
		if( !empty( $output ) ){
			$clear_all = sprintf('<a href="javascript:void(0);" class="btn-reset-all">%s</a>',
									esc_html__( 'Clear All', 'slzexploore-core' ));
			if( $is_archive ){
				$archive_link = get_post_type_archive_link( 'slzexploore_cruise' );
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
	public function check_cruise_booked(){

		$query_args = $array_booked = array();
		$args= array(
			'posts_per_page'   => -1,
			'post_type'        => 'slzexploore_cruise',
			'post_status'      => 'publish',
			'suppress_filters' => false,
			);
		$posts_array  = Slzexploore_Core_Com::get_post_id2title( $args, array(), false );
		if( !empty( $posts_array ) ){
			foreach( $posts_array as $key => $value ){

				$args_cabin = array(
					'post_type'    => 'slzexploore_cabin',
					'meta_query' => array(
						array(
							'key'     => 'slzexploore_cabin_cruise_id',
							'value'   => $key
						)
					)
				);

				$number = 0;
				$cabins  = Slzexploore_Core_Com::get_post_id2title( $args_cabin, array(), false );
				
				$book_full = false;
				if( !empty( $cabins ) ){
					foreach( $cabins as $cabin_id => $cabin_name ){

						$args_booked = array(
							'post_type'    => 'slzexploore_crbook',
							'meta_query' => array(
								array(
									'key'     => 'slzexploore_crbook_cabin_type_id',
									'value'   => $cabin_id
								),
								array(
									'key'     => 'slzexploore_crbook_cruise_id',
									'value'   => $key
								)
							)
						);
						$cabin_number = 0;
						$booked_number = 0;
						$booked_cabin  = Slzexploore_Core_Com::get_post_id2title( $args_booked, array(), false );
						
						if( !empty( $booked_cabin ) ){
							foreach( $booked_cabin as $book_id => $book_name ){
								
								$booked_number += intval( get_post_meta( $book_id, 'slzexploore_crbook_number', true ) );
							}
							$cabin_number = get_post_meta( $cabin_id, 'slzexploore_cabin_number', true );

							if( intval($cabin_number) -  intval($booked_number) < 1 ){
								$book_full = true;
							}else{
								$book_full = false;
							}

						}

					}
				}

				$hide =  get_post_meta( $key, 'slzexploore_cruise_hide_is_full', true );
				if( $book_full == true && $hide == '1'){
					array_push($array_booked,$key);
				}

			}
		}
		$query_args['post__not_in'] = $array_booked;
		return $query_args;
	}
}