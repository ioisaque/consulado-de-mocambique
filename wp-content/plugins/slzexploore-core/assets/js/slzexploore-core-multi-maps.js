(function($){
	"use strict";
	
	$.slzexploore_core_mainFunction = function(){
		/*Google map*/
		var data = $('#multi-marker').data( 'json' );
		if( typeof data !== 'undefined' && data.length > 0 ){
			
			var myLatLng = {lat: 13.8705583 - 0.0033, lng: 100.5976089 - 0.0055};
			var markerLatLng = {lat: 13.8705583, lng: 100.5976089};
			var markerTitle = '';
			var timeout_map = 1;
			var zoom = $('#multi-marker').closest('.map-block-wrapper').data('zoom');
			
			
			setTimeout(function() {

				if ($("#multi-marker").length) {
					
					if( data != undefined ) {
						myLatLng = {lat: parseFloat(data[0].lat), lng: parseFloat(data[0].lng) };
					}
				}
			
				var mapProp = {
					center: myLatLng,
					zoom:zoom,
					scrollwheel: false
				};

				function initialize() {

					var markers = [];
					var map = new google.maps.Map(document.getElementById("multi-marker"),mapProp);
					var image_url = $('#multi-marker').closest('.map-block-wrapper').attr('data-img-url');
					var clusterer_image = $('#multi-marker').closest('.map-block-wrapper').data('cluster');
					var bound = new google.maps.LatLngBounds();
					
					var image = {
						url: image_url,
					};
					var i;
					for (i = 0; i < data.length; i++) {
						if( data[i].lat != undefined && data[i].lng != undefined ) {
							markerLatLng = {lat: parseFloat(data[i].lat), lng: parseFloat(data[i].lng)};
							markerTitle = data[i].address;
							var marker = new google.maps.Marker({
								position: markerLatLng,
								map: map,
								icon: image,
								title:markerTitle
							});
							markers.push(marker);
							var content =  '<div id="infowindow">';
							if( data[i].image.length ){
								content += '<div class="image"><img class="img-responsive" src="'+ data[i].image +'"></div>';
							}
							content += '<h3 class="address"><a href="'+data[i].href+'">'+ data[i].title +'</a></h3>';
							if( data[i].star.length ){
								content += '<span data-rating="'+ data[i].star +'"></span>';
							}
							content += '</div>';
							var infowindow = new google.maps.InfoWindow()
							google.maps.event.addListener(marker,'click', (function(marker,content,infowindow){
							        return function() {
							           infowindow.setContent(content);
							           infowindow.open(map,marker);
							        };
							    })(marker,content,infowindow));
	
							infowindow.setContent(content);
							infowindow.open(map,marker);
							bound.extend( new google.maps.LatLng(data[i].lat, data[i].lng));
						}
					}
					var clusterStyles = [
					 {
					    textColor: 'white',
					    url: clusterer_image,
					    height: 55,
					    width: 56,
					  }
					];
					var clusterer_options = {
					    gridSize: 56,
					    styles: clusterStyles,
					    maxZoom: 11
					};
					var markerCluster = new MarkerClusterer(map, markers,clusterer_options);
					map.fitBounds(bound);
					
				}
				initialize();
			}, timeout_map);
		}
	};

})(jQuery);

jQuery( document ).ready( function() {
	if (jQuery("#multi-marker").length) {
		jQuery.slzexploore_core_mainFunction();
	}
});