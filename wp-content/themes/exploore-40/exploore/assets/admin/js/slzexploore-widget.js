jQuery(document).ready(function($) {
	"use strict";
	// social
	$(document).on('click','.widget-inside input[id^="widget-slz_social_group"]',function() {
		if($(this).attr('checked') != 'checked') {
			$(this).parents('p').next('div').fadeOut();
		} else {
			$(this).parents('p').next('div').fadeIn();
		}
	});
	// custom sidebar
	$('.widget-liquid-right').append($('#slzexploore-custom-widget').html());
	$('.sidebar-slz-custom',$('#widgets-right')).append('<span class="slzexploore-sidebar-delete-button">&times;</span>');
	$(".slzexploore-sidebar-delete-button").on('click', function(e) { 
		var confirm_msg = $(e.currentTarget).parents('.widget-liquid-right:eq(0)').find('input[name="slzexploore-delete-sidebar-nonce"]').data('confirm');
		var delete_it = confirm( confirm_msg );
		if(delete_it == false) return false;

		var widget  = $(e.currentTarget).parents('.widgets-holder-wrap:eq(0)'),
			spinner = widget.find('.sidebar-name .spinner'),
			widget_name = $.trim(spinner.parent().text()),
			nonce   = $(e.currentTarget).parents('.widget-liquid-right:eq(0)').find('input[name="slzexploore-delete-sidebar-nonce"]').val();
		$.ajax({
			type: "POST",
			url: window.ajaxurl,
			data: {
				action: 'slzexploore_del_custom_sidebar',
				name: widget_name,
				_wpnonce: nonce
			},

			beforeSend: function()
			{
				spinner.addClass('activate_spinner');
			},
			success: function(response)
			{
				if(response == 'success')
				{
					widget.slideUp(200, function(){
						
						$('.widget-control-remove', widget).trigger('click'); //delete all widgets inside
						widget.remove();
						
						wpWidgets.saveOrder();
						
					});
				} 
			}
		});
	});
});