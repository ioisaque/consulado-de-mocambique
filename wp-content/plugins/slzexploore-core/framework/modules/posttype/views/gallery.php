<?php $prefix = 'slzexploore_gallery_';?>
<div class="slz-custom-meta" >
	<div class="slz-meta-row active" >
		<div class="slz-desc">
			<span><?php esc_html_e( 'URL:', 'slzexploore-core' );?></span>
		</div>
		<div class="slz-field">
			<?php echo ( $this->text_field( 'slzexploore_gallery_meta[' . $prefix . 'url]',
														$this->get_field( $post_meta, 'url' ),
														array('class'=>'') ) );?>
		</div>
	</div>
	<div class="slz-meta-row active" >
		<div class="slz-desc">
			<label><?php esc_html_e( 'Gallery Images', 'slzexploore-core' );?></label>
		</div>
		<div class="slz-field">
			<?php $this->gallery( 'slzexploore_gallery_meta['. $prefix .'gallery_ids]',
								$this->get_field( $post_meta, 'gallery_ids' ),
								array('class'=>'') ); ?>
		</div>
	</div>
</div>