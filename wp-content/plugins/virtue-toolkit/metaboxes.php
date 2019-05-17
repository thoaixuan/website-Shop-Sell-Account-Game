<?php
require_once( VIRTUE_TOOLKIT_PATH . 'cmb/init.php' );
add_action( 'init', 'initialize_kadence_toolkit_meta_boxes', 10 );
function initialize_kadence_toolkit_meta_boxes() {
	$the_theme = wp_get_theme();
	if ( 'Pinnacle' == $the_theme->get( 'Name' ) || 'pinnacle' == $the_theme->get( 'Template') ) {
		add_filter( 'cmb2_admin_init', 'kadence_toolkit_pinnacle_metaboxes', 100 );
	} else if( ($the_theme->get( 'Name' ) == 'Virtue' && $the_theme->get( 'Version') >= '2.3.5') || ($the_theme->get( 'Template') == 'virtue') ) {
		add_filter( 'cmb2_admin_init', 'kadence_toolkit_virtue_metaboxes', 100 );
	} else if( 'Ascend' == $the_theme->get( 'Name' ) || 'ascend' == $the_theme->get( 'Template')  ) {
		add_filter( 'cmb2_admin_init', 'kadence_toolkit_ascend_metabox');
	}

}
// Build Metaboxs for portfolio type select
add_action( 'cmb2_render_kttk_select_type', 'kadence_toolkit_render_select_type', 10, 2 );
function kadence_toolkit_render_select_type( $field, $meta ) {
    wp_dropdown_categories(array(
            'show_option_none' => __( "All Types", 'ascend' ),
            'hierarchical' => 1,
            'taxonomy' => $field->args( 'taxonomy'),
            'orderby' => 'name', 
            'hide_empty' => 0, 
            'name' => $field->args( 'id' ),
            'selected' => $meta  

        ));
    $desc = $field->args( 'desc' );
    if ( !empty( $desc ) ) {
    	echo '<p class="cmb_metabox_description">' . $desc . '</p>';
    }
}
// Build Metaboxs for gallery
function kadence_toolkit_ascend_gallery_field( $field, $meta ) {
    echo '<div class="kad-gallery kttk_meta_image_gallery">';
    echo '<div class="gallery_images">';
    $attachments = array_filter( explode( ',', $meta ) );
            if ( $attachments ) :
                foreach ( $attachments as $attachment_id ) {
                    $img = wp_get_attachment_image_src($attachment_id, 'thumbnail');
                    $imgfull = wp_get_attachment_image_src($attachment_id, 'full');
                    echo '<a class="of-uploaded-image edit-kttk-meta-gal" data-attachment-id="'.esc_attr($attachment_id).'"  href="#">';
                    echo '<img class="gallery-widget-image" id="gallery_widget_image_'.esc_attr($attachment_id). '" src="' . esc_url($img[0]) . '" width="'.esc_attr($img[1]).'" height="'.esc_attr($img[2]).'" />';
                    echo '</a>';
                }
            endif;
    echo '</div>';
    echo ' <input type="hidden" id="' . esc_attr($field->args( 'id' )) . '" name="' . esc_attr($field->args( 'id' )) . '" class="gallery_values" value="' . esc_attr($meta) . '" />';
    echo '<a href="#" onclick="return false;" id="edit-gallery" class="kttk-gallery-attachments button button-primary">' . __('Add/Edit Gallery', 'virtue-toolkit') . '</a>';
    echo '<a href="#" onclick="return false;" id="clear-gallery" class="kttk-gallery-attachments button">' . __('Clear Gallery', 'virtue-toolkit') . '</a>';
    echo '</div>';
    $desc = $field->args('desc');
    if ( !empty( $desc)) {
        echo '<p class="cmb_metabox_description">' . $field->args( 'desc' ) . '</p>';
    }
}
add_filter( 'cmb2_render_kad_gallery', 'kadence_toolkit_ascend_gallery_field', 10, 2 );


function kadence_toolkit_pinnacle_metaboxes() {

	// Start with an underscore to hide fields from custom fields list
	$prefix = '_kad_';
	$kttk_page_subtitle = new_cmb2_box( array(
		'id'         	=> 'subtitle_metabox',
		'title'      	=> __( "Page Title and Subtitle", 'virtue-toolkit' ),
		'object_types'	=> array( 'page'),
		'priority'   	=> 'high',
	) );
	$kttk_page_subtitle->add_field( array(
		'name'    => __( "Subtitle", 'virtue-toolkit' ),
		'desc'    => __( "Subtitle will go below page title", 'virtue-toolkit' ),
		'id'      => $prefix . 'subtitle',
		'type' => 'textarea_code',
	) );
	$kttk_page_subtitle->add_field( array(
		'name'    => __("Hide Page Title", 'virtue-toolkit' ),
		'desc'    => '',
		'id'      => $prefix . 'pagetitle_hide',
		'type'    => 'select',
		'options' => array(
			'default' 		=> __("Default", 'virtue-toolkit' ),
			'show' 			=> __("Show", 'virtue-toolkit' ),
			'hide' 			=> __("Hide", 'virtue-toolkit' ),
		),
	) );
	$kttk_page_subtitle->add_field( array(
		'name'    => __("Page Title background behind Header", 'virtue-toolkit' ),
		'desc'    => '',
		'id'      => $prefix . 'pagetitle_behind_head',
		'type'    => 'select',
		'options' => array(
			'default' 		=> __("Default", 'virtue-toolkit' ),
			'true' 			=> __("Place behind Header", 'virtue-toolkit' ),
			'false' 		=> __("Don't place behind Header", 'virtue-toolkit' ),
		),
	) );

	// Post Subtitle
	$kttk_post_subtitle = new_cmb2_box( array(
		'id'         	=> 'post_subtitle_metabox',
		'title'      	=> __( "Post Title and Subtitle", 'virtue-toolkit' ),
		'object_types'	=> array( 'product', 'post', 'portfolio'),
		'priority'   	=> 'high',
	) );
	$kttk_post_subtitle->add_field( array(
		'name'    => __( "Post Header Title", 'virtue-toolkit' ),
		'desc'    => '',
		'id'      => $prefix . 'post_header_title',
		'type' => 'textarea_code',
	) );
	$kttk_post_subtitle->add_field( array(
		'name'    => __( "Subtitle", 'virtue-toolkit' ),
		'desc'    => __( "Subtitle will go below post title", 'virtue-toolkit' ),
		'id'      => $prefix . 'subtitle',
		'type' => 'textarea_code',
	) );
	$kttk_post_subtitle->add_field( array(
		'name'    => __("Hide Post Title", 'virtue-toolkit' ),
		'desc'    => '',
		'id'      => $prefix . 'pagetitle_hide',
		'type'    => 'select',
		'options' => array(
			'default' 		=> __("Default", 'virtue-toolkit' ),
			'show' 			=> __("Show", 'virtue-toolkit' ),
			'hide' 			=> __("Hide", 'virtue-toolkit' ),
		),
	) );
	$kttk_post_subtitle->add_field( array(
		'name'    => __("Post Title background behind Header", 'virtue-toolkit' ),
		'desc'    => '',
		'id'      => $prefix . 'pagetitle_behind_head',
		'type'    => 'select',
		'options' => array(
			'default' 		=> __("Default", 'virtue-toolkit' ),
			'true' 			=> __("Place behind Header", 'virtue-toolkit' ),
			'false' 		=> __("Don't place behind Header", 'virtue-toolkit' ),
		),
	) );

	// Gallery Post
	$kttk_gallery_post = new_cmb2_box( array(
		'id'         	=> 'gallery_post_metabox',
		'title'      	=> __( "Gallery Post Options", 'virtue-toolkit' ),
		'object_types'	=> array( 'post'),
		'priority'   	=> 'high',
	) );
	$kttk_gallery_post->add_field( array(
		'name'    => __("Post Head Content", 'virtue-toolkit' ),
		'desc'    => '',
		'id'      => $prefix . 'gallery_blog_head',
		'type'    => 'select',
		'options' => array(
			'default' 			=> __("Gallery Post Default", 'virtue-toolkit' ),
			'flex' 				=> __("Image Slider - (Cropped Image Ratio)", 'virtue-toolkit' ),
			'carouselslider' 	=> __("Image Slider - (Different Image Ratio)", 'virtue-toolkit' ),
			'none' 				=> __("None", 'virtue-toolkit' ),
		),
	) );
	$kttk_gallery_post->add_field( array(
		'name'    => __("Post Slider Gallery", 'virtue-toolkit' ),
		'desc'    => __("Add images for gallery here", 'virtue-toolkit' ),
		'id'      => $prefix . 'image_gallery',
		'type' 	  => 'kad_gallery',
	) );
	$kttk_gallery_post->add_field( array(
		'name'    => __("Max Slider Height", 'virtue-toolkit' ),
		'desc'    => __("Default is: 400 (Note: just input number, example: 350)", 'virtue-toolkit' ),
		'id'      => $prefix . 'gallery_posthead_height',
		'type' 	  => 'text_small',
	) );
	$kttk_gallery_post->add_field( array(
		'name'    => __("Max Slider Width", 'virtue-toolkit' ),
		'desc'    => __("Default is: 848 or 1140 on fullwidth posts (Note: just input number, example: 650, only applys to Image Slider)", 'virtue-toolkit' ),
		'id'      => $prefix . 'gallery_posthead_width',
		'type' 	  => 'text_small',
	) );
	$kttk_gallery_post->add_field( array(
		'name'    => __("Post Summary", 'virtue-toolkit' ),
		'desc'    => '',
		'id'      => $prefix . 'gallery_post_summery',
		'type'    => 'select',
		'options' => array(
			'default' 			=> __("Gallery Post Default", 'virtue-toolkit' ),
			'img_portrait' 		=> __("Portrait Image (Featured image)", 'virtue-toolkit' ),
			'img_landscape' 	=> __("Landscape Image (Featured image)", 'virtue-toolkit' ),
			'slider_portrait' 	=> __("Portrait Image Slider", 'virtue-toolkit' ),
			'slider_landscape' 	=> __("Landscape Image Slider", 'virtue-toolkit' ),
		),
	) );

	// Video Post
	$kttk_video_post = new_cmb2_box( array(
		'id'         	=> 'video_post_metabox',
		'title'      	=> __( "Video Post Options", 'virtue-toolkit' ),
		'object_types'	=> array( 'post'),
		'priority'   	=> 'high',
	) );
	$kttk_video_post->add_field( array(
		'name'    => __("Post Head Content", 'virtue-toolkit' ),
		'desc'    => '',
		'id'      => $prefix . 'video_blog_head',
		'type'    => 'select',
		'options' => array(
			'default' 			=> __("Video Post Default", 'virtue-toolkit' ),
			'video' 			=> __("Video", 'virtue-toolkit' ),
			'none' 				=> __("None", 'virtue-toolkit' ),
		),
	) );
	$kttk_video_post->add_field( array(
		'name'    => __("Video Post embed code", 'virtue-toolkit' ),
		'desc'    => __('Place Embed Code Here, works with youtube, vimeo. (Use the featured image for screen shot)', 'virtue-toolkit'),
		'id'      => $prefix . 'post_video',
		'type' 	  => 'textarea_code',
	) );
	$kttk_video_post->add_field( array(
		'name'    => __("Max Video Width", 'virtue-toolkit' ),
		'desc'    => __("Default is: 848 or 1140 on fullwidth posts (Note: just input number, example: 650 )", 'virtue-toolkit' ),
		'id'      => $prefix . 'video_posthead_width',
		'type' 	  => 'text_small',
	) );
	$kttk_video_post->add_field( array(
		'name'    => __("Post Summary", 'virtue-toolkit' ),
		'desc'    => '',
		'id'      => $prefix . 'video_post_summery',
		'type'    => 'select',
		'options' => array(
			'default' 			=> __("Video Post Default", 'virtue-toolkit' ),
			'video' 			=> __("Video - (when possible)", 'virtue-toolkit' ),
			'img_portrait' 		=> __("Portrait Image (Featured image)", 'virtue-toolkit' ),
			'img_landscape' 	=> __("Landscape Image (Featured image)", 'virtue-toolkit' ),
		),
	) );

	// Portfolio post
	$kttk_portfolio_post = new_cmb2_box( array(
		'id'         	=> 'portfolio_post_metabox',
		'title'      	=> __( "Portfolio Post Options", 'virtue-toolkit' ),
		'object_types'	=> array( 'portfolio'),
		'priority'   	=> 'high',
	) );
	$kttk_portfolio_post->add_field( array(
		'name'    => __("Project Layout", 'virtue-toolkit' ),
		'desc'    => '<a href="http://docs.kadencethemes.com/pinnacle-free/portfolio-posts/" target="_blank" >Whats the difference?</a>',
		'id'      => $prefix . 'ppost_layout',
		'type'    => 'radio_inline',
		'options' => array(
			'beside' 			=> __("Beside 40%", 'virtue-toolkit' ),
			'besidesmall' 		=> __("Beside 33%", 'virtue-toolkit' ),
			'above' 			=> __("Above", 'virtue-toolkit' ),
			'three' 			=> __("Three Rows", 'virtue-toolkit' ),
		),
	) );
	$kttk_portfolio_post->add_field( array(
		'name'    => __("Project Options", 'virtue-toolkit' ),
		'desc'    => '',
		'id'      => $prefix . 'ppost_type',
		'type'    => 'select',
		'options' => array(
			'image' 		=> __("Image", 'virtue-toolkit' ),
			'flex' 			=> __("Image Slider (Cropped Image Ratio)", 'virtue-toolkit' ),
			'carousel' 		=> __("Image Slider (Different Image Ratio)", 'virtue-toolkit' ),
			'video' 		=> __("Video", 'virtue-toolkit' ),
			'imagegrid' 	=> __("Image Grid", 'virtue-toolkit' ),
			'none' 	=> __("None", 'virtue-toolkit' ),
		),
	) );
	$kttk_portfolio_post->add_field( array(
		'name'    => __("Columns (Only for Image Grid option)", 'virtue-toolkit' ),
		'desc'    => '',
		'id'      => $prefix . 'portfolio_img_grid_columns',
		'type'    => 'select',
		'options' => array(
			'4' 		=> __("Four Column", 'virtue-toolkit' ),
			'3' 		=> __("Three Column", 'virtue-toolkit' ),
			'2' 		=> __("Two Column", 'virtue-toolkit' ),
			'5' 		=> __("Five Column", 'virtue-toolkit' ),
			'6' 		=> __("Six Column", 'virtue-toolkit' ),
		),
	) );
	$kttk_portfolio_post->add_field( array(
		'name'    => __("Portfolio Slider/Images", 'virtue-toolkit' ),
		'desc'    => __("Add images for post here", 'virtue-toolkit' ),
		'id'      => $prefix . 'image_gallery',
		'type' => 'kad_gallery',
	) );
	$kttk_portfolio_post->add_field( array(
		'name'    => __("Max Image/Slider Height", 'virtue-toolkit' ),
		'desc'    => __("Default is: 450 (Note: just input number, example: 350)", 'virtue-toolkit' ),
		'id'      => $prefix . 'posthead_height',
		'type' => 'text_small',
	) );
	$kttk_portfolio_post->add_field( array(
		'name'    => __("Max Image/Slider Width", 'virtue-toolkit' ),
		'desc'    => __("Default is: 670 or 1140 on above or three row layouts (Note: just input number, example: 650)", 'virtue-toolkit' ),
		'id'      => $prefix . 'posthead_width',
		'type' => 'text_small',
	) );
	$kttk_portfolio_post->add_field( array(
		'name'    => __('Value 01 Title', 'virtue-toolkit'),
		'desc'    => __('ex. Project Type:', 'virtue-toolkit'),
		'id'      => $prefix . 'project_val01_title',
		'type' => 'text_medium',
	) );
	$kttk_portfolio_post->add_field( array(
		'name'    => __('Value 01 Description', 'virtue-toolkit'),
		'desc'    => __('ex. Character Illustration', 'virtue-toolkit'),
		'id'      => $prefix . 'project_val01_description',
		'type' => 'text_medium',
	) );
	$kttk_portfolio_post->add_field( array(
		'name'    => __('Value 02 Title', 'virtue-toolkit'),
		'desc'    => __('ex. Skills Needed:', 'virtue-toolkit'),
		'id'      => $prefix . 'project_val02_title',
		'type' => 'text_medium',
	) );
	$kttk_portfolio_post->add_field( array(
		'name'    => __('Value 02 Description', 'virtue-toolkit'),
		'desc'    => __('ex. Photoshop, Illustrator', 'virtue-toolkit'),
		'id'      => $prefix . 'project_val02_description',
		'type' => 'text_medium',
	) );
	$kttk_portfolio_post->add_field( array(
		'name'    => __('Value 03 Title', 'virtue-toolkit'),
		'desc'    => __('ex. Customer:', 'virtue-toolkit'),
		'id'      => $prefix . 'project_val03_title',
		'type' => 'text_medium',
	) );
	$kttk_portfolio_post->add_field( array(
		'name'    => __('Value 03 Description', 'virtue-toolkit'),
		'desc'    => __('ex. Example Inc', 'virtue-toolkit'),
		'id'      => $prefix . 'project_val03_description',
		'type' => 'text_medium',
	) );
	$kttk_portfolio_post->add_field( array(
		'name'    => __('Value 04 Title', 'virtue-toolkit'),
		'desc'    => __('ex. Project Year:', 'virtue-toolkit'),
		'id'      => $prefix . 'project_val04_title',
		'type' => 'text_medium',
	) );
	$kttk_portfolio_post->add_field( array(
		'name'    => __('Value 04 Description', 'virtue-toolkit'),
		'desc'    => __('ex. 2013', 'virtue-toolkit'),
		'id'      => $prefix . 'project_val04_description',
		'type' => 'text_medium',
	) );
	$kttk_portfolio_post->add_field( array(
		'name'    => __('External Website', 'virtue-toolkit'),
		'desc'    => __('ex. Website:', 'virtue-toolkit'),
		'id'      => $prefix . 'project_val05_title',
		'type' => 'text_medium',
	) );
	$kttk_portfolio_post->add_field( array(
		'name'    => __('Website Address', 'virtue-toolkit'),
		'desc'    => __('ex. http://www.example.com', 'virtue-toolkit'),
		'id'      => $prefix . 'project_val05_description',
		'type' => 'text_medium',
	) );
	$kttk_portfolio_post->add_field( array(
		'name'    => __('If Video Project', 'virtue-toolkit'),
		'desc'    => __('Place Embed Code Here, works with youtube, vimeo...', 'virtue-toolkit'),
		'id'      => $prefix . 'post_video',
		'type' => 'textarea_code',
	) );

	// Portfolio Post Carousel
	$kttk_portfolio_post_carousel = new_cmb2_box( array(
		'id'         	=> 'portfolio_post_carousel_metabox',
		'title'      	=> __( "Bottom Carousel Options", 'virtue-toolkit' ),
		'object_types'	=> array( 'portfolio'),
		'priority'   	=> 'high',
	) );
	$kttk_portfolio_post_carousel->add_field( array(
		'name'    => __("Carousel Title", 'virtue-toolkit' ),
		'desc'    => __('ex. Similar Projects', 'virtue-toolkit'),
		'id'      => $prefix . 'portfolio_carousel_title',
		'type' => 'text_medium',
	) );
	$kttk_portfolio_post_carousel->add_field( array(
		'name'    => __("Bottom Portfolio Carousel", 'virtue-toolkit' ),
		'desc'    => __('Display a carousel with portfolio items below project?', 'virtue-toolkit'),
		'id'      => $prefix . 'portfolio_carousel_recent',
		'type'    => 'select',
		'options' => array(
			'defualt' 	=> __("Default", 'virtue-toolkit' ),
			'no' 		=> __("No", 'virtue-toolkit' ),
			'yes' 		=> __("Yes", 'virtue-toolkit' ),
		),
	) );
	$kttk_portfolio_post_carousel->add_field( array(
		'name'    => __("Carousel Items", 'virtue-toolkit' ),
		'desc'    => '',
		'id'      => $prefix . 'portfolio_carousel_group',
		'type'    => 'select',
		'options' => array(
			'defualt' 	=> __("Default", 'virtue-toolkit' ),
			'all' 		=> __("All Portfolio Posts", 'virtue-toolkit' ),
			'cat' 		=> __("Only of same Portfolio Type", 'virtue-toolkit' ),
		),
	) );
	$kttk_portfolio_post_carousel->add_field( array(
		'name'    => __("Carousel Order", 'virtue-toolkit' ),
		'desc'    => '',
		'id'      => $prefix . 'portfolio_carousel_order',
		'type'    => 'select',
		'options' => array(
			'menu_order' 	=> __("Menu Order", 'virtue-toolkit' ),
			'title' 		=> __("Title", 'virtue-toolkit' ),
			'date' 			=> __("Date", 'virtue-toolkit' ),
			'rand' 			=> __("Random", 'virtue-toolkit' ),
		),
	) );

	// Portfolio Grid Page
	$kttk_portfolio_page = new_cmb2_box( array(
		'id'         	=> 'portfolio_metabox',
		'title'      	=> __( "Portfolio Page Options", 'virtue-toolkit' ),
		'object_types'	=> array( 'page'),
		'show_on'      	=> array( 'key' => 'page-template', 'value' => 'template-portfolio-grid.php' ),
		'priority'   	=> 'high',
	) );
	$kttk_portfolio_page->add_field( array(
		'name'    => __("Style", 'virtue-toolkit' ),
		'desc'    => '',
		'id'      => $prefix . 'portfolio_style',
		'type'    => 'select',
		'options' => array(
			'default' 			=> __("Default", 'virtue-toolkit' ),
			'padded_style' 		=> __("Post Boxes", 'virtue-toolkit' ),
			'flat-w-margin' 	=> __("Flat with Margin", 'virtue-toolkit' ),
		),
	) );
	$kttk_portfolio_page->add_field( array(
		'name'    => __("Hover Style", 'virtue-toolkit' ),
		'desc'    => '',
		'id'      => $prefix . 'portfolio_hover_style',
		'type'    => 'select',
		'options' => array(
			'default' 			=> __("Default", 'virtue-toolkit' ),
			'p_lightstyle' 		=> __("Light", 'virtue-toolkit' ),
			'p_darkstyle' 		=> __("Dark", 'virtue-toolkit' ),
			'p_primarystyle' 	=> __("Primary Color", 'virtue-toolkit' ),
		),
	) );
	$kttk_portfolio_page->add_field( array(
		'name'    => __("Columns", 'virtue-toolkit' ),
		'desc'    => '',
		'id'      => $prefix . 'portfolio_columns',
		'type'    => 'select',
		'options' => array(
			'4' 		=> __("Four Column", 'virtue-toolkit' ),
			'3' 		=> __("Three Column", 'virtue-toolkit' ),
			'2' 		=> __("Two Column", 'virtue-toolkit' ),
			'5' 		=> __("Five Column", 'virtue-toolkit' ),
		),
	) );
	$kttk_portfolio_page->add_field( array(
		'name'    => __("Portfolio Work Types", 'virtue-toolkit' ),
		'desc'    => '',
		'id'      => $prefix . 'portfolio_type',
		'type' 		=> 'kttk_select_type',
		'taxonomy' 	=> 'portfolio-type',
	) );
	$kttk_portfolio_page->add_field( array(
		'name'    => __("Order Items By", 'virtue-toolkit' ),
		'desc'    => '',
		'id'      => $prefix . 'portfolio_order',
		'type'    => 'select',
		'options' => array(
			'menu_order' 	=> __("Menu Order", 'virtue-toolkit' ),
			'title' 		=> __("Title", 'virtue-toolkit' ),
			'date' 			=> __("Date", 'virtue-toolkit' ),
			'rand' 			=> __("Random", 'virtue-toolkit' ),
		),
	) );
	$kttk_portfolio_page->add_field( array(
		'name'    => __("Items per Page", 'virtue-toolkit' ),
		'desc'    => __('How many portfolio items per page', 'virtue-toolkit'),
		'id'      => $prefix . 'portfolio_items',
		'type'    => 'select',
		'options' => array(
			'all' 	=> __("All", 'virtue-toolkit' ),
			'3' 	=> __("3", 'virtue-toolkit' ),
			'4' 	=> __("4", 'virtue-toolkit' ),
			'5' 	=> __("5", 'virtue-toolkit' ),
			'6' 	=> __("6", 'virtue-toolkit' ),
			'7' 	=> __("7", 'virtue-toolkit' ),
			'8' 	=> __("8", 'virtue-toolkit' ),
			'9' 	=> __("9", 'virtue-toolkit' ),
			'10' 	=> __("10", 'virtue-toolkit' ),
			'11' 	=> __("11", 'virtue-toolkit' ),
			'12' 	=> __("12", 'virtue-toolkit' ),
			'13' 	=> __("13", 'virtue-toolkit' ),
			'14' 	=> __("14", 'virtue-toolkit' ),
			'15' 	=> __("15", 'virtue-toolkit' ),
			'16' 	=> __("16", 'virtue-toolkit' ),
		),
	) );
	$kttk_portfolio_page->add_field( array(
		'name'    => __("Image Ratio?", 'virtue-toolkit' ),
		'desc'    => '',
		'id'      => $prefix . 'portfolio_img_ratio',
		'type'    => 'select',
		'options' => array(
			'menu_order' 		=> __("Default", 'virtue-toolkit' ),
			'square' 			=> __("Square 1:1", 'virtue-toolkit' ),
			'portrait' 			=> __("Portrait 3:4", 'virtue-toolkit' ),
			'landscape' 		=> __("Landscape 4:3", 'virtue-toolkit' ),
			'widelandscape' 	=> __("Wide Landscape 4:2", 'virtue-toolkit' ),
		),
	) );
	$kttk_portfolio_page->add_field( array(
		'name'    => __('Display Item Work Types', 'virtue-toolkit'),
		'desc'    => '',
		'id'      => $prefix . 'portfolio_item_types',
		'type' 	  => 'checkbox',
	) );
	$kttk_portfolio_page->add_field( array(
		'name'    => __('Display Item Excerpt', 'virtue-toolkit'),
		'desc'    => '',
		'id'      => $prefix . 'portfolio_item_excerpt',
		'type' 	  => 'checkbox',
	) );
	$kttk_portfolio_page->add_field( array(
		'name'    => __('Add Lightbox link in each item', 'virtue-toolkit'),
		'desc'    => '',
		'id'      => $prefix . 'portfolio_lightbox',
		'type' 	  => 'checkbox',
	) );
	
	// Page Feature
	$kttk_feature_page = new_cmb2_box( array(
		'id'         	=> 'pagefeature_metabox',
		'title'      	=> __( "Feature Page Options", 'virtue-toolkit' ),
		'object_types'	=> array( 'page' ),
		'show_on'      	=> array( 'key' => 'page-template', 'value' => 'template-feature.php' ),
		'priority'   	=> 'high',
	) );
	$kttk_feature_page->add_field( array(
		'name'    => __("Header Options", 'virtue-toolkit' ),
		'desc'    => __('If image slider make sure images uploaded are at-least 1170px wide.', 'virtue-toolkit'),
		'id'      => $prefix . 'page_head',
		'type'    => 'select',
		'default' => 'pagetitle',
		'options' => array(
			'pagetitle' 	=> __("Page Title", 'virtue-toolkit' ),
			'flex' 			=> __("Image Slider (Cropped Image Ratio)", 'virtue-toolkit' ),
			'carousel' 		=> __("Image Slider (Different Image Ratio)", 'virtue-toolkit' ),
			'video' 		=> __("Flat with Margin", 'virtue-toolkit' ),
		),
	) );
	$kttk_feature_page->add_field( array(
		'name'    => __("Slider Images", 'virtue-toolkit' ),
		'desc'    => __("Add images for post here", 'virtue-toolkit' ),
		'id'      => $prefix . 'image_gallery',
		'type' => 'kad_gallery',
	) );
	$kttk_feature_page->add_field( array(
		'name'    => __("Max Image/Slider Height", 'virtue-toolkit' ),
		'desc'    => __("Default is: 400 (Note: just input number, example: 350)", 'virtue-toolkit' ),
		'id'      => $prefix . 'posthead_height',
		'type' => 'text_small',
	) );
	$kttk_feature_page->add_field( array(
		'name'    => __("Max Image/Slider Width", 'virtue-toolkit' ),
		'desc'    => __("Default is: 1140 (Note: just input number, example: 650)", 'virtue-toolkit' ),
		'id'      => $prefix . 'posthead_width',
		'type' => 'text_small',
	) );
	$kttk_feature_page->add_field( array(
		'name'    => __("If Video Post", 'virtue-toolkit' ),
		'desc'    => __("Place Embed Code Here, works with youtube, vimeo.", 'virtue-toolkit' ),
		'id'      => $prefix . 'post_video',
		'type' => 'textarea_code',
	) );

	// Contact Page
	$kttk_contact_page = new_cmb2_box( array(
		'id'         	=> 'contact_metabox',
		'title'      	=> __( "Contact Page Options", 'virtue-toolkit' ),
		'object_types'	=> array( 'page' ),
		'show_on'      	=> array( 'key' => 'page-template', 'value' => 'template-contact.php' ),
		'priority'   	=> 'high',
	) );
	$kttk_contact_page->add_field( array(
		'name'    => __("Use Contact Form", 'virtue-toolkit' ),
		'desc'    => '',
		'id'      => $prefix . 'contact_form',
		'type'    => 'select',
		'options' => array(
			'yes' 		=> __("Yes", 'virtue-toolkit' ),
			'no' 		=> __("No", 'virtue-toolkit' ),
		),
	) );
	$kttk_contact_page->add_field( array(
		'name'    => __("Contact Form Title", 'virtue-toolkit' ),
		'desc'    => __('ex. Send us an Email', 'virtue-toolkit'),
		'id'      => $prefix . 'contact_form_title',
		'type'    => 'text',
	) );
	$kttk_contact_page->add_field( array(
		'name'    => __("Contact Form Email Recipient", 'virtue-toolkit' ),
		'desc'    => __('ex. joe@gmail.com', 'virtue-toolkit'),
		'id'      => $prefix . 'contact_form_email',
		'type'    => 'text',
	) );
	$kttk_contact_page->add_field( array(
		'name'    => __("Use Simple Math Question", 'virtue-toolkit' ),
		'desc'    => __('Adds a simple math question to form.', 'virtue-toolkit'),
		'id'      => $prefix . 'contact_form_math',
		'type'    => 'select',
		'options' => array(
			'yes' 		=> __("Yes", 'virtue-toolkit' ),
			'no' 		=> __("No", 'virtue-toolkit' ),
		),
	) );
	$kttk_contact_page->add_field( array(
		'name'    => __("Use Map", 'virtue-toolkit' ),
		'desc'    => __('You need free api for google maps to work, add in the theme options > misc settings.', 'virtue-toolkit'),
		'id'      => $prefix . 'contact_map',
		'type'    => 'select',
		'options' => array(
			'no' 		=> __("No", 'virtue-toolkit' ),
			'yes' 		=> __("Yes", 'virtue-toolkit' ),
		),
	) );
	$kttk_contact_page->add_field( array(
		'name'    => __("Address", 'virtue-toolkit' ),
		'desc'    => __('Enter your Location', 'virtue-toolkit'),
		'id'      => $prefix . 'contact_address',
		'type'    => 'text',
	) );
	$kttk_contact_page->add_field( array(
		'name'    => __("Map Type", 'virtue-toolkit' ),
		'desc'    => '',
		'id'      => $prefix . 'contact_maptype',
		'type'    => 'select',
		'options' => array(
			'ROADMAP' 		=> __("ROADMAP", 'virtue-toolkit' ),
			'HYBRID' 		=> __("HYBRID", 'virtue-toolkit' ),
			'TERRAIN' 		=> __("TERRAIN", 'virtue-toolkit' ),
			'SATELLITE' 	=> __("SATELLITE", 'virtue-toolkit' ),
		),
	) );
	$kttk_contact_page->add_field( array(
		'name'    => __("Map Zoom Level", 'virtue-toolkit' ),
		'desc'    => __('A good place to start is 15', 'virtue-toolkit'),
		'id'      => $prefix . 'contact_zoom',
		'type'    => 'select',
		'options' => array(
			'1' 	=> __("1 (World View)", 'virtue-toolkit' ),
			'2' 	=> __("2", 'virtue-toolkit' ),
			'3' 	=> __("3", 'virtue-toolkit' ),
			'4' 	=> __("4", 'virtue-toolkit' ),
			'5' 	=> __("5", 'virtue-toolkit' ),
			'6' 	=> __("6", 'virtue-toolkit' ),
			'7' 	=> __("7", 'virtue-toolkit' ),
			'8' 	=> __("8", 'virtue-toolkit' ),
			'9' 	=> __("9", 'virtue-toolkit' ),
			'10' 	=> __("10", 'virtue-toolkit' ),
			'11' 	=> __("11", 'virtue-toolkit' ),
			'12' 	=> __("12", 'virtue-toolkit' ),
			'13' 	=> __("13", 'virtue-toolkit' ),
			'14' 	=> __("14", 'virtue-toolkit' ),
			'15' 	=> __("15", 'virtue-toolkit' ),
			'16' 	=> __("16", 'virtue-toolkit' ),
			'17' 	=> __("17", 'virtue-toolkit' ),
			'18' 	=> __("18", 'virtue-toolkit' ),
			'19' 	=> __("19", 'virtue-toolkit' ),
			'20' 	=> __("20", 'virtue-toolkit' ),
			'21' 	=> __("21 (Street View)", 'virtue-toolkit' ),
		),
	) );
	$kttk_contact_page->add_field( array(
		'name'    => __("Map Height", 'virtue-toolkit' ),
		'desc'    => __('Default is 300', 'virtue-toolkit'),
		'id'      => $prefix . 'contact_mapheight',
		'type'    => 'text_small',
	) );

}


function kadence_toolkit_virtue_metaboxes() {

	$prefix = '_kad_';

	$kttk_page_subtitle = new_cmb2_box( array(
		'id'           => 'subtitle_metabox',
		'title'        => __( 'Page Title and Subtitle', 'virtue-toolkit' ),
		'object_types' => array( 'page' ),
		'priority'     => 'high',
	) );
	$kttk_page_subtitle->add_field( array(
		'name' => __( 'Subtitle', 'virtue-toolkit' ),
		'desc' => __( 'Subtitle will go below page title', 'virtue-toolkit' ),
		'id'   => $prefix . 'subtitle',
		'type' => 'textarea_small',
	) );
	
	// Video Post
	$kttk_video_post = new_cmb2_box( array(
		'id'         	=> 'post_video_metabox',
		'title'      	=> __( "Post Video Box", 'virtue-toolkit' ),
		'object_types'	=> array( 'post' ),
		'priority'   	=> 'high',
	) );
	$kttk_video_post->add_field( array(
		'name'    => __("If Video Post", 'virtue-toolkit' ),
		'desc'    => __('Place Embed Code Here, works with youtube, vimeo...', 'virtue-toolkit'),
		'id'      => $prefix . 'post_video',
		'type'    => 'textarea_code',
	) );

	// Portfolio Post
	$kttk_portfolio_post = new_cmb2_box( array(
		'id'         	=> 'portfolio_post_metabox',
		'title'      	=> __( "Portfolio Post Options", 'virtue-toolkit' ),
		'object_types'	=> array( 'portfolio' ),
		'priority'   	=> 'high',
	) );
	$kttk_portfolio_post->add_field( array(
		'name'    => __("Project Layout", 'virtue-toolkit' ),
		'desc'    => '<a href="http://docs.kadencethemes.com/virtue-free/portfolio-posts/" target="_new" >Whats the difference?</a>',
		'id'      => $prefix . 'ppost_layout',
		'type'    => 'radio_inline',
		'options' => array(
			'beside' 		=> __("Beside", 'virtue-toolkit' ),
			'above' 		=> __("Above", 'virtue-toolkit' ),
			'three' 		=> __("Three Rows", 'virtue-toolkit' ),
		),
	) );
	$kttk_portfolio_post->add_field( array(
		'name'    => __("Project Options", 'virtue-toolkit' ),
		'desc'    => '',
		'id'      => $prefix . 'ppost_type',
		'type'    => 'select',
		'options' => array(
			'image' 		=> __("Image", 'virtue-toolkit' ),
			'flex' 			=> __("Image Slider", 'virtue-toolkit' ),
			'carousel' 		=> __("Carousel Slider", 'virtue-toolkit' ),
			'imagegrid' 	=> __("Image Grid", 'virtue-toolkit' ),
			'video' 		=> __("Video", 'virtue-toolkit' ),
			'none' 			=> __("None", 'virtue-toolkit' ),
		),
	) );
	$kttk_portfolio_post->add_field( array(
		'name'    => __("Columns (Only for Image Grid option)", 'virtue-toolkit' ),
		'desc'    => '',
		'id'      => $prefix . 'portfolio_img_grid_columns',
		'type'    => 'select',
		'options' => array(
			'4' 		=> __("Four Column", 'virtue-toolkit' ),
			'3' 		=> __("Three Column", 'virtue-toolkit' ),
			'2' 		=> __("Two Column", 'virtue-toolkit' ),
			'5' 		=> __("Five Column", 'virtue-toolkit' ),
			'6' 		=> __("Six Column", 'virtue-toolkit' ),
		),
	) );
	$kttk_portfolio_post->add_field( array(
		'name'    => __("Max Image/Slider Height", 'virtue-toolkit' ),
		'desc'    => __("Default is: 450 <b>(Note: just input number, example: 350)</b>", 'virtue-toolkit' ),
		'id'      => $prefix . 'posthead_height',
		'type'    => 'text_small',
	) );
	$kttk_portfolio_post->add_field( array(
		'name'    => __("Max Image/Slider Width", 'virtue-toolkit' ),
		'desc'    => __("Default is: 670 or 1140 on <b>above</b> or <b>three row</b> layouts (Note: just input number, example: 650)</b>", 'virtue-toolkit' ),
		'id'      => $prefix . 'posthead_width',
		'type'    => 'text_small',
	) );
	$kttk_portfolio_post->add_field( array(
		'name'    => __("Auto Play Slider?", 'virtue-toolkit' ),
		'desc'    => '',
		'id'      => $prefix . 'portfolio_autoplay',
		'type'    => 'select',
		'options' => array(
			'Yes' 		=> __("Yes", 'virtue-toolkit' ),
			'no' 		=> __("No", 'virtue-toolkit' ),
		),
	) );
	$kttk_portfolio_post->add_field( array(
		'name'    => __("Value 01 Title", 'virtue-toolkit' ),
		'desc'    => __('ex. Project Type:', 'virtue-toolkit'),
		'id'      => $prefix . 'project_val01_title',
		'type' 	  => 'text_medium',
	) );
	$kttk_portfolio_post->add_field( array(
		'name'    => __("Value 01 Description", 'virtue-toolkit' ),
		'desc'    => __('ex. Character Illustration', 'virtue-toolkit'),
		'id'      => $prefix . 'project_val01_description',
		'type' 	  => 'text_medium',
	) );
	$kttk_portfolio_post->add_field( array(
		'name'    => __("Value 02 Title", 'virtue-toolkit' ),
		'desc'    => __('ex. Skills Needed:', 'virtue-toolkit'),
		'id'      => $prefix . 'project_val02_title',
		'type' 	  => 'text_medium',
	) );
	$kttk_portfolio_post->add_field( array(
		'name'    => __("Value 02 Description", 'virtue-toolkit' ),
		'desc'    => __('ex. Photoshop, Illustrator', 'virtue-toolkit'),
		'id'      => $prefix . 'project_val02_description',
		'type' 	  => 'text_medium',
	) );
	$kttk_portfolio_post->add_field( array(
		'name'    => __("Value 03 Title", 'virtue-toolkit' ),
		'desc'    => __('ex. Customer:', 'virtue-toolkit'),
		'id'      => $prefix . 'project_val03_title',
		'type' 	  => 'text_medium',
	) );
	$kttk_portfolio_post->add_field( array(
		'name'    => __("Value 03 Description", 'virtue-toolkit' ),
		'desc'    => __('ex. Example Inc', 'virtue-toolkit'),
		'id'      => $prefix . 'project_val03_description',
		'type' 	  => 'text_medium',
	) );
	$kttk_portfolio_post->add_field( array(
		'name'    => __("Value 04 Title", 'virtue-toolkit' ),
		'desc'    => __('ex. Project Year:', 'virtue-toolkit'),
		'id'      => $prefix . 'project_val04_title',
		'type' 	  => 'text_medium',
	) );
	$kttk_portfolio_post->add_field( array(
		'name'    => __("Value 04 Description", 'virtue-toolkit' ),
		'desc'    => __('ex. 2013', 'virtue-toolkit'),
		'id'      => $prefix . 'project_val04_description',
		'type' 	  => 'text_medium',
	) );
	$kttk_portfolio_post->add_field( array(
		'name'    => __("External Website", 'virtue-toolkit' ),
		'desc'    => __('ex. Website:', 'virtue-toolkit'),
		'id'      => $prefix . 'project_val05_title',
		'type' 	  => 'text_medium',
	) );
	$kttk_portfolio_post->add_field( array(
		'name'    => __("Website Address", 'virtue-toolkit' ),
		'desc'    => __('ex. http://www.example.com', 'virtue-toolkit'),
		'id'      => $prefix . 'project_val05_description',
		'type' 	  => 'text_medium',
	) );
	$kttk_portfolio_post->add_field( array(
		'name'    => __("If Video Project", 'virtue-toolkit' ),
		'desc'    => __('Place Embed Code Here, works with youtube, vimeo...', 'virtue-toolkit'),
		'id'      => $prefix . 'post_video',
		'type' 	  => 'textarea_code',
	) );
	$kttk_portfolio_post->add_field( array(
		'name'    => __("Similar Portfolio Item Carousel", 'virtue-toolkit' ),
		'desc'    => __('Display a carousel with similar portfolio items below project?', 'virtue-toolkit'),
		'id'      => $prefix . 'portfolio_carousel_recent',
		'type'    => 'select',
		'options' => array(
			'no' 		=> __("No", 'virtue-toolkit' ),
			'yes' 		=> __("Yes - Display Recent Projects", 'virtue-toolkit' ),
		),
	) );
	$kttk_portfolio_post->add_field( array(
		'name'    => __("Carousel Title", 'virtue-toolkit' ),
		'desc'    => __('ex. Similar Projects', 'virtue-toolkit'),
		'id'      => $prefix . 'portfolio_carousel_title',
		'type' 	  => 'text_medium',
	) );

	// Portfolio Page Grid
	$kttk_portfolio_page = new_cmb2_box( array(
		'id'         	=> 'portfolio_metabox',
		'title'      	=> __( "Portfolio Page Options", 'virtue-toolkit' ),
		'object_types'	=> array( 'page' ),
		'show_on'      	=> array( 'key' => 'page-template', 'value' => 'page-portfolio.php' ),
		'priority'   	=> 'high',
	) );
	$kttk_portfolio_page->add_field( array(
		'name'    => __("Columns", 'virtue-toolkit' ),
		'desc'    => '',
		'id'      => $prefix . 'portfolio_columns',
		'type'    => 'select',
		'options' => array(
			'4' 		=> __("Four Column", 'virtue-toolkit' ),
			'3' 		=> __("Three Column", 'virtue-toolkit' ),
			'2' 		=> __("Two Column", 'virtue-toolkit' ),
		),
	) );
	$kttk_portfolio_page->add_field( array(
		'name'    	=> __("Portfolio Work Types", 'virtue-toolkit' ),
		'desc'    	=> '',
		'id'     	=> $prefix . 'portfolio_type',
		'type'    	=> 'kttk_select_type',
		'taxonomy' 	=> 'portfolio-type',
	) );
	$kttk_portfolio_page->add_field( array(
		'name'    => __("Order Items By", 'virtue-toolkit' ),
		'desc'    => '',
		'id'      => $prefix . 'portfolio_order',
		'type'    => 'select',
		'options' => array(
			'menu_order' 	=> __("Menu Order", 'virtue-toolkit' ),
			'title' 		=> __("Title", 'virtue-toolkit' ),
			'date' 			=> __("Date", 'virtue-toolkit' ),
			'rand' 			=> __("Random", 'virtue-toolkit' ),
		),
	) );
	$kttk_portfolio_page->add_field( array(
		'name'    => __("Items per Page", 'virtue-toolkit' ),
		'desc'    => __('How many portfolio items per page', 'virtue-toolkit'),
		'id'      => $prefix . 'portfolio_items',
		'type'    => 'select',
		'options' => array(
			'all' 		=> __("All", 'virtue-toolkit' ),
			'3' 		=> __("3", 'virtue-toolkit' ),
			'4' 		=> __("4", 'virtue-toolkit' ),
			'5' 		=> __("5", 'virtue-toolkit' ),
			'6' 		=> __("6", 'virtue-toolkit' ),
			'7' 		=> __("7", 'virtue-toolkit' ),
			'8' 		=> __("8", 'virtue-toolkit' ),
			'9' 		=> __("9", 'virtue-toolkit' ),
			'10' 		=> __("10", 'virtue-toolkit' ),
			'11' 		=> __("11", 'virtue-toolkit' ),
			'12' 		=> __("12", 'virtue-toolkit' ),
			'13' 		=> __("13", 'virtue-toolkit' ),
			'14' 		=> __("14", 'virtue-toolkit' ),
			'15' 		=> __("15", 'virtue-toolkit' ),
			'16' 		=> __("16", 'virtue-toolkit' ),
		),
	) );
	$kttk_portfolio_page->add_field( array(
		'name'    => __("Set image height", 'virtue-toolkit' ),
		'desc'    => __('Default is 1:1 ratio <b>(Note: just input number, example: 350)</b>', 'virtue-toolkit'),
		'id'      => $prefix . 'portfolio_img_crop',
		'type' => 'text_small',
	) );
	$kttk_portfolio_page->add_field( array(
		'name'    => __("Display Item Work Types", 'virtue-toolkit' ),
		'desc'    => '',
		'id'      => $prefix . 'portfolio_item_types',
		'type' => 'checkbox',
	) );
	$kttk_portfolio_page->add_field( array(
		'name'    => __("Display Item Excerpt", 'virtue-toolkit' ),
		'desc'    => '',
		'id'      => $prefix . 'portfolio_item_excerpt',
		'type' => 'checkbox',
	) );
	$kttk_portfolio_page->add_field( array(
		'name'    => __("Add Lightbox link in the top right of each item", 'virtue-toolkit' ),
		'desc'    => '',
		'id'      => $prefix . 'portfolio_lightbox',
		'type'    => 'select',
		'options' => array(
			'no' 		=> __("No", 'virtue-toolkit' ),
			'yes' 		=> __("Yes", 'virtue-toolkit' ),
		),
	) );

	// Feature Page
	$kttk_feature_page = new_cmb2_box( array(
		'id'         	=> 'pagefeature_metabox',
		'title'      	=> __( "Feature Page Options", 'virtue-toolkit' ),
		'object_types'	=> array( 'page' ),
		'show_on'      	=> array( 'key' => 'page-template', 'value' => array( 'page-feature.php', 'page-feature-sidebar.php') ),
		'priority'   	=> 'high',
	) );
	$kttk_feature_page->add_field( array(
		'name'    => __("Feature Options", 'virtue-toolkit' ),
		'desc'    => __('If image slider make sure images uploaded are at least 1140px wide.', 'virtue-toolkit'),
		'id'      => $prefix . 'page_head',
		'type'    => 'select',
		'options' => array(
			'flex' 		=> __("Image Slider", 'virtue-toolkit' ),
			'video' 	=> __("Video", 'virtue-toolkit' ),
			'image' 	=> __("Image", 'virtue-toolkit' ),
		),
	) );
	$kttk_feature_page->add_field( array(
		'name'    => __("Slider Gallery", 'virtue-toolkit' ),
		'desc'    => __('Add images for gallery here', 'virtue-toolkit'),
		'id'      => $prefix . 'image_gallery',
		'type' 	  => 'kad_gallery',
	) );
	$kttk_feature_page->add_field( array(
		'name'    => __("Max Image/Slider Height", 'virtue-toolkit' ),
		'desc'    => __('Default is: 400 <b>(Note: just input number, example: 350)</b>', 'virtue-toolkit'),
		'id'      => $prefix . 'posthead_height',
		'type' 	  => 'text_small',
	) );
	$kttk_feature_page->add_field( array(
		'name'    => __("Max Image/Slider Width", 'virtue-toolkit' ),
		'desc'    => __('Default is: 1140 <b>(Note: just input number, example: 650, does not apply to Carousel slider)</b>', 'virtue-toolkit'),
		'id'      => $prefix . 'posthead_width',
		'type' 	  => 'text_small',
	) );
	$kttk_feature_page->add_field( array(
		'name'    => __("Use Lightbox for Feature Image", 'virtue-toolkit' ),
		'desc'    => __('If feature option is set to image, choose to use lightbox link with image.', 'virtue-toolkit'),
		'id'      => $prefix . 'feature_img_lightbox',
		'type' 	  => 'select',
		'options' => array(
			'yes' 		=> __("Yes", 'virtue-toolkit' ),
			'no' 		=> __("No", 'virtue-toolkit' ),
		),
	) );
	$kttk_feature_page->add_field( array(
		'name'    => __("If Video Post", 'virtue-toolkit' ),
		'desc'    => __('Place Embed Code Here, works with youtube, vimeo...', 'virtue-toolkit'),
		'id'      => $prefix . 'post_video',
		'type' 	  => 'textarea_code',
	) );

	// Contact Page 
	$kttk_contact_page = new_cmb2_box( array(
		'id'         	=> 'contact_metabox',
		'title'      	=> __( "Contact Page Options", 'virtue-toolkit' ),
		'object_types'	=> array( 'page' ),
		'show_on'      	=> array( 'key' => 'page-template', 'value' => 'page-contact.php' ),
		'priority'   	=> 'high',
	) );
	$kttk_contact_page->add_field( array(
		'name'    => __("Use Contact Form", 'virtue-toolkit' ),
		'desc'    => '',
		'id'      => $prefix . 'contact_form',
		'type'    => 'select',
		'options' => array(
			'yes' 	=> __("Yes", 'virtue-toolkit' ),
			'no' 	=> __("No", 'virtue-toolkit' ),
		),
	) );
	$kttk_contact_page->add_field( array(
		'name'    => __("Contact Form Title", 'virtue-toolkit' ),
		'desc'    => __('ex. Send us an Email', 'virtue-toolkit'),
		'id'      => $prefix . 'contact_form_title',
		'type'    => 'text',
	) );
	$kttk_contact_page->add_field( array(
		'name'    => __("Use Simple Math Question", 'virtue-toolkit' ),
		'desc'    => __('Adds a simple math question to form.', 'virtue-toolkit'),
		'id'      => $prefix . 'contact_form_math',
		'type'    => 'select',
		'options' => array(
			'yes' 	=> __("Yes", 'virtue-toolkit' ),
			'no' 	=> __("No", 'virtue-toolkit' ),
		),
	) );
	$kttk_contact_page->add_field( array(
		'name'    => __("Use Map", 'virtue-toolkit' ),
		'desc'    => __('You need free api for google maps to work, add in the theme options > misc settings.', 'virtue-toolkit'),
		'id'      => $prefix . 'contact_map',
		'type'    => 'select',
		'options' => array(
			'no' 	=> __("No", 'virtue-toolkit' ),
			'yes' 	=> __("Yes", 'virtue-toolkit' ),
		),
	) );
	$kttk_contact_page->add_field( array(
		'name'    => __("Address", 'virtue-toolkit' ),
		'desc'    => __('Enter your Location', 'virtue-toolkit'),
		'id'      => $prefix . 'contact_address',
		'type'    => 'text',
	) );
	$kttk_contact_page->add_field( array(
		'name'    => __("Map Type", 'virtue-toolkit' ),
		'desc'    => '',
		'id'      => $prefix . 'contact_maptype',
		'type'    => 'select',
		'options' => array(
			'ROADMAP' 		=> __("ROADMAP", 'virtue-toolkit' ),
			'HYBRID' 		=> __("HYBRID", 'virtue-toolkit' ),
			'TERRAIN' 		=> __("TERRAIN", 'virtue-toolkit' ),
			'SATELLITE' 	=> __("SATELLITE", 'virtue-toolkit' ),
		),
	) );
	$kttk_contact_page->add_field( array(
		'name'    => __("Map Zoom Level", 'virtue-toolkit' ),
		'desc'    => __('A good place to start is 15', 'virtue-toolkit'),
		'id'      => $prefix . 'contact_zoom',
		'type'    => 'select',
		'options' => array(
			'1' 	=> __("1 (World View)", 'virtue-toolkit' ),
			'2' 	=> __("2", 'virtue-toolkit' ),
			'3' 	=> __("3", 'virtue-toolkit' ),
			'4' 	=> __("4", 'virtue-toolkit' ),
			'5' 	=> __("5", 'virtue-toolkit' ),
			'6' 	=> __("6", 'virtue-toolkit' ),
			'7' 	=> __("7", 'virtue-toolkit' ),
			'8' 	=> __("8", 'virtue-toolkit' ),
			'9' 	=> __("9", 'virtue-toolkit' ),
			'10' 	=> __("10", 'virtue-toolkit' ),
			'11' 	=> __("11", 'virtue-toolkit' ),
			'12' 	=> __("12", 'virtue-toolkit' ),
			'13' 	=> __("13", 'virtue-toolkit' ),
			'14' 	=> __("14", 'virtue-toolkit' ),
			'15' 	=> __("15", 'virtue-toolkit' ),
			'16' 	=> __("16", 'virtue-toolkit' ),
			'17' 	=> __("17", 'virtue-toolkit' ),
			'18' 	=> __("18", 'virtue-toolkit' ),
			'19' 	=> __("19", 'virtue-toolkit' ),
			'20' 	=> __("20", 'virtue-toolkit' ),
			'21' 	=> __("21 (Street View)", 'virtue-toolkit' ),
		),
	) );
	$kttk_contact_page->add_field( array(
		'name'    => __("Map Height", 'virtue-toolkit' ),
		'desc'    => __('Default is 300', 'virtue-toolkit'),
		'id'      => $prefix . 'contact_mapheight',
		'type'    => 'text_small',
	) );

	// Gallery Post
	$kttk_gallery_post = new_cmb2_box( array(
		'id'         	=> 'virtue_post_gallery',
		'title'      	=> __( "Slider Images", 'virtue-toolkit' ),
		'object_types'	=> array( 'post', 'portfolio' ),
		'priority'   	=> 'high',
	) );
	$kttk_gallery_post->add_field( array(
		'name'    => __("Slider Gallery", 'virtue-toolkit' ),
		'desc'    => __('Add images for gallery here', 'virtue-toolkit'),
		'id'      => $prefix . 'image_gallery',
		'type'    => 'kad_gallery',
	) );

}


function kadence_toolkit_ascend_metabox() {
	$prefix = '_kad_';
	// GALLERY POST
	$kt_gallery_post = new_cmb2_box( array(
		'id'         	=> 'gallery_post_metabox',
		'title'      	=> __("Gallery Post Options", 'virtue-toolkit'),
		'object_types'	=> array( 'post'),
		'priority'   	=> 'high',
	) );
	
	$kt_gallery_post->add_field( array(
		'name'    => __("Post Head Content", 'virtue-toolkit' ),
		'desc'    => '',
		'id'      => $prefix . 'gallery_blog_head',
		'type'    => 'select',
		'options' => array(
			'default' 			=> __("Gallery Post Default", 'virtue-toolkit' ),
			'flex' 				=> __("Image Slider - (Cropped Image Ratio)", 'virtue-toolkit' ),
			'carouselslider' 	=> __("Image Slider - (Different Image Ratio)", 'virtue-toolkit' ),
			'thumbslider' 		=> __("Image Slider with thumbnails - (Cropped Image Ratio)", 'virtue-toolkit' ),
			'imgcarousel' 		=> __("Image Carousel - (Muiltiple Images Showing At Once)", 'virtue-toolkit' ),
			'gallery' 			=> __("Image Collage - (Use 2 to 5 images)", 'virtue-toolkit' ),
			'shortcode' 		=> __("Shortcode", 'virtue-toolkit' ),
			'none' 				=> __("None", 'virtue-toolkit' ),
			),
	) );
	$kt_gallery_post->add_field( array(
		'name' => __("Post Slider Gallery", 'virtue-toolkit' ),
		'desc' => __("Add images for gallery here - Use large images", 'virtue-toolkit' ),
		'id'   => $prefix . 'image_gallery',
		'type' => 'kad_gallery',
	) );

	$kt_gallery_post->add_field( array(
		'name' => __('Gallery Post Shortcode', 'virtue-toolkit'),
		'desc' => __('If using shortcode place here.', 'virtue-toolkit'),
		'id'   => $prefix . 'post_gallery_shortcode',
		'type' => 'textarea_code',
	) );
	$kt_gallery_post->add_field( array(
		'name' => __("Max Slider/Image Height", 'virtue-toolkit' ),
		'desc' => __("Note: just input number, example: 350", 'virtue-toolkit' ),
		'id'   => $prefix . 'gallery_posthead_height',
		'type' => 'text_small',
	) );
	$kt_gallery_post->add_field( array(
		'name' => __("Max Slider/Image Width", 'virtue-toolkit' ),
		'desc' => __("Note: just input number, example: 650", 'virtue-toolkit' ),
		'id'   => $prefix . 'gallery_posthead_width',
		'type' => 'text_small',
	) );
	$kt_gallery_post->add_field( array(
		'name'    => __("Post Summary", 'virtue-toolkit' ),
		'desc'    => '',
		'id'      => $prefix . 'gallery_post_summery',
		'type'    => 'select',
		'options' => array(
			'default' 			=> __('Gallery Post Default', 'virtue-toolkit' ),
			'img_portrait' 		=> __('Portrait Image (feature image)', 'virtue-toolkit'),
			'img_landscape' 	=> __('Landscape Image (feature image)', 'virtue-toolkit'),
			'slider_portrait' 	=> __('Portrait Image Slider', 'virtue-toolkit'),
			'slider_landscape' 	=> __('Landscape Image Slider', 'virtue-toolkit'),
			'gallery_grid' 		=> __('Photo Collage - (Use 2 to 5 images)', 'virtue-toolkit'),
			),
	) );
	// VIDEO POST
	$kt_video_post = new_cmb2_box( array(
		'id'         	=> 'video_post_metabox',
		'title'      	=> __("Video Post Options", 'virtue-toolkit'),
		'object_types'  => array( 'post'),
		'priority'   	=> 'high',
	) );
	$kt_video_post->add_field( array(
		'name'    => __("Post Head Content", 'virtue-toolkit' ),
		'desc'    => '',
		'id'      => $prefix . 'video_blog_head',
		'type'    => 'select',
		'options' => array(
			'default' 	=> __("Video Post Default", 'virtue-toolkit' ),
			'video' 	=> __("Video", 'virtue-toolkit' ),
			'none' 		=> __("None", 'virtue-toolkit' ),
			),
	) );

	$kt_video_post->add_field( array(
		'name' => __('Video post embed', 'virtue-toolkit'),
		'desc' => __('Place url, embed code or shortcode, works with youtube, vimeo. (Use the featured image for screen shot)', 'virtue-toolkit'),
		'id'   => $prefix . 'post_video',
		'type' => 'textarea_code',
	) );
	$kt_video_post->add_field( array(
		'name' => __("Video Meta Title", 'virtue-toolkit' ),
		'desc' => __("Used for SEO purposes", 'virtue-toolkit' ),
		'id'   => $prefix . 'video_meta_title',
		'type' => 'text',
	) );
	$kt_video_post->add_field( array(
		'name' => __("Video Meta description", 'virtue-toolkit' ),
		'desc' => __("Used for SEO purposes", 'virtue-toolkit' ),
		'id'   => $prefix . 'video_meta_description',
		'type' => 'text',
	) );
	$kt_video_post->add_field( array(
		'name' => __("Max Video Width", 'virtue-toolkit' ),
		'desc' => __("Note: just input number, example: 650", 'virtue-toolkit' ),
		'id'   => $prefix . 'video_posthead_width',
		'type' => 'text_small',
	) );
	$kt_video_post->add_field( array(
		'name'    => __("Post Summary", 'virtue-toolkit' ),
		'desc'    => '',
		'id'      => $prefix . 'video_post_summery',
		'type'    => 'select',
		'options' => array(
			'default' 		=> __('Video Post Default', 'virtue-toolkit' ),
			'video' 		=> __('Video - (when possible)', 'virtue-toolkit'),
			'img_portrait' 	=> __('Portrait Image (feature image)', 'virtue-toolkit'),
			'img_landscape' => __('Landscape Image (feature image)', 'virtue-toolkit'),
			),
	) );
	// Quote
	$kt_quote_post = new_cmb2_box( array(
		'id'         	=> 'quote_post_metabox',
		'title'      	=> __("Quote Post Options", 'virtue-toolkit'),
		'object_types'  => array( 'post'),
		'priority'   	=> 'high',
	) );
	$kt_quote_post->add_field( array(
		'name' => __("Quote author", 'virtue-toolkit' ),
		'id'   => $prefix . 'quote_author',
		'type' => 'text',
	) );

	// Portfolio
	$kt_portfolio_post = new_cmb2_box( array(
		'id'         	=> 'portfolio_post_metabox',
		'title'      	=> __("Portfolio Options", 'virtue-toolkit'),
		'object_types'  => array('portfolio'),
		'priority'   	=> 'high',
	) );
	$kt_portfolio_post->add_field( array(
		'name'    => __('Project Layout', 'virtue-toolkit'),
		'desc'    => '',
		'id'      => $prefix . 'ppost_layout',
		'type'    => 'radio_inline',
		'options' => array(
			'default' 		=> __("Default", 'virtue-toolkit' ),
			'beside' 		=> __("Beside 40%", 'virtue-toolkit' ),
			'besidesmall' 	=> __("Beside 33%", 'virtue-toolkit' ),
			'above' 		=> __("Above", 'virtue-toolkit' ),
		),
	) );
	$kt_portfolio_post->add_field( array(
		'name'    => __('Project Options', 'virtue-toolkit'),
		'desc'    => '',
		'id'      => $prefix . 'ppost_type',
		'type'    => 'select',
		'options' => array(
			'image' 			=> __("Image", 'virtue-toolkit' ),
			'flex' 				=> __("Image Slider (Cropped Image Ratio)", 'virtue-toolkit' ),
			'carouselslider' 	=> __("Image Slider - (Different Image Ratio)", 'virtue-toolkit' ),
			'thumbslider' 		=> __("Image Slider with thumbnails (Cropped Image Ratio)", 'virtue-toolkit' ),
			'imgcarousel' 		=> __("Image Carousel - (Muiltiple Images Showing At Once)", 'virtue-toolkit' ),
			'collage' 			=> __("Image Collage - (Use 2 to 5 images)", 'virtue-toolkit' ),
			'imagegrid' 		=> __("Image Grid", 'virtue-toolkit' ),
			'video' 			=> __("Video", 'virtue-toolkit' ),
			'none' 				=> __("None", 'virtue-toolkit' ),
		),
	) );
	$kt_portfolio_post->add_field( array(
		'name'    => __('Columns', 'virtue-toolkit'),
		'desc'    => '',
		'id'      => $prefix . 'portfolio_img_grid_columns',
		'type'    => 'select',
		'default' => '3',
		'options' => array(
			'2' 		=> __("Two Columns", 'virtue-toolkit' ),
			'3' 		=> __("Three Columns", 'virtue-toolkit' ),
			'4' 		=> __("Four Columns", 'virtue-toolkit' ),
			'5' 		=> __("Five Columns", 'virtue-toolkit' ),
			'6' 		=> __("Six Columns", 'virtue-toolkit' ),
		),
	) );
	$kt_portfolio_post->add_field( array(
		'name' => __("Portfolio Image Gallery", 'virtue-toolkit' ),
		'desc' => __("Add images for gallery here - Use large images", 'virtue-toolkit' ),
		'id'   => $prefix . 'image_gallery',
		'type' => 'kad_gallery',
	) );
	$kt_portfolio_post->add_field( array(
		'name' => __('Video embed', 'virtue-toolkit'),
		'desc' => __('Place url, embed code or shortcode, works with youtube, vimeo. (Use the featured image for screen shot)', 'virtue-toolkit'),
		'id'   => $prefix . 'post_video',
		'type' => 'textarea_code',
	) );
	$kt_portfolio_post->add_field( array(
		'name' => __("Max Slider/Image Width", 'virtue-toolkit' ),
		'desc' => __("Note: just input number, example: 650", 'virtue-toolkit' ),
		'id'   => $prefix . 'portfolio_slider_width',
		'type' => 'text_small',
	) );
	$kt_portfolio_post->add_field( array(
		'name' => __("Max Slider/Image Height", 'virtue-toolkit' ),
		'desc' => __("Note: just input number, example: 350", 'virtue-toolkit' ),
		'id'   => $prefix . 'portfolio_slider_height',
		'type' => 'text_small',
	) );
	// Portfolio Carousel
	$kt_portfolio_carousel = new_cmb2_box( array(
		'id'         	=> 'portfolio_post_carousel_metabox',
		'title'      	=> __("Portfolio Bottom Carousel Options", 'virtue-toolkit'),
		'object_types'  => array('portfolio'),
		'priority'   	=> 'high',
	) );
	$kt_portfolio_carousel->add_field( array(
		'name' => __('Carousel Title', 'virtue-toolkit'),
		'desc' => __('ex. Similar Projects', 'virtue-toolkit'),
		'id'   => $prefix . 'portfolio_carousel_title',
		'type' => 'text_medium',
	));
	$kt_portfolio_carousel->add_field( array(
		'name' => __('Bottom Portfolio Carousel', 'virtue-toolkit'),
		'desc' => __('Display a carousel with portfolio items below project?', 'virtue-toolkit'),
		'id'   => $prefix . 'portfolio_carousel',
		'type'    => 'select',
		'options' => array(
			'default' 		=> __("Default", 'virtue-toolkit' ),
			'related' 		=> __("Related Post Carousel", 'virtue-toolkit' ),
			'recent' 		=> __("Recent Portfolio Carousel", 'virtue-toolkit' ),
			'none' 	=> __("No Carousel", 'virtue-toolkit' ),
		),
	));
}