// JavaScript Document
(function($) {
	"use strict";
	var slzexploore_blog_masonry = function(){
		if ($('.blog-masonry').length > 0){
			$('.blog-masonry').isotope({
				itemSelector: '.blog-item',
				layoutMode: 'masonry',
				masonry: {
					columnWidth: '.blog-item'
				}
			});
		}
	}
	var slzexploore_search = function(){
		$('.widget_search').each(function() {
			$(this).addClass('search-widget');
			$(this).find('.search-form').find('.search-field').addClass('search-input form-control').wrap('<div class="search-wrapper input-group"></div>');
			$(this).find('.search-wrapper').append('<span class="input-group-btn"><button type="submit" class="btn submit-btn"><span class="fa fa-search"></span></button></span>')
		});
	}
	var slzexploore_calendar = function(){
		$('.widget_calendar').each(function() {
			$(this).find('.calendar_wrap').remove();
			$(this).find('#calendar_wrap').remove();
			$(this).addClass('archives-widget');
			$(this).append('<div class="content-widget"><div class="archive-datepicker"></div></div>')
		});
		$('.input-daterange, .archive-datepicker').datepicker({
			format: 'mm/dd/yy',
			maxViewMode: 0
		});
	}
	var slzexploore_tag = function(){
		$(".widget_tag_cloud").each(function() {
			$(this).addClass('tags-widget');
			$(this).find('.tag-item').addClass('tag');
		});
	}
	//archive widget js
	var slzexploore_ArchiveWidget = function() {
		$('.archive-widget-box').each(function (){
			$('.list-unstyled li',$(this)).each( function (){
				var post_counts = $('.post-counts-box',$(this));
				if ( post_counts.length > 0){
					$('a',$(this)).append( post_counts.html());
					$(post_counts).remove();
				}
			});
			
		});
	};
	//tag widget
	var slzexploore_TagWidget = function() {
		if( $('.widget_tag_cloud').length > 0 ) {
			$('.widget_tag_cloud').addClass('popular-widget');
			$('.widget_tag_cloud').find('.tagcloud').addClass('content-widget');
		}
		$('.tagcloud').each(function (){
			$(this).find('a').addClass('tag-item').wrap('<li class="popular-group"></li>');
			var item = $(this).html();
			$(this).append('<ul class="tag-widget list-unstyled"></ul>');
			$('li',$(this)).remove();
			$(item).wrap('.tag-widget',$(this));
			$('.tag-widget',$(this)).replaceWith('<ul class="tag-widget list-unstyled">'+item+'</ul>');
		});
	};
	//css for widget default
	var slzexploore_custom_widget_default = function() {
		if( $('.slz-widget').length > 0 ) {
			$('.slz-widget').find('ul').addClass('list-unstyled');
		}
	};
	
	/**
	 * Comment
	 */
	var slzexploore_comment = function() {
		$("#submit",$("#commentform")).click(function () {
			var emailRegex = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/;
			var urlPattern = /(http|ftp|https):\/\/[\w-]+(\.[\w-]+)+([\w.,@?^=%&amp;:\/~+#-]*[\w@?^=%&amp;\/~+#-])?/;
			var isError	= false;
			var focusEle   = null; 
			$("#commentform .input-error-msg").addClass('hide');
			$("#commentform input, #commentform textarea").removeClass('input-error');
			if ( $("#author").length ){
				if($("#comment").val().trim() == '' ){
					$('#comment-err-required').removeClass('hide');
					$("#comment").addClass('input-error');
					isError  = true;
					focusEle = "#comment";
				}
				else if($("#author").val().trim() == '' ) {
						$('#author-err-required').removeClass('hide');
						$("#author").addClass('input-error');
						isError  = true;
						focusEle = "#author";
					}
				else if($("#email").val().trim() == '' ){
					$('#email-err-required').removeClass('hide');
					$("#email").addClass('input-error');
					isError  = true;
					focusEle = "#email";
				}
				else if(!$("#email").val().match(emailRegex)){
					$('#email-err-valid').removeClass('hide');
					$("#email").addClass('input-error');
					isError  = true;
					focusEle = "#email";
				}
			}else{
				if($("#comment").val().trim() == '' ){
					$('#comment-err-required').removeClass('hide');
					$("#comment").addClass('input-error');
					isError  = true;
					focusEle = "#comment";
				}
			}
			if(isError){
				$(focusEle).focus();
				return false;
			}
			return true;
		});
	}; // end comment func

    /**
	 * Header Gallery
     */
    var slzexploore_posttype_header_gallery = function () {
        var slzcore_flag_rtl = true;
        if (!$('body').hasClass("rtl")) {
            slzcore_flag_rtl = false;
        }
        $(".posttype-header-gallery").each(function(){
            $(this).slick({
                rtl: slzcore_flag_rtl,
            });
        });
    };

	/**
	 * Initial Script
	 */
	$(document).ready(function() {
		slzexploore_blog_masonry();
		slzexploore_tag();
		slzexploore_calendar();
		slzexploore_search();
		slzexploore_TagWidget();
		slzexploore_ArchiveWidget();
		slzexploore_custom_widget_default();
		slzexploore_comment();
        slzexploore_posttype_header_gallery();
	});
	$( window ).load( function() {
		slzexploore_blog_masonry();
		slzexploore_tag();
	});
})(jQuery);