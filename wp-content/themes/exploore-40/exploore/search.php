<?php
/**
 * The template for displaying Search Results pages
 * 
 * @author Swlabs
 * @package Exploore
 * @since 1.0
 */
// css to show/hide sidebar.
$all_container_css = slzexploore_get_container_css();
extract($all_container_css);

if(get_post_type() == "page") {
	$css = '
	#wrapper #content-wrapper {
		padding-top: 80px;
		padding-bottom: 80px;
	}';
	do_action( 'slzexploore_add_inline_style', $css);
}
get_header();
?>
<div class="section blog padding-top padding-bottom">
	<div class="<?php echo esc_attr( $container_css );?>">
		<div class="blog-wrapper row">
			<div id="page-content" class="blog-content  <?php echo esc_attr( $content_css ); ?>">
				<div class="search-page">
					<?php if ( have_posts() && strlen( trim(get_search_query()) ) != 0 ) : ?>
						<?php do_action( 'slzexploore_show_searchform' ); ?>
						<div class="news-detail-wrapper">
							<!-- The loop -->
							<?php while ( have_posts() ) : the_post(); ?>
								<?php get_template_part( 'inc/content-search' ); ?>
							<?php endwhile; ?>
							<?php echo slzexploore_paging_nav(); ?>
						</div>
					<?php else : ?>
						<?php get_template_part( 'inc/content', 'none' ); ?>
					<?php endif; ?>
				</div>
			</div>
			<?php if ( $show_sidebar != 'none' ) { ?>
				<div id='page-sidebar' class="sidebar sidebar-widget <?php echo esc_attr( $sidebar_css ); ?>">
					<?php slzexploore_get_sidebar($sidebar_id);?>
				</div>
			<?php } ?>
		</div>
	</div>
</div>
<?php get_footer();?>