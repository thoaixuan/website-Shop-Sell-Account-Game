<?php
/*
 * Widget being added soon
 */

//add_action( 'init', 'kadence_toolkit_initialize_theme_widgets', 5 );
function kadence_toolkit_initialize_theme_widgets() {
	$the_theme = wp_get_theme();
	if ( 'Pinnacle' == $the_theme->get( 'Name' ) || 'pinnacle' == $the_theme->get( 'Template') ) {
		require_once( VIRTUE_TOOLKIT_PATH .'widgets/class-kad-image-widget.php');
	} else if( 'Virtue' == $the_theme->get( 'Name' ) || 'virtue' == $the_theme->get( 'Template') ) {
		require_once( VIRTUE_TOOLKIT_PATH .'widgets/class-simple-about-with-image.php');
	} else if( 'Ascend' == $the_theme->get( 'Name' ) || 'ascend' == $the_theme->get( 'Template')  ) {
		require_once( VIRTUE_TOOLKIT_PATH .'widgets/class-ascend-image-widget.php');
	}

}
