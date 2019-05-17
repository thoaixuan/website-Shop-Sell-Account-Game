<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Function include all files in folder
 *
 * @param $path   Directory address
 * @param $ext    array file extension what will include
 * @param $prefix string Class prefix
 */
if ( ! function_exists( 'vi_include_folder' ) ) {
	function vi_include_folder( $path, $prefix = '', $ext = array( 'php' ) ) {

		/*Include all files in payment folder*/
		if ( ! is_array( $ext ) ) {
			$ext = explode( ',', $ext );
			$ext = array_map( 'trim', $ext );
		}
		$sfiles = scandir( $path );
		foreach ( $sfiles as $sfile ) {
			if ( $sfile != '.' && $sfile != '..' ) {
				if ( is_file( $path . "/" . $sfile ) ) {
					$ext_file  = pathinfo( $path . "/" . $sfile );
					$file_name = $ext_file['filename'];
					if ( $ext_file['extension'] ) {
						if ( in_array( $ext_file['extension'], $ext ) ) {
							$class = preg_replace( '/\W/i', '_', $prefix . ucfirst( $file_name ) );

							if ( ! class_exists( $class ) ) {
								require_once $path . $sfile;
								if ( class_exists( $class ) ) {
									new $class;
								}
							}
						}
					}
				}
			}
		}
	}
}
if ( ! function_exists( 'woocommerce_notification_prefix' ) ) {
	function woocommerce_notification_prefix() {
		$prefix = get_option( '_woocommerce_notification_prefix', date( "Ymd" ) );

		return $prefix . '_products_' . date( "Ymd" );
	}
}

if ( ! function_exists( 'woocommerce_notification_wpversion' ) ) {
	function woocommerce_notification_wpversion() {
		global $wp_version;
		if ( version_compare( $wp_version, '4.5.0', '<=' ) ) {
			return true;
		} else {
			false;
		}
	}
}
if ( ! function_exists( 'woocommerce_notification_background_images' ) ) {
	function woocommerce_notification_background_images( $key = false ) {
		$prefix   = $key ? 'bg_' : '';
		$ext      = $key ? '.png' : '.jpg';
		$b_images = array(
			'spring'         => VI_WNOTIFICATION_F_BACKGROUND_IMAGES . $prefix . 'spring' . $ext,
			'summer'         => VI_WNOTIFICATION_F_BACKGROUND_IMAGES . $prefix . 'summer' . $ext,
			'autumn'         => VI_WNOTIFICATION_F_BACKGROUND_IMAGES . $prefix . 'autumn' . $ext,
			'winter'         => VI_WNOTIFICATION_F_BACKGROUND_IMAGES . $prefix . 'winter' . $ext,
			'christmas'      => VI_WNOTIFICATION_F_BACKGROUND_IMAGES . $prefix . 'christmas' . $ext,
			'christmas_1'    => VI_WNOTIFICATION_F_BACKGROUND_IMAGES . $prefix . 'christmas_1' . $ext,
			'black_friday'   => VI_WNOTIFICATION_F_BACKGROUND_IMAGES . $prefix . 'black_friday' . $ext,
			'happy_new_year' => VI_WNOTIFICATION_F_BACKGROUND_IMAGES . $prefix . 'happy_new_year' . $ext,
			'valentine'      => VI_WNOTIFICATION_F_BACKGROUND_IMAGES . $prefix . 'valentine' . $ext,
			'father'         => VI_WNOTIFICATION_F_BACKGROUND_IMAGES . $prefix . 'father' . $ext,
			'halloween'      => VI_WNOTIFICATION_F_BACKGROUND_IMAGES . $prefix . 'halloween' . $ext,
			'halloween_1'    => VI_WNOTIFICATION_F_BACKGROUND_IMAGES . $prefix . 'halloween_1' . $ext,
			'kids'           => VI_WNOTIFICATION_F_BACKGROUND_IMAGES . $prefix . 'kids' . $ext,
			'kids_1'         => VI_WNOTIFICATION_F_BACKGROUND_IMAGES . $prefix . 'kids_1' . $ext,
			'mother'         => VI_WNOTIFICATION_F_BACKGROUND_IMAGES . $prefix . 'mother' . $ext,
			'mother_1'       => VI_WNOTIFICATION_F_BACKGROUND_IMAGES . $prefix . 'mother_1' . $ext,
		);
		if ( $key ) {
			return isset( $b_images[ $key ] ) ? $b_images[ $key ] : false;
		} else {
			return $b_images;
		}

	}
}

/**
 *
 * @param string $version
 *
 * @return bool
 */
if ( ! function_exists( 'woocommerce_version_check' ) ) {
	function woocommerce_version_check( $version = '3.0' ) {
		global $woocommerce;

		if ( version_compare( $woocommerce->version, $version, ">=" ) ) {
			return true;
		}

		return false;
	}
}