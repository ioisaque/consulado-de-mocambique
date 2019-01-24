<?php
	$element = 'tabcontent';
	// Extract tab titles
	preg_match_all( '/slzexploore_core_tab_sc([^\]]+)/i', $content, $matches, PREG_OFFSET_CAPTURE );
	$tab_titles = array();
	if ( isset( $matches[1] ) ) {
		$tab_titles = $matches[1];
	}
	$layout = isset($atts['layout'] )?$atts['layout'] :'01';
	$color_active = isset($atts['color_active'] )?$atts['color_active'] :'#ffdd00';
	$color_normal = isset($atts['color_normal'] )?$atts['color_normal'] :'#3c3c3c';
	$extra_class = isset($atts['tabs_el_class'] )?$atts['tabs_el_class'] :'';
	if ($layout  == '02'){
		$class = 'tab-search tab-search-condensed';
	}else{
		 $class = 'tab-search tab-search-long tab-search-default ';
	}
	$custom_css  = '';
	if (!empty($color_active)){
		$custom_css .=  '#tabs-'.esc_attr($atts['id']).' .tab-btn-wrapper.active .tab-btn,#tabs-'.esc_attr($atts['id']).' .tab-content-bg,#tabs-'.esc_attr($atts['id']).' .tab-content-bg .panel-body{background-color: '.$color_active.';}';
		$custom_css .='#tabs-'.esc_attr($atts['id']).'  .tab-btn-wrapper .tab-btn{ color: '.$color_active.';}';
		$custom_css .='#tabs-'.esc_attr($atts['id']).'  .tab-btn-wrapper .tab-btn:hover{background-color: '.$color_active.';}';
		$custom_css .=  '#tabs-'.esc_attr($atts['id']).' .tab-btn-wrapper .tab-btn i{ color:'.$color_active.';}';

	}
	if(!empty($color_normal)){
		$custom_css .=  '#tabs-'.esc_attr($atts['id']).' .tab-btn-wrapper.active .tab-btn,#tabs-'.esc_attr($atts['id']).' .tab-content-bg,#tabs-'.esc_attr($atts['id']).' .tab-content-bg .panel-body{
			 color: '.$color_normal.';}';
		$custom_css .='#tabs-'.esc_attr($atts['id']).' .tab-btn-wrapper.active .tab-btn i{ color:'.$color_normal.';}';
		$custom_css .='#tabs-'.esc_attr($atts['id']).'  .tab-btn-wrapper .tab-btn{background-color: '.$color_normal.';}';
		$custom_css .='#tabs-'.esc_attr($atts['id']).'  .tab-btn-wrapper .tab-btn:hover{color: '.$color_normal.';}';
		$custom_css .='#tabs-'.esc_attr($atts['id']).' .tab-btn-wrapper .tab-btn:hover i{color: '.$color_normal.';}';
	}
	if( $custom_css ) {
		do_action( 'slzexploore_core_add_inline_style', $custom_css );
	}
?>

<div id="tabs-<?php echo  esc_attr($atts['id']) ?>" data-slz-tabs="tabs-<?php echo  esc_attr($atts['id']) ?>"  class=" <?php echo esc_attr($class); ?> icontabs <?php echo esc_attr( $extra_class); ?> tabs-<?php echo  esc_attr($atts['id']); ?> slz-tabs">
	<?php  if ($layout  == '01'){
		echo ' <div class="container"><div class="row"><div class="col-xs-12">';
	}?>
		<ul  role="tablist" class="nav nav-tabs">
		<?php  $index = 0;
			foreach ( $tab_titles as $tab ):
				$cl_active = '';
				if($index == 0) $cl_active = 'active'; $index++;
				$tab_atts = shortcode_parse_atts( $tab[0] );
				if(!isset(  $tab_atts['icon_type'])){
					$tab_atts['icon_type'] = '01';
				}
				if(!isset( $tab_atts['i_position'] )){
					$tab_atts['i_position'] = 'left';
				}
				if (!empty($tab_atts['title'])) {
					$title = '<span class="vc_tta-title-text">' . $tab_atts['title'] . '</span>';
					if ( isset( $tab_atts['add_icon']) && 'true' === $tab_atts['add_icon'] ) {
						
						$class = 'slzexploore_core-icon';
						if ( $tab_atts['icon_type'] == '01' && isset($tab_atts[ 'icon_ex' ])){
							$class .=  ' '.$tab_atts[ 'icon_ex' ];
						}else{
							if ( isset( $tab_atts[ 'i_icon_fontawesome'] ) ) {
							$class .= ' ' .$tab_atts[ 'i_icon_fontawesome'];
							} else {
								$class .= ' fa fa-adjust';
							}
						}
						$icon_html = '<i class="' . $class . '"></i>';
						if ( 'right' === $tab_atts['i_position'] ) {
							$title = $title . $icon_html;
						} else {
							$title = $icon_html . $title;
						}
					}
					$a_html = '<a class="tab-btn" href="#tab-'.esc_attr($tab_atts['tab_id']).'" data-toggle="tab" >' . $title . '</a>';
					echo '<li class="tab-btn-wrapper '.$cl_active.'"> '.$a_html.'</li>'; 
				}
			?>
		<?php endforeach;?>
		</ul>
	<?php  if ($layout  == '01'){
		echo '</div></div></div>';
	}?>
	<div class="tab-content-bg">
		<?php  if ($layout  == '01'){
		echo ' <div class="container"><div class="row"> <div class="col-xs-12">';
		}?>
			<div class="panel-body">
				<div class="tab-content">
					<?php echo wpb_js_remove_wpautop( $content ) ?>
				</div>
			</div>
		<?php  if ($layout  == '01'){
		echo '</div></div></div>';
		}?>
	</div>
</div>