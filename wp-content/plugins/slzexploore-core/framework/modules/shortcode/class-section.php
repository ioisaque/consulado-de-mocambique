<?php 
if ( class_exists( 'WPBakeryShortCodesContainer' ) ) { 
	require_once vc_path_dir( 'SHORTCODES_DIR', 'vc-column.php' );

	class WPBakeryShortCode_slzexploore_core_tabs_sc extends WPBakeryShortCode {

		static $filter_added = false;

			protected $controls_css_settings = 'out-tc vc_controls-content-widget';
			protected $controls_list = array('add', 'edit', 'clone', 'delete');
		public function __construct( $settings ) {
			parent::__construct( $settings );
			if ( ! self::$filter_added ) {
				$this->addFilter( 'vc_inline_template_content', 'setCustomTabId' );
				self::$filter_added = true;
			}
		}

		public function contentAdmin( $atts, $content = null ) {
			$width = $custom_markup = '';
			$shortcode_attributes = array( 'width' => '1/1' );
			foreach ( $this->settings['params'] as $param ) {
				if ( $param['param_name'] != 'content' ) {
					if ( isset( $param['value'] ) && is_string( $param['value'] ) ) {
						$shortcode_attributes[$param['param_name']] = $param['value'];
					} elseif ( isset( $param['value'] ) ) {
						$shortcode_attributes[$param['param_name']] = $param['value'];
					}
				} else if ( $param['param_name'] == 'content' && $content == NULL ) {
						$content = $param['value'];
					}
			}
			extract( shortcode_atts(
					$shortcode_attributes
					, $atts ) );

			// Extract tab titles

			preg_match_all( '/slzexploore_core_tab_sc title="([^\"]+)"(\stab_id\=\"([^\"]+)\"){0,1}/i', $content, $matches, PREG_OFFSET_CAPTURE );
			$output = '';
			$tab_titles = array();

			if ( isset( $matches[0] ) ) {
				$tab_titles = $matches[0];
			}
			$tmp = '';
			if ( count( $tab_titles ) ) {
				$tmp .= '<ul class="clearfix tabs_controls">';
				foreach ( $tab_titles as $tab ) {
					preg_match( '/title="([^\"]+)"(\stab_id\=\"([^\"]+)\"){0,1}/i', $tab[0], $tab_matches, PREG_OFFSET_CAPTURE );
					if ( isset( $tab_matches[1][0] ) ) {
						$tmp .= '<li><a href="#tab-' . ( isset( $tab_matches[3][0] ) ? $tab_matches[3][0] : sanitize_title( $tab_matches[1][0] ) ) . '">' . $tab_matches[1][0] . '</a></li>';

					}
				}
				$tmp .= '</ul>' . "\n";
			} else {
				$output .= do_shortcode( $content );
			}

			$elem = $this->getElementHolder( $width );

			$iner = '';
			foreach ( $this->settings['params'] as $param ) {
				$custom_markup = '';
				$param_value = isset( $$param['param_name'] ) ? $$param['param_name'] : '';
				if ( is_array( $param_value ) ) {
					// Get first element from the array
					reset( $param_value );
					$first_key = key( $param_value );
					$param_value = $param_value[$first_key];
				}
				$iner .= $this->singleParamHtmlHolder( $param, $param_value );
			} 

			if ( isset( $this->settings["custom_markup"] ) && $this->settings["custom_markup"] != '' ) {
				if ( $content != '' ) {
					$custom_markup = str_ireplace( "%content%", $tmp . $content, $this->settings["custom_markup"] );
				} else if ( $content == '' && isset( $this->settings["default_content_in_template"] ) && $this->settings["default_content_in_template"] != '' ) {
						$custom_markup = str_ireplace( "%content%", $this->settings["default_content_in_template"], $this->settings["custom_markup"] );
					} else {
					$custom_markup = str_ireplace( "%content%", '', $this->settings["custom_markup"] );
				}
				$iner .= do_shortcode( $custom_markup );
			}
			$elem = str_ireplace( '%wpb_element_content%', $iner, $elem );
			$output = $elem;

			return $output;
		}

		public function getTabTemplate() {
			return '<div class="wpb_template">' . do_shortcode( '[slzexploore_core_tab_sc title="Tab" tab_id=""][/slzexploore_core_tab_sc]' ) . '</div>';
		}

		public function setCustomTabId( $content ) {
			return preg_replace( '/tab\_id\=\"([^\"]+)\"/', 'tab_id="$1-' . time() . '"', $content );
		}

	}

	class WPBakeryShortCode_slzexploore_core_tab_sc extends WPBakeryShortCode_VC_Column {

	protected $controls_css_settings = 'tc vc_control-container';
	protected $controls_list = array('add', 'edit', 'clone', 'delete');
 
	protected $controls_template_file = 'editors/partials/backend_controls_tab.tpl.php';

		public function __construct( $settings ) {
			parent::__construct( $settings );
		}

		public function customAdminBlockParams() {
			return ' id="tab-' . $this->atts['tab_id'] . '"';
		}

		public function mainHtmlBlockParams( $width, $i ) {
			return 'data-element_type="' . $this->settings["base"] . '" class="wpb_' . $this->settings['base'] . ' wpb_sortable wpb_content_holder"' . $this->customAdminBlockParams();
		}

		public function containerHtmlBlockParams( $width, $i ) {
			return 'class="wpb_column_container vc_container_for_children"';
		}

		public function getColumnControls( $controls, $extended_css = '' ) {
			return $this->getColumnControlsModular($extended_css); 
		}
	}
}