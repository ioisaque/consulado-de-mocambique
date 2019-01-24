(function($) {
	"use strict";

	$.slzexploore_cf7_booking = function(){ 
		
		//Contact Form 7 AJAX Callback
		$(".slz-booking-from .wpcf7").on('wpcf7:mailsent', function(event){
		 	$('#booking-form').modal('toggle');
		}); 
		$('.cf7-book').click(function(){
			var data = [], element = {};
			if($(this).length){
				var post_type = $(this).data('type');
				element.id =  $(this).data('id');
				element.post_type =  post_type;
				if( post_type == 'accommodation'){
					element.room_type =  $(this).data('roomtype');
				}
				if( post_type == 'cruise'){
					element.cabin_id =  $(this).data('cabin');
				}
				data.push(element);
			};
			var that = $(this);
			$.fn.Form.ajax(['booking.Booking_Controller', 'cf7_ajax_book'], data, function(res) {
				var data_json = $('.cf7-book').data('json');
				var cf7_js_uri = $('#booking-form').find('.cf7_js_uri').html();
				$('#booking-form').find('.cf7_booking_name').val(data_json.name);
				$('#booking-form').find('.cf7_booking_url').val(data_json.url);
				$('#booking-form').find('.cf7_booking_cc').val(data_json.mail_cc);
				$('#booking-form').find('.cf7_booking_bcc').val(data_json.mail_bcc);
				console.log(data_json);
				if( cf7_js_uri ) {
					$.getScript( cf7_js_uri, function(){} );
				}
			});
		});
	};
	/* Booking Hotel */
	$.slzexploore_hotel_booking = function() {
		// Book room button click event
		if ($('.overview-block').length > 0){
			if($('.overview-block .timeline-hotel-view .timeline-content .timeline-book-block').length){
				$('.overview-block .timeline-hotel-view .hotels-layout .btn-book-tour').on('click', function(e){
					e.preventDefault();
					var container = $(this).closest('.timeline-content');
					if( $('.timeline-book-block', container).hasClass('show-book-block') ){
						$('.timeline-book-block', container).toggleClass('show-book-block');
						$('.timeline-book-block', container).html('');
					}
					else{
						var id = $(this).data('id');
						var parent = $(this).parent();
						$(this).parent().append('<i class="fa fa-spinner fa-spin"></i>');
						$.fn.Form.ajax(['booking.Booking_Controller', 'ajax_hotel_booking_form'], { 'id' : id }, function(res) {
							$('.timeline-book-block', container ).html( res );
							$('.timeline-book-block', container).toggleClass('show-book-block');
							slzexploore_room_booking_form($('.timeline-book-block .hotel-booking', container));
							$('.fa-spinner', parent).remove();
						});
					}
				});
			}
		}
	}
	function slzexploore_cal_hotel_summary( container ){
		var obj_hotel_booking  = $('.timeline-book-block .hotel-booking', container);
		if (obj_hotel_booking){
			$('.timeline-book-block .find-widget .loading', container).show().fadeIn();
			var room_id   = $('.slz-btn-group button.check-booking', container).data('id');
			var post_data 	 = [{ name : 'room_id', value : room_id}];
			var booking_data = $('.booking-data input, .booking-data select', obj_hotel_booking).serializeArray();
			$('.extra-item table td.number-item select', obj_hotel_booking).each(function() {
				if( $(this).val() != 0 ){
					post_data.push({ name : $(this).attr('name'), value : $(this).val()});
				}
			});
			var extra_data   = $('.extra-item table td.number-item select', obj_hotel_booking).serializeArray();
			post_data = $.merge( post_data, booking_data );
			$.fn.Form.ajax(['booking.Booking_Controller', 'ajax_calculate_hotel_summary'], post_data, function(res) {
				var res_data = jQuery.parseJSON(res);
				$('.summary table td.booking-price span', obj_hotel_booking).html( res_data['booking_total'] );
				$('.summary table td.extra-total span', obj_hotel_booking).html( res_data['extra_total'] );
				$('.summary table td.total-price span', obj_hotel_booking).html( res_data['total'] );
				$('.timeline-book-block .find-widget .loading', container).show().fadeOut();
			});
		}
		
	}
	function slzexploore_hotel_booking_load_js( obj_hotel_booking ) {
		var allow_date = $('.input-daterange', obj_hotel_booking).attr('data-allow-date');
		var datepicker_options = {
							startDate: '-0d',
							format: 'yyyy-mm-dd',
							autoclose : true,
							maxViewMode: 0
						};
		if( allow_date != '' ){
			datepicker_options = {
					startDate: '-0d',
					format: 'yyyy-mm-dd',
					autoclose : true,
					maxViewMode: 0,
					beforeShowDay:  function (date) {
						var yr      = date.getFullYear();
						var month   = date.getMonth() + 1;
						var day     = date.getDate();
					    month       = month < 10 ? '0' + month : month;
					    day         = day  < 10 ? '0' + day  : day;
					    var check_date = yr.toString() + month.toString() + day.toString();
			            if( allow_date.indexOf(check_date) != '-1' ){
			               return;
			            }
			            return {
			                  enabled : false
			               };
				    }
				};
		}
		$('.input-daterange', obj_hotel_booking).datepicker(datepicker_options);
		
		var asset_uri = $('.find-hotel-widget .slz-asset-uri').html();
		$.getScript( asset_uri + "/libs/plus-minus-input/plus-minus-input.js", function() {
			$('.find-hotel-widget .hotel-booking .slz-asset-uri').remove();
		});
		$('.number-item select.selectbox', obj_hotel_booking).selectbox();
		$('.booking-data select[name="deposit_method"]', obj_hotel_booking).selectbox();
	}
	function slzexploore_room_booking_form( obj_hotel_booking ) {
		// reload js
		slzexploore_hotel_booking_load_js( obj_hotel_booking );

		// deposit booking
		$.slzexploore_deposit_booking();

		// Calculate Summary when change input value
		if(obj_hotel_booking){
			$('.booking-data input[name="number_room"]', obj_hotel_booking).unbind("change");
			$('.booking-data input[name="number_room"], .booking-data input[name="adults"], .booking-data input[name="children"], .booking-data input[name="check_in_date"], .booking-data input[name="check_out_date"], .extra-item table td.number-item select, .booking-data input[name="is_person_price"], .booking-data input[name="infant"]', obj_hotel_booking).on('change', function(e){
				e.preventDefault();
				slzexploore_cal_hotel_summary( $(this).closest('.timeline-content') );
			});
			$('.booking-data input[name="check_in_date"], .booking-data input[name="check_out_date"]', obj_hotel_booking).on('change', function(e){
				e.preventDefault();
				var container = $(this).closest('.slz-booking-wrapper');
				var room_id   = $('.slz-btn-group button.check-booking', container).data('id');
				var check_in_date  = $('.booking-data input[name="check_in_date"]', container).val();
				var check_out_date = $('.booking-data input[name="check_out_date"]', container).val();
				var data = { 'room_id' : room_id, 'check_in_date' : check_in_date, 'check_out_date' : check_out_date };
				$.fn.Form.ajax(['booking.Booking_Controller', 'ajax_check_hotel_available'], data, function(res) {
					$('.hotel-booking-info tr td.room_number', container).html( res );
				});
			});
			$('.booking-data input[name="check_in_date"]', obj_hotel_booking).trigger('change');
			
			// Check Now button
			$('button.check-booking', obj_hotel_booking).unbind("click");
			$('button.check-booking', obj_hotel_booking).on('click', function(e){
				e.preventDefault();
				var $this = $(this);
				var container = $(this).closest('.find-widget');
				$('.loading', container).show().fadeIn();
				var id = $(this).data('id');
				var data = $('.hotel-booking .booking-data',container).serializeArray();
				$.fn.Form.ajax(['booking.Booking_Controller', 'ajax_check_hotel_booking'], {'data':data, 'id':id }, function(res) {
					if( res == 'success' ){
						slzexploore_hotel_booking_total( $this.closest('.hotel-booking') );
						if( $this.hasClass('slz-add-to-cart') ){
							slzexploore_add_hotel_to_cart( $this.closest('.hotel-booking') );
						}
						else{
							$this.closest('.hotel-booking').find('.err-message').addClass('hide');
							$this.closest('.hotel-booking').find('.booking-data').addClass('hide');
							$this.closest('.hotel-booking').find('.extra-item').addClass('hide');
							$this.closest('.hotel-booking').find('.booking-total').removeClass('hide');
							$this.closest('.hotel-booking').find('.customer-info').removeClass('hide');
							$this.parent().find('.btn-next').removeClass('hide');
							$this.parent().find('.btn-back').removeClass('hide');
							$this.addClass('hide');
							$('.loading', container).show().fadeOut();
						}
					}
					else{
						$('.err-message', container).html(res).removeClass('hide');
						$('.loading', container).show().fadeOut();
					}
				});
			});
			// Back to form input booking info
			$('button.btn-back', obj_hotel_booking).on('click', function(e){
				$.slzexploore_scroll_to( $(this).closest('.hotel-booking') );
				$(this).closest('.hotel-booking').find('.booking-data').removeClass('hide');
				$(this).closest('.hotel-booking').find('.extra-item').removeClass('hide');
				$(this).closest('.hotel-booking').find('.booking-total').addClass('hide');
				$(this).closest('.hotel-booking').find('.customer-info').addClass('hide');
				$(this).closest('.hotel-booking').find('.success-message span').html('');
				$(this).closest('.hotel-booking').find('.success-message').addClass('hide');
				$(this).parent().find('.btn-next').addClass('hide');
				$(this).parent().find('.check-booking').removeClass('hide');
				$(this).addClass('hide');
			});
			// book cruise and save info
			$('button.btn-next', obj_hotel_booking).on('click', function(e){
				e.preventDefault();
				var $this = $(this);
				var container = $(this).closest('.hotel-booking');
				var widget    = $(this).closest('.find-widget')
				
				if( !slzexploore_validate_booking_form( container ) ){
					return false;
				}
				// end validate
				
				$('.loading', widget).show().fadeIn();
				var room_id		= $(this).data('id');
				var room_price	= $('.hotel-booking-info table td.room_price span', container).html();
				var extra_price = '';
				var description = '';
				if( $('.extra-item', container).length ){
					extra_price = $('.summary table td.extra-total span', container).html();
					description = $('.extra-item .booking-des', container).html();
				}
				var total 		= $('.summary table td.total-price span', container).html();
				var post_data 	= [{ name : 'room_type', value : room_id},
									{ name : 'room_price', value : room_price},
									{ name : 'extra_price', value : extra_price},
									{ name : 'total', value : total},
									{ name : 'description', value : description}];
				var book_data 	= $('.booking-data',container).serializeArray();
				var cus_data	= $('.customer-info',container).serializeArray();
				post_data = $.merge( $.merge( post_data, book_data ), cus_data );
				$.fn.Form.ajax(['booking.Booking_Controller', 'ajax_book_hotel'], post_data, function(res) {
					if( res.indexOf("[SUCCESS]") != -1 ){
						var booking_id = res.replace("[SUCCESS]", "");
						$('.customer-info', container).addClass('hide');
						$('.err-message', container ).html('').addClass('hide');
						$('.success-message', container).removeClass('hide');
						$('.success-message span', container).html( booking_id );
						$.slzexploore_scroll_to( container );
						$this.addClass('hide');
					}
					else if( res.indexOf("[REDIRECT]") != -1 ){
						var redirect_page = res.replace("[REDIRECT]", "");
						window.location = redirect_page;
					}
					else{
						$('.err-message', container).html(res).removeClass('hide');
						$('.success-message span', container).html('');
						$('.success-message', container).addClass('hide');
					}
					$('.loading', widget).show().fadeOut();
				});
			});
		}
		
	}
	function slzexploore_hotel_booking_total( parent ){
		var check_in    = $('.booking-data input[name="check_in_date"]', parent).val();
		var check_out   = $('.booking-data input[name="check_out_date"]', parent).val();
		var number      = $('.booking-data input[name="number_room"]', parent).val();
		var adults      = $('.booking-data input[name="adults"]', parent).val();
		var children    = $('.booking-data input[name="children"]', parent).val();
		var infant      = $('.booking-data input[name="infant"]', parent).val();
		var total       = $('.summary td.booking-price', parent).html();
		if ($('.booking-total').length > 0){
			$('.booking-total table td.check-in', parent).html( check_in );
			$('.booking-total table td.check-out', parent).html( check_out );
			$('.booking-total table td.number', parent).html( number );
			$('.booking-total table td.adults', parent).html( adults );
			$('.booking-total table td.children', parent).html( children + ' / ' + infant );
			$('.booking-total table td.total', parent).html( total );
			
			$('.booking-total .extra-item-total', parent).addClass( 'hide' );
			$('.booking-total .extra-item-total table tr.extra', parent).remove();
		}

		if( $('.extra-item', parent).length ){
			var description = '';
			$('.extra-item table td select.quantity', parent).each(function() {
				if( $(this).val() != 0 ){
					$('.booking-total .extra-item-total', parent).removeClass( 'hide' );
					var item_name = $(this).closest('tr').find('td.item-info h5').html();
					var item_price_text = $(this).closest('tr').find('td.item-price').html();
					var item_price = $(this).closest('tr').find('td.item-price span').html();
					var item_total = parseInt( $(this).val() ) * parseInt( item_price );
					var item_number = parseInt( $(this).val() );
					var is_persons = '';
					if( $(this).closest('tr').find('td.is-person span').html() ){
						is_persons =  parseInt( adults ) + parseInt( children );
						item_total *= is_persons;
						item_number *= is_persons;
					}
					else{
						is_persons = number;
						item_total *= number;
						item_number *= number;
					}
					var is_days = '';
					if( $(this).closest('tr').find('td.is-day span').html() ){
						var start_date = new Date(check_in);
						var return_date = new Date(check_out);
						is_days = Math.abs(return_date - start_date) / 86400000;
						if( is_days == 0 ) {
							is_days = 1;
						}
						item_total *= parseInt( is_days );
						item_number *= parseInt( is_days );
					}
					item_total = slzexploore_formatNumber( item_total );
					item_total = item_price_text.replace(/<span>[\s\S]*?<\/span>/, '<span>' + item_total + '<\/span>');
					var item_row = '<tr class="extra">'+
										'<td class="name">'+ item_name +'</td>'+
										'<td class="price">'+ item_price_text +'</td>'+
										'<td class="person">'+ is_persons +'</td>'+
										'<td class="day">'+ is_days +'</td>'+
										'<td class="quantity">'+ $(this).val() +'</td>'+
										'<td class="total">'+ item_total +'</td>'+
									'</tr>';
					$('.booking-total .extra-item-total table tbody', parent).append( item_row );
					description += item_name + ' : ' + item_price_text + ' x ' + item_number + ', ';
				}
			});
			$('.extra-item .booking-des', parent).html( description );
		}
	}
	// Check out by Woocommerce
	function slzexploore_add_hotel_to_cart( container ){
		var room_id		= $('.btn.check-booking', container).data('id');
		var price 		= $('.summary table td.booking-price span', container).html();
		var extra_price = $('.summary table td.extra-total span', container).html();
		var description = '';
		if( extra_price ){
			description = $('.extra-item .booking-des', container).html();
		}
		var total_price = $('.summary table td.total-price span', container).html();
		var post_data 	= [{ name : 'room_type', value : room_id},
							{ name : 'room_price', value : price},
							{ name : 'extra_price', value : extra_price},
							{ name : 'total', value : total_price},
							{ name : 'description', value : description}];
		var book_data 	= $('.booking-data',container).serializeArray();
		post_data 		= $.merge( post_data, book_data );
		
		$.fn.Form.ajax(['booking.Booking_Controller', 'ajax_add_hotel_to_cart'], post_data, function(res) {
			if( res.indexOf("[SUCCESS]") != -1 ){
				var wc_CartPageUrl = res.replace("[SUCCESS]", "");
				top.location.href = wc_CartPageUrl;
			}
			else{
				console.log(res);
			}
		});
	}
	// validate form
	function slzexploore_validate_booking_form( container ){
		// Validate form
		$('.validate-message', container).addClass('hide');
		$('.invalid-message', container).addClass('hide');
		var isError = false;
		$('.tb-input', container).each(function() {
			if( $(this).val().trim() == '' && $(this).attr('name').trim() != 'customer_des' ){
				$(this).closest('.text-box-wrapper').find('.validate-message').removeClass('hide');
				$(this).focus();
				isError = true;
				return false;
			}
		});
		if( !isError && $(".email .tb-input", container).length ){
			var emailRegex = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/;
			if( !$(".email .tb-input", container).val().match(emailRegex) ){
				$(".email .invalid-message", container).removeClass('hide');
				$(".email .tb-input", container).focus();
				isError  = true;
			}
		}
		if(isError){
			return false;
		}
		return true;
	}

	/* Booking Tour */
	$.slzexploore_tour_booking = function() {
		if($('.tour-view-main .timeline-book-block').length){
			// show/hide booking form
			$('.tour-view-main .slz-book-tour a.btn-book').on('click', function(e){
				e.preventDefault();
				$(this).parents('.tour-view-main').find('.slz-booking-block').toggleClass('show-book-block');
			});
			
			var obj_tour_booking = $('.tour-view-main .slz-booking-block .tour-booking');

			// Load datepicker in tour booking form
			slzexploore_tour_load_datepicker( obj_tour_booking );

			// Calculate Summary when change input value
			$('.booking-data input[name="adults"], .booking-data input[name="children"], .booking-data input[name="infant"], .extra-item table td.number-item select', obj_tour_booking ).on('change', function(e){
				e.preventDefault();
				slzexploore_cal_tour_summary();
			});
			
			// Calculate availabel when change input value
			$('.booking-data input[name="start_date"]', obj_tour_booking ).on('change', function(e){
				e.preventDefault();
				slzexploore_cal_tour_summary();
				var container = $(this).closest('.slz-booking-wrapper');
				var tour_id = $('.slz-btn-group button.btn-check', obj_tour_booking).data('id');
				var data = { 'tour_id' : tour_id, 'tour_date' : $(this).val() };
				$.fn.Form.ajax(['booking.Booking_Controller', 'ajax_check_tour_available'], data, function(res) {
					$('.tour-info tr td.availabel', container).html( res );
				});
			});
			$('.booking-data input[name="start_date"]', obj_tour_booking ).trigger('change');
			
			// Check Booking
			$('.slz-btn-group button.btn-check', obj_tour_booking).on('click', function(e){
				e.preventDefault();
				var $this = $(this);
				var container = $(this).closest('.find-widget');
				$('.loading', container).show().fadeIn();
				var data = $('.tour-booking .booking-data',container).serializeArray();
				data.push({ 'name' : 'tour_id', 'value' : $(this).data('id') });
				$.fn.Form.ajax(['booking.Booking_Controller', 'ajax_check_tour_booking'], data, function(res) {
					if( res == 'success' ){
						slzexploore_tour_booking_info( $this.closest('.tour-booking') );
						if( $this.hasClass('slz-add-to-cart') ){
							slzexploore_add_tour_to_cart( $this.closest('.tour-booking') );
						}
						else{
							$this.closest('.tour-booking').find('.err-message').addClass('hide');
							$this.closest('.tour-booking').find('.booking-data').addClass('hide');
							$this.closest('.tour-booking').find('.extra-item').addClass('hide');
							$this.closest('.tour-booking').find('.booking-total').removeClass('hide');
							$this.closest('.tour-booking').find('.customer-info').removeClass('hide');
							$this.parent().find('.btn-next').removeClass('hide');
							$this.parent().find('.btn-back').removeClass('hide');
							$this.addClass('hide');
							$('.loading', container).show().fadeOut();
						}
					}
					else{
						$this.closest('.tour-booking').find('.err-message').html(res).removeClass('hide');
						$('.loading', container).show().fadeOut();
					}
				});
			});
			// Back to form input booking info
			$('.slz-btn-group button.btn-back', obj_tour_booking).on('click', function(e){
				$.slzexploore_scroll_to( $(this).closest('.slz-booking-block') );
				$(this).closest('.tour-booking').find('.booking-data').removeClass('hide');
				$(this).closest('.tour-booking').find('.extra-item').removeClass('hide');
				$(this).closest('.tour-booking').find('.booking-total').addClass('hide');
				$(this).closest('.tour-booking').find('.customer-info').addClass('hide');
				$(this).closest('.tour-booking').find('.success-message span').html('');
				$(this).closest('.tour-booking').find('.success-message').addClass('hide');
				$(this).parent().find('.btn-next').addClass('hide');
				$(this).parent().find('.btn-check').removeClass('hide');
				$(this).addClass('hide');
			});
			// book tour and save info
			$('.slz-btn-group button.btn-next', obj_tour_booking).on('click', function(e){
				e.preventDefault();
				var $this = $(this);
				var container = $(this).closest('.tour-booking');
				var widget    = $(this).closest('.find-widget')
				
				if( !slzexploore_validate_booking_form( $('.customer-info',container) ) ){
					return false;
				}
				// end validate
			
				$('.loading', widget).show().fadeIn();
				var tour_id		= $(this).data('id');
				var tour_price	= $('.summary table td.sub-total span', container).html();
				var extra_price = '';
				var description = '';
				if( $('.extra-item', container).length ){
					extra_price = $('.summary table td.extra-total span', container).html();
					description = $('.extra-item .booking-des', container).html();
				}
				var total_price	= $('.summary table td.total-price span', container).html();
				var post_data 	= [{ name : 'tour', value : tour_id},
									{ name : 'tour_price', value : tour_price},
									{ name : 'extra_price', value : extra_price},
									{ name : 'total_price', value : total_price},
									{ name : 'description', value : description}];
				var book_data 	= $('.booking-data',container).serializeArray();
				var data		= $('.customer-info',container).serializeArray();
				post_data = $.merge( $.merge( post_data, book_data ), data ); 
				
				$.fn.Form.ajax(['booking.Booking_Controller', 'ajax_book_tour'], post_data, function(res) {
					
					if( res.indexOf("[SUCCESS]") != -1 ){
						var booking_id = res.replace("[SUCCESS]", "");
						$('.customer-info', container).addClass('hide');
						$('.err-message', container ).html('').addClass('hide');
						$('.success-message', container).removeClass('hide');
						$('.success-message span', container).html( booking_id );
						$('.booking-data input[name="start_date"]', container ).trigger('change');
						$.slzexploore_scroll_to( container );
						$this.addClass('hide');
					}
					else if( res.indexOf("[REDIRECT]") != -1 ){
						var redirect_page = res.replace("[REDIRECT]", "");
						window.location = redirect_page;
					}
					else{
						$('.err-message', container).html(res).removeClass('hide');
						$('.success-message span', container).html('');
						$('.success-message', container).addClass('hide');
					}
					$('.loading', widget).show().fadeOut();
				});
			});
		}
	}
	function slzexploore_tour_load_datepicker( obj_tour_booking ) {
		var options = $('.booking-data .tour-datepicker-options', obj_tour_booking).attr('data-options');
		options = jQuery.parseJSON(options);
		var datepicker_options = {
							startDate: '-0d',
							format: 'yyyy-mm-dd',
							autoclose : true,
							maxViewMode: 0
						};
		if( options['type'] == 'weekly' ){
			datepicker_options = {
							startDate: '-0d',
							format: 'yyyy-mm-dd',
							daysOfWeekDisabled: options['value'].replace("7", "0"),
							autoclose : true,
							maxViewMode: 0
						};
		}
		else if( options['type'] == 'monthly' ){
			datepicker_options = {
							startDate: '-0d',
							format: 'yyyy-mm-dd',
							autoclose : true,
							maxViewMode: 0,
							beforeShowDay:  function (date) {
								var day     = date.getDate();
							    day         = day  < 10 ? '0' + day  : day;
					            if( options['value'].indexOf(day.toString()) != '-1' || options['value'] == date.getDate() ){
					               return;
					            }
					            return {
					                  enabled : false
					               };
						    }
						};
		}
		else if( options['type'] == 'season' ){
			datepicker_options = {
							startDate: options['start_date'],
							endDate: options['end_date'],
							format: 'yyyy-mm-dd',
							autoclose : true,
							maxViewMode: 0
						};
		}
		$('.text-box-wrapper .tb-input.datepicker', obj_tour_booking).datepicker(datepicker_options);
	}
	function slzexploore_cal_tour_summary(){
		if ( $('.tour-view-main .slz-booking-block .tour-booking').length > 0 ){
			var obj_tour_booking  = $('.tour-view-main .slz-booking-block .tour-booking');
			$('.loading', obj_tour_booking.parent()).show().fadeIn();
			var tour_id      = $('.slz-btn-group button.btn-check', obj_tour_booking).data('id');
			var post_data 	 = [{ name : 'tour_id', value : tour_id}];
			var booking_data = $('.booking-data input, .booking-data select', obj_tour_booking).serializeArray();
			var extra_data   = $('.extra-item table td.number-item select', obj_tour_booking).serializeArray();
			post_data        = $.merge( $.merge( post_data, booking_data ), extra_data );
			$.fn.Form.ajax(['booking.Booking_Controller', 'ajax_calculate_tour_summary'], post_data, function(res) {
				var res_data = jQuery.parseJSON(res);
				$('.summary table td.sub-total span', obj_tour_booking).html( res_data['booking_total'] );
				$('.summary table td.extra-total span', obj_tour_booking).html( res_data['extra_total'] );
				$('.summary table td.total-price span', obj_tour_booking).html( res_data['total'] );
				$('.loading', obj_tour_booking.parent()).show().fadeOut();
			});
		}
	}
	function slzexploore_tour_booking_info( parent ){
		var name           = $('.tour-info .name', parent).html();
		var adults         = $('.booking-data input[name="adults"]', parent).val();
		var children       = $('.booking-data input[name="children"]', parent).val();
		var adults_price   = $('.tour-info .price-adult', parent).html();
		var children_price = $('.tour-info .price-child', parent).html();
		var start_date     = $('.booking-data input[name="start_date"]', parent).val();
		var subtotal       = $('.summary td.sub-total', parent).html();
		var date_title     = $('.tour-info table tr th:nth-child(5)', parent).html();
		var name           = name + "<br>( "+ date_title +": "+start_date +" )";
		
		$('.booking-total table td.name', parent).html( name );
		$('.booking-total table td.price', parent).html( adults_price + '/' + children_price );
		$('.booking-total table td.person', parent).html( adults + '/' + children );
		$('.booking-total table td.quantity', parent).html(1);
		$('.booking-total table td.total', parent).html( subtotal );
		
		$('.booking-total table tr.extra', parent).remove();
		if( $('.extra-item', parent).length ){
			var description = '';
			$('.extra-item table td select.quantity', parent).each(function() {
				if( $(this).val() != 0 ){
					var item_name = $(this).closest('tr').find('td.item-info h5').html();
					var item_price_text = $(this).closest('tr').find('td.item-price').html();
					var item_price = $(this).closest('tr').find('td.item-price span').html();
					var item_total = parseInt( $(this).val() ) * parseInt( item_price );
					var is_persons = 0;
					if( $(this).closest('tr').find('td.is-person span').html() ){
						is_persons =  parseInt( adults ) + parseInt( children );
						item_total *= is_persons;
					}
					item_total = slzexploore_formatNumber( item_total );
					item_total = item_price_text.replace(/<span>[\s\S]*?<\/span>/, '<span>' + item_total + '<\/span>');
					var item_row = '<tr class="extra">'+
										'<td class="name">'+ item_name +'</td>'+
										'<td class="price">'+ item_price_text +'</td>'+
										'<td class="person">'+ is_persons +'</td>'+
										'<td class="quantity">'+ $(this).val() +'</td>'+
										'<td class="total">'+ item_total +'</td>'+
									'</tr>';
					$('.booking-total table tbody', parent).append( item_row );
					var item_number = parseInt( $(this).val() );
					if( parseInt( is_persons ) > 0 ){
						item_number *= parseInt( is_persons );
					}
					description += item_name + ' : ' + item_price_text + ' x ' + item_number + ', ';
				}
			});
			$('.extra-item .booking-des', parent).html( description );
		}
	}
	// Check out by Woocommerce
	function slzexploore_add_tour_to_cart( container ){
		
		var tour_id		= $('.btn.btn-check', container).data('id');
		var price 		= $('.summary table td.sub-total span', container).html();
		var extra_price = $('.summary table td.extra-total span', container).html();
		var description = '';
		if( extra_price ){
			description = $('.extra-item .booking-des', container).html();
		}
		var total_price = $('.summary table td.total-price span', container).html();
		var post_data 	= [{ name : 'tour', value : tour_id},
							{ name : 'tour_price', value : price},
							{ name : 'extra_price', value : extra_price},
							{ name : 'total_price', value : total_price},
							{ name : 'description', value : description}];
		var book_data 	= $('.booking-data',container).serializeArray();
		post_data 		= $.merge( post_data, book_data );
		$.fn.Form.ajax(['booking.Booking_Controller', 'ajax_add_tour_to_cart'], post_data, function(res) {
			if( res.indexOf("[SUCCESS]") != -1 ){
				var wc_CartPageUrl = res.replace("[SUCCESS]", "");
				top.location.href = wc_CartPageUrl;
			}
			else{
				console.log(res);
			}
		});
	}

	/* Car Booking */
	$.slzexploore_car_booking = function() {
		if($('.car-detail-main .timeline-book-block').length){
			// show/hide booking form
			$('.car-detail-main .slz-book-car a.btn-book').on('click', function(e){
				e.preventDefault();
				$(this).parents('.car-detail-main').find('.slz-booking-block').toggleClass('show-book-block');
			});
			// Calculate Summary when load
			slzexploore_cal_car_booking();

			var obj_car_boking = $('.car-detail-main .slz-booking-block .car-booking');

			// load datepicker
			$('.booking-data .input-daterange', obj_car_boking).datepicker({
				startDate: '-0d',
				format: 'yyyy-mm-dd',
				autoclose : true,
				maxViewMode: 0
			});

			// Calculate Summary when change input value
			$('.booking-data input[name="date_from"], .booking-data input[name="date_to"], .booking-data input[name="number"], .extra-item table td.number-item select', obj_car_boking ).on('change', function(e){
				e.preventDefault();
				slzexploore_cal_car_booking();
			});
			$('.booking-data input[name="date_from"], .booking-data input[name="date_to"]', obj_car_boking ).on('change', function(e){
				e.preventDefault();
				var container = $(this).closest('.slz-booking-wrapper');
				var car_id    = $('.slz-btn-group button.btn-check', container).data('id');
				var date_from = $('.booking-data input[name="date_from"]', container).val();
				var date_to   = $('.booking-data input[name="date_to"]', container).val();
				var data = { 'car_id' : car_id, 'date_from' : date_from, 'date_to' : date_to };
				$.fn.Form.ajax(['booking.Booking_Controller', 'ajax_check_car_available'], data, function(res) {
					$('.booking-info tr td.availabel', container).html( res );
				});
			});
			$('.booking-data input[name="date_from"]', obj_car_boking).trigger('change');
			// Check Booking
			$('.car-detail-main .slz-booking-block .car-booking button.btn-check').on('click', function(e){
				e.preventDefault();
				var $this = $(this);
				var container = $(this).closest('.find-widget');
				$('.loading', container).show().fadeIn();
				var id = $(this).data('id');
				var start_date  = $('.car-booking .booking-data input[name="date_from"]', container).val();
				var return_date = $('.car-booking .booking-data input[name="date_to"]', container).val();
				var number      = $('.car-booking .booking-data input[name="number"]', container).val();
				var data = { 'id' : id, 'start_date' : start_date, 'return_date' : return_date, 'number' : number };
				$.fn.Form.ajax(['booking.Booking_Controller', 'ajax_check_car_booking'], data, function(res) {
					if( res == 'success' ){
						slzexploore_car_booking_info( $this.closest('.car-booking') );
						if( $this.hasClass('slz-add-to-cart') ){
							slzexploore_add_car_rent_to_cart( $this.closest('.car-booking') );
						}
						else{
							$this.closest('.car-booking').find('.err-message').addClass('hide');
							$this.closest('.car-booking').find('.booking-data').addClass('hide');
							$this.closest('.car-booking').find('.extra-item').addClass('hide');
							$this.closest('.car-booking').find('.booking-total').removeClass('hide');
							$this.closest('.car-booking').find('.customer-info').removeClass('hide');
							$this.parent().find('.btn-next').removeClass('hide');
							$this.parent().find('.btn-back').removeClass('hide');
							$this.addClass('hide');
							$('.loading', container).show().fadeOut();
						}
					}
					else{
						$this.closest('.car-booking').find('.err-message').html(res).removeClass('hide');
						$('.loading', container).show().fadeOut();
					}
				});
			});
			// Back to form input booking info
			$('.car-detail-main .slz-booking-block .car-booking button.btn-back').on('click', function(e){
				$.slzexploore_scroll_to( $(this).closest('.car-booking') );
				$(this).closest('.car-booking').find('.booking-data').removeClass('hide');
				$(this).closest('.car-booking').find('.extra-item').removeClass('hide');
				$(this).closest('.car-booking').find('.booking-total').addClass('hide');
				$(this).closest('.car-booking').find('.customer-info').addClass('hide');
				$(this).closest('.car-booking').find('.success-message span').html('');
				$(this).closest('.car-booking').find('.success-message').addClass('hide');
				$(this).parent().find('.btn-next').addClass('hide');
				$(this).parent().find('.btn-check').removeClass('hide');
				$(this).addClass('hide');
			});
			// book car and save info
			$('.car-detail-main .slz-booking-block .car-booking button.btn-next').on('click', function(e){
				e.preventDefault();
				var $this = $(this);
				var container = $(this).closest('.car-booking');
				var widget    = $(this).closest('.find-widget')
				
				if( !slzexploore_validate_booking_form( $('.customer-info',container) ) ){
					return false;
				}
				// end validate
			
				$('.loading', widget).show().fadeIn();
				var car_id		= $(this).data('id');
				var price 		= $('.summary table td.booking-price span').html();
				var extra_price = $('.summary table td.extra-total span').html();
				var extra_price = '';
				var description = '';
				if( $('.extra-item', container).length ){
					extra_price = $('.summary table td.extra-total span', container).html();
					description = $('.extra-item .booking-des', container).html();
				}
				var total_price = $('.summary table td.total-price span').html();
				var post_data 	= [{ name : 'car_id', value : car_id},
									{ name : 'price', value : price},
									{ name : 'extra_price', value : extra_price},
									{ name : 'total_price', value : total_price},
									{ name : 'description', value : description}];
				var book_data 	= $('.booking-data',container).serializeArray();
				var data		= $('.customer-info',container).serializeArray();
				post_data = $.merge( $.merge( post_data, book_data ), data ); 
				
				$.fn.Form.ajax(['booking.Booking_Controller', 'ajax_book_car'], post_data, function(res) {
					if( res.indexOf("[SUCCESS]") != -1 ){
						var booking_id = res.replace("[SUCCESS]", "");
						$('.customer-info', container).addClass('hide');
						$('.err-message', container ).html('').addClass('hide');
						$('.success-message', container).removeClass('hide');
						$('.success-message span', container).html( booking_id );
						$('.booking-data input[name="date_from"]', container).trigger('change');
						$.slzexploore_scroll_to( container );
						$this.addClass('hide');
					}
					else if( res.indexOf("[REDIRECT]") != -1 ){
						var redirect_page = res.replace("[REDIRECT]", "");
						window.location = redirect_page;
					}
					else{
						$('.err-message', container).html(res).removeClass('hide');
						$('.success-message span', container).html('');
						$('.success-message', container).addClass('hide');
					}
					$('.loading', widget).show().fadeOut();
				});
			});
		}
	}
	function slzexploore_car_booking_info( parent ){
		var car_name = $('.booking-info .name', parent).html();
		var car_price = $('.booking-info .price', parent).html();
		var booking_price = $('.summary .booking-price', parent).html();
		var booking_number = $('.booking-data input[name="number"]', parent).val();
		var start_date  = $('.booking-data input[name="date_from"]', parent).val();
		var return_date = $('.booking-data input[name="date_to"]', parent).val();
		start_date = new Date(start_date);
		return_date = new Date(return_date);
		var days = Math.abs(return_date - start_date) / 86400000;
		if( days == 0 ) {
			days = 1;
		}
		$('.booking-total table tr.extra', parent).remove();
		$('.booking-total table td.name', parent).html( car_name );
		$('.booking-total table td.price', parent).html( car_price );
		$('.booking-total table td.days', parent).html( days );
		$('.booking-total table td.quantity', parent).html( booking_number );
		$('.booking-total table td.total', parent).html( booking_price );
		var description = '';
		if( $('.extra-item', parent).length ){
			$('.extra-item table td select.quanlity', parent).each(function() {
				if( $(this).val() != 0 ){
					var item_name = $(this).closest('tr').find('td.item-info h5').html();
					var item_price_text = $(this).closest('tr').find('td.item-price').html();
					var item_price = $(this).closest('tr').find('td.item-price span').html();
					var item_total = parseInt( $(this).val() ) * parseInt( item_price );
					var item_number = parseInt( $(this).val() );
					var is_days = '';
					if( $(this).closest('tr').find('td.is-day span').html() ){
						is_days = days;
						item_total *= parseInt( days );
						item_number *= parseInt( days );
					}
					if( booking_number > 1 ){
						item_total *= parseInt( booking_number );
						item_number *= parseInt( booking_number );
					}
					item_total = slzexploore_formatNumber( item_total );
					item_total = item_price_text.replace(/<span>[\s\S]*?<\/span>/, '<span>' + item_total + '<\/span>');
					var item_row = '<tr class="extra">'+
										'<td class="name">'+ item_name +'</td>'+
										'<td class="price">'+ item_price_text +'</td>'+
										'<td class="days">'+ is_days +'</td>'+
										'<td class="quantity">'+ $(this).val() + ' x ' + booking_number +'</td>'+
										'<td class="total">'+ item_total +'</td>'+
									'</tr>';
					$('.booking-total table tbody', parent).append( item_row );
					description += item_name + ' : ' + item_price_text + ' x ' + item_number + ', ';
				}
			});
		}
		$('.extra-item .booking-des', parent).html( description );
	}
	function slzexploore_cal_car_booking(){
		if ($('.car-detail-main').length > 0){
			var obj_car_booking  = $('.car-detail-main .slz-booking-block .car-booking');
			$('.loading', obj_car_booking.parent()).show().fadeIn();
			var car_id      = $('.slz-btn-group button.btn-check', obj_car_booking).data('id');
			var post_data 	 = [{ name : 'car_id', value : car_id}];
			var booking_data = $('.booking-data input, .booking-data select', obj_car_booking).serializeArray();
			var extra_data   = $('.extra-item table td.number-item select', obj_car_booking).serializeArray();
			post_data        = $.merge( $.merge( post_data, booking_data ), extra_data );
			$.fn.Form.ajax(['booking.Booking_Controller', 'ajax_calculate_car_summary'], post_data, function(res) {
				var res_data = jQuery.parseJSON(res);
				$('.summary table td.booking-price span', obj_car_booking).html( res_data['booking_total'] );
				$('.summary table td.extra-total span', obj_car_booking).html( res_data['extra_total'] );
				$('.summary table td.total-price span', obj_car_booking).html( res_data['total'] );
				$('.loading', obj_car_booking.parent()).show().fadeOut();
			});
		}
	}
	// Check out by Woocommerce
	function slzexploore_add_car_rent_to_cart( container ){
		var car_id		= $('.btn.btn-check', container).data('id');
		var price 		= $('.summary table td.booking-price span', container).html();
		var extra_price = $('.summary table td.extra-total span', container).html();
		var description = '';
		if( extra_price ){
			description = $('.extra-item .booking-des', container).html();
		}
		var total_price = $('.summary table td.total-price span', container).html();
		var post_data 	= [{ name : 'car_id', value : car_id},
							{ name : 'price', value : price},
							{ name : 'extra_price', value : extra_price},
							{ name : 'total_price', value : total_price},
							{ name : 'description', value : description}];
		var book_data 	= $('.booking-data',container).serializeArray();
		post_data 		= $.merge( post_data, book_data );
		
		$.fn.Form.ajax(['booking.Booking_Controller', 'ajax_add_car_rent_to_cart'], post_data, function(res) {
			if( res.indexOf("[SUCCESS]") != -1 ){
				var wc_CartPageUrl = res.replace("[SUCCESS]", "");
				top.location.href = wc_CartPageUrl;
			}
			else{
				console.log(res);
			}
		});
	}

	/* Cruise Booking */
	$.slzexploore_cruise_booking = function() {
		if($('.cruises-result-detail .timeline-book-block').length){
			// show/hide booking form
			$('.cruises-result-detail .slz-book-cruise a.btn-book').on('click', function(e){
				e.preventDefault();
				$(this).parents('.cruises-result-detail').find('.slz-booking-block').toggleClass('show-book-block');
			});

			var obj_cruise_booking = $('.cruises-result-detail .slz-booking-block .cruise-booking');
			
			// Calculate Summary when load
			slzexploore_cal_cruise_booking();

			// Load datepicker in tour booking form
			slzexploore_cruise_load_datepicker( obj_cruise_booking );
			
			// Calculate Summary when change input value
			$('.booking-data input[name="number"], .extra-item table td.number-item select, .booking-data select[name="cabin_type_id"], .booking-data input[name="adults"], .booking-data input[name="children"], .booking-data input[name="is_person_price"], .booking-data input[name="infant"]', obj_cruise_booking ).on('change', function(e){
				e.preventDefault();
				slzexploore_cal_cruise_booking();
			});
            $('.booking-data input[name="start_date"], .booking-data select[name="cabin_type_id"]', obj_cruise_booking ).on('change', function(e){
                e.preventDefault();
                var obj_cruise_booking  = $('.cruises-result-detail .slz-booking-block .cruise-booking');
                var cruise_id     = $('.slz-btn-group button.btn-check', obj_cruise_booking).data('id');
                var container  = $(this).closest('.slz-booking-wrapper');
                var cabin_id   = $('.booking-data select[name="cabin_type_id"]', container).val();
                var start_date = $('.booking-data input[name="start_date"]', container).val();
                var data = { 'cabin_id' : cabin_id, 'start_date' : start_date, 'cruise_id' : cruise_id };
                $.fn.Form.ajax(['booking.Booking_Controller', 'ajax_check_cruise_available'], data, function(res) {
                    res = JSON.parse(res);
                    if(res.type == 'cabin') {
                        $('.cabin-types tr td.available-' + cabin_id, container).html( res.available_number );
                    } else if( res.type == 'seat' ) {
                        $('.cruise-info tr:eq(1) td:eq(1)', container).html( res.available_number );
                    }
                });
            });
			$('.booking-data input[name="start_date"]', obj_cruise_booking ).trigger('change');
			
			// Check Booking
			$('.cruises-result-detail .slz-booking-block .cruise-booking button.btn-check').on('click', function(e){
				e.preventDefault();
				var $this = $(this);
				var container = $(this).closest('.find-widget');
				$('.loading', container).show().fadeIn();
				var data = $('.cruise-booking .booking-data',container).serializeArray();
				data.push({ 'name' : 'cruise_id', 'value' : $(this).data('id') });
				$.fn.Form.ajax(['booking.Booking_Controller', 'ajax_check_cruise_booking'], data, function(res) {
					if( res == 'success' ){
						slzexploore_cruise_booking_info( $this.closest('.cruise-booking') );
						if( $this.hasClass('slz-add-to-cart') ){
							slzexploore_add_cruise_to_cart( $this.closest('.cruise-booking') );
						}
						else{
							$this.closest('.cruise-booking').find('.err-message').addClass('hide');
							$this.closest('.cruise-booking').find('.booking-data').addClass('hide');
							$this.closest('.cruise-booking').find('.extra-item').addClass('hide');
							$this.closest('.cruise-booking').find('.booking-total').removeClass('hide');
							$this.closest('.cruise-booking').find('.customer-info').removeClass('hide');
							$this.parent().find('.btn-next').removeClass('hide');
							$this.parent().find('.btn-back').removeClass('hide');
							$this.addClass('hide');
							$('.loading', container).show().fadeOut();
						}
					}
					else{
						$this.closest('.cruise-booking').find('.err-message').html(res).removeClass('hide');
						$('.loading', container).show().fadeOut();
					}
				});
			});
			// Back to form input booking info
			$('.cruises-result-detail .slz-booking-block .cruise-booking button.btn-back').on('click', function(e){
				$.slzexploore_scroll_to( $(this).closest('.cruise-booking') );
				$(this).closest('.cruise-booking').find('.booking-data').removeClass('hide');
				$(this).closest('.cruise-booking').find('.extra-item').removeClass('hide');
				$(this).closest('.cruise-booking').find('.booking-total').addClass('hide');
				$(this).closest('.cruise-booking').find('.customer-info').addClass('hide');
				$(this).closest('.cruise-booking').find('.success-message span').html('');
				$(this).closest('.cruise-booking').find('.success-message').addClass('hide');
				$(this).parent().find('.btn-next').addClass('hide');
				$(this).parent().find('.btn-check').removeClass('hide');
				$(this).addClass('hide');
			});
			// book cruise and save info
			$('.cruises-result-detail .slz-booking-block .cruise-booking button.btn-next').on('click', function(e){
				e.preventDefault();
				var $this = $(this);
				var container = $(this).closest('.cruise-booking');
				var widget    = $(this).closest('.find-widget')
				
				if( !slzexploore_validate_booking_form( $('.customer-info',container) ) ){
					return false;
				}
				// end validate
			
				$('.loading', widget).show().fadeIn();
				var cruise_id	= $(this).data('id');
				var price 		= $('.summary table td.booking-price span', container).html();
				var extra_price = $('.summary table td.extra-total span', container).html();
				var total_price = $('.summary table td.total-price span', container).html();
				var description = $('.extra-item .booking-des', container).html();
				var post_data 	= [{ name : 'cruise_id', value : cruise_id},
									{ name : 'price', value : price},
									{ name : 'extra_price', value : extra_price},
									{ name : 'total_price', value : total_price},
									{ name : 'description', value : description}];
				var book_data 	= $('.booking-data',container).serializeArray();
				var data		= $('.customer-info',container).serializeArray();
				post_data = $.merge( $.merge( post_data, book_data ), data ); 
				
				$.fn.Form.ajax(['booking.Booking_Controller', 'ajax_book_cruise'], post_data, function(res) {
					if( res.indexOf("[SUCCESS]") != -1 ){
						var booking_id = res.replace("[SUCCESS]", "");
						$('.customer-info', container).addClass('hide');
						$('.err-message', container ).html('').addClass('hide');
						$('.success-message', container).removeClass('hide');
						$('.success-message span', container).html( booking_id );
						$.slzexploore_scroll_to( $( '.booking-total', container ) );
						$('.booking-data input[name="start_date"]', container ).trigger('change');
						$this.addClass('hide');
					}
					else if( res.indexOf("[REDIRECT]") != -1 ){
						var redirect_page = res.replace("[REDIRECT]", "");
						window.location = redirect_page;
					}
					else{
						$('.err-message', container).html(res).removeClass('hide');
						$('.success-message span', container).html('');
						$('.success-message', container).addClass('hide');
					}
					$('.loading', widget).show().fadeOut();
				});
			});
		}
	}
	function slzexploore_cal_cruise_booking(){
		if ($('.cruises-result-detail').length > 0){
			var obj_cruise_booking  = $('.cruises-result-detail .slz-booking-block .cruise-booking');
			$('.loading', obj_cruise_booking.parent()).show().fadeIn();
			var cruise_id     = $('.slz-btn-group button.btn-check', obj_cruise_booking).data('id');
			var post_data 	 = [{ name : 'cruise_id', value : cruise_id}];
			var booking_data = $('.booking-data input, .booking-data select', obj_cruise_booking).serializeArray();
			var extra_data   = $('.extra-item table td.number-item select', obj_cruise_booking).serializeArray();
			post_data        = $.merge( $.merge( post_data, booking_data ), extra_data );
			$.fn.Form.ajax(['booking.Booking_Controller', 'ajax_calculate_cruise_summary'], post_data, function(res) {
				var res_data = jQuery.parseJSON(res);
				$('.summary table td.booking-price span', obj_cruise_booking).html( res_data['booking_total'] );
				$('.summary table td.extra-total span', obj_cruise_booking).html( res_data['extra_total'] );
				$('.summary table td.total-price span', obj_cruise_booking).html( res_data['total'] );
				$('.loading', obj_cruise_booking.parent()).show().fadeOut();
			});
		}
	}
	function slzexploore_cruise_load_datepicker( obj_cruise_booking ) {
		var options = $('.booking-data .cruise-datepicker-options', obj_cruise_booking).attr('data-options');
		options = jQuery.parseJSON(options);
		var datepicker_options = {
							startDate: '-0d',
							format: 'yyyy-mm-dd',
							autoclose : true,
							maxViewMode: 0
						};
		if( options['type'] == 'weekly' ){
			datepicker_options = {
							startDate: '-0d',
							format: 'yyyy-mm-dd',
							daysOfWeekDisabled: options['value'].replace("7", "0"),
							autoclose : true,
							maxViewMode: 0
						};
		}
		else if( options['type'] == 'monthly' ){
			datepicker_options = {
							startDate: '-0d',
							format: 'yyyy-mm-dd',
							autoclose : true,
							maxViewMode: 0,
							beforeShowDay:  function (date) {
								var day     = date.getDate();
							    day         = day  < 10 ? '0' + day  : day;
					            if( options['value'].indexOf(day.toString()) != '-1' || options['value'] == date.getDate() ){
					               return;
					            }
					            return {
					                  enabled : false
					               };
						    }
						};
		}
		else if( options['type'] == 'season' ){
			datepicker_options = {
							startDate: options['start_date'],
							endDate: options['end_date'],
							format: 'yyyy-mm-dd',
							autoclose : true,
							maxViewMode: 0
						};
		}
		$('.booking-data .text-box-wrapper .datepicker', obj_cruise_booking).datepicker(datepicker_options);
	}
	function slzexploore_cruise_booking_info( parent ){
		var cabin_id = $('.booking-data select[name="cabin_type_id"]', parent).val();
		var cabin_name = $('.cabin-types table td.name-'+ cabin_id, parent).html();
		var cabin_price = $('.cabin-types table td.cabin-'+ cabin_id, parent).html();
		var booking_price = $('.summary .booking-price', parent).html();
		var booking_number = $('.booking-data input[name="number"]', parent).val();
		var booking_adults = $('.booking-data input[name="adults"]', parent).val();
		var booking_children = $('.booking-data input[name="children"]', parent).val();
		var booking_persons = parseInt( booking_adults ) + parseInt( booking_children );
		var start_date      = $('.booking-data input[name="start_date"]', parent).val();
		var date_title      = $('.cruise-info table tr th:nth-child(3)', parent).html();
		var cabin_name      = cabin_name + "<br>( "+ date_title +": "+start_date +" )";
		
		$('.booking-total table tr.extra', parent).remove();
		$('.booking-total table td.name', parent).html( cabin_name );
		$('.booking-total table td.price', parent).html( cabin_price );
		$('.booking-total table td.persons', parent).html( booking_persons );
		$('.booking-total table td.quantity', parent).html( booking_number );
		$('.booking-total table td.total', parent).html( booking_price );
		var description = '';
		if( $('.extra-item', parent).length ){
			$('.extra-item table td select.quanlity', parent).each(function() {
				if( $(this).val() != 0 ){
					var item_name = $(this).closest('tr').find('td.item-info h5').html();
					var item_price_text = $(this).closest('tr').find('td.item-price').html();
					var item_price = $(this).closest('tr').find('td.item-price span').html();
					var item_total = parseInt( $(this).val() ) * parseInt( item_price );
					var is_person = $(this).closest('tr').find('td.is-person span').html();
					var item_number = parseInt( $(this).val() );
					var item_persons = 0;
					if( is_person ){
						item_persons = booking_persons;
						item_total *= parseInt( item_persons );
						item_number *= parseInt( item_persons );
					}
					item_total = slzexploore_formatNumber( item_total );
					item_total = item_price_text.replace(/<span>[\s\S]*?<\/span>/, '<span>' + item_total + '<\/span>');
					var item_row = '<tr class="extra">'+
										'<td class="name">'+ item_name +'</td>'+
										'<td class="price">'+ item_price_text +'</td>'+
										'<td class="persons">'+ item_persons +'</td>'+
										'<td class="quantity">'+ $(this).val() +'</td>'+
										'<td class="total">'+ item_total +'</td>'+
									'</tr>';
					$('.booking-total table tbody', parent).append( item_row );
					description += item_name + ' : ' + item_price_text + ' x ' + item_number + ', ';
				}
			});
		}
		$('.extra-item .booking-des', parent).html( description );
	}
	// Check out by Woocommerce
	function slzexploore_add_cruise_to_cart( container ){
		var cruise_id	= $('.btn.btn-check', container).data('id');
		var price 		= $('.summary table td.booking-price span', container).html();
		var extra_price = $('.summary table td.extra-total span', container).html();
		var description = '';
		if( extra_price ){
			description = $('.extra-item .booking-des', container).html();
		}
		var total_price = $('.summary table td.total-price span', container).html();
		var post_data 	= [{ name : 'cruise_id', value : cruise_id},
							{ name : 'price', value : price},
							{ name : 'extra_price', value : extra_price},
							{ name : 'total_price', value : total_price},
							{ name : 'description', value : description}];
		var book_data 	= $('.booking-data',container).serializeArray();
		post_data 		= $.merge( post_data, book_data );
		
		$.fn.Form.ajax(['booking.Booking_Controller', 'ajax_add_cruise_to_cart'], post_data, function(res) {
			if( res.indexOf("[SUCCESS]") != -1 ){
				var wc_CartPageUrl = res.replace("[SUCCESS]", "");
				top.location.href = wc_CartPageUrl;
			}
			else{
				console.log(res);
			}
		});
	}
	
	function slzexploore_formatNumber (num) {
		return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
	}
	
	$.slzexploore_deposit_booking = function() {
		if( $('.slz-booking-wrapper .booking-data select[name="deposit_method"]').length > 0 ){
			$('.slz-booking-wrapper .booking-data select[name="deposit_method"]' ).on('change', function(e){
				if( $(this).val() == 'full' ){
					$(this).closest('.text-box-wrapper').find('.description').hide();
				}
				else{
					$(this).closest('.text-box-wrapper').find('.description').show();
				}
			});
			$('.slz-booking-wrapper .booking-data select[name="deposit_method"]' ).trigger('change');
		}
	};

})(jQuery);

jQuery( document ).ready( function() {
	jQuery.slzexploore_cf7_booking();
	jQuery.slzexploore_hotel_booking();
	jQuery.slzexploore_tour_booking();
	jQuery.slzexploore_car_booking();
	jQuery.slzexploore_cruise_booking();
	jQuery.slzexploore_deposit_booking();
});
jQuery( window ).load( function() {
	
});