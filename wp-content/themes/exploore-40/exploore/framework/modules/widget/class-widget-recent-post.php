<?php
/**
 * Widget_Recent_Post class.
 * 
 * @since 1.0
 */
Slzexploore::load_class( 'front.Blog' );
class Slzexploore_Widget_Recent_Post extends WP_Widget {

	public function __construct() {
		$widget_ops = array( 'classname' => 'widget_slz_recent_post', 'description' => esc_html__( "A recent posts list.", 'exploore') );
		parent::__construct( 'slzexploore_recent_post', esc_html_x( 'SLZ: Recent Posts', 'Recent posts widget', 'exploore' ), $widget_ops );
	}
	function form( $instance ) {
		$default = array(
			'title'           => esc_html__( "Recent Post", 'exploore'),
			'limit_post'      => '5',
			'show_title'      => 'on',
			'show_date'       => 'on',
			'show_views_count'   	=> 'on',
			'show_comments_count' 	=> 'on',
			'show_thumbnail'  		=> 'on',
		);
		$check_box = array(
			'show_title'   		=> esc_html__( 'Display the title', 'exploore' ),
			'show_date'      	=> esc_html__( 'Display post date', 'exploore' ),
			'show_views_count'  => esc_html__( 'Display view count', 'exploore' ),
			'show_comments_count'  => esc_html__( 'Display comment count', 'exploore' ),
			'show_thumbnail' 	   => esc_html__( 'Display thumbnail', 'exploore' ),
		);
		$instance = wp_parse_args( (array) $instance, $default );
		$title 		= esc_attr( $instance['title'] );
		$limit_post = esc_attr( $instance['limit_post'] );
		?>
		<p>
			<label for="<?php echo  esc_attr( $this->get_field_id('title') ); ?>"><?php esc_html_e( 'Title', 'exploore' );?></label>
			<input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" name="<?php echo esc_attr( $this->get_field_name('title') ); ?>" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
			<label for="<?php echo  esc_attr( $this->get_field_id('limit_post') ); ?>"><?php esc_html_e( 'Number Post', 'exploore' );?></label>
			<input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id('limit_post') ); ?>" name="<?php echo esc_attr( $this->get_field_name('limit_post') ); ?>" value="<?php echo esc_attr( $limit_post ); ?>" />
		</p>
		<?php
			$format = '
				<p>
					<input class="checkbox" type="checkbox" %1$s id="%2$s" name="%3$s" />
					<label for="%4$s">%5$s</label>
				</p>';
			foreach( $check_box as $field => $text ) {
				printf( $format,
						checked($instance[$field], 'on', false ),
						esc_attr( $this->get_field_id($field) ),
						esc_attr( $this->get_field_name($field) ),
						esc_attr( $this->get_field_id($field) ),
						$text
					);
			}
	}

	function update( $new_instance, $old_instance ) {
		$instance          = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['limit_post'] = strip_tags( $new_instance['limit_post'] );
		$params = array(
				'title',
				'limit_post',
				'show_title',
				'show_date',
				'show_views_count',
				'show_comments_count',
				'show_thumbnail',
			);
		foreach( $params as $item ) {
			$instance[$item] = strip_tags( $new_instance[$item] );
		}
		return $instance;
	}


	function widget($args, $instance) {
		$title_filter = apply_filters( 'widget_title', empty($instance['title']) ? '' : $instance['title'], $instance );
		extract( $args );
		$limit_post = $instance['limit_post'];
		$title      = apply_filters('widget_title', $instance['title']);
		$id         = Slzexploore::make_id();
		$custom_css ='';
		//call function from shortcode
		$model = new Slzexploore_Blog();
		$default = array(
			'layout'          => 'wg_recent_post',
			'title'           => '',
			'limit_post'      => '',
			'prefix_category' => '',
			'show_title'      => '',
			'show_date'       => '',
			'show_views_count'      => '',
			'show_comments_count'   => '',
			'show_thumbnail'  => '',
			'show_category'   => '',
		);
		$check_box = array(
			'show_title'   	 => '',
			'show_date'      => '',
			'show_thumbnail' => '',
			'show_views_count'     => '',
			'show_comments_count'  => '',
		);
		$instance = wp_parse_args( (array) $instance, $default );
		extract( $instance );
		$atts = $instance;
		foreach( $check_box as $k => $v ) {
			if( isset($atts[$k]) && $atts[$k] != 'on'){
				$atts[$k] = 'hide';
			}
			if( isset($atts[$k]) && $atts[$k] == 'on') {
				$atts[$k] = '';
			}
		}
		$model->init( $atts, $content = null);
		$model->large_image_post = false;
		$model->show_widget_meta = true;
		$show_title    		= '';
		$show_thumbnail  	= '';
		$show_views_count   = '';
		$show_comments_count   = '';
		$show_date       	= '';
		if( $atts['show_title'] != 'hide' ) {
			$show_title = '%2$s';
		}
		if( $atts['show_views_count'] != 'hide' ) {
			$show_views_count = '%4$s';
		}
		if( $atts['show_date'] != 'hide' ) {
			$show_date = '%3$s<span class="sep">/</span>';
		}
		if( $atts['show_comments_count'] != 'hide' ) {
			$show_comments_count = '%5$s';
		}
		if( $atts['show_thumbnail'] != 'hide' ) {
			$show_thumbnail = '%1$s';
		}
		$html_format = '<div class="single-widget-item">
							<div class="single-recent-post-widget">'.$show_thumbnail.'
								<div class="post-info">
									<div class="meta-info">'.$show_date.$show_views_count.$show_comments_count.'
									</div>
									'.$show_title.'
								</div>
							</div>
						</div>';
		echo wp_kses_post( $before_widget );?>
		<div class="recent-post-widget-<?php echo esc_attr( $id );?>">
			<?php
				if( !empty( $title_filter ) ){
					echo wp_kses_post( $before_title );
					echo esc_html( $title_filter );
					echo wp_kses_post( $after_title );
				}
				if ( $model->query->have_posts() ) :?>
					<div class="content-widget">
						<div class="recent-post-list">	 
						<?php
							$post_options = array(
								'small_post_format' 	=> $html_format,
								'small_not_div'         => true
								);
							$model->render_block( $post_options );
						?>
						</div>
					</div>
				<?php
				endif;
			?>
		</div>
		<?php 
		echo wp_kses_post( $after_widget );
	}
}