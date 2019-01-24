<?php
/**
 * Extra Items Controller
 * 
 * @since 1.0
 */
Slzexploore_Core::load_class( 'Abstract' );
class Slzexploore_Core_Extra_Item_Controller extends Slzexploore_Core_Abstract {
	public function save() {
		global $post;
		$post_id = $post->ID;
		parent::save();
		if( isset( $_POST['slzexploore_exitem_meta']) ) {
			$data_meta = $_POST['slzexploore_exitem_meta'];
			$prefix = 'slzexploore_exitem_';
			
			// set empty value for checkbox
			$chk_fields = array( 'hotel_cat', 'car_cat', 'cruise_cat', 'tour_cat' );
			foreach( $chk_fields as $field ) {
				if( ! isset( $data_meta[$prefix.$field] ) ) {
					$data_meta[$prefix.$field] = '';
				}
				elseif( is_array( $data_meta[$prefix.$field] ) ){
					$data_meta[$prefix.$field] = implode( ',', $data_meta[$prefix.$field] );
				}
			}
			if( !isset( $data_meta[$prefix.'fixed_item'] ) ){
				$data_meta[$prefix.'fixed_item'] = '';
			}
			foreach ( array( 'hotel_post', 'car_post', 'cruise_post', 'tour_post' ) as $opt_name ) {
				if ( ! isset( $data_meta[ $prefix . $opt_name ] ) ) {
					$data_meta[ $prefix . $opt_name ] = '';
				}
			}
			// format number
			$data_meta[$prefix.'price']  = Slzexploore_Core_Format::format_number( $data_meta[$prefix.'price'] );
			$data_meta[$prefix.'max_items']  = number_format_i18n( $data_meta[$prefix.'max_items'] );

			foreach( $data_meta as $key => $value ) {
				update_post_meta ( $post_id, $key, $value );
			}
		}
	}

	public function metabox_extra_item_options() {
		global $post;
		$post_id = $post->ID;
		$obj_prop = new Slzexploore_Core_Extra_Item();
		$obj_prop->loop_index();
		$data_meta = $obj_prop->post_meta;
		
		$chk_fields = array( 'hotel_cat', 'car_cat', 'cruise_cat', 'tour_cat' );
		foreach( $chk_fields as $field ) {
			if( !empty( $data_meta[$field] ) ) {
				$data_meta[$field] = explode( ',', $data_meta[$field] );
			}
		}
		
		$args              = array( 'hide_empty' => false, 'orderby' => 'term_id' );
		$hotel_cat         = Slzexploore_Core_Com::get_tax_options_id2name( 'slzexploore_hotel_cat', array(), $args );
		$tour_cat          = Slzexploore_Core_Com::get_tax_options_id2name( 'slzexploore_tour_cat', array(), $args );
		$car_cat           = Slzexploore_Core_Com::get_tax_options_id2name( 'slzexploore_car_cat', array(), $args );
		$cruise_cat        = Slzexploore_Core_Com::get_tax_options_id2name( 'slzexploore_cruise_cat', array(), $args );
		$hotel_post        = Slzexploore_Core_Com::get_post_id2title( array( 'post_type' => 'slzexploore_hotel' ), array(), false );
		$car_post          = Slzexploore_Core_Com::get_post_id2title( array( 'post_type' => 'slzexploore_car' ), array(), false );
		$cruise_post       = Slzexploore_Core_Com::get_post_id2title( array( 'post_type' => 'slzexploore_cruise' ), array(), false );
		$tour_post         = Slzexploore_Core_Com::get_post_id2title( array( 'post_type' => 'slzexploore_tour' ), array(), false );
		$params            = array(
								'hotel_cat'         => $hotel_cat,
								'tour_cat'          => $tour_cat,
								'car_cat'           => $car_cat,
								'cruise_cat'        => $cruise_cat,
								'hotel_post'        => $hotel_post,
								'car_post'          => $car_post,
								'cruise_post'       => $cruise_post,
								'tour_post'         => $tour_post,
							);
		
		$this->render( 'extra_item', array( 'data_meta' => $data_meta, 'params' => $params ) );
	}

}