<?php
$model = new Slzexploore_Core_Cruise();
$atts['columns'] = absint($atts['columns']);
if( empty($atts['columns']) ) {
	$atts['columns'] = 2;
}
$model->init($atts);
// 1$ - img, 2$ - title, 3$ - discount, 4$ - price, 5$ - excerpt, 6$ - button, 7$ - responsive & post class
$html_format = '
		<div class="%7$s">
			<div class="cruises-layout">
				<div class="image-wrapper">
					%1$s
					%3$s
					%9$s
				</div>
				<div class="content-wrapper">
					<div class="content">
						%2$s
						%4$s
						%8$s
						%5$s
						%6$s
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
if( $model->query->have_posts() ) :?>
<div class="cruises-result-main slz-shortcode <?php echo esc_attr($atts['extra_class']) . ' ' . esc_attr($id); ?>" >
	<div class="loading">
		<div class='spinner sk-spinner-wave'>
			<div class='rect1'></div><div class='rect2'></div><div class='rect3'></div>
			<div class='rect4'></div><div class='rect5'></div>
		</div>
	</div>
	<div class="result-body">
		<div class="cruises-result-content">
            <div class="cruises-list column-<?php echo intval( $atts['columns'] ); ?>">
				<div class="row">
					<?php $model->render_list($html_options);?>
				</div>
			</div><?php
			if( !empty( $atts['pagination'] ) ) {
				printf('<div class="hide pagination-json" data-json="%s"></div>',
					esc_attr(json_encode($model->attributes))
				);
				echo Slzexploore_Core_Pagination::paging_ajax( $model->query->max_num_pages, 2, $model->query );
			}?>
		</div>
	</div>
</div>
<?php endif; // has post
// custom css
$custom_css = '';
if ( !empty($atts['btn_book_color']) ){
	$custom_css .= sprintf('.%1$s .cruises-layout .content-wrapper .content .group-button a{color:%2$s;}', esc_attr($id), esc_attr($atts['btn_book_color']));
}
if ( !empty($atts['btn_book_bg_color']) ){
	$custom_css .= sprintf('.%1$s .cruises-layout .content-wrapper .content .group-button a{background-color:%2$s;border-color:%2$s}', esc_attr($id), esc_attr($atts['btn_book_bg_color']));
	$custom_css .= sprintf('.%1$s .cruises-layout .content-wrapper .content .group-button a:hover{color:%2$s;}', esc_attr($id), esc_attr($atts['btn_book_bg_color']));
}
if ( !empty($atts['btn_book_hover_bg_color']) ){
	$custom_css .= sprintf('.%1$s .cruises-layout .content-wrapper .content .group-button a:hover{background-color:%2$s; border-color:%2$s}', esc_attr($id), esc_attr($atts['btn_book_hover_bg_color']));
}
if( $custom_css ) {
	do_action( 'slzexploore_core_add_inline_style', $custom_css );
}