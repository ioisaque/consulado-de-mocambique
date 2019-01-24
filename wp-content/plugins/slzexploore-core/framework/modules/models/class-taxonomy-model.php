<?php
class Slzexploore_Core_Taxonomy_Model {
	public $taxonomy = 'category';
	public $taxonomy_atts = array();
	public $terms;
	public $custom_fields;
	public $cur_term;
	public $term_url;
	public $counter;

	/**
	 * Init class.
	 */
	public function init( $tax, $atts = array(), $query_args = array() ) {
		$this->taxonomy = $tax;
		$this->taxonomy_atts = $atts;
		$this->terms = $this->get_terms( $query_args );
		if( isset( $this->taxonomy_atts['image-size'] ) ) {
			$this->get_thumb_size();
		}
		if( isset( $this->taxonomy_atts['column'] ) ) {
			$this->taxonomy_atts['column'] = absint($this->taxonomy_atts['column']);
		} else {
			$this->taxonomy_atts['column'] = 0;
		}
		$this->get_responsive_class();
		if( !isset( $this->taxonomy_atts['uniq_id'] ) ) {
			$this->taxonomy_atts['uniq_id'] = 'block-'.Slzexploore_Core::make_id();
		}
	}
	/**
	 * Render html with icon.
	 * 
	 * @param array $html_options
	 *     Optional. Array of arguments to get html formats.
	 *
	 *     @type string    $html_format      General html format to display a block.
	 *                                       Params (1: term_id, 2: icon, 3: term name, 4: short desc, 5: term desc).
	 *     @type string    $html_icon        Html format to display custom field "icon".
	 *     @type string    $html_name        Html format to display term name.
	 *     @type string    $html_desc        Html format to display term description.
	 *     @type string    $html_short_desc  Html format to display custom field "short_description".
	 */
	public function render_sc( $html_options = array() ) {
		$row_counter = 0;
		if( $this->terms ) {
			foreach( $this->terms as $term ){
				$this->cur_term = $term;
				$term_id = $term->term_id;
				$this->term_url = get_term_link($term);
				$this->get_custom_fields( $term_id );
				if( isset( $html_options['open_row'] ) && !empty( $html_options['open_row'] ) ) {
					if( $this->taxonomy_atts['column'] > 0 && $this->taxonomy_atts['column'] <= $row_counter ) {
						$row_counter = 0;
						echo ( $html_options['close_row'] . $html_options['open_row'] );
					}
				}
				printf( $html_options['html_format'],
						$term_id,
						$this->get_icon( $html_options ),
						$this->get_term_name( $html_options ),
						$this->get_short_desc( $html_options ),
						$this->get_term_desc( $html_options )
				);
				$row_counter ++;
			}
		}
	}
	/**
	 * Render html with thumb image.
	 * 
	 * @param array $html_options {
	 *     Optional. Array of arguments to get html formats.
	 *
	 *     @type string    $html_format      General html format to display a block.
	 *                                       Params (1: term_id, 2: thumb, 3: term name, 4: term desc, 5: count, 6: button, 7: responsive class).
	 *     @type string    $html_name        Html format to display term name.
	 *     @type string    $html_desc        Html format to display term description.
	 *     @type string    $html_count       Html format to display term count.
	 *     @type string    $html_button      Html format to display button.
	 *     @type string    $thumb_href_class Extra class to href of thumbnail. 
	 *     @type string    $open_row         Html format to open new row. Required $close_row.
	 *     @type string    $close_row        Html format to close new row. Required $open_row.
	 */
	public function render_grid( $html_options = array() ) {
		$row_counter = 0;
		if( $this->terms ) {
			foreach( $this->terms as $term ){
				$this->cur_term = $term;
				$term_id = $term->term_id;
				$this->term_url = get_term_link($term);
				$this->get_custom_fields( $term_id );
				if( isset( $html_options['open_row'] ) && !empty( $html_options['open_row'] ) ) {
					if( $this->taxonomy_atts['column'] > 0 && $this->taxonomy_atts['column'] <= $row_counter ) {
						$row_counter = 0;
						echo ( $html_options['close_row'] . $html_options['open_row'] );
					}
				}
				printf( $html_options['html_format'],
						$term_id,
						$this->get_thumbnail( $html_options ),
						$this->get_term_name( $html_options ),
						$this->get_term_desc( $html_options ),
						$this->get_term_count( $html_options ),
						$this->get_button_link( $html_options ),
						$this->taxonomy_atts['responsive_class']
				);
				$row_counter ++;
			}
		}
	}
	/**
	 * Get responsive class to display a block.
	 */
	public function get_responsive_class(){
		$defaults = array(
			'2' => 'col-md-6',
			'3' => 'col-md-4',
			'4' => 'col-md-3',
		);
		$class = '';
		if( isset( $this->taxonomy_atts['column']) ) {
			$class = Slzexploore_Core::get_value( $defaults, $this->taxonomy_atts['column'] );
		}
		$this->taxonomy_atts['responsive_class'] = $class;
	}
	/**
	 * Get terms.
	 */
	public function get_terms( $args = array() ) {
		$atts = $this->taxonomy_atts;
		$defaults = array(
			'slug' => $atts['term_slug'],
		);
		$query_args = array_merge( $defaults, $args );
		$limit = 0;
		if( isset( $atts['limit'] ) ) {
			$limit = absint( $atts['limit'] );
		}
		if( isset($atts['paged']) && $atts['paged'] ) {
			$paged = $atts['paged'];
		} else {
			$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
		}
		$offset = absint( Slzexploore_Core::get_value($atts, 'offset', 0 ) );
		$query_args['offset'] = $offset;
		$max_num_pages = 1;
		if( isset($atts['pagination']) && $atts['pagination'] == 'yes' ) {
			$query_args['paged'] = $paged;
			// get max num pages
			$max_term = get_terms( $this->taxonomy, $query_args );
			if( $limit > 0 ) {
				$max_num_pages = ceil( count($max_term) / $limit );
			}
		}
		$this->taxonomy_atts['max_num_pages'] = $max_num_pages;
		$this->taxonomy_atts['paged'] = $paged;

		// calc offset
		if( $paged > 1 ) {
			$offset = $offset + ( ($paged - 1) * $limit) ;
		}
		$query_args['offset'] = $offset;
		$query_args['number'] = $limit;
		return get_terms( $this->taxonomy, $query_args );
	}
	/**
	 * Get custom fields of taxonomy.
	 */
	public function get_custom_fields( $term_id ){
		$default = array(
			'color'              => '',
			'background_color'   => '',
		);
		$option_key = SLZEXPLOORE_CORE_TAXONOMY_CUS . $term_id;
		$option_val = get_option($option_key);
		foreach($default as $key=>$val) {
			if( isset ($option_val[$key]) ) {
				$default[$key] = $option_val[$key];
			}
		}
		$this->custom_fields = $default;
		return $this->custom_fields;
	}
	/**
	 * Get custom field "thumbnail_id" of taxonomy. 
	 */
	public function get_thumbnail( $html_options = array(), $thumb_type = 'large' ){
		$output = $thumb_img = '';
		$thumb_size = $this->taxonomy_atts['thumb-size'][$thumb_type];
		$thumb_id = $this->custom_fields['thumbnail_id'];
		if( $thumb_id ) {
			$thumb_img = wp_get_attachment_image( $thumb_id, $thumb_size, false, array('class' => 'img-responsive') );
		} else {
			$alt = trim(strip_tags( $this->cur_term->name ));;
			if ( empty( $alt ) ) {
				$alt = $this->cur_term->slug;
			}
			$def_img = '';
			if( isset($this->taxonomy_atts['thumb-size']['no-image-' . $thumb_type])) {
				$no_image = $this->taxonomy_atts['thumb-size']['no-image-' . $thumb_type];
			} else {
				$no_image = $this->taxonomy_atts['thumb-size']['no-image'];
			}
			$thumb_img = sprintf('<img src="%1$s" alt="%2$s" class="img-responsive" />', SLZEXPLOORE_CORE_NO_IMG_URI . $no_image ,$alt);
		}
		$format = '<a href="%2$s" class="%3$s">%1$s</a>';
		//1: img, 2: url, 3: url class
		$output = sprintf( $format, $thumb_img, esc_url($this->term_url), esc_attr( $html_options['thumb_href_class'] ) );
		return $output;
	}
	/**
	 * Get custom field "icon" of taxonomy. 
	 */
	public function get_icon( $html_options = array() ) {
		if( isset( $html_options['html_icon'] ) && !empty( $html_options['html_icon'] ) ) {
			$format = $html_options['html_icon'];
		} else {
			$format = '<div class="icon-background"><i class="icons-img %s"></i></div>';
		}
		$icon = Slzexploore_Core::get_value($this->custom_fields, 'icon');
		if( !empty($icon) ){
			return sprintf( $format, esc_attr($icon) );
		}
		return '';
	}
	/**
	 * Get custom field "short_description" of taxonomy.
	 */
	public function get_short_desc( $html_options = array() ) {
		if( isset( $html_options['html_short_desc'] ) && !empty( $html_options['html_short_desc'] ) ) {
			$format = $html_options['html_short_desc'];
		} else {
			$format = '%1$s';
		}
		$short_desc = Slzexploore_Core::get_value($this->custom_fields, 'short_description');
		if( !empty( $short_desc ) ) {
			return sprintf( $format, esc_html($short_desc), esc_url($this->term_url) );
		}
		return '';
	}
	/**
	 * Get term name.
	 */
	public function get_term_name( $html_options = array() ) {
		if( isset( $html_options['html_name'] ) && !empty( $html_options['html_name'] ) ) {
			$format = $html_options['html_name'];
		} else {
			$format = '<a href="%1$s">%2$s</a>';
		}
		$term_name = $this->cur_term->name;
		if( $term_name ) {
			return sprintf( $format, esc_url($this->term_url), esc_html( $term_name ) );
		}
		return '';
	}
	/**
	 * Get term description.
	 */
	public function get_term_desc( $html_options = array() ) {
		if( isset( $html_options['html_desc'] ) && !empty( $html_options['html_desc'] ) ) {
			$format = $html_options['html_desc'];
		} else {
			$format = '%1$s';
		}
		$desc = $this->cur_term->description;
		if( $desc ) {
			return sprintf( $format, nl2br( esc_textarea( $desc ) ), esc_url($this->term_url) );
		}
		return '';
	}
	/**
	 * Get term count.
	 */
	public function get_term_count( $html_options = array() ) {
		$show_count = Slzexploore_Core::get_value( $this->taxonomy_atts, 'show_term_counts');
		if( $show_count == 'yes' ) {
			if( isset( $html_options['html_count'] ) && !empty( $html_options['html_count'] ) ) {
				$format = $html_options['html_count'];
			} else {
				$format = '<a href="%1$s">'.esc_html__('Total', 'slzexploore-core').' %2$s</a>';
			}
			$term_count = $this->cur_term->count;
			return sprintf( $format, esc_url($this->term_url), number_format_i18n( $term_count ) );
		}
		return '';
	}
	/**
	 * Get html of button.
	 */
	public function get_button_link( $html_options = array() ) {
		if( isset( $html_options['html_button'] ) && !empty( $html_options['html_button'] ) ) {
			$format = $html_options['html_button'];
		} else {
			$format = '<a href="%1$s" class="btn btn-green"><span>%2$s</span></a>';
		}
		$btn_content = Slzexploore_Core::get_value( $this->taxonomy_atts, 'btn_content');
		if( !empty( $btn_content ) ) {
			return sprintf( $format, esc_url($this->term_url), esc_html($btn_content) );
		}
		return '';
	}
	/**
	 * Get image size.
	 */
	private function get_thumb_size() {
		$params = Slzexploore_Core::get_params( 'block-image-size', $this->taxonomy_atts['image-size'] );
		$this->taxonomy_atts['thumb-size'] = Slzexploore_Core_Util::get_thumb_size( $params, $this->taxonomy_atts );
	}
	/**
	 * Taxonomy Pagination
	 * 
	 * @param string $pages - Max number pages
	 * @param string $paged - Current page.
	 * @return string
	 */
	public function paging_nav( $pages = '', $paged = '' ) {
		if( empty($pages) ) {
			$pages = $this->taxonomy_atts['max_num_pages'];
		}
		if( empty( $paged )) {
			$paged = $this->taxonomy_atts['paged'];
		}
		if( empty($pages) || empty($paged) ) return;

		$prev = $paged - 1;
		$next = $paged + 1;
		$range = 1; // only edit this if you want to show more page-links
		$showitems = ($range * 2);
	
		$method = "get_pagenum_link";
		$output = $output_page = $showpages = $disable = '';
		$page_format = '<li class=""><a href="%2$s" class="pagination__page btn-squae" >%1$s</a></li>';
		if( 1 != $pages ) {
			$output_page .= '<ul class="pagination__list">';
			// prev
			if( $paged == 1 ) {
				$disable = ' disable';
			}
			$output_page .= '<li><a href="'.$method($prev).'" rel="prev" class="pagination__previous btn-squae'.$disable.'"><i class="fa fa-angle-left" ></i></a></li>';
			// first pages
			if( $paged > $showitems ) {
				$output_page .= sprintf( $page_format, 1, $method(1) );
			}
			// show ...
			if( $paged - $range > $showitems && $paged - $range > 2 ) {
				$output_page .= sprintf( $page_format, '&bull;&bull;&bull;', $method($paged - $range - 1) );'<li><a href="'.$method($prev).'">&bull;&bull;&bull;</a></li>';
			}
			for ($i=1; $i <= $pages; $i++) {
				if (1 != $pages &&( !($i >= $paged+$showitems || $i <= $paged-$showitems) || $pages <= $showitems )) {
					if( $paged == $i ) {
						$output_page .= '<li><span class="pagination__page btn-squae active">'.$i.'</span></li>';
					} else {
						$output_page .= sprintf( $page_format, $i, $method($i) );
					}
					$showpages = $i;
				}
			}
			// show ...
			if( $paged < $pages-1 && $showpages < $pages -1 ){
				$showpages = $showpages + 1;
				$output_page .= sprintf( $page_format, '&bull;&bull;&bull;', $method($showpages) );
			}
			// end pages
			if( $paged < $pages && $showpages < $pages ) {
				$output_page .= sprintf( $page_format, $pages, $method($pages) );
			}
			//next
			$disable = '';
			if( $paged == $pages ) {
				$disable = ' disable';
			}
			$output_page .= '<li><a href="'.$method($next).'" rel="next" class="pagination__next btn-squae'.$disable.'"><i class="fa fa-angle-right" ></i></a></li>';
			$output_page .= '</ul>'."\n";
			$output = sprintf('<nav class="pagination">%1$s</nav>', $output_page );
		}
		return $output;
	}
}