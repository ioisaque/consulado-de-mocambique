<?php
/**
 * Widget Gallery class.
*
* @since 1.0
*/
class Slzexploore_Widget_Gallery extends WP_Widget {

	public function __construct() {
		$widget_ops = array( 'classname' => 'widget_slzexploore_gallery', 'description' => esc_html__( "A list image of tour or accommodation post type.", 'exploore') );
		parent::__construct( 'slzexploore_gallery', esc_html_x( 'SLZ: Gallery', 'Gallery widget', 'exploore' ), $widget_ops );
	}

	function form( $instance ) {
		$widget_default = array(
				'style'           => '',
				'title'           => esc_html__( "Gallery", 'exploore') ,
				'limit_post'      => '6',
				'cat_id'          => '',
				'posttype'        => '',
				'column'          => 'column-3'

		);
		$instance         = wp_parse_args( (array) $instance, $widget_default );
		$style            = $instance ['style'];
		$title            = $instance ['title'];
		$limit_post       = $instance ['limit_post'];
		$cat_id           = $instance ['cat_id'];
		$posttype         = $instance ['posttype'];
		$column           = isset( $instance['column'] ) ? $instance['column']:'';
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id('style') ); ?>"><?php esc_html_e( 'Style', 'exploore' );?></label>
			<select class="widefat" id="<?php echo esc_attr( $this->get_field_id('style') ); ?>" name="<?php echo esc_attr( $this->get_field_name('style') ); ?>" >
				<option value=""><?php esc_html_e( 'Landing Page', 'exploore' );?></option>
				<option value="gallery"<?php if( $style == 'gallery' ) echo " selected"; ?>><?php esc_html_e( 'Gallery', 'exploore' );?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php esc_html_e( 'Title', 'exploore' ) ?>
			<input type="text" name="<?php echo esc_attr($this->get_field_name ( 'title' )); ?>" id="<?php echo esc_attr($this->get_field_id ( 'title' )); ?>" value="<?php echo esc_attr( $title ); ?>" class="widefat"/></label>
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'limit_post' )); ?>"><?php esc_html_e( 'Number Item', 'exploore' ) ?>
			<input type="text" name="<?php echo esc_attr($this->get_field_name ( 'limit_post' )); ?>" id="<?php echo esc_attr($this->get_field_id ( 'limit_post' )); ?>" value="<?php echo esc_attr( $limit_post ); ?>" class="widefat"/></label>
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'cat_id' )); ?>"><?php esc_html_e( 'Category ID', 'exploore' ) ?>
			<input type="text" name="<?php echo esc_attr($this->get_field_name ( 'cat_id' )); ?>" id="<?php echo esc_attr($this->get_field_id ( 'cat_id' )); ?>" value="<?php echo esc_attr( $cat_id ); ?>" class="widefat"/></label>
			<span><?php esc_html_e( 'Example : 1, 2, 3', 'exploore'); ?></span>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id('column') ); ?>"><?php esc_html_e( 'Choose Column', 'exploore' );?></label>
			<select class="widefat" id="<?php echo esc_attr( $this->get_field_id('column') ); ?>" name="<?php echo esc_attr( $this->get_field_name('column') ); ?>" >
				<option value="column-3"<?php if( $column == 'column-3' ) echo " selected"; ?>><?php esc_html_e( '3 Column', 'exploore' );?></option>
				<option value="column-4"<?php if( $column == 'column-4' ) echo " selected"; ?>><?php esc_html_e( '4 Column', 'exploore' );?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id('posttype') ); ?>"><?php esc_html_e( 'Choose Post Type', 'exploore' );?></label>
			<select class="widefat" id="<?php echo esc_attr( $this->get_field_id('posttype') ); ?>" name="<?php echo esc_attr( $this->get_field_name('posttype') ); ?>" >
			<?php
				$arr_post_type = array(
					'slzexploore_hotel' => esc_html__( 'Accommodation', 'exploore' ),
					'slzexploore_car'           => esc_html__( 'Car', 'exploore' ),
					'slzexploore_cruise'        => esc_html__( 'Cruise', 'exploore' ),
					'slzexploore_tour'          => esc_html__( 'Tour', 'exploore' ),
					'slzexploore_gallery'       => esc_html__( 'Gallery', 'exploore' )
				);
				foreach( $arr_post_type as $key=>$value ){
					$selected = '';
					if( $posttype == $key ){
						$selected = 'selected';
					}
					printf('<option value="%1$s" %2$s>%3$s</option>', $key, $selected, $value);
				}
			?>
			</select>
		</p>
		<?php
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$params = array(
			'style',
			'title',
			'limit_post',
			'cat_id',
			'posttype',
			'column'
		);
		foreach( $params as $item ) {
			$instance[$item] = strip_tags( $new_instance[$item] );
		}
		return $instance;
	}
	function widget( $args, $instance ) {
		$title_filter = apply_filters( 'widget_title', empty($instance['title']) ? '' : $instance['title'], $instance );
		extract( $args );
		$default = array(
			'style'           => '',
			'title'           => '',
			'limit_post'      => '',
			'cat_id'          => '',
			'posttype' 		  => '',
			'column' 		  => ''
		);
		$instance = wp_parse_args( (array) $instance, $default );
		$column   = $instance['column'];
		$posttype = $instance['posttype'];
		$style    = $instance['style'];
		$cat_id   = $instance['cat_id'];
		$atts = array(
					'limit_post' => $instance['limit_post'],
					'posttype'   => $posttype
				);
		if( !empty( $cat_id ) ){
			$arr_cat_id = explode( ',', rtrim( $cat_id, ',' ) );
			$category_slug = array();
			foreach( $arr_cat_id as $value ){
				if( !empty( $value ) ){
					$term = Slzexploore_Core_Com::get_tax_options_by_id( $value, $posttype . '_cat' );
					if( $term ){
						$category_slug[] = $term->slug;
					}
				}
			}
			if( !empty( $category_slug ) ){
				$atts['category_slug'] = $category_slug;
			}
		}
		echo wp_kses_post( $before_widget );
			if( $posttype == 'slzexploore_car' ){
				$model = new Slzexploore_Core_Car();
			}
			elseif( $posttype == 'slzexploore_cruise' ){
				$model = new Slzexploore_Core_Cruise();
			}
			elseif( $posttype == 'slzexploore_tour' ){
				$model = new Slzexploore_Core_Tour();
			}
			elseif( $posttype == 'slzexploore_gallery' ){
				$model = new Slzexploore_Core_Gallery();
			}
			else{
				$model = new Slzexploore_Core_Accommodation();
			}
			$model->init( $atts );
			$cls_widget = 'gallery-widget';
			if( $column == 'column-3'){
				$cls_widget = 'destination-widget';
			}
			$cls_content = '';
			$html_options = array(
								'html_format' => '<li><a class="thumb" href="%2$s">%1$s</a></li>'
							);
			if( $style ){
				$cls_content = 'main-gallery-fancybox';
				$html_options = array(
					'html_format'  => '<li class="gallery-content">%1$s</li>',
					'gallery_item' => '<a href="%1$s" data-fancybox-group="gallery-%2$s" class="wp-gallery %3$s fancybox thumb">
											%4$s
										</a>'
				);
			}
			if( !empty( $title_filter ) ) {
				echo wp_kses_post( $before_title );
				echo esc_html( $title_filter );
				echo wp_kses_post( $after_title );
			}
			?>
			<div class="<?php echo esc_attr( $cls_widget ); ?> widget">
				<div class="content-widget <?php echo esc_attr( $cls_content ); ?>">
					<ul class="list-unstyled list-inline">
						<?php
							if( $style ){
								$model->render_gallery_widget_style_2( $html_options);
							}
							else{
								$model->render_gallery_widget( $html_options);
							}
						?>
					</ul>
				</div>
			</div>
			<?php
		echo wp_kses_post( $after_widget );
	}
}