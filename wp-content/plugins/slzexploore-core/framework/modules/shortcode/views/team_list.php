<?php
$model = new Slzexploore_Core_Team();
$model->init( $atts );
$custom_css = '';
$id = 'teamlist-'.rand();
$html ='';
$col = '';
if ( $atts['column'] == '1' ) {
	$col = 'col-md-12 col-sm-12 col-xs-12';
	$row_class = 'row';
	$custom_css .= '.'.$id.'.wrapper-organization .md-organization{ text-align: center; }';
}
if ( $atts['column'] == '2' ) {
	$col = 'col-md-6 col-sm-6 col-xs-6';
	$row_class = 'row-2';
	$custom_css .= '.'.$id.'.wrapper-organization .row-2 .md-organization:first-child{ text-align: center;} div.row-2{float:left; width:100%;}';
}
if ( $atts['column'] == '3' ) {
	$col = 'col-md-4 col-sm-4 col-xs-4';
	$row_class = 'row-3';
	$custom_css .= 'div.row-3{float:left; width:100%;}';
}

$html = '
<div class="'.esc_attr($col).' md-organization">
	<div class="content-organization">
		<div class="wrapper-img">
			%1$s
		</div>
		<div class="main-organization">
			<div class="organization-title">
				%2$s
				<p class="text">%3$s</p>
			</div>
			<div class="content-widget">
				<div class="info-list">
					%7$s
						%4$s
						%5$s
						%6$s
					%8$s
				</div>
			</div>
		</div>
	</div>
</div>
';
$html_options = array(
	'html_format' => $html,
	'thumb_class' => 'img img-responsives',
	'open_row'    => '<div class="'.$row_class.' padding-top">',
	'close_row'   => '</div>',
);
if ( !empty($custom_css) ) {
	do_action( 'slzexploore_core_add_inline_style', $custom_css );
}
?>
<div class="slz-shortcode row <?php echo esc_attr($atts['extra_class']); ?>">
	<div class="wrapper-organization <?php echo esc_attr($id) ?>">
		<div class="<?php echo esc_attr($row_class)?>">
			<?php $model->render_list($html_options); ?>
		</div>
	</div>
</div>