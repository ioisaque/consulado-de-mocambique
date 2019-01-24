<?php
	//Back end
	add_filter( 'wp_edit_nav_menu_walker', 'slzexploore_edit_nav_menu', 10, 2 );
	function slzexploore_edit_nav_menu( $walker, $menu_id ) {
		return 'Slzexploore_Nav_Menu_Edit_Custom';
	}
	
	add_action( 'wp_update_nav_menu_item', 'slzexploore_update_menu', 100, 3);

	//update menu
	function slzexploore_update_menu($menu_id, $menu_item_db)
	{
		
		$check = array('label-type','sub-label','show-megamenu','megamenu-style','megamenu-column','choose-icon','choose-widgetarea','show-widget','tab-title','megamenu-widget-column');
		foreach ( $check as $key )
		{
			if(!isset($_POST['menu-item-slz-'.esc_attr($key)][$menu_item_db]))
			{
				$_POST['menu-item-slz-'.esc_attr($key)][$menu_item_db] = "";
			}
			$value = $_POST['menu-item-slz-'.esc_attr($key)][$menu_item_db];
			update_post_meta( $menu_item_db, '_menu-item-slz-'.esc_attr($key), $value );
		}
	}
	if( !class_exists( 'Slzexploore_Nav_Menu_Edit_Custom' ) )
	{
		class Slzexploore_Nav_Menu_Edit_Custom extends Walker_Nav_Menu
		{
			/**
			 * @see Walker_Nav_Menu::start_lvl()
			 * @since 3.0.0
			 *
			 * @param string $output Passed by reference.
			 * @param int $depth Depth of page.
			 */
			public function start_lvl(&$output, $depth = 0, $args = array() ) {}

			/**
			 * @see Walker_Nav_Menu::end_lvl()
			 * @since 3.0.0
			 *
			 * @param string $output Passed by reference.
			 * @param int $depth Depth of page.
			 */
			public function end_lvl(&$output, $depth = 0, $args = array()) {}
			/**
			 * @see Walker::start_el()
			 * @since 3.0.0
			 *
			 * @param string $output Passed by reference. Used to append additional content.
			 * @param object $item Menu item data object.
			 * @param int $depth Depth of menu item. Used for padding.
			 * @param int $current_page Menu item ID.
			 * @param object $args
			 */
			public function start_el(&$output, $item, $depth = 0, $args = array(), $current_object_id = 0 ) {
				global $_wp_nav_menu_max_depth;
				$_wp_nav_menu_max_depth = $depth > $_wp_nav_menu_max_depth ? $depth : $_wp_nav_menu_max_depth;
				$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
				ob_start();
				$item_id = esc_attr( $item->ID );
				$removed_args = array(
					'action',
					'customlink-tab',
					'edit-menu-item',
					'menu-item',
					'page-tab',
					'_wpnonce',
				);

				$original_title = '';
				if ( 'taxonomy' == $item->type ) {
					$original_title = get_term_field( 'name', $item->object_id, $item->object, 'raw' );
				} elseif ( 'post_type' == $item->type ) {
					$original_object = get_post( $item->object_id );
					$original_title = $original_object->post_title;
				}

				$classes = array(
					'menu-item menu-item-depth-' . esc_attr($depth),
					'menu-item-' . esc_attr( $item->object ),
					'menu-item-edit-' . ( ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ? 'active' : 'inactive'),
				);

				$title = $item->title;

				if ( isset( $item->post_status ) && 'draft' == $item->post_status ) {
					$classes[] = 'pending';
					$title = sprintf( esc_html__('%s (Pending)', 'exploore'), $item->title );
				}

				$title = empty( $item->label ) ? $title : $item->label;

				$itemValue = "";
				if($depth == 0)
				{
					$itemValue = get_post_meta( $item->ID, '_menu-item-slz-megamenu', true);
					if($itemValue != "") $itemValue = 'slz_mega_active ';
				}
				?>
				<li id="menu-item-<?php echo esc_attr( $item_id ); ?>" class="<?php echo esc_attr( $itemValue ); echo ' ' . implode(' ', $classes ); ?>">
					<dl class="menu-item-bar">
						<dt class="menu-item-handle">
							<span class="item-title"><?php echo esc_html( $title ); ?></span>
							<span class="item-controls">
								<span class="item-type item-type-default"><?php echo esc_html( $item->type_label ); ?></span>
								<a class="item-edit" id="edit-<?php echo esc_attr( $item_id ); ?>" title="<?php esc_html_e('Edit Menu Item', 'exploore' ); ?>" href="<?php
									echo ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ? esc_url( admin_url( 'nav-menus.php' ) ) : add_query_arg( 'edit-menu-item', $item_id, remove_query_arg( $removed_args, esc_url( admin_url( 'nav-menus.php#menu-item-settings-' . $item_id ) ) ) );
								?>"><?php esc_html_e('Edit Menu Item', 'exploore' ); ?></a>
							</span>
						</dt>
					</dl>

					<div class="menu-item-settings" id="menu-item-settings-<?php echo esc_attr( $item_id ); ?>">
						<?php if( 'custom' == $item->type ) : ?>
							<p class="field-url description description-wide">
								<label for="edit-menu-item-url-<?php echo esc_attr( $item_id ); ?>">
									<?php esc_html_e( 'URL', 'exploore' ); ?><br />
									<input type="text" id="edit-menu-item-url-<?php echo esc_attr( $item_id ); ?>" class="widefat code edit-menu-item-url" name="menu-item-url[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $item->url ); ?>" />
								</label>
							</p>
						<?php endif; ?>
						<p class="description description-thin description-label slz_label_desc_on_active">
							<label for="edit-menu-item-title-<?php echo esc_attr( $item_id ); ?>">
							<span class='slz_default_label'><?php esc_html_e( 'Navigation Label', 'exploore'  ); ?></span>
								<br />
								<input type="text" id="edit-menu-item-title-<?php echo esc_attr( $item_id ); ?>" class="widefat edit-menu-item-title" name="menu-item-title[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $item->title ); ?>" />
							</label>
						</p>
						<p class="description description-thin description-title">
							<label for="edit-menu-item-attr-title-<?php echo esc_attr( $item_id ); ?>">
								<?php esc_html_e( 'Title Attribute', 'exploore'  ); ?><br />
								<input type="text" id="edit-menu-item-attr-title-<?php echo esc_attr( $item_id ); ?>" class="widefat edit-menu-item-attr-title" name="menu-item-attr-title[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $item->post_excerpt ); ?>" />
							</label>
						</p>
						<p class="field-link-target description description-thin">
							<label for="edit-menu-item-target-<?php echo esc_attr( $item_id ); ?>">
								<?php esc_html_e( 'Link Target', 'exploore'  ); ?><br />
								<select id="edit-menu-item-target-<?php echo esc_attr( $item_id ); ?>" class="widefat edit-menu-item-target" name="menu-item-target[<?php echo esc_attr( $item_id ); ?>]">
									<option value="" <?php selected( $item->target, ''); ?>><?php esc_html_e('Same window or tab', 'exploore' ); ?></option>
									<option value="_blank" <?php selected( $item->target, '_blank'); ?>><?php esc_html_e('New window or tab', 'exploore' ); ?></option>
								</select>
							</label>
						</p>
						<p class="field-css-classes description description-thin">
							<label for="edit-menu-item-classes-<?php echo esc_attr( $item_id ); ?>">
								<?php esc_html_e( 'CSS Classes (optional)' , 'exploore' ); ?><br />
								<input type="text" id="edit-menu-item-classes-<?php echo esc_attr( $item_id ); ?>" class="widefat code edit-menu-item-classes" name="menu-item-classes[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( implode(' ', $item->classes ) ); ?>" />
							</label>
						</p>
						<p class="field-xfn description description-thin">
							<label for="edit-menu-item-xfn-<?php echo esc_attr( $item_id ); ?>">
								<?php esc_html_e( 'Link Relationship (XFN)', 'exploore'  ); ?><br />
								<input type="text" id="edit-menu-item-xfn-<?php echo esc_attr( $item_id ); ?>" class="widefat code edit-menu-item-xfn" name="menu-item-xfn[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $item->xfn ); ?>" />
							</label>
						</p>
						<p class="field-description description description-wide">
							<label for="edit-menu-item-description-<?php echo esc_attr( $item_id ); ?>">
								<?php esc_html_e( 'Description', 'exploore'  ); ?><br />
								<textarea id="edit-menu-item-description-<?php echo esc_attr( $item_id ); ?>" class="widefat edit-menu-item-description" rows="3" cols="20" name="menu-item-description[<?php echo esc_attr( $item_id ); ?>]"><?php echo esc_html( $item->post_content ); ?></textarea>
							</label>
						</p>
						<?php do_action( 'wp_nav_menu_item_custom_fields', $item_id, $item, $depth, $args ); ?>
						
						<!--custom menu-->
						<div class='slz_menu_options'>
							<!--sub label-->
								<?php
								$key = "menu-item-slz-sub-label";
								$value = get_post_meta( $item->ID, '_'.$key, true);
								?>
								<p class="">
									<label for="edit-<?php echo esc_attr( $key ).'-'. esc_attr( $item_id ); ?>"><?php esc_html_e( 'Sub Label' , 'exploore'); ?><br>
										<input type="text"  id="<?php echo esc_attr( $key ).'-'. esc_attr( $item_id ); ?>" class=" <?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( $key ) . "[". $item_id ."]";?>" value="<?php echo esc_attr( $value ); ?>" />
									</label>
								</p>
								<?php
								$key = "menu-item-slz-label-type";
								$value = get_post_meta( $item->ID, '_'.esc_attr($key), true);
								?>
								<p class="">
									<label for="edit-menu-item-megamenu-column-<?php echo esc_attr( $item_id ); ?>">
										<?php esc_html_e( 'Select label type' , 'exploore'); ?>
										<select id="<?php echo esc_attr( $item_id ); ?>" class="widefat code" name="menu-item-slz-label-type[<?php echo esc_attr( $item_id ); ?>]">
											<option value="label-default" <?php selected( $value, 'label-default' ); ?>><?php esc_html_e( 'Label Default' , 'exploore'); ?></option>
											<option value="label-primary" <?php selected( $value, 'label-primary' ); ?>><?php esc_html_e( 'Label Primary' , 'exploore'); ?></option>
											<option value="label-success" <?php selected( $value, 'label-success' ); ?>><?php esc_html_e( 'Label Success' , 'exploore'); ?></option>
											<option value="label-info" <?php selected( $value, 'label-info' ); ?>><?php esc_html_e( 'Label Info' , 'exploore'); ?></option>
											<option value="label-warning" <?php selected( $value, 'label-warning' ); ?>><?php esc_html_e( 'Label Warning' , 'exploore'); ?></option>
											<option value="label-danger" <?php selected( $value, 'label-danger' ); ?>><?php esc_html_e( 'Label Danger' , 'exploore'); ?></option>
										</select>
									</label>
								</p>
							
							<!--choose icon-->
							<?php
							$key = "menu-item-slz-choose-icon";
							$value = get_post_meta( $item->ID, '_'.$key, true);
							?>
							<p class="shw_hide_d0 shw_text choose-icon  shw_mega_menu ">
								<label for="edit-<?php echo esc_attr( $key ).'-'. esc_attr( $item_id ); ?>"><?php esc_html_e( 'Enter icon for menu' , 'exploore'); ?><br>
									<input type="text"  id="edit-<?php echo esc_attr( $key ).'-'. esc_attr( $item_id ); ?>" class=" <?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( $key ) . "[". $item_id ."]";?>" value="<?php echo esc_attr( $value ); ?>" /><?php esc_html_e( 'Ex:fa-crosshairs', 'exploore' );?>
								</label>
							</p>
							<!--use mega menu-->
							<?php 
							$key = "menu-item-slz-show-megamenu";
							$show_megamenu  = get_post_meta( $item->ID, '_'.esc_attr($key), true);
							$megamenu_item = '';
							$widget_item = '';
							if( $show_megamenu  != "" ){
								$show_megamenu = "checked='checked'";
								$megamenu_item = "slz-mega-menu-d0";
								$widget_item = "open";
							}
							
							?>
							<p class="description description-wide slz-show-megamenu slz-mega-menu-d0">
								<label for="edit-<?php echo esc_attr( $key ) . '-' . esc_attr( $item_id ); ?>">
									<input type="checkbox" value="active" id="edit-<?php echo esc_attr( $key ) . '-' .esc_attr( $item_id ); ?>" class=" <?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( $key ) . "[". esc_attr( $item_id )."]";?>" <?php echo esc_attr( $show_megamenu ); ?> /><?php esc_html_e( 'Use as Mega Menu' , 'exploore'); ?>
								</label>
							</p>
							<!--Choose column for normal mega menu-->
							<?php
							$key = "menu-item-slz-megamenu-style";
							$value = get_post_meta( $item->ID, '_'.esc_attr($key), true);
							?>
							<p class="megamenu-style description description-wide <?php echo esc_attr( $megamenu_item ); ?>">
								<label for="edit-menu-item-megamenu-style-<?php echo esc_attr( $item_id ); ?>">
									<?php esc_html_e( 'Select style for megamenu' , 'exploore'); ?>
									<select id="edit-menu-item-megamenu-style-<?php echo esc_attr( $item_id ); ?>" class="widefat code edit-menu-item-megamenu-style" name="menu-item-slz-megamenu-style[<?php echo esc_attr( $item_id ); ?>]">
										<option value="0" <?php selected( $value, '0' ); ?>><?php esc_html_e( 'Block' , 'exploore'); ?></option>
										<option value="1" <?php selected( $value, '1' ); ?>><?php esc_html_e( 'Tab' , 'exploore'); ?></option>
									</select>
								</label>
							</p>
							<!--Choose column for normal mega menu-->
							<?php
							$key = "menu-item-slz-megamenu-column";
							$value = get_post_meta( $item->ID, '_'.esc_attr($key), true);
							?>
							<p class="megamenu-column description description-wide <?php echo esc_attr( $megamenu_item ); ?>">
								<label for="edit-menu-item-megamenu-column-<?php echo esc_attr( $item_id ); ?>">
									<?php esc_html_e( 'Select column number for megamenu' , 'exploore'); ?>
									<select id="edit-menu-item-megamenu-column-<?php echo esc_attr( $item_id ); ?>" class="widefat code edit-menu-item-megamenu-column" name="menu-item-slz-megamenu-column[<?php echo esc_attr( $item_id ); ?>]">
										<option value="0" <?php selected( $value, '0' ); ?>><?php esc_html_e( '1 Column' , 'exploore'); ?></option>
										<option value="1" <?php selected( $value, '1' ); ?>><?php esc_html_e( '2 Columns' , 'exploore'); ?></option>
										<option value="2" <?php selected( $value, '2' ); ?>><?php esc_html_e( '3 Columns' , 'exploore'); ?></option>
										<option value="3" <?php selected( $value, '3' ); ?>><?php esc_html_e( '4 Columns' , 'exploore'); ?></option>
									</select>
								</label>
							</p>
							

							<!--Title for menu has widget-->
							<?php
								$key = "menu-item-slz-tab-title";
								$value = get_post_meta( $item->ID, '_'.esc_attr($key), true);
							?>
							<p class="slz_text tab-title slz_mega_menu <?php echo esc_attr( $megamenu_item ); ?>">
								<label for="edit-<?php echo esc_attr( $key ) . '-' . esc_attr( $item_id ); ?>"><?php esc_html_e( 'Enter title  for mega menu style "Tab" ' , 'exploore'); ?><br>
									<input type="text"  id="edit-<?php echo esc_attr( $key ) . '-' . esc_attr( $item_id ); ?>" class="menu-text-box  <?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( $key ) . "[". esc_attr( $item_id ) ."]";?>" value="<?php esc_attr( $value ); ?>" />
								</label>
							</p>
							 <!--Choose widget area-->
							<?php
							$key = "menu-item-slz-choose-widgetarea";
							$value = get_post_meta( $item->ID, '_'.esc_attr($key), true);?>
							<p class="description description-wide choose-widgetarea  <?php echo esc_attr( $widget_item ) ;?>">
								<label for="edit-menu-item-megamenu-widgetarea-<?php echo esc_attr( $item_id ); ?>">
									<?php esc_html_e( 'Select Widget Area' , 'exploore'); ?>
									<select id="edit-menu-item-megamenu-widgetarea-<?php echo esc_attr( $item_id ); ?>" class="widefat code edit-menu-item-megamenu-widgetarea" name="menu-item-slz-choose-widgetarea[<?php echo esc_attr( $item_id ); ?>]">
										<option value="0"><?php esc_html_e( 'Select Widget Area' , 'exploore'); ?></option>
										<?php
										global $wp_registered_sidebars;
										if( ! empty( $wp_registered_sidebars ) && is_array( $wp_registered_sidebars ) ):
											foreach( $wp_registered_sidebars as $sidebar ):
										?>
										<option value="<?php echo esc_attr( $sidebar['id'] ); ?>" <?php selected( $value, $sidebar['id'] ); ?>><?php echo esc_html( $sidebar['name'] ); ?></option>
										<?php endforeach; ?>
										<?php endif; ?>
									</select>
								</label>
							</p>
							
							<!--End option-->
							<div class="menu-item-actions description-wide submitbox">
								<?php if( 'custom' != $item->type ) : ?>
									<p class="link-to-original">
										<?php printf( esc_html__('Original: %s', 'exploore'), '<a href="' . esc_url( $item->url ) . '">' . esc_html( $original_title ) . '</a>' ); ?>
									</p>
								<?php endif; ?>
								<a class="item-delete submitdelete deletion" id="delete-<?php echo esc_attr( $item_id ); ?>" href="<?php
								echo wp_nonce_url(
									add_query_arg(
										array(
											'action' => 'delete-menu-item',
											'menu-item' => $item_id,
										),
										remove_query_arg($removed_args, esc_url( admin_url( 'nav-menus.php' ) ) )
									),
									'delete-menu_item_' . $item_id
								); ?>"><?php esc_html_e('Remove', 'exploore'); ?></a> <span class="meta-sep"> | </span> <a class="item-cancel submitcancel" id="cancel-<?php echo esc_attr( $item_id ); ?>" href="<?php echo add_query_arg( array('edit-menu-item' => $item_id, 'cancel' => time()), remove_query_arg( $removed_args, esc_url( admin_url( 'nav-menus.php' ) ) ) );
									?>#menu-item-settings-<?php echo esc_attr( $item_id ); ?>"><?php esc_html_e('Cancel', 'exploore'); ?></a>
							</div>

							<input class="menu-item-data-db-id" type="hidden" name="menu-item-db-id[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $item_id ); ?>" />
							<input class="menu-item-data-object-id" type="hidden" name="menu-item-object-id[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $item->object_id ); ?>" />
							<input class="menu-item-data-object" type="hidden" name="menu-item-object[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $item->object ); ?>" />
							<input class="menu-item-data-parent-id" type="hidden" name="menu-item-parent-id[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $item->menu_item_parent ); ?>" />
							<input class="menu-item-data-position" type="hidden" name="menu-item-position[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $item->menu_order ); ?>" />
							<input class="menu-item-data-type" type="hidden" name="menu-item-type[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $item->type ); ?>" />
						</div>
					</div>
					<ul class="menu-item-transport"></ul>
				<?php
				$output .= ob_get_clean();
			} // end start_el func
		} // end class
	}

	//frontend
	if( ! function_exists( 'slzexploore_show_main_menu' ) ) {
		function slzexploore_show_main_menu() {
			$walker = new Slzexploore_Nav_Walker;
			if ( has_nav_menu( 'main-nav' ) ) {
				wp_nav_menu( array(
					'theme_location'  => 'main-nav',
					'container'       => 'ul',
					'menu_class'      => 'nav-links nav navbar-nav slzexploore-menu',
					'walker'          => $walker
				));
			}
		}
	}
	/**
	 * Add class active
	 */
	if( ! function_exists( 'slzexploore_special_nav_class' ) ) {
		function slzexploore_special_nav_class( $classes, $item ) {
			if( in_array( 'current-menu-item', $classes ) || ( in_array( 'current-menu-ancestor', $classes ) ) ) {
				$classes[] = 'active';
			}
			return $classes;
		}
		add_filter( 'nav_menu_css_class', 'slzexploore_special_nav_class', 10, 2 );
	}
	/**
	 * Menu
	 */
	class Slzexploore_Nav_Walker extends Walker_Nav_Menu {
		
		
		public function start_lvl( &$output, $depth = 0, $args = array() ) {
			$dropdown_align =  Slzexploore::get_option('slz-dropdownmenu-align');
			$indent = str_repeat( "\t", $depth );
			if ($this->mega_active == "active" ){
				if($this->mega_style == 1){
					if( $depth == 0 ) {
						$output .= $indent.'<div class="mega-menu-content menu-tabs"><div class="row"><div class="col-md-3 prn"><ul class="nav nav-tabs"><li><h3 class="heading">'.$this->menu_tab_title.'</h3></li>';
					}
					else if( $depth == 1 ){
						$output .= $indent.'<ul class="menu-tab-depth-2">';
					}else{
						$output .= $indent.'<ul>';
					}
					
				}else{
					if( $depth == 0 ) {
						$output .= $indent.'<ul class="mega-content-wrap"><li class="mega-wrap">';
					}
					else{
						$output .= $indent.'<ul class="dropdown-menu dropdown-menu-1">';
					}
				}
			}else{
				if( $depth == 0 ) {
					$output .= $indent.'<ul class="dropdown-menu dropdown-menu-1 exploore-dropdown-menu-1">';
				}
				else{
					$output .= $indent.'<ul class="dropdown-menu dropdown-menu-2 exploore-dropdown-menu-2 '.$dropdown_align.'">';
				}
			}
		}
		
		public function end_lvl( &$output, $depth = 0, $args = array() ) {
			$indent  = str_repeat( "\t", $depth );
			if ($this->mega_active == "active" ){
				if($this->mega_style == 1){
					if( $depth == 0 ) {
						$output .= $indent.'</ul></div><div class="col-md-9 pln"><div class="tab-content "></div></div></div></div>'; 
					}else{
						$output .= $indent.'</ul>'; 
					}
				}
				else{
					if( $depth == 0 ) {
						$output .= $indent.'</li></ul>';
					}else{
						$output .= $indent.'</ul>'; 
					}
				
				}
			}else{
				$output .= $indent.'</ul>'; 
			}
			
		}

		public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
			if( empty( $args ) ) {
				return '';
			}
			$args        = (object) $args;
			$indent      = ( $depth ) ? str_repeat( "\t", $depth ) : '';
			$classes     = empty( $item->classes ) ? array() : (array) $item->classes;
			$classes[]   = 'menu-item-' . $item->ID;
			$class_names = join( ' ', apply_filters('nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
			$this->icon = get_post_meta( $item->ID, '_menu-item-slz-choose-icon', true);
			if(empty($this->icon)){
				$this->icon = '';
			}else{
				$this->icon = $this->icon.' '.'menu-icon';
			}

			//setings for sub label
			$this->label = get_post_meta( $item->ID, '_menu-item-slz-sub-label', true);
			$this->label_type = get_post_meta( $item->ID, '_menu-item-slz-label-type', true);
			$label = '';
			if(!empty($this->label)){
				$label = '<span class="label '.esc_attr($this->label_type).'">'.esc_attr($this->label).'</span>';
			}

			//mega menu
			if ($depth === 0){
				$this->megamenu_column = get_post_meta( $item->ID, '_menu-item-slz-megamenu-column', true);
				$this->mega_active = get_post_meta( $item->ID, '_menu-item-slz-show-megamenu', true);
				$this->mega_style = get_post_meta( $item->ID, '_menu-item-slz-megamenu-style', true);
				$this->menu_tab_title = get_post_meta( $item->ID, '_menu-item-slz-tab-title', true);
			
				if ( $this->megamenu_column == 0 ){
					$this->megamenu_column = "col-md-12";
				}
				else if ( $this->megamenu_column == 1 ){
					$this->megamenu_column = "col-md-6";
				}
				else if ( $this->megamenu_column == 2 ){
					$this->megamenu_column = "col-md-4";
				}
				else {
					$this->megamenu_column = "col-md-3";
				}
			}else if ($depth === 1){
				$this->menu_megamenu_widgetarea = get_post_meta( $item->ID, '_menu-item-slz-choose-widgetarea', true);
			}
			if ($this->mega_active == "active"){

				if($this->mega_style == 1){
					if ( $depth == 0 ){
					$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . ' dropdown-menu-03 mega-menu" ' : '';
					}else if ( $depth == 1 ){

						$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . ' shw-tab-item " ' : '';
					}else{
						$class_names ='';
					}
					$id      = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth );
					$tab_id  = 'menutab-id-'.$item->ID;
					$id      = $id ? ' id="' . esc_attr( $id ) . '"' : '';
					$output .= $indent . '<li' . $id . $class_names . '>';

					$atts = array();
					$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
					$atts['target'] = ! empty( $item->target ) ? $item->target : '';
					$atts['rel']    = ! empty( $item->xfn ) ? $item->xfn : '';
					$atts['href']   = ! empty( $item->url ) ? $item->url : '';

					$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );
					$attributes = '';
					foreach( $atts as $attr => $value ) {
						if( ! empty( $value ) ) {
							$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
							$attributes .= ' ' . $attr . '="' . $value . '"';
						}
					}
					$item_output = $args->before;
					
					if( $depth == 0 ) {
						$item_output .= '<a class="main-menu" '. $attributes . '><span class="text"><i class="fa  '.$this->icon.'"></i>';
					} 
					else if($depth == 1){
						$item_output .= '<a href="#'.$tab_id.'"><i class="fa  '.$this->icon.'"></i></i>';
					}else{
						$item_output .= '<a class="link-page" ' . $attributes . '><span class="text"><i class="fa  '.$this->icon.'"></i>';
					}
					
					$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
					if ($depth != 1){
						$item_output .= '</span>';
					}
					
					if( $args->walker->has_children && $depth == 0 ){
						$item_output .= '<span class="fa fa-angle-down icons-dropdown"></span>';
					}
					
					$item_output .= $label;
					$item_output .= '</a>';
					
					if ($depth == 1){
						//add widget
						if( $this->menu_megamenu_widgetarea && is_active_sidebar( $this->menu_megamenu_widgetarea )) {
							$item_output .= '<div data-column="'. $this->megamenu_column.'" id="'.$tab_id.'" class="row tab-pane '.$this->menu_megamenu_widgetarea.'"><div class="tab-content-item">';
								ob_start();
								dynamic_sidebar( $this->menu_megamenu_widgetarea );
							$item_output .= ob_get_clean() . '</div></div>';
						}else{
							$item_output .= '<div data-column="'. $this->megamenu_column.'" id="'.$tab_id.'" class="tab-pane row"><div class="tab-content-item tab-widget-none"></div></div>';
						}
					}
					$item_output .= $args->after;
					
					$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );

				}else{

					if ( $depth == 0 ){
					$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . ' mega-menu dropdown" ' : '';
					}else if ( $depth == 1 ){
						$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . ' mega-menu-title sub-menu " ' : '';
					}else{
						$class_names ='';
					}
				
					$id      = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth );
					$id      = $id ? ' id="' . esc_attr( $id ) . '"' : '';
					if ( $depth == 1 ){
						$output .= '<div class="mega-menu-column '.$this->megamenu_column.'"><ul class="mega-menu-column-box">';
					}
					$output .= $indent . '<li' . $id . $class_names . '>';

					$atts = array();
					$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
					$atts['target'] = ! empty( $item->target ) ? $item->target : '';
					$atts['rel']    = ! empty( $item->xfn ) ? $item->xfn : '';
					$atts['href']   = ! empty( $item->url ) ? $item->url : '';

					$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );
					$attributes = '';
					foreach( $atts as $attr => $value ) {
						if( ! empty( $value ) ) {
							$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
							$attributes .= ' ' . $attr . '="' . $value . '"';
						}
					}
					$item_output = $args->before;
					
					if( $depth == 0 ) {
						$item_output .= '<a class="main-menu" '. $attributes . '><span class="text"><i class="fa  '.$this->icon.'"></i>';
					} 
					else if($depth == 1){
						$item_output .= '<a href="javascript:void(0)" class="sf-with-ul"><i class="fa  '.$this->icon.'"></i><span>';
					}else{
						$item_output .= '<a class="link-page" ' . $attributes . '><span class="text"><i class="fa  '.$this->icon.'"></i>';
					}

					$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;

					$item_output .= $label.'</span>';

					if( $args->walker->has_children && $depth == 0 ){
						$item_output .= '<span class="fa fa-angle-down icons-dropdown"></span>';
					}

					$item_output .= '</a>';
					if( $depth == 0 ) {
					$item_output .= 
					'<div class="dropdown-menu mega-menu-content clearfix">';
					} 
					if ($depth == 1){
						//add widget
						if( $this->menu_megamenu_widgetarea && is_active_sidebar( $this->menu_megamenu_widgetarea )) {
							$item_output .= '<div class="tab-pane '.$this->menu_megamenu_widgetarea.'">';
								ob_start();
								dynamic_sidebar( $this->menu_megamenu_widgetarea );
							$item_output .= ob_get_clean() . '</div>';
						}
					}
					$item_output .= $args->after;
					
					$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );

					}
					
			}else {
				
				if( $args->walker->has_children ) {
					if ( $depth == 0 ){
						$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . ' dropdown menu-item-depth1" ' : '';
					}else{
						$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . ' dropdown " ' : '';
					}
				}else {
					$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '" ' : '';
				}

				$id      = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth );
				$id      = $id ? ' id="' . esc_attr( $id ) . '"' : '';
				$output .= $indent . '<li' . $id . $class_names . '>';

				$atts = array();
				$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
				$atts['target'] = ! empty( $item->target ) ? $item->target : '';
				$atts['rel']    = ! empty( $item->xfn ) ? $item->xfn : '';
				$atts['href']   = ! empty( $item->url ) ? $item->url : '';

				$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );
				$attributes = '';
				foreach( $atts as $attr => $value ) {
					if( ! empty( $value ) ) {
						$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
						$attributes .= ' ' . $attr . '="' . $value . '"';
					}
				}
				
				$item_output = $args->before;
				if( $depth == 0 ) {
					$item_output .= '<a class="main-menu" '. $attributes . '>';
				} 
				else{
					$item_output .= '<a class="link-page" ' . $attributes . '>';
				}
				if( $args->walker->has_children && $depth != 0 ){
					$item_output .= '<span class="fa fa-angle-right icons-dropdown"></span>';
				}
				$item_output .= '<i class="fa  '.$this->icon.'"></i>';
				$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
				$item_output .= $label;
				if( $args->walker->has_children && $depth == 0 ){
					$item_output .= '<span class="icons-dropdown"><i class="fa fa-angle-down"></i></span>';
				}
				$item_output .= '</a>';
				$item_output .= $args->after;
				
				$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
			}
		}
		public function end_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
			if ($this->mega_active == "active"){
				if($this->mega_style == 1){
					$output .= "</li>\n";
				}else{
					if( $depth == 0 ) {
						$output .= "</div></li>\n";
					}else if( $depth == 1 ){
						$output .= "</li></ul></div>\n";
					}else{
						$output .= "</li>\n";
					}
				}
			}else{
				$output .= "</li>\n";
			}
		}
	}