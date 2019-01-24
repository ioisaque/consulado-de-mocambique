<?php
/**
 * The template for displaying archive pages.
 * 
 * @author Swlabs
 * @package Exploore
 * @since 1.0
 */
$template = slzexploore_is_custom_post_type_archive();
if( $template ) {
	get_template_part( 'inc/template-search', $template );
}
else{
	do_action('slzexploore_show_index');
}