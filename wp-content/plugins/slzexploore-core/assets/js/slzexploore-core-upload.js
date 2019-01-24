jQuery(document).ready(function($) {
	"use strict";
	var slzexploore_core_upload_frame;
	var slzexploore_core_btn_upload;

	// Runs when the image button is clicked.
	$('.slz-btn-upload').live('click', function(e){

		// Prevents the default action from occuring.
		e.preventDefault();

		slzexploore_core_btn_upload = $(this);
		// If the frame already exists, re-open it.
		if ( slzexploore_core_upload_frame ) {
			slzexploore_core_upload_frame.open();
			return;
		}

		// Sets up the media library frame
		slzexploore_core_upload_frame = wp.media.frames.meta_image_frame = wp.media({
			title: slzexploore_core_meta_image.title,
			button: { text:  slzexploore_core_meta_image.button },
			library: { type: 'image' },
		});

		// Runs when an image is selected.
		slzexploore_core_upload_frame.on('select', function(){

			// Grabs the attachment selection and creates a JSON representation of the model.
			var media_attachment = slzexploore_core_upload_frame.state().get('selection').first().toJSON();

			// Container
			var rel = slzexploore_core_btn_upload.attr('data-rel');
			var self_parent = slzexploore_core_btn_upload.parent();
			// Sends the attachment URL to our custom image input field.
			var med_url = media_attachment.sizes && media_attachment.sizes.medium ? media_attachment.sizes.medium.url : media_attachment.url;
			$('#' + rel + '_name').val(media_attachment.url);
			$('#' + rel + '_id').val(media_attachment.id);
			self_parent.find('img').attr('src', med_url);
			self_parent.find('div').removeClass('hide');
			slzexploore_core_btn_upload.next().removeClass('hide');
		});

		// Opens the media library frame.
		slzexploore_core_upload_frame.open();
	});
	$('.slz-btn-remove').live('click', function(e) {
		// Prevents the default action from occuring.
		e.preventDefault();

		var self = $(this);
		var rel = self.attr('data-rel');
		var self_parent = self.parent();

		$('#' + rel + '_name').val('');
		$('#' + rel + '_id').val('');
		self_parent.find('div').addClass('hide');
		self.addClass('hide');
	});

	/* initialize uploader */
	var uploader = new plupload.Uploader({
		browse_button: 'select-images',
		file_data_name: 'slzexploore_core_upload_file',
		drop_element: 'drag-and-drop-gallery',
		url: ajaxurl + "?action=slzexploore_core_image_upload",
		filters: {
			mime_types : [
				{ extensions : "jpg,jpeg,gif,png" }
			],
			prevent_duplicates: true
		}
	});
	uploader.init();

	/* Run after adding file */
	uploader.bind('FilesAdded', function(up, files) {
		var html = '';
		var galleryThumb = "";
		plupload.each(files, function(file) {
			galleryThumb += '<li id="img-' + file.id + '" class="gallery-thumb">' + '' + '</li>';
		});
		$('#errors-log').html('');
		$('#gallery-thumbs-container').append(galleryThumb);
		up.refresh();
		uploader.start();
	});

	/* In case of error */
	uploader.bind('Error', function( up, err ) {
		$('#errors-log').html("Error #" + err.code + ": " + err.message);
	});

	/* If files are uploaded successfully */
	uploader.bind('FileUploaded', function ( up, file, ajax_response ) {
		var response = $.parseJSON( ajax_response.response );
		if ( response.success ) {
			var galleryThumbHtml = '<img src="' + response.url + '" alt="" />' +
			'<a class="remove-image" data-attachment-id="' + response.attachment_id + '" href="#remove-image" >&times;</a>' +
			'<input type="hidden" class="gallery-image-id" name="gallery_ids" value="' + response.attachment_id + '"/>' ;
			$( '#img-' + file.id ).html( galleryThumbHtml );

			bindThumbnailEvents();
		}
		else {
			console.log ( response );
		}
	});

	/* Bind thumbnails events with newly added gallery thumbs */
	var bindThumbnailEvents = function () {
		// Remove gallery images
		$('a.remove-image').unbind('click');
		$('a.remove-image').click(function(event){
			event.preventDefault();
			var $this = $(this);
			var file_id = $(this).closest('li').attr('id').substr(4);
			var gallery_thumb = $this.closest('.gallery-thumb');
			var removal_request = $.ajax({
				url: ajaxurl,
				type: "POST",
				data: {
					attachment_id : $this.data('attachment-id'),
					action : "remove_upload_image"
				},
				dataType: "html"
			});
			removal_request.done(function( response ) {
				var result = $.parseJSON( response );
				if( result.attachment_removed ){
					gallery_thumb.remove();
					// delete featured images
					var file = featured_uploader.getFile( file_id );
					if( file ) {
						featured_uploader.removeFile(file);
					}
					var file_thumbnail = thumbnail_uploader.getFile( file_id );
					if( file_thumbnail ) {
						thumbnail_uploader.removeFile(file_thumbnail);
					}
					var file_trans_image = tran_image_uploader.getFile( file_id );
					if( file_trans_image ) {
						tran_image_uploader.removeFile(file_trans_image);
					}
				} else {
					$('#errors-log').html("Error : Failed to remove attachment");
				}
			});
			removal_request.fail(function( jqXHR, textStatus ) {
				alert( "Request failed: " + textStatus );
			});
		});
	};
	bindThumbnailEvents();

	/****************************Attachments***************************/
	var attachment_uploader = new plupload.Uploader({
		browse_button: 'select-attachment',
		file_data_name: 'slzexploore_core_upload_file',
		drop_element: 'drag-and-drop-attachment',
		url: ajaxurl + "?action=slzexploore_core_image_upload",
		filters: {
			prevent_duplicates: true
		}
	});
	attachment_uploader.init();

	/* Run after adding file */
	attachment_uploader.bind('FilesAdded', function(up, files) {
		var html = '';
		var galleryThumb = "";
		plupload.each(files, function(file) {
			galleryThumb += '<li id="img-' + file.id + '" class="gallery-thumb">' + '' + '</li>';
		});
		$('#attachment-errors-log').html('');
		$('#attachment-container').append(galleryThumb);
		up.refresh();
		attachment_uploader.start();
	});

	/* In case of error */
	attachment_uploader.bind('Error', function( up, err ) {
		$('#attachment-errors-log').html("Error #" + err.code + ": " + err.message);
	});

	/* If files are uploaded successfully */
	attachment_uploader.bind('FileUploaded', function ( up, file, ajax_response ) {
		var response = $.parseJSON( ajax_response.response );
		if ( response.success ) {
			var galleryThumbHtml = '<img src="' + response.url + '" alt="" />' +
			'<a class="remove-image" data-attachment-id="' + response.attachment_id + '" href="#remove-image" >&times;</a>' +
			'<input type="hidden" class="gallery-image-id" name="attachment_ids" value="' + response.attachment_id + '"/>' ;
			$( '#img-' + file.id ).html( galleryThumbHtml );

			bindThumbnailEvents();
		}
		else {
			console.log ( response );
		}
	});

	/**************************** Featured Images ***************************/
	var featured_uploader = new plupload.Uploader({
		browse_button: 'select-featured-image',
		file_data_name: 'slzexploore_core_upload_file',
		url: ajaxurl + "?action=slzexploore_core_image_upload",
		filters: {
			mime_types : [
				{ extensions : "jpg,jpeg,gif,png" }
			]
		}
	});
	featured_uploader.init();

	/* Run after adding file */
	featured_uploader.bind('FilesAdded', function(up, files) {
		var html = '';
		var galleryThumb = "";
		if(up.files.length > 1 || featured_uploader.files.length > 1 ) {
			alert( $('#select-featured-image').attr('data-msg') );
			featured_uploader.files.splice();
			$.each(files, function(i, file) {
				up.removeFile(file);
			});
			up.refresh();
			return false;
		}
		plupload.each(files, function(file) {
			galleryThumb += '<li id="img-' + file.id + '" class="gallery-thumb">' + '' + '</li>';
		});
		$('#featured-errors-log').html('');
		$('#featured-image-container').append(galleryThumb);
		up.refresh();
		featured_uploader.start();
	});

	/* In case of error */
	featured_uploader.bind('Error', function( up, err ) {
		$('#featured-errors-log').html("Error #" + err.code + ": " + err.message);
	});

	/* If files are uploaded successfully */
	featured_uploader.bind('FileUploaded', function ( up, file, ajax_response ) {
		var response = $.parseJSON( ajax_response.response );
		if ( response.success ) {
			var galleryThumbHtml = '<img src="' + response.url + '" alt="" />' +
			'<a class="remove-image" data-attachment-id="' + response.attachment_id + '" href="#remove-image" >&times;</a>' +
			'<input type="hidden" class="gallery-image-id" name="featured_image_id" value="' + response.attachment_id + '"/>' ;
			$( '#img-' + file.id ).html( galleryThumbHtml );

			bindThumbnailEvents();
		}
		else {
			console.log ( response );
		}
	});

	/**************************** Upload Thumbnail Images for Agent ***************************/
	var thumbnail_uploader = new plupload.Uploader({
		browse_button: 'select-single-image',
		file_data_name: 'slzexploore_core_upload_file',
		url: ajaxurl + "?action=slzexploore_core_image_upload",
		filters: {
			mime_types : [
				{ extensions : "jpg,jpeg,gif,png" }
			]
		}
	});
	thumbnail_uploader.init();

	/* Run after adding file */
	thumbnail_uploader.bind('FilesAdded', function(up, files) {
		var html = '';
		var galleryThumb = "";
		if(up.files.length > 1 || thumbnail_uploader.files.length > 1 ) {
			alert( $('#select-single-image').attr('data-msg') );
			thumbnail_uploader.files.splice();
			$.each(files, function(i, file) {
				up.removeFile(file);
			});
			up.refresh();
			return false;
		}
		plupload.each(files, function(file) {
			galleryThumb += '<li id="img-' + file.id + '" class="gallery-thumb">' + '' + '</li>';
		});
		$('#thumbnail-errors-log').html('');
		if ( $('#thumbnail-image-container').find('.gallery-thumb')[0]){
			$('#thumbnail-image-container').find('.gallery-thumb').replaceWith(galleryThumb);
		}else{
			$('#thumbnail-image-container').append(galleryThumb);
		}
		up.refresh();
		thumbnail_uploader.start();
	});
		/* In case of error */
	thumbnail_uploader.bind('Error', function( up, err ) {
		$('#thumbnail-errors-log').html("Error #" + err.code + ": " + err.message);
	});

	/* If files are uploaded successfully */
	thumbnail_uploader.bind('FileUploaded', function ( up, file, ajax_response ) {
		var response = $.parseJSON( ajax_response.response );
		if ( response.success ) {
			var galleryThumbHtml = '<img src="' + response.url + '" alt="" />' +
			'<a class="remove-image" data-attachment-id="' + response.attachment_id + '" href="#remove-image" >&times;</a>' +
			'<input type="hidden" class="gallery-image-id" name="thumbnail_image_id" value="' + response.attachment_id + '"/>' ;
			$( '#img-' + file.id ).html( galleryThumbHtml );

			bindThumbnailEvents();
		}
		else {
			console.log ( response );
		}
	});
	/**************************** Upload  Transparent Images for Agent ***************************/
	var tran_image_uploader = new plupload.Uploader({
		browse_button: 'select-transparent-image',
		file_data_name: 'slzexploore_core_upload_file',
		url: ajaxurl + "?action=slzexploore_core_image_upload",
		filters: {
			mime_types : [
				{ extensions : "jpg,jpeg,gif,png" }
			]
		}
	});
	tran_image_uploader.init();

	/* Run after adding file */
	tran_image_uploader.bind('FilesAdded', function(up, files) {
		var html = '';
		var galleryThumb = "";
		if(up.files.length > 1 || tran_image_uploader.files.length > 1 ) {
			alert( $('#select-single-image').attr('data-msg') );
			tran_image_uploader.files.splice();
			$.each(files, function(i, file) {
				up.removeFile(file);
			});
			up.refresh();
			return false;
		}
		plupload.each(files, function(file) {
			galleryThumb += '<li id="img-' + file.id + '" class="gallery-thumb">' + '' + '</li>';
		});
		$('#transparent-errors-log').html('');
		if ( $('#transparent-image-container').find('.gallery-thumb')[0]){
			$('#transparent-image-container').find('.gallery-thumb').replaceWith(galleryThumb);
		}else{
			$('#transparent-image-container').append(galleryThumb);
		}
		up.refresh();
		tran_image_uploader.start();
	});
		/* In case of error */
	tran_image_uploader.bind('Error', function( up, err ) {
		$('#transparent-errors-log').html("Error #" + err.code + ": " + err.message);
	});

	/* If files are uploaded successfully */
	tran_image_uploader.bind('FileUploaded', function ( up, file, ajax_response ) {
		var response = $.parseJSON( ajax_response.response );
		if ( response.success ) {
			var galleryThumbHtml = '<img src="' + response.url + '" alt="" />' +
			'<a class="remove-image" data-attachment-id="' + response.attachment_id + '" href="#remove-image" >&times;</a>' +
			'<input type="hidden" class="gallery-image-id" name="transparent_image_id" value="' + response.attachment_id + '"/>' ;
			$( '#img-' + file.id ).html( galleryThumbHtml );

			bindThumbnailEvents();
		}
		else {
			console.log ( response );
		}
	});
	/**************************** Profile image ***************************/
	var profile_uploader = new plupload.Uploader({
		browse_button: 'select-profile-image',
		file_data_name: 'slzexploore_core_upload_file',
		url: ajaxurl + "?action=slzexploore_core_image_upload",
		filters: {
			mime_types : [
				{ extensions : "jpg,jpeg,gif,png" }
			]
		}
	});
	profile_uploader.init();

	/* Run after adding file */
	profile_uploader.bind('FilesAdded', function(up, files) {
		var image_id = $('.profile-image-container .profile-image-id');
		if( image_id && image_id.val() ) {
			$.ajax({
				url: ajaxurl,
				type: "POST",
				data: {
					attachment_id : image_id.val(),
					action : "remove_upload_image"
				},
				dataType: "html"
			});
		}
		$('.profile-errors-log').html('');
		up.refresh();
		profile_uploader.start();
	});

	/* In case of error */
	profile_uploader.bind('Error', function( up, err ) {
		$('.profile-errors-log').html("Error #" + err.code + ": " + err.message);
	});

	/* If files are uploaded successfully */
	profile_uploader.bind('FileUploaded', function ( up, file, ajax_response ) {
		var response = $.parseJSON( ajax_response.response );
		if ( response.success ) {
			var galleryThumbHtml = '<img src="' + response.url + '" alt="" />' +
			'<input type="hidden" class="profile-image-id" name="profile_image_id" value="' + response.attachment_id + '"/>' ;
			$( '.profile-image-container' ).html( galleryThumbHtml );
			removeProfileImage();
		}
		else {
			console.log ( response );
		}
	});

	var removeProfileImage = function () {
		$('a#remove-profile-image').unbind('click');
		$('a#remove-profile-image').on( 'click', function(e){
			e.preventDefault();
			var image_id = $(this).closest('.property-padding').find('.profile-image-id');
			if( image_id && image_id.val() ) {
				$.ajax({
					url: ajaxurl,
					type: "POST",
					data: {
						attachment_id : image_id.val(),
						action : "remove_upload_image"
					},
					dataType: "html"
				}).done(function() {
					image_id.val('');
					image_id.prev('img').remove();
				});
			}
		});
	};
	removeProfileImage();
});