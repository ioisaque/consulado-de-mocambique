<div class="section section-padding page-detail">
	<div class="container">
		<div class="row">
			<div id="page-content" class="col-sm-12">
				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?> >
					<div class="entry-content padding-top padding-bottom">
						<?php echo get_the_password_form( $post );?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php get_footer(); ?>