<?php
if ( is_user_logged_in() ) {
	$location = esc_url( admin_url( 'profile.php' ) );
	wp_safe_redirect( $location );
	exit;
}
get_header();
$email = '';
if ( isset( $_POST['email'] ) && !empty( $_POST['email'] ) ) {
	$email = $_POST['email'];
}
$back_url = esc_url( home_url('/') );
if( isset( $_SERVER['HTTP_REFERER'] ) && !empty( $_SERVER['HTTP_REFERER'] ) ){
	$back_url = $_SERVER['HTTP_REFERER'];
}
?>
<div class="page-login rlp">
	<div class="container">
		<div class="login-wrapper rlp-wrapper">
			<div class="login-table rlp-table">
				<a class="btn-close" href="<?php echo esc_url( $back_url ); ?>">&times;</a>
				<?php
					$logo_url = Slzexploore::get_option('slz-logo-loginpage', 'url');
					if( ! empty( $logo_url ) ){
						printf('<a href="%s"><img src="%s" class="login" alt="logo"/></a>',
								esc_url( home_url( '/') ),
								esc_url( $logo_url )
								);
					}
				?>
				<div class="login-title rlp-title">
					<?php echo nl2br( wp_kses_post( Slzexploore::get_option('slz-title-loginpage') ) ); ?>
				</div>
				<?php do_action( 'slzexploore_print_notices', 'login_msg_error' ); ?>
				<form method="post">
					<div class="login-form bg-w-form rlp-form">
						<div class="row">
							<div class="col-md-12">
								<label for="regemail" class="control-label form-label">
									<?php esc_html_e( 'email', 'exploore' ); ?> 
									<span class="required">*</span>
								</label>
								<input id="regemail" type="email" placeholder="" name="email" 
								class="form-control form-input" value="<?php echo esc_attr( $email ); ?>">
							</div>
							<div class="col-md-12">
								<label for="regpassword" class="control-label form-label">
									<?php esc_html_e( 'password', 'exploore' ); ?> 
									<span class="required">*</span>
								</label>
								<input id="regpassword" type="password" name="password" placeholder="" class="form-control form-input">
							</div>
						</div>
						<div class="">
							<input id="remember" name="rememberme" type="checkbox" class="check"/>
							<label for="remember" class="type-checkbox">
								<?php esc_html_e( 'Remember me', 'exploore' ); ?>
							</label>
						</div>
					</div>
					<div class="login-submit">
						<?php wp_nonce_field( 'exploore-login' ); ?>
						<input type="submit" class="btn btn-maincolor" name="login" value="<?php esc_html_e( 'sign in', 'exploore' ); ?>" />
						<?php
							printf( '<a href="%1$s" class="btn btn-cancel">%2$s</a>',
									esc_url( $back_url ),
									esc_html__( 'Cancel', 'exploore' )
								);
						?>
					</div>
				</form>
				<p class="title-sign-in">
				<?php
					$register_page_id = get_option( 'slzexploore_register_page_id' );
					$register_url = sprintf( '<a href="%1$s" class="link signin">%2$s!</a>',
											esc_url( get_permalink( $register_page_id ) ),
											esc_html__( 'create now', 'exploore' )
										);
					echo wp_kses_post( Slzexploore::get_option('slz-text-loginpage') ) . $register_url ;
				?>
				</p>
			</div>
		</div>
	</div>
</div>
<?php get_footer('none');?>