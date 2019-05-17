<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'VillaTheme_Support' ) ) {

	/**
	 * Class VillaTheme_Support
	 * 1.0.5
	 */
	class VillaTheme_Support {
		public function __construct( $data ) {
			$this->data               = array();
			$this->data['support']    = $data['support'];
			$this->data['docs']       = $data['docs'];
			$this->data['review']     = $data['review'];
			$this->data['css_url']    = $data['css'];
			$this->data['images_url'] = $data['image'];
			$this->data['slug']       = $data['slug'];
			$this->data['menu_slug']  = $data['menu_slug'];
			$this->data['version']    = isset( $data['version'] ) ? $data['version'] : '1.0.0';
			$this->data['pro_url']    = isset( $data['pro_url'] ) ? $data['pro_url'] : '';
			add_action( 'villatheme_support_' . $this->data['slug'], array( $this, 'villatheme_support' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'scripts' ) );
			add_action( 'admin_notices', array( $this, 'review_notice' ) );
			add_action( 'admin_init', array( $this, 'hide_review_notice' ) );
			add_action( 'admin_menu', array( $this, 'admin_menu' ), 9999 );

			/*Admin notices*/
			if ( ! get_transient( 'villatheme_call' ) || get_transient( 'villatheme_call' ) == $this->data['slug'] ) {
				set_transient( 'villatheme_call', $this->data['slug'], 86400 );
				/*Hide notices*/
				add_action( 'admin_init', array( $this, 'hide_notices' ) );

				add_action( 'admin_notices', array( $this, 'form_ads' ) );

				/*Admin dashboard*/
				add_action( 'wp_dashboard_setup', array( $this, 'dashboard' ) );
			}
		}

		/**
		 * Add Extension page
		 */
		function admin_menu() {
			add_submenu_page(
				$this->data['menu_slug'], esc_html__( 'Extensions', $this->data['slug'] ), esc_html__( 'Extensions', $this->data['slug'] ), 'manage_options', $this->data['slug'] . '-extensions', array(
					$this,
					'page_callback'
				)
			);
			if ( $this->data['menu_slug'] && $this->data['pro_url'] ) {
				global $submenu;
				$submenu[ $this->data['menu_slug'] ][] = array(
					esc_html__( 'Try Premium Version', $this->data['slug'] ),
					'manage_options',
					$this->data['pro_url']
				);
			}
		}

		/**
		 * Extensions page
		 * @return bool
		 */
		public function page_callback() { ?>
            <div class="villatheme-extension-page">
                <div class="villatheme-extension-top">
                    <h2><?php echo esc_html__( 'THE BEST PLUGINS FOR WOOCOMMERCE', $this->data['slug'] ) ?></h2>
                    <p><?php echo esc_html__( 'Our plugins are constantly updated and thanks to your feedback. We add new features on a daily basis. Try our live demo and start increasing the conversions on your ecommerce right away.', $this->data['slug'] ) ?></p>
                </div>
                <div class="villatheme-extension-content">
					<?php
					$feeds = get_transient( 'villatheme_ads' );
					if ( ! $feeds ) {
						@$ads = file_get_contents( 'https://villatheme.com/wp-json/info/v1' );
						set_transient( 'villatheme_ads', $ads, 86400 );
					} else {
						$ads = $feeds;
					}
					if ( $ads ) {
						$ads = json_decode( $ads );
						$ads = array_filter( $ads );
					} else {
						return false;
					}
					if ( is_array( $ads ) && count( $ads ) ) {
						foreach ( $ads as $ad ) {
							?>
                            <div class="villatheme-col-4">
								<?php if ( $ad->image ) { ?>
                                    <div class="villatheme-item-image">
                                        <img src="<?php echo esc_url( $ad->image ) ?>">
                                        <div class="villatheme-item-controls">
                                            <div class="villatheme-item-controls-inner">
												<?php if ( @$ad->link ) { ?>
                                                    <a class="villatheme-button villatheme-primary" target="_blank"
                                                       href="<?php echo esc_url( $ad->link ) ?>"><?php echo esc_html__( 'Download', $this->data['slug'] ) ?></a>
												<?php }
												if ( @$ad->demo_url ) { ?>
                                                    <a class="villatheme-button" target="_blank"
                                                       href="<?php echo esc_url( $ad->demo_url ) ?>"><?php echo esc_html__( 'Demo', $this->data['slug'] ) ?></a>
												<?php }
												if ( @$ad->free_url ) { ?>
                                                    <a class="villatheme-button" target="_blank"
                                                       href="<?php echo esc_url( $ad->free_url ) ?>"><?php echo esc_html__( 'Trial', $this->data['slug'] ) ?></a>
												<?php } ?>
                                            </div>
                                        </div>
                                    </div>
								<?php } ?>
								<?php if ( $ad->title ) { ?>
                                    <div class="villatheme-item-title">
                                        <h3>
											<?php if ( @$ad->link ) { ?>
                                            <a class="villatheme-primary-color" target="_blank"
                                               href="<?php echo esc_url( $ad->link ) ?>">
												<?php } ?>
												<?php echo esc_html( $ad->title ) ?>
												<?php if ( @$ad->link ) { ?>
                                            </a>
										<?php } ?>
                                        </h3>
                                    </div>
                                    <div class="villatheme-item-rating">
                                        &#x2606;&#x2606;&#x2606;&#x2606;&#x2606;
                                    </div>
								<?php }
								if ( @$ad->description ) { ?>
                                    <div class="villatheme-item-description"><?php echo strip_tags( $ad->description ) ?></div>
								<?php } ?>

                            </div>
						<?php }
					} ?>
                </div>
            </div>
		<?php }

		/**
		 * Hide notices
		 */
		public function hide_review_notice() {

			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}
			if ( ! isset( $_GET['_villatheme_nonce'] ) ) {
				return;
			}
			if ( wp_verify_nonce( $_GET['_villatheme_nonce'], $this->data['slug'] . '_dismiss_notices' ) ) {
				update_option( $this->data['slug'] . '_dismiss_notices', 1 );
			}
			if ( wp_verify_nonce( $_GET['_villatheme_nonce'], $this->data['slug'] . '_hide_notices' ) ) {
				set_transient( $this->data['slug'] . $this->data['version'] . '_hide_notices', 1, 2592000 );
			}
			if ( wp_verify_nonce( $_GET['_villatheme_nonce'], $this->data['slug'] . '_wp_reviewed' ) ) {
				set_transient( $this->data['slug'] . $this->data['version'] . '_hide_notices', 1, 2592000 );
				update_option( $this->data['slug'] . '_wp_reviewed', 1 );
				ob_start();
				ob_end_clean();
				wp_redirect( $this->data['review'] );
				die;
			}
		}

		/**
		 * Show review wordpress
		 */
		public function review_notice() {
			if ( get_option( $this->data['slug'] . '_dismiss_notices', 0 ) ) {
				return;
			}
			if ( get_transient( $this->data['slug'] . $this->data['version'] . '_hide_notices' ) ) {
				return;
			}
			$name         = str_replace( '-', ' ', $this->data['slug'] );
			$name         = ucwords( $name );
			$check_review = get_option( $this->data['slug'] . '_wp_reviewed', 0 );
			$check_start  = get_option( $this->data['slug'] . '_start_use', 0 );
			if ( ! $check_start ) {
				update_option( $this->data['slug'] . '_start_use', 1 );
				set_transient( $this->data['slug'] . $this->data['version'] . '_hide_notices', 1, 259200 );

				return;
			}
			if ( $check_review && ! $this->data['pro_url'] ) {
				return;
			}
			?>

            <div class="villatheme-dashboard updated" style="border-left: 4px solid #ffba00">
                <div class="villatheme-content">
                    <form action="" method="get">
						<?php if ( ! $check_review ) { ?>
                            <p><?php echo esc_html__( 'Hi there! You\'ve been using ', $this->data['slug'] ) . '<strong>' . $name . '</strong>' . esc_html__( ' on your site for a few days - I hope it\'s been helpful. If you\'re enjoying my plugin, would you mind rating it 5-stars to help spread the word?', $this->data['slug'] ) ?></p>
						<?php } else { ?>
                            <p><?php echo esc_html__( 'Hi there! You\'ve been using ', $this->data['slug'] ) . '<strong>' . $name . '</strong>' . esc_html__( ' on your site for a few days - I hope it\'s been helpful. Would you want get more features?', $this->data['slug'] ) ?></p>
						<?php } ?>
                        <p>
                            <a href="<?php echo esc_url( wp_nonce_url( @add_query_arg(), $this->data['slug'] . '_hide_notices', '_villatheme_nonce' ) ); ?>"
                               class="button"><?php esc_html_e( 'Thanks, later', $this->data['slug'] ) ?></a>
							<?php if ( ! $check_review ) { ?>
                                <button class="button button-primary"><?php esc_html_e( 'Rate now', $this->data['slug'] ) ?></button>
								<?php wp_nonce_field( $this->data['slug'] . '_wp_reviewed', '_villatheme_nonce' ) ?>
							<?php } ?>
							<?php if ( $this->data['pro_url'] ) { ?>
                                <a target="_blank" href="<?php echo esc_url( $this->data['pro_url'] ); ?>"
                                   class="button button-primary"><?php esc_html_e( 'Try Premium Version', $this->data['slug'] ) ?></a>
							<?php } ?>
                            <a target="_self"
                               href="<?php echo esc_url( wp_nonce_url( @add_query_arg(), $this->data['slug'] . '_dismiss_notices', '_villatheme_nonce' ) ); ?>"
                               class="button notice-dismiss vi-button-dismiss"><?php esc_html_e( 'Dismiss', $this->data['slug'] ) ?></a>
                        </p>
                    </form>
                </div>

            </div>
		<?php }

		/**
		 * Dashboard widget
		 */
		public function dashboard() {
			$hide = get_transient( 'villatheme_hide_notices' );
			if ( $hide ) {
				return;
			}
			wp_add_dashboard_widget( 'villatheme_dashboard_status', __( 'VillaTheme Offer', $this->data['slug'] ), array(
				$this,
				'widget'
			) );
		}

		public function widget() {

			$default = array(
				'heading'     => '',
				'description' => '',
				'link'        => ''
			);
			$data    = get_transient( 'villatheme_notices' );

			if ( ! $data ) {
				@$data = json_decode( file_get_contents( 'https://villatheme.com/notices.php' ), true );
				set_transient( 'villatheme_notices', $data, 86400 );
			}
			if ( ! is_array( $data ) ) {
				return;
			}
			$data = wp_parse_args( $data, $default );
			if ( ! $data['heading'] && ! $data['description'] ) {
				return;
			} ?>
            <div class="villatheme-dashboard">
                <div class="villatheme-content">
					<?php if ( $data['heading'] ) { ?>
                        <div class="villatheme-left">
							<?php echo $data['heading'] ?>
                        </div>
					<?php } ?>
                    <div class="villatheme-right">
						<?php if ( $data['description'] ) { ?>
                            <div class="villatheme-description">
								<?php echo $data['description']; ?>
                            </div>
						<?php } ?>
                        <div class="villatheme-notification-controls">
							<?php if ( $data['link'] ) { ?>
                                <a target="_blank" href="<?php echo esc_url( $data['link'] ) ?>"
                                   class="villatheme-button villatheme-primary"><?php esc_html_e( 'View', $this->data['slug'] ) ?></a>
							<?php } ?>
                            <a href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'villatheme-hide-notice', '1' ), 'hide_notices', '_villatheme_nonce' ) ); ?>"
                               class="villatheme-button"><?php esc_html_e( 'Skip', $this->data['slug'] ) ?></a>
                        </div>
                    </div>
                </div>

            </div>

		<?php }

		/**
		 * Hide notices
		 */
		public function hide_notices() {
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}
			if ( ! isset( $_GET['villatheme-hide-notice'] ) && ! isset( $_GET['_villatheme_nonce'] ) ) {
				return;
			}
			if ( wp_verify_nonce( $_GET['_villatheme_nonce'], 'hide_notices' ) ) {
				if ( $_GET['villatheme-hide-notice'] == 1 ) {
					set_transient( 'villatheme_hide_notices', 1, 86400 );
				} else {
					set_transient( 'villatheme_hide_notices', 1, 86400 * 30 );

				}
			}
		}

		/**
		 * Show Notices
		 */
		public function form_ads() {
			$hide = get_transient( 'villatheme_hide_notices' );
			if ( $hide ) {
				return;
			}
			$default = array(
				'heading'     => '',
				'description' => '',
				'link'        => ''
			);
			$data    = get_transient( 'villatheme_notices' );

			if ( ! $data ) {
				@$data = json_decode( file_get_contents( 'https://villatheme.com/notices.php' ), true );
				set_transient( 'villatheme_notices', $data, 86400 );
			}
			if ( ! is_array( $data ) ) {
				return;
			}
			$data = wp_parse_args( $data, $default );
			if ( ! $data['heading'] && ! $data['description'] ) {
				return;
			}
			ob_start(); ?>
            <div class="villatheme-notification-wrapper notice">
                <div class="villatheme-content">
					<?php if ( $data['heading'] ) { ?>
                        <div class="villatheme-left">
							<?php echo $data['heading'] ?>
                        </div>
					<?php } ?>
                    <div class="villatheme-right">
						<?php if ( $data['description'] ) { ?>
                            <div class="villatheme-description">
								<?php echo $data['description']; ?>
                            </div>
						<?php } ?>
                        <div class="villatheme-notification-controls">
                            <a href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'villatheme-hide-notice', '2' ), 'hide_notices', '_villatheme_nonce' ) ); ?>"
                               class="villatheme-button"><?php esc_html_e( 'Dismiss', $this->data['slug'] ) ?></a>
							<?php if ( $data['link'] ) { ?>
                                <a target="_blank" href="<?php echo esc_url( $data['link'] ) ?>"
                                   class="villatheme-button villatheme-primary"><?php esc_html_e( 'View', $this->data['slug'] ) ?></a>
							<?php } ?>
                            <a href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'villatheme-hide-notice', '1' ), 'hide_notices', '_villatheme_nonce' ) ); ?>"
                               class="villatheme-button"><?php esc_html_e( 'No, Thanks latter', $this->data['slug'] ) ?></a>
                        </div>
                    </div>
                </div>

            </div>
			<?php $html = ob_get_clean();
			$html       = apply_filters( 'form_ads_data', $html );
			echo $html;
		}

		/**
		 * Init script
		 */
		public function scripts() {
			wp_enqueue_style( 'villatheme-support', $this->data['css_url'] . 'villatheme-support.css' );
		}

		/**
		 *
		 */
		public function villatheme_support() { ?>

            <div id="villatheme-support" class="vi-ui form segment">

                <div class="fields">
                    <div class="four wide field ">
                        <h3><?php echo esc_html__( 'HELP CENTER', $this->data['slug'] ) ?></h3>
                        <div class="villatheme-support-area">
                            <a target="_blank" href="<?php echo esc_url( $this->data['support'] ) ?>">
                                <img src="<?php echo $this->data['images_url'] . 'support.jpg' ?>">
                            </a>
                        </div>
                        <div class="villatheme-docs-area">
                            <a target="_blank" href="<?php echo esc_url( $this->data['docs'] ) ?>">
                                <img src="<?php echo $this->data['images_url'] . 'docs.jpg' ?>">
                            </a>
                        </div>
                        <div class="villatheme-review-area">
                            <a target="_blank" href="<?php echo esc_url( $this->data['review'] ) ?>">
                                <img src="<?php echo $this->data['images_url'] . 'reviews.jpg' ?>">
                            </a>
                        </div>
                    </div>
					<?php $items = $this->get_data( $this->data['slug'] );
					if ( is_array( $items ) && count( $items ) ) {
						shuffle( $items );
						$items = array_slice( $items, 0, 2 );
						foreach ( $items as $k => $item ) { ?>
                            <div class="six wide field">
								<?php if ( $k == 0 ) { ?>
                                    <h3><?php echo esc_html__( 'MAYBE YOU LIKE', $this->data['slug'] ) ?></h3>
								<?php } else { ?>
                                    <h3>&nbsp;</h3>
								<?php } ?>
                                <div class="villatheme-item">
                                    <a target="_blank" href="<?php echo esc_url( $item->link ) ?>">
                                        <img src="<?php echo esc_url( $item->image ) ?>"/>
                                    </a>
                                </div>
                            </div>
						<?php }
						?>

					<?php } ?>
                </div>

            </div>
		<?php }

		/**
		 * Get data from server
		 * @return array
		 */
		protected function get_data( $slug = false ) {
			$feeds   = get_transient( 'villatheme_ads' );
			$results = array();
			if ( ! $feeds ) {
				@$ads = file_get_contents( 'https://villatheme.com/wp-json/info/v1' );
				set_transient( 'villatheme_ads', $ads, 86400 );
			} else {
				$ads = $feeds;
			}
			if ( $ads ) {
				$ads = json_decode( $ads );
				$ads = array_filter( $ads );
			} else {
				return false;
			}
			if ( is_array( $ads ) && count( $ads ) ) {
				$theme_select = null;
				foreach ( $ads as $ad ) {
					if ( $slug ) {
						if ( $ad->slug == $slug ) {
							continue;
						}
					}
					$item        = new stdClass();
					$item->title = $ad->title;
					$item->link  = $ad->link;
					$item->thumb = $ad->thumb;
					$item->image = $ad->image;
					$item->desc  = $ad->description;
					$results[]   = $item;
				}
			} else {
				return false;
			}
			if ( is_array( $results ) && count( $results ) ) {
				return $results;
			} else {
				return false;
			}
		}
	}
}
new VillaTheme_Support(
	array(
		'support'   => 'https://wordpress.org/support/plugin/woo-notification',
		'docs'      => 'http://docs.villatheme.com/?item=woocommerce-notification',
		'review'    => 'https://wordpress.org/support/plugin/woo-notification/reviews/?rate=5#rate-response',
		'pro_url'   => 'http://bit.ly/woocommerce-notification',
		'css'       => VI_WNOTIFICATION_F_CSS,
		'image'     => VI_WNOTIFICATION_F_IMAGES,
		'slug'      => 'woo-notification',
		'menu_slug' => 'woo-notification',
		'version'   => VI_WNOTIFICATION_F_VERSION
	)
);
