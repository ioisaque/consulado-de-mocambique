<?php
/**
 * Widget_Cruise class.
 * 
 * @since 1.0
 */
class Slzexploore_Widget_Cruise extends WP_Widget {

	public function __construct() {
		$widget_ops = array( 'classname' => 'widget_slz_widget_cruise', 'description' => esc_html__( "A list of Cruise.", 'exploore') );
		parent::__construct( 'slzexploore_cruise', esc_html_x( 'SLZ: Cruise', 'Cruise widget', 'exploore' ), $widget_ops );
	}

	function form($instance) {
		$default = array(
			'title'           => esc_html__( 'Cruise', 'exploore' ),
			'limit_post'      => '5',
			'cat_slug'        => '',
			'location_slug'   => '',
			'sort_by'         => '',
			'featured_filter' => '0',
			'show_thumnail'   => '',
			'show_price'      => ''
		);
		$check_box = array(
			'show_thumnail'    => esc_html__( 'Display the thumnail', 'exploore' ),
			'show_price'       => esc_html__( 'Display price cruise', 'exploore' ),
		);
		$cat               = Slzexploore_Core_Com::get_tax_options2slug( 'slzexploore_cruise_cat', array('empty'       => esc_html__( '--All Cruise Categories--', 'exploore' ) ) );
		$location          = Slzexploore_Core_Com::get_tax_options2slug( 'slzexploore_cruise_location', array('empty'   => esc_html__( '--All Cruise Location--', 'exploore' ) ) );
		$sort_arr          = Slzexploore_Core::get_params('sort-cruise');
		$featured_filter_arr = array(
			esc_html__('None', 'exploore')        => '0',
			esc_html__('Featured', 'exploore')    => '1',
		);
		$instance 		   = wp_parse_args( (array) $instance, $default );
		$title             = esc_attr( $instance['title'] );
		$limit_post        = esc_attr( $instance['limit_post'] );
		$location_slug     = esc_attr( $instance['location_slug'] );
		$cat_slug          = esc_attr( $instance['cat_slug'] );
		$sort_by           = esc_attr( $instance['sort_by'] );
		$featured_filter   = esc_attr( $instance['featured_filter'] );
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id('title') ); ?>"><?php esc_html_e( 'Title', 'exploore' );?></label>
			<input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" name="<?php echo esc_attr( $this->get_field_name('title') ); ?>" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
			<label for="<?php echo  esc_attr( $this->get_field_id('limit_post') ); ?>"><?php esc_html_e( 'Number Post', 'exploore' );?></label>
			<input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id('limit_post') ); ?>" name="<?php echo esc_attr( $this->get_field_name('limit_post') ); ?>" value="<?php echo esc_attr( $limit_post ); ?>" />
		</p>
		<p>
			<label for="<?php echo  esc_attr( $this->get_field_id('featured_filter') ); ?>"><?php esc_html_e( 'Featured Cruise', 'exploore' );?></label>
			<select class="widefat" id="<?php echo esc_attr( $this->get_field_id('featured_filter') ); ?>" name="<?php echo esc_attr( $this->get_field_name('featured_filter') ); ?>" >
				<?php foreach( $featured_filter_arr  as $k => $v ){?>
					<option value="<?php echo esc_attr($v); ?>"<?php if( $featured_filter == $v ) echo " selected"; ?>><?php echo esc_html($k); ?></option>
				<?php } ?>
			</select>
		</p>
		<p>
			<label for="<?php echo  esc_attr( $this->get_field_id('sort_by') ); ?>"><?php esc_html_e( 'Sort By', 'exploore' );?></label>
			<select class="widefat" id="<?php echo esc_attr( $this->get_field_id('sort_by') ); ?>" name="<?php echo esc_attr( $this->get_field_name('sort_by') ); ?>" >
				<?php foreach( $sort_arr  as $k => $v ){?>
					<option value="<?php echo esc_attr($v); ?>"<?php if( $sort_by == $v ) echo " selected"; ?>><?php echo esc_html($k); ?></option>
				<?php } ?>
			</select>
		</p>
		<p>
			<label for="<?php echo  esc_attr( $this->get_field_id('cat_slug') ); ?>"><?php esc_html_e( 'Cruise Categories', 'exploore' );?></label>
			<select class="widefat" id="<?php echo esc_attr( $this->get_field_id('cat_slug') ); ?>" name="<?php echo esc_attr( $this->get_field_name('cat_slug') ); ?>" >
				<?php foreach( $cat  as $k => $v ){?>
					<option value="<?php echo esc_attr($v); ?>"<?php if( $cat_slug == $v ) echo " selected"; ?>><?php echo esc_html($k); ?></option>
				<?php } ?>
			</select>
		</p>
		<p>
			<label for="<?php echo  esc_attr( $this->get_field_id('location_slug') ); ?>"><?php esc_html_e( 'Cruise Location', 'exploore' );?></label>
			<select class="widefat" id="<?php echo esc_attr( $this->get_field_id('location_slug') ); ?>" name="<?php echo esc_attr( $this->get_field_name('location_slug') ); ?>" >
				<?php foreach( $location  as $k => $v ){?>
					<option value="<?php echo esc_attr($v); ?>"<?php if( $location_slug == $v ) echo " selected"; ?>><?php echo esc_html($k); ?></option>
				<?php } ?>
			</select>
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
		$instance = $old_instance;
		$params   = array(
			'title',
			'limit_post',
			'cat_slug',
			'location_slug',
			'sort_by',
			'featured_filter',
			'show_thumnail',
			'show_price',
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
			'layout'          => 'wg_cruise',
			'title'           => '',
			'limit_post'      => '',
			'cat_slug'        => '',
			'location_slug'   => '',
			'sort_by'         => '',
			'featured_filter' => '1',
			'show_thumnail'   => '',
			'show_price'      => '',
		);

		$instance = wp_parse_args( (array) $instance, $default );
		extract( $instance );
		$atts  = $instance;
		$model = new Slzexploore_Core_Cruise();
		$atts['layout'] = 'wg_cruise';
		$model->init( $atts );
		$html_format = '<li><a href="%2$s" class="link">%1$s</a></li>';
		echo wp_kses_post( $before_widget );
		if( $atts['show_price'] == 'on' || $atts['show_thumnail'] == 'on') {
			$show_thumnail = '';
			$show_price    = '';
			$show_titl     = '';
			if($atts['show_price'] == 'on'){
				$show_price = '%4$s';
			}
			if($atts['show_thumnail'] == 'on'){
				$show_thumnail = '%3$s';
				$show_title   = '<a href="%2$s" class="title">%1$s</a>';
			}
			$html_format_style = '<div class="single-widget-item slz-custom-post %5$s">
									<div class="single-recent-post-widget">'.$show_thumnail.'
										<div class="post-info">
											<div class="meta-info">'.$show_price.'</div>
											'.$show_title.'
										</div>
									</div>
								</div>';
			$html_options = array(
				'html_format'       => $html_format_style,
				'price_format'      => '<div class="price"><div class="price-text">'.esc_html__('Price from ','exploore').'</div> %1$s%2$s</div>',
			);
			echo '<div class="recent-post-widget widget">';
				if( !empty( $title_filter ) ){
					echo wp_kses_post( $before_title );
					echo esc_html( $title_filter );
					echo wp_kses_post( $after_title );
				}
				echo '<div class="content-widget">';
					echo '<div class="recent-post-list">';
						$model->render_widget( $html_options );
					echo '</div>';
				echo '</div>';
			echo '</div>';
		}
		else{
		?>
			<div class="explore-widget widget">
				<?php
					if( !empty( $title_filter ) ){
					echo wp_kses_post( $before_title );
					echo esc_html( $title_filter );
					echo wp_kses_post( $after_title );
				}
				?>
				<div class="content-widget">
					<?php if( $model->query->have_posts() ):?>
					<ul class="list-unstyled">
						<?php 
							$html_options = array(
								'html_format' => $html_format,
							);
							$model->render_widget( $html_options );
						?>
					</ul>
					<?php endif;?>
				</div>
			</div>
		<?php
		}
		echo wp_kses_post( $after_widget );
	}
}