<?php
Slzexploore::load_class( 'front.Blog' );
get_header();
$userID = get_query_var('author');
?>
<div class="section section-padding">
	<div class="container">
		<div class="row mbxxl">
			<div id="page-content" class="author-archive col-md-12 col-sm-12 padding-top">
				<?php
				do_action( 'slzexploore_post_author' );
				
				$display_name = get_the_author_meta( 'display_name', $userID);
				$limit_post = get_option('posts_per_page');
				$model = new Slzexploore_Blog;
				$atts = array(
					'layout'      => 'author',
					'pagination'  => 'yes',
					'offset_post' => 0,
					'limit_post'  => $limit_post,
					'author'      => $userID
				);
				$model->init( $atts );
				$block_cls = $model->attributes['block-class'];
				$class = 'col-md-6';
				$html_format = '<div class="blog-item '.esc_attr( $class ).' blog-post %7$s">
						%1$s
						<div class="blog-content">
						    <div class="col-xs-2">
						        <div class="row">%2$s</div>
						    </div>
							<div class="col-xs-10 content-wrapper">
							    %4$s
							    <h5 class="meta-info">%3$s</h5>
							    <p class="preview">%5$s</p>
							    
							</div>
						</div>
					</div>';?>
				<?php if ( $model->query->have_posts() ) :
					printf('<div class="comment-count blog-comment-title sideline">%s %s</div>',
						esc_html( ucfirst($display_name) ),
						esc_html__( 'Articles', 'exploore' )
					);?>
					<div class="slz-shortcode margin-bottom blog <?php echo esc_attr( $block_cls ) ?>">
						<div class="blog-wrapper blog-masonry">
							<?php
								$post_options = array(
									'html_format' => $html_format,
									);
								$model->render_author_list( $post_options );
							?>
						</div>
						<?php
						//paging		
						if( $model->attributes['pagination'] == 'yes' ) {
							printf('<div class="clearfix"></div>%s', $model->paging_nav( $model->query->max_num_pages, 2, $model->query) );
						}
						?>
					</div>
				<?php endif; // have_post?>
			</div>
		</div><!-- // row -->
	</div>
</div>
<?php get_footer(); ?>