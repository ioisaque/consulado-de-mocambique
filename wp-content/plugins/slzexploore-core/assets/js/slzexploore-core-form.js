;(function($) {
	"use strict";
	$.fn.Form = function(){};
	var $this = $.fn.Form;

	$.fn.Form.media = function( options, fun ) {
		try {
			var media = wp.media.frames.file_frame = wp.media( $this.merge({
				title: 'Choose Image',
				button:{
					text: 'Choose Image'
				},
				multiple: true
			}, options ) ); 

			media.on( 'select', function() {
				if( typeof( fun ) == 'function' ) {
					fun.call( this, media.state().get('selection').toJSON() );
				}
			});

			media.open();
		} catch( err ){}
	};

	/**
	 * jQuery.fn.Form.ajax(['setting.Setting_Main', 'html'], [[],[]], function(data){})
	 */
	$.fn.Form.ajax = function( module, params, fun ) {
		if( ! ajaxurl ) {
			return;
		}

		jQuery.ajax({
			'type'  : 'POST',
			'url'   : ajaxurl,
			'data'  : { 'action' : 'slzexploore_core', 'module' : module, 'params' : params },
			success : function( response ) {
				if( 'function' == typeof( fun ) ) {
					fun.call( this, response );
				}
			}
		});
	};

	$.fn.Form.replace = function( str, data ) {
		return str.replace( /{([^}]+)}/g, function( match, key, offset, old ) {
			return $this.find( data, key );
		});
	};

	$.fn.Form.find = function( data, key ) {
		var part = key.split(".");
		var rev = data;
		$.each( part, function( index, value ) {
			if( 'undefined' != typeof( rev ) ) {
				rev = rev[value];
			}
		});

		return rev;
	};
	
	$.fn.Form.merge = function( o, n ) {
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
	
	$.fn.Form.toHash = function( data ) {
		var result = {};

		$.each( data, function( index, row ) {
			result[row['name']] = row['value'];
		});

		return result;
	};
	
	$.fn.Form.valid = function() {
		if( $('#post').length > 0 ){
			var post_type = $("#post [name='post_type']").val();
			switch( post_type ){
				case 'design':
					$.fn.Form.valid_design();
					break;
			}
		}
	};

	$.fn.Form.ok = false;
	
	$.fn.Form.valid_design = function() {

		$('#post').submit( function() {
			if( ! $.fn.Form.ok ){
				$.fn.Form.ajax( ['setting.Setting_Controller', 'design_valid'], [$.fn.Form.toHash( $('#post').serializeArray() )], function( response ) {
					if( response && 1 == response['ok'] ) {
						$.fn.Form.ok = true;
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
	jQuery.fn.Form.valid();
});