<?php
class Slzexploore_Core_Custom_Post_Model {
	public $post_id;
	public $cur_post;
	public $permalink;
	public $taxonomy_cat;
	public $attributes;
	public $post_view_key;
	public $query;
	public $post_count;
	public $post_meta_atts;
	public $post_meta;
	public $post_meta_label;
	public $post_meta_def;
	public $post_meta_prefix;
	public $post_password;

	public $title;
	public $title_attribute;
	public $has_thumbnail;
	public $uniq_id;
	public $sort_meta_key;
	public $post_rating_key;
	
	public function get_single_post( $post_id ) {
		global $post;
		if( ! empty( $post_id ) ) {
			$post = get_post( $post_id );
		}
		$this->loop_index( $post );
	}
	public function get_custom_post( $post_id ) {
		if( ! empty( $post_id ) ) {
			$post = get_post( $post_id );
			if( $post ) {
				$this->loop_index( $post, false );
			}
		}
	}
	/**
	 * Setting current post.
	 * 
	 * @param string $cur_post
	 */
	public function loop_index( $current_post = '', $is_global = true ) {
		if( $is_global ) {
			global $post;
		}
		if( ! empty( $current_post ) ) {
			$post = $current_post;
		}
	
		$this->post_id = $post->ID;
		$this->cur_post = $post;
	
		$this->title = get_the_title( $post->ID );
		$this->title_attribute = esc_attr( strip_tags( $this->title ) );
		$this->permalink = esc_url( get_permalink( $post->ID ) );
		if ( has_post_thumbnail( $post->ID ) ) {
			$this->has_thumbnail = true;
		} else {
			$this->has_thumbnail = false;
		}
		$this->post_password = post_password_required( $post );
		// set post meta
		$meta_data = array();
		if( $this->post_meta_def ) {
			foreach( $this->post_meta_def as $key => $val ) {
				$meta_key = $this->post_meta_prefix . $key;
				$meta_data[$key] = $val;
				if( metadata_exists( 'post', $this->post_id, $meta_key)) {
					$meta_data[$key] = get_post_meta( $this->post_id, $meta_key, true );
				}
			}
		}
		$this->post_meta = $meta_data;
		if( isset( $this->post_meta['display_title'] ) ) {
			if( empty( $this->post_meta['display_title'] ) ) {
				$this->post_meta['display_title'] = strip_tags($post->post_title);
			} else {
				$this->title = $this->post_meta['display_title'];
			}
		}
	}
	/**
	 * Get post query.
	 * 
	 * @param array $args   Optional. Query arguments.
	 * @param array $data
	 * @return WP_Query
	 */
	public function get_query( $args = array(), $data = array() ) {
		$is_paging = false;
		$defaults = array(
			'post_status'    =>'publish',
			'posts_per_page' => -1,
		);
		
		$query_args = array_merge( $defaults, $args );
		//post slug
		if( isset( $data['post_slug'] ) && !empty( $data['post_slug'] ) ) {
			if( is_array( $data['post_slug'] ) ) {
				$post_in_arr = array();
				foreach( $data['post_slug'] as $slug ) {
					$post_in_id = $this->get_custom_post_id_by_slug( $query_args['post_type'], $slug );
					if( $post_in_id ) {
						$post_in_arr[] = $post_in_id;
					}
				}
				if( $post_in_arr ) {
					$query_args['post__in'] = $post_in_arr;
				}
			} else {
				$query_args['name'] = $data['post_slug'];
			}
		}
		// post id
		if( isset( $data['post_id'] ) && !empty( $data['post_id'] ) ) {
			if( is_array( $data['post_id'] ) ) {
				$query_args['post__in'] = $data['post_id'];
			}
		}
		if ( isset($data['post__not_in']) ) {
			if( is_array( $data['post__not_in'] ) ) {
				$query_args['post__not_in'] = $data['post__not_in'];
			} else {
				$query_args['post__not_in'] = array($data['post__not_in']);
			}
		}
		// limit post
		$posts_per_page = get_option('posts_per_page');
		if( isset($data['limit_post']) ) {
			if ( empty($data['limit_post'] ) ) {
				$data['limit_post'] = $posts_per_page;
			}
			if( isset( $data['cur_limit'] ) &&  $data['cur_limit'] ) {
				$data['limit_post'] = $data['cur_limit'];
			}
			$query_args['posts_per_page'] = $data['limit_post'];
		}
		//paged
		if( isset($data['paged']) && $data['paged'] ) {
			$paged = $data['paged'];
		} else {
			$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
		}
		// Offset start: 0
		$offset = Slzexploore_Core::get_value( $data, 'offset_post', 0 );
		if( isset( $data['pagination'] ) ) {
			if( empty($data['paged']) && ($data['pagination'] == 'ajax' || $data['pagination'] == 'load_more') ) {
				$paged = 1;
			}
			if( $data['pagination'] == '' ) {
				$query_args['nopaging '] = false;
			} else {
				$is_paging = true;
				$query_args['nopaging '] = 'paging';
				$query_args['paged'] = $paged;
				if( isset($data['offset_post']) && $paged > 1 ) {
					$offset = $offset + ( ($paged - 1) * $data['limit_post']) ;
				}
			}
		}
		$query_args['offset'] = $offset ;
		$author_data = Slzexploore_Core::get_value( $data, 'author', 0 );
		if( is_array( $author_data ) ) {
			$query_args['author__in'] = $author_data;
		}
		else {
			$query_args['author'] = $author_data;
		}
		// category or taxonomy
		$this->parse_tax_query( $query_args, $data );
		//search by meta
		$this->parse_meta_key( $query_args, $data );
		// sort by
		$this->parse_order_by( $query_args, $data );
		
		$query = new WP_Query( $query_args );
		if( $is_paging && $this->attributes['offset_post'] > 0 ) {
			$start_offset = $this->attributes['offset_post'];
			$query->found_posts = $this->recalc_found_posts($query, $start_offset );
			$query->max_num_pages = ceil( $query->found_posts / $query_args['posts_per_page']);
		}
		return $query;
	}
	private function parse_order_by( &$query_args, $data ) {
		// sort by
		if( isset( $data['order_by'] ) && ! empty( $data['order_by'] ) ) {
			$args['orderby'] = $data['order_by'];
			$args['order'] = $data['order'];
		}
		$sort_by = Slzexploore_Core::get_value( $data, 'sort_by' );
		switch ( $sort_by ) {
			case 'az_order':
				$query_args['orderby'] = 'title';
				$query_args['order'] = 'ASC';
				break;
			case 'za_order':
				$query_args['orderby'] = 'title';
				$query_args['order'] = 'DESC';
				break;
			case 'popular':
				$query_args['meta_key'] = $this->post_view_key;
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
			case 'post__in':
				$query_args['orderby'] = 'post__in';
				$query_args['order'] = 'ASC';
				break;
			case 'az_price':
				if( isset($this->sort_meta_key['price']) ) {
					$query_args['orderby'] = array( 'meta_value_num' => 'ASC', 'date' => 'DESC' );
					$query_args['meta_key'] = $this->sort_meta_key['price'];
				}
				break;
			case 'za_price':
				if( isset($this->sort_meta_key['price']) ) {
					$query_args['orderby'] = array( 'meta_value_num' => 'DESC', 'date' => 'DESC' );
					$query_args['meta_key'] = $this->sort_meta_key['price'];
				}
				break;
			case 'az_star_rating':
				if( isset($this->sort_meta_key['star_rating']) ) {
					$query_args['orderby'] = array( 'meta_value_num' => 'ASC', 'date' => 'DESC' );
					$query_args['meta_key'] = $this->sort_meta_key['star_rating'];
				}
				break;
			case 'za_star_rating':
				if( isset($this->sort_meta_key['star_rating']) ) {
					$query_args['orderby'] = array( 'meta_value_num' => 'DESC', 'date' => 'DESC' );
					$query_args['meta_key'] = $this->sort_meta_key['star_rating'];
				}
				break;
			case 'az_review_rating':
				if( isset($this->sort_meta_key['review_rating']) ) {
					$query_args['orderby'] = array( 'meta_value_num' => 'ASC', 'date' => 'DESC' );
					$query_args['meta_key'] = $this->sort_meta_key['review_rating'];
				}
				break;
			case 'za_review_rating':
				if( isset($this->sort_meta_key['review_rating']) ) {
					$query_args['orderby'] = array( 'meta_value_num' => 'DESC', 'date' => 'DESC' );
					$query_args['meta_key'] = $this->sort_meta_key['review_rating'];
				}
				break;
			case 'az_discount':
				if( isset($this->sort_meta_key['discount']) ) {
					$query_args['orderby'] = array( 'meta_value_num' => 'ASC', 'date' => 'DESC' );
					$query_args['meta_key'] = $this->sort_meta_key['discount'];
				}
				break;
			case 'za_discount':
				if( isset($this->sort_meta_key['discount']) ) {
					$query_args['orderby'] = array( 'meta_value_num' => 'DESC', 'date' => 'DESC' );
					$query_args['meta_key'] = $this->sort_meta_key['discount'];
				}
				break;
			case 'az_start_date':
				if( isset($this->sort_meta_key['start_date']) ) {
					$query_args['orderby'] = array( 'meta_value' => 'ASC', 'date' => 'DESC' );
					$query_args['meta_key'] = $this->sort_meta_key['start_date'];
				}
				break;
			case 'za_start_date':
				if( isset($this->sort_meta_key['start_date']) ) {
					$query_args['orderby'] = array( 'meta_value' => 'DESC', 'date' => 'DESC' );
					$query_args['meta_key'] = $this->sort_meta_key['start_date'];
				}
				break;
			default:
		}
	}
	private function parse_tax_query( &$query_args, $data ) {
		$tax_query = array();
		if( isset($data['category_slug']) && !empty( $data['category_slug'] ) ) {
			$tax_query[] = array(
				'taxonomy' => $this->taxonomy_cat,
				'field'    => 'slug',
				'terms'    => $data['category_slug'],
				'include_children' => 0,
			);
		}
		if( isset($data['taxonomy_not_in']) && !empty( $data['taxonomy_not_in'] ) ) {
			$tax_query[] = array(
				'taxonomy'  => $this->taxonomy_cat,
				'field'     => 'term_id',
				'terms'    => $data['taxonomy_not_in'],
				'operator' => 'NOT IN',
			);
		}
		// Any taxonomy
		if( isset($data['taxonomy_slug']) && !empty( $data['taxonomy_slug'] ) ) {
			$taxonomy_slug = $data['taxonomy_slug'];
			if( is_array($taxonomy_slug) ) {
				foreach( $taxonomy_slug as $taxonomy_name => $slug_val ) {
					if( !empty($slug_val ) ) {
						$taxonomy_args = array(
							'taxonomy' => $taxonomy_name,
							'field'    => 'slug',
							'terms'    => $slug_val,
						);
						if( ( $taxonomy_name == 'slzexploore_hotel_cat'
								|| $taxonomy_name == 'slzexploore_hotel_facility' ) && is_array( $slug_val ) ) {
							$taxonomy_args['operator'] = 'AND';
						}
						$tax_query[] = $taxonomy_args;
					}
				}
			}
		}
		if(! empty( $tax_query ) ) {
			if( count($tax_query) > 1 ) {
				$tax_query['relation'] = 'AND';
			}
			$query_args["tax_query"] = $tax_query;
		}
	}
	private function parse_meta_key( &$query_args, $data ){
		$meta_query = array();
		if( isset($data['meta_key']) && !empty( $data['meta_key'] ) ) {
			$meta_key_arr = $data['meta_key'];
			if( is_array($meta_key_arr) ) {
				//multi key
				foreach( $meta_key_arr as $key => $val ) {
					$meta_query[] = array(
						'key'     => $key,
						'value'   => $val,
					);
				}
			} else {
				//single key
				$meta_query[] = array(
					'key'     => $data['meta_key'],
					'value'   => $data['meta_value']
				);
			}
		}
		if( isset($data['meta_key_compare']) && !empty( $data['meta_key_compare'] ) ) {
			$meta_key_arr = $data['meta_key_compare'];
			foreach( $meta_key_arr as $value ) {
				$meta_query[] = $value;
			}
		}
		if(! empty( $meta_query ) ) {
			if( count( $meta_query ) > 1 ) {
				$meta_query['relation'] = 'AND';
			}
			$query_args["meta_query"] = $meta_query;
		}
	}
	public function get_taxonomy_params( $post_type, $post_taxonomy, $atts ) {
		$taxonomy_arr = array();
		foreach( $post_taxonomy as $key ) {
			$taxonomy = $post_type . '_' . $key;
			$tax_slug = $key . '_slug';
			if( isset( $atts[$tax_slug] ) && !empty( $atts[$tax_slug] ) ) {
				if( is_array($atts[$tax_slug])) {
					$atts[$tax_slug] = array_filter($atts[$tax_slug]);
				}
				$taxonomy_arr[$taxonomy] = $atts[$tax_slug];
				
			}
		}
		return $taxonomy_arr;
	}
	/**
	 * Get the title.
	 * 
	 * @param array $html_options   title_format: 1$ - title, 2$ - permalink.
	 * @param string $echo          Default "false"
	 * @return string
	 */
	public function get_title( $html_options = array(), $echo = false ) {
		$format = '%1$s';
		if( isset( $html_options['title_format'] ) ) {
			$format = $html_options['title_format'];
		}
		$limit = absint(Slzexploore_Core::get_value($this->attributes, 'title_length'));
		$is_limit = Slzexploore_Core::get_value($html_options, 'is_limit');
		$title = $this->title;
		if( $is_limit && $limit ){
			// cut title by limit
			$title = wp_trim_words($this->title, $limit );
		}
		$output = sprintf( $format, esc_attr( $title ), esc_url( $this->permalink ) );
		if( $echo ) {
			echo wp_kses_post( $output );
		} else {
			return $output;
		}
	}
	/**
	 * Get featured image.
	 * 
	 * @param array $html_options   image_format: 1$ - img, 2$ - url, 3$ - thumb_href_class
	 * @param string $thumb_type    Type "large" or "small". Default "large"
	 * @param string $echo          Default "false"
	 * @return string
	 */
	public function get_featured_image( $html_options = array(), $thumb_type = 'large', $echo = false ) {
		$output = $thumb_img = '';
		$format = '%1$s';
		if( isset( $html_options['image_format'] ) ) {
			$format = $html_options['image_format'];
		}
		$href_class = Slzexploore_Core::get_value( $html_options, 'thumb_href_class' );
		$thumb_class = Slzexploore_Core::get_value( $html_options, 'thumb_class', 'img-responsive' );
		$thumb_size = $this->attributes['thumb-size'][$thumb_type];
		if( $this->has_thumbnail ) {
			$thumb_id = get_post_thumbnail_id( $this->post_id );
			// regenerate if not exist.
			$helper = new Slzexploore_Core_Helper();
			$helper->regenerate_attachment_sizes($thumb_id, $thumb_size);
			$thumb_img = wp_get_attachment_image( $thumb_id, $thumb_size, false, array('class' => $thumb_class ) );
		}else {
			$thumb_img = Slzexploore_Core_Util::get_no_image( $this->attributes['thumb-size'], $this->cur_post, $thumb_type );
		}

		//1: img, 2: url, 3: url class
		$output = sprintf( $format, $thumb_img, $this->permalink, $href_class );
		if( $echo ) {
			echo wp_kses_post( $output );
		} else {
			return $output;
		}
	}
	/**
	 * Get the excerpt
	 * 
	 * @param array $html_options   excerpt_format: 1$ - excerpt.
	 * @param string $echo          Default "false"
	 * @return string
	 */
	public function get_excerpt( $html_options = array(), $echo = false ) {
		$format = '%1$s';
		if( isset( $html_options['excerpt_format'] ) ) {
			$format = $html_options['excerpt_format'];
		}
		if( isset($html_options['post_description']) && $html_options['post_description'] ) {
			$excerpt = $html_options['post_description'];
		} else {
			$excerpt = get_the_excerpt();
		}

		$output = sprintf( $format, $excerpt );
		if( isset( $html_options['extend_desc'])) {
			$output = $output . $html_options['extend_desc'];
		}
		if( !$echo ) {
			return wp_kses_post($output);
		}
		echo wp_kses_post($output);
	}
	public function get_content( $html_options = array(), $echo = false ){
		$format = '%1$s';
		if( isset( $html_options['content_format'] ) ) {
			$format = $html_options['content_format'];
		}
		$more_link_format = '<a href="%s"><button class="btn btn-green"><span>%s<span></button></a>';
		if( isset( $html_options['content_more_link_format'] ) ) {
			$more_link_format = $html_options['content_more_link_format'];
		}
		$more_link_text = Slzexploore_Core::get_value( $html_options, 'content_more_link_text', esc_html__('Read more', 'slzexploore-core') );
		$more_link_text = sprintf( $more_link_format, $this->permalink, $more_link_text );
		$content = apply_filters('the_content', get_the_content( $more_link_text ));

		$output = sprintf( $format, $content );
		if( $echo ) {
			echo wp_kses_post( $output );
		} else {
			return $output;
		}
	}
	/**
	 * Get post date
	 * 
	 * @param array $html_options   date_format: 1$ - date, 2$ - permalink.
	 * @param string $echo          Default "false"
	 * @return string
	 */
	public function get_date( $html_options = array(), $echo = false ) {
		if( ! $this->is_show( $this->attributes, 'show_date') ) {
			return '';
		}
		$format = '%1$s';
		if( isset( $html_options['date_format'] ) ) {
			$format = $html_options['date_format'];
		}
		$date = get_the_date();

		$output = sprintf( $format, esc_html( $date ), $this->permalink );
		if( $echo ) {
			echo wp_kses_post( $output );
		} else {
			return $output;
		}
	}
	/**
	 * Get the author
	 * 
	 * @param array $html_options   author_format: 1$ - author, 2$ - author href.
	 * @param string $echo          Default "false"
	 * @return string
	 */
	public function get_author( $html_options = array(), $echo = false ){
		if( ! $this->is_show( $this->attributes, 'show_author') ) {
			return '';
		}
		//show
		$format = '%1$s';
		if( isset( $html_options['author_format'] ) ) {
			$format = $html_options['author_format'];
		}

		$post_author = $this->cur_post->post_author;
		$url = get_author_posts_url( $post_author );
		$author = get_the_author_meta('display_name', $post_author );

		$output = sprintf( $format, esc_html( $author ), esc_url( $url ) );
		if( $echo ) {
			echo wp_kses_post( $output );
		} else {
			return $output;
		}
	}
	/**
	 * Get post view counts
	 * 
	 * @param array $html_options   Format: 1$ - view counts, 2$ - permalink.
	 * @param string $echo          Default "false"
	 * @return string
	 */
	public function get_views( $html_options = array(), $echo = false ) {
		if( ! $this->is_show( $this->attributes, 'show_views') ) {
			return '';
		}
		$format = '%1$s';
		if( isset( $html_options['view_format'] ) ) {
			$format = $html_options['view_format'];
		}

		$output = sprintf( $format, $this->get_post_view(), $this->permalink );
		if( $echo ) {
			echo wp_kses_post( $output );
		} else {
			return $output;
		}
	}
	/**
	 * Get post comment counts
	 * 
	 * @param array $html_options Format: 1$ - comment counts, 2$ - comment link.
	 * @param string $echo
	 * @return string
	 */
	public function get_comments( $html_options = array(), $echo = false ) {
		if( ! $this->is_show( $this->attributes, 'show_comments') ) {
			return '';
		}
		$output = '';
		$format = '%1$s';
		if( isset( $html_options['comment_format'] ) ) {
			$format = $html_options['comment_format'];
		}

		if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			$output = sprintf( $format, get_comments_number(), get_comments_link( $this->post_id ) );
		}
		if( $echo ) {
			echo wp_kses_post( $output );
		} else {
			return $output;
		}
	}
	public function get_reviews( $html_options = array(), $echo = false ) {
		if( ! $this->is_show( $this->attributes, 'show_reviews') ) {
			return '';
		}
		$output = '';
		$format = '%1$s/%2$s';
		if( isset( $html_options['review_format'] ) ) {
			$format = $html_options['review_format'];
		}
	
		if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			$comment_count = get_comments_number();
			$comment = sprintf( _n('%s', '%s', $comment_count, 'slzexploore-core'), $comment_count );
			$rating = $this->get_rating();
			if( $rating ) {
				$output = sprintf( $format, $rating, $comment, get_comments_link( $this->post_id ) );
			}
		}
		if( $echo ) {
			echo wp_kses_post( $output );
		} else {
			return $output;
		}
	}
	public function get_rating( $post_id = '', $rating_field = '' ){
		if( empty($post_id) ) {
			$post_id = $this->post_id;
		}
		if( empty( $rating_field ) ) {
			$rating_field = $this->post_rating_key;
		}
		$comments = get_comments( array('post_id' => $post_id) );
		$cmt_number = 0;
		$rate_number = 0;
		foreach($comments as $cmt){
			$rating = get_comment_meta( $cmt->comment_ID, $rating_field, true);
			if($rating){
				$rate_number += intval($rating);
				$cmt_number ++;
			}
		}
		if($cmt_number == 0){
			$cmt_number = 1;
		}
		$rate_number = $rate_number/$cmt_number;
		$rate_number = round($rate_number, 1);
		$sub_rate = substr($rate_number, 2);
		if($sub_rate){
			if(intval($sub_rate) < 5){
				$rate_number = substr($rate_number, 0, 1);
			}
			else{
				$rate_number = substr($rate_number, 0, 2).'5';
			}
		}
	
		return $rate_number;
	}
	/**
	 * Get more button
	 * 
	 * @param array $html_options Format: 1$ - button content, 2$ - link.
	 * @param string $echo
	 * @return string
	 */
	public function get_btn_more( $html_options = array(), $echo = false ) {
		$btn_content = Slzexploore_Core::get_value( $this->attributes, 'btn_content', esc_html__('More', 'slzexploore-core') );

		$format = '<a href="%2$s">%1$s</a>';
		if( isset( $html_options['btn_more_format'] ) ) {
			$format = $html_options['btn_more_format'];
		}

		if( $echo ) {
			printf( $format, esc_html( $btn_content ), $this->permalink );
		} else {
			return sprintf( $format, esc_attr( $btn_content ), $this->permalink );
		}
	}
	public function get_full_post_class( $class = '', $post_id = '' ) {
		if( empty( $post_id ) ) {
			$post_id = $this->post_id;
		}
		return join( ' ', get_post_class( $class, $post_id ) );
	}
	public function get_post_class( $class = '', $post_id = '' ) {
		if( empty( $post_id ) ) {
			$post_id = $this->post_id;
		}
		if ( $class ) {
			if ( ! is_array( $class ) ) {
				$class = preg_split( '#\s+#', $class );
			}
			$classes = array_map( 'esc_attr', $class );
		}
		$classes[] = 'post-' . $post_id;
		$classes[] = get_post_type( $post_id );
		return join( ' ', $classes );
	}
	public function get_post_view( $post_id = '' ) {
		if( empty( $post_id ) ) {
			$post_id = $this->post_id;
		}
		$count_key = $this->post_view_key;
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
	public function get_current_taxonomy( $taxonomy = '' ) {
		if( empty( $taxonomy ) ) {
			$taxonomy = $this->taxonomy_cat;
		}
		$result = array();
		$terms = get_the_terms( $this->post_id, $taxonomy );
		if ($terms && ! is_wp_error($terms)) {
			$result = current( $terms );
		}
		return (array)$result;
	}
	public function get_taxonomy_slug( $taxonomy = '', $seperate = ' ' ) {
		if( empty( $taxonomy ) ) {
			$taxonomy = $this->taxonomy_cat;
		}
		$result = array();
		$terms = get_the_terms( $this->post_id, $taxonomy );
		if ($terms && ! is_wp_error($terms)) {
			foreach( $terms as $term ) {
				$result[] = $term->slug;
			}
		}
		$result = implode( $seperate, $result );
		return $result;
	}
	public function is_video() {
		return get_post_format() === 'video';
	}
	public function is_audio() {
		return get_post_format() === 'audio';
	}
	public function is_show( $atts, $fields ) {
		if( !isset( $atts[$fields] ) || ( isset( $atts[$fields] ) && $atts[$fields] != 'no' ) ) {
			return true;
		}
		return false;
	}
	
	public function recalc_found_posts($query, $offset) {
		$found_posts = $query->found_posts;
		if( $offset ) {
			return $found_posts - $offset;
		}
		return $found_posts;
	}
	public function get_custom_post_id_by_slug( $post_type, $slug ) {
		$id = '';
		if( !empty( $slug ) ) {
			$slug_args = array(
				'post_status'      => 'publish',
				'post_type'        => $post_type,
				'name'             => $slug,
				'suppress_filters' => false,
			);
			$p = get_posts($slug_args);
			if( $p ) {
				$id = $p[0]->ID;
			}
		}
		return $id;
	}
	public function parse_list_to_array( $name, $listIDs = array() ) {
		$res = array();
		if( !empty( $listIDs) ){
			foreach($listIDs as $value){
				if(!empty($value)){
					$res[] = $value[$name];
				}
			}
		}
		return $res;
	}
	public function parse_cat_slug_to_post_id( $taxonomy, $slug, $post_type ) {
		$id = array();
		if( !empty( $slug ) ) {
			$args = array(
				'post_type'         => $post_type,
				'post_status'       => 'publish',
				'posts_per_page'    => -1,
				$taxonomy           => $slug,
			);
			$posts = get_posts($args);
			if( $posts ) {
				foreach( $posts as $val){
					$id[] = $val->ID;
				}
			}
		}
		return $id;
	}
}