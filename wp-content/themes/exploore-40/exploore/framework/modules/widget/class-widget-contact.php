<?php 
/**
 * Widget_Contact class.
 *
 * @since 1.0
 */

class Slzexploore_Widget_Contact extends WP_Widget{
	
	public function __construct(){
		$widget_ops = array('classname' => 'widget_slz_contact', 'description' => esc_html__("Slz Contact", 'exploore' ) );
		parent::__construct('slzexploore_contact', esc_html_x('SLZ: Contact', 'Contact widget', 'exploore' ), $widget_ops );
	}
	public function form($instance){
		$default = array(
			'title'       => esc_html__("Contact us", 'exploore' ),
			'address'     => '',
			'phone'       => '',
			'email'       => '',
			'info'        => '',
		);
		$instance = wp_parse_args((array) $instance, $default );
		$title = esc_attr( $instance['title'] );
		extract($instance)
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id('title') ); ?>"><?php esc_html_e( 'Title: ', 'exploore' );?></label>
			<input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" name="<?php echo esc_attr( $this->get_field_name('title') ); ?>" value="<?php echo esc_attr($title ); ?>"/>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id('info') ); ?>"><?php esc_html_e( 'Description: ', 'exploore' );?></label>
			<textarea class="widefat" rows="4" id="<?php echo esc_attr( $this->get_field_id('info') ); ?>" name="<?php echo esc_attr( $this->get_field_name('info') ); ?>"><?php echo esc_textarea($info ); ?></textarea>
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('address') ); ?>"><?php esc_html_e('Address: ', 'exploore' );?></label>
			<input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id('address') ); ?>" name="<?php echo esc_attr($this->get_field_name('address') ); ?>" value="<?php echo esc_attr($address) ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('phone') );?>"><?php esc_html_e('Phone number: ', 'exploore' );?></label>
			<input class="widefat" type="text" id="<?php  echo esc_attr($this->get_field_id('phone') );?>" name="<?php echo esc_attr($this->get_field_name('phone'))?>" value="<?php echo esc_attr($phone) ?>"/>
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('email') );?>"><?php esc_html_e('Email: ', 'exploore' );?></label>
			<input class="widefat" type="text" id="<?php  echo esc_attr($this->get_field_id('email') );?>" name="<?php echo esc_attr($this->get_field_name('email'))?>" value="<?php echo esc_attr($email) ?>"/>
		</p>
		<?php
	}
	function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['title']        = strip_tags( $new_instance['title'] );
		$instance['address']      = strip_tags( $new_instance['address'] );
		$instance['phone']        = strip_tags( $new_instance['phone'] );
		$instance['email']        = strip_tags( $new_instance['email'] );
		$instance['info']         = strip_tags( $new_instance['info'] );

		/**
		 * register strings for translation
		 */
		if (function_exists ( 'icl_register_string' )){
			icl_register_string('Widgets', $this->name . ' Widget input-' . $this->number, $instance['address']);
			icl_register_string('Widgets', $this->name . ' Widget textarea-' . $this->number, $instance['info']);
		}
		return $instance;
	}
	function widget($args, $instance){
		extract( $args );
		$title_filter = apply_filters( 'widget_title', empty($instance['title']) ? '' : $instance['title'], $instance );
		$instance['info'] = isset($instance['info']) ? $instance['info'] : '';
		extract($instance);
		if ( has_filter( 'wpml_translate_single_string' ) ){
			$address = apply_filters('wpml_translate_single_string', $instance['address'], 'Widgets', $this->name . ' Widget input-' . $this->number );
			$info    = apply_filters('wpml_translate_single_string', $instance['info'], 'Widgets', $this->name . ' Widget textarea-' . $this->number );
		}
		echo wp_kses_post( $before_widget );?>
			<div class="contact-us-widget widget">
				<?php
				if( !empty( $title_filter ) ) {
					echo wp_kses_post( $before_title );
					echo esc_html( $title_filter );
					echo wp_kses_post( $after_title );
				}
				?>
				<div class="content-widget">
					<div class="info-list">
						<?php if(!empty($info)): ?>
						<div><?php echo wp_kses_post(nl2br($info) ); ?></div>
						<?php endif; ?>
						<?php if(!empty($address)): ?>
						<div><i class="icons fa fa-map-marker"></i><span><?php echo esc_html($address); ?></span></div>
						<?php endif; ?>
						<?php if(!empty($phone)): ?>
							<div><i class="icons fa fa-phone"></i><span><?php echo esc_html($phone); ?></span></div>
						<?php endif; ?>
						<?php if(!empty($email)): ?>
							<div><i class="icons fa fa-envelope-o"></i><a class="link" href="mailto:<?php echo esc_attr( $email ); ?>"><span><?php echo esc_html($email); ?></span></a></div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		<?php
		echo wp_kses_post( $after_widget );
	}
}