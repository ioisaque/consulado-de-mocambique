<?php
/**
 * Data Com class.
 * 
 * @since 1.0
 */
class Slzexploore_Core_Com {
	public static function get_contact_form(){

		$contact_form_arr = array( '' => esc_html__( '-None-', 'slzexploore-core' ));
		$args = array (
					'post_type'     => 'wpcf7_contact_form',
					'post_per_page' => -1,
					'status'        => 'publish',
				);
		$post_arr = get_posts( $args );
		foreach( $post_arr as $post ){
			$title = ( !empty( $post->post_title ) )? $post->post_title : $post->post_name;
			$contact_form_arr[$post->ID] =  $title;
		}
		return $contact_form_arr;
	}
	public static function get_regist_sidebars( $exclude = array() ) {
		global $wp_registered_sidebars;
		$result = array();
		foreach( (array)$wp_registered_sidebars as $key => $sidebar ) {
			if( empty($exclude) || ( $exclude && ! in_array( $key, $exclude ) ) ) {
				$result[$key] = $sidebar['name'];
			}
			
		}
		return $result;
	}
	public static function get_user_login2id( $args = array(), $params = array() ) {
		$result = array();
		$users = get_users( $args );
		if(isset($params['empty'])) {
			$result[$params['empty']] = '';
		}
		if( $users ) {
			foreach( $users as $row ) {
				$result[$row->user_login] = $row->ID;
			}
		}
		return $result;
	}
	public static function get_user_id2login( $args = array(), $params = array() ) {
		$result = array();
		$users = get_users( $args );
		if(isset($params['empty'])) {
			$result[$params['empty']] = '';
		}
		if( $users ) {
			
			foreach( $users as $row ) {
				$result[$row->ID] = $row->display_name;
			}
		}
		return $result;
	}
	/**
	 * Get list page (post_title=>ID)
	 * 
	 */
	public static function get_page_title2id( $args = array() ) {
		$result = array();
		if( ! empty( $args ) ) {
			$records = get_pages( $args );
		} else {
			$records = get_pages();
		}
		if( $records ) {
			foreach( $records as $row ) {
				$key = $row->post_title;
				$val = $row->ID;
				$result[$key] = $val;
			}
		}
		return $result;
	}
	/**
	 * Get list page (ID=>post_title)
	 * 
	 */
	public static function get_page_id2title( $args = array() ) {
		$result = array();
		if( ! empty( $args ) ) {
			$records = get_pages( $args );
		} else {
			$records = get_pages();
		}
		if( $records ) {
			foreach( $records as $row ) {
				$result[$row->ID] = $row->post_title;
			}
		}
		return $result;
	}
	/**
	 * Get list post (post_name => post_title)
	 *
	 * $args = array('post_type' => 'my_posttype', 'post_status' => 'publish', ...);
	 *
	 * @param array $args
	 * @return array
	 */
	public static function get_post_id2title( $args = array(), $options = array(), $is_empty = true ) {
		$empty = '';
		if( isset( $options['empty'] ) ) {
			$empty = $options['empty'];
		}
		$defaults = array(
			'posts_per_page'   => -1,
			'post_status'      => 'publish',
			'suppress_filters' => false,
		);
		$args = array_merge( $defaults, $args );
		$result = array();
		if($is_empty) {
			$result = array( '' => $empty );
		}
		$records = get_posts( $args );
		if( $records ) {
			foreach( $records as $row ) {
				$key = $row->ID;
				$val = $row->post_title;
				$val = empty($val) ? $row->post_name : $val;
				$result[$key] = $val;
			}
		}
		return $result;
	}
	/**
	 * Get list post (post_name => post_title)
	 *
	 * $args = array('post_type' => 'my_posttype', 'post_status' => 'publish', ...);
	 *
	 * @param array $args
	 * @return array
	 */
	public static function get_post_name2title( $args = array(), $options = array() ) {
		$empty = '';
		if( isset( $options['empty'] ) ) {
			$empty = $options['empty'];
		}
		$defaults = array(
			'posts_per_page'   => -1,
			'post_status'      => 'publish',
			'suppress_filters' => false,
		);
		$args = array_merge( $defaults, $args );
		$result = array( '' => $empty );
		$records = get_posts( $args );
		if( $records ) {
			foreach( $records as $row ) {
				$key = $row->post_name;
				$val = $row->post_title;
				$val = empty($val) ? $key : $val;
				$result[$key] = $val;
			}
		}
		return $result;
	}
	/**
	 * Get list post (post_title => post_name)
	 *
	 * $args = array('post_type' => 'my_posttype', 'post_status' => 'publish', ...);
	 *
	 * @param array $args
	 * @return array
	 */
	public static function get_post_title2name( $args = array(), $options = array() ) {
		$empty = '';
		if( isset( $options['empty'] ) ) {
			$empty = $options['empty'];
		}
		$defaults = array(
			'posts_per_page'   => -1,
			'post_status'      => 'publish',
			'suppress_filters' => false,
		);
		$args = array_merge( $defaults, $args );
		$result = array( $empty => '');
		$records = get_posts( $args );
		if( $records ) {
			foreach( $records as $row ) {
				$key = $row->post_title;
				$val = $row->post_name;
				$key = empty($key) ? $val : $key;
				$result[$key] = $val;
			}
		}
		return $result;
	}
	/**
	 * Get list post (post_title (post_name) => ID)
	 *
	 * $args = array('post_type' => 'my_posttype', 'post_status' => 'publish', ...);
	 *
	 * @param array $args
	 * @return array
	 */
	public static function get_post_title2id( $args = array(), $options = array() ) {
		$empty = '';
		if( isset( $options['empty'] ) ) {
			$empty = $options['empty'];
		}
		$defaults = array(
			'posts_per_page'   => -1,
			'post_status'      => 'publish',
			'suppress_filters' => false,
		);
		$args = array_merge( $defaults, $args );
		$result = array( $empty => '');
		$records = get_posts( $args );
		if( $records ) {
			foreach( $records as $row ) {
				$key = empty($row->post_title) ? $row->post_name : $row->post_title;
				$val = $row->ID;
				$result[$key] = $val;
			}
		}
		return $result;
	}
	/**
	 * Get list display name (show title if empty display name) of post type
	 * 
	 */
	public static function get_post_display_name( $args = array(), $options = array() ) {
		$empty = '';
		$meta='';
		if( isset( $options['empty'] ) ) {
			$empty = $options['empty'];
		}
		if( isset( $options['meta'] ) ) {
			$meta = $options['meta'];
		}
		$defaults = array(
			'posts_per_page'   => -1,
			'post_status'      => 'publish',
			'suppress_filters' => false,
		);
		$args = array_merge( $defaults, $args );
		$result = array( $empty => '');
		$records = get_posts( $args );
		if( $records ) {
			foreach( $records as $row ) {
				$option = get_post_meta( $row->ID, $meta, true );
				$display_name = Slzexploore_Core::get_value( $option, 'display_name' );
				if ( empty( $display_name ) ){
				$key = $row->post_title;
				}else{
					$key = $display_name;
				}
				$val = $row->post_name;
				$result[$key] = $val;
			
			}
		}
		return $result;
		
	}
	/**
	 * Get all taxonomy
	 * 
	 */
	public static function get_all_tax_options( $taxonomy ) {
		$result = array();
		$terms = get_terms( $taxonomy );
		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
			$result = $terms;
		}
		return $result;
	}
	/**
	 * Get taxonomy by post
	 * 
	 */
	public static function get_tax_options_by_post( $post_id, $taxonomy, $options = array() ) {
		$result = '';
		$terms = get_the_terms( $post_id, $taxonomy );
		if ($terms && ! is_wp_error($terms)) {
			$term_slugs_arr = array();
			foreach ($terms as $term) {
				$term_slugs_arr[] = $term->slug;
			}
			$result = $term_slugs_arr;
			if( isset( $options['delimiter'] )) {
				$terms_slug_str = join( $options['delimiter'], $term_slugs_arr);
				$result = $terms_slug_str;
			}
		}
		return $result;
	}
	/**
	 * Get taxonomy by id
	 * 
	 */
	public static function get_tax_options_by_id( $term_id, $taxonomy ) {
		$ret_val = '';
		if( ! empty( $term_id ) ) {
			$term = get_term_by('term_id', $term_id, $taxonomy );
			if ( ! empty( $term ) && ! is_wp_error( $term ) ) {
				$ret_val = $term;
			}
		}
		return $ret_val;
	}
	/**
	 * Get taxonomy by slug
	 *
	 */
	public static function get_tax_options_by_slug( $value, $taxonomy ) {
		$ret_val = '';
		if( ! empty( $value ) ) {
			$term = get_term_by('slug', $value, $taxonomy );
			if ( ! empty( $term ) && ! is_wp_error( $term ) ) {
				$ret_val = $term;
			}
		}
		return $ret_val;
	}
	/**
	 * Get list taxonomy (slug=>name(count))
	 * 
	 */
	public static function get_tax_options( $taxonomy, $args = array(), $params = array(), $exclude_slugs = array() ) {
		$default = array("orderby"=>"name", "hierarchical"=>false, "hide_empty" => true);
		$args = array_merge( $default, $args );
		$terms = get_terms( $taxonomy, $args);
		$options = array();
		if(isset($params['empty'])) {
			$options[""] = "";
		}
		if (is_array( $terms ) ) {
			foreach ( $terms as $term ) {
				$value = "$term->name";
				if(isset($params['show_count'])) {
					$value = "$term->name ($term->count)";
				}
				if( ! isset($exclude_slugs[$term->slug]) ) {
					$options["$term->slug"] = $value;
				}
			}
		}
		return $options;
	}
	/**
	 * Get list taxonomy (name=>slug)
	 * 
	 */
	public static function get_tax_options2slug( $taxonomy, $params = array(), $args = array() ) {
		$def_args = array('orderby'=>'name', 'hierarchical'=>false, 'hide_empty' => true);
		$args = array_merge( $def_args, $args );
		$terms = get_terms( $taxonomy, $args);
		$options = array();
		if(isset($params['empty'])) {
			$options[$params['empty']] = '';
		}
		if (is_array( $terms ) ) {
			foreach ( $terms as $term ) {
				$key = $term->name;
				if( isset($options[$key]) ) {
					$key = $key . ' (' . $term->slug . ')';
				}
				$options[$key] = $term->slug;
			}
		}
		return $options;
	}
	/**
	 * Get list taxonomy (slug=>name)
	 * 
	 */
	public static function get_tax_options2name( $taxonomy, $params = array(), $args = array() ) {
		$def_args = array('orderby'=>'name', 'hierarchical'=>false, 'hide_empty' => true);
		$args = array_merge( $def_args, $args );
		$terms = get_terms( $taxonomy, $args);
		$options = array();
		if(isset($params['empty'])) {
			$options[''] = $params['empty'];
		}
		if (is_array( $terms ) ) {
			foreach ( $terms as $term ) {
				$options[$term->slug] = $term->name;
			}
		}
		return $options;
	}

	/**
	 * Query related posts
	 */
	public static function get_query_related_posts( $post_id, $args = array(), $taxonomy = 'category' ) {
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
				'suppress_filters' => false,
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
	/**
	 * Return category name
	 *
	 * @param $category_id
	 *
	 * @return string
	 */
	public static function get_product_category_by_id( $category_id ) {
		$term = get_term_by( 'id', $category_id, 'product_cat', 'ARRAY_A' );
		return $term['name'];
	}
	/**
	 * Get category list.(name => slug)
	 * 
	 */
	static $sw_category2slug_walker_buffer = array();
	public static function get_category2slug_array( $all_category = true, $args = array() ) {
		
		/*if ( is_admin() === false ) {
			return;
		}*/
		$args = array (
			'hide_empty' => 0,
			'number' => 1000 
		);
		if ( empty( self::$sw_category2slug_walker_buffer ) ) {
			$categories = get_categories( $args );
			$category_walker = new Slzexploore_Core_Category2Slug_Walker();
			$category_walker->walk( $categories, 4 );
			self::$sw_category2slug_walker_buffer = $category_walker->sw_buffer;
		}
		
		if ( $all_category === true ) {
			$categories_buffer ['- All categories -'] = '';
			return array_merge( $categories_buffer, self::$sw_category2slug_walker_buffer );
		} else {
			return self::$sw_category2slug_walker_buffer;
		}
	}
	public static function get_category2name_array( $all_category = true ) {
		$category = array();
		$category_temp = self::get_category2slug_array( $all_category );
		if( $category_temp ) {
			foreach($category_temp as $name => $slug ) {
				$category[$slug] = $name;
			}
		}
		return $category;
	}
	// get post ID from name
	public static function get_post_name2id( $name, $post_type ) {
		$args = array(
			'name'             => $name,
			'post_type'        => $post_type,
			'post_status'      => 'publish',
			'posts_per_page'   => 1,
			'suppress_filters' => false,
		);
		$posts = get_posts( $args );
		if( $posts ) {
			return $posts[0]->ID;
		}
		return false;
	}
	// get post name from ID
	public static function get_post_id2name( $id ) {
		$posts = get_post( $id );
		if( $posts ) {
			return $posts->post_name;
		}
		return false;
	}
	/**
	 * Get list taxonomy (id=>name), 'hide_empty' => false
	 * 
	 */
	public static function get_tax_options_id2name( $taxonomy, $params = array(), $args = array() ) {
		$def_args = array('orderby'=>'name', 'hierarchical'=>false, 'hide_empty' => true);
		$args = array_merge( $def_args, $args );
		$terms = get_terms( $taxonomy, $args);
		$options = array();
		if(isset($params['empty'])) {
			$options[''] = $params['empty'];
		}
		if (is_array( $terms ) ) {
			foreach ( $terms as $term ) {
				$options[$term->term_id] = $term->name;
			}
		}
		return $options;
	}
	/**
	 * Get term in hierarchical (name=> slug)
	 *
	 * @param array $args
	 * @param array $options
	 */
	public static function get_hierarchical_term2slug( $args = array(), $options = array() ) {
		Slzexploore_Core_Term_Tree::$arr_term2slug_walker_buffer = array();
		return Slzexploore_Core_Term_Tree::get_hierarchical_term2slug_tree( $args, $options );
	}
	/**
	 * Get term in hierarchical (slug=> name)
	 *
	 * @param array $args
	 * @param array $options
	 */
	public static function get_hierarchical_term2name( $args = array(), $options = array() ) {
		Slzexploore_Core_Term_Tree::$arr_term2name_walker_buffer = array();
		return Slzexploore_Core_Term_Tree::get_hierarchical_term2name_tree( $args, $options );
	}
}
class Slzexploore_Core_Term_Tree {
	/**
	 * Get taxonomy list with hierarchical.(name => slug)
	 *
	 */
	static $arr_term2slug_walker_buffer = array();
	public static function get_hierarchical_term2slug_tree( $args = array(), $options = array() ) {

		$defaults = array (
			'hide_empty' => 0,
			'number' => 1000,
		);
		$args = array_merge( $defaults, $args );
		if ( empty( self::$arr_term2slug_walker_buffer ) ) {
			$categories = get_categories( $args );
			$category_walker = new Slzexploore_Core_Category2Slug_Walker();
			$category_walker->walk( $categories, 4 );
			self::$arr_term2slug_walker_buffer = $category_walker->sw_buffer;
		}

		if ( isset($options['empty']) ) {
			$categories_buffer [$options['empty']] = '';
			return array_unshift( self::$arr_term2slug_walker_buffer, $categories_buffer );
		} else {
			return self::$arr_term2slug_walker_buffer;
		}
	}
	/**
	 * Get taxonomy list with hierarchical.(slug => name)
	 *
	 */
	static $arr_term2name_walker_buffer = array();
	public static function get_hierarchical_term2name_tree( $args = array(), $options = array() ) {

		$defaults = array (
			'hide_empty' => 0,
			'number' => 1000,
		);
		$args = array_merge( $defaults, $args );
		if ( empty( self::$arr_term2name_walker_buffer ) ) {
			$categories = get_categories( $args );
			$category_walker = new Slzexploore_Core_Category2Name_Walker();
			$category_walker->walk( $categories, 4 );
			self::$arr_term2name_walker_buffer = $category_walker->sw_buffer;
		}

		if ( isset($options['empty']) ) {
			$categories_buffer [''] = $options['empty'];
			return array_unshift( self::$arr_term2name_walker_buffer, $categories_buffer );
		} else {
			return self::$arr_term2name_walker_buffer;
		}
	}
}
/**
 * Category Walker Class
 * array( name=> slug )
 * @since 1.0
 *
 */
class Slzexploore_Core_Category2Slug_Walker extends Walker {
	public $tree_type = 'category';
	public $db_fields = array ('parent' => 'parent', 'id' => 'term_id');

	public $sw_buffer = array();

	function start_lvl( &$output, $depth = 0, $args = array() ) {
	}

	function end_lvl( &$output, $depth = 0, $args = array() ) {
	}

	function start_el( &$output, $category, $depth = 0, $args = array(), $id = 0 ) {
		$key = str_repeat(' - ', $depth) .  $category->name;
		if( isset($this->sw_buffer[$key])) {
			$key = $key . ' (' . $category->slug .')';
		}
		$this->sw_buffer[$key] = $category->slug;
	}

	function end_el( &$output, $page, $depth = 0, $args = array() ) {
	}

}
/**
 * Category Walker Class
 * array( slug=> name )
 * @since 1.0
 *
 */
class Slzexploore_Core_Category2Name_Walker extends Walker {
	public $tree_type = 'category';
	public $db_fields = array ('parent' => 'parent', 'id' => 'term_id');

	public $sw_buffer = array();

	function start_lvl( &$output, $depth = 0, $args = array() ) {
	}

	function end_lvl( &$output, $depth = 0, $args = array() ) {
	}

	function start_el( &$output, $category, $depth = 0, $args = array(), $id = 0 ) {
		$this->sw_buffer[$category->slug] = str_repeat(' - ', $depth) .  $category->name;
	}

	function end_el( &$output, $page, $depth = 0, $args = array() ) {
	}

}