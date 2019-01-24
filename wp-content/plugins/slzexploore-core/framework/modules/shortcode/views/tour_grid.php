<?php
$model = new Slzexploore_Core_Tour();
$atts['columns'] = absint($atts['columns']);
if( empty($atts['columns']) ) {
	$atts['columns'] = 2;
}
$atts['add_custom_css'] = '';
$model->init($atts);
// 1$ - img, 2$ - title, 3$ - discount, 4$ - meta, 5$ - price, 6$ - excerpt, 7$ - button, 8$ - responsive
$html_format = '<div class="%8$s">
					<div class="tours-layout">
						<div class="image-wrapper">
							%1$s
							%2$s
							%3$s
							%9$s
						</div>
						<div class="content-wrapper">
							%4$s
							<div class="content">
								%5$s
								%6$s
								%7$s
							</div>
						</div>
					</div>
				</div>';
$html_options = array(
	'html_format'      => $html_format,
	'open_row'         => '<div class="row">',
	'close_row'        => '</div>',
);
$id = $model->uniq_id;
?>
<div class="tour-result-main result-body slz-shortcode <?php echo esc_attr($atts['extra_class']); ?>">
	<div class="loading">
		<div class='spinner sk-spinner-wave'>
			<div class='rect1'></div><div class='rect2'></div><div class='rect3'></div>
			<div class='rect4'></div><div class='rect5'></div>
		</div>
	</div>
	<div class="main-right f-none tours-result-content <?php echo esc_attr($id); ?>">
        <div class="tours-list column-<?php echo intval( $atts['columns'] ); ?>">
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
if ( !empty($atts['btn_book_color']) ){
	$custom_css .= sprintf('.%s .tours-layout .content-wrapper .content .group-btn-tours a{color:%s;}', esc_attr($id), esc_attr($atts['btn_book_color']));
}
if ( !empty($atts['btn_book_bg_color']) ){
	$custom_css .= sprintf('.%s .tours-layout .content-wrapper .content .group-btn-tours a{background-color:%s;}', esc_attr($id), esc_attr($atts['btn_book_bg_color']));
	$custom_css .= sprintf('.%s .tours-layout .content-wrapper .content .group-btn-tours a:hover{color:%s;}', esc_attr($id), esc_attr($atts['btn_book_bg_color']));
}
if ( !empty($atts['btn_book_hover_bg_color']) ){
	$custom_css .= sprintf('.%s .tours-layout .content-wrapper .content .group-btn-tours a:hover{background-color:%s;}', esc_attr($id), esc_attr($atts['btn_book_hover_bg_color']));
}
if( $custom_css ) {
	do_action( 'slzexploore_core_add_inline_style', $custom_css );
}
?>