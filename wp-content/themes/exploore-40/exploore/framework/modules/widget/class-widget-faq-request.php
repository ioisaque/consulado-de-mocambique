<?php
/**
 * Widget_Faq_request class.
 * 
 * @since 1.0
 */

class Slzexploore_Widget_Faq_request extends WP_Widget {

	public function __construct() {
		$widget_ops = array( 'classname' => 'widget_slzexploore_faq_request', 'description' => esc_html__( "A list of faq_request", 'exploore' ) );
		parent::__construct( 'slzexploore_faq_request', esc_html_x( 'SLZ: FAQs Request', 'faq_request widget', 'exploore' ), $widget_ops );
	}

	function form( $instance ) {
		$default = array( 
			'title'			=> esc_html__( "SEND YOUR REQUEST", 'exploore' ),
			'contact_form'	=> '',
		);
		$instance = wp_parse_args( (array) $instance, $default );
		$title = esc_attr( $instance['title'] );
		$contact_form = esc_html( $instance['contact_form'] );
		$contact_form_arr = array('' => esc_html__( '-None-', 'exploore' ));
		$args = array (
					'post_type'     => 'wpcf7_contact_form',
					'post_per_page' => -1,
					'status'        => 'publish',
					'suppress_filters' => false,
				);
		$post_arr = get_posts( $args );
		foreach( $post_arr as $post ){
			$name = ( !empty( $post->post_title ) )? $post->post_title : $post->post_name;
			$contact_form_arr[$post->ID ] =  $name;
		}
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id('title') ); ?>"><?php esc_html_e('Title:', 'exploore'); ?>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" name="<?php echo esc_attr( $this->get_field_name('title') ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</label>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id('contact_form') ); ?>"><?php esc_html_e('Contact form:', 'exploore'); ?>
				<select class="widefat" id="<?php echo esc_attr( $this->get_field_id('contact_form') ); ?>" name="<?php echo esc_attr( $this->get_field_name('contact_form') ); ?>">
					<?php foreach ($contact_form_arr as $key => $value) {
						$selected = $contact_form == $key ? 'selected="selected"' : '';
						echo '<option value="'. $key .'" '. $selected .'>'. esc_html($value) .'</option>';
					}?>
				</select>
			</label>
		</p>
		<?php
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['contact_form'] = strip_tags( $new_instance['contact_form'] );
		return $instance;
	}

	function widget( $args, $instance ) {
		$title_filter = apply_filters( 'widget_title', empty($instance['title']) ? '' : $instance['title'], $instance );
		extract( $args );
		$default  = array(
			'title'				=> '',
			'contact_form'		=> '',
		);
		$contact_form = $instance ['contact_form'];
		$instance   = wp_parse_args( (array) $instance, $default );
		extract( $instance );

		echo wp_kses_post( $before_widget );
		?>
		<div class="widget-sidebar widget-make-faq-request">
			<div class="slz-shortcode sc_faq_request">
				<div class="wrapper-contact-faq">
					<div class="contact-wrapper">
						<div class="contact-box">
							<?php if ( !empty($title_filter) ) {
								printf('<h5 class="title">%s</h5>', esc_html($title_filter));
							} ?>
							<?php if ( !empty( $contact_form ) && SLZEXPLOORE_WPCF7_ACTIVE ) { ?>
							<?php echo do_shortcode('[contact-form-7 id="'.esc_attr($contact_form).'" title="'.esc_attr($title_filter).'" html_id="contact-faq-form-'.esc_attr($contact_form).'" html_class="contact-form"]');
							} ?>
							<div class="clearfix"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
		echo wp_kses_post( $after_widget );
	}
}