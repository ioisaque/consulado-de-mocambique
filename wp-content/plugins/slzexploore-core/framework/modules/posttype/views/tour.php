<?php
$prefix = 'slzexploore_tour_';
$date_type = array('1' => esc_html__( 'Fixed Days', 'slzexploore-core' ), '' => esc_html__( 'None', 'slzexploore-core' ) );
$display_params = array( '1' => esc_html__( 'Show', 'slzexploore-core' ), '' => esc_html__( 'None', 'slzexploore-core' ) );
$yes_no = array( 'yes' => esc_html__( 'Yes', 'slzexploore-core' ), '' => esc_html__( 'No', 'slzexploore-core' ) );
$deposit_type = array( 'percent' => esc_html__( 'Percentage', 'slzexploore-core' ), 'fixed' => esc_html__( 'Fixed Amount', 'slzexploore-core' ) );
$html_options = array(
	'separator' => '&nbsp;&nbsp;',
	'class' => 'slz-w190'
);
$display_feature_params = array( '1' => esc_html__( 'Featured ', 'slzexploore-core' ), '' => esc_html__( 'None', 'slzexploore-core' ) );
?>
<div class="tab-panel">

	<ul class="tab-list">
		<li class="active">
			<a href="slz-tab-tour-general"><?php esc_html_e( 'General', 'slzexploore-core' );?></a>
		</li>
		<li class="">
			<a href="slz-tab-tour-gallery"><?php esc_html_e( 'Gallery', 'slzexploore-core' );?></a>
		</li>
		<li class="">
			<a href="slz-tab-tour-team"><?php esc_html_e( 'Travel Guide', 'slzexploore-core' );?></a>
		</li>
		<li class="">
			<a href="slz-tab-tour-deposit"><?php esc_html_e( 'Deposit', 'slzexploore-core' );?></a>
		</li>
		<li class="">
			<a href="slz-tab-tour-booking"><?php esc_html_e( 'Booking', 'slzexploore-core' );?></a>
		</li>
		<li class="">
			<a href="slz-tab-tour-other"><?php esc_html_e( 'Others', 'slzexploore-core' );?></a>
		</li>
	</ul>
	<div class="tab-container">
		<div class="tab-wrapper slz-page-meta">
			<!-- General -->
			<div id="slz-tab-tour-general" class="tab-content active slzexploore_core-map-metabox">
				<table class="form-table">
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Display Title', 'slzexploore-core' );?></label>
							<span class="f-right"><?php $this->tooltip_html( esc_html__( 'This content will display title of the tour publicly.', 'slzexploore-core' ) );?></span>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_tour_meta['. $prefix .'display_title]',
																$this->get_field( $data_meta, 'display_title' ),
																array( 'class' => 'slz-block' ) ) );?>
						</td>
					</tr>
					
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Short Description', 'slzexploore-core' );?></label>
							<span class="f-right"><?php $this->tooltip_html( esc_html__( 'This is the content that will be shown on tour blocks.', 'slzexploore-core' ) );?></span>
						</th>
						<td>
							<?php echo ( $this->text_area( 'slzexploore_tour_meta['. $prefix .'description]',
																$this->get_field( $data_meta, 'description' ),
																array( 'class' => 'slz-block' ,'rows' => '8') ) );?>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Date Type', 'slzexploore-core' );?></label>
							<span class="f-right"><?php $this->tooltip_html( esc_html__( 'Choose date type for the tour. Check Fixed Days if the tour open with fixed date.', 'slzexploore-core' ) );?></span>
						</th>
						<td>
							<?php echo ( $this->radio_button_list( 'slzexploore_tour_meta['. $prefix .'date_type]',
																		$this->get_field( $data_meta, 'date_type'),
																		$date_type,
																		array(
																			'class' => 'slz-date-type',
																			'separator' => '&nbsp;&nbsp;'
																		) ) );?>
						</td>
					</tr>
					<tr class="last">
						<td colspan="2">
							<table class="form-table">
								<tr class="fixed">
									<th>
										<label><?php esc_html_e( 'Date Frequency', 'slzexploore-core' );?></label>
										<span class="f-right"><?php $this->tooltip_html( esc_html__( 'Choose departure time of the tour is weekly, monthly, any day during the season or a specific day.', 'slzexploore-core' ) );?></span>
									</th>
									<td>
										<?php
											$tour_frequency = array(
																'weekly'  => esc_html__( 'Weekly', 'slzexploore-core' ),
																'monthly' => esc_html__( 'Monthly', 'slzexploore-core' ),
																'season'  => esc_html__( 'Season', 'slzexploore-core' ),
																'other'   => esc_html__( 'Specific Day', 'slzexploore-core' )
															);
											echo ( $this->radio_button_list( 'slzexploore_tour_meta['. $prefix .'frequency]',
																	$this->get_field( $data_meta, 'frequency' ),
																	$tour_frequency,
																	array(
																		'class' => 'slz-date-frequency',
																		'separator' => '&nbsp;&nbsp;'
																	) ) );
										?>
									</td>
								</tr>
								<tr class="fixed weekly">
									<th><label><?php esc_html_e( 'Day Of Week', 'slzexploore-core' );?></label>
										<span class="f-right"><?php $this->tooltip_html( esc_html__( 'Choose day that the tour open weekly.', 'slzexploore-core' ) );?></span></th>
									<td>
										<?php
											$weekly = array(
												'1' => esc_html__( 'Monday', 'slzexploore-core' ),
												'2' => esc_html__( 'Tuesday', 'slzexploore-core' ),
												'3' => esc_html__( 'Wednesday', 'slzexploore-core' ),
												'4' => esc_html__( 'Thursday', 'slzexploore-core' ),
												'5' => esc_html__( 'Friday', 'slzexploore-core' ),
												'6' => esc_html__( 'Saturday', 'slzexploore-core' ),
												'7' => esc_html__( 'Sunday', 'slzexploore-core' )
											);
											
											echo ( $this->check_box_list( 'slzexploore_tour_meta['. $prefix .'weekly][]',
																$this->get_field( $data_meta, 'weekly' ),
																$weekly,
																array( 'class' => 'slz-block',
																		'separator' => '<br>' ) ) );
										?>
									</td>
								<tr class="fixed monthly hide">
									<th><label><?php esc_html_e( 'Day Of Month', 'slzexploore-core' );?></label>
										<span class="f-right"><?php $this->tooltip_html( esc_html__( 'Choose day that the tour open monthly.', 'slzexploore-core' ) );?></span></th>
									<td>
										<?php
											$monthly = array();
											for( $i = 1; $i <= 31; $i ++ ){
												if($i<10){
													$i = '0'.$i;
												}
												$monthly[$i] = $i;
											}
											echo ( $this->list_box( 'slzexploore_tour_meta['. $prefix .'monthly]',
													$this->get_field( $data_meta, 'monthly' ),
													$monthly,
													array( 'class' => 'slz-w190',
															'multiple' => 'true' ) ) );
										?>
									</td>
								</tr>
								<tr class="fixed fixed-date hide">
									<th scope="row">
										<label><?php esc_html_e( 'Tour Date', 'slzexploore-core' );?></label>
										<span class="f-right"><?php $this->tooltip_html( esc_html__( 'Enter date of the tour.', 'slzexploore-core' ) );?></span>
									</th>
									<td>
										<?php echo ( $this->date_picker( 'slzexploore_tour_meta['. $prefix .'start_date]',
																			$this->get_field( $data_meta, 'start_date' ),
																			array( 'class' => 'slz-block-half',
																					'placeholder' => esc_html__( 'YYYY-MM-DD', 'slzexploore-core' ) ) ) );?>
										<span>  &sim;  </span>
										<?php echo ( $this->date_picker( 'slzexploore_tour_meta['. $prefix .'end_date]',
																			$this->get_field( $data_meta, 'end_date' ),
																			array( 'class' => 'slz-block-half',
																					'placeholder' => esc_html__( 'YYYY-MM-DD', 'slzexploore-core' ) ) ) );?>
										<p class="description"><?php esc_html_e( 'Example: 2016-06-01', 'slzexploore-core' );?></p>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Duration', 'slzexploore-core' );?></label>
							<span class="f-right"><?php $this->tooltip_html( esc_html__( 'Duration information in the tour.', 'slzexploore-core' ) );?></span>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_tour_meta['. $prefix .'duration]',
																$this->get_field( $data_meta, 'duration' ),
																array( 'class' => 'slz-block' ) ) );?>
							<p class="description"><?php esc_html_e( 'Example: 4 days', 'slzexploore-core' );?></p>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Maximum Seats', 'slzexploore-core' );?></label>
							<span class="f-right"><?php $this->tooltip_html( esc_html__( 'Max people in the tour.', 'slzexploore-core' ) );?></span>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_tour_meta['. $prefix .'available_seat]',
																$this->get_field( $data_meta, 'available_seat' ),
																array( 'class' => 'slz-block-half' ) ) );?>
							<p class="description"><?php esc_html_e( 'Example: 10', 'slzexploore-core' );?></p>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Price Per Adult', 'slzexploore-core' );?></label>
							<span class="f-right"><?php $this->tooltip_html( esc_html__( 'Price for an adult in the tour.', 'slzexploore-core' ) );?></span>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_tour_meta['. $prefix .'price_adult]',
																$this->get_field( $data_meta, 'price_adult' ),
																array( 'class' => 'slz-block-half' ) ) );?>
							<p class="description"><?php esc_html_e( 'Example: 9800', 'slzexploore-core' );?></p>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Price Per Child', 'slzexploore-core' );?></label>
							<span class="f-right"><?php $this->tooltip_html( esc_html__( 'Price for an child in the tour.', 'slzexploore-core' ) );?></span>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_tour_meta['. $prefix .'price_child]',
																$this->get_field( $data_meta, 'price_child' ),
																array( 'class' => 'slz-block-half' ) ) );?>
							<p class="description"><?php esc_html_e( 'Example: 1000', 'slzexploore-core' );?></p>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Price Per Infant', 'slzexploore-core' );?></label>
							<span class="f-right"><?php $this->tooltip_html( esc_html__( 'Price for an infant in the tour.', 'slzexploore-core' ) );?></span>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_tour_meta['. $prefix .'price_infant]',
																$this->get_field( $data_meta, 'price_infant' ),
																array( 'class' => 'slz-block-half' ) ) );?>
							<p class="description"><?php esc_html_e( 'Example: 500', 'slzexploore-core' );?></p>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Destination', 'slzexploore-core' );?></label>
							<span class="f-right"><?php $this->tooltip_html( esc_html__( 'Destination of this tour', 'slzexploore-core' ) );?></span>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_tour_meta['. $prefix .'destination]',
																$this->get_field( $data_meta, 'destination' ),
																array( 'class' => 'slz-block' ) ) );?>
							<p class="description"><?php esc_html_e( 'Example: London, UK', 'slzexploore-core' );?></p>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Is Discount?', 'slzexploore-core' );?></label>
							<span class="f-right"><?php $this->tooltip_html( esc_html__( 'Add discount this tour.', 'slzexploore-core' ) );?></span>
						</th>
						<td>
							<?php
							echo ( $this->check_box( 'slzexploore_tour_meta['. $prefix .'is_discount]',
															$this->get_field( $data_meta, 'is_discount'),
														array( 'class'=>'tour_show_discount') ) );
							esc_html_e( 'Discount this tour', 'slzexploore-core' );
							?>
						</td>
					</tr>
					<tr class="check-tour_show_discount slz-hide">
						<th scope="row">
							<label><?php esc_html_e( 'Discount Rate (%)', 'slzexploore-core' );?></label>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_tour_meta['. $prefix .'discount_rate]',
																$this->get_field( $data_meta, 'discount_rate' ),
																array( 'class' => 'slz-block-half' ) ) );?>
							<p class="description"><?php esc_html_e( 'Example: 35', 'slzexploore-core' );?></p>
						</td>
					</tr>
					<tr class="check-tour_show_discount slz-hide">
						<th scope="row">
							<label><?php esc_html_e( 'Discount Text', 'slzexploore-core' );?></label>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_tour_meta['. $prefix .'discount_text]',
																$this->get_field( $data_meta, 'discount_text' ),
																array( 'class' => 'slz-block' ) ) );?>
							<p class="description"><?php esc_html_e( 'Example: Sale Off', 'slzexploore-core' );?></p>
						</td>
					</tr>
					<tr class="tour-status last">
						<th scope="row">
							<label><?php esc_html_e( 'Status', 'slzexploore-core' );?></label>
							<span class="f-right"><?php $this->tooltip_html( esc_html__( 'Tour status.', 'slzexploore-core' ) );?></span>
						</th>
						<td>
							<?php
							echo ( $this->drop_down_list('slzexploore_tour_meta['. $prefix .'status]',
												$this->get_field( $data_meta, 'status' ),
												$params['tour_status'],
												array('class' => 'slz-block-half f-left') ) );
							$new_link = 'edit-tags.php?taxonomy=slzexploore_tour_status&post_type=slzexploore_tour';
							printf('<a href="%1$s" title="%2$s" target="_blank">
										<i class="fa fa-plus-square" aria-hidden="true"></i>
									</a>',
									esc_attr( $new_link ),
									esc_attr__( 'Add new status', 'slzexploore-core' )
								);
							?>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Address', 'slzexploore-core' );?></label>
							<span class="f-right"><?php $this->tooltip_html( esc_html__( 'Address of tour', 'slzexploore-core' ) );?></span>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_tour_meta['. $prefix .'address]',
															$this->get_field( $data_meta, 'address' ),
															array( 'class' => 'slz-block slzexploore_core-map-address ui-autocomplete-input' ) ) );?>
							<?php echo ( $this->hidden_field( 'slzexploore_tour_meta['. $prefix .'location]',
															$this->get_field( $data_meta, 'location' ),
															array( 'class' => 'slz-block slzexploore_core-map-location' ) ) );?>
							<br/>
							<?php echo ( $this->button_field( 'find_location',
										esc_html( 'Find', 'slzexploore-core' ),
										array( 'class'=>'find-address slzexploore_core-find-address-button') ) );?>
						</td>
					</tr>
					<tr class="last">
						<td colspan="2">
							<div class="slzexploore_core_map_area"></div>
						</td>
					</tr>
				</table>
			</div>
			<!-- Gallery Images -->
			<div id="slz-tab-tour-gallery" class="tab-content">
				<table class="form-table">
					<tr class="last">
						<th scope="row" colspan="2">
							<label><?php esc_html_e( 'Show Gallery?', 'slzexploore-core' );?></label>
							<p class="description"><?php esc_html_e( 'Show/ Hide gallery in tour detail.', 'slzexploore-core' );?></p>
						</th>
					</tr>
					<tr>
						<td colspan="2">
							<?php echo ( $this->radio_button_list( 'slzexploore_tour_meta['. $prefix .'show_gallery]',
																		$this->get_field( $data_meta, 'show_gallery'),
																		$display_params,
																		$html_options ) );?>
						</td>
					</tr>
					<tr class="last">
						<th scope="row" colspan="2">
							<label><?php esc_html_e( 'Gallery Title', 'slzexploore-core' );?></label>
							<p class="description"><?php esc_html_e( 'Enter title to gallery box in tour detail.', 'slzexploore-core' );?></p>
						</th>
					</tr>
					<tr>
						<td colspan="2">
							<?php echo ( $this->text_field( 'slzexploore_tour_meta['. $prefix .'gallery_box_title]',
																		$this->get_field( $data_meta, 'gallery_box_title'),
																		array('class' => 'slz-block') ) );?>
						</td>
					</tr>
					<tr class="last">
						<th scope="row" colspan="2">
							<label><?php esc_html_e( 'Gallery Background', 'slzexploore-core' );?></label>
							<p class="description"><?php esc_html_e( 'Choose background for gallery box.', 'slzexploore-core' );?></p>
						</th>
					</tr>
					<tr>
						<td colspan="2">
							<?php echo ( $this->single_image( 'slzexploore_tour_meta['. $prefix .'gallery_backg]',
																$this->get_field( $data_meta, 'gallery_backg' ),
																array( 'id'=> $prefix .'gallery_backg_id',
																	'data-rel' => $prefix .'gallery_backg' ) ) );?>
						</td>
					</tr>
					<tr class="last">
						<th scope="row" colspan="2">
							<label><?php esc_html_e( 'Gallery Images', 'slzexploore-core' );?></label>
							<p class="description"><?php esc_html_e( 'Images should have minimum size: 800x540. Bigger size images will be cropped automatically.', 'slzexploore-core' );?></p>
						</th>
					</tr>
					<tr class="last">
						<td colspan="2">
							<?php $this->gallery( 'slzexploore_tour_meta['. $prefix .'gallery_ids]', $data_meta['gallery_ids'] ); ?>
						</td>
					</tr>					
				</table>
			</div>
			<!-- Team Information-->
			<div id="slz-tab-tour-team" class="tab-content">
				<table class="form-table">
					<tr class="last">
						<th scope="row" colspan="2">
							<label><?php esc_html_e( 'Show Travel Guide Information?', 'slzexploore-core' );?></label>
							<p class="description"><?php esc_html_e( 'Show/ Hide travel guide information in tour detail.', 'slzexploore-core' );?></p>
						</th>
					</tr>
					<tr>
						<td colspan="2">
							<?php echo ( $this->radio_button_list( 'slzexploore_tour_meta['. $prefix .'show_team]',
																		$this->get_field( $data_meta, 'show_team'),
																		$display_params,
																		$html_options ) );?>
						</td>
					</tr>
					<tr class="last">
						<th scope="row" colspan="2">
							<label><?php esc_html_e( 'Box Title', 'slzexploore-core' );?></label>
							<p class="description"><?php esc_html_e( 'Enter title to travel guide box in tour detail.', 'slzexploore-core' );?></p>
						</th>
					</tr>
					<tr>
						<td colspan="2">
							<?php echo ( $this->text_field( 'slzexploore_tour_meta['. $prefix .'team_box_title]',
																		$this->get_field( $data_meta, 'team_box_title'),
																		array('class' => 'slz-block') ) );?>
						</td>
					</tr>
					<tr class="last">
						<th scope="row" colspan="2">
							<label><?php esc_html_e( 'Information', 'slzexploore-core' );?></label>
							<p class="description"><?php esc_html_e( 'Enter information to travel guide box in tour detail.', 'slzexploore-core' );?></p>
						</th>
					</tr>
					<tr>
						<td colspan="2">
							<?php echo ( $this->text_area( 'slzexploore_tour_meta['. $prefix .'team_box_info]',
																$this->get_field( $data_meta, 'team_box_info' ),
																array( 'class' => 'slz-block' ,'rows' => '10') ) );?>
						</td>
					</tr>
					<tr class="last">
						<th scope="row" colspan="2">
							<label><?php esc_html_e( 'Travel Guide', 'slzexploore-core' );?></label>
							<p class="description"><?php esc_html_e( 'Add new travel guide from ', 'slzexploore-core' );?><a href="<?php echo admin_url('post-new.php?post_type=slzexploore_team')?>" ><?php esc_html_e('here', 'slzexploore-core');?></a></p>
						</th>
					</tr>
					<tr class="last">
						<td colspan="2">
							<?php
								echo ( $this->drop_down_list( 'slzexploore_tour_meta['. $prefix .'team]',
																		$this->get_field( $data_meta, 'team' ),
																		$params['team'],
																		array( 'class' => 'slz-w190' ) ) );
							?>
						</td>
					</tr>
				</table>
			</div>
			<!-- Deposit-->
			<div id="slz-tab-tour-deposit" class="tab-content">
				<table class="form-table">
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Enable Deposit', 'slzexploore-core' );?></label>
							<span class="f-right"><?php $this->tooltip_html( esc_html__( 'Enable Deposit', 'slzexploore-core' ) );?></span>
						</th>
						<td>
							<?php echo ( $this->drop_down_list( 'slzexploore_tour_meta['. $prefix .'is_deposit]',
																$this->get_field( $data_meta, 'is_deposit' ),
																$yes_no,
																array( 'class' => 'slz-block-half' ) ) );?>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Deposit Unit', 'slzexploore-core' );?></label>
							<span class="f-right"><?php $this->tooltip_html( esc_html__( 'Fixed Amount only apply for adult price, Percentage apply for total price .', 'slzexploore-core' ) );?></span>
						</th>
						<td>
							<?php echo ( $this->drop_down_list( 'slzexploore_tour_meta['. $prefix .'deposit_type]',
																$this->get_field( $data_meta, 'deposit_type' ),
																$deposit_type,
																array( 'class' => 'slz-block-half' ) ) );?>
						</td>
					</tr>
					<tr class="last">
						<th scope="row">
							<label><?php esc_html_e( 'Deposit Amount', 'slzexploore-core' );?></label>
							<span class="f-right"><?php $this->tooltip_html( esc_html__( 'Enter deposit amount.', 'slzexploore-core' ) );?></span>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_tour_meta['. $prefix .'deposit_amount]',
																$this->get_field( $data_meta, 'deposit_amount' ),
																array( 'class' => 'slz-block-half' ) ) );?>
							<p class="description"><?php esc_html_e( 'Example: 100', 'slzexploore-core' );?></p>
						</td>
					</tr>
				</table>
			</div>
			<!-- Booking-->
			<div id="slz-tab-tour-booking" class="tab-content">
				<table class="form-table">
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Booking Method', 'slzexploore-core' );?></label>
							<span class="f-right"><?php $this->tooltip_html( esc_html__( 'Choose booking method when click BOOK NOW button in tour detail page. This option overidde value in theme option.', 'slzexploore-core' ) );?></span>
						</th>
						<td>
							<?php
								$booking_method = array(
									'' => esc_html__( 'Default', 'slzexploore-core' ),
									'booking' => esc_html__( 'Booking Form', 'slzexploore-core' ),
									'contact' => esc_html__( 'Contact Form', 'slzexploore-core' ),
									'both'    => esc_html__( 'Both', 'slzexploore-core' )
								);
								echo ( $this->radio_button_list( 'slzexploore_tour_meta['. $prefix .'booking_method]',
																	$this->get_field( $data_meta, 'booking_method' ),
																	$booking_method,
																	$html_options ) );
							?>
						</td>
					</tr>
					<tr class="last">
						<th scope="row" colspan="2">
							<label><?php esc_html_e( 'Booking Email', 'slzexploore-core' );?></label>
							<p class="description"><?php esc_html_e( 'This email will receive user booking information when this tour booked. Emails are separated by commas.', 'slzexploore-core' );?></p>
						</th>
					</tr>
					<tr class="last">
						<th>
							<label><?php esc_html_e( 'CC Email', 'slzexploore-core' );?></label>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_tour_meta['. $prefix .'mail_cc]',
																$this->get_field( $data_meta, 'mail_cc' ),
																array( 'class' => 'slz-block' ) ) );?>
						</td>
					</tr>
					<tr class="last">
						<th>
							<label><?php esc_html_e( 'BCC Email', 'slzexploore-core' );?></label>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_tour_meta['. $prefix .'mail_bcc]',
																$this->get_field( $data_meta, 'mail_bcc' ),
																array( 'class' => 'slz-block' ) ) );?>
						</td>
					</tr>
				</table>
			</div>
			<!-- Other -->
			<div id="slz-tab-tour-other" class="tab-content">
				<table class="form-table">
					<tr class="hide">
						<th scope="row" colspan="2">
							<?php echo ( $this->check_box( 'slzexploore_tour_meta['. $prefix .'hide_is_full]',
									$this->get_field( $data_meta,'hide_is_full') ) );?><label><?php esc_html_e( 'Hide (it is fully booked) ?', 'slzexploore-core' );?></label>
							<p class="description"><?php esc_html_e( ' Auto hide this post when it is fully booked.', 'slzexploore-core' );?></p>
						</th>
					</tr>
					<tr class="last">
						<th scope="row" colspan="2">
							<label><?php esc_html_e( 'Mark The Tour As Featured ?', 'slzexploore-core' );?></label>
						</th>
					</tr>
					<tr>
						<td colspan="2">
							<?php echo ( $this->radio_button_list( 'slzexploore_tour_meta['. $prefix .'is_featured]',
																		$this->get_field( $data_meta, 'is_featured', 1 ),
																		$display_feature_params,
																		$html_options ) );?>
						</td>
					</tr>
					<?php 
					$is_featured = $this->get_field( $data_meta, 'is_featured', 1 );
					$feature_text = (empty($is_featured)) ? 'hide': '';
					?>
					<tr class="feature-text <?php echo esc_attr($feature_text);?>">
						<th>
							<label><?php esc_html_e( 'Feature Text', 'slzexploore-core' );?></label>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_tour_meta['. $prefix .'featured_text]',
																$this->get_field( $data_meta, 'featured_text' ),
																array('class'=>'slz-block')
																 ) );?>
							<p class="description"><?php esc_html_e( 'Enter content if you want to display featured ribbon on block. Ex: Hot, New, ...', 'slzexploore-core' );?></p>
						</td>
					</tr>
					<tr class="last">
						<th scope="row" colspan="2">
							<label><?php esc_html_e( 'Attachments', 'slzexploore-core' );?></label>
							<p class="description"><?php esc_html_e( 'Attach PDF files, Map images OR other documents related to the tour.', 'slzexploore-core' );?></p>
						</th>
						
					</tr>
					<tr class="last">
						<td colspan="2">
							<?php $this->upload_attachment( 'slzexploore_tour_meta['. $prefix .'attachment_ids]', $attachment_ids );?>
						</td>
					</tr>
				</table>
			</div>

		</div>
	</div>
</div>