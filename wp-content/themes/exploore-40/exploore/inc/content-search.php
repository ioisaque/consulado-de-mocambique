<?php
/**
 * The default template for displaying content
 *
 * Used for search.
 *
 * @author Swlabs
 * @package Exploore
 * @since 1.0
 */
?>
<div class="blog-post content-search" id="post-<?php the_ID(); ?>" <?php post_class( 'exploore-item' ); ?>>
	<div class="blog-content no-images">
		<div class="col-xs-2">
			<div class="row">
			   <?php echo slzexploore_post_date();?>
			</div>
		</div>
		<div class="col-xs-10">
		<?php the_title( sprintf( '<a class="heading" href="%s">', esc_url( get_permalink() ) ) , '</a>' ) ;?>
		<?php do_action( 'slzexploore_entry_meta');?>
		<div class="blog-descritption entry-content margin-bottom70">
			<?php  the_excerpt(); ?>
		</div><?php
		do_action('slzexploore_categories_meta');
		do_action('slzexploore_tags_meta');?>
		</div>
	</div>
</div>