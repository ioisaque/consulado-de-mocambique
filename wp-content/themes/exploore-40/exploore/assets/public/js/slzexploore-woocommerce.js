(function($) {
	"use strict";
	$(function(){
		var flag_rtl = true;
		if (!$('body').hasClass("rtl")) {
			flag_rtl = false;
		}
		var remember_input = $('.woocommerce .login .form-row:not(.form-row-first):not(.form-row-last) label input');
		$(remember_input).parent().before($(remember_input));
		$('.woocommerce .cart-collaterals').append($('.woocommerce .cart-collaterals .cross-sells'));
		$('.woocommerce .woocommerce-shipping-fields #ship-to-different-address label').before($('.woocommerce .woocommerce-shipping-fields #ship-to-different-address input'));
		$('.slz-woocommerce .type-product .woocommerce-tabs .panel').addClass('fadeIn animated');
		$('.woocommerce table.wishlist_table').parent().addClass('table-responsive');
		$(".slz-woocommerce .type-product .entry-summary .quantity, .woocommerce table.shop_table .quantity").append('<div class="inc button-quantity"><i class="fa fa-caret-up"></i></div><div class="dec button-quantity"><i class="fa fa-caret-down"></i></div>');
		$(".button-quantity").on("click", function() {
			var $button = $(this);
			var oldValue = $button.parent().find("input").val();
			var newVal;

			if ($button.children().hasClass('fa-caret-up')) {
				newVal = parseFloat(oldValue) + 1;
			} else {
				// Don't allow decrementing below zero
				if (oldValue > 0) {
					newVal = parseFloat(oldValue) - 1;
				} else {
					newVal = 0;
				}
			}
			$button.parent().find("input").val(newVal);
		});
		$('.product_meta').insertAfter($('.slz-woocommerce .type-product .entry-summary .price,woocommerce .type-product .entry-summary .price'));
		
		var owlthumbnails = $('.slz-woocommerce .type-product .images .thumbnails ul,.woocommerce .type-product .images .thumbnails ul').slick({
			slidesToShow: 3,
			prevArrow: '<button type="button" class="slick-prev fa fa-angle-left"></button>',
			nextArrow: '<button type="button" class="slick-next fa fa-angle-right"></button>',
			rtl: flag_rtl
		});

		var owlthumbnails_1 = $('.col-md-8 .woocommerce .cart-collaterals .cross-sells .products,.woocommerce-page .col-md-8 .cart-collaterals .cross-sells .products, .slz-woocommerce .col-md-8 .type-product .upsells > .products, .slz-woocommerce .col-md-8 .type-product .related > .products').slick({
			slidesToShow: 3,
			prevArrow: '<button type="button" class="slick-prev fa fa-angle-left"></button>',
			nextArrow: '<button type="button" class="slick-next fa fa-angle-right"></button>',
			infinite: true,
			rtl: flag_rtl,
			responsive: [
				{
					breakpoint: 768,
					settings: {
						slidesToShow: 2,
					}
				},
				{
					breakpoint: 481,
					settings: {
						slidesToShow: 1,
					}
				}
			]
		});

		var owlthumbnails_2 = $('.col-md-12 .woocommerce .cart-collaterals .cross-sells .products, .col-md-12 .woocommerce-page .cart-collaterals .cross-sells .products, .slz-woocommerce .col-md-12 .type-product .upsells > .products, .slz-woocommerce .col-md-12 .type-product .related > .products, .col-md-12 .woocommerce .type-product .upsells > .products, .col-md-12 .woocommerce .type-product .related > .products').slick({
			slidesToShow: 4,
			prevArrow: '<button type="button" class="slick-prev fa fa-angle-left"></button>',
			nextArrow: '<button type="button" class="slick-next fa fa-angle-right"></button>',
			infinite: true,
			rtl: flag_rtl,
			responsive: [
				{
					breakpoint: 992,
					settings: {
						slidesToShow: 3,
					}
				},
				{
					breakpoint: 768,
					settings: {
					   slidesToShow: 2,
					}
				},
				{
					breakpoint: 481,
					settings: {
						slidesToShow: 1,
					}
				}
			]
		});

		var owlthumbnails_3 = $('.col-md-8 .woocommerce .type-product .upsells > .products, .col-md-8 .woocommerce .type-product .related > .products').slick({
			slidesToShow: 2,
			prevArrow: '<button type="button" class="slick-prev fa fa-angle-left"></button>',
			nextArrow: '<button type="button" class="slick-next fa fa-angle-right"></button>',
			infinite: true,
			rtl: flag_rtl,
			responsive: [
				{
					breakpoint: 992,
					settings: {
						slidesToShow: 3,
					}
				},
				{
					breakpoint: 768,
					settings: {
					   slidesToShow: 2,
					}
				},
				{
					breakpoint: 481,
					settings: {
						slidesToShow: 1,
					}
				}
			]
		});

		$('.woocommerce.widget_product_categories .product-categories li .children').each(function(){
			var ul_child_drop = $(this);
			$(ul_child_drop).hide();
			if ($(ul_child_drop).length > 0) {
				$(this).parent().append('<span class="fa fa-long-arrow-right"></span>');
			}
		});
		$('.woocommerce.widget_product_categories .product-categories li span').on('click',function(){
			$(this).prev().slideToggle();
			$(this).parent().toggleClass('open');
		});
		//SHOW THE SALE PERCENTAGE
		var pattern = /[^0-9.+]/
		$('.product.sale').each(function() {
			var oldPrice = parseFloat($(this).find('.price').find('del').find('.amount').first().text().replace(pattern, ''));
			var newPrice = parseFloat($(this).find('.price').find('ins').find('.amount').first().text().replace(pattern, ''));
			var salePercent = Math.round((oldPrice - newPrice) / oldPrice * 100);
			$(this).find('.onsale').find('.sale-percent').html(salePercent + '%');
		});
		//PAGINATION REFORMATTED
		$('.woocommerce-pagination > .page-numbers > li .page-numbers:not(.next, .prev)').each(function() {
			var page = parseInt($(this).text());
			if (page < 10) {
				$(this).html('0' + page);
			}
		});

		$('.woocommerce .cart-collaterals .cross-sells ul.products li img, .slz-woocommerce .type-product img').wrap('<div class="img-wrapper"></div>');
		
		$(document).ready(function () {
			$(".comment-form-rating p.stars a").hover( function() {
				$(this).prevAll().andSelf().addClass( "shine-hover" );
			}, function() {
				$(this).prevAll().andSelf().removeClass( "shine-hover" );
			}).on("click", function() {
				$(this).nextAll().removeClass("shine");
				$(this).prevAll().andSelf().addClass("shine");
			});
		});
	});
})(jQuery);