<?php
$cart_link = '';
$cart_icon = '';
$header_account = Slzexploore::get_option('slz-header-account');
if($header_account == 'woocommerce' && SLZEXPLOORE_WOOCOMMERCE_ACTIVE) {
	$cart_link        = get_permalink( get_option( 'woocommerce_cart_page_id') );
	$register_page_id = $login_page_id = get_option( 'woocommerce_myaccount_page_id' );
	$account_link     =  get_permalink( $login_page_id );
	$account_text     = esc_html__( 'My Account', 'exploore' );
}else{
	$login_page_id    = get_option( 'slzexploore_login_page_id' );
	$register_page_id = get_option( 'slzexploore_register_page_id' );
	$account_link     = esc_url( admin_url( 'profile.php' ) );
	$account_text     = esc_html__( 'My Profile', 'exploore' );
}

if( !empty( $cart_link ) ) {
	$cart_icon = sprintf('<li><a href="%s" class="item"><i class="icons fa fa-shopping-cart"></i></a></li>',
		esc_url($cart_link)
	);
}
if ( Slzexploore::get_option('slz-header-account') != 'hide' ){
	if ( is_user_logged_in() ) {
		printf('<ul class="topbar-right pull-right list-unstyled list-inline login-widget">
			<li><a href="%1$s" class="item">%2$s</a></li>
			<li><a href="%3$s" class="item">%4$s</a></li>'.wp_kses_post($cart_icon).'</ul>',
			esc_url($account_link),
			esc_html( $account_text ),
			esc_url( wp_logout_url( get_permalink( $login_page_id ) ) ),
			esc_html__( 'Sign out', 'exploore' )
		);
	}
	else {
		printf('<ul class="topbar-right pull-right list-unstyled list-inline login-widget">	<li><a href="%1$s" class="item">%2$s</a></li>
				<li><a href="%3$s" class="item">%4$s</a></li>'.wp_kses_post($cart_icon).'</ul>',
				esc_url( get_permalink( $login_page_id ) ),
				esc_html__( 'Login', 'exploore' ),
				esc_url( get_permalink( $register_page_id ) ),
				esc_html__( 'Register', 'exploore' )
		);
	}	
}
