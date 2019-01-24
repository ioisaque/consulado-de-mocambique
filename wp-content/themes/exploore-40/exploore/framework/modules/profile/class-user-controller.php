<?php
/**
 * User Controller.
 * 
 * @since 1.0
 */
Slzexploore::load_class( 'Abstract' );

class Slzexploore_User_Controller extends Slzexploore_Abstract {

	// Add notices message
	public function add_notices( $notice_name, $message ){
		update_option( $notice_name, $message);
	}

	// Print notices message
	public function print_notices( $notice_name ){
		$message = get_option( $notice_name, '');
		if($message){
			printf('<div class="display-notices">%s</div>', $message);
			delete_option($notice_name);
		}
	}

	public function create_default_pages(){
		// add role
		$this->create_roles();
		// add page
		$pages = array(
			'login' => array(
				'name'      => esc_html_x( 'login', 'Page slug', 'exploore' ),
				'title'     => esc_html_x( 'Login', 'Page title', 'exploore' ),
				'template'  => 'page-templates/page-login.php',
				'content'   => ''
			),
			'register' => array(
				'name'      => esc_html_x( 'register', 'Page slug', 'exploore' ),
				'title'     => esc_html_x( 'Register', 'Page title', 'exploore' ),
				'template'  => 'page-templates/page-register.php',
				'content'   => ''
			)
		);
	
		foreach ( $pages as $key => $page ) {
			$this->create_page( esc_sql( $page['name'] ),
								'slzexploore_' . $key . '_page_id',
								$page['title'],
								$page['template'],
								$page['content']
							);
		}
	}

	// create role
	public function create_roles() {
		global $wp_roles;
	
		if ( ! class_exists( 'WP_Roles' ) ) {
			return;
		}
	
		if ( ! isset( $wp_roles ) ) {
			$wp_roles = new WP_Roles();
		}
	
		// Add role
		add_role( 'client', esc_html__( 'Client', 'exploore' ), array(
			'read' 						=> true,
			'edit_posts' 				=> false,
			'delete_posts' 				=> false
		) );
	}

	/**
	* Create a page and store the ID in an option.
	*
	* @param mixed $slug Slug for the new page
	* @param string $option Option name to store the page's ID
	* @param string $page_title (default: '') Title for the new page
	* @param string $page_content (default: '') Content for the new page
	* @param int $post_parent (default: 0) Parent for the new page
	* @return int page ID
	*/
	public function create_page( $slug, $option = '', $page_title = '', $page_template = '', 
									$page_content = '', $post_parent = 0){
		global $wpdb;
		$option_value     = get_option( $option );
		if ( $option_value > 0 ) {
			$page_object = get_post( $option_value );
			if ( !empty($page_object) && 'page' === $page_object->post_type && ! in_array( $page_object->post_status, array( 'pending', 'trash', 'future', 'auto-draft' ) ) ) {
				return $page_object->ID;
			}
		}
	
		if ( strlen( $page_content ) > 0 ) {
			$valid_page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type='page' AND post_status NOT IN ( 'pending', 'trash', 'future', 'auto-draft' ) AND post_content LIKE %s LIMIT 1;", "%{$page_content}%" ) );
		} else {
			$valid_page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type='page' AND post_status NOT IN ( 'pending', 'trash', 'future', 'auto-draft' )  AND post_name = %s LIMIT 1;", $slug ) );
		}
		if ( $valid_page_found ) {
			if ( $option ) {
				update_option( $option, $valid_page_found );
			}
			return $valid_page_found;
		}
	
		// Search for a matching valid trashed page
		if ( strlen( $page_content ) > 0 ) {
			// Search for an existing page with the specified page content (typically a shortcode)
			$trashed_page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type='page' AND post_status = 'trash' AND post_content LIKE %s LIMIT 1;", "%{$page_content}%" ) );
		} else {
			// Search for an existing page with the specified page slug
			$trashed_page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type='page' AND post_status = 'trash' AND post_name = %s LIMIT 1;", $slug ) );
		}
	
		if ( $trashed_page_found ) {
			$page_id   = $trashed_page_found;
			$page_data = array(
				'ID'             => $page_id,
				'post_status'    => 'publish',
			);
			wp_update_post( $page_data );
		} else {
			$page_data = array(
				'post_status'    => 'publish',
				'post_type'      => 'page',
				'post_author'    => 1,
				'post_name'      => $slug,
				'post_title'     => $page_title,
				'post_content'   => $page_content,
				'post_parent'    => $post_parent,
				'comment_status' => 'closed'
			);
			$page_id = wp_insert_post( $page_data );
			if ( $page_id && ! is_wp_error( $page_id ) && !empty($page_template)){
				update_post_meta( $page_id, '_wp_page_template', $page_template );
			}
		}
	
		if ( $option ) {
			update_option( $option, $page_id );
		}
	
		return $page_id;
	}

	/**
	* Login a client (set auth cookie and set global user object)
	*
	* @param int $user_id
	*/
	public function set_customer_auth_cookie( $user_id ) {
		global $current_user;
		$current_user = get_user_by( 'id', $user_id );
		wp_set_auth_cookie( $user_id, true );
	}
	
	// create a new user
	public function create_new_user( $email, $username = '', $password = '' ) {
		// Check the e-mail address
		if ( empty( $email ) || ! is_email( $email ) ) {
			return new WP_Error( 'registration-error-invalid-email', esc_html__( 'Please provide a valid email address.', 'exploore' ) );
		}
	
		if ( email_exists( $email ) ) {
			return new WP_Error( 'registration-error-email-exists', esc_html__( 'An account is already registered with your email address. Please login.', 'exploore' ) );
		}
	
		// Handle username creation
		if ( ! empty( $username ) ) {
	
			$username = sanitize_user( $username );
	
			if (! validate_username( $username ) ) {
				return new WP_Error( 'registration-error-invalid-username', esc_html__( 'Please enter a valid account username.', 'exploore' ) );
			}
	
			if ( username_exists( $username ) )
				return new WP_Error( 'registration-error-username-exists', esc_html__( 'An account is already registered with that username. Please choose another.', 'exploore' ) );
		} else {
			return new WP_Error( 'registration-error-missing-username', esc_html__( 'Please enter an account username.', 'exploore' ) );
		}
	
		// Handle password creation
		if ( empty( $password ) ) {
			return new WP_Error( 'registration-error-missing-password', esc_html__( 'Please enter an account password.', 'exploore' ) );
		}
	
		// WP Validation
		$validation_errors = new WP_Error();
	
		if ( $validation_errors->get_error_code() )
			return $validation_errors;
	
		$role = 'client';
		$new_user_data =  array(
			'user_login' => $username,
			'user_pass'  => $password,
			'user_email' => $email,
			'role'       => $role
		);
		$customer_id = wp_insert_user( $new_user_data );
	
		if ( is_wp_error( $customer_id ) ) {
			return new WP_Error( 'registration-error', '<strong>' . esc_html__( 'ERROR', 'exploore' ) . '</strong>: ' . esc_html__( 'Couldn&#8217;t register you&hellip; please contact us if you continue to have problems.', 'exploore' ) );
		}
		return $customer_id;
	}
	// sedn mail
	public function send_email( $to, $username, $password ) {
		$subject = sprintf('[%s] %s',
							esc_html( get_bloginfo( 'name' ) ),
							esc_html__( 'Your username and password info', 'exploore' )
					);
		$message = sprintf('%1$s : %2$s </br> %3$s : %4$s </br> %4$s',
							esc_html__( 'Username', 'exploore' ),
							esc_html( $username ),
							esc_html__( 'Pasword', 'exploore' ),
							esc_html( $password ),
							esc_url( home_url('/') )
						);
		wp_mail ( $to, $subject, $message );
	}

	/**
	 * Register Form Process
	 */
	public function process_registration() {
		if ( ! empty( $_POST['register'] ) && isset( $_POST['_wpnonce'] ) && wp_verify_nonce( $_POST['_wpnonce'], 'exploore-register' ) ) {
			$username = !empty($_POST['username']) ? $_POST['username'] : '';
			$password = !empty($_POST['password']) ? $_POST['password'] : '';
			$repassword = !empty($_POST['repassword']) ? $_POST['repassword'] : '';
			$email    = !empty($_POST['email']) ? $_POST['email'] : '';
			$terms_register    = !empty($_POST['agree']) ? $_POST['agree'] : '';

			try {
				$validation_error = new WP_Error();
					
				if ( $validation_error->get_error_code() ) {
					throw new Exception( $validation_error->get_error_message() );
				}

				/* reCaptcha */
				$response		= '';
				$is_verify		= false;
				$showcaptcha_register_option = Slzexploore::get_option('slz-show-captcha-registerpage');
				$keycaptcha_register_option = Slzexploore::get_option('slz-captcha-key-registerpage');
				$secretkeycaptcha_register_option = Slzexploore::get_option('slz-captcha-skey-registerpage');
				if ( $showcaptcha_register_option == 1 && isset($_POST['g-recaptcha-response'])) {
					$api_url		= 'https://www.google.com/recaptcha/api/siteverify';
					$site_key		= Slzexploore::get_option('slz-captcha-key-registerpage');
					$secret_key		= Slzexploore::get_option('slz-captcha-skey-registerpage');
					$site_key_post	= $_POST['g-recaptcha-response'];
					$remoteip		= slzexploore_getIP();

					if ( !empty($site_key_post) ) {
						$api_url = $api_url.'?secret='.$secret_key.'&response='.$site_key_post.'&remoteip='.$remoteip;
						$args = array();
						$response = wp_remote_get( $api_url, $args );
						$response = $response['body'];
						$response = json_decode($response);
					}
					if( !empty($response->success) && $response->success == true ) {
						$is_verify = true;
					} else {
						$is_verify = false;
					}
				}

				if ( empty( $username) ) {
					throw new Exception( esc_html__( 'Name field is required.', 'exploore' ) );
				}
	
				if ( empty( $email) ) {
					throw new Exception( esc_html__( 'Email field is required.', 'exploore' ) );
				}
					
				if ( empty( $password) ) {
					throw new Exception( esc_html__( 'Password field is required.', 'exploore' ) );
				}
					
				if ( ! empty( $password ) && empty( $repassword ) ) {
					throw new Exception( esc_html__( 'Please fill out all password fields.', 'exploore' ) );
				}
	
				if ( $password != $repassword ) {
					throw new Exception( esc_html__( 'Password is not match.', 'exploore' ) );
				}
	
				if ( $showcaptcha_register_option == 1 && !empty($keycaptcha_register_option) && !empty($secretkeycaptcha_register_option) && $is_verify != true ) {
					throw new Exception( esc_html__( 'Captcha incorrect.', 'exploore' ) );
				}

				if ( !empty($_POST['agree']) && $terms_register != 'yes' ) {
					throw new Exception( esc_html__( 'To register for membership, you must agree to the terms and conditions of our.', 'exploore' ) );
				}

	
				$new_user = $this->create_new_user(
						sanitize_email( $email ),
						sanitize_text_field( $username ),
						$password
					);
	
				if ( is_wp_error( $new_user ) ) {
					throw new Exception( $new_user->get_error_message() );
				}
					
				if ( !empty( $new_user ) ) {
					$this->set_customer_auth_cookie( $new_user );
				}
				$this->send_email( $email, $username, $password );
				wp_safe_redirect( esc_url( home_url('/') ) );
				exit;
	
			} catch ( Exception $e ) {
				$message = str_replace( '<strong>ERROR</strong>:', ' ', $e->getMessage() );
				$this->add_notices( 'register_msg_error', $message );
			}
		}
	}


	/**
	 * Sign Up Form Process
	 */
	 public function process_login() {
		if ( ! empty( $_POST['login'] ) && ! empty( $_POST['_wpnonce'] ) && wp_verify_nonce( $_POST['_wpnonce'], 'exploore-login' ) ) {
			try {
				$creds  = array();
	
				$validation_error = new WP_Error();
	
				if ( $validation_error->get_error_code() ) {
					throw new Exception( $validation_error->get_error_message() );
				}
	
				if ( empty( $_POST['email'] ) ) {
					throw new Exception( esc_html__( 'Email is required.', 'exploore' ) );
				}
	
				if ( empty( $_POST['password'] ) ) {
					throw new Exception( esc_html__( 'Password is required.', 'exploore' ) );
				}
	
				if ( is_email( $_POST['email'] ) ) {
					$user = get_user_by( 'email', $_POST['email'] );
	
					if ( isset( $user->user_login ) ) {
						$creds['user_login'] 	= $user->user_login;
					} else {
						throw new Exception( esc_html__( 'A user could not be found with this email address.', 'exploore' ) );
					}
	
				} else {
					$creds['user_login'] 	= $_POST['email'];
				}
	
				$creds['user_password'] = $_POST['password'];
				$creds['remember']      = isset( $_POST['rememberme'] );
				$secure_cookie          = is_ssl() ? true : false;
				$user                   = wp_signon( $creds , $secure_cookie );
	
				if ( is_wp_error( $user ) ) {
					$message = $user->get_error_message();
					$message = str_replace( '<strong>' . esc_html( $creds['user_login'] ) . '</strong>', '<strong>' . esc_html( $_POST['email'] ) . '</strong>', $message );
					throw new Exception( $message );
				} else {
					if ( ! empty( $_POST['redirect'] ) ) {
						$redirect = $_POST['redirect'];
					} else {
						$redirect = esc_url( home_url( '/' ) );
					}
	
					// Feedback
					printf( esc_html__( 'You are now logged in as <strong>%s</strong>', 'exploore' ), $user->display_name );
					wp_redirect( $redirect );
					exit;
				}
	
			} catch (Exception $e) {
				$message = str_replace( '<strong>ERROR</strong>:', ' ', $e->getMessage() );
				$this->add_notices( 'login_msg_error', $message );
			}
		}
	}
}