<?php if($postcats):?>
	<div class="blog-detail-cat cats-widget">
		<span class="content-tag"><?php esc_html_e( 'Categories:', 'exploore' )?></span>
		<div class="content-widget">
			<?php
			$links = array();
			foreach($postcats as $cat) {
				$cat_link = get_category_link($cat->term_id);
				$links[] = sprintf( '<a href="%1$s" class="tag-item">%2$s</a>', esc_url( $cat_link ), esc_html( $cat->name ) );
			}// endforeach
			if( $links ) {
				echo (implode('', $links));
			}
			?>
		</div>
	</div>
<?php endif;?>