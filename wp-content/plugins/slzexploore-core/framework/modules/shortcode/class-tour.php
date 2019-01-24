<?php
class Slzexploore_Core_Tour extends Slzexploore_Core_Custom_Post_Model {

	private $post_type = 'slzexploore_tour';
	private $post_taxonomy = array( 'cat', 'location', 'tag', 'status' );
	public $html_format;
	
	public function __construct( $options = array() ) {
		$this->meta_attributes();
		$this->set_meta_attributes();
		$this->post_meta_prefix = $this->post_type . '_';
		$this->set_default_options( $options );
		$this->uniq_id = 'block-' . Slzexploore_Core::make_id();
		$this->sort_meta_key['price'] = $this->post_type . '_price_adult';
		$this->sort_meta_key['discount'] = $this->post_type . '_discount_rate';
		$this->sort_meta_key['review_rating'] = $this->post_type . '_rating';
		$this->sort_meta_key['start_date'] = $this->post_type . '_start_date';
		$this->taxonomy_cat = 'slzexploore_tour_cat';
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
			'price_infant'      => '',
			'destination'       => '',
			'is_discount'       => '',
			'discount_rate'     => '',
			'discount_text'     => '',
			'show_gallery'      => '',
			'gallery_box_title' => '',
			'gallery_ids'       => '',
			'gallery_backg'     => '',
			'show_team'         => '',
			'team'              => '',
			'team_box_info'     => '',
			'team_box_title'    => '',
			'mail_cc'           => '',
			'mail_bcc'          => '',
			'is_featured'       => '',
			'attachment_ids'    => '',
			'rating'            => '',
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
			'show_team'   => '1',
			'date_type'   => '1',
			'frequency'   => 'weekly',
			'weekly'      => '1',
			'is_active'   => '1',
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
			'layout'               => 'tour',
			'limit_post'           => '-1',
			'author'               => '',
			'offset_post'          => '0',
			'sort_by'              => '',
			'pagination'           => '',
			'tag_slug'             => '',
			'location_slug'        => '',
			'category_slug'        => '',
			'columns'              => '1',
			'paged'                => '',
			'cur_limit'            => '',
			'content_display'      => '',
			'bg_image'             => '',
			'post_in'              => '',
			'show_views'           => '1',
			'show_reviews'         => '1',
			'show_wishlist'        => '1',
			'show_share_post'      => '1',
			'btn_wlist_action'     => '',
		);
		$atts = array_merge( $default_atts, $atts );
		$atts['taxonomy_slug'] = $this->get_taxonomy_params( $this->post_type, $this->post_taxonomy, $atts );
		$atts['offset_post'] = absint( $atts['offset_post'] );
		if( isset( $atts['f_tour_slug'] ) && !empty( $atts['f_tour_slug'] ) ) {
			$atts['post_slug'] = $atts['f_tour_slug'];
		}
		if( isset( $atts['tour_slug'] ) && !empty( $atts['tour_slug'] ) ) {
			$atts['post_slug'] = $atts['tour_slug'];
		}
		if( isset( $atts['featured_filter'] ) && !empty( $atts['featured_filter'] ) ) {
			$atts['meta_key'] = array(
				'slzexploore_tour_is_featured' => $atts['featured_filter']
			);
		}
		// check if have address, show post in Map Location shortcode
		if( isset($atts['allow_address_empty']) && !empty($atts['allow_address_empty']) ){
			$atts['meta_key_compare'][] = array(
						'key'     => 'slzexploore_tour_address',
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
		$query_booked = $this->check_tour_booked();
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
		$disable_status = Slzexploore_Core::get_theme_option('slz-tour-disable-status');
		if( !empty( $disable_status ) ){
			$this->attributes['meta_key_compare'][] = array(
				'relation' => 'OR',
				array(
					'key'     => 'slzexploore_tour_status',
					'value'   => $disable_status,
					'compare' => 'NOT IN'
				),
				array(
					'key'     => 'slzexploore_tour_status',
					'compare' => 'NOT EXISTS'
				)
			);
		}

		// hide passed tour
		$hide_tours = Slzexploore_Core::get_theme_option('slz-tour-hide-passed');
		if( !empty( $hide_tours ) ){
			$args = array(
				'post_type' => 'slzexploore_tour',
				'meta_query' => array(
					array(
						'relation' => 'OR',
						array(
							'key'     => 'slzexploore_tour_frequency',
							'value'   => 'season'
						),
						array(
							'key'     => 'slzexploore_tour_frequency',
							'value'   => 'other'
						)
					),
					array(
						array(
							'key'     => 'slzexploore_tour_end_date',
							'value'   => '',
							'compare' => '!='
						),
						array(
							'key'     => 'slzexploore_tour_end_date',
							'value'   => date('Y-m-d'),
							'compare' => '<'
						)
					)
				)
			);
			$arr_tours = Slzexploore_Core_Com::get_post_id2title( $args, array(), false );
			if( !empty( $arr_tours ) ){
				$this->attributes['post__not_in'] = array_keys($arr_tours);
			}
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
			'2' => 'col-md-6 col-sm-6',
			'3' => 'col-md-4 col-sm-4',
			'4' => 'col-md-3',
		);;
	
		if( $column && isset($def[$column])) {
			$this->attributes['responsive-class'] = $def[$column];
		} else {
			$this->attributes['responsive-class'] = $def['1'];
		}
	}

	//-------------------- Render Html << -------------------------
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
	/**
	 * Render html by carousel.
	 *
	 * @param array $html_options      Format: 1$ - img, 2$ - title, 3$ - discount, 4$ - meta, 5$ - price,
	 *                                         6$ - excerpt, 7$ - button, 8$ - post class
	 */
	public function render_block_carousel( $html_options = array() ) {
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
					$this->get_discount(),
					$this->get_meta_info(),
					$this->get_price(),
					$this->get_excerpt( $html_options ),
					$this->get_button($count),
					$this->get_post_class(),
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
	
	/**
	 * Render html by list.
	 *
	 * @param array $html_options
	 * @format: 1$ - img, 2$ - title, 3$ - discount, 4$ - meta, 5$ - price, 6$ - excerpt, 7$ - button, 8$ - responsive
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
					$this->get_featured_image( $html_options ),
					$this->get_display_title(),
					$this->get_discount(),
					$this->get_meta_info(),
					$this->get_price(),
					$this->get_excerpt( $html_options ),
					$this->get_button( $count ),
					$this->attributes['responsive-class'].' '.$this->get_post_class(),
					$this->get_feature_ribbon()
			);
		}
		$this->reset();
	}
	
	//-------------------- Render Html >> -------------------------
	//------------------- Post Infomations << -------------------
	private function set_meta_info(){
		$meta_enabled = Slzexploore::get_option('slz-tour-list-info', 'enabled');
		if( !isset( $meta_enabled['comment'] ) ) {
			$this->attributes['show_reviews'] = 0;
		}
		if( !isset( $meta_enabled['view'] ) ) {
			$this->attributes['show_views'] = 0;
		}
		if( !isset( $meta_enabled['list-wist'] ) ) {
			$this->attributes['show_wishlist'] = 0;
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
		if( $this->attributes['show_share_post'] ) {
			$info[] = $this->get_share_post();
		}
		if( $this->attributes['show_reviews'] ) {
			$info[] = $this->get_reviews( $this->html_format );
		}
		
		if( $info ) {
			return sprintf($this->html_format['meta_format'], implode('', $info), esc_attr('slz-item-'.count($info)) );
		}
	}
	public function check_tour_booked(){
		$query_args = $array_booked = array();
		$args= array(
			'posts_per_page'   => -1,
			'post_type'        => 'slzexploore_tour',
			'post_status'      => 'publish',
			'suppress_filters' => false,
			);
		$posts_array  = Slzexploore_Core_Com::get_post_id2title( $args, array(), false );
		if( !empty( $posts_array ) ){
			foreach( $posts_array as $key => $value ){
				$args_booked = array(
					'post_type'  => 'slzexploore_tbook',
					'meta_query' => array(
						array(
							'key'     => 'slzexploore_tbook_tour',
							'value'   => $key
						),
					)
				);
				$booked_person = 0;
				$booked_tour  = Slzexploore_Core_Com::get_post_id2title( $args_booked, array(), false );
				if( !empty( $booked_tour ) ){
					foreach( $booked_tour as $post_id => $post_name ){
						$booked_person  += intval( get_post_meta( $post_id, 'slzexploore_tbook_adults', true ) );
						$booked_person  += intval( get_post_meta( $post_id, 'slzexploore_tbook_children', true ) );
					}
				}
				$available_seat = get_post_meta( $key, 'slzexploore_tour_available_seat', true );
				$allow_seats = intval( $available_seat ) - intval( $booked_person );
				$hide =  get_post_meta( $key, 'slzexploore_tour_hide_is_full', true );
				if( intval( $allow_seats ) < 1 && $hide == '1' ){
					array_push($array_booked,$key);
				}
			}
		}
		$query_args['post__not_in'] = $array_booked;
		return $query_args;
	}
	public function get_wishlist() {
		$class = '';
		$href = 'javascript:void(0);';
		if( is_user_logged_in() ){
			if( $this->attributes['btn_wlist_action'] ) {
				$class = 'slz_add_wish_list';
			}
			$user_id = get_current_user_id();
			$user_meta = get_user_meta( $user_id, 'slzexploore_tour_wish_list' );
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
		return sprintf($this->html_format['wishlist_format'], $wlist_number, $class, $this->post_id, $href );
	}
	public function get_share_post() {
		$share_link = slzexploore_core_get_share_link();
		return sprintf($this->html_format['share_format'], $share_link );
	}
	public function get_button( $index ) {
		$btn = '';
		$custom_css = $custom_left = $custom_right = '';
		if(isset($this->attributes['btn_book']) && $this->attributes['btn_book'] ) {
			$btn .= sprintf($this->html_format['btn_book_format'],
							esc_html( $this->attributes['btn_book'] ),
							esc_url( $this->permalink )
						);
			$custom_left = '.%1$s .tours-layout .content-wrapper > .content .group-btn-tours .left-btn {border-radius: 50px; padding-right: 25px; border-right: 0;}';
		}
		if(isset($this->attributes['btn_wlist']) && $this->attributes['btn_wlist'] ) {
			$add_wlist = '';
			$btn_url = $this->permalink;
			if( isset($this->attributes['btn_wlist_action']) && $this->attributes['btn_wlist_action'] ) {
				if( is_user_logged_in() ){
					$add_wlist = 'slz_add_wish_list';
					$btn_url = 'javascript:void(0);';
					$user_meta = get_user_meta( get_current_user_id(), 'slzexploore_tour_wish_list' );
					if( in_array( $this->post_id, $user_meta ) ){
						$add_wlist = 'added_wish_list';
					}
				}
				else{
					$login_page_id = get_option( 'slzexploore_login_page_id' );
					$btn_url = get_permalink( $login_page_id );
				}
			}
			elseif( isset($this->attributes['btn_link']) && !empty($this->attributes['btn_link']) ) {
				$arr_link = Slzexploore_Core_Util::get_link( $this->attributes['btn_link'] );
				if( !empty( $arr_link['link'] ) ) {
					$btn_url = $arr_link['link'];
				}
			}
			$btn .= sprintf($this->html_format['btn_wlist_format'],
								$this->attributes['btn_wlist'], $add_wlist, $this->post_id, $btn_url
							);
			$custom_right = '.%1$s .tours-layout .content-wrapper > .content .group-btn-tours .right-btn {border-radius: 50px; padding-left: 25px;}';
		}
		if( isset($this->attributes['add_custom_css'])) {
			if( ! ( $custom_left && $custom_right) ) {
				$custom_css = $custom_left . $custom_right;
				if( $custom_css && $index <= 1 ) {
					$custom_css = sprintf( $custom_css, $this->uniq_id );
					apply_filters('slzexploore_core_add_inline_style', $custom_css);
				}
			}
		}
		if( !empty($btn) ) {
			return sprintf($this->html_format['btn_group'], $btn);
		}
	}
	public function get_price() {
		if( $this->post_password ) return '';
		$out = '';
		$format = $this->html_format['price_format'];
		$price = $this->get_schedule_price( $this->post_id, current_time('Y-m-d') );
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
	public function get_schedule_price( $post_id, $compare_date ) {
		if( $this->post_password ) return '';
		$price = get_post_meta( $post_id, 'slzexploore_tour_price_adult', true );
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
				$schedule_price = get_post_meta( $id, 'slzexploore_schedule_price_adult', true );
				if(trim($schedule_price) != ''){
					$price = $schedule_price;
					break;
				}
			}
		}
		return Slzexploore_Core_Format::format_number( $price );
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
	public function get_display_title() {
		$format = $this->html_format['title_format'];
		$title = !empty($this->post_meta['display_title']) ? $this->post_meta['display_title'] : $this->title;
		$output = sprintf( $format, esc_html( $title ), esc_url( $this->permalink ) );
		return $output;
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
			$location  = get_post_meta( $this->post_id, 'slzexploore_tour_location', true );
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
			'btn_color'          => '.%1$s .tours-layout .content-wrapper > .content .group-btn-tours .left-btn, .tours-layout .content-wrapper > .content .group-btn-tours .right-btn{ color: %2$s; }' . "\n",
			'btn_bg_color'       => '.%1$s .tours-layout .content-wrapper > .content .group-btn-tours .left-btn, .tours-layout .content-wrapper > .content .group-btn-tours .right-btn{background-color: %3$s;}' . "\n" .
									'.%1$s .tours-layout .content-wrapper > .content .group-btn-tours .left-btn:hover, .tours-layout .content-wrapper > .content .group-btn-tours .right-btn:hover{color: %3$s;}' . "\n",
			'btn_hover_bg_color' => '.%1$s .tours-layout .content-wrapper > .content .group-btn-tours .left-btn:hover, .tours-layout .content-wrapper > .content .group-btn-tours .right-btn:hover{background-color: %4$s;}',
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
	//------------------- Post Infomations >> -------------------
	private function get_thumb_size() {
		$params = Slzexploore_Core::get_params( 'block-image-size', $this->attributes['layout'] );
		$this->attributes['thumb-size'] = Slzexploore_Core_Util::get_thumb_size( $params, $this->attributes );
	}
	public function set_default_options( &$html_options = array() ) {
		$defaults = array(
			'title_format'     => '<div class="title-wrapper"><a href="%2$s" class="title">%1$s</a><i class="icons flaticon-circle"></i></div>',
			'image_format'     => '<a class="link" href="%2$s">%1$s</a>',
			'meta_format'      => '<ul class="list-info list-inline list-unstyle %2$s">%1$s</ul>',
			'view_format'      => '<li class="view"><a href="%2$s" class="link"><i class="icons fa fa-eye"></i><span class="text number">%1$s</span></a></li>',
			'wishlist_format'  => '<li class="wishlist"><a href="%4$s" class="link %2$s list-wist" data-item="%3$s"><i class="icons fa fa-heart"></i><span class="text number">%1$s</span></a></li>',
			'comment_format'   => '<li><a href="%2$s" class="link"><i class="icons fa fa-comment"></i><span class="text number">%1$s</span></a></li>',
			'share_format'     => '<li class="share"><a href="javascript:void(0);" class="link"><i class="icons fa fa-share-alt"></i></a>%1$s</li>',
			'review_format'    => '<li class="comment"><a href="%3$s" class="link"><i class="icons fa fa-comment"></i><span class="text number">%1$s/%2$s</span></a></li>',
			'excerpt_format'   => '<div class="text">%1$s</div>',
			'price_format'     => '<div class="title"><div class="price">%1$s</div>%2$s</div>',
			'sign_price_format' => '<sup>%1$s</sup>',
			'duration_format'   => '<p class="for-price">%1$s</p>',
			'short_desc_format' => '<div class="description">%1$s</div>',
			'discount_fotmat'   => '<div class="label-sale">%1$s</div>',
			'extend_format'    => '<div class="%1$s">%2$s</div>',
			'btn_book_format'  => '<a href="%2$s" class="left-btn">%1$s</a>',
			'btn_wlist_format' => '<a href="%4$s" class="right-btn %2$s" data-item="%3$s">%1$s</a>',
			'btn_group'        => '<div class="group-btn-tours">%1$s</div>',
			'feature_ribbon_format'=>'<div class="ribbon-sale"><i class="fa fa-bolt"></i><span>%1$s</span></div>'
		);
		$html_options = array_merge( $defaults, $html_options );
		$this->html_format = $html_options;
		return $html_options;
	}
	public function get_feature_ribbon(){
		$out = '';
		$format = $this->html_format['feature_ribbon_format'];
		if( !empty($this->post_meta['is_featured']) && $this->post_meta['featured_text']){
			$out = sprintf($format,$this->post_meta['featured_text']);
		}
		return $out;
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
	// get link search
	public function get_search_link() {
		$post_type = get_post_type_object( 'slzexploore_tour' );
		$slug = $post_type->rewrite['slug'];
		$link = esc_url( home_url() ) .'/'.$slug;
		return esc_url($link);
	}
	// get search atts
	public function get_search_atts( &$atts, &$query_args, $search_data ) {
		// search by keyword
		if( isset($search_data['keyword']) && !empty($search_data['keyword']) ){
			$query_args['s'] = $search_data['keyword'];
		}
		
		// search tour by price
		if( isset($search_data['price']) && !empty($search_data['price']) ){
			$search_price = explode( ',', $search_data['price'] );
			$args = array( 'post_type' => 'slzexploore_tour' );
			$post_in_ids = array();
			$results = Slzexploore_Core_Com::get_post_id2title( $args, array(), false );
			if(!empty($results)){
				foreach($results as $id => $title){
					$sch_price = $this->get_schedule_price( $id, current_time('Y-m-d') );
					if( $sch_price >= $search_price[0] && $sch_price <= $search_price[1] ){
						$post_in_ids[] = $id;
					}
				}
			}
			if( !empty( $post_in_ids ) ){
				$atts['post_id'] = $post_in_ids;
			}
		}
		
		// search tour by location
		if( isset($search_data['location']) && !empty($search_data['location']) ){
			$atts['location_slug'] = $search_data['location'];
		}
		// search tour by category
		if( isset($search_data['category']) && !empty($search_data['category']) ){
			$atts['category_slug'] = $search_data['category'];
		}
		// search tour by review rating
		if( isset($search_data['review_rating']) && !empty($search_data['review_rating']) ){
			$atts['meta_key']['slzexploore_tour_rating'] = $search_data['review_rating'];
		}
		
		// search by date
		if( isset($search_data['start_date']) && !empty( $search_data['start_date'] ) ) {
			$date_weekly = date( 'N', strtotime( $search_data['start_date'] ) );
			$tour_monthly_d = date( 'd', strtotime( $search_data['start_date'] ) );
			$tour_monthly_j = date( 'j', strtotime( $search_data['start_date'] ) );
			$atts['meta_key_compare'][] = array(
						'relation' => 'OR',
						// find tour that have date_type empty
						array(
							'key'     => 'slzexploore_tour_date_type',
							'value'   => ''
						),
						// find tour that tour_frequency = weekly and tour_weekly = date_from
						array(
							'key'     => 'slzexploore_tour_weekly',
							'value'   => $date_weekly,
							'compare' => 'LIKE'
						),
						// find tour that tour_frequency = monthly and tour_monthly = date_from
						array(
							'key'     => 'slzexploore_tour_monthly',
							'value'   => $tour_monthly_d,
							'compare' => 'LIKE'
						),
						array(
							'key'     => 'slzexploore_tour_monthly',
							'value'   => $tour_monthly_j
						),
						// find tour that tour_frequency = other and start_date <= start_date
						array(
							'key'     => 'slzexploore_tour_start_date',
							'value'   =>  $search_data['start_date']
						),
						array(
							array(
								'key'     => 'slzexploore_tour_start_date',
								'value'   => '',
								'compare' => '!='
							),
							array(
								'key'     => 'slzexploore_tour_start_date',
								'value'   =>  $search_data['start_date'],
								'compare' => '<'
							),
							array(
								'key'     => 'slzexploore_tour_end_date',
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
						$term = Slzexploore_Core_Com::get_tax_options_by_slug( $slug, 'slzexploore_tour_location' );
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
				$term = Slzexploore_Core_Com::get_tax_options_by_slug( $data['location'], 'slzexploore_tour_location' );
				$output .= sprintf( $format, 'location', $term->name );
			}
		}
		if( isset( $data['start_date'] ) && !empty( $data['start_date'] ) ){
			$output .= sprintf( $format, 'start_date', $data['start_date']);
		}
		if( isset($data['price']) && !empty($data['price']) ){
			$max_value = Slzexploore::get_option('slz-tour-price-max');
			$default_price = '0,' . $max_value;
			if( $data['price'] != $default_price ){
				$price = str_replace( ',', ' ~ ', $data['price'] );
				$sign = Slzexploore_Core::get_theme_option('slz-currency-sign');
				$output .= sprintf( $format, 'price', $price . $sign );
			}
		}
		if( isset( $data['review_rating'] ) && !empty( $data['review_rating'] ) ){
			if( is_array( $data['review_rating'] ) ){
				$rating = implode( ' or ', $data['review_rating'] );
			}
			else{
				$rating = $data['review_rating'];
			}
			$output .= sprintf( $format, 'review_rating', $rating . esc_html__( ' Star(s)', 'slzexploore-core' ) );
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
						$term = Slzexploore_Core_Com::get_tax_options_by_slug( $slug, 'slzexploore_tour_cat' );
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
				$term = Slzexploore_Core_Com::get_tax_options_by_slug( $data['category'], 'slzexploore_tour_cat' );
				$output .= sprintf( $format, 'category', $term->name);
			}
		}
		if( !empty( $output ) ){
			$clear_all = sprintf('<a href="javascript:void(0);" class="btn-reset-all">%s</a>',
									esc_html__( 'Clear All', 'slzexploore-core' ));
			if( $is_archive ){
				$archive_link = get_post_type_archive_link( 'slzexploore_tour' );
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
}