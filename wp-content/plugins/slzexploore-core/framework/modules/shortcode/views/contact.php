<?php
$custom_css = '';
?>

<div class="slz-shortcode contact-<?php echo esc_attr( $id ).' '.esc_attr( $extra_class );?>">
<?php 
if ( !empty($style) && $style == '1' ) {
	$img = wpb_getImageBySize( array( 'attach_id' => (int) $image, 'thumb_size' => 'full' ,'class' => 'img-responsive' ) );
	$bg_img = wpb_getImageBySize( array( 'attach_id' => (int) $background, 'thumb_size' => 'full' ,'class' => 'img-responsive' ) );
	if ( !empty( $bg_img ) ){
		$custom_css .= '.contact-'.esc_attr( $id ).' .page-contact-form { background-image:url("'.esc_attr( $bg_img['p_img_large'][0] ).'");}';
	}
?>
	<div class="contact style-1 page-contact-form padding-top padding-bottom">
		<?php if ( !empty($insert_container) && $insert_container == 'yes' ) {
			echo '<div class="container">';
		} ?>
		<div class="wrapper-contact-form">
			<div data-wow-delay="0.5s" class="contact-wrapper wow fadeInLeft">
				<div class="contact-box">
					<?php if ( !empty( $title ) ) { ?>
					<h5 class="title"><?php printf( "%s", esc_html($title) );?></h5>
					<?php } ?>
					<?php if ( !empty( $description) ) { ?>
					<p class="text"><?php printf( "%s", wp_kses_post($description) );?></p>
					<?php } ?>
					<?php if ( !empty( $contact_form ) && SLZEXPLOORE_CORE_WPCF7_ACTIVE ) { ?>
					<?php echo do_shortcode('[contact-form-7 id="'.esc_attr($contact_form).'" title="'.esc_attr($title).'" html_id="contact-form-'.esc_attr($id).'" html_class="contact-form"]');
					} ?>
				</div>
			</div>
			<?php if ( !empty($img) ) { ?>
			<div data-wow-delay="0.5s" class="wrapper-form-images wow fadeInRight"><?php printf( '%s', $img['thumbnail'] );?></div>
			<?php } ?>
		</div>
		<div class="clear"></div>
		<?php if ( !empty($insert_container) && $insert_container == 'yes' ) {
			echo '</div>';
		} ?>
	</div>
<?php
} elseif ( !empty($style) && $style == '2' ) {
	$img = wpb_getImageBySize( array( 'attach_id' => (int) $image, 'thumb_size' => 'full' ,'class' => 'img-responsive' ) );
	$bg_img = wpb_getImageBySize( array( 'attach_id' => (int) $background, 'thumb_size' => 'full' ,'class' => 'img-responsive' ) );
	if ( !empty( $bg_img ) ){
		$custom_css .= '.contact-'.esc_attr( $id ).' .contact { background-image:url("'.esc_attr( $bg_img['p_img_large'][0] ).'");}';
	}
	if ( !empty($img) ) {
		$row_begin 	= '<div class="wrapper-contact-style">
							<div data-wow-delay="0.5s" class="contact-wrapper-images wow fadeInLeft">'.($img['thumbnail']).'</div>
							<div class="col-lg-6 col-sm-7 col-lg-offset-4 col-sm-offset-5">
								<div data-wow-delay="0.4s" class="contact-wrapper padding-top padding-bottom wow fadeInRight">';
		$row_end 	= '</div>
					</div>
				</div>';
	} else {
		$row_begin 	= '<div class="col-sm-8 col-sm-offset-2">
							<div data-wow-delay="0.4s" class="contact-wrapper padding-top padding-bottom wow zoomIn">';
		$row_end 	= '</div>
					</div>';		
	}
?>
	<div class="contact style-1">
		<?php if ( !empty($insert_container) && $insert_container == 'yes' ) {
			echo '<div class="container">';
		} ?>
		<div class="row">
			<?php echo ( $row_begin ); ?>
				<div class="contact-box">
					<?php if ( !empty( $title ) ) { ?>
					<h5 class="title"><?php printf( "%s", esc_html($title) );?></h5>
					<?php } ?>
					<?php if ( !empty( $description ) ) { ?>
					<p class="text"><?php printf( "%s", wp_kses_post($description) );?></p>
					<?php } ?>
					<?php if ( !empty( $contact_form ) && SLZEXPLOORE_CORE_WPCF7_ACTIVE ) { ?>
					<?php echo do_shortcode('[contact-form-7 id="'.$contact_form.'" title="'.esc_attr($title).'" html_id="contact-form-'.esc_attr($id).'" html_class="contact-form"]');
					} ?>
				</div>
			<?php echo ($row_end); ?>
		</div>
		<?php if ( !empty($insert_container) && $insert_container == 'yes' ) {
			echo '</div>';
		} ?>
	</div>
<?php

} elseif ( !empty($style) && $style == '3' ) {	
?>
	<div class="map-block" data-id="<?php echo esc_attr( $id );?>">
		<div class="map-info">
			<?php if ( !empty( $title ) ) { ?>
			<h3 class="title-style-2"><?php printf( "%s", $title );?></h3>
			<?php } ?>

			<?php if ( !empty( $address ) ) { ?>
			<p class="address"><i class="<?php echo esc_attr( $address_icon ); ?>"></i> <?php printf( "%s", esc_html($address) );?></p>
			<?php } ?>

			<?php if ( !empty( $phone ) ) { ?>
			<p class="phone"><i class="<?php echo esc_attr( $phone_icon ); ?>"></i> <?php printf( "%s", esc_html($phone) );?></p>
			<?php } ?>

			<?php if ( !empty( $email ) ) { ?>
			<p class="mail"><a href="mailto:<?php echo esc_attr( $email ); ?>"> <i class="<?php echo esc_attr( $email_icon ); ?>"></i> <?php printf( "%s", esc_html($email) );?></a></p>
			<?php } ?>

			<div class="footer-block"><a class="btn btn-open-map"><?php printf( "%s", esc_html__( 'Open Map', 'slzexploore-core' ) ); ?></a></div>
		</div>
		<?php if (!empty($address)) {
			printf( '<div id="googleMap" data-img-url="%1$s" data-address="%2$s"></div>', SLZEXPLOORE_CORE_MAP_MAKER, esc_attr( $address ) );
		} ?>
	</div>
<?php
}
if( $custom_css ) {
	do_action( 'slzexploore_core_add_inline_style', $custom_css );
}
?>
</div>