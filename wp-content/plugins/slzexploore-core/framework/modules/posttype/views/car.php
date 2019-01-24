<?php
$prefix = 'slzexploore_car_';
$html_options = array(
	'separator' => '&nbsp;&nbsp;',
	'class' => 'slz-w190'
);
$display_feature_params = array( '1' => esc_html__( 'Featured ', 'slzexploore-core' ), '' => esc_html__( 'None', 'slzexploore-core' ) );
$yes_no = array( 'yes' => esc_html__( 'Yes', 'slzexploore-core' ), '' => esc_html__( 'No', 'slzexploore-core' ) );
$deposit_type = array( 'percent' => esc_html__( 'Percentage', 'slzexploore-core' ), 'fixed' => esc_html__( 'Fixed Amount', 'slzexploore-core' ) );
?>
<div class="tab-panel">
	<ul class="tab-list">
		<li class="active">
			<a href="slz-tab-car-general"><?php esc_html_e( 'General', 'slzexploore-core' );?></a>
		</li>
		<li>
			<a href="slz-tab-car-discount"><?php esc_html_e( 'Discount', 'slzexploore-core' );?></a>
		</li>
		<li class="">
			<a href="slz-tab-car-gallery"><?php esc_html_e( 'Gallery', 'slzexploore-core' );?></a>
		</li>
		<li class="">
			<a href="slz-tab-car-deposit"><?php esc_html_e( 'Deposit', 'slzexploore-core' );?></a>
		</li>
		<li class="">
			<a href="slz-tab-car-booking"><?php esc_html_e( 'Booking', 'slzexploore-core' );?></a>
		</li>
		<li class="">
			<a href="slz-tab-car-other"><?php esc_html_e( 'Others', 'slzexploore-core' );?></a>
		</li>
	</ul>
	<div class="tab-container">
		<div class="tab-wrapper slz-page-meta">
			<!-- General -->
			<div id="slz-tab-car-general" class="tab-content active slzexploore_core-map-metabox">
				<table class="form-table">
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Display Title', 'slzexploore-core' );?></label>
							<span class="f-right"><?php $this->tooltip_html( esc_html__( 'This content will display title of the car publicly.', 'slzexploore-core' ) );?></span>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_car_meta['. $prefix .'display_title]',
																$this->get_field( $data_meta, 'display_title' ),
																array( 'class' => 'slz-block' ) ) );?>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Short Description', 'slzexploore-core' );?></label>
							<span class="f-right"><?php $this->tooltip_html( esc_html__( 'Enter information about car.', 'slzexploore-core' ) );?></span>
						</th>
						<td>
							<?php echo ( $this->text_area( 'slzexploore_car_meta['. $prefix .'description]',
																$this->get_field( $data_meta, 'description' ),
																array( 'class' => 'slz-block' ,'rows' => '8') ) );?>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Price (per day) / Subfix', 'slzexploore-core' );?></label>
							<span class="f-right"><?php $this->tooltip_html( esc_html__( 'Price (per day ) in this car. Enter price subfix to display ( Example: per day)', 'slzexploore-core' ) );?></span>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_car_meta['. $prefix .'price]',
																$this->get_field( $data_meta, 'price' ),
																array( 'class' => 'slz-block-half' ) ) );?>
							<?php echo ( $this->text_field( 'slzexploore_car_meta['. $prefix .'price_text]',
																$this->get_field( $data_meta, 'price_text' ),
																array( 'class' => 'slz-block-half' ) ) );?>
							<p class="description"><?php esc_html_e( 'Example: 50 / per day', 'slzexploore-core' );?></p>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Available Cars', 'slzexploore-core' );?></label>
							<span class="f-right"><?php $this->tooltip_html( esc_html__( 'Number of available cars for rent.', 'slzexploore-core' ) );?></span>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_car_meta['. $prefix .'number]',
																$this->get_field( $data_meta, 'number' ),
																array( 'class' => 'slz-block-half' ) ) );?>
							<p class="description"><?php esc_html_e( 'Example: 5', 'slzexploore-core' );?></p>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Max People', 'slzexploore-core' );?></label>
							<span class="f-right"><?php $this->tooltip_html( esc_html__( 'Maximum people are allowed in the car.', 'slzexploore-core' ) );?></span>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_car_meta['. $prefix .'max_people]',
																$this->get_field( $data_meta, 'max_people' ),
																array( 'class' => 'slz-block-half' ) ) );?>
							<p class="description"><?php esc_html_e( 'Example: 7', 'slzexploore-core' );?></p>
						</td>
					</tr>
					<tr class="car-status last">
						<th scope="row">
							<label><?php esc_html_e( 'Status', 'slzexploore-core' );?></label>
							<span class="f-right"><?php $this->tooltip_html( esc_html__( 'car status.', 'slzexploore-core' ) );?></span>
						</th>
						<td>
							<?php
							echo ( $this->drop_down_list('slzexploore_car_meta['. $prefix .'status]',
												$this->get_field( $data_meta, 'status' ),
												$this->get_field( $params, 'car_status' ),
												array('class' => 'slz-block-half f-left') ) );
							$new_link = 'edit-tags.php?taxonomy=slzexploore_car_status&post_type=slzexploore_car';
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
							<?php echo ( $this->text_field( 'slzexploore_car_meta['. $prefix .'address]',
															$this->get_field( $data_meta, 'address' ),
															array( 'class' => 'slz-block slzexploore_core-map-address ui-autocomplete-input' ) ) );?>
							<?php echo ( $this->hidden_field( 'slzexploore_car_meta['. $prefix .'location]',
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
			<!--Discount-->
			<div id="slz-tab-car-discount" class="tab-content">
				<table class="form-table">
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Is Discount?', 'slzexploore-core' );?></label>
							<span class="f-right"><?php $this->tooltip_html( esc_html__( 'Add discount to this car.', 'slzexploore-core' ) );?></span>
						</th>
						<td><label>
							<?php echo ( $this->check_box( 'slzexploore_car_meta['. $prefix .'is_discount]',
																$this->get_field( $data_meta, 'is_discount'),
																array( 'class' => 'slz-show-discount') ) );
								esc_html_e( 'Discount this car', 'slzexploore-core' );?>
						</label></td>
					</tr>
					<?php
						$cls_hide_discount = '';
						if( $this->get_field( $data_meta, 'is_discount' ) != 1 ) {
							$cls_hide_discount = 'hide';
						}
					?>
					<tr class="discount <?php echo esc_attr( $cls_hide_discount ); ?>">
						<th scope="row">
							<label><?php esc_html_e( 'Discount Rate (%)', 'slzexploore-core' );?></label>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_car_meta['. $prefix .'discount_rate]',
																$this->get_field( $data_meta, 'discount_rate' ),
																array( 'class' => 'slz-block-half' ) ) );?>
							<p class="description"><?php esc_html_e( 'Example: 35', 'slzexploore-core' );?></p>
						</td>
					</tr>
					<tr class="discount <?php echo esc_attr( $cls_hide_discount ); ?>">
						<th scope="row">
							<label><?php esc_html_e( 'Discount Text', 'slzexploore-core' );?></label>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_car_meta['. $prefix .'discount_text]',
																$this->get_field( $data_meta, 'discount_text' ),
																array( 'class' => 'slz-block' ) ) );?>
							<p class="description"><?php esc_html_e( 'Example: Sale Off', 'slzexploore-core' );?></p>
						</td>
					</tr>
					<tr class="discount <?php echo esc_attr( $cls_hide_discount ); ?>">
						<th scope="row">
							<label><?php esc_html_e( 'Discount Start Date', 'slzexploore-core' );?></label>
						</th>
						<td>
							<?php echo ( $this->date_picker( 'slzexploore_car_meta['. $prefix .'discount_start_date]',
																$this->get_field( $data_meta, 'discount_start_date' ),
																array( 'class' => 'slz-block-half',
																		'placeholder' => esc_html__( 'YYYY-MM-DD', 'slzexploore-core' ),
																		'readonly' => 'readonly' ) ) );?>
							<p class="description"><?php esc_html_e( 'Example: 2016-07-12', 'slzexploore-core' );?></p>
						</td>
					</tr>
					<tr class="discount <?php echo esc_attr( $cls_hide_discount ); ?> last">
						<th scope="row">
							<label><?php esc_html_e( 'Discount End Date', 'slzexploore-core' );?></label>
						</th>
						<td>
							<?php echo ( $this->date_picker( 'slzexploore_car_meta['. $prefix .'discount_end_date]',
																$this->get_field( $data_meta, 'discount_end_date' ),
																array( 'class' => 'slz-block-half',
																		'placeholder' => esc_html__( 'YYYY-MM-DD', 'slzexploore-core' ),
																		'readonly' => 'readonly' ) ) );?>
							<p class="description"><?php esc_html_e( 'Example: 2016-08-12', 'slzexploore-core' );?></p>
						</td>
					</tr>
				</table>
			</div>
			<!-- Gallery Images -->
			<div id="slz-tab-car-gallery" class="tab-content">
				<table class="form-table">
					<tr class="last">
						<th scope="row" colspan="2">
							<label><?php esc_html_e( 'Gallery Images', 'slzexploore-core' );?></label>
							<p class="description"><?php esc_html_e( 'Images should have minimum size: 900x500. Bigger size images will be cropped automatically.', 'slzexploore-core' );?></p>
						</th>
					</tr>
					<tr class="last">
						<td colspan="2">
							<?php $this->gallery( 'slzexploore_car_meta['. $prefix .'gallery_ids]', $data_meta['gallery_ids'] ); ?>
						</td>
					</tr>
				</table>
			</div>
			<!-- Deposit-->
			<div id="slz-tab-car-deposit" class="tab-content">
				<table class="form-table">
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Enable Deposit', 'slzexploore-core' );?></label>
							<span class="f-right"><?php $this->tooltip_html( esc_html__( 'Enable Deposit', 'slzexploore-core' ) );?></span>
						</th>
						<td>
							<?php echo ( $this->drop_down_list( 'slzexploore_car_meta['. $prefix .'is_deposit]',
																$this->get_field( $data_meta, 'is_deposit' ),
																$yes_no,
																array( 'class' => 'slz-block-half' ) ) );?>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Deposit Unit', 'slzexploore-core' );?></label>
							<span class="f-right"><?php $this->tooltip_html( esc_html__( 'Fixed Amount only apply for car price per day, Percentage apply for total price .', 'slzexploore-core' ) );?></span>
						</th>
						<td>
							<?php echo ( $this->drop_down_list( 'slzexploore_car_meta['. $prefix .'deposit_type]',
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
							<?php echo ( $this->text_field( 'slzexploore_car_meta['. $prefix .'deposit_amount]',
																$this->get_field( $data_meta, 'deposit_amount' ),
																array( 'class' => 'slz-block-half' ) ) );?>
							<p class="description"><?php esc_html_e( 'Example: 100', 'slzexploore-core' );?></p>
						</td>
					</tr>
				</table>
			</div>
			<!-- Booking-->
			<div id="slz-tab-car-booking" class="tab-content">
				<table class="form-table">
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Booking Method', 'slzexploore-core' );?></label>
							<span class="f-right"><?php $this->tooltip_html( esc_html__( 'Choose booking method when click BOOK NOW button in car detail page. This option overidde value in theme option.', 'slzexploore-core' ) );?></span>
						</th>
						<td>
							<?php
								$booking_method = array(
									'' => esc_html__( 'Default', 'slzexploore-core' ),
									'booking' => esc_html__( 'Booking Form', 'slzexploore-core' ),
									'contact' => esc_html__( 'Contact Form', 'slzexploore-core' ),
									'both'    => esc_html__( 'Both', 'slzexploore-core' )
								);
								echo ( $this->radio_button_list( 'slzexploore_car_meta['. $prefix .'booking_method]',
																	$this->get_field( $data_meta, 'booking_method' ),
																	$booking_method,
																	$html_options ) );
							?>
						</td>
					</tr>
					<tr class="last">
						<th scope="row" colspan="2">
							<label><?php esc_html_e( 'Booking Email', 'slzexploore-core' );?></label>
							<p class="description"><?php esc_html_e( 'This email will receive user booking information when this car booked. Emails are separated by commas.', 'slzexploore-core' );?></p>
						</th>
					</tr>
					<tr class="last">
						<th>
							<label><?php esc_html_e( 'CC Email', 'slzexploore-core' );?></label>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_car_meta['. $prefix .'mail_cc]',
																$this->get_field( $data_meta, 'mail_cc' ),
																array( 'class' => 'slz-block' ) ) );?>
						</td>
					</tr>
					<tr class="last">
						<th>
							<label><?php esc_html_e( 'BCC Email', 'slzexploore-core' );?></label>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_car_meta['. $prefix .'mail_bcc]',
																$this->get_field( $data_meta, 'mail_bcc' ),
																array( 'class' => 'slz-block' ) ) );?>
						</td>
					</tr>
				</table>
			</div>
			<!-- Other-->
			<div id="slz-tab-car-other" class="tab-content">
				<table class="form-table">
					<tr class="hide">
						<th scope="row" colspan="2">
							<?php echo ( $this->check_box( 'slzexploore_car_meta['. $prefix .'hide_is_full]',
									$this->get_field( $data_meta,'hide_is_full') ) );?>
							<label><?php esc_html_e( 'Hide (it is fully booked) ?', 'slzexploore-core' );?></label>
							<p class="description"><?php esc_html_e( ' Auto hide this post when it is fully booked.', 'slzexploore-core' );?></p>
						</th>
					</tr>
					<tr class="last">
						<th scope="row" colspan="2">
							<label><?php esc_html_e( 'Mark The Car As Featured ?', 'slzexploore-core' );?></label>
						</th>
					</tr>
					<tr>
						<td colspan="2">
							<?php echo ( $this->radio_button_list( 'slzexploore_car_meta['. $prefix .'is_featured]',
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
							<?php echo ( $this->text_field( 'slzexploore_car_meta['. $prefix .'featured_text]',
																$this->get_field( $data_meta, 'featured_text' ),
																array('class'=>'slz-block')
																 ) );?>
							<p class="description"><?php esc_html_e( 'Enter content if you want to display featured ribbon on block. Ex: Hot, New, ...', 'slzexploore-core' );?></p>
						</td>
					</tr>
					<tr class="last">
						<th scope="row" colspan="2">
							<label><?php esc_html_e( 'Attachments', 'slzexploore-core' );?></label>
							<p class="description"><?php esc_html_e( 'Attach PDF files, other documents related to the car.', 'slzexploore-core' );?></p>
						</th>
						
					</tr>
					<tr class="last">
						<td colspan="2"><?php 
							$this->upload_attachment( 'slzexploore_car_meta['. $prefix .'attachment_ids]', $attachment_ids );?>
						</td>
					</tr>
				</table>
			</div>
			
		</div>
	</div>
</div>