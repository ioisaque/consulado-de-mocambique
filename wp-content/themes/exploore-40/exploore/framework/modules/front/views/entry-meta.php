<div class="meta-info">
	<?php edit_post_link( esc_html__( 'Edit', 'exploore' ), '<span class="item edit-link"><i class="fa fa-pencil"></i>', '</span><span class="sep">/</span>' ); ?>
	<?php
	$author_url = get_author_posts_url( get_the_author_meta( 'ID' ) );
	if( $author_url ) {
		$author_string = '<span class="author"><span>%3$s</span><a href="%1$s"><span>%2$s</span></a><span class="sep">/</span></span>';
	} else {
		$author_string = '<span>%3$s</span><span>%2$s</span>';
	}
	echo sprintf( 
		$author_string,
		esc_url( $author_url ),
		esc_html( get_the_author_meta( 'display_name' ) ),
		esc_html__('Posted By : ', 'exploore')
	);
	?>
	<?php $view_count = slzexploore_postview_get( get_the_ID() );?>
	<span class="view-count fa-custom"><?php printf( _n('%s', '%s', esc_html($view_count), 'exploore'), esc_html($view_count) ); ?></span>
	<?php if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) : ?>
	<span class="comment-count fa-custom"><?php comments_popup_link('0', '1', '%');?></span>
	<?php endif;?>
</div>