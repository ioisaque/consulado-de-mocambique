<?php
	$prefix = 'slzexploore_hotel_';
	$display_feature_params = array(
								'1' => esc_html__( 'Featured ', 'slzexploore-core' ),
								'' => esc_html__( 'None', 'slzexploore-core' )
							);
	$show_params = array(
							'1' => esc_html__( 'Show ', 'slzexploore-core' ),
							'' => esc_html__( 'None', 'slzexploore-core' )
						);
	$html_options = array(
		'separator' => '&nbsp;&nbsp;',
		'class' => 'slz-w190'
	);
	$yes_no       = array( 'yes' => esc_html__( 'Yes', 'slzexploore-core' ), '' => esc_html__( 'No', 'slzexploore-core' ) );
	$deposit_type = array( 'percent' => esc_html__( 'Percentage', 'slzexploore-core' ), 'fixed' => esc_html__( 'Fixed Amount', 'slzexploore-core' ) );
?>
<div class="tab-panel">
	<ul class="tab-list">
		<li class="active">
			<a href="slz-tab-accommodation-general"><?php esc_html_e( 'General', 'slzexploore-core' );?></a>
		</li>
		<li>
			<a href="slz-tab-accommodation-room-types"><?php esc_html_e( 'Room Types', 'slzexploore-core' );?></a>
		</li>
		<li>
			<a href="slz-tab-accommodation-discount"><?php esc_html_e( 'Discount', 'slzexploore-core' );?></a>
		</li>
		<li>
			<a href="slz-tab-accommodation-other"><?php esc_html_e( 'Others', 'slzexploore-core' );?></a>
		</li>
		<li class="">
			<a href="slz-tab-accommodation-deposit"><?php esc_html_e( 'Deposit', 'slzexploore-core' );?></a>
		</li>
	</ul>
	<div class="tab-container">
		<div class="tab-wrapper slz-page-meta">
			<!-- General -->
			<div id="slz-tab-accommodation-general" class="tab-content active slzexploore_core-map-metabox">
				<table class="form-table">
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Display Title', 'slzexploore-core' );?></label>
							<span class="f-right"><?php $this->tooltip_html( esc_html__( 'This content will display title of the accommodation publicly.', 'slzexploore-core' ) );?></span>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_hotel_meta['. $prefix .'display_title]',
															$this->get_field( $data_meta, 'display_title' ),
															array( 'class' => 'slz-block' ) ) );?>
						</td>
					</tr>
					<tr class="hotel-status">
						<th scope="row">
							<label><?php esc_html_e( 'Status', 'slzexploore-core' );?></label>
							<span class="f-right"><?php $this->tooltip_html( esc_html__( 'Hotel status.', 'slzexploore-core' ) );?></span>
						</th>
						<td>
							<?php
							echo ( $this->drop_down_list('slzexploore_hotel_meta['. $prefix .'status]',
												$this->get_field( $data_meta, 'status' ),
												$this->get_field( $params, 'status' ),
												array('class' => 'slz-block-half f-left') ) );
							$new_link = 'edit-tags.php?taxonomy=slzexploore_hotel_status&post_type=slzexploore_hotel';
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
							<label><?php esc_html_e( 'Short Description', 'slzexploore-core' );?></label>
							<span class="f-right"><?php $this->tooltip_html( esc_html__( 'This is the content that will be shown on accommodation blocks.', 'slzexploore-core' ) );?></span>
						</th>
						<td>
							<?php echo ( $this->text_area( 'slzexploore_hotel_meta['. $prefix .'short_des]',
																$this->get_field( $data_meta, 'short_des' ),
																array( 'class' => 'slz-block',
																		'rows' => 8 ) ) );?>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Thumbnail', 'slzexploore-core' );?></label>
							<span class="f-right"><?php $this->tooltip_html( esc_html__( 'Upload thumbnail using to accommodation blocks.', 'slzexploore-core' ) );?></span>
						</th>
						<td>
							<?php echo ( $this->single_image( 'slzexploore_hotel_meta['. $prefix .'thumbnail]',
																$this->get_field( $data_meta, 'thumbnail' ),
																array( 'id'=> $prefix .'thumbnail_id',
																	'data-rel' => $prefix .'thumbnail' ) ) );?>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Check-in Time', 'slzexploore-core' );?></label>
							<span class="f-right"><?php $this->tooltip_html( esc_html__( 'Enter information about time check in accommodation.', 'slzexploore-core' ) );?></span>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_hotel_meta['. $prefix .'check_in_time]',
															$this->get_field( $data_meta, 'check_in_time' ),
															array( 'class' => 'slz-block-half' ) ) );?>
							<p class="description"><?php esc_html_e( 'Example: 2 PM', 'slzexploore-core' );?></p>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Check-out Time', 'slzexploore-core' );?></label>
							<span class="f-right"><?php $this->tooltip_html( esc_html__( 'Choose time check out accommodation.', 'slzexploore-core' ) );?></span>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_hotel_meta['. $prefix .'check_out_time]',
															$this->get_field( $data_meta, 'check_out_time' ),
															array( 'class' => 'slz-block-half' ) ) );?>
							<p class="description"><?php esc_html_e( 'Example: 12 AM', 'slzexploore-core' );?></p>
						</td>
					</tr>
					<tr class="last">
						<th scope="row">
							<label><?php esc_html_e( 'Star Rating', 'slzexploore-core' );?></label>
							<span class="f-right"><?php $this->tooltip_html( esc_html__( 'Star rating in this accommodation.', 'slzexploore-core' ) );?></span>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_hotel_meta['. $prefix .'hotel_rating]',
																$this->get_field( $data_meta, 'hotel_rating' ),
																array( 'class' => 'slz-block-half' ) ) );?>
							<p class="description"><?php esc_html_e( 'Example: 5', 'slzexploore-core' );?></p>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Average Price / Subfix', 'slzexploore-core' );?></label>
							<span class="f-right"><?php $this->tooltip_html( esc_html__( 'Average price per night in this accommodation. Enter price subfix to display ( Example: per night)', 'slzexploore-core' ) );?></span>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_hotel_meta['. $prefix .'average_price]',
																$this->get_field( $data_meta, 'average_price' ),
																array( 'class' => 'slz-block-half' ) ) );
								echo ( $this->text_field( 'slzexploore_hotel_meta['. $prefix .'price_subfix]',
																$this->get_field( $data_meta, 'price_subfix' ),
																array( 'class' => 'slz-block-half' ) ) );?>
							<p class="description"><?php esc_html_e( 'Example: 500 / per night', 'slzexploore-core' );?></p>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Email', 'slzexploore-core' );?></label>
							<span class="f-right"><?php $this->tooltip_html( esc_html__( 'Contact to accommodation by email.', 'slzexploore-core' ) );?></span>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_hotel_meta['. $prefix .'email]',
																$this->get_field( $data_meta, 'email' ),
																array( 'class' => 'slz-block' ) ) );?>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Phone', 'slzexploore-core' );?></label>
							<span class="f-right"><?php $this->tooltip_html( esc_html__( 'Contact to accommodation by phone.', 'slzexploore-core' ) );?></span>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_hotel_meta['. $prefix .'phone]',
																$this->get_field( $data_meta, 'phone' ),
																array( 'class' => 'slz-block' ) ) );?>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Address', 'slzexploore-core' );?></label>
							<span class="f-right"><?php $this->tooltip_html( esc_html__( 'Address of accommodation', 'slzexploore-core' ) );?></span>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_hotel_meta['. $prefix .'address]',
															$this->get_field( $data_meta, 'address' ),
															array( 'class' => 'slz-block slzexploore_core-map-address ui-autocomplete-input' ) ) );?>
							<?php echo ( $this->hidden_field( 'slzexploore_hotel_meta['. $prefix .'location]',
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
			<!-- Room Type-->
			<div id="slz-tab-accommodation-room-types" class="tab-content">
				<table class="form-table">
					<tr class="last">
						<th scope="row" colspan="2">
							<label><?php esc_html_e( 'Show Room Types Box?', 'slzexploore-core' );?></label>
							<p class="description"><?php esc_html_e( 'Show/Hide room types box in detail page.', 'slzexploore-core' );?></p>
						</th>
					</tr>
					<tr>
						<td colspan="2">
							<?php echo ( $this->radio_button_list('slzexploore_hotel_meta['. $prefix .'disable_room_type]',
																$this->get_field( $data_meta, 'disable_room_type'),
																$show_params,
																array( 'id' => 'disable_room_type',
																		'separator' => '&nbsp;&nbsp;' ) ) );?>
						</td>
					</tr>
					<tr class="last">
						<th scope="row">
							<label><?php esc_html_e( 'Room Types', 'slzexploore-core' );?></label>
						</th>
					</tr>
					<tr>
						<td>
							<?php 
								$new_room_link = 'post-new.php?post_type=slzexploore_room';
								printf('<a href="%1$s" title="%2$s">%2$s</a>',
										esc_url( $new_room_link ),
										esc_html__( 'Add new room type', 'slzexploore-core' )
									);
								$room_ids = $this->get_field( $data_meta, 'room_type');
								if( !empty( $room_ids ) ) {
									echo '<ul>';
									$arr_room = explode( ',', $room_ids );
									foreach( $arr_room as $r_id ) {
										if( !empty( $r_id ) ) {
											$editlink = get_edit_post_link( $r_id );
											printf('<li>%1$s
														<a href="%2$s" title="%3$s"><i class="fa fa-pencil"></i></a>
														<a href="javascript:void(0);" title="" data-id="%4$s" class="delete-room">
															<i class="fa fa-trash"></i></a>
													</li>',
													get_the_title ( $r_id ),
													esc_url( $editlink ),
													esc_html__( 'Edit', 'slzexploore-core' ),
													esc_attr( $r_id )
												);
										}
									}
									echo '</ul>';
									echo ( $this->hidden_field('slzexploore_hotel_meta['. $prefix .'room_type]',
																$this->get_field( $data_meta, 'room_type'),
																array( 'class' => 'room_type') ) );
								}
							?>
						</td>
					</tr>
				</table>
			</div>
			<!--Discount-->
			<div id="slz-tab-accommodation-discount" class="tab-content">
				<table class="form-table">
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Is Discount?', 'slzexploore-core' );?></label>
							<span class="f-right"><?php $this->tooltip_html( esc_html__( 'Add discount to this accommodation.', 'slzexploore-core' ) );?></span>
						</th>
						<td><label>
							<?php echo ( $this->check_box( 'slzexploore_hotel_meta['. $prefix .'discount]',
																$this->get_field( $data_meta, 'discount'),
																array( 'class' => 'slz-show-discount') ) );
								esc_html_e( 'Discount this accommodation', 'slzexploore-core' );?>
						</label></td>
					</tr>
					<?php
						$cls_hide_discount = '';
						if( $this->get_field( $data_meta, 'discount', 1) != 1 ) {
							$cls_hide_discount = 'hide';
						}
					?>
					<tr class="discount <?php echo esc_attr( $cls_hide_discount ); ?>">
						<th scope="row">
							<label><?php esc_html_e( 'Discount Rate (%)', 'slzexploore-core' );?></label>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_hotel_meta['. $prefix .'discount_rate]',
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
							<?php echo ( $this->text_field( 'slzexploore_hotel_meta['. $prefix .'discount_text]',
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
							<?php echo ( $this->date_picker( 'slzexploore_hotel_meta['. $prefix .'discount_start_date]',
																$this->get_field( $data_meta, 'discount_start_date' ),
																array( 'class' => 'slz-block-half' ) ) );?>
							<p class="description"><?php esc_html_e( 'Example: 2016-06-12', 'slzexploore-core' );?></p>
						</td>
					</tr>
					<tr class="discount <?php echo esc_attr( $cls_hide_discount ); ?> last">
						<th scope="row">
							<label><?php esc_html_e( 'Discount End Date', 'slzexploore-core' );?></label>
						</th>
						<td>
							<?php echo ( $this->date_picker( 'slzexploore_hotel_meta['. $prefix .'discount_end_date]',
																$this->get_field( $data_meta, 'discount_end_date' ),
																array( 'class' => 'slz-block-half' ) ) );?>
							<p class="description"><?php esc_html_e( 'Example: 2016-07-12', 'slzexploore-core' );?></p>
						</td>
					</tr>
				</table>
			</div>
			<!-- Others-->
			<div id="slz-tab-accommodation-other" class="tab-content">
				<table class="form-table">
					<tr class="last">
						<th scope="row" colspan="2">
							<label><?php esc_html_e( 'Booking Email', 'slzexploore-core' );?></label>
							<p class="description"><?php esc_html_e( 'This email will receive user booking information when this accommodation booked. Emails are separated by commas.', 'slzexploore-core' );?></p>
						</th>
					</tr>
					<tr class="last">
						<th>
							<label><?php esc_html_e( 'CC Email', 'slzexploore-core' );?></label>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_hotel_meta['. $prefix .'mail_cc]',
																$this->get_field( $data_meta, 'mail_cc' ),
																array( 'class' => 'slz-block' ) ) );?>
						</td>
					</tr>
					<tr>
						<th>
							<label><?php esc_html_e( 'BCC Email', 'slzexploore-core' );?></label>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_hotel_meta['. $prefix .'mail_bcc]',
																$this->get_field( $data_meta, 'mail_bcc' ),
																array( 'class' => 'slz-block' ) ) );?>
						</td>
					</tr>
					<tr class="last">
						<th scope="row" colspan="2">
							<label><?php esc_html_e( 'Mark The Accommodation As Featured?', 'slzexploore-core' );?></label>
						</th>
					</tr>
					<tr>
						<td colspan="2">
							<?php echo ( $this->radio_button_list( 'slzexploore_hotel_meta['. $prefix .'is_featured]',
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
							<?php echo ( $this->text_field( 'slzexploore_hotel_meta['. $prefix .'featured_text]',
																$this->get_field( $data_meta, 'featured_text' ),
																array('class'=>'slz-block')
																 ) );?>
							<p class="description"><?php esc_html_e( 'Enter content if you want to display featured ribbon on block. Ex: Hot, New, ...', 'slzexploore-core' );?></p>
						</td>
					</tr>
					<tr class="last">
						<th scope="row" colspan="2">
							<label><?php esc_html_e( 'Attachments', 'slzexploore-core' );?></label>
							<p class="description"><?php esc_html_e( 'Attach PDF files, Map images OR other documents related to the accommodation.', 'slzexploore-core' );?></p>
						</th>
					</tr>
					<tr class="last">
						<td colspan="2">
							<?php $this->upload_attachment( 'slzexploore_hotel_meta['. $prefix .'attachment_ids]', $data_meta['attachment_ids'] );?>
						</td>
					</tr>
				</table>
			</div>
			<!-- Deposit-->
			<div id="slz-tab-accommodation-deposit" class="tab-content">
				<table class="form-table">
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Enable Deposit', 'slzexploore-core' );?></label>
							<span class="f-right"><?php $this->tooltip_html( esc_html__( 'Enable Deposit', 'slzexploore-core' ) );?></span>
						</th>
						<td>
							<?php echo ( $this->drop_down_list( 'slzexploore_hotel_meta['. $prefix .'is_deposit]',
																$this->get_field( $data_meta, 'is_deposit' ),
																$yes_no,
																array( 'class' => 'slz-block-half' ) ) );?>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Deposit Unit', 'slzexploore-core' );?></label>
							<span class="f-right"><?php $this->tooltip_html( esc_html__( 'Fixed Amount only apply for room price, Percentage apply for total price .', 'slzexploore-core' ) );?></span>
						</th>
						<td>
							<?php echo ( $this->drop_down_list( 'slzexploore_hotel_meta['. $prefix .'deposit_type]',
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
							<?php echo ( $this->text_field( 'slzexploore_hotel_meta['. $prefix .'deposit_amount]',
																$this->get_field( $data_meta, 'deposit_amount' ),
																array( 'class' => 'slz-block-half' ) ) );?>
							<p class="description"><?php esc_html_e( 'Example: 100', 'slzexploore-core' );?></p>
						</td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</div>