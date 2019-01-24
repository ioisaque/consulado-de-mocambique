<?php
if( ! SLZEXPLOORE_CORE_IS_ACTIVE ) exit;
get_header();
while ( have_posts() ) :
	the_post();
	if ( post_password_required() ) {
		get_template_part( 'inc/content-password' );
		wp_reset_postdata();
		return;
	}
	
$post_id        = get_the_ID();
$display_title  = get_post_meta( $post_id, 'slzexploore_car_display_title', true );
$discount_text  = get_post_meta( $post_id, 'slzexploore_car_discount_text', true );
// attachment car
$attachments       = get_post_meta($post_id, 'slzexploore_car_attachment_ids', true);
$attach            = Slzexploore_Core_Util::get_single_attachments($attachments);

$all_container_css = slzexploore_get_container_css();
extract($all_container_css);

?>
<div class="car-detail-main padding-top padding-bottom">
	<div class="<?php echo esc_attr($container_css);?>">
		<div class="wrapper-car-detail">
			<div class="row">
				<div class="<?php echo esc_attr( $content_css ); ?>">
					<?php
					// Gallery
					$gallery_ids = get_post_meta( $post_id, 'slzexploore_car_gallery_ids', true );
					$gallery_ids = rtrim( str_replace(',,', ',', $gallery_ids), ',' );
					if( !empty( $gallery_ids ) ){
						$gallery_shortcode = sprintf('[slzexploore_core_gallery_sc style="image_slider" images="%1$s" arrows="true" layout_extra="2"]',
											$gallery_ids );
						echo do_shortcode( $gallery_shortcode );
					}?>
					<div class="car-rent-layout">
						<div class="content-wrapper">
						<?php
							$html_discount = '';
							$model = new Slzexploore_Core_Car();
							$check_date = date('Y-m-d');
							$discount_rate = $model->get_discount_number( $post_id, $check_date );
							if( !empty( $discount_rate ) ) {
								if( empty( $discount_text ) ){
									$discount_text = esc_html__( 'Sale Off', 'exploore' );
								}
								$html_discount = sprintf( '<span> (%1$s %2$s)</span>',
										esc_html( $discount_text ),
										esc_html( $discount_rate ). '%'
										);
							}
							$display_title = apply_filters('get_the_title',$display_title );
							printf( '<h3 class="title-style-2 title">%1$s %2$s</h3>',
									esc_html( $display_title ),
									$html_discount
								);
							?>

							<?php
								$show_share = Slzexploore::get_option('slz-car-show-share');
								$social_enable  = Slzexploore::get_option('slz-social-share', 'enabled');
								if( $show_share && count( $social_enable ) > 1 ){ ?>
									<div class="btn-share-social">
										<a href="javascript:void(0);" class="btn-share"><i class="icons fa fa-share-alt"></i></a>
										<?php do_action('slzexploore_share_custom_post'); ?>
									</div>
							<?php } ?>

							<?php
							$comment_count = get_comments_number( $post_id );
							if ( comments_open() || $comment_count ) {
								$review = sprintf( _n('%s', '%s', $comment_count, 'exploore'), $comment_count );
								$rating = slzexploore_get_rating( $post_id );
								if( $rating ) {
									$rating_text = sprintf( esc_html__('Review Ratings: %s', 'exploore'), $rating . '/' . $review );
									$rating = printf( '<div class="car-meta-info star-ratings">
											<div class="guest-rating">%s</div></div>', $rating_text);
								}
							}
							?>
							
							<!-- content -->
							<div class="entry-content description">
							<?php
								the_content( sprintf( '<a href="%s" class="btn btn-gray btn-fit btn-capitalize">%s</a>',
										esc_url( get_permalink() ),
										esc_html__( 'Read more', 'exploore' )
								) );
								wp_link_pages( array( 'before' => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'exploore' ) . '</span>', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>' ) );
								?>
							</div>
							<?php if(!empty($attach)){?>
								<div class="attach-single-detail style-attach">
									<h3 class="title-style-3"><?php esc_html_e( 'Attachments', 'exploore' ) ?></h3>
									<?php printf( '%s', $attach); ?>
								</div>
							<?php }?>
						</div>
					</div>
					<?php 
						$booking_active   = Slzexploore::get_option('slz-booking-active', 'enabled');
							if ( isset( $booking_active['car'] ) ){
							do_action( 'slzexploore_core_car_booking' ); 
						}
					?>
					<?php
					// comments and ratings
					if ( is_single() && ( comments_open() || get_comments_number() ) ){
						echo '<div class="entry-comment entry-page-comment">';
							comments_template();
						echo '</div>';
					}?>
				</div>
				<?php if ( $show_sidebar != 'none' ) : ?>
				<div id='page-sidebar' class="<?php echo esc_attr( $sidebar_css ); ?> sidebar-widget">
					<?php slzexploore_get_sidebar($sidebar_id);?>
				</div>
				<?php endif;?>
			</div>
		</div>
	</div>
	<div class="container">
			<?php
			if ( Slzexploore::get_option('slz-car-show-related') == 1 ){
				// Carousel
				$category_slug = '';
				$car_categories = get_the_terms ( $post_id, 'slzexploore_car_cat' );

				if( !empty( $car_categories  ) ) {
					foreach( $car_categories  as $cat ){
						$slug[] = $cat->slug;
					}
					$category_slug = implode( ',', $slug );
				}
				$carousel_shortcode = sprintf('[slzexploore_core_post_carousel_sc posttype="slzexploore_car" limit_post="-1" category_slug="%1$s" title="%2$s" extra_class="margin-top70" post__not_in="%3$s"]',
										esc_attr( $category_slug ),
										esc_html__( 'You Will Also Like', 'exploore' ),
										esc_attr( $post_id )
									);
				echo do_shortcode( $carousel_shortcode );
			}
		?>
	</div>
</div>
<?php
endwhile;
wp_reset_postdata();
?>
<?php get_footer(); ?>