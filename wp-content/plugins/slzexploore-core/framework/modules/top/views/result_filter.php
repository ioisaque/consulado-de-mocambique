<div class="result-filter">
	<div class="hide slz-sort-type" data-type="<?php echo esc_attr( $sort_type ); ?>"></div>
	<label class="result-filter-label">
		<?php esc_html_e( 'Sort by:', 'slzexploore-core' ); ?>
	</label>
	<div class="selection-bar">
		<div class="select-wrapper">
		<?php
			if(!empty($enable_type)){
				$type = $sort_type;
			}else{
				$type = slzexploore_is_custom_post_type_archive();
			}
			$sort_by = Slzexploore_Core::get_params('filter-' . $type);
			echo ( $this->drop_down_list('sort_by',
										'',
										$sort_by,
										array('class' => 'custom-select selectbox') ) );
		?>
		</div>
	</div>
</div>