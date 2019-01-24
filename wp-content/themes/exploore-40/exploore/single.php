<?php
/**
 * The template for displaying all single posts.
 * 
 * @author Swlabs
 * @package Exploore
 * @since 1.0
 */
$post_type = get_post_type();
$custom_post_type = array(
	'slzexploore_hotel',
	'slzexploore_tour',
	'slzexploore_team',
	'slzexploore_car',
	'slzexploore_cruise' );
if(in_array( $post_type, $custom_post_type ) && SLZEXPLOORE_CORE_IS_ACTIVE ){
	$post_type = str_replace('slzexploore_', '', $post_type);
	get_template_part( 'inc/single', $post_type );
	exit;
}
// css to show/hide sidebar.
$all_container_css = slzexploore_get_container_css();
extract($all_container_css);
$template = '';
/**
 * Start Template
 */
get_header();
?>
<div class="page-main padding-top padding-bottom">
	<div class="<?php echo esc_attr( $container_css ); ?>">
		<div class="row">
			<div id="page-content" class="<?php echo esc_attr( $content_css ); ?>">
				<div class="item-blog-detail">
					<?php if ( have_posts() ) : ?>
						<?php /* Custom post type */ ?>
						<?php if ( $post_type != 'post' && $template ) : ?>
							<div class="section-content blog-detail">
								<?php while ( have_posts() ) : the_post(); ?>
									<?php get_template_part( 'inc/single', $template ); ?>
									<?php
										// If comments are open or we have at least one comment, load up the comment template.
										if ( comments_open() || get_comments_number() ) :
											comments_template();
										endif;
									?>
								<?php endwhile; ?>
							</div>
						<?php else: //single post?>
							<div class="section-content blog-detail">
								<?php /* The loop */ ?>
								<?php while ( have_posts() ) : the_post(); ?>
									<?php get_template_part( 'inc/content-single' ); ?>
								<?php endwhile; ?>
							</div>
							<div class="clear-fix" ></div>
						<?php endif;?>
					<?php else : ?>
						<?php get_template_part( 'inc/content', 'none' ); ?>
					<?php endif; // have_posts?>
				</div>
			</div><!-- #page-content -->
			<?php if ( $show_sidebar != 'none' ) : ?>
				<div id='page-sidebar' class="sidebar-widget <?php echo esc_attr( $sidebar_css )?>">
					<?php slzexploore_get_sidebar($sidebar_id);?>
				</div>
			<?php endif;?>
		</div>
	</div>
</div>
<?php get_footer(); ?>