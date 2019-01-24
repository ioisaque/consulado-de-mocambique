<?php
if ( has_post_thumbnail() && ! post_password_required() && ! is_attachment() ) {
	if ( is_singular() ) {
		
		printf( '<div class="blog-image"><a href="%s" class="link">%s</a></div>', 
				esc_url(slzexploore_get_link_url()), get_the_post_thumbnail( get_the_ID(), 'post-thumbnail', array('class' => 'img-responsive') )
			);
	} else {
		if( ! empty($args) ) {
			$icon_date = '';
		}
		printf( '<div class="blog-image"><a href="%s" class="link" >%s</a></div>', esc_url(slzexploore_get_link_url()), get_the_post_thumbnail( get_the_ID(), 'post-thumbnail', array('class' => 'img-responsive') ));
	}
}