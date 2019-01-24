<?php
Slzexploore_Core::load_class( 'models.Blog_Model' );

class Slzexploore_Core_Block extends Slzexploore_Core_Blog_Model {

	public function init( $atts, $content = null ) {

		// set attributes
		$atts['content'] = $content;
		$atts = $this->get_block_setting($atts);
		$this->block_atts = $atts;
		$this->set_attributes( $atts );
		$this->block_atts['block-class'] = $this->attributes['block-class'];

		$this->get_thumb_size();
	}
	public function set_post_options_defaults( $atts ) {
		$default = array(
			'html_format'       => '',
			'content_length'    => '',
			'thumb_href_class'  => '',
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
	/**
	 * Render html to shortcode
	 *
	 */
	public function render_block( $options = array() ) {
		$exclude = array();
		$row_begin = $row_center = $row_end= '';
		$row_begin = '<div class="row multi-column">';
		$row_center = '</div><div class="row multi-column">';
		$row_end = '</div>';
		$counter = 0;
		$options = $this->set_post_options_defaults( $options );
		echo !empty($this->attributes['column']) && $this->attributes['column'] >= 2 ? $row_begin : "";
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
				echo ($row_center);
			}


		}// end while
		echo !empty($this->attributes['column']) && $this->attributes['column'] >= 2 ? ($row_end) : "";

		//paging		
		if( $this->attributes['pagination'] == 'yes' ) {
			printf('<div class="clearfix"></div>%s', Slzexploore_Core_Pagination::paging_nav( $this->query->max_num_pages, 2, $this->query) );
		}
		// reset postdata
		wp_reset_postdata();

	}
	public function render_recent_news( $options = array() ) {

		$exclude = array();
		$options = $this->set_post_options_defaults( $options );
		while ( $this->query->have_posts() ) {
			$this->query->the_post();
			$this->loop_index();
			// render post
			$html_format = $options['html_format'];
			$options['thumb_href_class'] = 'link';
			$options['citations_length'] = $this->attributes['citations_length'];
			$options['excerpt_length'] = $this->attributes['excerpt_length'];
			$options['show_tag'] = $this->attributes['show_tag'];
			$feature_image = '';
			if( $this->has_thumbnail ){
				$feature_image = $this->get_featured_image( 'large', false, $options );
			}
			printf( $html_format,
					$feature_image,
					$this->get_meta_date_author(),
					$this->get_title(true, false, $options, 'title' ),
					$this->get_excerpt( true, false, $options['excerpt_length'] ),
					$this->get_link(),
					$this->get_post_class(),
					$this->get_tags(true, $options['show_tag'])
			);
		}// end while

		// reset postdata
		wp_reset_postdata();
	}

	public function render_carousel( $html_options = array() ) {
		$count = 0;
		while ( $this->query->have_posts() ) {
			$this->query->the_post();
			$this->loop_index();
			$count ++;
			$is_limit = Slzexploore_Core::get_value($html_options, 'is_limit');
			printf( $html_options['html_format'],
					$this->get_featured_image( 'large', false, $html_options ),
					$this->get_title( $is_limit, false, $html_options ),
					$this->get_post_class()
			);
		}
		wp_reset_postdata();
	}


	public function get_meta_info( $options = array(), $seperate = '' ) {
		$meta_array = array(
			'author'   => $this->get_author(false, true),
			'view'  => $this->get_views(),
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
	
	public function get_meta_date_author() {
		$output = '';
		if( $this->attributes['show_meta'] != 'yes' ) {
			return '';
		}
		$options['thumb_href_class'] = 'link';
		$html = '
			<ul class="info list-inline list-unstyled">
				<li><a href="%1$s" class="link">%2$s</a></li>
				<li>%3$s</li>
			</ul>';
		$output = sprintf( $html, $this->get_link(), $this->get_post_date(), $this->get_author(false, false, $options));
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
					$iframe_video = '<iframe src="https://www.youtube.com/embed/'.esc_attr( $post_meta_video['youtube_id'] ).'?rel=0" allowfullscreen class="video-embed"></iframe>';
				} elseif ($video_type == "vimeo") {
					$iframe_video = '<iframe src="https://player.vimeo.com/video/'.esc_attr( $post_meta_video['vimeo_id'] ).'?'.'" allowfullscreen class="video-embed"></iframe>';
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
	
	private function get_thumb_size() {
		$params = Slzexploore_Core::get_params( 'block-image-size', $this->attributes['layout'] );
		if( empty($params) ) {
			$this->attributes['thumb-size'] = array(
				'large'    => 'post-thumbnail',
				'no-image-large' => 'thumb-750x350.gif',
			);
		} else {
			$this->attributes['thumb-size'] = Slzexploore_Core_Util::get_thumb_size( $params, $this->attributes );
		}
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
		);
		$displays['layout'] = $atts['layout'];
		if( function_exists( SLZEXPLOORE_CORE_THEME_PREFIX . '_get_block_options')){
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

}