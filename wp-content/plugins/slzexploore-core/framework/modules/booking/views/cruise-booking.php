<?php
$cruise_id         = get_the_ID();
$cruise_name       = get_post_meta( $cruise_id, 'slzexploore_cruise_display_title', true );
$cruise_seats      = get_post_meta( $cruise_id, 'slzexploore_cruise_available_seat', true );
$cruise_duration   = get_post_meta( $cruise_id, 'slzexploore_cruise_duration', true );
$cruise_date_type  = get_post_meta( $cruise_id, 'slzexploore_cruise_date_type', true );
$cruise_frequency  = get_post_meta( $cruise_id, 'slzexploore_cruise_frequency', true );
$cruise_weekly     = get_post_meta( $cruise_id, 'slzexploore_cruise_weekly', true );
$cruise_monthly    = get_post_meta( $cruise_id, 'slzexploore_cruise_monthly', true );
$cruise_start_date = get_post_meta( $cruise_id, 'slzexploore_cruise_start_date', true );
$cruise_end_date   = get_post_meta( $cruise_id, 'slzexploore_cruise_end_date', true );
$cruise_price_adult= get_post_meta( $cruise_id, 'slzexploore_cruise_price_adult', true );
$is_discount       = get_post_meta( $cruise_id, 'slzexploore_cruise_is_discount', true );
$discount_rate     = get_post_meta( $cruise_id, 'slzexploore_cruise_discount_rate', true );
$discount          = '';
$booking_mail_cc   = get_post_meta( $cruise_id, 'slzexploore_cruise_mail_cc', true );
$booking_mail_bcc  = get_post_meta( $cruise_id, 'slzexploore_cruise_mail_bcc', true );

if( $is_discount && $discount_rate ){
	$discount = $discount_rate;
}
$departure_date    = esc_html__( 'Anytime', 'slzexploore-core' );
$datepicker_options = array('type' => 'any');
$selected_date = date('Y-m-d');
if( !empty( $cruise_date_type ) ){
	if( $cruise_frequency == 'weekly' && !empty($cruise_weekly)  ){
		$weekly = array(
						'1' => esc_html__( 'Monday', 'slzexploore-core' ),
						'2' => esc_html__( 'Tuesday', 'slzexploore-core' ),
						'3' => esc_html__( 'Wednesday', 'slzexploore-core' ),
						'4' => esc_html__( 'Thursday', 'slzexploore-core' ),
						'5' => esc_html__( 'Friday', 'slzexploore-core' ),
						'6' => esc_html__( 'Saturday', 'slzexploore-core' ),
						'7' => esc_html__( 'Sunday', 'slzexploore-core' )
					);
		$arr_weekly = array();
		$datepicker_value = array('1' => '1','2' => '2','3' => '3','4' => '4','5' => '5','6' => '6','7' => '7');
		$cruise_weekly = explode(',', $cruise_weekly);
		$today = date('N');
		if( count($cruise_weekly) < 7 ){
			foreach( $cruise_weekly as $val ){
				$arr_weekly[] = $weekly[$val];
				unset($datepicker_value[$val]);
			}
			$departure_date = esc_html__( 'Every ', 'slzexploore-core' ).implode(', ', $arr_weekly);
			$datepicker_options = array('type' => 'weekly', 'value' => implode(', ', $datepicker_value));
			$index = 0;
			while ( !in_array($today, $cruise_weekly) ) {
				$index++;
				$selected_date = date('Y-m-d',strtotime($selected_date . ' +1 days'));
				if(in_array(date('N', strtotime($selected_date)), $cruise_weekly) || $index > 7){
					break;
				}
			}
		}
	}
	elseif( $cruise_frequency == 'monthly' && !empty( $cruise_monthly ) ){
		$arr_order = array( '1' => 'st', '2' => 'nd', '3' => 'rd' );
		$datepicker_value = $cruise_monthly;
		$cruise_monthly = explode(',', $cruise_monthly);
		$arr_monthly = array();
		$today = date('d');
		foreach ($cruise_monthly as $val) {
			$monthly_last = substr( $val, -1 );
			if( array_key_exists( $monthly_last, $arr_order ) ){
				$date_subfix = $arr_order[$monthly_last];
			}
			else{
				$date_subfix = 'th';
			}
			$arr_monthly[] = $val.$date_subfix;
		}	
		$departure_date = implode(', ', $arr_monthly). esc_html__( ' of every month', 'slzexploore-core' );
		$datepicker_options = array('type' => 'monthly', 'value' => $datepicker_value);
		$index = 0;
		while ( !in_array($today, $cruise_monthly) ) {
			$index++;
			$selected_date = date('Y-m-d',strtotime($selected_date . ' +1 days'));
			if(in_array(date('d', strtotime($selected_date)), $cruise_monthly) || $index > 31){
				break;
			}
		}
	}
	elseif( $cruise_frequency == 'season' ){
		if( !empty( $cruise_start_date ) || !empty( $cruise_end_date ) ){
			$start_date = date( 'Y-m-d' );
			if( $start_date < $cruise_start_date ){
				$start_date = $cruise_start_date;
			}
			$datepicker_options = array(
									'type' => 'season',
									'start_date' => $start_date,
									'end_date' => $cruise_end_date
								);
			if( !empty( $cruise_start_date ) ){
				$selected_date =$cruise_start_date;
				$cruise_start_date = date( 'd M', strtotime( $cruise_start_date ) );
			}
			if( !empty( $cruise_end_date ) ){
				$cruise_end_date = date( 'd M', strtotime( $cruise_end_date ) );
			}
			$departure_date = sprintf( '%1$s ~ %2$s', $cruise_start_date, $cruise_end_date );
		}
	}
	else{
		if( !empty( $cruise_start_date ) ){
			$departure_date = date( 'd M', strtotime( $cruise_start_date ) );
		}
	}
}

$cruise_cabin_type = get_post_meta( $cruise_id, 'slzexploore_cruise_cabin_type', true );
$arr_cabin_type    = explode( ',', rtrim($cruise_cabin_type, ',') );
$cabin_types       = array();
if( !empty( $arr_cabin_type ) ){
	foreach( $arr_cabin_type as $cabin_id ){
		if( !empty( $cabin_id ) ){
			$cabin_types[$cabin_id] = get_post_meta( $cabin_id, 'slzexploore_cabin_display_title', true );
		}
	}
}

$is_deposit         = get_post_meta( $cruise_id, 'slzexploore_cruise_is_deposit', true );
$deposit_type       = get_post_meta( $cruise_id, 'slzexploore_cruise_deposit_type', true );
$deposit_amount     = get_post_meta( $cruise_id, 'slzexploore_cruise_deposit_amount', true );
$deposit_method     = array( 'full'   => esc_html__( 'Pay in Full ', 'slzexploore-core' ),
							'deposit' => esc_html__( 'Pay Deposit', 'slzexploore-core' ) );

$cruise_cat_ids   = wp_get_post_terms( $cruise_id, 'slzexploore_cruise_cat', array( 'fields' => 'ids' ) );
$yes_no           = array( '1' => esc_html__( 'Yes', 'slzexploore-core' ), '' => esc_html__( 'No', 'slzexploore-core' ) );
$cruise_item_ids  = array();
if( !empty( $cruise_cat_ids ) ){
$item_args        = array(
						'post_type'    => 'slzexploore_exitem',
						'meta_key'     => 'slzexploore_exitem_cruise_cat',
						'meta_value'   => ' ',
						'meta_compare' => '!='
					);
	$cruise_extra_items = Slzexploore_Core_Com::get_post_id2title( $item_args, array(), false );
	foreach( $cruise_extra_items as $item_id => $item_name ){
		$item_cruise_cat = get_post_meta( $item_id, 'slzexploore_exitem_cruise_cat', true );
		$arr_cruise_cat  = explode( ',', $item_cruise_cat );
		$arr_intersect   = array_intersect( $cruise_cat_ids, $arr_cruise_cat );
		if( count( $arr_intersect ) > 0 ){
			$cruise_item_ids[] = $item_id;
		}
	}
}
$item_args        = array(
	'post_type'    => 'slzexploore_exitem',
	'meta_key'     => 'slzexploore_exitem_cruise_post',
	'meta_value'   => ' ',
	'meta_compare' => '!='
);
$cruise_extra_items = Slzexploore_Core_Com::get_post_id2title( $item_args, array(), false );
foreach ( $cruise_extra_items as $item_id => $item_name ) {
	$item_cruise_post = get_post_meta( $item_id, 'slzexploore_exitem_cruise_post', true );
	if ( in_array( $cruise_id, $item_cruise_post ) && ! in_array( $item_id, $cruise_item_ids ) ) {
		$cruise_item_ids[] = $item_id;
	}
}
/*------------update contact form to booking----------*/

$show_ctf = Slzexploore::get_option('slz-cruise-booking-method' );
$booking_method = get_post_meta( $cruise_id, 'slzexploore_cruise_booking_method', true );
if( !empty($booking_method) ){
	$show_ctf = $booking_method;
}
$cf7_form = Slzexploore::get_option('slz-cruise-cf7-booking');

echo '<div class="wrapper-btn margin-top70 slz-book-wrapper slz-book-cruise">';
if( $show_ctf == 'booking' || $show_ctf == 'both' ){
	printf('<a href="%1$s" class="btn btn-maincolor btn-book" data-id="%3$s">%2$s</a>',
		esc_url( get_permalink() ),
		esc_html__( 'book now', 'slzexploore-core' ),
		esc_attr( $cruise_id )
	);
}
if( !empty($cf7_form) && ( $show_ctf == 'contact' || $show_ctf == 'both' ) ){
	$admin_mail = get_option('admin_email');
	$post_type = 'cruise';
	$json_data = array();
	$json_data['name'] = $cruise_name;
	$json_data['url'] =get_permalink($cruise_id);
	$booking_mail_cc = !empty($booking_mail_cc) ? $booking_mail_cc: $admin_mail;
	$booking_mail_bcc = !empty($booking_mail_bcc) ? $booking_mail_bcc: $admin_mail;
	$json_data['mail_cc'] = $booking_mail_cc;
	$json_data['mail_bcc'] = $booking_mail_bcc;
	$json_data = json_encode($json_data);
	?>
	<a class="btn btn-maincolor btn-info cf7-book" data-toggle="modal" data-target="#booking-form"  data-cabin="<?php echo esc_attr($cabin_id);?>" data-id="<?php echo esc_attr( $cruise_id );?>" data-json='<?php echo $json_data;?>' data-type="<?php echo esc_attr( esc_attr($post_type) );?>"><?php echo esc_html__( 'contact us', 'slzexploore-core' )?></a>
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
		<h4 class="title-widgets"><?php esc_html_e( 'Book Cruise', 'slzexploore-core' ) ?></h4>
		<div class="loading">
			<div class='spinner sk-spinner-wave'>
				<div class='rect1'></div><div class='rect2'></div><div class='rect3'></div>
				<div class='rect4'></div><div class='rect5'></div>
			</div>
		</div>
		<div class="content-widget cruise-booking slz-booking-wrapper">
			<div class="cruise-info table-responsive">
				<table class="table">
					<tr>
						<th><?php esc_html_e( 'Cruise Name', 'slzexploore-core' ); ?></th>
						<th><?php esc_html_e( 'Available Seats', 'slzexploore-core' ); ?></th>
						<th><?php esc_html_e( 'Departure Date', 'slzexploore-core' ); ?></th>
						<th><?php esc_html_e( 'Duration', 'slzexploore-core' ); ?></th>
					</tr>
					<tr>
						<td class="name"><?php echo esc_html( $cruise_name ); ?></td>
						<td><?php echo esc_html( $cruise_seats );?></td>
						<td><?php echo esc_html( $departure_date );?></td>
						<td><?php echo esc_html( $cruise_duration );?></td>
					</tr>
				</table>
			</div>
			<?php if( !empty( $cruise_cabin_type ) ): ?>
			<div class="cabin-types table-responsive">
				<table class="table">
					<tr>
						<th><?php esc_html_e( 'Cabin Type Name', 'slzexploore-core' ); ?></th>
						<th><?php esc_html_e( 'Cabin Price', 'slzexploore-core' ); ?></th>
						<th><?php esc_html_e( 'Number', 'slzexploore-core' ); ?></th>
						<th><?php esc_html_e( 'Max Adults', 'slzexploore-core' ); ?></th>
						<th><?php esc_html_e( 'Max Children', 'slzexploore-core' ); ?></th>
					</tr>
					<?php
						foreach( $arr_cabin_type as $cabin_id ){
							$cabin_name = get_post_meta( $cabin_id, 'slzexploore_cabin_display_title', true );
							$cabin_price = get_post_meta( $cabin_id, 'slzexploore_cabin_price', true );
							$cabin_number = get_post_meta( $cabin_id, 'slzexploore_cabin_number', true );
							$cabin_adults = get_post_meta( $cabin_id, 'slzexploore_cabin_max_adults', true );
							$cabin_children = get_post_meta( $cabin_id, 'slzexploore_cabin_max_children', true );
							$cabin_price = apply_filters( 'slzexploore_booking_price', esc_html( $cabin_price ) );
							printf('<tr>
										<td class="name-%6$s">%1$s</td>
										<td class="cabin-%6$s">%2$s</td>
										<td class="available-%6$s">%3$s</td>
										<td>%4$s</td>
										<td>%5$s</td>
									</tr>',
									esc_html( $cabin_name ),
									$cabin_price,
									esc_html( $cabin_number ),
									esc_html( $cabin_adults ),
									esc_html( $cabin_children ),
									esc_attr( $cabin_id )
								);
						}
					?>
					
				</table>
			</div>
            <?php endif; ?>
			<div class="booking-total hide">
				<h4><?php esc_html_e( 'Booking Information', 'slzexploore-core' ); ?></h4>
				<div class="table-responsive">
					<table class="table">
						<tr>
							<th><?php esc_html_e( 'Name', 'slzexploore-core' ); ?></th>
							<th><?php esc_html_e( 'Price', 'slzexploore-core' ); ?></th>
							<th><?php esc_html_e( 'Persons', 'slzexploore-core' ); ?></th>
							<th><?php esc_html_e( 'Quantity', 'slzexploore-core' ); ?></th>
							<th><?php esc_html_e( 'Total', 'slzexploore-core' ); ?></th>
						</tr>
						<tr>
							<td class="name"></td>
							<td class="price"></td>
							<td class="persons"></td>
							<td class="quantity"></td>
							<td class="total"></td>
						</tr>
					</table>
				</div>
			</div>
			<form class="text-input small-margin-top booking-data">
				<?php if( !empty( $cruise_cabin_type ) ): ?>
				<div class="text-box-wrapper">
					<label class="tb-label">
						<?php esc_html_e( 'Cabin Type', 'slzexploore-core' ); ?>
					</label>
					<div class="input-group">
					<?php
						echo ( $this->drop_down_list( 'cabin_type_id',
														'',
														$cabin_types,
														array( 'class' => 'tb-input selectbox' ) ) );
					?>
					</div>
				</div>
                <?php endif; ?>
				<div class="text-box-wrapper">
					<label class="tb-label">
						<?php esc_html_e( 'Departure Date', 'slzexploore-core' ); ?>
					</label>
					<div class="hide cruise-datepicker-options" data-options="<?php echo esc_attr( json_encode($datepicker_options) ); ?>"></div>
					<div class="input-group">
					<?php
						if( $cruise_frequency == 'other' && !empty( $cruise_start_date ) ){
							echo ( $this->text_field( 'start_date',
									$cruise_start_date,
									array(
										'class' => 'tb-input',
										'readonly' => 'readonly'
									) ) );
						}
						else{
							echo ( $this->text_field( 'start_date',
									$selected_date,
									array(
										'class' => 'tb-input datepicker',
										'placeholder' => esc_html__( 'YYYY-MM-DD', 'slzexploore-core' ),
										'readonly' => 'readonly'
									) ) );
						}
					?>
						<i class="tb-icon fa fa-calendar input-group-addon"></i>
					</div>
				</div>
                <?php
                $number_atts = array(
	                'class' => 'tb-input count',
	                'min'   => '1',
	                'max'   => '100'
                );
                ?>
				<?php if( !empty( $cruise_cabin_type ) ): ?>
				<div class="count text-box-wrapper">
					<label class="tb-label"><?php esc_html_e( 'Number', 'slzexploore-core' ) ?></label>
					<div class="input-group">
						<button disabled data-type="minus" data-field="number" class="input-group-btn btn-minus">
							<span class="tb-icon fa fa-minus"></span>
						</button>
						<?php
						echo( $this->input_field( 'number', 'number', 1, $number_atts ) );
						?>
						<button data-type="plus" data-field="number" class="input-group-btn btn-plus">
							<span class="tb-icon fa fa-plus"></span>
						</button>
					</div>
				</div>
				<?php endif; ?>
				<div class="count text-box-wrapper">
					<label class="tb-label"><?php esc_html_e( 'Adult', 'slzexploore-core' ) ?></label>
					<div class="input-group">
						<button disabled data-type="minus" data-field="adults" class="input-group-btn btn-minus">
							<span class="tb-icon fa fa-minus"></span>
						</button>
						<?php
							echo ( $this->input_field( 'number', 'adults', 1, $number_atts) );
						?>
						<button data-type="plus" data-field="adults" class="input-group-btn btn-plus">
							<span class="tb-icon fa fa-plus"></span>
						</button>
					</div>
				</div>
				<div class="count text-box-wrapper">
					<label class="tb-label"><?php esc_html_e( 'Child', 'slzexploore-core' ) ?></label>
					<div class="input-group">
						<button disabled data-type="minus" data-field="children" class="input-group-btn btn-minus">
							<span class="tb-icon fa fa-minus"></span>
						</button>
						<?php
							$number_atts['min'] = 0;
							echo ( $this->input_field( 'number', 'children', 0, $number_atts ) );
						?>
						<button data-type="plus" data-field="children" class="input-group-btn btn-plus">
							<span class="tb-icon fa fa-plus"></span>
						</button>
					</div>
				</div>
				<?php if( !empty( $cruise_cabin_type ) ): ?>
				<div class="count text-box-wrapper">
					<label class="tb-label"><?php esc_html_e( 'Infant', 'slzexploore-core' ) ?></label>
					<div class="input-group">
						<button disabled data-type="minus" data-field="infant" class="input-group-btn btn-minus">
							<span class="tb-icon fa fa-minus"></span>
						</button>
						<?php
							echo ( $this->input_field( 'number', 'infant', 0, $number_atts ) );
						?>
						<button data-type="plus" data-field="infant" class="input-group-btn btn-plus">
							<span class="tb-icon fa fa-plus"></span>
						</button>
					</div>
				</div>
				<?php endif; ?>
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
							printf( esc_html__( 'Pay a deposit of %1$s per cabin type.', 'slzexploore-core' ), apply_filters( 'slzexploore_booking_price', esc_html( $deposit_amount ), false ) );
						}
					?>
					</p>
				</div>
				<?php endif; ?>
			</form>
			<!-- End text-input class -->
			
			<?php if( !empty( $cruise_item_ids ) ): ?>
			<div class="extra-item">
				<h4><?php esc_html_e( 'Extra Items', 'slzexploore-core' ); ?></h4>
				<p class="des"><?php esc_html_e( 'Please select the extra items you wish to be included with your cruise using the controls you see below.', 'slzexploore-core' ); ?></p>
				<div class="table-responsive">
					<table class="table">
						<tr>
							<th><?php esc_html_e( 'Item', 'slzexploore-core' ); ?></th>
							<th><?php esc_html_e( 'Price', 'slzexploore-core' ); ?></th>
							<th><?php esc_html_e( 'Per Person', 'slzexploore-core' ); ?></th>
							<th><?php esc_html_e( 'Quantity', 'slzexploore-core' ); ?></th>
						</tr>
						<?php
							foreach( $cruise_item_ids as $item_id ){
								$item_name     = get_the_title( $item_id );
								$item_content  = apply_filters('the_content', get_post_field( 'post_content', $item_id ) );
								$item_price    = get_post_meta( $item_id, 'slzexploore_exitem_price', true );
								$item_is_person= get_post_meta( $item_id, 'slzexploore_exitem_is_price_person', true );
								$item_maximum  = get_post_meta( $item_id, 'slzexploore_exitem_max_items', true );
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
											<td class="is-person">%4$s<span class="hide">%5$s</span></td>
											<td class="number-item">%6$s</td>
										</tr>',
										esc_html( $item_name ),
										wp_kses_post( $item_content ),
										$item_price,
										esc_html( $yes_no[$item_is_person] ),
										esc_html( $item_is_person ),
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
						$user_meta['last_name']  = get_user_meta( $user_id, 'last_name', true );
						$user_meta['email']      = wp_get_current_user()->data->user_email;
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
				if ( SLZEXPLOORE_CORE_WOOCOMMERCE_ACTIVE && isset( $woo_checkout_active['cruise'] ) ){
					$woo_checkout_cls = 'slz-add-to-cart';
				}
				printf('<button data-hover="%1$s" class="btn btn-slide btn-check %4$s" data-id="%2$s">
							<span class="text">%3$s</span>
							<span class="icons fa fa-long-arrow-right"></span>
						</button>',
						esc_attr__( 'book now', 'slzexploore-core' ),
						esc_attr( $cruise_id ),
						esc_html__( 'book', 'slzexploore-core' ),
						esc_attr( $woo_checkout_cls )
					);
				printf('<button data-hover="%1$s" class="btn btn-slide btn-back hide" data-id="%2$s">
							<span class="text">%1$s</span>
							<span class="icons fa fa-long-arrow-left"></span>
						</button>',
						esc_attr__( 'back', 'slzexploore-core' ),
						esc_attr( $cruise_id )
					);
				printf('<button data-hover="%1$s" class="btn btn-slide btn-next hide" data-id="%2$s">
							<span class="text">%1$s</span>
							<span class="icons fa fa-long-arrow-right"></span>
						</button>',
						esc_attr__( 'next', 'slzexploore-core' ),
						esc_attr( $cruise_id )
					);
			?>
			</div>
		</div>
	</div>
</div>
<!-- Modal -->
<div id="booking-form" class="modal fade " role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-body">
       	<div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal">&times;</button>
	    </div>
      	<div class="slz-booking-from contact-box">
	      	<?php
	      	if(!empty($cf7_form)){
	      		$uri = plugins_url().'/contact-form-7/includes/js/scripts.js';
				printf('<div class="cf7_js_uri hide">%s</div>', esc_url( $uri ) );
	      		echo do_shortcode('[contact-form-7 id="'.esc_attr($cf7_form).'"]');
	      	}?>
	      	</div>
      </div>
    </div>
  </div>
</div>