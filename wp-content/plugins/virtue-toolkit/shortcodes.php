<?php
//Shortcode for accordion
function virtue_toolkit_accordion_shortcode_function($atts, $content ) {
	extract(shortcode_atts(array(
	'id' => rand(1, 999)
), $atts));
	global $kt_pane_count, $kt_panes;
	$kt_pane_count = 0;
	$kt_panes = array();
	$return = '';
	do_shortcode( $content );
	if( is_array( $kt_panes ) && !empty($kt_panes)){
		$i = 0;
		foreach( $kt_panes as $tab ){
			if ($i % 2 == 0) {
				$eo = "even";
			} else {
				$eo = "odd";
			}
			$tabs[] = '<div class="panel panel-default panel-'.esc_attr($eo).'"><div class="panel-heading"><a class="accordion-toggle '.esc_attr($tab['open']).'" data-toggle="collapse" data-parent="#accordionname'.esc_attr($id).'" href="#collapse'.esc_attr($id.$tab['link']).'"><h5><i class="icon-minus kt-icon-minus primary-color"></i><i class="icon-plus kt-icon-plus"></i>'.wp_kses_post($tab['title']).'</h5></a></div><div id="collapse'.esc_attr($id.$tab['link']).'" class="panel-collapse collapse '.esc_attr($tab['in']).'"><div class="panel-body postclass">'.do_shortcode($tab['content']).'</div></div></div>';
			$i++;
		}
		$return = "\n".'<div class="panel-group kt-accordion" id="accordionname'.esc_attr($id).'">'.implode( "\n", $tabs ).'</div>'."\n";
	}
return $return;
}

function virtue_toolkit_accordion_pane_function($atts, $content ) {
	extract(shortcode_atts(array(
		'title' => 'Pane %d',
		'start' => ''
	), $atts));
	if (!empty($start) || $start == 'closed') {
		$open = '';
	} else {
		$open = 'collapsed';
	}
	if (!empty($start) || $start == 'closed') {
		$in = 'in';
	} else {
		$in = '';
	}
	global $kt_pane_count, $kt_panes;
	$x = $kt_pane_count;
	$kt_panes[$x] = array( 'title' => $title, 'open' => $open, 'in' => $in, 'link' => $kt_pane_count, 'content' =>  $content );

	$kt_pane_count++;
}
function virtue_toolkit_tab_shortcode_function($atts, $content ) {
	extract(shortcode_atts(array(
		'id' => rand(1, 9999),
		'style' => '1',
	), $atts));
	global $kt_tab_count, $kt_tabs;
	$kt_tab_count = 0;
	$kt_tabs = array();
	$return = '';
	do_shortcode( $content );
	if( is_array( $kt_tabs ) && !empty($kt_tabs)) {
		foreach( $kt_tabs as $nav ){
			$tabnav[] = '<li class="'.esc_attr($nav['active']).'"><a href="#sctab'.esc_attr($id.$nav['link']).'">'.wp_kses_post($nav['title']).'</a></li>';
		}
		foreach( $kt_tabs as $tab ){
			$tabs[] = '<div class="tab-pane clearfix '.esc_attr($tab['active']).'" id="sctab'.esc_attr($id.$tab['link']).'">'.do_shortcode( $tab['content']).'</div>';
		}
	
		$return = "\n".'<ul class="nav nav-tabs sc_tabs kt-tabs kt-sc-tabs kt-tabs-style'.esc_attr($style).'">'.implode( "\n", $tabnav ).'</ul> <div class="tab-content kt-tab-content postclass">'.implode( "\n", $tabs ).'</div>'."\n";
	}
	return $return;
}
function virtue_toolkit_tab_pane_function($atts, $content ) {
	extract(shortcode_atts(array(
		'title' => 'Tab %d',
		'start' => ''
	), $atts));
	if (!empty($start)) {
		$active = 'active';
	} else {
		$active = '';
	}
	global $kt_tab_count, $kt_tabs;

	$x = $kt_tab_count;
	$kt_tabs[$x] = array( 'title' => $title, 'active' => $active, 'link' => $kt_tab_count, 'content' => $content );

	$kt_tab_count++;
}

//Shortcode for columns
function virtue_toolkit_column_shortcode_function( $atts, $content ) {
	return '<div class="row">'.do_shortcode($content).'</div>';
}
function virtue_toolkit_hcolumn_shortcode_function( $atts, $content ) {
	return '<div class="row">'.do_shortcode($content).'</div>';
}
function virtue_toolkit_column11_function( $atts, $content ) {
	return '<div class="col-md-11">'.do_shortcode($content).'</div>';
}
function virtue_toolkit_column10_function( $atts, $content ) {
	return '<div class="col-md-10">'.do_shortcode($content).'</div>';
}
function virtue_toolkit_column9_function( $atts, $content ) {
	return '<div class="col-md-9">'.do_shortcode($content).'</div>';
}
function virtue_toolkit_column8_function( $atts, $content ) {
	return '<div class="col-md-8">'.do_shortcode($content).'</div>';
}
function virtue_toolkit_column7_function( $atts, $content ) {
	return '<div class="col-md-7">'.do_shortcode($content).'</div>';
}
function virtue_toolkit_column6_function( $atts, $content ) {
	return '<div class="col-md-6">'.do_shortcode($content).'</div>';
}
function virtue_toolkit_column5_function( $atts, $content ) {
	return '<div class="col-md-5">'.do_shortcode($content).'</div>';
}
function virtue_toolkit_column4_function( $atts, $content ) {
	return '<div class="col-md-4">'.do_shortcode($content).'</div>';
}
function virtue_toolkit_column3_function( $atts, $content ) {
	return '<div class="col-md-3">'.do_shortcode($content).'</div>';
}
function virtue_toolkit_column2_function( $atts, $content ) {
	return '<div class="col-md-2">'.do_shortcode($content).'</div>';
}
function virtue_toolkit_column1_function( $atts, $content ) {
	return '<div class="col-md-1">'.do_shortcode($content).'</div>';
}
//Shortcode for Icons
function virtue_toolkit_icon_shortcode_function( $atts) {
	extract(shortcode_atts(array(
		'icon' => '',
		'size' => '',
		'color' => '',
		'float'=> ''
	), $atts));
	if ($float != '') {
	 	$output = '<i class="'.esc_attr($icon).'" style="font-size:'.esc_attr($size).'; color:'.esc_attr($color).'; float:'.esc_attr($float).'; padding:10px;"></i>';
	} else {
		$output = '<i class="'.esc_attr($icon).'" style="font-size:'.esc_attr($size).'; color:'.esc_attr($color).';"></i>';
	}
	return $output;
}
// Video Shortcode
function virtue_toolkit_video_shortcode_function( $atts, $content) {
	extract(shortcode_atts(array(
		'width' => '',
), $atts));
	if(!empty($width)) { 
		$output = '<div style="max-width:'.esc_attr($width).'px;"><div class="videofit">'.wp_kses_post($content).'</div></div>';
	} else { 
		$output = '<div class="videofit">'.wp_kses_post($content).'</div>'; 
	}
	return $output;
}
// Based on Ultimate Shortcodes youtube and vimeo shortcodes
function virtue_toolkit_youtube_shortcode_function( $atts, $content) {
		// Prepare data
		$atts = shortcode_atts(array(
				'url'  => false,
				'width' => 600,
				'height' => 400,
				'maxwidth' => '',
				'autoplay' => 'false',
				'controls' => 'true',
				'hidecontrols' => 'false',
				'fs' => 'true',
				'loop' => 'false',
				'rel' => 'false',
				'vq' => '',
				'modestbranding' => 'false',
				'theme' => 'dark'
		), $atts, 'kad_youtube' );
		$return = array();
		$params = array();

		if ( !$atts['url'] ){
			return '<p class="error">YouTube: ' . __( 'please specify correct url', 'virtue-toolkit' ) . '</p>';
		}
		$id = ( preg_match( '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $atts['url'], $match ) ) ? $match[1] : false;
		// Check that url is specified
		if ( !$id ) {
			return '<p class="error">YouTube: ' . __( 'please specify correct url', 'virtue-toolkit' ) . '</p>';
		}
		// Prepare params
		if($atts['hidecontrols'] == 'true') {$atts['controls'] = 'false';}
		foreach ( array('autoplay', 'controls', 'fs', 'modestbranding', 'theme', 'rel', 'loop' ) as $param ) $params[$param] = str_replace( array( 'false', 'true', 'alt' ), array( '0', '1', '2' ), $atts[$param] );
		// Prepare player parameters
		if(!empty($atts['vq']) ) {$params['vq'] = $atts['vq']; }
		$params = http_build_query( $params );
		if($atts['maxwidth']) {$maxwidth = 'style="max-width:'.esc_attr($atts['maxwidth']).'px;"';} else{ $maxwidth = '';}
		// Create player
		$return[] = '<div class="kad-youtube-shortcode videofit" '.$maxwidth.' >';
		$return[] = '<iframe width="' . esc_attr($atts['width']) . '" height="' . esc_attr($atts['height']) . '" src="//www.youtube.com/embed/' . esc_attr($id) . '?' . esc_attr($params) . '" frameborder="0" allowfullscreen="true"></iframe>';
		$return[] = '</div>';
		// Return result
		return implode( '', $return );
}
function virtue_toolkit_vimeo_shortcode_function( $atts, $content) {
		$atts = shortcode_atts( array(
				'url'        => false,
				'width'      => 600,
				'height'     => 400,
				'maxwidth' 	 => '',
				'autoplay'   => 'no'
			), $atts, 'vimeo' );
		
			if ( !$atts['url'] ){
				return '<p class="error">Vimeo: ' . __( 'please specify correct url', 'virtue-toolkit' ) . '</p>';
			}
			$id = ( preg_match( '~(?:<iframe [^>]*src=")?(?:https?:\/\/(?:[\w]+\.)*vimeo\.com(?:[\/\w]*\/videos?)?\/([0-9]+)[^\s]*)"?(?:[^>]*></iframe>)?(?:<p>.*</p>)?~ix', $atts['url'], $match ) ) ? $match[1] : false;
			// Check that url is specified
			if ( !$id ) {
				return '<p class="error">Vimeo: ' . __( 'please specify correct url', 'virtue-toolkit' ) . '</p>';
			}
			$return = array();
			if($atts['maxwidth']) {
				$maxwidth = 'style="max-width:'.esc_attr($atts['maxwidth']).'px;"';
			} else{ 
				$maxwidth = '';
			}
			$autoplay = ( $atts['autoplay'] === 'yes' ) ? '&amp;autoplay=1' : '';
			// Create player
			$return[] = '<div class="kad-vimeo-shortcode  videofit '.$maxwidth.'">';
			$return[] = '<iframe width="' . esc_attr($atts['width']) . '" height="' . esc_attr($atts['height']) .'" src="//player.vimeo.com/video/' . esc_attr($id) . '?title=0&amp;byline=0&amp;portrait=0&amp;color=ffffff' .esc_attr($autoplay) . '" frameborder="0" allowfullscreen="true"></iframe>';
			$return[] = '</div>';

			// Return result
		return implode( '', $return );
	}
//Button
function virtue_toolkit_button_shortcode_function( $atts) {
	extract(shortcode_atts(array(
		'id' => rand(1, 999),
		'bcolor' => '',
		'bhovercolor' => '',
		'thovercolor' => '',
		'link' => '',
		'text' => '',
		'target' => '_self',
		'tcolor' => '',
), $atts));
	$output = '<a href="'.esc_url($link).'" class="btn button btn-shortcode kad-btn kad-btn-primary" id="kadbtn'.esc_attr($id).'" target="'.esc_attr($target).'" style="';
	if(!empty($bcolor)) {
		$output .= 'background-color:'.esc_attr($bcolor).';';
	}
	if(!empty($tcolor)) {
		$output .= 'color:'.esc_attr($tcolor).';';
	}
	$output .= '"';
	if($thovercolor == $tcolor) {$thovercolor = null;}
	if(!empty($bhovercolor) || !empty($thovercolor)) {
		$output .= 'onMouseOver="this.style.background=\''.esc_attr($bhovercolor).'\',this.style.color=\''.esc_attr($thovercolor).'\'" onMouseOut="this.style.background=\''.esc_attr($bcolor).'\', this.style.color=\''.esc_attr($tcolor).'\'"';
	}
	$output .= '>'.wp_kses_post($text).'</a>';

	return $output;
}
function virtue_toolkit_blockquote_shortcode_function( $atts, $content) {
	extract(shortcode_atts(array(
		'align' => 'center',
), $atts));
		switch ($align)
	{
		case "center":
		$output = '<div class="blockquote blockquote-full postclass clearfix">' . do_shortcode(wp_kses_post($content)) . '</div>';
		break;
		
		case "left":
		$output = '<div class="blockquote blockquote-left postclass clearfix">' . do_shortcode(wp_kses_post($content)) . '</div>';
		break;
		
		case "right":
		$output = '<div class="blockquote blockquote-right postclass clearfix">' . do_shortcode(wp_kses_post($content)) . '</div>';
		break;
	}
	  return $output;
}
function virtue_toolkit_pullquote_shortcode_function( $atts, $content) {
   extract( shortcode_atts( array(
	  'align' => 'center'
  ), $atts ));

	switch ($align)
	{
		case "center":
		$output = '<div class="pullquote pullquote-center">' . do_shortcode(wp_kses_post($content)) . '</div>';
		break;
		
		case "right":
		$output = '<div class="pullquote pullquote-right">' . do_shortcode(wp_kses_post($content)) . '</div>';
		break;
		
		case "left":
		$output = '<div class="pullquote pullquote-left">' . do_shortcode(wp_kses_post($content)) . '</div>';
		break;
	}

   return $output;
}
function virtue_toolkit_hrule_function( ) {
	return '<div class="hrule clearfix"></div>';
}
function virtue_toolkit_hrpadding10_function( ) {
	return '<div class="space_20 clearfix"></div>';
}
function virtue_toolkit_hrpadding20_function( ) {
	return '<div class="space_40 clearfix"></div>';
}
function virtue_toolkit_hrpadding40_function( ) {
	return '<div class="space_80 clearfix"></div>';
}
function virtue_toolkit_clearfix_function( ) {
	return '<div class="clearfix"></div>';
}
function virtue_toolkit_columnhelper_function( ) {
	return '';
}
function virtue_toolkit_register_shortcodes(){
   	add_shortcode('accordion', 'virtue_toolkit_accordion_shortcode_function');
   	add_shortcode('pane', 'virtue_toolkit_accordion_pane_function');
   	add_shortcode('tabs', 'virtue_toolkit_tab_shortcode_function');
   	add_shortcode('tab', 'virtue_toolkit_tab_pane_function');
   	add_shortcode('columns', 'virtue_toolkit_column_shortcode_function');
   	add_shortcode('hcolumns', 'virtue_toolkit_hcolumn_shortcode_function');
   	add_shortcode('span11', 'virtue_toolkit_column11_function');
   	add_shortcode('span10', 'virtue_toolkit_column10_function');
   	add_shortcode('span9', 'virtue_toolkit_column9_function');
   	add_shortcode('span8', 'virtue_toolkit_column8_function');
   	add_shortcode('span7', 'virtue_toolkit_column7_function');
   	add_shortcode('span6', 'virtue_toolkit_column6_function');
   	add_shortcode('span5', 'virtue_toolkit_column5_function');
   	add_shortcode('span4', 'virtue_toolkit_column4_function');
   	add_shortcode('span3', 'virtue_toolkit_column3_function');
   	add_shortcode('span2', 'virtue_toolkit_column2_function');
   	add_shortcode('span1', 'virtue_toolkit_column1_function');
   	add_shortcode('columnhelper', 'virtue_toolkit_columnhelper_function');
   	add_shortcode('icon', 'virtue_toolkit_icon_shortcode_function');
   	add_shortcode('pullquote', 'virtue_toolkit_pullquote_shortcode_function');
   	add_shortcode('blockquote', 'virtue_toolkit_blockquote_shortcode_function');
   	add_shortcode('btn', 'virtue_toolkit_button_shortcode_function');
   	add_shortcode('hr', 'virtue_toolkit_hrule_function');
   	add_shortcode('space_20', 'virtue_toolkit_hrpadding10_function');
   	add_shortcode('space_40', 'virtue_toolkit_hrpadding20_function');
   	add_shortcode('space_80', 'virtue_toolkit_hrpadding40_function');
    add_shortcode('kad_youtube', 'virtue_toolkit_youtube_shortcode_function');
    add_shortcode('kt_youtube', 'virtue_toolkit_youtube_shortcode_function');
   	add_shortcode('kad_vimeo', 'virtue_toolkit_vimeo_shortcode_function');
   	add_shortcode('kt_vimeo', 'virtue_toolkit_vimeo_shortcode_function');
   	add_shortcode('clear', 'virtue_toolkit_clearfix_function');
}
add_action( 'init', 'virtue_toolkit_register_shortcodes');


function virtue_toolkit_register_button( $buttons ) {
	if ( empty( $buttons ) ) {
		$buttons = array();
	}
	array_push( $buttons, "|", "kadcolumns" );
	array_push( $buttons, "|", "kaddivider" );
	array_push( $buttons, "|", "kadaccordion" );
	array_push( $buttons, "|", "kadquote" );
	array_push( $buttons, "|", "kadbtn" );
	array_push( $buttons, "|", "kadicon" );
	array_push( $buttons, "|", "kadyoutube" );
	array_push( $buttons, "|", "kadvimeo" );

	return $buttons;
}
function virtue_toolkit_add_plugin( $plugin_array ) {
   $plugin_array['kadcolumns'] = VIRTUE_TOOLKIT_URL . '/shortcodes/columns/columns_shortgen.js';
   $plugin_array['kadicon'] = VIRTUE_TOOLKIT_URL . '/shortcodes/icons/icon_shortgen.js';
   $plugin_array['kadaccordion'] = VIRTUE_TOOLKIT_URL . '/shortcodes/accordion/accordion_shortgen.js';
   $plugin_array['kadyoutube'] = VIRTUE_TOOLKIT_URL . '/shortcodes/youtube/youtube_shortgen.js';
   $plugin_array['kadvimeo'] = VIRTUE_TOOLKIT_URL . '/shortcodes/vimeo/vimeo_shortgen.js';
   $plugin_array['kadquote'] = VIRTUE_TOOLKIT_URL . '/shortcodes/pullquote/quote_shortgen.js';
   $plugin_array['kadbtn'] = VIRTUE_TOOLKIT_URL . '/shortcodes/btns/btns_shortgen.js';
   $plugin_array['kaddivider'] = VIRTUE_TOOLKIT_URL . '/shortcodes/divider/divider_shortgen.js';
   return $plugin_array;
}
function virtue_toolkit_tinymce_shortcode_button() {

	if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') ) {
		return;
	}
	if ( get_user_option('rich_editing') == 'true' ) {
		add_filter( 'mce_external_plugins', 'virtue_toolkit_add_plugin' );
		add_filter( 'mce_buttons_3', 'virtue_toolkit_register_button', 50 );
	}

}
add_action('init', 'virtue_toolkit_tinymce_shortcode_button');

//    Clean up Shortcodes
function virtue_toolkit_content_clean_shortcodes($content){   
    $array = array (
        '<p>[' => '[', 
        ']</p>' => ']', 
        ']<br />' => ']'
    );
    $content = strtr($content, $array);
    return $content;
}
add_filter('the_content', 'virtue_toolkit_content_clean_shortcodes');
function virtue_toolkit_widget_clean_shortcodes($text){   
    $array = array (
        '<p>[' => '[', 
        ']</p>' => ']', 
        '<p></p>' => '', 
        ']<br />' => ']',
        '<br />[' => '['
    );
    $text = strtr($text, $array);
    return $text;
}
add_filter('widget_text', 'virtue_toolkit_widget_clean_shortcodes');
add_filter('widget_text', 'do_shortcode', 50);
