<?php
$model = new Slzexploore_Core_Team();
$atts['post_id'] = array( '' => $atts['data'] );
$model->init($atts);
$col = '';
$html1 = '';
$html2 = '';
$html = '';
$custom_css = '';

if ( !empty( $atts['color_hover'] ) ) {
	$custom_css .= sprintf( '.team-single-%s .contact-list-media:hover .icons{background: %s; border: 1px solid %s;}', esc_attr($atts['id']), esc_attr($atts['color_hover']), esc_attr($atts['color_hover']) );
}

if( $atts['style'] == '2' ) {
	$html1 = '
				<div class="avatar">
					<div class="image-wrapper">
						%1$s
					</div>
					<div class="content-wrapper">
						<p class="name">%2$s</p>
						<p class="position">%3$s</p>
					</div>
				</div>';
	$html2 = '
		<div class="media contact-list-media">
			<div class="media-left">
				<span class="icons"><i class="fa fa-%1$s"></i></span>
			</div>
			<div class="media-right">
				<span class="title">%3$s</span>
				<p class="text">%2$s</p>
			</div>
		</div>
	';
	
	$html_options2= array( 
		'html_format' => $html2,
	);
	$html_options1 = array(
		'html_format' => $html1,
		'thumb_class' => 'img img-responsive',
		'phone_format' => '<p class="text">%1$s</p>',
		'email_format' => '<p class="text">%1$s</p>',
	);
	echo '<div class="slz-shortcode about-us-wrapper '.esc_attr($atts['extra_class']).' team-single-'.esc_attr($atts['id']).'">';
		$model->render_single2($html_options1,$html_options2);
	echo '</div>';
	if ( !empty($custom_css) ) {
		do_action( 'slzexploore_core_add_inline_style', $custom_css );
	}
}elseif ( $atts['style'] == '' ) {
	$html = '
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
						<ul class="list-unstyled">
							%4$s
							%5$s
							%6$s
						</ul>
					</div>
				</div>
			</div>
	';
	$html_options = array(
		'html_format' => $html,
		'thumb_class' => 'img img-responsive',
	);
	echo '<div class="slz-shortcode content-organization '.esc_attr($atts['extra_class']).'">';
		$model->render_single($html_options);
	echo '</div>';
}