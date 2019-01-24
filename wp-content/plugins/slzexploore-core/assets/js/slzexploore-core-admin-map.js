;(function($) {
	"use strict";
	$.slzexploore_core_autocomplete_address = function () {
		//autocomplete address
		$(".slzexploore_core-map-address").geocomplete().bind("geocode:result", function () {
			var address = $(this).val();
			if ( address ){
				$(this).gmap3({
					getlatlng:{
						address:address,
						callback:function(r){
							if(!r){
								return false;
							}
							var location = r[0].geometry.location;
							$(this).next(".slzexploore_core-map-location").val( location.lat()+','+location.lng());
							$(this).parents('.slzexploore_core-map-metabox').find(".slzexploore_core_map_area").gmap3({
								get:{
									name:"marker",
									callback:function(m){
										m.setPosition(location);
										$(this).parents('.slzexploore_core-map-metabox').find(".slzexploore_core_map_area").gmap3({map:{options:{center:location}}});
									}
								}
							});
							
						}
						
					}
				});
			}
		});
		//event chang address
		$(".slzexploore_core-map-address").focusout(function() {
			var address = $(this).val();
			if ( address ){
				$(this).gmap3({
					getlatlng:{
						address:address,
						callback:function(r){
							if(!r){
								return false;
							}
							var location = r[0].geometry.location;
							$(this).next(".slzexploore_core-map-location").val( location.lat()+','+location.lng());
						}
						
					}
				});
			}
		});
	};
	$.slzexploore_core_get_location = function () {
		$(".slzexploore_core-map-address").each(function (){
			var str = $(this).next(".slzexploore_core-map-location").val();
			var position;
			if(str){
				var res = str.split(",");
				position = new google.maps.LatLng( res[0], res[1] );
			}else{
				position = new google.maps.LatLng(51.5073509, -0.12775829999998223);
			}
			var option = {
				map:{ options:{zoom:14} },
				marker:{
					options:{ draggable:true },
					events:{
						dragend:function(m){
							var mark = m.getPosition();
							$(this).next(".slzexploore_core-map-location").val( mark.lat()+','+mark.lng());
						}
					}
				}
			};
			option.map.options.center = position;
			option.marker.values = [{ latLng: position }];
			$(".slzexploore_core_map_area").css("height", 300).gmap3(option);
		});
		//get position of address
		$("body").on("keyup", ".slzexploore_core-map-address", function(e){
			e.preventDefault();
			if(e.keyCode == 13)
				$(".slzexploore_core-map-address").trigger("click");
			return false;
			}).parent().find(".find-address").on("click", function(){
				var address = $(this).parents('.slzexploore_core-map-metabox').find(".slzexploore_core-map-address").val();
				$(this).parents('.slzexploore_core-map-metabox').find(".slzexploore_core_map_area").gmap3({
					getlatlng:{
						address:address,
						callback:function(r){
							if(!r){
								return false;
							}
							var location = r[0].geometry.location;
							$(this).parents('.slzexploore_core-map-metabox').find(".slzexploore_core-map-location").val( location.lat()+','+location.lng());
							$(this).parents('.slzexploore_core-map-metabox').find(".slzexploore_core_map_area").gmap3({
								get:{
									name:"marker",
									callback:function(m){
										m.setPosition(location);
										$(this).parents('.slzexploore_core-map-metabox').find(".slzexploore_core_map_area").gmap3({map:{options:{center:location}}});
									}
								}
							});
						}
					}
				});
			});
	};
})(jQuery);

jQuery( document ).ready( function() {
	jQuery.slzexploore_core_get_location();
	jQuery.slzexploore_core_autocomplete_address();
});