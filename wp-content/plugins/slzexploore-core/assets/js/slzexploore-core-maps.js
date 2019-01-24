(function($){
	"use strict";
	
	$.slzexploore_core_map_mainFunction = function(){
		/*Google map*/
		var myLatLng = {lat: 13.8705583 - 0.0033, lng: 100.5976089 - 0.0055};
		var markerLatLng = {lat: 13.8705583, lng: 100.5976089};
		var markerTitle = '';

		/* Begin Map contact short code*/
		var json= {};
		var timeout_map = 1;
		var attr = '';
		var block_class = '';
		$(".slz-shortcode .map-block").each(function() {
			attr = $(this).attr('data-id');
			if (attr != '') {
				block_class = '.contact-' + attr + ' ';
			}
			if ($( block_class + "#googleMap" ).length) {
				timeout_map = 1000;
				var address = $( block_class + '#googleMap' ).data( 'address' );
				if (address != '') {
					$.ajax({
						url: "https://maps.googleapis.com/maps/api/geocode/json?address="+address+"&sensor=false",
						type: "POST",
						success: function(res){
							json.address = address;
							json.lat = res.results[0].geometry.location.lat;
							json.lng = res.results[0].geometry.location.lng;
							$( block_class + "#googleMap" ).attr('data-json', JSON.stringify(json));
						}
					});
				}
			}
		});
		/* End Map contact short code*/
	

		setTimeout(function() {

			if ($("#googleMap").length) {
				var data = $('#googleMap').data( 'json' );
				if( data != undefined ) {
					myLatLng = {lat: data.lat - 0.0022, lng: data.lng - 0.0055};
					markerLatLng = {lat: parseFloat(data.lat), lng: parseFloat(data.lng)};
					markerTitle = data.address;
				}
			}

			var customMapType = new google.maps.StyledMapType(
				[
					{
						"featureType": "water",
						"stylers": [
							{ "color": "#f0f3f6" }
						]
					},
					{
						"featureType": "road.highway",
						"elementType": "geometry",  
						"stylers": [
							{ "color": "#adb3b7" }
						]
					},
					{
						"featureType": "road.highway",
						"elementType": "labels.icon",
						"stylers": [
						  { "hue": "#ededed" }
						]
					},
					{
						"featureType": "road.arterial",
						"stylers": [
							{ "color": "#c8cccf" }
						]
					},
					{
						"featureType": "road.local",
						"stylers": [
							{ "color": "#e6e6e6" }
						]
					},
					{
						"featureType": "landscape",
						"stylers": [
							{ "color": "#ffffff" }
						]
					},
					{
						"elementType": "labels.text",
						"stylers": [
							{ "weight": 0.1 },
						  { "color": "#6d6d71" }
						]
					}
				], 
				{
					name: 'Custom Style'
			});
			var customMapTypeId = 'custom_style';
		
			var mapProp = {
				center: myLatLng,
				zoom:16,
				mapTypeId:google.maps.MapTypeId.ROADMAP,
				scrollwheel: false,
				draggable: false,
				disableDefaultUI: true,
				mapTypeControlOptions: {
					mapTypeIds: [google.maps.MapTypeId.ROADMAP, customMapTypeId]
				}
			};
			function initialize() {
				var map = new google.maps.Map(document.getElementById("googleMap"),mapProp);
				var image_url = $('#googleMap').attr('data-img-url');
				map.mapTypes.set(customMapTypeId, customMapType);
				map.setMapTypeId(customMapTypeId);
				var image = {
					url: image_url,
					origin: new google.maps.Point(0, 0),
					anchor: new google.maps.Point(17, 34),
					scaledSize: new google.maps.Size(40, 40)
				};

				var marker = new google.maps.Marker({
					position: markerLatLng,
					map: map,
					animation:google.maps.Animation.BOUNCE, 
					icon: image,
					title: markerTitle
				});
		
				marker.addListener('click', function() {
					$('.btn-open-map').parents('.map-info').toggle(400);
				});
			}
			initialize();
			/*google.maps.event.addDomListener(window, 'load', initialize);*/

	
			$('.btn-open-map').click(function(event) {
				/* Act on the event */
				$(this).parents('.map-info').toggle(400);
				$('#googleMap').css('pointer-events', 'auto');
				if($(window).width() > 462) {
					mapProp = {
						center: markerLatLng,
						zoom:16,
						mapTypeId:google.maps.MapTypeId.ROADMAP,
						scrollwheel: false,
						disableDefaultUI: true,
						mapTypeControlOptions: {
							mapTypeIds: [google.maps.MapTypeId.ROADMAP, customMapTypeId]
						}
					};
				}
				else {
					mapProp = {
						center: markerLatLng,
						zoom:15,
						mapTypeId:google.maps.MapTypeId.ROADMAP,
						scrollwheel: false,
						navigationControl: false,
						mapTypeControl: false,
						mapTypeControlOptions: {
							mapTypeIds: [google.maps.MapTypeId.ROADMAP, customMapTypeId]
						}
					};
				} 
		
				initialize();
			});

		}, timeout_map);

	};

})(jQuery);

jQuery( document ).ready( function() {
	if (jQuery("#googleMap").length) {
		jQuery.slzexploore_core_map_mainFunction();
	}
});