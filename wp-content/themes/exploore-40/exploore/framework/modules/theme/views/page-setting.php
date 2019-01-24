<?php
	// Slider Revolution
	global $wpdb;
	$revolution_sliders = array( '' => esc_html('No Slider', 'exploore') );
	if( SLZEXPLOORE_REVSLIDER_ACTIVE ) {
		$db_revslider = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM '.$wpdb->prefix.'revslider_sliders %', '' ), OBJECT );
		if ( $db_revslider ) {
			foreach ( $db_revslider as $slider ) {
				$revolution_sliders[$slider->alias] = $slider->title;
			}
		}
	}
	$header_top_menu = array( '' => esc_html('Select Menu', 'exploore') );
	$nav_menus = wp_get_nav_menus( array( 'fields' => 'id=>name' ) );
	foreach( $nav_menus as  $id => $name){
		$header_top_menu[$id] = $name;
	}


	// post
	$image_uri = get_template_directory_uri() . '/assets/admin/images/';
	$img_options = array( 'class' => 'slz-block-9' );
	$header_layout = Slzexploore::get_params( 'header_layout');
	$header_layout = $this->radio_image_label( $header_layout, $image_uri, $img_options );
	$html_options_img = array(
		'separator'    => '',
		'class'        => 'slz-w190 hide',
		'labelOptions' => array(
			'class'          => ' slz-image-select ',
			'selected_class' => ' slz-image-select-selected ',
		)
	);
	$html_options = array(
		'separator' => '&nbsp;&nbsp;',
		'class' => 'slz-w190'
	);
	$sidebar_layout = 'sidebar_layout';
	$sidebar_layout_id = 'sidebar_id';
	$screen = get_current_screen();
	$pt_bg_image_show = true;
	$pt_bg_prefix = 'pt_';
	$is_page = false;
	$show_title = false;
	if( $screen ) {
		$screen_type = $screen->post_type;
		switch( $screen_type ) {
			case 'page':
				$is_page = true;
				$show_title = true;
				break;
			case 'post':
				$sidebar_layout = 'sidebar_post_layout';
				$sidebar_layout_id = 'sidebar_post_id';
				break;
			case 'slzexploore_tour':
				$sidebar_layout = 'sidebar_tour_layout';
				$sidebar_layout_id = 'sidebar_tour_id';
				break;
			case 'slzexploore_hotel':
				$sidebar_layout = 'sidebar_hotel_layout';
				$sidebar_layout_id = 'sidebar_hotel_id';
				break;
			case 'slzexploore_car':
				$sidebar_layout = 'sidebar_car_layout';
				$sidebar_layout_id = 'sidebar_car_id';
				break;
			case 'slzexploore_cruise':
				$sidebar_layout = 'sidebar_cruise_layout';
				$sidebar_layout_id = 'sidebar_cruise_id';
				break;
			case 'product':
				$sidebar_layout = 'sidebar_shop_layout';
				$sidebar_layout_id = 'sidebar_shop_id';
				break;
		}
	}
	$footer_style = $this->get_field( $page_options, 'footer_style', $defaults );
	if( empty($footer_style)) {
		$footer_style = 'dark';
	}
?>
<div class="tab-panel slz-tab-mbox">
	<ul class="tab-list">
		<li class="slz-tab active slz-tab-general">
			<a href="slz-tab-page-general"><?php esc_html_e( 'General', 'exploore' );?></a>
		</li>
		<li class="slz-tab">
			<a href="slz-tab-page-header"><?php esc_html_e( 'Header', 'exploore' );?></a>
		</li>
		<?php if( $is_page || in_array(  get_post_type(), $posttype_support_header_content ) ): ?>
		<li class="slz-tab">
			<a href="slz-tab-page-header-content"><?php esc_html_e( 'Header Content', 'exploore' );?></a>
		</li>
		<?php endif;?>
		<li class="slz-tab">
			<a href="slz-tab-page-pagetitle"><?php esc_html_e( 'Page Title', 'exploore' );?></a>
		</li>
		<li class="slz-tab">
			<a href="slz-tab-page-sidebar"><?php esc_html_e( 'Sidebar', 'exploore' );?></a>
		</li>
		<li class="slz-tab">
			<a href="slz-tab-page-footer"><?php esc_html_e( 'Footer', 'exploore' );?></a>
		</li>
	</ul>
	<div class="tab-container">
		<div class="tab-wrapper slz-page-meta">
			<!-- General -->
			<div id="slz-tab-page-general" class="tab-content active slz-tab-general">
				<table class="form-table">
					
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Hide Header', 'exploore' );?></label>
							<span class="f-right"><?php $this->tooltip_html( esc_html__( 'Show/Hide header.', 'exploore' ) );?></span>
						</th>
						<td>
							<?php echo ( $this->drop_down_list( 'slzexploore_page_options[slider-header-fixed]',
																	$this->get_field( $page_options, 'slider-header-fixed' ),
																	$params['show_header'],
																	array( 'class' => 'slz-w190' ) ) );?>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Body Extra Class', 'exploore' );?></label>
							<span class="f-right"><?php $this->tooltip_html( esc_html__( 'Add custom class if you want to change style of your site.', 'exploore' ) );?></span>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_page_options[body_extra_class]',
																$this->get_field( $page_options, 'body_extra_class' ),
																array() ) );?>
						</td>
					</tr>
					<tr>
						<th scope="row">
							
							<span class="f-right"><?php $this->tooltip_html( esc_html__( 'Enter content top/bottom padding (px)', 'exploore' ) );?></span>
							<label><?php wp_kses(_e( 'Content Padding <br/> (Top/Bottom)', 'exploore' ), array('br' => array()));?></label>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_page_options[ct_padding_top]',
																$this->get_field( $page_options, 'ct_padding_top' ),
																array( 'class' => '' ) ) );?>
							<?php echo ( $this->text_field( 'slzexploore_page_options[ct_padding_bottom]',
																$this->get_field( $page_options, 'ct_padding_bottom' ),
																array( 'class' => '' ) ) );?>
						</td>
					</tr>
					<!-- Default -->
					<tr>
						<th scope="row">
							<label><?php echo ( $this->check_box( 'slzexploore_page_options[general_default]',
																	$this->get_field( $page_options, 'general_default', 1 ),
																	array( 'class' => 'slz-general-option' ) ) );
									esc_html_e( 'Default Setting', 'exploore' )?></label>
							<span class="f-right"><?php $this->tooltip_html(esc_html__( 'Using setting of theme options. All below setting will NOT be allowed. Uncheck to change setting this page.', 'exploore' ) );?></span>
						</th>
						<td></td>
					</tr>
				</table>
				<table id="div_slz_general_option" class="form-table <?php echo ( $this->get_field( $page_options, 'general_default', 1 )? 'hide' : '' ); ?>">
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Body Background', 'exploore' );?></label>
							<span class="f-right"><?php $this->tooltip_html(esc_html__( 'Setting background in the page.', 'exploore' ) .'<br/>background-color <br/>background-repeat, background-size <br/>background-attachment, background-position <br/>background-image' );?></span>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_page_options[background_color]',
																$this->get_field( $page_options, 'background_color', $defaults ),
																array('class' => 'slzexploore_core-meta-color') ) );?>
							<span class="valign-top">
								<?php echo (  $this->check_box( 'slzexploore_page_options[background_transparent]',
																	$this->get_field( $page_options, 'background_transparent', $defaults ),
																	array( 'id'=>'background_transparent_id' ,'value' => 'transparent') ) );
									esc_html_e( 'Transparent', 'exploore' );?>
							</span>
							<br/>
							<div><?php echo ( $this->drop_down_list( 'slzexploore_page_options[background_repeat]',
																		$this->get_field( $page_options, 'background_repeat', $defaults ),
																		$params['background-repeat'],
																		array( 'class' => 'slz-w200' ) ) );?>
								<?php echo ( $this->drop_down_list( 'slzexploore_page_options[background_size]',
																		$this->get_field( $page_options, 'background_size', $defaults ),
																		$params['background-size'],
																		array( 'class' => 'slz-w200' ) ) );?>
								
							</div>
							<br/>
							<div>
								<?php echo ( $this->drop_down_list( 'slzexploore_page_options[background_attachment]',
																		$this->get_field( $page_options, 'background_attachment', $defaults ),
																		$params['background-attachment'],
																		array( 'class' => 'slz-w200' ) ) );?>
								<?php echo ( $this->drop_down_list( 'slzexploore_page_options[background_position]',
																		$this->get_field( $page_options, 'background_position', $defaults ),
																		$params['background-position'],
																		array( 'class' => 'slz-w200' ) ) ); ?>
								
							</div>
							<br/>
							<div class="bg-image">
								<?php echo ( $this->text_field( 'slzexploore_page_options[background_image]',
																	esc_attr( $params['bg_image']['url'] ),
																	array( 'id' => 'slz_bg_image_name', 'readonly'=>'readonly', 'class'=> 'slz-block') ) );?>
								<input type="hidden" name="slzexploore_page_options[background_image_id]" id="slz_bg_image_id" value="<?php echo esc_attr( $params['bg_image']['id'] ); ?>" />
								<div class="screenshot <?php echo esc_attr( $params['bg_image']['class'] );?>" >
									<img src="<?php echo esc_attr( $params['bg_image']['url'] ); ?>" />
								</div>
								<br/>
								<input type="button" data-rel="slz_bg_image" class="button slz-btn-upload" value="<?php esc_attr_e( 'Upload Image', 'exploore' )?>" />
								<input type="button" data-rel="slz_bg_image" class="button slz-btn-remove <?php echo esc_attr( $params['bg_image']['class'] );?>" value="<?php esc_attr_e( 'Remove', 'exploore' )?>" />
							</div>
						</td>
					</tr>
				</table>
			</div>
			
			<!-- Header -->
			<div id="slz-tab-page-header" class="tab-content">
				<table class="form-table">
					<tr>
						<th scope="row">
							<label><?php echo ( $this->check_box( 'slzexploore_page_options[header_default]',
																	$this->get_field( $page_options, 'header_default', 1 ),
																	array( 'class' => 'slz-header-option' ) ) );
									esc_html_e( 'Default Setting', 'exploore' )?></label>
							<span class="f-right"><?php $this->tooltip_html(esc_html__( 'Using setting of theme options. All below setting will NOT be allowed. Uncheck to change setting this page.', 'exploore' ) );?></span>
						</th>
						<td></td>
					</tr>
				</table>
				<table id="div_slz_header_option" class="form-table <?php echo ($this->get_field( $page_options, 'header_default', 1 )? 'hide' : '');?>">
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Header Sticky', 'exploore' );?></label>
							<span class="f-right"><?php $this->tooltip_html( esc_html__( 'Enable/Disable fixed header when scroll', 'exploore' ) );?></span>
						</th>
						<td>
							<label><?php echo ( $this->check_box( 'slzexploore_page_options[header_sticky_enable]',
																	$this->get_field( $page_options, 'header_sticky_enable', 1 ),
																	array( 'class' => '' ) ) );
									esc_html_e( 'Enable', 'exploore' )?></label>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Header Style', 'exploore' );?></label>
							<span class="f-right"><?php $this->tooltip_html( esc_html__( 'Choose header layout to display in the page.', 'exploore' ) );?></span>
						</th>
						<td class="slz-mbox-radio-row">
							<?php echo ( $this->radio_button_list( 'slzexploore_page_options[header_layout]',
																		$this->get_field( $page_options, 'header_layout', $defaults ),
																		$header_layout,
																		$html_options_img ) );?>
							
						</td>
					</tr>
					<tr class="last">
						<th scope="row" colspan="2">
							<label><?php esc_html_e( 'Show Topbar Menu', 'exploore' );?></label>
							<p class="description"><?php esc_html_e( 'Show/Hide menu in topbar.', 'exploore' );?></p>
						</th>
					</tr>
					<tr>
						<td colspan="2" class="slz-mbox-show-header-top-menu">
							<?php echo ( $this->drop_down_list( 'slzexploore_page_options[header_top_show_menu]',
																	$this->get_field( $page_options, 'header_top_show_menu' ),
																	$params['show'],
																	array( 'class' => 'slz-w190' ) ) );?>
						</td>
					</tr>
				</table>
				<?php
					$show_menu = $this->get_field( $page_options, 'header_top_show_menu', '');
					$cls_hide = 'hide';
					if( !empty($show_menu) ){
						$cls_hide = '';
					}
				?>
				<table class="form-table slz-mbox-header-top-menu <?php echo esc_attr( $cls_hide ); ?>">
					<tr class="last">
						<th scope="row" colspan="2">
							<label><?php esc_html_e( 'Menu Position', 'exploore' );?></label>
							<p class="description"><?php esc_html_e( 'Select where display menu in header top.', 'exploore' );?></p>
						</th>
					</tr>
					<tr>
						<td colspan="2">
							<?php echo ( $this->radio_button_list( 'slzexploore_page_options[header_top_menu_position]',
																		$this->get_field( $page_options, 'header_top_menu_position', $defaults),
																		$params['header_top_menu_pos'],
																		$html_options ) );?>
						</td>
					</tr>
					<tr class="last">
						<th scope="row">
							<label><?php esc_html_e( 'Select Menu', 'exploore' );?></label>
							<p class="description"><?php esc_html_e( 'Select menu for topbar menu', 'exploore' );?></p>
						</th>
					</tr>
					<tr class="last">
						<td>
							<?php echo ( $this->drop_down_list( 'slzexploore_page_options[header_top_menu]',
																	$this->get_field( $page_options, 'header_top_menu', $defaults ),
																	$header_top_menu,
																	array( 'class' => 'slz-w190' ) ) );?>
							
						</td>
					</tr>
				</table>
			</div>
			<!-- Header Content-->
			<?php if($is_page):?>
			<div id="slz-tab-page-header-content" class="tab-content">
				<table class="form-table">
					<tr class="last">
						<th scope="row" colspan="2">
							<label><?php esc_html_e( 'Header content type. ', 'exploore' );?></label>
							<p class="description"><?php esc_html_e( 'Choose header content type to display in the page. ', 'exploore' );?></p>
						</th>
					</tr>
					<tr>
						<td colspan="2" class="slz-mbox-header-content">
							<?php echo ( $this->radio_button_list( 'slzexploore_page_options[header_content_type]',
																		$this->get_field( $page_options, 'header_content_type'),
																		$params['header_content_type'],
																		$html_options ) );?>
						</td>
					</tr>
				</table>
				<table class="form-table slz-mbox-header-content-slider <?php echo esc_attr($header_content_display['slider'])?>">
					<tr class="last">
						<th scope="row">
							<label><?php esc_html_e( 'Choose Slider', 'exploore' );?></label>
							<p class="description"><?php echo wp_kses(__( 'Display or not slider in the page.<br/> Default no display slider in the page. To add new slider, please go to ', 'exploore' ), array('br') ) .'<a href="'.esc_url( admin_url( 'admin.php?page=revslider' ) ).'" >Slider Revolutions</a>';?></p>
						</th>
					</tr>
				
					<tr>
						<td>
							<?php echo ( $this->drop_down_list( 'slzexploore_page_options[header_revolution_slider]',
																	$this->get_field( $page_options, 'header_revolution_slider', $defaults ),
																	$revolution_sliders,
																	array( 'class' => 'slz-w190' ) ) );?>
							
						</td>
					</tr>
				</table>
				<table class="form-table slz-mbox-header-content-custom <?php echo esc_attr($header_content_display['custom'])?>">
					<tr class="last">
						<th scope="row" colspan="2">
							<label><?php esc_html_e( 'Search Form', 'exploore' );?></label>
							<p class="description"><?php esc_html_e( 'Show/Hide search form(only aplly for header one,two,three).', 'exploore' );?></p>
						</th>
					</tr>
					<tr>
						<td colspan="2">
							<?php echo ( $this->drop_down_list( 'slzexploore_page_options[header_search_form]',
																	$this->get_field( $page_options, 'header_search_form' ),
																	$params['show'],
																	array( 'class' => 'slz-w190' ) ) );?>
						</td>
					</tr>
					<tr class="last" >
						<th scope="row" colspan="2">
							<label><?php esc_html_e( 'Header Background(required)', 'exploore' );?></label>
							<p class="description"><?php esc_html_e( 'Add image, video link in header.', 'exploore' );?></p>
						</th>
					</tr>
					<tr class="last">
						<td scope="row" colspan="2">
							<label><?php esc_html_e( 'Image', 'exploore' );?></label>
						</td>
					</tr>
					<tr class="last">
						<td colspan="2" class="second">
							<?php echo ( $this->single_image( 'slzexploore_page_options[header_bg_image]',
																$this->get_field( $page_options, 'header_bg_image' ),
																array( 'id'=> 'header_image_id',
																	'data-rel' => 'header_image' ) ) );?>
						</td>
					</tr>
					<tr class="last">
						<td scope="row" colspan="2">
							<label><?php esc_html_e( 'Video', 'exploore' );?></label>
							<p class="description"><?php esc_html_e( 'Add video to make background for header(only aplly for header four).', 'exploore' );?></p>
						</td>
					</tr>
					<tr class="last">
						<td colspan="2" class="second">
							<?php echo ( $this->upload_video( "slzexploore_page_options[header_bg_video]",$this->get_field($page_options,'header_bg_video'), esc_html__('MP4 Upload', 'exploore' ), esc_html__('Choose file .mp4 to upload', 'exploore' )) );?>
						</td>
					</tr>
					<tr class="last">
						<th scope="row" colspan="2">
							<label><?php esc_html_e( 'Caption', 'exploore' );?></label>
							<p class="description"><?php esc_html_e( 'Enter information to caption in header.', 'exploore' );?></p>
						</th>
					</tr>
					<tr class="last">
						<td scope="row" colspan="2">
							<label><?php esc_html_e( 'Caption Text 1', 'exploore' );?></label>
						</td>
					</tr>
					<tr class="last">
						<td class="second">
							<?php echo ( $this->text_field( 'slzexploore_page_options[header_caption_1]',
																	$this->get_field( $page_options, 'header_caption_1' ),
																	array( 'class' => 'slz-block' ) ) );?>
						</td>
					</tr>
					
					<tr class="last">
						<td scope="row" colspan="2">
							<label><?php esc_html_e( 'Font size (px)', 'exploore' );?></label>
						</td>
					</tr>
					<tr class="last">
						<td class="second">
							<?php echo ( $this->text_field( 'slzexploore_page_options[font_size_1]',
																	$this->get_field( $page_options, 'font_size_1' ),
																	array( 'class' => 'slz-w190' ) ) );?>
						</td>
					</tr>
					<tr class="last">
						<td scope="row" colspan="2">
							<label><?php esc_html_e( 'Caption Text 2', 'exploore' );?></label>
						</td>
					</tr>
					<tr class="last">
						<td class="second">
							<?php echo ( $this->text_field( 'slzexploore_page_options[header_caption_2]',
																	$this->get_field( $page_options, 'header_caption_2' ),
																	array( 'class' => 'slz-block' ) ) );?>
						</td>
					</tr>
					
					<tr class="last">
						<td scope="row" colspan="2">
							<label><?php esc_html_e( 'Font size (px)', 'exploore' );?></label>
						</td>
					</tr>
					<tr>
						<td class="second">
							<?php echo ( $this->text_field( 'slzexploore_page_options[font_size_2]',
																	$this->get_field( $page_options, 'font_size_2' ),
																	array( 'class' => 'slz-block-half' ) ) );?>
						</td>
					</tr>
					<!-- Button -->
					<tr class="last">
						<th scope="row" colspan="2">
							<label><?php esc_html_e( 'Button', 'exploore' );?></label>
							<p class="description"><?php esc_html_e( 'Enter information to button in header.', 'exploore' );?></p>
						</th>
					</tr>
					<tr class="last">
						<td scope="row" colspan="2">
							<label><?php esc_html_e( 'Button Content 1', 'exploore' );?></label>
						</td>
					</tr>
					<tr class="last">
						<td class="second">
							<?php echo ( $this->text_field( 'slzexploore_page_options[header_button]',
																	$this->get_field( $page_options, 'header_button' ),
																	array( 'class' => 'slz-block-half' ) ) );?>
						</td>
					</tr>
					<tr class="last">
						<td scope="row" colspan="2">
							<label><?php esc_html_e( 'Button Hover Content', 'exploore' );?></label>
						</td>
					</tr>
					<tr class="last">
						<td class="second">
							<?php echo ( $this->text_field( 'slzexploore_page_options[header_button_hover]',
																	$this->get_field( $page_options, 'header_button_hover' ),
																	array( 'class' => 'slz-block-half' ) ) );?>
						</td>
					</tr>
					<tr class="last">
						<td scope="row" colspan="2">
							<label><?php esc_html_e( 'Button Link', 'exploore' );?></label>
						</td>
					</tr>
					<tr class="last">
						<td class="second">
							<?php echo ( $this->text_field( 'slzexploore_page_options[button_link]',
																	$this->get_field( $page_options, 'button_link' ),
																	array( 'class' => 'slz-block' ) ) );?>
						</td>
					</tr>
				</table>
			</div>
            <?php
            elseif( in_array(  get_post_type(), $posttype_support_header_content ) ):
	            $arr_desc = array(
		            'slzexploore_tour'   => esc_html__( 'Choose header content type to display in the page. Default display page title background or Featured image ( setting from Theme options > Tour Setting > Page Title Background ).', 'exploore' ),
		            'slzexploore_hotel'  => esc_html__( 'Choose header content type to display in the page. Default display page title background or Featured image ( setting from Theme options > Accommodations Setting > Page Title Background ).', 'exploore' ),
		            'slzexploore_car'    => esc_html__( 'Choose header content type to display in the page. Default display page title background or Featured image ( setting from Theme options > Car Rent Setting > Page Title Background ).', 'exploore' ),
		            'slzexploore_cruise' => esc_html__( 'Choose header content type to display in the page. Default display page title background or Featured image ( setting from Theme options > Cruises Setting > Page Title Background ).', 'exploore' ),
	            );
            ?>
            <div id="slz-tab-page-header-content" class="tab-content">
                <table class="form-table">
                    <tr class="last">
                        <th scope="row" colspan="2">
                            <label><?php esc_html_e( 'Header content type. ', 'exploore' );?></label>
                            <p class="description"><?php echo ( $arr_desc[ get_post_type() ] ) ?></p>
                        </th>
                    </tr>
                    <tr>
                        <td colspan="2" class="slz-mbox-header-content">
                            <?php echo ( $this->radio_button_list( 'slzexploore_posttype_header_options[posttype_header_content_type]',
                                $this->get_field( $posttype_header_options, 'posttype_header_content_type'),
                                $posttype_header_content_type,
                                $html_options ) );?>
                        </td>
                    </tr>
                </table>
                <table class="form-table slz-mbox-header-content-slider <?php echo esc_attr( $posttype_header_content_display['slider'] ) ?>">
                    <tr class="last">
                        <th scope="row">
                            <label><?php esc_html_e( 'Choose Slider', 'exploore' );?></label>
                            <p class="description"><?php echo wp_kses(__( 'Display or not slider in the page.<br/> Default no display slider in the page. To add new slider, please go to ', 'exploore' ), array('br') ) .'<a href="'.esc_url( admin_url( 'admin.php?page=revslider' ) ).'" >Slider Revolutions</a>';?></p>
                        </th>
                    </tr>

                    <tr>
                        <td>
                            <?php echo ( $this->drop_down_list( 'slzexploore_posttype_header_options[posttype_header_revolution_slider]',
                                $this->get_field( $posttype_header_options, 'posttype_header_revolution_slider', $defaults ),
                                $revolution_sliders,
                                array( 'class' => 'slz-w190' ) ) );?>

                        </td>
                    </tr>
                </table>
                <table class="form-table slz-mbox-header-content-custom <?php echo esc_attr( $posttype_header_content_display['custom'] ) ?>">
                    <tr>
                        <th scope="row">
                            <label><?php esc_html_e( 'Height', 'exploore' );?></label>
                            <span class="f-right"><?php $this->tooltip_html( esc_html__( 'Enter header content height.', 'exploore' ) );?></span>
                        </th>
                        <td>
			                <?php echo ( $this->text_field( 'slzexploore_posttype_header_options[posttype_header_height]',
				                $this->get_field( $posttype_header_options, 'posttype_header_height' ),
				                array() ) );?>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label><?php esc_html_e( 'Slider Speed', 'exploore' );?></label>
                            <span class="f-right"><?php $this->tooltip_html( esc_html__( 'Enter speed of slider. Default: 300.', 'exploore' ) );?></span>
                        </th>
                        <td>
			                <?php echo ( $this->text_field( 'slzexploore_posttype_header_options[posttype_header_slider_speed]',
				                $this->get_field( $posttype_header_options, 'posttype_header_slider_speed' ),
				                array() ) );?>
                        </td>
                    </tr>
                    <tr class="last">
                        <th scope="row" colspan="2">
                            <label><?php esc_html_e( 'Images', 'slzexploore-core' );?></label>
                            <p class="description"><?php esc_html_e( 'Upload images for slider.', 'slzexploore-core' );?></p>
                        </th>
                    </tr>
                    <tr class="last">
                        <td colspan="2" class="header_content_gallery">
			                <?php $this->gallery( 'slzexploore_posttype_header_options[posttype_header_gallery_ids]',
				                $this->get_field( $posttype_header_options, 'posttype_header_gallery_ids', '' ) ); ?>
                        </td>
                    </tr>
                </table>
        </div>
			<?php endif;?>
			<!-- Page Title -->
			<div id="slz-tab-page-pagetitle" class="tab-content">
				<table class="form-table">
					<tr>
						<th scope="row">
							<label><?php echo ( $this->check_box( 'slzexploore_page_options[page_title_default]',
																	$this->get_field( $page_options, 'page_title_default', 1 ),
																	array( 'class' => 'slz-page-title-option' ) ) );
									esc_html_e( 'Default Setting', 'exploore' )?></label>
							<span class="f-right"><?php $this->tooltip_html(esc_html__( 'Using setting of theme options. All below setting will NOT be allowed. Uncheck to change setting this page.', 'exploore' ) );?></span>
						</th>
						<td></td>
					</tr>
				</table>
				<table id="div_slz_page_title_option" class="form-table <?php echo ($this->get_field( $page_options, 'page_title_default', 1 )? 'hide' : '');?>">
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Show Page Title', 'exploore' );?></label>
							<span class="f-right"><?php $this->tooltip_html( esc_html__( 'Show/Hide page title in the page', 'exploore' ) );?></span>
						</th>
						<td>
							<label><?php echo ( $this->check_box( 'slzexploore_page_options[page_title_show]',
																	$this->get_field( $page_options, 'page_title_show', 1 ),
																	array( 'class' => '' ) ) );
									esc_html_e( 'Show', 'exploore' )?></label>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Background Style', 'exploore' );?></label>
							<span class="f-right"><?php $this->tooltip_html(esc_html__( 'Setting background of page title in the page.', 'exploore' ) .'<br/>background-color <br/>background-repeat, background-size <br/>background-attachment, background-position <br/>background-image' );?></span>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_page_options['.$pt_bg_prefix.'background_color]',
																$this->get_field( $page_options, $pt_bg_prefix.'background_color', $defaults ),
																array('class' => 'slzexploore_core-meta-color') ) );?>
							<span class="valign-top">
								<?php echo ( $this->check_box( 'slzexploore_page_options['.$pt_bg_prefix.'background_transparent]',
																	$this->get_field( $page_options, $pt_bg_prefix.'background_transparent', $defaults ),
																	array( 'class' => '', 'value' => 'transparent' ) ) );
									esc_html_e( 'Transparent', 'exploore' )?>
							</span>
							<br/>
							<div><?php echo ( $this->drop_down_list( 'slzexploore_page_options['.$pt_bg_prefix.'background_repeat]',
																		$this->get_field( $page_options, $pt_bg_prefix.'background_repeat', $defaults ),
																		$params['background-repeat'],
																		array( 'class' => 'slz-w200' ) ) );?>
								<?php echo ( $this->drop_down_list( 'slzexploore_page_options['.$pt_bg_prefix.'background_size]',
																		$this->get_field( $page_options, $pt_bg_prefix.'background_size', $defaults ),
																		$params['background-size'],
																		array( 'class' => 'slz-w200' ) ) );?>
								
							</div>
							<br/>
							<div>
								<?php echo ( $this->drop_down_list( 'slzexploore_page_options['.$pt_bg_prefix.'background_attachment]',
																		$this->get_field( $page_options, $pt_bg_prefix.'background_attachment', $defaults ),
																		$params['background-attachment'],
																		array( 'class' => 'slz-w200' ) ) );?>
								<?php echo ( $this->drop_down_list( 'slzexploore_page_options['.$pt_bg_prefix.'background_position]',
																		$this->get_field( $page_options, $pt_bg_prefix.'background_position', $defaults ),
																		$params['background-position'],
																		array( 'class' => 'slz-w200' ) ) ); ?>
							</div>
							<br/>
							<?php if( $pt_bg_image_show ) :?>
							<div class="bg-image">
								<?php echo ( $this->text_field( 'slzexploore_page_options[pt_background_image]',
																	esc_attr( $params['pt_bg_image']['url'] ),
																	array( 'id' => 'slz_pt_bg_image_name', 'readonly'=>'readonly', 'class' => 'slz-block' ) ) );?>
								<input type="hidden" name="slzexploore_page_options[pt_background_image_id]" id="slz_pt_bg_image_id" value="<?php echo esc_attr( $params['pt_bg_image']['id'] ); ?>" />
								<div class="screenshot <?php echo esc_attr( $params['pt_bg_image']['class'] );?>" >
									<img src="<?php echo esc_attr( $params['pt_bg_image']['url'] ); ?>" />
								</div>
								<br/>
								<input type="button" data-rel="slz_pt_bg_image" class="button slz-btn-upload" value="<?php esc_html_e( 'Upload Image', 'exploore' )?>" />
								<input type="button" data-rel="slz_pt_bg_image" class="button slz-btn-remove <?php echo esc_attr( $params['pt_bg_image']['class'] );?>" value="<?php esc_html_e( 'Remove', 'exploore' )?>" />
							</div>
							<?php endif;?>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<span class="f-right"><?php $this->tooltip_html( esc_html__( 'Insert page title height (px)', 'exploore' ) );?></span>
							<label><?php esc_html_e( 'Height', 'exploore' );?></label>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_page_options[pt_height]',
																$this->get_field( $page_options, 'pt_height', $defaults ),
																array( 'class' => '' ) ) );?>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Show Title', 'exploore' );?></label>
							<span class="f-right"><?php $this->tooltip_html( esc_html__( 'Show/Hide title in page title', 'exploore' ) );?></span>
						</th>
						<td>
							<label><?php echo ( $this->check_box( 'slzexploore_page_options[title_show]',
																	$this->get_field( $page_options, 'title_show', 1 ),
																	array( 'class' => '' ) ) );
									esc_html_e( 'Show', 'exploore' )?></label>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Custom Title', 'exploore' );?></label>
							<span class="f-right"><?php $this->tooltip_html( esc_html__( 'Enter custom title to display in page title.', 'exploore' ) );?></span>
							<p class="description" ></p>
						</th>
						<td>
							<?php echo ( $this->text_field( 'slzexploore_page_options[title_custom_content]',
																$this->get_field( $page_options, 'title_custom_content' ),
																array('class' => 'slz-block') ) );?>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Show Breadcrumb', 'exploore' );?></label>
							<span class="f-right"><?php $this->tooltip_html( esc_html__( 'Show/Hide breadcrumb', 'exploore' ) );?></span>
						</th>
						<td>
							<label><?php echo ( $this->check_box( 'slzexploore_page_options[breadcrumb_show]',
																	$this->get_field( $page_options, 'breadcrumb_show', 1 ),
																	array( 'class' => '' ) ) );
									esc_html_e( 'Show', 'exploore' )?></label>
						</td>
					</tr>
				</table>
			</div>
			<!-- Sidebar -->
			<div id="slz-tab-page-sidebar" class="tab-content">
				<table class="form-table">
					<tr>
						<th scope="row">
							<label><?php echo ( $this->check_box( 'slzexploore_page_options[sidebar_default]',
																	$this->get_field( $page_options, 'sidebar_default', 1 ),
																	array( 'class' => 'slz-sidebar-option' ) ) );
									esc_html_e( 'Default Setting', 'exploore' )?></label>
							<span class="f-right"><?php $this->tooltip_html(esc_html__( 'Using setting of theme options. All below setting will NOT be allowed. Uncheck to change setting this page.', 'exploore' ) );?></span>
						</th>
						<td></td>
					</tr>
				</table>
				<table id="div_slz_sidebar_option" class="form-table <?php echo ($this->get_field( $page_options, 'sidebar_default', 1 )? 'hide' : '');?>">
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Sidebar Layout', 'exploore' );?></label>
							<span class="f-right"><?php $this->tooltip_html( esc_html__( 'Choose locate to display sidebar in the page.', 'exploore' ) );?></span>
						</th>
						<td>
							<?php echo ( $this->drop_down_list( 'slzexploore_page_options['.$sidebar_layout.']',
																	$this->get_field( $page_options, $sidebar_layout, $defaults ),
																	$params['sidebar_layout'],
																	array( 'class' => 'slz-w200' ) ) );?>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Sidebar Name', 'exploore' );?></label>
							<span class="f-right"><?php $this->tooltip_html( esc_html__( 'Choose sidebar to display in the page. To add new sidebar, please go to ', 'exploore' ) .'<a href="'.esc_url( admin_url( 'widgets.php' ) ).'" >Appearance>Widgets</a>' );?></span>
						</th>
						<td>
							<?php echo ( $this->drop_down_list( 'slzexploore_page_options['.$sidebar_layout_id.']',
																	$this->get_field( $page_options, $sidebar_layout_id, $defaults ),
																	$params['regist_sidebars'],
																	array( 'class' => 'slz-w200', 'prompt' => 'Default sidebar') ) );?>
						</td>
					</tr>
				</table>
			</div>
			<!-- Footer -->
			<div id="slz-tab-page-footer" class="tab-content">
				<table class="form-table">
					<tr>
						<th scope="row">
							
							<label><?php echo ( $this->check_box( 'slzexploore_page_options[footer_default]',
																	$this->get_field( $page_options, 'footer_default', 1 ),
																	array( 'class' => 'slz-footer-option' ) ) );
									esc_html_e( 'Default Setting', 'exploore' )?></label>
							<span class="f-right"><?php $this->tooltip_html(esc_html__( 'Using setting of theme options. All below setting will NOT be allowed. Uncheck to change setting this page.', 'exploore' ) );?></span>
						</th>
						<td></td>
					</tr>
				</table>
				<table id="div_slz_footer_option" class="form-table <?php echo ($this->get_field( $page_options, 'footer_default', 1 )? 'hide' : '');?>">
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Footer Section', 'exploore' );?></label>
							<span class="f-right"><?php $this->tooltip_html( esc_html__( 'Show/Hide footer', 'exploore' ) );?></span>
						</th>
						<td>
							<label><?php echo ( $this->check_box( 'slzexploore_page_options[footer_show]',
																	$this->get_field( $page_options, 'footer_show', $defaults ),
																	array( 'class' => '' ) ) );
									esc_html_e( 'Show', 'exploore' )?></label>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Footer Style', 'exploore' );?></label>
							<span class="f-right"><?php $this->tooltip_html( esc_html__( 'Select style to footer in the page.', 'exploore' ) );?></span>
						</th>
						<td>
							<?php echo ( $this->drop_down_list( 'slzexploore_page_options[footer_style]',
																	$footer_style,
																	$params['footer_style'],
																	array( 'class' => 'slz-w190') ) );?>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Show NewsLetter', 'exploore' );?></label>
							<span class="f-right"><?php $this->tooltip_html( esc_html__( 'Show/Hide newsletter on footer', 'exploore' ) );?></span>
						</th>
						<td>
							<label><?php echo ( $this->check_box( 'slzexploore_page_options[show_newsletter]',
																	$this->get_field( $page_options, 'show_newsletter', 1 ),
																	array( 'class' => '' ) ) );
									esc_html_e( 'Show', 'exploore' )?></label>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Footer Bottom', 'exploore' );?></label>
							<span class="f-right"><?php $this->tooltip_html( esc_html__( 'Show/Hide footer bottom', 'exploore' ) );?></span>
						</th>
						<td>
							<label><?php echo ( $this->check_box( 'slzexploore_page_options[footer_bottom_show]',
																	$this->get_field( $page_options, 'footer_bottom_show', 1 ),
																	array( 'class' => '' ) ) );
									esc_html_e( 'Show', 'exploore' )?></label>
						</td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</div>