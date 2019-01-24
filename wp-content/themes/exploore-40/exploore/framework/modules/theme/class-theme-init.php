<?php
/**
 * Theme class.
 * 
 * @author Swlabs
 * @package Exploore
 * @since 1.0
 */
Slzexploore::load_class( 'Abstract' );

class Slzexploore_Theme_Init extends Slzexploore_Abstract {
	/**
	 * Register style/script in admin
	 * 
	 */
	public function admin_enqueue(){
		$uri = get_template_directory_uri() . '/assets/admin';
		// css
		wp_enqueue_style( 'slzexploore-admin-style',   $uri . '/css/slzexploore-admin-style.css', false, SLZEXPLOORE_THEME_VER, 'all' );
		wp_enqueue_style( 'font-awesome.min',          SLZEXPLOORE_PUBLIC_URI . '/font/font-icon/font-awesome/css/font-awesome.min.css', array(), false );
		wp_enqueue_style( 'slzexploore-font-flaticon', SLZEXPLOORE_PUBLIC_URI . '/font/font-icon/font-flaticon/flaticon.css', array(), SLZEXPLOORE_THEME_VER );
		wp_enqueue_style( 'slzexploore-font-icomoon',  SLZEXPLOORE_PUBLIC_URI . '/font/font-icon/font-icomoon/icomoon.css', array(), SLZEXPLOORE_THEME_VER );
		// js
		wp_enqueue_media();
		wp_enqueue_script( 'slzexploore-widget',      $uri . '/js/slzexploore-widget.js', array('jquery'), SLZEXPLOORE_THEME_VER, true );
		//menu
		wp_enqueue_script( 'slzexploore-menu',        $uri . '/js/slzexploore-menu.js', array('jquery'), SLZEXPLOORE_THEME_VER, true );
	}

	/**
	 * Register style/script in public
	 */
	public function public_enqueue() {
		$dir_uri = get_template_directory_uri();
		$uri = SLZEXPLOORE_PUBLIC_URI;
		$site_skin = Slzexploore::get_option( 'slz-site-skin' );
		$site_skin_array = array('color-1','color-10','color-2','color-3','color-4','color-5','color-6','color-7','color-8','color-9');
	
		wp_enqueue_style( 'slzexploore-style', get_stylesheet_uri(), array(), SLZEXPLOORE_THEME_VER );
		//google fonts
		wp_enqueue_style( 'slzexploore-fonts',                $this->add_fonts_url(), array(), null );
		//font
		wp_enqueue_style( 'font-awesome.min',                 $uri . '/font/font-icon/font-awesome/css/font-awesome.min.css', array(), false );
		wp_enqueue_style( 'slzexploore-font-flaticon',        $uri . '/font/font-icon/font-flaticon/flaticon.css', array(), SLZEXPLOORE_THEME_VER );
		wp_enqueue_style( 'slzexploore-font-icomoon',         $uri . '/font/font-icon/font-icomoon/icomoon.css', array(), SLZEXPLOORE_THEME_VER );

		//libs
		wp_enqueue_style( 'bootstrap.min',                    $uri . '/libs/bootstrap/css/bootstrap.min.css', array(), false );
		wp_enqueue_style( 'animate',                          $uri . '/libs/animate/animate.css', array(), false );
		wp_enqueue_style( 'bootstrap-datetimepicker',         $uri . '/libs/bootstrap-datepicker/css/bootstrap-datepicker.min.css', array(), false );
		wp_enqueue_style( 'validate',                         $uri . '/libs/validation/css/validate.css', array(), '1.15.0' );
		wp_enqueue_style( 'slzexploore-layout',               $uri . '/css/slzexploore-layout.css', array(), SLZEXPLOORE_THEME_VER );
		wp_enqueue_style( 'slzexploore-components',           $uri . '/css/slzexploore-components.css', array(), SLZEXPLOORE_THEME_VER );
		
		wp_enqueue_style( 'slzexploore-responsive',           $uri . '/css/slzexploore-responsive.css', array(), SLZEXPLOORE_THEME_VER );
		wp_enqueue_style( 'slzexploore-custom-theme',         $uri . '/css/slzexploore-custom-theme.css', array(), SLZEXPLOORE_THEME_VER );
		wp_enqueue_style( 'slzexploore-custom-editor',        $uri . '/css/slzexploore-custom-editor.css', array(), SLZEXPLOORE_THEME_VER );
		wp_enqueue_style( 'slzexploore-custom',        		  $uri . '/css/slzexploore-custom.css', array(), SLZEXPLOORE_THEME_VER );

		// js
		wp_enqueue_script( 'slzexploore-skip-link-focus-fix', $dir_uri . '/js/skip-link-focus-fix.js', array(), '20130115', true );

		// comment
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

		if(is_author()){
			wp_enqueue_script( 'isotope.min',          $uri . '/libs/isotope/isotope.min.js', array('jquery'), false, true );
		}

		wp_enqueue_script( 'bootstrap.min',            $uri . '/libs/bootstrap/js/bootstrap.min.js', array('jquery'), false, true );
		wp_enqueue_script( 'background-video',         $uri . '/libs/background-video/video-bg.js', array('jquery'), false, true );
		wp_enqueue_script( 'detect-browser',           $uri . '/libs/detect-browser/browser.js', array('jquery'), false, true );
		wp_enqueue_script( 'bootstrap-datepicker.min', $uri . '/libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js', array('jquery'), false, true );

		// recaptcha
		wp_enqueue_script( 'recaptcha', 'https://www.google.com/recaptcha/api.js', array(), false, true );
		wp_enqueue_script( 'jquery.validate.min', $uri . '/libs/validation/js/jquery.validate.min.js', array('jquery'), false, true );

		// theme js
		wp_enqueue_script( 'slzexploore-main',         $uri . '/js/slzexploore-main.js', array(), SLZEXPLOORE_THEME_VER, true );
		wp_enqueue_script( 'slzexploore-custom',       $uri . '/js/slzexploore-custom.js', array(), SLZEXPLOORE_THEME_VER, true );
		
		// ajax script
		wp_enqueue_script( 'slzexploore-ajax',         $uri . '/js/slzexploore-ajax.js', array('jquery'), SLZEXPLOORE_THEME_VER, true );
		wp_localize_script(
				'slzexploore-ajax',
				'ajaxurl',
				esc_url( admin_url( 'admin-ajax.php' ) )
		);
		
		//for contact form 7 plugin
		if ( SLZEXPLOORE_WPCF7_ACTIVE ) {
			wp_localize_script(
					'slz-form',
					'ajaxurl',
					esc_url( admin_url( 'admin-ajax.php' ) )
			);
			wp_enqueue_script( 'slzexploore-cf7-jquery', plugins_url() . '/contact-form-7/includes/js/jquery.form.min.js', array(), '3.51.0-2014.06.20', true );
			wp_enqueue_script( 'slzexploore-cf7-scripts', plugins_url() . '/contact-form-7/includes/js/scripts.js', array(), WPCF7_VERSION, true );
		}
		// Woocommerce plugin
		if ( SLZEXPLOORE_WOOCOMMERCE_ACTIVE ) {
			wp_enqueue_style( 'slzexploore-woocommerce',       $uri . '/css/slzexploore-woocommerce.css', array(), SLZEXPLOORE_THEME_VER );
			wp_enqueue_script( 'slzexploore-woocommerce',      $uri . '/js/slzexploore-woocommerce.js', array('jquery'), SLZEXPLOORE_THEME_VER, true );
		}

		//load by skin
		if ( in_array( $site_skin,$site_skin_array ) && $site_skin != 'color-1' ){
			wp_enqueue_style( 'slzexploore-color',            $uri . '/css/skin/'.$site_skin.'/slzexploore-color.css', array(), SLZEXPLOORE_THEME_VER );
		}

		if( is_rtl() ){
			wp_enqueue_style( 'slzexploore-bootstrap-rtl',    $uri . '/libs/bootstrap-rtl.min.css', array(), false );
		}
	}
	/**
	 * Google fonts
	 */
	function add_fonts_url() {
		$fonts_url    = '';
		$family_fonts = array();
		$subsets      = 'latin,latin-ext';
	
		/* Translators: If there are characters in your language that are not supported
		 by chosen font(s), translate this to 'off'. Do not translate into your own language.
			*/
		if ( 'off' !== _x( 'on', 'Roboto font: on or off', 'exploore' ) ) {
			$family_fonts[] = 'Roboto:300,400,500,700,900';
		}
	
		/* Translators: If there are characters in your language that are not supported
		 by chosen font(s), translate this to 'off'. Do not translate into your own language.
			*/
		if ( 'off' !== _x( 'on', 'Montserrat font: on or off', 'exploore' ) ) {
			$family_fonts[] = 'Montserrat:400,700';
		}
	
		if ( $family_fonts ) {
			$fonts_url = add_query_arg( array(
				'family' => urlencode( implode( '|', $family_fonts ) ),
				'subset' => urlencode( $subsets ),
			), 'https://fonts.googleapis.com/css' );
		}
	
		return $fonts_url;
	}
	/**
	 * General setting
	 * 
	 */
	public function theme_setup() {
		// Editor
		$this->add_theme_supports();
		$this->add_image_sizes();
	}
	/**
	 * Add theme_supports
	 * 
	 */
	public function add_theme_supports() {
	
		// Add RSS feed links to <head> for posts and comments.
		add_theme_support( 'automatic-feed-links' );
		// Default custom header
		add_theme_support( 'custom-header' );
		// Default custom backgrounds
		add_theme_support( 'custom-background' );
		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
		) );

		/*
		* Enable support for Post Formats.
		*/
		// Post Formats
		add_theme_support( 'post-formats', array( 'image', 'video','audio' ) );
		// Add post thumbnail functionality
		add_theme_support('post-thumbnails');
		set_post_thumbnail_size(750, 350, true);
		//
		add_theme_support( 'title-tag' );
		// woocommerce support
		add_theme_support( 'woocommerce' );
	}
	
	/**
	 * Add image sizes
	 * 
	 */
	public function add_image_sizes() {
		$image_sizes = Slzexploore::get_config('image_sizes');
		foreach($image_sizes as $key => $sizes ) {
			$crop = true;
			if( isset( $sizes['crop'] ) ) {
				$crop = $sizes['crop'];
			}
			$key = SLZEXPLOORE_THEME_PREFIX . $key;
			add_image_size( $key, $sizes['width'], $sizes['height'], $crop );
		}
	}
	/**
	 * action using generate inline css
	 * @param string $custom_css
	 */
	public function add_inline_style( $custom_css ) {
		wp_enqueue_style('slzexploore-custom-inline', SLZEXPLOORE_PUBLIC_URI . '/css/slzexploore-custom-inline.css');
		wp_add_inline_style( 'slzexploore-custom-inline', $custom_css );
	}

	//************************* Front Page << ***********************
	/**
	 * Get page options, apply to theme options.(front page)
	 *
	 */
	public function get_page_options() {
		global $slzexploore_options;
		global $slzexploore_page_options;
	
		if( slzexploore_is_custom_post_type_archive() ) {
			$archive_opt = Slzexploore::get_config( 'mapping', 'archive' );
			foreach ( $archive_opt as $opt_key => $opt ) {
				if( is_post_type_archive($opt_key) ) {
					foreach( $opt as $key => $val ) {
						$slzexploore_options[$val] = $slzexploore_options[$key];
					}
					break;
				}
			}
		}
		if( is_search() || is_archive() || is_category() || is_tag() ){
			return;
		}
		$post_id = get_the_ID();
		if( ! $post_id ) {
			return;
		}
		$post_type = get_post_type($post_id);
		if( $post_type == 'product' ) {
			$product_opt = Slzexploore::get_config( 'mapping', 'product' );
			foreach ( $product_opt as $opt_key => $opt_val ) {
				$slzexploore_options[$opt_val] = $slzexploore_options[$opt_key];
			}
		}
		//
		$featured_img = '';
		if( $post_type == 'slzexploore_hotel' || $post_type == 'slzexploore_tour' || $post_type == 'slzexploore_car' || $post_type == 'slzexploore_cruise' ){
			$post_f_image = get_post_thumbnail_id( $post_id );
			if( !empty($post_f_image) ) {
				$attachment_image = wp_get_attachment_image_src($post_f_image, 'full');
				$featured_img = $attachment_image[0];
			}
		}
		//
		$slzexploore_page_options = get_post_meta( $post_id, 'slzexploore_page_options', true );
		if( empty( $slzexploore_page_options ) ) {
			if( !empty( $featured_img ) ) {
				$slzexploore_options['slz-page-title-bg']['background-image'] = $featured_img;
			}
			return;
		}
		$image_id_keys = array('background_image_id', 'pt_background_image_id');
		$maps = Slzexploore::get_config( 'mapping', 'options' );
	
		$no_default = Slzexploore::get_config( 'mapping', 'no-default-options' );
		foreach($maps as $option_type => $page_options ) {
			$is_theme_default = $option_type .'_default';
			if( ( ! in_array($option_type, $no_default) ) &&
					(!isset( $slzexploore_page_options[$is_theme_default] ) || isset( $slzexploore_page_options[$is_theme_default] ) && ! empty( $slzexploore_page_options[$is_theme_default] ) ) )
			{
				// no get page options
				continue;
			} else {
				foreach( $page_options as $key => $option) {
					$default = '';
					$bg_img = '';
					$bg_array = array(
						'background_transparent'       => 'background_color',
						'pt_background_transparent'    => 'pt_background_color'
					);
					foreach($bg_array as $bg_key=>$bg_val ) {
						if( isset($slzexploore_page_options[$bg_key]) && !empty($slzexploore_page_options[$bg_key])) {
							$slzexploore_page_options[$bg_val] = $slzexploore_page_options[$bg_key];
							unset($page_options[$bg_key]);
						}
					}
					if( isset( $slzexploore_page_options[$key] ) ) {
						$option_val = $slzexploore_page_options[$key];
						if( in_array( $key, $image_id_keys ) && ! empty( $option_val ) ) {
							$attachment_image = wp_get_attachment_image_src($option_val, 'full');
							$bg_img = $attachment_image[0];
							$default = $option_val;
						} else {
							$default = $option_val;
						}
					}
					if( $option ) {
						if( is_array( $option ) ) {
							if( count( $option ) == 3 ) {
								if( $default ) {
									$slzexploore_options[$option[0]][$option[1]][$option[2]] = $default;
									if( !empty( $bg_img ) ) {
										$slzexploore_options[$option[0]]['background-image'] = $bg_img;
									}
								}
							}
							else {
								$slzexploore_options[$option[0]][$option[1]] = $default;
							}
						} else {
							$slzexploore_options[$option] = $default;
						}
					}
				}
			}
		}

		/**
		 * Page Title Background for Post Type
		 */
		$arr_posttype                        = array( 'hotel', 'tour', 'car', 'cruise' );
		$arr_posttype_not_use_featured_image = array();
		foreach ( $arr_posttype as $pt ) {
			$opt_name  = sprintf( 'slz-%s-page-title-bg-type', $pt );
			$opt_value = Slzexploore::get_option( $opt_name );
			if ( isset( $opt_value ) && ( $opt_value == false || $opt_value == '0' ) ) {
				$arr_posttype_not_use_featured_image[] = sprintf( 'slzexploore_%s', $pt );
			}
		}

		if ( ! in_array( $post_type, $arr_posttype_not_use_featured_image ) ) {
			// case others
			if ( ! empty( $featured_img ) ) {
				$slzexploore_options['slz-page-title-bg']['background-image'] = $featured_img;
			}
		}

	}
	//************************* Front Page >> ***********************
	//************************* Admin Page << ***********************
	/**
	 * Get theme options to init page options. (admin page)
	 */
	public function init_page_setting() {
		global $slzexploore_core_default_options;
		global $slzexploore_options;

		$maps = Slzexploore::get_config( 'mapping', 'options' );
		$special_keys = array( 'pt_padding_top', 'pt_padding_bottom', 'header_padding_top', 'header_padding_bottom' );
		$transparent_keys = array( 'background_transparent', 'pt_background_transparent' );
		
		foreach( $maps as $option_type => $options ) {
			foreach( $options as $key => $option) {
				$default = '';
				if( $option ) {
					if( is_array( $option ) ) {
						if(count($option) == 3) {
							if( isset( $slzexploore_options[$option[0]][$option[1]][$option[2]] ) ) {
								$default = $slzexploore_options[$option[0]][$option[1]][$option[2]];
							}
						}
						else if( isset( $slzexploore_options[$option[0]][$option[1]] ) ) {
							$default = $slzexploore_options[$option[0]][$option[1]];
						}
					} else if( isset( $slzexploore_options[$option] ) ) {
						$default = $slzexploore_options[$option];
					}
					if( in_array( $key, $special_keys ) ) {
						$default = str_replace( 'px', '', $default );
					} else if( in_array( $key, $transparent_keys ) ) {
						if( $default =='transparent' ) {
							$default = 1;
						} else {
							$default = '';
						}
					}
					$slzexploore_core_default_options[$key] = $default;
				}
			}
		}
	}
	/**
	 * Add meta box page setting to page or post type.
	 */
	public function add_page_options() {
		if( SLZEXPLOORE_CORE_IS_ACTIVE ) {
			$post_types = Slzexploore::get_config( 'page_options', 'post_types');
			foreach( $post_types as $post_type ) {
				add_meta_box( 'slz_mbox_page_setting', esc_html__('Page Setting', 'exploore'), array( SLZEXPLOORE_THEME_CLASS, '[theme.Page_Controller, meta_box_setting]' ), $post_type, 'normal', 'low' );
			}
		}
	}
	/**
	 * Save page
	 */
	public function save_page( $post_id = '' ) {
		if( empty( $post_id ) ) {
			global $post;
			$post_id = $post->ID;
			parent::save();
		}
		// save page options start
		$maps = Slzexploore::get_config( 'mapping', 'options' );
		$no_default = Slzexploore::get_config( 'mapping', 'no-default-options' );
		foreach($maps as $k=>$v) {
			$is_default = $k .'_default';
			if( ( !isset($_POST['slzexploore_page_options'][$is_default]) ) ){
				$_POST['slzexploore_page_options'][$is_default] = '';
			}
		}
		/**
		 * Fixed: Page Title Save Missing Data
		 */
		$page_options = array_keys( $maps['page_title'] );
		$post_data    = '';
		if ( isset( $_POST['slzexploore_page_options'] ) ) {
			$post_data = $_POST['slzexploore_page_options'];
			foreach ( $page_options as $opt_name ) {
				if ( ! isset( $post_data[ $opt_name ] ) ) {
					$post_data[ $opt_name ] = '';
				}
			}
		}
		update_post_meta( $post_id, 'slzexploore_page_options', $post_data );

		/**
		 * Post Type Header Content
		 */
		$posttype_header_options = array(
			'posttype_header_content_type',
			'posttype_header_revolution_slider',
			'posttype_header_gallery_ids',
			'posttype_header_height',
			'posttype_header_slider_speed',
		);
		$post_data = '';
		if( isset( $_POST['slzexploore_posttype_header_options'] ) ) {
			$post_data = $_POST['slzexploore_posttype_header_options'];
			foreach ( $posttype_header_options as $opt_name ) {
				if ( ! isset( $post_data[ $opt_name ] ) ) {
					$post_data[ $opt_name ] = '';
				}
			}
		}
		update_post_meta( $post_id, 'slzexploore_posttype_header_options', $post_data );
	}
	/**
	 * Save post
	 */
	public function save_post() {
		global $post;
		$post_id = $post->ID;
		parent::save();
		// save page options
		$this->save_page( $post_id );
		if( SLZEXPLOORE_CORE_IS_ACTIVE ) {
			do_action( 'slzexploore_core_save_feature_video', $post_id );
		}
	}
	/**
	 * Save product
	 */
	public function save_product() {
		global $post;
		$post_id = $post->ID;
		parent::save();
		// save page options
		$this->save_page( $post_id );
	}
}