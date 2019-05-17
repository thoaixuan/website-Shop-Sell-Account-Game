 (function($){
    "use strict";
    
    $.kttkgallery = $.kttkgallery || {};
    
    $(document).ready(function () {
        $.kttkgallery();
    });

    $.kttkgallery = function(){
        // When the user clicks on the Add/Edit gallery button, we need to display the gallery editing
        $('body').on({
            click: function(event){
                var current_gallery = $(this).closest('.kttk_meta_image_gallery');

                if (event.currentTarget.id === 'clear-gallery') {
                    //remove value from input 
                    
                    var rmVal = current_gallery.find('.gallery_values').val('');

                    //remove preview images
                    current_gallery.find(".gallery_images").html("");

                    return;

                }

                // Make sure the media gallery API exists
                if ( typeof wp === 'undefined' || ! wp.media || ! wp.media.gallery ) {
                    return;
                }
                event.preventDefault();

                // Activate the media editor
                var $$ = $(this);

                var val = current_gallery.find('.gallery_values').val();
                wp.media.view.Settings.Gallery = wp.media.view.Settings.Gallery.extend({
                    template: function(view){
                      return;
                    },
                });
                var final;
                if (!val) {
                    var options = {
					frame: 'post',
					       state: 'gallery',
					       multiple: true
					};

					var frame = wp.media.editor.open('gallery_values',options);
                } else {
                    final = '[gallery ids="' + val + '"]';
                    frame = wp.media.gallery.edit(final);
                }


                    
                // When the gallery-edit state is updated, copy the attachment ids across
                frame.state('gallery-edit').on( 'update', function( selection ) {

                    //clear screenshot div so we can append new selected images
                    current_gallery.find(".gallery_images").html("");
                    
                    var element, preview_html= "", preview_img, img_id;
                    var ids = selection.models.map(function(e){
                        element = e.toJSON();
                        preview_img = typeof element.sizes.thumbnail !== 'undefined'  ? element.sizes.thumbnail.url : element.url ;
                        img_id = element.id;
                        preview_html = '<a class="of-uploaded-image edit-kttk-meta-gal" data-attachment-id="'+img_id+'" href="#"><img class="gallery-widget-image" src="'+preview_img+'" /></a>';
                        current_gallery.find(".gallery_images").append(preview_html);
                        return e.id;
                    });
                    current_gallery.find('.gallery_values').val(ids.join(','));
                     current_gallery.find( '.gallery_values' );
    
                });


                return false;
            }
        }, '.kttk-gallery-attachments');
    };
})(jQuery);

(function($){
    "use strict";
    
    $.kttk_attachment_gallery = $.kttk_attachment_gallery || {};
    
    $(document).ready(function () {
        $.kttk_attachment_gallery();
    });

    $.kttk_attachment_gallery = function(){
        // When the user clicks on the Add/Edit gallery button, we need to display the gallery editing
        $('body').on({
            click: function(event){
                var current_gallery = $(this).closest('.kttk_meta_image_gallery');
                var selected = $(this).data('attachment-id');

                // Make sure the media gallery API exists
                if ( typeof wp === 'undefined' || ! wp.media || ! wp.media.gallery ) {
                    return;
                }

                event.preventDefault();
                // Activate the media editor
                 wp.media.view.Settings.Gallery = wp.media.view.Settings.Gallery.extend({
                    template: function(view){
                      return;
                    },
                });
                var $$ = $(this);
                var val = current_gallery.find('.gallery_values').val();
                var final = '[gallery ids="' + val + '"]';
                var frame = wp.media.gallery.edit(final);
                
                // When the gallery-edit state is updated, copy the attachment ids across
                frame.state('gallery-edit').on( 'update', function( selection ) {

                    //clear screenshot div so we can append new selected images
                    current_gallery.find(".gallery_images").html("");
                    
                    var element, preview_html= "", preview_img, img_id;
                    var ids = selection.models.map(function(e){
                        element = e.toJSON();
                        preview_img = typeof element.sizes.thumbnail !== 'undefined'  ? element.sizes.thumbnail.url : element.url ;
                        img_id = element.id;
                        preview_html = '<a class="of-uploaded-image edit-kttk-meta-gal" data-attachment-id="'+img_id+'" href="#"><img class="gallery-widget-image" src="'+preview_img+'" /></a>';
                        current_gallery.find(".gallery_images").append(preview_html);
                        return e.id;
                    });
                    current_gallery.find('.gallery_values').val(ids.join(','));
                    current_gallery.find( '.gallery_values' );
    
                });


                return false;
            }
        }, '.edit-kttk-meta-gal');
    };
})(jQuery);


