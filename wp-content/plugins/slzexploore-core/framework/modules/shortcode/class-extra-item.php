<?php
class Slzexploore_Core_Extra_Item extends Slzexploore_Core_Custom_Post_Model {

	private $post_type = 'slzexploore_exitem';
	private $html_format;

	public function __construct() {
		$this->meta_attributes();
		$this->set_meta_attributes();
		$this->post_meta_prefix = $this->post_type . '_';
		$this->html_format = $this->set_default_options();
	}
	public function meta_attributes() {
		$meta_atts = array( 
			'price'               => '',
			'fixed_item'          => '',
			'max_items'           => '',
			'is_price_day'        => '',
			'is_price_person'     => '',
			'hotel_cat'           => '',
			'tour_cat'            => '',
			'car_cat'             => '',
			'cruise_cat'          => '',
			'hotel_post'          => '',
			'car_post'            => '',
			'cruise_post'         => '',
			'tour_post'           => '',
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
	public function get_column_price() {
		$out = '';
		$format = '%1$s';
		$price = Slzexploore_Core_Format::format_number( $this->post_meta['price'] );
		$sign = sprintf( $this->html_format['sign_price_format'],
				esc_html( Slzexploore_Core::get_theme_option('slz-currency-sign') ) );
		if( !empty( $price ) ) {
			$decimal = Slzexploore_Core::get_theme_option('slz-currency-decimal');
			$price = '<span class="number">' . number_format_i18n( $price, $decimal ) .'</span>';
			$sign_position = Slzexploore_Core::get_theme_option('slz-symbol-currency-position');
			if( $sign_position == 'before' ) {
				$price = $sign .$price;
			} else {
				$price = $price .$sign;
			}
			$out = sprintf($format, $price );
		}
		return $out;
	}
	//------------------- Post Infomations >> -------------------

	public function set_default_options( $html_options = array() ) {
		$defaults = array(
			'sign_price_format'   => '<sup>%1$s</sup>',
			'thumb_href_class'    => ''
		);
		$html_options = array_merge( $defaults, $html_options );
		return $html_options;
	}
}