<?php
Slzexploore_Core::load_class( 'models.Custom_Post_Model' );
class Slzexploore_Core_Testimonial extends Slzexploore_Core_Custom_Post_Model {

	private $post_type = 'slzexploore_testi';
	private $post_taxonomy = 'slzexploore_testi_cat';
	
	public function __construct() {
		$this->meta_attributes();
		$this->set_meta_attributes();
		$this->post_meta_prefix = $this->post_type . '_';
	}
	public function meta_attributes() {
		$meta_atts = array(
			'thumbnail'     => '',
			'position'      => '',
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
			'layout'               => 'testimonial',
			'limit_post'           => '',
			'offset_post'          => '0',
			'sort_by'              => '',
			'paged'                => '',
			'cur_limit'            => '',
			'content_display'      => '',
			'columns'              => '',
		);
		$atts = array_merge( $default_atts, $atts );
		$atts['taxonomy_slug'] = $this->post_taxonomy;
		$atts['offset_post'] = absint( $atts['offset_post'] );
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
	public function render_sc( $html_options = array() ) {
		$this->html_format = $this->set_default_options( $html_options );
		while ( $this->query->have_posts() ) {
			$this->query->the_post();
			$this->loop_index();
			$data = $this->get_content( $html_options );
			if ($data != '') {
				$data = '"'.$data.'"';
			}
			$html_options = $this->html_format;
				printf( $html_options['html_format'],
					$data,
					$this->get_featured_image( $html_options ),
					$this->get_title( $html_options ),
					$this->get_position(),
					$this->get_post_class(),
					$this->get_thumbnail_image()
			);
		}
		$this->reset();
	}
	public function get_position() {
		$format = $this->html_format['position_format'];
		$val = $this->post_meta['position'];
	
		$out = '';
		if( !empty($val) ) {
			$out = sprintf( $format, esc_html( $val ) );
		}
		return $out;
	}
	public function set_default_options( $html_options = array() ) {
		$defaults = array(
			'title_format'       => '<p class="name">%1$s</p>',
			'position_format'    =>'<p class="address">%1$s</p>',
		);
		$html_options = array_merge( $defaults, $html_options );
		return $html_options;
	}
	public function get_thumb_size() {
		$params = Slzexploore_Core::get_params( 'block-image-size', $this->attributes['layout'] );
		$this->attributes['thumb-size'] = Slzexploore_Core_Util::get_thumb_size( $params, $this->attributes );
	}
	public function get_thumbnail_image(){
		$thumb_id = $this->post_meta['thumbnail'];
		if( !empty( $thumb_id ) ) {
			$format = '%1$s';
			$thumb_class = Slzexploore_Core::get_value( $this->html_format, 'thumb_class', 'img-responsive' );
			$thumb_size = $this->attributes['thumb-size']['small'];
			$helper = new Slzexploore_Core_Helper();
			$helper->regenerate_attachment_sizes($thumb_id, $thumb_size);
			$thumb_img = wp_get_attachment_image( $thumb_id, $thumb_size, false, array('class' => $thumb_class ) );
			$output = sprintf( $format, $thumb_img, $this->permalink );
			return $output;
		}
		else {
			return $this->get_featured_image( $this->html_format, 'small' );
		}
	}
}