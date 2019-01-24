<?php
Slzexploore::load_class( 'Abstract' );
class Slzexploore_Page_Controller extends Slzexploore_Abstract {
	/**
	 * Setting page
	 */
	public function meta_box_setting() {
		global $post;
		global $slzexploore_core_default_options;
		$post_id = $post->ID;
		// default
		$bg_repeat      = Slzexploore::get_params( 'background-repeat' );
		$bg_size        = Slzexploore::get_params( 'background-size' );
		$bg_position    = Slzexploore::get_params( 'background-position' );
		$bg_attachment  = Slzexploore::get_params( 'background-attachment' );
		$sidebar_layout = Slzexploore::get_params( 'sidebar-layout' );
		$slider_type    = Slzexploore::get_params( 'slider-type' );
	
		// get meta
		$page_options = get_post_meta( $post_id, 'slzexploore_page_options', true );
		//
		if( $page_options ) {
			$bg_array = array(
				'background_transparent'        => 'background_color',
				'pt_background_transparent'     => 'pt_background_color',
			);
			foreach($bg_array as $key=>$val ) {
				if( isset($page_options[$key]) && !empty($page_options[$key])) {
					$page_options[$val] = $page_options[$key];
				}
			}
		}
		// header content
		$header_content = Slzexploore::get_value($page_options, 'header_content_type');
		$header_content_display = array(
			'slider' => 'hide',
			'custom' => 'hide',
		);
		if($header_content == 1) {
			$header_content_display['slider'] = '';
		}
		if($header_content == 2) {
			$header_content_display['custom'] = '';
		}
		$header_top_menu_pos = array(
			'left'     =>  esc_html__( 'Left', 'exploore' ),
			'right'    =>  esc_html__( 'Right', 'exploore' )
		);
		//params
		$header_style = array(
			'one'   => esc_html__('Header Style 1', 'exploore'),
			'two'   => esc_html__('Header Style 2', 'exploore'),
			'three' => esc_html__('Header Style 3', 'exploore'),
			'four'  => esc_html__('Header Style 4', 'exploore'),
		);
		$header_content_type = array( 
			'' => esc_html__('None', 'exploore'),
			'1' => esc_html__('Slider', 'exploore'),
			'2' => esc_html__('Custom', 'exploore')
		);

		/**
		 * Post Type Header Content
		 */
		$posttype_support_header_content = array( 'slzexploore_tour', 'slzexploore_hotel', 'slzexploore_car', 'slzexploore_cruise' );
		$posttype_header_content_type = array(
			''  => esc_html__( 'Default', 'exploore' ),
			'1' => esc_html__( 'Slider', 'exploore' ),
			'2' => esc_html__( 'Custom Slider', 'exploore' )
		);
		$posttype_header_options      = get_post_meta( $post_id, 'slzexploore_posttype_header_options', true );
		$posttype_header_content      = Slzexploore::get_value( $posttype_header_options, 'posttype_header_content_type' );
		$posttype_header_content_display = array(
			'slider' => 'hide',
			'custom' => 'hide',
		);
		if($posttype_header_content == 1) {
			$posttype_header_content_display['slider'] = '';
		}
		if($posttype_header_content == 2) {
			$posttype_header_content_display['custom'] = '';
		}

		$params = array(
			'background-repeat'     => $bg_repeat,
			'background-attachment' => $bg_attachment,
			'background-position'   => $bg_position,
			'background-size'       => $bg_size,
			'sidebar_layout'        => $sidebar_layout,
			'slider-type'           => $slider_type,
			'regist_sidebars'       => Slzexploore_Core_Com::get_regist_sidebars(),
			'video_type'            => array( '' => esc_html__('Youtube', 'exploore'), '1' => esc_html__('Vimeo', 'exploore')),
			'show_header'           => array( '' => esc_html__('Show', 'exploore'), '1' => esc_html__('Hide', 'exploore')),
			'show'                  => array( '' => esc_html__('Hide', 'exploore'), '1' => esc_html__('Show', 'exploore')),
			'footer_style'          => array('dark' => esc_html__('Dark', 'exploore'), 'light' => esc_html__('Light', 'exploore') ),
			'header_style'          => $header_style,
			'header_content_type'   => $header_content_type,
			'header_top_menu_pos'   => $header_top_menu_pos
		);
		$this->parse_image($params, $page_options, $slzexploore_core_default_options );
		$this->render( 'page-setting', array(
			'params' => $params,
			'defaults' => $slzexploore_core_default_options,
			'page_options' => $page_options,
			'header_content_display' => $header_content_display,
			'posttype_support_header_content' => $posttype_support_header_content,
			'posttype_header_content_type'    => $posttype_header_content_type,
			'posttype_header_options'         => $posttype_header_options,
			'posttype_header_content_display' => $posttype_header_content_display,
		) );
	}
	private function parse_image( &$params, $page_options, $slzexploore_core_default_options ) {
		$image_id_keys = array(
			'bg_image'       => array('background_image', 'background_image_id' ),
			'pt_bg_image'    => array('pt_background_image', 'pt_background_image_id' )
		);
		foreach( $image_id_keys as $img_key => $img_val ) {
			$attachment = array ( 'id' => '', 'url' => '', 'class' => '' );
			$attachment['url'] = $this->get_field( $page_options, $img_val[0], $slzexploore_core_default_options );
			if( empty( $attachment['url'] ) ) {
				$attachment['class'] = 'hide';
			}
			$thumb_id = $this->get_field( $page_options, $img_val[1], $slzexploore_core_default_options );
			if( ! empty( $thumb_id )) {
				$attachment_image = wp_get_attachment_image_src($thumb_id, 'full');
				$attachment = array ( 'id' => $thumb_id, 'url' => $attachment_image[0], 'class' => '' );
			}
			$params[$img_key] = $attachment;
		}
	}
}