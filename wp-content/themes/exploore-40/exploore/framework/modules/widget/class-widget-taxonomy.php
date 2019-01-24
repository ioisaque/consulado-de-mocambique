<?php
/**
 * Widget_Taxonomy class.
 *
 * @since 1.0
 */
class Slzexploore_Widget_Taxonomy extends WP_Widget {

	public function __construct() {
		$widget_ops = array('classname' => 'widget_slz_taxonomy', 'description' => esc_html__('List of taxonomy','exploore'));
		parent::__construct('slzexploore_taxonomy', esc_html_x('SLZ: Taxonomy', 'Taxonomy widget','exploore' ),$widget_ops);
	}

	public function form( $instance ) {
		$default = array(
			'title' 		=> esc_html__('Exploore','exploore'),
			'taxonomy' 		=> ''
		);
		$instance 	= wp_parse_args((array) $instance, $default);
		$title    	= esc_attr($instance['title']);
		$taxonomy 	= esc_attr($instance['taxonomy']);
		$taxonomies = get_taxonomies( array( 'show_tagcloud' => true ), 'object' );
		$current_taxonomy = $this->_get_current_taxonomy($instance);
		?>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('title') );?>"><?php echo esc_html_e('Title: ', 'exploore' ); ?></label>
			<input class="widefat" type="text" id="<?php echo esc_attr($this->get_field_id('title') ); ?>" name="<?php echo esc_attr($this->get_field_name('title') ); ?>" value="<?php echo esc_attr($title); ?>"/>
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('taxonomy') );?>"><?php echo esc_html_e('Taxonomy: ', 'exploore' ); ?></label>
			<select class="widefat" id="<?php echo esc_attr($this->get_field_id('taxonomy') ); ?>" name="<?php echo esc_attr($this->get_field_name('taxonomy') );?>">
			<?php
			foreach ( $taxonomies as $taxonomy => $tax ) {
				printf(
					'<option value="%s"%s>%s</option>',
					esc_attr($taxonomy),
					selected($taxonomy, $current_taxonomy, false),
					esc_html($tax->labels->name)
				);
			}
			?>
			</select>
		</p>
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] 	  = strip_tags( $new_instance['title'] );
		$instance['taxonomy'] = strip_tags($new_instance['taxonomy']);
		return $instance;
	}

	public function widget( $args, $instance ) {
		$title_filter = apply_filters( 'widget_title', empty($instance['title']) ? '' : $instance['title'], $instance );
		extract($args);
		$current_taxonomy = $this->_get_current_taxonomy($instance);
		$terms = get_terms($current_taxonomy);
		echo wp_kses_post( $before_widget );?>
			<div class="explore-widget widget">
				<?php
					if( !empty( $title_filter ) ){
					echo wp_kses_post( $before_title );
					echo esc_html( $title_filter );
					echo wp_kses_post( $after_title );
				}
				?>
				<div class="content-widget">
					<ul class="list-unstyled">
					<?php 
						foreach ( $terms as $term ) {
							$term_link = get_term_link( $term );
							echo '<li><a href="'.esc_url($term_link ).'" class="link">'.esc_html($term->name).'';
							echo '</a></li>';
						}
					?>
					</ul>
				</div>
			</div>
		<?php
		echo wp_kses_post( $after_widget );
	}

	public function _get_current_taxonomy($instance) {
		if ( !empty($instance['taxonomy']) && taxonomy_exists($instance['taxonomy']) )
			return $instance['taxonomy'];
		return 'post_tag';
	}
}