<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the content div and all content after
 *
 * @author Swlabs
 * @since 1.0
 */

/* Subcribe
$subcribe_show 		= Slzexploore::get_option('slz-subcribe');
$subcribe_text01 	= Slzexploore::get_option('slz-subcribe-text-1');
$subcribe_text02 	= Slzexploore::get_option('slz-subcribe-text-2');
$subcribe_formtext 	= Slzexploore::get_option('slz-subcribe-form-text');
$form = '';
if (SLZEXPLOORE_NEWSLETTER_ACTIVE) {
	global $newsletter;
	$options_profile = get_option('newsletter_profile');
	$form = NewsletterSubscription::instance()->get_form_javascript();

	$form .= '<form action="' . esc_url( home_url('/') ). '?na=s" onsubmit="return newsletter_check(this)" method="post">';
		$form .= '<div class="input-group form-subscribe-email">';
		$form .= '<input type="hidden" name="nr" value="widget"/>';
		$form .= '<input class="form-control" type="email" required name="ne" placeholder="'.wp_kses_post($subcribe_formtext).'" onclick="if (this.defaultValue==this.value) this.value=\'\'" onblur="if (this.value==\'\') this.value=this.defaultValue"/>';
		$form .= '<span class="input-group-btn-custom">';
					$form .= '<button type="submit" class="btn-email">&#8594;</button>';
					$form .= '</span>';
		$form .= '</div></form>';
	$form = $newsletter->replace($form);
}*/

?>
					</div>
					<!-- MAIN CONTENT-->
				</div>
				<!-- WRAPPER -->
			</div>
			<!-- WRAPPER CONTENT -->
			<!-- FOOTER-->
			<footer>
				<?php if ( $subcribe_show == '1' ) { ?>
					<div class="subscribe-email">
					    <div class="container">
					        <div class="subscribe-email-wrapper">
					            <div class="subscribe-email-left">
					            	<p class="subscribe-email-title"><?php echo wp_kses_post($subcribe_text01); ?></p>
					                <p class="subscribe-email-text"><?php echo wp_kses_post($subcribe_text02); ?></p>
					            </div>
					            <div class="subscribe-email-right">
					            	<?php printf ('%s',$form); ?>
					            </div>
					            <div class="clearfix"></div>
					        </div>
					    </div>
					</div>
				<?php } ?>
				<?php do_action('slzexploore_show_footerbottom');?>
			</footer>
		</div>
		<div class="slz-button-hove-text hide" data-text="<?php esc_html_e( 'SEND NOW', 'exploore' ) ?>"></div>
		<!-- End #page -->
		<?php if ( Slzexploore::get_option('slz-backtotop') == '1') { ?>
			<div id="back-top"><a href="#top" class="link"><i class="fa fa-angle-double-up"></i></a></div>
		<?php } ?>
		<?php wp_footer(); ?>
	</body>
</html>