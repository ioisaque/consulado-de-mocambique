<?php
$model = new Slzexploore_Core_Block;
$model->init( $atts, $content );
$block_cls = $model->attributes['extra_class'] . ' ' . $model->attributes['block-class'];
$button = $class = $title_one = $title_two = $title_group = $attr_autoplay = $attr_autospeed = $attr_speed = '';

if ( !empty( $atts['title_one'] ) ) {
	$title_one = '<div class="sub-title"><p class="text">'.esc_attr( $atts['title_one'] ).'</p><i class="icons flaticon-people"></i></div>';
}
if ( !empty( $atts['title_two'] ) ) {
	$title_two = '<h2 class="main-title">'.esc_attr( $atts['title_two'] ).'</h2>';
}
if ( !empty( $atts['title_one'] ) || !empty( $atts['title_two'] ) ) {
	$title_group = '
	    <div class="group-title">
	        '.$title_one.'
	        '.$title_two.'
	    </div>';
}
if ( !empty( $atts['auto_play'] ) && $atts['auto_play'] == 'yes') {
	$attr_autoplay = 'data-autoplay="true"';
}
if ( !empty( $atts['auto_speed'] ) ) {
	$attr_autospeed = 'data-autospeed='.esc_attr(intval($atts['auto_speed'])).'';
}
if ( !empty( $atts['speed'] ) ) {
	$attr_speed = 'data-speed='.esc_attr(intval($atts['speed'])).'';
}
if ( !empty( $atts['button_text'] ) ){
	$button = '<a href="%5$s" class="btn btn-maincolor">'.esc_attr( $atts['button_text'] ).'</a>';
}
// 1: blog-image/video, 2: meta date/author, 3: title, 4: description, 5: permarlink, 6: class, 7: tags
$html_format = '	
    <div class="new-layout %6$s">
        %1$s
        <div class="content-wrapper">
        	%3$s
            %2$s
            <div class="text">%4$s</div>
            '.$button.'
            %7$s
        </div>
    </div>
';
$custom_css = "";

if ( !empty( $atts['button_color'] )){
	$custom_css .= '.'.$model->attributes['block-class'].' .btn.btn-maincolor {background-color:'.esc_attr( $atts['button_color'] ).';}';
}
if ( !empty( $atts['button_text_color'] )){
	$custom_css .=  '.'.$model->attributes['block-class'].' .btn.btn-maincolor {color:'.esc_attr( $atts['button_text_color'] ).';}';
}
if ( !empty( $atts['button_hv_color'] )){
	$custom_css .=  '.'.$model->attributes['block-class'].' .btn.btn-maincolor:hover {background-color:'.esc_attr( $atts['button_hv_color'] ).';}';
}
if ( !empty( $atts['button_text_hv_color'] )){
	$custom_css .=  '.'.$model->attributes['block-class'].' .btn.btn-maincolor:hover {color:'.esc_attr( $atts['button_text_hv_color'] ).';}';
}
if ( !empty( $atts['button_border_color'] )){
	$custom_css .=  '.'.$model->attributes['block-class'].' .btn.btn-maincolor {border-color:'.esc_attr( $atts['button_border_color'] ).';}';
}
if ( !empty( $atts['button_border_hv_color'] )){
	$custom_css .=  '.'.$model->attributes['block-class'].' .btn.btn-maincolor:hover {border-color:'.esc_attr( $atts['button_border_hv_color'] ).';}';
}
if($custom_css) {
	do_action( 'slzexploore_core_add_inline_style', $custom_css );
}

?>

<div class="slz-shortcode news <?php echo esc_attr( $block_cls ) ?>">
	<div class="news-wrapper">
		<?php echo $title_group; ?>
		<div class="news-content margin-top70">
			<div class="news-list" <?php echo $attr_autoplay; ?> <?php echo $attr_autospeed; ?> <?php echo $attr_speed; ?>>
				<?php if ( $model->query->have_posts() ) :?>
					<?php
						$post_options = array(
							'html_format'   => $html_format,
							'image_format'  => '<div class="image-wrapper"><a href="%1$s" class="%3$s">%2$s</a></div>'
						);
						$model->render_recent_news( $post_options );
					?>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
<?php wp_reset_postdata();?>
