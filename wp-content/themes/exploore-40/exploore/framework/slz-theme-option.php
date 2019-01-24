<?php
/**
 * Dynamic css from theme options - Output will be included into end of head tag
 *
 * @author Swlabs
 * @package Exploore
 * @since 1.0
 */
function slzexploore_dynamic_css() {

	// page options
	do_action('slzexploore_page_options');

	$content = "";
	$content_desktop = "";

	$content_ptop   	= Slzexploore::get_option('slz-content-padding-top');
	$content_pbottom   	= Slzexploore::get_option('slz-content-padding-bottom');
	$content_pbottom = str_replace('px', '', $content_pbottom);
	$content_ptop = str_replace('px', '', $content_ptop);
	if( is_numeric( $content_ptop ) ) {
		$content_ptop = 'padding-top:'.$content_ptop.'px;';
	} else {
		$content_ptop = '';
	}
	if( is_numeric( $content_pbottom ) ) {
		$content_pbottom = 'padding-bottom:'.$content_pbottom.'px;';
	} else {
		$content_pbottom = '';
	}

	$content .= '#wrapper-content .section.page-detail{'.$content_ptop.$content_pbottom.'}';
	/*page option*/
	$header_caption_fontsize   	= Slzexploore::get_option('slz-header-caption-fontsize-1');
	$header_caption_fontsize_2  = Slzexploore::get_option('slz-header-caption-fontsize-2');
	$header_caption_fontsize = str_replace('px', '', $header_caption_fontsize);
	$header_caption_fontsize_2 = str_replace('px', '', $header_caption_fontsize_2);
	$content .= '.homepage-banner-content .group-title .banner{font-size:'.$header_caption_fontsize.'px;}';
	$content .= '.homepage-banner-content .group-title .sub-banner{font-size:'.$header_caption_fontsize_2.'px;}';
	/* Layout setting */
	$boxed_layout 		= Slzexploore::get_option('slz-layout');
	$boxed_bg     		= Slzexploore::get_option('slz-layout-boxed-bg');
	
	$bg_image = '';
	if( !empty( $boxed_bg['background-image'] ) ) {
		$bg_image = 'background-image: url("' .$boxed_bg['background-image']. '");';
	}
	if ( !empty($boxed_bg) ) {
		$content .= 'body {background-color: ' .$boxed_bg['background-color']. ';'. $bg_image .'background-repeat: ' .$boxed_bg['background-repeat']. ';background-attachment: ' .$boxed_bg['background-attachment']. ';background-position:'.$boxed_bg['background-position'].';background-size:'.$boxed_bg['background-size'].';}';
	}

	/* Page Title */
	$page_title_bg			= Slzexploore::get_option('slz-page-title-bg');
	$page_title_overlay_bg 	= Slzexploore::get_option('slz-pagetitle-overlay-bg');
	$page_title_h			= Slzexploore::get_option('slz-page-title-height');
	$page_title_align		= Slzexploore::get_option('slz-pagetitle-align');
	$bc_typo        		= Slzexploore::get_option('slz-breadcrumb-path');
	$bc_typo2        		= Slzexploore::get_option('slz-breadcrumb-path2');
	$title_typo     		= Slzexploore::get_option('slz-pagetitle-title');
	$border_color     		= Slzexploore::get_option('slz-breadcrumb-border-color');
		
	if ( Slzexploore::get_option('slz-page-title-show') == '1' ) {
		$bg_image = '';
		if( $page_title_bg ) {
			if( $page_title_bg['background-image'] ) {
				$bg_image = 'background-image: url("' .$page_title_bg['background-image']. '");';
			}
			$content .= '.page-title{'.$bg_image.'}';
	
			$content_desktop .= '.page-title{background-color: ' .$page_title_bg['background-color']. ';' . $bg_image . 'background-repeat: ' .$page_title_bg['background-repeat']. ';background-attachment: ' .$page_title_bg['background-attachment']. ';background-position:'.$page_title_bg['background-position'].';background-size:'.$page_title_bg['background-size'].';text-align:'.$page_title_align.';}';
		}
		if ( !empty($page_title_h['height']) ){
			$content_desktop .= '.page-title{height:'.$page_title_h['height'].';}';
		}
		
		if( !empty( $page_title_overlay_bg['rgba'] ) ) {
			$content .= '.page-title:before{content:"";position: absolute;width: 100%;height: 100%;left: 0;top: 0;background-color:'.$page_title_overlay_bg['rgba'].'}';
		}
		
		if( $bc_typo ) {
			$content .= '.page-title .page-title-wrapper .breadcrumb > li .link.home{color:'.$bc_typo['color'].';font-weight:'.$bc_typo['font-weight'].';text-transform:'.$bc_typo['text-transform'].';}';
			$content_desktop .= '.page-title .page-title-wrapper .breadcrumb > li .link.home{font-size:'.$bc_typo['font-size'].';}';
			$content .= '.page-title .page-title-wrapper .breadcrumb > li .link{color:'.$bc_typo['color'].';}';
		}

		if( $bc_typo2 ) {
			$content .= '.page-title .page-title-wrapper .breadcrumb > li .link{font-weight:'.$bc_typo2['font-weight'].';text-transform:'.$bc_typo2['text-transform'].';}';
			$content_desktop .= '.page-title .page-title-wrapper .breadcrumb > li .link{font-size:'.$bc_typo2['font-size'].';}';
		}

		if( $bc_typo ) {
			$content .= '.page-title .page-title-wrapper .breadcrumb > li + li:before,.page-title .page-title-wrapper li.active .link:after{color:'.$bc_typo['color'].';}';
		}

		if(!empty($border_color)){
			$content .= '.page-title-wrapper .breadcrumb li.active .link:after{background-color:'.$border_color.';}';
			$content .= '.page-title .page-title-wrapper .breadcrumb{border-bottom:1px solid '.$border_color.';}';
			$content .= '.page-title-wrapper .breadcrumb li.active .link:after{background-color:'.$border_color.';}';
		}

		$content .= '.page-title .page-title-wrapper .breadcrumb > li a{opacity: 0.8}';

		if( $bc_typo ) {
			$content_desktop .= '.page-title .page-title-wrapper .breadcrumb > li,.page-title .page-title-wrapper .breadcrumb > li a,.page-title .page-title-wrapper .breadcrumb > li.active{font-size:'.$bc_typo['font-size'].';}';
		}

		if( $title_typo ) {
			$content .= '.page-title .captions{color:'.$title_typo['color'].';font-weight:'.$title_typo['font-weight'].';text-transform:'.$title_typo['text-transform'].';}';
			$content_desktop .= '.page-title .captions{font-size:'.$title_typo['font-size'].';}';
		}
	}
	if ( Slzexploore::get_option('slz-show-breadcrumb') == "0") {
		$content .= '.page-title .page-title-wrapper .breadcrumb{display:none}';
	}
	if ( Slzexploore::get_option('slz-show-title') == "0") {
		if (is_page()){
			$content .= '.page-title .page-title-wrapper .captions{visibility: hidden;}';
		}
	}

	/* Menu */
	$menu_text 				= Slzexploore::get_option('slz-menu-item-text');
	$menu_height     		= Slzexploore::get_option('slz-menu-height');
	$submenu_bg       		= Slzexploore::get_option('slz-submenu-bg');
	$submenu_width			= Slzexploore::get_option('slz-submenu-width');
	$submenu_border   		= Slzexploore::get_option('slz-submenu-border');
	$submenu_color    		= Slzexploore::get_option('slz-submenu-color');
	$submenu_padding  		= Slzexploore::get_option('slz-submenu-padding');
	$submenu_active_color   = Slzexploore::get_option('slz-submenu-active-color');
	

	if ( Slzexploore::get_option('slz-menu-custom') == '1' ) {
		if( $menu_text ) {
			$content .= '.header-main .navigation .nav-links .main-menu {color:'.$menu_text['regular'].';}';
			$content .= 'header .bg-transparent .header-main .navigation .nav-links li.active .main-menu, header .bg-white .header-main .navigation .nav-links li:hover .main-menu {color:'.$menu_text['hover'].'}';
			$content .= '.header-main .navigation .nav-links li.active .main-menu{color:'.$menu_text['active'].';border-color:'.$menu_text['active'].'}';
			$content .= '.header-main .navigation .nav-links .main-menu:after,.header-main .navigation .nav-links .main-menu:before{background-color:'.$menu_text['active'].';}';
			
			$content .= '.header-main .navigation .nav-links li:hover .main-menu .icons-dropdown i{color:'.$menu_text['hover'].';}';
			$content .= '.header-main .navigation .nav-links li:hover .main-menu:after,.header-main .navigation .nav-links li:hover .main-menu:before{background-color:'.$menu_text['hover'].';}';
		}
		if( $menu_height ) {
			$content .= '.header-main .navigation .nav-links .main-menu {line-height:'.$menu_height['height'] .';}';
			$content .= '.header-main .logo{line-height:'.$menu_height['height'] .';}';
		}
	}

	if ( Slzexploore::get_option('slz-submenu-custom') == '1') {
		if( $submenu_bg ){
			$content .= 'header .header-main .exploore-dropdown-menu-1, header .header-main .exploore-dropdown-menu-2{background-color:'.$submenu_bg['rgba'].';}';
		}
		if( $submenu_width ){
			$content .= 'header .header-main .exploore-dropdown-menu-1, header .header-main .exploore-dropdown-menu-2{width:'.$submenu_width['width'].'}';
		}
		if( $submenu_border ) {
			$content .= 'header .header-main .exploore-dropdown-menu-1, header .header-main .exploore-dropdown-menu-2{border-bottom:'.$submenu_border['border-bottom'].' '.$submenu_border['border-style'].' '.$submenu_border['border-color'].'}';
		}

		if( !empty( $submenu_color['regular'] ) ) {
			$content .= 'header .header-main .exploore-dropdown-menu-1 li .link-page, header .header-main .exploore-dropdown-menu-2 li .link-page{color:'.$submenu_color['regular'].';}';
		}
		
		if( $submenu_padding ) {
			$content .= 'header .header-main .exploore-dropdown-menu-1 li .link-page, header .header-main .exploore-dropdown-menu-2 li .link-page{'
												.'padding-right:'.$submenu_padding['padding-right'].';padding-left:'.$submenu_padding['padding-left'].';'
												.'padding-top:'.$submenu_padding['padding-top'].';padding-bottom:'.$submenu_padding['padding-bottom'].';}';
		}

		if( !empty( $submenu_color['hover'] ) ) {
			$content .= 'header .header-main .exploore-dropdown-menu-1 li:hover .link-page, header .header-main .exploore-dropdown-menu-2 li:hover .link-page{color:'.$submenu_color['hover'].'}';
		}

		$content .= 'header .header-main .exploore-dropdown-menu-1 li:hover, header .header-main .exploore-dropdown-menu-2 li:hover{background-color: rgba(0, 0, 0, 0.04)}';

		if( !empty($submenu_color['active'])) {
			$content .= 'header .header-main .exploore-dropdown-menu-1 li.active .link-page,header .header-main .exploore-dropdown-menu-2 li.active .link-page{color: '.$submenu_color['active'].';}';
		}

		if( !empty( $submenu_active_color['rgba'] )) {
			$content .= 'header .header-main .dropdown-menu>.active>a, .dropdown-menu>.active>a:focus, .dropdown-menu>.active>a:hover{background-color: '.$submenu_active_color['rgba'].';}';
		}
	}

	/* Sidebar */
	$sidebar_box_mb = Slzexploore::get_option('slz-sidebar-mb');
	$sidebar_box_pb = Slzexploore::get_option('slz-sidebar-pb');

	if ( !empty( $sidebar_box_mb['margin-bottom'] ) ) {
		$content .= '#page-sidebar .widget{margin-bottom:'.$sidebar_box_mb['margin-bottom'].'}';
	}
	if ( !empty( $sidebar_box_pb['padding-bottom'] ) ) {
		$content .= '#page-sidebar .widget{padding-bottom:'.$sidebar_box_pb['padding-bottom'].'}';
	}
	/* Footer */
	$footer_style  			= Slzexploore::get_option('slz-footer-style');
	$footer_bg      		= Slzexploore::get_option('slz-footer-bg');
	$footer_mask    		= Slzexploore::get_option('slz-footer-mask-bg');
	$footer_light_bg      	= Slzexploore::get_option('slz-footer-light-bg');
	$footer_light_mask    	= Slzexploore::get_option('slz-footer-light-mask-bg');
	$footer_light_pd      	= Slzexploore::get_option('slz-footer-light-padding');

	if ( $footer_style == 'dark' ) {
		$footer_bg_image = '';
		if( !empty( $footer_bg['background-image'] ) ) {
			$footer_bg_image = 'background-image: url("' .$footer_bg['background-image']. '");';
		}
		if ( !empty( $footer_bg['background-color'] ) || !empty( $footer_bg['background-image'] )) {
			$content .= '.footer-main-container {background-color: ' .$footer_bg['background-color']. ';' . $footer_bg_image . 'background-repeat: ' .$footer_bg['background-repeat']. ';background-attachment: ' .$footer_bg['background-attachment']. ';background-position:'.$footer_bg['background-position'].';background-size:'.$footer_bg['background-size'].';}';
			if ( !empty( $footer_mask['rgba'] ) ) {
				$content .= '.footer-main {background-color:'.$footer_mask['rgba'].';}';
			} else {
				$content .= '.footer-main {background-color:transparent;';
			}
		}
	} else if ( $footer_style == 'light' ) {
		$footer_light_bg_image = '';
		if( !empty( $footer_light_bg['background-image'] ) ) {
			$footer_light_bg_image = 'background-image: url("' .$footer_light_bg['background-image']. '");';
		}
		if ( !empty( $footer_light_bg['background-color']) || !empty( $footer_light_bg['background-image'] )) {
			$content .= 'footer .bg-transparent {background-color: ' .$footer_light_bg['background-color']. ';' . $footer_light_bg_image . 'background-repeat: ' .$footer_light_bg['background-repeat']. ';background-attachment: ' .$footer_light_bg['background-attachment']. ';background-position:'.$footer_light_bg['background-position'].';background-size:'.$footer_light_bg['background-size'].';}';
			if ( !empty( $footer_light_mask['rgba'] ) ) {
				$content .= 'footer .bg-transparent .footer-main {background-color:'.$footer_light_mask['rgba'].';}';
			} else {
				$content .= 'footer .bg-transparent .footer-main {background-color:transparent;}';
			}
		}
		if ( $footer_light_pd ) {
			$content_desktop .= '.footer-main .footer-main-wrapper{padding-top:'.$footer_light_pd['padding-top'].';padding-bottom:'.$footer_light_pd['padding-bottom'].';}';
		}
	}
	/*shop setting*/
	$post_type = get_post_type();
	if ($post_type == 'product'){
		$page_title_product = Slzexploore::get_option('slz-shop-page-title-bg');
		if( $page_title_product ) {
			$bg_image_shop = '';
			if( !empty( $page_title_product['background-image'] ) ) {
				$bg_image_shop = 'background-image: url("' .$page_title_product['background-image']. '");';
			}
			$content .= '.page-title{background-color: ' .$page_title_product['background-color']. ';' . $bg_image_shop . 'background-repeat: ' .$page_title_product['background-repeat']. ';background-attachment: ' .$page_title_product['background-attachment']. ';background-position:'.$page_title_product['background-position'].';background-size:'.$page_title_product['background-size'].';}';
		}
	}
	/* 404 Setting */
	if (is_404()){
		$content .= '.page-title.page-banner-2{display:none;}';
	}
	$page_404_bg  = Slzexploore::get_option('slz-404-bg');

	$bg_image = '';
	if( $page_404_bg ) {
		if( $page_404_bg['background-image'] ) {
			$bg_image = 'background-image: url("' .$page_404_bg['background-image']. '");';
		}
		$content .= '.page-404{background-color: ' .$page_404_bg['background-color']. ';' . $bg_image .'background-repeat: ' .$page_404_bg['background-repeat']. ';background-attachment: ' .$page_404_bg['background-attachment']. ';background-position: ' .$page_404_bg['background-position']. ';background-size:'.$page_404_bg['background-size'].';}';
	}

	/* Blog Display */
	$bloginfo       = Slzexploore::get_option('slz-bloginfo', 'disabled');
	$commentbox     = Slzexploore::get_option('slz-commentbox');
	$taginfo        = Slzexploore::get_option('slz-blog-tag');
	$catinfo        = Slzexploore::get_option('slz-blog-cat');
	if (Slzexploore::get_option('slz-blog-show-title') == "0") {
		if (is_singular('post')){
			$content .= '.page-banner .page-title-wrapper .captions{visibility: hidden;}';
		}
	}
	
	if ( $bloginfo ) {
		foreach ($bloginfo as $key => $value) {
			switch ( $key ) {
				case 'author':
					$content .= '.blog-post .meta-info .author{display:none;}';
					break;

				case 'view':
					$content .= '.blog-post .meta-info .view-count{display:none;}';
					break;

				case 'comment':
					$content .= '.blog-post .meta-info .comment-count{display:none;}';
					break;

				case 'date':
					$content .= '.blog-post .date{display:none;}';
					break;

				default:
					# code...
					break;
			}
		}
	}
	
	if ( $commentbox == '0' ) {
		$content .= '.entry-comment{display:none;}';
	}

	if ( $taginfo == '0' ) {
		$content .= '.item-blog-detail .blog-text .cats-widget{display:none;}';
	}

	if ( $catinfo == '0' ) {
		$content .= '.item-blog-detail .blog-text .tags-widget{display:none;}';
	}

	/* Extras */

	$register_bg_url	= Slzexploore::get_option('slz-bg-registerpage', 'url');
	$login_bg_url     	= Slzexploore::get_option('slz-bg-loginpage', 'url');

	if( $register_bg_url ) {
		if( $register_bg_url ) {
			$register_bg_css = 'background-image: url("' . esc_url($register_bg_url). '");';
			$content .= '.page-register {'. $register_bg_css.'}';
		}
	}
	if( $login_bg_url ) {
		if( $login_bg_url ) {
			$login_bg_css = 'background-image: url("' . esc_url($login_bg_url). '");';
			$content .= '.page-login {'.$login_bg_css.'}';
		}
	}

	/* Typography */
	$body_typo      = Slzexploore::get_option('slz-typo-body');
	$para_typo      = Slzexploore::get_option('slz-typo-p');
	$h1_typo        = Slzexploore::get_option('slz-typo-h1');
	$h2_typo        = Slzexploore::get_option('slz-typo-h2');
	$h3_typo        = Slzexploore::get_option('slz-typo-h3');
	$h4_typo        = Slzexploore::get_option('slz-typo-h4');
	$h5_typo        = Slzexploore::get_option('slz-typo-h5');
	$h6_typo        = Slzexploore::get_option('slz-typo-h6');
	$text_selection = Slzexploore::get_option('slz-typo-selection' );
	$link_color     = Slzexploore::get_option('slz-link-color');

	$body_typo_css = '';
	if( $body_typo ) {
		if ( !empty( $body_typo['font-family']) ) {
			$body_typo_css .= 'font-family:'.$body_typo['font-family'].';';
		}
		if ( !empty( $body_typo['color'] ) ) {
			$body_typo_css .= 'color:'.$body_typo['color'].';';
		}
		if ( !empty( $body_typo['font-size'] ) ) {
			$body_typo_css .= 'font-size:'.$body_typo['font-size'].';';
		}
		if ( !empty( $body_typo['font-weight'] ) ) {
			$body_typo_css .= 'font-weight:'.$body_typo['font-weight'].';';
		}
		if ( !empty( $body_typo['font-style'] ) ) {
			$body_typo_css .= 'font-style:'.$body_typo['font-style'].';';
		}
		if ( !empty( $body_typo['text-align'] ) ) {
			$body_typo_css .= 'text-align:'.$body_typo['text-align'].';';
		}
		if ( isset( $body_typo['line-height'] )  && $body_typo['line-height'] !== '' ) {
			$body_typo_css .= 'line-height:'.$body_typo['line-height'].';';
		}
	}

	$para_typo_css = '';
	if( $para_typo ) {
		if ( !empty( $para_typo['font-family'] ) ) {
			$para_typo_css .= 'font-family:'.$para_typo['font-family'].';';
		}
		if ( !empty( $para_typo['color'] ) ) {
			$para_typo_css .= 'color:'.$para_typo['color'].';';
		}
		if ( !empty( $para_typo['font-size'] ) ) {
			$para_typo_css .= 'font-size:'.$para_typo['font-size'].';';
		}
		if ( !empty( $para_typo['font-weight'] ) ) {
			$para_typo_css .= 'font-weight:'.$para_typo['font-weight'].';';
		}
		if ( !empty( $para_typo['font-style'] ) ) {
			$para_typo_css .= 'font-style:'.$para_typo['font-style'].';';
		}
		if ( !empty( $para_typo['text-align']) ) {
			$para_typo_css .= 'text-align:'.$para_typo['text-align'].';';
		}
		if ( isset( $para_typo['line-height'] ) && $para_typo['line-height'] !== '' ) {
			$para_typo_css .= 'line-height:'.$para_typo['line-height'].';';
		}
	}

	$h1_typo_css = '';
	if( $h1_typo ) {
		if ( !empty( $h1_typo['font-family'] ) ) {
			$h1_typo_css .= 'font-family:'.$h1_typo['font-family'].';';
		}
		if ( !empty( $h1_typo['color'] ) ) {
			$h1_typo_css .= 'color:'.$h1_typo['color'].';';
		}
		if ( !empty( $h1_typo['font-size'] ) ) {
			$h1_typo_css .= 'font-size:'.$h1_typo['font-size'].';';
		}
		if ( !empty( $h1_typo['font-weight'] ) ) {
			$h1_typo_css .= 'font-weight:'.$h1_typo['font-weight'].';';
		}
		if ( !empty( $h1_typo['font-style'] ) ) {
			$h1_typo_css .= 'font-style:'.$h1_typo['font-style'].';';
		}
		if ( !empty( $h1_typo['text-align'] ) ) {
			$h1_typo_css .= 'text-align:'.$h1_typo['text-align'].';';
		}
		if ( isset( $h1_typo['line-height'] ) && $h1_typo['line-height'] !== '' ) {
			$h1_typo_css .= 'line-height:'.$h1_typo['line-height'].';';
		}
	}

	$h2_typo_css = '';
	if( $h2_typo ) {
		if ( !empty( $h2_typo['font-family'] )  ) {
			$h2_typo_css .= 'font-family:'.$h2_typo['font-family'].';';
		}
		if ( !empty( $h2_typo['color'] ) ) {
			$h2_typo_css .= 'color:'.$h2_typo['color'].';';
		}
		if ( !empty( $h2_typo['font-size'] ) ) {
			$h2_typo_css .= 'font-size:'.$h2_typo['font-size'].';';
		}
		if ( !empty( $h2_typo['font-weight'] ) ) {
			$h2_typo_css .= 'font-weight:'.$h2_typo['font-weight'].';';
		}
		if ( !empty( $h2_typo['font-style'] ) ) {
			$h2_typo_css .= 'font-style:'.$h2_typo['font-style'].';';
		}
		if ( !empty( $h2_typo['text-align'] ) ) {
			$h2_typo_css .= 'text-align:'.$h2_typo['text-align'].';';
		}
		if ( isset( $h2_typo['line-height'] ) && $h2_typo['line-height'] !== '' ) {
			$h2_typo_css .= 'line-height:'.$h2_typo['line-height'].';';
		}
	}

	$h3_typo_css = '';
	if( $h3_typo ) {
		if ( !empty( $h3_typo['font-family'] ) ) {
			$h3_typo_css .= 'font-family:'.$h3_typo['font-family'].';';
		}
		if ( !empty( $h3_typo['color'] ) ) {
			$h3_typo_css .= 'color:'.$h3_typo['color'].';';
		}
		if ( !empty( $h3_typo['font-size'] ) ) {
			$h3_typo_css .= 'font-size:'.$h3_typo['font-size'].';';
		}
		if ( !empty( $h3_typo['font-weight'] ) ) {
			$h3_typo_css .= 'font-weight:'.$h3_typo['font-weight'].';';
		}
		if ( !empty( $h3_typo['font-style'] ) ) {
			$h3_typo_css .= 'font-style:'.$h3_typo['font-style'].';';
		}
		if ( !empty( $h3_typo['text-align'] ) ) {
			$h3_typo_css .= 'text-align:'.$h3_typo['text-align'].';';
		}
		if ( isset( $h3_typo['line-height'] ) && $h3_typo['line-height'] !== '' ) {
			$h3_typo_css .= 'line-height:'.$h3_typo['line-height'].';';
		}
	}

	$h4_typo_css = '';
	if( $h4_typo ) {
		if ( !empty( $h4_typo['font-family'] ) ) {
			$h4_typo_css .= 'font-family:'.$h4_typo['font-family'].';';
		}
		if ( !empty( $h4_typo['color'] ) ) {
			$h4_typo_css .= 'color:'.$h4_typo['color'].';';
		}
		if ( !empty( $h4_typo['font-size'] ) ) {
			$h4_typo_css .= 'font-size:'.$h4_typo['font-size'].';';
		}
		if ( !empty( $h4_typo['font-weight'] ) ) {
			$h4_typo_css .= 'font-weight:'.$h4_typo['font-weight'].';';
		}
		if ( !empty( $h4_typo['font-style']) ) {
			$h4_typo_css .= 'font-style:'.$h4_typo['font-style'].';';
		}
		if ( !empty( $h4_typo['text-align'] ) ) {
			$h4_typo_css .= 'text-align:'.$h4_typo['text-align'].';';
		}
		if ( isset( $h4_typo['line-height'] ) && $h4_typo['line-height'] !== '' ) {
			$h4_typo_css .= 'line-height:'.$h4_typo['line-height'].';';
		}
	}

	$h5_typo_css = '';
	if( $h5_typo ) {
		if ( !empty( $h5_typo['font-family'] ) ) {
			$h5_typo_css .= 'font-family:'.$h5_typo['font-family'].';';
		}
		if ( !empty( $h5_typo['color'] )  ) {
			$h5_typo_css .= 'color:'.$h5_typo['color'].';';
		}
		if ( !empty( $h5_typo['font-size'] ) ) {
			$h5_typo_css .= 'font-size:'.$h5_typo['font-size'].';';
		}
		if ( !empty( $h5_typo['font-weight']) ) {
			$h5_typo_css .= 'font-weight:'.$h5_typo['font-weight'].';';
		}
		if ( !empty( $h5_typo['font-style'] ) ) {
			$h5_typo_css .= 'font-style:'.$h5_typo['font-style'].';';
		}
		if ( !empty( $h5_typo['text-align'] ) ) {
			$h5_typo_css .= 'text-align:'.$h5_typo['text-align'].';';
		}
		if ( isset( $h5_typo['line-height'] ) && $h5_typo['line-height'] !== ''  ) {
			$h5_typo_css .= 'line-height:'.$h5_typo['line-height'].';';
		}
	}

	$h6_typo_css = '';
	if( $h6_typo ) {
		if ( !empty( $h6_typo['font-family'] ) ) {
			$h6_typo_css .= 'font-family:'.$h6_typo['font-family'].';';
		}
		if ( !empty( $h6_typo['color'] ) ) {
			$h6_typo_css .= 'color:'.$h6_typo['color'].';';
		}
		if ( !empty( $h6_typo['font-size'] ) ) {
			$h6_typo_css .= 'font-size:'.$h6_typo['font-size'].';';
		}
		if ( !empty( $h6_typo['font-weight'] ) ) {
			$h6_typo_css .= 'font-weight:'.$h6_typo['font-weight'].';';
		}
		if ( !empty( $h6_typo['font-style'] ) ) {
			$h6_typo_css .= 'font-style:'.$h6_typo['font-style'].';';
		}
		if ( !empty( $h6_typo['text-align'] ) ) {
			$h6_typo_css .= 'text-align:'.$h6_typo['text-align'].';';
		}
		if ( isset( $h6_typo['line-height'] ) && $h6_typo['line-height'] !== '' )  {
			$h6_typo_css .= 'line-height:'.$h6_typo['line-height'].';';
		}
	}

	if( $body_typo_css ) {
		$content .= 'body{'.$body_typo_css.'}';
	}
	if( $para_typo_css) {
		$content .= 'p{'.$para_typo_css.'}';
	}
	if( $h1_typo_css) {
		$content .= 'h1{'.$h1_typo_css.'}';
	}
	if( $h2_typo_css ) {
		$content .= 'h2{'.$h2_typo_css.'}';
	}
	if( $h3_typo_css ) {
		$content .= 'h3{'.$h3_typo_css.'}';
	}
	if( $h4_typo_css ) {
		$content .= 'h4{'.$h4_typo_css.'}';
	}
	if( $h5_typo_css ) {
		$content .= 'h5{'.$h5_typo_css.'}';
	}
	if( $h6_typo_css ) {
		$content .= 'h6{'.$h6_typo_css.'}';
	}

	if ( $link_color ) {
		$content .= 'a{color:'.$link_color['regular'].'}';
		$content .= 'a:hover{color:'.$link_color['hover'].'}';
		$content .= 'a:active{color:'.$link_color['active'].'}';
	}
	/* End of dynamic CSS */
	echo "<!-- Start Dynamic Styling -->\n<style type=\"text/css\">\n@media screen {" . $content . "}</style> <!-- End Dynamic Styling -->\n";
	echo "<!-- Start Dynamic Styling only for desktop -->\n<style type=\"text/css\">\n@media screen and (min-width: 767px) {" . $content_desktop . "}</style> <!-- End Dynamic Styling only for desktop -->\n";
	/* Custom CSS */
	$custom_css = Slzexploore::get_option('slz-custom-css');

	if ($custom_css != '') {
		echo "<!-- Custom Styling -->\n<style type=\"text/css\">\n" . esc_html( $custom_css ) . "</style>\n";
	}

	/* Custom JS */
	$custom_js = Slzexploore::get_option('slz-custom-js');

	if ($custom_js != '') {
		echo "<!-- Custom JS -->\n<script type=\"text/javascript\">\n" . $custom_js . "</script>\n";
	}
}

add_action('wp_head', 'slzexploore_dynamic_css');

/*
 * Extras Options Not use CSS
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array (Maybe) filtered body classes.
 */
if ( get_option('slzexploore_options') ) {
	function slzexploore_extras_body_class( $classes ) {
		//stick header
		if ( Slzexploore::get_option('slz-sticky-enable') == '1') {
			$classes[] = ' sticky-enable';
		}
		//search bar
		if ( Slzexploore::get_option('slz-header-search-type') == '2') {
			$classes[] = ' searchbar-type-2';
		}
		return $classes;
	}
	add_filter( 'body_class', 'slzexploore_extras_body_class' );
}

/* Custom Styles to WordPress Visual Editor */
function slzexploore_mce_buttons_2($buttons) {
	array_unshift($buttons, 'styleselect');
	return $buttons;
}
add_filter('mce_buttons_2', 'slzexploore_mce_buttons_2');

// Callback function to filter the MCE settings
function slzexploore_mce_before_init_insert_formats( $init_array ) {
	$init_array['style_formats'] = json_encode( Slzexploore::get_params('style_formats') );
	return $init_array;
}
// Attach callback to 'tiny_mce_before_init'
add_filter( 'tiny_mce_before_init', 'slzexploore_mce_before_init_insert_formats' );

/* add editor style */
function slzexploore_add_editor_styles() {
	add_editor_style( get_template_directory_uri() . '/assets/public/css/slzexploore-custom-editor.css' );
	add_editor_style( get_template_directory_uri() . '/assets/public/libs/bootstrap/css/bootstrap.min.css' );
	add_editor_style( get_template_directory_uri() . '/assets/public/font/font-icon/font-awesome/css/font-awesome.min.css' );
}
add_action( 'init', 'slzexploore_add_editor_styles' );

/* Custom comment_reply_link */
function slzexploore_comment_reply($link, $args, $comment) {
	$reply_link_text = $args['reply_text'];
	$link = str_replace($reply_link_text, '<i class="fa fa-reply"></i>' . esc_html__('Reply', 'exploore'), $link);
	$link = str_replace("class='comment-reply-link", "class='btn-crystal btn", $link);
	return $link;
}
add_filter('comment_reply_link', 'slzexploore_comment_reply', 10, 3);

// change default avatar
add_filter( 'get_avatar' , 'slzexploore_custom_avatar' , get_current_user_id(), 5 );
function slzexploore_custom_avatar( $avatar, $user_id, $size, $default, $alt ) {
	$avatar_url = '';
	$avatar_id = get_user_meta($user_id, 'profile_image_id', true);
	if( $avatar_id ) {
		$avatar_url = wp_get_attachment_url( $avatar_id );
	}
	else {
		$avatar_url = get_avatar_url( $user_id );
	}
	$avatar = "<img alt='{}' src='{$avatar_url}' class='avatar avatar-{$size} photo' height='{$size}' width='{$size}'/>";
	return $avatar;
}