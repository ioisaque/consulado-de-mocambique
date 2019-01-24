<?php if( ! SLZEXPLOORE_CORE_IS_ACTIVE ) exit; ?>
<?php get_header(); ?>
<div class="tour-result-main padding-top padding-bottom">
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
		
		$tour_result_count  = Slzexploore::get_option( 'slz-tour-result-count' );
		$tour_sort_by       = Slzexploore::get_option( 'slz-tour-list-sort' );
		// result-meta
		$show_result = false;
		$show_sort = false;
		$result_class = 'col-md-7';
		$sort_class = 'col-md-5';
		if( $tour_result_count ) {
			$show_result = true;
		}
		else{
			$sort_class = 'col-md-12';
		}
		if( $tour_sort_by ) {
			$show_sort = true;
		}
		else{
			$result_class = 'col-md-12';
		}
		$show_top_cat = Slzexploore::get_option('slz-tour-top-cat');
		if( $show_top_cat ){
			echo do_shortcode( '[slzexploore_core_tour_category_sc extra_class="slz-tour-category"]' );
		}
		$atts       = array();
		$query_args = array();
		$is_archive = false;
		// is taxonomy page
		$queried_object = get_queried_object();
		if( is_tax( 'slzexploore_tour_cat' ) ){
			$atts['category_slug'] = $queried_object->slug;
			$_GET['category'] = $queried_object->slug;
			$is_archive = true;
		}
		if( is_tax( 'slzexploore_tour_location' ) ){
			$atts['location_slug'] = $queried_object->slug;
			$_GET['location'] = $queried_object->slug;
			$is_archive = true;
		}
		$atts['pagination'] = 'yes';
		$atts['columns']    = 2;
		$atts['btn_book']   = esc_html__( 'Book Now', 'exploore' );
		$atts['limit_post'] = Slzexploore::get_option('slz-tour-posts');
		$model = new Slzexploore_Core_Tour();
		if( isset( $_GET['search-tour'] ) ) {
			$model->get_search_atts( $atts, $query_args, $_GET );
		}
		if ( $show_sidebar == 'none' ){
			$atts['columns'] = 3;
		}
		$model->init( $atts, $query_args );
		//
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
		$custom_css = sprintf( '.%1$s .tours-layout .content-wrapper > .content .group-btn-tours .left-btn {border-radius: 50px; padding-right: 25px; border-right: 0;}', $model->uniq_id );
		apply_filters('slzexploore_core_add_inline_style', $custom_css);
		?>
		<div class="result-body <?php echo esc_attr($model->uniq_id); ?>">
			<div class="row">
				<div id="page-content" class="<?php echo esc_attr( $content_css ); ?>">
					<?php if( $show_result || $show_sort ): ?>
					<div class="result-meta row">
					<?php 
						if( $show_result ){
							printf('<div class="%s"><div class="result-count-wrapper"></div></div>',
									esc_attr( $result_class ) );
						}
						if( $show_sort ){
							echo '<div class="'.esc_attr( $sort_class ).'"><div class="result-filter-wrapper">';
							 do_action('slzexploore_core_result_filter', 'tour');
							echo '<div class="hide slz-archive-column" data-col="'.esc_attr($atts['columns']).'"></div>';
							echo '</div></div>';
						}
					?>
					</div>
					<?php endif; ?>
					<div class="tours-result-content">
						<?php
							if( $model->post_count <= 0 ){
								esc_html_e( 'Sorry, but nothing matched your search  filters. Please try again with different search filters.', 'exploore' );
								$model->display_results_found( $_GET, $is_archive );
								if( !isset( $base ) ){
									global $wp;
									$base = $wp->request;
								}
								printf('<div class="hide slz-pagination-base" data-base="%s"></div>', esc_attr($base) );
							}
							else{
						?>
							<div class="tours-list">
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
						$sidebar_content = Slzexploore::get_option('slz-tour-archive-sidebar-content');
						if( $sidebar_content ){
							slzexploore_get_sidebar($sidebar_id);
						}
						else{
							echo do_shortcode('[slzexploore_core_tour_search_sc]');
						}
					?>
				</div>
				<?php endif;?>
			</div>
		</div>
	</div>
</div>
<?php get_footer(); ?>