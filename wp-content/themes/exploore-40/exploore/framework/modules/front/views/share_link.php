<?php
if( ! SLZEXPLOORE_CORE_IS_ACTIVE ) return;

$cls_share = new Slzexploore_Core_Social_Share;
$share_url = $cls_share->get_share_link();
$action = 'window.open(this.href, \'Share Window\',\'left=50,top=50,width=600,height=350,toolbar=0\');';
$social_enable  = Slzexploore::get_option('slz-blog-social-share', 'enabled');
$share_link = array();
if( !empty( $social_enable ) ) {
	foreach ($social_enable as $key => $value) {
		if ( isset($share_url[$key])){
			$share_link[] = sprintf('<a href="%1$s" onclick="%2$s; return false;" class="link">
										<i class="icons fa fa-%3$s"></i>
									</a>',
									esc_url($share_url[$key]), $action, $key);
		}
	}
}
if( !empty( $share_link ) ){
	printf('<div class="social-share">
				 <div class="title">%1$s</div>
				<div class="social-item">%2$s</div>
			</div>',
			esc_html__( 'share to :', 'exploore' ),
			implode('</div><div class="social-item">', $share_link)
		);
}
?>