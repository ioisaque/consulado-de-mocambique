<?php
class Slzexploore_Core_Faq extends Slzexploore_Core_Custom_Post_Model {

	private $post_type = 'slzexploore_faq';
	private $post_taxonomy = 'slzexploore_faq_cat';
	private $html_format;
	
	public function __construct() {
		$this->meta_attributes();
		$this->set_meta_attributes();
		$this->post_meta_prefix = $this->post_type . '_';
		$this->taxonomy_cat = $this->post_taxonomy;
	}
	public function meta_attributes() {
		$meta_atts = array(
			'fields'    		=> esc_html__( 'Fields', 'slzexploore-core' ),
			'meta'    			=> esc_html__( 'Meta', 'slzexploore-core' ),
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
			'limit_post'            => '',
			'offset_post'         	=> '',
			'sort_by'             	=> '',
			'list_faq'      		=> '',
			'extra_class' 			=> '',
			'method'				=> 'cat',
			'list_cat'				=> ''
		);

		$atts = array_merge( $default_atts, $atts );

		if( $atts['method'] == 'cat' ) {
			$atts['post_id'] = $this->parse_cat_slug_to_post_id( 
										'slzexploore_faq_cat',
										$atts['list_cat'],
										'slzexploore_faq'
									);
		} else {
			$atts['post_id'] = $this->parse_list_to_array( 'faq', $atts['list_faq'] );
		}

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
	}
	public function reset(){
		wp_reset_postdata();
	}
	/****************/
	/**** RENDER ****/
	/****************/
	public function render_sc( $html_options = array() ) {
		$count_post = 0;
		$index = 0;
		if( $this->query->have_posts() ) {
			while ( $this->query->have_posts() ) {
				$index++;
				$this->query->the_post();
				$this->loop_index();
				// render post
				$html_format = $html_options['html_format'];
				printf( $html_format,
						$this->get_title(),
						$this->get_content(),
						$index
				);
			}
			$this->reset();
		}
	}
}