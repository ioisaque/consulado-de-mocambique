<?php 
/**
 * Widget_Categories class.
 *
 * @since 1.0
 */

class Slzexploore_Widget_Categories extends WP_Widget{
	
	public function __construct(){
		$widget_ops = array('classname' => 'widget_slz_categories', 'description' => esc_html__('Slz categories','exploore'));
		parent::__construct('slzexploore_categories', esc_html_x('SLZ: Categories', 'Categories widget','exploore' ),$widget_ops);
	}
	
	function form($instance){
		$default = array(
			'title' 		=> esc_html__("Categories",'exploore'),
			'show_count' 	=> 'on'
		);
		$instance = wp_parse_args((array) $instance, $default);
		$title = esc_attr($instance['title']);
		?>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('title') );?>"><?php echo esc_html_e('Title: ', 'exploore' ); ?></label>
			<input class="widefat" type="text" id="<?php echo esc_attr($this->get_field_id('title') ); ?>" name="<?php echo esc_attr($this->get_field_name('title') ); ?>" value="<?php echo esc_attr($title); ?>"/>
		</p>
		<p>
			<input class="checkbox" type="checkbox" <?php checked($instance['show_count'], 'on' ); ?> id="<?php echo esc_attr($this->get_field_id('show_count') ); ?>" name="<?php echo esc_attr($this->get_field_name('show_count')); ?>" />
			<label for="<?php echo esc_attr($this->get_field_id('show_count') );?>"><?php echo esc_html_e('Show count categories', 'exploore' ); ?></label>
		</p>
	<?php
	}
	function update($new_instance,$old_instance){
		$instance = $old_instance;
		$instance['title'] = $new_instance['title'];
		$instance['show_count'] = $new_instance['show_count'];
		return $instance;
	}
	function widget($args,$instance){
		$title_filter = apply_filters( 'widget_title', empty($instance['title']) ? '' : $instance['title'], $instance );
		extract($args);
		$categories = get_categories();
		echo wp_kses_post( $before_widget );?>
			<div class="categories-widget widget">
			<?php
				if( !empty( $title_filter ) ) {
					echo wp_kses_post( $before_title );
					echo esc_html( $title_filter );
					echo wp_kses_post( $after_title );
				}
			?>
				<div class="content-widget">
					<ul class="widget-list">
						<?php 
							foreach($categories as $key => $value){
								$category_id = $value->cat_ID;
								$category_link = get_category_link( $category_id );
								echo '<li class="single-widget-item"><a href="'.esc_url( $category_link ).'" class="link">';
								echo '<span class="fa-custom category">'.esc_attr( $value->name ).'</span>';
								if ( $instance['show_count'] == 'on' ) {
									echo '<span class="count">'.esc_html( $value->count ).'</span>';
								}
								echo '</a></li>';
							}
						?>
					</ul>
				</div>
			</div>
		<?php
		echo wp_kses_post( $after_widget );
	}
}