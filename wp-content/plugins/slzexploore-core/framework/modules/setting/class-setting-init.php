<?php
/**
 * Setting_Init class.
 * 
 * @since 1.0
 */

class Slzexploore_Core_Setting_Init {
	/**
	 * Regist scripts - admin
	 * 
	 */
	public function enqueue(){
		$uri = SLZEXPLOORE_CORE_ASSET_URI;
		$protocol = is_ssl() ? 'https' : 'http';
		// css
		wp_enqueue_style( 'slzexploore-core-admin',        $uri . '/css/slzexploore-core-admin.css', false, SLZEXPLOORE_CORE_VERSION, 'all' );
		wp_enqueue_style( 'font-awesome.min',              $uri . '/libs/font-awesome/css/font-awesome.min.css', false, false, 'all' );
		// js
		wp_enqueue_media();
		wp_enqueue_script( 'slzexploore-core-common',    $uri . '/js/slzexploore-core-common.js', array('jquery'), SLZEXPLOORE_CORE_VERSION, false );
		wp_enqueue_script( 'slzexploore-core-admin',     $uri . '/js/slzexploore-core-admin.js', array('jquery'), SLZEXPLOORE_CORE_VERSION, false );
		wp_enqueue_script( 'slzexploore-core-metabox',   $uri . '/js/slzexploore-core-metabox.js', array('jquery'), SLZEXPLOORE_CORE_VERSION, false );
		wp_enqueue_script( 'slzexploore-core-datepicker',$uri . '/js/slzexploore-core-datetimepicker.js', array('jquery'), SLZEXPLOORE_CORE_VERSION, false );
		wp_enqueue_script( 'slzexploore-core-admin-map', $uri . '/js/slzexploore-core-admin-map.js', array('jquery'), SLZEXPLOORE_CORE_VERSION, false );

		wp_enqueue_script( 'slzexploore-core-image',     $uri . '/js/slzexploore-core-image.js', array('jquery'), SLZEXPLOORE_CORE_VERSION, false );
		wp_localize_script( 'slzexploore-core-image', 'slzexploore_core_meta_image',
				array(
					'title'  => esc_html__( 'Choose or Upload an Image', 'slzexploore-core' ),
					'button' => esc_html__( 'Use this image', 'slzexploore-core' ),
				)
		);
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'slzexploore-core-metacolor', $uri . '/js/slzexploore-core-metacolor.js', array( 'wp-color-picker' ) );

		wp_enqueue_script( 'jquery.datetimepickermin', $uri . '/libs/datetimepicker/jquery.datetimepicker.min.js', array(), SLZEXPLOORE_CORE_VERSION, true );
		wp_enqueue_script( 'jquery.tooltipster.min',   $uri . '/libs/tooltipster/jquery.tooltipster.min.js', array(), SLZEXPLOORE_CORE_VERSION, true );
		//for map of accommodation
		$keyMapAPI = '';
		$keyMapAPIOption = Slzexploore_Core::get_theme_option('slz-map-key-api');
		if ( !empty($keyMapAPIOption) ) {
			$keyMapAPI = 'key='.trim($keyMapAPIOption);
		}
		wp_enqueue_script( 'jquery.googleapis',        $protocol . '://maps.googleapis.com/maps/api/js?'.$keyMapAPI.'&amp;libraries=drawing,geometry,places' );
		wp_enqueue_script( 'jquery.gmap3',             $uri . '/libs/google-map/gmap3.js', array(), SLZEXPLOORE_CORE_VERSION, true );
		wp_enqueue_script( 'jquery.autocomplete',      $uri . '/libs/google-map/jquery.geocomplete.js', array(), SLZEXPLOORE_CORE_VERSION, true );
		
		//-----------------enqueue script to run ajax-------------------------- 
		wp_enqueue_script( 'slzexploore-core-admin-form', $uri . '/js/slzexploore-core-form.js', array('jquery'), SLZEXPLOORE_CORE_VERSION, true );
		wp_localize_script(
				'slzexploore-core-admin-form',
				'ajaxurl',
				admin_url( 'admin-ajax.php' )
		);
		// css for shortcode 
		wp_enqueue_style( 'jquery.datetimepicker', $uri . '/libs/datetimepicker/jquery.datetimepicker.css', array(), SLZEXPLOORE_CORE_VERSION );
	}

	/**
	 * Scripts & Css - frondend
	 */
	public function dev_enqueue_scripts(){
		$uri = SLZEXPLOORE_CORE_ASSET_URI;
		$protocol = is_ssl() ? 'https' : 'http';
		// css
		wp_enqueue_style( 'jquery.fancybox',          $uri . '/libs/fancybox/css/jquery.fancybox.css');
		wp_enqueue_style( 'jquery.fancybox-buttons',  $uri . '/libs/fancybox/css/jquery.fancybox-buttons.css');
		wp_enqueue_style( 'jquery.fancybox-thumbs',   $uri . '/libs/fancybox/css/jquery.fancybox-thumbs.css');
		wp_enqueue_style( 'jquery.directional-hover', $uri . '/libs/mouse-direction-aware/jquery.directional-hover.css');
		wp_enqueue_style( 'slick',                    $uri . '/libs/slick-slider/slick.css');
		wp_enqueue_style( 'slick-theme',              $uri . '/libs/slick-slider/slick-theme.css');
		wp_enqueue_style( 'jquery.selectbox',         $uri . '/libs/selectbox/css/jquery.selectbox.css' );
		wp_enqueue_style( 'jquery.select2',           $uri . '/libs/select2/css/select2.min.css' );
		wp_enqueue_style( 'please-wait',              $uri . '/libs/please-wait/please-wait.css');
		wp_enqueue_style( 'jquery.nstSlider.min',     $uri . '/libs/nst-slider/css/jquery.nstSlider.min.css');

		// js
		// datetime picker
		wp_enqueue_script( 'bootstrap-datepicker.min',    $uri . '/libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js', array(), false, true );
		wp_enqueue_script( 'jquery.mousewheel-pack',      $uri . '/libs/fancybox/js/jquery.mousewheel-pack.js', array(), false, true );
		wp_enqueue_script( 'jquery.fancybox',             $uri . '/libs/fancybox/js/jquery.fancybox.js', array(), false, true );
		wp_enqueue_script( 'jquery.fancybox-buttons',     $uri . '/libs/fancybox/js/jquery.fancybox-buttons.js', array(), false, true );
		wp_enqueue_script( 'jquery.fancybox-thumbs',      $uri . '/libs/fancybox/js/jquery.fancybox-thumbs.js', array(), false, true );
		//please-wait
		wp_enqueue_script( 'please-wait.min',             $uri . '/libs/please-wait/please-wait.min.js', array(), false, true );
		//wow
		wp_enqueue_script( 'wow.min',                     $uri . '/libs/wow-js/wow.min.js', array(), false, true );
		//directional-hover
		wp_enqueue_script( 'jquery.directional-hover',    $uri . '/libs/mouse-direction-aware/jquery.directional-hover.js', array(), false, true );
		wp_enqueue_script( 'isotope.min',                 $uri . '/libs/isotope/isotope.min.js', array(), false, true );
		// selectbox
		wp_enqueue_script( 'jquery.selectbox-0.2',        $uri . '/libs/selectbox/js/jquery.selectbox-0.2.js', array(), false, true );
		// select2
		wp_enqueue_script( 'jquery.select2',              $uri . '/libs/select2/js/select2.min.js', array(), false, true );
		wp_enqueue_script( 'plus-minus-input',            $uri . '/libs/plus-minus-input/plus-minus-input.js', array(), false, true );
		wp_enqueue_script( 'TweenMax.min',                $uri . '/libs/parallax/TweenMax.min.js', array(), false, true );
		wp_enqueue_script( 'jquery-parallax',             $uri . '/libs/parallax/jquery-parallax.js', array(), false, true );
		wp_enqueue_script( 'slick.min',                   $uri . '/libs/slick-slider/slick.min.js', array(), false, true );
		wp_enqueue_script( 'jquery.nstSlider.min',        $uri . '/libs/nst-slider/js/jquery.nstSlider.min.js', array(), false, true );
		
		$keyMapAPI = '';
		$keyMapAPIOption = Slzexploore_Core::get_theme_option('slz-map-key-api');
		if ( !empty($keyMapAPIOption) ) {
			$keyMapAPI = 'key='.trim($keyMapAPIOption);
		}
		wp_enqueue_script( 'googleapis',                 $protocol . '://maps.googleapis.com/maps/api/js?'.$keyMapAPI.'&amp;libraries=places', array(), false, true );
		
		wp_enqueue_script( 'markerclusterer',             $uri . '/libs/google-map/markerclusterer.js', array(), false, true );

		//-----------------enqueue script to run ajax-------------------------- 
		wp_enqueue_script( 'slzexploore-core-form', $uri . '/js/slzexploore-core-form.js', array('jquery'), SLZEXPLOORE_CORE_VERSION, true );
		wp_localize_script(
				'slzexploore-core-form',
				'ajaxurl',
				admin_url( 'admin-ajax.php' )
		);
		wp_enqueue_script( 'slzexploore-core-shortcode', $uri . '/js/slzexploore-core-shortcode.js', array('jquery'), SLZEXPLOORE_CORE_VERSION, true );
		
		wp_enqueue_script( 'slzexploore-core-maps',      $uri . '/js/slzexploore-core-maps.js', array('jquery'), SLZEXPLOORE_CORE_VERSION, true );

		wp_enqueue_script( 'slzexploore-core-multi-maps',      $uri . '/js/slzexploore-core-multi-maps.js', array('jquery'), SLZEXPLOORE_CORE_VERSION, true );

		wp_enqueue_script( 'slzexploore-core-booking',   $uri . '/js/slzexploore-core-booking.js', array('jquery'), SLZEXPLOORE_CORE_VERSION, true );
	}
	/**
	 * action using generate inline css
	 * @param string $custom_css
	 */
	public function add_inline_style( $custom_css ) {
		wp_enqueue_style('slzexploore-core-custom', SLZEXPLOORE_CORE_ASSET_URI . '/css/slzexploore-core-custom.css', array(), SLZEXPLOORE_CORE_VERSION);
		wp_add_inline_style( 'slzexploore-core-custom', $custom_css );
	}
	//********************* Post << *********************
	/**
	 * Add columns to post type list screen.
	 *
	 * @param array $columns Existing columns.
	 * @return array Amended columns.
	 */
	public function add_custom_columns( $columns ) {
		global $post;
	
		$defaults = array(
			'cb'               => '',
			'slzexploore_core-thumbs'   => esc_html__( 'Thumbnail', 'slzexploore-core' ),
			'title'            => '',
			'slzexploore_core-category' => esc_html__( 'Categories', 'slzexploore-core' ),
			'date'             => '',
		);
		$columns = array_merge( $defaults, $columns );
		return $columns;
	}
	public function add_slzexploore_tbook_columns( $columns ) {
		$defaults = array(
			'cb'                => '',
			'slzexploore_core-id'        => esc_html__( 'ID', 'slzexploore-core' ),
			'slzexploore_core-name'      => esc_html__( 'Customer Name', 'slzexploore-core' ),
			'slzexploore_core-tour'      => esc_html__( 'Tour Name', 'slzexploore-core' ),
			'slzexploore_core-tour-date' => esc_html__( 'Tour Date', 'slzexploore-core' ),
			'slzexploore_core-status'    => esc_html__( 'Status', 'slzexploore-core' ),
			'date'              => '',
		);
		$columns = array_merge( $defaults, $columns );
		unset($columns['title']);
		return $columns;
	}
	public function add_slzexploore_book_columns( $columns ) {
		$defaults = array(
			'cb'                    => '',
			'slzexploore_core-id'            => esc_html__( 'ID', 'slzexploore-core' ),
			'slzexploore_core-name'          => esc_html__( 'Customer Name', 'slzexploore-core' ),
			'slzexploore_core-checkin-date'  => esc_html__( 'Check in', 'slzexploore-core' ),
			'slzexploore_core-checkout-date' => esc_html__( 'Check out', 'slzexploore-core' ),
			'slzexploore_core-accomodation'  => esc_html__( 'Accomodation', 'slzexploore-core' ),
			'slzexploore_core-room-type'     => esc_html__( 'Room Types', 'slzexploore-core' ),
			'slzexploore_core-status'        => esc_html__( 'Status', 'slzexploore-core' ),
			'date'                  => '',
		);
		$columns = array_merge( $defaults, $columns );
		unset($columns['title']);
		return $columns;
	}
	public function add_slzexploore_vacancy_columns( $columns ) {
		$defaults = array(
			'cb'                   => '',
			'slzexploore_core-id'           => esc_html__( 'ID', 'slzexploore-core' ),
			'slzexploore_core-date-from'    => esc_html__( 'Date From', 'slzexploore-core' ),
			'slzexploore_core-date-to'      => esc_html__( 'Date To', 'slzexploore-core' ),
			'slzexploore_core-accomodation' => esc_html__( 'Accomodation', 'slzexploore-core' ),
			'slzexploore_core-room-type'    => esc_html__( 'Room Types', 'slzexploore-core' ),
 			'slzexploore_core-price'        => esc_html__( 'Price', 'slzexploore-core' ),
			'date'                 => '',
		);
		$columns = array_merge( $defaults, $columns );
		unset($columns['title']);
		return $columns;
	}
	public function add_slzexploore_room_columns( $columns ) {
		$defaults = array(
			'cb'                   => '',
			'slzexploore_core-thumbs'       => esc_html__( 'Thumbnail', 'slzexploore-core' ),
			'title'                => '',
			'slzexploore_core-category'     => esc_html__( 'Categories', 'slzexploore-core' ),
			'slzexploore_core-accomodation' => esc_html__( 'Accomodation', 'slzexploore-core' ),
			'slzexploore_core-price'        => esc_html__( 'Price', 'slzexploore-core' ),
			'date'                 => '',
		);
		$columns = array_merge( $defaults, $columns );
		return $columns;
	}
	
	public function add_slzexploore_tour_columns( $columns ) {
		$defaults = array(
			'cb'               => '',
			'slzexploore_core-thumbs'   => esc_html__( 'Thumbnail', 'slzexploore-core' ),
			'title'            => '',
			'slzexploore_core-category' => esc_html__( 'Categories', 'slzexploore-core' ),
			'slzexploore_core-location' => esc_html__( 'Locations', 'slzexploore-core' ),
			'slzexploore_core-price'    => esc_html__( 'Price', 'slzexploore-core' ),
			'date'             => '',
		);
		$columns = array_merge( $defaults, $columns );
		return $columns;
	}
	public function add_slzexploore_hotel_columns( $columns ) {
		$defaults = array(
			'cb'               => '',
			'slzexploore_core-thumbs'   => esc_html__( 'Thumbnail', 'slzexploore-core' ),
			'title'            => '',
			'slzexploore_core-category' => esc_html__( 'Categories', 'slzexploore-core' ),
			'slzexploore_core-location' => esc_html__( 'Locations', 'slzexploore-core' ),
			'slzexploore_core-price'    => esc_html__( 'Price', 'slzexploore-core' ),
			'date'             => '',
		);
		$columns = array_merge( $defaults, $columns );
		return $columns;
	}
	public function add_slzexploore_car_columns( $columns ) {
		$defaults = array(
			'cb'               => '',
			'slzexploore_core-thumbs'   => esc_html__( 'Thumbnail', 'slzexploore-core' ),
			'title'            => '',
			'slzexploore_core-category' => esc_html__( 'Categories', 'slzexploore-core' ),
			'slzexploore_core-location' => esc_html__( 'Locations', 'slzexploore-core' ),
			'slzexploore_core-price'    => esc_html__( 'Price', 'slzexploore-core' ),
			'date'             => '',
		);
		$columns = array_merge( $defaults, $columns );
		return $columns;
	}
	public function add_slzexploore_cruise_columns( $columns ) {
		$defaults = array(
			'cb'               => '',
			'slzexploore_core-thumbs'   => esc_html__( 'Thumbnail', 'slzexploore-core' ),
			'title'            => '',
			'slzexploore_core-category' => esc_html__( 'Categories', 'slzexploore-core' ),
			'slzexploore_core-location' => esc_html__( 'Locations', 'slzexploore-core' ),
			'slzexploore_core-price'    => esc_html__( 'Price', 'slzexploore-core' ),
			'date'             => '',
		);
		$columns = array_merge( $defaults, $columns );
		return $columns;
	}
	public function add_slzexploore_cabin_columns( $columns ) {
		$defaults = array(
			'cb'                   => '',
			'title'                => '',
			'slzexploore_core-category'     => esc_html__( 'Categories', 'slzexploore-core' ),
			'slzexploore_core-cruise'       => esc_html__( 'Cruises', 'slzexploore-core' ),
			'slzexploore_core-price'        => esc_html__( 'Price', 'slzexploore-core' ),
			'date'                 => '',
		);
		$columns = array_merge( $defaults, $columns );
		return $columns;
	}
	public function add_slzexploore_crbook_columns( $columns ) {
		$defaults = array(
			'cb'                => '',
			'slzexploore_core-id'        => esc_html__( 'ID', 'slzexploore-core' ),
			'slzexploore_core-name'      => esc_html__( 'Customer Name', 'slzexploore-core' ),
			'slzexploore_core-cruise'    => esc_html__( 'Cruise Name', 'slzexploore-core' ),
			'slzexploore_core-start-date'=> esc_html__( 'Start Date', 'slzexploore-core' ),
			'slzexploore_core-person'    => esc_html__( 'Person', 'slzexploore-core' ),
			'slzexploore_core-status'    => esc_html__( 'Status', 'slzexploore-core' ),
			'date'              => '',
		);
		$columns = array_merge( $defaults, $columns );
		unset($columns['title']);
		return $columns;
	}
	public function add_slzexploore_cbook_columns( $columns ) {
		$defaults = array(
			'cb'                => '',
			'slzexploore_core-id'        => esc_html__( 'ID', 'slzexploore-core' ),
			'slzexploore_core-name'      => esc_html__( 'Customer Name', 'slzexploore-core' ),
			'slzexploore_core-car'       => esc_html__( 'Car Name', 'slzexploore-core' ),
			'slzexploore_core-date-from' => esc_html__( 'Date From', 'slzexploore-core' ),
			'slzexploore_core-date-to'   => esc_html__( 'Date To', 'slzexploore-core' ),
			'slzexploore_core-status'    => esc_html__( 'Status', 'slzexploore-core' ),
			'date'              => '',
		);
		$columns = array_merge( $defaults, $columns );
		unset($columns['title']);
		return $columns;
	}
	public function add_slzexploore_exitem_columns( $columns ) {
		$defaults = array(
			'cb'                => '',
			'title'             => '',
			'slzexploore_core-price'     => esc_html__( 'Price', 'slzexploore-core' ),
			'slzexploore_core-max-item'  => esc_html__( 'Items', 'slzexploore-core' ),
			'slzexploore_core-support'   => esc_html__( 'Supports', 'slzexploore-core' ),
			'date'              => '',
		);
		$columns = array_merge( $defaults, $columns );
		return $columns;
	}
	public function add_slzexploore_faq_columns( $columns ) {
		$defaults = array(
			'cb'                => '',
			'title'             => '',
			'slzexploore_core-category'  => esc_html__( 'Categories', 'slzexploore-core' ),
			'date'              => '',
		);
		$columns = array_merge( $defaults, $columns );
		return $columns;
	}
	public function add_slzexploore_testi_columns( $columns ) {
		$defaults = array(
			'cb'                => '',
			'slzexploore_core-thumbs'    => esc_html__( 'Thumbnail', 'slzexploore-core' ),
			'title'             => '',
			'date'              => '',
		);
		$columns = array_merge( $defaults, $columns );
		return $columns;
	}
	public function add_slzexploore_schedule_columns( $columns ) {
		$defaults = array(
			'cb'                            => '',
			'slzexploore_core-id'           => esc_html__( 'ID', 'slzexploore-core' ),
			'slzexploore_core-tour'         => esc_html__( 'Tour', 'slzexploore-core' ),
			'slzexploore_core-start-date'   => esc_html__( 'Start Date', 'slzexploore-core' ),
			'slzexploore_core-end-date'     => esc_html__( 'End Date', 'slzexploore-core' ),
			'slzexploore_core-price'        => esc_html__( 'Adult Price', 'slzexploore-core' ),
			'date'                          => ''
		);
		$columns = array_merge( $defaults, $columns );
		unset($columns['title']);
		return $columns;
	}
	/**
	 * Custom column callback
	 *
	 * @param string $column Column ID.
	 */
	public function display_custom_columns( $column ){
		global $post;
		if ( ! $post ) return '';
		$post_id = $post->ID;
		$post_type = get_post_type();
		$method_name = $post_type . '_columns';
		if ( method_exists( $this, $method_name ) ) {
			$this->$method_name( $column, $post_id, $post_type );
		}
		switch ( $column ) {
			case 'slzexploore_core-thumbs':
				$opts = array(
					'post_id'    => $post_id,
					'size'       => array( 100, 100 ),
					'post_title' => $post->post_title,
				);
				echo ( Slzexploore_Core_Util::get_thumb_image( $opts ) );
				break;
			case 'slzexploore_core-category':
				$taxonomy_cat = $post_type . '_cat';
				$term = $this->get_taxonomy_column( $taxonomy_cat, $post_id, $post_type );
				echo wp_kses_post($term);
				break;
			case 'slzexploore_core-status':
				$taxonomy = $post_type . '_status';
				$term = $this->get_taxonomy_column( $taxonomy, $post_id, $post_type );
				echo wp_kses_post($term);
				break;
			case 'slzexploore_core-location':
				$taxonomy = $post_type . '_location';
				$term = $this->get_taxonomy_column( $taxonomy, $post_id, $post_type );
				echo wp_kses_post($term);
				break;
			case 'slzexploore_core-type':
				$taxonomy = $post_type . '_type';
				$term = $this->get_taxonomy_column( $taxonomy, $post_id, $post_type );
				echo wp_kses_post($term);
				break;
			case 'slzexploore_core-accomodation':
				$val = absint( get_post_meta( $post_id, $post_type . '_accommodation', true ) );
				if( !empty($val) ) {
					$model = new Slzexploore_Core_Accommodation();
					$model->get_custom_post($val);
					if( $model->title ) {
						echo '<a href="'.esc_url( get_edit_post_link($val) ).'" >'.esc_html($model->title).'</a>';
					}
				}
				break;
			case 'slzexploore_core-room-type':
				$val = absint( get_post_meta( $post_id, $post_type . '_room_type', true ) );
				if( !empty($val) ) {
					$model = new Slzexploore_Core_Room();
					$model->get_custom_post($val);
					if( $model->title ) {
						echo '<a href="'.esc_url( get_edit_post_link($val) ).'" >'.esc_html($model->title).'</a>';
					}
				}
				break;
			case 'slzexploore_core-tour':
				$val = absint( get_post_meta( $post_id, $post_type . '_tour', true ) );
				if( !empty($val) ) {
					$model = new Slzexploore_Core_Tour();
					$model->get_custom_post($val);
					if( $model->title ) {
						echo '<a href="'.esc_url( get_edit_post_link($val) ).'" >'.esc_html($model->title).'</a>';
					}
				}
				break;
			case 'slzexploore_core-price':
				$model = '';
				if( $post_type == 'slzexploore_room' ) {
					$model = new Slzexploore_Core_Room();
				} else if( $post_type == 'slzexploore_tour' ) {
					$model = new Slzexploore_Core_Tour();
				} else if( $post_type == 'slzexploore_hotel' ) {
					$model = new Slzexploore_Core_Accommodation();
				} else if( $post_type == 'slzexploore_car' ) {
					$model = new Slzexploore_Core_Car();
				} else if( $post_type == 'slzexploore_cruise' ) {
					$model = new Slzexploore_Core_Cruise();
				} else if( $post_type == 'slzexploore_exitem' ) {
					$model = new Slzexploore_Core_Extra_Item();
				} else if( $post_type == 'slzexploore_cabin' ) {
					$model = new Slzexploore_Core_Cabin_Type();
				} else if( $post_type == 'slzexploore_vacancy' ) {
					$model = new Slzexploore_Core_Vacancy();
				} else if( $post_type == 'slzexploore_schedule' ) {
					$model = new Slzexploore_Core_Schedule();
				}
				if( $model ) {
					$model->get_custom_post($post_id);
					echo ( $model->get_column_price( true ) );
				}
				break;
			case 'slzexploore_core-id':
				echo '<a href="'.esc_url( get_edit_post_link($post_id) ).'" >'.esc_html($post_id).'</a>';
				$this->set_link_column( $post_id, true, $post_type);
				break;
			case 'slzexploore_core-cruise':
				$val = absint( get_post_meta( $post_id, $post_type . '_cruise_id', true ) );
				if( !empty($val) ) {
					$model = new Slzexploore_Core_Cruise();
					$model->get_custom_post($val);
					if( $model->title ) {
						echo '<a href="'.esc_url( get_edit_post_link($val) ).'" >'.esc_html($model->title).'</a>';
					}
				}
				break;
			case 'slzexploore_core-max-item':
				echo esc_html( get_post_meta( $post_id, $post_type .'_max_items', true ) );
				break;
			case 'slzexploore_core-support':
				$arr = array(
					'_tour_cat'          => esc_html__( 'Tour', 'slzexploore-core' ),
					'_accommodation_cat' => esc_html__( 'Accommodation', 'slzexploore-core' ),
					'_car_cat'           => esc_html__( 'Car Rent', 'slzexploore-core' ),
					'_cruise_cat'        => esc_html__( 'Cruise', 'slzexploore-core' ),
				);
				$support = array();
				foreach( $arr as $key => $key_val ) {
					$meta_val = get_post_meta( $post_id, $post_type . $key, true );
					if( $meta_val ) {
						$support[] = $key_val;
					}
				}
				if( $support ) {
					echo ( implode(', ', $support ) );
				}
				break;
		}
	}
	private function slzexploore_vacancy_columns( $column, $post_id, $post_type ) {
		$defaults = array(
			'slzexploore_core-date-from'    => 'date_from',
			'slzexploore_core-date-to'      => 'date_to',
		);
		switch ( $column ) {
			default:
				$key = Slzexploore_Core::get_value($defaults, $column );
				if( !empty( $key ) ) {
					$key = $post_type . '_' . $key;
					echo esc_html( get_post_meta( $post_id, $key, true ) );
				}
				break;
		}
	}
	private function slzexploore_schedule_columns( $column, $post_id, $post_type ) {
		$defaults = array(
			'slzexploore_core-start-date'    => 'start_date',
			'slzexploore_core-end-date'      => 'end_date'
		);
		switch ( $column ) {
			default:
				$key = Slzexploore_Core::get_value($defaults, $column );
				if( !empty( $key ) ) {
					$key = $post_type . '_' . $key;
					echo esc_html( get_post_meta( $post_id, $key, true ) );
				}
				break;
		}
	}
	private function slzexploore_book_columns( $column, $post_id, $post_type ) {
		$defaults = array(
			'slzexploore_core-name'          => '',
			'slzexploore_core-checkin-date'  => 'check_in_date',
			'slzexploore_core-checkout-date' => 'check_out_date',
		);
		switch ( $column ) {
			case 'slzexploore_core-name':
				$key = $post_type . '_first_name';
				$name[] = get_post_meta( $post_id, $key, true );
				$key = $post_type . '_last_name';
				$name[] = get_post_meta( $post_id, $key, true );
				echo esc_html( implode( ' ', $name ) );
				break;
			default:
				$key = Slzexploore_Core::get_value($defaults, $column );
				if( !empty( $key ) ) {
					$key = $post_type . '_' . $key;
					echo esc_html( get_post_meta( $post_id, $key, true ) );
				}
				break;
		}
	}
	private function slzexploore_tbook_columns( $column, $post_id, $post_type ) {
		$defaults = array(
			'slzexploore_core-booking'       => 'booking_no',
			'slzexploore_core-name'          => '',
			'slzexploore_core-tour-date'     => 'tour_date',
			'slzexploore_core-person'        => '',
		);
		switch ( $column ) {
			case 'slzexploore_core-name':
				$key = $post_type . '_first_name';
				$name[] = get_post_meta( $post_id, $key, true );
				$key = $post_type . '_last_name';
				$name[] = get_post_meta( $post_id, $key, true );
				echo esc_html( implode( ' ', $name ) );
				break;
			default:
				$key = Slzexploore_Core::get_value($defaults, $column );
				if( !empty( $key ) ) {
					$key = $post_type . '_' . $key;
					echo esc_html( get_post_meta( $post_id, $key, true ) );
				}
				break;
		}
	}
	
	private function slzexploore_crbook_columns( $column, $post_id, $post_type ) {
		$defaults = array(
			'slzexploore_core-name'          => '',
			'slzexploore_core-start-date'     => 'start_date',
			'slzexploore_core-person'        => '',
		);
		switch ( $column ) {
			case 'slzexploore_core-name':
				$key = $post_type . '_first_name';
				$name[] = get_post_meta( $post_id, $key, true );
				$key = $post_type . '_last_name';
				$name[] = get_post_meta( $post_id, $key, true );
				echo esc_html( implode( ' ', $name ) );
				break;
			case 'slzexploore_core-person':
				$person = array();
				$key = $post_type . '_adults';
				$adults = get_post_meta( $post_id, $key, true );
				if( $adults ){
					$person[] = $adults . esc_html__( ' adults', 'slzexploore-core' );
				}
				
				$key = $post_type . '_children';
				$children = get_post_meta( $post_id, $key, true );
				if( $children ){
					$person[] = $children . esc_html__( ' children', 'slzexploore-core' );
				}
				echo esc_html( implode( ', ', $person ) );
				break;
			default:
				$key = Slzexploore_Core::get_value($defaults, $column );
				if( !empty( $key ) ) {
					$key = $post_type . '_' . $key;
					echo esc_html( get_post_meta( $post_id, $key, true ) );
				}
				break;
		}
	}
	
	private function slzexploore_cbook_columns( $column, $post_id, $post_type ) {
		$defaults = array(
			'slzexploore_core-name'          => '',
			'slzexploore_core-car'           => '',
			'slzexploore_core-date-from'     => 'date_from',
			'slzexploore_core-date-to'       => 'date_to',
		);
		switch ( $column ) {
			case 'slzexploore_core-name':
				$key = $post_type . '_first_name';
				$name[] = get_post_meta( $post_id, $key, true );
				$key = $post_type . '_last_name';
				$name[] = get_post_meta( $post_id, $key, true );
				echo esc_html( implode( ' ', $name ) );
				break;
			case 'slzexploore_core-car':
				$key = $post_type . '_car_id';
				$car_id = get_post_meta( $post_id, $key, true );
				if( !empty( $car_id ) ){
					echo esc_html( get_the_title( $car_id ) );
				}
				break;
			default:
				$key = Slzexploore_Core::get_value($defaults, $column );
				if( !empty( $key ) ) {
					$key = $post_type . '_' . $key;
					echo esc_html( get_post_meta( $post_id, $key, true ) );
				}
				break;
		}
	}
	
	private function set_link_column( $post_id, $is_edit = false, $post_type = '' ) {
		global $post;
		if( ! $post ) return;
		if( $is_edit ) {
			if( $post->post_status == 'trash' ) {
				$link_untrash = wp_nonce_url( admin_url( 'post.php?post=' . $post_id . '&amp;action=untrash' ), 'untrash-post_' . $post_id );
				$link_edit = '<a href="'.esc_url( $link_untrash ).'" >'.esc_html__('Restore', 'slzexploore-core').'</a>';
				$link_del_trash = '<a href="'.esc_url( get_delete_post_link( $post_id, '', true ) ).'" >'.esc_html__('Delete Permanently', 'slzexploore-core').'</a>';
			} else {
				$link_edit = '<a href="'.esc_url( get_edit_post_link($post_id) ).'" >'.esc_html__('Edit', 'slzexploore-core').'</a>';
				
				if( $post_type == 'slzexploore_vacancy' || $post_type == 'slzexploore_schedule' ) {
					$link_del_trash = '<a href="'.esc_url( get_delete_post_link( $post_id, '', true ) ).'" >'.esc_html__('Delete Permanently', 'slzexploore-core').'</a>';
				} else{
					$link_del_trash = '<a href="'.esc_url( get_delete_post_link($post_id) ).'" >'.esc_html__('Trash', 'slzexploore-core').'</a>';
				}
			}
			echo '<div class="row-actions">
					<span class="edit">' . ($link_edit) . '</span> | 
					<span class="delete">' . ($link_del_trash) . '</span>
				</div>';
		}
	}
	private function get_taxonomy_column( $taxonomy, $post_id, $post_type ) {
		$term = '';
		if( taxonomy_exists( $taxonomy ) ) {
			// add separator for two or more terms
			$separtor = ', ';
			// get lists of term associated in the current post type
			$terms = get_the_terms( $post_id, $taxonomy );
			$links = array();
			if( $terms ) {
				foreach ( $terms as $term ) {
					// get link
					$term_link = home_url().'/wp-admin/edit.php?post_type='.$post_type.'&'.$taxonomy.'='.$term->slug;
					// the function explain its purpose
					if( is_wp_error( $term_link ) )
						continue;
					$links[] = '<a href="'.$term_link.'">'.$term->name.'</a>';
				}
			}
			$term = implode( $separtor, $links );
		}
		return $term;
	}
	/**
	 * Add columns to post type list
	 */
	public function manage_custom_columns(){
		$post_types = Slzexploore_Core::get_config( 'post_type', 'custom_column' );
		if( $post_types ) {
			foreach( $post_types as $pt ) {
				$method_name = 'add_'.$pt.'_columns';
				if( method_exists( $this, $method_name)) {
					add_filter( 'manage_edit-'. $pt .'_columns', array( 'Slzexploore_Core', '[setting.Setting_Init, add_'. $pt .'_columns]' ) );
				} else {
					add_filter( 'manage_edit-'. $pt .'_columns', array( 'Slzexploore_Core', '[setting.Setting_Init, add_custom_columns]' ) );
				}
				add_action( 'manage_'. $pt .'_posts_custom_column', array( 'Slzexploore_Core', '[setting.Setting_Init, display_custom_columns]' ) );
			}
		}
	}
	/**
	 * Add meta box feature video to post type
	 */
	public function add_metabox_feature_video() {
		$post_types = Slzexploore_Core::get_config( 'post_type', 'feature_video' );
		if( $post_types ) {
			foreach( $post_types as $post_type ) {
				add_meta_box( 'slzexploore_core_mbox_feature_video', 'Featured Video', array( 'Slzexploore_Core', '[posttype.Post_Controller, metabox_feature_video]' ), $post_type, 'normal', 'high' );
			}
		}
	}
	/**
	 * Save feature video to post type
	 */
	public function save_feature_video( $post_id ) {
		if( isset( $_POST['slzexploore_feature_video'] ) ) {
			if( !isset($_POST['slzexploore_feature_video']['generate_thumnail']) ) {
				$_POST['slzexploore_feature_video']['generate_thumnail'] = '';
			}
			update_post_meta( $post_id, 'slzexploore_feature_video', $_POST['slzexploore_feature_video'] );
			if( $_POST['slzexploore_feature_video']['generate_thumnail'] ) {
				$model = new Slzexploore_Core_Video_Model();
				$model->get_video_thumb( $post_id, 'slzexploore_feature_video' );
			}
		}
	}
	//********************* Post >> *********************
	public function add_submenu_pages() {
		add_submenu_page( 'edit.php?post_type=slzexploore_hotel', esc_html__( 'Room Types', 'slzexploore-core' ), esc_html__( 'Room Types', 'slzexploore-core' ), 'edit_posts', 'edit.php?post_type=slzexploore_room' );
		add_submenu_page( 'edit.php?post_type=slzexploore_hotel', esc_html__( 'Vacancies', 'slzexploore-core' ), esc_html__( 'Vacancies', 'slzexploore-core' ), 'edit_posts', 'edit.php?post_type=slzexploore_vacancy' );
		add_submenu_page( 'edit.php?post_type=slzexploore_hotel', esc_html__( 'Bookings', 'slzexploore-core' ), esc_html__( 'Bookings', 'slzexploore-core' ), 'edit_posts', 'edit.php?post_type=slzexploore_book' );
		add_submenu_page( 'edit.php?post_type=slzexploore_tour', esc_html__( 'Tour Bookings', 'slzexploore-core' ), esc_html__( 'Tour Bookings', 'slzexploore-core' ), 'edit_posts', 'edit.php?post_type=slzexploore_tbook' );
		add_submenu_page( 'edit.php?post_type=slzexploore_car', esc_html__( 'Car Bookings', 'slzexploore-core' ), esc_html__( 'Car Bookings', 'slzexploore-core' ), 'edit_posts', 'edit.php?post_type=slzexploore_cbook' );
		// Cabin Types
		add_submenu_page( 'edit.php?post_type=slzexploore_cruise', esc_html__( 'Cabin Types', 'slzexploore-core' ), esc_html__( 'Cabin Types', 'slzexploore-core' ), 'edit_posts', 'edit.php?post_type=slzexploore_cabin' );
		// Cruise Bookings
		add_submenu_page( 'edit.php?post_type=slzexploore_cruise', esc_html__( 'Cruise Bookings', 'slzexploore-core' ), esc_html__( 'Cruise Bookings', 'slzexploore-core' ), 'edit_posts', 'edit.php?post_type=slzexploore_crbook' );
		// Schedule
		add_submenu_page( 'edit.php?post_type=slzexploore_tour', esc_html__( 'Tour Schedule', 'slzexploore-core' ), esc_html__( 'Tour Schedule', 'slzexploore-core' ), 'edit_posts', 'edit.php?post_type=slzexploore_schedule' );
	}

	public function remove_custom_metabox() {
		remove_meta_box( 'slzexploore_hotel_statusdiv',  'slzexploore_hotel', 'side' );
		remove_meta_box( 'slzexploore_room_statusdiv',  'slzexploore_room', 'side' );
		remove_meta_box( 'slzexploore_book_statusdiv',   'slzexploore_book', 'side' );
		remove_meta_box( 'slzexploore_tour_statusdiv',   'slzexploore_tour', 'side' );
		remove_meta_box( 'slzexploore_tbook_statusdiv',  'slzexploore_tbook', 'side' );
		remove_meta_box( 'slzexploore_car_statusdiv',    'slzexploore_car', 'side' );
		remove_meta_box( 'slzexploore_cbook_statusdiv',  'slzexploore_cbook', 'side' );
		remove_meta_box( 'slzexploore_cruise_statusdiv', 'slzexploore_cruise', 'side' );
		remove_meta_box( 'slzexploore_crbook_statusdiv', 'slzexploore_crbook', 'side' );
	}

	public function add_permalink_settings(){
		$screen = get_current_screen();
		if ( !$screen ) {
			return;
		}
		if( $screen->id == 'options-permalink' ){
			$this->add_permalink_options();
			$this->save_permalink_options();
		}
	}
	public function add_permalink_options(){
		$custom_link_obj = array(
			'car'             => esc_html__( 'Car base', 'slzexploore-core' ),
			'car_cat'         => esc_html__( 'Car category base', 'slzexploore-core' ),
			'car_location'    => esc_html__( 'Car location base', 'slzexploore-core' ),
			'cruise'          => esc_html__( 'Cruise base', 'slzexploore-core' ),
			'cruise_cat'      => esc_html__( 'Cruise category base', 'slzexploore-core' ),
			'cruise_facility' => esc_html__( 'Cruise facility base', 'slzexploore-core' ),
			'cruise_location' => esc_html__( 'Cruise location base', 'slzexploore-core' ),
			'hotel'           => esc_html__( 'Accommodation base', 'slzexploore-core' ),
			'hotel_cat'       => esc_html__( 'Accommodation category base', 'slzexploore-core' ),
			'hotel_facility'  => esc_html__( 'Accommodation facility base', 'slzexploore-core' ),
			'hotel_location'  => esc_html__( 'Accommodation location base', 'slzexploore-core' ),
			'tour'            => esc_html__( 'Tour base', 'slzexploore-core' ),
			'tour_cat'        => esc_html__( 'Tour category base', 'slzexploore-core' ),
			'tour_location'   => esc_html__( 'Tour location base', 'slzexploore-core' ),
			'team'            => esc_html__( 'Team base', 'slzexploore-core' ),
			'team_cat'        => esc_html__( 'Team category base', 'slzexploore-core' ),
		);
		foreach( $custom_link_obj as $key => $value ){
			add_settings_field(
				'slzexploore_' . $key . '_slug',
				$value,
				array( SLZEXPLOORE_CORE_CLASS, '[setting.Setting_Init, render_permalink_input]' ),
				'permalink',
				'optional',
				array( 'key' => $key )
			);
		}
	}
	public function save_permalink_options(){
		if ( ! is_admin() ) {
			return;
		}
		if ( isset( $_POST['permalink_structure'] ) ) {
			$permalinks = get_option( 'slzexploore_permalinks' );
			if ( ! $permalinks ) {
				$permalinks = array();
			}
			$custom_link = array(
							'hotel', 'hotel_cat', 'hotel_facility', 'hotel_location',
							'tour', 'tour_cat', 'tour_location',
							'team', 'team_cat',
							'car', 'car_cat', 'car_location',
							'cruise', 'cruise_cat', 'cruise_location', 'cruise_facility'
						);
			foreach( $custom_link as $name ){
				if( isset( $_POST['slzexploore_'.$name.'_slug'] ) ){
					$permalinks[$name] = untrailingslashit( trim( $_POST['slzexploore_'.$name.'_slug'] ) );
				}
			}
			update_option( 'slzexploore_permalinks', $permalinks );
		}
	}
	public function render_permalink_input( $data ) {
		$permalinks = get_option( 'slzexploore_permalinks' );
		if( isset( $data['key'] ) && !empty( $data['key'] ) ){
			$value = '';
			$key = $data['key'];
			if( isset( $permalinks[$key] ) && !empty( $permalinks[$key] ) ){
				$value = $permalinks[$key];
			}
			switch ( $key ) {
				case 'hotel':
					echo '<input name="slzexploore_hotel_slug" type="text" class="regular-text code" value="'.esc_attr( $value ).'" placeholder="'.esc_attr__('accommodations', 'slzexploore-core').'" />';
					break;
				case 'hotel_cat':
					echo '<input name="slzexploore_hotel_cat_slug" type="text" class="regular-text code" value="'.esc_attr( $value ).'" placeholder="'.esc_attr__('accommodation-category', 'slzexploore-core').'" />';
					break;
				case 'hotel_facility':
					echo '<input name="slzexploore_hotel_facility_slug" type="text" class="regular-text code" value="'.esc_attr( $value ).'" placeholder="'.esc_attr__('facility', 'slzexploore-core').'" />';
					break;
				case 'hotel_location':
					echo '<input name="slzexploore_hotel_location_slug" type="text" class="regular-text code" value="'.esc_attr( $value ).'" placeholder="'.esc_attr__('location', 'slzexploore-core').'" />';
					break;
				case 'tour':
					echo '<input name="slzexploore_tour_slug" type="text" class="regular-text code" value="'.esc_attr( $value ).'" placeholder="'.esc_attr__('tours', 'slzexploore-core').'" />';
					break;
				case 'tour_cat':
					echo '<input name="slzexploore_tour_cat_slug" type="text" class="regular-text code" value="'.esc_attr( $value ).'" placeholder="'.esc_attr__('tour-category', 'slzexploore-core').'" />';
					break;
				case 'tour_location':
					echo '<input name="slzexploore_tour_location_slug" type="text" class="regular-text code" value="'.esc_attr( $value ).'" placeholder="'.esc_attr__('tour-location', 'slzexploore-core').'" />';
					break;
				case 'team':
					echo '<input name="slzexploore_team_slug" type="text" class="regular-text code" value="'.esc_attr( $value ).'" placeholder="'.esc_attr__('teams', 'slzexploore-core').'" />';
					break;
				case 'team_cat':
					echo '<input name="slzexploore_team_cat_slug" type="text" class="regular-text code" value="'.esc_attr( $value ).'" placeholder="'.esc_attr__('team-category', 'slzexploore-core').'" />';
					break;
				case 'car':
					echo '<input name="slzexploore_car_slug" type="text" class="regular-text code" value="'.esc_attr( $value ).'" placeholder="'.esc_attr__('car_rent', 'slzexploore-core').'" />';
					break;
				case 'car_cat':
					echo '<input name="slzexploore_car_cat_slug" type="text" class="regular-text code" value="'.esc_attr( $value ).'" placeholder="'.esc_attr__('car-category', 'slzexploore-core').'" />';
					break;
				case 'car_location':
					echo '<input name="slzexploore_car_location_slug" type="text" class="regular-text code" value="'.esc_attr( $value ).'" placeholder="'.esc_attr__('car-location', 'slzexploore-core').'" />';
					break;
				case 'cruise':
					echo '<input name="slzexploore_cruise_slug" type="text" class="regular-text code" value="'.esc_attr( $value ).'" placeholder="'.esc_attr__('cruises', 'slzexploore-core').'" />';
					break;
				case 'cruise_cat':
					echo '<input name="slzexploore_cruise_cat_slug" type="text" class="regular-text code" value="'.esc_attr( $value ).'" placeholder="'.esc_attr__('cruise-category', 'slzexploore-core').'" />';
					break;
				case 'cruise_location':
					echo '<input name="slzexploore_cruise_location_slug" type="text" class="regular-text code" value="'.esc_attr( $value ).'" placeholder="'.esc_attr__('cruise-location', 'slzexploore-core').'" />';
					break;
				case 'cruise_facility':
					echo '<input name="slzexploore_cruise_facility_slug" type="text" class="regular-text code" value="'.esc_attr( $value ).'" placeholder="'.esc_attr__('cruise-facility', 'slzexploore-core').'" />';
					break;
			}
			
		}
	}

	public function update_notices() {
		if ( !get_option( 'slzexploore_upd_wc_db', false ) && version_compare(WC_VERSION, '3.0.0') >= 0 )
		{
	    ?>
			<div id="message" class="notice notice-success is-dismissible">
				<p><strong><?php esc_html_e( 'Exploore Products update', 'slzexploore-core' ); ?></strong> &#8211; <?php esc_html_e( 'We need to update your store\'s database to the latest version.', 'slzexploore-core' ); ?></p>
				<p><button class="slz-upd-wc-db button-primary"><?php esc_html_e( 'Run the updater', 'slzexploore-core' ); ?></button></p>
			</div>
		<?php
		}
	}
}