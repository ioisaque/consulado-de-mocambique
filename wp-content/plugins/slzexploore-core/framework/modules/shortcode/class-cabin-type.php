<?php
class Slzexploore_Core_Cabin_Type extends Slzexploore_Core_Custom_Post_Model {

	private $post_type = 'slzexploore_cabin';
	private $html_format;

	public function __construct() {
		$this->meta_attributes();
		$this->set_meta_attributes();
		$this->post_meta_prefix = $this->post_type . '_';
		$this->html_format = $this->set_default_options();
		$this->uniq_id = 'block-' . Slzexploore_Core::make_id();
		$this->taxonomy_cat = 'slzexploore_cabin_cat';
	}
	public function meta_attributes() {
		$meta_atts = array( 
			'display_title'        => '',
			'cruise_id'            => '',
			'max_adults'           => '',
			'max_children'         => '',
			'number'               => '',
			'price'                => '',
			'price_infant'         => '',
			'is_price_person'      => '',
			'price_text'           => ''
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
			'layout'               => 'room',
			'limit_post'           => '-1',
			'offset_post'          => '0',
		);
		$atts = array_merge( $default_atts, $atts );
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
			$this->attributes['uniq_id'] = $this->post_type . '-' . Slzexploore_Core::make_id();
		}
		
		// query
		$this->query = $this->get_query( $query_args, $this->attributes );
		$this->post_count = 0;
		if( $this->query->have_posts() ) {
			$this->post_count = $this->query->post_count;
		}
		$this->get_thumb_size();
	}
	public function reset(){
		wp_reset_postdata();
	}
	//-------------------->> Render Html << -------------------------
	/**
	 * Render html by list.
	 *
	 * @param array $html_options
	 * @format: 1$ - img, 2$ - title, 3$ - discount, 4$ - meta, 5$ - price, 6$ - excerpt, 7$ - button, 8$ - responsive
	 */
	public function render_list( $html_options = array() ) {
		$this->set_default_options( $html_options );
		$count = 0;
		while ( $this->query->have_posts() ) {
			$this->query->the_post();
			$this->loop_index();
			$count ++;
			printf( $html_options['html_format'],
					$this->get_title( $html_options ),
					$this->get_room_slider(),
					$this->get_price(),
					$this->get_content( $html_options ),
					$this->get_button( $count )
			);
		}
		$this->reset();
	}
	
	//--------------------<< Render Html >> -------------------------
	//------------------- >> Post Infomations << --------------------
	
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
			$out = sprintf($format, $price);
		}
		return $out;
	}
	
	//------------------- << Post Infomations >> -------------------

	public function set_default_options( &$html_options = array() ) {
		$defaults = array(
			'sign_price_format'   => '<sup>%1$s</sup>',
		);
		$html_options = array_merge( $defaults, $html_options );
		$this->html_format = $html_options;
		return $html_options;
	}
	public function get_thumb_size() {
		$params = Slzexploore_Core::get_params( 'block-image-size', $this->attributes['layout'] );
		$this->attributes['thumb-size'] = Slzexploore_Core_Util::get_thumb_size( $params, $this->attributes );
	}
}