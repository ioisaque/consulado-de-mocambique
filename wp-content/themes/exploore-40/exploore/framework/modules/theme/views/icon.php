<div class="wrap about-wrap slz-wrap slz-tab-style">
	<?php do_action('slzexploore_get_theme_header');?>
	<div class="slz-demo-themes slz-install-plugins slz-icons">
	<?php
	if(SLZEXPLOORE_CORE_IS_ACTIVE) {
		$sh_icons = Slzexploore_Core::get_font_icons('font-flaticon');
		unset($sh_icons['']);
		foreach( $sh_icons as $icon => $icon_name ) {
			printf('<div class="glyph">
						<div class="clearfix pbs">
							<span class="%1$s"></span>
							<span class="mls">%2$s</span>
						</div>
					</div>', $icon, $icon_name );
		}
	}
	?>
		<div class="clearfix"></div>
	</div>
</div>