;(function($) {
	"use strict";
	$.fn.slzexploore = function(){};
	var $this = $.fn.slzexploore;

	/**
	 * jQuery.fn.slzexploore.ajax(['setting.Setting_Main', 'html'], [[],[]], function(data){})
	 */
	$.fn.slzexploore.ajax = function( module, params, fun ) {
		if( ! ajaxurl ) {
			return;
		}

		jQuery.ajax({
			'type'  : 'POST',
			'url'   : ajaxurl,
			'data'  : { 'action' : 'slzexploore', 'module' : module, 'params' : params },
			success : function( response ) {
				if( 'function' == typeof( fun ) ) {
					fun.call( this, response );
				}
			}
		});
	};

	$.fn.slzexploore.replace = function( str, data ) {
		return str.replace( /{([^}]+)}/g, function( match, key, offset, old ) {
			return $this.find( data, key );
		});
	};

	$.fn.slzexploore.find = function( data, key ) {
		var part = key.split(".");
		var rev = data;
		$.each( part, function( index, value ) {
			if( 'undefined' != typeof( rev ) ) {
				rev = rev[value];
			}
		});

		return rev;
	};
	
	$.fn.slzexploore.merge = function( o, n ) {
		if( n ) {
			for( var p in n ) {
				try {
					if( n[p].constructor == Object ) {
						o[p] = $.fn.Chart.merge( o[p], n[p] );
					} else {
						o[p] = n[p];
					}
				} catch( e ) {
					o[p] = n[p];
				}
			}
		}

		return o;
	};
	
	$.fn.slzexploore.toHash = function( data ) {
		var result = {};

		$.each( data, function( index, row ) {
			result[row['name']] = row['value'];
		});

		return result;
	};
	
	$.fn.slzexploore.valid = function() {
		if( $('#post').length > 0 ){
			var post_type = $("#post [name='post_type']").val();
			switch( post_type ){
				case 'design':
					$.fn.slzexploore.valid_design();
					break;
			}
		}
	};

	$.fn.slzexploore.ok = false;
	
	$.fn.slzexploore.valid_design = function() {

		$('#post').submit( function() {
			if( ! $.fn.slzexploore.ok ){
				$.fn.slzexploore.ajax( ['setting.Setting_Controller', 'design_valid'], [$.fn.slzexploore.toHash( $('#post').serializeArray() )], function( response ) {
					if( response && 1 == response['ok'] ) {
						$.fn.slzexploore.ok = true;
						$('#post').submit();
					} else {
						console.dir( response['error'] );
					}
				});

				return false;
			}
		});
	};
})(jQuery);

jQuery( document ).ready(function() {
	jQuery.fn.slzexploore.valid();
});
