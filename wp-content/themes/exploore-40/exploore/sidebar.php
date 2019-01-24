<?php
/**
 * The sidebar containing the default widget area.
 * 
 * @author Swlabs
 * @package Exploore
 * @since 1.0
 */

if ( ! is_active_sidebar( 'slzexploore-sidebar-default' ) ) {
	return;
}
?>
<div class="sidebar-wrapper">
	<?php dynamic_sidebar( 'slzexploore-sidebar-default' ); ?>
</div>
