<?php
/**
 * Options Config (ReduxFramework Sample Config File).
 *
 * For full documentation, please visit: https://docs.reduxframework.com
 *
 */
if (!class_exists('Slzexploore_Redux_Framework_Config')) {

	class Slzexploore_Redux_Framework_Config {

		public $args     = array();
		public $sections = array();
		public $theme;
		public $ReduxFramework;

		public function __construct() {

			if ( ! class_exists('ReduxFramework') ) {
				return;
			}

			// This is needed. Bah WordPress bugs.  ;)
			if ( true == Redux_Helpers::isTheme( __FILE__ ) ) {
				$this->initSettings();
			} else {
				add_action('plugins_loaded', array($this, 'initSettings'), 10);
			}

		}

		public function initSettings() {

			// Just for demo pureposes. Not needed per say.
			$this->theme = wp_get_theme();

			// Set the default arguments
			$this->setArguments();

			// Set a few help tabs so you can see how it's done
			$this->setHelpTabs();

			// Create the sections and fields
			$this->setSections();

			if (!isset($this->args['opt_name'])) { // No errors please
				return;
			}

			// If Redux is running as a plugin, this will remove the demo notice and links
			add_action( 'redux/loaded', array( $this, 'remove_demo' ) );

			$this->ReduxFramework = new ReduxFramework($this->sections, $this->args);
		}

		/**
		 * This is a test function that will let you see when the compiler hook occurs.
		 *
		 * It only runs if a field   set with compiler=>true is changed.
		 */
		function compiler_action($options, $css) {
			return;
		}

		/**
		 * Custom function for filtering the sections array.
		 *
		 */
		function dynamic_section($sections) {
			$sections[] = array(
				'title'  => esc_html__('Section via hook', 'exploore'),
				'desc'   => sprintf('<p class="description">%s</p>', esc_html__('This is a section created by adding a filter to the sections array. Can be used by child themes to add/remove sections from the options.', 'exploore')),
				'icon'   => 'el-icon-paper-clip',
				// Leave this as a blank section, no options just some intro text set above.
				'fields' => array()
			);

			return $sections;
		}

		/**
		 * Filter hook for filtering the args. Good for child themes to override or add to the args array. Can also be used in other functions.
		 *
		 */
		function change_arguments($args) {
			//$args['dev_mode'] = false;
			return $args;
		}

		/**
		 * Filter hook for filtering the default value of any given field. Very useful in development mode.
		 */
		function change_defaults($defaults) {
			$defaults['str_replace'] = 'Testing filter hook!';

			return $defaults;
		}

		// Remove the demo link and the notice of integrated demo from the redux-framework plugin
		function remove_demo() {

			// Used to hide the demo mode link from the plugin page. Only used when Redux is a plugin.
			if ( class_exists('ReduxFrameworkPlugin') ) {
				remove_filter('plugin_row_meta', array(ReduxFrameworkPlugin::instance(), 'plugin_metalinks'), null, 2);

				// Used to hide the activation notice informing users of the demo panel. Only used when Redux is a plugin.
				remove_action('admin_notices', array(ReduxFrameworkPlugin::instance(), 'admin_notices'));
			}
		}
		
		public function get_mail_template( $obj ) {
			if( SLZEXPLOORE_CORE_IS_ACTIVE && function_exists('slzexploore_core_get_mail_template')) {
				return slzexploore_core_get_mail_template( $obj );
			}
			return '';
		}
		
		public function get_contact_form( ) {
			if ( SLZEXPLOORE_CORE_IS_ACTIVE && method_exists( 'Slzexploore_Core_Com', 'get_contact_form' ) ) {
				return Slzexploore_Core_Com::get_contact_form();
			}
			return array();
		}

		public function setSections() {

			/*
			  Used within different fields. Simply examples. Search for ACTUAL DECLARATION for field examples
			*/
			// Background Patterns Reader
			$sample_patterns_path   = ReduxFramework::$_dir . '../sample/patterns/';
			$sample_patterns_url    = ReduxFramework::$_url . '../sample/patterns/';
			$sample_patterns        = array();
			$image_opt_path         = get_template_directory_uri() . '/assets/admin/images/';

			if ( is_dir( $sample_patterns_path ) ) {

				if ( $sample_patterns_dir = opendir( $sample_patterns_path ) ) {
					$sample_patterns = array();

					while ( ( $sample_patterns_file = readdir($sample_patterns_dir) ) !== false ) {

						if ( stristr( $sample_patterns_file, '.png' ) !== false || stristr( $sample_patterns_file, '.jpg' ) !== false ) {
							$name = explode( '.', $sample_patterns_file );
							$name = str_replace( '.' . end($name), '', $sample_patterns_file );
							$sample_patterns[] = array( 'alt' => $name, 'img' => $sample_patterns_url . $sample_patterns_file );
						}
					}
				}
			}

			ob_start();

			$ct          = wp_get_theme();
			$this->theme = $ct;
			$item_name   = $this->theme->get('Name');
			$tags        = $this->theme->Tags;
			$screenshot  = $this->theme->get_screenshot();
			$class       = $screenshot ? 'has-screenshot' : '';

			$customize_title = sprintf( esc_html__( 'Customize &#8220;%s&#8221;', 'exploore' ), $this->theme->display('Name') );

			?>
			<div id="current-theme" class="<?php echo esc_attr($class); ?>">
			<?php if ( $screenshot ) : ?>
				<?php if ( current_user_can('edit_theme_options') ) : ?>
						<a href="<?php echo esc_url( wp_customize_url() ); ?>" class="load-customize hide-if-no-customize" title="<?php echo esc_attr($customize_title); ?>">
							<img src="<?php echo esc_url($screenshot); ?>" alt="<?php esc_attr_e('Current theme preview', 'exploore'); ?>" />
						</a>
				<?php endif; ?>
					<img class="hide-if-customize" src="<?php echo esc_url($screenshot); ?>" alt="<?php esc_attr_e('Current theme preview', 'exploore' ); ?>" />
				<?php endif; ?>

				<h4><?php echo esc_html( $this->theme->display('Name') ); ?></h4>

				<div>
					<ul class="theme-info">
						<li><?php printf(esc_html__('By %s', 'exploore'), $this->theme->display('Author')); ?></li>
						<li><?php printf(esc_html__('Version %s', 'exploore'), $this->theme->display('Version')); ?></li>
						<li><?php echo '<strong>' . esc_html__('Tags', 'exploore') . ':</strong> '; ?><?php printf($this->theme->display('Tags')); ?></li>
					</ul>
					<p class="theme-description"><?php echo esc_html( $this->theme->display('Description') ); ?></p>
			<?php
			if ( $this->theme->parent() ) {
				printf(' <p class="howto">' . wp_kses( __('This <a href="%1$s">child theme</a> requires its parent theme, %2$s.', 'exploore'), array( 'a' => array('href' => array()) ) ) . '</p>', 'http://codex.wordpress.org/Child_Themes', $this->theme->parent()->display('Name'));
			}
			?>

				</div>
			</div>

			<?php
			$item_info = ob_get_contents();

			ob_end_clean();

			$admin_widget_url = '<a href="'.esc_url( admin_url('widgets.php','http') ).'" target="_blank">'.esc_html__( 'Widget', 'exploore' ).'</a>';
			$admin_menus_url  = '<a href="'.esc_url( admin_url('nav-menus.php','http') ).'" target="_blank">'.esc_html__( 'Menus', 'exploore' ).'</a>';
			$admin_icon_url   = '<a href="'.esc_url( admin_url('admin.php?page=slzexploore_icon','http') ).'" target="_blank">'.esc_html__( 'Icon Page', 'exploore' ).'</a>';
			$icon_url         = 'https://fortawesome.github.io/Font-Awesome/icons/';
			$fontawesome_url  = '<a href="'. esc_url( $icon_url ) .'">'.esc_html__( 'Fontawesome', 'exploore' ).'</a>';
			$google_api_url   = '<a href="'. esc_url('https://developers.google.com/maps/documentation/javascript/get-api-key') .'" target="_blank">'.esc_html__( 'here', 'exploore' ).'</a>';

			// ACTUAL DECLARATION OF SECTIONS
			// General setting
			$this->sections[] = array(
				'title'     => esc_html__( 'General', 'exploore' ),
				'icon'      => 'el-icon-adjust-alt',
				'fields'    => array(
					array(
						'id'       => 'slz-layout',
						'type'     => 'image_select',
						'title'    => esc_html__( 'Layout Display', 'exploore' ),
						'subtitle' => esc_html__( 'Choose type of layout', 'exploore' ),
						'desc'     => esc_html__( 'This option will change layout for all page of theme.', 'exploore' ),
						'options'  => array(
							'1' => array(
								'alt' => esc_attr__( 'Fluid', 'exploore' ),
								'img' => esc_url($image_opt_path) . 'full.png'
							),
							'2' => array(
								'alt' => esc_attr__( 'Boxed', 'exploore' ),
								'img' => esc_url($image_opt_path) . 'boxed.png'
							),
						),
						'default'  => '1'
					),
					
					array(
						'id'       => 'slz-layout-boxed-bg',
						'type'     => 'background',
						'title'    => esc_html__( 'Body Background', 'exploore' ),
						'required' => array('slz-layout','=','2'),
						'default'  => array(
							'background-color'      => '#ffffff',
							'background-image'      => '',
							'background-repeat'     => 'no-repeat',
							'background-attachment' => '',
							'background-position'	=> 'center center',
							'background-size'		=> 'cover'
						)
					),
					array(
			            'id'       => 'slz-site-skin',
			            'type'     => 'palette',
			            'title'    => esc_html__( 'Site Skin', 'exploore' ),
			            'subtitle' => esc_html__( 'Select a site skin', 'exploore' ),
			            'default'  => 'color-1',
			            'palettes' => array(
			                'color-1'  => array(
			                    '#fd0'
			                ),
			                'color-2' => array(
			                    '#2aacff'
			                ),
			                'color-3' => array(
			                    '#E91E63'
			                ),
			                'color-4' => array(
			                    '#86bc42'
			                ),
			                'color-5' => array(
			                    '#50bcb6'
			                ),
			                'color-6' => array(
			                    '#c74a73'
			                ),
			                'color-7'  => array(
			                    '#f66666'
			                ),
			                'color-8' => array(
			                    '#ffb0b0'
			                ),
			                'color-9' => array(
			                    '#ff9c00'
			                ),
			                'color-10' => array(
			                    '#F1C40F'
			                ),
			            )
				    ),
					array(
						'id'       => 'slz-logo-header',
						'type'     => 'media',
						'url'      => true,
						'title'    => esc_html__( 'Header Logo', 'exploore' ),
						'compiler' => 'true',
						'subtitle' => esc_html__( 'Choose logo image', 'exploore' ),
						'default'  => array( 'url' => esc_url( SLZEXPLOORE_LOGO_2 ) )
					),
					array(
						'id'       => 'slz-logo-header-transparent',
						'type'     => 'media',
						'url'      => true,
						'title'    => esc_html__( 'Header Transparent Logo', 'exploore' ),
						'compiler' => 'true',
						'subtitle' => esc_html__( 'Choose logo image for transparent header', 'exploore' ),
						'default'  => array( 'url' => esc_url( SLZEXPLOORE_LOGO ) )
					),
					array(
						'id'       => 'slz-logo-footer',
						'type'     => 'media',
						'url'      => true,
						'title'    => esc_html__( 'Footer Logo', 'exploore' ),
						'compiler' => 'true',
						'subtitle' => esc_html__( 'Choose logo image', 'exploore' ),
						'default'  => array( 'url' => esc_url( SLZEXPLOORE_LOGO_FOOTER ) )
					),
					array(
						'id'       => 'slz-logo-footer-light',
						'type'     => 'media',
						'url'      => true,
						'title'    => esc_html__( 'Footer Logo Light', 'exploore' ),
						'compiler' => 'true',
						'subtitle' => esc_html__( 'Choose logo image', 'exploore' ),
						'default'  => array( 'url' => '' )
					),
					array(
						'id'		=>	'slz-sticky-enable',
						'type'		=> 	'switch',
						'title'		=>	esc_html__('Header Sticky Enable', 'exploore'),
						'subtitle'  =>  esc_html__( 'Enable or disable fixed header when scroll', 'exploore' ),
						'default'   =>  true,
					),
					array(
						'id'       	=> 'slz-backtotop',
						'type'     	=> 'switch',
						'title'    	=> esc_html__( 'Back To Top Button', 'exploore' ),
						'subtitle' 	=> esc_html__( 'Setting for back to top button', 'exploore' ),
						'on'       	=> esc_html__( 'Show', 'exploore' ),
						'off'      	=> esc_html__( 'Hide', 'exploore' ),
						'default'  	=> true
					),
					array(
						'id'       => 'slz-currency-sign',
						'type'     => 'text',
						'title'    => esc_html__( 'Currency Sign', 'exploore' ),
						'default'  => '$'
					),
					array(
					    'id'       => 'slz-symbol-currency-position',
					    'type'     => 'button_set',
					    'title'    => esc_html__( 'Where To Show The Currency Sign?', 'exploore' ),
					    'subtitle' => esc_html__( 'Choose to show position of the currency symbol?', 'exploore' ),
					    'options'  => array(
					        'before' 	=> esc_html__( 'Before', 'exploore' ), 
					        'after' 	=> esc_html__( 'After', 'exploore' ),
					     ), 
					    'default' => 'before'
					),
					array(
						'id'       	=> 'slz-currency-decimal',
						'type'     	=> 'select',
						'title'    	=> esc_html__( 'Number of Decimals', 'exploore' ),
						'subtitle'	=> esc_html__( 'This sets the number of decimal points shown in displayed prices.', 'exploore' ),
						'options'  => array(
					        1 	=> 1,
					        2 	=> 2,
					        3 	=> 3,
					        4 	=> 4
					     )
					),
					array(
						'id'       => 'slz-map-key-api',
						'type'     => 'text',
						'title'    => esc_html__( 'Map Google API Key', 'exploore' ),
						'subtitle' => sprintf( esc_html__( 'This key is used to run a some feature of Map.Please refer %s to create a key', 'exploore' ), $google_api_url ),
					),
					array(
						'id'        => 'slz-search-box-section',
						'type'      => 'section',
						'title'     => esc_html__( 'Search Box', 'exploore' ),
						'subtitle'  => esc_html__( 'Setting for tabs in the "search box" on the homepage.', 'exploore' ),
						'indent'    => true,
					),
					array(
						'id'        => 'slz-search-general',
						'type'      => 'sorter',
						'title'     => esc_html__( 'Search Box Info', 'exploore' ),
						'subtitle'  => esc_html__( 'Choose what fields to show on search box of home page (tour,hotel...)', 'exploore' ),
						'options'   => array(
							'disabled' => array(
							),
							'enabled'  => array(
								'tour' 	    =>  esc_html__( 'Tour', 'exploore' ),
								'hotel'	 	=>  esc_html__( 'Hotel', 'exploore' ),
								'car' 		=>  esc_html__( 'Car Rent', 'exploore' ),
								'cruise'	=>  esc_html__( 'Cruises', 'exploore' ),
							),
						),
					),
					array(
						'id'       => 'slz-search-tour-icon',
						'type'     => 'text',
						'title'    => esc_html__( 'Search Tour Tab Icon', 'exploore' ),
						'subtitle' => sprintf(__( 'Please go on "Exploore->%s" to referentce about icons of our theme.', 'exploore' ), $admin_icon_url ),
						'default'  => 'flaticon-people'
					),
					array(
						'id'       => 'slz-search-hotel-icon',
						'type'     => 'text',
						'title'    => esc_html__( 'Search Hotel Tab Icon', 'exploore' ),
						'subtitle' => sprintf(__( 'Please go on "Exploore->%s" to referentce about icons of our theme.', 'exploore' ), $admin_icon_url ),
						'default'  => 'flaticon-three'
					),	
					array(
						'id'       => 'slz-search-car-icon',
						'type'     => 'text',
						'title'    => esc_html__( 'Search Car Rent Tab Icon', 'exploore' ),
						'subtitle' => sprintf(__( 'Please go on "Exploore->%s" to referentce about icons of our theme.', 'exploore' ), $admin_icon_url ),
						'default'  => 'flaticon-transport-7'
					),	
					array(
						'id'       => 'slz-search-cruises-icon',
						'type'     => 'text',
						'title'    => esc_html__( 'Search Cruises Tab Icon', 'exploore' ),
						'subtitle' => sprintf(__( 'Please go on "Exploore->%s" to referentce about icons of our theme.', 'exploore' ), $admin_icon_url ),
						'default'  => 'flaticon-transport-4'
					),
				)
			);

			// Social
			$this->sections[] = array (
				'title'     => esc_html__( 'Social', 'exploore' ),
				'desc'	    => wp_kses( __( 'This part will be used for manage your social network', 'exploore' ), array('strong' => array())),
				'icon'      => 'el-icon-group-alt',
				'fields'    => array(
					array(
						'id'        => 'slz-social-section',
						'type'      => 'section',
						'title'     => esc_html__( 'Social Page', 'exploore' ),
						'subtitle'  => esc_html__( 'These information will be used in footer area', 'exploore' ),
						'indent'    => true,
					),
					array(
						'id'       => 'slz-social-facebook',
						'type'     => 'text',
						'title'    => esc_html__( 'Facebook', 'exploore' ),
						'default'  => 'http://facebook.com'
					),
					array(
						'id'       => 'slz-social-twitter',
						'type'     => 'text',
						'title'    => esc_html__( 'Twitter', 'exploore' ),
						'default'  => 'http://twitter.com'
					),
					array(
						'id'       => 'slz-social-google-plus',
						'type'     => 'text',
						'title'    => esc_html__( 'Googleplus', 'exploore' ),
						'default'  => 'https://plus.google.com/'
					),
					array(
						'id'       => 'slz-social-pinterest',
						'type'     => 'text',
						'title'    => esc_html__( 'Pinterest', 'exploore' ),
						'default'  => 'https://pinterest.com/'
					),
					array(
						'id'       => 'slz-social-instagram',
						'type'     => 'text',
						'title'    => esc_html__( 'Instagram', 'exploore' ),
						'default'  => 'http://instagram.com'
					),
					array(
						'id'       => 'slz-social-dribbble',
						'type'     => 'text',
						'title'    => esc_html__( 'Dribbble', 'exploore' ),
						'default'  => 'http://dribbble.com'
					),
					array(
						'id'        => 'slz-social-section',
						'type'      => 'section',
						'indent'    => false,
					),
					array(
						'id'        => 'slz-social-share',
						'type'      => 'sorter',
						'title'     => esc_html__( 'Social Share Link', 'exploore' ),
						'subtitle'  => esc_html__( 'These information will be used in some shortcodes like hotel, tour, car or cruise.', 'exploore' ),
						'options'   => array(
							'disabled' => array(
								'pinterest'     => esc_html__( 'Pinterest', 'exploore' ),
								'linkedin'  	=> esc_html__( 'LinkedIn', 'exploore' ),
								'digg'	 		=> esc_html__( 'Digg', 'exploore' ),
							),
							'enabled'  => array(
								'facebook'  	=> esc_html__( 'Facebook', 'exploore' ),
								'twitter'	 	=> esc_html__( 'Twitter', 'exploore' ),
								'google-plus'   => esc_html__( 'Google Plus', 'exploore' ),
							),
						),
					),
				)
			);

			// Header
			$this->sections[] = array(
				'title'   => esc_html__( 'Header', 'exploore' ),
				'desc'    => esc_html__( 'This section will change setting for header', 'exploore' ),
				'icon'    => 'el-icon-caret-up',
				'fields'  => array(
					array(
					    'id'       => 'slz-style-header',
					    'type'     => 'image_select',
					    'title'    => esc_html__('Main Layout', 'exploore'), 
					    'subtitle' => esc_html__('Select main content and sidebar alignment. Choose between 1, 2 or 3 column layout.', 'exploore'),
					    'options'  => array(
					        'one'   => array(
								'alt' => esc_html__( 'Style 1', 'exploore' ),
								'img' => esc_url($image_opt_path) . 'style_1.png'
							),
							'two' => array(
								'alt' => esc_html__( 'Style 2', 'exploore' ),
								'img' => esc_url($image_opt_path) . 'style_2.png'
							),
							'three'   => array(
								'alt' => esc_html__( 'Style 3', 'exploore' ),
								'img' => esc_url($image_opt_path) . 'style_3.png'
							),
							'four'   => array(
								'alt' => esc_html__( 'Style 4', 'exploore' ),
								'img' => esc_url($image_opt_path) . 'style_4.png'
							),
							'five'   => array(
								'alt' => esc_html__( 'Style 5', 'exploore' ),
								'img' => esc_url($image_opt_path) . 'style_5.png'
							)
					    ),
					    'default' => 'one'
					),
					array(
						'id'       => 'slz-logo-header-04',
						'type'     => 'media',
						'url'      => true,
						'title'    => esc_html__( 'Header Logo', 'exploore' ),
						'subtitle'  => esc_html__( 'Only apply for header style 4', 'exploore' ),
						'compiler' => 'true',
					),
					array(
						'id'        => 'slz-header-search-icon',
						'type'      => 'switch',
						'title'     => esc_html__( 'Search Icon', 'exploore' ),
						'subtitle'  => esc_html__( 'Show / Hide search icon in the navigation', 'exploore' ),
						'on'       	=> esc_html__( 'Show', 'exploore' ),
						'off'      	=> esc_html__( 'Hide', 'exploore' ),
						'default'   => true
					),
					array(
					    'id'       => 'slz-header-search-type',
					    'type'     => 'image_select',
					    'title'    => esc_html__('Search Layout', 'exploore'), 
					    'options'  => array(
					        '1'   => array(
								'alt' => esc_html__( 'Style 1', 'exploore' ),
								'img' => esc_url($image_opt_path) . 'searchbar-stype-1.png'
							),
							'2' => array(
								'alt' => esc_html__( 'Style 2', 'exploore' ),
								'img' => esc_url($image_opt_path) . 'searchbar-stype-2.png'
							),
					    ),
					    'default' => '1'
					),
					array(
						'id'        => 'slz-header-section',
						'type'      => 'section',
						'title'     => esc_html__( 'Header Top', 'exploore' ),
						'subtitle'  => esc_html__( 'Configure detailed information for each content in header', 'exploore' ),
						'indent'    => true,
					),
					array(
						'id'       => 'slz-language-switcher',
						'type'     => 'switch',
						'title'    => esc_html__( 'Language Switcher On Header Top', 'exploore' ),
						'subtitle' => esc_html__( 'Show language switcher of plugin WPML on header top', 'exploore' ),
						'on'       => esc_html__( 'Show', 'exploore' ),
						'off'      => esc_html__( 'Hide', 'exploore' ),
						'default'  => false
					),
					array(
						'id'        => 'slz-header-social-section',
						'type'      => 'section',
						'class'     => 'slz-sub-session',
						'title'     => esc_html__( 'Social', 'exploore' ),
						'subtitle'  => esc_html__( 'Settings social for header top', 'exploore' ),
						'indent'    => true,
					),
						array(
							'id'        => 'slz-header-social-info',
							'type'      => 'switch',
							'title'     => esc_html__( 'Social Content', 'exploore' ),
							'on'        => esc_html__( 'Show', 'exploore' ),
							'off'       => esc_html__( 'Hide', 'exploore' ),
							'default'   => true
						),
						array(
							'id'       => 'slz-header-social',
							'type'     => 'sorter',
							'title'    => esc_html__( 'Social list', 'exploore' ),
							'subtitle' => esc_html__( 'Only apply for header three.Please go on "Social" menu to enter social link.', 'exploore' ),
							'required' => array('slz-header-social-info','=',true),
							'options'  => array(
								'disabled' => array(
									
								),
								'enabled'  => array(
									'facebook'     => esc_html__( 'Facebook', 'exploore' ),
									'google-plus'  => esc_html__( 'Google plus', 'exploore' ),
									'twitter'      => esc_html__( 'Twitter', 'exploore' ),
									'instagram'    => esc_html__( 'Instagram', 'exploore' ),
									'dribbble'     => esc_html__( 'Dribbble', 'exploore' ),
									'pinterest'    => esc_html__( 'Pinterest', 'exploore' ),
								),
							),
						),
						array(
							'id'        => 'slz-topbar-more-social',
							'type'      => 'multi_text',
							'class'     => 'slz-sub-session',
							'title'     => esc_html__( 'Add More Social', 'exploore' ),
							'required'  => array('slz-header-social-info','=',true),
							'subtitle'  => sprintf( wp_kses( __( 'Please use format: icon / Social Link.<br>Ex: ["fa fa-facebook","http://facebook.com"]', 'exploore' ), array('br' => array()) )),
						),
					array(
						'id'     => 'section-end',
						'type'   => 'section',
						'indent' => false,
					),
					
					array(
						'id'        => 'slz-header-top-show-menu',
						'type'      => 'switch',
						'title'     => esc_html__( 'Show menu', 'exploore' ),
						'subtitle'  => esc_html__( 'Show or hide menu in header top.', 'exploore' ),
						'on'        => esc_html__( 'Show', 'exploore' ),
						'off'       => esc_html__( 'Hide', 'exploore' ),
						'default'   => false
					),
					array(
						'id'        => 'slz-header-top-menu-position',
						'type'      => 'radio',
						'title'     => esc_html__( 'Menu Position', 'exploore' ),
						'subtitle'  => esc_html__( 'Select where display menu in header top.', 'exploore' ),
						'required'  => array( 'slz-header-top-show-menu', '=', true ),
						'options'   => array(
							'left'     =>  esc_html__( 'Left', 'exploore' ),
							'right'    =>  esc_html__( 'Right', 'exploore' ),
						),
						'default'   => 'right'
					),
					array(
						'id'        => 'slz-header-top-menu',
						'type'      => 'select',
						'title'     => esc_html__( 'Select Menu', 'exploore' ),
						'subtitle'  => esc_html__( 'Select menu for topbar menu. This changes will be apply for all headers.', 'exploore' ),
						'required'  => array( 'slz-header-top-show-menu', '=', true ),
						'data'      => 'menus'
					),
					array(
						'id'        => 'slz-header-other-info',
						'type'      => 'multi_text',
						'title'     => esc_html__( 'Other Infomation', 'exploore' ),
						'subtitle'  => sprintf( wp_kses( __( 'Please use format: icon / Content.<br>Ex: fa fa-phone / +84 909 015 345', 'exploore' ), array('br' => array()) )),
					),
					array(
						'id'       => 'slz-header-text-block-content',
						'type'     => 'textarea',
						'title'    => esc_html__( 'Text Block Content', 'exploore' ),
						'subtitle' => esc_html__( "Enter text or HTML for header title content.\n Ex: <a href=\"tel:123456\"><i class=\"fa fa fa-phone \"></i> 123456</a>", 'exploore' ),
					),
					array(
						'id'       => 'slz-header-account',
						'type'     => 'button_set',
						'title'    => esc_html__('Account Session', 'exploore'),
						'subtitle'  => esc_html__( 'Choose type account to display or hide on header', 'exploore' ),
						//Must provide key => value pairs for options
						'options' => array(
							'woocommerce'  => esc_html__( 'Woocommerce', 'exploore'),
							'theme'     => esc_html__( 'Theme', 'exploore'), 
							'hide'    => esc_html__( 'Hide', 'exploore'),
						), 
						'default' => 'hide'
					),
					array(
						'id'     => 'section-end',
						'type'   => 'section',
						'indent' => false,
					),
				)
			);

			// Menu
			$this->sections[] = array(
				'title'    => esc_html__( 'Menu', 'exploore' ),
				'desc'     => esc_html__( 'Configuration for main navigation on top', 'exploore' ),
				'icon'     => 'el-icon-brush',
				'fields'   => array(
					array(
						'id'        => 'slz-submenu-section',
						'type'      => 'section',
						'title'     => esc_html__( 'Main Menu Setting', 'exploore' ),
						'subtitle'  => esc_html__( 'Configuration for main menu', 'exploore' ),
						'indent'    => true,
					),
					array(
						'id'        => 'slz-menu-custom',
						'type'      => 'switch',
						'title'     => esc_html__( 'Main Menu Custom', 'exploore' ),
						'on'       	=> esc_html__( 'Custom', 'exploore' ),
						'off'      	=> esc_html__( 'Default', 'exploore' ),
						'default'   => false,
					),
					array(
						'id'        => 'slz-menu-item-text',
						'type'      => 'link_color',
						'title'     => esc_html__( 'Menu Item Color', 'exploore' ),
						'subtitle'  => esc_html__( 'Set color for menu item', 'exploore' ),
						'required'  => array( 'slz-menu-custom', '=', true ),
						'default'   => array(
							'regular'   => '#555e69',
							'hover'     => '#ffdd00',
							'active'    => '#ffdd00'
						)
					),
					array(
						'id'             => 'slz-menu-height',
						'type'           => 'dimensions',
						'units'          => 'px',
						'units_extended' => 'false',
						'all'			 => false,
						'width'			 => false,
						'title'          => esc_html__( 'Menu Item Height', 'exploore' ),
						'subtitle'       => esc_html__( 'Choose line height for each menu item', 'exploore' ),
						'required'       => array( 'slz-menu-custom', '=', true ),
						'default'        => array (
							'width'		=> 'auto',
							'height'	=> '100px'
						)
					),
					array(
						'id'     => 'section-end',
						'type'   => 'section',
						'indent' => false,
					),
					array(
						'id'        => 'slz-submenu-section',
						'type'      => 'section',
						'title'     => esc_html__( 'Dropdown Menu Setting', 'exploore' ),
						'subtitle'  => esc_html__( 'Configuration for submenu', 'exploore' ),
						'indent'    => true,
					),
					array(
						'id'        => 'slz-submenu-custom',
						'type'      => 'switch',
						'title'     => esc_html__( 'Dropdown Menu Custom', 'exploore' ),
						'subtitle'	=> esc_html__( 'In default, dropdown menu will follow "Submenu Style" above', 'exploore' ),
						'on'       	=> esc_html__( 'Custom', 'exploore' ),
						'off'      	=> esc_html__( 'Default', 'exploore' ),
						'default'   => false,
					),
					array(
						'id'        => 'slz-submenu-bg',
						'type'      => 'color_rgba',
						'title'     => esc_html__( 'Submenu Background', 'exploore' ),
						'subtitle'  => esc_html__( 'Set background color for submenu dropdown', 'exploore' ),
						'default'   => array(
							'color'    => '#fff',
							'alpha'    => '1',
							'rgba'     => 'rgba(255, 255, 255, 1)'
						),
						'required'  => array( 'slz-submenu-custom', '=', true ),
						'mode'      => 'background',
						'validate'  => 'colorrgba'
					),
					array(
						'id'        => 'slz-submenu-color',
						'type'      => 'link_color',
						'title'     => esc_html__( 'SubMenu Item Color', 'exploore' ),
						'subtitle'  => esc_html__( 'Set color for text in submenu', 'exploore' ),
						'required'  => array( 'slz-submenu-custom', '=', true ),
						'default'   => array(
							'regular'   => '#555e69',
							'hover'     => '#555e69',
							'active'    => '#555e69',
							'visited'   => '#555e69'
						)
					),
					array(
					    'id'       => 'slz-submenu-active-color',
					    'type'     => 'color_rgba',
					    'title'    =>  esc_html__('Submenu Active Color', 'exploore'), 
					    'subtitle' =>  esc_html__('Choose bacground color for submenu when active.', 'exploore'),
					    'required'  => array( 'slz-submenu-custom', '=', true ),
					    'default'   => array(
								'color'    => '#f5f5f5',
							),
					    'mode'      => 'background',
						'validate'  => 'colorrgba'
					),
					array(
						'id'             => 'slz-submenu-width',
						'type'           => 'dimensions',
						'units_extended' => false,
						'title'          => esc_html__( 'Submenu Width', 'exploore' ),
						'height'         => false,
						'required'       => array( 'slz-submenu-custom', '=', true ),
						'default'        => array(
							'width'  => 'auto',
							'height' => '60'
						)
					),
					array(
						'id'        => 'slz-submenu-border',
						'type'      => 'border',
						'title'     => esc_html__( 'Submenu Separate', 'exploore' ),
						'subtitle'  => esc_html__( 'Set border bottom attribute for submenu', 'exploore' ),
						'all'       => false,
						'top'       => false,
						'left'      => false,
						'right'     => false,
						'required'  => array( 'slz-submenu-custom', '=', true ),
						'default'   => array(
							'border-style'  => 'solid',
							'border-color'  => '#ffdd00',
							'border-bottom' => '2px',
							'border-top'    => '0px',
							'border-left'   => '0px',
							'border-right'  => '0px'
						)
					),
					array(
						'id'             => 'slz-submenu-padding',
						'type'           => 'spacing',
						'mode'           => 'padding',
						'all'            => false,
						'units'          => 'px',      // You can specify a unit value. Possible: px, em, %
						'units_extended' => 'false',   // Allow users to select any type of unit
						'title'          => esc_html__( 'SubMenu Item Padding', 'exploore' ),
						'subtitle'       => esc_html__( 'Choose inwards spacing for each submenu item', 'exploore' ),
						'desc'           => esc_html__( 'unit is "px"', 'exploore' ),
						'required'  	 => array( 'slz-submenu-custom', '=', true ),
						'default'        => false
					),
					array(
						'id'        => 'slz-dropdownmenu-align',
						'type'      => 'radio',
						'title'     => esc_html__( 'Dropdown Menu Align', 'exploore' ),
						'options'   => array(
							'left'     => esc_html__( 'Left', 'exploore' ),
							'right'    => esc_html__( 'Right', 'exploore' )
						),
						'default'   => 'right'
					),
					
				)
			);

			// Page title setting
			$this->sections[] = array(
				'title'     => esc_html__( 'Page Title Setting', 'exploore' ),
				'icon'      => 'el-icon-website',
				'fields'    => array(
					array(
						'id'        => 'slz-page-title-show',
						'type'      => 'switch',
						'title'     => esc_html__( 'Show Page Title', 'exploore' ),
						'subtitle'  => esc_html__( 'Choose to show or hide page title', 'exploore' ),
						'on'       	=> esc_html__( 'Show', 'exploore'),
						'off'      	=> esc_html__( 'Hide', 'exploore'),
						'default'   => true,
					),
					array(
						'id'        => 'slz-page-title-bg',
						'type'      => 'background',
						'title'     => esc_html__( 'Page Title Background Image', 'exploore' ),
						'subtitle'  => esc_html__( 'Body background image for page title section', 'exploore' ),
						'default'   => array(
							'background-color'      => '#f3f3f3',
							'background-repeat'     => 'no-repeat',
							'background-size'       => 'cover',
							'background-attachment' => 'fixed',
							'background-position'   => 'center center',
							'background-image'      => ''
						)
					),
					array(
						'id'        => 'slz-pagetitle-overlay-bg',
						'type'      => 'color_rgba',
						'title'     => esc_html__( 'Page Title Overlay Background', 'exploore' ),
						'default'   => array(
							'color'     => '#fff',
							'alpha'     => '0',
							'rgba'      => 'rgba(255, 255, 255, 0)'
						)
					),
					array(
						'id'     	=> 'slz-pagetitle-pl-notice',
						'type'   	=> 'info',
						'notice' 	=> false,
						'style'  	=> 'info',
						'title'  	=> esc_html__( 'Background Parallax', 'exploore' ),
						'desc'   	=> esc_html__( 'To use background parallax effect for Page Title, please set background-attachment field is "Fixed"', 'exploore')
					),
					array(
						'id'             => 'slz-page-title-height',
						'type'           => 'dimensions',
						'units'          => 'px',
						'units_extended' => 'false',
						'all'			 => false,
						'width'			 => false,
						'title'          => esc_html__( 'Page Title Height', 'exploore' ),
						'default'        => array (
							'width'		=> 'auto',
							'height'	=> '540px'
						)
					),
					array(
						'id'        => 'slz-pagetitle-align',
						'type'      => 'radio',
						'title'     => esc_html__( 'Pagetitle Text Align', 'exploore' ),
						'options'   => array(
							'center'   =>  esc_html__( 'Center', 'exploore' ),
							'left'     =>  esc_html__( 'Left', 'exploore' ),
							'right'    =>  esc_html__( 'Right', 'exploore' ),
						),
						'default'   => 'left'
					),
					array(
						'id'        => 'slz-title',
						'type'      => 'section',
						'title'     => esc_html__( 'The Title', 'exploore' ),
						'indent'    => true,
					),
					array(
						'id'       => 'slz-show-title',
						'type'     => 'switch',
						'title'    => esc_html__( 'Show Title', 'exploore' ),
						'subtitle' => esc_html__( 'Choose to show or hide title (only apply for page)', 'exploore' ),
						'on'       => esc_html__( 'Show', 'exploore'),
						'off'      => esc_html__( 'Hide', 'exploore'),
						'default'  => true,
					),
					array(
						'id'             => 'slz-pagetitle-title',
						'type'           => 'typography',
						'title'          => esc_html__( 'Page Title Text', 'exploore' ),
						'google'         => false,
						'font-backup'    => true,
						'line-height'    => false,
						'preview'        => true,
						'text-transform' => true,
						'font-family'    => false,
						'text-align'     => false,
						'all_styles'     => true,
						'units'          => 'px',
						// Defaults to px
						'subtitle'       => esc_html__( 'Config typography for page title text', 'exploore' ),
						'default'        => array(
							'color'           => '#ffffff',
							'font-weight'     => '900',
							'font-size'       => '100px',
							'text-transform'  => 'uppercase'
						),
					),
					array(
						'id'     => 'slz-title-end',
						'type'   => 'section',
						'indent' => false,
					),
					array(
						'id'        => 'slz-breadcrumb',
						'type'      => 'section',
						'title'     => esc_html__( 'Breadcrumb', 'exploore' ),
						'indent'    => true,
					),
					array(
						'id'        => 'slz-show-breadcrumb',
						'type'      => 'switch',
						'title'     => esc_html__( 'Show Breadcrumb', 'exploore' ),
						'subtitle'  => esc_html__( 'Choose to show or hide breadcrumb', 'exploore' ),
						'on'       	=> esc_html__('Show', 'exploore'),
						'off'      	=> esc_html__('Hide', 'exploore'),
						'default'   => true,
					),
					array(
						'id'             => 'slz-breadcrumb-path',
						'type'           => 'typography',
						'title'          => esc_html__( 'Breadcrumb Home', 'exploore' ),
						'google'         => false,
						'font-backup'    => true,
						'line-height'    => false,
						'preview'        => true,
						'text-transform' => true,
						'font-family'    => false,
						'text-align'     => false,
						'all_styles'     => true,
						'units'          => 'px',
						// Defaults to px
						'subtitle'       => esc_html__( 'Config typography for breadcrumb title', 'exploore' ),
						'default'        => array(
							'color'           => '#ffffff',
							'font-weight'     => '400',
							'font-size'       => '20px',
							'text-transform'  => 'uppercase',
						)
					),
					array(
						'id'             => 'slz-breadcrumb-path2',
						'type'           => 'typography',
						'title'          => esc_html__( 'Breadcrumb Link', 'exploore' ),
						'google'         => false,
						'font-backup'    => false,
						'color'          => false,
						'line-height'    => false,
						'preview'        => true,
						'text-transform' => true,
						'font-family'    => false,
						'text-align'     => false,
						'all_styles'     => false,
						'units'          => 'px',
						// Defaults to px
						'subtitle'       => esc_html__( 'Config typography for breadcrumb title', 'exploore' ),
						'default'        => array(
							'font-weight'     => '400',
							'font-size'       => '12px',
							'text-transform'  => 'uppercase',
						)
					),
					array(
					    'id'          => 'slz-breadcrumb-border-color',
					    'type'        => 'color',
					    'title'       => esc_html__('Breadcrumb Border Bottom Color', 'exploore'), 
					    'subtitle'    => esc_html__('Choose color for border bottom of brreadcrumb', 'exploore'),
					    'default'     => '',
					    'transparent' => false,
					    'validate'    => 'color',
					),
					array(
						'id'        => 'slz-breadcrumb-labels',
						'type'      => 'section',
						'title'     => esc_html__( 'Breadcrumb Labels', 'exploore' ),
						'indent'    => true,
					),
					array(
						'id'          => 'slz-breadcrumb-labels-car',
						'type'        => 'text',
						'title'       => esc_html__( 'Car Rents', 'exploore' ),
						'subtitle'    => esc_html__( 'Enter breadcrumb label for car rent detail page.', 'exploore' ),
						'placeholder' => esc_html__( 'Car Rent', 'exploore' )
					),
					array(
						'id'          => 'slz-breadcrumb-labels-car-cat',
						'type'        => 'text',
						'title'       => esc_html__( 'Car Categories', 'exploore' ),
						'subtitle'    => esc_html__( 'Enter breadcrumb label for car category page.', 'exploore' ),
						'placeholder' => esc_html__( 'Car Categories', 'exploore' )
					),
					array(
						'id'          => 'slz-breadcrumb-labels-car-location',
						'type'        => 'text',
						'title'       => esc_html__( 'Car Locations', 'exploore' ),
						'subtitle'    => esc_html__( 'Enter breadcrumb label for car location page.', 'exploore' ),
						'placeholder' => esc_html__( 'Car Locations', 'exploore' )
					),
					array(
						'id'          => 'slz-breadcrumb-labels-cruise',
						'type'        => 'text',
						'title'       => esc_html__( 'Cruises', 'exploore' ),
						'subtitle'    => esc_html__( 'Enter breadcrumb label for cruise detail page.', 'exploore' ),
						'placeholder' => esc_html__( 'Cruises', 'exploore' )
					),
					array(
						'id'          => 'slz-breadcrumb-labels-cruise-cat',
						'type'        => 'text',
						'title'       => esc_html__( 'Cruises Categories', 'exploore' ),
						'subtitle'    => esc_html__( 'Enter breadcrumb label for cruise category page.', 'exploore' ),
						'placeholder' => esc_html__( 'Cruises Categories', 'exploore' )
					),
					array(
						'id'          => 'slz-breadcrumb-labels-cruise-location',
						'type'        => 'text',
						'title'       => esc_html__( 'Cruises Locations', 'exploore' ),
						'subtitle'    => esc_html__( 'Enter breadcrumb label for cruise location page.', 'exploore' ),
						'placeholder' => esc_html__( 'Cruises Locations', 'exploore' )
					),
					array(
						'id'          => 'slz-breadcrumb-labels-cruise-facility',
						'type'        => 'text',
						'title'       => esc_html__( 'Cruises Facilities', 'exploore' ),
						'subtitle'    => esc_html__( 'Enter breadcrumb label for cruise facility page.', 'exploore' ),
						'placeholder' => esc_html__( 'Cruises Facilities', 'exploore' )
					),
					array(
						'id'          => 'slz-breadcrumb-labels-hotel',
						'type'        => 'text',
						'title'       => esc_html__( 'Accommodations', 'exploore' ),
						'subtitle'    => esc_html__( 'Enter breadcrumb label for accommodation detail page.', 'exploore' ),
						'placeholder' => esc_html__( 'Accommodations', 'exploore' )
					),
					array(
						'id'          => 'slz-breadcrumb-labels-hotel-cat',
						'type'        => 'text',
						'title'       => esc_html__( 'Accommodations Categories', 'exploore' ),
						'subtitle'    => esc_html__( 'Enter breadcrumb label for accommodation category page.', 'exploore' ),
						'placeholder' => esc_html__( 'Accommodations Categories', 'exploore' )
					),
					array(
						'id'          => 'slz-breadcrumb-labels-hotel-facility',
						'type'        => 'text',
						'title'       => esc_html__( 'Accommodation Facilities', 'exploore' ),
						'subtitle'    => esc_html__( 'Enter breadcrumb label for accommodation facility page.', 'exploore' ),
						'placeholder' => esc_html__( 'Facilities', 'exploore' )
					),
					array(
						'id'          => 'slz-breadcrumb-labels-hotel-location',
						'type'        => 'text',
						'title'       => esc_html__( 'Accommodation Locations', 'exploore' ),
						'subtitle'    => esc_html__( 'Enter breadcrumb label for accommodation location page.', 'exploore' ),
						'placeholder' => esc_html__( 'Locations', 'exploore' )
					),
					array(
						'id'          => 'slz-breadcrumb-labels-tour',
						'type'        => 'text',
						'title'       => esc_html__( 'Tours', 'exploore' ),
						'subtitle'    => esc_html__( 'Enter breadcrumb label for tour detail page.', 'exploore' ),
						'placeholder' => esc_html__( 'Tours', 'exploore' )
					),
					array(
						'id'          => 'slz-breadcrumb-labels-tour-cat',
						'type'        => 'text',
						'title'       => esc_html__( 'Tour Categories', 'exploore' ),
						'subtitle'    => esc_html__( 'Enter breadcrumb label for tour category page.', 'exploore' ),
						'placeholder' => esc_html__( 'Tour Categories', 'exploore' )
					),
					array(
						'id'          => 'slz-breadcrumb-labels-tour-location',
						'type'        => 'text',
						'title'       => esc_html__( 'Tour Locations', 'exploore' ),
						'subtitle'    => esc_html__( 'Enter breadcrumb label for tour location page.', 'exploore' ),
						'placeholder' => esc_html__( 'Tour Locations', 'exploore' )
					),
					array(
						'id'          => 'slz-breadcrumb-labels-team',
						'type'        => 'text',
						'title'       => esc_html__( 'Teams', 'exploore' ),
						'subtitle'    => esc_html__( 'Enter breadcrumb label for team detail page.', 'exploore' ),
						'placeholder' => esc_html__( 'Teams', 'exploore' )
					),
					array(
						'id'          => 'slz-breadcrumb-labels-team-cat',
						'type'        => 'text',
						'title'       => esc_html__( 'Team Categories', 'exploore' ),
						'subtitle'    => esc_html__( 'Enter breadcrumb label for team category page.', 'exploore' ),
						'placeholder' => esc_html__( 'Team Categories', 'exploore' )
					)
				)
			);

			// Sidebar setting
			$this->sections[] = array(
				'title'     => esc_html__( 'Sidebar', 'exploore' ),
				'desc'      => esc_html__( 'Configuration for sidebar', 'exploore' ),
				'icon'      => 'el-icon-caret-right',
				'fields'    => array(
					array(
						'id'        => 'slz-sidebar-section',
						'type'      => 'section',
						'title'     => esc_html__( 'Default Sidebar', 'exploore' ),
						'indent'    => true,
					),
					array(
						'id'        => 'slz-sidebar-layout',
						'type'      => 'image_select',
						'title'     => esc_html__( 'Default Sidebar Layout', 'exploore' ),
						'subtitle'  => esc_html__( 'Set how to display default sidebar', 'exploore' ),
						'options'   => array(
							'left'  => array(
								'alt' => 'left',
								'img' => esc_url($image_opt_path) . 'left.png'
							),
							'right' => array(
								'alt' => 'right',
								'img' => esc_url($image_opt_path) . 'right.png'
							),
							'none'  => array(
								'alt' => 'none',
								'img' => esc_url($image_opt_path) . 'nosidebar.png'
							)
						),
						'default'   => 'right'
					),
					array(
						'id'        => 'slz-sidebar',
						'type'      => 'select',
						'data'      => 'sidebars',
						'title'     => esc_html__( 'Default Sidebar', 'exploore' ),
						'subtitle'  => sprintf(__( 'You can create new sidebar in Appearance -> %s', 'exploore' ), $admin_widget_url ),
						'default'   => ''
					),
					array(
						'id'        => 'slz-sidebar-section',
						'type'      => 'section',
						'title'     => esc_html__( 'Blog Sidebar', 'exploore' ),
						'indent'    => true,
					),
					array(
						'id'        => 'slz-blog-sidebar-layout',
						'type'      => 'image_select',
						'title'     => esc_html__( 'Blog Sidebar Layout', 'exploore' ),
						'subtitle'  => esc_html__( 'Set how to display sidebar for blog single pages.', 'exploore' ),
						'options'   => array(
							'left'  => array(
								'alt' => esc_html__( 'left', 'exploore' ),
								'img' => esc_url($image_opt_path) . 'left.png'
							),
							'right' => array(
								'alt' => esc_html__( 'right', 'exploore' ),
								'img' => esc_url($image_opt_path) . 'right.png'
							),
							'none'  => array(
								'alt' => esc_html__( 'none', 'exploore' ),
								'img' => esc_url($image_opt_path) . 'nosidebar.png'
							)
						),
						'default'   => 'right'
					),
					array(
						'id'        => 'slz-blog-sidebar',
						'type'      => 'select',
						'data'      => 'sidebars',
						'title'     => esc_html__( 'Blog Sidebar', 'exploore' ),
						'subtitle'  => sprintf( esc_html__( 'You can create new sidebar in Appearance -> %s', 'exploore' ), $admin_widget_url ),
						'default'   => ''
					),

					array(
						'id'        => 'slz-sidebar-section',
						'type'      => 'section',
						'title'     => esc_html__( 'Tour Sidebar', 'exploore' ),
						'indent'    => true,
					),
					array(
						'id'        => 'slz-tour-sidebar-layout',
						'type'      => 'image_select',
						'title'     => esc_html__( 'Tour Sidebar Layout', 'exploore' ),
						'subtitle'  => esc_html__( 'Set how to display sidebar for tour single pages.', 'exploore' ),
						'options'   => array(
							'left'  => array(
								'alt' => esc_html__( 'left', 'exploore' ),
								'img' => esc_url($image_opt_path) . 'left.png'
							),
							'right' => array(
								'alt' => esc_html__( 'right', 'exploore' ),
								'img' => esc_url($image_opt_path) . 'right.png'
							),
							'none'  => array(
								'alt' => esc_html__( 'none', 'exploore' ),
								'img' => esc_url($image_opt_path) . 'nosidebar.png'
							)
						),
						'default'   => 'none'
					),
					array(
						'id'        => 'slz-tour-sidebar',
						'type'      => 'select',
						'data'      => 'sidebars',
						'title'     => esc_html__( 'Tour Sidebar', 'exploore' ),
						'subtitle'  => sprintf( esc_html__( 'You can create new sidebar in Appearance -> %s', 'exploore' ), $admin_widget_url ),
						'default'   => ''
					),

					array(
						'id'        => 'slz-sidebar-section',
						'type'      => 'section',
						'title'     => esc_html__( 'Tour Archive Sidebar', 'exploore' ),
						'indent'    => true,
					),
					array(
						'id'        => 'slz-tour-archive-sidebar-layout',
						'type'      => 'image_select',
						'title'     => esc_html__( 'Tour Archive Sidebar Layout', 'exploore' ),
						'subtitle'  => esc_html__( 'Set how to display sidebar for tour archive pages.', 'exploore' ),
						'options'   => array(
							'left'  => array(
								'alt' => esc_html__( 'left', 'exploore' ),
								'img' => esc_url($image_opt_path) . 'left.png'
							),
							'right' => array(
								'alt' => esc_html__( 'right', 'exploore' ),
								'img' => esc_url($image_opt_path) . 'right.png'
							),
							'none'  => array(
								'alt' => esc_html__( 'none', 'exploore' ),
								'img' => esc_url($image_opt_path) . 'nosidebar.png'
							)
						),
						'default'   => 'left'
					),
					array(
						'id'       => 'slz-tour-archive-sidebar-content',
						'type'     => 'switch',
						'title'    => esc_html__( 'Tour Archive Sidebar Content', 'exploore' ),
						'subtitle' => esc_html__( 'Default display tour search bar. Choose sidebar to setting your sidebar. If "Tour Archive Sidebar Layout" is "No Sidebar", nothing display. ', 'exploore' ),
						'on'       => esc_html__( 'Sidebar', 'exploore' ),
						'off'      => esc_html__( 'Search Bar', 'exploore' ),
						'default'  => false,
					),
					array(
						'id'        => 'slz-tour-archive-sidebar',
						'type'      => 'select',
						'data'      => 'sidebars',
						'title'     => esc_html__( 'Tour Archive Sidebar', 'exploore' ),
						'subtitle'  => sprintf( esc_html__( 'You can create new sidebar in Appearance -> %s', 'exploore' ), $admin_widget_url ),
						'required'  => array( 'slz-tour-archive-sidebar-content', '=', true ),
						'default'   => ''
					),

					array(
						'id'        => 'slz-sidebar-section',
						'type'      => 'section',
						'title'     => esc_html__( 'Accommodation Sidebar', 'exploore' ),
						'indent'    => true,
					),
					array(
						'id'        => 'slz-hotel-sidebar-layout',
						'type'      => 'image_select',
						'title'     => esc_html__( 'Accommodation Sidebar Layout', 'exploore' ),
						'subtitle'  => esc_html__( 'Set how to display sidebar for accommodation single pages.', 'exploore' ),
						'options'   => array(
							'left'  => array(
								'alt' => esc_html__( 'left', 'exploore' ),
								'img' => esc_url($image_opt_path) . 'left.png'
							),
							'right' => array(
								'alt' => esc_html__( 'right', 'exploore' ),
								'img' => esc_url($image_opt_path) . 'right.png'
							),
							'none'  => array(
								'alt' => esc_html__( 'none', 'exploore' ),
								'img' => esc_url($image_opt_path) . 'nosidebar.png'
							)
						),
						'default'   => 'none'
					),
					array(
						'id'        => 'slz-hotel-sidebar',
						'type'      => 'select',
						'data'      => 'sidebars',
						'title'     => esc_html__( 'Accommodation Sidebar', 'exploore' ),
						'subtitle'  => sprintf( esc_html__( 'You can create new sidebar in Appearance -> %s', 'exploore' ), $admin_widget_url ),
						'default'   => ''
					),

					array(
						'id'        => 'slz-sidebar-section',
						'type'      => 'section',
						'title'     => esc_html__( 'Accommodation Archive Sidebar', 'exploore' ),
						'indent'    => true,
					),
					array(
						'id'        => 'slz-hotel-archive-sidebar-layout',
						'type'      => 'image_select',
						'title'     => esc_html__( 'Accommodation Archive Sidebar Layout', 'exploore' ),
						'subtitle'  => esc_html__( 'Set how to display sidebar for accommodation archive pages.', 'exploore' ),
						'options'   => array(
							'left'  => array(
								'alt' => esc_html__( 'left', 'exploore' ),
								'img' => esc_url($image_opt_path) . 'left.png'
							),
							'right' => array(
								'alt' => esc_html__( 'right', 'exploore' ),
								'img' => esc_url($image_opt_path) . 'right.png'
							),
							'none'  => array(
								'alt' => esc_html__( 'none', 'exploore' ),
								'img' => esc_url($image_opt_path) . 'nosidebar.png'
							)
						),
						'default'   => 'left'
					),
					array(
						'id'       => 'slz-hotel-archive-sidebar-content',
						'type'     => 'switch',
						'title'    => esc_html__( 'Accommodation Archive Sidebar Content', 'exploore' ),
						'subtitle' => esc_html__( 'Default display accommodation search bar. Choose sidebar to setting your sidebar. If "Accommodation Archive Sidebar Layout" is "No Sidebar", nothing display. ', 'exploore' ),
						'on'       => esc_html__( 'Sidebar', 'exploore' ),
						'off'      => esc_html__( 'Search Bar', 'exploore' ),
						'default'  => false,
					),
					array(
						'id'        => 'slz-hotel-archive-sidebar',
						'type'      => 'select',
						'data'      => 'sidebars',
						'title'     => esc_html__( 'Accommodation Archive Sidebar', 'exploore' ),
						'subtitle'  => sprintf( esc_html__( 'You can create new sidebar in Appearance -> %s', 'exploore' ), $admin_widget_url ),
						'required'  => array( 'slz-hotel-archive-sidebar-content', '=', true ),
						'default'   => ''
					),

					//car rent
					array(
						'id'        => 'slz-sidebar-section',
						'type'      => 'section',
						'title'     => esc_html__( 'Car Rent Sidebar', 'exploore' ),
						'indent'    => true,
					),
					array(
						'id'        => 'slz-car-sidebar-layout',
						'type'      => 'image_select',
						'title'     => esc_html__( 'Car Rent Sidebar Layout', 'exploore' ),
						'subtitle'  => esc_html__( 'Set how to display sidebar for car rent single pages.', 'exploore' ),
						'options'   => array(
							'left'  => array(
								'alt' => 'left',
								'img' => esc_url($image_opt_path) . 'left.png'
							),
							'right' => array(
								'alt' => 'right',
								'img' => esc_url($image_opt_path) . 'right.png'
							),
							'none'  => array(
								'alt' => 'none',
								'img' => esc_url($image_opt_path) . 'nosidebar.png'
							)
						),
						'default'   => 'none'
					),
					array(
						'id'       	=> 'slz-car-sidebar',
						'type'     	=> 'select',
						'data'     	=> 'sidebars',
						'title'    	=> esc_html__( 'Car Rent Sidebar', 'exploore' ),
						'subtitle'	=> sprintf( esc_html__( 'You can create new sidebar in Appearance -> %s', 'exploore' ), $admin_widget_url ),
						'default'  	=> ''
					),

					array(
						'id'        => 'slz-sidebar-section',
						'type'      => 'section',
						'title'     => esc_html__( 'Car Rent Archive Sidebar', 'exploore' ),
						'indent'    => true,
					),
					array(
						'id'        => 'slz-car-archive-sidebar-layout',
						'type'      => 'image_select',
						'title'     => esc_html__( 'Car Rent Archive Sidebar Layout', 'exploore' ),
						'subtitle'  => esc_html__( 'Set how to display sidebar for car rent archive pages.', 'exploore' ),
						'options'   => array(
							'left'  => array(
								'alt' => 'left',
								'img' => esc_url($image_opt_path) . 'left.png'
							),
							'right' => array(
								'alt' => 'right',
								'img' => esc_url($image_opt_path) . 'right.png'
							),
							'none'  => array(
								'alt' => 'none',
								'img' => esc_url($image_opt_path) . 'nosidebar.png'
							)
						),
						'default'   => 'left'
					),
					array(
						'id'       => 'slz-car-archive-sidebar-content',
						'type'     => 'switch',
						'title'    => esc_html__( 'Car Rent Archive Sidebar Content', 'exploore' ),
						'subtitle' => esc_html__( 'Default display car rent search bar. Choose sidebar to setting your sidebar. If "Car Rent Archive Sidebar Layout" is "No Sidebar", nothing display. ', 'exploore' ),
						'on'       => esc_html__( 'Sidebar', 'exploore' ),
						'off'      => esc_html__( 'Search Bar', 'exploore' ),
						'default'  => false,
					),
					array(
						'id'       	=> 'slz-car-archive-sidebar',
						'type'     	=> 'select',
						'data'     	=> 'sidebars',
						'title'    	=> esc_html__( 'Car Rent Archive Sidebar', 'exploore' ),
						'subtitle'	=> sprintf( esc_html__( 'You can create new sidebar in Appearance -> %s', 'exploore' ), $admin_widget_url ),
						'required'  => array( 'slz-car-archive-sidebar-content', '=', true ),
						'default'  	=> ''
					),

					//Cruises
					array(
						'id'        => 'slz-sidebar-section',
						'type'      => 'section',
						'title'     => esc_html__( 'Cruises Sidebar', 'exploore' ),
						'indent'    => true,
					),
					array(
						'id'        => 'slz-cruises-sidebar-layout',
						'type'      => 'image_select',
						'title'     => esc_html__( 'Cruises Sidebar Layout', 'exploore' ),
						'subtitle'  => esc_html__( 'Set how to display sidebar for cruise single pages.', 'exploore' ),
						'options'   => array(
							'left'  => array(
								'alt' => 'left',
								'img' => esc_url($image_opt_path) . 'left.png'
							),
							'right' => array(
								'alt' => 'right',
								'img' => esc_url($image_opt_path) . 'right.png'
							),
							'none'  => array(
								'alt' => 'none',
								'img' => esc_url($image_opt_path) . 'nosidebar.png'
							)
						),
						'default'   => 'none'
					),
					array(
						'id'       	=> 'slz-cruises-sidebar',
						'type'     	=> 'select',
						'data'     	=> 'sidebars',
						'title'    	=> esc_html__( 'Cruises Sidebar', 'exploore' ),
						'subtitle'	=> sprintf( esc_html__( 'You can create new sidebar in Appearance -> %s', 'exploore' ), $admin_widget_url ),
						'default'  	=> ''
					),

					array(
						'id'        => 'slz-sidebar-section',
						'type'      => 'section',
						'title'     => esc_html__( 'Cruises Archive Sidebar', 'exploore' ),
						'indent'    => true,
					),
					array(
						'id'        => 'slz-cruises-archive-sidebar-layout',
						'type'      => 'image_select',
						'title'     => esc_html__( 'Cruises Archive Sidebar Layout', 'exploore' ),
						'subtitle'  => esc_html__( 'Set how to display sidebar for cruise archive pages.', 'exploore' ),
						'options'   => array(
							'left'  => array(
								'alt' => 'left',
								'img' => esc_url($image_opt_path) . 'left.png'
							),
							'right' => array(
								'alt' => 'right',
								'img' => esc_url($image_opt_path) . 'right.png'
							),
							'none'  => array(
								'alt' => 'none',
								'img' => esc_url($image_opt_path) . 'nosidebar.png'
							)
						),
						'default'   => 'left'
					),
					array(
						'id'       => 'slz-cruises-archive-sidebar-content',
						'type'     => 'switch',
						'title'    => esc_html__( 'Cruises Archive Sidebar Content', 'exploore' ),
						'subtitle' => esc_html__( 'Default display cruise search bar. Choose sidebar to setting your sidebar. If "Cruises Archive Sidebar Layout" is "No Sidebar", nothing display. ', 'exploore' ),
						'on'       => esc_html__( 'Sidebar', 'exploore' ),
						'off'      => esc_html__( 'Search Bar', 'exploore' ),
						'default'  => false,
					),
					array(
						'id'       	=> 'slz-cruises-archive-sidebar',
						'type'     	=> 'select',
						'data'     	=> 'sidebars',
						'title'    	=> esc_html__( 'Cruises Archive Sidebar', 'exploore' ),
						'subtitle'	=> sprintf( esc_html__( 'You can create new sidebar in Appearance -> %s', 'exploore' ), $admin_widget_url ),
						'required'  => array( 'slz-cruises-archive-sidebar-content', '=', true ),
						'default'  	=> ''
					),

					//shop
					array(
						'id'        => 'slz-sidebar-section',
						'type'      => 'section',
						'title'     => esc_html__( 'Shop Sidebar', 'exploore' ),
						'indent'    => true,
					),
					array(
						'id'        => 'slz-shop-sidebar-layout',
						'type'      => 'image_select',
						'title'     => esc_html__( 'Shop Sidebar Layout', 'exploore' ),
						'subtitle'  => esc_html__( 'Set how to display shop sidebar', 'exploore' ),
						'options'   => array(
							'left'  => array(
								'alt' => 'left',
								'img' => esc_url($image_opt_path) . 'left.png'
							),
							'right' => array(
								'alt' => 'right',
								'img' => esc_url($image_opt_path) . 'right.png'
							),
							'none'  => array(
								'alt' => 'none',
								'img' => esc_url($image_opt_path) . 'nosidebar.png'
							)
						),
						'default'   => 'none'
					),
					array(
						'id'       	=> 'slz-shop-sidebar',
						'type'     	=> 'select',
						'data'     	=> 'sidebars',
						'title'    	=> esc_html__( 'Shop Sidebar', 'exploore' ),
						'subtitle'	=> sprintf( esc_html__( 'You can create new sidebar in Appearance -> %s', 'exploore' ), $admin_widget_url ),
						'default'  	=> ''
					),
					array(
						'id'        => 'slz-sidebar-section',
						'type'      => 'section',
						'indent'    => false,
					),
					array(
						'id'             => 'slz-sidebar-mb',
						'type'           => 'spacing',
						'mode'           => 'margin',
						'all'            => false,
						'top'            => false,
						'left'           => false,
						'right'          => false,
						'units'          => 'px',      // You can specify a unit value. Possible: px, em, %
						'units_extended' => 'false',   // Allow users to select any type of unit
						'title'          => esc_html__( 'Block Margin Bottom', 'exploore' ),
						'subtitle'       => esc_html__( 'Choose margin bottom between 2 block content on sidebar', 'exploore' ),
						'desc'           => esc_html__( 'unit is "px"', 'exploore' ),
						'default'        => array(
							'margin-top'     => '',
							'margin-right'   => '',
							'margin-bottom'  => '50px',
							'margin-left'    => '',
							'units'          => 'px',
						)
					),
					array(
						'id'             => 'slz-sidebar-pb',
						'type'           => 'spacing',
						'mode'           => 'padding',
						'all'            => false,
						'top'            => false,
						'left'           => false,
						'right'          => false,
						'units'          => 'px',      // You can specify a unit value. Possible: px, em, %
						'units_extended' => 'false',   // Allow users to select any type of unit
						'title'          => esc_html__( 'Block Padding Bottom', 'exploore' ),
						'subtitle'       => esc_html__( 'Choose padding bottom for one block content on sidebar', 'exploore' ),
						'desc'           => esc_html__( 'unit is "px"', 'exploore' ),
						'default'        => array(
							'padding-top'     => '',
							'padding-right'   => '',
							'padding-bottom'  => '',
							'padding-left'    => '',
							'units'           => 'px',
						)
					),
				)
			);

			// News Letter
			$this->sections[] = array(
				'title'     => esc_html__( 'Pre Footer', 'exploore' ),
				'icon'      => 'el-icon-caret-down',
				'desc'      => esc_html__( 'Configuration for subscribe section', 'exploore' ),
				'fields'    => array(
					array(
						'id'        => 'slz-subcribe',
						'type'      => 'switch',
						'title'     => esc_html__( 'Subcribe', 'exploore' ),
						'on'       	=> esc_html__( 'Show', 'exploore'),
						'off'      	=> esc_html__( 'Hide', 'exploore'),
						'default'   => true
					),
					array(
					    'id'               => 'slz-subcribe-text-1',
					    'type'             => 'editor',
					    'title'            => esc_html__( 'The Title', 'exploore' ), 
					    'default'          => 'Subscribe <span class="logo-text">Exploore</span>&nbsp;to get latest offers and deals to day',
					),
					array(
					    'id'               => 'slz-subcribe-text-2',
					    'type'             => 'editor',
					    'title'            => esc_html__( 'The Subtitle', 'exploore' ), 
					    'default'          => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore.',
					),
					array(
						'id'        	   => 'slz-subcribe-form-text',
						'type'      	   => 'text',
						'title'     	   => esc_html__( 'Text Information', 'exploore' ),
						'default'   	   => 'Write your email',
					),
				)
			);

			// Footer setting
			$this->sections[] = array(
				'title'     => esc_html__( 'Footer', 'exploore' ),
				'icon'      => 'el-icon-caret-down',
				'desc'      => esc_html__( 'Configuration for footer of site', 'exploore' ),
				'fields'    => array(
					array(
						'id'        => 'slz-footer',
						'type'      => 'switch',
						'title'     => esc_html__( 'Footer Section', 'exploore' ),
						'on'        => esc_html__( 'Show', 'exploore'),
						'off'       => esc_html__( 'Hide', 'exploore'),
						'default'   => true
					),
					array(
						'id'        => 'slz-footerbt-logo-show',
						'type'      => 'switch',
						'title'     => esc_html__( 'Show Footer Logo', 'exploore' ),
						'on'       	=> esc_html__( 'Show', 'exploore'),
						'off'      	=> esc_html__( 'Hide', 'exploore'),
						'default'   => true,
					),
					array(
						'id'        => 'slz-footer-col',
						'type'      => 'radio',
						'title'     => esc_html__( 'Columns', 'exploore' ),
						'subtitle'  => sprintf( wp_kses( __( 'Choose grid layout for footer.<br> Please go on "Appearance->%1$s" to set data for footer', 'exploore' ), array('br' => array()) ), $admin_widget_url),
						'options'   => array(
							'11' => esc_html__( '1 Column & text center', 'exploore' ),
							'1'  => esc_html__( '1 Column', 'exploore' ),
							'2'  => esc_html__( '2 Columns', 'exploore' ),
							'3'  => esc_html__( '3 Columns', 'exploore' ),
							'4'  => esc_html__( '4 Columns', 'exploore' ),
							'5'  => esc_html__( '5 Columns', 'exploore' )
						),
						'default'   => '5'
					),
					array(
						'id'     => 'opt-notice-info',
						'type'   => 'info',
						'notice' => false,
						'style'  => 'info',
						'title'  => esc_html__( 'Footer content when set column', 'exploore' ),
						'desc'   => wp_kses( __(' - 2 columns: Widgets Footer 1&2 will show.<br> - 3 columns: Widgets Footer 1, 2 & 3 will show.<br> - 4 columns: Widgets Footer 1, 2, 3 & 4 will show.<br> - 5 columns: All Widgets will show.', 'exploore'), array('br' => array()))
					),
					array(
						'id'      => 'slz-footer-style',
						'type'    => 'button_set',
						'title'   => esc_html__('Footer Style', 'exploore'),
						//Must provide key => value pairs for options
						'options' => array(
							'dark'  => esc_html__( 'Dark', 'exploore' ), 
							'light' => esc_html__( 'Light', 'exploore' ),
						 ), 
						'default' => 'dark'
					),
					array(
						'id'        => 'slz-footer-bg',
						'type'      => 'background',
						'title'     => esc_html__( 'Footer Background Image', 'exploore' ),
						'subtitle'  => esc_html__( 'Set background image for footer section', 'exploore' ),
						'required'  => array('slz-footer-style','=','dark'),
						'default'   => array(
							'background-color'      => '#292F32',
							'background-image'      => '',
							'background-repeat'     => 'no-repeat',
							'background-attachment' => '',
							'background-position'	=> '',
							'background-size'		=> ''
						)
					),
					array(
						'id'        => 'slz-footer-mask-bg',
						'type'      => 'color_rgba',
						'title'     => esc_html__( 'Footer Overlay Background', 'exploore' ),
						'subtitle'  => esc_html__( 'Set background color for mask layer above footer', 'exploore' ),
						'required'  => array('slz-footer-style','=','dark'),
						'default'   => array(
							'color'     => 'transparent',
							'alpha'     => 0,
							'rgba'      => 'rgba(0, 0, 0, 0)'
						)
					),
					array(
						'id'        => 'slz-footer-light-bg',
						'type'      => 'background',
						'title'     => esc_html__( 'Footer Background Image', 'exploore' ),
						'subtitle'  => esc_html__( 'Set background image for footer section', 'exploore' ),
						'required'  => array('slz-footer-style','=','light'),
						'default'   => array(
							'background-color'      => '#ffffff',
							'background-image'      => '',
							'background-repeat'     => 'no-repeat',
							'background-attachment' => '',
							'background-position'	=> 'center center',
							'background-size'		=> 'cover'
						)
					),
					array(
						'id'        => 'slz-footer-light-mask-bg',
						'type'      => 'color_rgba',
						'title'     => esc_html__( 'Footer Overlay Background', 'exploore' ),
						'subtitle'  => esc_html__( 'Set background color for mask layer above footer', 'exploore' ),
						'required'  => array('slz-footer-style','=','light'),
						'default'   => array(
							'color'     => 'transparent',
							'alpha'     => 0,
							'rgba'      => 'rgba(0, 0, 0, 0)'
						)
					),
					array(
						'id'             => 'slz-footer-light-padding',
						'type'           => 'spacing',
						'mode'           => 'padding',
						'all'            => false,
						'left'           => false,
						'right'          => false,
						'units'          => 'px',
						'units_extended' => 'false',
						'title'          => esc_html__( 'Footer Padding', 'exploore' ),
						'subtitle'       => esc_html__( 'Choose inwards spacing for footer section', 'exploore' ),
						'desc'           => esc_html__( 'unit is "px"', 'exploore' ),
						'required'  => array('slz-footer-style','=','light'),
						'default'        => array(
							'padding-top'    => '100px',
							'padding-bottom' => '40px'
						)
					),
					array(
						'id'        => 'slz-footerbt-section',
						'type'      => 'section',
						'title'     => esc_html__( 'Footer Bottom', 'exploore' ),
						'indent'    => true,
					),
					array(
						'id'        => 'slz-footerbt-show',
						'type'      => 'switch',
						'title'     => esc_html__( 'Show Footer Bottom', 'exploore' ),
						'on'       	=> esc_html__( 'Show', 'exploore'),
						'off'      	=> esc_html__( 'Hide', 'exploore'),
						'default'   => true,
					),
					array(
						'id'        => 'slz-footerbt-partner',
						'type'      => 'switch',
						'title'     => esc_html__( 'Show Partner Slider', 'exploore' ),
						'on'       	=> esc_html__( 'Show', 'exploore'),
						'off'      	=> esc_html__( 'Hide', 'exploore'),
						'required'  => array('slz-footerbt-show','=',true),
						'default'   => true,
					),
					array(
						'id'          => 'slz-footerbt-partner-cat',
						'type'        => 'select',
						'multi'       => true,
						'placeholder' => esc_html__( 'Select categories', 'exploore'),
						'title'       => esc_html__( 'Partner Filter', 'exploore' ),
						'subtitle'    => esc_html__( 'Choose categories of partner to display partners in slider. Leave it blank if you want to display all partners.', 'exploore' ),
						'data'        => 'categories',
						'args'        => array('taxonomy' => array('slzexploore_partner_cat')),
					),
					array(
						'id'        => 'slz-footer-social-section',
						'type'      => 'section',
						'class'     => 'slz-sub-session',
						'title'     => esc_html__( 'Social', 'exploore' ),
						'subtitle'  => esc_html__( 'Settings social for footer', 'exploore' ),
						'indent'    => true,
					),
						array(
							'id'        => 'slz-footerbt-social-content',
							'type'      => 'switch',
							'title'     => esc_html__( 'Footer Social', 'exploore' ),
							'subtitle'  => esc_html__( 'Social information will get in "Social" tab', 'exploore' ),
							'on'       	=> esc_html__( 'Show', 'exploore'),
							'off'      	=> esc_html__( 'Hide', 'exploore'),
							'default'   => true,
							'required'  => array('slz-footerbt-show','=',true),
						),
						array(
							'id'       => 'slz-footer-social',
							'type'     => 'sorter',
							'title'    => esc_html__( 'Social List', 'exploore'),
							'options'  => array(
								'disabled' => array(
									
								),
								'enabled'  => array(
									'facebook'    	=> esc_html__( 'Facebook', 'exploore'),
									'google-plus' 	=> esc_html__( 'Google Plus', 'exploore'),
									'twitter'     	=> esc_html__( 'Twitter', 'exploore'),
									'instagram'	 	=> esc_html__( 'Instagram', 'exploore'),
									'dribbble' 	 	=> esc_html__( 'Dribbble', 'exploore'),
									'pinterest'	 	=> esc_html__( 'Pinterest', 'exploore'),
								),
							),
							'required'  => array('slz-footerbt-social-content','=',true),
						),
						array(
							'id'        => 'slz-footer-more-social',
							'type'      => 'multi_text',
							'title'     => esc_html__( 'Add More Social', 'exploore' ),
							'required'  => array('slz-footerbt-social-content','=',true),
							'subtitle'  => sprintf( wp_kses( __( 'Please use format: icon / Social Link.<br>Ex: ["fa fa-facebook","http://facebook.com"]', 'exploore' ), array('br' => array()) )),
						),

					array(
						'id'     => 'section-end',
						'type'   => 'section',
						'indent' => false,
					),
					array(
						'id'        => 'slz-footerbt-text',
						'type'      => 'text',
						'title'     => esc_html__( 'Text Information', 'exploore' ),
						'default'   => esc_html( SLZEXPLOORE_COPYRIGHT ),
						'required'  => array('slz-footerbt-show','=',true),
					),
					array(
						'id'     => 'slz-subtitle-end',
						'type'   => 'section',
						'indent' => false,
					)
				)
			);
			
			// Blog Setting
			$this->sections[] = array(
				'title'     => esc_html__( 'Blog Display', 'exploore' ),
				'icon'      => 'el-icon-edit',
				'desc'      => esc_html__( 'Configuration layout for blog template', 'exploore' ),
				'fields'    => array(
					array(
						'id'       => 'slz-blog-show-title',
						'type'     => 'button_set',
						'title'    => esc_html__( 'Show Blog Title', 'exploore' ),
						'subtitle' => esc_html__( 'Choose show or hide title on page title (only apply for blog detail page)', 'exploore' ),
						'options'  => array(
					        'category'   => esc_html__( 'Show Category', 'exploore' ),
					        'title'      => esc_html__( 'Show Title', 'exploore' ),
					        'hide'       => esc_html__( 'Hide', 'exploore' ),
					     ),
						'default'  => 'category',
					),

					array(
						'id'        => 'slz-bloginfo',
						'type'      => 'sorter',
						'title'     =>  esc_html__( 'Blog Info', 'exploore' ),
						'subtitle'  =>  esc_html__( 'Choose what information to show below blog content', 'exploore' ),
						'options'   => array(
							'disabled' => array(

							),
							'enabled'  => array(
								'author'    => esc_html__( 'Author', 'exploore' ),
								'view'      => esc_html__( 'View', 'exploore' ),
								'comment'   => esc_html__( 'Comment', 'exploore' ),
								'date'      => esc_html__( 'Date', 'exploore' ),
							),
						),
					),array(
						'id'        => 'slz-blog-social-share',
						'type'      => 'sorter',
						'title'     => esc_html__( 'Social Share Link', 'exploore' ),
						'subtitle'  => esc_html__( 'These information will be used to posts.', 'exploore' ),
						'options'   => array(
							'disabled' => array(
								'pinterest'     => esc_html__( 'Pinterest', 'exploore' ),
								'linkedin'  	=> esc_html__( 'LinkedIn', 'exploore' ),
								'digg'	 		=> esc_html__( 'Digg', 'exploore' ),
							),
							'enabled'  => array(
								'facebook'  	=> esc_html__( 'Facebook', 'exploore' ),
								'twitter'	 	=> esc_html__( 'Twitter', 'exploore' ),
								'google-plus'   => esc_html__( 'Google Plus', 'exploore' ),
							),
						),
					),
					array(
						'id'        => 'slz-blog-tag',
						'type'      => 'switch',
						'title'     => esc_html__( 'Tag Section', 'exploore' ),
						'on'        => esc_html__( 'Show', 'exploore'),
						'off'       => esc_html__( 'Hide', 'exploore'),
						'default'   => true
					),
					array(
						'id'        => 'slz-blog-cat',
						'type'      => 'switch',
						'title'     => esc_html__( 'Categories Section', 'exploore' ),
						'on'        => esc_html__( 'Show', 'exploore'),
						'off'       => esc_html__( 'Hide', 'exploore'),
						'default'   => true
					),
					array(
						'id'        => 'slz-commentbox',
						'type'      => 'switch',
						'title'     => esc_html__( 'Comments Section', 'exploore' ),
						'on'        => esc_html__( 'Show', 'exploore'),
						'off'       => esc_html__( 'Hide', 'exploore'),
						'default'   => true
					),
					array(
						'id'        => 'slz-authorbox',
						'type'      => 'switch',
						'title'     => esc_html__( 'Author Section', 'exploore' ),
						'on'        => esc_html__( 'Show', 'exploore'),
						'off'       => esc_html__( 'Hide', 'exploore'),
						'default'   => true
					)
				)
			);
			// Tour Setting
			$this->sections[] = array(
				'title'     => esc_html__( 'Tour Setting', 'exploore' ),
				'icon'      => 'el-icon-gift',
				'desc'      => esc_html__( 'Setting for tour page.', 'exploore' ),
				'fields'    => array(
					array(
						'id'        => 'slz-tour-disable-status',
						'type'      => 'select',
						'multi'     => true,
						'title'     => esc_html__( 'Tour Status Disable', 'exploore' ),
						'subtitle'  => esc_html__( 'Choose disable status. Tours in this status is not display on frontend.', 'exploore' ),
						'data'      => 'categories',
						'args'      => array( 'taxonomy' => array('slzexploore_tour_status') )
					),
					array(
						'id'       => 'slz-tour-hide-passed',
						'type'     => 'switch',
						'title'    => esc_html__( 'Hide Passed Tours', 'exploore' ),
						'subtitle' => esc_html__( 'Automatically hide tours which have already passed', 'exploore' ),
						'on'       => esc_html__( 'Yes', 'exploore' ),
						'off'      => esc_html__( 'No', 'exploore' ),
						'default'  => false
					),
					array(
						'id'       => 'slz-tour-page-title-bg-type',
						'type'     => 'switch',
						'title'    => esc_html__( 'Page Title Background', 'exploore' ),
						'subtitle' => esc_html__( 'Setting page title background for single page. Select featured image or page title background image.', 'exploore' ),
						'on'       => esc_html__( 'Featured Image', 'exploore' ),
						'off'      => esc_html__( 'Page Title Background', 'exploore' ),
						'default'  => true,
					),
					array(
						'id'        => 'slz-tour-title-section',
						'type'      => 'section',
						'title'     => esc_html__( 'Title Setting', 'exploore' ),
						'subtitle'  => esc_html__( 'Configuration for title. This content will display below breadcrumb.', 'exploore' ),
						'indent'    => true,
					),
					array(
						'id'       => 'slz-tour-show-title',
						'type'     => 'switch',
						'title'    => esc_html__( 'Show Tour Title', 'exploore' ),
						'subtitle' => esc_html__( 'Choose to show or hide title - only apply for tour page (archive,detail)', 'exploore' ),
						'on'       => esc_html__( 'Show', 'exploore' ),
						'off'      => esc_html__( 'Hide', 'exploore' ),
						'default'  => false,
					),
					array(
						'id'        => 'slz-tour-custom-title',
						'type'      => 'text',
						'title'     => esc_html__( 'Custom Tour Title', 'exploore' ),
						'subtitle'  => esc_html__( 'Custom title for tour archive, tour category page', 'exploore' ),
						'required'  => array('slz-tour-show-title','=',true),
						'default'   => ''
					),
					array(
						'id'        => 'slz-tour-detail-custom-title',
						'type'      => 'text',
						'title'     => esc_html__( 'Custom Tour Detail Title', 'exploore' ),
						'subtitle'  => esc_html__( 'Custom title for tour detail page', 'exploore' ),
						'required'  => array('slz-tour-show-title','=',true),
						'default'   => ''
					),
					array(
						'id'       => 'slz-tour-show-price',
						'type'     => 'switch',
						'title'    => esc_html__( 'Show Price', 'exploore' ),
						'subtitle' => esc_html__( 'Choose to show or hide price on page title of tour detail page', 'exploore' ),
						'on'       => esc_html__( 'Show', 'exploore' ),
						'off'      => esc_html__( 'Hide', 'exploore' ),
						'required' => array('slz-tour-show-title','=',true),
						'default'  => true,
					),
					array(
						'id'     => 'section-end',
						'type'   => 'section',
						'indent' => false,
					),
					array(
						'id'       => 'slz-tour-show-related',
						'type'     => 'switch',
						'title'    => esc_html__( 'Show Related Tours', 'exploore' ),
						'subtitle' => esc_html__( 'Show or hide related tours in tour detail page.', 'exploore' ),
						'on'       => esc_html__( 'Show', 'exploore' ),
						'off'      => esc_html__( 'Hide', 'exploore' ),
						'default'  => true,
					),
					array(
						'id'        => 'slz-tour-filter-section',
						'type'      => 'section',
						'title'     => esc_html__( 'Setting Tour Search', 'exploore' ),
						'subtitle'  => esc_html__( 'Set the filter conditions in tour result page', 'exploore' ),
						'indent'    => true,
					),
					array(
						'id'        => 'slz-tour-posts',
						'type'      => 'text',
						'title'     => esc_html__( 'Tours Per Page', 'exploore' ),
						'subtitle'  => esc_html__( 'Select a number of tours to show on search tour result page', 'exploore' ),
						'default'   => ''
					),
					array(
						'id'        => 'slz-tour-page-title-show',
						'type'      => 'switch',
						'title'     => esc_html__( 'Show Page Title', 'exploore' ),
						'subtitle'  => esc_html__( 'Choose to show or hide page title', 'exploore' ),
						'on'        => esc_html__( 'Show', 'exploore'),
						'off'       => esc_html__( 'Hide', 'exploore'),
						'default'   => true,
					),
					array(
						'id'        => 'slz-tour-page-title-bg',
						'type'      => 'background',
						'title'     => esc_html__( 'Page Title Background Image', 'exploore' ),
						'subtitle'  => esc_html__( 'Body background image for page title section', 'exploore' ),
						'default'   => array(
							'background-color'      => '#f3f3f3',
							'background-repeat'     => 'no-repeat',
							'background-size'       => 'cover',
							'background-attachment' => 'fixed',
							'background-position'   => 'center center',
							'background-image'      => ''
						)
					),
					array(
						'id'        => 'slz-tour-top-cat',
						'type'      => 'switch',
						'title'     => esc_html__( 'Categories Section On Top Page', 'exploore' ),
						'on'        => esc_html__( 'Show', 'exploore'),
						'off'       => esc_html__( 'Hide', 'exploore'),
						'default'   => true
					),
					array(
						'id'        => 'slz-tour-filter',
						'type'      => 'sorter',
						'title'     => esc_html__( 'Tour Filter By', 'exploore' ),
						'options'   => array(
							'disabled' => array(
								'category'          => esc_html__( 'Categories', 'exploore' ),
							),
							'enabled'  => array(
								'find-tour-box'     => esc_html__( 'Find Tour Box', 'exploore' ),
								'price'             => esc_html__( 'Price', 'exploore' ),
								'rating'            => esc_html__( 'Review Rating', 'exploore' ),
								'cities'            => esc_html__( 'Locations', 'exploore' ),
							),
						),
					),
					array(
						'id'        => 'slz-tour-price-max',
						'type'      => 'text',
						'title'     => esc_html__( 'Price Filter Max Value', 'exploore' ),
						'default'   => '3000'
					),
					array(
						'id'        => 'slz-tour-result-count',
						'type'      => 'switch',
						'title'     => esc_html__( 'Show Result Found', 'exploore' ),
						'subtitle'  => esc_html__( 'Choose to show or hide Result Found box in tour list', 'exploore' ),
						'on'        => esc_html__( 'Show', 'exploore'),
						'off'       => esc_html__( 'Hide', 'exploore'),
						'default'   => true
					),
					array(
						'id'        => 'slz-tour-list-sort',
						'type'      => 'switch',
						'title'     => esc_html__( 'Show Sort By', 'exploore' ),
						'subtitle'  => esc_html__( 'Choose to show or hide Sort By box in tour list', 'exploore' ),
						'on'        => esc_html__( 'Show', 'exploore'),
						'off'       => esc_html__( 'Hide', 'exploore'),
						'default'   => true
					),
					array(
						'id'        => 'slz-tour-list-setting-section',
						'type'      => 'section',
						'title'     => esc_html__( 'Setting Tour List', 'exploore' ),
						'subtitle'  => esc_html__( 'Setting field in tour list', 'exploore' ),
						'indent'    => true,
					),
					array(
						'id'        => 'slz-tour-list-info',
						'type'      => 'sorter',
						'title'     => esc_html__( 'Tour List Info', 'exploore' ),
						'options'   => array(
							'disabled' => array(
								'share'      => esc_html__( 'Share', 'exploore' )
							),
							'enabled'  => array(
								'comment'    => esc_html__( 'Review', 'exploore' ),
								'view'       => esc_html__( 'View', 'exploore' ),
								'list-wist'  => esc_html__( 'Wishlist', 'exploore' )
							),
						),
					),
				)
			);
			// Hotel Setting
			$this->sections[] = array(
				'title'     => esc_html__( 'Accommodations  Setting', 'exploore' ),
				'icon'      => 'el-icon-home',
				'desc'      => esc_html__( 'Setting for accommodation page.', 'exploore' ),
				'fields'    => array(
					array(
						'id'        => 'slz-hotel-disable-status',
						'type'      => 'select',
						'multi'     => true,
						'title'     => esc_html__( 'Accommodations Status Disable', 'exploore' ),
						'subtitle'  => esc_html__( 'Choose disable status. Accommodations in this status is not display on frontend.', 'exploore' ),
						'data'      => 'categories',
						'args'      => array( 'taxonomy' => array('slzexploore_hotel_status') )
					),
					array(
						'id'        => 'slz-room-disable-status',
						'type'      => 'select',
						'multi'     => true,
						'title'     => esc_html__( 'Room Status Disable', 'exploore' ),
						'subtitle'  => esc_html__( 'Choose disable status. Room in this status is not display on frontend.', 'exploore' ),
						'data'      => 'categories',
						'args'      => array( 'taxonomy' => array('slzexploore_room_status') )
					),
					array(
						'id'       => 'slz-hotel-page-title-bg-type',
						'type'     => 'switch',
						'title'    => esc_html__( 'Page Title Background', 'exploore' ),
						'subtitle' => esc_html__( 'Setting page title background for single page. Select featured image or page title background image.', 'exploore' ),
						'on'       => esc_html__( 'Featured Image', 'exploore' ),
						'off'      => esc_html__( 'Page Title Background', 'exploore' ),
						'default'  => true,
					),
					array(
						'id'        => 'slz-tour-title-section',
						'type'      => 'section',
						'title'     => esc_html__( 'Title Setting', 'exploore' ),
						'subtitle'  => esc_html__( 'Configuration for title. This content will display below breadcrumb.', 'exploore' ),
						'indent'    => true,
					),
					array(
						'id'       => 'slz-hotel-show-title',
						'type'     => 'switch',
						'title'    => esc_html__( 'Show Accommodation Title', 'exploore' ),
						'subtitle' => esc_html__( 'Choose to show or hide title - only apply for accommodation page (archive,detail)', 'exploore' ),
						'on'       => esc_html__( 'Show', 'exploore' ),
						'off'      => esc_html__( 'Hide', 'exploore' ),

						'default'  => false,
					),
					array(
						'id'        => 'slz-hotel-custom-title',
						'type'      => 'text',
						'title'     => esc_html__( 'Custom Accommodation Title', 'exploore' ),
						'subtitle'  => esc_html__( 'Custom title for accommodation archive, accommodation category page', 'exploore' ),
						'required'  => array('slz-hotel-show-title','=',true),
						'default'   => ''	
					),
					array(
						'id'        => 'slz-hotel-detail-custom-title',
						'type'      => 'text',
						'title'     => esc_html__( 'Custom Accommodation Detail Title', 'exploore' ),
						'subtitle'  => esc_html__( 'Custom title for accommodation detail page', 'exploore' ),
						'required'  => array('slz-hotel-show-title','=',true),
						'default'   => ''	
					),
					array(
						'id'       => 'slz-hotel-show-price',
						'type'     => 'switch',
						'title'    => esc_html__( 'Show Price', 'exploore' ),
						'subtitle' => esc_html__( 'Choose to show or hide price on page title of accommodation detail page', 'exploore' ),
						'on'       => esc_html__( 'Show', 'exploore' ),
						'off'      => esc_html__( 'Hide', 'exploore' ),
						'required' => array('slz-hotel-show-title','=',true),
						'default'  => true,
					),
					array(
						'id'       => 'slz-hotel-show-star',
						'type'     => 'switch',
						'title'    => esc_html__( 'Show Star', 'exploore' ),
						'subtitle' => esc_html__( 'Choose to show or hide accommodation star number on page title of  detail page.', 'exploore' ),
						'on'       => esc_html__( 'Show', 'exploore' ),
						'off'      => esc_html__( 'Hide', 'exploore' ),
						'required' => array('slz-hotel-show-title','=',true),
						'default'  => false
					),
					array(
						'id'     => 'section-end',
						'type'   => 'section',
						'indent' => false,
					),
					array(
						'id'       => 'slz-hotel-show-map',
						'type'     => 'switch',
						'title'    => esc_html__( 'Show Map & Contact', 'exploore' ),
						'subtitle' => esc_html__( 'Choose to show or hide map and contact on accommodation detail page', 'exploore' ),
						'on'       => esc_html__( 'Show', 'exploore' ),
						'off'      => esc_html__( 'Hide', 'exploore' ),
						'default'  => true,
					),
					array(
						'id'       => 'slz-hotel-show-related',
						'type'     => 'switch',
						'title'    => esc_html__( 'Show Related Accommodations', 'exploore' ),
						'subtitle' => esc_html__( 'Show or hide related accommodations in accommodation detail page.', 'exploore' ),
						'on'       => esc_html__( 'Show', 'exploore' ),
						'off'      => esc_html__( 'Hide', 'exploore' ),
						'default'  => true,
					),
					array(
						'id'        => 'slz-hotel-filter-section',
						'type'      => 'section',
						'title'     => esc_html__( 'Setting Accommodation Search', 'exploore' ),
						'subtitle'  => esc_html__( 'Set the filter conditions in accommodation result page', 'exploore' ),
						'indent'    => true,
					),
					array(
						'id'        => 'slz-hotel-posts',
						'type'      => 'text',
						'title'     => esc_html__( 'Accommodations Per Page', 'exploore' ),
						'subtitle'  => esc_html__( 'Select a number of accommodations to show on search accommodation result page', 'exploore' ),
						'default'   => ''
					),
					array(
						'id'        => 'slz-hotel-page-title-show',
						'type'      => 'switch',
						'title'     => esc_html__( 'Show Page Title', 'exploore' ),
						'subtitle'  => esc_html__( 'Choose to show or hide page title', 'exploore' ),
						'on'        => esc_html__( 'Show', 'exploore'),
						'off'       => esc_html__( 'Hide', 'exploore'),
						'default'   => true,
					),
					array(
						'id'        => 'slz-hotel-page-title-bg',
						'type'      => 'background',
						'title'     => esc_html__( 'Page Title Background Image', 'exploore' ),
						'subtitle'  => esc_html__( 'Body background image for page title section', 'exploore' ),
						'default'   => array(
							'background-color'      => '#f3f3f3',
							'background-repeat'     => 'no-repeat',
							'background-size'       => 'cover',
							'background-attachment' => 'fixed',
							'background-position'   => 'center center',
							'background-image'      => ''
						)
					),
					array(
						'id'        => 'slz-hotel-filter',
						'type'      => 'sorter',
						'title'     => esc_html__( 'Accommodation Search By', 'exploore' ),
						'options'   => array(
							'disabled' => array(
							),
							'enabled'  => array(
								'find-hotel-box'     => esc_html__( 'Find Hotel Box', 'exploore' ),
								'price'	 	         => esc_html__( 'Price', 'exploore' ),
								'rating' 	         => esc_html__( 'Star Rating', 'exploore' ),
								'review_rating'      => esc_html__( 'Review Rating', 'exploore' ),
								'location'           => esc_html__( 'Locations', 'exploore' ),
								'accommodation_type' => esc_html__( 'Category', 'exploore' ),
								'facilities'         => esc_html__( 'Facilities', 'exploore' ),
							),
						),
					),
					array(
						'id'        => 'slz-hotel-price-max',
						'type'      => 'text',
						'title'     => esc_html__( 'Price Filter Max Value', 'exploore' ),
						'subtitle'  => esc_html__( 'Setting maximum price value in search box.', 'exploore' ),
						'default'   => '3000'
					),
					array(
						'id'        => 'slz-hotel-max-rate',
						'type'      => 'text',
						'title'     => esc_html__( 'Max Star Rate', 'exploore' ),
						'subtitle'  => esc_html__( 'Setting maximum star rating value in search box.', 'exploore' ),
						'default'   => '5'
					),
					array(
						'id'        => 'slz-hotel-result-count',
						'type'      => 'switch',
						'title'     => esc_html__( 'Show Result Found', 'exploore' ),
						'subtitle'  => esc_html__( 'Choose to show or hide Result Found box in accommodation list', 'exploore' ),
						'on'        => esc_html__( 'Show', 'exploore'),
						'off'       => esc_html__( 'Hide', 'exploore'),
						'default'   => true
					),
					array(
						'id'        => 'slz-hotel-list-sort',
						'type'      => 'switch',
						'title'     => esc_html__( 'Show Sort By', 'exploore' ),
						'subtitle'  => esc_html__( 'Choose to show or hide Sort By box in accommodation list', 'exploore' ),
						'on'        => esc_html__( 'Show', 'exploore'),
						'off'       => esc_html__( 'Hide', 'exploore'),
						'default'   => true
					),
					array(
						'id'        => 'slz-hotel-list-setting-section',
						'type'      => 'section',
						'title'     => esc_html__( 'Setting Accommodation List', 'exploore' ),
						'subtitle'  => esc_html__( 'Setting field in accommodation list', 'exploore' ),
						'indent'    => true,
					),
					array(
						'id'        => 'slz-hotel-list-info',
						'type'      => 'sorter',
						'title'     => esc_html__( 'Accommodation List Info', 'exploore' ),
						'options'   => array(
							'disabled' => array(
							),
							'enabled'  => array(
								'comment' 	 => esc_html__( 'Review', 'exploore' ),
								'view'	 	 => esc_html__( 'View', 'exploore' ),
								'list-wist'  => esc_html__( 'Wishlist', 'exploore' ),
								'share'      => esc_html__( 'Share', 'exploore' ),
								'address'    => esc_html__( 'Address', 'exploore' ),
							),
						),
					),
				)
			);
			// Car Rent Setting
			$this->sections[] = array(
				'title'     => esc_html__( 'Car Rent Setting', 'exploore' ),
				'icon'      => 'el el-car',
				'desc'      => esc_html__( 'Setting for car rent page.', 'exploore' ),
				'fields'    => array(
					array(
						'id'        => 'slz-car-disable-status',
						'type'      => 'select',
						'multi'     => true,
						'title'     => esc_html__( 'Car Status Disable', 'exploore' ),
						'subtitle'  => esc_html__( 'Choose disable status. Cars in this status is not display on frontend.', 'exploore' ),
						'data'      => 'categories',
						'args'      => array( 'taxonomy' => array('slzexploore_car_status') )
					),
					array(
						'id'       => 'slz-car-page-title-bg-type',
						'type'     => 'switch',
						'title'    => esc_html__( 'Page Title Background', 'exploore' ),
						'subtitle' => esc_html__( 'Setting page title background for single page. Select featured image or page title background image.', 'exploore' ),
						'on'       => esc_html__( 'Featured Image', 'exploore' ),
						'off'      => esc_html__( 'Page Title Background', 'exploore' ),
						'default'  => true,
					),
					array(
						'id'        => 'slz-car-title-section',
						'type'      => 'section',
						'title'     => esc_html__( 'Title Setting', 'exploore' ),
						'subtitle'  => esc_html__( 'Configuration for title. This content will display below breadcrumb.', 'exploore' ),
						'indent'    => true,
					),
					array(
						'id'       => 'slz-car-show-title',
						'type'     => 'switch',
						'title'    => esc_html__( 'Show Car Rent Title', 'exploore' ),
						'subtitle' => esc_html__( 'Choose to show or hide title - only apply for car rent page (archive,detail)', 'exploore' ),
						'on'       => esc_html__( 'Show', 'exploore' ),
						'off'      => esc_html__( 'Hide', 'exploore' ),
						'default'  => false,
					),
					array(
						'id'        => 'slz-car-custom-title',
						'type'      => 'text',
						'title'     => esc_html__( 'Custom Car Rent Title', 'exploore' ),
						'subtitle'  => esc_html__( 'Custom title for car rent archive, car category page', 'exploore' ),
						'required'  => array('slz-car-show-title','=',true),
						'default'   => ''
					),
					array(
						'id'        => 'slz-car-detail-custom-title',
						'type'      => 'text',
						'title'     => esc_html__( 'Custom Car Rent Detail Title', 'exploore' ),
						'subtitle'  => esc_html__( 'Custom title for car rent detail page', 'exploore' ),
						'required'  => array('slz-car-show-title','=',true),
						'default'   => ''
					),
					array(
						'id'       => 'slz-car-show-price',
						'type'     => 'switch',
						'title'    => esc_html__( 'Show Price', 'exploore' ),
						'subtitle' => esc_html__( 'Choose to show or hide price on page title of car rent detail page', 'exploore' ),
						'on'       => esc_html__( 'Show', 'exploore' ),
						'off'      => esc_html__( 'Hide', 'exploore' ),
						'required' => array('slz-car-show-title','=',true),
						'default'  => true,
					),
					array(
						'id'     => 'section-end',
						'type'   => 'section',
						'indent' => false,
					),
					array(
						'id'       => 'slz-car-show-related',
						'type'     => 'switch',
						'title'    => esc_html__( 'Show Related Cars Rent', 'exploore' ),
						'subtitle' => esc_html__( 'Show or hide related cars in car rent detail page.', 'exploore' ),
						'on'       => esc_html__( 'Show', 'exploore' ),
						'off'      => esc_html__( 'Hide', 'exploore' ),
						'default'  => true,
					),
					array(
						'id'       => 'slz-car-show-share',
						'type'     => 'switch',
						'title'    => esc_html__( 'Show Share Button', 'exploore' ),
						'subtitle' => esc_html__( 'Show or hide share button in car list page.', 'exploore' ),
						'on'       => esc_html__( 'Show', 'exploore' ),
						'off'      => esc_html__( 'Hide', 'exploore' ),
						'default'  => false,
					),
					array(
						'id'        => 'slz-car-filter-section',
						'type'      => 'section',
						'title'     => esc_html__( 'Setting Car Rent Search', 'exploore' ),
						'subtitle'  => esc_html__( 'Set the filter conditions in car rent result page', 'exploore' ),
						'indent'    => true,
					),
					array(
						'id'        => 'slz-car-posts',
						'type'      => 'text',
						'title'     => esc_html__( 'Cars Per Page', 'exploore' ),
						'subtitle'  => esc_html__( 'Select a number of cars to show on search car result page', 'exploore' ),
						'default'   => '5'
					),
					array(
						'id'        => 'slz-car-page-title-show',
						'type'      => 'switch',
						'title'     => esc_html__( 'Show Page Title', 'exploore' ),
						'subtitle'  => esc_html__( 'Choose to show or hide page title', 'exploore' ),
						'on'        => esc_html__( 'Show', 'exploore'),
						'off'       => esc_html__( 'Hide', 'exploore'),
						'default'   => true,
					),
					array(
						'id'        => 'slz-car-page-title-bg',
						'type'      => 'background',
						'title'     => esc_html__( 'Page Title Background Image', 'exploore' ),
						'subtitle'  => esc_html__( 'Body background image for page title section', 'exploore' ),
						'default'   => array(
							'background-color'      => '#f3f3f3',
							'background-repeat'     => 'no-repeat',
							'background-size'       => 'cover',
							'background-attachment' => 'fixed',
							'background-position'   => 'center center',
							'background-image'      => ''
						)
					),
					array(
						'id'        => 'slz-car-filter',
						'type'      => 'sorter',
						'title'     => esc_html__( 'Car Filter By', 'exploore' ),
						'options'   => array(
							'disabled' => array(
							),
							'enabled'  => array(
								'find-car-box'      => esc_html__( 'Find Cars Box', 'exploore' ),
								'price'             => esc_html__( 'Price', 'exploore' ),
								'rating'            => esc_html__( 'Review Rating', 'exploore' ),
								'location'          => esc_html__( 'Locations', 'exploore' ),
								'category'          => esc_html__( 'Categories', 'exploore' )
							),
						),
					),
					array(
						'id'        => 'slz-car-price-max',
						'type'      => 'text',
						'title'     => esc_html__( 'Price Filter Max Value', 'exploore' ),
						'default'   => '3000'
					),
					array(
						'id'        => 'slz-car-result-count',
						'type'      => 'switch',
						'title'     => esc_html__( 'Show Result Found', 'exploore' ),
						'subtitle'  => esc_html__( 'Choose to show or hide Result Found box in car list', 'exploore' ),
						'on'        => esc_html__( 'Show', 'exploore'),
						'off'       => esc_html__( 'Hide', 'exploore'),
						'default'   => true
					),
					array(
						'id'        => 'slz-car-list-sort',
						'type'      => 'switch',
						'title'     => esc_html__( 'Show Sort By', 'exploore' ),
						'subtitle'  => esc_html__( 'Choose to show or hide Sort By box in car list', 'exploore' ),
						'on'        => esc_html__( 'Show', 'exploore'),
						'off'       => esc_html__( 'Hide', 'exploore'),
						'default'   => true
					)
				)
			);
			// cruises  Setting
			$this->sections[] = array(
				'title'     => esc_html__( 'Cruises Setting', 'exploore' ),
				'icon'      => 'el el-lines',
				'desc'      => esc_html__( 'Setting for cruises page.', 'exploore' ),
				'fields'    => array(
					array(
						'id'        => 'slz-cruises-disable-status',
						'type'      => 'select',
						'multi'     => true,
						'title'     => esc_html__( 'Cruise Status Disable', 'exploore' ),
						'subtitle'  => esc_html__( 'Choose disable status. Cruises in this status is not display on frontend.', 'exploore' ),
						'data'      => 'categories',
						'args'      => array( 'taxonomy' => array('slzexploore_cruise_status') )
					),
					array(
						'id'       => 'slz-cruise-page-title-bg-type',
						'type'     => 'switch',
						'title'    => esc_html__( 'Page Title Background', 'exploore' ),
						'subtitle' => esc_html__( 'Setting page title background for single page. Select featured image or page title background image.', 'exploore' ),
						'on'       => esc_html__( 'Featured Image', 'exploore' ),
						'off'      => esc_html__( 'Page Title Background', 'exploore' ),
						'default'  => true,
					),
					array(
						'id'        => 'slz-cruises-title-section',
						'type'      => 'section',
						'title'     => esc_html__( 'Title Setting', 'exploore' ),
						'subtitle'  => esc_html__( 'Configuration for title. This content will display below breadcrumb.', 'exploore' ),
						'indent'    => true,
					),
					array(
						'id'       => 'slz-cruises-show-title',
						'type'     => 'switch',
						'title'    => esc_html__( 'Show Cruises Title', 'exploore' ),
						'subtitle' => esc_html__( 'Choose to show or hide title - only apply for cruises page (archive,detail)', 'exploore' ),
						'on'       => esc_html__( 'Show', 'exploore' ),
						'off'      => esc_html__( 'Hide', 'exploore' ),
						'default'  => false,
					),
					array(
						'id'        => 'slz-cruises-custom-title',
						'type'      => 'text',
						'title'     => esc_html__( 'Custom Cruises Title', 'exploore' ),
						'subtitle'  => esc_html__( 'Custom title for cruises archive, cruises category page', 'exploore' ),
						'required'  => array('slz-cruises-show-title','=',true),
						'default'   => ''
					),
					array(
						'id'        => 'slz-cruises-detail-custom-title',
						'type'      => 'text',
						'title'     => esc_html__( 'Custom Cruises Detail Title', 'exploore' ),
						'subtitle'  => esc_html__( 'Custom title for cruises detail page', 'exploore' ),
						'required'  => array('slz-cruises-show-title','=',true),
						'default'   => ''
					),
					array(
						'id'       => 'slz-cruises-show-price',
						'type'     => 'switch',
						'title'    => esc_html__( 'Show Price', 'exploore' ),
						'subtitle' => esc_html__( 'Choose to show or hide price on page title of cruises detail page', 'exploore' ),
						'on'       => esc_html__( 'Show', 'exploore' ),
						'off'      => esc_html__( 'Hide', 'exploore' ),
						'required' => array('slz-cruises-show-title','=',true),
						'default'  => true,
					),
					array(
						'id'     => 'section-end',
						'type'   => 'section',
						'indent' => false,
					),
					array(
						'id'       => 'slz-cruises-show-related',
						'type'     => 'switch',
						'title'    => esc_html__( 'Show Related Cruises', 'exploore' ),
						'subtitle' => esc_html__( 'Show or hide related cruises in cruises detail page.', 'exploore' ),
						'on'       => esc_html__( 'Show', 'exploore' ),
						'off'      => esc_html__( 'Hide', 'exploore' ),
						'default'  => true,
					),
					array(
						'id'       => 'slz-cruises-show-share',
						'type'     => 'switch',
						'title'    => esc_html__( 'Show Share Button', 'exploore' ),
						'subtitle' => esc_html__( 'Show or hide share button in cruise list page.', 'exploore' ),
						'on'       => esc_html__( 'Show', 'exploore' ),
						'off'      => esc_html__( 'Hide', 'exploore' ),
						'default'  => false,
					),
					array(
						'id'        => 'slz-cruises-filter-section',
						'type'      => 'section',
						'title'     => esc_html__( 'Setting Cruises Search', 'exploore' ),
						'subtitle'  => esc_html__( 'Set the filter conditions in cruises result page', 'exploore' ),
						'indent'    => true,
					),
					array(
						'id'        => 'slz-cruises-posts',
						'type'      => 'text',
						'title'     => esc_html__( 'Cruises Per Page', 'exploore' ),
						'subtitle'  => esc_html__( 'Select a number of cruises to show on search cruises result page', 'exploore' ),
						'default'   => '6'
					),
					array(
						'id'        => 'slz-cruises-page-title-show',
						'type'      => 'switch',
						'title'     => esc_html__( 'Show Page Title', 'exploore' ),
						'subtitle'  => esc_html__( 'Choose to show or hide page title', 'exploore' ),
						'on'        => esc_html__( 'Show', 'exploore'),
						'off'       => esc_html__( 'Hide', 'exploore'),
						'default'   => true,
					),
					array(
						'id'        => 'slz-cruises-page-title-bg',
						'type'      => 'background',
						'title'     => esc_html__( 'Page Title Background Image', 'exploore' ),
						'subtitle'  => esc_html__( 'Body background image for page title section', 'exploore' ),
						'default'   => array(
							'background-color'      => '#f3f3f3',
							'background-repeat'     => 'no-repeat',
							'background-size'       => 'cover',
							'background-attachment' => 'fixed',
							'background-position'   => 'center center',
							'background-image'      => ''
						)
					),
					array(
						'id'        => 'slz-cruises-filter',
						'type'      => 'sorter',
						'title'     => esc_html__( 'Cruises Filter By', 'exploore' ),
						'options'   => array(
							'disabled' => array(
							),
							'enabled'  => array(
								'find-cruises-box'  => esc_html__( 'Find Cruises Box', 'exploore' ),
								'price'             => esc_html__( 'Price', 'exploore' ),
								'star_rating'       => esc_html__( 'Star Rating', 'exploore' ),
								'rating'            => esc_html__( 'Review Rating', 'exploore' ),
								'location'          => esc_html__( 'Locations', 'exploore' ),
								'category'          => esc_html__( 'Categories', 'exploore' ),
								'facility'          => esc_html__( 'Facilities', 'exploore' )
							),
						),
					),
					array(
						'id'        => 'slz-cruises-price-max',
						'type'      => 'text',
						'title'     => esc_html__( 'Price Filter Max Value', 'exploore' ),
						'default'   => '3000'
					),
					array(
						'id'        => 'slz-cruises-max-rate',
						'type'      => 'text',
						'title'     => esc_html__( 'Max Star Rate', 'exploore' ),
						'subtitle'  => esc_html__( 'Setting maximum star rating value in search box.', 'exploore' ),
						'default'   => '5'
					),
					array(
						'id'        => 'slz-cruises-result-count',
						'type'      => 'switch',
						'title'     => esc_html__( 'Show Result Found', 'exploore' ),
						'subtitle'  => esc_html__( 'Choose to show or hide Result Found box in cruises list', 'exploore' ),
						'on'        => esc_html__( 'Show', 'exploore'),
						'off'       => esc_html__( 'Hide', 'exploore'),
						'default'   => true
					),
					array(
						'id'        => 'slz-cruises-list-sort',
						'type'      => 'switch',
						'title'     => esc_html__( 'Show Sort By', 'exploore' ),
						'subtitle'  => esc_html__( 'Choose to show or hide Sort By box in cruises list', 'exploore' ),
						'on'        => esc_html__( 'Show', 'exploore'),
						'off'       => esc_html__( 'Hide', 'exploore'),
						'default'   => true
					)
				)
			);
			// Booking
			$this->sections[] = array(
				'title'     => esc_html__( 'Booking Setting', 'exploore' ),
				'icon'      => 'el el-calendar',
				'desc'      => esc_html__( 'Setting for booking information', 'exploore' ),
				'fields'    => array(
					array(
						'id'        => 'slz-booking-active',
						'type'      => 'sorter',
						'title'     => esc_html__( 'Booking Active', 'exploore' ),
						'subtitle'  => esc_html__( 'Choose what you want to show booking button.', 'exploore' ),
						'options'   => array(
							'disabled' => array(
							),
							'enabled'  => array(
								'car' 		=>  esc_html__( 'Car Rent', 'exploore' ),
								'cruise'	=>  esc_html__( 'Cruises', 'exploore' ),
								'hotel'	 	=>  esc_html__( 'Hotel', 'exploore' ),
								'tour' 	    =>  esc_html__( 'Tour', 'exploore' )
							),
						)
					),
					array(
						'id'        => 'slz-woocommerce-checkout-active',
						'type'      => 'sorter',
						'title'     => esc_html__( 'Checkout by Woocommerce', 'exploore' ),
						'subtitle'  => esc_html__( 'Choose what you want to checkout booking by using Woocommerce.', 'exploore' ),
						'options'   => array(
							'disabled' => array(
							),
							'enabled'  => array(
								'car' 		=>  esc_html__( 'Car Rent', 'exploore' ),
								'cruise'	=>  esc_html__( 'Cruises', 'exploore' ),
								'hotel'	 	=>  esc_html__( 'Hotel', 'exploore' ),
								'tour' 	    =>  esc_html__( 'Tour', 'exploore' )
							),
						)
					),
					array(
						'id'        => 'slz-booking-redirect-page',
						'type'      => 'select',
						'data'      => 'pages',
						'title'     => esc_html__( 'Choose Redirect Page', 'exploore' ),
						'subtitle'  => esc_html__( 'Choose a page that redirect to after booking finished. This only apply when Checkout by Woocommerce option is disabled.', 'exploore' ),
						'default'   => ''
					),
					array(
						'id'        => 'slz-tour-confirm-email-section',
						'type'      => 'section',
						'title'     => esc_html__( 'Tour Booking Email Setting', 'exploore' ),
						'subtitle'  => esc_html__( 'Configuration for email', 'exploore' ),
						'indent'    => true,
					),
					array(
						'id'        => 'slz-tour-booking-method',
						'type'      => 'button_set',
						'title'     => esc_html__( 'Booking Method', 'exploore' ),
						'subtitle'  => esc_html__( 'Choose booking method in detail page.','exploore'),
						'options'   => array(
							'booking'       => esc_html__( 'Booking Form', 'exploore' ),
							'contact'       => esc_html__( 'Contact Form', 'exploore' ),
							'both'          => esc_html__( 'Booking Form and Contact Form', 'exploore' )
						),
						'default'   => 'booking'
					),
					array(
						'id'        => 'slz-tour-cf7-booking',
						'type'      => 'select',
						'options'   => $this->get_contact_form(),
						'title'     => esc_html__( ' Contact Form Booking', 'exploore' ),
						'subtitle'  => esc_html__( 'Choose contact form to booking', 'exploore' )
					),
					array(
						'id'        => 'slz-tour-send-owner-email',
						'type'      => 'switch',
						'title'     => esc_html__( 'Send Email To Manager', 'exploore' ),
						'subtitle'  => esc_html__( 'Enable or disable send booking infomation to manager.', 'exploore' ),
						'on'        => esc_html__( 'Yes', 'exploore' ),
						'off'       => esc_html__( 'No', 'exploore' ),
						'default'   => true
					),
					array(
						'id'        => 'slz-tour-confirm-email-to',
						'type'      => 'text',
						'title'     => esc_html__('To', 'exploore'),
						'subtitle'  => esc_html__( 'Enter a receiver email. Booking infomation will be sent to this email and customer email.', 'exploore' ),
					),
					array(
						'id'        => 'slz-tour-confirm-email-from',
						'type'      => 'text',
						'title'     => esc_html__('From', 'exploore'),
						'subtitle'  => esc_html__( 'Enter a sender email. If this field is empty, Sender email is admin email.', 'exploore' ),
						'default'   => get_option('admin_email'),
					),
					array(
						'id'        => 'slz-tour-confirm-email-subject',
						'type'      => 'text',
						'title'     => esc_html__('Subject', 'exploore'),
						'subtitle'  => esc_html__( 'Tour booking email subject.', 'exploore' ),
						'default'   => 'Exploore "[tour_name]"'
					),
					array(
						'id'        => 'slz-tour-confirm-email-header',
						'type'      => 'text',
						'title'     => esc_html__('Reply To', 'exploore'),
						'subtitle'  => esc_html__( 'Tour booking email reply to.', 'exploore' ),
						'default'   => '[site_name] <[from_email]>'
					),
					array(
						'id'        => 'slz-tour-confirm-email-description',
						'type'      => 'editor',
						'title'     => esc_html__('Message Body', 'exploore'),
						'subtitle'  => esc_html__( 'Tour booking email message.', 'exploore' ),
						'default'   => $this->get_mail_template( 'tour' )
					),
					array(
						'id'        => 'slz-hotel-confirm-email-section',
						'type'      => 'section',
						'title'     => esc_html__( 'Accomodation Booking Email Setting', 'exploore' ),
						'subtitle'  => esc_html__( 'Configuration for email', 'exploore' ),
						'indent'    => true,
					),
					array(
						'id'        => 'slz-hotel-booking-method',
						'type'      => 'button_set',
						'title'     => esc_html__( 'Booking Method', 'exploore' ),
						'subtitle'  => esc_html__( 'Choose booking method in detail page.', 'exploore' ),
						'options'   => array(
							'booking'       => esc_html__( 'Booking Form', 'exploore' ),
							'contact'       => esc_html__( 'Contact Form', 'exploore' ),
							'both'          => esc_html__( 'Booking Form and Contact Form', 'exploore' )
						),
						'default'   => 'booking'
					),
					array(
						'id'        => 'slz-hotel-cf7-booking',
						'type'      => 'select',
						'options'   => $this->get_contact_form(),
						'title'     => esc_html__( ' Contact Form Booking', 'exploore' ),
						'subtitle'  => esc_html__( 'Choose contact form to booking', 'exploore' )
					),
					array(
						'id'        => 'slz-hotel-send-owner-email',
						'type'      => 'switch',
						'title'     => esc_html__( 'Send Email To Manager', 'exploore' ),
						'subtitle'  => esc_html__( 'Enable or disable send booking infomation to manager.', 'exploore' ),
						'on'        => esc_html__( 'Yes', 'exploore' ),
						'off'       => esc_html__( 'No', 'exploore' ),
						'default'   => true
					),
					array(
						'id'        => 'slz-hotel-confirm-email-to',
						'type'      => 'text',
						'title'     => esc_html__('To', 'exploore'),
						'subtitle'  => esc_html__( 'Enter a receiver email. Booking infomation will be sent to this email and customer email.', 'exploore' )
					),
					array(
						'id'        => 'slz-hotel-confirm-email-from',
						'type'      => 'text',
						'title'     => esc_html__('From', 'exploore'),
						'subtitle'  => esc_html__( 'Enter a sender email. If this field is empty, Sender email is admin email.', 'exploore' ),
						'default'   => get_option('admin_email')
					),
					array(
						'id'        => 'slz-hotel-confirm-email-subject',
						'type'      => 'text',
						'title'     => esc_html__('Subject', 'exploore'),
						'subtitle'  => esc_html__( 'Hotel booking email subject.', 'exploore' ),
						'default'   => 'Exploore "[hotel_name]"'
					),
					array(
						'id'        => 'slz-hotel-confirm-email-header',
						'type'      => 'text',
						'title'     => esc_html__('Reply To', 'exploore'),
						'subtitle'  => esc_html__( 'Hotel booking email reply to.', 'exploore' ),
						'default'   => '[site_name] <[from_email]>'
					),
					array(
						'id'        => 'slz-hotel-confirm-email-description',
						'type'      => 'editor',
						'title'     => esc_html__('Message Body', 'exploore'),
						'subtitle'  => esc_html__( 'Hotel booking email message.', 'exploore' ),
						'default'   => $this->get_mail_template( 'hotel' )
					),
					array(
						'id'        => 'slz-car-confirm-email-section',
						'type'      => 'section',
						'title'     => esc_html__( 'Car Booking Email Setting', 'exploore' ),
						'subtitle'  => esc_html__( 'Configuration for email', 'exploore' ),
						'indent'    => true,
					),
					array(
						'id'        => 'slz-car-booking-method',
						'type'      => 'button_set',
						'title'     => esc_html__( 'Booking Method', 'exploore' ),
						'subtitle'  => esc_html__( 'Choose booking method in detail page.', 'exploore' ),
						'options'   => array(
							'booking'       => esc_html__( 'Booking Form', 'exploore' ),
							'contact'       => esc_html__( 'Contact Form', 'exploore' ),
							'both'          => esc_html__( 'Booking Form and Contact Form', 'exploore' )
						),
						'default'   => 'booking'
					),
					array(
						'id'        => 'slz-car-cf7-booking',
						'type'      => 'select',
						'options'   => $this->get_contact_form(),
						'title'     => esc_html__( ' Contact Form Booking', 'exploore' ),
						'subtitle'  => esc_html__( 'Choose contact form to booking', 'exploore' )
					),
					array(
						'id'        => 'slz-car-send-owner-email',
						'type'      => 'switch',
						'title'     => esc_html__( 'Send Email To Manager', 'exploore' ),
						'subtitle'  => esc_html__( 'Enable or disable send booking infomation to manager.', 'exploore' ),
						'on'        => esc_html__( 'Yes', 'exploore' ),
						'off'       => esc_html__( 'No', 'exploore' ),
						'default'   => true
					),
					array(
						'id'        => 'slz-car-confirm-email-to',
						'type'      => 'text',
						'title'     => esc_html__('To', 'exploore'),
						'subtitle'  => esc_html__( 'Enter a receiver email. Booking infomation will be sent to this email and customer email.', 'exploore' )
					),
					array(
						'id'        => 'slz-car-confirm-email-from',
						'type'      => 'text',
						'title'     => esc_html__('From', 'exploore'),
						'subtitle'  => esc_html__( 'Enter a sender email. If this field is empty, Sender email is admin email.', 'exploore' ),
						'default'   => get_option('admin_email')
					),
					array(
						'id'        => 'slz-car-confirm-email-subject',
						'type'      => 'text',
						'title'     => esc_html__('Subject', 'exploore'),
						'subtitle'  => esc_html__( 'Car booking email subject.', 'exploore' ),
						'default'   => 'Exploore "[car_name]"'
					),
					array(
						'id'        => 'slz-car-confirm-email-header',
						'type'      => 'text',
						'title'     => esc_html__('Reply To', 'exploore'),
						'subtitle'  => esc_html__( 'Car booking email reply to.', 'exploore' ),
						'default'   => '[site_name] <[from_email]>'
					),
					array(
						'id'        => 'slz-car-confirm-email-description',
						'type'      => 'editor',
						'title'     => esc_html__('Message Body', 'exploore'),
						'subtitle'  => esc_html__( 'Car booking email message.', 'exploore' ),
						'default'   => $this->get_mail_template( 'car_rent' )
					),
					array(
						'id'        => 'slz-cruise-confirm-email-section',
						'type'      => 'section',
						'title'     => esc_html__( 'Cruise Booking Email Setting', 'exploore' ),
						'subtitle'  => esc_html__( 'Configuration for email', 'exploore' ),
						'indent'    => true,
					),
					array(
						'id'        => 'slz-cruise-booking-method',
						'type'      => 'button_set',
						'title'     => esc_html__( 'Booking Method', 'exploore' ),
						'subtitle'  => esc_html__( 'Choose booking method in detail page.', 'exploore' ),
						'options'   => array(
							'booking'       => esc_html__( 'Booking Form', 'exploore' ),
							'contact'       => esc_html__( 'Contact Form', 'exploore' ),
							'both'          => esc_html__( 'Booking Form and Contact Form', 'exploore' )
						),
						'default'   => 'booking'
					),
					array(
						'id'        => 'slz-cruise-cf7-booking',
						'type'      => 'select',
						'options'   => $this->get_contact_form(),
						'title'     => esc_html__( ' Contact Form Booking', 'exploore' ),
						'subtitle'  => esc_html__( 'Choose contact form to booking', 'exploore' )
					),
					array(
						'id'        => 'slz-cruise-send-owner-email',
						'type'      => 'switch',
						'title'     => esc_html__( 'Send Email To Manager', 'exploore' ),
						'subtitle'  => esc_html__( 'Enable or disable send booking infomation to manager.', 'exploore' ),
						'on'        => esc_html__( 'Yes', 'exploore' ),
						'off'       => esc_html__( 'No', 'exploore' ),
						'default'   => true
					),
					array(
						'id'        => 'slz-cruise-confirm-email-to',
						'type'      => 'text',
						'title'     => esc_html__('To', 'exploore'),
						'subtitle'  => esc_html__( 'Enter a receiver email. Booking infomation will be sent to this email and customer email.', 'exploore' )
					),
					array(
						'id'        => 'slz-cruise-confirm-email-from',
						'type'      => 'text',
						'title'     => esc_html__('From', 'exploore'),
						'subtitle'  => esc_html__( 'Enter a sender email. If this field is empty, Sender email is admin email.', 'exploore' ),
						'default'   => get_option('admin_email')
					),
					array(
						'id'        => 'slz-cruise-confirm-email-subject',
						'type'      => 'text',
						'title'     => esc_html__('Subject', 'exploore'),
						'subtitle'  => esc_html__( 'Cruise booking email subject.', 'exploore' ),
						'default'   => 'Exploore "[cruise_name]"'
					),
					array(
						'id'        => 'slz-cruise-confirm-email-header',
						'type'      => 'text',
						'title'     => esc_html__('Reply To', 'exploore'),
						'subtitle'  => esc_html__( 'Cruise booking email reply to.', 'exploore' ),
						'default'   => '[site_name] <[from_email]>'
					),
					array(
						'id'        => 'slz-cruise-confirm-email-description',
						'type'      => 'editor',
						'title'     => esc_html__('Message Body', 'exploore'),
						'subtitle'  => esc_html__( 'Cruise booking email message.', 'exploore' ),
						'default'   => $this->get_mail_template( 'cruise' )
					),
				)
			);
			// Shop Setting
			$this->sections[] = array(
				'title'     => esc_html__( 'Product Setting', 'exploore' ),
				'icon'      => 'el el-shopping-cart',
				'desc'      => esc_html__( 'Setting for product pages.', 'exploore' ),
				'fields'    => array(
					array(
						'id'        => 'slz-shop-title-section',
						'type'      => 'section',
						'title'     => esc_html__( 'Title Setting', 'exploore' ),
						'subtitle'  => esc_html__( 'Configuration for title of product detail pages. This content will display below breadcrumb.', 'exploore' ),
						'indent'    => true,
					),
					array(
						'id'       => 'slz-shop-show-title',
						'type'     => 'switch',
						'title'    => esc_html__( 'Show Title', 'exploore' ),
						'subtitle' => esc_html__( 'Choose to show or hide product detail title.', 'exploore' ),
						'on'       => esc_html__( 'Show', 'exploore' ),
						'off'      => esc_html__( 'Hide', 'exploore' ),
						'default'  => false,
					),
					array(
						'id'        => 'slz-shop-custom-title',
						'type'      => 'text',
						'title'     => esc_html__( 'Custom Title', 'exploore' ),
						'subtitle'  => esc_html__( 'Custom title for product detail pages.', 'exploore' ),
						'required'  => array('slz-shop-show-title','=',true),
						'default'   => ''
					),
					array(
						'id'     => 'section-end',
						'type'   => 'section',
						'indent' => false,
					),
					array(
						'id'        => 'slz-shop-page-title-section',
						'type'      => 'section',
						'title'     => esc_html__( 'Page Title Setting', 'exploore' ),
						'subtitle'  => esc_html__( 'Configuration for page title of product pages ( product detail, archive )', 'exploore' ),
						'indent'    => true,
					),
					array(
						'id'        => 'slz-shop-page-title-show',
						'type'      => 'switch',
						'title'     => esc_html__( 'Show Page Title', 'exploore' ),
						'subtitle'  => esc_html__( 'Choose to show or hide page title', 'exploore' ),
						'on'        => esc_html__( 'Show', 'exploore'),
						'off'       => esc_html__( 'Hide', 'exploore'),
						'default'   => true,
					),
					array(
						'id'        => 'slz-shop-page-title-bg',
						'type'      => 'background',
						'title'     => esc_html__( 'Page Title Background Image', 'exploore' ),
						'subtitle'  => esc_html__( 'Body background image for page title section', 'exploore' ),
						'default'   => array(
							'background-color'      => '#f3f3f3',
							'background-repeat'     => 'no-repeat',
							'background-size'       => 'cover',
							'background-attachment' => 'fixed',
							'background-position'   => 'center center',
							'background-image'      => ''
						)
					),
					array(
						'id'     => 'slz-shop-page-title-section-end',
						'type'   => 'section',
						'indent' => false,
					),
				)
			);
			// 404  Setting
			$this->sections[] = array(
				'title'     => esc_html__( '404 Setting', 'exploore' ),
				'icon'      => 'el-icon-info-circle',
				'desc'      => esc_html__( 'This page will display options for 404 page', 'exploore' ),
				'fields'    => array(
					array(
						'id'        => 'slz-404-bg',
						'type'      => 'background',
						'title'     => esc_html__( '404 Page Background', 'exploore' ),
						'default'   => array(
							'background-color'      => '#ffffff',
							'background-image'      => '',
							'background-repeat'     => 'no-repeat',
							'background-attachment' => '',
							'background-position'	=> 'center center',
							'background-size'		=> 'cover'
						)
					),
					
					array(
						'id'        => 'slz-404-title',
						'type'      => 'text',
						'title'     => esc_html__( 'Main Title', 'exploore' ),
						'default'   => esc_html__( '404', 'exploore' )
					),
					array(
						'id'        => 'slz-404-desc',
						'type'      => 'text',
						'title'     => esc_html__( 'Description', 'exploore' ),
						'default'   => esc_html__( 'Page Not Found', 'exploore' )
					),
					array(
						'id'        => 'slz-404-backhome',
						'type'      => 'text',
						'title'     => esc_html__( 'Back To Home Text', 'exploore' ),
						'default'   => esc_html__( 'Back To Home', 'exploore' )
					),
					array(
						'id'        => 'slz-404-button-02',
						'type'      => 'text',
						'title'     => esc_html__( 'Button 02 Text', 'exploore' ),
						'default'   => esc_html__( 'Get Help', 'exploore' )
					),
					array(
						'id'        => 'slz-404-button-02-link-custom',
						'type'      => 'text',
						'title'     => esc_html__( 'Button 02 Link(Custom)', 'exploore'),
						'subtitle'  => esc_html__( 'Empty this field if you want to choose link to page', 'exploore' ),
					),
					array(
						'id'        => 'slz-404-button-02-link',
						'type'      => 'select',
						'data'      => 'pages',
						'title'     => esc_html__( 'Button 02 Link(Link To Page)', 'exploore' ),
						'default'   => '1'
					),
				)
			);
			// Extras
			$captcha_url = 'https://www.google.com/recaptcha/intro/index.html';
			$this->sections[] = array(
				'title'     => esc_html__( 'Extras', 'exploore' ),
				'icon'      => 'el-icon-puzzle',
				'fields'    => array(
					array(
						'id'     	=> 'login-section-start',
						'type'   	=> 'section',
						'title'		=> esc_html__( 'Teams Setting', 'exploore' ),
						'indent' 	=> true,
					),
					array(
						'id'       => 'slz-team-show-teammate',
						'type'     => 'switch',
						'title'    => esc_html__( 'Show Teammate?', 'exploore' ),
						'subtitle' => esc_html__( 'Show or hide list team in team detail pages.', 'exploore' ),
						'on'       => esc_html__( 'Show', 'exploore' ),
						'off'      => esc_html__( 'Hide', 'exploore' ),
						'default'  => true,
					),
					array(
						'id'     	=> 'login-section-end',
						'type'   	=> 'section',
						'indent' 	=> false
					),
					array(
						'id'     	=> 'login-section-start',
						'type'   	=> 'section',
						'title'		=> esc_html__( 'Login Page Setting', 'exploore' ),
						'indent' 	=> true,
					),
					array(
						'id'        => 'slz-login-page',
						'type'      => 'select',
						'data'      => 'pages',
						'title'     => esc_html__( 'Choose Login Page', 'exploore' ),
						'subtitle'  => esc_html__( 'Choose a page that is displayed as login page.', 'exploore' ),
						'default'   => get_option( 'slzexploore_login_page_id' )
					),
					array(
						'id'        => 'slz-bg-loginpage',
						'type'      => 'media',
						'url'       => true,
						'title'     => esc_html__( 'Background Login Page', 'exploore' ),
						'compiler'  => 'true',
						'subtitle'  => esc_html__( 'Choose image', 'exploore' ),
						'default'   => ''
					),
					array(
						'id'        => 'slz-logo-loginpage',
						'type'      => 'media',
						'url'       => true,
						'title'     => esc_html__( 'Logo In Login Page', 'exploore' ),
						'compiler'  => 'true',
						'subtitle'  => esc_html__( 'Choose logo image', 'exploore' ),
						'default'   => array( 'url' => esc_url( SLZEXPLOORE_LOGO_2 ) )
					),
					array(
						'id'        => 'slz-title-loginpage',
						'type'      => 'editor',
						'title'     => esc_html__( 'Title', 'exploore' ),
						'subtitle'  => esc_html__( 'Enter title content. This content will display below logo in login page.', 'exploore' ),
						'default'   => esc_html__( 'sign in to your exploore.com account! ', 'exploore' )
					),
					array(
						'id'        => 'slz-text-loginpage',
						'type'      => 'editor',
						'title'     => esc_html__( 'Text', 'exploore' ),
						'subtitle'  => esc_html__( 'This content will display below sign in button in login page.', 'exploore' ),
						'default'   => esc_html__( 'Don\'t have a exploore account yet?', 'exploore' )
					),
					array(
						'id'     	=> 'login-section-end',
						'type'   	=> 'section',
						'indent' 	=> false
					),
					array(
						'id'     	=> 'register-section-startW',
						'type'   	=> 'section',
						'title'		=> esc_html__( 'Register Page Setting', 'exploore' ),
						'indent' 	=> true,
					),
					array(
						'id'       	=> 'slz-register-page',
						'type'     	=> 'select',
						'data'     	=> 'pages',
						'title'    	=> esc_html__( 'Choose Register Page', 'exploore' ),
						'subtitle'	=> esc_html__( 'Choose a page that is displayed as register page.', 'exploore' ),
						'default'  	=> get_option( 'slzexploore_register_page_id' )
					),
					array(
						'id'       	=> 'slz-bg-registerpage',
						'type'     	=> 'media',
						'url'      	=> true,
						'title'    	=> esc_html__( 'Background Register Page', 'exploore' ),
						'compiler' 	=> 'true',
						'subtitle' 	=> esc_html__( 'Choose image', 'exploore' ),
						'default'  	=> ''
					),
					array(
						'id'       	=> 'slz-logo-registerpage',
						'type'     	=> 'media',
						'url'      	=> true,
						'title'    	=> esc_html__( 'Logo In Register Page', 'exploore' ),
						'compiler' 	=> 'true',
						'subtitle' 	=> esc_html__( 'Choose logo image', 'exploore' ),
						'default'  	=> array( 'url' => esc_url( SLZEXPLOORE_LOGO_2 ) )
					),
					array(
						'id'        => 'slz-title-registerpage',
						'type'      => 'editor',
						'title'     => esc_html__( 'Title', 'exploore' ),
						'subtitle'  => esc_html__( 'Enter title content. This content will display below logo in register page.', 'exploore' ),
						'default'   => 'create your account and join with us!',
					),
					array(
						'id'       => 'slz-show-terms-registerpage',
						'type'     => 'switch',
						'title'    => esc_html__( 'Show Terms Of Service', 'exploore' ),
						'subtitle' => esc_html__( 'Show or hide checkbox term of service in register page.', 'exploore' ),
						'on'       => esc_html__( 'Show', 'exploore' ),
						'off'      => esc_html__( 'Hide', 'exploore' ),
						'default'  => true,
					),
					array(
						'id'        => 'slz-terms-registerpage',
						'type'      => 'editor',
						'required'  => array('slz-show-terms-registerpage','=',true),
						'title'     => esc_html__( 'Terms Of Service', 'exploore' ),
						'subtitle'  => esc_html__( 'Enter content to display at checkbox terms of service in register page.', 'exploore' ),
						'default'   => 'I agree to the terms of service',
					),
					array(
						'id'       => 'slz-show-captcha-registerpage',
						'type'     => 'switch',
						'title'    => esc_html__( 'Show Captcha', 'exploore' ),
						'subtitle' => sprintf( esc_html__( 'Show or hide google reCaptcha in register page. Go to %s to create Key API & Secret Key API for this your site.', 'exploore' ), '<a href="'.esc_url( $captcha_url ).'" >here</a>'),
						'on'       => esc_html__( 'Show', 'exploore' ),
						'off'      => esc_html__( 'Hide', 'exploore' ),
						'default'  => true,
					),
					array(
						'id'        => 'slz-captcha-key-registerpage',
						'type'      => 'text',
						'title'     => esc_html__( 'Key API', 'exploore' ),
						'subtitle'  => esc_html__( 'Enter Key API if using reCaptcha in register page.', 'exploore' ),
						'required'  => array('slz-show-captcha-registerpage','=',true)
					),
					array(
						'id'        => 'slz-captcha-skey-registerpage',
						'type'      => 'text',
						'title'     => esc_html__( 'Secret Key API', 'exploore' ),
						'subtitle'  => esc_html__( 'Enter Secret Key API if using reCaptcha in register page.', 'exploore' ),
						'required'  => array('slz-show-captcha-registerpage','=',true)
					),
					array( 
						'id'     	=> 'login-section-end',
						'type'   	=> 'section',
						'indent' 	=> false
					),
				)
			);
			// Custom CSS
			$this->sections[] = array(
				'title'     => esc_html__( 'Custom Style', 'exploore' ),
				'icon'      => 'el-icon-css',
				'desc'      => esc_html__( 'Customize your site by code', 'exploore' ),
				'fields'    => array(
					array(
						'id'       => 'slz-custom-css',
						'type'     => 'ace_editor',
						'title'    => esc_html__( 'CSS Code', 'exploore' ),
						'subtitle' => esc_html__( 'Paste your CSS code here.', 'exploore' ),
						'mode'     => 'css',
						'theme'    => 'monokai',
						'default'  => "body{\n   margin: 0 auto;\n}"
					),
				)
			);

			// Custom js
			$this->sections[] = array(
				'title'     => esc_html__( 'Custom Script', 'exploore' ),
				'icon'      => 'el-icon-link',
				'desc'      => esc_html__( 'Customize your site by code', 'exploore' ),
				'fields'    => array(
					array(
						'id'       => 'slz-custom-js',
						'type'     => 'ace_editor',
						'title'    => esc_html__( 'JS Code', 'exploore' ),
						'subtitle' => esc_html__( 'Paste your JS code here.', 'exploore' ),
						'mode'     => 'javascript',
						'theme'    => 'chrome',
						'default'  => "jQuery(document).ready(function(){\n\n});"
					),
				)
			);

			// Typography
			$this->sections[] = array(
				'title'     => esc_html__( 'Typography', 'exploore' ),
				'icon'      => 'el-icon-text-height',
				'desc'      => esc_html__( 'Customize your site by code', 'exploore' ),
				'fields'    => array(
					array(
						'id'        => 'slz-typo-body',
						'type'      => 'typography',
						'title'     => esc_html__( 'Body Text', 'exploore' ),
						'subtitle'  => esc_html__( 'Set font ', 'exploore' ),
						'google'    => true,
						'default'   => false
					),
					array(
						'id'        => 'slz-typo-p',
						'type'      => 'typography',
						'title'     => esc_html__( 'Paragraph Text', 'exploore' ),
						'google'    => true,
						'default'   => false
					),
					array(
						'id'       => 'slz-link-color',
						'type'     => 'link_color',
						'title'    => esc_html__( 'Links Color Option', 'exploore' ),
						'subtitle' => esc_html__( 'Only color validation can be done on this field type', 'exploore' ),
						'default'  => array(
							'regular' => '#555e69',
							'hover'   => '#ffdd00',
							'active'  => '#ffdd00',
						)
					),
					array(
						'id'        => 'slz-typo-h1',
						'type'      => 'typography',
						'title'     => esc_html__( 'H1 Text', 'exploore' ),
						'google'    => true,
						'default'   => false
					),
					array(
						'id'        => 'slz-typo-h2',
						'type'      => 'typography',
						'title'     => esc_html__( 'H2 Text', 'exploore' ),
						'google'    => true,
						'default'   => false
					),
					array(
						'id'        => 'slz-typo-h3',
						'type'      => 'typography',
						'title'     => esc_html__( 'H3 Text', 'exploore' ),
						'google'    => true,
						'default'   => false
					),
					array(
						'id'        => 'slz-typo-h4',
						'type'      => 'typography',
						'title'     => esc_html__( 'H4 Text', 'exploore' ),
						'google'    => true,
						'default'   => false
					),
					array(
						'id'        => 'slz-typo-h5',
						'type'      => 'typography',
						'title'     => esc_html__( 'H5 Text', 'exploore' ),
						'google'    => true,
						'default'   => false
					),
					array(
						'id'        => 'slz-typo-h6',
						'type'      => 'typography',
						'title'     => esc_html__( 'H6 Text', 'exploore' ),
						'google'    => true,
						'default'   => false
					)
				)
			);

		}

		public function setHelpTabs() {

			// Custom page help tabs, displayed using the help API. Tabs are shown in order of definition.
			$this->args['help_tabs'][] = array(
				'id'        => 'redux-help-tab-1',
				'title'     => esc_html__('Theme Information 1', 'exploore'),
				'content'   => sprintf('<p>%s</p>', esc_html__('This is the tab content, HTML is allowed.', 'exploore'))
			);

			$this->args['help_tabs'][] = array(
				'id'        => 'redux-help-tab-2',
				'title'     => esc_html__('Theme Information 2', 'exploore'),
				'content'   => sprintf('<p>%s</p>', esc_html__('This is the tab content, HTML is allowed.', 'exploore'))
			);

			// Set the help sidebar
			$this->args['help_sidebar'] = sprintf('<p>%s</p>', esc_html__('This is the sidebar content, HTML is allowed.', 'exploore'));

		}

		/*
	      All the possible arguments for Redux.
	      For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
		*/

		public function setArguments() {

			$theme = wp_get_theme(); // For use with some settings. Not necessary.

			$this->args = array(
				'opt_name'              => 'slzexploore_options',
				'dev_mode'              => false, // disable dev mode when release
				'use_cdn' 				=> false,
				'global_variable'       => 'slzexploore_options',
				'display_name'          =>  esc_html__('Exploore', 'exploore'),
				'display_version'       => false,
				'page_slug'             => esc_html__('Exploore_options', 'exploore'),
				'page_title'            => esc_html__('Exploore Option Panel', 'exploore'),
				'update_notice'         => false,
				'menu_type'             => 'menu',
				'menu_title'            => esc_html__('Theme options', 'exploore'),
				'menu_icon'             => SLZEXPLOORE_ADMIN_URI.'/images/menu-icon.png',
				'allow_sub_menu'        => true,
				'page_priority'         => '31',
				'page_parent' 			=> 'slzexploore_welcome',
				'customizer'            => true,
				'default_mark'          => '*',
				'class'                 => 'sw_theme_options_panel',
				'hints'                 => array(
					'icon'          => 'el-icon-question-sign',
					'icon_position' => 'right',
					'icon_size'     => 'normal',
					'tip_style'     => array(
						'color' => 'light',
					),
					'tip_position' => array(
						'my' => 'top left',
						'at' => 'bottom right',
					),
					'tip_effect' => array(
						'show' => array(
							'duration' => '500',
							'event'    => 'mouseover',
						),
						'hide' => array(
							'duration' => '500',
							'event'    => 'mouseleave unfocus',
						),
					),
				),
				'intro_text'         => '',
				'footer_text'        => wp_kses( __('<p>Thank you for purchased Exploore!</p>', 'exploore'), array('p' => array())),
				'page_icon'          => 'icon-themes',
				'page_permissions'   => 'manage_options',
				'save_defaults'      => true,
				'show_import_export' => true,
				'database'           => 'options',
				'transient_time'     => '3600',
				'network_sites'      => true,
			);

			$this->args['share_icons'][] = array(
				'url'   => 'https://www.facebook.com/rubikthemes/',
				'title' =>  esc_html__('Like us on Facebook', 'exploore'),
				'icon'  => 'el-icon-facebook'
			);
			$this->args['share_icons'][] = array(
				'url'   => 'https://themeforest.net/user/therubikthemes',
				'title' => esc_html__('Follow us on themeforest', 'exploore'),
				'icon'  => 'el-icon-user'
			);
			$this->args['share_icons'][] = array(
				'url'   => 'mailto:admin@rubikthemes.com',
				'title' => esc_html__('Send us email', 'exploore'),
				'icon'  => 'el-icon-envelope'
			);
		}
	}

	global $reduxConfig;
	$reduxConfig = new Slzexploore_Redux_Framework_Config();
}
/*
  Custom function for the callback validation referenced above
*/
if (!function_exists('slzexploore_validate_callback_function')):
	function slzexploore_validate_callback_function($field, $value, $existing_value) {
		$error = false;
		$value = 'just testing';

		$return['value'] = $value;
		if ($error == true) {
			$return['error'] = $field;
		}
		return $return;
	}
endif;