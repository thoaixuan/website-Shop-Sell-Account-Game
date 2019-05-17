<?php
/**
 * Plugin Name: Kadence Toolkit
 * Description: Custom Portfolio and Shortcode functionality for free Kadence WordPress themes
 * Version: 4.9.2
 * Author: Kadence Themes
 * Author URI: https://kadencethemes.com/
 * License: GPLv2 or later
 *
 * @package Kadence Toolkit
 */

/**
 * Kadence Toolkit Activation
 */
function virtue_toolkit_activation() {
	flush_rewrite_rules();
	get_option( 'kadence_toolkit_flushpermalinks', '2' );
}
register_activation_hook( __FILE__, 'virtue_toolkit_activation' );

/**
 * Set redux args
 *
 * @param array $args redux framework args.
 */
function virtue_toolkit_redux_args_new( $args ) {
	$args['customizer_only'] = false;
	$args['save_defaults']   = true;
	return $args;
}
add_filter( 'kadence_theme_options_args', 'virtue_toolkit_redux_args_new' );

if ( ! defined( 'VIRTUE_TOOLKIT_PATH' ) ) {
	define( 'VIRTUE_TOOLKIT_PATH', realpath( plugin_dir_path( __FILE__ ) ) . DIRECTORY_SEPARATOR );
}
if ( ! defined( 'VIRTUE_TOOLKIT_URL' ) ) {
	define( 'VIRTUE_TOOLKIT_URL', plugin_dir_url( __FILE__ ) );
}

require_once VIRTUE_TOOLKIT_PATH . 'kadence_image_processing.php';
require_once VIRTUE_TOOLKIT_PATH . 'post-types.php';
require_once VIRTUE_TOOLKIT_PATH . 'gallery.php';
require_once VIRTUE_TOOLKIT_PATH . 'author_box.php';
require_once VIRTUE_TOOLKIT_PATH . 'shortcodes.php';
require_once VIRTUE_TOOLKIT_PATH . 'shortcode_ajax.php';
require_once VIRTUE_TOOLKIT_PATH . 'pagetemplater.php';
require_once VIRTUE_TOOLKIT_PATH . 'metaboxes.php';
require_once VIRTUE_TOOLKIT_PATH . 'class-virtue-toolkit-welcome.php';
require_once VIRTUE_TOOLKIT_PATH . 'widgets.php';

/**
 * Virtue Toolkit Textdomain
 */
function virtue_toolkit_textdomain() {
	load_plugin_textdomain( 'virtue-toolkit', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'virtue_toolkit_textdomain' );


function virtue_toolkit_admin_scripts( $hook ) {
	wp_enqueue_style( 'virtue_toolkit_adminstyles', VIRTUE_TOOLKIT_URL . '/assets/toolkit_admin.css', false, 46 );

	if( $hook != 'edit.php' && $hook != 'post.php' && $hook != 'post-new.php' && $hook != 'widgets.php' ) {
		return;
	}
	wp_enqueue_media();
	wp_enqueue_script('toolkit_gallery_meta', VIRTUE_TOOLKIT_URL . '/assets/kttk_admin_gallery.js', array( 'jquery' ), 460, false);

}

add_action('admin_enqueue_scripts', 'virtue_toolkit_admin_scripts');

function virtue_toolkit_flushpermalinks() {
	$hasflushed = get_option('kadence_toolkit_flushpermalinks', '0');
	if($hasflushed != '2') {
		flush_rewrite_rules();
		update_option('kadence_toolkit_flushpermalinks', '2');
	}
}
add_action('init', 'virtue_toolkit_flushpermalinks');


add_action( 'after_setup_theme', 'virtue_toolkit_add_in_slider_sections', 1);
function virtue_toolkit_add_in_slider_sections() {
	$the_theme = wp_get_theme();
	// Ascend only 
	if( $the_theme->get( 'Name' ) == 'Ascend' || $the_theme->get( 'Template') == 'ascend' ) {

	    if ( ! class_exists( 'Redux' ) ) {
	        return;
	    }
	    if(ReduxFramework::$_version <= '3.5.6') {
	        return;
	    }

	    $options_slug = 'ascend';
	    $home_header = Redux::getField($options_slug, 'home_header');
	    if(is_array($home_header)){
	    	$hextras = array('basic' => __('Basic Slider', 'ascend'), 'basic_post_carousel' => __('Post Carousel', 'ascend'));
	    	$home_header['options'] = array_merge($hextras, $home_header['options']);
	    }
	    Redux::setField($options_slug, $home_header);
	}
}



