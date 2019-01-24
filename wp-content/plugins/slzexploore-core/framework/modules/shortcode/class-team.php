<?php
class Slzexploore_Core_Team extends Slzexploore_Core_Custom_Post_Model {

	private $post_type = 'slzexploore_team';
	private $post_taxonomy = 'slzexploore_team_cat';
	private $html_format;

	public function __construct() {
		$this->meta_attributes();
		$this->set_meta_attributes();
		$this->post_meta_prefix = $this->post_type . '_';
		$this->taxonomy_cat = $this->post_taxonomy;
		$this->html_format = $this->set_default_options();
	}
	public function meta_attributes() {
		$meta_atts = array(
			'thumbnail'        => esc_html__('Thumbnail Image', 'slzexploore-core'),
			'description'      => esc_html__('Description', 'slzexploore-core'),
			'position'         => esc_html__('Position', 'slzexploore-core'),
			'address'          => esc_html__('Address', 'slzexploore-core'),
			'phone'            => esc_html__('Phone', 'slzexploore-core'),
			'email'            => esc_html__('Email', 'slzexploore-core'),
			'skype'            => esc_html__('Skype', 'slzexploore-core'),
		);
		$this->post_meta_atts = array_merge($meta_atts,Slzexploore_Core::get_params( 'teammbox-social'));
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
			'layout'               => '',
			'offset_post'          => '',
			'columns'              => '',
			'limit_post'           => '',
			'post__not_in'         => '',
			'sort_by'              => '',
			'post_id'              => '',
			'post__in'             => '',
			'color'                => '',
			'content_display'      => '',
			'category_slug'        => '',
			'number'               => '',
			'btn_content'          => ''
		);
		$atts = array_merge( $default_atts, $atts );
		$atts['offset_post'] = absint( $atts['offset_post'] );

		if( isset( $atts['team_slug'] ) && !empty( $atts['team_slug'] ) ) {
			$atts['post_slug'] = $atts['team_slug'];
		}

		if ( isset($atts['post__not_in']) ) {
			$post__not_in = explode(',', $atts['post__not_in']);
			if ( is_array($post__not_in) ) {
				$atts['post__not_in'] = $post__not_in;
			}
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
	public function setting( $query_args ){
		if( !isset( $this->attributes['uniq_id'] ) ) {
			$this->attributes['uniq_id'] = $this->post_type . '-' .Slzexploore_Core::make_id();
		}
		// query
		$this->query = $this->get_query( $query_args, $this->attributes );
		$this->post_count = 0;
		if( $this->query->have_posts() ) {
			$this->post_count = $this->query->post_count;
		}
		// image size
		$this->get_thumb_size();
	}
	public function reset(){
		wp_reset_postdata();
	}
	/****************/
	/**** RENDER ****/
	/****************/
	public function render_carousel( $html_options = array() ) {
		$this->html_format = $this->set_default_options( $html_options );
		while ( $this->query->have_posts() ) {
			$this->query->the_post();
			$this->loop_index();

			$html_options = $this->html_format;
				printf( $html_options['html_format'],
					$this->get_featured_image( $html_options ),
					$this->get_social(),
					$this->get_title( $html_options ),
					$this->get_info_position(),
					$this->html_format['meta_open'],
					$this->html_format['meta_close']
			);
		}
		$this->reset();
	}
	public function render_single( $html_options = array() ) {
		$this->html_format = $this->set_default_options( $html_options );
		while ( $this->query->have_posts() ) {
			$this->query->the_post();
			$this->loop_index();
			$html_options = $this->html_format;
				printf( $html_options['html_format'],
					$this->get_thumbnail_image(),
					$this->get_title( $html_options ),
					$this->get_info_position(),
					$this->get_info_address(),
					$this->get_info_phone(),
					$this->get_info_mail(),
					$this->get_info_skype()
			);
		}
		$this->reset();
	}
	public function render_single2( $html_options1 = array(), $html_options2 = array() ) {
		$this->html_format = $this->set_default_options( $html_options1 );
		while ( $this->query->have_posts() ) {
			$this->query->the_post();
			$this->loop_index();
			$html_options1 = $this->html_format;
				printf( $html_options1['html_format'],
					$this->get_thumbnail_image(),
					$this->get_title( $html_options1 ),
					$this->get_info_position()
			);
			$arr = $this->data_all();
			$col = $this->custom_info();
			$mail = '';
			echo '<div class="row group-list contact-list">';
			$this->html_format = $this->set_default_options( $html_options2 );
			foreach ($arr as $key => $value) {
				if ( !empty($value[0]) ) {
					echo '<div class="col-sm-'.esc_attr($col).' col-xs-'.esc_attr($col).' col-contact">';
					$html_options2 = $this->html_format;
						printf( $html_options2['html_format'],
							$key,
							$value[0],
							$value[1]
						);

					echo '</div>';
				}
			}
			echo '</div>';
		}
		$this->reset();
	}

	public function render_list( $html_options = array() ) {
		$this->html_format = $this->set_default_options( $html_options );
		$count = $row_count = 0;
		$columns = absint($this->attributes['column']);
		while ( $this->query->have_posts() ) {
			$this->query->the_post();
			$this->loop_index();
			$count ++;
			if( isset( $html_options['open_row'] ) && $columns > 1 && $count > $columns ) {
				echo ( $html_options['close_row'] . $html_options['open_row'] );
				$count = 1;
			}
			$html_options = $this->html_format;
				printf( $html_options['html_format'],
					$this->get_thumbnail_image(),
					$this->get_title( $html_options ),
					$this->get_info_position(),
					$this->get_info_address(),
					$this->get_info_phone(),
					$this->get_info_mail(),
					$this->html_format['meta_open'],
					$this->html_format['meta_close']
			);
		}
		$this->reset();
	}

	/*******************/
	/* FUNCTION CUSTOM */
	/*******************/

	public function get_thumbnail_image(){
		$thumb_id = $this->post_meta['thumbnail'];
		if( !empty( $thumb_id ) ) {
			$format = '<a href="%2$s">%1$s</a>';
			$thumb_class = Slzexploore_Core::get_value( $this->html_format, 'thumb_class', 'img-responsive' );
			$thumb_size = $this->attributes['thumb-size']['large'];
			$helper = new Slzexploore_Core_Helper();
			$helper->regenerate_attachment_sizes($thumb_id, $thumb_size);
			$thumb_img = wp_get_attachment_image( $thumb_id, $thumb_size, false, array('class' => $thumb_class ) );
			$output = sprintf( $format, $thumb_img, $this->permalink );
			return $output;
		}
		else {
			return $this->get_featured_image( $this->html_format );
		}
	}
	public function custom_info() {
		$count = 0;
		$info = '';
		$data = array(
			'skype' => $this->post_meta['skype'],
			'phone' => $this->post_meta['phone'],
			'email' => $this->post_meta['email'],
		);
		foreach ($data as $key => $value) {
			if ( !empty($value) ) {
				$count++;
			}
		}
		if ($count == 1) {
			$info = '12';
		}elseif ( $count == 2 ) {
			$info = '6';
		}elseif ( $count == 3 ) {
			$info = '4';
		}
		return $info;
	}
	public function data_all(){
		$array = array(
			'desktop'       => array( '0' => $this->post_meta['skype'], '1' => 'Contact Skype' ),
			'phone'         => array( '0' => $this->post_meta['phone'], '1' => 'Phone' ),
			'envelope-o'    => array( '0' => $this->post_meta['email'], '1' => 'Email' ),
		);
		return $array;
	}
	public function get_info_position() {
		$out = $this->post_meta['position'];
		if( empty( $out ) ) {
			return '';
		}
		return $out;
	}
	public function get_info_skype() {
		$out = $this->post_meta['skype'];
		if( empty( $out ) ) {
			return '';
		}
		return $out;
	}
	public function get_info_address() {
		$format = $this->html_format['address_format'];
		$out = $this->post_meta['address'];
		if( empty( $out ) ) {
			return '';
		}
		$out = sprintf($format,esc_html($out));
		return $out;
	}
	public function get_info_phone() {
		$format = $this->html_format['phone_format'];
		$out = $this->post_meta['phone'];
		if( empty( $out ) ) {
			return '';
		}
		$out = sprintf( $format, esc_html($out) );
		return $out;
	}
	public function get_info_mail(){
		$format = $this->html_format['email_format'];
		$out = $this->post_meta['email'];
		if( empty( $out ) ) {
			return '';
		}
		$out = sprintf( $format, esc_html($out) );
		return $out;
	}

	public function get_current_category() {
		$term = $this->get_current_taxonomy( $this->post_type . '_cat');
		$format = $this->html_format['category_format'];
		
		$out = '';
		if( $term ) {
			$out = sprintf( $format, esc_html( $term['name'] ) );
		}
		return $out;
	}
	public function get_social() {
		$out ='';
		$format = $this->html_format['social_format'];
		$social_group = Slzexploore_Core::get_params( 'teammbox-social');
		foreach( $social_group as $social => $social_text ){
			$item = $this->post_meta[$social];
			if( !empty($item) ) {
				$href = $this->post_meta[$social];
				$out.= sprintf( $format, esc_attr( $social ), esc_url( $href ) );
			}
		}
		return $out;
	}

	public function set_default_options( $html_options = array() ) {
		$defaults = array(
			'title_format'             => '<a href="%2$s" class="title">%1$s</a>',
			'category_format'          => '<p class="job">%1$s</p>',
			'image_format'             => '<a class="%3$s" href="%2$s">%1$s</a>',
			'description_format'       => '%1$s',
			'position_format'          => '%1$s',
			'address_format'           => '<li class="main-list"><i class="icons fa fa-map-marker"></i><span class="link">%1$s</span></li>',
			'phone_format'             => '<li class="main-list"><i class="icons fa fa-phone"></i><span class="link">%1$s</span></li>',
			'email_format'             => '<li class="main-list"><i class="icons fa fa-envelope-o"></i><span class="link">%1$s</span></li>',
			'skype_format'             => '%1$s',
			'social_format'            => '<li><a href="%2$s" class="social-expert"><i class="expert-icon fa fa-%1$s"></i></a></li>',
			'meta_open'                => '<ul class="list-unstyled">',
			'meta_close'               => '</ul>',
			'thumb_href_class'         => ''
		);
		$html_options = array_merge( $defaults, $html_options );
		return $html_options;
	}
	public function get_thumb_size() {
		$params = Slzexploore_Core::get_params( 'block-image-size', $this->attributes['layout'] );
		$this->attributes['thumb-size'] = Slzexploore_Core_Util::get_thumb_size( $params, $this->attributes );
	}
}