<?php
get_header();
while ( have_posts() ) :
	the_post();
	if ( post_password_required() ) {
		get_template_part( 'inc/content-password' );
		wp_reset_postdata();
		return;
	}

$post_type		= get_post_type();
$taxonomy_cat	= $post_type . '_cat';
$prefixMeta		= $post_type .'_';
$post_id		= get_the_ID();
$thumbnail		= get_post_meta( $post_id, $prefixMeta .'thumbnail', true );
$description	= get_post_meta( $post_id, $prefixMeta .'description', true );
$position		= get_post_meta( $post_id, $prefixMeta .'position', true );
$address		= get_post_meta( $post_id, $prefixMeta .'address', true );
$phone			= get_post_meta( $post_id, $prefixMeta .'phone', true );
$email			= get_post_meta( $post_id, $prefixMeta .'email', true );
$skype			= get_post_meta( $post_id, $prefixMeta .'skype', true );
$show_teammate  = Slzexploore::get_option('slz-team-show-teammate');

$social_group	= Slzexploore_Core::get_params( 'teammbox-social');

$category_sc = '';
$getTerm = slzexploore_getTermSimpleByPost( $post_id, $taxonomy_cat );
$term_slug = !empty($getTerm) ? $getTerm['slug'] : '';

// css to show/hide sidebar.
$all_container_css = slzexploore_get_container_css();
extract($all_container_css);
?>
 <div class="tour-view-main padding-top padding-bottom">
	<div class="<?php echo esc_attr($container_css);?>">
		<div class="row">
			<div id="page-content" class="<?php echo esc_attr( $content_css ); ?>">
				<div class="entry-content">

					<div class="wrapper-team-detail">
						<div class="main-team">
							<div class="row">
								<div class="col-md-4 col-sm-4 col-xs-5 padding-col-right">
									<div class="content-team-detail">
										<div class="content-expert">
											<?php the_post_thumbnail( 'large', array( 'class' => 'img-responsive img img-expert' ) ); ?>
											<div class="caption-expert">
												<?php if ( !empty($skype) ) :
												printf( '<div class="item-expert"><i class="icon-expert fa fa-comment-o"></i><a href="skype:%s?chat" class="title" title="%s">Talk to me!</a></div>', $skype, esc_attr('Talk to me!', 'exploore' ), esc_html__('Talk to me!', 'exploore' ) );
												endif; ?>
												<ul class="social list-inline">
													<?php foreach( $social_group as $social => $social_text ):
														$socialValue = get_post_meta( $post_id, $prefixMeta . $social, true );
														if ( !empty($socialValue) ) {
															printf( '<li><a href="%s" class="social-expert"><i class="expert-icon fa fa-%s"></i></a></li>', esc_url($socialValue), esc_attr($social) );
														}
													endforeach; ?>
												</ul>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-8 col-sm-8 col-xs-7 padding-col-left">
									<div class="wrapper-caption-team">
										<div class="wrapper-team-title">
											<?php the_title( '<a href="javascript:void(0)" class="team-title">', '</a>', true ); ?>
											<?php if ( !empty($position) ) :
											printf( '<p class="team-title-small">%s</p>', $position );
											endif; ?>
											<?php if ( !empty($address) ) :
											printf( '<div class="team-title-andress"><i class="team-icon fa fa-map-marker"></i><a href="#" class="item-andress">%s</a></div>', $address );
											endif; ?>
										</div>

										<div class="post-single-content entry-content">
											<?php
												the_content( sprintf( '<a href="%s" class="read-more">%s<i class="fa fa-angle-right"></i></a>',
														esc_url( get_permalink() ),
														esc_html__( 'Read more', 'exploore' )
												) );
												wp_link_pages( array( 'before' => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'exploore' ) . '</span>', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>' ) );
											?>
										</div>
									</div>
								</div>
							</div>
						</div>
						<?php
						if ( !empty($term_slug) && $show_teammate == '1') {
							echo '<div class="padding-top">';
							$category_slug[] = array('category_slug'=>"$term_slug");
							$category_sc = urlencode(json_encode($category_slug));
							$shortcode = sprintf('[slzexploore_core_team_carousel_sc style="2" category_list="%1$s" title="%2$s" post__not_in="%3$s" ]',
									$category_sc,
									esc_html__( 'Teammate', 'exploore' ),
									$post_id
								);
							// print_r($shortcode); die;
							echo do_shortcode( $shortcode );
							echo '</div>';
						}
						?>
					</div>
				</div>
			</div>
			<?php if ( $show_sidebar != 'none' ) : ?>
			<div id='page-sidebar' class="sidebar sidebar-widget <?php echo esc_attr( $sidebar_css ); ?> ">
				<?php slzexploore_get_sidebar($sidebar_id);?>
			</div>
			<?php endif;?>
		</div>
	</div>
</div>
<?php
endwhile;
wp_reset_postdata();
?>
<?php get_footer(); ?>