<?php
class Slzexploore_Core_Blog_Model {
	public $post_id;
	public $title;
	public $title_attribute;
	public $has_thumbnail = false;
	public $permalink;

	public $uniq_id;
	public $post;
	public $attributes = array();
	public $query;

	public function set_attributes( $atts = array() ) {
		$this->uniq_id = Slzexploore_Core::make_id();
		$default = array(
			'layout'               => '',
			'style'                => '',
			'block_title'          => '',
			'block_title_color'    => '',
			'block_title_bg_color' => '',
			'limit_post'           => '',
			'offset_post'          => '0',
			'extra_class'          => '',
			'sort_by'              => '',
			'pagination'           => '',
			'max_post'             => '',
			'category_filter'      => '',
			'category_filter_text' => esc_html__( 'All', 'slzexploore-core' ),
			'title_length'         => '',
			'citations_length'     => '',
			'excerpt_length'       => '',
			'show_category'        => '',
			'show_tag'             => '',
			'show_date'            => '',
			'show_author'          => '',
			'show_comments'        => '',
			'show_views'           => '',
			'show_excerpt'         => '',
			'show_meta'            => '',
			'category_list'        => '',
			'tag_list'             => '',
			'author_list'          => '',
			'category_slug'        => '',
			'tag_slug'             => '',
			'author'               => '',
			'cur_post_id'          => '',
			'post_filter_by'       => '',
			'block-class'          => '',
			'responsive-class'     => '',
			'column'               => '1',
			'related_post_count'   => '2',
			'paged'                => '',
			'cur_limit'            => '',
			'button_text'          => '',
			'button_color'         => '',
			'button_text_color'    => '',
			'button_hv_color'      => '',
			'button_text_hv_color' => ''
		);
		$data = Slzexploore_Core::set_shortcode_defaults( $default, $atts);
		if( empty( $data['category_slug'] ) ) {
			list( $data['category_list_parse'], $data['category_slug'] ) = Slzexploore_Core_Util::get_list_vc_param_group( $data, 'category_list', 'category_slug' );
		}
		if( empty( $data['tag_slug'] ) ) {
			list( $data['tag_list_parse'], $data['tag_slug'] ) = Slzexploore_Core_Util::get_list_vc_param_group( $data, 'tag_list', 'tag_slug' );
		}
		if( empty( $data['author'] ) ) {
			list( $data['author_list_parse'], $data['author'] ) = Slzexploore_Core_Util::get_list_vc_param_group( $data, 'author_list', 'author' );
		}
		// Check valid
		if($data['limit_post'] != -1) {
			$data['limit_post'] = absint($data['limit_post']);
		}
		$data['offset_post'] = absint($data['offset_post']);
		if( empty($data['offset_post']) ) {
			$data['offset_post'] = 0;
		}
		if( empty($data['block-class'] ) ) {
			$data['block-class'] = sprintf( '%s-%s', $data['layout'], $this->uniq_id ) ;
		}
		$this->attributes = $data;
		// query
		$this->query = $this->get_query( $data );
	}
	/**
	 * Loop posts.
	 */
	public function loop_index() {
		global $post;

		$this->post_id = $post->ID;
		$this->post = $post;

		$this->title = get_the_title( $post->ID );
		$this->title_attribute = esc_attr( strip_tags( $this->title ) );
		$this->permalink = esc_url( $this->get_post_url( $post->ID ) );

		if ( has_post_thumbnail( $post->ID ) ) {
			$this->has_thumbnail = true;
		} else {
			$this->has_thumbnail = false;
		}
	}
	/**
	 * Query
	 * 
	 * @return WP_Query
	 */
	private function get_query( $data, $paged = '') {
		$is_paging = false;
		$query_args = array(
			'post_type' => 'post',
			'post_status' => 'publish',
			'ignore_sticky_posts' => 1 // do not move sticky posts to the start of the set.
		);
		$posts_per_page = get_option('posts_per_page');
		//custom pagination limit
		if ( empty($data['limit_post'] ) ) {
			$data['limit_post'] = $posts_per_page;
		}
		if( $data['cur_limit'] ) {
			$data['limit_post'] = $data['cur_limit'];
		}
		$query_args['posts_per_page'] = $data['limit_post'];
		
		if( isset($data['paged']) && $data['paged'] ) {
			$paged = $data['paged'];
		} else {
			$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
		}
		if( empty($data['paged']) && ($data['pagination'] == 'ajax' || $data['pagination'] == 'load_more') ) {
			$paged = 1;
		}
		// Offset start: 0
		$offset = $data['offset_post'];
		if( $data['pagination'] == '' ) {
			$query_args['nopaging '] = false;
		} else {
			$query_args['nopaging '] = 'paging';
			$query_args['paged'] = $paged;
			$is_paging = true;
			if( isset($data['offset_post']) && $paged > 1 ) {
				$offset = $offset + ( ($paged - 1) * $data['limit_post']) ;
			}
		}
		$query_args['offset'] = $offset ;
		if( !empty( $data['category_slug'] ) ) {
			if( is_array( $data['category_slug'] ) ) {
				$data['category_slug'] = implode(',', $data['category_slug']);
			}
			$query_args['category_name'] = $data['category_slug'];
		}
		if( !empty( $data['tag_slug'] ) ) {
			if( is_array( $data['tag_slug'] )) {
				$data['tag_slug'] = implode(',', $data['tag_slug']);
			}
			$query_args['tag'] = $data['tag_slug'];
		}
		if( !empty( $data['author'] ) ) {
			//author_id
			if( is_array( $data['author'] )) {
				$data['author'] = implode(',', $data['author']);
			}
			$query_args['author'] = $data['author'];
		}
		// filter by
		if( !empty( $data['post_filter_by'] ) ) {
			$cur_post_id = $this->attributes['cur_post_id'];
			if( empty($cur_post_id) ) {
				$cur_post_id = get_the_ID();
			}
			switch ($data['post_filter_by'] ) {
				case 'post_same_author':
					$query_args['author'] = get_post_field( 'post_author', $cur_post_id );
					$query_args['post__not_in'] = array( $this->post_id );
					break;
				case 'post_same_category':
					$query_args['category__in'] = wp_get_post_categories( $cur_post_id );
					$query_args['post__not_in'] = array( $cur_post_id );
					
					break;
				case 'post_same_format':
					$post_format = get_post_format( $cur_post_id );
					if( $post_format ) {
						$tax_query = array(
							array(
								'taxonomy' => 'post_format',
								'field' => 'slug',
								'terms' => array("post-format-{$post_format}")
							)
						);
						$query_args["tax_query"] = $tax_query;
						$query_args['post__not_in'] = array( $cur_post_id );
					}
					break;
				case 'post_same_tag':
					$tags = wp_get_post_tags( $cur_post_id );
					if ( $tags ) {
						$tag_list = array();
						for ($i = 0; $i <= 4; $i++) {
							if (!empty($tags[$i])) {
								$taglist[] = $tags[$i]->term_id;
							} else {
								break;
							}
						}
						$query_args['tag__in'] = $tag_list;
						$query_args['post__not_in'] = array( $this->post_id  );
					}
					break;
			}
		}
		// sort by
		switch ( $data['sort_by'] ) {
			case 'az_order':
				$query_args['orderby'] = 'title';
				$query_args['order'] = 'ASC';
				break;
			case 'za_order':
				$query_args['orderby'] = 'title';
				$query_args['order'] = 'DESC';
				break;
			case 'popular':
				$query_args['meta_key'] = SLZEXPLOORE_CORE_POST_VIEWS;
				$query_args['orderby'] = 'meta_value_num';
				$query_args['order'] = 'DESC';
				break;
			case 'random_posts':
				$query_args['orderby'] = 'rand';
				break;
			case 'random_today':
				$query_args['orderby'] = 'rand';
				$query_args['year'] = date('Y');
				$query_args['monthnum'] = date('n');
				$query_args['day'] = date('j');
				break;
			case 'random_7_day':
				$query_args['orderby'] = 'rand';
				$query_args['date_query'] = array(
					'column' => 'post_date_gmt',
					'after' => '1 week ago'
				);
				break;
			case 'random_month':
				$query_args['orderby'] = 'rand';
				$query_args['date_query'] = array(
					'column' => 'post_date_gmt',
					'after' => '1 month ago'
				);
				break;
			case 'comment_count':
				$query_args['orderby'] = 'comment_count';
				$query_args['order'] = 'DESC';
				break;
			default:
		}		
		
		if( $is_paging && !empty($this->attributes['max_post']) && $this->attributes['max_post'] > 0 ) {
			$max_num_pages = ceil( $this->attributes['max_post'] / $query_args['posts_per_page']);
			if ( $this->attributes['max_post'] < $query_args['posts_per_page'] ) {
				$query_args['offset'] = $data['offset_post'];
			}
			if ($query_args['paged'] > $max_num_pages) {
				$query_args['offset'] = $data['offset_post'];
				$query_args['paged'] = 1;
			}
			if ( $max_num_pages == $query_args['paged'] && !empty($this->attributes['max_post']) ) {
				$query_args['posts_per_page'] = $this->attributes['max_post'] - $query_args['offset'];
			}
		}

		$query = new WP_Query( $query_args );
		if( $is_paging ) {
			$start_offset = !empty($this->attributes['offset_post']) && $this->attributes['offset_post'] > 0 ? $this->attributes['offset_post'] : 0;
			if (!empty($this->attributes['max_post']) && $this->attributes['max_post'] < $query_args['posts_per_page']) {
				$found_posts = $query_args['posts_per_page'];
			} elseif (!empty($this->attributes['max_post']) && $this->attributes['max_post'] > 0) {
				$found_posts = $this->attributes['max_post'];
			} else {
				$found_posts = $this->recalc_found_posts($query, $start_offset );
			}
			$query->found_posts = $found_posts;
			$query->max_num_pages = ceil( $query->found_posts / $query_args['posts_per_page']);
		}

		return $query;
	}
	public function recalc_found_posts($query, $offset) {
		$found_posts = $query->found_posts;
		if( $offset ) {
			return $found_posts - $offset;
		}
		return $found_posts;
	}
	/**
	 * Get post author.
	 * 
	 * @param string $echo - false(default)
	 * @param string $is_custom - false(default)
	 * @param string $option
	 * @return string
	 */
	public function get_author( $echo= false, $is_custom = false, $options = '' ) {
		$out = $format = '';
		$thumb_href_class = !empty($options['thumb_href_class']) ? $options['thumb_href_class'] : "";
		if( $this->attributes['show_author'] != 'hide' ) {
			if ( is_singular() || is_multi_author() ) {
				$format = ' <a href="%1$s" class="%3$s"><span>%2$s</span></a> ';
				$url = get_author_posts_url( $this->post->post_author );
				if ($is_custom == true) {
					$format = '<span>'.esc_html__( 'Posted by:', 'slzexploore-core' ) .'</span>'. $format .'<span class="sep">/</span>';
				}
				$out = sprintf( $format,
						esc_url( $url ),
						esc_attr( get_the_author_meta('display_name', $this->post->post_author ) ),
						esc_attr( $thumb_href_class )
				);
			}
		}
		if( ! $echo ) {
			return $out;
		}
		echo wp_kses_post( $out );
	}
	/**
	 * Get post views
	 * 
	 * @param string $echo - false(default)
	 * @return string
	 */
	public function get_views( $echo= false ) {
		$out = '';
		$format = '<span class="view-count fa-custom">%1$s</span>';
		if( $this->attributes['show_views'] != 'hide' ) {
			$out = sprintf( $format, $this->get_post_view( $this->post_id ));
		}
		if( ! $echo ) {
			return $out;
		}
		echo wp_kses_post( $out );
		
	}
	/**
	 * Get post comment number.
	 * 
	 * @param string $echo - false(default)
	 * @return string
	 */
	public function get_comments( $echo = false ) {
		$out = '';
		$format = '<span class="comment-count fa-custom"><a href="%1$s">%2$s</a></span>';
		if( $this->attributes['show_comments'] != 'hide' ) {
			if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
				$out = sprintf( $format, get_comments_link( $this->post_id ), get_comments_number() );
			}
		}
		if( ! $echo ) {
			return $out;
		}
		echo wp_kses_post( $out );
	}
	/**
	 * Get post date
	 * 
	 * @param string $echo - false(default)
	 * @return string
	 */
	public function get_date( $echo = false ) {
		$out = '';
		$format ='<a href="%1$s" class="text"><span>%2$s</span></a>';
		$date = get_the_date();
		$out  = sprintf( $format, $this->permalink, $date );
		if( ! $echo ) {
			return $out;
		}
		echo wp_kses_post( $out );
	}
	/**
	 * Get main category of post
	 * 
	 */
	/**
	 * Get main category of post.
	 * 
	 * @param string $echo - false(default)
	 * @return string
	 */
	public function get_category( $echo = false ) {
		$out = $cat = '';
		if( $this->attributes['show_category'] != 'hide' ) {
			$format = '<div class="info"><i class="fa fa-file-o"></i><a href="%1$s" class="info-inner">%2$s</a></div>';
			// Read the post meta
			$post_options = get_post_meta( $this->post_id, SLZEXPLOORE_CORE_POST_OPTIONS , true);
			if ( isset( $post_options['main_category'] ) && !empty( $post_options['main_category'] ) ) {
				// Main category has seleted post setting
				$cat = get_category_by_slug( $post_options['main_category'] );
			} else {
				// Get one auto
				$cat = current( get_the_category( $this->post ) );
			}
			if ( $cat ) {
				$out = sprintf( $format, get_category_link($cat->cat_ID), esc_html( $cat->name ) );
			}
		}
		if( !$echo ) {
			return $out;
		}
		echo wp_kses_post( $out );
	}
	/**
	 * Feature images
	 * 
	 */
	public function get_featured_image( $thumb_type = 'large', $echo = false, $options = array() ) {
		$out = $thumb_img = '';
		$thumb_size = $this->attributes['thumb-size'][$thumb_type];
		
		if( empty( $options['thumb_href_class'] ) ) {
			$options['thumb_href_class'] = 'media-image';
		}
		// 1: href, 2: image, 3: thumb_href_class
		$out = '<a href="%1$s" class="%3$s">%2$s</a>';
		if( isset( $options['image_format'] ) && !empty( $options['image_format'] ) ) {
			$out = $options['image_format'];
		}
		if( $this->has_thumbnail ) {
			$thumb_id = get_post_thumbnail_id( $this->post_id );
			$thumb_img = wp_get_attachment_image( $thumb_id, $thumb_size, false, array('class' => 'img-responsive') );
		} else {
			$thumb_img = Slzexploore_Core_Util::get_no_image( $this->attributes['thumb-size'], $this->post, $thumb_type );
			if (!empty($options['hide_no_image']) && $options['hide_no_image'] == true) {
				$thumb_img = "";
			}
		}
		$out = sprintf( $out, $this->permalink, $thumb_img, $options['thumb_href_class'] );

		if (!empty($options['no_href_image']) && $options['no_href_image'] == true) {
			$out = $thumb_img;
		}

		if( !$echo ) {
			return $out;
		}
		echo wp_kses_post( $out );
	}
	/**
	 * Get related post
	 * 
	 * @param string $limit
	 * @param string $echo
	 * @return string
	 */
	public function get_related_post( $limit = '', $echo = false, $options = array() ) {
		$out = '';
		if( empty($limit) ) {
			$limit = -1;
		}
		$args = array( 'posts_per_page' => $limit );
		$related_query = Slzexploore_Core_Com::get_query_related_posts( $this->post_id, $args );
		$format = '<a href="%1$s"><i class="fa fa-caret-right"></i><span>%2$s</span></a>';
		if ( $related_query->have_posts() ) {
			$related_posts = $related_query->posts;
			foreach($related_posts as $row ) {
				$title = isset( $row->post_title ) ? $row->post_title : '';
				if( ! empty ( $title ) ) {
					$out .= sprintf($format, get_permalink($row->ID), get_the_title($row->ID) );
				}
			}
		}
		if( empty( $out ) ) {
			return '';
		}
		$format = '<div class="sub-link">%s</div>';
		if( !$echo ) {
			return sprintf( $format, $out );
		}
		printf( $format, $out );
	}
	/**
	 * Get post title
	 * 
	 * @param string $limit
	 * @param string $echo
	 * @param string $class
	 * @return string
	 */
	public function get_title( $is_limit = true, $echo = false, $options = array(), $class = '' ) {
		$output = '<a href="%2$s" class="%4$s %3$s" >%1$s</a>';
		if( ! isset( $options['title_class'] ) ) $options['title_class'] = 'heading';
		if( isset( $options['title_format'] ) && !empty( $options['title_format'] ) ) {
			$output = $options['title_format'];
		}
		$title = $this->title;
		$limit = absint($this->attributes['title_length']);
		if( $is_limit && !empty( $limit ) ) {
			// cut title by limit
			$title = wp_trim_words($this->title, $limit);
		}
		if( ! $echo ) {
			return sprintf( $output, esc_html( $title ), $this->permalink, $options['title_class'], $class );
		} 
		printf( $output, esc_html( $title ), $this->permalink, $options['title_class'] );
	}
	/**
	 * Get the permalink
	 */
	
	public function get_link() {
		global $post;
		$this->post_id = $post->ID;
		$this->post = $post;
		$this->permalink = esc_url( $this->get_post_url( $post->ID ) );
		return $this->permalink;
	}
	/**
	 * Get the excerpt
	 * 
	 * @param string $is_limit
	 * @param string $echo
	 * @param string $limit
	 * @return string
	 */
	
	public function get_excerpt( $is_limit = true, $echo = false, $limit = '' ) {
		$trim_excerpt = '';
		if ( $this->attributes['show_excerpt'] != 'hide'){
			$trim_excerpt = get_the_excerpt();
			if( $is_limit && !empty( $limit ) ) {
				$trim_excerpt = wp_trim_words(get_the_excerpt(), $limit, ' …');
			}
		}
		if( !$echo ) {
			return $trim_excerpt;
		}
		echo wp_kses_post($trim_excerpt);
	}
	private function isLink( $post_id ) {
		return get_post_format( $post_id ) === 'link';
	}
	private function isVideo() {
		return get_post_format( $post_id ) === 'video';
	}
	private function isGallery() {
		return get_post_format( $post_id ) === 'gallery';
	}
	private function get_post_url( $post_id ) {
		if( $this->isLink( $post_id ) ) {
			$content = get_the_content( $post_id );
			$has_url = get_url_in_content( $content );
				
			return ( $has_url ) ? $has_url : apply_filters( 'the_permalink', get_permalink( $post_id ) );
		} else {
			return get_permalink( $post_id );
		}
	}
	public function add_post_filter_atts( $atts ) {
		if ( !empty( $atts['post_filter_by'] ) ) {
			$atts['cur_post_id'] = get_queried_object_id(); //add the current post id
			$atts['cur_post_author'] =  get_post_field( 'post_author', $atts['cur_post_id']); //get the current author
		}
		return $atts;
	}
	public function get_post_view( $post_id = '' ) {
		global $post;
		if( empty( $post_id ) && $post ) {
			$post_id = $post->ID;
		}
		$count_key = SLZEXPLOORE_CORE_POST_VIEWS;
		$count = get_post_meta( $post_id, $count_key, true );
		$res = '';
		if($count == '') {
			delete_post_meta( $post_id, $count_key );
			add_post_meta( $post_id, $count_key, '0' );
			$res = 0;
		} else {
			$res = $count;
		}
		return $res;
	}
	public function get_post_class( $class = '', $post_id = '' ) {
		if( empty( $post_id ) ) {
			$post_id = $this->post_id;
		}
		return join( ' ', get_post_class( $class, $post_id ) );
	}
	public function view_more_button( $echo = false, $post_id = '', $btn_content = '', $html_options = array() ) {
		if( empty( $btn_content ) ) {
			$btn_content = esc_html__( 'View more', 'slzexploore-core' );
		}
		if( ! isset( $html_options['class'] ) ) {
			$html_options['class'] = 'btn btn-viewmore';
		}
		if( $echo ) {
			return printf( '<a href="%1$s" class="%2$s">%3$s</a>', $this->permalink, $html_options['class'], $btn_content );
		}
		return sprintf( '<a href="%1$s" class="%2$s">%3$s</a>', $this->permalink, $html_options['class'], $btn_content );
	}
	/**
	 * Get tags
	 * 
	 * @param string $is_custom
	 * @param string $number
	 * @return string
	 */
	public function get_tags( $is_custom = false, $number = '' ) {
		$output = $html = $loop = $tags_loop = '';
		if ($number == '' || $number == 0) {
			return false;
		}
		global $post;
		$postID = $post->ID;
		$args = array(
				'number' => $number,
			);
		$tagByPost = wp_get_post_tags( $postID, $args );

		if ($is_custom == true) {
			$html = '
				<div class="tags">
					<div class="title-tag">'.esc_html__( "Tags:", "slzexploore-core" ).'</div>
					<ul class="list-inline list-unstyled list-tags">
						%1$s
					</ul>
				</div>
			';
		}

		if (!empty($tagByPost)) {
			foreach ($tagByPost as $key => $tag) {
				if ($key+1 > $number) { break; }
				$permarlink = get_term_link ($tag);
				$title = $tag->name;
				$tags_loop .= '<li><a href="'.$permarlink.'" class="tag-item">'.$title.'</a></li>';
			}
		 	$output = sprintf( $html, $tags_loop );
		}

		return $output;
	}
}