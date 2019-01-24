<?php
$model = new Slzexploore_Core_Car();
$layout_class = 'car-rent-layout';
if( isset( $atts['style'] ) && $atts['style'] == 'grid' ) {
	$layout_class = 'car-rent-layout-2';
} else {
	if( empty($atts['columns']) ) {
		$atts['columns'] = 1;
	}
}
if( empty($atts['columns']) ) {
	$atts['columns'] = 2;
}
$model->init( $atts, $query_args );
$html_format = '<div class="%7$s">
					<div class="car-layout '.esc_attr($layout_class).'">
						<div class="image-wrapper">
							%1$s
							%2$s
						</div>
						<div class="content-wrapper">
							%3$s
							%4$s
							%5$s
							%6$s
						</div>
					</div>
				</div>';
$html_options = array(
	'html_format'      => $html_format,
	'open_row'         => '<div class="row">',
	'close_row'        => '</div>',
);
$tax_cat      = get_taxonomy( 'slzexploore_car_cat' )->rewrite['slug'];
$tax_location = get_taxonomy( 'slzexploore_car_location' )->rewrite['slug'];
$is_archive = false;
if( strpos($base, $tax_cat) !== false || strpos($base, $tax_location) !== false ){
	$is_archive = true;
}
if( $model->post_count <= 0 ) {
	esc_html_e( 'Sorry, but nothing matched your search filters. Please try again with different search filters.', 'slzexploore-core' );
	$model->display_results_found( $data, $is_archive );
	printf('<div class="hide slz-pagination-base" data-base="%s"></div>', esc_attr($base) );
	exit;
}
?>
<div class="car-rent-list">
	<div class="row">
		<?php $model->render_list($html_options);?>
	</div>
</div>
<?php
	$model->display_results_found( $data, $is_archive );
	printf('<div class="hide pagination-json" data-json="%s" data-search="%s"></div>',
		esc_attr(json_encode($model->attributes)),
		esc_attr(json_encode($data))
	);
	echo Slzexploore_Core_Pagination::paging_ajax( $model->query->max_num_pages, 2, $model->query, $base );
?>