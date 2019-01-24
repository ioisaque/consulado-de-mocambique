<?php
$car_id             = get_the_ID();
$car_name           = get_the_title( $car_id );
$car_price          = get_post_meta( $car_id, 'slzexploore_car_price', true );
$car_number         = get_post_meta( $car_id, 'slzexploore_car_number', true );
$car_max_people     = get_post_meta( $car_id, 'slzexploore_car_max_people', true );
$is_deposit         = get_post_meta( $car_id, 'slzexploore_car_is_deposit', true );
$deposit_type       = get_post_meta( $car_id, 'slzexploore_car_deposit_type', true );
$deposit_amount     = get_post_meta( $car_id, 'slzexploore_car_deposit_amount', true );
$deposit_method     = array( 'full'   => esc_html__( 'Pay in Full ', 'slzexploore-core' ),
							'deposit' => esc_html__( 'Pay Deposit', 'slzexploore-core' ) );

$location_args      = array( 'hide_empty' => false, 'orderby' => 'term_id' );
$location_empty     = array('empty' => esc_html__( '-- Select Location --', 'slzexploore-core' ) );
$car_location       = Slzexploore_Core_Com::get_tax_options2name( 'slzexploore_car_location', $location_empty, $location_args );

$car_cat_ids        = wp_get_post_terms( $car_id, 'slzexploore_car_cat', array( 'fields' => 'ids' ) );
$booking_mail_cc    = get_post_meta( $car_id, 'slzexploore_car_mail_cc', true );
$booking_mail_bcc   = get_post_meta( $car_id, 'slzexploore_car_mail_bcc', true );

$yes_no             = array( '1' => esc_html__( 'Yes', 'slzexploore-core' ), '' => esc_html__( 'No', 'slzexploore-core' ) );
$car_item_ids = array();
if( !empty( $car_cat_ids ) ){
	$item_args          = array(
								'post_type'    => 'slzexploore_exitem',
								'meta_key'     => 'slzexploore_exitem_car_cat',
								'meta_value'   => ' ',
								'meta_compare' => '!='
							);
	$car_extra_items    = Slzexploore_Core_Com::get_post_id2title( $item_args, array(), false );
	foreach( $car_extra_items as $item_id => $item_name ){
		$item_car_cat = get_post_meta( $item_id, 'slzexploore_exitem_car_cat', true );
		$arr_car_cat = explode( ',', $item_car_cat );
		$arr_intersect = array_intersect( $car_cat_ids, $arr_car_cat );
		if( count( $arr_intersect ) > 0 ){
			$car_item_ids[] = $item_id;
		}
	}
}
$item_args        = array(
	'post_type'    => 'slzexploore_exitem',
	'meta_key'     => 'slzexploore_exitem_car_post',
	'meta_value'   => ' ',
	'meta_compare' => '!='
);
$car_extra_items = Slzexploore_Core_Com::get_post_id2title( $item_args, array(), false );
foreach ( $car_extra_items as $item_id => $item_name ) {
	$item_car_post = get_post_meta( $item_id, 'slzexploore_exitem_car_post', true );
	if ( in_array( $car_id, $item_car_post ) && ! in_array( $item_id, $car_item_ids ) ) {
		$car_item_ids[] = $item_id;
	}
}

$model = new Slzexploore_Core_Car();
$check_date = date('Y-m-d');
$discount   = $model->get_discount_number( $car_id, $check_date );

/*------------update contact form to booking----------*/

$show_ctf = Slzexploore::get_option('slz-car-booking-method');
$booking_method = get_post_meta( $car_id, 'slzexploore_car_booking_method', true );
if( !empty($booking_method) ){
	$show_ctf = $booking_method;
}
$cf7_form = Slzexploore::get_option('slz-car-cf7-booking');

echo '<div class="wrapper-btn margin-top70 slz-book-car slz-book-wrapper">';
if( $show_ctf == 'booking' || $show_ctf == 'both' ){
	printf('<a href="%1$s" class="btn btn-maincolor btn-book" data-id="%3$s">%2$s</a>',
		esc_url( get_permalink() ),
		esc_html__( 'book now', 'slzexploore-core' ),
		esc_attr( $car_id )
	);
}
if( !empty($cf7_form) && ( $show_ctf == 'contact' || $show_ctf == 'both' ) ){

	$admin_mail = get_option('admin_email');
	$json_data = array();
	$json_data['name'] = $car_name;
	$json_data['url'] =get_permalink($car_id);
	$booking_mail_cc = !empty($booking_mail_cc) ? $booking_mail_cc: $admin_mail;
	$booking_mail_bcc = !empty($booking_mail_bcc) ? $booking_mail_bcc: $admin_mail;
	$json_data['mail_cc'] = $booking_mail_cc;
	$json_data['mail_bcc'] = $booking_mail_bcc;
	
	$json_data = json_encode($json_data);
	$post_type  = 'car';
	?>
	<a class="btn btn-maincolor btn-info cf7-book " data-toggle="modal" data-target="#booking-form" data-id="<?php echo esc_attr( $car_id );?>" data-json='<?php echo $json_data;?>' data-type="<?php echo esc_attr( esc_attr($post_type) );?>"><?php echo esc_html__( 'contact us', 'slzexploore-core' )?></a>
	<?php
}
echo '</div>';


$all_container_css = slzexploore_get_container_css();
extract($all_container_css);
$cls_sidebar = '';
if( $sidebar_css != 'hide' ){
	$cls_sidebar = 'has-sidebar';
}
?>
<div class="slz-booking-block timeline-book-block <?php echo esc_attr( $cls_sidebar ); ?>">
	<div class="find-widget find-hotel-widget widget new-style">
		<h4 class="title-widgets"><?php esc_html_e( 'Book Car', 'slzexploore-core' ) ?></h4>
		<div class="loading">
			<div class='spinner sk-spinner-wave'>
				<div class='rect1'></div><div class='rect2'></div><div class='rect3'></div>
				<div class='rect4'></div><div class='rect5'></div>
			</div>
		</div>
		<div class="content-widget car-booking slz-booking-wrapper">
			<div class="booking-info table-responsive">
				<table class="table">
					<tr>
						<th><?php esc_html_e( 'Car Name', 'slzexploore-core' ); ?></th>
						<th><?php esc_html_e( 'Price Per Day', 'slzexploore-core' ); ?></th>
						<th><?php esc_html_e( 'Availabel Cars', 'slzexploore-core' ); ?></th>
						<th><?php esc_html_e( 'Max People', 'slzexploore-core' ); ?></th>
					</tr>
					<tr>
						<td class="name"><?php echo esc_html( $car_name ); ?></td>
						<td class="price"><?php echo apply_filters( 'slzexploore_booking_price', esc_html( $car_price ) );?></td>
						<td class="availabel"><?php echo esc_html( $car_number );?></td>
						<td><?php echo esc_html( $car_max_people );?></td>
					</tr>
				</table>
			</div>
			
			<div class="booking-total hide">
				<h4><?php esc_html_e( 'Booking Information', 'slzexploore-core' ); ?></h4>
				<div class="table-responsive">
					<table class="table">
						<tr>
							<th><?php esc_html_e( 'Name', 'slzexploore-core' ); ?></th>
							<th><?php esc_html_e( 'Price', 'slzexploore-core' ); ?></th>
							<th><?php esc_html_e( 'Days', 'slzexploore-core' ); ?></th>
							<th><?php esc_html_e( 'Quantity', 'slzexploore-core' ); ?></th>
							<th><?php esc_html_e( 'Total', 'slzexploore-core' ); ?></th>
						</tr>
						<tr>
							<td class="name"></td>
							<td class="price"></td>
							<td class="days"></td>
							<td class="quantity"></td>
							<td class="total"></td>
						</tr>
					</table>
				</div>
			</div>
			
			<form class="text-input small-margin-top booking-data">
				<div class="input-daterange">
					<div class="text-box-wrapper">
						<label class="tb-label">
							<?php esc_html_e( 'Start Date', 'slzexploore-core' ); ?>
						</label>
						<div class="input-group">
						<?php
							echo ( $this->text_field( 'date_from',
									date( 'Y-m-d' ),
									array(
										'class' => 'tb-input',
										'placeholder' => esc_html__( 'YYYY-MM-DD', 'slzexploore-core' ),
										'readonly' => 'readonly'
									) ) );
						?>
							<i class="tb-icon fa fa-calendar input-group-addon"></i>
						</div>
					</div>
					<div class="text-box-wrapper">
						<label class="tb-label">
							<?php esc_html_e( 'Return Date', 'slzexploore-core' ); ?>
						</label>
						<div class="input-group">
						<?php
							echo ( $this->text_field( 'date_to',
									date( 'Y-m-d' ),
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
				<div class="place text-box-wrapper">
					<label class="tb-label">
						<?php esc_html_e( 'Number of Cars', 'slzexploore-core' ); ?>
					</label>
					<div class="input-group">
						<button disabled data-type="minus" data-field="number" class="input-group-btn btn-minus">
							<span class="tb-icon fa fa-minus"></span>
						</button>
						<?php
							$number_atts = array( 'class' => 'tb-input count',
													'min' => '1',
													'max' => '100'
												);
							echo ( $this->input_field( 'number', 'number', 1, $number_atts) );
						?>
						<button data-type="plus" data-field="number" class="input-group-btn btn-plus">
							<span class="tb-icon fa fa-plus"></span>
						</button>
					</div>
				</div>
				<div class="place text-box-wrapper">
					<label class="tb-label">
						<?php esc_html_e( 'Drop-off Location', 'slzexploore-core' ); ?>
					</label>
					<div class="input-group">
					<?php
						echo ( $this->drop_down_list( 'location',
														'',
														$car_location,
														array( 'class' => 'tb-input selectbox' ) ) );
					?>
					</div>
				</div>
				<?php if( $is_deposit ): ?>
				<div class="text-box-wrapper">
					<label class="tb-label">
						<?php esc_html_e( 'Deposit Method', 'slzexploore-core' ); ?>
					</label>
					<div class="input-group">
					<?php
						echo ( $this->drop_down_list( 'deposit_method',
														'full',
														$deposit_method,
														array( 'class' => 'tb-input selectbox' ) ) );
						echo ( $this->hidden_field( 'deposit_type', $deposit_type ) );
						echo ( $this->hidden_field( 'deposit_amount', $deposit_amount ) );
					?>
					</div>
					<p class="description">
					<?php
						if( $deposit_type == 'percent' ){
							printf( esc_html__( 'Pay a %1$s%% deposit per total price.', 'slzexploore-core' ), esc_html( $deposit_amount ) );
						}
						else{
							printf( esc_html__( 'Pay a deposit of %1$s per car per day.', 'slzexploore-core' ), apply_filters( 'slzexploore_booking_price', esc_html( $deposit_amount ), false ) );
						}
					?>
					</p>
				</div>
				<?php endif; ?>
			</form>
			<!-- End text-input class -->
			
			<?php if( !empty( $car_item_ids ) ): ?>
			<div class="extra-item">
				<h4><?php esc_html_e( 'Extra Items', 'slzexploore-core' ); ?></h4>
				<p class="des"><?php esc_html_e( 'Please select the extra items you wish to be included with your car using the controls you see below.', 'slzexploore-core' ); ?></p>
				<div class="table-responsive">
					<table class="table">
						<tr>
							<th><?php esc_html_e( 'Item', 'slzexploore-core' ); ?></th>
							<th><?php esc_html_e( 'Price', 'slzexploore-core' ); ?></th>
							<th><?php esc_html_e( 'Per Day', 'slzexploore-core' ); ?></th>
							<th><?php esc_html_e( 'Quantity', 'slzexploore-core' ); ?></th>
						</tr>
						<?php
							foreach( $car_item_ids as $item_id ){
								$item_name    = get_the_title( $item_id );
								$item_content = apply_filters('the_content', get_post_field( 'post_content', $item_id ) );
								$item_price   = get_post_meta( $item_id, 'slzexploore_exitem_price', true );
								$item_is_day  = get_post_meta( $item_id, 'slzexploore_exitem_is_price_day', true );
								$item_maximum = get_post_meta( $item_id, 'slzexploore_exitem_max_items', true );
								$item_is_fixed = get_post_meta( $item_id, 'slzexploore_exitem_fixed_item', true );
								$item_quanlity = '<select class="tb-input selectbox quanlity" name="quantity_'.$item_id.'">';
								if( $item_is_fixed ){
									$item_quanlity .= sprintf( '<option value="%1$s">%1$s</option>', $item_maximum );
								}
								else{
									for( $i = 0; $i <= intval( $item_maximum ); $i++ ){
										$item_quanlity .= sprintf( '<option value="%1$s">%1$s</option>', $i );
									}
								}
								$item_quanlity .= '</select>';
								$item_price = apply_filters( 'slzexploore_booking_price', esc_html( $item_price ) );
								printf('<tr>
											<td class="item-info"><h5>%1$s</h5> %2$s</td>
											<td class="item-price">%3$s</td>
											<td class="is-day">%4$s<span class="hide">%5$s</span></td>
											<td class="number-item">%6$s</td>
										</tr>',
										esc_html( $item_name ),
										wp_kses_post( $item_content ),
										$item_price,
										esc_html( $yes_no[$item_is_day] ),
										esc_html( $item_is_day ),
										wp_kses( $item_quanlity, array(
																'select' => array(
																	'class' => array(),
																	'name' => array()
																),
																'option' => array(
																	'value' => array()
																)
															) )
									);
							}
						?>
					</table>
				</div>
				<div class="booking-des hide"></div>
			</div><!-- End extra items -->
			<?php endif; ?>
			
			<div class="summary">
				<h4><?php esc_html_e( 'SUMMARY', 'slzexploore-core' ); ?></h4>
				<table>
					<tr>
						<th><?php esc_html_e( 'Booking', 'slzexploore-core' ); ?></th>
						<td class="booking-price">
							<?php echo apply_filters( 'slzexploore_booking_price', '' ); ?>
						</td>
					</tr>
					<tr>
						<th><?php esc_html_e( 'Extra Items', 'slzexploore-core' ); ?></th>
						<td class="extra-total">
							<?php echo apply_filters( 'slzexploore_booking_price', '' ); ?>
						</td>
					</tr>
					<?php if( $discount ){ ?>
					<tr>
						<th><?php esc_html_e( 'Discount', 'slzexploore-core' ); ?></th>
						<td class="discount">
							<span><?php echo esc_html( $discount ); ?></span>%
						</td>
					</tr>
					<?php } ?>
					<tr>
						<th><?php esc_html_e( 'Total', 'slzexploore-core' ); ?></th>
						<td class="total-price">
							<?php echo apply_filters( 'slzexploore_booking_price', '' ); ?>
						</td>
					</tr>
				</table>
			</div><!-- End summary class -->
			
			<form class="customer-info hide">
				<h4><?php esc_html_e( 'Customer Information', 'slzexploore-core' ); ?></h4>
				<?php
					$user_meta = array();
					$user_id = get_current_user_id();
					if( $user_id ){
						$user_meta['first_name'] = get_user_meta( $user_id, 'first_name', true );
						$user_meta['last_name'] = get_user_meta( $user_id, 'last_name', true );
						$user_meta['email'] = wp_get_current_user()->data->user_email;
					}
				?>
				<div class="first-name text-box-wrapper">
					<label class="tb-label"><?php esc_html_e( 'First Name', 'slzexploore-core' ) ?></label>
					<span class="required">*</span>
					<div class="input-group">
					<?php
						echo ( $this->text_field( 'first_name',
								$this->get_field( $user_meta, 'first_name', '' ),
								array(
									'class' => 'tb-input',
									'placeholder' => esc_html__( 'Write your first name', 'slzexploore-core' )
								) ) );
					?>
					</div>
					<div class="validate-message hide">
						<?php esc_html_e( 'First Name is required.', 'slzexploore-core' ) ?></div>
				</div>
				<div class="last-name text-box-wrapper">
					<label class="tb-label"><?php esc_html_e( 'Last Name', 'slzexploore-core' ) ?></label>
					<span class="required">*</span>
					<div class="input-group">
					<?php
						echo ( $this->text_field( 'last_name',
								$this->get_field( $user_meta, 'last_name', '' ),
								array(
									'class' => 'tb-input',
									'placeholder' => esc_html__( 'Write your last name', 'slzexploore-core' )
								) ) );
					?>
					</div>
					<div class="validate-message hide">
						<?php esc_html_e( 'Last Name is required.', 'slzexploore-core' ) ?></div>
				</div>
				<div class="email text-box-wrapper">
					<label class="tb-label"><?php esc_html_e( 'Email', 'slzexploore-core' ) ?></label>
					<span class="required">*</span>
					<div class="input-group">
					<?php
						echo ( $this->text_field( 'email',
								$this->get_field( $user_meta, 'email', '' ),
								array(
									'class' => 'tb-input',
									'placeholder' => esc_html__( 'Write your email address', 'slzexploore-core' )
								) ) );
					?>
					</div>
					<div class="validate-message hide">
						<?php esc_html_e( 'Email is required.', 'slzexploore-core' ) ?></div>
					<div class="invalid-message hide">
						<?php esc_html_e( 'Please enter a valid email.', 'slzexploore-core' ) ?></div>
				</div>
				<div class="phone text-box-wrapper">
					<label class="tb-label"><?php esc_html_e( 'Number Phone', 'slzexploore-core' ) ?></label>
					<span class="required">*</span>
					<div class="input-group">
					<?php
						echo ( $this->text_field( 'phone', '',
								array(
									'class' => 'tb-input',
									'placeholder' => esc_html__( 'Write your number phone', 'slzexploore-core' )
								) ) );
					?>
					</div>
					<div class="validate-message hide">
						<?php esc_html_e( 'Number Phone is required.', 'slzexploore-core' ) ?></div>
				</div>
				<div class="place text-box-wrapper">
					<label class="tb-label"><?php esc_html_e( 'Address', 'slzexploore-core' ) ?></label>
					<span class="required">*</span>
					<div class="input-group">
					<?php
						echo ( $this->text_field( 'address', '',
								array(
									'class' => 'tb-input',
									'placeholder' => esc_html__( 'Write your address', 'slzexploore-core' )
								) ) );
					?>
					</div>
					<div class="validate-message hide">
						<?php esc_html_e( 'Address is required.', 'slzexploore-core' ) ?></div>
				</div>
				<div class="note text-box-wrapper">
					<label class="tb-label"><?php esc_html_e( 'Note', 'slzexploore-core' ) ?>:</label>
					<div class="input-group">
					<?php
						echo ( $this->text_area( 'customer_des', '',
									array(
										'class' => 'tb-input',
										'rows' => 3,
										'placeholder' => esc_html__( 'Write your note', 'slzexploore-core' )
									) ) );
					?>
					</div>
					<div class="validate-message hide">
						<?php esc_html_e( 'Note is required.', 'slzexploore-core' ) ?></div>
				</div>
			</form>
			
			<div class="err-message text-box-wrapper hide"></div>
			<div class="success-message text-box-wrapper hide">
			<?php
				esc_html_e('Thank You. Your booking have received and booking number is ','slzexploore-core');
			?>
				<span></span>
			</div>
			<div class="clearfix"></div>
			<div class="slz-btn-group">
			<?php
				$woo_checkout_active = Slzexploore::get_option('slz-woocommerce-checkout-active', 'enabled');
				$woo_checkout_cls = '';
				if ( SLZEXPLOORE_CORE_WOOCOMMERCE_ACTIVE && isset( $woo_checkout_active['car'] ) ){
					$woo_checkout_cls = 'slz-add-to-cart';
				}
				printf('<button data-hover="%1$s" class="btn btn-slide btn-check %4$s" data-id="%2$s">
							<span class="text">%3$s</span>
							<span class="icons fa fa-long-arrow-right"></span>
						</button>',
						esc_attr__( 'book now', 'slzexploore-core' ),
						esc_attr( $car_id ),
						esc_html__( 'book', 'slzexploore-core' ),
						esc_attr( $woo_checkout_cls )
					);
				printf('<button data-hover="%1$s" class="btn btn-slide btn-back hide" data-id="%2$s">
							<span class="text">%1$s</span>
							<span class="icons fa fa-long-arrow-left"></span>
						</button>',
						esc_attr__( 'back', 'slzexploore-core' ),
						esc_attr( $car_id )
					);
				printf('<button data-hover="%1$s" class="btn btn-slide btn-next hide" data-id="%2$s">
							<span class="text">%1$s</span>
							<span class="icons fa fa-long-arrow-right"></span>
						</button>',
						esc_attr__( 'next', 'slzexploore-core' ),
						esc_attr( $car_id )
					);
			?>
			</div>
		</div>
	</div>
</div>

<!-- Modal -->
<div id="booking-form" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
	    <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal">&times;</button>
	    </div>
      	<div class="modal-body">
	      	<div class="slz-booking-from contact-box">
		      	<?php
		      	if(!empty($cf7_form)){
		      		$uri = plugins_url().'/contact-form-7/includes/js/scripts.js';
					printf('<div class="cf7_js_uri hide">%s</div>', esc_url( $uri ) );
		      	 	echo do_shortcode('[contact-form-7 id="'.esc_attr($cf7_form).'"]');
		      	}
		      	?>
		    </div>
      </div>
    </div>
  </div>
</div>