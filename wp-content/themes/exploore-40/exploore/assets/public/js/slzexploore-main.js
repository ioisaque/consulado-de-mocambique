jQuery(function($){
	"use strict";

	var slzexploore_fn = window.slzexploore_fn || {};

	/*=======================================
	=			 MAIN FUNCTION			 =
	=======================================*/

	slzexploore_fn.mainFunction = function(){

		var width_boder = $('.homepage-default .title').width() - $('.homepage-default .sub-banner').width() - 10;
		$('.homepage-default .sub-banner .boder').width(width_boder);

		//set page title class
		var header = $('body').attr('data-class');
		if( header == 'header-two'){
			$('body').find('.page-title').addClass('page-banner-2');
		}else{
			 $('body').find('.page-title').addClass('page-banner');
		}
		
		//JS for background home 4
		if ($(window).width() > 767) {
			$(".btn-play").on('click', function(event) {
				$(".bg-image").addClass('show-video');
				$(".btn-pause").removeClass('show-video');
				$(".btn-play").addClass('show-video');
			});

			$(".btn-pause").on('click', function(event) {
				$(".bg-image").removeClass('show-video');
				$(".btn-pause").addClass('show-video');
				$(".btn-play").removeClass('show-video');
			});
		}

		// ----------------------- SELECTBOX --------- //
	
		if ($('.selectbox').length > 0){
			$('.selectbox').selectbox();
		}
		// Select box stacking order
		if ($('.result-filter-wrapper').length > 0){
			$('.result-filter-wrapper .select-wrapper').each(function(i) {
				$(this).css('z-index', 100 - i);
			});
		}
		
		// ----------------------- STICKY SIDEBAR --------- //
		if($('.sidebar-widget').length > 0 && window.innerWidth > 1024) {
			setInterval(function() {
				/*check height of sidebar and their parent*/
				$('.sidebar-widget .sidebar-wrapper').each(function(index, el) {
					var sidebar = $(this);
					var height_sidebar,
						height_content_wrapper,
						empty_spacing;
	
					height_content_wrapper = sidebar.parent().prev().height();
					height_sidebar = sidebar.height();
					empty_spacing = height_content_wrapper-height_sidebar;
					if(empty_spacing <= 0) {
						sidebar.css('position', 'static');
					}
				});
			},200);
			
			$('.sidebar-widget .sidebar-wrapper').each(function(index, el) {
				var sidebar = $(this);
				
				var width_sidebar,
					height_sidebar,
					height_content_wrapper,
					empty_spacing;
	
				var lastScrollTop = 0;
	
				setTimeout(function() {
					width_sidebar = sidebar.parent().width();
				},200);
	
				$(window).scroll(function(){
					var currentScrollTop = $(this).scrollTop();
					
					/*height of menu sticky*/
					var height_menu_fixed = $('.sticky-header').height();
	
					/*offsetTop of main wrapper*/
					// var offsetTop_main = sidebar.parents('.row').offset().top;
					var offsetTop_main = sidebar.parent().prev().offset().top;
	
					height_content_wrapper = sidebar.parent().prev().height();
	
					height_sidebar = sidebar.height();
	
					width_sidebar = sidebar.parent().width();
	
					/*offsetTop of sidebar wrapper and sidebar*/
					var offsetTop_sidebar = 0;
	
					if( currentScrollTop  <= height_content_wrapper + offsetTop_main) {
						offsetTop_sidebar = sidebar.offset().top - sidebar.parent().offset().top;
	
						/*update empty spacing when height of sidebar is changed*/
						empty_spacing = height_content_wrapper-height_sidebar;
	
						if(empty_spacing > 0) {
							if (currentScrollTop > lastScrollTop){
								// Scroll Down
								/*make sidebar position change from fixed to absolute when scroll down*/
								if(sidebar.offset().top - $(window).scrollTop() == height_menu_fixed + 20) {
									sidebar.css({
										'position': 'absolute',
										'top': offsetTop_sidebar+'px',
										'bottom': 'auto'
									});
								}
	
								if(height_sidebar >= window.innerHeight) {
									/*keep sidebar fixed when scroll down to the bottom of sidebar*/
									if(currentScrollTop >= (height_sidebar + sidebar.offset().top - window.innerHeight) ) {
										sidebar.css({
											'width': width_sidebar+'px',
											'position': 'fixed',
											'top': 'auto',
											'bottom': 0
										});
	
										$(window).on('load', function() {
											if(sidebar.offset().top+sidebar.height() > height_content_wrapper) {
												empty_spacing = height_content_wrapper-height_sidebar;
												sidebar.css({
													'position': 'absolute',
													'top': empty_spacing+'px',
													'bottom': 'auto'
												});
											}
										});
									}   
								}
								else {
									/*keep sidebar fixed when scroll down to the top of sidebar*/
									if(currentScrollTop >= (offsetTop_main - height_menu_fixed - 20) ) {
										sidebar.css({
											'width': width_sidebar+'px',
											'position': 'fixed',
											'top': height_menu_fixed+20+'px',
											'bottom': 'auto'
										});
									}
								}
	
								/*keep sidebar at the bottom of their parent*/
								if(height_sidebar + offsetTop_sidebar >= height_content_wrapper ) {
									sidebar.css({
										'position': 'absolute',
										'top': empty_spacing+'px',
										'bottom': 'auto'
									});
								} 
							} else {
								// Scroll Up
								if(offsetTop_sidebar !== 0) {
									/*make sidebar position absolute when scroll up*/
									sidebar.css({
										'position': 'absolute',
										'top': offsetTop_sidebar+'px',
										'bottom': 'auto'
									});
	
									/*keep sidebar fixed when scroll up to the top of sidebar*/
									if( currentScrollTop <= (sidebar.offset().top - height_menu_fixed) ) {
										sidebar.css({
											'width': width_sidebar+'px',
											'position': 'fixed',
											'top': height_menu_fixed+20+'px',
											'bottom': 'auto'
										});
									}
									
									/*make sidebar original*/
									if(offsetTop_sidebar <= 0 ) {
										sidebar.css({
											'position': 'static',
											'top': 'auto',
											'bottom': 'auto'
										});
									} 
								}
							}
						}
					}
					
					lastScrollTop = currentScrollTop;
					/*keep sidebar at the bottom of their parent in case of pressing button END*/
					
					document.addEventListener("keydown", function(event) {
						height_content_wrapper = sidebar.parent().prev().height();
						height_sidebar = sidebar.height();
						empty_spacing = height_content_wrapper-height_sidebar;
						if(event.which == 35 && empty_spacing > 0) {
							sidebar.css({
								'position': 'absolute',
								'top': empty_spacing+'px',
								'bottom': 'auto'
							});
						}
					});
					if(currentScrollTop + $(window).innerHeight() == $(document).innerHeight() && empty_spacing > 0) {
						sidebar.css({
							'position': 'absolute',
							'top': empty_spacing+'px',
							'bottom': 'auto'
						});
					}
	
					if(currentScrollTop == 0) {
						sidebar.css('position', 'static');
					}
	
				});
				
			});
		}
	};
	/*=======================================
	=		   END MAIN FUNCTION		   =
	=======================================*/
	
	/*=======================================
	=			HEADER & FOOTER			=
	=======================================*/
	slzexploore_fn.header_footerFunction = function() {

		// Show dropdown language on topbar
		$('.dropdown-text').on('click', function(){
			if ($(this).parent().find(".dropdown-topbar").hasClass('hide') === false) {
				$(this).parent().find(".dropdown-topbar").addClass('hide');
				$('.dropdown-topbar').addClass('hide');
			}
			else {
				$('.dropdown-topbar').addClass('hide');
				$(this).parent().find(".dropdown-topbar").removeClass('hide');
			}
		});
		$('body').on('click', function(event){
			if ( $('.dropdown-text').has(event.target).length === 0 && !$('.dropdown-text').is(event.target)) {
				$('.dropdown-topbar').addClass('hide');
			}
		});

		// ----------------------- BACK TOP --------------------------- //
		$('#back-top .link').on('click', function () {
			$('body,html').animate({
				scrollTop: 0
			}, 900);
			return false;
		});

		var temp = $(window).height();
		$(window).on('scroll load', function (event) {
			if ($(window).scrollTop() > temp){
				$('#back-top .link').addClass('show-btn');
			}
			else {
				$('#back-top .link').removeClass('show-btn');
			}
		});

		//js for menu PC
			// js for wpml plugin
			var dropdown_lg = $('.menu-item-language').find('ul').hasClass('submenu-languages');
			if(dropdown_lg){
				$('.menu-item-language-current').addClass('dropdown').find('>a').append('<span class="icons-dropdown"><i class="fa fa-angle-down"></i></span>').addClass('main-menu');
			}
			
			// mega menu tab
			$('.shw-tab-item').each(function(){
				var parent = $(this).parents('.dropdown-menu-03');
				var tabitem = $(this);
				var item_content = tabitem.find('.tab-pane');
				var data_column = item_content.data('column');
				item_content.find('.tab-content-item').children().addClass(data_column);
				var tab_content = parent.find('.tab-content');
				item_content.appendTo(tab_content);
			})
			$('.menu-tabs .tab-content').each(function(){
					$('.menu-tab-depth-2').each(function(){
						var parent_depth_2 = $(this).closest('li').find('a').attr('href');
						var tab_none_widget = $(this).parents('.dropdown-menu-03').find('.tab-content').find(parent_depth_2).find('.tab-content-item');
						$(this).children().appendTo(tab_none_widget);
					})
				$(this).children().first().addClass('active');
			})
			$('.menu-tabs .nav-tabs').each(function(){
				$(this).children().slice( 1, 2 ).addClass('active');
			})
			$('.dropdown-menu-03 .nav-tabs > li > a').hover(function(e){
				e.preventDefault();
				var href = $(this).attr('href');
				$(this).parents('.dropdown-menu-03').find('.tab-content .tab-pane').removeClass('active'); 
				$(this).parents('.dropdown-menu-03').find('.tab-content').find(href).addClass('active');
			});	
			
			// js for wpml plugin
			if(dropdown_lg){
				$('.menu-item-language').find('ul').addClass('dropdown-menu dropdown-menu-1 exploore-dropdown-menu-1');
				$('.menu-item-language').find('.submenu-languages a').addClass('link-page');
			}else{
				$('.menu-item-language').each(function(){
					$(this).find('a').addClass('main-menu');
				})
			}
			
			if ($(window).width() > 768){
				 // Add class fixed for menu when scroll
				var window_height = $(window).height();

				$(window).on('scroll load', function (event) {
					if ($(window).scrollTop() > window_height) {
						$(".sticky-enable .header-main").addClass('header-fixed');
					}
					else {
						$(".sticky-enable .header-main").removeClass('header-fixed');
					}
					if ($('.bg-white').hasClass('header-03') || $('.bg-transparent').hasClass('header-03')) {
						if ($(window).scrollTop() <= 50) {
							$(".sticky-enable .header-main").removeClass('header-fixed');
						}
					}
				});

				// Show menu when scroll up, hide menu when scroll down
				var lastScroll = 50;
				$(window).on('scroll load', function (event) {
					var st = $(this).scrollTop();
					if (st > lastScroll) {
						$('.sticky-enable .header-main').addClass('hide-menu');
					}
					else if (st < lastScroll) {
						$('.sticky-enable .header-main').removeClass('hide-menu');
					}

					if ($(window).scrollTop() <= 200 ){
						$('.sticky-enable .header-main').removeClass('.header-fixed').removeClass('hide-menu');
					}
					else if ($(window).scrollTop() < window_height && $(window).scrollTop() > 0) {
						$('.sticky-enable .header-main').addClass('hide-menu');
					}
					lastScroll = st;

				});


				// show menu for homepage 03 when click btn-menu
				$('.btn-menu').on('click', function(){
					$('.header-main').toggleClass('show-menu');
				});

				// Show - hide box search on menu
				$('.button-search').on('click', function () {
					$('.nav-search').toggleClass('hide');
					$(this).parents('.navigation').find('.nav-search .searchbox').focus();
					$(this).find('.fa').toggleClass('fa-close');
					if ($('body').hasClass('searchbar-type-2')) {
						$(this).parents('header').toggleClass('search-open');
					}

					if($('header > div').hasClass('header-01') || $('header > div').hasClass('header-04') || $('header > div').hasClass('header-03') ){
		               if ($('.header-main').hasClass('header-fixed')){
		                   $('.nav-search').css({"right": '0'});
		               } else {
		                   var wrapper_search = $('header .header-main-wrapper').width();
		                   var navigation_width = $('header .navigation').width();
		                   var right_search = (wrapper_search - navigation_width) / 2;
		                   $('.nav-search').css({"right": right_search});
		                }
		           }
				});

			}

			// js show menu when screen < 1024px
			$('.hamburger-menu').on('click', function(){
				$('.hamburger-menu-wrapper').toggleClass('open');
				$('body').toggleClass('show-nav');
			});

			if ($(window).width() <= 768) {
				// show hide dropdown menu

				$('.menu-mobile .dropdown .main-menu .icons-dropdown, .menu-mobile .dropdown .link-page .icons-dropdown').on('click', function(e){
					e.preventDefault();
					
					if ($(this).parent().parent().find('.dropdown-menu').hasClass('dropdown-focus') === true) {
						$(this).parent().parent().find('.dropdown-menu').removeClass('dropdown-focus');
						$(this).removeClass('active');
					}
					else {
						$('.menu-mobile .dropdown .dropdown-menu').removeClass('dropdown-focus');
						$('.icons-dropdown').removeClass('active');
						$(this).parents('.dropdown').find('.dropdown-menu:first').addClass('dropdown-focus');
						$(this).addClass('active');
					}
				});
				$('.dropdown-submenu .icons-dropdown').on('click', function(){
					$(this).parents('.dropdown-submenu').find('.dropdown-menu-2:first').toggleClass('dropdown-focus');
					$(this).toggleClass('active');
				});
			}

		////Responsive for Tab search
		$(window).on('resize load', function(event) {
			//Responsive slider for Tab search default
			if ($(window).width() <= 480) {
				if (!$('.tab-search-default .nav-tabs').hasClass('slick-slider')) {
					$('.tab-search-default .nav-tabs').slick({
						fade: true,
						mobileFirst: true,
						swipe: false,
						responsive: [{
							breakpoint: 480,
							settings: "unslick"
						}]
					});
					$('.tab-search-default .slick-prev, .tab-search-default .slick-next').on('click', function(event) {
						var tab_id = $('.tab-search-default .nav-tabs li.slick-current a').attr('href');
						$(tab_id).siblings().removeClass('active in');
						$(tab_id).addClass('active in');
					});
				}
				$('.tab-search-condensed .nav-tabs, .tab-search-transparent .nav-tabs').each(function() {
					var height = $(this).height();
					$(this).css('margin-bottom', height * (-1));
				});
			} else {
				$('.tab-search-condensed .nav-tabs, .tab-search-transparent .nav-tabs').removeAttr('style');
			}
		});
		
	};
	slzexploore_fn.wp_adminbar = function(){
		// Admin bar style
		if ( $( '#wpadminbar' ).length ) {
			var adminbar_style = '<style>html{margin-top:32px;} @media screen and (max-width:782px) {html{margin-top:46px;}}</style>';
			$('body').addClass('adminbar-on');
			$('head').prepend(adminbar_style);
		}
	}
	slzexploore_fn.headerFunction = function(){
		// Set height page banner color transparent
		var header_height = $('header').height();
		var tabBtn_height = $('.tab-search .nav-tabs .tab-btn-wrapper').height();
		$('.page-banner').css('top',header_height*(-1));
		$('.page-banner').css('margin-bottom',header_height*(-1) - tabBtn_height);
		$('.rev-container').css('top',header_height*(-1));

		if($("header .bg-transparent").length) {
			$('.wrapper-custom-slider').css('top',header_height*(-1));
			$('.wrapper-custom-slider').css('margin-bottom',header_height*(-1) - tabBtn_height);
		}
	}
	
	/*=======================================
	=		 END HEADER & FOOTER		   =
	=======================================*/

	/* JS register page */
	slzexploore_fn.registerPage = function(){
		if ( $("#register_member").length ) {
			var formRegisterMember = $("#register_member");
			var msg_username_required = $('input[name=username]').data('validation-error-msg-required');
			var msg_username_minlength = $('input[name=username]').data('validation-error-msg-minlength');
			var msg_email_required = $('input[name=email]').data('validation-error-msg-required');
			var msg_email_format = $('input[name=email]').data('validation-error-msg-format');
			var msg_password_required = $('input[name=password]').data('validation-error-msg-required');
			var msg_password_minlength = $('input[name=password]').data('validation-error-msg-minlength');
			var msg_repassword_required = $('input[name=repassword]').data('validation-error-msg-required');
			var msg_repassword_minlength = $('input[name=repassword]').data('validation-error-msg-minlength');
			var msg_repassword_equalTo = $('input[name=repassword]').data('validation-error-msg-equalTo');
			var msg_recaptcha_required = $('input[name=recaptcha]').data('validation-error-msg-required');
			var msg_agree_required = $('input[name=agree]').data('validation-error-msg-required');

			formRegisterMember.validate({
				rules: {
					username: {
						required: true,
						minlength: 6
					},
					email: {
						required: true,
						email: true
					},
					password: {
						required: true,
						minlength: 8
					},
					repassword: {
						required: true,
						minlength: 8,
						equalTo: "#password"
					},
					'g-recaptcha-response': "required",
					agree: "required"
				},
				messages: {
					username: {
						required: msg_username_required,
						minlength: msg_username_minlength
					},
					email: {
						required: msg_email_required,
						email: msg_email_format
					},
					password: {
						required: msg_password_required,
						minlength: msg_password_minlength
					},
					repassword: {
						required: msg_repassword_required,
						minlength: msg_repassword_minlength,
						equalTo: msg_repassword_equalTo
					},
					'g-recaptcha-response': msg_recaptcha_required,
					agree: msg_agree_required,
				}
			});

			formRegisterMember.on('submit', function(e){
				var isValid = $(this).valid();
				if (isValid) {
					console.log('isValid');
					formRegisterMember.find('input.btn-register').removeClass('btn-register').removeAttr('type').attr('readonly', '').val('Processing');
				}
			});
			
			$('.register-form input, .register-form').live('change click', function(){
				if($('#g-recaptcha-response').val() != "") {
					$('label[for="g-recaptcha-response"]').empty().hide();
				}
			});
		}
	}


	/*======================================
	=			INIT FUNCTIONS			=
	======================================*/

	$(document).ready(function(){
		slzexploore_fn.header_footerFunction();
		slzexploore_fn.mainFunction();
		slzexploore_fn.headerFunction();
		slzexploore_fn.registerPage();
		slzexploore_fn.wp_adminbar();
	});
	$( window ).load( function() {
		$( window ).load( function() {
			if($(window).width() < 768) {
				setTimeout(function () {
					var tabBtn_height = $('.tab-search .nav-tabs .tab-btn-wrapper').height();
					$('.page-banner').css('margin-bottom',header_height*(-1) - tabBtn_height);
				}, 100);
			};
		});
		//setting rtl
		if ($('body').hasClass("rtl")) {
			var offsetLeft = parseInt($('.wrapper-content .vc_row[data-vc-full-width]').css('left'));
			$('.wrapper-content .vc_row[data-vc-full-width]').css({
				'left': 'auto',
				'right': offsetLeft
			});
		}
		setTimeout(function() {
			$('.sidebar-widget .sidebar-wrapper').css('position', 'static');
		}, 200);
	});
	$(window).on('resize', function() {
		if (window.innerWidth < 1025) {
			$('.sidebar-widget .sidebar-wrapper').css('position', 'static');
		}
		/* Act on the event */
	});
	/*======================================
	=		  END INIT FUNCTIONS		  =
	======================================*/
});
