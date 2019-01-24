<?php $model = new Slzexploore_Core_Car(); ?>
<div class="find-widget find-car-widget widget">
	<h4 class="title-widgets"><?php esc_html_e( 'FIND CARS', 'slzexploore-core' ) ?></h4>
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
					$location = Slzexploore_Core_Com::get_tax_options2name( 'slzexploore_car_location', $location_empty, $location_args );
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
						<?php esc_html_e( 'Start Date', 'slzexploore-core' ); ?>
					</label>

					<div class="input-group">
					<?php
						echo ( $this->text_field( 'date_from',
								'',
								array(
									'class' => 'tb-input',
									'placeholder' => esc_html__( 'YYYY-MM-DD', 'slzexploore-core' ),
									'readonly' => 'readonly'
								) ) );
					?>
						<i class="tb-icon fa fa-calendar input-group-addon"></i>
					</div>
				</div>
				<div class="text-box-wrapper half <?php if( $style == '02' ) echo 'right'; ?>">
					<label class="tb-label">
						<?php esc_html_e( 'Return Date', 'slzexploore-core' ); ?>
					</label>

					<div class="input-group">
					<?php
						echo ( $this->text_field( 'date_to',
								'',
								array(
									'class' => 'tb-input',
									'placeholder' => esc_html__( 'YYYY-MM-DD', 'slzexploore-core' ),
									'readonly' => 'readonly'
								) ) );
					?>
						<i class="tb-icon fa fa-calendar input-group-addon"></i>
					</div>
				</div>
			</div>
			<button type="submit" name="search-car" value="1" data-hover="<?php esc_html_e( 'SEARCH NOW', 'slzexploore-core' ); ?>" class="btn btn-slide <?php if( $style == '02' ) echo 'small-margin-top'; ?>"><span class="text"><?php esc_html_e( 'SEARCH', 'slzexploore-core' ); ?></span><span class="icons fa fa-long-arrow-right"></span></button>
		</div>
	</form>
</div>