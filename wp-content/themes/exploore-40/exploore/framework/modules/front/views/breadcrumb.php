<?php if ( $breadcrumb = slzexploore_get_breadcrumb() ) :?>
	<div class="row">
		<div class="col-left col-sm-12">
			<ul class="breadcrumb">
				<?php
					foreach ( $breadcrumb as $key => $crumb ) {
						if ( ! empty( $crumb[1] ) && sizeof( $breadcrumb ) !== $key + 1 ) {
							echo '<li><a href="' . esc_url( $crumb[1] ) . '">' . esc_html( $crumb[0] ) . '</a></li>';
						} else {
							if( ! empty( $crumb[0] ) ) {
								echo '<li class="active">' . esc_html( $crumb[0] ) . '</li>';
							}
						}
					}
				?>
			</ul>
		</div>
	</div>
<?php endif;?>