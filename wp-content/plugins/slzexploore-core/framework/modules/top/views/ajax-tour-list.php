<?php
$model = new Slzexploore_Core_Tour();
$model->init( $atts, $query_args );
$html_format = '<div class="%8$s">
					<div class="tours-layout">
						<div class="image-wrapper">
							%1$s
							%2$s
							%3$s
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
$tax_tour_cat      = get_taxonomy( 'slzexploore_tour_cat' )->rewrite['slug'];
$tax_tour_location = get_taxonomy( 'slzexploore_tour_location' )->rewrite['slug'];
$is_archive = false;
if( strpos($base, $tax_tour_cat) !== false || strpos($base, $tax_tour_location) !== false ){
	$is_archive = true;
}
if( $model->post_count <= 0 ) {
	esc_html_e( 'Sorry, but nothing matched your search filters. Please try again with different search filters.', 'slzexploore-core' );
	$model->display_results_found( $data, $is_archive );
	printf('<div class="hide slz-pagination-base" data-base="%s"></div>', esc_attr($base) );
	exit;
}
?>
<div class="tours-list">
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