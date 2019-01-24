<?php
$model = new Slzexploore_Core_Team();
$model->init( $atts );
$block_cls = $model->attributes['extra_class'] . ' ' . $model->attributes['uniq_id'];
$style = $model->attributes['style'];

$custom_css = '';
if( $style == '2' ) {
	$custom_css .= sprintf('.%s .wrapper-expert .content-expert:nth-child(even) { margin-top: 0; }', $model->attributes['uniq_id'] );
}
if ($model->attributes['color_title_group']) {
	$custom_css .= sprintf('.%s.sc_team_carousel .title-style-2 { color: %s; }', $model->attributes['uniq_id'], $model->attributes['color_title_group'] );
}
if ($model->attributes['color_title_group_line']) {
	$custom_css .= sprintf('.%s.sc_team_carousel .title-style-2:after { background-color: %s; }', $model->attributes['uniq_id'], $model->attributes['color_title_group_line'] );
}
if ($model->attributes['color_box_bg']) {
	$custom_css .= sprintf('.%s.sc_team_carousel .content-expert .caption-expert { background-color: %s; }', $model->attributes['uniq_id'], $model->attributes['color_box_bg'] );
}
if ($model->attributes['color_box_line']) {
	$custom_css .= sprintf('.%s.sc_team_carousel .content-expert .caption-expert { border-left-color: %s; }', $model->attributes['uniq_id'], $model->attributes['color_box_line'] );
}
if ($model->attributes['color_title']) {
	$custom_css .= sprintf('.%s.sc_team_carousel .content-expert .caption-expert .title { color: %s; }', $model->attributes['uniq_id'], $model->attributes['color_title'] );
}
if ($model->attributes['color_title_hv']) {
	$custom_css .= sprintf('.%s.sc_team_carousel .content-expert .caption-expert .title:hover { color: %s; }', $model->attributes['uniq_id'], $model->attributes['color_title_hv'] );
}
if ($model->attributes['color_postion']) {
	$custom_css .= sprintf('.%s.sc_team_carousel .wrapper-expert .caption-expert .text { color: %s; }', $model->attributes['uniq_id'], $model->attributes['color_postion'] );
}
if ($model->attributes['color_icon']) {
	$custom_css .= sprintf('.%s.sc_team_carousel .content-expert .caption-expert .expert-icon { color: %s; }', $model->attributes['uniq_id'], $model->attributes['color_icon'] );
}
if ($model->attributes['color_icon_hv']) {
	$custom_css .= sprintf('.%s.sc_team_carousel .content-expert .caption-expert .expert-icon:hover { color: %s; }', $model->attributes['uniq_id'], $model->attributes['color_icon_hv'] );
}
if ( !empty($custom_css) ) {
	do_action( 'slzexploore_core_add_inline_style', $custom_css );
}

$html_format = '
		<div class="item content-expert">
				%1$s
			<div class="caption-expert">%3$s
				<p class="text">%4$s</p>
				%5$s
				%2$s
				%6$s
			</div>
		</div>
	';
$html_options = array(
	'html_format' => $html_format,
	'thumb_href_class' => 'img-expert',
	'thumb_class'      => 'img img-responsive',
	'meta_open'        => '<ul class="social list-inline">'
);
?>
<div class="slz-shortcode sc_team_carousel our-expert <?php echo esc_attr( $block_cls ); ?>">
	<?php 
	if ( !empty($atts['title']) ) {
		echo '<h3 class="title-style-2">'.esc_html($atts['title']).'</h3>';
	} ?>
	<div class="wrapper-expert">
		<?php $model->render_carousel( $html_options );?>
	</div>
</div>