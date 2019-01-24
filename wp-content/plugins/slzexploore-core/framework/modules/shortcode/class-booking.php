<?php
class Slzexploore_Core_Booking extends Slzexploore_Core_Custom_Post_Model {

	private $post_type = 'slzexploore_book';
	private $html_format;

	public function __construct() {
		$this->meta_attributes();
		$this->set_meta_attributes();
		$this->post_meta_prefix = $this->post_type . '_';
		$this->html_format = $this->set_default_options();
	}
	public function meta_attributes() {
		$meta_atts = array( 
			'status'             => '',
			'accommodation'      => '',
			'room_type'          => '',
			'number_room'        => '',
			'check_in_date'      => '',
			'check_out_date'     => '',
			'adults'             => '',
			'children'           => '',
			'infant'             => '',
			'room_price'         => '',
			'extra_price'        => '',
			'total'              => '',
			'deposit_amount'     => '',
			'future_payment'     => '',
			'description'        => '',
			'first_name'         => '',
			'last_name'          => '',
			'email'              => '',
			'company'            => '',
			'phone'              => '',
			'country'            => '',
			'address'            => '',
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
	//------------------- Post Infomations >> -------------------

	public function set_default_options( $html_options = array() ) {
		$defaults = array(
			'thumb_href_class' => ''
		);
		$html_options = array_merge( $defaults, $html_options );
		return $html_options;
	}
}