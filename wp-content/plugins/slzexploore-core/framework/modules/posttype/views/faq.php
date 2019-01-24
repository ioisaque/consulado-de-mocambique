<?php
	$prefix = 'slzexploore_faq_';
?>
<div class="tab-panel">

	<ul class="tab-list">
		<li class="active">
			<a href="slz-tab-customer"><?php esc_html_e( 'FAQs Request', 'slzexploore-core' );?></a>
		</li>
	</ul>
	<div class="tab-container">
		<div class="tab-wrapper slz-page-meta">
			<!-- FAQs Request Information-->
			<div id="slz-tab-customer" class="tab-content active">
				<table class="form-table">
					<?php
					$fields = $this->get_field( $data_meta, 'fields' );
					if ( !empty($fields) ) {
						foreach ($fields as $key => $value) {
					?>
						<tr>
							<th scope="row">
								<label><?php printf( '%s', esc_html( $key ) ) ?></label>
							</th>
							<td>
								<?php echo ( $this->text_field( $prefix .'meta['. $prefix . $key .']', get_post_meta( get_the_ID(), $prefix . $key, true ), array( 'class' => 'slz-block' ) ) );?>
							</td>
						</tr>
					<?php
						}
					} else {
						printf( '<p>%s</p>', esc_html__( 'No content', 'slzexploore-core' ) );
					}

					$meta = $this->get_field( $data_meta, 'meta' );
					if ( !empty($meta) ) {
						foreach ($meta as $key => $value) {
					?>
						<tr>
							<th scope="row">
								<label><?php printf( '%s', esc_html( $key ) ) ?></label>
							</th>
							<td>
								<?php printf( '%s', esc_html( $value ) ) ?>
							</td>
						</tr>
					<?php
						}
					}
					?>
				</table>
				
			</div>
		</div>
	</div>

</div>
