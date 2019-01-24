<?php
/**
 * The default template for displaying content
 *
 * Used for both single and index/archive.
 *
 * @author Swlabs
 * @package Exploore
 * @since 1.0
 */
?>
<div id="post-<?php the_ID(); ?>" <?php post_class( 'blog-post' ); ?>>
	<!-- thumbnail -->
	<?php do_action( 'slzexploore_entry_thumbnail');?>
	<div class="blog-content">
		<div class="col-xs-2">
			<div class="row">
				<?php echo slzexploore_post_date();?>
			</div>
		</div>
		<div class="col-xs-10 blog-text">
			<!-- title -->
			<?php
				if ( is_single() ) :
					the_title( '<h1 class="heading heading-title">', '</h1>' );
				else :
					the_title( sprintf( '<h1 class="heading heading-title"><a href="%s">', esc_url( slzexploore_get_link_url() ) ), '</a></h1>' );
				endif;
			?>
			<?php do_action('slzexploore_entry_meta');?>
			<?php if ( has_excerpt() ) : ?>
				<div class="entry-summary">
					<?php the_excerpt(); ?>
				</div><!-- .entry-summary -->
			<?php endif;?>
			<div class="blog-descritption entry-content margin-bottom70">
				<?php
					the_content( sprintf( '<a href="%s" class="btn btn-gray btn-fit btn-capitalize">%s</a>',
							esc_url( get_permalink() ),
							esc_html__( 'Read more', 'exploore' )
					) );
					wp_link_pages( array( 'before' => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'exploore' ) . '</span>', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>' ) );
				?>
			</div><?php
			do_action('slzexploore_categories_meta');
			do_action('slzexploore_tags_meta');?>
		</div>
	</div>
	<?php if( is_single() ):?>
		<?php do_action( 'slzexploore_post_author' );?>
	<?php endif;?>
</div>