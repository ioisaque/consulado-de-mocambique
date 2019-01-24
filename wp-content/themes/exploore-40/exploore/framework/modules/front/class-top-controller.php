<?php
/**
 * Top Controller.
 * 
 * @since 1.0
 */
Slzexploore::load_class( 'Abstract' );

class Slzexploore_Top_Controller extends Slzexploore_Abstract {
	public function show_post_index(){
		$this->render( SLZEXPLOORE_THEME_DIR . '/index.php', array() );
	}
	public function header() {
		$this->render( 'header', array());
	}
	public function footerbottom() {
		$this->render( 'footer-bottom', array());
	}
	public function breadcrumb() {
		$this->render( 'breadcrumb', array());
	}
	public function show_post_entry_thumbnail( $args = array() ) {
		$this->render( 'entry-thumbnail', array ( 'args' => $args ) );
	}
	public function show_post_entry_meta( $args = array() ) {
		$posttags = get_the_tags();
		$category_list = get_the_category();
		$this->render( 'entry-meta', array (
			'args' => $args,
			'posttags' => $posttags,
			'category_list' => $category_list 
		) );
	}
	public function show_post_tags_meta( $args = array() ) {
		$posttags = get_the_tags();
		$this->render( 'tags-meta', array (
			'args' => $args,
			'posttags' => $posttags
		) );
	}
	public function show_post_categories_meta( $args = array() ) {
		$postcats = get_the_category();
		$this->render( 'categories-meta', array (
			'args' => $args,
			'postcats' => $postcats
		) );
	}
	public function show_searchform(){
		$this->render( 'search-form', array() );
	}
	
	public function show_post_author() {	
		$author_id = get_the_author_meta( 'ID' );
		$this->render( 'author', array (
			'author_id' => $author_id 
		) );
	}
	public function show_post_entry_video( $args = array() ) {
		if(class_exists('Slzexploore_Core_Video_Model')){
			$post_id = get_the_ID();
			$post_options = get_post_meta( $post_id, 'slzexploore_feature_video', true);
			$youtube_id = Slzexploore::get_value( $post_options, 'youtube_id' );
			$vimeo_id = Slzexploore::get_value( $post_options, 'vimeo_id' );
			$upload_video = Slzexploore::get_value( $post_options, 'upload_video' );
			if(empty($youtube_id) && empty($vimeo_id) && empty($upload_video) ){
				do_action( 'slzexploore_entry_thumbnail');
			}
			else{
				$video_model = new Slzexploore_Core_Video_Model();
				$video_model->init();
				echo ( $video_model->get_video( $post_options['video_type'] , $youtube_id, $vimeo_id , $upload_video ) );
			}
		} else {
			do_action( 'slzexploore_entry_thumbnail');
		}
	}
	public function show_page_title() {
		$this->render( 'page-title',array() );
	}
	public function show_slider() {
		$this->render( 'slider',array() );
	}
	// share post
	public function get_share_link() {
		$this->render( 'share_link', array());
	}
	// share hotel, tour, car and cruise
	public function share_custom_post() {
		$this->render( 'share_custom_post', array());
	}
	public function show_login_link() {
		$this->render( 'login-link');
	}
	public function add_comment_rating( $comment_id ) {
		$post_type_arr = array(
								'slzexploore_hotel' => 'slzexploore_hotel_rating',
								'slzexploore_tour'          => 'slzexploore_tour_rating',
								'slzexploore_car'           => 'slzexploore_car_rating',
								'slzexploore_cruise'        => 'slzexploore_cruise_rating'
							);
		$post_id = $_POST['comment_post_ID'];
		$post_type = get_post_type( $post_id );
		if ( isset( $_POST['rating'] ) && isset($post_type_arr[$post_type]) ) {
			if ( empty( $_POST['rating']) || $_POST['rating'] > 5 || $_POST['rating'] < 1 ) {
				return;
			}
			add_comment_meta( $comment_id, $post_type_arr[$post_type], (int) esc_attr( $_POST['rating'] ), true );
			
			$rating = slzexploore_get_rating_number( $post_id, $post_type );
			update_post_meta ( $post_id, $post_type_arr[$post_type], (int) esc_attr( $rating ) );
		}
	}
	public function update_review_rating( $comment_id ) {
		$comment = get_comment( $comment_id, ARRAY_A );
		$post_id = $comment['comment_post_ID'];
		$post_type = get_post_type( $post_id );
		$rating = slzexploore_get_rating_number( $post_id, $post_type );
		update_post_meta ( $post_id, $post_type.'_rating', (int) esc_attr( $rating ) );
	}

	/**
	 * Display Post Type Header Content
	 */
	public function show_posttype_header_content() {
		$this->render( 'posttype-header-content',array() );
	}
}