<?php
/**
 * Widget_Init class.
 * 
 * @since 1.0
 */

Slzexploore::load_class( 'widget.Widget_Categories' );
Slzexploore::load_class( 'widget.Widget_Contact' );
Slzexploore::load_class( 'widget.Widget_Social' );
Slzexploore::load_class( 'widget.Widget_Recent_Post' );
Slzexploore::load_class( 'widget.Widget_Taxonomy' );
Slzexploore::load_class( 'widget.Widget_Custom_Link' );
if(SLZEXPLOORE_NEWSLETTER_ACTIVE){
	Slzexploore::load_class( 'widget.Widget_Newsletter' );
}
if(SLZEXPLOORE_CORE_IS_ACTIVE) {
	Slzexploore::load_class( 'widget.Widget_Tour' );
	Slzexploore::load_class( 'widget.Widget_Accommodation' );
	Slzexploore::load_class( 'widget.Widget_Gallery' );
	Slzexploore::load_class( 'widget.Widget_Faq_request' );
	Slzexploore::load_class( 'widget.Widget_Car' );
	Slzexploore::load_class( 'widget.Widget_Cruise' );
	Slzexploore::load_class( 'widget.Widget_Search' );
}
class Slzexploore_Widget_Init {
	/**
	 * Load widgets
	 *
	 */
	public function load() {
		register_widget( 'Slzexploore_Widget_Categories' );
		register_widget( 'Slzexploore_Widget_Contact' );
		register_widget( 'Slzexploore_Widget_Social' );
		register_widget( 'Slzexploore_Widget_Recent_Post' );
		register_widget( 'Slzexploore_Widget_Taxonomy' );
		register_widget( 'Slzexploore_Widget_Custom_Link' );
		if(SLZEXPLOORE_CORE_IS_ACTIVE) {
			register_widget( 'Slzexploore_Widget_Tour' );
			register_widget( 'Slzexploore_Widget_Accommodation' );
			register_widget( 'Slzexploore_Widget_Gallery' );
			register_widget( 'Slzexploore_Widget_Faq_request' );
			register_widget( 'Slzexploore_Widget_Car' );
			register_widget( 'Slzexploore_Widget_Cruise' );
			register_widget( 'Slzexploore_Widget_Search' );
		}
	}
	/**
	 * Register sidebars
	 *
	 */
	public function widgets_init() {
		register_sidebar( array (
			'name'          => esc_html__( 'Default Widget Area', 'exploore' ),
			'id'            => 'slzexploore-sidebar-default',
			'description'   => esc_html__( 'Add widgets here to appear in sidebar of posts and pages', 'exploore'),
			'before_widget' => '<div id="%1$s" class="box %2$s slz-widget widget">',
			'after_widget'  => '</div>',
			'before_title'  => '<div class="title-widget"><div class="title">',
			'after_title'   => '</div></div>'
		));
		// Register footer area
		for ( $i = 1; $i < 6; $i++ ) {
			register_sidebar( array (
				'name'          => sprintf( esc_html__( 'Footer Widget Area %s', 'exploore' ), $i ),
				'id'            => 'slzexploore-sidebar-footer-' . $i,
				'description'   => sprintf( esc_html__( 'Add widgets here to appear in footer column %s.', 'exploore' ), $i ),
				'before_widget' => '<div id="%1$s" class="%2$s slz-widget widget widget-footer">',
				'after_widget'  => '</div>',
				'before_title'  => '<div class="title-widget">',
				'after_title'   => '</div>'
			));
		}
		//Register sidebar main
		register_sidebar( array (
			'name'          => esc_html__( 'Main Widget Area', 'exploore' ),
			'id'            => 'slzexploore-sidebar-main',
			'description'   => esc_html__( 'Add widgets here to appear in sidebar pages.', 'exploore' ),
			'before_widget' => '<div id="%1$s" class="box %2$s slz-widget widget">',
			'after_widget'  => '</div>',
			'before_title'  => '<div class="title-widget"><div class="title">',
			'after_title'   => '</div></div>'
		));
		//Register sidebar blog
		register_sidebar( array (
			'name'          => esc_html__( 'Blog Widget Area', 'exploore' ),
			'id'            => 'slzexploore-sidebar-blog',
			'description'   => esc_html__( 'Add widgets here to appear in sidebar of posts.', 'exploore' ),
			'before_widget' => '<div id="%1$s" class="box %2$s slz-widget widget">',
			'after_widget'  => '</div>',
			'before_title'  => '<div class="title-widget"><div class="title">',
			'after_title'   => '</div></div>'
		));
		//Register sidebar search
		register_sidebar( array (
			'name'          => esc_html__( 'Search Widget Area', 'exploore' ),
			'id'            => 'slzexploore-sidebar-search',
			'description'   => esc_html__( 'Add widgets here to appear in sidebar of search page.', 'exploore' ),
			'before_widget' => '<div id="%1$s" class="box %2$s slz-widget widget">',
			'after_widget'  => '</div>',
			'before_title'  => '<div class="title-widget"><div class="title">',
			'after_title'   => '</div></div>'
		));
		//Register sidebar shop
		register_sidebar( array (
			'name'          => esc_html__( 'Shop Widget Area', 'exploore' ),
			'id'            => 'slzexploore-sidebar-shop',
			'description'   => esc_html__( 'Add widgets here to appear in sidebar of products.', 'exploore' ),
			'before_widget' => '<div id="%1$s" class="box %2$s slz-widget widget">',
			'after_widget'  => '</div>',
			'before_title'  => '<div class="title-widget"><div class="title">',
			'after_title'   => '</div></div>'
		));
		// Register custom sidebar
		$sidebars = get_option('slzexploore_custom_sidebar');
		$args =  array (
			'before_widget' => '',
			'after_widget'  => '',
			'before_title'  => '',
			'after_title'   => ''
		);
		if( is_array( $sidebars ) ) {
			foreach ( $sidebars as $sidebar ) {
				if( !empty($sidebar) ) {
					$name = isset($sidebar['name']) ? $sidebar['name'] : '';
					$title = isset($sidebar['title']) ? $sidebar['title'] : '';
					$class = isset($sidebar['class']) ? $sidebar['class'] : '';
					$args['name']   = $title;
					$args['id']     = str_replace(' ','-',strtolower( $name ));
					$args['class']  = 'slz-custom';
					$args['before_widget'] = '<div class="box %2$s widget slz-widget '. $class .'">';
					$args['after_widget']  = '</div>';
					$args['before_title']  = '<div class="title-widget">';
					$args['after_title']   = '</div>';
					register_sidebar($args);
				}
			}
		}
	}
	/**
	 * Add custom sidebar area
	 *
	 */
	public function add_widget_field() {
		$nonce =  wp_create_nonce ('slzexploore-delete-sidebar-nonce');
		$confirm_msg = esc_html__('Do you really want to delete this widget area?', 'exploore');
		$nonce = '<input type="hidden" name="slzexploore-delete-sidebar-nonce" data-confirm="'.esc_attr($confirm_msg).'" value="'.esc_attr($nonce).'" />';
		echo "\n<script type='text/html' id='slzexploore-custom-widget'>";
		echo "\n  <form class='slzexploore-add-widget' method='POST'>";
		echo "\n  <h3>". esc_html__('Exploore Custom Widgets', 'exploore')."</h3>";
		echo "\n    <input class='slzexploore_style_wrap' type='text' value='' placeholder = '". esc_html__('Enter Name of the new Widget Area here', 'exploore') ."' name='slzexploore-add-widget[name]' />";
		echo "\n    <input class='slzexploore_style_wrap' type='text' value='' placeholder = '". esc_html__('Enter class display on front-end', 'exploore') ."' name='slzexploore-add-widget[class]' />";
		echo "\n    <input class='slzexploore_button' type='submit' value='". esc_html__('Add Widget Area', 'exploore') ."' />";
		echo "\n    ". ($nonce);
		echo "\n  </form>";
		echo "\n</script>\n";
	}

	public function add_sidebar_area() {
		if( isset($_POST['slzexploore-add-widget']) && !empty($_POST['slzexploore-add-widget']['name']) ) {
			$sidebars = array();
			$sidebars = get_option('slzexploore_custom_sidebar');
			$name = $this->get_name($_POST['slzexploore-add-widget']['name']);
			$class = $_POST['slzexploore-add-widget']['class'];
			$sidebars[] = array('name'=>sanitize_title($name), 'title' => $name, 'class'=>$class);
			update_option('slzexploore_custom_sidebar', $sidebars);
			wp_redirect( esc_url( admin_url('widgets.php') ) );
			die();
		}
	}

	public function get_name( $name ) {
		if( empty($GLOBALS['wp_registered_sidebars']) ){
			return $name;
		}

		$taken = array();
		foreach ( $GLOBALS['wp_registered_sidebars'] as $sidebar ) {
			$taken[] = $sidebar['name'];
		}
		$sidebars = get_option('slzexploore_custom_sidebar');

		if( empty($sidebars) ) {
			$sidebars = array();
		}

		$taken = array_merge($taken, $sidebars);
		if( in_array($name, $taken) ) {
			$counter  = substr($name, -1);
			$new_name = "";
			if( !is_numeric($counter) ) {
				$new_name = $name . " 1";
			}
			else {
				$new_name = substr($name, 0, -1) . ((int) $counter + 1);
			}
			$name = $new_name;
		}
		return $name;
	}
	public function delete_custom_sidebar() {
		check_ajax_referer('slzexploore-delete-sidebar-nonce');
		if( !empty($_POST['name']) ) {
			$name = sanitize_title($_POST['name']);
			$sidebars = get_option('slzexploore_custom_sidebar');
			foreach($sidebars as $key => $sidebar){
				if( strcmp(trim($sidebar['name']), trim($name)) == 0) {
					unset($sidebars[$key]);
					update_option('slzexploore_custom_sidebar', $sidebars);
					echo "success";
					break;
				}
			}
		}
		die();
	}
}