<?php
/**
 * The sidebar containing the main widget area.
 * 
 * @author Swlabs
 * @package Exploore
 * @since 1.0
 */

if ( ! is_active_sidebar( 'slzexploore-sidebar-blog' ) ) {
	return;
}
?>
<div class="sidebar-wrapper">
	<?php dynamic_sidebar( 'slzexploore-sidebar-blog' ); ?>
</div>
