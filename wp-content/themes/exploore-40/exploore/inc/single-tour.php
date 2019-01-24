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
$display_title  = get_post_meta( $post_id, 'slzexploore_tour_display_title', true );
$discount       = get_post_meta( $post_id, 'slzexploore_tour_is_discount', true );
$discount_rate  = get_post_meta( $post_id, 'slzexploore_tour_discount_rate', true );
$discount_text  = get_post_meta( $post_id, 'slzexploore_tour_discount_text', true );
$start_date     = get_post_meta( $post_id, 'slzexploore_tour_start_date', true );
$end_date       = get_post_meta( $post_id, 'slzexploore_tour_end_date', true );
$duration       = get_post_meta( $post_id, 'slzexploore_tour_duration', true );
$available_seat = get_post_meta( $post_id, 'slzexploore_tour_available_seat', true );

$date_type      = get_post_meta( $post_id, 'slzexploore_tour_date_type', true );
$date_frequency = get_post_meta( $post_id, 'slzexploore_tour_frequency', true );
$date_weekly    = get_post_meta( $post_id, 'slzexploore_tour_weekly', true );
$date_montly    = get_post_meta( $post_id, 'slzexploore_tour_monthly', true );
// attachment tour
$attachments    = get_post_meta($post_id, 'slzexploore_tour_attachment_ids', true);
$attach         = Slzexploore_Core_Util::get_single_attachments($attachments);
$weekly = array(
	'1' => esc_html__('Monday', 'exploore'),
	'2' => esc_html__('Tuesday', 'exploore'),
	'3' => esc_html__('Wednesday', 'exploore'),
	'4' => esc_html__('Thursday', 'exploore'),
	'5' => esc_html__('Friday', 'exploore'),
	'6' => esc_html__('Saturday', 'exploore'),
	'7' => esc_html__('Sunday', 'exploore'),
);
// css to show/hide sidebar.
$all_container_css = slzexploore_get_container_css();
extract($all_container_css);
?>
 <div class="tour-view-main padding-top padding-bottom">
	<div class="<?php echo esc_attr($container_css);?>">
		<div class="row">
			<div id="page-content" class="<?php echo esc_attr( $content_css ); ?>">
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
					$display_title = apply_filters('get_the_title', $display_title );
					printf( '<h3 class="title-style-2">%1$s %2$s</h3>',
							esc_html( $display_title ),
							$html_discount
						);
				?>
				<?php
					$social_enable  = Slzexploore::get_option('slz-social-share', 'enabled');
					$tour_list_info = Slzexploore::get_option('slz-tour-list-info', 'enabled');
					if( isset( $tour_list_info['share'] ) && count( $social_enable ) > 1 ){
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
				<div class="schedule-block">
				<?php
					$schedule_format = '<div class="element">
											<p class="schedule-title">%1$s</p>
											<span class="schedule-content">%2$s</span>
										</div>';
					$date_text = esc_html__('Daily', 'exploore');
					if( !empty($date_type) ) {
						if( $date_frequency == 'weekly' && !empty($date_weekly) ) {
							$date_weekly = explode(',', $date_weekly);
							if( count($date_weekly) < 7 ){
								$arr_dates = array();
								foreach($date_weekly as $val){
									if( isset( $weekly[$val]) ) {
										$arr_dates[] = $weekly[$val];
									}
								}
								$date_text = esc_html__( 'Every ', 'exploore' ).implode(', ', $arr_dates);
							}
						} else if( $date_frequency == 'monthly' && !empty($date_montly) ){
							$arr_order    = array( '1' => 'st', '2' => 'nd', '3' => 'rd' );
							$date_montly = explode(',', $date_montly);
							$arr_monthly = array();
							$date_subfix = '';
							foreach ($date_montly as $val) {
								$monthly_last = substr( $val, -1 );
								if( in_array( $monthly_last, $arr_order ) ){
									$date_subfix = $arr_order[$monthly_last];
								}
								else{
									$date_subfix = 'th';
								}
								$arr_monthly[] = $val.$date_subfix;
							}
							$date_text = implode(', ', $arr_monthly). esc_html__( ' of every month', 'exploore' );
						} else {
							if( !empty( $start_date ) && !empty( $end_date ) ){
								$date_text = date( 'd M', strtotime($start_date) ) . ' - ' . date( 'd M', strtotime($end_date) );
							}
							else if( !empty( $start_date ) ){
								$date_text = date( 'd M', strtotime($start_date) );
							}
							else if( !empty( $end_date ) ){
								$date_text = date( 'd M', strtotime($end_date) );
							}
						}
					}
					if( !empty( $date_text ) ){
						printf( $schedule_format, esc_html__( 'Departure Date', 'exploore' ), esc_attr( $date_text ) );
					}
					if( !empty( $duration ) ){
						printf( $schedule_format, esc_html__( 'Duration', 'exploore' ), esc_attr( $duration ) );
					}
					$view_count = slzexploore_postview_get($post_id);
					$view = sprintf( _n('%s', '%s', $view_count, 'exploore'), $view_count );
					printf( $schedule_format, esc_html__( 'Views', 'exploore' ), $view );
					$comment_count = get_comments_number( $post_id );
					if ( comments_open() || $comment_count ) {
						$review = sprintf( _n('%s', '%s', $comment_count, 'exploore'), $comment_count );
						$rating = slzexploore_get_rating( $post_id );
						if( $rating ) {
							printf( $schedule_format, esc_html__( 'Review Ratings', 'exploore' ), $rating . '/' . $review );
						}
					}
					if( !empty( $available_seat ) ){
						printf( $schedule_format, esc_html__( 'Maximum Seats', 'exploore' ), esc_attr( $available_seat ) );
					}
				?>
				</div>
				<div class="entry-content margin-top70">
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
				<?php 
					}
					$booking_active   = Slzexploore::get_option('slz-booking-active', 'enabled');
					if ( isset( $booking_active['tour'] ) ){
						do_action( 'slzexploore_core_tour_booking' );
					}
				?>
			</div>
			<?php if ( $show_sidebar != 'none' ) : ?>
			<div id='page-sidebar' class="sidebar sidebar-widget <?php echo esc_attr( $sidebar_css ); ?> ">
				<?php slzexploore_get_sidebar($sidebar_id);?>
			</div>
			<?php endif;?>
		</div>
	</div>
	<?php
		// Gallery
		$show_gallery = get_post_meta( $post_id, 'slzexploore_tour_show_gallery', true );
		$gallery_ids = get_post_meta( $post_id, 'slzexploore_tour_gallery_ids', true );
		if( !empty( $show_gallery ) && !empty( $gallery_ids ) ){
			$gallery_title = get_post_meta( $post_id, 'slzexploore_tour_gallery_box_title', true );
			$gallery_shortcode = sprintf('[slzexploore_core_gallery_sc title="%1$s" images="%2$s" extra_class="margin-top70" show_container="1"]',
								esc_attr( $gallery_title ),
								rtrim( $gallery_ids, ',' )
							);
			echo do_shortcode( $gallery_shortcode );
			$gallery_backg = get_post_meta( $post_id, 'slzexploore_tour_gallery_backg', true );
			if( !empty( $gallery_backg ) ) {
				$bg_url = wp_get_attachment_url( $gallery_backg );
				$custom_css = sprintf('.slz-shortcode .gallery-block{ background-image : url(%s); }', esc_url($bg_url) );
				do_action( 'slzexploore_core_add_inline_style', $custom_css );
			}
		}
	?>
	<div class="container">
		<?php
		// Travel Guide
			$show_team = get_post_meta( $post_id, 'slzexploore_tour_show_team', true );
			if( $show_team ) :
		?>
		<div class="expert-block padding-top">
			<div class="row">
				<div class="col-md-6 col-sm-12 col-xs-12">
				<?php
					$team_box_title = get_post_meta( $post_id, 'slzexploore_tour_team_box_title', true );
					$team_box_info = get_post_meta( $post_id, 'slzexploore_tour_team_box_info', true );
					if( !empty( $team_box_title ) ) {
						printf('<h3 class="title-style-2">%s</h3>', esc_attr( $team_box_title ) );
					}
					if( !empty( $team_box_info ) ) {
						echo wp_kses_post( $team_box_info );
					}
				?>
				</div>
				<div class="col-md-6 col-sm-12 col-xs-12">
					<?php
						$team_slug = get_post_meta( $post_id, 'slzexploore_tour_team', true );
						$team_id = Slzexploore_Core_Com::get_post_name2id( $team_slug, 'slzexploore_team' );
						$team_shortcode = sprintf('[slzexploore_core_team_single_sc style="2" data="%1$s"]', esc_attr( $team_id ) );
						echo do_shortcode( $team_shortcode );
					?>
				</div>
			</div>
		</div>
		<?php
			endif;
			// comments and ratings
			if ( is_single() && ( comments_open() || get_comments_number() ) ){
				echo '<div class="entry-comment entry-page-comment">';
					comments_template();
				echo '</div>';
			}
			if ( Slzexploore::get_option('slz-tour-show-related') == 1 ){
				// Carousel
				$category_slug = '';
				$tour_categories = get_the_terms ( $post_id, 'slzexploore_tour_cat' );
				if( !empty( $tour_categories ) ) {
					foreach( $tour_categories as $cat ){
						$slug[] = $cat->slug;
					}
					$category_slug = implode( ',', $slug );
				}
				$carousel_shortcode = sprintf('[slzexploore_core_post_carousel_sc posttype="slzexploore_tour" limit_post="-1" category_slug="%1$s" title="%2$s" extra_class="margin-top70" post__not_in="%3$s"]',
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