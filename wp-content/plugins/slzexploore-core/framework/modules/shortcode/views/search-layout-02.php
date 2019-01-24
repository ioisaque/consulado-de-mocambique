<div class="tab-search tab-search-condensed slz-shortcode <?php echo esc_attr($extra_class); ?>">
	<ul role="tablist" class="nav nav-tabs">
		<?php
			$tablist = array(
				'hotel'  => esc_attr( $hotel_icon ),
				'tour'   => esc_attr( $tour_icon ),
				'car'    => esc_attr( $car_icon ),
				'cruise' => esc_attr( $cruise_icon )
			);
			foreach( $tablist as $key => $icon ) {
				if( isset( $tab_enabled[$key] ) ) {
					$tab_id = $key . $id;
					printf('<li role="presentation" class="tab-btn-wrapper">
								<a href="#%1$s" aria-controls="%1$s" role="tab" data-toggle="tab" class="tab-btn">
								<i class="%2$s"></i></a>
							</li>',
							esc_attr( $tab_id ),
							esc_attr( $icon )
					);
				}
			}
		?>
	</ul>
	<div class="tab-content-bg">
		<div class="tab-content" data-placeholder="<?php esc_html_e( 'Choose Location', 'slzexploore-core' ) ?>">
		<?php
			$model = new Slzexploore_Core_Shortcode_Controller();
			foreach( $tablist as $key => $value ) {
				if( isset( $tab_enabled[$key] ) ) {
					$content_id = $key . $id;
					printf('<div role="tabpanel" id="%s" class="tab-pane fade">', esc_attr( $content_id ) );
					$model->get_layout_search( $key, '02' );
					printf('</div>');
				}
			}
		?>
		</div>
	</div>
</div>