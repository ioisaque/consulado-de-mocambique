<?php
$model = new Slzexploore_Core_Accommodation();
$atts['columns'] = absint($atts['columns']);
if( empty($atts['columns']) ) {
	$atts['columns'] = 2;
}
$model->init($atts);
// 1$ - img, 2$ - price, 3$ - title, 4$ - star, 5$ - content, 6$ - social, 7$ - responsive
$html_format = '<div class="%7$s">
					<div class="hotels-layout">
						<div class="image-wrapper">
							%1$s
							%2$s
							%9$s
							<div class="title-wrapper">
								%3$s
								%4$s
							</div>
						</div>
						<div class="content-wrapper %8$s">
							<div class="content">
								%5$s
							</div>
							%6$s
						</div>
					</div>
				</div>';
$html_options = array(
	'html_format'      => $html_format
);
$id = $model->uniq_id;
?>
<div class="hotels-content hotel-result-main result-body slz-shortcode <?php echo esc_attr($atts['extra_class']); ?>">
	<div class="loading">
		<div class='spinner sk-spinner-wave'>
			<div class='rect1'></div><div class='rect2'></div><div class='rect3'></div>
			<div class='rect4'></div><div class='rect5'></div>
		</div>
	</div>
	<div class="main-right f-none hotel-result-content sc-<?php echo esc_attr($id); ?>">
		<div class="hotel-list">
			<div class="row">
				<?php $model->render_list($html_options);?>
			</div>
		</div>
		<?php
			if( !empty( $atts['pagination'] ) ) {
				printf('<div class="hide pagination-json" data-json="%s"></div>',
					esc_attr(json_encode($model->attributes))
				);
				echo Slzexploore_Core_Pagination::paging_ajax( $model->query->max_num_pages, 2, $model->query );
			}
		?>
	</div>
</div>
<?php
// custom css
$custom_css = '';
if ( !empty($atts['bg_color']) ){
	$custom_css .= sprintf('.sc-%s .hotel-list .hotels-layout .content-wrapper{background-color:%s;}', esc_attr($id), esc_attr($atts['bg_color']));
}
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