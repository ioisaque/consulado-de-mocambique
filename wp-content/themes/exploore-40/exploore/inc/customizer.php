<?php
/**
 * Theme Customizer
 *
 * @author Swlabs
 * @package Exploore
 * @since 1.0
 */

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function slzexploore_customize_preview_js() {
	wp_enqueue_script( 'slzexploore_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20130508', true );
}
add_action( 'customize_preview_init', 'slzexploore_customize_preview_js' );