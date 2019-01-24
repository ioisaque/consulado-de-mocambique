<?php
$model = new Slzexploore_Core_Gallery();
$model->init( $atts );
$extra_class = $model->attributes['extra_class'];
$block_class = $model->attributes['block_class'];
$column = $model->attributes['column'];
$title_gallery = $model->attributes['title'];
$style = $model->attributes['style'];
$arrows =  $model->attributes['arrows'];
$show_container = $atts['show_container'];

$html_format = $html_format_nav = $style_col = $row_begin = $row_end = $row_begin_for = $row_end_for = $row_begin_nav = $row_end_nav = $custom_css = $class_col = '';

if ( $column == 1 ){
	$class_col = 'col-md-12';
}else if ( $column == 2 ){
	$class_col = 'col-md-6';
}else{
	$class_col = 'col-md-6';
}

if ( $style == 'image_grid' ) {
	$percent_col		= 100/$column;
	if (!empty($percent_col)) {
		$custom_css .= '.gallery_'.esc_attr($block_class).' .gallery-widget ul li { width:'.esc_attr( $percent_col ).'%;}';
	}
	$row_begin 			= '<div class="gallery-widget ">
							    <div class="content-widget">
							        <ul class="list-unstyled list-inline gallery-block">';
	$html_format 		= '<li><a href="%2$s" data-fancybox-group="gallery_'.esc_attr($block_class).'" class="thumb fancybox">%1$s</a></li>';
	$row_end 			= '</ul>
						</div>
					</div>';
} elseif ( $style == 'image_slider' ) {

	$row_begin 			= '<div data-arrows= '.$arrows.' class="image-hotel-view-block" data-slider="'.esc_attr($block_class).'">';
	$row_begin_for 		= '<div class="slider-for">';
	$html_format 		= '<div class="item item-cd">%1$s</div>';
	$row_end_for 		= '</div>';
	$row_begin_nav 		= '<div class="slider-nav">';
	$html_format_nav 	= '<div class="item ">%1$s</div>';
	$row_end_nav 		= '</div>';
	$row_end 			= '</div>';
} elseif ( $style == 'masonry_grid' ) {
	if ( !empty($title_gallery) ) {
		$title_gallery = sprintf('<h3 class="title-style-2">%s</h3>', esc_html($title_gallery));
	}
	if ( !empty($show_container) ) {
		$show_container = sprintf('<div class="container">');
	}
	$row_begin 			= '<div class="gallery-block">
						    	'.$show_container.'
						    	'.$title_gallery.'
						        <div class="grid">
						            <div class="grid-sizer"></div>
						            <div class="gutter-sizer"></div>';
	$html_format 		= '<div class="grid-item %2$s">
				                <div class="gallery-image"><a href="%1$s" data-fancybox-group="gallery_'.esc_attr($block_class).'" class="title-hover dh-overlay fancybox"><i class="icons fa fa-eye"></i></a>
				                    <div class="bg"></div>
				                </div>
				            </div>';
	$row_end 			= '</div>
						</div>';
	if ( !empty($show_container) ) {
		$row_end .= '</div>';
	}
} elseif ( $style == 'image_regions' ) {
	$html_format = '
		<div class="a-fact-image">
			<a href="%4$s" %5$s class="icons icons-%2$s"><i class="%1$s"></i></a>
			%3$s
		</div>
	';
	$html_options = array(
		'html_format' => $html_format,
	);
}
if( $custom_css ) {
	do_action( 'slzexploore_core_add_inline_style', $custom_css );
}
?>

<div class="slz-shortcode sc_gallery <?php echo esc_attr($extra_class); ?> gallery_<?php echo esc_attr($block_class); ?>" data-id="gallery_<?php echo esc_attr($block_class); ?>">
	<?php 
		if( $style == 'image_regions' ) : 
			echo '<div class="a-fact-image-wrapper">';
				$model->render_gallery_regions( $html_options );
			echo '</div>';
		else :
	?>
		<?php echo ($row_begin); ?>
		<?php echo ($row_begin_for); ?>
			<?php
				$post_options = array(
					'html_format' => $html_format,
					);
				$model->render_gallery( $post_options );
			?>	
		<?php echo ($row_end_for); ?>

		<?php echo ($row_begin_nav); ?>
			<?php
				$post_options_nav = array(
					'html_format' => $html_format_nav,
					);
				$model->render_gallery( $post_options_nav );
			?>
		<?php echo ($row_end_nav); ?>
		<?php echo ($row_end); ?>
	<?php endif; ?>
</div>