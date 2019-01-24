<?php
$model = new Slzexploore_Core_Room();
if( !empty( $atts['room_id'] ) ) {
	$atts['post_id'] = $atts['room_id'];
	$atts['sort_by'] = 'post__in';
}
else if( empty( $atts['category_slug'] ) ){
	esc_html_e( 'No Room Type Chosen. Please choose a room type to display.', 'slzexploore-core' );
	return;
}
$model->init($atts);
// 1$ - title, 2$ - gallery, 3$ - price, 4$ - excerpt, 5$ - button, 6$ - social, 7$ - book form

$html_format = '<div class="timeline-block">
					%1$s
					<div class="timeline-point">
						<i class="fa fa-circle-o"></i>
					</div>
					<div class="timeline-content">
						<div class="row">
							<div class="timeline-custom-col">
								<div class="image-hotel-view-block">
									%2$s
								</div>
							</div>
							<div class="timeline-custom-col image-col hotels-layout">
								<div class="content-wrapper">
									<div class="content">
										%3$s
										%4$s
										%5$s
									</div>
								</div>
							</div>
							<div class="timeline-custom-col full timeline-book-block"></div>
						</div>
					</div>
				</div>';
$html_options = array( 'html_format'      => $html_format );
$id = $model->uniq_id;
?>
<div class="overview-block clearfix slz-shortcode sc-<?php echo esc_attr($id).' '.esc_attr($atts['extra_class']); ?>">
	<?php
		if( !empty( $atts['title'] ) ) {
			printf('<h3 class="title-style-3">%s</h3>', esc_html( $atts['title'] ) );
		}
	?>
	<div class="timeline-container">
		<div class="timeline timeline-hotel-view">
				<?php $model->render_list($html_options);?>
		</div>
	</div>
</div>
<?php
// custom css
$custom_css = '';
if ( !empty($atts['btn_book_color']) ){
	$custom_css .= sprintf('.sc-%s .hotels-layout .content-wrapper .content .group-btn-tours a{color:%s;}', esc_attr($id), esc_attr($atts['btn_book_color']));
}
if ( !empty($atts['btn_book_bg_color']) ){
	$custom_css .= sprintf('.sc-%s .hotels-layout .content-wrapper .content .group-btn-tours{background-color:%s;}', esc_attr($id), esc_attr($atts['btn_book_bg_color']));
	$custom_css .= sprintf('.sc-%s .hotels-layout .content-wrapper .content .group-btn-tours:hover a{color:%s;}', esc_attr($id), esc_attr($atts['btn_book_bg_color']));
}
if ( !empty($atts['btn_book_hover_bg_color']) ){
	$custom_css .= sprintf('.sc-%s .hotels-layout .content-wrapper .content .group-btn-tours:hover{background-color:%s;}', esc_attr($id), esc_attr($atts['btn_book_hover_bg_color']));
}
if( $custom_css ) {
	do_action( 'slzexploore_core_add_inline_style', $custom_css );
}
?>