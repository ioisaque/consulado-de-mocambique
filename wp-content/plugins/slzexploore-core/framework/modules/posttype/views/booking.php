<?php
	$prefix = 'slzexploore_book_';
?>
<div class="tab-panel">
	<ul class="tab-list">
		<li class="active">
			<a href="slz-tab-accommodation-booking"><?php esc_html_e( 'Booking', 'slzexploore-core' );?></a>
		</li>
		<li class="">
			<a href="slz-tab-accommodation-customer"><?php esc_html_e( 'Customer', 'slzexploore-core' );?></a>
		</li>
	</ul>
	<div class="tab-container">
		<div class="tab-wrapper slz-page-meta">
			<!-- General -->
			<div id="slz-tab-accommodation-booking" class="tab-content active slzexploore_core-map-metabox">
				<table class="form-table">
					<tr class="booking-status">
						<th scope="row">
							<label><?php esc_html_e( 'Status', 'slzexploore-core' );?></label>
						</th>
						<td>
						<?php
							$params = array('empty' => esc_html__( '-- None --', 'slzexploore-core' ) );
							$args = array('hide_empty' => false);
							$booking_status = Slzexploore_Core_Com::get_tax_options_id2name( 'slzexploore_book_status', $params, $args );
							echo ( $this->drop_down_list('slzexploore_book_meta['. $prefix .'status]',
												$this->get_field( $data_meta, 'status' ),
												$booking_status,
												array('class' => 'slz-block-half f-left') ) );
							$new_link = 'edit-tags.php?taxonomy=slzexploore_book_status&post_type=slzexploore_book';
							printf('<a href="%1$s" title="%2$s"><i class="fa fa-plus-square" aria-hidden="true"></i></a>',
									esc_attr( $new_link ),
									esc_attr__( 'Add new status', 'slzexploore-core' )
								);
						?>
						
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Acommodation', 'slzexploore-core' );?></label>
						</th>
						<td>
							<?php
							$params = array('empty' => esc_html__( '-- All Accommodation --', 'slzexploore-core' ) );
							$args = array('post_type' => 'slzexploore_hotel');
							$arr_accommodation = Slzexploore_Core_Com::get_post_id2title( $args, $params );
							echo ( $this->drop_down_list('slzexploore_book_meta['. $prefix .'accommodation]',
												$this->get_field( $data_meta, 'accommodation' ),
												$arr_accommodation,
												array('class' => 'slz-block-half slz-accommodation-id') ) );
							?>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Room Type', 'slzexploore-core' );?></label>
						</th>
						<td class="slz-room-type">
						<?php
							$arr_room_type = array();
							$room_params = array('empty' => esc_html__( '-- All Room Types --', 'slzexploore-core' ) );
							$room_args = array('post_type' => 'slzexploore_room');
							$accommodation_id = $this->get_field( $data_meta, 'accommodation' );
							if( !empty( $accommodation_id ) ) {
								$room_args['meta_key'] = 'slzexploore_room_accommodation';
								$room_args['meta_value'] = $accommodation_id;
							}
							$arr_room_type = Slzexploore_Core_Com::get_post_id2title( $room_args, $room_params );
							echo ( $this->drop_down_list('slzexploore_book_meta['. $prefix .'room_type]',
															$this->get_field( $data_meta, 'room_type' ),
															$arr_room_type,
															array('class' => 'slz-block-half') ) );
						?>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Number Of Rooms', 'slzexploore-core' );?></label>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_book_meta['. $prefix .'number_room]',
																$this->get_field( $data_meta, 'number_room' ),
																array( 'class' => 'slz-block-half' ) ) );?>
							<p class="description"><?php esc_html_e( 'Example: 2', 'slzexploore-core' );?></p>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Check-in Date', 'slzexploore-core' );?></label>
						</th>
						<td>
							<?php echo ( $this->date_picker( 'slzexploore_book_meta['. $prefix .'check_in_date]',
															$this->get_field( $data_meta, 'check_in_date' ),
															array( 'class' => 'slz-block-half' ) ) );?>
							<p class="description"><?php esc_html_e( 'Example: 2016-07-12', 'slzexploore-core' );?></p>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Check-out Date', 'slzexploore-core' );?></label>
						</th>
						<td>
							<?php echo ( $this->date_picker( 'slzexploore_book_meta['. $prefix .'check_out_date]',
															$this->get_field( $data_meta, 'check_out_date' ),
															array( 'class' => 'slz-block-half' ) ) );?>
							<p class="description"><?php esc_html_e( 'Example: 2016-07-15', 'slzexploore-core' );?></p>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Adults', 'slzexploore-core' );?></label>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_book_meta['. $prefix .'adults]',
																$this->get_field( $data_meta, 'adults' ),
																array( 'class' => 'slz-block-half' ) ) );?>
							<p class="description"><?php esc_html_e( 'Example: 4', 'slzexploore-core' );?></p>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Children', 'slzexploore-core' );?></label>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_book_meta['. $prefix .'children]',
																$this->get_field( $data_meta, 'children' ),
																array( 'class' => 'slz-block-half' ) ) );?>
							<p class="description"><?php esc_html_e( 'Example: 1', 'slzexploore-core' );?></p>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Infant', 'slzexploore-core' );?></label>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_book_meta['. $prefix .'infant]',
																$this->get_field( $data_meta, 'infant' ),
																array( 'class' => 'slz-block-half' ) ) );?>
							<p class="description"><?php esc_html_e( 'Example: 1', 'slzexploore-core' );?></p>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Room Price', 'slzexploore-core' );?></label>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_book_meta['. $prefix .'room_price]',
																$this->get_field( $data_meta, 'room_price' ),
																array( 'class' => 'slz-block-half' ) ) );?>
							<p class="description"><?php esc_html_e( 'Example: 30', 'slzexploore-core' );?></p>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Extra Price', 'slzexploore-core' );?></label>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_book_meta['. $prefix .'extra_price]',
																$this->get_field( $data_meta, 'extra_price' ),
																array( 'class' => 'slz-block-half' ) ) );?>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Total Price', 'slzexploore-core' );?></label>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_book_meta['. $prefix .'total]',
																$this->get_field( $data_meta, 'total' ),
																array( 'class' => 'slz-block-half' ) ) );?>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Deposit Amount', 'slzexploore-core' );?></label>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_book_meta['. $prefix .'deposit_amount]',
																$this->get_field( $data_meta, 'deposit_amount' ),
																array( 'class' => 'slz-block-half' ) ) );?>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Future Payments', 'slzexploore-core' );?></label>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_book_meta['. $prefix .'future_payment]',
																$this->get_field( $data_meta, 'future_payment' ),
																array( 'class' => 'slz-block-half' ) ) );?>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Order ID', 'slzexploore-core' );?></label>
						</th>
						<td>
							<?php
								echo ( $this->text_field( 'slzexploore_book_meta['. $prefix .'order]',
																$this->get_field( $data_meta, 'order' ),
																array( 'class' => 'slz-block-half' ) ) );
								$order_id = $this->get_field( $data_meta, 'order' );
								if( !empty( $order_id ) ){
									$order_url = get_edit_post_link( $order_id );
									printf( '<a href="%1$s" title="%2$s" class="view-order" target="_blank">%2$s</a>',
											esc_url( $order_url ),
											esc_html__( 'View Order', 'slzexploore-core' )
										);
								}
							?>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'SKU', 'slzexploore-core' );?></label>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_book_meta['. $prefix .'sku]',
																$this->get_field( $data_meta, 'sku' ),
																array( 'class' => 'slz-block-half' ) ) );?>
						</td>
					</tr>
					<tr class="last">
						<th scope="row">
							<label><?php esc_html_e( 'Description', 'slzexploore-core' );?></label>
						</th>
						<td>
							<?php echo ( $this->text_area( 'slzexploore_book_meta['. $prefix .'description]',
																$this->get_field( $data_meta, 'description' ),
																array( 'class' => 'slz-block', 'rows' => 6 ) ) );?>
						</td>
					</tr>
				</table>
			</div>
			<!-- Gallery Images -->
			<div id="slz-tab-accommodation-customer" class="tab-content">
				<table class="form-table">
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'First Name', 'slzexploore-core' );?></label>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_book_meta['. $prefix .'first_name]',
																$this->get_field( $data_meta, 'first_name' ),
																array( 'class' => 'slz-block' ) ) );?>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Last Name', 'slzexploore-core' );?></label>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_book_meta['. $prefix .'last_name]',
																$this->get_field( $data_meta, 'last_name' ),
																array( 'class' => 'slz-block' ) ) );?>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Email', 'slzexploore-core' );?></label>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_book_meta['. $prefix .'email]',
																$this->get_field( $data_meta, 'email' ),
																array( 'class' => 'slz-block' ) ) );?>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Company Name', 'slzexploore-core' );?></label>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_book_meta['. $prefix .'company]',
																$this->get_field( $data_meta, 'company' ),
																array( 'class' => 'slz-block' ) ) );?>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Phone', 'slzexploore-core' );?></label>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_book_meta['. $prefix .'phone]',
																$this->get_field( $data_meta, 'phone' ),
																array( 'class' => 'slz-block' ) ) );?>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Country', 'slzexploore-core' );?></label>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_book_meta['. $prefix .'country]',
																$this->get_field( $data_meta, 'country' ),
																array( 'class' => 'slz-block' ) ) );?>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Address', 'slzexploore-core' );?></label>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_book_meta['. $prefix .'address]',
																$this->get_field( $data_meta, 'address' ),
																array( 'class' => 'slz-block' ) ) );?>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'City', 'slzexploore-core' );?></label>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_book_meta['. $prefix .'city]',
																$this->get_field( $data_meta, 'city' ),
																array( 'class' => 'slz-block' ) ) );?>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Postcode/Zip', 'slzexploore-core' );?></label>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_book_meta['. $prefix .'postcode]',
																$this->get_field( $data_meta, 'postcode' ),
																array( 'class' => 'slz-block' ) ) );?>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Customer IP', 'slzexploore-core' );?></label>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_book_meta['. $prefix .'customer_ip]',
																$this->get_field( $data_meta, 'customer_ip' ),
																array( 'class' => 'slz-block' ) ) );?>
						</td>
					</tr>
					<tr class="last">
						<th scope="row">
							<label><?php esc_html_e( 'Customer Notes', 'slzexploore-core' );?></label>
						</th>
						<td>
							<?php echo ( $this->text_area( 'slzexploore_book_meta['. $prefix .'customer_des]',
																$this->get_field( $data_meta, 'customer_des' ),
																array( 'class' => 'slz-block', 'rows' => 6 ) ) );?>
						</td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</div>