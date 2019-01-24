<?php
$prefix = 'slzexploore_testi_';
?>
<div class="slz-custom-meta" >
	<div class="slz-meta-row active" >
		<div class="slz-desc">
			<span><?php esc_html_e( 'Position', 'slzexploore-core' );?></span>
			<span class="f-right"><?php $this->tooltip_html( esc_html__( 'Position of testimonial.', 'slzexploore-core' ) );?></span>
		</div>
		<div class="slz-field">
			<?php echo ( $this->text_field( 'slzexploore_testi_meta['. $prefix .'position]',
																$this->get_field( $data_meta, 'position' ),
																array( 'class' => 'slz-block' ) ) );?>
		</div>
	</div>
	<div class="slz-meta-row active" >
		<div class="slz-desc">
			<span><?php esc_html_e( 'Thumbnail', 'slzexploore-core' );?></span>
			<span class="f-right"><?php $this->tooltip_html( esc_html__( 'Thumbnail picture of testimonial.', 'slzexploore-core' ) );?></span>
		</div>
		<div class="slz-field">
			<?php echo ( $this->single_image( 'slzexploore_testi_meta['. $prefix .'thumbnail]',
																$this->get_field( $data_meta, 'thumbnail' ),
																array( 'id'=> $prefix .'thumbnail_id',
																	'data-rel' => $prefix .'thumbnail' ) ) );?>
		</div>
	</div>
</div>