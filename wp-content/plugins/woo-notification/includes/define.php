<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
define( 'VI_WNOTIFICATION_F_DIR', WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . "woo-notification" . DIRECTORY_SEPARATOR );
define( 'VI_WNOTIFICATION_F_ADMIN', VI_WNOTIFICATION_F_DIR . "admin" . DIRECTORY_SEPARATOR );
define( 'VI_WNOTIFICATION_F_FRONTEND', VI_WNOTIFICATION_F_DIR . "frontend" . DIRECTORY_SEPARATOR );
define( 'VI_WNOTIFICATION_F_LANGUAGES', VI_WNOTIFICATION_F_DIR . "languages" . DIRECTORY_SEPARATOR );
define( 'VI_WNOTIFICATION_F_INCLUDES', VI_WNOTIFICATION_F_DIR . "includes" . DIRECTORY_SEPARATOR );
define( 'VI_WNOTIFICATION_F_TEMPLATES', VI_WNOTIFICATION_F_DIR . "templates" . DIRECTORY_SEPARATOR );
//$plugin_url = plugins_url( 'woo-notification' );
$plugin_url = plugins_url( '', __FILE__ );
$plugin_url = str_replace( '/includes', '', $plugin_url );
define( 'VI_WNOTIFICATION_F_CSS', $plugin_url . "/css/" );
define( 'VI_WNOTIFICATION_F_CSS_DIR', VI_WNOTIFICATION_F_DIR . "css" . DIRECTORY_SEPARATOR );
define( 'VI_WNOTIFICATION_F_JS', $plugin_url . "/js/" );
define( 'VI_WNOTIFICATION_F_JS_DIR', VI_WNOTIFICATION_F_DIR . "js" . DIRECTORY_SEPARATOR );
define( 'VI_WNOTIFICATION_F_IMAGES', $plugin_url . "/images/" );
define( 'VI_WNOTIFICATION_F_BACKGROUND_IMAGES', VI_WNOTIFICATION_F_IMAGES . 'background/' );


/*Include functions file*/
if ( is_file( VI_WNOTIFICATION_F_INCLUDES . "functions.php" ) ) {
	require_once VI_WNOTIFICATION_F_INCLUDES . "functions.php";
}
if ( is_file( VI_WNOTIFICATION_F_INCLUDES . "data.php" ) ) {
	require_once VI_WNOTIFICATION_F_INCLUDES . "data.php";
}
/*Include functions file*/
if ( is_file( VI_WNOTIFICATION_F_INCLUDES . "support.php" ) ) {
	require_once VI_WNOTIFICATION_F_INCLUDES . "support.php";
}
if ( is_file( VI_WNOTIFICATION_F_INCLUDES . "mobile_detect.php" ) ) {
	require_once VI_WNOTIFICATION_F_INCLUDES . "mobile_detect.php";
}

vi_include_folder( VI_WNOTIFICATION_F_ADMIN, 'VI_WNOTIFICATION_F_Admin_' );
vi_include_folder( VI_WNOTIFICATION_F_FRONTEND, 'VI_WNOTIFICATION_F_Frontend_' );