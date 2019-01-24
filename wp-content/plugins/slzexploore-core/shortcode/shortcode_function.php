<?php
Slzexploore_Core::load_class( 'shortcode.Shortcode_Controller' );
// Add custom type
if( function_exists("vc_add_shortcode_param") ) {
	/*
	 * Dropdown multiple
	 * 
	 * Using: array(
				"type" => "slz_dropdownmultiple",
				"class" => "",
				"heading" => esc_html__("Services", 'slzexploore-core'),
				"param_name" => "services",
				"value" => $categories
		),
	 */
	vc_add_shortcode_param( 'slz_dropdownmultiple' , 'slzexploore_core_dropdownmultiple_settings_field' );
	function slzexploore_core_dropdownmultiple_settings_field( $settings, $value ) {
		$dependency = vc_generate_dependencies_attributes( $settings );
		$value = explode( ",", $value );
		$output = '<select name="' . esc_attr( $settings['param_name'] ).'" class="wpb_vc_param_value wpb-input wpb-select ' .
					esc_attr( $settings['param_name'] ) . ' ' .
					esc_attr( $settings['type'] ) . '"' . $dependency . ' multiple>';
		foreach( $settings['value'] as $text_val => $val ) {
			if( is_numeric($text_val) && is_string($val) || is_numeric($text_val) && is_numeric($val) ) {
				$text_val = $val;
			}
			$selected = '';
			if ( in_array( $val, $value ) ) {
				$selected = ' selected="selected" ';
			}
			$output .= '<option class="' . $val. '" value="' . $val . '"' . $selected . '>' . $text_val . '</option>';
		}
		$output .= '</select>';
		return $output;
	}
	// date time picker
	vc_add_shortcode_param( 'slz_datetime_picker' , 'slzexploore_core_datetime_picker_field' , SLZEXPLOORE_CORE_ASSET_URI . '/js/slzexploore-core-datetimepicker.js');
	
	function slzexploore_core_datetime_picker_field( $settings, $value ) {
		$dependency = vc_generate_dependencies_attributes( $settings );
		$output = '<input name="' . $settings['param_name'] . '" ';
		$output .= 'class="wpb_vc_param_value wpb-textinput ' . $settings['param_name'] . ' ' . $settings['type'].'_field" ';
		$output .= 'type="text" value="' . $value . '" ' . $dependency . ' id="datetimepicker"/>';
		return $output;
	}
}

if( SLZEXPLOORE_CORE_VC_ACTIVE ) {
	// Map Shortcodes
	// =============================================================================
	if( ! function_exists( 'slzexploore_core_vc_map_shortcodes' ) ) {
		function slzexploore_core_vc_map_shortcodes() {
			$list_shortcodes = Slzexploore_Core::get_config( 'shortcode' );
			foreach( $list_shortcodes as $shortcode => $func ) {
				$sc_file = SLZEXPLOORE_CORE_SHORTCODE_DIR . '/inc/' . $func . '.php';
				if( file_exists( $sc_file ) ) {
					require_once( $sc_file );
				}
			}
		}
	}
	add_action('vc_before_init', 'slzexploore_core_vc_map_shortcodes');
}

//Add Shortcode
// =============================================================================
if( ! function_exists( 'slzexploore_core_add_shortcodes' ) ) {
	function slzexploore_core_add_shortcodes() {
		$list_shortcodes = Slzexploore_Core::get_config( 'shortcode' );
		foreach( $list_shortcodes as $shortcode => $func ) {
			if ( ! SLZEXPLOORE_CORE_WOOCOMMERCE_ACTIVE && in_array( $func, array( 'product_tab', 'product_slide', 'product_category_tab', 'product_countdown' ) ) ) {
				continue;
			}
			$objShortcode = new Slzexploore_Core_Shortcode_Controller();
			if ( method_exists( $objShortcode, $func ) ) {
				add_shortcode( $shortcode, array( 'Slzexploore_Core', '[shortcode.Shortcode_Controller, ' . $func . ']' ) );
			}
		}
	}
}
add_action('init', 'slzexploore_core_add_shortcodes');
