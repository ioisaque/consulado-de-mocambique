<?php
$prefix = 'slzexploore_exitem_';
$yes_no = array('1' => esc_html__( 'Yes', 'slzexploore-core' ), '' => esc_html__( 'No', 'slzexploore-core' ) );
?>
<div class="tab-panel">
	<ul class="tab-list">
		<li class="active">
			<a href="slz-tab-extra-item-general"><?php esc_html_e( 'General', 'slzexploore-core' );?></a>
		</li>
		<li class="">
			<a href="slz-tab-extra-item-support"><?php esc_html_e( 'Support', 'slzexploore-core' );?></a>
		</li>
	</ul>
	<div class="tab-container">
		<div class="tab-wrapper slz-page-meta">
			<!-- General -->
			<div id="slz-tab-extra-item-general" class="tab-content active slzexploore_core-map-metabox">
				<table class="form-table">
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Price Per Item', 'slzexploore-core' );?></label>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_exitem_meta['. $prefix .'price]',
																$this->get_field( $data_meta, 'price' ),
																array( 'class' => 'slz-block-half' ) ) );?>
							<p class="description"><?php esc_html_e( 'Example: 350', 'slzexploore-core' );?></p>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Is Price Per Day', 'slzexploore-core' );?></label>
						</th>
						<td>
							<?php echo ( $this->radio_button_list( 'slzexploore_exitem_meta['. $prefix .'is_price_day]',
																		$this->get_field( $data_meta, 'is_price_day'),
																		$yes_no,
																		array(
																			'class' => 'slz-is-price-day',
																			'separator' => '&nbsp;&nbsp;'
																		) ) );?>
							<p class="description"><?php esc_html_e( 'Only apply for Accomodation and Car.', 'slzexploore-core' );?></p>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Is Price Per Person', 'slzexploore-core' );?></label>
						</th>
						<td>
							<?php echo ( $this->radio_button_list( 'slzexploore_exitem_meta['. $prefix .'is_price_person]',
																		$this->get_field( $data_meta, 'is_price_person'),
																		$yes_no,
																		array(
																			'class' => 'slz-is-price-person',
																			'separator' => '&nbsp;&nbsp;'
																		) ) );?>
							<p class="description"><?php esc_html_e( 'Only apply for Accomodation, Cruise and Tour.', 'slzexploore-core' );?></p>
						</td>
					</tr>
					<tr class="last">
						<th scope="row">
							<label><?php esc_html_e( 'Max Allowed Items', 'slzexploore-core' );?></label>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_exitem_meta['. $prefix .'max_items]',
																$this->get_field( $data_meta, 'max_items' ),
																array( 'class' => 'slz-block-half' ) ) );?>
							<p class="description"><?php esc_html_e( 'Example: 1', 'slzexploore-core' );?></p>
						</td>
					</tr>
					<tr class="last">
						<th scope="row">
							<label><?php esc_html_e( 'Fixed Item', 'slzexploore-core' );?></label>
						</th>
						<td>
							<label>
								<?php echo ( $this->check_box( 'slzexploore_exitem_meta['. $prefix .'fixed_item]',
																	$this->get_field( $data_meta, 'fixed_item' ),
																	array( 'class' => 'slz-block-half' ) ) );?>
								<?php esc_html_e( 'Is fixed item number', 'slzexploore-core' );?>
							</label>
							<p class="description"><?php esc_html_e( 'If checked the price is charged per entire duration of accommodation stay, tour, car rent, cruise. Example tax.', 'slzexploore-core' );?></p>
						</td>
					</tr>
				</table>
			</div>
			<!-- Gallery Images -->
			<div id="slz-tab-extra-item-support" class="tab-content">
				<table class="form-table">
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Accomodation Categories', 'slzexploore-core' );?></label>
							<span class="f-right"><?php $this->tooltip_html( esc_html__( 'The accommodation categories that this extra item is applicable to.', 'slzexploore-core' ) );?></span>
						</th>
						<td>
							<?php echo ( $this->check_box_list( 'slzexploore_exitem_meta['. $prefix .'hotel_cat][]',
																$this->get_field( $data_meta, 'hotel_cat' ),
																$this->get_field( $params, 'hotel_cat' ),
																array( 'class' => 'slz-block',
																		'separator' => '<br>' ) ) );?>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Car Categories', 'slzexploore-core' );?></label>
							<span class="f-right"><?php $this->tooltip_html( esc_html__( 'The car categories that this extra item is applicable to.', 'slzexploore-core' ) );?></span>
						</th>
						<td>
							<?php echo ( $this->check_box_list( 'slzexploore_exitem_meta['. $prefix .'car_cat][]',
																$this->get_field( $data_meta, 'car_cat' ),
																$this->get_field( $params, 'car_cat' ),
																array( 'class' => 'slz-block',
																		'separator' => '<br>' ) ) );?>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Cruise Categories', 'slzexploore-core' );?></label>
							<span class="f-right"><?php $this->tooltip_html( esc_html__( 'The cruise categories that this extra item is applicable to.', 'slzexploore-core' ) );?></span>
						</th>
						<td>
							<?php echo ( $this->check_box_list( 'slzexploore_exitem_meta['. $prefix .'cruise_cat][]',
																$this->get_field( $data_meta, 'cruise_cat' ),
																$this->get_field( $params, 'cruise_cat' ),
																array( 'class' => 'slz-block',
																		'separator' => '<br>' ) ) );?>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Tour Categories', 'slzexploore-core' );?></label>
							<span class="f-right"><?php $this->tooltip_html( esc_html__( 'The tour categories that this extra item is applicable to.', 'slzexploore-core' ) );?></span>
						</th>
						<td>
							<?php echo ( $this->check_box_list( 'slzexploore_exitem_meta['. $prefix .'tour_cat][]',
																$this->get_field( $data_meta, 'tour_cat' ),
																$this->get_field( $params, 'tour_cat' ),
																array( 'class' => 'slz-block',
																		'separator' => '<br>' ) ) );?>
						</td>
					</tr>
                    <tr>
                        <th scope="row">
                            <label><?php esc_html_e( 'Accomodation Posts', 'slzexploore-core' );?></label>
                            <span class="f-right"><?php $this->tooltip_html( esc_html__( 'The accomodation posts that this extra item is applicable to.', 'slzexploore-core' ) );?></span>
                        </th>
                        <td>
							<?php
							echo ( $this->list_box(
								'slzexploore_exitem_meta[' . $prefix . 'hotel_post]',
								$this->get_field( $data_meta, 'hotel_post' ),
								$this->get_field( $params, 'hotel_post' ),
								array(
									'multiple'     => 'multiple',
								)
							) ); ?>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label><?php esc_html_e( 'Car Posts', 'slzexploore-core' );?></label>
                            <span class="f-right"><?php $this->tooltip_html( esc_html__( 'The car posts that this extra item is applicable to.', 'slzexploore-core' ) );?></span>
                        </th>
                        <td>
							<?php
							echo ( $this->list_box(
								'slzexploore_exitem_meta[' . $prefix . 'car_post]',
								$this->get_field( $data_meta, 'car_post' ),
								$this->get_field( $params, 'car_post' ),
								array(
									'multiple'     => 'multiple',
								)
							) ); ?>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label><?php esc_html_e( 'Cruise Posts', 'slzexploore-core' );?></label>
                            <span class="f-right"><?php $this->tooltip_html( esc_html__( 'The cruise posts that this extra item is applicable to.', 'slzexploore-core' ) );?></span>
                        </th>
                        <td>
							<?php
							echo ( $this->list_box(
								'slzexploore_exitem_meta[' . $prefix . 'cruise_post]',
								$this->get_field( $data_meta, 'cruise_post' ),
								$this->get_field( $params, 'cruise_post' ),
								array(
									'multiple'     => 'multiple',
								)
							) ); ?>
                        </td>
                    </tr>
                    <tr class="last">
                        <th scope="row">
                            <label><?php esc_html_e( 'Tour Posts', 'slzexploore-core' );?></label>
                            <span class="f-right"><?php $this->tooltip_html( esc_html__( 'The tour posts that this extra item is applicable to.', 'slzexploore-core' ) );?></span>
                        </th>
                        <td>
							<?php
							echo ( $this->list_box(
								'slzexploore_exitem_meta[' . $prefix . 'tour_post]',
								$this->get_field( $data_meta, 'tour_post' ),
								$this->get_field( $params, 'tour_post' ),
								array(
									'multiple'     => 'multiple',
								)
							) ); ?>
                        </td>
                    </tr>
				</table>
			</div>
		</div>
	</div>
</div>