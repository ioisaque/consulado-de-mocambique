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
$post_id           = get_the_ID();
$display_title     = get_post_meta( $post_id, 'slzexploore_hotel_display_title', true );
$email             = get_post_meta( $post_id, 'slzexploore_hotel_email', true );
$phone             = get_post_meta( $post_id, 'slzexploore_hotel_phone', true );
$address           = get_post_meta( $post_id, 'slzexploore_hotel_address', true );
$location          = get_post_meta( $post_id, 'slzexploore_hotel_location', true );
$discount_text     = get_post_meta( $post_id, 'slzexploore_hotel_discount_text', true );
$disable_room_type = get_post_meta( $post_id, 'slzexploore_hotel_disable_room_type', true );
// attachment accommodation
$attachments       = get_post_meta($post_id, 'slzexploore_hotel_attachment_ids', true);
$attach            = Slzexploore_Core_Util::get_single_attachments($attachments);
// css to show/hide sidebar.
$all_container_css = slzexploore_get_container_css();
extract($all_container_css);
?>
<div class="hotel-view-main padding-top padding-bottom entry-content">
	<div class="<?php echo esc_attr($container_css);?>">
		<div class="journey-block">
			<div class="row">
				<div id="page-content" class="<?php echo esc_attr( $content_css ); ?>">
					<?php
						$html_discount = '';
						$model = new Slzexploore_Core_Accommodation();
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
						$display_title = apply_filters('get_the_title', $display_title );
						printf( '<h3 class="title-style-2">%1$s %2$s</h3>',
								esc_html( $display_title ),
								$html_discount
							);
					?>
					<?php
						$social_enable  = Slzexploore::get_option('slz-social-share', 'enabled');
						$hotel_list_info = Slzexploore::get_option('slz-hotel-list-info', 'enabled');
						if( isset( $hotel_list_info['share'] ) && count( $social_enable ) > 1 ){
					?>
						<div class="tours-layout">
							<div class="content-wrapper">
								<ul class="list-info list-inline list-unstyle">
									<li class="share">
										<a href="javascript:void(0);" class="link"><i class="icons fa fa-share-alt"></i></a>
										<?php do_action('slzexploore_share_custom_post'); ?>
									</li>
								</ul>
							</div>
						</div>
					<?php } ?>
					<div class="entry-content">
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
				<?php if ( $show_sidebar != 'none' ) : ?>
					<div id='page-sidebar' class="sidebar sidebar-widget <?php echo esc_attr( $sidebar_css ); ?> ">
						<?php slzexploore_get_sidebar($sidebar_id);?>
					</div>
				<?php endif;?>
			</div>
			<?php
				if( !empty( $disable_room_type ) ){
					$room_id = get_post_meta ( $post_id, 'slzexploore_hotel_room_type', true );
					$shortcode = sprintf('[slzexploore_core_room_type_sc room_id="%1$s" btn_book="%2$s" title="%3$s"]',
								esc_attr($room_id),
								esc_html__( 'Book Now', 'exploore' ),
								esc_html__( 'Hotel Overview', 'exploore' )
							);
					echo do_shortcode( $shortcode );
				}
			?>
		</div>
	</div>
	<div class="map-block">
		<?php if ( Slzexploore::get_option('slz-hotel-show-map') == 1 ): ?>
		<div class="map-info">
			<h3 class="title-style-2"><?php esc_html_e( 'Contact Us', 'exploore' ) ?></h3>
			<p class="address"><i class="fa fa-map-marker"></i><?php echo esc_html( $address ); ?></p>
			<p class="phone"><i class="fa fa-phone"></i><?php echo esc_html( $phone ); ?></p>
			<p class="mail">
				<?php
					printf('<a href="mailto:%1$s"> <i class="fa fa-envelope-o"></i>%1$s</a>', $email );
				?>
			</p>
			<div class="footer-block"><a class="btn btn-open-map"><?php esc_html_e( 'Open Map', 'exploore' ) ?></a></div>
		</div>
		<?php
			$maps_data = array( 'address' => $address, 'lat' => '', 'lng' => '' );
			if( !empty( $location ) ){
				$location_arr = explode( ',', $location );
				if( count( $location_arr ) >= 2 ) {
					$lat = $location_arr[0];
					$lng = $location_arr[1];
					$maps_data = array( 'address' => $address, 'lat' => $lat, 'lng' => $lng );
				}
			}
			printf( '<div id="googleMap" data-img-url="%1$s" data-json="%2$s"></div>',
					SLZEXPLOORE_CORE_MAP_MAKER,
					htmlentities( json_encode($maps_data), ENT_QUOTES, "utf-8" )
				);
		?>
		<?php endif; ?>
	</div>
	<div class="container">
		<?php
			// comments and ratings
			if ( is_single() && ( comments_open() || get_comments_number() ) ){
				echo '<div class="entry-comment entry-page-comment">';
					comments_template();
				echo '</div>';
			}
			// Carousel Related Post
			if ( Slzexploore::get_option('slz-hotel-show-related') == 1 ){
				$category_slug = '';
				$hotel_categories = get_the_terms ( $post_id, 'slzexploore_hotel_cat' );
				if( !empty( $hotel_categories ) ) {
					foreach( $hotel_categories as $cat ){
						$slug[] = $cat->slug;
					}
					$category_slug = implode( ',', $slug );
				}
				$carousel_shortcode = sprintf('[slzexploore_core_post_carousel_sc posttype="slzexploore_hotel" limit_post="-1" category_slug="%1$s" title="%2$s" extra_class="margin-top70" post__not_in="%3$s"]',
										esc_attr( $category_slug ),
										esc_html__( 'special offer', 'exploore' ),
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