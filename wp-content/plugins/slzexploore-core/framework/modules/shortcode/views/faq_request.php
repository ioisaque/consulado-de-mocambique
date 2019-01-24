<?php
$uniq_id = 'slzexploore_faq_request-'.esc_attr( $id );
$custom_css = '';
if( !empty($background_box) ) {
	$custom_css .= sprintf('.%1$s.sc_faq_request .contact-box { background-color: %2$s; }',
							$uniq_id, $background_box
						);
}
if( !empty($color_error) ) {
	$custom_css .= sprintf('.%1$s.sc_faq_request .contact-form span.wpcf7-not-valid-tip { color: %2$s; }',
							$uniq_id, $color_error
						);
	$custom_css .= sprintf('.%1$s.sc_faq_request .contact-form div.wpcf7-response-output { color: %2$s; border-color: %2$s;}',
							$uniq_id, $color_error
						);
}
if( !empty($color_title_box) ) {
	$custom_css .= sprintf('.%1$s.sc_faq_request .wrapper-contact-faq .contact-box .title { color: %2$s;}',
							$uniq_id, $color_title_box
						);
}
if( !empty($background_input) ) {
	$custom_css .= sprintf('.%1$s.sc_faq_request .contact-form input[type=text], .%1$s.sc_faq_request .contact-form input[type=text], .%1$s.sc_faq_request .contact-form input[type=email], .%1$s.sc_faq_request .contact-form input[type=number], .%1$s.sc_faq_request .contact-form select, .%1$s.sc_faq_request .contact-form textarea { background-color: %2$s;}',
							$uniq_id, $background_input
						);
}
if( !empty($color_input) ) {
	$custom_css .= sprintf('.%1$s.sc_faq_request .contact-form input, .%1$s.sc_faq_request .contact-form input[type=text], .%1$s.sc_faq_request .contact-form input[type=text], .%1$s.sc_faq_request .contact-form input[type=email], .%1$s.sc_faq_request .contact-form input[type=number], .%1$s.sc_faq_request .contact-form select, .%1$s.sc_faq_request .contact-form textarea { color: %2$s;}',
							$uniq_id, $color_input
						);
	$custom_css .= sprintf('.%1$s.sc_faq_request .contact-form ::-webkit-input-placeholder { color: %2$s !important;}',
							$uniq_id, $color_input
						);
	$custom_css .= sprintf('.%1$s.sc_faq_request .contact-form ::-moz-placeholder { color: %2$s !important;}',
							$uniq_id, $color_input
						);
	$custom_css .= sprintf('.%1$s.sc_faq_request .contact-form :-ms-input-placeholder { color: %2$s !important;}',
							$uniq_id, $color_input
						);
	$custom_css .= sprintf('.%1$s.sc_faq_request .contact-form :-moz-placeholder { color: %2$s !important;}',
							$uniq_id, $color_input
						);
	$custom_css .= sprintf('.%1$s.sc_faq_request .contact-form input:-webkit-autofill { -webkit-text-fill-color: %2$s;}',
							$uniq_id, $color_input
						);
}
if( !empty($color_text_button_hv) ) {
	$custom_css .= sprintf('.%1$s.sc_faq_request .contact-form input[type=submit]:hover { color: %2$s;}',
							$uniq_id, $color_text_button_hv
						);
}
if( !empty($color_text_button) ) {
	$custom_css .= sprintf('.%1$s.sc_faq_request .contact-form input[type=submit] { color: %2$s;}',
							$uniq_id, $color_text_button
						);
}
if( !empty($background_button) ) {
	$custom_css .= sprintf('.%1$s.sc_faq_request .contact-form input[type=submit] { background-color: %2$s; border-color: %2$s;}',
							$uniq_id, $background_button
						);
}
if( !empty($background_button_hv) ) {
	$custom_css .= sprintf('.%1$s.sc_faq_request .contact-form input[type=submit]:hover { background-color: %2$s; border-color: %2$s;}',
							$uniq_id, $background_button_hv
						);
}
if( $custom_css ) {
	do_action( 'slzexploore_core_add_inline_style', $custom_css );
}
?>

<div class="slz-shortcode sc_faq_request <?php echo esc_attr( $uniq_id ).' '.esc_attr( $extra_class );?>">
	<div class="wrapper-contact-faq">
		<div class="contact-wrapper">
			<?php if ( !empty( $contact_form ) && SLZEXPLOORE_CORE_WPCF7_ACTIVE ) { ?>
			<div class="contact-box">
				<?php if ( !empty( $title_box ) ) { ?>
				<?php printf( '<h5 class="title">%s</h5>', $title_box );?>
				<?php } ?>
				<?php echo do_shortcode('[contact-form-7 id="'.$contact_form.'" title="'.$title_box.'" html_id="contact-faq-form-'.$id.'" html_class="contact-form"]'); ?>
				<div class="clearfix"></div>
			</div>
			<?php
			} ?>
		</div>
	</div>
</div>