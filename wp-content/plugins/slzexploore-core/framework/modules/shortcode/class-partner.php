<?php
class Slzexploore_Core_Partner extends Slzexploore_Core_Custom_Post_Model {

	private $post_type = 'slzexploore_partner';

	public function __construct() {
		$this->meta_attributes();
		$this->set_meta_attributes();
		$this->post_meta_prefix = $this->post_type . '_';
		$this->taxonomy_cat = 'slzexploore_partner_cat';
	}
	public function meta_attributes() {
		$meta_atts = array( 
			'url'             => esc_html__( 'Url', 'slzexploore-core' ),
			'gallery_ids'     => ''
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
			'layout'			=> 'partner',
			'offset_post'		=> '0',
			'limit_post'		=> '-1',
			'number'			=> '',
			'category_slug'		=> '',
			'sort_by'			=> 'post__in'
		);
		$atts = array_merge( $default_atts, $atts );
		$atts['offset_post'] = absint( $atts['offset_post'] );
		if( isset( $atts['method'] ) ) {
			if( $atts['method'] == 'cat' ){
				$atts['post_id'] = $this->parse_cat_slug_to_post_id( 
											'slzexploore_partner_cat',
											$atts['category'],
											'slzexploore_partner'
										);
			}
			else {
				$atts['post_id'] = $this->parse_list_to_array( 'partner', $atts['list_partner'] );
			}
		}
		if( empty( $atts['post_id'] ) && !empty( $atts['number'] ) ){
			$atts['limit_post'] = absint( $atts['number'] );
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
			$this->attributes['uniq_id'] = $this->post_type . '_' .Slzexploore_Core::make_id();
		}
		
		// query
		$this->query = $this->get_query( $query_args, $this->attributes );
		$this->post_count = 0;
		if( $this->query->have_posts() ) {
			$this->post_count = $this->query->post_count;
		}
		// image size
		$this->attributes['thumb-size']['large'] = '';
	}
	public function reset(){
		wp_reset_postdata();
	}
	/*-------------------- >> Render Html << -------------------------*/
	/**
	 * Render html.
	 *
	 * @param array $html_options
	 * Format: 1$ - url mete, 2$ - feature image
	 */
	public function render_partner_1row_style1( $html_options = array() ) {
		while ( $this->query->have_posts() ) {
			$this->query->the_post();
			$this->loop_index();
			if( $this->has_thumbnail ) {
				printf( $html_options['html_format'],
						esc_url( $this->get_url() ),
						get_the_title(),
						$this->get_featured_image( $html_options )
					);
			}
		}
		$this->reset();
	}
	public function render_partner_2rows_style1( $html_options = array() ) {
		$i = 1;
		while ( $this->query->have_posts() ) {
			$this->query->the_post();
			$this->loop_index();
			if( $this->has_thumbnail ) {
				if ( $i > 2 ) {
					echo '</div><div class="content-banner">';
					$i = 1;
				}
				printf( $html_options['html_format'],
						esc_url( $this->get_url() ),
						get_the_title(),
						$this->get_featured_image( $html_options )
					);
				$i++;
			}
		}
		$this->reset();
	}
	public function render_partner_style2( $html_options = array() ) {
		while ( $this->query->have_posts() ) {
			$this->query->the_post();
			$this->loop_index();
			if( $this->has_thumbnail ) {
				printf( $html_options['html_format'],
						esc_url( $this->get_url() ),
						$this->get_featured_image( $html_options )
					);
			}
		}
		$this->reset();
	}
	public function get_url() {
		$url = $this->post_meta['url'];
		if( empty( $url ) ) {
			return '';
		}
 		return $url;
	}
}