<?php
Slzexploore::load_class( 'models.Blog_Model' );

class Slzexploore_Blog extends Slzexploore_Blog_Model {

	public $large_image_post = true;
	public $start_group = true;
	private $row_post_counter = 0;
	private $row_counter;
	private $post_counter = 0;
	private $block_atts;
	public $show_full_meta = true;
	public $show_widget_meta = false;
	public $show_author_meta = false;

	public function init( $atts, $content = null ) {
		// default
		$this->large_image_post = true;
		$this->start_group = true;
		$this->row_post_counter = 0;
		$this->row_counter = 1;
		$this->post_counter = 0;

		// set attributes
		$atts['content'] = $content;
		$atts = $this->get_block_setting($atts);
		$this->block_atts = $atts;
		$this->set_attributes( $atts );
		$this->block_atts['block-class'] = $this->attributes['block-class'];

		$this->get_thumb_size();
		$this->set_responsive_class($atts);
		
		// add inline css
		$custom_css = $this->add_custom_css();
		if( $custom_css ) {
			do_action( 'slzexploore_add_inline_style', $custom_css );
		}
	}
	public function set_post_options_defaults( $atts ) {
		$default = array(
			'large_post_format' => '',
			'small_post_format' => '',
			'open_group'        => '',
			'open_row'          => '',
			'close_row'         => '',
			'close_group'       => '',
			'content_length'    => '',
			'large_post_counter'=> '',
			'show_related_post' => '',
			'new_row'           => '1',
			'thumb_href_class'  => '',
			'show_full_meta'    => '',
			'show_widget_meta'  =>'',
			'meta_info_format'  => '<div class="info">%1$s</div>',
			'meta_more_format'  => '<div class="info-more">%1$s</div>',
			'author_meta_format'=> '<div class="info-house">%1$s</div>',
		);
		foreach($default as $key => $val ) {
			if( isset( $atts[$key] ) ) {
				$default[$key] = $atts[$key];
				unset( $atts[$key] );
			}
		}
		if( $atts ) {
			foreach($atts as $key => $val ) {
				$default[$key] = $atts[$key];
			}
		}
		return $default;
	}
	public function set_responsive_class($atts) {
		$class = '';
		$column = $this->attributes['column'];
		if( isset($atts['res_class']) ) {
			$class = $atts['res_class'];
		}
		$def = array(
			'1' => 'col-sm-12',
			'2' => 'col-sm-6 ' . $class,
			'3' => 'col-sm-4 col-xs-12',
			'4' => 'col-sm-3',
		);;
		
		if( $column && isset($def[$column])) {
			$this->attributes['responsive-class'] = $def[$column];
		} else {
			$this->attributes['responsive-class'] = $def['1'];
		}
	}

	public function render_block( $options = array() ) {
		$exclude = array();
		$options = $this->set_post_options_defaults( $options );
		while ( $this->query->have_posts() ) {
			$this->query->the_post();
			$this->loop_index();
			$this->post_counter ++;
			$related_post = '';
			$limit_content = true;
			// render post
			$html_format = $options['small_post_format'];
			if( $this->large_image_post ) {
				// large image group
				if( empty($options['large_post_counter']) || 
					( !empty($options['large_post_counter']) && $options['large_post_counter'] == $this->post_counter ) ) {
						$this->large_image_post = false;
				}
				$type = 'large';
				$related_post = $this->get_related_post($this->attributes['related_post_count']);
				$html_format = $options['large_post_format'];
				$limit_content = false;
				if( isset($options['large_thumb_href_class'] ) ) {
					$options['thumb_href_class'] = $options['large_thumb_href_class'];
				}
				if( isset($options['large_title_class'])) {
					$options['title_class'] = $options['large_title_class'];
				}
			} else {
				if( isset($options['small_thumb_href_class'] ) ) {
					$options['thumb_href_class'] = $options['small_thumb_href_class'];
				}
				unset($options['title_class']);
				if( isset($options['small_title_class'])) {
					$options['title_class'] = $options['small_title_class'];
				}
				// small image group
				$type = 'small';
				if( $this->start_group ) {
					echo wp_kses_post( $options['open_group'] . $options['open_row'] );
					$this->start_group = false;
				}
				$this->row_post_counter ++;
			}
			if( $options['new_row'] && $this->attributes['column'] > 1 && $this->row_post_counter > $this->attributes['column'] ) {
				// add new row
				$this->row_counter ++;
				$this->row_post_counter = 1;
				echo wp_kses_post( $options['close_row'] . $options['open_row'] );
			}
			printf( $html_format,
					$this->get_featured_image( $type, false, $options ),
					$this->get_title(),
					$this->get_date(),
					$this->get_views(),
					$this->get_comments()
			);
		}// end while
		echo wp_kses_post( $options['close_row'] . $options['close_group'] );

		//paging
		if( $this->attributes['pagination'] == 'yes' ) {
			printf('<div class="" >%s</div>', $this->paging_nav( $this->query->max_num_pages, 2, $this->query) );
		}
		// reset postdata
		wp_reset_postdata();
	}

	
	public function render_author_list( $options = array() ) {
		$exclude = array();
		$row_begin = $row_center = $row_end= '';
		$row_begin = '<div class="row multi-column">';
		$row_center = '</div><div class="row multi-column">';
		$row_end = '</div>';
		$counter = 0;
		$options = $this->set_post_options_defaults( $options );
		echo !empty($this->attributes['column']) && $this->attributes['column'] >= 2 ? ($row_begin) : "";
		while ( $this->query->have_posts() ) {
			$this->query->the_post();
			$this->loop_index();
			$counter ++;
			$column = !empty($this->attributes['column']) ? $this->attributes['column'] : 1;
			// render post
			$html_format = $options['html_format'];
			printf( $html_format,
					$this->get_box_image( $options ),
					$this->get_post_date(true),
					$this->get_meta_info( $options ),
					$this->get_title(true, false, $options ),
					$this->get_excerpt(),
					$this->get_link(),
					$this->get_post_class()
			);

			if (!empty($column) && $column >= 2 && $counter > 1 && is_int($counter/$column)) {
				echo wp_kses_post($row_center);
			}


		}// end while
		echo !empty($this->attributes['column']) && $this->attributes['column'] >= 2 ? $row_end : "";
		// reset postdata
		wp_reset_postdata();

	}
	public  function paging_nav( $pages = '', $range = 2, $current_query = '' ) {
		global $paged;
		if( $current_query == '' ) {
			if( empty( $paged ) ) $paged = 1;
		} else {
			$paged = $current_query->query_vars['paged'];
		}
		$prev = $paged - 1;
		$next = $paged + 1;
		$range = 1; // only edit this if you want to show more page-links
		$showitems = ($range * 2);
		
		if( $pages == '' ) {
			global $wp_query;
			$pages = $wp_query->max_num_pages;
			if( ! $pages ) {
				$pages = 1;
			}
		}
		$method = "get_pagenum_link";
		if(is_single()) {
			$method = self::theme_post_pagination_link;
		}
		$output = $output_page = $showpages = $disable = '';
	$page_format = '<li><a href="%2$s" class="btn-pagination" >%1$s</a></li>';
		if( 1 != $pages ) {
			$output_page .= '<ul class="pagination">';
			// prev
			if( $paged == 1 ) {
				$disable = ' disable';
			}
			$output_page .= '<li class="'.$disable.'"><a href="'.esc_url( $method($prev) ).'" rel="prev" class="btn-pagination previous"><span aria-hidden="true" class="fa fa-angle-left"></span></a></li>';
			// first pages
			if( $paged > $showitems ) {
				$output_page .= sprintf( $page_format, 1, $method(1) );
			}
			// show ...
			if( $paged - $range > $showitems && $paged - $range > 2 ) {
				$output_page .= sprintf( $page_format, '&bull;&bull;&bull;', $method($paged - $range - 1) );'<li><a href="'.esc_url( $method($prev) ).'">&bull;&bull;&bull;</a></li>';
			}
			for ($i=1; $i <= $pages; $i++) {
				if (1 != $pages &&( !($i >= $paged+$showitems || $i <= $paged-$showitems) || $pages <= $showitems )) {
					if( $paged == $i ) {
						$output_page .= '<li><span class="btn-pagination active">'.$i.'</span></li>';
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
			$output_page .= '<li class="'.$disable.'"><a href="'.esc_url( $method($next) ).'" rel="next" class="btn-pagination next"><span aria-hidden="true" class="fa fa-angle-right"></span></a></li>';
			$output_page .= '</ul>'."\n";
			$output = sprintf('<nav class="pagination-list margin-top70">%1$s</nav>', $output_page );
		}
		return $output;
	}
	
	public function render_grid( $options = array() ) {
		$exclude = array();
		$options = $this->set_post_options_defaults( $options );
		while ( $this->query->have_posts() ) {
			$this->query->the_post();
			$this->loop_index();
			$this->post_counter ++;
			$limit_content = true;
			// small image group
			$this->row_post_counter ++;
			if( $options['open_row'] && $this->attributes['column'] > 1 && $this->row_post_counter > $this->attributes['column'] ) {
				// add new row
				$this->row_counter ++;
				$this->row_post_counter = 1;
				echo wp_kses_post( $options['close_row'] . $options['open_row'] );
			}
			printf( $options['small_post_format'],
					$this->get_featured_image( 'large', false, $options ),
					$this->get_date(),
					$this->get_author_meta( $options ),
					$this->get_title(true, false, $options ),
					$this->get_excerpt( $limit_content ),
					$this->attributes['responsive-class']
			);
		}// end while

		//paging
		if( $this->attributes['pagination'] == 'yes' ) {
			echo '<div class="clearfix"></div>';
			printf('<div class="" >%s</div>', $this->paging_nav( $this->query->max_num_pages, 2, $this->query) );
		}
		// reset postdata
		wp_reset_postdata();
	}

	private function get_post_date($is_custom=false) {
		$output = '';
		if ($is_custom == true) {
			$format_string = '<div class="date"><h1 class="day">%1$s</h1><div class="month">%2$s</div><div class="year">%3$s</div></div>';
			$day = get_the_time('d');
			$month = get_the_time('M');
			$year = get_the_time('Y');
			$output .= sprintf( $format_string, $day, $month, $year, esc_url( slzexploore_get_link_url() ) );
		} else {
			$output = get_the_date();
		}

		return $output;
	}

	public function get_box_image($options = array()) {
		$output = $thumb_img = $img_cate = $iframe_video = '';
		$options['thumb_href_class'] = 'link';
		
		// 1: thumbnail image
		$out_image = '<div class="blog-image">%1$s</div>';
		// 1: thumbnail image, 2: iframe
		$out_video = '<div class="blog-video"><div class="video-thumbnail"><div class="video-bg">%1$s</div><div class="video-button-play"><i class="icons fa fa-play"></i></div><div class="video-button-close"></div>%2$s</div></div>';

		$post_format = get_post_format( $this->post_id );
		$post_meta_video = get_post_meta( $this->post_id, 'slzexploore_feature_video', true );
		$is_video_type = false;
		if (!empty($post_meta_video)) {
			if ( $post_meta_video['video_type'] == 'youtube' && !empty($post_meta_video['youtube_id']) ) {
				$is_video_type = true;
			} elseif ( $post_meta_video['video_type'] == 'vimeo' && !empty($post_meta_video['vimeo_id']) ) {
				$is_video_type = true;
			}
		}
		if ($post_format == 'video' ) {
			if ($is_video_type == true) {
				$video_type = $post_meta_video['video_type'];

				if ($video_type == "youtube") {
					$iframe_video = '<iframe src="https://www.youtube.com/embed/'.esc_attr( $post_meta_video['youtube_id'] ).'?rel=0" class="video-embed"></iframe>';
				} elseif ($video_type == "vimeo") {
					$iframe_video = '<iframe src="https://player.vimeo.com/video/'.esc_attr( $post_meta_video['vimeo_id'] ).'?'.'" class="video-embed"></iframe>';
				}
				$options['no_href_image'] = true;
			}
			$output = sprintf( $out_video, $this->get_featured_image( 'large', false, $options ), $iframe_video );
		} else {
			$options['hide_no_image'] = true;
			$output = sprintf( $out_image, $this->get_featured_image( 'large', false, $options ) );
		}

        return $output;
	}
	public function get_meta_info( $options = array(), $seperate = '' ) {
		$meta_array = array(
			'author'   => $this->get_author(false, true),
			'view'     => $this->get_views(),
			'comment'  => $this->get_comments(),
		);
		foreach( $meta_array as $key => $val ) {
			if( empty( $val ) ) {
				unset($meta_array[$key]);
			}
		}
		$output = implode( $seperate, $meta_array );
		return $output;
	}
	
	public function get_meta_info_more( $html_options = array(), $seperate = '' ) {
		$meta_array = array(
			'view'     => $this->get_views(),
			'comment'  => $this->get_comments(),
		);
		foreach( $meta_array as $key => $val ) {
			if( empty( $val ) ) {
				unset($meta_array[$key]);
			}
		}
		$output = implode( $seperate, $meta_array );
		$format = $html_options['meta_more_format'];
		if( $output ) {
			$output = sprintf( $format, $output );
		}
		return $output;
	}
	
	public function get_author_meta( $html_options = array(), $seperate = '' ) {
		$meta_array = array(
			'category' => $this->get_category(),
			'comment'  => $this->get_comments(),
			'views'  => $this->get_views(),
		);
		foreach( $meta_array as $key => $val ) {
			if( empty( $val ) ) {
				unset($meta_array[$key]);
			}
		}
		$output = implode( $seperate, $meta_array );
		$format = $html_options['author_meta_format'];
		if( $output ) {
			$output = sprintf( $format, $output );
		}
		return $output;
	}
	
	public function get_meta( $html_options = array(),$seperate = '') {
		$output = '';
		if( $this->attributes['show_meta'] == 'hide' ) {
			return '';
		}
		if ( !$this->show_full_meta ){
			$output = $this->get_meta_info( $html_options ).$this->get_meta_info_more( $html_options );
			return $output;
		}else{
			$meta_array = array(
				'category' => $this->get_category(),
				'date'     => $this->get_date(),
				'author'   => $this->get_author(),
				'view'     => $this->get_views(),
				'comment'  => $this->get_comments(),
				);
				foreach( $meta_array as $key => $val ) {
					if( empty( $val ) ) {
						unset($meta_array[$key]);
					}
				}
				$format = $html_options['meta_info_format'];
			if( $this->show_widget_meta ){
				$not_div         = false;
				$prefix_category = "";
				if( isset($html_options["small_not_div"]) ){
					$not_div = $html_options["small_not_div"];
				}
				if( isset($html_options["small_prefix_category"]) ){
					$prefix_category = $html_options["small_prefix_category"];
				}
				$output  = sprintf( $format, $this->get_date( false, $not_div ), $this->get_comments(), $this->get_views(), $this->get_category( false, $prefix_category ), $this->get_author());
				return $output;
			}else{
				$output= implode( $seperate, $meta_array );
				if( $output ) {
					$output = sprintf( $format, $output );
				}
				return $output;
			}
		}
	}
	
	public function add_custom_css() {
		$css = '';
		if( $this->attributes['block_title_color'] ) {
			$css .= sprintf('.%s .block-title { color: %s;}', $this->attributes['block-class'], $this->attributes['block_title_color']);
			$css .= sprintf('.%s .section-name { border-color: %s;}', $this->attributes['block-class'], $this->attributes['block_title_color']);
		}
		return $css;
	}
	private function get_thumb_size() {
		$params = Slzexploore::get_params( 'default-image-size', $this->attributes['layout'] );
		$this->attributes['thumb-size'] = $this->get_thumb_sizes( $params, $this->attributes );
	}
	private function get_block_setting( $atts ) {
		$displays = array(
			'show_category'        => '',
			'show_tag'             => '',
			'show_comment'         => '',
			'show_views'           => '',
			'show_date'            => '',
			'show_author'          => '',
			'show_excerpt'         => '',
			'show_meta'            => '',
			'content_length'       => '',
			'title_length'         => '',
		);
		$displays['layout'] = $atts['layout'];
		if( function_exists('slzexploore_get_block_options')){
			slzexploore_get_block_options( $displays );
		}
		foreach( $displays as $key => $val ) {
			if( ! isset($atts[$key]) ) {
				$atts[$key] = $displays[$key];
			} else if( ($atts[$key] == 'no' || $atts[$key] == 'hide') ) {
				$atts[$key] = 'hide';
			}
		}
		return $atts;
	}

}