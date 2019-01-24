<?php
$room_name      = get_post_meta( $post_id, 'slzexploore_room_display_title', true );
$hotel_id       = get_post_meta( $post_id, 'slzexploore_room_accommodation', true );
$max_adults     = get_post_meta( $post_id, 'slzexploore_room_max_adults', true );
$max_children   = get_post_meta( $post_id, 'slzexploore_room_max_children', true );
$room_number    = get_post_meta( $post_id, 'slzexploore_room_number_room', true );
$room_price     = get_post_meta( $post_id, 'slzexploore_room_price', true );
$allow_booking  = get_post_meta( $post_id, 'slzexploore_room_allow_booking', true );
$is_deposit     = get_post_meta( $hotel_id, 'slzexploore_hotel_is_deposit', true );
$deposit_type   = get_post_meta( $hotel_id, 'slzexploore_hotel_deposit_type', true );
$deposit_amount = get_post_meta( $hotel_id, 'slzexploore_hotel_deposit_amount', true );
$deposit_method = array( 'full'   => esc_html__( 'Pay in Full ', 'slzexploore-core' ),
							'deposit' => esc_html__( 'Pay Deposit', 'slzexploore-core' ) );

$check_in_date = date('Y-m-d');
$discount      = $this->get_hotel_discount( $hotel_id, $check_in_date );
$decimal       = Slzexploore_Core::get_theme_option('slz-currency-decimal');
// extra item
$hotel_cat_ids    = wp_get_post_terms( $hotel_id, 'slzexploore_hotel_cat', array( 'fields' => 'ids' ) );
$yes_no           = array( '1' => esc_html__( 'Yes', 'slzexploore-core' ), '' => esc_html__( 'No', 'slzexploore-core' ) );
$hotel_item_ids   = array();
if( !empty( $hotel_cat_ids ) ){
	$item_args          = array(
								'post_type'    => 'slzexploore_exitem',
								'meta_key'     => 'slzexploore_exitem_hotel_cat',
								'meta_value'   => ' ',
								'meta_compare' => '!='
							);
	$hotel_extra_items    = Slzexploore_Core_Com::get_post_id2title( $item_args, array(), false );
	foreach( $hotel_extra_items as $item_id => $item_name ){
		$item_hotel_cat = get_post_meta( $item_id, 'slzexploore_exitem_hotel_cat', true );
		$arr_hotel_cat = explode( ',', $item_hotel_cat );
		$arr_intersect = array_intersect( $hotel_cat_ids, $arr_hotel_cat );
		if( count( $arr_intersect ) > 0 ){
			$hotel_item_ids[] = $item_id;
		}
	}
}
$item_args        = array(
	'post_type'    => 'slzexploore_exitem',
	'meta_key'     => 'slzexploore_exitem_hotel_post',
	'meta_value'   => ' ',
	'meta_compare' => '!='
);
$hotel_extra_items = Slzexploore_Core_Com::get_post_id2title( $item_args, array(), false );
foreach ( $hotel_extra_items as $item_id => $item_name ) {
	$item_hotel_post = get_post_meta( $item_id, 'slzexploore_exitem_hotel_post', true );
	if ( in_array( $hotel_id, $item_hotel_post ) && ! in_array( $item_id, $hotel_item_ids ) ) {
		$hotel_item_ids[] = $item_id;
	}
}

// check vacancy to get price
$args = array(
	'post_type' => 'slzexploore_vacancy',
	'meta_query' => array(
		array(
			'relation' => 'OR',
			array(
				'key'     => 'slzexploore_vacancy_room_type',
				'value'   => $post_id
			),
			array(
				array(
					'key'     => 'slzexploore_vacancy_room_type',
					'value'   => ''
				),
				array(
					'key'     => 'slzexploore_vacancy_accommodation',
					'value'   => $hotel_id
				)
			)
		),
		array(
			'key'     => 'slzexploore_vacancy_date_from',
			'value'   => date('Y-m-d'),
			'compare' => '<='
		),
		array(
			'relation' => 'OR',
			array(
				'key'     => 'slzexploore_vacancy_date_to',
				'value'   => ''
			),
			array(
				'key'     => 'slzexploore_vacancy_date_to',
				'value'   => date('Y-m-d'),
				'compare' => '>='
			)
		)
	)
);
$vacancy_post = Slzexploore_Core_Com::get_post_id2title( $args, array(), false );
if(!empty($vacancy_post)){
	$vacancy_ids = array_keys($vacancy_post);
	if(isset($vacancy_ids[0]) && !empty($vacancy_ids[0])){
		$vacancy_price = get_post_meta($vacancy_ids[0], 'slzexploore_vacancy_price', true);
		if(trim($vacancy_price) != ''){
			$room_price = $vacancy_price;
		}
	}
}
?>
<div class="find-widget find-hotel-widget widget new-style">
	<h4 class="title-widgets"><?php esc_html_e( 'Book Hotel', 'slzexploore-core' ) ?></h4>
	<div class="loading">
		<div class='spinner sk-spinner-wave'>
			<div class='rect1'></div><div class='rect2'></div><div class='rect3'></div>
			<div class='rect4'></div><div class='rect5'></div>
		</div>
	</div>
	<div class="hide slz-asset-uri"><?php echo esc_html(SLZEXPLOORE_CORE_ASSET_URI); ?></div>
	<div class="content-widget hotel-booking slz-booking-wrapper">
		<div class="text-input small-margin-top">
			<div class="hotel-booking-info table-responsive">
				<table class="table">
					<tr>
						<th><?php esc_html_e( 'Room Type', 'slzexploore-core' ); ?></th>
						<th><?php esc_html_e( 'Number Room', 'slzexploore-core' ); ?></th>
						<th><?php esc_html_e( 'Max Adults', 'slzexploore-core' ); ?></th>
						<th><?php esc_html_e( 'Max Children', 'slzexploore-core' ); ?></th>
						<th><?php esc_html_e( 'Price', 'slzexploore-core' ); ?></th>
					</tr>
					<tr>
						<td class="name"><?php echo esc_html( $room_name ); ?></td>
						<td class="room_number"><?php echo esc_html( $room_number ); ?></td>
						<td class="max_adults"><?php echo esc_html( $max_adults ); ?></td>
						<td class="max_children"><?php echo esc_html( $max_children ); ?></td>
						<td class="room_price"><?php echo apply_filters( 'slzexploore_booking_price', esc_html( $room_price ) );?></td>
					</tr>
				</table>
				<div class="vacancy-info">
				<?php
					$args = array(
						'post_status'    =>'publish',
						'post_type'      => 'slzexploore_vacancy',
						'posts_per_page' => -1,
						'orderby'        => 'meta_value',
						'order'          => 'ASC',
						'meta_key'       => 'slzexploore_vacancy_date_from',
						'meta_query'     => array(
							array(
								'relation' => 'OR',
								array(
									'key'     => 'slzexploore_vacancy_room_type',
									'value'   => $post_id
								),
								array(
									array(
										'key'     => 'slzexploore_vacancy_room_type',
										'value'   => ''
									),
									array(
										'key'     => 'slzexploore_vacancy_accommodation',
										'value'   => $hotel_id
									)
								)
							),
							array(
								'key'    => 'slzexploore_vacancy_date_to',
								'value'  => current_time('Y-m-d'),
								'compare'=> '>='
							)
						)
					);
					$the_query = new WP_Query( $args );
					if ( $the_query->have_posts() ) {
						echo '<p>'.esc_html__( 'This room is only available for below period.', 'slzexploore-core' ).'</p>';
						echo '<table class="table">
								<tr>
									<th>'.esc_html__( 'Start Date', 'slzexploore-core' ).'</th>
									<th>'.esc_html__( 'End Date', 'slzexploore-core' ).'</th>
									<th>'.esc_html__( 'Price', 'slzexploore-core' ).'</th>
								</tr>';
						while ( $the_query->have_posts() ) {
							$the_query->the_post();
							$vacany_id = get_the_ID();
							$vacancy_date_from = get_post_meta( $vacany_id, 'slzexploore_vacancy_date_from', true );
							$vacancy_date_to   = get_post_meta( $vacany_id, 'slzexploore_vacancy_date_to', true );
							$vacancy_price     = get_post_meta( $vacany_id, 'slzexploore_vacancy_price', true );
							if(!empty($vacancy_price)){
								$vacancy_price = apply_filters( 'slzexploore_booking_price', esc_html( $vacancy_price ) );
							}
							echo '<tr>
									<td>'.esc_html( $vacancy_date_from ).'</td>
									<td>'.esc_html( $vacancy_date_to ).'</td>
									<td>'.$vacancy_price.'</td>
								</tr>';
						}
						echo '</table>';
						wp_reset_postdata();
					}
				?>
				</div>
			</div>
			<div class="booking-total hide">
				<h4><?php esc_html_e( 'Booking Information', 'slzexploore-core' ); ?></h4>
				<div class="table-responsive">
					<table class="table">
						<tr>
							<th><?php esc_html_e( 'Check In', 'slzexploore-core' ); ?></th>
							<th><?php esc_html_e( 'Check Out', 'slzexploore-core' ); ?></th>
							<th><?php esc_html_e( 'Number', 'slzexploore-core' ); ?></th>
							<th><?php esc_html_e( 'Adults', 'slzexploore-core' ); ?></th>
							<th><?php esc_html_e( 'Children / Infant', 'slzexploore-core' ); ?></th>
							<th><?php esc_html_e( 'Total', 'slzexploore-core' ); ?></th>
						</tr>
						<tr>
							<td class="check-in"></td>
							<td class="check-out"></td>
							<td class="number"></td>
							<td class="adults"></td>
							<td class="children"></td>
							<td class="total"></td>
						</tr>
					</table>
				</div>
				<div class="table-responsive extra-item-total">
					<table class="table">
						<tr>
							<th><?php esc_html_e( 'Item Name', 'slzexploore-core' ); ?></th>
							<th><?php esc_html_e( 'Price', 'slzexploore-core' ); ?></th>
							<th><?php esc_html_e( 'Persons/Rooms', 'slzexploore-core' ); ?></th>
							<th><?php esc_html_e( 'Days', 'slzexploore-core' ); ?></th>
							<th><?php esc_html_e( 'Quantity', 'slzexploore-core' ); ?></th>
							<th><?php esc_html_e( 'Total', 'slzexploore-core' ); ?></th>
						</tr>
					</table>
				</div>
			</div>
			<form class="text-input small-margin-top booking-data">
				<div class="input-daterange" data-allow-date="<?php echo esc_attr($allow_booking); ?>">
					<?php
						$date_atts = array('class'        => 'tb-input',
											'placeholder' => esc_html__( 'YYYY-MM-DD', 'slzexploore-core' ),
											'readonly'    => 'readonly'
										);
						$today = date('Y-m-d');
						if(!empty($allow_booking)){
							$first_date = explode(',', $allow_booking);
							if(str_replace('-', '', $today) < $first_date[0]){
								$today = substr($first_date[0], 0, 4) .'-'.
										substr($first_date[0], 4, 2).'-'.
										substr($first_date[0], 6, 2);
							}
						}
					?>
					<div class="text-box-wrapper half">
						<label class="tb-label"><?php esc_html_e( 'Check in', 'slzexploore-core' ) ?></label>
						<div class="input-group">
							<?php echo ( $this->text_field( 'check_in_date', $today, $date_atts) ); ?>
							<i class="tb-icon fa fa-calendar input-group-addon"></i>
						</div>
					</div>
					<div class="text-box-wrapper half">
						<label class="tb-label"><?php esc_html_e( 'Check out', 'slzexploore-core' ) ?></label>
						<div class="input-group">
							<?php echo ( $this->text_field( 'check_out_date', $today, $date_atts) ); ?>
							<i class="tb-icon fa fa-calendar input-group-addon"></i>
						</div>
					</div>
				</div>
				<div class="count child-count text-box-wrapper">
					<label class="tb-label"><?php esc_html_e( 'Number Room', 'slzexploore-core' ) ?></label>
					<div class="input-group">
						<button disabled data-type="minus" data-field="number_room" class="input-group-btn btn-minus">
							<span class="tb-icon fa fa-minus"></span>
						</button>
						<?php
							$number_atts = array( 'class' => 'tb-input count',
													'min' => '1',
													'max' => '100'
												);
							echo ( $this->input_field( 'number', 'number_room', 1, $number_atts) );
						?>
						<button data-type="plus" data-field="number_room" class="input-group-btn btn-plus">
							<span class="tb-icon fa fa-plus"></span>
						</button>
					</div>
				</div>
				<div class="count adult-count text-box-wrapper">
					<label class="tb-label"><?php esc_html_e( 'Adult', 'slzexploore-core' ) ?></label>
					<div class="input-group">
						<button disabled data-type="minus" data-field="adults" class="input-group-btn btn-minus">
							<span class="tb-icon fa fa-minus"></span>
						</button>
						<?php echo ( $this->input_field( 'number', 'adults', 1, $number_atts) ); ?>
						<button data-type="plus" data-field="adults" class="input-group-btn btn-plus">
							<span class="tb-icon fa fa-plus"></span>
						</button>
					</div>
				</div>
				<div class="count child-count text-box-wrapper">
					<label class="tb-label"><?php esc_html_e( 'Child', 'slzexploore-core' ) ?></label>
					<div class="input-group">
						<button disabled data-type="minus" data-field="children" class="input-group-btn btn-minus">
							<span class="tb-icon fa fa-minus"></span>
						</button>
						<?php
							$number_atts['min'] = 0;
							echo ( $this->input_field( 'number', 'children', 0, $number_atts) );
						?>
						<button data-type="plus" data-field="children" class="input-group-btn btn-plus">
							<span class="tb-icon fa fa-plus"></span>
						</button>
					</div>
				</div>
				<div class="count child-count text-box-wrapper">
					<label class="tb-label"><?php esc_html_e( 'Infant', 'slzexploore-core' ) ?></label>
					<div class="input-group">
						<button disabled data-type="minus" data-field="infant" class="input-group-btn btn-minus">
							<span class="tb-icon fa fa-minus"></span>
						</button>
						<?php
							echo ( $this->input_field( 'number', 'infant', 0, $number_atts) );
						?>
						<button data-type="plus" data-field="infant" class="input-group-btn btn-plus">
							<span class="tb-icon fa fa-plus"></span>
						</button>
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
							printf( esc_html__( 'Pay a deposit of %1$s per room type.', 'slzexploore-core' ), apply_filters( 'slzexploore_booking_price', esc_html( $deposit_amount ), false ) );
						}
					?>
					</p>
				</div>
				<?php endif; ?>
			</form>
			
			<!-- Extra Items -->
			<?php if( !empty( $hotel_item_ids ) ): ?>
			<div class="extra-item">
				<h4><?php esc_html_e( 'Extra Items', 'slzexploore-core' ); ?></h4>
				<p class="des"><?php esc_html_e( 'Please select the extra items you wish to be included with your hotel using the controls you see below.', 'slzexploore-core' ); ?></p>
				<div class="table-responsive">
					<table class="table">
						<tr>
							<th><?php esc_html_e( 'Item', 'slzexploore-core' ); ?></th>
							<th><?php esc_html_e( 'Price', 'slzexploore-core' ); ?></th>
							<th><?php esc_html_e( 'Per Person', 'slzexploore-core' ); ?></th>
							<th><?php esc_html_e( 'Per Day', 'slzexploore-core' ); ?></th>
							<th><?php esc_html_e( 'Quantity', 'slzexploore-core' ); ?></th>
						</tr>
						<?php
							foreach( $hotel_item_ids as $item_id ){
								$item_name      = get_the_title( $item_id );
								$item_content   = apply_filters('the_content', get_post_field( 'post_content', $item_id ) );
								$item_price     = get_post_meta( $item_id, 'slzexploore_exitem_price', true );
								$item_is_person = get_post_meta( $item_id, 'slzexploore_exitem_is_price_person', true );
								$item_is_day    = get_post_meta( $item_id, 'slzexploore_exitem_is_price_day', true );
								$item_maximum   = get_post_meta( $item_id, 'slzexploore_exitem_max_items', true );
								$item_is_fixed  = get_post_meta( $item_id, 'slzexploore_exitem_fixed_item', true );
								$item_quantity  = '<select class="tb-input selectbox quantity" name="quantity_'.$item_id.'">';
								if( $item_is_fixed ){
									$item_quantity .= sprintf( '<option value="%1$s">%1$s</option>', $item_maximum );
								}
								else{
									for( $i = 0; $i <= intval( $item_maximum ); $i++ ){
										$item_quantity .= sprintf( '<option value="%1$s">%1$s</option>', $i );
									}
								}
								$item_quantity .= '</select>';
								$item_price = apply_filters( 'slzexploore_booking_price', esc_html( $item_price ) );
								printf('<tr>
											<td class="item-info"><h5>%1$s</h5> %2$s</td>
											<td class="item-price">%3$s</td>
											<td class="is-person">%4$s<span class="hide">%5$s</span></td>
											<td class="is-day">%6$s<span class="hide">%7$s</span></td>
											<td class="number-item">%8$s</td>
										</tr>',
										esc_html( $item_name ),
										wp_kses_post( $item_content ),
										$item_price,
										esc_html( $yes_no[$item_is_person] ),
										esc_html( $item_is_person ),
										esc_html( $yes_no[$item_is_day] ),
										esc_html( $item_is_day ),
										wp_kses( $item_quantity, array(
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
			
			<div class="summary" data-decimal="<?php echo esc_attr( $decimal ); ?>">
				<h4><?php esc_html_e( 'SUMMARY', 'slzexploore-core' ); ?></h4>
				<table>
					<tr>
						<th><?php esc_html_e( 'Booking Total', 'slzexploore-core' ); ?></th>
						<td class="booking-price">
							<?php echo apply_filters( 'slzexploore_booking_price', '' ); ?>
						</td>
					</tr>
					<?php if( !empty( $hotel_item_ids ) ){ ?>
					<tr>
						<th><?php esc_html_e( 'Extra Items', 'slzexploore-core' ); ?></th>
						<td class="extra-total">
							<?php echo apply_filters( 'slzexploore_booking_price', '' ); ?>
						</td>
					</tr>
					<?php } ?>
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
				</div>
			</form>
			
			<div class="err-message text-box-wrapper hide"></div>
			<div class="success-message text-box-wrapper hide">
			<?php
				esc_html_e('Thank You. Your booking have received and booking number is ','slzexploore-core');
			?>
				<span></span>
			</div>
			<div class="slz-btn-group">
			<?php
				$woo_checkout_active = Slzexploore::get_option('slz-woocommerce-checkout-active', 'enabled');
				$woo_checkout_cls = '';
				if ( SLZEXPLOORE_CORE_WOOCOMMERCE_ACTIVE && isset( $woo_checkout_active['hotel'] ) ){
					$woo_checkout_cls = 'slz-add-to-cart';
				}
				printf('<button data-hover="%1$s" class="btn btn-slide check-booking %4$s" data-id="%3$s">
							<span class="text">%2$s</span><span class="icons fa fa-long-arrow-right"></span>
						</button>',
						esc_attr__( 'Book Now', 'slzexploore-core' ),
						esc_html__( 'Book', 'slzexploore-core' ),
						esc_attr( $post_id ),
						esc_attr( $woo_checkout_cls )
					);
				
				printf('<button data-hover="%1$s" class="btn btn-slide btn-back hide" data-id="%2$s">
							<span class="text">%1$s</span><span class="icons fa fa-long-arrow-right"></span>
						</button>',
						esc_attr__( 'Back', 'slzexploore-core' ),
						esc_attr( $post_id )
					);
				printf('<button data-hover="%1$s" class="btn btn-slide btn-next hide" data-id="%2$s">
							<span class="text">%1$s</span><span class="icons fa fa-long-arrow-right"></span>
						</button>',
						esc_attr__( 'Next', 'slzexploore-core' ),
						esc_attr( $post_id )
					);
			?>
			</div>
		</div>
	</div>
</div>