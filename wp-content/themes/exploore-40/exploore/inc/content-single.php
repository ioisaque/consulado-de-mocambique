<?php
/**
 * The default template for displaying content
 *
 * Used for single.
 *
 * @author Swlabs
 * @package Exploore
 * @since 1.0
 */
?>
<div <?php post_class('blog-post blog-text'); ?> >
	<!-- thumbnail -->
	<?php do_action( 'slzexploore_entry_video');?>
	<div class="blog-content margin-bottom70">
		<div class="col-xs-1">
			<div class="row">
				<?php echo slzexploore_post_date();?>
			</div>
		</div>
		<div class="col-xs-11 blog-text">
			<!-- title -->
			<?php
				if ( is_single() ) :
					the_title( '<h1 class="heading heading-title">', '</h1>' );
				else :
					the_title( sprintf( '<h1><a class="heading heading-title" href="%s">', esc_url( slzexploore_get_link_url() ) ), '</a></h1>' );
				endif;
			?>
			<?php do_action('slzexploore_entry_meta');?>
			<?php do_action('slzexploore_share_link');?>
			<div class="blog-descritption entry-content">
				<?php
					the_content( sprintf( '<a href="%s" class="btn btn-gray btn-fit btn-capitalize">%s</a>',
							esc_url( get_permalink() ),
							esc_html__( 'Read more', 'exploore' )
					) );
					wp_link_pages( array( 'before' => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'exploore' ) . '</span>', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>' ) );
				?>
			</div>
		</div>
	</div>
	<?php do_action('slzexploore_categories_meta');?>
	<?php do_action('slzexploore_tags_meta');?>
	<?php
		$authorbox = Slzexploore::get_option('slz-authorbox');
		if( is_single()  && $authorbox && get_the_author_meta( 'description' ) ){
			do_action( 'slzexploore_post_author' );
		}
		if ( is_single() && ( comments_open() || get_comments_number() ) ) :
			echo '<div class="entry-comment">';
			comments_template();
			echo '</div>';
		endif;
	?>
</div>