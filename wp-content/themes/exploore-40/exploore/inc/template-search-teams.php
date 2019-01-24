<?php get_header(); ?>
<div class="page-main padding-top padding-bottom">
	<div class="container">
		<div class="row">
			<div class="col-md-12 col-xs-12">
				<?php if( shortcode_exists('slzexploore_core_team_list_sc') ) {
					echo do_shortcode('[slzexploore_core_team_list_sc column="3" limit_post="-1"]');
				}?>
			</div>
		</div>
	</div>
</div>
<?php get_footer(); ?>