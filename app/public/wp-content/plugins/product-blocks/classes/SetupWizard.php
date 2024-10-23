<?php
/**
 * Initial Setup.
 *
 * @package WOPB\Notice
 * @since v.2.4.4
 */
namespace WOPB;

defined( 'ABSPATH' ) || exit;

class SetupWizard {

	public function __construct() {
		add_action( 'wowstore_menu', array( $this, 'menu_page_callback' ) );
		add_action( 'rest_api_init', array( $this, 'wopb_register_route' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'script_wizard_callback' ) ); // Option Panel
	}
	
	/**
     * Setup Wizard Function
     *
     * @since v.4.0.0
     * @return NULL
     */
	public function script_wizard_callback() {
		if ( wopb_function()->get_screen() == 'wopb-initial-setup-wizard' ) {
			wp_enqueue_script( 'wopb-setup-wizard', WOPB_URL . 'assets/js/setup.wizard.js', array( 'wp-i18n', 'wp-api-fetch', 'wp-api-request', 'wp-components', 'wp-blocks' ), WOPB_VER, true );
			wp_localize_script( 'wopb-setup-wizard', 'setup_wizard', array(
				'url' => WOPB_URL,
				'version' => WOPB_VER,
				'security' => wp_create_nonce('wopb-nonce'),
				'redirect' => admin_url('admin.php?page=wopb-settings#home')
			) );
			wp_set_script_translations( 'wopb-setup-wizard', 'product-blocks', WOPB_URL . 'languages/' );
		}
	}
	
	/**
     * Plugins Menu Page Added
     *
     * @since v.1.0.0
     * @return NULL
     */
    public function menu_page_callback() {
		add_submenu_page(
			'wopb-settings',
			esc_html__( 'Setup Wizard', 'product-blocks' ),
			esc_html__( 'Setup Wizard', 'product-blocks' ),
			'manage_options',
			'wopb-initial-setup-wizard',
			array( $this , 'initial_setup' )
		);
	}

	 /**
     * Initial Plugin Setting
     *
     * * @since 3.0.0
     * @return STRING
     */
    public function initial_setup() { ?>
        <div class="wopb-initial-setting-wrap" id="wopb-initial-setting"></div>
    <?php }

	/**
	 * REST API Action
	 *  * @since 3.0.0
	 * @return NULL
	 */
	public function wopb_register_route() {
        register_rest_route(
			'wopb/v2', 
			'/wizard_action/',
			array(
				array(
					'methods'  => 'POST', 
					'callback' => array( $this, 'wizard_site_action_callback' ),
					'permission_callback' => function () {
						return current_user_can( 'manage_options' );
					},
					'args' => array()
				)
			)
        );
	}

	/**
	 * Save General Settings Data.
	 *
	 * @return void
	 * @since 3.0.0
	 */
	public function wizard_site_action_callback( $server ) {
        $params = $server->get_params();
		if ( ! ( isset( $params['wpnonce'] ) && wp_verify_nonce( sanitize_key( wp_unslash( $params['wpnonce'] ) ), 'wopb-nonce' ) ) ) {
			die();
		}

        if ( ! function_exists( 'is_plugin_active' ) ) {
            include_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

		$action = sanitize_text_field( $params['action'] );
		
		if ( $action == 'install' ) {
			if ( isset( $params['siteType'] ) ) {
				$site_type = sanitize_text_field( $params['siteType'] );
				update_option( '__wopb_site_type', $site_type );
			}
	
			$woocommerce_installed = file_exists( WP_PLUGIN_DIR . '/woocommerce/woocommerce.php' );
			$wholesalex_installed  = file_exists( WP_PLUGIN_DIR . '/wholesalex/wholesalex.php' );
			if ( isset( $params['install_woocommerce'] ) && 'yes' === $params['install_woocommerce'] ) {
				if ( $woocommerce_installed ) {
					$is_wc_active = is_plugin_active( 'woocommerce/woocommerce.php' );
					if ( ! $is_wc_active ) {
						$activate_status = activate_plugin( 'woocommerce/woocommerce.php', '', false, true );
						if ( is_wp_error( $activate_status ) ) {
							wp_send_json_error( array( 'message' => __( 'WooCommerce Activation Failed!', 'wholesalex' ) ) );
						}
					}
				}
			}
			if ( isset( $params['install_wholesalex'] ) && 'yes' === $params['install_wholesalex'] ) {
				if ( ! $wholesalex_installed ) {
					include_once WOPB_PATH . 'classes/Notice.php';
					$obj = new \WOPB\Notice();
					$status = $obj->plugin_install( 'wholesalex' );
					if ( $status && ! is_wp_error( $status ) ) {
						$activate_status = activate_plugin( 'wholesalex/wholesalex.php', '', false, true );
						if ( is_wp_error( $activate_status ) ) {
							wp_send_json_error( array( 'message' => __( 'WholesaleX Activation Failed!', 'wholesalex' ) ) );
						}
					} else {
						wp_send_json_error( array( 'message' => __( 'WholesaleX Installation Failed!', 'wholesalex' ) ) );
					}
				} else {
					$is_wc_active = is_plugin_active( 'wholesalex/wholesalex.php' );
					if ( ! $is_wc_active ) {
						$activate_status = activate_plugin( 'wholesalex/wholesalex.php', '', false, true );
						if ( is_wp_error( $activate_status ) ) {
							wp_send_json_error( array( 'message' => __( 'WholesaleX Activation Failed!', 'wholesalex' ) ) );
						}
					}
				}
			}
			return rest_ensure_response( ['success' => true ] );
		} else if ( $action == 'send' ) {
			update_option('wopb_setup_wizard_data', 'yes');
			$site = isset( $post['site'] ) ? sanitize_text_field( $post['site'] ) : get_option('__wopb_site_type', '');
			require_once WOPB_PATH . 'classes/Deactive.php';
			$obj = new \WOPB\Deactive();
			$obj->send_plugin_data( 'productx_wizard' , $site);

			return rest_ensure_response([
				'success' => true,
			]);
		}
	}
}
