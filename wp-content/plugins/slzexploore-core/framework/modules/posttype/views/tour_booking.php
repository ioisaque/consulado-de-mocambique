<?php
	$prefix = 'slzexploore_tbook_';
?>
<div class="tab-panel">
	<ul class="tab-list">
		<li class="active">
			<a href="slz-tab-tour-booking"><?php esc_html_e( 'Booking', 'slzexploore-core' );?></a>
		</li>
		<li class="">
			<a href="slz-tab-tour-customer"><?php esc_html_e( 'Customer', 'slzexploore-core' );?></a>
		</li>
	</ul>
	<div class="tab-container">
		<div class="tab-wrapper slz-page-meta">
			<!-- General -->
			<div id="slz-tab-tour-booking" class="tab-content active slzexploore_core-map-metabox">
				<table class="form-table">
					<tr class="booking-status">
						<th scope="row">
							<label><?php esc_html_e( 'Booking Status', 'slzexploore-core' );?></label>
						</th>
						<td>
							<?php
							$params = array('empty' => esc_html__( '-- None --', 'slzexploore-core' ) );
							$args = array('hide_empty' => false);
							$booking_status = Slzexploore_Core_Com::get_tax_options_id2name( 'slzexploore_tbook_status', 
																					$params, $args );
							echo ( $this->drop_down_list('slzexploore_tbook_meta['. $prefix .'status]',
												$this->get_field( $data_meta, 'status' ),
												$booking_status,
												array('class' => 'slz-block-half f-left') ) );
							$new_link = 'edit-tags.php?taxonomy=slzexploore_tbook_status&post_type=slzexploore_tbook';
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
							<label><?php esc_html_e( 'Tour', 'slzexploore-core' );?></label>
						</th>
						<td>
							<?php
							$params = array('empty' => esc_html__( '-- All Tour --', 'slzexploore-core' ) );
							$args = array('post_type' => 'slzexploore_tour');
							$arr_tour = Slzexploore_Core_Com::get_post_id2title( $args, $params );
							echo ( $this->drop_down_list('slzexploore_tbook_meta['. $prefix .'tour]',
												$this->get_field( $data_meta, 'tour' ),
												$arr_tour,
												array('class' => 'slz-block-half') ) );
							?>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Tour Date', 'slzexploore-core' );?></label>
						</th>
						<td>
							<?php echo ( $this->date_picker( 'slzexploore_tbook_meta['. $prefix .'tour_date]',
															$this->get_field( $data_meta, 'tour_date' ),
															array( 'class' => 'slz-block-half' ) ) );?>
							<p class="description"><?php esc_html_e( 'Example: 2016-07-12', 'slzexploore-core' );?></p>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Adults', 'slzexploore-core' );?></label>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_tbook_meta['. $prefix .'adults]',
																$this->get_field( $data_meta, 'adults' ),
																array( 'class' => 'slz-block-half' ) ) );?>
							<p class="description"><?php esc_html_e( 'Number of adults in the tour. Example: 10', 'slzexploore-core' );?></p>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Children', 'slzexploore-core' );?></label>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_tbook_meta['. $prefix .'children]',
																$this->get_field( $data_meta, 'children' ),
																array( 'class' => 'slz-block-half' ) ) );?>
							<p class="description"><?php esc_html_e( 'Number of children in the tour. Example: 1', 'slzexploore-core' );?></p>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Infant', 'slzexploore-core' );?></label>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_tbook_meta['. $prefix .'infant]',
																$this->get_field( $data_meta, 'infant' ),
																array( 'class' => 'slz-block-half' ) ) );?>
							<p class="description"><?php esc_html_e( 'Example: 1', 'slzexploore-core' );?></p>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Tour Price', 'slzexploore-core' );?></label>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_tbook_meta['. $prefix .'tour_price]',
																$this->get_field( $data_meta, 'tour_price' ),
																array( 'class' => 'slz-block-half' ) ) );?>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Extra Price', 'slzexploore-core' );?></label>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_tbook_meta['. $prefix .'extra price]',
																$this->get_field( $data_meta, 'extra_price' ),
																array( 'class' => 'slz-block-half' ) ) );?>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Total Price', 'slzexploore-core' );?></label>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_tbook_meta['. $prefix .'total_price]',
																$this->get_field( $data_meta, 'total_price' ),
																array( 'class' => 'slz-block-half' ) ) );?>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Deposit Amount', 'slzexploore-core' );?></label>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_tbook_meta['. $prefix .'deposit_amount]',
																$this->get_field( $data_meta, 'deposit_amount' ),
																array( 'class' => 'slz-block-half' ) ) );?>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Future Payments', 'slzexploore-core' );?></label>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_tbook_meta['. $prefix .'future_payment]',
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
								echo ( $this->text_field( 'slzexploore_tbook_meta['. $prefix .'order]',
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
							<?php echo ( $this->text_field( 'slzexploore_tbook_meta['. $prefix .'sku]',
																$this->get_field( $data_meta, 'sku' ),
																array( 'class' => 'slz-block-half' ) ) );?>
						</td>
					</tr>
					
					<tr class="last">
						<th scope="row">
							<label><?php esc_html_e( 'Description', 'slzexploore-core' );?></label>
						</th>
						<td>
							<?php echo ( $this->text_area( 'slzexploore_tbook_meta['. $prefix .'description]',
																$this->get_field( $data_meta, 'description' ),
																array( 'class' => 'slz-block', 'rows' => 5 ) ) );?>
						</td>
					</tr>
				</table>
			</div>
			<!-- Gallery Images -->
			<div id="slz-tab-tour-customer" class="tab-content">
				<table class="form-table">
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'First Name', 'slzexploore-core' );?></label>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_tbook_meta['. $prefix .'first_name]',
																$this->get_field( $data_meta, 'first_name' ),
																array( 'class' => 'slz-block' ) ) );?>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Last Name', 'slzexploore-core' );?></label>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_tbook_meta['. $prefix .'last_name]',
																$this->get_field( $data_meta, 'last_name' ),
																array( 'class' => 'slz-block' ) ) );?>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Email', 'slzexploore-core' );?></label>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_tbook_meta['. $prefix .'email]',
																$this->get_field( $data_meta, 'email' ),
																array( 'class' => 'slz-block' ) ) );?>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Company Name', 'slzexploore-core' );?></label>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_tbook_meta['. $prefix .'company]',
																$this->get_field( $data_meta, 'company' ),
																array( 'class' => 'slz-block' ) ) );?>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Phone', 'slzexploore-core' );?></label>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_tbook_meta['. $prefix .'phone]',
																$this->get_field( $data_meta, 'phone' ),
																array( 'class' => 'slz-block' ) ) );?>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Country', 'slzexploore-core' );?></label>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_tbook_meta['. $prefix .'country]',
																$this->get_field( $data_meta, 'country' ),
																array( 'class' => 'slz-block' ) ) );?>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Address', 'slzexploore-core' );?></label>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_tbook_meta['. $prefix .'address]',
																$this->get_field( $data_meta, 'address' ),
																array( 'class' => 'slz-block' ) ) );?>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'City', 'slzexploore-core' );?></label>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_tbook_meta['. $prefix .'city]',
																$this->get_field( $data_meta, 'city' ),
																array( 'class' => 'slz-block' ) ) );?>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Postcode/Zip', 'slzexploore-core' );?></label>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_tbook_meta['. $prefix .'postcode]',
																$this->get_field( $data_meta, 'postcode' ),
																array( 'class' => 'slz-block' ) ) );?>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Customer IP', 'slzexploore-core' );?></label>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_tbook_meta['. $prefix .'customer_ip]',
																$this->get_field( $data_meta, 'customer_ip' ),
																array( 'class' => 'slz-block' ) ) );?>
						</td>
					</tr>
					<tr class="last">
						<th scope="row">
							<label><?php esc_html_e( 'Customer Notes', 'slzexploore-core' );?></label>
						</th>
						<td>
							<?php echo ( $this->text_area( 'slzexploore_tbook_meta['. $prefix .'customer_des]',
																$this->get_field( $data_meta, 'customer_des' ),
																array( 'class' => 'slz-block', 'rows' => 5 ) ) );?>
						</td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</div>