<?php
$model = new Slzexploore_Core_Testimonial();
$model->init( $atts );
$custom_css = '';
$col_left = '';
$col_right = '';
$html_format = '';
$block_cls = 'travel-id-'.esc_attr($atts['id']).' '.esc_attr($atts['extra_class']);

if ( !empty($atts['image']) ) {
	$img = wp_get_attachment_url( $atts['image'] );
	$custom_css .= sprintf( '.travel-id-%s{background-image: url("%s");}', esc_attr($atts['id']), esc_attr($img) );
}
if ( !empty($custom_css) ) {
	do_action( 'slzexploore_core_add_inline_style', $custom_css );
}
if ( empty($atts['title_top']) && empty($atts['title_main']) ) {
	$col_right = 'col-md-12';
}else{
	$col_left = 'col-md-4';
	$col_right = 'col-md-8';
}
$html_format = '
			<div class="traveler">
				<div class="cover-image">%2$s</div>
				<div class="wrapper-content">
					<div class="avatar">
						%6$s
					</div>
					%3$s
					%4$s
					<div class="description">%1$s</div>
				</div>
			</div>
';
$html_options = array(
	'html_format' => $html_format,
);
?>

<div class="slz-shortcode travelers <?php echo esc_attr($block_cls); ?>">
	<div class="container">
		<div class="row">
			<?php if( !empty($atts['title_top']) || !empty($atts['title_main']) ) : ?>
			<div class="<?php echo esc_attr($col_left); ?>">
				<div class="traveler-wrapper padding-top padding-bottom">
					<div class="group-title white">
						<?php if( !empty($atts['title_top']) ) : 
							echo '
							<div class="sub-title">
								<p class="text">'.esc_html($atts['title_top']).'</p>';
							if( !empty($atts['icon_type']) && $atts['icon_type'] == '02' && !empty($atts['icon_fw']) ){
								echo '<i class="icons '.esc_attr($atts['icon_fw']).'"></i>';
							} elseif ( empty($atts['$icon_type']) && !empty($atts['icon_ex']) ) {
								echo '<i class="icons '.esc_attr($atts['icon_ex']).'"></i>';
							}
							echo '</div>';
						endif; 
						if ( !empty($atts['title_main']) ) :
							echo '<h2 class="main-title">'.esc_html($atts['title_main']).'</h2>';
						endif;
						?>
					</div>
				</div>
			</div>
			<?php endif; ?>
			<div class="<?php echo esc_attr($col_right); ?>">
				<div class="traveler-list">
					<?php $model->render_sc( $html_options );?>
				</div>
			</div>
		</div>
	</div>
</div>