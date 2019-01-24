<?php
class Slzexploore_Core_Car extends Slzexploore_Core_Custom_Post_Model {

	private $post_type = 'slzexploore_car';
	private $post_taxonomy = array( 'cat', 'location' );
	private $html_format;

	public function __construct() {
		$this->meta_attributes();
		$this->set_meta_attributes();
		$this->post_meta_prefix = $this->post_type . '_';
		$this->set_default_options();
		$this->set_sort_key();
		$this->taxonomy_cat = 'slzexploore_car_cat';
		$this->uniq_id = 'block-' . Slzexploore_Core::make_id();
		$this->post_rating_key = $this->post_type . '_rating';
	}
	public function meta_attributes() {
		$meta_atts = array( 
			'display_title'        => '',
			'status'               => '',
			'description'          => '',
			'price'                => '',
			'price_text'           => '',
			'number'               => '',
			'max_people'           => '',
			'is_discount'          => '',
			'discount_rate'        => '',
			'discount_text'        => '',
			'discount_start_date'  => '',
			'discount_end_date'    => '',
			'gallery_ids'          => '',
			'rating'               => '',
			'mail_cc'              => '',
			'mail_bcc'             => '',
			'is_featured'          => '',
			'attachment_ids'       => '',
			'is_deposit'           => '',
			'deposit_type'         => '',
			'deposit_amount'       => '',
			'address'              => '',
			'location'			   => '',
			'hide_is_full'         => '',
			'featured_text'        => '',
			'booking_method'       => ''
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
			'layout'               => 'car',
			'limit_post'           => '-1',
			'author'               => '',
			'offset_post'          => '0',
			'sort_by'              => '',
			'pagination'           => '',
			'category_slug'        => '',
			'location_slug'        => '',
			'columns'              => '1',
			'paged'                => '',
			'cur_limit'            => '',
			'post_in'              => '',
			'show_reviews'         => '1',
		);
		$atts = array_merge( $default_atts, $atts );
		$atts['taxonomy_slug'] = $this->get_taxonomy_params( $this->post_type, $this->post_taxonomy, $atts );
		$atts['offset_post'] = absint( $atts['offset_post'] );
		if( isset( $atts['featured_filter'] ) && !empty( $atts['featured_filter'] ) ) {
			$atts['meta_key'] = array(
				'slzexploore_car_is_featured' => $atts['featured_filter']
			);
		}
		// check if have address, show post in Map Location shortcode
		if( isset($atts['allow_address_empty']) && !empty($atts['allow_address_empty']) ){
			$atts['meta_key_compare'][] = array(
						'key'     => 'slzexploore_car_address',
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
		$query_booked = $this->check_car_booked();
		if(!empty($query_booked)){
			$query_args = array_merge( $query_booked, $query_args );
		}

		// setting
		$this->setting( $query_args);
	}
	public function set_sort_key(){
		$this->sort_meta_key['price']        = $this->post_meta_prefix . 'price';
		$this->sort_meta_key['discount']     = $this->post_meta_prefix . 'discount_rate';
		$this->sort_meta_key['review_rating']= $this->post_meta_prefix . 'rating';
	}
	public function setting( $query_args ){
		if( !isset( $this->attributes['uniq_id'] ) ) {
			$this->attributes['uniq_id'] = 'block-'.Slzexploore_Core::make_id();
		}
		$this->post_view_key = SLZEXPLOORE_CORE_POST_VIEWS;
		// disable status
		$disable_status = Slzexploore_Core::get_theme_option('slz-car-disable-status');
		if( !empty( $disable_status ) ){
			$this->attributes['meta_key_compare'][] = array(
				'relation' => 'OR',
				array(
					'key'     => 'slzexploore_car_status',
					'value'   => $disable_status,
					'compare' => 'NOT IN'
				),
				array(
					'key'     => 'slzexploore_car_status',
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
			'1' => 'col-md-12',
			'2' => 'col-md-6',
			'3' => 'col-md-4 col-sm-4',
			'4' => 'col-md-3',
		);;
		
		if( $column && isset($def[$column])) {
			$this->attributes['responsive-class'] = $def[$column];
		} else {
			$this->attributes['responsive-class'] = $def['1'];
		}
	}

	/* -------------------- << Render Html >> ------------------------- */
	/**
	 * Render html by list.
	 *
	 * @param array $html_options
	 * @format: 1$ - img, 2$ - discount, 3$ - title, 4$ - price, 5$ - des, 6$ - button, 7$ - responsive, 8$ - reviews
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
			printf( $html_options['html_format'],
					$this->get_featured_image( $this->html_format ),
					$this->get_discount(),
					$this->get_title( $this->html_format ),
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
		$gallery_ids = get_post_meta( $post_id, 'slzexploore_car_gallery_ids', true );
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
	
	public function check_car_booked(){
		$query_args = $array_booked = array();
		$args= array(
			'posts_per_page' => -1,
			'post_type'=> 'slzexploore_car',
			'post_status'      => 'publish',
			'suppress_filters' => false,
			);
		$posts_array  = Slzexploore_Core_Com::get_post_id2title( $args, array(), false );
		if( !empty( $posts_array ) ){
			foreach( $posts_array as $key => $value ){
				$args_book = array(
					'post_type' => 'slzexploore_cbook',
					'meta_query' => array(
						array(
							'key'     => 'slzexploore_cbook_car_id',
							'value'   => $key,
						),
					),
				);
				$booked_car  = Slzexploore_Core_Com::get_post_id2title( $args_book, array(), false );
				$booked_number = 0;
				if( !empty( $booked_car ) ){
					foreach( $booked_car as $post_id => $post_name ){
						$booked_number  += intval( get_post_meta( $post_id, 'slzexploore_cbook_number', true ) );
					}
				}
				$car_number =  get_post_meta( $key, 'slzexploore_car_number', true );
				$available_number = intval( $car_number ) - intval( $booked_number );
				$hide =  get_post_meta( $key, 'slzexploore_car_hide_is_full', true );
				if($available_number < 1 && $hide == '1'){
					array_push($array_booked,$key);
				}
			}
		}
		$query_args['post__not_in'] = $array_booked;
		return $query_args;
	}
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
	public function get_price() {
		if( $this->post_password ) return '';
		$out = '';
		$price = Slzexploore_Core_Format::format_number( $this->post_meta['price'] );
		if( !empty( $price ) ) {
			$format = $this->html_format['price_format'];
			$decimal = Slzexploore_Core::get_theme_option('slz-currency-decimal');
			$price = '<span class="number">' . number_format_i18n( $price, $decimal ) .'</span>';
			$sign_position = Slzexploore_Core::get_theme_option('slz-symbol-currency-position');
			$sign = sprintf( $this->html_format['sign_price_format'], Slzexploore_Core::get_theme_option('slz-currency-sign') );
			if( $sign_position == 'before' ) {
				$price = $sign .$price;
			} else {
				$price = $price .$sign;
			}
			$price_subfix = $this->post_meta['price_text'];
			if( !empty( $price_subfix ) ){
				$price_subfix = sprintf( $this->html_format['price_subfix_format'], esc_html( $price_subfix ) );
			}
			$out = sprintf($format, $price, $price_subfix );
		}
		return $out;
	}
	public function get_column_price() {
		$out = '';
		$format = '%1$s';
		$price = Slzexploore_Core_Format::format_number( $this->post_meta['price'] );
		$sign = sprintf( $this->html_format['sign_price_format'],
				esc_attr( Slzexploore_Core::get_theme_option('slz-currency-sign') ) );
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
	public function get_category() {
		if( $this->post_password ) return '';
		$out = '';
		$categories = $this->get_taxonomy_slug( 'slzexploore_car_cat' );
		if( !empty( $categories ) ){
			$out = sprintf( $this->html_format['sub_title_format'], $categories );
		}
		return $out;
	}
	public function get_short_des() {
		if( $this->post_password ) return '';
		$description = $this->post_meta['description'];
		if( empty( $description ) ){
			$description = get_the_excerpt();
		}
		$out = sprintf( $this->html_format['short_desc_format'], wp_kses_post( $description ) );
		return $out;
	}
	public function get_button( $index ) {
		if( $this->post_password ) return '';
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
			$location  = get_post_meta( $this->post_id, 'slzexploore_car_location', true );
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
	public function get_share_post() {
		$show_share = Slzexploore::get_option('slz-car-show-share');
		$share_link = slzexploore_core_get_share_link();
		if( $show_share && !empty( $share_link ) ){
			return sprintf($this->html_format['share_format'], $share_link );
		}
		return '';
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
	
	//------------------- Post Infomations >> -------------------

	public function set_default_options( $html_options = array() ) {
		$defaults = array(
			'image_format'        => '<a class="link" href="%2$s">%1$s</a>',
			'title_format'        => '<a href="%2$s" class="title">%1$s</a>',
			'price_format'        => '<div class="price">%1$s %2$s</div>',
			'sign_price_format'   => '<sup>%1$s</sup>',
			'price_subfix_format' => '<p class="for-price">%1$s</p>',
			'sub_title_format'    => '<div class="sub-title">%1$s</div>',
			'short_desc_format'   => '<div class="text">%1$s</div>',
			'discount_format'     => '<div class="label-sale">%1$s</div>',
			'subtitle_format'     => '<div class="sub-title">%1$s</div>',
			'btn_book_format'     => '<a href="%2$s" class="btn btn-gray">%1$s</a>',
			'share_format'        => '<div class="btn-share-social"><a href="javascript:void(0)" class="btn-share"><i class="icons fa fa-share-alt"></i></a>%1$s</div>',
			'btn_group'           => '<div class="group-button">%1$s</div>',
			'thumb_href_class'    => '',
			'review_format'       => '<div class="guest-rating">'.esc_html__('Review Ratings: %1$s/%2$s', 'slzexploore-core').'</div>',
			'meta_format'         => '<div class="car-meta-info star-ratings">%1$s<div class="clearfix"></div></div>',
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
	
	// get car id from search atts
	public function get_car_id( $date_from, $date_to ){
		if( empty( $date_from ) && empty( $date_to ) ) {
			return '';
		}
		$atts = array();
		$prefix = 'slzexploore_cbook_';
		
		$atts['meta_key_compare'][] = array(
			array(
				'key'     => $prefix . 'date_from',
				'value'   => $date_to,
				'compare' => '<'
			),
			array(
				'key'     => $prefix . 'date_to',
				'value'   => $date_from,
				'compare' => '>'
			)
		);
		
		$model = new Slzexploore_Core_Car_Booking();
		$model->init( $atts );
		$car_id = $model->get_car_id();
		return $car_id;
	}
	// get link search
	public function get_search_link() {
		$post_type = get_post_type_object( 'slzexploore_car' );
		$slug = $post_type->rewrite['slug'];
		$link = home_url( '/' ).$slug;
		return esc_url($link);
	}
	// get search atts
	public function get_search_atts( &$atts, &$query_args, $search_data ){
		// search by keyword
		if( isset($search_data['keyword']) && !empty($search_data['keyword']) ){
			$query_args['s'] = $search_data['keyword'];
		}
		// search car by price
		if( isset($search_data['price']) && !empty($search_data['price']) ){
			$price = explode( ',', $search_data['price'] );
			$atts['meta_key_compare'][] = array(
						'key'     => 'slzexploore_car_price',
						'value'   => $price,
						'type'    => 'numeric',
						'compare' => 'BETWEEN'
					);
		}
		// search car by location
		if( isset($search_data['location']) && !empty($search_data['location']) ){
			$atts['location_slug'] = $search_data['location'];
		}
		// search car by category
		if( isset($search_data['category']) && !empty($search_data['category']) ){
			$atts['category_slug'] = $search_data['category'];
		}
		// search car by rating
		if( isset($search_data['rating']) && !empty($search_data['rating']) ){
			$atts['meta_key']['slzexploore_car_rating'] = $search_data['rating'];
		}
		// search car by date
		if( isset( $search_data['date_from'] ) && !empty($search_data['date_from'])  ){
			$car_id = $this->get_car_id( $search_data['date_from'], $search_data['date_to'] );
			if( !empty($car_id) ){
				$query_args['post__not_in'] = $car_id;
			}
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
						$term = Slzexploore_Core_Com::get_tax_options_by_slug( $slug, 'slzexploore_car_location' );
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
				$term = Slzexploore_Core_Com::get_tax_options_by_slug( $data['location'], 'slzexploore_car_location' );
				$output .= sprintf( $format, 'location', $term->name );
			}
		}
		if( isset( $data['date_from'] ) && !empty( $data['date_from'] ) ){
			$output .= sprintf( $format, 'date_from', $data['date_from']);
		}
		if( isset( $data['date_to'] ) && !empty( $data['date_to'] ) ){
			$output .= sprintf( $format, 'date_to', $data['date_to']);
		}
		if( isset($data['price']) && !empty($data['price']) ){
			$max_value = Slzexploore::get_option('slz-car-price-max');
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
			$output .= sprintf( $format, 'rating', $rating . esc_html__( ' Star(s)', 'slzexploore-core' ) );
		}
		if( isset( $data['category'] ) && !empty( $data['category'] ) ){
			if( is_array( $data['category'] ) ){
				$categories = array_unique( $data['category'] );
				if( count( $categories ) > 2 ){
					$output .= sprintf( $format, 'category',
										count( $categories ) . esc_html__( ' Categories', 'slzexploore-core' ) );
				}
				else{
					$cat_names = '';
					foreach( $categories as $k => $slug ){
						$term = Slzexploore_Core_Com::get_tax_options_by_slug( $slug, 'slzexploore_car_cat' );
						if( $k != 0 ){
							$cat_names .= ' or ';
						}
						$cat_names .= $term->name;
					}
					if( !empty( $cat_names ) ){
						$output .= sprintf( $format, 'category', $cat_names);
					}
				}
			}
			else{
				$term = Slzexploore_Core_Com::get_tax_options_by_slug( $data['category'], 'slzexploore_car_cat' );
				$output .= sprintf( $format, 'category', $term->name);
			}
		}
		if( !empty( $output ) ){
			$clear_all = sprintf('<a href="javascript:void(0);" class="btn-reset-all">%s</a>',
									esc_html__( 'Clear All', 'slzexploore-core' ));
			if( $is_archive ){
				$archive_link = get_post_type_archive_link( 'slzexploore_car' );
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
		$is_discount   = get_post_meta( $post_id, 'slzexploore_car_is_discount', true );
		$discount_rate = get_post_meta ( $post_id, 'slzexploore_car_discount_rate', true );
		if( $is_discount && $discount_rate ){
			$start_date = get_post_meta( $post_id, 'slzexploore_car_discount_start_date', true );
			$end_date   = get_post_meta ( $post_id, 'slzexploore_car_discount_end_date', true );
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