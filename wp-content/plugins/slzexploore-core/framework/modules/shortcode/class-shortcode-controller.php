<?php
/**
 * Controller Shortcode class.
 *
 * @since 1.0
 */

Slzexploore_Core::load_class('Abstract'); 
class Slzexploore_Core_Shortcode_Controller extends Slzexploore_Core_Abstract {

	//[slz_module cl="shortcode.Shortcode_Controller" mt="shortcode_test" atr1="test" atr2="tesatres"]content[/slz_module]
	public function module( $atts, $content = null ) {
		if( ! empty( $atts['cl'] ) && ! empty( $atts['mt'] ) ) {
			if( Slzexploore_Core::load_class( $atts['cl'] ) ) {
				return Slzexploore_Core::new_object( $atts['cl'] )->{$atts['mt']}( $atts, $content );
			}
		}
	}
	/**
	 * Map Location
	 */
	public function map_location( $atts, $content = null ) {  
		$default = array(
			'post_type'       => 'slzexploore_hotel',
			'map_marker'      => '',
			'zoom'            => '14',
			'cluster_image'   => '',
			'columns'         => '2',
			'offset_post'     => '',
			'height'          => '',
			'limit_post'      => '6',
			'is_container'    => '',
			'extra_class'     => '',
			'sort_hotel'      => '',
			'sort_tour'       => '',
			'sort_car'        => '',
			'sort_cruise'     => ''
		);
		$data = Slzexploore_Core::set_shortcode_defaults( $default, $atts);
		$data['id'] = Slzexploore_Core::make_id();
		return $this->render( 'map_location', $data, true );
	}

	/**
	 * Tab List
	 */
	public function tab( $atts, $content = null ) {  
		extract( shortcode_atts( array(
					'tab_id' => '',
				), $atts ) );

		$output = ''; 
		if(!empty($tab_id)){
			$output .= '<section class="tab-pane fade  " id="tab-'.$tab_id.'">';
			$output .= do_shortcode($content);
			$output .= '</section>';
		} 
		return $output;
	}
	
	/**
	 * Tabs
	 */
	public function tabs( $atts, $content = null ) {
		$default = array( 
			'layout'			=> '01',
			'color_active'		=>'',
			'color_normal'      =>'',
			'tabs_el_class'	    => '',
		);
		$data = Slzexploore_Core::set_shortcode_defaults( $default, $atts);
		$data['id'] = Slzexploore_Core::make_id();
		$atts['id'] = Slzexploore_Core::make_id();
		return $this->render( 'tabs',array('atts'=> $atts,'content' =>$content ), true );
	}

	/**
	 * Accordion
	 */
	public function accordion( $atts ,$content = null) {
		$default = array(
			'extra_class'    => '',
		);
		if( function_exists( 'wpb_js_remove_wpautop' ) ) {
			$content = wpb_js_remove_wpautop( $content, true );
		}
		$data = Slzexploore_Core::set_shortcode_defaults( $default, $atts);
		$data['content'] = $content;
		$data['id'] = Slzexploore_Core::make_id();
		return $this->render( 'accordion', $data, true );
	}

	/**
	 * Icon Box
	 */
	public function icon_box( $atts, $content = null ){
		$default = array(
			'style_icon'     => '6',
			'color'          => '',
			'url'            => '',
			'icon_type'      => '',
			'icon_fw'        => '',
			'icon_ex'        => '',
			'title'          => '',
			'description'    => '',
			'image'          => '',
			'title_color'    => '',
			'description_color' => '',
			'extra_class'    => '',
		);
		$data = Slzexploore_Core::set_shortcode_defaults( $default, $atts);
		$data['id'] = Slzexploore_Core::make_id();
		return $this->render( 'icon_box', $data, true );
	}

	/**
	 * Item List
	 */
	public function item_list( $atts, $content = null ){
		$default = array(
			'style_icon'         => '',
			'color_bg'           => '',
			'color_hover_bg'     => '',
			'color_border'       => '',
			'color_text'         => '',
			'color_line'         => '',
			'color_title'        => '', // style 6
			'color_hover_title'  => '',
			'item_color'         => '',
			'item_color_hv'      => '', // style 6
			'number_show'        => '6',
			'list_item_one'      => '',
			'list_item_two'      => '',
			'list_item_three'    => '',
			'list_item_four'     => '',
			'list_item_six'      => '', // style 6
			'extra_class'        => '',
		);
		$data = Slzexploore_Core::set_shortcode_defaults( $default, $atts);
		$data['id'] = Slzexploore_Core::make_id();
		return $this->render( 'item_list', $data, true );
	}

	/**
	 * Block Title
	 */
	public function block_title( $atts, $content = null ){
		$default = array(
			'style_title'         => '',
			'sub_title'           => '',
			'title'               => '',
			'title_color'         => '',
			'margin_bottom_title' => '70',
			'description'         => '',
			'icon_type'           => '',
			'show_line'           => '',
			'icon_fw'             => '',
			'icon_ex'             => '',
			'extra_class'         => '',
		);
		$data = Slzexploore_Core::set_shortcode_defaults( $default, $atts);
		$data['id'] = Slzexploore_Core::make_id();
		return $this->render( 'block_title', $data, true );
	}
	/**
	 * Button
	 */
	public function button( $atts, $content = null ){
		$default = array(
			'text'                   => '',
			'url'                    => '',
			'color_button'           => '',
			'color_button_hover'     => '',
			'color_text'             => '',
			'color_text_hover'       => '',
			'color_border'           => '',
			'bg_transparent'         => '',
			'bg_hv_transparent'      => '',
			'extra_class'            => '',
		);
		$data = Slzexploore_Core::set_shortcode_defaults( $default, $atts);
		$data['id'] = Slzexploore_Core::make_id();
		return $this->render( 'button', $data, true );
	}

	/**
	 * Blog
	 */
	public function blog( $atts ,$content = null) {
		$default = array(
			'layout'               		=> 'blog',
			'limit_post'           		=> '',
			'offset_post'          		=> '0',
			'sort_by'              		=> '',
			'extra_class'          		=> '',
			'category_list'        		=> '',
			'tag_list'             		=> '',
			'author_list'          		=> '',
			'button_text'          		=> '',
			'button_color'         		=> '',
			'button_text_color'    		=> '',
			'button_hv_color'      		=> '',
			'button_text_hv_color' 		=> '',
			'button_border_color' 		=> '',
			'button_border_hv_color' 	=> '',
			'pagination'           		=> 'yes',
			'max_post'           		=> '',
			'column'               		=> '1',
		);
		$data = Slzexploore_Core::set_shortcode_defaults( $default, $atts);
		return $this->render( 'blog', array( 'atts' => $data, 'content' => $content ), true );
	}

	/**
	 * Recent news
	 */
	public function recent_news( $atts ,$content = null) {
		$default = array(
			'layout'               		=> 'recent_news',
			'title_one'           		=> '',
			'title_two'           		=> '',
			'limit_post'           		=> '',
			'excerpt_length'           	=> '',
			'offset_post'          		=> '0',
			'sort_by'              		=> '',
			'show_tag'              	=> '',
			'show_meta'              	=> 'yes',
			'extra_class'          		=> '',
			'auto_play'          		=> '',
			'auto_speed'          		=> '6000',
			'speed'          			=> '700',
			'category_list'        		=> '',
			'tag_list'             		=> '',
			'author_list'          		=> '',
			'button_text'          		=> '',
			'button_color'         		=> '',
			'button_text_color'    		=> '',
			'button_hv_color'      		=> '',
			'button_text_hv_color' 		=> '',
			'button_border_color' 		=> '',
			'button_border_hv_color' 	=> '',
		);
		$data = Slzexploore_Core::set_shortcode_defaults( $default, $atts);
		return $this->render( 'recent_news', array( 'atts' => $data, 'content' => $content ), true );
	}

	/**
	 * Gallery
	 */
	public function gallery( $atts ,$content = null) {
		$default = array(
			'layout'         => 'gallery',
			'style'          => 'masonry_grid',
			'layout_extra'   => '',
			'title'          => '',
			'column'         => '4',
			'images'         => '',
			'images_all'     => '',
			'show_container' => '',
			'arrows'         => 'true',
			'extra_class'    => '',
			'image_size'     => 'medium',
		);
		$data = Slzexploore_Core::set_shortcode_defaults( $default, $atts);
		if( empty( $data['info'] ) ) {
			list( $data['images_parse'] ) = Slzexploore_Core_Util::get_list_vc_param_group( $data, 'images_all', 'info' );
		}
		return $this->render( 'gallery', array( 'atts' => $data, 'content' => $content ), true );
	}

	/**
	 * Contact
	 */
	public function contact( $atts ,$content = null) {
		$default = array(
			'style'          			=> '1',
			'insert_container'          => '',
			'title'               		=> '',
			'description'               => '',
			'contact_form'              => '',
			'image'               		=> '',
			'background'               	=> '',
			'address'               	=> '',
			'address_icon'              => 'fa fa-map-marker',
			'phone'               		=> '',
			'phone_icon'               	=> 'fa fa-phone',
			'email'               		=> '',
			'email_icon'               	=> 'fa fa-envelope',
			'extra_class'          		=> '',
		);
		$data_arr =  Slzexploore_Core::set_shortcode_defaults( $default, $atts);
		$data_arr['id'] = Slzexploore_Core::make_id();
		$data_arr['content'] = $content;
		return $this->render( 'contact', $data_arr, true );
	}

	/**
	 * Partner
	 */
	public function partner( $atts, $content = null ){
		$default = array(
			'style'                   => '',
			'row'                     => '',
			'title'                   => '',
			'method'                  => 'cat',
			'category'                => '',
			'list_partner'            => '',
			'extra_class'             => '',
		);
		$data = Slzexploore_Core::set_shortcode_defaults( $default, $atts);
		if(isset($atts['list_partner'])){
			$list_partner = (array) vc_param_group_parse_atts( $atts['list_partner'] );
			$data['list_partner'] = $list_partner;
		}
		return $this->render( 'partner', array('atts' => $data), true );
	}
	/**
	 * Team Carousel
	 */
	public function team_carousel( $atts, $content = null ){
		$default = array(
			'layout'        		=> 'team_carousel',
			'style'					=> '1',
			'title'         		=> '',
			'offset_post'   		=> '',
			'limit_post'    		=> '5',
			'post__not_in'    		=> '',
			'sort_by'       		=> '',
			'extra_class'   		=> '',
			'category_list' 		=> '',
			'category_slug' 		=> '',
			'color_title_group' 	=> '',
			'color_title_group_line'=> '',
			'color_box_bg' 			=> '',
			'color_box_line' 		=> '',
			'color_title' 			=> '',
			'color_title_hv' 		=> '',
			'color_postion' 		=> '',
			'color_icon' 			=> '',
			'color_icon_hv' 		=> '',
		);
		$data = Slzexploore_Core::set_shortcode_defaults( $default, $atts);
		if( empty( $data['category_slug'] ) ) {
			list( $data['category_list_parse'], $data['category_slug'] ) = Slzexploore_Core_Util::get_list_vc_param_group( $data, 'category_list', 'category_slug' );
		}
		return $this->render( 'team_carousel', array('atts' => $data), true );
	}
	/**
	 * Team Single
	 */
	public function team_single( $atts, $content = null ){
		$default = array(
			'layout'                  => 'team_single',
			'style'                   => '',
			'data'                    => '',
			'color_hover'             => '',
			'extra_class'             => '',
		);
		$data = Slzexploore_Core::set_shortcode_defaults( $default, $atts);
		$data['id'] = Slzexploore_Core::make_id();
		return $this->render( 'team_single', array('atts' => $data), true );
	}
	/**
	 * Team List
	 */
	public function team_list( $atts, $content = null ){
		$default = array(
			'layout'                  => 'team_single',
			'column'                  => '1',
			'offset_post'             => '',
			'limit_post'              => '3',
			'extra_class'             => '',
			'category_list'           => '',
			'category_slug'           => '',
		);
		$data = Slzexploore_Core::set_shortcode_defaults( $default, $atts);
		if( empty( $data['category_slug'] ) ) {
			list( $data['category_list_parse'], $data['category_slug'] ) = Slzexploore_Core_Util::get_list_vc_param_group( $data, 'category_list', 'category_slug' );
		}
		$data['id'] = Slzexploore_Core::make_id();
		return $this->render( 'team_list', array('atts' => $data), true );
	}
	/**
	 * Post Carousel
	 */
	public function post_carousel( $atts, $content = null ){
		$default = array(
			'layout'                  => 'carousel',
			'title'                   => '',
			'posttype'                => 'slzexploore_hotel',
			'category_accommodation'  => '',
			'category_tour'           => '',
			'category_car'            => '',
			'category_cruise'         => '',
			'category_post'           => '',
			'category_slug'           => '',
			'offset_post'             => '',
			'limit_post'              => '5',
			'post__not_in'            => '',
			'extra_class'             => '',
			'title_length'            => '',
		);
		$data = Slzexploore_Core::set_shortcode_defaults( $default, $atts);
		$query_args = array();
		if( !empty( $data['post__not_in'] ) ) {
			$query_args['post__not_in'] = array( $data['post__not_in'] );
		}
		if( empty( $data['category_slug'] ) ) {
			switch($data['posttype']) {
				case 'post':
					list( $data['category_list_parse'], $data['category_slug'] ) = Slzexploore_Core_Util::get_list_vc_param_group( $data, 'category_post', 'category_slug' );
					break;
				case 'slzexploore_tour':
					list( $data['category_list_parse'], $data['category_slug'] ) = Slzexploore_Core_Util::get_list_vc_param_group( $data, 'category_tour', 'category_slug' );
					break;
				case 'slzexploore_car':
					list( $data['category_list_parse'], $data['category_slug'] ) = Slzexploore_Core_Util::get_list_vc_param_group( $data, 'category_car', 'category_slug' );
					break;
				case 'slzexploore_cruise':
					list( $data['category_list_parse'], $data['category_slug'] ) = Slzexploore_Core_Util::get_list_vc_param_group( $data, 'category_cruise', 'category_slug' );
					break;
				default:
					list( $data['category_list_parse'], $data['category_slug'] ) = Slzexploore_Core_Util::get_list_vc_param_group( $data, 'category_accommodation', 'category_slug' );
			}
		}
		if( !empty( $data['category_slug']) && !is_array( $data['category_slug'] ) ) {
			$data['category_slug'] = explode( ',', $data['category_slug'] );
		}
		return $this->render( 'post_carousel', array('atts' => $data, 'query_args' => $query_args), true );
	}
	/**
	 * Discount Box
	 */
	public function discount_box( $atts, $content = null ){
		$default = array(
			'style'                     => '',
			'left_text'                 => '',
			'left_text2'                => '',
			'left_text3'                => '',
			'discount_percent'          => '',
			'dicount_text1'             => '',
			'dicount_text2'             => '',
			'image'                     => '',
			'height'                    => '500',
			'usebutton'                 => '',
			'buttontext1'               => '',
			'url1'                      => '',
			'btn_text_1'                => '',
			'btn_text_hover_1'          => '',
			'btn_bg_1'                  => '',
			'btn_bg_hover_1'            => '',
			'color_border1'             => '',
			'buttontext2'               => '',
			'url2'                      => '',
			'btn_text_2'                => '',
			'btn_text_hover_2'          => '',
			'btn_bg_2'                  => '',
			'btn_bg_hover_2'            => '',
			'bg_transparent1'           => '',
			'bg_hv_transparent1'        => '',
			'bg_transparent2'           => '',
			'bg_hv_transparent2'        => '',
			'color_border2'             => '',
			'extra_class'               => '',
		);
		$data = Slzexploore_Core::set_shortcode_defaults( $default, $atts);
		$data['id'] = Slzexploore_Core::make_id();
		if ( !empty($data['url1']) ) {
			$data['url1'] = Slzexploore_Core_Util::get_link($data['url1']);
		}
		if ( !empty($data['url2']) ) {
			$data['url2'] = Slzexploore_Core_Util::get_link($data['url2']);
		}
		if( function_exists( 'wpb_js_remove_wpautop' ) ) {
			$data['content'] = wpb_js_remove_wpautop( $content, true );
		}
		return $this->render( 'discount_box', $data, true );
	}
	/**
	 * Accommodation Search
	 */
	public function accommodation_search( $atts, $content = null ){
		$default = array(
			'extra_class'        => '',
			'search_page'        => '',
			'expand'             => '',
		);
		$data = Slzexploore_Core::set_shortcode_defaults( $default, $atts);
		// Accommodation location
		$location_empty   = array( 'empty' => esc_html__( 'Choose Location', 'slzexploore-core' ) );
		$location_args    = array( 'hide_empty' => false, 'orderby' => 'term_id' );
		$data['location'] = Slzexploore_Core_Com::get_tax_options2name( 'slzexploore_hotel_location',
																$location_empty, $location_args );
		return $this->render( 'accommodation_search', $data, true );
	}
	/**
	 * Search SC
	 */
	public function search( $atts, $content = null ){
		$default = array(
			'layout'             => '01',
			'extra_class'        => ''
		);
		$data = Slzexploore_Core::set_shortcode_defaults( $default, $atts);
		// search icon
		$hotel_icon  = Slzexploore::get_option('slz-search-hotel-icon');
		$tour_icon   = Slzexploore::get_option('slz-search-tour-icon');
		$car_icon    = Slzexploore::get_option('slz-search-car-icon');
		$cruise_icon = Slzexploore::get_option('slz-search-cruises-icon');
		$data['hotel_icon']  = !empty( $hotel_icon ) ? $hotel_icon : 'flaticon-three';
		$data['tour_icon']   = !empty( $tour_icon ) ? $tour_icon : 'flaticon-people';
		$data['car_icon']    = !empty( $car_icon ) ? $car_icon : 'flaticon-transport-7';
		$data['cruise_icon'] = !empty( $cruise_icon ) ? $cruise_icon : 'flaticon-transport-4';
		// tab enable
		$arr_type = Slzexploore::get_option('slz-search-general', 'enabled');
		if( count( $arr_type ) == 1 ) {
			return '';
		}
		$data['tab_enabled'] = $arr_type;
		$data['id'] = Slzexploore_Core::make_id();
		return $this->render( 'search-layout-' . $data['layout'], $data, true );
	}
	/**
	 * Tour carousel
	 */
	public function tour_carousel( $atts, $content = null ){
		$default = array(
			'layout'             => 'tour_carousel',
			'columns'            => '3',
			'offset_post'        => '',
			'limit_post'         => '8',
			'extra_class'        => '',
			'location_list'      => '',
			'location_slug'      => '',
			'author_list'        => '',
			'author'             => '',
			'category_list'      => '',
			'category_slug'      => '',
			'featured_filter'    => '',
			'sort_by'            => '',
			'btn_wlist'          => '',
			'btn_wlist_action'   => '',
			'btn_link'           => '',
			'btn_book'           => '',
			'btn_color'          => '',
			'btn_bg_color'       => '',
			'btn_hover_bg_color' => '',
		);
		$data = Slzexploore_Core::set_shortcode_defaults( $default, $atts);
		$data['content'] = $content;
		if( empty( $data['location_slug'] ) ) {
			list( $data['location_list_parse'], $data['location_slug'] ) = Slzexploore_Core_Util::get_list_vc_param_group( $data, 'location_list', 'location_slug' );
		}
		if( empty( $data['author'] ) ) {
			list( $data['author_list_parse'], $data['author'] ) = Slzexploore_Core_Util::get_list_vc_param_group( $data, 'author_list', 'author' );
		}
		if( empty( $data['category_slug'] ) ) {
			list( $data['category_list_parse'], $data['category_slug'] ) = Slzexploore_Core_Util::get_list_vc_param_group( $data, 'category_list', 'category_slug' );
		}
		return $this->render( 'tour_carousel', array('atts' => $data), true );
	}
	
	/**
	 * Tour Search
	 */
	public function tour_search( $atts, $content = null ){
		$default = array(
			'extra_class'        => '',
			'search_page'        => '',
			'expand'             => ''
		);
		$data = Slzexploore_Core::set_shortcode_defaults( $default, $atts);
		// Tour location
		$location_empty   = array( 'empty' => esc_html__( 'Choose Location', 'slzexploore-core' ) );
		$location_args    = array( 'hide_empty' => false, 'orderby' => 'term_id' );
		$data['location'] = Slzexploore_Core_Com::get_tax_options2name( 'slzexploore_tour_location', $location_empty, $location_args );
		return $this->render( 'tour_search', $data, true );
	}
	public function get_layout_search( $type = '', $style = '01' ){
		return $this->render( $type . '_layout_search', array( 'style' => $style ) );
	}
	/**
	 * Accommodation Grid
	 */
	public function accommodation_grid( $atts, $content = null ){
		$default = array(
			'layout'          => 'accommodation',
			'columns'         => '2',
			'offset_post'     => '',
			'limit_post'      => '4',
			'pagination'      => 'yes',
			'bg_color'        => '',
			'extra_class'     => '',
			'featured_filter' => '',
			'location_list'   => '',
			'location_slug'   => '',
			'author_list'     => '',
			'author'          => '',
			'category_list'   => '',
			'category_slug'   => '',
			'facility_list'   => '',
			'facility_slug'   => '',
			'sort_by'         => '',
			'btn_book'        => '',
			'btn_link'        => '',
			'btn_book_color'          => '',
			'btn_book_bg_color'       => '',
			'btn_book_hover_bg_color' => '',
			'allow_address_empty'     => ''
		);
		$data = Slzexploore_Core::set_shortcode_defaults( $default, $atts);
		$data['content'] = $content;
		if( empty( $data['location_slug'] ) ) {
			list( $data['location_list_parse'], $data['location_slug'] ) = Slzexploore_Core_Util::get_list_vc_param_group( $data, 'location_list', 'location_slug' );
		}
		if( empty( $data['author'] ) ) {
			list( $data['author_list_parse'], $data['author'] ) = Slzexploore_Core_Util::get_list_vc_param_group( $data, 'author_list', 'author' );
		}
		if( empty( $data['category_slug'] ) ) {
			list( $data['category_list_parse'], $data['category_slug'] ) = Slzexploore_Core_Util::get_list_vc_param_group( $data, 'category_list', 'category_slug' );
		}
		if( empty( $data['facility_slug'] ) ) {
			list( $data['facility_list_parse'], $data['facility_slug'] ) = Slzexploore_Core_Util::get_list_vc_param_group( $data, 'facility_list', 'facility_slug' );
		}
		return $this->render( 'accommodation_grid', array('atts' => $data), true );
	}
	/**
	 * Tour Grid
	 */
	public function tour_grid( $atts, $content = null ){
		$default = array(
			'layout'          => 'tour',
			'columns'         => '2',
			'offset_post'     => '',
			'limit_post'      => '4',
			'pagination'      => 'yes',
			'extra_class'     => '',
			'featured_filter' => '',
			'location_list'   => '',
			'location_slug'   => '',
			'author_list'     => '',
			'author'          => '',
			'category_list'   => '',
			'category_slug'   => '',
			'tag_list'        => '',
			'tag_slug'        => '',
			'sort_by'         => '',
			'btn_book'        => '',
			'btn_wlist'       => '',
			'btn_wlist_action'=> '',
			'btn_link'        => '',
			'btn_book_color'  => '',
			'btn_book_bg_color'       => '',
			'btn_book_hover_bg_color' => '',
			'allow_address_empty'     => ''
		);
		$data = Slzexploore_Core::set_shortcode_defaults( $default, $atts);
		$data['content'] = $content;
		if( empty( $data['location_slug'] ) ) {
			list( $data['location_list_parse'], $data['location_slug'] ) = Slzexploore_Core_Util::get_list_vc_param_group( $data, 'location_list', 'location_slug' );
		}
		if( empty( $data['author'] ) ) {
			list( $data['author_list_parse'], $data['author'] ) = Slzexploore_Core_Util::get_list_vc_param_group( $data, 'author_list', 'author' );
		}
		if( empty( $data['category_slug'] ) ) {
			list( $data['category_list_parse'], $data['category_slug'] ) = Slzexploore_Core_Util::get_list_vc_param_group( $data, 'category_list', 'category_slug' );
		}
		if( empty( $data['tag_slug'] ) ) {
			list( $data['tag_list_parse'], $data['tag_slug'] ) = Slzexploore_Core_Util::get_list_vc_param_group( $data, 'tag_list', 'tag_slug' );
		}
		return $this->render( 'tour_grid', array('atts' => $data), true );
	}
	/**
	 * Tour schedule
	 */
	public function tour_schedule( $atts, $content = null ){
		$default = array(
			'block_title'        => '',
			'tour_schedule_list' => '',
			'extra_class'        => '',
			'show_dots'          => '1',
			'show_arrows'        => '1',
			'slide_autoplay'     => '1',
			'slide_infinite'     => '1',
			'slide_speed'        => '300',
			'slide_animation'    => '0',
		);
		$data = Slzexploore_Core::set_shortcode_defaults( $default, $atts);
		$data['content'] = $content;
		$data['tour_schedule'] = Slzexploore_Core_Util::get_list_vc_param_group_arr($data, 'tour_schedule_list');
		return $this->render( 'tour_schedule', array( 'atts' => $data ), true );
	}
	
	/**
	 * Room Types
	 */
	public function room_type( $atts, $content = null ){
		$default = array(
			'title'                   => '',
			'filter_by'               => '',
			'room_list'               => '',
			'room_id'                 => '',
			'category_list'           => '',
			'category_slug'           => '',
			'btn_book'                => '',
			'btn_book_color'          => '',
			'btn_book_bg_color'       => '',
			'btn_book_hover_bg_color' => '',
			'extra_class'             => '',
		);
		$data = Slzexploore_Core::set_shortcode_defaults( $default, $atts);
		$data['content'] = $content;
		if( empty( $data['room_id'] ) ) {
			list( $data['room_list_parse'], $data['room_id'] ) = Slzexploore_Core_Util::get_list_vc_param_group( $data, 'room_list', 'room_id' );
		}
		else if( !is_array( $data['room_id'] ) ) {
			$data['room_id'] = explode( ',', $data['room_id'] );
		}
		if( empty( $data['category_slug'] ) ) {
			list( $data['category_list_parse'], $data['category_slug'] ) = Slzexploore_Core_Util::get_list_vc_param_group( $data, 'category_list', 'category_slug' );
		}
		return $this->render( 'room_type', array('atts' => $data ), true );
	}

	/**
	 * Testimonial
	 */
	public function testimonial( $atts, $content = null ) {
		$default = array(
			'layout'              => 'testimonial',
			'title_top'           => '',
			'icon_type'           => '',
			'icon_fw'             => '',
			'icon_ex'             => '',
			'title_main'          => '',
			'offset_post'         => '',
			'limit_post'          => '',
			'image'               => '',
			'extra_class'         => '',
		);
		$data = Slzexploore_Core::set_shortcode_defaults( $default, $atts);
		$data['id'] = Slzexploore_Core::make_id();
		return $this->render( 'testimonial', array( 'atts' => $data ), true );
	}
	/**
	 * Banner
	 */
	public function banner( $atts, $content = null ) {
		$default = array(
			'style'					=> '1',
			'is_container'			=> '', // style 3
			'image_bg'				=> '',
			'extra_class'			=> '',
			'image_video'			=> '', // style 1,2
			'video_type'			=> '', // style 1,2
			'id_youtube'			=> '', // style 1,2
			'id_vimeo'				=> '', // style 1,2
			'button_txt'			=> '', // style 1,3
			'url_btn'				=> '', // style 1,3
			'bg_transparent'		=> '', // style 1,3
			'color_button'			=> '', // style 1,3
			'bg_hv_transparent'		=> '', // style 1,3
			'color_button_hover'	=> '', // style 1,3
			'color_text'			=> '', // style 1,3
			'color_text_hover'		=> '', // style 1,3
			'color_border'			=> '', // style 1,3
			'color_border_hover'	=> '', // style 1,3
		);
		$data = Slzexploore_Core::set_shortcode_defaults( $default, $atts);
		$data['id'] = Slzexploore_Core::make_id();
		if ( !empty($data['url_btn']) ) {
			$data['url_btn'] = Slzexploore_Core_Util::get_link($data['url_btn']);
		}
		if( function_exists( 'wpb_js_remove_wpautop' ) ) {
			$data['content'] = wpb_js_remove_wpautop( $content, true );
		}
		return $this->render( 'banner', $data , true );
	}
	/**
	 * Tour Category
	 */
	public function tour_category( $atts, $content = null ) {
		$default = array(
			'category_list'           => '',
			'category_slug'           => '',
			'extra_class'             => '',
		);
		$data = Slzexploore_Core::set_shortcode_defaults( $default, $atts);
		$data['content'] = $content;
		if( empty( $data['category_slug'] ) ) {
			list( $data['category_list_parse'], $data['category_slug'] ) = Slzexploore_Core_Util::get_list_vc_param_group( $data, 'category_list', 'category_slug' );
		}
		else if( !is_array( $data['category_slug'] ) ) {
			$data['category_slug'] = explode( ',', $data['category_slug'] );
		}
		$data['block_id'] = 'block_' . Slzexploore_Core::make_id();
		return $this->render( 'tour_category', array('atts' => $data ), true );
	}
	/**
	 * FAQ Request form
	 */
	public function faq_request( $atts ,$content = null) {
		$default = array(
			'title_box'					=> '',
			'contact_form'				=> '',
			'background_box'			=> '',
			'color_error'				=> '',
			'color_title_box'			=> '',
			'background_input'			=> '',
			'color_input'				=> '',
			'color_text_button'			=> '',
			'color_text_button_hv'		=> '',
			'background_button'			=> '',
			'background_button_hv'		=> '',
			'extra_class'				=> '',
		);
		$data_arr =  Slzexploore_Core::set_shortcode_defaults( $default, $atts);
		$data_arr['id'] = Slzexploore_Core::make_id();
		$data_arr['content'] = $content;
		return $this->render( 'faq_request', $data_arr, true );
	}
	/**
	 * FAQS
	 */
	public function faqs( $atts, $content = null ) {
		$default = array(
			'limit_post'            => '',
			'offset_post'         	=> '',
			'sort_by'             	=> '',
			'list_faq'      		=> '',
			'extra_class' 			=> '',
			'method'				=> 'cat',
			'list_cat'				=> '',
		);
		$data = Slzexploore_Core::set_shortcode_defaults( $default, $atts);
	
		if( empty( $data['category_slug'] ) ) {
			list( $data['category_list_parse'], $data['category_slug'] ) = Slzexploore_Core_Util::get_list_vc_param_group( $data, 'list_cat', 'faq_category' );
		}
	
		if( $data['method'] == 'faq' && !empty($data['list_faq'])){
			$list_faq = (array) vc_param_group_parse_atts( $data['list_faq'] );
			$data['list_faq'] = $list_faq;
		}
		return $this->render( 'faqs', array( 'atts' => $data ), true );
	}
	/**
	 * Toggle Box
	 */
	public function toggle_box( $atts, $content = null ) {
		$default = array(
			'extra_class'         => '',
			'toggle_content'      => '',
		);
		$data = Slzexploore_Core::set_shortcode_defaults( $default, $atts);
		if( !empty( $data['toggle_content'] ) ){
			$list_content = vc_param_group_parse_atts( $data['toggle_content'] );
			$data['list_content'] = $list_content;
		}
		$data['block_id'] = Slzexploore_Core::make_id();
		return $this->render( 'toggle_box', $data, true );
	}
	/**
	 * Cruise Grid
	 */
	public function cruise_grid( $atts, $content = null ) {
		$default = array(
			'layout'          => 'cruise',
			'columns'         => '2',
			'offset_post'     => '',
			'limit_post'      => '6',
			'pagination'      => 'yes',
			'extra_class'     => '',
			'featured_filter' => '',
			'location_list'   => '',
			'location_slug'   => '',
			'author_list'     => '',
			'author'          => '',
			'category_list'   => '',
			'category_slug'   => '',
			'sort_by'         => '',
			'btn_book'        => '',
			'btn_custom'       => '',
			'btn_link'        => '',
			'btn_book_color'  => '',
			'btn_book_bg_color'       => '',
			'btn_book_hover_bg_color' => '',
			'allow_address_empty'     => ''
		);
		$data = Slzexploore_Core::set_shortcode_defaults( $default, $atts);
		$data['content'] = $content;
		if( empty( $data['location_slug'] ) ) {
			list( $data['location_list_parse'], $data['location_slug'] ) = Slzexploore_Core_Util::get_list_vc_param_group( $data, 'location_list', 'location_slug' );
		}
		if( empty( $data['author'] ) ) {
			list( $data['author_list_parse'], $data['author'] ) = Slzexploore_Core_Util::get_list_vc_param_group( $data, 'author_list', 'author' );
		}
		if( empty( $data['category_slug'] ) ) {
			list( $data['category_list_parse'], $data['category_slug'] ) = Slzexploore_Core_Util::get_list_vc_param_group( $data, 'category_list', 'category_slug' );
		}
		return $this->render( 'cruise_grid', array('atts' => $data), true );
	}
	/**
	 * Car Grid
	 */
	public function car_grid( $atts, $content = null ) {
		$default = array(
			'layout'          => 'car',
			'style'           => 'list',
			'columns'         => '2',
			'offset_post'     => '',
			'limit_post'      => '6',
			'pagination'      => 'yes',
			'extra_class'     => '',
			'featured_filter' => '',
			'location_list'   => '',
			'location_slug'   => '',
			'author_list'     => '',
			'author'          => '',
			'category_list'   => '',
			'category_slug'   => '',
			'sort_by'         => '',
			'btn_book'        => '',
			'btn_custom'       => '',
			'btn_link'        => '',
			'btn_book_color'  => '',
			'btn_book_bg_color'       => '',
			'btn_book_hover_bg_color' => '',
			'allow_address_empty'     => ''
		);
		$data = Slzexploore_Core::set_shortcode_defaults( $default, $atts);
		$data['content'] = $content;
		if( empty( $data['location_slug'] ) ) {
			list( $data['location_list_parse'], $data['location_slug'] ) = Slzexploore_Core_Util::get_list_vc_param_group( $data, 'location_list', 'location_slug' );
		}
		if( empty( $data['author'] ) ) {
			list( $data['author_list_parse'], $data['author'] ) = Slzexploore_Core_Util::get_list_vc_param_group( $data, 'author_list', 'author' );
		}
		if( empty( $data['category_slug'] ) ) {
			list( $data['category_list_parse'], $data['category_slug'] ) = Slzexploore_Core_Util::get_list_vc_param_group( $data, 'category_list', 'category_slug' );
		}
		return $this->render( 'car_grid', array('atts' => $data), true );
	}
	/**
	 * Car Search
	 */
	public function car_search( $atts, $content = null ){
		$default = array(
			'extra_class'        => '',
			'search_page'        => '',
			'expand'             => ''
		);
		$data = Slzexploore_Core::set_shortcode_defaults( $default, $atts);
		// Car location
		$location_empty   = array( 'empty' => esc_html__( 'Choose Location', 'slzexploore-core' ) );
		$location_args    = array( 'hide_empty' => false, 'orderby' => 'term_id' );
		$data['location'] = Slzexploore_Core_Com::get_tax_options2name( 'slzexploore_car_location', $location_empty, $location_args );
		return $this->render( 'car_search', $data, true );
	}
	/**
	 * Cruise Search
	 */
	public function cruise_search( $atts, $content = null ){
		$default = array(
			'extra_class'        => '',
			'search_page'        => '',
			'expand'             => ''
		);
		$data = Slzexploore_Core::set_shortcode_defaults( $default, $atts);
		// Car location
		$location_empty   = array( 'empty' => esc_html__( 'Choose Location', 'slzexploore-core' ) );
		$location_args    = array( 'hide_empty' => false, 'orderby' => 'term_id' );
		$data['location'] = Slzexploore_Core_Com::get_tax_options2name( 'slzexploore_cruise_location', $location_empty, $location_args );
		return $this->render( 'cruise_search', $data, true );
	}
}