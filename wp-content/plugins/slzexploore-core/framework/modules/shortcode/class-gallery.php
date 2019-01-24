<?php
class Slzexploore_Core_Gallery extends Slzexploore_Core_Custom_Post_Model {
	public $uniq_id;
	private $post_type = 'slzexploore_gallery';

	public function __construct() {
		$this->meta_attributes();
		$this->set_meta_attributes();
		$this->post_meta_prefix = $this->post_type . '_';
		$this->taxonomy_cat = 'slzexploore_gallery_cat';
	}
	public function meta_attributes() {
		$meta_atts = array( 
			'url'             => '',
			'gallery_ids'     => ''
		);
		$this->post_meta_atts = $meta_atts;
	}
	public function set_meta_attributes() {
		$meta_arr = array();
		$meta_label_arr = array();
		foreach( $this->post_meta_atts as $att => $name ){
			$key = $att;
			$meta_arr[$key] = '';
			$meta_label_arr[$key] = $name;
		}
		$this->post_meta_def = $meta_arr;
		$this->post_meta = $meta_arr;
		$this->post_meta_label = $meta_label_arr;
	}
	public function init( $atts = array(), $query_args = array() ) {
		$uniq_id = Slzexploore_Core::make_id();
		$defaults = array(
			'layout'               => '',
			'offset_post'          => '0',
			'limit_post'           => '-1',
			'category_slug'        => '',
			'style'                => '',
			'layout_extra'         => '',
			'arrows'               => 'true',
			'title'                => '',
			'column'               => '4',
			'images'               => '',
			'images_all'           => '',
			'extra_class'          => '',
			'block_class'          => $uniq_id,
			'image_size'           => 'medium',
		);
		$this->attributes = Slzexploore_Core::set_meta_defaults($defaults, $atts);
		if ( !empty($this->attributes['layout']) && !empty($this->attributes['style']) ) {
			$layout = $this->attributes['layout'];
			if( $this->attributes['style'] == 'image_slider' ){
				$layout = $this->attributes['layout'] . '_' . $this->attributes['image_size'];
			}
			$layout = $layout . '_' . $this->attributes['style'];
			if( $this->attributes['layout_extra'] ) {
				$layout .= '_' . $this->attributes['layout_extra'];
			}
			$this->get_thumb_size($layout);
		}
		// query
		$default_args = array(
			'post_type' => $this->post_type,
		);
		$query_args = array_merge( $default_args, $query_args );
		// setting
		$this->setting( $query_args);
	}
	public function set_post_options_defaults( $atts ) {
		$default = array(
			'html_format' => '',
			'html_format_nav' => '',
			'thumb_class'  => '',
			'thumb_href_class'  => '',
			'get_thumb_src'  => false,
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
	public function setting( $query_args ){
		if( !isset( $this->attributes['uniq_id'] ) ) {
			$this->attributes['uniq_id'] = $this->post_type . '_' .Slzexploore_Core::make_id();
		}
		
		// query
		$this->query = $this->get_query( $query_args, $this->attributes );
		$this->post_count = 0;
		if( $this->query->have_posts() ) {
			$this->post_count = $this->query->post_count;
		}
	}
	public function reset(){
		wp_reset_postdata();
	}
	/**
	 * Render html to shortcode
	 *
	 */
	public function render_gallery( $options = array() ) {
		$class_item_masonry = array(
				'1' => 'grid-item--big',
				'2' => 'grid-item--medium',
				'3' => 'img-small pdr',
				'4' => 'img-small pdl',
				'5' => 'grid-item--width2',
			);
		$counter = 0;

		if (!empty($this->attributes['style']) && $this->attributes['style'] == 'masonry_grid') {
			$options['get_thumb_src'] = true;
		}

		$imagesArray = !empty($this->attributes['images']) ? $this->attributes['images'] : "";
		$images = explode(',', $imagesArray);
		if ( !empty($images) ) {
			foreach ($images as $key => $image) {
				$html_format = $options['html_format'];
				$output = $this->get_gallery_featured_image( $image, 'large', false, $options );
				if( $output ) {
					if (!empty($this->attributes['style']) && $this->attributes['style'] == 'image_grid') {
						$img_src = wp_get_attachment_image_src( $image, 'full' );
						printf( $html_format,
								$output,
								esc_url($img_src[0])
						);
					}
					else{
						$counter ++;
						if ($counter > 5) {
							$counter = 1;
						}
						printf( $html_format,
								$output,
								$class_item_masonry[$counter]
						);
					}
				}
			}
		}

	}

	public function render_gallery_regions( $options = array() ) {
		$count = 0;
		$custom_css = '';
		$info = urldecode($this->attributes['images_all']);
		$info = json_decode($info);
		if ( !empty($info) ) {
			foreach ($info as $data) {
				$count++;
				if ( empty($data->img) ) {
					continue;
				} elseif ( !empty($data->img) ) {
					$get_attached_file = get_attached_file($data->img);
					if ( !file_exists($get_attached_file) ) {
						continue;
					}
				}
				$icon = '';
				$image_fit = wp_get_attachment_image($data->img, 'full', array( "class" => "img-responsive" ) );
				$html_format = $options['html_format'];
				if( !empty($data->icon_type) && $data->icon_type == '02' && !empty($data->icon_fw) ) {
					$icon = $data->icon_fw;
				}elseif( !empty($data->icon_ex) ) {
					$icon = $data->icon_ex;
				}
				if ( !empty($data->position_top) && (int) $data->position_top) {
					$custom_css .= sprintf('.gallery_%1$s.sc_gallery .a-fact-image-wrapper .a-fact-image .icons.icons-%2$s { top: %3$s; }',
									$this->attributes['block_class'], $count, esc_attr( (int)$data->position_top.'%' )
								);
				}
				if ( !empty($data->position_left) ) {
					$custom_css .= sprintf('.gallery_%1$s.sc_gallery .a-fact-image-wrapper .a-fact-image .icons.icons-%2$s { left: %3$s; }',
									$this->attributes['block_class'], $count, esc_attr( (int)$data->position_left.'%' )
								);
				}
				$url = '';
				$link_atts = '';
				if ( isset( $data->url ) && !empty( $data->url ) ) {
					$vc_link = vc_build_link( $data->url );
					if( !empty( $vc_link['url'] ) ){
						$url = esc_url($vc_link['url']);
					}
					if( !empty( $vc_link['title'] ) ){
						$link_atts .= 'title="'.esc_attr($vc_link['title']).'" ';
					}
					if( !empty( $vc_link['target'] ) ){
						$link_atts .= 'target="'. esc_attr($vc_link['target']).'"';
					}
				}
				printf( $html_format,
					$icon,
					$count,
					$image_fit,
					$url,
					$link_atts
				);
			}
		}
		if( $custom_css ) {
			do_action( 'slzexploore_core_add_inline_style', $custom_css );
		}
	}
	
	private function get_thumb_size($layout = '') {
		$params = Slzexploore_Core::get_params( 'block-image-size', $layout );
		$this->attributes['thumb-size'] = Slzexploore_Core_Util::get_thumb_size( $params, $this->attributes );
	}

	public function get_gallery_featured_image( $idThumb, $thumb_type = 'large', $echo = false, $options = array() ) {
		$out = $thumb_img = $thumb_class = '';
		$thumb_size = $this->attributes['thumb-size'][$thumb_type];
		
		if( empty( $options['thumb_href_class'] ) ) {
			$options['thumb_href_class'] = 'media-image';
		}
		if( !empty( $options['thumb_class'] ) ) {
			$thumb_class = $options['thumb_class'];
		}

		if( !empty($idThumb) ) {
			$helper = new Slzexploore_Core_Helper();
			$helper->regenerate_attachment_sizes($idThumb, $thumb_size);
			if ( !empty( $options['get_thumb_src'] ) && $options['get_thumb_src'] == true) {
				$thumb_img = wp_get_attachment_image_src( $idThumb, $thumb_size );
				$thumb_img = $thumb_img[0];
			} else {
				$thumb_img = wp_get_attachment_image( $idThumb, $thumb_size, false, array('class' => 'img-responsive '.$thumb_class.'') );
			}
		}
		$out = $thumb_img;

		if( !$echo ) {
			return $out;
		}
		echo wp_kses_post( $out );
	}
	public function render_gallery_widget( $html_options = array() ) {
		$params = Slzexploore_Core::get_params( 'block-image-size', 'wg_gallery' );
		$this->attributes['thumb-size'] = Slzexploore_Core_Util::get_thumb_size( $params, $this->attributes );
		while ( $this->query->have_posts() ) {
			$this->query->the_post();
			$this->loop_index();
			$url = $this->post_meta['url'];
			if( empty( $url ) ) {
				$url = $this->permalink;
			}
			printf( $html_options['html_format'],
					$this->get_featured_image( array(), 'small' ),
					esc_url( $url )
			);
		}
		$this->reset();
	}
	public function render_gallery_widget_style_2( $html_options = array() ) {
		$params = Slzexploore_Core::get_params( 'block-image-size', 'wg_gallery' );
		$this->attributes['thumb-size'] = Slzexploore_Core_Util::get_thumb_size( $params, $this->attributes );
		while ( $this->query->have_posts() ) {
			$this->query->the_post();
			$this->loop_index();
			printf( $html_options['html_format'],
					$this->render_post_images( $this->post_id, $html_options['gallery_item'] )
			);
		}
		$this->reset();
	}
	public function render_post_images( $post_id, $format ){
		$output = '';
		$image_ids = array();
		$gallery_ids = get_post_meta( $post_id, $this->post_meta_prefix . 'gallery_ids', true );
		if( !empty( $gallery_ids ) ){
			$image_ids = explode( ',', rtrim( $gallery_ids, ',' ) );
		}
		$thumbnail_id = get_post_thumbnail_id( $post_id );
		if( !empty( $thumbnail_id ) ){
			array_unshift( $image_ids, $thumbnail_id );
		}
		if( !empty( $image_ids ) ){
			foreach( $image_ids as $k => $image_id ){
				$cls_position = 'glry-absolute';
				if( $k == 0 ){
					$cls_position = 'glry-relative';
				}
				$image_url = wp_get_attachment_image_src( $image_id, $this->attributes['thumb-size']['large'] );
				$image     = wp_get_attachment_image( $image_id, $this->attributes['thumb-size']['small'], false,
													array( "class" => "img-responsive" ) );
				$output .= sprintf( $format, $image_url[0], $post_id, $cls_position, $image );
			}
		}
		return $output;
	}
}