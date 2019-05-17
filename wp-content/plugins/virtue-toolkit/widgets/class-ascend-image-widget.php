<?php
/*
* Ascend Image Widget for compatiblity
*/

if ( ! class_exists( 'ascend_image_widget' ) ) {
	class ascend_image_widget extends WP_Widget{
    private static $instance = 0;
    public function __construct() {
        $widget_ops = array('classname' => 'kadence_about_with_image', 'description' => __('This allows for an image and a simple about text.', 'virtue-toolkit'));
        parent::__construct('kadence_about_with_image', __('Ascend: Image', 'virtue-toolkit'), $widget_ops);
    }

    public function widget($args, $instance){ 
    	if ( ! isset( $args['widget_id'] ) ) {
	      $args['widget_id'] = $this->id;
	    }
        extract( $args );
        if (!empty($instance['image_link_open']) && $instance['image_link_open'] == "none") {
          $uselink = false;
          $link = '';
          $linktype = '';
        } else if(empty($instance['image_link_open']) || $instance['image_link_open'] == "lightbox") {
          $uselink = true;
          $link = esc_url($instance['image_uri']);
          $linktype = 'data-rel="lightbox"';
        } else if($instance['image_link_open'] == "_blank") {
          $uselink = true;
          if(!empty($instance['image_link'])) {$link = $instance['image_link'];} else {$link = esc_url($instance['image_uri']);}
          $linktype = 'target="_blank"';
        } else if($instance['image_link_open'] == "_self") {
          $uselink = true;
          if(!empty($instance['image_link'])) {$link = $instance['image_link'];} else {$link = esc_url($instance['image_uri']);}
          $linktype = 'target="_self"';
        }
        if(!empty($instance['image_id'])) {
          $alt = esc_attr( get_post_meta($instance['image_id'], '_wp_attachment_image_alt', true) );
        } else {
          $alt = '';
        }
        if(isset($instance['image_size']) && !empty($instance['image_size'])) {
        	$size = $instance['image_size'];
        } else {
        	$size = 'full';
        }

        echo $before_widget; 
            echo '<div class="kad_img_upload_widget kt-image-widget-'.esc_attr($args['widget_id']).'">';
               	if($uselink == true) {
               		echo '<a href="'.esc_url($link).'" '.wp_kses($linktype, array('a' => array('target' => array(),'data' => array()))).'>';
               	} 
                if($size == 'custom') {
                	$img = ascend_get_image_array($instance['width'], $instance['height'], true, null, null, $instance['image_id'], true);
                	echo '<img src="'.esc_url($img['src']).'" width="'.esc_attr($img['width']).'" height="'.esc_attr($img['height']).'" '.$img['srcset'].' class="'.esc_attr($img['class']).'" itemprop="contentUrl" alt="'.esc_attr($img['alt']).'">';
                } else {
                	echo wp_get_attachment_image( $instance['image_id'], $size ); 
                }
                if($uselink == true) {
                	echo '</a>'; 
                }
               	if(!empty($instance['text'])) {
               		echo '<div class="kadence_image_widget_caption">'.$instance['text'].'</div>';
               	}
            echo '</div>';
        echo $after_widget; 
    }

    public function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['text'] = wp_filter_post_kses($new_instance['text']);
        $instance['alttext'] = sanitize_text_field($new_instance['alttext']);
        $instance['image_id'] = (int) $new_instance['image_id'];
        $instance['image_uri'] = esc_url_raw( $new_instance['image_uri'] );
        $instance['image_link'] = esc_url_raw($new_instance['image_link']);
        $instance['image_link_open'] = sanitize_text_field($new_instance['image_link_open']);
        $instance['image_size'] = sanitize_text_field($new_instance['image_size']);
        $instance['width'] = (int) $new_instance['width'];
        $instance['height'] = (int) $new_instance['height'];
        return $instance;
    }

  public function form($instance){ 
    $image_uri = isset($instance['image_uri']) ? esc_attr($instance['image_uri']) : '';
    $image_link = isset($instance['image_link']) ? esc_attr($instance['image_link']) : '';
    $width = isset($instance['width']) ? esc_attr($instance['width']) : '';
    $height = isset($instance['height']) ? esc_attr($instance['height']) : '';
    $image_id = isset($instance['image_id']) ? esc_attr($instance['image_id']) : '';
    if (isset($instance['image_link_open'])) { $image_link_open = esc_attr($instance['image_link_open']); } else {$image_link_open = 'lightbox';}
    if (isset($instance['image_size'])) { $image_size = esc_attr($instance['image_size']); } else {$image_size = 'full';}
    $link_options = array();
    $link_options_array = array();
    $sizes = ascend_basic_image_sizes();
    $link_options[] = array("slug" => "lightbox", "name" => __('Lightbox', 'virtue-toolkit'));
    $link_options[] = array("slug" => "_blank", "name" => __('New Window', 'virtue-toolkit'));
    $link_options[] = array("slug" => "_self", "name" => __('Same Window', 'virtue-toolkit'));
    $link_options[] = array("slug" => "none", "name" => __('No Link', 'virtue-toolkit'));

    foreach ($link_options as $link_option) {
      if ($image_link_open == $link_option['slug']) { $selected=' selected="selected"';} else { $selected=""; }
      $link_options_array[] = '<option value="' . esc_attr($link_option['slug']) .'"' . $selected . '>' . esc_html($link_option['name']) . '</option>';
    }
    foreach ($sizes as $size => $size_info) {
      	if ($image_size == $size) { $selected=' selected="selected"';} else { $selected=""; }
      		$sizes_array[] = '<option value="' . esc_attr($size) .'"' . $selected . '>' . esc_html($size_info) .'</option>';
    }
    ?>
  <div class="kad_img_upload_widget">
    <p>
        <img class="kad_custom_media_image" src="<?php if(!empty($instance['image_uri'])){echo esc_attr($instance['image_uri']);} ?>" style="margin:0;padding:0;max-width:100px;display:block" />
    </p>
    <p>
        <label for="<?php echo $this->get_field_id('image_uri'); ?>"><?php _e('Image URL', 'virtue-toolkit'); ?></label><br />
        <input type="text" class="widefat kad_custom_media_url" name="<?php echo $this->get_field_name('image_uri'); ?>" id="<?php echo $this->get_field_id('image_uri'); ?>" value="<?php echo esc_attr($image_uri); ?>">
        <input type="hidden" value="<?php echo esc_attr($image_id); ?>" class="kad_custom_media_id" name="<?php echo $this->get_field_name('image_id'); ?>" id="<?php echo $this->get_field_id('image_id'); ?>" />
        <input type="button" value="<?php esc_attr_e('Upload', 'virtue-toolkit'); ?>" class="button kad_custom_media_upload" id="kad_custom_image_uploader" />
    </p>
    <p>
        <label for="<?php echo $this->get_field_id('image_size'); ?>"><?php _e('Image size', 'virtue-toolkit'); ?></label><br />
        <select id="<?php echo $this->get_field_id('image_size'); ?>" name="<?php echo $this->get_field_name('image_size'); ?>"><?php echo implode('', $sizes_array);?></select>
    </p>
    <p>
        <label for="<?php echo $this->get_field_id('width'); ?>"><?php _e('Custom Width', 'virtue-toolkit'); ?></label><br />
        <input type="text" class="widefat kad_img_widget_link" name="<?php echo $this->get_field_name('width'); ?>" id="<?php echo $this->get_field_id('width'); ?>" value="<?php echo esc_attr($width); ?>">
    </p>
    <p>
        <label for="<?php echo $this->get_field_id('height'); ?>"><?php _e('Custom Height', 'virtue-toolkit'); ?></label><br />
        <input type="text" class="widefat kad_img_widget_link" name="<?php echo $this->get_field_name('height'); ?>" id="<?php echo $this->get_field_id('height'); ?>" value="<?php echo esc_attr($height); ?>">
    </p>
    <p>
        <label for="<?php echo $this->get_field_id('image_link_open'); ?>"><?php _e('Image opens in', 'virtue-toolkit'); ?></label><br />
        <select id="<?php echo $this->get_field_id('image_link_open'); ?>" name="<?php echo $this->get_field_name('image_link_open'); ?>"><?php echo implode('', $link_options_array);?></select>
    </p>
    <p>
        <label for="<?php echo $this->get_field_id('image_link'); ?>"><?php _e('Image Link (optional)', 'virtue-toolkit'); ?></label><br />
        <input type="text" class="widefat kad_img_widget_link" name="<?php echo $this->get_field_name('image_link'); ?>" id="<?php echo $this->get_field_id('image_link'); ?>" value="<?php echo esc_attr($image_link); ?>">
    </p>
    <p>
      <label for="<?php echo $this->get_field_id('text'); ?>"><?php _e('Text/Caption (optional)', 'virtue-toolkit'); ?></label><br />
      <textarea name="<?php echo $this->get_field_name('text'); ?>" id="<?php echo $this->get_field_id('text'); ?>" class="widefat" ><?php if(!empty($instance['text'])) echo esc_textarea($instance['text']); ?></textarea>
    </p>
  </div>
    <?php
  }

}


}