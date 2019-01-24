<?php
$prefix = 'slzexploore_cruise_';
$date_type = array('1' => esc_html__( 'Fixed Days', 'slzexploore-core' ), '' => esc_html__( 'None', 'slzexploore-core' ) );
$display_params = array( '1' => esc_html__( 'Show', 'slzexploore-core' ), '' => esc_html__( 'None', 'slzexploore-core' ) );
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
			<a href="slz-tab-cruise-general"><?php esc_html_e( 'General', 'slzexploore-core' );?></a>
		</li>
		<li class="">
			<a href="slz-tab-cruise-gallery"><?php esc_html_e( 'Gallery', 'slzexploore-core' );?></a>
		</li>
		<li class="">
			<a href="slz-tab-cabin-type"><?php esc_html_e( 'Cabin Types', 'slzexploore-core' );?></a>
		</li>
		<li class="">
			<a href="slz-tab-cruise-deposit"><?php esc_html_e( 'Deposit', 'slzexploore-core' );?></a>
		</li>
		<li class="">
			<a href="slz-tab-cruise-booking"><?php esc_html_e( 'Booking', 'slzexploore-core' );?></a>
		</li>
		<li class="">
			<a href="slz-tab-cruise-other"><?php esc_html_e( 'Others', 'slzexploore-core' );?></a>
		</li>
	</ul>
	<div class="tab-container">
		<div class="tab-wrapper slz-page-meta">
			<!-- General -->
			<div id="slz-tab-cruise-general" class="tab-content active slzexploore_core-map-metabox">
				<table class="form-table">
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Display Title', 'slzexploore-core' );?></label>
							<span class="f-right"><?php $this->tooltip_html( esc_html__( 'This content will display title of the cruise publicly.', 'slzexploore-core' ) );?></span>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_cruise_meta['. $prefix .'display_title]',
																$this->get_field( $data_meta, 'display_title' ),
																array( 'class' => 'slz-block' ) ) );?>
						</td>
					</tr>
					
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Information', 'slzexploore-core' );?></label>
							<span class="f-right"><?php $this->tooltip_html( esc_html__( 'This is the content that will be shown on cruise detail page.', 'slzexploore-core' ) );?></span>
						</th>
						<td>
							<?php 
							$id = 'slzexploore_cruise_meta_info';
							echo ( $this->editor($info , $id , array(
								'wpautop' => true,
								'media_buttons' => false, 
								'teeny' => true,
								'textarea_cols' => '',
								'tinymce' => array(
												'theme_advanced_buttons1' => 'save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,cut,copy,paste,pastetext,pasteword,',
												'theme_advanced_buttons2' => "styleselect,formatselect,fontselect,fontsizeselect,",
												'theme_advanced_buttons3' => ",bullist,numlist,|,outdent,indent,blockquote,|,link,anchor,image,|,insertdate,forecolor,backcolor,|,tablecontrols,|,hr,|,fullscreen",
												'theme_advanced_buttons4' => "",
												'theme_advanced_text_colors' => '0f3156,636466,0486d3',
											),
								'quicktags' => array(
									'buttons' => 'b,i,ul,ol,li,link,close'
								)
							)));

																?>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Short Description', 'slzexploore-core' );?></label>
							<span class="f-right"><?php $this->tooltip_html( esc_html__( 'This is the content that will be shown on cruise blocks.', 'slzexploore-core' ) );?></span>
						</th>
						<td>
							<?php echo ( $this->text_area( 'slzexploore_cruise_meta['. $prefix .'description]',
																$this->get_field( $data_meta, 'description' ),
																array( 'class' => 'slz-block' ,'rows' => '8') ) );?>
						</td>
					</tr>
					<tr class="last">
						<th scope="row">
							<label><?php esc_html_e( 'Star Rating', 'slzexploore-core' );?></label>
							<span class="f-right"><?php $this->tooltip_html( esc_html__( 'Star rating in this cruise.', 'slzexploore-core' ) );?></span>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_cruise_meta['. $prefix .'star_rating]',
																$this->get_field( $data_meta, 'star_rating' ),
																array( 'class' => 'slz-block-half' ) ) );?>
							<p class="description"><?php esc_html_e( 'Example: 5', 'slzexploore-core' );?></p>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Date Type', 'slzexploore-core' );?></label>
							<span class="f-right"><?php $this->tooltip_html( esc_html__( 'Choose date type for the cruise. Check Fixed Days if the cruise open with fixed date.', 'slzexploore-core' ) );?></span>
						</th>
						<td>
							<?php echo ( $this->radio_button_list( 'slzexploore_cruise_meta['. $prefix .'date_type]',
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
							<table class="form-table" >
								<tr class="fixed">
									<th>
										<label><?php esc_html_e( 'Date Frequency', 'slzexploore-core' );?></label>
										<span class="f-right"><?php $this->tooltip_html( esc_html__( 'Choose departure time of the cruise is weekly, monthly, any day during the season or a specific day.', 'slzexploore-core' ) );?></span>
									</th>
									<td>
										<?php
											$cruise_frequency = array(
																'weekly'  => esc_html__( 'Weekly', 'slzexploore-core' ),
																'monthly' => esc_html__( 'Monthly', 'slzexploore-core' ),
																'season'  => esc_html__( 'Season', 'slzexploore-core' ),
																'other'   => esc_html__( 'Specific Day', 'slzexploore-core' )
															);
											echo ( $this->radio_button_list( 'slzexploore_cruise_meta['. $prefix .'frequency]',
																	$this->get_field( $data_meta, 'frequency' ),
																	$cruise_frequency,
																	array(
																		'class' => 'slz-date-frequency',
																		'separator' => '&nbsp;&nbsp;'
																	) ) );
										?>
									</td>
								</tr>
								<tr class="fixed weekly">
									<th><label><?php esc_html_e( 'Day Of Week', 'slzexploore-core' );?></label>
										<span class="f-right"><?php $this->tooltip_html( esc_html__( 'Choose day that the cruise open weekly.', 'slzexploore-core' ) );?></span></th>
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
											echo ( $this->check_box_list( 'slzexploore_cruise_meta['. $prefix .'weekly][]',
																$this->get_field( $data_meta, 'weekly' ),
																$weekly,
																array( 'class' => 'slz-block',
																		'separator' => '<br>' ) ) );
										?>
									</td>
								<tr class="fixed monthly hide">
									<th><label><?php esc_html_e( 'Day Of Month', 'slzexploore-core' );?></label>
										<span class="f-right"><?php $this->tooltip_html( esc_html__( 'Choose day that the cruise open monthly.', 'slzexploore-core' ) );?></span></th>
									<td>
										<?php
											$monthly = array();
											for( $i = 1; $i <= 31; $i ++ ){
												if($i<10){
													$i = '0'.$i;
												}
												$monthly[$i] = $i;
											}
											echo ( $this->list_box( 'slzexploore_cruise_meta['. $prefix .'monthly]',
													$this->get_field( $data_meta, 'monthly' ),
													$monthly,
													array( 'class' => 'slz-w190',
															'multiple' => 'true' ) ) );
										?>
									</td>
								</tr>
								<tr class="fixed fixed-date hide">
									<th scope="row">
										<label><?php esc_html_e( 'Cruise Date', 'slzexploore-core' );?></label>
										<span class="f-right"><?php $this->tooltip_html( esc_html__( 'Enter date of the cruise.', 'slzexploore-core' ) );?></span>
									</th>
									<td>
										<?php echo ( $this->date_picker( 'slzexploore_cruise_meta['. $prefix .'start_date]',
																			$this->get_field( $data_meta, 'start_date' ),
																			array( 'class' => 'slz-block-half',
																			'placeholder' => esc_html__( 'YYYY-MM-DD', 'slzexploore-core' ),
																					'readonly' => 'readonly' ) ) );?>
										<span>  &sim;  </span>
										<?php echo ( $this->date_picker( 'slzexploore_cruise_meta['. $prefix .'end_date]',
																			$this->get_field( $data_meta, 'end_date' ),
																			array( 'class' => 'slz-block-half',
																			'placeholder' => esc_html__( 'YYYY-MM-DD', 'slzexploore-core' ),
																					'readonly' => 'readonly' ) ) );?>
										<p class="description"><?php esc_html_e( 'Example: 2016-06-01', 'slzexploore-core' );?></p>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Duration', 'slzexploore-core' );?></label>
							<span class="f-right"><?php $this->tooltip_html( esc_html__( 'Duration information in the cruise.', 'slzexploore-core' ) );?></span>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_cruise_meta['. $prefix .'duration]',
																$this->get_field( $data_meta, 'duration' ),
																array( 'class' => 'slz-block' ) ) );?>
							<p class="description"><?php esc_html_e( 'Example: 4 days', 'slzexploore-core' );?></p>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Price Per Adult', 'slzexploore-core' );?></label>
							<span class="f-right"><?php $this->tooltip_html( esc_html__( 'Price for an adult in the cruise.', 'slzexploore-core' ) );?></span>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_cruise_meta['. $prefix .'price_adult]',
																$this->get_field( $data_meta, 'price_adult' ),
																array( 'class' => 'slz-block-half' ) ) );?>
							<p class="description"><?php esc_html_e( 'Example: 9800', 'slzexploore-core' );?></p>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Price Per Child', 'slzexploore-core' );?></label>
							<span class="f-right"><?php $this->tooltip_html( esc_html__( 'Price for an child in the cruise.', 'slzexploore-core' ) );?></span>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_cruise_meta['. $prefix .'price_child]',
																$this->get_field( $data_meta, 'price_child' ),
																array( 'class' => 'slz-block-half' ) ) );?>
							<p class="description"><?php esc_html_e( 'Example: 1000', 'slzexploore-core' );?></p>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Available Seats', 'slzexploore-core' );?></label>
							<span class="f-right"><?php $this->tooltip_html( esc_html__( 'Max people in the cruise.', 'slzexploore-core' ) );?></span>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_cruise_meta['. $prefix .'available_seat]',
																$this->get_field( $data_meta, 'available_seat' ),
																array( 'class' => 'slz-block-half' ) ) );?>
							<p class="description"><?php esc_html_e( 'Example: 10', 'slzexploore-core' );?></p>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Destination', 'slzexploore-core' );?></label>
							<span class="f-right"><?php $this->tooltip_html( esc_html__( 'Destination of this cruise', 'slzexploore-core' ) );?></span>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_cruise_meta['. $prefix .'destination]',
																$this->get_field( $data_meta, 'destination' ),
																array( 'class' => 'slz-block' ) ) );?>
							<p class="description"><?php esc_html_e( 'Example: London, UK', 'slzexploore-core' );?></p>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Is Discount?', 'slzexploore-core' );?></label>
							<span class="f-right"><?php $this->tooltip_html( esc_html__( 'Add discount this cruise.', 'slzexploore-core' ) );?></span>
						</th>
						<td><label>
							<?php
							echo ( $this->check_box( 'slzexploore_cruise_meta['. $prefix .'is_discount]',
															$this->get_field( $data_meta, 'is_discount'),
														array( 'class'=>'cruise_show_discount') ) );
							esc_html_e( 'Discount this cruise', 'slzexploore-core' );
							?></label>
						</td>
					</tr>
					<tr class="check-cruise_show_discount slz-hide">
						<th scope="row">
							<label><?php esc_html_e( 'Discount Rate (%)', 'slzexploore-core' );?></label>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_cruise_meta['. $prefix .'discount_rate]',
																$this->get_field( $data_meta, 'discount_rate' ),
																array( 'class' => 'slz-block-half' ) ) );?>
							<p class="description"><?php esc_html_e( 'Example: 35', 'slzexploore-core' );?></p>
						</td>
					</tr>
					<tr class="check-cruise_show_discount slz-hide last">
						<th scope="row">
							<label><?php esc_html_e( 'Discount Text', 'slzexploore-core' );?></label>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_cruise_meta['. $prefix .'discount_text]',
																$this->get_field( $data_meta, 'discount_text' ),
																array( 'class' => 'slz-block' ) ) );?>
							<p class="description"><?php esc_html_e( 'Example: Sale Off', 'slzexploore-core' );?></p>
						</td>
					</tr>
					<tr class="cruise-status last">
						<th scope="row">
							<label><?php esc_html_e( 'Status', 'slzexploore-core' );?></label>
							<span class="f-right"><?php $this->tooltip_html( esc_html__( 'cruise status.', 'slzexploore-core' ) );?></span>
						</th>
						<td>
							<?php
							echo ( $this->drop_down_list('slzexploore_cruise_meta['. $prefix .'status]',
												$this->get_field( $data_meta, 'status' ),
												$this->get_field( $params, 'cruise_status' ),
												array('class' => 'slz-block-half f-left') ) );
							$new_link = 'edit-tags.php?taxonomy=slzexploore_cruise_status&post_type=slzexploore_cruise';
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
							<?php echo ( $this->text_field( 'slzexploore_cruise_meta['. $prefix .'address]',
															$this->get_field( $data_meta, 'address' ),
															array( 'class' => 'slz-block slzexploore_core-map-address ui-autocomplete-input' ) ) );?>
							<?php echo ( $this->hidden_field( 'slzexploore_cruise_meta['. $prefix .'location]',
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
			<div id="slz-tab-cruise-gallery" class="tab-content">
				<table class="form-table">
					<tr class="last">
						<th scope="row" colspan="2">
							<label><?php esc_html_e( 'Show Gallery?', 'slzexploore-core' );?></label>
							<p class="description"><?php esc_html_e( 'Show/ Hide gallery in cruise detail.', 'slzexploore-core' );?></p>
						</th>
					</tr>
					<tr>
						<td colspan="2">
							<?php echo ( $this->radio_button_list( 'slzexploore_cruise_meta['. $prefix .'show_gallery]',
																		$this->get_field( $data_meta, 'show_gallery'),
																		$display_params,
																		$html_options ) );?>
						</td>
					</tr>
					<tr class="last">
						<th scope="row" colspan="2">
							<label><?php esc_html_e( 'Gallery Images', 'slzexploore-core' );?></label>
							<p class="description"><?php esc_html_e( 'Images should have minimum size: 900x500. Bigger size images will be cropped automatically.', 'slzexploore-core' );?></p>
						</th>
					</tr>
					<tr class="last">
						<td colspan="2">
							<?php $this->gallery( 'slzexploore_cruise_meta['. $prefix .'gallery_ids]', $data_meta['gallery_ids'] ); ?>
						</td>
					</tr>					
				</table>
			</div>
			<!-- Team Information-->
			<div id="slz-tab-cabin-type" class="tab-content">
				<table class="form-table">
					<tr class="last">
						<th scope="row" colspan="2">
							<label><?php esc_html_e( 'Show Cabin Types Box?', 'slzexploore-core' );?></label>
							<p class="description"><?php esc_html_e( 'Show/Hide cabin types box in detail page.', 'slzexploore-core' );?></p>
						</th>
					</tr>
					<tr>
						<td colspan="2">
							<?php echo ( $this->radio_button_list('slzexploore_cruise_meta['. $prefix .'show_cabin_type]',
																$this->get_field( $data_meta, 'show_cabin_type'),
																$display_params,
																array( 'id' => 'show_cabin_type',
																		'separator' => '&nbsp;&nbsp;' ) ) );?>
						</td>
					</tr>
					<tr class="last">
						<th scope="row" colspan="2">
							<label><?php esc_html_e( 'Cabin Types', 'slzexploore-core' );?></label>
						</th>
					</tr>
					<tr class="last">
						<td colspan="2">
							<?php 
								$new_cabin_link = 'post-new.php?post_type=slzexploore_cabin';
								printf('<a href="%1$s" title="%2$s">%2$s</a>',
										esc_url( $new_cabin_link ),
										esc_html__( 'Add new cabin type', 'slzexploore-core' )
									);
								$cabin_ids = $this->get_field( $data_meta, 'cabin_type');
								if( !empty( $cabin_ids ) ) {
									echo '<ul>';
									$arr_cabin = explode( ',', $cabin_ids );
									foreach( $arr_cabin as $r_id ) {
										if( !empty( $r_id ) ) {
											$editlink = get_edit_post_link( $r_id );
											printf('<li>%1$s
														<a href="%2$s" title="%3$s"><i class="fa fa-pencil"></i></a>
														<a href="javascript:void(0);" title="" data-id="%4$s" class="delete-cabin">
															<i class="fa fa-trash"></i></a>
													</li>',
													get_the_title ( $r_id ),
													esc_url( $editlink ),
													esc_attr__( 'Edit', 'slzexploore-core' ),
													esc_attr( $r_id )
												);
										}
									}
									echo '</ul>';
									echo ( $this->hidden_field('slzexploore_cruise_meta['. $prefix .'cabin_type]',
																$this->get_field( $data_meta, 'cabin_type'),
																array( 'class' => 'cabin_type') ) );
								}
							?>
						</td>
					</tr>
				</table>
			</div>
			<!-- Deposit-->
			<div id="slz-tab-cruise-deposit" class="tab-content">
				<table class="form-table">
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Enable Deposit', 'slzexploore-core' );?></label>
							<span class="f-right"><?php $this->tooltip_html( esc_html__( 'Enable Deposit', 'slzexploore-core' ) );?></span>
						</th>
						<td>
							<?php echo ( $this->drop_down_list( 'slzexploore_cruise_meta['. $prefix .'is_deposit]',
																$this->get_field( $data_meta, 'is_deposit' ),
																$yes_no,
																array( 'class' => 'slz-block-half' ) ) );?>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Deposit Unit', 'slzexploore-core' );?></label>
							<span class="f-right"><?php $this->tooltip_html( esc_html__( 'Fixed Amount apply for each cabin price, Percentage apply for total price .', 'slzexploore-core' ) );?></span>
						</th>
						<td>
							<?php echo ( $this->drop_down_list( 'slzexploore_cruise_meta['. $prefix .'deposit_type]',
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
							<?php echo ( $this->text_field( 'slzexploore_cruise_meta['. $prefix .'deposit_amount]',
																$this->get_field( $data_meta, 'deposit_amount' ),
																array( 'class' => 'slz-block-half' ) ) );?>
							<p class="description"><?php esc_html_e( 'Example: 100', 'slzexploore-core' );?></p>
						</td>
					</tr>
				</table>
			</div>
			<!-- Booking-->
			<div id="slz-tab-cruise-booking" class="tab-content">
				<table class="form-table">
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Booking Method', 'slzexploore-core' );?></label>
							<span class="f-right"><?php $this->tooltip_html( esc_html__( 'Choose booking method when click BOOK NOW button in cruise detail page. This option overidde value in theme option.', 'slzexploore-core' ) );?></span>
						</th>
						<td>
							<?php
								$booking_method = array(
									'' => esc_html__( 'Default', 'slzexploore-core' ),
									'booking' => esc_html__( 'Booking Form', 'slzexploore-core' ),
									'contact' => esc_html__( 'Contact Form', 'slzexploore-core' ),
									'both'    => esc_html__( 'Both', 'slzexploore-core' )
								);
								echo ( $this->radio_button_list( 'slzexploore_cruise_meta['. $prefix .'booking_method]',
																	$this->get_field( $data_meta, 'booking_method' ),
																	$booking_method,
																	$html_options ) );
							?>
						</td>
					</tr>
					<tr class="last">
						<th scope="row" colspan="2">
							<label><?php esc_html_e( 'Booking Email', 'slzexploore-core' );?></label>
							<p class="description"><?php esc_html_e( 'This email will receive user booking information when this cruise booked. Emails are separated by commas.', 'slzexploore-core' );?></p>
						</th>
					</tr>
					<tr class="last">
						<th>
							<label><?php esc_html_e( 'CC Email', 'slzexploore-core' );?></label>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_cruise_meta['. $prefix .'mail_cc]',
																$this->get_field( $data_meta, 'mail_cc' ),
																array( 'class' => 'slz-block' ) ) );?>
						</td>
					</tr>
					<tr class="last">
						<th>
							<label><?php esc_html_e( 'BCC Email', 'slzexploore-core' );?></label>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_cruise_meta['. $prefix .'mail_bcc]',
																$this->get_field( $data_meta, 'mail_bcc' ),
																array( 'class' => 'slz-block' ) ) );?>
						</td>
					</tr>
				</table>
			</div>
			<!-- Other-->
			<div id="slz-tab-cruise-other" class="tab-content">
				<table class="form-table">
					<tr class="hide">
						<th scope="row" colspan="2">
							<?php echo ( $this->check_box( 'slzexploore_cruise_meta['. $prefix .'hide_is_full]',
									$this->get_field( $data_meta,'hide_is_full') ) );?>
							<label><?php esc_html_e( 'Hide (it is fully booked) ?', 'slzexploore-core' );?></label>
							<p class="description"><?php esc_html_e( ' Auto hide this post when it is fully booked.', 'slzexploore-core' );?></p>
						</th>
						<td><label>
							<?php echo ( $this->check_box( 'slzexploore_cruise_meta['. $prefix .'hide_is_full]',
									$this->get_field( $data_meta,'hide_is_full') ) );?>
						</label></td>
					</tr>
					<tr class="last">
						<th scope="row" colspan="2">
							<label><?php esc_html_e( 'Mark The Cruise As Featured ?', 'slzexploore-core' );?></label>
						</th>
					</tr>
					<tr class="">
						<td colspan="2">
							<?php echo ( $this->radio_button_list( 'slzexploore_cruise_meta['. $prefix .'is_featured]',
																		$this->get_field( $data_meta, 'is_featured', 1 ),
																		$display_feature_params,
																		$html_options ) );?>
						</td>
					</tr>
					<?php 
					$is_featured = $this->get_field( $data_meta, 'is_featured', 1 );
					$feature_text = (empty($is_featured)) ? 'hide': '';
					?>
					<tr class=" feature-text <?php echo esc_attr($feature_text);?>">
						<th>
							<label><?php esc_html_e( 'Feature Text', 'slzexploore-core' );?></label>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_cruise_meta['. $prefix .'featured_text]',
																$this->get_field( $data_meta, 'featured_text' ),
																array('class'=>'slz-block')
																 ) );?>
							<p class="description"><?php esc_html_e( 'Enter content if you want to display featured ribbon on block. Ex: Hot, New, ...', 'slzexploore-core' );?></p>
						</td>
					</tr>
					<tr class="last">
						<th scope="row" colspan="2">
							<label><?php esc_html_e( 'Attachments', 'slzexploore-core' );?></label>
							<p class="description"><?php esc_html_e( 'Attach PDF files, Map images OR other documents related to the cruise.', 'slzexploore-core' );?></p>
						</th>
						
					</tr>
					<tr class="last">
						<td colspan="2"><?php 
							$this->upload_attachment( 'slzexploore_cruise_meta['. $prefix .'attachment_ids]', $attachment_ids );?>
						</td>
					</tr>
				</table>
			</div>

		</div>
	</div>
</div>