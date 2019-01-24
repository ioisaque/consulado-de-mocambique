<?php
/**
 * Index
 * 
 * @author Swlabs
 * @package Exploore
 * @since 1.0
 */
// css to show/hide sidebar.
$all_container_css = slzexploore_get_container_css();
extract($all_container_css);
get_header();

?>
<div class="page-main padding-top padding-bottom" >
	<div class="<?php echo esc_attr( $container_css );?>">
		<div class="row">
			<div id="page-content" class=" blog-content <?php echo esc_attr( $content_css ); ?>">
				<?php if ( have_posts() ) : ?>
				<div class="item-blog-detail section-page-content">
					<div class="section-content">
						<!-- The loop -->
						<?php while ( have_posts() ) : the_post(); ?>
							<?php get_template_part( 'inc/content' ); ?>
						<?php endwhile; ?>
					</div>
					<div class="clearfix"></div>
					<?php echo slzexploore_paging_nav(); ?>
				</div>
				<?php else : ?>
					<?php get_template_part( 'inc/content', 'none' ); ?>
				<?php endif; ?>
			</div>
			<?php if ( $show_sidebar != 'none' ) : ?>
			<div id='page-sidebar' class="sidebar-widget <?php echo esc_attr( $sidebar_css ); ?>">
				<?php slzexploore_get_sidebar($sidebar_id);?>
			</div>
			<?php endif; ?>
		</div>
	</div>
</div>
<?php get_footer();?>