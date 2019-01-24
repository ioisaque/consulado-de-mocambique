<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
Slzexploore_Core::load_class('Abstract');

class Slzexploore_Core_Taxonomies_Controller extends Slzexploore_Core_Abstract {
	private $custom_taxonomies;
	private $taxonomy_opt = SLZEXPLOORE_CORE_TAXONOMY_CUS;

	public function __construct() {
		// Add form
		$this->custom_taxonomies = Slzexploore_Core::get_config('custom_taxonomies');
		if( is_array( $this->custom_taxonomies ) ) {
			foreach($this->custom_taxonomies as $taxonomy ){
				add_action( $taxonomy . '_add_form_fields', array( $this, 'add_taxonomy_fields' ) );
				add_action( $taxonomy . '_edit_form_fields', array( $this, 'edit_taxonomy_fields' ), 10 );
				add_filter( 'manage_edit-' . $taxonomy . '_columns', array( $this, 'taxonomy_columns' ) );
				add_filter( 'manage_' . $taxonomy . '_custom_column', array( $this, 'display_taxonomy_column' ), 10, 3 );
			}
		}
		add_action( 'created_term', array( $this, 'save_taxonomy_fields' ), 10, 3 );
		add_action( 'edit_term', array( $this, 'save_taxonomy_fields' ), 10, 3 );
	}
	/**
	 * Custom column added to taxonomy admin.
	 *
	 */
	public function taxonomy_columns( $columns ) {
		$new_columns = array();
		$new_columns['cb'] = $columns['cb'];
		$new_columns['color'] = esc_html__('Color', 'slzexploore-core');
	
		unset( $columns['cb'] );
	
		return array_merge( $new_columns, $columns );
	}
	
	/**
	 * Display custom fields.
	 *
	 */
	public function display_taxonomy_column( $out_columns, $column_name, $term_id, $taxonomy = '' ) {
		$option = $this->taxonomy_opt . $term_id;
		$data_meta = get_option( $option, array() );
		$color = Slzexploore_Core::get_value( $data_meta, 'color' );
		$bg_color = Slzexploore_Core::get_value( $data_meta, 'background_color' );
		$current_screen = get_current_screen();
		$name = '';
		if( $current_screen ) {
			$name = get_term_field('name', $term_id, get_current_screen()->taxonomy );
		}
		$custom_css = '';
		if( empty( $color ) ) {
			$color = '#ffffff';
		}
		$col_unique = 'col-' . $term_id ;
		$custom_css .= '.%1$s.note {color: %2$s;}' ."\n";
		if( empty( $bg_color ) ) {
			$bg_color = '#2aacff';
		}
		if( !empty( $bg_color ) ) {
			$custom_css .= '.%1$s.note {background-color: %3$s;}' ."\n";
		}
		if ( $column_name == 'color' ) {
			$out_columns = sprintf( '<div class="%s note">%s</div>', $col_unique , $name );
			if( !empty( $custom_css ) ) {
				$custom_css = sprintf( $custom_css, $col_unique, $color, $bg_color );
				apply_filters('slzexploore_core_add_inline_style', $custom_css);
			}
		}
		return $out_columns;
	}
	/**
	 * Add custom fields.
	 */
	public function add_taxonomy_fields() {
		$data_meta = array();
		$params = array();
		$this->render( 'add-taxonomy-fields',array(
					'params' => $params,
					'data_meta' => $data_meta,
					'taxonomy_opt' => $this->taxonomy_opt
				)
		);
	}
	
	/**
	 * Edit custom fields.
	 *
	 * @param mixed $term Term (category) being edited
	 */
	public function edit_taxonomy_fields( $term ) {
		$data_meta = array();
		$option = $this->taxonomy_opt . $term->term_id;
		$data_meta = get_option( $option, array() );
		
		$params = array();
		$this->render( 'edit-taxonomy-fields', array(
			'params' => $params,
			'data_meta' => $data_meta,
			'taxonomy_opt' => $this->taxonomy_opt
		));
	}

	/**
	 * save_category_fields function.
	 *
	 * @param mixed $term_id Term ID being saved
	 */
	public function save_taxonomy_fields( $term_id, $tt_id = '', $taxonomy = '' ) {
		if ( isset( $_POST[$this->taxonomy_opt] ) && in_array( $taxonomy, $this->custom_taxonomies ) ) {
			$option =  $this->taxonomy_opt . $term_id;
			update_option($option, $_POST[$this->taxonomy_opt]);
		}
	}
}
new Slzexploore_Core_Taxonomies_Controller();