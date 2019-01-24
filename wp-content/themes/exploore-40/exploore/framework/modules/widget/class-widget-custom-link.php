<?php
/**
 * Widget_Custom_Link class.
*
* @since 1.0
*/
class Slzexploore_Widget_Custom_Link extends WP_Widget {

	public function __construct() {
		$widget_ops = array( 'classname' => 'widget_slz_widget_custom_link', 'description' => esc_html__( "List of custom link.", 'exploore') );
		parent::__construct( 'slzexploore_custom_link', esc_html_x( 'SLZ: Custom Link', 'Custom Link widget', 'exploore' ), $widget_ops );
	}

	function form($instance) {
		$default = array(
			'title'  => esc_html__( 'Custom Link', 'exploore' ),
		);
		$instance	   = wp_parse_args( (array) $instance, $default );
		$title		  = esc_attr( $instance['title'] );
		$page_style	 = get_pages();
		?>
		<p>
			<label for="<?php echo  esc_attr( $this->get_field_id('title') ); ?>"><?php esc_html_e( 'Title', 'exploore' );?></label>
			<input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" name="<?php echo esc_attr( $this->get_field_name('title')); ?>" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<?php
		$fields			 = isset( $instance['fields'] ) ? $instance['fields'] : array ();
		$field_num		  = count( $fields );
		$fields[$field_num] = array( 'subtitle' => '', 'link' => '','page_link'=>'' );
		foreach ( $fields as $k => $v ) {
			$v = (array)$v;
			?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'fields' ) ) . '-subtitle'; ?>-<?php echo esc_attr($k); ?>"><?php echo esc_html_e( 'Subtitle', 'exploore' ); ?></label>
			<input type="text" id="<?php echo esc_attr( $this->get_field_id( 'fields' ) ) . '-subtitle'; ?>-<?php echo esc_attr($k); ?>" name="<?php echo esc_attr( $this->get_field_name( 'fields' ) ); ?>[<?php echo esc_attr($k); ?>][subtitle]" value="<?php echo esc_attr( $v['subtitle'] ); ?>" class="widefat"/>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'fields' ) ) . '-link'; ?>-<?php echo esc_attr($k); ?>"><?php echo esc_html_e( 'Custom Link', 'exploore' );?></label>
			<input type="text" id="<?php echo esc_attr( $this->get_field_id ( 'fields' ) ) . '-link'; ?>-<?php echo esc_attr($k); ?>" name="<?php echo esc_attr( $this->get_field_name( 'fields' ) ); ?>[<?php echo esc_attr($k); ?>][link]" value="<?php echo esc_attr( $v['link'] ); ?>" class="widefat"/>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'fields' ) ) . '-page_link'; ?>-<?php echo esc_attr($k); ?>"><?php esc_html_e( 'Or Link to Page', 'exploore' );?></label>
			<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'fields' ) ) . '-page_link'; ?>-<?php echo esc_attr($k); ?>" name="<?php echo esc_attr( $this->get_field_name( 'fields' ) ); ?>[<?php echo esc_attr($k); ?>][page_link]">
				<?php
					$result[''] = esc_html__('--All Pages--', 'exploore');
					foreach( $page_style as $row ) {
						$result[$row->post_name] = $row->post_title;
					}
					foreach ($result as $k1 => $v1 ) {
						$selected = '';
						if( $v['page_link'] ==  $k1){
							$selected = 'selected';
						}  
						printf(
							'<option value="%s" %s>%s</option>',
							esc_attr($k1),
							$selected,
							esc_html($v1)
						);
					}
				?>
			</select>
		</p>
		<?php 
		}
	}
	function update( $new_instance, $old_instance ) {
		$instance			= $old_instance;
		$instance['title']   = strip_tags( $new_instance['title'] );
		$instance['fields']  = array ();
		$uniq = $this->number;
		$i = 0;
		if ( isset( $new_instance['fields'] ) ) {
			foreach( $new_instance['fields'] as $k => $v ) {
				if (('' !== trim ( $v['subtitle'] )) || ( '' !== trim( $v['link'] )) || ( '' !== trim( $v['page_link'] ))){
					$instance['fields'][$k]['subtitle']  = $v['subtitle'];
					$instance['fields'][$k]['link']	  = $v['link'];
					$instance['fields'][$k]['page_link'] = $v['page_link'];
					// register strings for translation
					if (function_exists ( 'icl_register_string' )){
						icl_register_string('Widgets', $this->name . ' Widget link-'.$uniq . '-' .$i, $v['subtitle']);
					}
					$i++;
				}
			}
			
		}
		return $instance;
	}

	function widget( $args, $instance ) {
		$title_filter = apply_filters( 'widget_title', empty($instance['title']) ? '' : $instance['title'], $instance );
		extract( $args );
		$fields  = $instance['fields'];
		$fields_num = count( $fields );
		$count = 0;
		$uniq = $this->number;
		echo wp_kses_post( $before_widget );?>
			<div class="booking-widget widget text-center"><?php
				if( !empty( $title_filter ) ){
					echo wp_kses_post( $before_title );
					echo esc_html( $title_filter );
					echo wp_kses_post( $after_title );
				}?>
				<div class="content-widget"><?php
					if( $fields_num > 0 ) :?>
					<ul class="list-unstyled">
					<?php
						for( $i = 0; $i < $fields_num; $i++ ){
							$data_item = (array)$fields[$i];
							if ( has_filter( 'wpml_translate_single_string' ) ){
								$data_item['subtitle'] = apply_filters('wpml_translate_single_string', $data_item['subtitle'], 'Widgets', $this->name . ' Widget link-'.$uniq . '-' .$i );
							}
							if(empty($data_item['page_link'])){
								echo '<li><a href="'.esc_url($data_item['link']).'" class="link">'.esc_html( $data_item['subtitle'] ).'';
								echo '</a></li>';
							}
							else
							{
								$terms = get_pages($data_item['page_link']);
								foreach($terms as $term){
									$term_link = get_page_link($term);
									if($data_item['page_link'] == $term->post_name)
									{
										if(empty($data_item['subtitle']))
										{
											echo '<li><a href="'.esc_url($term_link).'" class="link">'.esc_html($term->post_title).'';
											echo '</a></li>';
										}
										else 
										{
											echo '<li><a href="'.esc_url($term_link).'" class="link">'.esc_html($data_item['subtitle']).'';
											echo '</a></li>';
										}
									}
								}
							}
						}
					?>
					</ul><?php
					endif;?>
				</div>
			</div>
		<?php
		echo wp_kses_post( $after_widget );
	}
}