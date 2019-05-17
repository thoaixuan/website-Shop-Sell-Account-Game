<?php

/*
Class Name: VI_WNOTIFICATION_F_Admin_Admin
Author: Andy Ha (support@villatheme.com)
Author URI: http://villatheme.com
Copyright 2015-2018 villatheme.com. All rights reserved.
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class VI_WNOTIFICATION_F_Admin_Admin {
	function __construct() {

		add_filter( 'plugin_action_links_woo-notification/woo-notification.php', array(
			$this,
			'settings_link'
		) );
		add_action( 'init', array( $this, 'init' ) );
		add_action( 'admin_menu', array( $this, 'menu_page' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ), 99999 );
	}


	/**
	 * Init Script in Admin
	 */
	public function admin_enqueue_scripts() {
		$page = isset( $_REQUEST['page'] ) ? $_REQUEST['page'] : '';
		if ( $page == 'woo-notification' ) {
			global $wp_scripts;
			$scripts = $wp_scripts->registered;
			//			print_r($scripts);
			foreach ( $scripts as $k => $script ) {
				preg_match( '/^\/wp-/i', $script->src, $result );
				if ( count( array_filter( $result ) ) < 1 ) {
					wp_dequeue_script( $script->handle );
				}
			}

			/*Stylesheet*/
			wp_enqueue_style( 'woo-notification-image', VI_WNOTIFICATION_F_CSS . 'image.min.css' );
			wp_enqueue_style( 'woo-notification-transition', VI_WNOTIFICATION_F_CSS . 'transition.min.css' );
			wp_enqueue_style( 'woo-notification-form', VI_WNOTIFICATION_F_CSS . 'form.min.css' );
			wp_enqueue_style( 'woo-notification-icon', VI_WNOTIFICATION_F_CSS . 'icon.min.css' );
			wp_enqueue_style( 'woo-notification-dropdown', VI_WNOTIFICATION_F_CSS . 'dropdown.min.css' );
			wp_enqueue_style( 'woo-notification-checkbox', VI_WNOTIFICATION_F_CSS . 'checkbox.min.css' );
			wp_enqueue_style( 'woo-notification-segment', VI_WNOTIFICATION_F_CSS . 'segment.min.css' );
			wp_enqueue_style( 'woo-notification-menu', VI_WNOTIFICATION_F_CSS . 'menu.min.css' );
			wp_enqueue_style( 'woo-notification-tab', VI_WNOTIFICATION_F_CSS . 'tab.css' );
			wp_enqueue_style( 'woo-notification-button', VI_WNOTIFICATION_F_CSS . 'button.min.css' );
			wp_enqueue_style( 'woo-notification-grid', VI_WNOTIFICATION_F_CSS . 'grid.min.css' );
			wp_enqueue_style( 'woo-notification-front', VI_WNOTIFICATION_F_CSS . 'woo-notification.css' );
			wp_enqueue_style( 'woo-notification-admin', VI_WNOTIFICATION_F_CSS . 'woo-notification-admin.css' );
			wp_enqueue_style( 'select2', VI_WNOTIFICATION_F_CSS . 'select2.min.css' );
			if ( woocommerce_version_check( '3.0.0' ) ) {
				wp_enqueue_script( 'select2' );
			} else {
				wp_enqueue_script( 'select2-v4', VI_WNOTIFICATION_F_JS . 'select2.js', array( 'jquery' ), '4.0.3' );
			}
			/*Script*/
			wp_enqueue_script( 'woo-notification-dependsOn', VI_WNOTIFICATION_F_JS . 'dependsOn-1.0.2.min.js', array( 'jquery' ) );
			wp_enqueue_script( 'woo-notification-transition', VI_WNOTIFICATION_F_JS . 'transition.min.js', array( 'jquery' ) );
			wp_enqueue_script( 'woo-notification-dropdown', VI_WNOTIFICATION_F_JS . 'dropdown.js', array( 'jquery' ) );
			wp_enqueue_script( 'woo-notification-checkbox', VI_WNOTIFICATION_F_JS . 'checkbox.js', array( 'jquery' ) );
			wp_enqueue_script( 'woo-notification-tab', VI_WNOTIFICATION_F_JS . 'tab.js', array( 'jquery' ) );
			wp_enqueue_script( 'woo-notification-address', VI_WNOTIFICATION_F_JS . 'jquery.address-1.6.min.js', array( 'jquery' ) );
			wp_enqueue_script( 'woo-notification-admin', VI_WNOTIFICATION_F_JS . 'woo-notification-admin.js', array( 'jquery' ) );

			/*Color picker*/
			wp_enqueue_script( 'iris', admin_url( 'js/iris.min.js' ), array(
				'jquery-ui-draggable',
				'jquery-ui-slider',
				'jquery-touch-punch'
			), false, 1 );

			/*Custom*/
			$params           = new VI_WNOTIFICATION_F_Admin_Settings();
			$highlight_color  = $params->get_field( 'highlight_color' );
			$text_color       = $params->get_field( 'text_color' );
			$background_color = $params->get_field( 'background_color' );
			$custom_css       = "
                #message-purchased{
                        background-color: {$background_color};
                        color:{$text_color};
                }
                 #message-purchased a{
                        color:{$highlight_color};
                }
                ";
			wp_add_inline_style( 'woo-notification', $custom_css );

		}
	}

	/**
	 * Link to Settings
	 *
	 * @param $links
	 *
	 * @return mixed
	 */
	public function settings_link( $links ) {
		$settings_link = '<a href="admin.php?page=woo-notification" title="' . __( 'Settings', 'woo-notification' ) . '">' . __( 'Settings', 'woo-notification' ) . '</a>';
		array_unshift( $links, $settings_link );

		return $links;
	}


	/**
	 * Function init when run plugin+
	 */
	function init() {
		/*Register post type*/

		load_plugin_textdomain( 'woo-notification' );
		$this->load_plugin_textdomain();

	}


	/**
	 * load Language translate
	 */
	public function load_plugin_textdomain() {
		$locale = apply_filters( 'plugin_locale', get_locale(), 'woo-notification' );
		// Admin Locale
		if ( is_admin() ) {
			load_textdomain( 'woo-notification', VI_WNOTIFICATION_F_LANGUAGES . "woo-notification-$locale.mo" );
		}

		// Global + Frontend Locale
		load_textdomain( 'woo-notification', VI_WNOTIFICATION_F_LANGUAGES . "woo-notification-$locale.mo" );
		load_plugin_textdomain( 'woo-notification', false, VI_WNOTIFICATION_F_LANGUAGES );
	}

	/**
	 * Register a custom menu page.
	 */
	public function menu_page() {
		add_menu_page( esc_html__( 'Notification for WooCommerce', 'woo-notification' ), esc_html__( 'Woo Notification', 'woo-notification' ), 'manage_options', 'woo-notification', array(
				'VI_WNOTIFICATION_F_Admin_Settings',
				'page_callback'
			), 'dashicons-megaphone', 2 );

	}

}
