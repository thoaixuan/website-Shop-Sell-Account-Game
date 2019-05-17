<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get Notification for WooCommerce Data Setting
 * Class VI_WNOTIFICATION_F_Data
 */
class VI_WNOTIFICATION_F_Data {
	private $params;

	/**
	 * WOOMULTI_CURRENCY_Data constructor.
	 * Init setting
	 */
	public function __construct() {

		global $woocommerce_notification_settings;

		if ( ! $woocommerce_notification_settings ) {
			$woocommerce_notification_settings = get_option( 'wnotification_params', array() );
		}
		$this->params = $woocommerce_notification_settings;
		$args         = array(
			'enable'                      => 0,
			'enable_mobile'               => 0,
			'enable_rtl'                  => 0,
			'highlight_color'             => '#212121',
			'text_color'                  => '#212121',
			'background_color'            => '#ffffff',
			'background_image'            => 0,
			'image_position'              => 0,
			'position'                    => 0,
			'border_radius'               => 0,
			'show_close_icon'             => 0,
			'time_close'                  => 0,
			'image_redirect'              => 0,
			'image_redirect_target'       => 0,
			'message_display_effect'      => 'fade-in',
			'message_hidden_effect'       => 'fade-out',
			'custom_css'                  => '',
			'message_purchased'           => array(),
			'custom_shortcode'            => '{number} people seeing this product right now',
			'min_number'                  => '100',
			'max_number'                  => '200',
			'archive_page'                => 0,
			'select_categories'           => array(),
			'cate_exclude_products'       => array(),
			'exclude_products'            => array(),
			'order_threshold_num'         => 30,
			'order_threshold_time'        => 0,
			'order_statuses'              => array( 'wc-processing', 'wc-completed' ),
			'archive_products'            => array(),
			'virtual_name'                => '',
			'virtual_time'                => 10,
			'country'                     => 0,
			'virtual_city'                => '',
			'virtual_country'             => '',
			'ipfind_auth_key'             => '',
			'product_sizes'               => 'shop_thumbnail',
			'non_ajax'                    => 0,
			'enable_out_of_stock_product' => 0,
			'product_link'                => 0,
		);
		$this->params = apply_filters( 'woonotification_settings_args', wp_parse_args( $this->params, $args ) );
		//		print_r($this->params);die;
	}

	/**
	 * Get time close cookie
	 * @return mixed|void
	 */
	public function get_time_close() {
		return apply_filters( 'woonotification_get_time_close', $this->params['time_close'] );
	}

	/**
	 * Enable RTL
	 * @return mixed|void
	 */
	public function enable_rtl() {
		return is_rtl();
	}

	/**
	 * Check External product
	 * @return mixed|void
	 */
	public function product_link() {
		return apply_filters( 'woonotification_product_link', $this->params['product_link'] );
	}

	/**
	 * Check enable plugin
	 * @return mixed|void
	 */
	public function enable() {
		return apply_filters( 'woonotification_enable', $this->params['enable'] );
	}

	/**
	 * Check enable mobile
	 * @return mixed|void
	 */
	public function enable_mobile() {
		return apply_filters( 'woonotification_enable_mobile', $this->params['enable_mobile'] );
	}

	/**
	 * Get Highlight Color
	 * @return mixed|void
	 */
	public function get_highlight_color() {
		return apply_filters( 'woonotification_get_highlight_color', $this->params['highlight_color'] );
	}

	/**
	 * Get Text Color
	 * @return mixed|void
	 */
	public function get_text_color() {
		return apply_filters( 'woonotification_get_text_color', $this->params['text_color'] );
	}

	/**
	 * Get Background Color
	 * @return mixed|void
	 */
	public function get_background_color() {
		return apply_filters( 'woonotification_get_background_color', $this->params['background_color'] );
	}

	/**
	 * Get Background Image
	 * @return mixed|void
	 */
	public function get_background_image() {
		return apply_filters( 'woonotification_get_background_image', $this->params['background_image'] );
	}

	/**
	 * Get Image Position
	 * @return mixed|void
	 */
	public function get_image_position() {
		return apply_filters( 'woonotification_get_image_position', $this->params['image_position'] );
	}

	/**
	 * Get position
	 * @return mixed|void
	 */
	public function get_position() {
		return apply_filters( 'woonotification_get_position', $this->params['position'] );
	}

	/**
	 * Get border radius
	 * @return mixed|void
	 */
	public function get_border_radius() {
		return apply_filters( 'woonotification_get_border_radius', $this->params['border_radius'] );
	}

	/**
	 * Check show close icon
	 * @return mixed|void
	 */
	public function show_close_icon() {
		return apply_filters( 'woonotification_image_redirect', $this->params['show_close_icon'] );
	}

	/**
	 * Check image clickable
	 * @return mixed|void
	 */
	public function image_redirect() {
		return apply_filters( 'woonotification_image_redirect', $this->params['image_redirect'] );
	}

	public function image_redirect_target() {
		return apply_filters( 'woonotification_image_redirect_target', $this->params['image_redirect_target'] );
	}

	/**
	 * Get Display Effect
	 * @return mixed|void
	 */
	public function get_display_effect() {
		return apply_filters( 'woonotification_get_message_display_effect', $this->params['message_display_effect'] );
	}

	/**
	 * Get Hidden Effect
	 * @return mixed|void
	 */
	public function get_hidden_effect() {
		return apply_filters( 'woonotification_get_message_hidden_effect', $this->params['message_hidden_effect'] );
	}

	/**
	 * Get custom CSS
	 * @return mixed|void
	 */
	public function get_custom_css() {
		return apply_filters( 'woonotification_get_custom_css', $this->params['custom_css'] );
	}

	/**
	 * Get message purchased with shortcode
	 * @return mixed|void
	 */
	public function get_message_purchased() {
		if ( is_plugin_active( 'sitepress-multilingual-cms/sitepress.php' ) ) {
			$current_lang = wpml_get_current_language();
			if ( isset( $this->params[ 'message_purchased_' . $current_lang ] ) ) {
				return apply_filters( 'woonotification_get_message_purchased_' . $current_lang, $this->params[ 'message_purchased_' . $current_lang ] );
			}
		} elseif ( class_exists( 'Polylang' ) ) {
			$current_lang = pll_current_language( 'slug' );
			if ( isset( $this->params[ 'message_purchased_' . $current_lang ] ) ) {
				return apply_filters( 'woonotification_get_message_purchased_' . $current_lang, $this->params[ 'message_purchased_' . $current_lang ] );
			}
		}

		return apply_filters( 'woonotification_get_message_purchased', $this->params['message_purchased'] );
	}

	/**
	 * Get custom shortcode
	 * @return mixed|void
	 */
	public function get_custom_shortcode() {
		if ( is_plugin_active( 'sitepress-multilingual-cms/sitepress.php' ) ) {
			$current_lang = wpml_get_current_language();
			if ( isset( $this->params[ 'custom_shortcode_' . $current_lang ] ) ) {
				return apply_filters( 'woonotification_get_custom_shortcode_' . $current_lang, $this->params[ 'custom_shortcode_' . $current_lang ] );
			} elseif ( class_exists( 'Polylang' ) ) {
				$current_lang = pll_current_language( 'slug' );
				if ( isset( $this->params[ 'message_purchased_' . $current_lang ] ) ) {
					return apply_filters( 'woonotification_get_message_purchased_' . $current_lang, $this->params[ 'message_purchased_' . $current_lang ] );
				}
			}
		}

		return apply_filters( 'woonotification_get_custom_shortcode', $this->params['custom_shortcode'] );
	}

	/**
	 * Get min number in shortcode
	 * @return mixed|void
	 */
	public function get_min_number() {
		return apply_filters( 'woonotification_get_min_number', $this->params['min_number'] );
	}

	/**
	 * Get max number in shortcode
	 * @return mixed|void
	 */
	public function get_max_number() {
		return apply_filters( 'woonotification_get_max_number', $this->params['max_number'] );
	}

	/**
	 * Check notification data type to get
	 * @return mixed|void
	 */
	public function archive_page() {
		return apply_filters( 'woonotification_get_archive_page', $this->params['archive_page'] );
	}

	/**
	 * Get list categories
	 * @return mixed|void
	 */
	public function get_categories() {
		return apply_filters( 'woonotification_get_select_categories', $this->params['select_categories'] );
	}

	/**
	 * Get exclude products of Categories
	 * @return mixed|void
	 */
	public function get_cate_exclude_products() {
		return apply_filters( 'woonotification_get_cate_exclude_products', $this->params['cate_exclude_products'] );
	}

	/**
	 * Get limit products
	 * @return mixed|void
	 */
	public function get_limit_product() {
		return apply_filters( 'woonotification_get_limit_product', $this->params['limit_product'] );
	}

	/**
	 * Get exclude products of get product from billing
	 * @return mixed|void
	 */
	public function get_exclude_products() {
		return apply_filters( 'woonotification_get_exclude_products', $this->params['exclude_products'] );
	}

	/**
	 * Get threshold number
	 * @return mixed|void
	 */
	public function get_order_threshold_num() {
		return apply_filters( 'woonotification_get_order_threshold_num', $this->params['order_threshold_num'] );
	}

	/**
	 * Get threshold type
	 * @return mixed|void
	 */
	public function get_order_threshold_time() {
		return apply_filters( 'woonotification_get_order_threshold_time', $this->params['order_threshold_time'] );
	}

	/**
	 * Get order status
	 * @return mixed|void
	 */
	public function get_order_statuses() {
		return apply_filters( 'woonotification_get_order_statuses', $this->params['order_statuses'] );
	}

	/**
	 * Get list products
	 * @return mixed|void
	 */
	public function get_products() {
		return apply_filters( 'woonotification_get_archive_products', $this->params['archive_products'] );
	}

	/**
	 * Check address type
	 * @return mixed|void
	 */
	public function country() {
		return apply_filters( 'woonotification_country', $this->params['country'] );
	}

	/**
	 * Get Virtual Time
	 * @return mixed|void
	 */
	public function get_virtual_name() {
		if ( is_plugin_active( 'sitepress-multilingual-cms/sitepress.php' ) ) {
			$current_lang = wpml_get_current_language();
			if ( isset( $this->params[ 'virtual_name_' . $current_lang ] ) ) {
				return apply_filters( 'woonotification_get_virtual_name_' . $current_lang, $this->params[ 'virtual_name_' . $current_lang ] );
			} elseif ( class_exists( 'Polylang' ) ) {
				$current_lang = pll_current_language( 'slug' );
				if ( isset( $this->params[ 'message_purchased_' . $current_lang ] ) ) {
					return apply_filters( 'woonotification_get_message_purchased_' . $current_lang, $this->params[ 'message_purchased_' . $current_lang ] );
				}
			}
		}

		return apply_filters( 'woonotification_get_virtual_name', $this->params['virtual_name'] );
	}

	/**
	 * Get Virtual Time
	 * @return mixed|void
	 */
	public function get_virtual_time() {
		return apply_filters( 'woonotification_get_virtual_time', $this->params['virtual_time'] );
	}

	/**
	 * Get Virtual City
	 * @return mixed|void
	 */
	public function get_virtual_city() {
		if ( is_plugin_active( 'sitepress-multilingual-cms/sitepress.php' ) ) {
			$current_lang = wpml_get_current_language();
			if ( isset( $this->params[ 'virtual_city_' . $current_lang ] ) ) {
				return apply_filters( 'woonotification_get_virtual_city_' . $current_lang, $this->params[ 'virtual_city_' . $current_lang ] );
			} elseif ( class_exists( 'Polylang' ) ) {
				$current_lang = pll_current_language( 'slug' );
				if ( isset( $this->params[ 'message_purchased_' . $current_lang ] ) ) {
					return apply_filters( 'woonotification_get_message_purchased_' . $current_lang, $this->params[ 'message_purchased_' . $current_lang ] );
				}
			}
		}

		return apply_filters( 'woonotification_get_virtual_city', $this->params['virtual_city'] );
	}

	/**
	 * Get Virtual Country
	 * @return mixed|void
	 */
	public function get_virtual_country() {
		if ( is_plugin_active( 'sitepress-multilingual-cms/sitepress.php' ) ) {
			$current_lang = wpml_get_current_language();
			if ( isset( $this->params[ 'virtual_country_' . $current_lang ] ) ) {
				return apply_filters( 'woonotification_get_virtual_country_' . $current_lang, $this->params[ 'virtual_country_' . $current_lang ] );
			} elseif ( class_exists( 'Polylang' ) ) {
				$current_lang = pll_current_language( 'slug' );
				if ( isset( $this->params[ 'message_purchased_' . $current_lang ] ) ) {
					return apply_filters( 'woonotification_get_message_purchased_' . $current_lang, $this->params[ 'message_purchased_' . $current_lang ] );
				}
			}
		}

		return apply_filters( 'woonotification_get_virtual_country', $this->params['virtual_country'] );
	}


	/**
	 * Get product image size
	 * @return mixed|void
	 */
	public function get_product_sizes() {
		return apply_filters( 'woonotification_get_product_sizes', $this->params['product_sizes'] );
	}

	/**
	 * Check turn off Ajax
	 * @return mixed|void
	 */
	public function non_ajax() {
		return apply_filters( 'woonotification_non_ajax', $this->params['non_ajax'] );
	}

	public function enable_out_of_stock_product() {
		return apply_filters( 'woonotification_enable_out_of_stock_product', $this->params['enable_out_of_stock_product'] );
	}

	/**
	 * Get purchased code
	 * @return mixed|void
	 */
	public function get_geo_api() {
		return apply_filters( 'woonotification_get_key', $this->params['key'] );
	}


}

new VI_WNOTIFICATION_F_Data();