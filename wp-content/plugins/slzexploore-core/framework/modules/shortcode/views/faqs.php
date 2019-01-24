<?php
$model = new Slzexploore_Core_Faq();
$model->init( $atts );
$block_cls = $model->attributes['extra_class'];
$block_id = $model->attributes['uniq_id'];

$html_format = '
	<div class="panel">
		<div class="panel-heading">
			<h5 class="panel-title">
				<a data-toggle="collapse" href="#'.esc_attr( $block_id ).'-collapse-%3$s" aria-expanded="false" class="accordion-toggle collapsed">%1$s</a>
			</h5>
		</div>
		<div id="'.esc_attr( $block_id ).'-collapse-%3$s" aria-expanded="false" class="panel-collapse collapse" role="tabpanel">
			<div class="panel-body">%2$s</div>
		</div>
	</div>
	';
$html_options = array(
	'html_format' => $html_format,
);

?>
<div id="accordion-<?php echo esc_attr( $block_id ); ?>" class="slz-shortcode  wrapper-accordion panel-group <?php echo esc_attr( $block_cls ); ?>">
	<?php
		$model->render_sc( $html_options );
	?>
</div>