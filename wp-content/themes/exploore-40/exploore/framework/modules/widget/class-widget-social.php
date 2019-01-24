<?php
/**
 * Widget_Social class.
 * 
 * @since 1.0
 */

class Slzexploore_Widget_Social extends WP_Widget {
	
	public function __construct() {
		$widget_ops = array( 'classname' => 'widget_social', 'description' => esc_html__( 'A list social links.', 'exploore' ) );
		parent::__construct( 'slzexploore_social', esc_html_x( 'SLZ: Social', 'Social widget', 'exploore' ), $widget_ops );
	}
	
	function form( $instance ) {
		$social_default = array(
			'title' => esc_html__("Social", 'exploore' ),
		);
		$arr_social = Slzexploore::get_params('social-icons');
		foreach($arr_social as $k => $v){
			$social_default[$k] = '';
		}
		$instance    = wp_parse_args( (array) $instance, $social_default );
		$title = esc_attr( $instance['title'] );
		foreach( $social_default as $k => $v ){
			printf('<p><label for="%1$s">%2$s<input type="text" class="widefat" id="%1$s" name="%3$s" value="%4$s" /></label></p>',
				esc_attr( $this->get_field_id($k) ),
				esc_attr( ucfirst( str_replace('-', ' ', $k ) ) ),
				esc_attr( $this->get_field_name($k) ),
				esc_attr($instance[$k])
			);
		}
	}
	
	function update( $new_instance, $old_instance ) {
		$instance          = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$arr_social        = Slzexploore::get_params('social-icons');
		foreach( $arr_social as $k => $v ){
			$instance[$k] = $new_instance[$k];
		}
		return $instance;
	}

	function widget( $args, $instance ) {
		extract( $args );
		$title_filter = apply_filters( 'widget_title', empty($instance['title']) ? '' : $instance['title'], $instance );
		$arr_social  = Slzexploore::get_params('social-icons');
		echo wp_kses_post( $before_widget );
		echo '<div class="social-widget widget">';
				if( !empty( $title_filter ) ) {
					echo wp_kses_post( $before_title );
					echo esc_html( $title_filter );
					echo wp_kses_post( $after_title );
				}
			echo '<div class="content-widget">';
				echo '<ul class="list-unstyled list-inline">';
					foreach( $arr_social as $k => $v ){
						if( !empty( $instance[$k] ) ){
							printf('<li><a href="%1$s" class="social-icon fa %3$s" target="_blank"></a></li>',
							esc_url( $instance[$k] ),
							esc_attr($k),
							esc_attr($v)
							);
						}
					}
				echo '</ul>';
			echo '</div>';
		echo '</div>';
		echo wp_kses_post( $after_widget );
	}
}