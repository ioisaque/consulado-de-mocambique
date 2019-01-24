<?php if( ! SLZEXPLOORE_CORE_IS_ACTIVE ) exit; ?>
<?php get_header();?>
<?php
	$cruise_filter        = Slzexploore::get_option( 'slz-cruises-filter', 'enabled');
	$cruise_result_count  = Slzexploore::get_option( 'slz-cruises-result-count' );
	$cruise_sort_by       = Slzexploore::get_option( 'slz-cruises-list-sort' );
	// result-meta
	$show_result = false;
	$show_sort = false;
	$result_class = 'col-md-7';
	$sort_class = 'col-md-5';
	if( $cruise_result_count ) {
		$show_result = true;
	}
	else{
		$sort_class = 'col-md-12';
	}
	if( $cruise_sort_by ) {
		$show_sort = true;
	}
	else{
		$result_class = 'col-md-12';
	}
?>
<div class="cruises-result-main padding-top padding-bottom">
	<div class="loading">
		<div class='spinner sk-spinner-wave'>
			<div class='rect1'></div><div class='rect2'></div><div class='rect3'></div>
			<div class='rect4'></div><div class='rect5'></div>
		</div>
	</div>
	<div class="container">
		<?php
		// css to show/hide sidebar.
		$all_container_css = slzexploore_get_container_css();
		extract($all_container_css);
		$columns = 2;
		if( $show_sidebar == 'none' ) {
			$columns = 3;
		}
		
		$atts = array();
		$is_archive = false;
		// is taxonomy page
		$queried_object = get_queried_object();
		if( is_tax( 'slzexploore_cruise_cat' ) ){
			$atts['category_slug'] = $queried_object->slug;
			$_GET['category'] = $queried_object->slug;
			$is_archive = true;
		}
		if( is_tax( 'slzexploore_cruise_location' ) ){
			$atts['location_slug'] = $queried_object->slug;
			$_GET['location'] = $queried_object->slug;
			$is_archive = true;
		}
		if( is_tax( 'slzexploore_cruise_facility' ) ){
			$atts['facility_slug'] = $queried_object->slug;
			$_GET['facility'] = $queried_object->slug;
			$is_archive = true;
		}
		$atts['pagination'] = 'yes';
		$atts['btn_book'] = esc_html__( 'Book Now', 'exploore' );
		$atts['columns'] = $columns;
		$atts['limit_post'] = Slzexploore::get_option('slz-cruises-posts');
		$query_args = array();
		$model = new Slzexploore_Core_Cruise();
		// get data from SC Search
		if( isset( $_GET['search-cruise'] ) ) {
			$model->get_search_atts( $atts, $query_args, $_GET );
		}
		$model->init( $atts, $query_args );
		
		$html_format = '<div class="%7$s">
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
		?>
		<div class="result-body">
			<div class="row">
				<div id="page-content" class="<?php echo esc_attr( $content_css ); ?>">
					<?php if( $show_result || $show_sort ): ?>
					<div class="result-meta row">
						<?php
							if( $show_result ){
								printf('<div class="%1$s"><div class="result-count-wrapper"></div></div>',
										esc_attr( $result_class )
									);
							}
							if( $show_sort ){
								printf('<div class="%1$s"><div class="result-filter-wrapper">', esc_attr( $sort_class ) );
								do_action('slzexploore_core_result_filter', 'cruise');
								echo '<div class="hide slz-archive-column" data-col="'.esc_attr($atts['columns']).'"></div>';
								echo '</div></div>';
							}
						?>
					</div>
					<?php endif; ?>
					<div class="cruises-result-content">
					<?php
						if( $model->post_count <= 0 ) {
							esc_html_e( 'Sorry, but nothing matched your search filters. Please try again with different search filters.', 'exploore' );
							$model->display_results_found( $_GET, $is_archive );
							if( !isset( $base ) ){
								global $wp;
								$base = $wp->request;
							}
							printf('<div class="hide slz-pagination-base" data-base="%s"></div>', esc_attr($base) );
						}
						else{
					?>
						<div class="cruises-list">
							<div class="row">
								<?php $model->render_list($html_options);?>
							</div>
						</div>
						<?php
							$model->display_results_found( $_GET, $is_archive );
							printf('<div class="hide pagination-json" data-json="%s" data-search="%s"></div>',
								esc_attr(json_encode($model->attributes)),
								esc_attr(json_encode($_GET))
							);
							echo Slzexploore_Core_Pagination::paging_ajax( $model->query->max_num_pages, 2, $model->query );
						?>
					<?php } ?>
					</div>
				</div>
				<?php if ( $show_sidebar != 'none' ) :?>
				<div id='page-sidebar' class="sidebar sidebar-widget <?php echo esc_attr( $sidebar_css ); ?> ">
					<?php
						$sidebar_content = Slzexploore::get_option('slz-cruises-archive-sidebar-content');
						if( $sidebar_content ){
							slzexploore_get_sidebar($sidebar_id);
						}
						else{
							echo do_shortcode('[slzexploore_core_cruise_search_sc]');
						}
					?>
				</div>
				<?php endif;?>
			</div>
		</div>
	</div>
</div>
<?php get_footer(); ?>