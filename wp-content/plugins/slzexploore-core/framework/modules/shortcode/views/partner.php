<?php
$model = new Slzexploore_Core_Partner();
$model->init( $atts );
$html = '';
$html_options = '';

if ( $atts['style'] == '2' ) {
	$html = '
		<div class="logo-item">
			<a href="%1$s" class="link">
				%2$s
			</a>
		</div>
	';
	$html_options = array(
		'html_format' => $html,
		'thumb_class' => 'img-responsive',
	);

	echo '<div class="slz-shortcode slide-logo-wrapper '.esc_attr($atts['extra_class']).'">';
		$model->render_partner_style2( $html_options );
	echo '</div>';

}elseif ( $atts['style'] == '' && $atts['row'] == '' ) {
	$html = '
		<div class="content-banner">
			<a class="img-banner" href="%1$s">
				%3$s
			</a>
		</div>
	';
	$html_options = array(
		'html_format' => $html,
		'thumb_class' => 'img-responsive',
	);

	echo '<div class="slz-shortcode about-banner '.esc_attr($atts['extra_class']).'">';
		if ( !empty($atts['title']) ) {
			echo '<h3 class="title-style-2">'.esc_html( $atts['title'] ).'</h3>';
		}
			echo '<div class="wrapper-banner">';
				$model->render_partner_1row_style1($html_options);
	echo '
			</div>
		</div>
	';

}elseif ( $atts['style'] == '' && $atts['row'] == '2' ) {
	$html = '
			<a class="img-banner" href="%1$s">
				%3$s
			</a>
	';
	$html_options = array(
		'html_format' => $html,
		'thumb_class' => 'img-responsive',
	);

	echo '<div class="slz-shortcode about-banner '.esc_attr($atts['extra_class']).'">';
	if ( !empty($atts['title']) ) {
		echo '<h3 class="title-style-2">'.esc_html( $atts['title'] ).'</h3>';
	}
		echo '<div class="wrapper-banner">
				<div class="content-banner">';
				$model->render_partner_2rows_style1($html_options);
	echo '
				</div>
			</div>
		</div>
	';
}