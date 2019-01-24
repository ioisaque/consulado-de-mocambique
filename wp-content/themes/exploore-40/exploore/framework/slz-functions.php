<?php
//get rating
// get number of rating of post
if( ! function_exists( 'slzexploore_get_rating_number' ) ) :
function slzexploore_get_rating_number( $post_id, $post_type ){
	$comments = get_comments( array('post_id' => $post_id) );
	$cmt_number = $rate_number = 0;
	if( empty( $comments ) ){
		return $rate_number;
	}
	foreach($comments as $cmt){
		$rating = get_comment_meta( $cmt->comment_ID, $post_type . '_rating', true);
		if($rating){
			$rate_number += intval($rating);
			$cmt_number ++;
		}
	}
	if($cmt_number == 0){
		return $rate_number;
	}
	$rate_number = $rate_number/$cmt_number;
	$rate_number = round($rate_number, 1);
	$sub_rate = substr($rate_number,2);
	if($sub_rate){
		if(intval($sub_rate) < 5){
			$rate_number = substr($rate_number, 0, 1);
		}
		else{
			$rate_number = intval(substr($rate_number, 0, 1)) + 1;
		}
	}
	return $rate_number;
}
endif;
// Set post view
add_action('wp_head', 'slzexploore_postview_set');
if( ! function_exists( 'slzexploore_postview_set' ) ) :

	function slzexploore_postview_set() {
		global $post;
		$post_types = array('post', 'slzexploore_tour', 'slzexploore_hotel');
		if( $post ) {
			$post_id = $post->ID;
			if( in_array(get_post_type(), $post_types) && is_single() ) {
				$count_key = 'slzexploore_postview_number';
				$count = get_post_meta( $post_id, $count_key, true );
				if( $count == '' ) {
					$count = 0;
					delete_post_meta( $post_id, $count_key );
					add_post_meta( $post_id, $count_key, '0' );
				} else {
					$count++;
					update_post_meta( $post_id, $count_key, $count );
				}
			}
		}
	}
endif;

// Get post view
if( ! function_exists( 'slzexploore_postview_get' ) ) :

	function slzexploore_postview_get( $post_id ) {
		$view_text = esc_html__( 'view', 'exploore' );
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
endif;
// get review rating
if( ! function_exists( 'slzexploore_get_rating' ) ) :
function slzexploore_get_rating( $post_id = '' ){
	if( empty($post_id) ) {
		$post_id = get_the_ID();
	}
	$post_type = get_post_type( $post_id );
	$rating_field = $post_type . '_rating';
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
endif;
//-----------------------------------------
if ( ! function_exists( 'slzexploore_is_custom_post_type_archive' ) ) :
	function slzexploore_is_custom_post_type_archive() {
		if( is_post_type_archive('slzexploore_hotel') || is_tax( 'slzexploore_hotel_cat' ) || is_tax( 'slzexploore_hotel_facility' ) || is_tax( 'slzexploore_hotel_location' ) ) {
			return 'hotel';
		} else if( is_post_type_archive('slzexploore_tour') || is_tax( 'slzexploore_tour_cat' ) || is_tax( 'slzexploore_tour_location' ) || is_tax( 'slzexploore_tour_tag' )) {
			return 'tour';
		} else if( is_post_type_archive('slzexploore_faq') || is_tax( 'slzexploore_faq_cat' ) ) {
			return 'teams';
		} else if( is_post_type_archive('slzexploore_car') || is_tax( 'slzexploore_car_cat' ) || is_tax( 'slzexploore_car_location' ) ) {
			return 'car';
		} else if( is_post_type_archive('slzexploore_cruise') || is_tax( 'slzexploore_cruise_cat' ) || is_tax( 'slzexploore_cruise_location' ) || is_tax( 'slzexploore_cruise_facility' ) ) {
			return 'cruise';
		}
		return false;
	}
endif;
// Breadcrumb
if ( ! function_exists( 'slzexploore_get_breadcrumb' ) ) :
	function slzexploore_get_breadcrumb()
	{
		if ( SLZEXPLOORE_WOOCOMMERCE_ACTIVE && get_post_type() == 'product' ) 
		{
			$breadcrumbs = new WC_Breadcrumb();
			$breadcrumbs->add_crumb( esc_html_x( 'Home', 'breadcrumb', 'exploore' ), apply_filters( 'woocommerce_breadcrumb_home_url', esc_url( home_url('/') ) ) );
		} else {
			$breadcrumbs = new Slzexploore_Breadcrumb();
			$breadcrumbs->add_crumb( esc_html_x( 'Home', 'breadcrumb', 'exploore' ), apply_filters( 'slzexploore_breadcrumb_home_url', esc_url( home_url('/') ) ) );
		}
		return $breadcrumbs->generate();
	}
endif;

remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );

if( !function_exists('slzexploore_regex') ) :

	function slzexploore_regex($string, $pattern = false, $start = "^", $end = "")
	{
		if(!$pattern) return false;

		if($pattern == "url")
		{
			$pattern = "!$start((https?|ftp)://(-\.)?([^\s/?\.#-]+\.?)+(/[^\s]*)?)$end!";
		}
		else if($pattern == "mail")
		{
			$pattern = "!$start\w[\w|\.|\-]+@\w[\w|\.|\-]+\.[a-zA-Z]{2,4}$end!";
		}
		else if($pattern == "image")
		{
			$pattern = "!$start(https?(?://([^/?#]*))?([^?#]*?\.(?:jpg|gif|png)))$end!";
		}
		else if(strpos($pattern,"<") === 0)
		{
			$pattern = str_replace('<',"",$pattern);
			$pattern = str_replace('>',"",$pattern);

			if(strpos($pattern,"/") !== 0) { $close = "\/>"; $pattern = str_replace('/',"",$pattern); }
			$pattern = trim($pattern);
			if(!isset($close)) $close = "<\/".$pattern.">";

			$pattern = "!$start\<$pattern.+?$close!";

		}

		preg_match($pattern, $string, $result);

		if(empty($result[0]))
		{
			return false;
		}
		else
		{
			return $result;
		}

	}
endif;
//-----------------------------------------
// Paging
if(!function_exists('slzexploore_paging_nav')) :
	/**
	 * Displays a page pagination if more posts are available than can be displayed on one page
	 * @param string $pages pass the number of pages instead of letting the script check the gobal paged var
	 * @return string $output returns the pagination html code
	 */
	function slzexploore_paging_nav( $pages = '', $current_query = '' )
	{
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
		
		if($pages == '') {
			global $wp_query;
			$pages = $wp_query->max_num_pages;
			if( ! $pages ) {
				$pages = 1;
			}
		}
		$method = "get_pagenum_link";
		if(is_single()) {
			$method = 'slzexploore_post_pagination_link';
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

	function slzexploore_post_pagination_link($link)
	{
		$url =  preg_replace('!">$!','',_wp_link_page($link));
		$url =  preg_replace('!^<a href="!','',$url);
		return $url;
	}

	function slzexploore_get_pagenum_link( $pagenum = 1, $escape = true, $base = null) {
		global $wp_rewrite;

		$pagenum = (int) $pagenum;
	
		$request = $base ? remove_query_arg( 'paged', $base ) : remove_query_arg( 'paged' );
	
		$home_root = parse_url( esc_url( home_url( '/') ) );
		$home_root = ( isset($home_root['path']) ) ? $home_root['path'] : '';
		$home_root = preg_quote( $home_root, '|' );
	
		$request = preg_replace('|^'. $home_root . '|i', '', $request);
		$request = preg_replace('|^/+|', '', $request);
	
		if ( !$wp_rewrite->using_permalinks() || is_admin() ) {
			$base = trailingslashit( esc_url( home_url( '/' ) ) );
	
			if ( $pagenum > 1 ) {
				$result = add_query_arg( 'paged', $pagenum, $base . $request );
			} else {
				$result = $base . $request;
			}
		} else {
			$qs_regex = '|\?.*?$|';
			preg_match( $qs_regex, $request, $qs_match );
	
			if ( !empty( $qs_match[0] ) ) {
				$query_string = $qs_match[0];
				$request = preg_replace( $qs_regex, '', $request );
			} else {
				$query_string = '';
			}
	
			$request = preg_replace( "|$wp_rewrite->pagination_base/\d+/?$|", '', $request);
			$request = preg_replace( '|^' . preg_quote( $wp_rewrite->index, '|' ) . '|i', '', $request);
			$request = ltrim($request, '/');
	
			$base = trailingslashit( esc_url( home_url( '/' ) ) );
	
			if ( $wp_rewrite->using_index_permalinks() && ( $pagenum > 1 || '' != $request ) )
				$base .= $wp_rewrite->index . '/';
	
			if ( $pagenum > 1 ) {
				$request = ( ( !empty( $request ) ) ? trailingslashit( $request ) : $request ) . user_trailingslashit( $wp_rewrite->pagination_base . "/" . $pagenum, 'paged' );
			}
	
			$result = $base . $request . $query_string;
		}
	
		/**
		 * Filter the page number link for the current request.
		 *
		 * @since 2.5.0
		 *
		 * @param string $result The page number link.
		 */
		$result = apply_filters( 'get_pagenum_link', $result );
	
		if ( $escape )
			return esc_url( $result );
		else
			return esc_url_raw( $result );
	}
endif;
//-----------------------------------------
// Post Navigation
if ( ! function_exists( 'slzexploore_post_nav' ) ) :
	/**
	 * Display navigation to next/previous post when applicable.
	*
	*/
	function slzexploore_post_nav() {
		global $post;
		// Don't print empty markup if there's nowhere to navigate.
		$previous = ( is_attachment() ) ? get_post( $post->post_parent ) : get_adjacent_post( false, '', true );
		$next     = get_adjacent_post( false, '', false );
		if ( ! $next && ! $previous )
			return;
		?>
		<nav class="post-navigation row" >
			<div class="col-md-12">
				<div class="nav-links">
					<div class="pull-left prev-post">
					<?php previous_post_link( '%link', _x( '<span class="meta-nav">&larr;</span> %title', 'Previous post link', 'exploore' ) ); ?>
					</div>
					<div class="pull-right next-post">
					<?php next_post_link( '%link', _x( '%title <span class="meta-nav">&rarr;</span>', 'Next post link', 'exploore' ) ); ?>
					</div>
				</div><!-- .nav-links -->
			</div>
		</nav><!-- .navigation -->
		<?php
	}
endif;
//-----------------------------------------
// Get link of blog content ( post-format: link)
if ( ! function_exists( 'slzexploore_get_link_url' ) ) :
	/**
	 * Return the post URL.
	 *
	 * @uses get_url_in_content() to get the URL in the post meta (if it exists) or
	 * the first link found in the post content.
	 *
	 * Falls back to the post permalink if no URL is found in the post.
	 *
	 *
	 * @return string The Link format URL.
	 */
	function slzexploore_get_link_url() {
		$has_url = '';
		if( get_post_format() == 'link') {
			$content = get_the_content();
			$has_url = get_url_in_content( $content );
		}
		return ( $has_url ) ? $has_url : apply_filters( 'the_permalink', get_permalink() );
	}
endif;
//-----------------------------------------
// Get Format Date
if ( ! function_exists( 'slzexploore_post_date' ) ) :
	function slzexploore_post_date() {
		$output = '';
		$format_string = 
		'<div class="date">
			<h1 class="day"><a href="%4$s">%1$s</a></h1>
			<div class="month"><a href="%4$s">%2$s</a></div>
			<div class="year"><a href="%4$s">%3$s</a></div>
		</div>';
		if( is_singular() ) {
			$format_string =
			'<div class="date">
				<h1 class="day">%1$s</h1>
				<div class="month">%2$s</div>
				<div class="year">%3$s</div>
			</div>';
		}
		$day = get_the_time('d');
		$month = get_the_time('M');
		$year = get_the_time('Y');
		$output = sprintf( $format_string, $day, $month, $year, esc_url( slzexploore_get_link_url() ) );
		return $output;
	}
endif;

//-----------------------------------------
// Get page template slug
if ( ! function_exists( 'slzexploore_get_page_template_slug' ) ) :
	function slzexploore_get_page_template_slug( $post_id = null ) {
		if (function_exists("get_page_template_slug")){
			return get_page_template_slug( $post_id );
		}
		$post = get_post( $post_id );
		if ( ! $post || 'page' != $post->post_type )
			return false;
		$template = get_post_meta( $post->ID, '_wp_page_template', true );
		if ( ! $template || 'default' == $template )
			return '';
		return $template;
	}
endif;
//-----------------------------------------
// Get css to show/hide sidebar
if ( ! function_exists( 'slzexploore_get_container_css' ) ) :
	function slzexploore_get_container_css( $show_sidebar = false ) {
		/* Global variable from theme option */
		do_action('slzexploore_page_options');
		$def_sidebar = Slzexploore::get_option('slz-sidebar-layout');
		$def_sidebar_id = Slzexploore::get_option('slz-sidebar');
		$post_type = get_post_type();
		$sidebar = $sidebar_id = '';
		$has_sidebar = '';
		if( is_single() ) {
			if( $post_type == 'product' ) {
				$sidebar = Slzexploore::get_option('slz-shop-sidebar-layout');
				$sidebar_id = Slzexploore::get_option('slz-shop-sidebar');
			}
			elseif( $post_type == 'slzexploore_tour' ) {
				$sidebar = Slzexploore::get_option('slz-tour-sidebar-layout');
				$sidebar_id = Slzexploore::get_option('slz-tour-sidebar');
			}
			elseif( $post_type == 'slzexploore_hotel' ) {
				$sidebar = Slzexploore::get_option('slz-hotel-sidebar-layout');
				$sidebar_id = Slzexploore::get_option('slz-hotel-sidebar');
			}
			elseif( $post_type == 'slzexploore_car' ) {
				$sidebar = Slzexploore::get_option('slz-car-sidebar-layout');
				$sidebar_id = Slzexploore::get_option('slz-car-sidebar');
			}
			elseif( $post_type == 'slzexploore_cruise' ) {
				$sidebar = Slzexploore::get_option('slz-cruises-sidebar-layout');
				$sidebar_id = Slzexploore::get_option('slz-cruises-sidebar');
			} else if( $post_type == 'post' ) {
				$sidebar = Slzexploore::get_option('slz-blog-sidebar-layout');
				$sidebar_id = Slzexploore::get_option('slz-blog-sidebar');
			}
		} else if( is_archive() ) {
			if( SLZEXPLOORE_WOOCOMMERCE_ACTIVE ) {
				if( !is_shop()  && $post_type == 'product' ) {
					$sidebar = Slzexploore::get_option('slz-shop-sidebar-layout');
					$sidebar_id = Slzexploore::get_option('slz-shop-sidebar');
				}
			}
			if( (is_tax() || is_category() || is_archive()) && $post_type != 'product' ) {
				$archive_type = slzexploore_is_custom_post_type_archive();
				if( $archive_type == 'hotel' ){
					$sidebar = Slzexploore::get_option('slz-hotel-archive-sidebar-layout');
					$sidebar_id = Slzexploore::get_option('slz-hotel-archive-sidebar');
				}
				elseif( $archive_type == 'tour' ){
					$sidebar = Slzexploore::get_option('slz-tour-archive-sidebar-layout');
					$sidebar_id = Slzexploore::get_option('slz-tour-archive-sidebar');
				}
				elseif( $archive_type == 'car' ){
					$sidebar = Slzexploore::get_option('slz-car-archive-sidebar-layout');
					$sidebar_id = Slzexploore::get_option('slz-car-archive-sidebar');
				}
				elseif( $archive_type == 'cruise' ){
					$sidebar = Slzexploore::get_option('slz-cruises-archive-sidebar-layout');
					$sidebar_id = Slzexploore::get_option('slz-cruises-archive-sidebar');
				}
				if( empty($sidebar)) {
					$sidebar = 'right';
				}
			}
		} else if( is_search() ) {
			$sidebar = 'right';
		}
		if( empty($sidebar)) {
			$sidebar = $def_sidebar;
		}
		if( empty($sidebar_id)) {
			$sidebar_id = $def_sidebar_id;
		}
		
		$content_css = 'col-md-8 col-xs-12';
		$sidebar_css = 'col-md-4 col-xs-12';

		if ( $sidebar == 'left' ) {
			$content_css = 'col-md-8 main-right col-xs-12';
			$sidebar_css = 'col-md-4 col-xs-12';
		} else if ( $sidebar == 'right' ) {
			$content_css = 'col-md-8 main-left col-xs-12';
			$sidebar_css = 'col-md-4 col-xs-12';
		} else {
			if( $show_sidebar ){
				$content_css = 'col-md-8 main-left col-xs-12';
				$sidebar_css = 'col-md-4 col-xs-12';
			} else {
				$content_css = 'col-md-12 col-xs-12';
				$sidebar_css = 'hide';
				$has_sidebar = 'none';
			}
		}
		$container_css = 'container';
		
		return array(
			'container_css' => $container_css,
			'content_css'   => $content_css,
			'sidebar_css'   => $sidebar_css,
			'sidebar'       => $sidebar,
			'sidebar_id'    => $sidebar_id,
			'show_sidebar'  => $has_sidebar,
		);
	}
endif;

if ( ! function_exists( 'slzexploore_get_sidebar' ) ) :
	function slzexploore_get_sidebar( $sidebar_id ) {
		if( empty($sidebar_id) ) {
			get_sidebar();
		} else {
			if ( is_active_sidebar( $sidebar_id ) ) {
				$is_close_tag = false;
				echo '<div class="sidebar-wrapper">';
				dynamic_sidebar( $sidebar_id );
				echo '</div>';
			}
		}
	}
endif;
//-----------------------------------------
/**
 * Custom callback function, see comments.php
 * 
 */
if ( ! function_exists( 'slzexploore_display_comments' ) ) : 
	function slzexploore_display_comments( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment; 
		$comment_id = get_comment_ID();
		?>
		<li class="media parent" id="comment-<?php echo get_comment_ID() ?>">
			<div class="comment-item">
				<div class="media-left">
					<div class="media-image">
						<?php echo wp_kses_post( get_avatar($comment, 77) ); ?>
					</div>
					
				</div>
				<div class="media-right">
					<div class="pull-left">
						<div class="author">
							<?php
								$url    = get_comment_author_url( $comment_id );
								$author = get_comment_author( $comment_id );
								
								if ( empty( $url ) || 'http://' == $url ){
									echo esc_html( ucfirst($author) );
								}else {
								printf('<a href="%1$s" title="%2$s">%2$s</a>',
										esc_url($url),
										esc_html(ucfirst($author))
									);
								}
							?>
						</div>
					</div>
					<div class="pull-right time"><i class="fa fa-clock-o"></i>
						<span>
							<?php echo slzexploore_display_comments_date(); ?>
						</span>
					</div>
					<div class="clearfix"></div>
					<div class="des"><?php comment_text() ?></div>
					<?php
						$queried_object = get_queried_object();
						$post_type = $queried_object->post_type;
						$post_type_arr =  array(
													'slzexploore_hotel' => 'slzexploore_hotel_rating',
													'slzexploore_tour'          => 'slzexploore_tour_rating',
													'slzexploore_car' => 'slzexploore_car_rating',
													'slzexploore_cruise'          => 'slzexploore_cruise_rating',
												);
						if( isset($post_type_arr[$post_type]) ){
							$rating = get_comment_meta( $comment_id, $post_type_arr[$post_type], true);
							if($rating){
								printf('<p class="border-none stars-rating">
											<span class="review star-%1$s active">%1$s</span>
										</p>', $rating);
							}
						}
						else{
							$comment_reply_link_args = array(
								'depth'  => $depth, 
								'before' => '',
								'after'  => ''
							);
							comment_reply_link( array_merge ( $args, $comment_reply_link_args ) );
						}
					?>
				</div>
			</div>
		<!-- </li>-->
		<?php
	}
endif;

if ( ! function_exists( 'slzexploore_display_comments_date' ) ) : 
	function slzexploore_display_comments_date() {
		$cmt_time = get_comment_time( 'U' );
		$current_time = current_time( 'timestamp' );
		$subtract_time = $current_time - $cmt_time;
		$days = ( 60*60*24*5 ); // 5 days
		if( $subtract_time > $days ){
			$res = get_comment_date();
		}
		else {
			$res = human_time_diff( get_comment_time( 'U' ), current_time( 'timestamp' ) );
			$res .= esc_html__( ' ago', 'exploore' );
		}
		return $res;
	}
endif;
//-----------------------------------------
// Unregister Newsletter widget
if(SLZEXPLOORE_NEWSLETTER_ACTIVE){
	function slzexploore_unregister_newsletter_widgets() {
		unregister_widget( 'NewsletterWidget' );
	}
	add_action( 'widgets_init', 'slzexploore_unregister_newsletter_widgets', 20 );
	add_action('widgets_init', create_function('', 'return register_widget("Slzexploore_Widget_Newsletter");'));
}
//-----------------------------------------
/**
 * Add item for user profile
 * To get contact items of user
 *      get_user_meta ( int $user_id, string $key = '', bool $single = false )
 */
function slzexploore_add_item_user_profile($items) {

	// Add new item
	$links = Slzexploore::get_params('author-social-links');
	foreach($links as $k=>$v){
		$items[$k] = $v;
	}
	return $items;
}
add_filter('user_contactmethods', 'slzexploore_add_item_user_profile');
//-----------------------------------------
// Change logo in login page
if ( ! function_exists( 'slzexploore_login_style' ) ) {
	function slzexploore_login_style() {
		$logo = slzexploore::get_option('slz-logo-header', 'url');
		if( $logo ) {
			$custom_css = '.login h1 a { 
								background : url('.esc_url($logo).') center no-repeat; 
								width: 100%; 
							}';
			wp_enqueue_style( 'slzexploore-login-style', get_template_directory_uri()."/assets/admin/css/slzexploore-admin-style.css", false, SLZEXPLOORE_THEME_VER, 'all' );
			wp_add_inline_style( 'slzexploore-login-style', $custom_css );
		}
	}
}
add_action( 'login_enqueue_scripts', 'slzexploore_login_style' );
if ( ! function_exists( 'slzexploore_login_logo_url' ) ) {
	function slzexploore_login_logo_url() {
		return esc_url( home_url( '/' ) );
	}
}
add_filter( 'login_headerurl', 'slzexploore_login_logo_url' );
//-----------------------------------------
// Woocommerce
if( !function_exists( 'slzexploore_is_wishlist_page') ) {
	/**
	 * Check if current page is wishlist
	 *
	 * @return bool
	 */
	function slzexploore_is_wishlist_page() {
		if( ! SWEDUGATE_WOOCOMMERCE_WISHLIST ) {
			return false;
		}
		$wishlist_page_id = yith_wcwl_object_id( get_option( 'yith_wcwl_wishlist_page_id' ) );
		if ( ! $wishlist_page_id ) {
			return false;
		}
		return is_page( $wishlist_page_id );
	}
}
/*
 * send mail functions
 */
if ( ! function_exists('slzexploore_send_mail') ) {
	function slzexploore_send_mail( $to_address, $subject, $description, $additional_headers ) {
		//Create Email Headers
		$headers  = array( "MIME-Version: 1.0", "Content-type:text/html;charset=UTF-8" );
		$headers  = array_merge( $headers, $additional_headers );
		$message  = "<html>\n";
		$message .= "<body>\n";
		$message .= $description;
		$message .= "</body>\n";
		$message .= "</html>\n";
		$mailsent = wp_mail( $to_address, $subject, $message, $headers );
		return ($mailsent)?(true):(false);
	}
}
/*
 * send mail functions
 */
if ( ! function_exists('slzexploore_check_valid_mail') ) {
	function slzexploore_check_valid_mail( $email ) {
		$out_email = array();
		$email = explode( ',', trim( $email, ',' ) );
		if( !empty( $email ) ){
			foreach( $email as $val ){
				$val = trim( $val );
				if( !empty( $val ) && is_email( $val ) ){
					$out_email[] = $val;
				}
			}
		}
		return implode( ',', $out_email );
	}
}


/**
 * getPosts
 * @param  string $postType : post type
 * @param  array $params   	: aguments to get post
 * @return array            : posts terms and conditions
 */
if ( ! function_exists( 'slzexploore_getPosts' ) ) : 
	function slzexploore_getPosts($postType = null, $params = null, $wp_query = false) {
		$postType || $postType = 'post';
		$defaultParams = array(
			'post_type' => $postType,
			'posts_per_page' => -1,
			'suppress_filters' => false
		);
		($params != null && is_array($params)) && $defaultParams = array_merge($defaultParams, $params);
		return !$wp_query ? get_posts($defaultParams) : new WP_Query($defaultParams);
	}
endif;

/*
* getTermSimpleByPost (Related post or post tag)
* params:
* 		- post id
* 		- taxonomy: (taxonomy slug | category | post_tag)
* return: One term related by post
*/
if ( ! function_exists( 'slzexploore_getTermSimpleByPost' ) ) : 
	function slzexploore_getTermSimpleByPost( $postID, $taxonomy ) {
		if( empty( $postID ) && empty($taxonomy) ) {
			return;
		}
		$result = array();
		$terms = get_the_terms( $postID, $taxonomy );
		if ($terms && ! is_wp_error($terms)) {
			$result = current( $terms );
		}
		return (array)$result;
	}
endif;

/*
* getTermsByPost (Related post or post tag)
* params:
* 		- post id
* 		- taxonomy: (taxonomy slug | category | post_tag)
* return: all terms related by post
*/
if ( ! function_exists( 'slzexploore_getTermsByPost' ) ) : 
	function slzexploore_getTermsByPost($postID, $taxonomy) {
		return get_the_terms($postID, $taxonomy);
	}
endif;

/*
* getTermsByPost (Related post or post tag)
* params:
* 		- post id
* 		- taxonomy: (taxonomy slug | category | post_tag)
* return: all terms related by post
*/
if ( ! function_exists( 'slzexploore_getIP' ) ) : 	
	function slzexploore_getIP() {
		if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
			//check ip from share internet
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			//to check ip is pass from proxy
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}
endif;