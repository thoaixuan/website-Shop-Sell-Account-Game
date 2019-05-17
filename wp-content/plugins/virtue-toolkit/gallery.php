<?php 
if ( ! class_exists( 'Kadence_Toolkit_Get_Image' ) ) {
	class Kadence_Toolkit_Get_Image {
        /**
         * The singleton instance
         */
        static private $instance = null;
        /**
         * No initialization allowed
         */
        private function __construct() {}

        /**
         * No cloning allowed
         */
        private function __clone() {}

        static public function getInstance() {
            if(self::$instance == null) {
                self::$instance = new self;
            }

            return self::$instance;
        }

        public function process($id = null, $width = null, $height = null) {
			// return if no ID
			if(empty($id)) {
				return false;
			}
			// return with orginal if no width or height set.
			if(empty($width) && empty($height) ) {
				return self::toolkit_get_full_image($id);
			}
			// Find width or height if one or the other is not set.
			$org_height = true;
			if(empty($height) ) {
				$org_height = false;
		        $image_attributes = wp_get_attachment_image_src( $id, 'full' );
		        $sizes = image_resize_dimensions($image_attributes[1], $image_attributes[2], $width, null, false );
		        $height = $sizes[5];
		    } else if(empty($width) ) {
		        $image_attributes = wp_get_attachment_image_src( $id, 'full' );
		        $sizes = image_resize_dimensions($image_attributes[1], $image_attributes[2], null, $height, false );
		        $width = $sizes[4];
		    }
		    // Now we checked for an ID, made sure the width and height have values lets check if we can make the size at all
		    if ( self::toolkit_image_size_larger_than_original( $id, $width, $height ) ) {
		    	return self::toolkit_get_full_image($id);
			}
		    //Check for jetpack
		    if( class_exists( 'Jetpack' ) && Jetpack::is_module_active( 'photon' ) ) {
		    	$args = array( 'resize' => $width . ',' . $height );
		    	$image_url = wp_get_attachment_image_url($id, 'full');
		    	return array (
		            0 => jetpack_photon_url( $image_url, $args ),
		            1 => $width,
		            2 => $height,
		            3 => self::toolkit_get_srcset_output($id, $image_url, $width, $height),
					4 => $image_url,
					5 => $id
		        );
		    } else if( self::toolkit_image_size_already_exists( $id, $width, $height ) ) {

		    		return self::toolkit_get_image_at_size($id, $width, $height );

		    } else if(class_exists( 'Kadence_Image_Processing' )) {
		    	// lets process the image
		    	$Kadence_Image_Processing = Kadence_Image_Processing::getInstance();
            	$created = $Kadence_Image_Processing->process($id, $width, $height);
            	if($created) {
		    		return self::toolkit_get_image_at_size($id, $width, $height );
			    } else {
			    	return self::toolkit_get_full_image($id);
			    }
		    } else {
		    	return self::toolkit_get_full_image($id);
		    }
		}
		public static function toolkit_get_image_srcset($id = null, $url = null, $width = null, $height = null) {
		  	if(empty($id) || empty($url) || empty($width) || empty($height)) {
		    	return false;
		  	}
		  
		  	$image_meta = self::toolkit_get_image_meta($id);
		  	if ( ! $image_meta ) {
				return false;
			}
		  	
			if(function_exists ( 'wp_calculate_image_srcset') ){
		  		$output = wp_calculate_image_srcset(array( $width, $height), $url, $image_meta, $id);
			} else {
		  		$output = '';
			}

		    return $output;
		}
		public static function toolkit_get_srcset_output($id = null, $url = null, $width = null, $height = null) {
		    $img_srcset = self::toolkit_get_image_srcset($id, $url, $width, $height);
		    if(!empty($img_srcset) ) {
		      	$output = 'srcset="'.esc_attr($img_srcset).'" sizes="(max-width: '.esc_attr($width).'px) 100vw, '.esc_attr($width).'px"';
		    } else {
		      	$output = '';
		    }
		    return $output;
		}
		public static function toolkit_image_size_larger_than_original($id, $width, $height) {
				$image_meta = self::toolkit_get_image_meta( $id );

				if ( ! isset( $image_meta['width'] ) || ! isset( $image_meta['height'] ) ) {
					return true;
				}
				if ( $width > $image_meta['width'] || $height > $image_meta['height'] ) {
					return true;
				}

				return false;
		}
		public static function toolkit_get_full_image($id) {
				$src = wp_get_attachment_image_src($id, 'full' );
				// array return.
				$image = array (
					0 => $src[0],
					1 => $src[1],
					2 => $src[2],
					3 => self::toolkit_get_srcset_output($id, $src[0], $src[1], $src[2]),
					4 => $src[0],
					5 => $id
				);
				return $image;
		}
		public static function toolkit_get_image_at_size($id, $width, $height) {
				$size = array(
					0 => $width,
					1 => $height
				);
				$src = wp_get_attachment_image_src($id, $size );
				$full = wp_get_attachment_image_url($id, 'full' );
				// array return.
				$image = array (
					0 => $src[0],
					1 => $src[1],
					2 => $src[2],
					3 => self::toolkit_get_srcset_output($id, $src[0], $src[1], $src[2]),
					4 => $full,
					5 => $id
				);
				return $image;
		}
		public static function toolkit_get_image_meta( $id ) {
			return wp_get_attachment_metadata( $id );
		}
		public static function toolkit_image_size_already_exists( $id, $width, $height ) {
			$image_meta = self::toolkit_get_image_meta( $id );
			$kip_size_name = self::kip_get_size_name( array( $width, $height ));
			if(isset( $image_meta['sizes'][ $kip_size_name ] ) ) {
				return true;
			} else {
				return false;
			}
		}
		public static function kip_get_size_name( $size ) {
			return 'kip-' . $size[0] . 'x' . $size[1];
		}
	}
}

function kadence_toolkit_get_image_array($width = null, $height = null, $crop = true, $class = null, $alt = null, $id = null) {
    if(empty($id)) {
        $id = get_post_thumbnail_id();
    }
    if(!empty($id)) {
        $Kadence_Toolkit_Get_Image = Kadence_Toolkit_Get_Image::getInstance();
        $image = $Kadence_Toolkit_Get_Image->process( $id, $width, $height);
        if(empty($alt)) {
            $alt = get_post_meta($id, '_wp_attachment_image_alt', true);
        }
        $return_array = array(
            'src' => $image[0],
            'width' => $image[1],
            'height' => $image[2],
            'srcset' => $image[3],
            'class' => $class,
            'alt' => $alt,
            'full' => $image[4],
        );
    } else {
        $return_array = array(
            'src' => '',
            'width' => '',
            'height' => '',
            'srcset' => '',
            'class' => '',
            'alt' => '',
            'full' => '',
        );
    }

    return $return_array;
}
/**
 *
 * Re-create the [gallery] shortcode and use thumbnails styling from kadencethemes
 *
 */
function kadence_toolkit_shortcode_gallery($attr) {
  	$post = get_post();
  	static $instance = 0;
  	$instance++;

  	if (!empty($attr['ids'])) {
    	if (empty($attr['orderby'])) {
     		$attr['orderby'] = 'post__in';
    	}
    	$attr['include'] = $attr['ids'];
  	}

  	$output = apply_filters('post_gallery', '', $attr);

  	if ($output != '') {
   		return $output;
  	}

  	if (isset($attr['orderby'])) {
    	$attr['orderby'] = sanitize_sql_orderby($attr['orderby']);
    	if (!$attr['orderby']) {
      		unset($attr['orderby']);
    	}
  	}
  	if(!isset($post)) {
    	$post_id = null;
  	} else {
    	$post_id = $post->ID;
  	}

  	extract(shortcode_atts(array(
		'order'      => 'ASC',
		'orderby'    => 'menu_order ID',
		'id'         => $post_id,
		'itemtag'    => '',
		'icontag'    => '',
		'captiontag' => '',
		'columns'    => 3,
		'link'      => 'file',
		'size'            => 'full',
		'include'         => '',
		'attachment_page' => 'false',
		'use_image_alt'   => 'false',
		'gallery_id'      => ( rand( 10, 100 ) ),
		'lightboxsize'    => 'full',
		'exclude'         => ''
  	), $attr));

  	$id = intval($id);

  	if ($order === 'RAND') {
    	$orderby = 'none';
  	}

  	$gallery_rn = (rand(10,100));

  	if (!empty($include)) {
    	$_attachments = get_posts(array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby));

    	$attachments = array();
	    foreach ($_attachments as $key => $val) {
	      	$attachments[$val->ID] = $_attachments[$key];
	    }
  	} elseif (!empty($exclude)) {
    	$attachments = get_children(array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby));
  	} else {
    	$attachments = get_children(array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby));
  	}

  	if (empty($attachments)) {
    	return '';
  	}

  	if (is_feed()) {
    	$output = "\n";
    	foreach ($attachments as $att_id => $attachment) {
      		$output .= wp_get_attachment_link($att_id, $size, true) . "\n";
    	}
    	return $output;
  	}

  	if ($columns == '2') {
    	$itemsize = 'tcol-lg-6 tcol-md-6 tcol-sm-6 tcol-xs-12 tcol-ss-12'; $imgsize = 600;
  	} else if ($columns == '1') {
    	$itemsize = 'tcol-lg-12 tcol-md-12 tcol-sm-12 tcol-xs-12 tcol-ss-12'; $imgsize = 1200;
  	} else if ($columns == '3'){
    	$itemsize = 'tcol-lg-4 tcol-md-4 tcol-sm-4 tcol-xs-6 tcol-ss-12'; $imgsize = 400;
  	} else if ($columns == '6'){
    	$itemsize = 'tcol-lg-2 tcol-md-2 tcol-sm-3 tcol-xs-4 tcol-ss-6'; $imgsize = 300;
  	} else if ($columns == '8' || $columns == '9' || $columns == '7'){ 
    	$itemsize = 'tcol-lg-2 tcol-md-2 tcol-sm-3 tcol-xs-4 tcol-ss-4'; $imgsize = 260;
  	} else if ($columns == '12' || $columns == '11'){ 
    	$itemsize = 'tcol-lg-1 tcol-md-1 tcol-sm-2 tcol-xs-2 tcol-ss-3'; $imgsize = 240;
  	} else if ($columns == '5'){ 
    	$itemsize = 'tcol-lg-25 tcol-md-25 tcol-sm-3 tcol-xs-4 tcol-ss-6'; $imgsize = 300;
  	} else {
    	$itemsize = 'tcol-lg-3 tcol-md-3 tcol-sm-4 tcol-xs-6 tcol-ss-12'; $imgsize = 300;
  	}

  	$output .= '<div id="kad-wp-gallery'.esc_attr($gallery_rn).'" class="kad-wp-gallery kad-light-wp-gallery clearfix kt-gallery-column-'.esc_attr($columns).' rowtight">'; 
      
  	foreach ($attachments as $id => $attachment) {
  		if($use_image_alt == 'true') {
      		$alt = get_post_meta($id, '_wp_attachment_image_alt', true);
    	} else {
      		$alt = $attachment->post_excerpt;
    	}

    	$img = kadence_toolkit_get_image_array($imgsize, $imgsize, true, 'light-dropshaddow', $alt, $id);
    	$attachment_url = $img['full'];
    
	    if($lightboxsize != 'full') {
	            $attachment_url = wp_get_attachment_image_src( $id, $lightboxsize);
	            $attachment_url = $attachment_url[0];
	    }
    	$lightbox_data = 'data-rel="lightbox"';
    	if($link == 'attachment_page' || $attachment_page == 'true') {
      		$attachment_url = get_permalink($id);
      		$lightbox_data = '';
    	}

    	$output .= '<div class="'.esc_attr($itemsize).' g_item"><div class="grid_item kad_gallery_fade_in gallery_item">';
    		$output .= '<a href="'.esc_url($attachment_url).'" '.$lightbox_data.' class="lightboxhover">';
    			$output .= '<img src="'.esc_url($img['src']).'" width="'.esc_attr($img['width']).'" height="'.esc_attr($img['height']).'" alt="'.esc_attr($img['alt']).'" '.$img['srcset'].' class="'.$img['class'].'"/>';
     		$output .= '</a>';
    	$output .= '</div></div>';
  	}
  	$output .= '</div>';
  
  	return $output;
}
add_action('init', 'kt_tool_gallery_setup_init');
function kt_tool_gallery_setup_init() {
	$pinnacle = get_option( 'pinnacle' );
	$virtue = get_option( 'virtue' );
	if(! function_exists( 'kadence_gallery' ) ) {
		if( (isset($pinnacle['pinnacle_gallery']) && $pinnacle['pinnacle_gallery'] == '1') ||  (isset($virtue['virtue_gallery']) && $virtue['virtue_gallery'] == '1') )  {
		  	remove_shortcode('gallery');
		  	add_shortcode('gallery', 'kadence_toolkit_shortcode_gallery');
		} 
	}
}
function kt_toolkit_shortcode_gallery($attr) {
	$post = get_post();
	static $instance = 0;
	$instance++;

	if (!empty($attr['ids'])) {
		if (empty($attr['orderby'])) {
		  	$attr['orderby'] = 'post__in';
		}
		$attr['include'] = $attr['ids'];
	}

	$output = apply_filters('post_gallery', '', $attr);

	if ($output != '') {
		return $output;
	}

	if (isset($attr['orderby'])) {
		$attr['orderby'] = sanitize_sql_orderby($attr['orderby']);
		if (!$attr['orderby']) {
	  		unset($attr['orderby']);
		}
	}
	if(!isset($post)) {
    	$post_id = null;
  	} else {
    	$post_id = $post->ID;
  	}

  	extract(shortcode_atts(array(
	    'order'      		=> 'ASC',
	    'orderby'    		=> 'menu_order ID',
	    'id'         		=> $post_id,
	    'columns'    		=> 3,
	    'link'      		=> 'file',
	    'size'       		=> 'full',
	    'include'    		=> '',
	    'use_image_alt' 	=> 'true',
	    'gallery_id'  		=> (rand(10,100)),
	    'lightboxsize'	 	=> 'full',
	    'exclude'    		=> ''
  	), $attr));

  	$id = intval($id);

  	if ($order === 'RAND') {
    	$orderby = 'none';
  	}
  	$caption = 'false';
  	if (!empty($include)) {
    	$_attachments = get_posts(array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby));
	    $attachments = array();
	    foreach ($_attachments as $key => $val) {
	      	$attachments[$val->ID] = $_attachments[$key];
	    }
  	} elseif (!empty($exclude)) {
    	$attachments = get_children(array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby));
  	} else {
    	$attachments = get_children(array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby));
  	}
  	if (empty($attachments)) {
    	return '';
  	}
  	if (is_feed()) {
    	$output = "\n";
    	foreach ($attachments as $att_id => $attachment) {
      		$output .= wp_get_attachment_link($att_id, $size, true) . "\n";
    	}
    	return $output;
  	}
  	$output .= '<div id="kad-wp-gallery'.esc_attr($gallery_id).'" class="kad-wp-gallery kt-gallery-column-'.esc_attr($columns).' kad-light-gallery clearfix row-margin-small">';
    if ($columns == '1') {
    	$itemsize = 'col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 col-ss-12'; 
    	$imgsize = 1140;
    } else if ($columns == '2') {
    	$itemsize = 'col-xxl-3 col-xl-4 col-lg-6 col-md-6 col-sm-6 col-xs-12 col-ss-12'; 
    	$imgsize = 600;
    } else if ($columns == '3'){
    	$itemsize = 'col-xxl-25 col-xl-3 col-lg-4 col-md-4 col-sm-4 col-xs-6 col-ss-12'; 
    	$imgsize = 400;
    } else if ($columns == '4'){ 
    	$itemsize = 'col-xxl-2 col-xl-25 col-lg-3 col-md-3 col-sm-4 col-xs-6 col-ss-12'; 
    	$imgsize = 300;
    } else if ($columns == '5'){ 
    	$itemsize = 'col-xxl-2 col-xl-2 col-lg-25 col-md-25 col-sm-3 col-xs-4 col-ss-6'; 
    	$imgsize = 240;
    } else if ($columns == '6'){ 
    	$itemsize = 'col-xxl-15 col-xl-2 col-lg-2 col-md-2 col-sm-3 col-xs-4 col-ss-6'; 
    	$imgsize = 240;
    } else { 
    	$itemsize = 'col-xxl-1 col-xl-15 col-lg-2 col-md-2 col-sm-3 col-xs-4 col-ss-4'; 
    	$imgsize = 240;
    }
      
  	$i = 0;
  	foreach ($attachments as $id => $attachment) {
    	// Get alt or caption for alt
	    if($use_image_alt == 'true') {
	      	$alt = get_post_meta($id, '_wp_attachment_image_alt', true);
	    } else {
	      	$alt = $attachment->post_excerpt;
	    }

	    $img = kadence_toolkit_get_image_array($imgsize, $imgsize, true, 'kt-gallery-img', $alt, $id);
	    $attachment_url = $img['full'];

	    if($lightboxsize != 'full') {
	            $attachment_lb = wp_get_attachment_image_src( $id, $lightboxsize);
	      		$attachment_url = $attachment_lb[0];
	    }
	    $lightbox_data = 'data-rel="lightbox"';
	    if($link == 'attachment_page') {
	      	$attachment_url = get_permalink($id);
	      	$lightbox_data = '';
	    }

	    $paddingbtn = ($img['height']/$img['width']) * 100;
    	$output .= '<div class="'.esc_attr($itemsize).' g_item"><div class="grid_item gallery_item">';
	      	if($link != 'none') { 
	        	$output .='<a href="'.esc_url($attachment_url).'" '.$lightbox_data.' class="gallery-link">';
	      	}
    		$output .= '<div class="kt-intrinsic" style="padding-bottom:'.esc_attr($paddingbtn).'%;" itemprop="image" itemscope itemtype="http://schema.org/ImageObject">';
    			$output .= '<img src="'.esc_url($img['src']).'" width="'.esc_attr($img['width']).'" height="'.esc_attr($img['height']).'" alt="'.esc_attr($img['alt']).'" '.$img['srcset'].' class="'.$img['class'].'" itemprop="contentUrl" />';
    			$output .= '<meta itemprop="url" content="'.esc_url($img['src']).'">';
                $output .= '<meta itemprop="width" content="'.esc_attr($img['width']).'">';
                $output .= '<meta itemprop="height" content="'.esc_attr($img['height']).'>">';
    		$output .= '</div>';
      		if (trim($attachment->post_excerpt) && $caption == 'true') {
      			$output .= '<div class="photo-caption-bg"></div>';
        		$output .= '<div class="caption kad_caption">';
        			$output .= '<div class="kad_caption_inner">' . wptexturize($attachment->post_excerpt) . '</div>';
        		$output .= '</div>';
      		}
	      	if($link != 'none') { 
	        	$output .= '</a>';
	      	}
    	$output .= '</div></div>';
  }
  $output .= '</div>';
  
  return $output;
}
add_action('init', 'kt_tool_ascend_gallery_setup_init');
function kt_tool_ascend_gallery_setup_init() {
	$the_theme = wp_get_theme();
	if($the_theme->get( 'Name' ) == 'Ascend' || ($the_theme->get( 'Template') == 'ascend') ) {
		$ascend = get_option( 'ascend' );
	    if(isset($ascend['kadence_gallery']) && $ascend['kadence_gallery'] == '1')  {
		  	remove_shortcode('gallery');
		  	add_shortcode('gallery', 'kt_toolkit_shortcode_gallery');
		} 
	}
}