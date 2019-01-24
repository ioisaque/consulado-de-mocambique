<?php $model = new Slzexploore_Core_Accommodation(); ?>
<div class="find-widget find-hotel-widget widget">
	<h4 class="title-widgets"><?php esc_html_e( 'FIND HOTELS', 'slzexploore-core' ); ?></h4>
	<form class="content-widget" action="<?php echo ( $model->get_search_link() ); ?>">
		<div class="text-input small-margin-top">
			<div class="place text-box-wrapper">
				<label class="tb-label">
					<?php esc_html_e( 'Where?', 'slzexploore-core' ); ?>
				</label>
				<div class="input-group">
				<?php
					$location_empty   = array( 'empty' => esc_html__( 'Choose Location', 'slzexploore-core' ) );
					$location_args    = array( 'hide_empty' => false, 'orderby' => 'term_id' );
					$location         = Slzexploore_Core_Com::get_tax_options2name( 'slzexploore_hotel_location',
																			$location_empty, $location_args );
					echo ( $this->drop_down_list( 'location',
													'',
													$location,
													array( 'class' => 'tb-input select2' ) ) );
				?>
				</div>
			</div>
			<div class="input-daterange">
				<div class="text-box-wrapper half <?php if( $style == '02' ) echo 'left'; ?>">
					<label class="tb-label">
						<?php esc_html_e( 'Check in', 'slzexploore-core' ); ?>
					</label>

					<div class="input-group">
					<?php
						echo ( $this->text_field( 'date_from',
													'',
													array(
														'class' => 'tb-input',
														'placeholder' => esc_html__( 'YYYY-MM-DD', 'slzexploore-core' )
													) ) );
					?>
						<i class="tb-icon fa fa-calendar input-group-addon"></i>
					</div>
				</div>
				<div class="text-box-wrapper half <?php if( $style == '02' ) echo 'right'; ?>">
					<label class="tb-label">
						<?php esc_html_e( 'Check out', 'slzexploore-core' ); ?>
					</label>

					<div class="input-group">
						<?php
							echo ( $this->text_field( 'date_to',
														'',
														array(
															'class' => 'tb-input',
															'placeholder' => esc_html__( 'YYYY-MM-DD', 'slzexploore-core' )
														) ) );
						?>
						<i class="tb-icon fa fa-calendar input-group-addon"></i>
					</div>
				</div>
			</div>
			<?php
				$cls_left = 'count adult-count';
				if( $style == '02' ) {
					$cls_left = 'half left outer';
				}
				$cls_right = 'count child-count';
				if( $style == '02' ) {
					$cls_right = 'half right outer';
				}
			?>
			<div class="<?php echo esc_attr( $cls_left ); ?> text-box-wrapper">
				<label class="tb-label">
					<?php esc_html_e( 'Adult', 'slzexploore-core' ); ?>
				</label>
				<div class="select-wrapper">
				<?php
					$number_per = array();
					for( $i = 1; $i < 10; $i++ ) {
						$number_per[$i] = $i;
					}
					echo ( $this->drop_down_list( 'adults',
							'',
							$number_per,
							array( 'class' => 'form-control custom-select selectbox' ) ) );
				?>
				</div>
			</div>
			<div class="<?php echo esc_attr( $cls_right ); ?> text-box-wrapper">
				<label class="tb-label">
					<?php esc_html_e( 'Child', 'slzexploore-core' ); ?>
				</label>

				<div class="select-wrapper">
				<?php
					array_unshift($number_per, 0);
					echo ( $this->drop_down_list( 'children',
							'',
							$number_per,
							array( 'class' => 'form-control custom-select selectbox' ) ) );
				?>
				</div>
			</div>
			<?php
				$cls_margin = '';
				if( $style == '02' ) {
					$cls_margin = 'small-margin-top';
				}
				printf('<button type="submit" data-hover="%1$s" class="btn btn-slide %2$s" name="search-accommodation">
							<span class="text">%3$s</span>
							<span class="icons fa fa-long-arrow-right"></span>
						</button>',
						esc_attr__( 'SEARCH NOW', 'slzexploore-core' ),
						esc_attr( $cls_margin ),
						esc_html__( 'SEARCH', 'slzexploore-core' )
					);
			?>
		</div>
	</form>
</div>