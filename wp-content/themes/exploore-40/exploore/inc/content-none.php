<?php
/**
 * The template for displaying a "No posts found" message
 *
 * @author Swlabs
 * @package Exploore
 * @since 1.0
 */
?>

<div>
	<h2 class="title"><?php esc_html_e('We can&rsquo;t find what you&rsquo;re looking for!', 'exploore'); ?></h2>
</div>

<div class="content-none">
	<?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>

	<p><?php printf( '%1$s <a href="%2$s">%3$s</a>.', esc_html__( 'Ready to publish your first post?', 'exploore' ), esc_url( admin_url( 'post-new.php' ) ), esc_html__( 'Get started here' , 'exploore')); ?></p>

	<?php elseif ( is_search() ) : ?>

	<p><?php esc_html_e( 'Please try again with different keywords.', 'exploore' ); ?></p>
	<?php do_action( 'slzexploore_show_searchform' ); ?>

	<?php else : ?>

	<p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'exploore' ); ?></p>
	<?php get_search_form(); ?>

	<?php endif; ?>
	<div class="help-links">
		<?php
		$menu_location = 'page-404-nav' ;
		$locations = get_nav_menu_locations();
		if( isset($locations[$menu_location]) ):?>
			<h3 class="title"><?php esc_html_e('Helpful Links', 'exploore')?></h3>
			<div class="help-links-content row"><?php
				$nav_items = wp_get_nav_menu_items( $locations[$menu_location] );
				if( $nav_items ) {
					$item_columns = array_chunk($nav_items, ceil(count($nav_items) / 3));
					if( $item_columns ) {
						foreach( $item_columns as $columns ) {
							if( $columns ) {
								echo '<div class="col-md-4 col-sm-4">';
									echo '<ul class="list-useful list-unstyled">';
										foreach( $columns as $menu_item ){
											printf('<li><a class="link" href="%1$s">%2$s</a></li>', esc_url($menu_item->url), esc_html($menu_item->title) );
										}
									echo '</ul>';
								echo '</div>';
							}
						}
					}
				}?>
			</div><?php
			endif;
			?>
	</div>
</div>