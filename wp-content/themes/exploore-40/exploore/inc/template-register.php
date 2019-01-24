<?php
if ( is_user_logged_in() ) {
	$location = esc_url( admin_url( 'profile.php' ) );
	wp_safe_redirect( $location );
	exit;
}
get_header();
$username = '';
$email = '';
if ( isset( $_POST['username'] ) && !empty( $_POST['username'] ) ) {
	$username = $_POST['username'];
}
if ( isset( $_POST['email'] ) && !empty( $_POST['email'] ) ) {
	$email = $_POST['email'];
}

$showterms_register_option = Slzexploore::get_option('slz-show-terms-registerpage');
$terms_register_option = Slzexploore::get_option('slz-terms-registerpage');
$showcaptcha_register_option = Slzexploore::get_option('slz-show-captcha-registerpage');
$keycaptcha_register_option = Slzexploore::get_option('slz-captcha-key-registerpage');
$secretkeycaptcha_register_option = Slzexploore::get_option('slz-captcha-skey-registerpage');

$back_url = esc_url( home_url('/') );
if( isset( $_SERVER['HTTP_REFERER'] ) && !empty( $_SERVER['HTTP_REFERER'] ) ){
	$back_url = $_SERVER['HTTP_REFERER'];
}
?>
<div class="page-register rlp">
	<div class="container">
		<div class="register-wrapper rlp-wrapper">
			<div class="register-table rlp-table">
				<a class="btn-close" href="<?php echo esc_url( $back_url ); ?>">&times;</a>
				<?php
					$logo_url = Slzexploore::get_option('slz-logo-header', 'url');
					if( ! empty( $logo_url ) ){
						printf('<a href="%s"><img src="%s" class="login" alt="logo"/></a>',
								esc_url( home_url( '/' ) ),
								esc_url( $logo_url )
								);
					}
				?>
				<div class="register-title rlp-title">
					<?php esc_html_e( 'create your account and join with us', 'exploore' ); ?>!
				</div>
				<?php do_action( 'slzexploore_print_notices', 'register_msg_error' ); ?>
				<form method="post" class="register" id="register_member" >
					<div class="register-form bg-w-form rlp-form">
						<div class="row">
							<div class="col-md-6">
								<label for="regname" class="control-label form-label">
									<?php esc_html_e( 'User Name', 'exploore' ); ?> 
									<span class="required">*</span>
								</label>
								<input id="regname" type="text" placeholder="" class="form-control form-input" 
										value="<?php if ( ! empty( $_POST['username'] ) ) { echo esc_attr( $_POST['username'] ); }?>" name="username" data-validation-error-msg-required="<?php esc_attr_e( 'Please enter a username.', 'exploore');?>" data-validation-error-msg-minlength="<?php esc_attr_e( 'Your username must consist of at least 6 characters.', 'exploore');?>"/>
								<label for="regname" class="error username"></label>
							</div>

							<div class="col-md-6">
								<label for="regemail" class="control-label form-label">
									<?php esc_html_e( 'Email', 'exploore' ); ?> 
									<span class="required">*</span>
								</label>
								<input id="regemail" type="email" placeholder="" class="form-control form-input"
										value="<?php if ( ! empty( $_POST['email'] ) ) { echo esc_attr( $_POST['email'] ); }?>" name="email" data-validation-error-msg-required="<?php esc_attr_e( 'Please enter your email.', 'exploore');?>" data-validation-error-msg-format="<?php esc_attr_e( 'Please enter a valid email address.', 'exploore');?>" />
								<label for="regemail" class="error email"></label>
							</div>

							<div class="col-md-6">
								<label for="password" class="control-label form-label">
									<?php esc_html_e( 'Password', 'exploore' ); ?> 
									<span class="required">*</span>
								</label>
								<input id="password" type="password" placeholder="" class="form-control form-input" name="password" data-validation-error-msg-required="<?php esc_attr_e( 'Please provide a password.', 'exploore');?>" data-validation-error-msg-minlength="<?php esc_attr_e( 'Your password must be at least 8 characters long.', 'exploore');?>"/>
								<label for="password" class="error password"></label>
							</div>

							<div class="col-md-6">
								<label for="reregpassword" class="control-label form-label">
									<?php esc_html_e( 'Confirm Password', 'exploore' ); ?> 
									<span class="required">*</span>
								</label>
								<input id="reregpassword" type="password" placeholder="" class="form-control form-input" name="repassword" data-validation-error-msg-required="<?php esc_attr_e( 'Please provide a password.', 'exploore');?>" data-validation-error-msg-minlength="<?php esc_attr_e( 'Your password must be at least 8 characters long.', 'exploore');?>" data-validation-error-msg-equalTo="<?php esc_attr_e( 'Please enter the same password as above.', 'exploore');?>"/>
								<label for="reregpassword" class="error repassword"></label>
							</div>

							<?php
							if ( $showcaptcha_register_option == 1 && !empty($keycaptcha_register_option) && !empty($secretkeycaptcha_register_option) ) { ?>
							<div class="col-md-6">
								<div class="register-captcha">
									<div class="g-recaptcha" data-sitekey="<?php echo esc_attr($keycaptcha_register_option); ?>"></div>
									<input id="regrecaptcha" type="hidden" name="recaptcha" value="" data-validation-error-msg-required="<?php esc_attr_e( "Please authentication I'm not a robot.", 'exploore');?>"/>
								</div>
							</div>
							<?php
							} ?>

							<?php
							if ( $showterms_register_option == 1 && !empty($terms_register_option) ) { ?>
							<div class="col-md-6">
								<div class="register-terms">
									<div class="checkbox-terms">
										<input type="checkbox" value="yes" name="agree" id="agree" class="" aria-invalid="false" data-validation-error-msg-required="<?php esc_attr_e( 'To register for membership, you must agree to the terms and conditions of our.', 'exploore');?>">
										<div class="content">
											<?php
											if ( !empty($terms_register_option) ) {
											 	printf( '%s', wp_kses_post( $terms_register_option ) );
											 } ?>
										</div>
										<label for="agree" class="error"></label>
									</div>
								</div>
							</div>
							<?php
							} ?>

						</div>
					</div>

					<div class="register-submit">
						<?php wp_nonce_field( 'exploore-register' ); ?>
						<?php
							printf( '<a href="%1$s" class="btn btn-cancel">%2$s</a>',
									esc_url( $back_url ),
									esc_html__( 'Cancel', 'exploore' )
								);
						?>
						<input type="submit" class="btn btn-register btn-maincolor" name="register" value="<?php esc_attr_e( 'Create Account', 'exploore' ); ?>"/>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<?php get_footer('none');?>