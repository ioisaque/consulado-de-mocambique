<div class="tab-search tab-search-long tab-search-default slz-shortcode <?php echo esc_attr($extra_class); ?>">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<ul role="tablist" class="nav nav-tabs">
				<?php
					$tablist = array(
						'hotel'  => array(
									'icon'  => esc_attr( $hotel_icon ),
									'title' => esc_html__( 'HOTELS', 'slzexploore-core' )
						),
						'tour'  => array(
									'icon'  => esc_attr( $tour_icon ),
									'title' => esc_html__( 'TOURS', 'slzexploore-core' )
						),
						'car'   => array(
									'icon'  => esc_attr( $car_icon ),
									'title' => esc_html__( 'CAR RENT', 'slzexploore-core' )
						),
						'cruise'=> array(
									'icon'  => esc_attr( $cruise_icon ),
									'title' => esc_html__( 'CRUISES', 'slzexploore-core' )
						)
					);
					foreach( $tablist as $key => $value ) {
						if( isset( $tab_enabled[$key] ) ) {
							$tab_id = $key . $id;
							printf('<li role="presentation" class="tab-btn-wrapper">
										<a href="#%1$s" aria-controls="%1$s" role="tab" data-toggle="tab" class="tab-btn">
										<i class="%2$s"></i><span>%3$s</span></a>
									</li>',
									esc_attr( $tab_id ),
									esc_attr( $value['icon'] ),
									esc_attr( $value['title'] )
							);
						}
					}
				?>
				</ul>
			</div>
		</div>
	</div>
	<div class="tab-content-bg">
		<div class="container">
			<div class="row">
				<div class="col-xs-12">
					<div class="tab-content" data-placeholder="<?php esc_html_e( 'Choose Location', 'slzexploore-core' ) ?>">
					<?php
						$model = new Slzexploore_Core_Shortcode_Controller();
						foreach( $tablist as $key => $value ) {
							if( isset( $tab_enabled[$key] ) ) {
								$content_id = $key . $id;
								printf('<div role="tabpanel" id="%s" class="tab-pane fade">', esc_attr( $content_id ) );
								$model->get_layout_search( $key, '01' );
								printf('</div>');
							}
						}
					?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>