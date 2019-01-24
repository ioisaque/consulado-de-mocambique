<?php
$model = new Slzexploore_Core_Tour();
$atts['columns'] = absint($atts['columns']);
if( empty($atts['columns']) ) {
	$atts['columns'] = 3;
}
$atts['add_custom_css'] = '';
$model->init($atts);
// 1: img, 2: title, 3: discount, 4: meta, 5: price, 6: excerpt, 7: button, 8: post class
$html_format = '<div class="tours-layout %8$s">
					<div class="image-wrapper">
						%1$s
						%2$s
						%3$s
						%9$s
					</div>
					<div class="content-wrapper">
						%4$s
						<div class="content">
							%5$s
							%6$s
							%7$s
						</div>
					</div>
				</div>';
$html_options = array(
	'html_format' => $html_format,
);
$model->set_custom_css();
?>
<div class="slz-shortcode tours-wrapper <?php echo esc_attr($model->uniq_id);?>">
	<div class="tours-content">
		<div class="tours-list tours-carousel" data-count="<?php echo esc_attr($atts['columns']);?>">
			<?php $model->render_block_carousel($html_options);?>
		</div>
	</div>
</div>