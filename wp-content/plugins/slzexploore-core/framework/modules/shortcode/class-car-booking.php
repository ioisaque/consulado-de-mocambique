<?php
class Slzexploore_Core_Car_Booking extends Slzexploore_Core_Custom_Post_Model {

	private $post_type = 'slzexploore_cbook';
	private $html_format;

	public function __construct() {
		$this->meta_attributes();
		$this->set_meta_attributes();
		$this->post_meta_prefix = $this->post_type . '_';
		$this->html_format = $this->set_default_options();
	}
	public function meta_attributes() {
		$meta_atts = array( 
			'car_id'             => '',
			'status'             => '',
			'date_from'          => '',
			'date_to'            => '',
			'number'             => '',
			'drop_off_location'  => '',
			'price'              => '',
			'extra_price'        => '',
			'total_price'        => '',
			'deposit_amount'     => '',
			'future_payment'     => '',
			'description'        => '',
			'first_name'         => '',
			'last_name'          => '',
			'email'              => '',
			'company'            => '',
			'phone'              => '',
			'address'            => '',
			'country'            => '',
			'city'               => '',
			'postcode'           => '',
			'customer_ip'        => '',
			'customer_des'       => '',
			'order'              => '',
			'sku'                => '',
			'extra_item'         => ''
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
			'limit_post'           => '-1',
			'offset_post'          => '0'
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
	}
	public function reset(){
		wp_reset_postdata();
	}
	public function get_car_id(){
		$car_id = array();
		while ( $this->query->have_posts() ) {
			$this->query->the_post();
			$this->loop_index();
			$post_meta = $this->post_meta['car_id'];
			if( !empty( $post_meta ) && !in_array( $post_meta, $car_id ) ) {
				$car_id[] = $post_meta;
			}
		}
		$this->reset();
		return $car_id;
	}
	//------------------- Post Infomations >> -------------------

	public function set_default_options( $html_options = array() ) {
		$defaults = array(
			'thumb_href_class' => ''
		);
		$html_options = array_merge( $defaults, $html_options );
		return $html_options;
	}
}