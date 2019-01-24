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
$display_title  = get_post_meta( $post_id, 'slzexploore_cruise_display_title', true );
$discount       = get_post_meta( $post_id, 'slzexploore_cruise_is_discount', true );
$discount_rate  = get_post_meta( $post_id, 'slzexploore_cruise_discount_rate', true );
$discount_text  = get_post_meta( $post_id, 'slzexploore_cruise_discount_text', true );
$description    = get_post_meta( $post_id, 'slzexploore_cruise_description', true );
$cabin_type     = get_post_meta( $post_id, 'slzexploore_cruise_show_cabin_type', true );
$cabin_type_arr = get_post_meta( $post_id, 'slzexploore_cruise_cabin_type', true );
$star_rating    = get_post_meta( $post_id, 'slzexploore_cruise_star_rating', true );
$info           = get_post_meta( $post_id, 'slzexploore_cruise_meta_info', true );
// attachment cruise
$attachments       = get_post_meta($post_id, 'slzexploore_cruise_attachment_ids', true);
$attach            = Slzexploore_Core_Util::get_single_attachments($attachments);
$all_container_css = slzexploore_get_container_css();
extract($all_container_css);
?>

<div class="cruises-result-detail padding-top padding-bottom">
	<div class="<?php echo esc_attr($container_css);?>">
		<div class="result-body">
			<div class="row">
				<div class="<?php echo esc_attr( $content_css ); ?>">
					<?php
					// Gallery
					$show_gallery = get_post_meta( $post_id, 'slzexploore_cruise_show_gallery', true );
					$gallery_ids = get_post_meta( $post_id, 'slzexploore_cruise_gallery_ids', true );
					$gallery_ids = rtrim( str_replace(',,', ',', $gallery_ids), ',' );
					if( !empty( $show_gallery ) && !empty( $gallery_ids ) ){
						$gallery_shortcode = sprintf('[slzexploore_core_gallery_sc style="image_slider" images="%1$s" arrows="true" layout_extra="2"]',
											$gallery_ids );
						echo do_shortcode( $gallery_shortcode );
					}?>
					<div class="wrapper-timeline">
						<div class="car-rent-layout">
							<div class="content-wrapper">
								<?php
								$html_discount = '';
								if( !empty( $discount ) && !empty( $discount_rate ) ) {
									if( empty( $discount_text ) ){
										$discount_text = esc_html__( 'Sale Off', 'exploore' );
									}
									$html_discount = sprintf( '<span> (%1$s %2$s)</span>',
											esc_html( $discount_text ),
											esc_html( $discount_rate ). '%'
											);
								}
								$display_title = apply_filters('get_the_title', 	$display_title );
								printf( '<h3 class="title-style-2 title">%1$s %2$s</h3>',
										esc_html( $display_title ),
										$html_discount
									);
								?>
								<?php
									$show_share = Slzexploore::get_option('slz-cruises-show-share');
									$social_enable  = Slzexploore::get_option('slz-social-share', 'enabled');
									if( $show_share && count( $social_enable ) > 1 ){
								?>
									<div class="btn-share-social">
										<a href="javascript:void(0);" class="btn-share"><i class="icons fa fa-share-alt"></i></a>
										<?php do_action('slzexploore_share_custom_post'); ?>
									</div>
								<?php } ?>
								<?php
								$comment_count = get_comments_number( $post_id );
								$str_rating = '';
								if ( comments_open() || $comment_count ) {
									$review = sprintf( _n('%s', '%s', $comment_count, 'exploore'), $comment_count );
									$rating = slzexploore_get_rating( $post_id );
									if( $rating ) {
										$rating_text = sprintf( esc_html__('Review Ratings: %s', 'exploore'), $rating . '/' . $review );
										$str_rating = sprintf( '<div class="guest-rating">%s</div>', $rating_text );
									}
								}
								if( $star_rating ) {
									$star_rating = sprintf('<div class="stars stars%1$s"><strong class="rating">%1$s</strong></div>', $star_rating);
								}
								if( $str_rating || $star_rating ) {
									echo '<div class="cruises-meta-info star-ratings">' . $star_rating . $str_rating . '</div>';
								}
								if(empty($info )){
									printf('<div class="description">%s</div>',wp_kses_post($description));
								}
								else{
										printf('<div class="description">%s</div>',wp_kses_post($info));
								}
								if(!empty($attach)){
									echo '<div class="attach-single-detail style-attach">
												<h3 class="title-style-3">'. esc_html__( 'Attachments', 'exploore' ) .'</h3>
													'. $attach .'
										</div>';
								}
								?>
							</div>
						</div>
						<?php 
						if ( !empty( $cabin_type ) && !empty( $cabin_type_arr ) ) :
							
							$post_arr = explode(",", $cabin_type_arr);
							$args = array(
								'post_type'        => 'slzexploore_cabin',
								'orderby'          => 'title',
								'posts_per_page'   => -1,
								'post_status'      => 'publish',
								'suppress_filters' => false,
								'post__in'         => $post_arr,
							);
							$records = get_posts( $args );
							$result = array();
							if( $records ) :?>
							<div class="cruises-detail-tag tags-widget">
								<h3 class="title-style-3"><?php echo esc_html__('Cabin Types','exploore');?></h3>
								<div class="content-widget">
								<?php 
									foreach( $records as $row ) {
										$key = empty($row->post_title) ? $row->post_name : $row->post_title;
										$val = $row->ID;
										$result[$key] = $val;
										echo '<a class="tag-item">'.$key.'</a>';
									}
								?>
								</div>
							</div>
							<?php endif;// records?>
						<?php endif;// $cabin_type?>
						<div class="entry-content">
						<?php
							the_content( sprintf( '<a href="%s" class="btn btn-gray btn-fit btn-capitalize">%s</a>',
									esc_url( get_permalink() ),
									esc_html__( 'Read more', 'exploore' )
							) );
							wp_link_pages( array( 'before' => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'exploore' ) . '</span>', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>' ) );
							?>
						</div>
						<?php 
						$booking_active   = Slzexploore::get_option('slz-booking-active', 'enabled');
						if ( isset( $booking_active['cruise'] ) ){
							do_action( 'slzexploore_core_cruise_booking' ); 
						}
					?>
					</div>
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
			if ( Slzexploore::get_option('slz-cruises-show-related') == 1 ){
				// Carousel
				$category_slug = '';
				$tour_categories = get_the_terms ( $post_id, 'slzexploore_cruise_cat' );
				if( !empty( $tour_categories ) ) {
					foreach( $tour_categories as $cat ){
						$slug[] = $cat->slug;
					}
					$category_slug = implode( ',', $slug );
				}
				$carousel_shortcode = sprintf('[slzexploore_core_post_carousel_sc posttype="slzexploore_cruise" limit_post="-1" cate
					gory_slug="%1$s" title="%2$s" extra_class="margin-top70" post__not_in="%3$s"]',
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