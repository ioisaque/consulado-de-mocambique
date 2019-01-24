<?php
/**
 * Social Share class.
 * 
 * @since 1.0
 */

class Slzexploore_Core_Social_Share {

	public function __construct() {
		/* Don't do anything, needs to be initialized via instance() method */
	}

	public function get_share_link() {
		$img_url = get_the_post_thumbnail_url( get_the_ID(), 'full' );
		$share_url = array(
			'facebook'		=> sprintf('http://www.facebook.com/sharer.php?u=%s&picture=%s',
									urlencode( esc_url( get_permalink() ) ),
									urlencode( esc_url( $img_url ) )
								),
			'twitter'		=> sprintf('https://twitter.com/intent/tweet?text=%s&url=%s&via=%s',
									htmlspecialchars(urlencode(html_entity_decode(get_the_title(), ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8'),
									urlencode( esc_url( get_permalink() ) ),
									urlencode( get_bloginfo( 'name' ))
								),
			'google-plus'	=> sprintf('http://plus.google.com/share?url=%s',
									urlencode( esc_url( get_permalink() ))
								),
			'pinterest'		=> sprintf('http://pinterest.com/pin/create/button/?url=%s', 
									urlencode( esc_url(get_permalink()) )
								),
			'linkedin'		=> sprintf('http://www.linkedin.com/shareArticle?mini=true&url=%s',
									urlencode( esc_url( get_permalink()) )
								),
			'digg'			=> sprintf('http://digg.com/submit?url=%s&title=%s',
									 urlencode( esc_url(get_permalink())),
									 htmlspecialchars(urlencode(html_entity_decode(get_the_title(), ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8')
								),
		);
		return $share_url;
	}
}