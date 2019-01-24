<?php if($posttags):?>
	<div class="blog-detail-tag tags-widget">
		<span class="content-tag"><?php esc_html_e( 'Tags:', 'exploore' )?></span>
		<div class="content-widget">
			<?php
			$links = array();
			foreach($posttags as $tag) {
				$tag_link = get_tag_link($tag->term_id);
				$links[] = sprintf( '<a href="%1$s" class="tag-item" rel="tag">%2$s</a>', esc_url( $tag_link ), esc_html( $tag->name ) );
			}// endforeach
			if( $links ) {
				echo ( implode('', $links) );
			}
			?>
		</div>
	</div>
<?php endif;?>