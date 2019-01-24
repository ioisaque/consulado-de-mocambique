<?php
/**
 * Widget_Search class.
 * 
 * @since 1.0
 */
class Slzexploore_Widget_Search extends WP_Widget {

	public function __construct() {
		$widget_ops = array( 'classname' => 'widget_slz_widget_search', 'description' => esc_html__( "A Search Form.", 'exploore') );
		parent::__construct( 'slzexploore_search', esc_html_x( 'SLZ: Search','Search widget','exploore'), $widget_ops );
	}

	function form($instance) {
		$default = array(
			'title'           => '',
			'type'            => 'accommodation',
		);
		$search_types = array(
			'accommodation' => esc_html__('Accommodation', 'exploore'),
			'car'           => esc_html__('Car', 'exploore'),
			'cruise'        => esc_html__('Cruise', 'exploore'),
			'tour'          => esc_html__('Tour', 'exploore')
		);
		$instance           = wp_parse_args( (array) $instance, $default );
		$title              =  $instance['title'];
		$type               =  $instance['type'];
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id('title') ); ?>"><?php esc_html_e( 'Title', 'exploore' );?></label>
			<input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" name="<?php echo esc_attr( $this->get_field_name('title') ); ?>" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
			<label for="<?php echo  esc_attr( $this->get_field_id('type') ); ?>"><?php esc_html_e( 'Search Type', 'exploore' );?></label>
			<select class="widefat" id="<?php echo esc_attr( $this->get_field_id('type') ); ?>" name="<?php echo esc_attr( $this->get_field_name('type') ); ?>" >
			<?php
				foreach( $search_types  as $k => $v ){
					$selected = '';
					if( $type == $k ){
						$selected = 'selected';
					}
					printf( '<option value="%1$s" %2$s>%3$s</option>',
							esc_attr( $k ),
							esc_attr( $selected ),
							esc_html( $v )
						);
				}
			?>
			</select>
		</p>
		<?php
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title']  = strip_tags( $new_instance['title'] );
		$instance['type']   = strip_tags( $new_instance['type'] );
		return $instance;
	}

	function widget( $args, $instance ) {
		$title_filter = apply_filters( 'widget_title', empty($instance['title']) ? '' : $instance['title'], $instance );
		extract( $args );
		$type   = $instance['type'];
		
		echo wp_kses_post( $before_widget );?>
			<div class="explore-widget widget">
				<?php
					if( !empty( $title_filter ) ){
						echo wp_kses_post( $before_title );
						echo esc_html( $title_filter );
						echo wp_kses_post( $after_title );
					}
					echo do_shortcode('[slzexploore_core_'.esc_attr($type).'_search_sc]');
				?>
			</div>
		<?php
		echo wp_kses_post( $after_widget );
	}
}