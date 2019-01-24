<div class="form-field term-color">
	<label><?php esc_html_e( 'Text Color', 'slzexploore-core' ); ?></label>
	<div>
		<?php echo ( $this->text_field( $taxonomy_opt .'[color]',
												$this->get_field( $data_meta, 'color', '' ),
												array('class' => 'slzexploore_core-meta-color') ) );?>
		<p><?php esc_html_e('Text color is how it appears on each property item. Leaving empty will using default style.', 'slzexploore-core');?></p>
	</div>
</div>
<div class="form-field term-background-color">
	<label><?php esc_html_e( 'Background Color', 'slzexploore-core' ); ?></label>
	<div>
		<?php echo ( $this->text_field( $taxonomy_opt .'[background_color]',
												$this->get_field( $data_meta, 'background_color' ),
												array('class' => 'slzexploore_core-meta-color') ) );?>
		<p><?php esc_html_e('Background color is how it appears on each property item. Leaving empty will using default style.', 'slzexploore-core');?></p>
	</div>
</div>
