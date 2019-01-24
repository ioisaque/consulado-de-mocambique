<?php
	$prefix = 'slzexploore_vacancy_';
?>
<div class="slz-page-meta slz-vacancy-options">
	<table class="form-table">
		<tr>
			<th scope="row"><?php esc_html_e( 'Accommodation', 'slzexploore-core' );?></th>
			<td>
			<?php
				$args = array('post_type' => 'slzexploore_hotel');
				$arr_accommodation = Slzexploore_Core_Com::get_post_id2title( $args, array(), false );
				echo ( $this->drop_down_list('slzexploore_vacancy_meta['. $prefix .'accommodation]',
												$this->get_field( $data_meta, 'accommodation' ),
												$arr_accommodation,
												array('class' => 'slz-block-half slz-accommodation-id') ) );
			?>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e( 'Room Type', 'slzexploore-core' );?></th>
			<td class="slz-room-type">
			<?php
				$arr_room_type = array();
				$accommodation_id = $this->get_field( $data_meta, 'accommodation' );
				$arr_accommodation_ids = array_keys($arr_accommodation);
				if( empty( $accommodation_id ) && !empty($arr_accommodation_ids) ) {
					$accommodation_id = $arr_accommodation_ids[0];
				}
				if( !empty( $accommodation_id ) ){
					$params = array('empty' => esc_html__( '-- All Room Types --', 'slzexploore-core' ) );
					$room_args = array(
								'post_type' => 'slzexploore_room',
								'meta_key' => 'slzexploore_room_accommodation',
								'meta_value' => $accommodation_id
							);
					$arr_room_type = Slzexploore_Core_Com::get_post_id2title( $room_args, $params );
				}
				echo ( $this->drop_down_list('slzexploore_vacancy_meta['. $prefix .'room_type]',
												$this->get_field( $data_meta, 'room_type' ),
												$arr_room_type,
												array('class' => 'slz-block-half') ) );
			?>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<?php esc_html_e( 'Date From', 'slzexploore-core' );?>
				<p class="description">
				<?php esc_html_e( 'If you leave this field blank it will be set as current date.', 'slzexploore-core' ); ?>
				</p>
			</th>
			<td>
			<?php echo ( $this->date_picker( 'slzexploore_vacancy_meta['. $prefix .'date_from]',
												$this->get_field( $data_meta, 'date_from' ),
												array( 'class' => 'slz-block-half' ) ) );?>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<?php esc_html_e( 'Date To', 'slzexploore-core' );?>
				<p class="description">
				<?php esc_html_e( 'Leave it blank if this rooms are available all the time.', 'slzexploore-core' ); ?>
				</p>
			</th>
			<td>
			<?php echo ( $this->date_picker( 'slzexploore_vacancy_meta['. $prefix .'date_to]',
												$this->get_field( $data_meta, 'date_to' ),
												array( 'class' => 'slz-block-half' ) ) );?>
			</td>
		</tr>
		<tr class="last">
			<th scope="row">
				<label><?php esc_html_e( 'Price', 'slzexploore-core' );?></label>
				<p class="description">
				<?php esc_html_e( 'If this field is blank, this price is set by room price. Example: 50.', 'slzexploore-core' ); ?>
				</p>
			</th>
			<td>
				<?php echo ( $this->text_field( 'slzexploore_vacancy_meta['. $prefix .'price]',
													$this->get_field( $data_meta, 'price' ),
													array( 'class' => 'slz-block-half' ) ) );?>
			</td>
		</tr>
	</table> 
</div>