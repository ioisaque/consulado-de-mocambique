<?php
	$prefix = 'slzexploore_schedule_';
?>
<div class="slz-page-meta slz-schedule-options">
	<table class="form-table">
		<tr>
			<th scope="row"><?php esc_html_e( 'Tour', 'slzexploore-core' );?></th>
			<td>
			<?php
				$args = array('post_type' => 'slzexploore_tour');
				$arr_tours = Slzexploore_Core_Com::get_post_id2title( $args, array(), false );
				echo ( $this->drop_down_list('slzexploore_schedule_meta['. $prefix .'tour]',
												$this->get_field( $data_meta, 'tour' ),
												$arr_tours,
												array('class' => 'slz-block-half') ) );
			?>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<label><?php esc_html_e( 'Maximum Seats', 'slzexploore-core' );?></label>
				<p class="description">
				<?php esc_html_e( 'If this field is blank, this value is set by tour maximum seats. Example: 50.', 'slzexploore-core' ); ?>
				</p>
			</th>
			<td>
				<?php echo ( $this->text_field( 'slzexploore_schedule_meta['. $prefix .'maximum_seat]',
													$this->get_field( $data_meta, 'maximum_seat' ),
													array( 'class' => 'slz-block-half' ) ) );?>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<?php esc_html_e( 'Start Date', 'slzexploore-core' );?>
				<p class="description">
				<?php esc_html_e( 'If you leave this field blank it will be set as current date.', 'slzexploore-core' ); ?>
				</p>
			</th>
			<td>
			<?php echo ( $this->date_picker( 'slzexploore_schedule_meta['. $prefix .'start_date]',
												$this->get_field( $data_meta, 'start_date' ),
												array( 'class' => 'slz-block-half' ) ) );?>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<?php esc_html_e( 'End Date', 'slzexploore-core' );?>
				<p class="description">
				<?php esc_html_e( 'If you leave this field blank it will be set as start date.', 'slzexploore-core' ); ?>
				</p>
			</th>
			<td>
			<?php echo ( $this->date_picker( 'slzexploore_schedule_meta['. $prefix .'end_date]',
												$this->get_field( $data_meta, 'end_date' ),
												array( 'class' => 'slz-block-half' ) ) );?>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<label><?php esc_html_e( 'Price Per Adult', 'slzexploore-core' );?></label>
				<p class="description">
				<?php esc_html_e( 'If this field is blank, this price is set by tour adult price. Example: 900.', 'slzexploore-core' ); ?>
				</p>
			</th>
			<td>
				<?php echo ( $this->text_field( 'slzexploore_schedule_meta['. $prefix .'price_adult]',
													$this->get_field( $data_meta, 'price_adult' ),
													array( 'class' => 'slz-block-half' ) ) );?>
			</td>
		</tr>
		<tr class="last">
			<th scope="row">
				<label><?php esc_html_e( 'Price Per Child ', 'slzexploore-core' );?></label>
				<p class="description">
				<?php esc_html_e( 'If this field is blank, this price is set by tour child price. Example: 100.', 'slzexploore-core' ); ?>
				</p>
			</th>
			<td>
				<?php echo ( $this->text_field( 'slzexploore_schedule_meta['. $prefix .'price_child]',
													$this->get_field( $data_meta, 'price_child' ),
													array( 'class' => 'slz-block-half' ) ) );?>
			</td>
		</tr>
	</table> 
</div>