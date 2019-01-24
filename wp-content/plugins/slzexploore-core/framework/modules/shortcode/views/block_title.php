<div class="slz-shortcode block-title-<?php echo esc_attr($id).' '.esc_attr($extra_class); ?>">
<?php
$custom_css = '';

if( $style_title == '2' ) {
	echo '<h3 class="title-style-2">'.esc_html($title).'</h3>';
	if ( $show_line == '' ) {
		$custom_css .= sprintf( '.block-title-%s .title-style-2:after{content: ""; display: block;}', esc_attr($id) );
	}elseif ( $show_line == '2' ) {
		$custom_css .= sprintf( '.block-title-%s .title-style-2:after{content: ""; display: none;}', esc_attr($id) );
	}
	if ( !empty($title_color) ) {
		$custom_css .= sprintf( '.block-title-%s .title-style-2{color:%s;}', esc_attr($id), esc_attr($title_color) );
	}
}elseif ( $style_title == '3' ) {
	if ( !empty($title) ) {
		echo '<h3 class="slz-shortcode title-style-3">'.esc_html($title).'</h3>';
	}
}
else {
	echo '
	<div class="group-title">
		<div class="sub-title">';
			if( $sub_title ) {
				echo '<p class="text">'.esc_html($sub_title).'</p>';
			}
			if( !empty($icon_type) && $icon_type == '02' && !empty($icon_fw) ){
				echo '<i class="icons '.esc_attr($icon_fw).'"></i>';
			} elseif ( empty($icon_type) && !empty($icon_ex) ) {
				echo '<i class="icons '.esc_attr($icon_ex).'"></i>';
			}
		echo '
		</div>';
		if( !empty($title) ) {
			echo '<h2 class="main-title">'.esc_html($title).'</h2>';
		}
	echo'
	</div>';
	if( !empty($description) ) {
		echo '<p>'.wp_kses_post($description).'</p>';
	}
	if ( !empty($margin_bottom_title) ) {
		$custom_css = sprintf( '.block-title-%s .group-title{margin-bottom:%spx}', esc_attr($id), esc_attr($margin_bottom_title) );
	}
}
?>
</div>
<?php 
if($custom_css ){
	do_action( 'slzexploore_core_add_inline_style', $custom_css );
}
?>