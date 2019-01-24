(function($) {
	"use strict";
	var slzcore_flag_rtl = true;
	if (!$('body').hasClass("rtl")) {
		slzcore_flag_rtl = false;
	}

	$.slzexploore_expand_event = function(){
		$(".search-expand").click(function () {
		    var header = $(this);
		    $(this).toggleClass('active');
		    var content = header.next();
		    content.slideToggle(500, function () {
		        header.text(function () {
		            return content.is(":visible") ? header.data('collapse'): header.data('expand');
		        });
		    });

		});
	};
	$.slzexploore_tabs = function(){
		$(".slz-tabs").each(function() {
			$(this).find('.tab-pane').first().addClass('active in');
		});
	};
	$.slzexploore_accordion = function(){
		if ($('.slz-accordion.timeline-container').length > 0){
			$('.slz-accordion.timeline-container .timeline>div').addClass('timeline-block');
			$('.slz-accordion .timeline .vc_tta-panel-heading').addClass('timeline-title');
			$('.slz-accordion .timeline .vc_tta-panel-body').addClass('timeline-content medium-margin-top');
			$('.slz-accordion .timeline .vc_tta-panel-heading .vc_tta-title-text').unwrap().unwrap();
			$('.slz-accordion .timeline .vc_tta-panel-body .row').prepend('<div class="timeline-point"><i class="fa fa-circle-o"></i></div>');
		}
		if ($('.timeline-block').length > 0){
			if (window.innerWidth < 769) {

				$('.timeline-custom-col.content-col').each(function(index, el) {
					var height_for_point = $(this).height()/2;
					$(this).prev().css('top',height_for_point);
				});
				$('.timeline-block:last-child .timeline-point').css('height', $('.timeline-block:last-child .timeline-content').height()-50-120);
			}
			else {
				$('.timeline-custom-col.content-col').each(function(index, el) {
					if( $(this).hasClass('w-full') == false ) {
						if ( $(this).height() > 250) {
							$(this).prev().css('top', '125px');
						}
					}
				});
				var line_point = $('.timeline-block:last-child .timeline-point').height() + 4;
				var last_block = $('.timeline-block:last-child .timeline-content').height()/ 2;

				if( $('.timeline-block:last-child .timeline-custom-col.content-col').hasClass('w-full') == false 
						&& $('.timeline-block:last-child .timeline-custom-col.content-col').height() > 250 ) {
					last_block = $('.timeline-block:last-child .timeline-content').height() - 125;
				}
				$('.timeline-block:last-child .timeline-point').css('height', last_block + line_point);
			}
		}
	};

	$.slzexploore_item_list_style4 = function(){
		var number=6;
		if ($('.wrapper-journey').length > 0){
			if ( $('div.wrapper-journey').attr('data-item') ) {
				var number = $('div.wrapper-journey').attr('data-item');
			}
			number = parseInt(number);
			$('.wrapper-journey').slick({
				infinite: false,
				slidesToShow: number,
				slidesToScroll: number,
				autoplay: false,
				speed: 700,
				dots: true,
				arrows: false,
				rtl: slzcore_flag_rtl,
				responsive: [
					{
						breakpoint: 1201,
						settings: {
							slidesToShow: 5,
							slidesToScroll: 5
						}
					},
					{
						breakpoint: 1025,
						settings: {
							slidesToShow: 4,
							slidesToScroll: 4
						}
					},
					{
						breakpoint: 769,
						settings: {
							slidesToShow: 3,
							slidesToScroll: 3,
							dots: true,
							arrows: false,
						}
					},
					{
						breakpoint: 481,
						settings: {
							slidesToShow: 2,
							slidesToScroll: 2,
							dots: true,
							arrows: false,
						}
					},
					{
						breakpoint: 381,
						settings: {
							slidesToShow: 1,
							slidesToScroll: 1,
							dots: true,
							arrows: false,
						}
					}
				]
			});
		}
	};

	$.slzexploore_partner = function(){
		$('.wrapper-banner').slick({
			infinite: true,
			slidesToShow: 6,
			slidesToScroll: 6,
			speed: 1500,
			dots: false,
			rtl: slzcore_flag_rtl,
			responsive: [
				{
					breakpoint: 769,
					settings: {
						slidesToShow: 4,
						slidesToScroll: 4
					}
				},
				{
					breakpoint: 481,
					settings: {
						slidesToShow: 2,
						slidesToScroll: 2
					}
				}
			]
		});
	};

	/*Recent news*/
	$.slzexploore_recent_news = function(){
		if ($('.news-list').length > 0){
			var attr_autoplay;
			var attr_autospeed;
			var attr_speed;
			var autoplay = false;
			var autospeed = 6000;
			var speed = 700;
			var news_list = $('.news-list');
			var attr_autoplay = news_list.attr('data-autoplay');
			var attr_autospeed = news_list.attr('data-autospeed');
			var attr_speed = news_list.attr('data-speed');
			if (attr_autoplay != "" && attr_autoplay == 'true') {
				autoplay = true;
			}
			if (attr_autospeed != "" && attr_autospeed > 0) {
				autospeed = attr_autospeed;
			}
			if (attr_speed != "" && attr_speed > 0) {
				speed = attr_speed;
			}
			news_list.slick({
				autoplay: autoplay,
				autoplaySpeed: autospeed,
				infinite: true,
				dots: true,
				speed: speed,
				slidesToShow: 1,
				slidesToScroll: 1,
				/*fade: true,*/
				cssEase: 'linear',
				arrows: false,
				rtl: slzcore_flag_rtl
			});
		}
	};

	/* Blog list */
	$.slzexploore_blog = function() {
		/*video play*/
		$(".video-button-play ").on('click', function(event){
			reload_video_play();
			var parent = $(this).parent();
			var video_jele = parent.find('iframe.video-embed');
			if (video_jele.length) {
				var video_src = video_jele.attr('src');
				video_jele.addClass('show-video');
				parent.find('.video-button-close').addClass('show-video');
				video_jele.attr('src-ori', video_src);
				video_jele.attr('src', video_src + "&autoplay=1");
				event.preventDefault();
			}
		});

		/*video disable*/
		$(".video-button-close").on('click', function(event){
			reload_video_play();
			event.preventDefault();
		});

		/* disable video, reload src to original*/
		function reload_video_play() {
			$('.video-button-close, iframe.video-embed').removeClass('show-video');
			$('.video-thumbnail').find('iframe.video-embed').each(function() {
				var video_src = $(this).attr('src'); 
				var video_src_ori = video_src.replace('&autoplay=1', '');
				$(this).attr('src', video_src_ori);
			});			
		}

	};
	/* 
	 * accommodation archive page
	 * START
	 */
	// accommodation template pagination with ajax
	$.slzexploore_accommodation_ajax_pagination = function() {
		if ($('.hotel-result-main').length > 0){
			$('.hotel-result-main nav.pagination-list.paging-ajax ul li a').unbind("click");
			$('.hotel-result-main nav.pagination-list.paging-ajax ul li a').on('click', function(e){
				e.preventDefault();
				var container  = $(this).closest('.result-body');
				$('.hotel-result-main > .loading').show().fadeIn();
				$.slzexploore_scroll_to( container );
				var page = parseInt( $(this).data('page') );
				var base = $(this).closest('.paging-ajax').parent().find('.slz-pagination-base').data('base');
				var atts = $(this).closest('.paging-ajax').parent().find('.pagination-json').attr('data-json');
				var data_search = $(this).closest('.paging-ajax').parent().find('.pagination-json').attr('data-search');
				var data = {"page":page, "base":base, "atts":jQuery.parseJSON(atts), "data":jQuery.parseJSON(data_search) };
				$.fn.Form.ajax(['top.Top_Controller', 'ajax_accommodation_pagination'], data, function(res) {
					$('.hotel-result-content', container).html(res);
					$('.hotel-result-main > .loading').show().fadeOut();
					$.slzexploore_accommodation_ajax_pagination();
					$.slzexploore_add_hotel_wish_list();
					$.slzexploore_result_found();
					$.slzexploore_reset_search_form();
				});
			});
		}
	};
	
	$.slzexploore_scroll_to = function( element ){
		if( element.length ){
			$('body,html').animate({
				scrollTop: element.offset().top - 100
			}, 900);
		}
		return false;
	}
	
	// nstSlider
	$.slzexploore_nstSlider = function() {
		$('.nstSlider').nstSlider({
			"crossable_handles": false,
			"left_grip_selector": ".leftGrip",
			"right_grip_selector": ".rightGrip",
			"value_changed_callback": function(cause, leftValue, rightValue) {
				var departTime = $(this).find('.leftGrip .number');
				var arriveTime = $(this).find('.rightGrip .number');
				departTime.text(leftValue);
				arriveTime.text(rightValue);
				var leftGripBoderRight = departTime.offset().left + departTime.width();
				var rightGripBorderLeft = arriveTime.offset().left;
				if (leftGripBoderRight >= rightGripBorderLeft) {
					arriveTime.css("top","20px");
				} else {
					arriveTime.removeAttr("style");
				}
				if (rightGripBorderLeft >= $(".rightLabel").offset().left - 30) {
					$(".rightLabel").css("top","20px");
				} else {
					$(".rightLabel").removeAttr("style");
				}
				$(this).parent().find('.sliderValue').val(leftValue + ',' + rightValue);
			},
			"user_mouseup_callback": function( vmin, vmax, left_grip_moved ) {
				// load hotel by price
				var container  = $(this).closest('.result-body');
				var data = $(this).closest('form').serializeArray();
				var price_widget = $(this).closest('.price-widget');
				if( price_widget.hasClass('accommodation') ) {
					slzexploore_reload_accommodation( container, data );
				}
				else if( price_widget.hasClass('tour') ) {
					slzexploore_reload_tour( container, data );
				}
				else if( price_widget.hasClass('car') ) {
					slzexploore_reload_car( container, data );
				}
				else if( price_widget.hasClass('cruise') ) {
					slzexploore_reload_cruises( container, data );
				}
			}
		});
	};
	// datepicker in search form
	$.slzexploore_datepicker = function() {
		if( $('.slz-shortcode .input-daterange').length > 0 || $('.archive-datepicker').length > 0 ){
			$('.slz-shortcode .input-daterange, .archive-datepicker').datepicker({
				format: 'yyyy-mm-dd',
				autoclose : true,
				maxViewMode: 0
			});
		}
	};
	function slzexploore_reload_accommodation( container, data ){
		// clear sort
		$('.result-meta').find('.result-filter select.selectbox').next('.sbHolder').each(function() {
			var def_val = $('.sbOptions li:first-child a', $(this) ).html();
			$('.sbSelector', $(this) ).html( def_val );
		});
		$('.hotel-result-main > .loading').show().fadeIn();
		$.slzexploore_scroll_to( container );
		$('.slz-search-widget .widget .content-widget').each(function() {
			slzexploore_show_reset_btn( $(this) );
		});
		var base = $('.slz-pagination-base', container).data('base');
		var attr_more =  $('.hotel-map-search', container).data('attr');
		$.fn.Form.ajax(['top.Top_Controller', 'ajax_search_accommodation'], {'data' : data, 'base' : base , 'atts_more': attr_more }, function(res) {
			$(container).closest('.sc-map-location').find('.map-block').remove();
			$('.hotel-result-content', container).html(res);
			if($('.map-block-ajax',container)[0]){
				var asset_uri =  $(container).closest('.result-page').data('asset_uri');
				setTimeout( function(){
					$.getScript( asset_uri + "/js/slzexploore-core-multi-maps.js", function(){} );
				}, 500 );
				var map_content = $(container).find('.map-block-ajax').clone();
				$(container).closest('.sc-map-location').find('.map-block-wrapper').html(map_content);
				$(container).find('.map-block-ajax').remove();
			}
			$('.hotel-result-main > .loading').show().fadeOut();
			$.slzexploore_accommodation_ajax_pagination();
			$.slzexploore_result_found();
			$.slzexploore_reset_search_form();
			$.slzexploore_add_hotel_wish_list();
		});
	}
	// accommodation search
	$.slzexploore_search_accommodation = function(){
		if ($('.result-page').length > 0){
			$('.result-page .hotel-template .content-widget button[type="submit"]').unbind("click");
			$('.result-page .hotel-template .content-widget button[type="submit"]').on('click', function(e){
				e.preventDefault();
				var container  = $(this).closest('.result-body');
				var data = $(this).closest('form').serializeArray();
				slzexploore_reload_accommodation( container, data );
			});
		}
		if ($('.find-hotel').length > 0){
			$('.turkey-cities-widget input[name="rating"], .accommodation-widget input[name="accommodation"], .stop-widget input[name="facilities"], .rating-widget input[name="review_rating"], .location-widget input[name="location"]', $('.find-hotel.result-page')).on('change', function(e){
				e.preventDefault();
				var container  = $(this).closest('.result-body');
				var data = $(this).closest('form').serializeArray();
				slzexploore_reload_accommodation( container, data );
			});
			// in other page
			$('.turkey-cities-widget input[name="rating"], .accommodation-widget input[name="accommodation"], .stop-widget input[name="facilities"], .rating-widget input[name="review_rating"], .location-widget input[name="location"]', $('.find-hotel:not(".result-page")') ).on('change', function(e){
				e.preventDefault();
				$(this).closest('.find-hotel').find('.find-widget button[type="submit"]').trigger('click');
			});
		}
	};
	// result filter with ajax
	$.slzexploore_result_sort_filter = function() {
		if ($('.result-filter').length > 0){
			$('.result-filter .select-wrapper select.selectbox').selectbox();
			$('.result-filter .select-wrapper select.selectbox').on('change', function(e){
				e.preventDefault();

				var cur_val = $(this).val();
				var container  = $(this).closest('.result-body');
				var base = $('.slz-pagination-base', container).data('base');
				var sort_type = $(this).closest('.result-filter').find('.slz-sort-type').attr('data-type');
				var search_data = '';
				var cls_content = '';
				var cls_main = '';
				if( sort_type == 'hotel' ) {
					search_data = $('form.find-hotel', container).serializeArray();
					cls_content = '.hotel-result-content';
					cls_main = '.hotel-result-main';
					var attr_more =  $('.hotel-map-search', container).data('attr');
				}
				else if( sort_type == 'tour' ) {
					search_data = $('.slz-search-widget', container).serializeArray();
					cls_content = '.tours-result-content';
					cls_main = '.tour-result-main';
					var attr_more =  $('.tour-map-search', container).data('attr');
				}
				else if( sort_type == 'car' ) {
					search_data = $('form.find-car', container).serializeArray();
					cls_content = '.car-result-content';
					cls_main = '.car-rent-result-main';
					var attr_more =  $('.car-map-search', container).data('attr');
				}
				else if(sort_type == 'cruise' ) {
					search_data = $('form.find-cruise', container).serializeArray();
					cls_content = '.cruises-result-content';
					cls_main = '.cruises-result-main';
					var attr_more =  $('.cruise-map-search', container).data('attr');
				}
				if( search_data == '' ){
					var column = $(this).closest('.result-filter-wrapper').find('.slz-archive-column').attr('data-col');
					if( column  ){
						search_data.push({name:'column', value:column});
					}
				}
				$( cls_main + ' > .loading').show().fadeIn();
				var data = {'sort_value' : cur_val, 'search_data' : search_data, 'sort_type' : sort_type, 'base' : base ,'atts_more': attr_more };
				$.fn.Form.ajax(['top.Top_Controller', 'ajax_result_filter'], data, function(res) {
					$( cls_content, container).html(res);
					$( cls_main + ' > .loading').show().fadeOut();
					if( sort_type == 'hotel' ) {
						$.slzexploore_accommodation_ajax_pagination();
					}
					else if( sort_type == 'tour' ) {
						$.slzexploore_tour_ajax_pagination();
					}
					else if( sort_type == 'car' ) {
						$.slzexploore_car_ajax_pagination();
					}
					else {
						$.slzexploore_cruises_ajax_pagination();
					}
					$.slzexploore_result_found();
					$.slzexploore_reset_search_form();
				});
			});
		}
	};
	// hotel result found
	$.slzexploore_result_found = function() {
		var result_found = $('.result-body .results-found').html();
		if( result_found ) {
			$('.result-body .result-meta .result-count-wrapper').html( result_found );
			$('.result-body .results-found').remove();
		}
	};
	/* 
	 * END
	 * accommodation archive page
	 */

	$.slzexploore_partner_style2 = function(){
		$('.slide-logo-wrapper').slick({
			infinite: true,
			slidesToShow: 6,
			slidesToScroll: 1,
			autoplay: true,
			autoplaySpeed: 3000,
			arrows: false,
			pauseOnHover: false,
			rtl: slzcore_flag_rtl,
			responsive: [
				{
					breakpoint: 769,
					settings: {
						slidesToShow: 4
					}
				},
				{
					breakpoint: 481,
					settings: {
						slidesToShow: 3,
						 speed: 5000
					}
				},
				{
					breakpoint: 381,
					settings: {
						slidesToShow: 2

					}
				}
			]
		});
	};

	$.slzexploore_carousel = function(){
		$('.special-offer-list .title-wrapper a.heading').addClass('title');
		$('.special-offer-list').slick({
			infinite: true,
			slidesToShow: 4,
			slidesToScroll: 4,
			speed: 2000,
			dots: false,
			rtl: slzcore_flag_rtl,
			responsive: [
				{
					breakpoint: 1025,
					settings: {
						slidesToShow: 3,
						slidesToScroll: 3,
						autoplay: true,
						autoplaySpeed: 5000,
						dots: true,
						arrows: false
					}
				},
				{
					breakpoint: 767,
					settings: {
						slidesToShow: 2,
						slidesToScroll: 2,
						autoplay: true,
						autoplaySpeed: 5000,
						dots: true,
						arrows: false
					}
				},
				{
					breakpoint: 481,
					settings: {
						slidesToShow: 1,
						slidesToScroll: 1,
						autoplay: true,
						autoplaySpeed: 5000,
						dots: true,
						arrows: false
					}
				}
			]
		});
	};

	$.slzexploore_teamcarousel = function(){
		$('.wrapper-expert').slick({
			infinite: true,
			slidesToShow: 4,
			slidesToScroll: 4,
			speed: 1500,
			dots: false,
			rtl: slzcore_flag_rtl,
			responsive: [
				{
					breakpoint: 769,
					settings: {
						slidesToShow: 3,
						slidesToScroll: 3,
						dots: true
					}
				},
				{
					breakpoint: 601,
					settings: {
						slidesToShow: 2,
						slidesToScroll: 2,
						dots: true
					}
				},
				{
					breakpoint: 381,
					settings: {
						slidesToShow: 1,
						slidesToScroll: 1,
						dots: true
					}
				}
			]
		});
	};
	$.slzexploore_tour_carousel = function() {
		if($('.tours-carousel').length == 0) return;
		$('.tours-carousel').each(function(){
			var slide_count = $(this).attr('data-count');
			if( slide_count == undefined || slide_count == "" ) {
				slide_count = 3;
			}
			$(this).slick({
				infinite : true,
				speed : 1000,
				slidesToShow : slide_count,
				slidesToScroll : 1,
				arrows : false,
				dots : false,
				rtl: slzcore_flag_rtl,
				responsive : [ {
					breakpoint : 769,
					settings : {
						slidesToShow : 2,
						slidesToScroll : 2,
						dots : true
					}
				}, {
					breakpoint : 601,
					settings : {
						slidesToShow : 1,
						slidesToScroll : 1,
						dots : true,
						speed : 600
					}
				}, {
					breakpoint : 481,
					settings : {
						slidesToShow : 1,
						slidesToScroll : 1,
						dots : true,
						speed : 600
					}
				} ]
			});
		});
	};

	/* 
	 * tour archive page
	 * START
	 */
	// tour template pagination with ajax
	$.slzexploore_tour_ajax_pagination = function() {
		if ($('.tour-result-main').length > 0){
			$('.tour-result-main nav.pagination-list.paging-ajax ul li a').unbind("click");
			$('.tour-result-main nav.pagination-list.paging-ajax ul li a').on('click', function(e){
				e.preventDefault();
				var container  = $(this).closest('.result-body');
				$('.tour-result-main > .loading').show().fadeIn();
				$.slzexploore_scroll_to( container );
				var page = parseInt( $(this).data('page') );
				var base = $(this).closest('.paging-ajax').parent().find('.slz-pagination-base').data('base');
				var atts = $(this).closest('.paging-ajax').parent().find('.pagination-json').attr('data-json');
				var data_search = $(this).closest('.paging-ajax').parent().find('.pagination-json').attr('data-search');
				var data = {"page":page, "base":base, "atts":jQuery.parseJSON(atts), "data":jQuery.parseJSON(data_search) };
				$.fn.Form.ajax(['top.Top_Controller', 'ajax_tour_pagination'], data, function(res) {
					$('.tours-result-content', container).html(res);
					$('.tour-result-main > .loading').show().fadeOut();
					$.slzexploore_result_found();
					$.slzexploore_reset_search_form();
					$.slzexploore_tour_ajax_pagination();
					$.slzexploore_add_tour_wish_list();
				});
			});
		}
	};
	function slzexploore_reload_tour( container, data ){
		// clear sort
		$('.result-meta').find('.result-filter select.selectbox').next('.sbHolder').each(function() {
			var def_val = $('.sbOptions li:first-child a', $(this) ).html();
			$('.sbSelector', $(this) ).html( def_val );
		});
		$('.tour-result-main > .loading').show().fadeIn();
		$.slzexploore_scroll_to( container );
		$('.slz-search-widget .widget .content-widget').each(function() {
			slzexploore_show_reset_btn( $(this) );
		});
		var base = $('.slz-pagination-base', container).data('base');
		var attr_more =  $('.tour-map-search', container).data('attr');
		$.fn.Form.ajax(['top.Top_Controller', 'ajax_search_tour'], {'data' : data, 'base' : base ,'atts_more': attr_more  }, function(res) {
			$(container).closest('.sc-map-location').find('.map-block').remove();
			$('.tours-result-content', container).html(res);
			if($('.map-block-ajax',container)[0]){
				var asset_uri =  $(container).closest('.result-page').data('asset_uri');
				setTimeout( function(){
					$.getScript( asset_uri + "/js/slzexploore-core-multi-maps.js", function(){} );
				}, 500 );
				var map_content = $(container).find('.map-block-ajax').clone();
				$(container).closest('.sc-map-location').find('.map-block-wrapper').html(map_content);
				$(container).find('.map-block-ajax').remove();
			}
			$('.tour-result-main > .loading').show().fadeOut();
			$.slzexploore_tour_ajax_pagination();
			$.slzexploore_result_found();
			$.slzexploore_reset_search_form();
			$.slzexploore_add_tour_wish_list();
		});
	}
	// accommodation search
	$.slzexploore_search_tour = function(){
		if( $('.slz-search-widget').length ){
			$('.slz-search-widget .widget .content-widget').each(function() {
				slzexploore_show_reset_btn( $(this) );
			});
		}
		if ($('.result-page').length > 0){
			$('.result-page .tour-template .content-widget button[type="submit"]').unbind("click");
			$('.result-page .tour-template .content-widget button[type="submit"]').on('click', function(e){
				e.preventDefault();
				var container  = $(this).closest('.result-body');
				var data = $(this).closest('form').serializeArray();
				slzexploore_reload_tour( container, data );
			});
		}
		// in Result page
		if ($('.city-widget').length > 0){
			$('.city-widget .content-widget input[name="location"], .city-widget .content-widget input[name="category"], .rating-widget .content-widget input[name="review_rating"]', $('.find-tour.result-page') ).on('change', function(e){
				e.preventDefault();
				var container  = $(this).closest('.result-body');
				var data = $(this).closest('form').serializeArray();
				slzexploore_reload_tour( container, data );
			});
			// in other page
			$('.city-widget .content-widget input[name="location"], .city-widget .content-widget input[name="category"], .rating-widget .content-widget input[name="review_rating"]', $('.find-tour:not(".result-page")') ).on('change', function(e){
				e.preventDefault();
				$(this).closest('.find-tour').find('.find-widget button[type="submit"]').trigger('click');
			});
		}
	};
	// set tab active in search form
	$.slzexploore_search_tab_active = function(){
		if ($('.tab-search').length > 0){
			$('.tab-search ul.nav-tabs li.tab-btn-wrapper:first-child').addClass("active");
			$('.tab-search .tab-content .tab-pane:first-child').addClass("in active");
			var placeholder = $('.tab-search .tab-content').data('placeholder');
			$('.tab-search .tab-content .find-widget .content-widget select.select2').select2({
				placeholder: placeholder,
				allowClear: true
			});
		}
		if( $('.slz-search-widget .find-widget').length > 0 ){
			var placeholder = $('.slz-search-widget .find-widget').data('placeholder');
			$('.slz-search-widget .find-widget .content-widget select.select2').select2({
				placeholder: placeholder,
				allowClear: true
			});
		}
	};
	/* 
	 * END
	 * tour archive page
	 */
	$.slzexploore_discount_box_style1 = function(){
		if ($('.banner-sale-2').length) {
			$('.banner-sale-2').mousemove(function(e) {
				$('.banner-sale-2 .text-parallax').parallax(25, e);
			});
		}
	}

	/* 
	 ** Gallery image_slider
	 ** Gallery masonry_grid
	 */
	$.slzexploore_gallery_image = function(){
		var attr = '';
		var arrows  = '';
		var block_class = '';
		$(".slz-shortcode .image-hotel-view-block").each(function() {
			attr = $(this).attr('data-slider');
			arrows = $(this).attr('data-arrows');
			if(arrows == 'true'){
				arrows = true;
			}else{
				arrows = false;
			}
			if (attr != '') {
				block_class = '.gallery_' + attr + ' ';
			}

			$(block_class + '.slider-for').slick({
				slidesToShow: 1,
				slidesToScroll: 1,
				arrows: arrows,
				fade: true,
				rtl: slzcore_flag_rtl,
				asNavFor: block_class + '.slider-nav'
			});
			$(block_class + '.slider-nav').slick({
				slidesToShow: 5,
				slidesToScroll: 1,
				asNavFor: block_class + '.slider-for',
				arrows: false,
				infinite: true,
				rtl: slzcore_flag_rtl,
				focusOnSelect: true,
				responsive: [
					{
						breakpoint: 1201,
						settings: {
							slidesToShow: 4,
							slidesToScroll: 1
						}
					},
					{
						breakpoint: 381,
						settings: {
							slidesToShow: 3,
							slidesToScroll: 1
						}
					},
				]
			});
		});	
		if ($('.gallery-block').length > 0){
			if($('.gallery-block').width()> 600) {
				var height_grid_item = $('.gallery-block').width()/3;
				$('.gallery-block .grid-item,.gallery-block .grid-item img, .gallery-image .bg').css('height', height_grid_item-50);

				$('.gallery-block .grid').isotope({
					itemSelector: '.grid-item',
					percentPosition: true,
					masonry: {
						columnWidth: '.grid-sizer',
						gutter: '.gutter-sizer'
					},
				});
			} else if($('.gallery-block').width()> 414) {
				var height_grid_item = $('.gallery-block').width()/2;
				$('.gallery-block .grid-item,.gallery-block .grid-item img, .gallery-image .bg').css('height', height_grid_item+30);
			}

			$('.gallery-block .fancybox').fancybox( {
				helpers : {
					thumbs  : {
						width   : 50,
						height  : 50
					}
				}
			});
		}

		if($(window).width() > 1023)	 
			$('.gallery-block .grid .grid-item .gallery-image').directionalHover();		

		$('.gallery-image .fancybox').each(function(index, el) {
			var src = $(this).attr('href');
			$(this).parents('.gallery-image').find('.bg').css({
				'background': 'url('+src+') no-repeat center',
				'background-size': 'cover'
			});
			if($(window).width() < 1024) {
				$(this).parents('.gallery-image').find('.fancybox').removeClass('dh-overlay').find('.icons').remove();
			}
		});

		if ($('.sc_gallery').length) {
			$('.sc_gallery').each(function() {
				var id = $(this).data('id');
				var block = '.' + id + ' ';
				$(block + '.icons').on('click', function(event) {
					$(block + '.a-fact-image-wrapper .a-fact-image').removeClass('active');
					$(this).parent().addClass('active');
				});
			});
		}

		// Gallery widget js
		if( $(".main-gallery-fancybox").length ){
			$(".main-gallery-fancybox .fancybox").fancybox({
				prevEffect  : 'none',
				nextEffect  : 'none',
				helpers : {
					title   : {
						type: 'outside'
					},
					thumbs  : {
						width   : 60,
						height  : 60
					}
				}
			});
			$.fancybox.helpers.thumbs.onUpdate = function( opts, obj ){
				if (this.list) {
					var center = Math.floor($(window).width() * 0.5 - (obj.group.length / 2 * this.width + this.width * 0.5));
					if(!$('body').hasClass("rtl")) {
						this.list.css('left', center);
					}
					else {
						this.list.css('right', center);
					}
				}
			};
		}
	}

	$.slzexploore_teamsingle = function() {
		$('.content-organization img').unwrap();
	}

	$.slzexploore_room_type = function() {
		$('.overview-block .image-hotel-view-block .slider-for').each(function() {
			$(this).slick({
				slidesToShow: 1,
				slidesToScroll: 1,
				arrows: false,
				fade: true,
				rtl: slzcore_flag_rtl,
				asNavFor: $('.slider-nav', $(this).parent())
			});
			$('.slider-nav', $(this).parent()).slick({
				slidesToShow: 5,
				slidesToScroll: 1,
				asNavFor: $(this),
				arrows: false,
				infinite: true,
				focusOnSelect: true,
				rtl: slzcore_flag_rtl,
				responsive: [
					{
						breakpoint: 1201,
						settings: {
							slidesToShow: 4,
							slidesToScroll: 1
						}
					},
					{
						breakpoint: 381,
						settings: {
							slidesToShow: 3,
							slidesToScroll: 1
						}
					},
				]
			});
		});
	}

	$.slzexploore_testimonial = function() {
		$('.traveler-list').slick({
			infinite: true,
			slidesToShow: 2,
			slidesToScroll: 2,
			autoplay: false,
			speed: 700,
			rtl: slzcore_flag_rtl,
			responsive: [{
				breakpoint: 769,
				settings: {
					slidesToShow: 2,
					slidesToScroll: 2,
					dots: true,
					arrows: false
				}
			}, {
				breakpoint: 601,
				settings: {
					slidesToShow: 1,
					slidesToScroll: 1,
					dots: true,
					arrows: false
				}
			}]
		});
	}

	$.slzexploore_add_tour_wish_list = function() {
		if ($('.tours-layout').length > 0){
			$('.tours-layout .content-wrapper a.slz_add_wish_list').unbind("click");
			$('.tours-layout .content-wrapper a.slz_add_wish_list').on('click', function(e){
				var post_id = $(this).data('item');
				var parent = $(this).closest('.content-wrapper');
				$.fn.Form.ajax(['top.Top_Controller', 'ajax_add_tour_wish_list'], {'post_id' : post_id}, function(res) {
					if( res != 'fail' ){
						$( '.list-info a.slz_add_wish_list', parent ).replaceWith(res);
					}
				});
			});
		}
	}

	$.slzexploore_add_hotel_wish_list = function() {
		if ($('.hotels-layout').length > 0){
			$('.hotels-layout .list-info a.slz_add_wish_list').unbind("click");
			$('.hotels-layout .list-info a.slz_add_wish_list').on('click', function(e){
				var post_id = $(this).data('item');
				var $this = $(this);
				$.fn.Form.ajax(['top.Top_Controller', 'ajax_add_hotel_wish_list'], {'post_id' : post_id}, function(res) {
					if( res != 'fail' ){
						$this.replaceWith(res);
					}
				});
			});
		}
	}

	$.slzexploore_contact_button = function() {
		var text = $('.slz-shortcode .contact .wpcf7-form input.wpcf7-submit').val();
		var hover_text = $('.slz-button-hove-text').data('text');
        $('.slz-shortcode .contact .wpcf7-form').off(); // fixed: duplicate submit contact form 7 plugin
		$('.slz-shortcode .contact .wpcf7-form input.wpcf7-submit').replaceWith('<button class="wpcf7-form-control wpcf7-submit btn btn-slide" data-hover="'+ hover_text +'" type="submit"><span class="text">'+ text +'</span><span class="icons fa fa-long-arrow-right"></span></button>');
	}

	$.slzexploore_star_rating = function() {
		$('.stars-rating span a').on('click', function(e){
			e.preventDefault();
			$('.stars-rating span').find('a').removeClass('active');
			$(this).addClass('active');
			$(this).closest('.comment-form-rating').find('input[name="rating"]').val( $(this).html() );
		});
	};
	$.slzexploore_toggle_box = function(){
		$('.wrapper-accordion .panel .panel-heading').on('click', function() {
			var accor = $(this).closest('.accordion');
			var accor_panel = $(this).parent();
			if (accor_panel.hasClass('active')){
				accor_panel.removeClass('active');
			} else{
				if ($('.panel-title a.accordion-toggle').hasClass('collapsed')) {
					$('.panel', accor).removeClass('active');
					accor_panel.addClass('active');
				} else{
					accor_panel.removeClass('active');
				}
			}
		});
	};
	$.slzexploore_faq = function() {
		if ($('.sc_faq_request form.contact-form')) {
			$('.sc_faq_request form.contact-form input[name=_wpcf7]').before('<input type="hidden" name="_wpcf7_slzexploore_faq_form" value="1">');
		}
	};
	/* 
	 * Car archive page
	 */
	/* pagination with ajax */
	$.slzexploore_car_ajax_pagination = function() {
		if ($('.car-rent-result-main').length > 0){
			$('.car-rent-result-main nav.pagination-list.paging-ajax ul li a').unbind("click");
			$('.car-rent-result-main nav.pagination-list.paging-ajax ul li a').on('click', function(e){
				e.preventDefault();
				var container  = $(this).closest('.result-body');
				$('.car-rent-result-main > .loading').show().fadeIn();
				$.slzexploore_scroll_to( container );
				var page = parseInt( $(this).data('page') );
				var base = $(this).closest('.paging-ajax').parent().find('.slz-pagination-base').data('base');
				var atts = $(this).closest('.paging-ajax').parent().find('.pagination-json').attr('data-json');
				var data_search = $(this).closest('.paging-ajax').parent().find('.pagination-json').attr('data-search');
				var data = {"page":page, "base":base, "atts":jQuery.parseJSON(atts), "data":jQuery.parseJSON(data_search) };
				$.fn.Form.ajax(['top.Top_Controller', 'ajax_car_pagination'], data, function(res) {
					$('.car-result-content', container).html(res);
					$('.car-rent-result-main > .loading').show().fadeOut();
					$.slzexploore_result_found();
					$.slzexploore_reset_search_form();
					$.slzexploore_car_ajax_pagination();
				});
			});
		}
	};
	/* Car search form */
	function slzexploore_reload_car( container, data ){
		// clear sort
		$('.result-meta').find('.result-filter select.selectbox').next('.sbHolder').each(function() {
			var def_val = $('.sbOptions li:first-child a', $(this) ).html();
			$('.sbSelector', $(this) ).html( def_val );
		});
		$('.car-rent-result-main > .loading').show().fadeIn();
		$.slzexploore_scroll_to( container );
		$('.slz-search-widget .widget .content-widget').each(function() {
			slzexploore_show_reset_btn( $(this) );
		});
		var base = $('.slz-pagination-base', container).data('base');
		var attr_more =  $('.car-map-search', container).data('attr');

		$.fn.Form.ajax(['top.Top_Controller', 'ajax_search_car'], {'data' : data, 'base' : base,'atts_more': attr_more }, function(res) {
			$(container).closest('.sc-map-location').find('.map-block').remove();
			$('.car-result-content', container).html(res);
			if($('.map-block-ajax',container)[0]){
				var asset_uri =  $(container).closest('.result-page').data('asset_uri');
				setTimeout( function(){
					$.getScript( asset_uri + "/js/slzexploore-core-multi-maps.js", function(){} );
				}, 500 );
				var map_content = $(container).find('.map-block-ajax').clone();
				$(container).closest('.sc-map-location').find('.map-block-wrapper').html(map_content);
				$(container).find('.map-block-ajax').remove();
			}
			$('.car-rent-result-main > .loading').show().fadeOut();
			$.slzexploore_car_ajax_pagination();
			$.slzexploore_result_found();
			$.slzexploore_reset_search_form();
		});
	}
	$.slzexploore_car_search = function(){
		$('.result-page .car-template .content-widget button[type="submit"]').unbind("click");
		$('.result-page .car-template .content-widget button[type="submit"]').on('click', function(e){
			e.preventDefault();
			var container  = $(this).closest('.result-body');
			var data = $(this).closest('form').serializeArray();
			slzexploore_reload_car( container, data );
		});
		if ($('.location-widget').length > 0){
			$('.location-widget .content-widget input[name="location"], .category-widget .content-widget input[name="category"], .rating-widget .content-widget input[name="rating"]', $('.find-car.result-page')).on('change', function(e){
				e.preventDefault();
				var container  = $(this).closest('.result-body');
				var data = $(this).closest('form').serializeArray();
				slzexploore_reload_car( container, data );
			});
			$('.location-widget input[name="location"], .category-widget input[name="category"], .rating-widget input[name="rating"]', $('.find-car:not(".result-page")')).on('change', function(e){
				e.preventDefault();
				$(this).closest('.find-car').find('.find-widget button[type="submit"]').trigger('click');
			});
		}
	};
	/* End Car archive page */

	/* 
	 * Cruises archive page
	 */
	/* pagination with ajax */
	$.slzexploore_cruises_ajax_pagination = function() {
		if ($('.cruises-result-main').length > 0){
			$('.cruises-result-main nav.pagination-list.paging-ajax ul li a').unbind("click");
			$('.cruises-result-main nav.pagination-list.paging-ajax ul li a').on('click', function(e){
				e.preventDefault();
				var container  = $(this).closest('.result-body');
				$('.cruises-result-main > .loading').show().fadeIn();
				$.slzexploore_scroll_to( container );
				var page = parseInt( $(this).data('page') );
				var base = $(this).closest('.paging-ajax').parent().find('.slz-pagination-base').data('base');
				var atts = $(this).closest('.paging-ajax').parent().find('.pagination-json').attr('data-json');
				var data_search = $(this).closest('.paging-ajax').parent().find('.pagination-json').attr('data-search');
				var data = {"page":page, "base":base, "atts":jQuery.parseJSON(atts), "data":jQuery.parseJSON(data_search) };
				$.fn.Form.ajax(['top.Top_Controller', 'ajax_cruises_pagination'], data, function(res) {
					$('.cruises-result-content', container).html(res);
					$('.cruises-result-main > .loading').show().fadeOut();
					$.slzexploore_result_found();
					$.slzexploore_reset_search_form();
					$.slzexploore_cruises_ajax_pagination();
				});
			});
		}
	};
	/* Cruises search form */
	function slzexploore_reload_cruises( container, data ){
		// clear sort
		$('.result-meta').find('.result-filter select.selectbox').next('.sbHolder').each(function() {
			var def_val = $('.sbOptions li:first-child a', $(this) ).html();
			$('.sbSelector', $(this) ).html( def_val );
		});
		$('.cruises-result-main > .loading').show().fadeIn();
		$.slzexploore_scroll_to( container );
		$('.slz-search-widget .widget .content-widget').each(function() {
			slzexploore_show_reset_btn( $(this) );
		});
		var base = $('.slz-pagination-base', container).data('base');
		var attr_more =  $('.cruise-map-search', container).data('attr');
		$.fn.Form.ajax(['top.Top_Controller', 'ajax_search_cruise'], {'data' : data, 'base' : base ,'atts_more': attr_more }, function(res) {
			$(container).closest('.sc-map-location').find('.map-block').remove();
			$('.cruises-result-content', container).html(res);
			if($('.map-block-ajax',container)[0]){
				var asset_uri =  $(container).closest('.result-page').data('asset_uri');
				setTimeout( function(){
					$.getScript( asset_uri + "/js/slzexploore-core-multi-maps.js", function(){} );
				}, 500 );
				var map_content = $(container).find('.map-block-ajax').clone();
				$(container).closest('.sc-map-location').find('.map-block-wrapper').html(map_content);
				$(container).find('.map-block-ajax').remove();
			}
			$('.cruises-result-main > .loading').show().fadeOut();
			$.slzexploore_cruises_ajax_pagination();
			$.slzexploore_result_found();
			$.slzexploore_reset_search_form();
		});
	}
	$.slzexploore_cruises_search = function(){
		$('.result-page .cruise-template .content-widget button[type="submit"]').unbind("click");
		$('.result-page .cruise-template .content-widget button[type="submit"]').on('click', function(e){
			e.preventDefault();
			var container  = $(this).closest('.result-body');
			var data = $(this).closest('form').serializeArray();
			slzexploore_reload_cruises( container, data );
		});
		if ($('.find-cruise').length > 0){
			$('.location-widget .content-widget input[name="location"], .category-widget .content-widget input[name="category"], .facility-widget .content-widget input[name="facility"], .rating-widget .content-widget input[name="rating"], .star-rating-widget .content-widget input[name="star_rating"]', $('.find-cruise.result-page')).on('change', function(e){
				e.preventDefault();
				var container  = $(this).closest('.result-body');
				var data = $(this).closest('form').serializeArray();
				slzexploore_reload_cruises( container, data );
			});
			$('.location-widget .content-widget input[name="location"], .category-widget .content-widget input[name="category"], .facility-widget .content-widget input[name="facility"], .rating-widget .content-widget input[name="rating"], .star-rating-widget .content-widget input[name="star_rating"]', $('.find-cruise:not(".result-page")')).on('change', function(e){
				e.preventDefault();
				$(this).closest('.find-cruise').find('.find-widget button[type="submit"]').trigger('click');
			});
		}
	};
	/* End Cruises archive page */

	$.slzexploore_reset_search_form = function() {
		$('.result-body .result-meta .result-count-wrapper a.btn-reset-all').unbind("click");
		$('.result-body .result-meta .result-count-wrapper a.btn-reset-all').on('click', function(e){
			e.preventDefault();
			var container =  $(this).closest('.result-body').find('.slz-search-widget');
			slzexploore_reset_form( container );
			if( container.hasClass('result-page') ){
				$('.find-widget button[type="submit"]', container ).trigger('click');
			}
		});
		$('.result-body .result-meta .result-count-wrapper span i').unbind("click");
		$('.result-body .result-meta .result-count-wrapper span i').on('click', function(e){
			e.preventDefault();
			var name = $(this).parent().attr('class');
			var container = $(this).closest('.result-body').find('.slz-search-widget');
			var input_wrapper = $('input[name="'+name+'"], select[name="'+name+'"]', container).parent();
			slzexploore_reset_form( input_wrapper );
			if( container.hasClass('result-page') ){
				$('.find-widget button[type="submit"]', container ).trigger('click');
			}
		});
		$('.slz-search-widget .widget .content-widget a.btn-reset').unbind("click");
		$('.slz-search-widget .widget .content-widget a.btn-reset').on('click', function(e){
			e.preventDefault();
			slzexploore_reset_form( $(this).closest('.content-widget') );
			if( $(this).closest('.slz-shortcode').hasClass('result-page') ){
				$('.slz-search-widget .find-widget button[type="submit"]' ).trigger('click');
			}
		});
	}
	function slzexploore_show_reset_btn( parent ){
		var show_button = false;
		if( $('input[type="text"], select.selectbox', parent).length ){
			$('input[type="text"], select.selectbox', parent).each(function() {
				if( $(this).val() ){
					show_button = true;
					return false;
				}
			});
		}
		if( $('input[type="checkbox"]', parent).length && $('input[type="checkbox"]', parent).is(':checked') ){
			show_button = true;
		}
		if( $('.price-wrapper', parent).length ){
			var min_val = $('.price-wrapper .nstSlider', parent).data('range_min');
			var max_val = $('.price-wrapper .nstSlider', parent).data('range_max');
			var price   = $('.price-wrapper input.sliderValue', parent).val().split(",");
			if( price[0] != min_val || price[1] != max_val ){
				show_button = true;
			}
		}
		if( show_button ){
			$('a.btn-reset', parent).removeClass('hide');
		}
		else{
			$('a.btn-reset', parent).addClass('hide');
		}
	}
	function slzexploore_reset_form( parent ){
		$('input[type="text"]', parent).val('');
		$('input[type="checkbox"]', parent).removeAttr('checked');
		$("select.selectbox", parent).selectbox("detach");
		$("select.selectbox", parent).val('');
		$("select.selectbox", parent).selectbox("attach");
		$("select.select2", parent).val('').trigger('change');
		$('input[type="number"]', parent).each(function() {
			var min_value = parseInt( $(this).attr('min') );
			$(this).val( min_value );
		});
		if( $('.nstSlider', parent).length ){
			var range_min = $('.nstSlider', parent).data('range_min');
			var range_max = $('.nstSlider', parent).data('range_max');
			$('.nstSlider', parent).nstSlider("set_position", range_min, range_max );
		}
	}

	$.slzexploore_image_regions = function() {
		$('.a-fact-image-wrapper').each(function(){
			var height_img = $(this).find('.a-fact-image').height();
			$(this).height(height_img);
		});
	}

	$.slzexploore_cf7_custom_error_message = function() {
		if( $('form.wpcf7-form').hasClass('invalid') ){
			$('form.wpcf7-form .wpcf7-not-valid-tip').each(function() {
				var field_name = $(this).parent().find('.form-input').attr('placeholder');
				var message = $(this).html().replace( '[field_name]', field_name );
				$(this).html( message );
			});
		}
	}

    $.slzexploore_tour_schedule = function () {
        $(".timeline-block .timeline-custom-col.image-col").each(function(){
            $(this).slick({
                rtl: slzcore_flag_rtl,
            });
        });
    };


})(jQuery);

jQuery( document ).ready( function() {
	new WOW().init();
	jQuery.slzexploore_expand_event();
	jQuery.slzexploore_tabs();
	jQuery.slzexploore_item_list_style4();
	jQuery.slzexploore_blog();
	jQuery.slzexploore_partner();
	jQuery.slzexploore_recent_news();
	jQuery.slzexploore_accommodation_ajax_pagination();
	jQuery.slzexploore_nstSlider();
	jQuery.slzexploore_datepicker();
	jQuery.slzexploore_search_accommodation();
	jQuery.slzexploore_result_sort_filter();
	jQuery.slzexploore_result_found();
	jQuery.slzexploore_partner_style2();
	jQuery.slzexploore_carousel();
	jQuery.slzexploore_teamcarousel();
	jQuery.slzexploore_tour_carousel();
	jQuery.slzexploore_tour_ajax_pagination();
	jQuery.slzexploore_discount_box_style1();
	jQuery.slzexploore_search_tour();
	jQuery.slzexploore_search_tab_active();
	jQuery.slzexploore_gallery_image();
	jQuery.slzexploore_teamsingle();
	jQuery.slzexploore_room_type();
	jQuery.slzexploore_testimonial();
	jQuery.slzexploore_add_tour_wish_list();
	jQuery.slzexploore_add_hotel_wish_list();
	jQuery.slzexploore_contact_button();
	jQuery.slzexploore_star_rating();
	jQuery.slzexploore_faq();
	jQuery.slzexploore_toggle_box();
	jQuery.slzexploore_car_ajax_pagination();
	jQuery.slzexploore_car_search();
	jQuery.slzexploore_cruises_ajax_pagination();
	jQuery.slzexploore_cruises_search();
	jQuery.slzexploore_accordion();
	jQuery.slzexploore_reset_search_form();
	jQuery.slzexploore_image_regions();
    jQuery.slzexploore_tour_schedule();
});
jQuery( window ).load( function() {
});