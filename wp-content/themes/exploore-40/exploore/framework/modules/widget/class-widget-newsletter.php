<?php
/**
 * Widget_Newsletter class.
 * 
 * @since 1.0
 */
class Slzexploore_Widget_Newsletter extends NewsletterWidget {
	public function __construct() {
		$widget_ops = array( 'classname' => '', 'description' => esc_html__( "Newsletter widget to add subscription forms.", 'exploore') );
		parent::__construct( 'slzexploore_newsletter', esc_html_x( 'Newsletter', 'Newsletter widget', 'exploore' ), $widget_ops );
	}

	function form($instance) {
		$widget_default = array(
			'title'              => '',
			'description'        => '',
		);
		$instance = wp_parse_args( (array) $instance, $widget_default );
		printf('<p><label for="%s">%s
					<input type="text" class="widefat" id="%s" name="%s" value="%s" />
				</label></p>',
				esc_attr( $this->get_field_id('title') ),
				esc_html__( 'Title:', 'exploore' ),
				esc_attr( $this->get_field_id('title') ),
				esc_attr( $this->get_field_name('title') ),
				esc_attr($instance['title'])
			);
		printf('<p><label for="%1$s">%2$s
					<textarea class="widefat" rows="3" id="%1$s" name="%3$s">%4$s</textarea>
				</label>%5$s</p>',
				esc_attr( $this->get_field_id('description') ),
				esc_html__( 'Description:', 'exploore' ),
				esc_attr( $this->get_field_name('description') ),
				esc_textarea($instance['description']),
				esc_html__( 'Enter description to display on newsletter form.', 'exploore' )
			);
	}
	
	function update($new_instance, $old_instance) {
		$instance                       = $old_instance;
		$instance['title']              = strip_tags($new_instance['title']);
		$instance['description']        = $new_instance['description'];
		/**
		 * register strings for translation
		 */
		if (function_exists ( 'icl_register_string' )){
			icl_register_string('Widgets', $this->name . ' Widget textarea-' . $this->number, $instance['description']);
		}
		return $instance;
	}
	
	function widget($args, $instance) {
		global $newsletter;
		$title_filter = apply_filters( 'widget_title', empty($instance['title']) ? '' : $instance['title'], $instance );
		extract($args);
		if ( has_filter( 'wpml_translate_single_string' ) ){
			$address = apply_filters('wpml_translate_single_string', $instance['description'], 'Widgets', $this->name . ' Widget textarea-' . $this->number );
		}
		echo wp_kses_post( $before_widget );
			echo '<div class="form-email">';
			if (!empty($title_filter)) {
				echo wp_kses_post( $before_title );
				echo esc_attr( $title_filter );
				echo wp_kses_post( $after_title );
			}
			$description        = apply_filters('widget_text', $instance['description'], $instance);
			if(!empty($description)){
				printf('<p class="text">%s</p>', nl2br(esc_textarea($description)));
			}
			$form = Slzexploore_Widget_Newsletter::get_slz_widget_form();
			$form = $newsletter->replace($form);
			printf ('%s',$form);
			echo '</div>';
		echo wp_kses_post( $after_widget );
	}

	static function get_slz_widget_form() {
		$options_profile = get_option('newsletter_profile');
		$form = NewsletterSubscription::instance()->get_form_javascript();
		$form .= '<form action="' . esc_url( home_url('/') ) . '?na=s" onsubmit="return newsletter_check(this)" method="post">';
			$form .= '<div class="input-group">';
				$form .= '<input type="hidden" name="nr" value="widget"/>';
				$form .= '<input class="form-control form-email-widget" type="email" required name="ne" placeholder="'.esc_html__('Email address', 'exploore').'" onclick="if (this.defaultValue==this.value) this.value=\'\'" onblur="if (this.value==\'\') this.value=this.defaultValue"/>';
				$form .= '<span class="input-group-btn-custom">';
				$form .= '<button type="submit" class="btn-email">&#10004;</button>';
				$form .= '</span>';
		$form .= '</div></form>';
		return $form;
	}
}