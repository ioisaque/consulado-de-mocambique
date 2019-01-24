<?php
if(empty($author_id)) {
	$author_id = get_query_var('author');
}
if( ! $author_id ) {
	return;
}
$extra_class = '';
if( is_singular() ) {
	$extra_class = 'margin-top';
}
$author_url = get_author_posts_url( $author_id );
$userdata = get_userdata($author_id);
$author_role = '';
if( $userdata ){
	$author_role = $userdata->roles[0];
}
$author_desc = get_the_author_meta( 'description', $author_id );
?>
<div class="blog-author margin-bottom <?php  echo esc_attr($extra_class);?>">
	<div class="media blog-author-content">
		<div class="media-left">
			<a class="media-image" href="<?php echo esc_url( $author_url )?>">
				<?php echo wp_kses_post( get_avatar($author_id, 100) ); ?>
			</a>
		</div>
		<div class="media-right">
			<div class="author">
				<a class="name" href="<?php echo esc_url( $author_url )?>"><?php echo get_the_author_meta( 'display_name', esc_attr($author_id) ); ?></a>
			</div>
			<div class="position"><?php echo esc_html( ucfirst($author_role) ) ?></div>
			<?php if($author_desc):?>
			<div class="des"><p><?php echo nl2br( esc_textarea( $author_desc ) ) ?></p></div>
			<?php endif;?>
		</div>
	</div>
</div>