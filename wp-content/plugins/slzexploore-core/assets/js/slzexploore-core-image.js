jQuery.slzexploore_init_gallery_uploader = function (args) {
	if( typeof args !== 'object' ) return;
    var gallery_frame,
        gallery_image_ids = args.input_elm ? args.input_elm : jQuery('#slzexploore_core_gallery_image_ids'),
        gallery_images = args.ul_elm ? args.ul_elm : jQuery('#slzexploore_core_gallery_container').find('ul.gallery_images'),
        btn_add_images = args.button_elm ? args.button_elm : jQuery('.btn-open-gallery');

    // when gallery link clicked
    btn_add_images.live('click', function (e) {
        e.preventDefault();

        // if exist frame, reuse it
        if (gallery_frame) {
            gallery_frame.open();
            return;
        }

        // init media library frame
        gallery_frame = wp.media.frames.meta_image_frame = wp.media({
            title: btn_add_images.data('title'),
            button: {text: btn_add_images.data('btn-text')},
            library: {type: 'image'},
            multiple: true
        });

        // when image selected
        gallery_frame.unbind('select');
        gallery_frame.on('select', function () {
            // Grabs the attachment selection and creates a JSON representation of the model.
            var selection = gallery_frame.state().get('selection');
            var attachment_ids = gallery_image_ids.val();

            selection.map(function (attachment) {
                attachment = attachment.toJSON();

                if (attachment.id) {
                    attachment_ids = attachment_ids ? attachment_ids + ',' + attachment.id : attachment.id;
                    var attachment_image = attachment.sizes && attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;

                    gallery_images.append('<li class="image" data-attachment_id="' + attachment.id + '"><img src="' + attachment_image + '" /><ul class="actions"><li><a href="#" class="delete" title="' + btn_add_images.data('delete') + '">&times;</a></li></ul></li>');
                }
            });

            gallery_image_ids.val(attachment_ids);
        });

        // open media library frame.
        gallery_frame.open();
        e.stopImmediatePropagation()
    });

    // remove images
    gallery_images.on('click', 'a.delete', function (e) {
    	e.preventDefault();

        jQuery(this).closest('li.image').remove();
        var attachment_ids = '';

        gallery_images.find('li.image').css('cursor', 'default').each(function () {
            var attachment_id = jQuery(this).attr('data-attachment_id');
            attachment_ids = attachment_ids + attachment_id + ',';
        });

        gallery_image_ids.val(attachment_ids);

        e.stopImmediatePropagation();
        return false;
    });

    // Image ordering
    gallery_images.sortable({
        items: 'li.image',
        cursor: 'move',
        scrollSensitivity: 40,
        forcePlaceholderSize: true,
        forceHelperSize: false,
        helper: 'clone',
        opacity: 0.65,
        placeholder: 'wc-metabox-sortable-placeholder',
        start: function (event, ui) {
            ui.item.css('background-color', '#f6f6f6');
        },
        stop: function (event, ui) {
            ui.item.removeAttr('style');
        },
        update: function () {
            var attachment_ids = '';

            gallery_images.find('li.image').css('cursor', 'default').each(function () {
                var attachment_id = jQuery(this).attr('data-attachment_id');
                attachment_ids = attachment_ids + attachment_id + ',';
            });

            gallery_image_ids.val(attachment_ids);
        }
    });
};

jQuery(document).ready(function($) {
	"use strict";

    /**
     * Upload Header Gallery for Post Type
     */
    $('#slz-tab-page-header-content .header_content_gallery').each(function (idx, dom) {
        var _this = $(dom);
        var btn = $('.btn-open-gallery', _this );
        jQuery.slzexploore_init_gallery_uploader({
            button_elm:btn,
            ul_elm: $('ul', _this),
            input_elm: $('input', _this)
        });
    });

	// Instantiates the variable that holds the media library frame.
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

	/*
	* Upload gallery image
	*/
	
	var slzexploore_core_gallery_frame;
	var slzexploore_core_gallery_image_ids = $( '#slzexploore_core_gallery_image_ids' );
	var slzexploore_core_gallery_images    = $( '#slzexploore_core_gallery_container' ).find( 'ul.gallery_images' );
	var slzexploore_core_btn_add_images;
	
	// Runs when the gallery link is clicked.
	$('.btn-open-gallery').live('click', function(e){

		// Prevents the default action from occuring.
		e.preventDefault();

		slzexploore_core_btn_add_images = $(this);
		// If the frame already exists, re-open it.
		if ( slzexploore_core_gallery_frame ) {
			slzexploore_core_gallery_frame.open();
			return;
		}

		// Sets up the media library frame
		slzexploore_core_gallery_frame = wp.media.frames.meta_image_frame = wp.media({
			title: slzexploore_core_btn_add_images.data( 'title' ),
			button: { text:  slzexploore_core_btn_add_images.data( 'btn-text' ) },
			library: { type: 'image' },
			multiple: true
		});

		// Runs when an image is selected.
		slzexploore_core_gallery_frame.on('select', function(){

			// Grabs the attachment selection and creates a JSON representation of the model.
			var selection = slzexploore_core_gallery_frame.state().get('selection');
			var attachment_ids = slzexploore_core_gallery_image_ids.val();

			selection.map( function( attachment ) {
				attachment = attachment.toJSON();

				if ( attachment.id ) {
					attachment_ids   = attachment_ids ? attachment_ids + ',' + attachment.id : attachment.id;
					var attachment_image = attachment.sizes && attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;

					slzexploore_core_gallery_images.append( '<li class="image" data-attachment_id="' + attachment.id + '"><img src="' + attachment_image + '" /><ul class="actions"><li><a href="#" class="delete" title="' + slzexploore_core_btn_add_images.data('delete') + '">&times;</a></li></ul></li>' );
				}
			});

			slzexploore_core_gallery_image_ids.val( attachment_ids );
		});

		// Opens the media library frame.
		slzexploore_core_gallery_frame.open();
	});
	
	// Remove images
	$( '#slzexploore_core_gallery_container ul.gallery_images' ).on( 'click', 'a.delete', function() {
		$( this ).closest( 'li.image' ).remove();

		var attachment_ids = '';

		$( '#slzexploore_core_gallery_container' ).find( 'ul li.image' ).css( 'cursor', 'default' ).each( function() {
			var attachment_id = jQuery( this ).attr( 'data-attachment_id' );
			attachment_ids = attachment_ids + attachment_id + ',';
		});

		slzexploore_core_gallery_image_ids.val( attachment_ids );

		return false;
	});
	
	// Image ordering
	slzexploore_core_gallery_images.sortable({
		items: 'li.image',
		cursor: 'move',
		scrollSensitivity: 40,
		forcePlaceholderSize: true,
		forceHelperSize: false,
		helper: 'clone',
		opacity: 0.65,
		placeholder: 'wc-metabox-sortable-placeholder',
		start: function( event, ui ) {
			ui.item.css( 'background-color', '#f6f6f6' );
		},
		stop: function( event, ui ) {
			ui.item.removeAttr( 'style' );
		},
		update: function() {
			var attachment_ids = '';

			$( '#slzexploore_core_gallery_container' ).find( 'ul li.image' ).css( 'cursor', 'default' ).each( function() {
				var attachment_id = jQuery( this ).attr( 'data-attachment_id' );
				attachment_ids = attachment_ids + attachment_id + ',';
			});

			slzexploore_core_gallery_image_ids.val( attachment_ids );
		}
	});

	
	/*
	* Upload attachment image
	*/
	
	var slzexploore_core_attachment_frame;
	var slzexploore_core_attachment_ids = $( '#slzexploore_core_attachment_image_ids' );
	var slzexploore_core_attachments    = $( '#slzexploore_core_attachment_container' ).find( 'ul.attachment_images' );
	var slzexploore_core_btn_add_attachment;
	
	// Runs when the gallery link is clicked.
	$('.btn-open-attachment').live('click', function(e){

		// Prevents the default action from occuring.
		e.preventDefault();

		slzexploore_core_btn_add_attachment = $(this);
		// If the frame already exists, re-open it.
		if ( slzexploore_core_attachment_frame ) {
			slzexploore_core_attachment_frame.open();
			return;
		}

		// Sets up the media library frame
		slzexploore_core_attachment_frame = wp.media.frames.meta_image_frame = wp.media({
			title: slzexploore_core_btn_add_attachment.data( 'title' ),
			button: { text:  slzexploore_core_btn_add_attachment.data( 'btn-text' ) },
			library: { type: '' },
			multiple: true
		});

		// Runs when an image is selected.
		slzexploore_core_attachment_frame.on('select', function(){

			// Grabs the attachment selection and creates a JSON representation of the model.
			var selection = slzexploore_core_attachment_frame.state().get('selection');
			var attachment_ids = slzexploore_core_attachment_ids.val();

			selection.map( function( attachment ) {
				attachment = attachment.toJSON();

				if ( attachment.id ) {
					attachment_ids   = attachment_ids ? attachment_ids + ',' + attachment.id : attachment.id;
					var attachment_image = attachment.sizes && attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;
					if(attachment.type != 'image') {
						attachment_image = attachment.icon;
					}

					slzexploore_core_attachments.append( '<li class="image" data-attachment_id="' + attachment.id + '"><div class="media-left"><img src="' + attachment_image + '" alt="" /></div><div class="media-right"><a href="' + attachment.url + '" class="title" title="' + attachment.title + '">' + attachment.title + '</a><div class="attachment_type">' + attachment.mime + '</div><a href="#" class="delete" title=""><i class="fa fa-times"></i>' + slzexploore_core_btn_add_attachment.data('delete') + '</a></div></li>' );
				}
			});

			slzexploore_core_attachment_ids.val( attachment_ids );
		});

		// Opens the media library frame.
		slzexploore_core_attachment_frame.open();
	});
	
	// Remove images
	slzexploore_core_attachments.on( 'click', 'a.delete', function() {
		$( this ).closest( 'li.image' ).remove();

		var attachment_ids = '';

		$( '#slzexploore_core_attachment_container' ).find( 'ul li.image' ).css( 'cursor', 'default' ).each( function() {
			var attachment_id = jQuery( this ).attr( 'data-attachment_id' );
			attachment_ids = attachment_ids + attachment_id + ',';
		});

		slzexploore_core_attachment_ids.val( attachment_ids );

		return false;
	});
	
	// Attachment ordering
	slzexploore_core_attachments.sortable({
		items: 'li.image',
		cursor: 'move',
		scrollSensitivity: 40,
		forcePlaceholderSize: true,
		forceHelperSize: false,
		helper: 'clone',
		opacity: 0.65,
		placeholder: 'wc-metabox-sortable-placeholder',
		start: function( event, ui ) {
			ui.item.css( 'background-color', '#f6f6f6' );
		},
		stop: function( event, ui ) {
			ui.item.removeAttr( 'style' );
		},
		update: function() {
			var attachment_ids = '';

			$( '#slzexploore_core_attachment_container' ).find( 'ul li.image' ).css( 'cursor', 'default' ).each( function() {
				var attachment_id = jQuery( this ).attr( 'data-attachment_id' );
				attachment_ids = attachment_ids + attachment_id + ',';
			});

			slzexploore_core_attachment_ids.val( attachment_ids );
		}
	});
});