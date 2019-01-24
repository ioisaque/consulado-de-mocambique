<?php
$model = new Slzexploore_Core_Block;
$model->init( $atts, $content );
$block_cls = $model->attributes['extra_class'] . ' ' . $model->attributes['block-class'];
$model->large_image_post = false;
$model->show_full_meta = false;
$column = $model->attributes['column'];
$button = '';
$class = '';
if ( $column == 1 ){
	$class = 'col-md-12';
}else if ($column == 2){
	$class = 'col-md-6';
}else{
	$class = 'col-md-4';
}
if ( !empty( $atts['button_text'] ) ){
	$button = '<a href="%6$s" class="btn btn-gray btn-fit btn-capitalize">'.esc_html( $atts['button_text'] ).'</a>';
}
// 1: blog-image/video, 2: title, 3: info author/view/comment, 4: date, 5: description, 6: permarlink, 7: class
$html_format = '
	<div class="'.esc_attr( $class ).' blog-post %7$s">
		%1$s
		<div class="blog-content">
			<div class="col-xs-2">
				<div class="row">%2$s</div>
			</div>
			<div class="col-xs-10 content-wrapper">
				%4$s
				<h5 class="meta-info">%3$s</h5>
				<div class="preview">%5$s</div>
				'.$button.'
			</div>
		</div>
	</div>';

$custom_css = "";

if ( !empty( $atts['button_color'] )){
	$custom_css .= '.'.$model->attributes['block-class'].' .btn.btn-gray {background-color:'.esc_attr( $atts['button_color'] ).';}';
}
if ( !empty( $atts['button_text_color'] )){
	$custom_css .=  '.'.$model->attributes['block-class'].' .btn.btn-gray {color:'.esc_attr( $atts['button_text_color'] ).';}';
}
if ( !empty( $atts['button_hv_color'] )){
	$custom_css .=  '.'.$model->attributes['block-class'].' .btn.btn-gray:hover {background-color:'.esc_attr( $atts['button_hv_color'] ).';}';
}
if ( !empty( $atts['button_text_hv_color'] )){
	$custom_css .=  '.'.$model->attributes['block-class'].' .btn.btn-gray:hover {color:'.esc_attr( $atts['button_text_hv_color'] ).';}';
}
if ( !empty( $atts['button_border_color'] )){
	$custom_css .=  '.'.$model->attributes['block-class'].' .btn.btn-gray {border-color:'.esc_attr( $atts['button_border_color'] ).';}';
}
if ( !empty( $atts['button_border_hv_color'] )){
	$custom_css .=  '.'.$model->attributes['block-class'].' .btn.btn-gray:hover {border-color:'.esc_attr( $atts['button_border_hv_color'] ).';}';
}
if( $custom_css ) {
	do_action( 'slzexploore_core_add_inline_style', $custom_css );
}

?>
<div class="slz-shortcode blog <?php echo esc_attr( $block_cls ) ?>">
	<div class="blog-wrapper">
		<?php if ( $model->query->have_posts() ) :?>
			<?php
				$post_options = array(
					'html_format' => $html_format,
					);
				$model->render_block( $post_options );
			?>
		<?php endif; // have_post?>
	</div>
</div>
<?php wp_reset_postdata();?>
