<?php
/**
 * Blog Model.
 * 
 * @since 1.0
 */
class Slzexploore_Blog_Model {
	public $post_id;
	private $title;
	private $title_attribute;
	private $has_thumbnail = false;
	private $permalink;

	public $uniq_id;
	public $post;
	public $attributes = array();
	public $query;

	public function set_attributes( $atts = array() ) {
		$this->uniq_id = Slzexploore::make_id();
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
			'category_filter'      => '',
			'category_filter_text' => esc_html__( 'All', 'exploore' ),
			'title_length'         => '',
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
		);
		$data = array_merge( $default, $atts );
		
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
		if( $data['pagination'] == '' ) {
			$query_args['nopaging '] = false;
		} else {
			$query_args['nopaging '] = 'paging';
			$query_args['paged'] = $paged;
			$is_paging = true;
		}
		// Offset start: 0
		$offset = $data['offset_post'];
		if( $is_paging && isset($data['offset_post']) && $paged > 1 ) {
			$offset = $offset + ( ($paged - 1) * $data['limit_post']) ;
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
				$query_args['meta_key'] = 'slzexploore_postview_number';
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
		$query = new WP_Query( $query_args );
		if( $is_paging && $this->attributes['offset_post'] > 0 ) {
			$start_offset = $this->attributes['offset_post'];
			$query->found_posts = $this->recalc_found_posts($query, $start_offset );
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
					$format = esc_html__( 'Posted by:', 'exploore' ) . $format .'<span class="sep">/</span>';
				}
				$out = sprintf( $format,
						esc_url( $url ),
						esc_html( get_the_author_meta('display_name', $this->post->post_author ) ),
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
			$out = sprintf( $format, esc_html( $this->get_post_view( $this->post_id ) ) );
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
				$out = sprintf( $format, esc_url( get_comments_link( $this->post_id ) ), get_comments_number() );
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
	public function get_date( $echo = false, $not_div = false ) {
		$out = '';
		$date_string = '';
		if ( $this->show_widget_meta ){
			if( $not_div ){
				$format ='<a class="date" href="%1$s">%2$s</a>';
			}else{
				$format ='<span>%2$s</span>';
			}
		}
		else if( $this->show_author_meta ) {
			$day = get_the_time('d');
			$month = get_the_time('M');
			$year = get_the_time('Y');
			$date_string = sprintf('<span>%1$s</span> %2$s <span>%3$s</span>',$day, $month, $year );
			$format = '<a href="%1$s" class="text">%2$s</a>';
		}
		else{
			$format = '<div class="date-time item"><a href="%1$s">%2$s</a></div>';
		}
		
		if( $this->attributes['show_date'] != 'hide' ) {
			if( empty($date_string ) ) {
				$date_string = get_the_date();
			}
			$out = sprintf( $format, esc_url( $this->permalink ), $date_string );
		}
		if( ! $echo ) {
			return $out;
		}
		echo wp_kses_post($out);
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
	public function get_category( $echo = false, $prefix_category = '' ) {
		$out = $cat = '';
		if( $this->attributes['show_category'] != 'hide' ) {
			if ( $this->show_widget_meta ) {
				$format = '<a href="%1$s" class="category">%3$s<span>%2$s</span></a>';
			}
			else if($this->show_author_meta) {
				$format = '<div class="info"><i class="fa fa-file-o"></i><a href="%1$s" class="info-inner">%2$s</a></div>';
			}
			else{
				$format = '<div class="category item"><a href="%1$s" class="">%2$s</a></div>';
			}
			// Read the post meta
			$post_options = get_post_meta( $this->post_id, 'slzexploore_post_options', true);
			if ( isset( $post_options['main_category'] ) && !empty( $post_options['main_category'] ) ) {
				// Main category has seleted post setting
				$cat = get_category_by_slug( $post_options['main_category'] );
			} else {
				// Get one auto
				$cat = current( get_the_category( $this->post ) );
			}
			if ( $cat ) {
				$out = sprintf( $format, esc_url( get_category_link($cat->cat_ID) ), esc_html( $cat->name ), esc_attr($prefix_category) );
			}
		}
		if( !$echo ) {
			return $out;
		}
		echo wp_kses_post($out);
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
		if( $this->has_thumbnail ) {
			$thumb_id = get_post_thumbnail_id( $this->post_id );
			$thumb_img = wp_get_attachment_image( $thumb_id, $thumb_size, false, array('class' => 'img-responsive') );
		} else {
			$thumb_img = $this->get_no_image( $this->attributes['thumb-size'], $this->post, $thumb_type );
			if (!empty($options['hide_no_image']) && $options['hide_no_image'] == true) {
				$thumb_img = "";
			}
		}
		$out = sprintf( $out, esc_url( $this->permalink ), $thumb_img, $options['thumb_href_class'] );

		if (!empty($options['no_href_image']) && $options['no_href_image'] == true) {
			$out = $thumb_img;
		}

		if( !$echo ) {
			return $out;
		}
		echo wp_kses_post( $out );
	}
	public  function get_no_image( $atts = array(), $post = null, $thumb_type = 'large', $options = array() ){
		$alt = '';
		if( $post ) {
			$alt = trim( strip_tags( $post->post_title ) );;
		}
		if( isset($atts['no-image-' . $thumb_type])) {
			$no_image = $atts['no-image-' . $thumb_type];
		} else {
			$no_image = $atts['no-image'];
		}
		$filename = SLZEXPLOORE_NO_IMG_URI . $no_image;
		if( ! file_exists( $filename ) ) {
			$no_image = SLZEXPLOORE_NO_IMG;
		} else {
			$no_image = SLZEXPLOORE_NO_IMG_URI . $no_image;
		}
		$thumb_class = Slzexploore::get_value( $options, 'thumb_class', 'img-responsive' );
		$thumb_img = sprintf('<img src="%1$s" alt="%2$s" class="%3$s" />', $no_image, $alt, $thumb_class );
		return $thumb_img;
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
		$related_query = $this->get_query_related_posts( $this->post_id, $args );
		$format = '<a href="%1$s"><i class="fa fa-caret-right"></i><span>%2$s</span></a>';
		if ( $related_query->have_posts() ) {
			$related_posts = $related_query->posts;
			foreach($related_posts as $row ) {
				$title = isset( $row->post_title ) ? $row->post_title : '';
				if( ! empty ( $title ) ) {
					$out .= sprintf($format, esc_url( get_permalink($row->ID) ), get_the_title($row->ID) );
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
	 * @return string
	 */
	public function get_title( $is_limit = true, $echo = false, $options = array(), $class = '' ) {
		$output = '<a href="%2$s" class="%4$s %3$s" >%1$s</a>';
		if( ! isset( $options['title_class'] ) ) $options['title_class'] = 'heading';
		if( isset( $options['title_format'] ) && !empty( $options['title_format'] ) ) {
			$output = $options['title_format'];
		}
		$title = $this->title;
		$limit = $this->attributes['title_length'];
		if( $is_limit && !empty( $limit ) ) {
			// cut title by limit
			$title = wp_trim_words($this->title, $limit);
		}
		if( ! $echo ) {
			return sprintf( $output, esc_html( $title ), esc_url( $this->permalink ), $options['title_class'], $class );
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
	/**
	 * Get the excerpt
	 * 
	 * @param string $limit
	 * @param string $echo
	 * @return string
	 */
	public function get_excerpt( $is_limit = true, $echo = false ) {
		$trim_excerpt = '';
		if ( $this->attributes['show_excerpt'] != 'hide'){
			$trim_excerpt = get_the_excerpt();
			$limit = $this->attributes['excerpt_length'];
			if( $is_limit && !empty( $limit ) ) {
				$trim_excerpt = wp_trim_words($trim_excerpt, $limit, ' &#91;&#46;&#46;&#46;&#93;'); // [...]
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
		$count_key = 'slzexploore_postview_number';
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
			$btn_content = esc_html__( 'View more', 'exploore' );
		}
		if( ! isset( $html_options['class'] ) ) {
			$html_options['class'] = 'btn btn-viewmore';
		}
		if( $echo ) {
			return printf( '<a href="%1$s" class="%2$s">%3$s</a>', esc_url( $this->permalink ), $html_options['class'], $btn_content );
		}
		return sprintf( '<a href="%1$s" class="%2$s">%3$s</a>', esc_url( $this->permalink ), $html_options['class'], $btn_content );
	}
	public function get_thumb_sizes( $sizes, $options= array(), $theme_prefix = SLZEXPLOORE_THEME_PREFIX ) {
	
		$thumb_size = array(
			'large' => 'full',
		);
		if( !isset($options['column'])) {
			$options['column'] = '';
		}
		$small_column = 'small-' . $options['column'];
		if( $sizes ) {
			foreach( $sizes as $key => $value ) {
				$prefix = 'thumb-';
				$ext = '.gif';
				if( $key == 'large' || $key == 'small' || $key == $small_column ) {
					$prefix = $theme_prefix . '-thumb-';
					$ext = '';
				}
				$thumb_size[$key] = $prefix . $value . $ext;
			}
			if( ! isset( $thumb_size['no-image'] ) ) {
				$thumb_size['no-image'] = 'thumb-' . $sizes['large'] . '.gif';
			}
		} else {
			$thumb_size['no-image'] = 'thumb-no-image.gif';
		}
		// no small size => small size = large size
		if( ! isset( $thumb_size['small'] ) ) {
			$thumb_size['small'] = $thumb_size['large'];
		}
		if( isset( $thumb_size[$small_column] ) ) {
			$thumb_size['small'] = $thumb_size[$small_column];
			if( isset( $thumb_size['no-image-' . $small_column] ) ) {
				$thumb_size['no-image-small'] = $thumb_size['no-image-' . $small_column];
			}
		}
		return $thumb_size;
	}
	public function get_query_related_posts( $post_id, $args = array(), $taxonomy = 'category' ) {
		$query = new WP_Query();
	
		$terms = get_the_terms( $post_id, $taxonomy );
		$terms_array = array();
		if( $terms ) {
			foreach( $terms as $item ) {
				$terms_array[] = $item->term_id;
			}
		}
		if( ! empty( $terms_array ) ) {
			$args = wp_parse_args( $args, array(
				'ignore_sticky_posts' => 0,
				'posts_per_page' => -1,
				'post__not_in' => array( $post_id ),
				'post_type' => 'post',
				'tax_query' => array(
					array(
						'field' => 'id',
						'taxonomy' => $taxonomy,
						'terms' => $terms_array,
					)
				)
			));
			$query = new WP_Query( $args );
		}
		return $query;
	}
}