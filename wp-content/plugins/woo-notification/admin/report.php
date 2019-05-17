<?php

/*
Class Name: VI_WNOTIFICATION_F_Admin_Report
Author: Andy Ha (support@villatheme.com)
Author URI: http://villatheme.com
Copyright 2015-2018 villatheme.com. All rights reserved.
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class VI_WNOTIFICATION_F_Admin_Report {

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'menu_page' ) );
	}

	/**
	 * HTML Reporting
	 */
	public function page_callback() { ?>
		<h2><?php esc_html_e( 'Notification for WooCommerce Reporting', 'woo-notification' ) ?></h2>
		<a class="vi-ui button" target="_blank" href="http://bit.ly/woocommerce-notification"><?php esc_html_e( 'Update This Feature', 'woo-notification' ) ?></a>
	<?php }

	/**
	 * Register a custom menu page.
	 */
	public function menu_page() {
		add_submenu_page(
			'woo-notification',
			esc_html__( 'Report', 'woo-notification' ),
			esc_html__( 'Report', 'woo-notification' ),
			'manage_options',
			'woo-notification-report',
			array( $this, 'page_callback' )
		);

	}
}

?>