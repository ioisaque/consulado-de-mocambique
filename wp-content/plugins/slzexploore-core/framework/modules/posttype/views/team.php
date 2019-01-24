<?php $prefix='slzexploore_team_'; ?>
<div class="tab-panel">
	<ul class="tab-list">
		<li class="active">
			<a href="slz-tab-team-general"><?php esc_html_e( 'General', 'slzexploore-core' );?></a>
		</li>
		<li class="">
			<a href="slz-tab-team-social"><?php esc_html_e( 'Social', 'slzexploore-core' );?></a>
		</li>
	</ul>
	<div class="tab-container">
		<div class="tab-wrapper slz-page-meta">
			<!-- General -->
			<div id="slz-tab-team-general" class="tab-content active">
				<table class="form-table">
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Thumbnail', 'slzexploore-core' );?></label>
							<span class="f-right"><?php $this->tooltip_html( esc_html__( 'Upload Thumbnail Image.', 'slzexploore-core' ) );?></span>
						</th>
						<td>
							<?php echo ( $this->single_image( 'slzexploore_team_meta['. $prefix .'thumbnail]',
																$this->get_field( $data_meta, 'thumbnail' ),
																array( 'id'=> $prefix .'thumbnail_id',
																	'data-rel' => $prefix .'thumbnail' ) ) );?>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Description', 'slzexploore-core' );?></label>
							<span class="f-right"><?php $this->tooltip_html( esc_html__( 'Enter Team Description.', 'slzexploore-core' ) );?></span>
						</th>
						<td>
							<?php echo ( $this->text_area( 'slzexploore_team_meta['.$prefix.'description]',
														$this->get_field( $data_meta, 'description' ),
														array('class'=>'slz-block','rows' => '6') ) );?>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Position', 'slzexploore-core' );?></label>
							<span class="f-right"><?php $this->tooltip_html( esc_html__( 'Enter Position of Him (or Her).', 'slzexploore-core' ) );?></span>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_team_meta['.$prefix.'position]',
														$this->get_field( $data_meta, 'position' ),
														array('class'=>'slz-block') ) );?>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Address', 'slzexploore-core' );?></label>
							<span class="f-right"><?php $this->tooltip_html( esc_html__( 'Enter Address to Contact.', 'slzexploore-core' ) );?></span>

						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_team_meta['.$prefix.'address]',
														$this->get_field( $data_meta, 'address' ),
														array('class'=>'slz-block') ) );?>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Phone', 'slzexploore-core' );?></label>
							<span class="f-right"><?php $this->tooltip_html( esc_html__( 'Enter Phone Number to Contact.', 'slzexploore-core' ) );?></span>

						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_team_meta['.$prefix.'phone]',
														$this->get_field( $data_meta, 'phone' ),
														array('class'=>'slz-block') ) );?>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Email', 'slzexploore-core' );?></label>
							<span class="f-right"><?php $this->tooltip_html( esc_html__( 'Enter Email to Contact.', 'slzexploore-core' ) );?></span>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_team_meta['.$prefix.'email]',
														$this->get_field( $data_meta, 'email' ),
														array('class'=>'slz-block') ) );?>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Skype', 'slzexploore-core' );?></label>
							<span class="f-right"><?php $this->tooltip_html( esc_html__( 'Enter Skype Name being used.', 'slzexploore-core' ) );?></span>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_team_meta['.$prefix.'skype]',
														$this->get_field( $data_meta, 'skype' ),
														array('class'=>'slz-block') ) );?>
						</td>
					</tr>
				</table>
			</div>

			<!-- Social-->
			<div id="slz-tab-team-social" class="tab-content">
				<table class="form-table">
					<?php $social_group = Slzexploore_Core::get_params( 'teammbox-social');
						foreach( $social_group as $social => $social_text ):
							$fieldname = 'slzexploore_team_meta['.$prefix.$social.']';
						?>
						<tr>
							<th scope="row">
								<label><?php echo esc_attr( $social_text );?></label>
							</th>
							<td>
								<?php echo ( $this->text_field( $fieldname,
																$this->get_field( $data_meta, $social ),
																array( 'class' => 'slz-block' ) ) );?>
							</td>
						</tr>
					<?php endforeach;?>
				</table>
			</div>
		</div>
	</div>
</div>