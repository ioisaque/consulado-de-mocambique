<?php
if( !empty( $list_content ) && is_array( $list_content ) ) {
	$html = '';
	$i = 0;
	foreach( $list_content as $item ) {
		$title = Slzexploore_Core::get_value($item, 'title');
		$body_content = Slzexploore_Core::get_value($item, 'body_content');
		if( !empty($title) || !empty($body_content) ) {
			$i ++;
			$html .= '
				<div class="panel">
					<div class="panel-heading">
						<h5 class="panel-title">
							<a data-toggle="collapse" href="#'.esc_attr( $block_id ).'-collapse-'.esc_attr($i).'" aria-expanded="false" class="accordion-toggle collapsed">'.esc_html($title).'</a>
						</h5>
					</div>
					<div id="'.esc_attr( $block_id ).'-collapse-'.esc_attr($i).'" aria-expanded="false" class="panel-collapse collapse" role="tabpanel">
						<div class="panel-body">'.nl2br(esc_textarea($body_content)).'</div>
					</div>
				</div>';
		}
	}
	if( $html ) {
		echo '
			<div id="toggle-box-'.esc_attr( $block_id ).'" class="slz-shortcode panel-group wrapper-accordion '.esc_attr( $extra_class ).'">'.
				($html) .'
			</div>';
	}
}