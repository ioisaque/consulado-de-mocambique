<?php
if( ! function_exists( 'slzexploore_core_post_pagination_link' ) ) :
	function slzexploore_core_post_pagination_link($link)
	{
		$url =  preg_replace('!">$!','',_wp_link_page($link));
		$url =  preg_replace('!^<a href="!','',$url);
		return $url;
	}
endif;

if( ! function_exists( 'slzexploore_core_get_pagenum_link' ) ) :
	function slzexploore_core_get_pagenum_link( $pagenum = 1, $base = null, $escape = true ) {
		global $wp_rewrite;
	
		$pagenum = (int) $pagenum;
	
		$request = $base ? remove_query_arg( 'paged', $base ) : remove_query_arg( 'paged' );
	
		$home_root = parse_url(home_url());
		$home_root = ( isset($home_root['path']) ) ? $home_root['path'] : '';
		$home_root = preg_quote( $home_root, '|' );
	
		$request = preg_replace('|^'. $home_root . '|i', '', $request);
		$request = preg_replace('|^/+|', '', $request);
		
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

		$base = trailingslashit( home_url() );

		if ( $wp_rewrite->using_index_permalinks() && ( $pagenum > 1 || '' != $request ) )
			$base .= $wp_rewrite->index . '/';

		if ( $pagenum > 1 ) {
			$request = ( ( !empty( $request ) ) ? trailingslashit( $request ) : $request ) . user_trailingslashit( $wp_rewrite->pagination_base . "/" . $pagenum, 'paged' );
		}

		$result = $base . $request . $query_string;
	
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

// upload images
add_action( 'wp_ajax_slzexploore_core_image_upload', 'slzexploore_core_image_upload' );
if( ! function_exists( 'slzexploore_core_image_upload' ) ) :
	function slzexploore_core_image_upload()
	{
		$submitted_file = $_FILES['slzexploore_core_upload_file'];
		$uploaded_image = wp_handle_upload( $submitted_file, array( 'test_form' => false ) );
		
		if ( isset( $uploaded_image['file'] ) ) {
			$file_name          =   basename( $submitted_file['name'] );
			$file_type          =   wp_check_filetype( $uploaded_image['file'] );
			
			$attachment_details = array(
				'guid'           => $uploaded_image['url'],
				'post_mime_type' => $file_type['type'],
				'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $file_name ) ),
				'post_content'   => '',
				'post_status'    => 'inherit'
			);
	
			$attach_id      =   wp_insert_attachment( $attachment_details, $uploaded_image['file'] );
			$attach_data    =   wp_generate_attachment_metadata( $attach_id, $uploaded_image['file'] );
			
			if( isset( $attach_data ) && !empty( $attach_data ) ) {
				// attachment is image
				wp_update_attachment_metadata( $attach_id, $attach_data );
				$thumbnail_url = slzexploore_core_get_thumbnail_url( $attach_data );
			}
			else {
				// attachment is not image
				$image_src = wp_get_attachment_image_src($attach_id, 'thumbnail' , true);
				$thumbnail_url = $image_src[0];
			}
			$ajax_response = array(
				'success'   => true,
				'url' => $thumbnail_url,
				'attachment_id'    => $attach_id
			);
			echo json_encode( $ajax_response );
			die;
		}
		else {
			$ajax_response = array( 'success' => false, 'reason' => 'Image upload failed!' );
			echo json_encode( $ajax_response );
			die;
		}
	}
endif;

if( !function_exists( 'slzexploore_core_get_thumbnail_url' ) ):
	function slzexploore_core_get_thumbnail_url( $attach_data ){
		$upload_dir         =   wp_upload_dir();
		$image_path_array   =   explode( '/', $attach_data['file'] );
		$image_path_array   =   array_slice( $image_path_array, 0, count( $image_path_array ) - 1 );
		if( isset( $attach_data['sizes']['thumbnail'] ) ) {
			$image_path      =   implode( '/', $image_path_array );
			$image_path     .=   '/' . $attach_data['sizes']['thumbnail']['file'];
		}
		else {
			$image_path      =   $attach_data['file'];
		}
		return $upload_dir['baseurl'] . '/' . $image_path ;
	}
endif;

// remove image
add_action( 'wp_ajax_remove_upload_image', 'slzexploore_core_remove_upload_image' );
if( !function_exists( 'slzexploore_core_remove_upload_image' ) ):
	function slzexploore_core_remove_upload_image() {
		$attachment_removed = false;
		if( isset( $_POST['attachment_id'] ) ) {
			$attachment_id = intval( $_POST['attachment_id'] );
			 if ( $attachment_id > 0 &&  wp_delete_attachment ( $attachment_id ) ) {
				$attachment_removed = true;
			}
		}
		$ajax_response = array(
			'attachment_removed' => $attachment_removed,
		);
		echo json_encode( $ajax_response );
		die;
	}
endif;

if( !function_exists( 'slzexploore_core_get_revolution_slider' ) ):
	function slzexploore_core_get_revolution_slider(){
		global $wpdb;
		$revolution_sliders = array( '' => esc_html('No Slider', 'slzexploore-core') );
		if( SLZEXPLOORE_REVSLIDER_ACTIVE ) {
			$db_revslider = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM '.$wpdb->prefix.'revslider_sliders %', '' ) );
			if ( $db_revslider ) {
				foreach ( $db_revslider as $slider ) {
					$revolution_sliders[$slider->alias] = $slider->title;
				}
			}
		}
		return $revolution_sliders;
	}
endif;

/*
	* get share link *
*/
if( ! function_exists( 'slzexploore_core_get_share_link' ) ) {
	
	function slzexploore_core_get_share_link() {
		$socials = Slzexploore_Core::get_theme_option('slz-social-share', 'enabled');
		if( isset($socials['placebo'] )) {
			unset($socials['placebo']);
		}
		$arr_link = array();
		$share_url = array(
			'facebook'		=> sprintf('http://www.facebook.com/sharer.php?u=%s',
									urlencode( esc_url( get_permalink() ) ) ),
			'twitter'		=> sprintf('https://twitter.com/intent/tweet?text=%s&url=%s&via=%s',
									htmlspecialchars(urlencode(html_entity_decode(get_the_title(), ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8'),
									urlencode( esc_url( get_permalink() ) ),
									urlencode( get_bloginfo( 'name' ))
								),
			'google-plus'	=> sprintf('http://plus.google.com/share?url=%s',
									urlencode( esc_url( get_permalink() ))
								),
			'pinterest'		=> sprintf('http://pinterest.com/pin/create/button/?url=%s', 
									urlencode( esc_url(get_permalink()) )
								),
			'stumbleupon'	=> sprintf('http://www.stumbleupon.com/submit?url=%s&title=%s',
									urlencode (esc_url( get_permalink()) ),
									esc_attr( get_the_title() )
								),
			'linkedin'		=> sprintf('http://www.linkedin.com/shareArticle?mini=true&url=%s',
									urlencode( esc_url( get_permalink()) )
								),
			'digg'			=> sprintf('http://digg.com/submit?url=%s&title=%s',
									 urlencode( esc_url(get_permalink())),
									 esc_attr(get_the_title())
								),
		);
		$action = 'window.open(this.href, \'Share Window\',\'left=50,top=50,width=600,height=350,toolbar=0\');';
		if( $socials ) {
			foreach($socials as $k=>$v){
				if( isset( $share_url[$k] ) ) {
					$arr_link[] = sprintf('<li><a href="%1$s" class="link-social" onclick="%2$s return false;">
											<i class="icons fa fa-%3$s"></i></a></li>',
											$share_url[$k], $action, $k );
				}
			}
			if( !empty( $arr_link ) ){
				return sprintf( '<ul class="share-social-list">%1$s</ul>', implode('', $arr_link) );
			}
		}
		return '';
	}
}
if( ! function_exists( 'slzexploore_core_add_menu_page' ) ) :
	function slzexploore_core_add_menu_page($page_title, $menu_title, $capability, $menu_slug, $function = '', $icon_url = '', $position = null)
	{
		add_menu_page($page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position);
	}
endif;

if( ! function_exists( 'slzexploore_core_add_submenu_page' ) ) :
	function slzexploore_core_add_submenu_page($parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function = '')
	{
		add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );
	}
endif;


if( ! function_exists( 'slzexploore_core_get_mail_template' ) ) :
	function slzexploore_core_get_mail_template( $obj )
	{
		if( $obj == 'tour' ){
			$filename = 'tour_confirm_email_description.html';
		}
		elseif( $obj == 'car_rent' ){
			$filename = 'car_confirm_email_description.html';
		}
		elseif( $obj == 'cruise' ){
			$filename = 'cruise_confirm_email_description.html';
		}
		else{
			$filename = 'hotel_confirm_email_description.html';
		}
		return file_get_contents( dirname( __FILE__ ) . '/mail-templates/' . $filename );
	}
endif;
if( ! function_exists( 'slzexploore_core_test_rtl' ) ) :
	function slzexploore_core_test_rtl() {
		//test RTL
		$rtl_link = esc_url(home_url('/')).'?d=ltr';
		$str_text = esc_html__('Switch to LTR', 'slzexploore-core');
		if( !is_rtl() ) {
			$rtl_link = esc_url(home_url('/')).'?d=rtl';
			$str_text = esc_html__('Switch to RTL', 'slzexploore-core');
		}
		$topbar_rtl = '<li><a href="'.$rtl_link.'" class="monney dropdown-text"><span>'.$str_text.'</span></a></li>';
		return $topbar_rtl;
	}
endif;
if( ! function_exists( 'slzexploore_core_upd_room_allow_booking' ) ) :
	function slzexploore_core_upd_room_allow_booking( $room_id ) {
		$hotel_id = get_post_meta( $room_id, 'slzexploore_room_accommodation', true);
		$args = array(
			'post_type'  => 'slzexploore_vacancy',
			'post_status'=> 'publish',
			'posts_per_page' => -1,
			'orderby'    => 'meta_value',
			'order'      => 'ASC',
			'meta_key'   => 'slzexploore_vacancy_date_from',
			'meta_query' => array(
				array(
					'relation' => 'OR',
					array(
						'key'     => 'slzexploore_vacancy_room_type',
						'value'   => $room_id
					),
					array(
						array(
							'key'     => 'slzexploore_vacancy_room_type',
							'value'   => ''
						),
						array(
							'key'     => 'slzexploore_vacancy_accommodation',
							'value'   => $hotel_id
						)
					)
				),
				array(
					'key'     => 'slzexploore_vacancy_date_to',
					'value'   => current_time('Y-m-d'),
					'compare' => '>='
				)
			)
		);
		$the_query = new WP_Query( $args );
		if ( $the_query->have_posts() ) {
			$date_string = array();
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				$post_id = get_the_ID();
				$start = get_post_meta( $post_id, 'slzexploore_vacancy_date_from', true);
				$end = get_post_meta( $post_id, 'slzexploore_vacancy_date_to', true );
				$date_string[] = Slzexploore_Core_Util::join_date_string($start, $end);
			}
			$date_string = implode(',', $date_string);
			update_post_meta ( $room_id, 'slzexploore_room_allow_booking', $date_string );
			wp_reset_postdata();
		}
		else{
			update_post_meta ( $room_id, 'slzexploore_room_allow_booking', '' );
		}
	}
endif;
if( ! function_exists( 'slzexploore_core_update_db' ) ) :
	function slzexploore_core_update_db() {
		$query_args = array(
			'post_status'    =>'publish',
			'posts_per_page' => -1,
			'post_type'      => 'slzexploore_room',
			'meta_query' => array(
                array(
                 	'key' => 'slzexploore_room_allow_booking',
                 	'compare' => 'NOT EXISTS'
                )
   			)
		);
		$query = new WP_Query( $query_args );
		if( $query->have_posts() ){
			while ( $query->have_posts() ) {
				$query->the_post();
				$id = get_the_ID();
				if( ! metadata_exists('post', $id, 'slzexploore_room_allow_booking') ){
					slzexploore_core_upd_room_allow_booking($id);
				}
			}
			wp_reset_postdata();
		}
	}
endif;
// Remove trash from bulk_actions
if( !function_exists('slzexploore_core_vacancy_remove_trash_from_bulk_actions')):
	function slzexploore_core_vacancy_remove_trash_from_bulk_actions( $actions ){
		unset( $actions[ 'trash' ] );
		return $actions;
	}
endif;
add_filter( 'bulk_actions-edit-slzexploore_vacancy', 'slzexploore_core_vacancy_remove_trash_from_bulk_actions' );

// Hook to admin_head for the CSS to be applied earlier - Hide publishing and trash button
if( !function_exists('slzexploore_core_vacancy_hide_trash_from_page')):
	function slzexploore_core_vacancy_hide_trash_from_page() {
		$screen = get_current_screen();
		if( in_array( $screen->id, array( 'slzexploore_vacancy' ) ) ) {
			echo '<style type="text/css">
					#minor-publishing,
					#major-publishing-actions #delete-action
					{ display: none; }
				</style>';
		}
	}
endif;
add_action( 'admin_head', 'slzexploore_core_vacancy_hide_trash_from_page' );

