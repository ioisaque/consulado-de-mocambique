<div class="slz-shortcode <?php echo esc_attr($atts['block_id']) . ' ' .esc_attr($atts['extra_class']); ?>" data-id="<?php echo esc_attr($atts['block_id'])?>">
<?php
$active_slug = '';
if( is_tax('slzexploore_tour_cat')) {
	$active_term = $GLOBALS['wp_query']->get_queried_object();
	$active_slug = $active_term->slug;
}

$args = array();
if( !empty( $atts['category_slug'] ) ) {
	$args['slug'] = $atts['category_slug'];
}
$categories = Slzexploore_Core_Com::get_tax_options2name( 'slzexploore_tour_cat', array(), $args );
if( !empty( $categories ) ) {
	$format = '<div class="list-continent-wrapper"><a href="%1$s" class="continent %4$s" data-slug="%3$s">
					<i class="icons fa fa-map-marker"></i><span class="text">%2$s</span>
				</a>
				<div class="des">%5$s</div>
				</div>';
	echo '<div class="list-continents">';
	foreach( $categories as $slug=>$name ){
		$link = get_term_link( $slug, 'slzexploore_tour_cat' );
		$term = get_term_by( 'slug', $slug, 'slzexploore_tour_cat');
		$class = '';
		if( $active_slug == $slug ) {
			$class = 'active';
		}
		printf( $format,
				esc_url( $link ),
				esc_html( $name ),
				esc_attr( $slug ),
				$class,
				wp_kses_post( nl2br( $term->description ))
			);
	}
	echo '</div>';
}
?>
</div>