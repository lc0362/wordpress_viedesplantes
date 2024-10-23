<?php
/**
 * Compare Addons Core.
 *
 * @package WOPB\Compare
 * @since v.1.1.0
 */

namespace WOPB;

defined('ABSPATH') || exit;

/**
 * Compare class.
 */
class Compare {

    /**
	 * Setup class.
	 *
	 * @since v.1.1.0
	 */
    public $demo_column;
    public $my_account_compare_end_point;
    private $compare_id;
    public function __construct() {
        $this->demo_column = 3;
        $this->my_account_compare_end_point = 'my-compare';
        $this->compare_id = $this->get_compare_id();
        $compare_position_shop = wopb_function()->get_setting('compare_position_shop_page');

        add_action('wp_enqueue_scripts', array($this, 'add_compare_scripts'));
        add_action('wp_ajax_wopb_compare', array($this, 'wopb_compare_callback'));
        add_action('wp_ajax_nopriv_wopb_compare', array($this, 'wopb_compare_callback'));
        add_action('wc_ajax_wopb_product_list', array($this, 'wopb_product_list_callback'));
        add_action('wp_ajax_nopriv_wopb_product_list', array($this, 'wopb_product_list_callback'));

        if ( wopb_function()->get_setting('compare_nav_menu_enable') == 'yes' ) {
            if ( wopb_function()->get_setting('compare_nav_menu_shortcode') == 'yes' ) {
                add_shortcode('wopb_compare_nav', array($this, 'compare_nav_menu'));
            } else {
                add_filter('wp_nav_menu_items', array($this, 'nav_menu_item'), 10, 2);
            }
        }
        add_shortcode('wopb_compare_button', function () {
            return $this->get_compare( get_the_ID(), 'default' );
        });
        add_shortcode('wopb_compare', array($this, 'compare_wrapper'));

        if ( wopb_function()->get_setting('compare_single_enable') == 'yes' ) {
            if( wopb_function()->get_setting('wopb_quickview') == 'true' ) {
                add_filter('wopb_quick_view_bottom_cart', function ($content, $product_id) {
                    return $content . $this->get_compare($product_id, 'default');
                }, 11, 2);
            }
            add_action('woocommerce_before_single_product', array( $this,'before_single_product' ));
        }
        if ( wopb_function()->get_setting('compare_shop_enable') == 'yes') {
            $position_filters = $this->button_position_shop_filters();
            if( isset( $position_filters[$compare_position_shop] ) ) {
                add_filter($position_filters[$compare_position_shop], array($this, 'compare_button_in_cart'), 30, 1);
            }
        }
        if ( wopb_function()->get_setting('compare_my_account_enable') == 'yes' ) {
            $this->my_account_compare_endpoint();
            add_filter('woocommerce_account_menu_items', array($this, 'compare_my_account_menu_items'), 10, );
            add_filter( 'woocommerce_get_query_vars', array( $this, 'woocommerce_query_vars' ) );
            add_action( 'woocommerce_account_' . $this->my_account_compare_end_point . '_endpoint', function () {
                echo $this->compare_wrapper(); //phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
            });
        }

        if( wopb_function()->get_setting('compare_action_added') != 'redirect' || wopb_function()->get_setting('compare_nav_click_action') == 'popup' ) {
            add_filter('wopb_active_modal', function (array $loaders) {
                if( ! in_array( $modal_loader = wopb_function()->get_setting('compare_modal_loading'), $loaders ) ) {
                    $loaders[] = $modal_loader;
                }
                return $loaders;
            });
        }

        add_action( 'wopb_save_settings', array( $this, 'generate_css' ), 10, 1 ); // CSS Generator
        add_filter( 'wopb_grid_compare', array( $this, 'compare_data_callback' ), 10, 3 );
        add_filter( 'wopb_menu_compare_data', array( $this, 'menu_compare_data_callback' ), 10, 1 );
    }

    /**
     * Return compare data to block
     *
     * @param $data
     * @return array
     * @since v.4.0.0
     */

    public function menu_compare_data_callback( $data = []) {
        $compare_page = wopb_function()->get_setting( 'compare_page' );
        $action_added = wopb_function()->get_setting( 'compare_action_added' );
        $action = 'add';
        $redirect = $compare_page && $action_added == 'redirect' ? ( ' data-redirect="' . esc_url( get_permalink( $compare_page ) ) . '"' ) : '';
        $post_id = ! empty( $data['post_id'] ) ? ( 'data-postid="' . esc_attr( $data['post_id'] ) . '"' ) : '';
        $modal_wrapper_class = $action_added == 'sidebar' ? 'wopb-sidebar-wrap wopb-right_sidebar' : '';
        if( ! empty($data['action']) && $data['action'] == 'menu_block' ) {
            $action = 'menu_block';
            $action_added = $action_added == 'message' ? 'popup' : $action_added;
        }else {
            $modal_wrapper_class .= $action_added == 'message' ? ' wopb-modal-toast-wrapper' : '';
        }
        $modal_wrapper_attr = $modal_wrapper_class ? ( 'data-modal_wrapper_class="' . esc_attr( $modal_wrapper_class ) .'"' ) : '';
        return [
            'button_attr' =>'
                data-action="' . $action . '"
                data-added-action="' . esc_attr( $action_added ) . '"' .
                $redirect . $post_id . '
                data-open-animation="wopb-' . esc_attr( wopb_function()->get_setting( 'compare_modal_open_animation' ) ) . '"
                data-close-animation="wopb-' . esc_attr( wopb_function()->get_setting( 'compare_modal_close_animation' ) ) .'"
                data-modal-loader="' . esc_attr( wopb_function()->get_setting( 'compare_modal_loading' ) ) .'"' .
                $modal_wrapper_attr . '
            ',
            'c_items' => $this->compare_id
        ];
    }


    /**
     * Grid Compare Button HTML
     *
     * @param $output
     * @param $post_id
     * @param string $tooltip
     * @return string
     * @since v.4.0.0
     */
    public function compare_data_callback( $output, $post_id, $tooltip = 'left' ) {
        $compare_active = in_array( $post_id, $this->compare_id );
        $compare_text = wopb_function()->get_setting( 'compare_text' );
        $browse       = wopb_function()->get_setting( 'compare_added_text' );
        $button_class = 'wopb-compare-btn wopb_meta_svg_con' . ( $compare_active ? ' wopb-compare-active' : '' );
        $compare_data = apply_filters('wopb_menu_compare_data', ['post_id' => $post_id]);
        
        $output = '<div ';
            $output .= 'class="' . esc_attr( $button_class ) . '"';
            $output .= ! empty( $compare_data['button_attr'] ) ? $compare_data['button_attr'] : '';
        $output .= '>';
            $output .= '<span class="wopb-tooltip-text">';
                $output .= wopb_function()->svg_icon( wopb_function()->get_setting( 'compare_button_icon' ) );
                $output .= '<span class="wopb-tooltip-text-' . esc_attr( $tooltip ) . '">';
                    $output .= '<span>' . esc_html( $compare_text ) . '</span>';
                    $output .= '<span>' . esc_html( $browse ) . '</span>';
                $output .= '</span>';
            $output .= '</span>';
        $output .= '</div>';
        
        return $output;
    }

    /** 
	 * Compare Button HTML
     * 
     * @since v.1.1.0
	 * @return string
	 */
    public function get_compare( $post_id, $source = '' ) {
        $output = '';
        $is_product         = is_product();
        $compare_active     = in_array( $post_id, $this->compare_id );
        $button_class       = 'wopb-compare-btn wopb-compare-addon-btn' . ( $compare_active ? ' wopb-compare-active' : '' );

        $compare_text       = wopb_function()->get_setting( 'compare_text' );
        $browse             = wopb_function()->get_setting( 'compare_added_text' );
        $button_icon_enable = wopb_function()->get_setting( 'compare_button_icon_enable' );
        $icon               = wopb_function()->get_setting( 'compare_button_icon' );
        $icon_position      = wopb_function()->get_setting( 'compare_button_icon_position' );


        if ( $is_product ) {
            $icon_single            = wopb_function()->get_setting( 'compare_icon_single' );
            $compare_text_single    = wopb_function()->get_setting( 'compare_text_single' );
            $added_text_single      = wopb_function()->get_setting( 'compare_added_text_single' );
            $icon_enable_single     = wopb_function()->get_setting( 'compare_icon_enable_single' );
            $icon_position_single   = wopb_function()->get_setting( 'compare_icon_position_single' );
            
            $icon                   = $icon_single ? $icon_single : $icon;
            $compare_text           = $compare_text_single ? $compare_text_single : '';
            $browse                 = $added_text_single ? $added_text_single : '';
            $button_icon_enable     = $icon_enable_single;
            $icon_position          = $icon_position_single ? $icon_position_single : $icon_position;
        }
        $compare_icon = wopb_function()->svg_icon( $icon );

        if ( $source == 'default' ) {
            $button_class .= $is_product ? ' wopb-compare-single-btn' : ' wopb-compare-shop-btn';
        }
        $compare_data = apply_filters('wopb_menu_compare_data', ['post_id' => $post_id]);
        $output .= '<span class="wopb-compare-btn-wrap">';
            $output .= '<span ';
                 $output .= 'class="' . esc_attr( $button_class ) . '"';
                $output .= ! empty( $compare_data['button_attr'] ) ? $compare_data['button_attr'] : '';
            $output .= '>';
                if ( $source == 'default' ) {
                    $output .= $button_icon_enable == 'yes' && $icon_position == 'before_text' ? $compare_icon : ''; //phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
                    if ( $compare_text ) {
                        $output .= '<span class="wopb-compare-btn-text">' . esc_html( $compare_text ) . '</span>';
                    }
                    if( $browse ) {
                        $output .= '<span class="wopb-compare-added-text">' . esc_html( $browse ) . '</span>';
                    }
                    $output .= $button_icon_enable == 'yes' && $icon_position == 'after_text' ? $compare_icon : ''; //phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
                } else {
                    $layout = isset( $params['layout'] ) ? $params['layout'] : '';
                    $position = isset( $params['position'] ) ? $params['position'] : '';
                    $output .= '<span class="wopb-tooltip-text">';
                        $output .= $compare_icon;
                        $output .= '<span class="' . ( in_array( $layout, $position ) ? 'wopb-tooltip-text-left' : 'wopb-tooltip-text-top' ) .'">';
                            $output .= '<span>' . esc_html( $compare_text ) . '</span>';
                            $output .= '<span>' . esc_html( $browse ) . '</span>';
                        $output .= '</span>';
                    $output .= '</span>';
                }
            $output .= '</span>';
        $output .= '</span>';

        return $output;
    }

    /**
     * Get Compare ID.
     *
     * @since v.4.0.0
     */
    public function get_compare_id() {
        $data = array();
        $clear_cookie = false;
        if( isset( $_COOKIE['wopb_compare'] ) ) {
            $data = $_COOKIE['wopb_compare'];
            $data = json_decode( wp_unslash( $data ) );
            if ( is_object( $data ) ) {
                $data = (array) $data;
            }
            $cookie_data = $data;
        }
        if( $user_id = get_current_user_id() ) {
            $data = get_user_meta( $user_id, 'wopb_compare_ids', true );
            $data = is_array( $data ) ? $data : array();
            if( ! empty( $cookie_data ) ) {
                $data = array_merge( $data, $cookie_data );
                update_user_meta( $user_id, 'wopb_compare_ids', $data );
                $clear_cookie = true;
            }
        }
        if ( ! is_array( $data ) || $clear_cookie ) {
            $this->clear_compare_cookie();
        }
        return $data;
    }

    /**
     * Clear Compare Cookie.
     *
     * @since v.3.1.5
     */
    public function clear_compare_cookie() {
        ob_start();
            setcookie('wopb_compare', '', time() - 3600, '/');
        ob_get_clean();

        // Unset the cookie from the $_COOKIE array
        unset($_COOKIE['wopb_compare']);
        // Optionally, destroy the cookie variable
        unset($GLOBALS['wopb_compare']);
        unset($_COOKIE['wopb_compare']);
    }
    

    /**
     * Compare JS Script Add
     *
     * @since v.1.1.0
     * @return null
     */
    public function add_compare_scripts() {
        wp_enqueue_style('wopb-modal-css', WOPB_URL.'assets/css/modal.min.css', array(), WOPB_VER);
        wp_enqueue_style('wopb-animation-css', WOPB_URL.'assets/css/animation.min.css', array(), WOPB_VER);
        wp_enqueue_style('wopb-compare-style', WOPB_URL.'addons/compare/css/compare.min.css', array(), WOPB_VER );
        wp_enqueue_script('wopb-compare', WOPB_URL.'addons/compare/js/compare.js', array('jquery'), WOPB_VER, true);
        $wopb_compare_localize = array(
            'ajax' => admin_url('admin-ajax.php'),
            'security' => wp_create_nonce('wopb-nonce')
        );
        $wopb_compare_localize = array_merge($wopb_compare_localize, wopb_function()->get_endpoint_urls());
        wp_localize_script('wopb-compare', 'wopb_compare', $wopb_compare_localize);
    }

    /**
     * Compare Addons Intitial Setup Action
     *
     * @since v.1.1.0
     * @return null
     */
    public function initial_setup() {
        $settings = wopb_function()->get_setting();
        // Set Default Value
        $initial_data = array(
            'compare_page' => '',
            'compare_my_account_enable' => 'yes',
            'compare_action_added' => 'popup',
            'compare_modal_loading' => 'loader_1',
            'compare_modal_open_animation' => 'zoom_in',
            'compare_modal_close_animation' => 'zoom_out',
            'compare_hide_empty_table' => 'yes',

            'compare_shop_enable' => 'yes',
            'compare_position_shop_page' => 'bottom_cart',
            'compare_text' => __('Add to Compare', 'product-blocks'),
            'compare_added_text' => __('Added', 'product-blocks'),
            'compare_button_icon_enable' => 'yes',
            'compare_button_icon' => 'compare_1',
            'compare_button_icon_position' => 'before_text',
            'compare_align_shop' => '',

            'compare_single_enable' => 'yes',
            'compare_position' => 'bottom_cart',
            'compare_text_single' => __('Add to Compare', 'product-blocks'),
            'compare_added_text_single' => __('Added', 'product-blocks'),
            'compare_icon_enable_single' => 'yes',
            'compare_icon_single' => 'compare_1',
            'compare_icon_position_single' => 'before_text',

            'compare_nav_menu_enable' => 'yes',
            'compare_nav_menu_location' => '',
            'compare_nav_text' => '',
            'compare_nav_icon' => 'compare_1',
            'compare_nav_icon_position' => 'before_text',
            'compare_nav_click_action' => 'popup',
            'compare_nav_menu_shortcode' => 'yes',

            'compare_table_columns' => $this->compare_table_columns('default'),
            'compare_add_product_button' => 'yes',
            'compare_clear' => 'yes',
            'compare_first_column_sticky' => 'yes',
            'compare_first_row_sticky' => 'yes',
            'compare_close_button' => 'yes',

            'compare_layout' => 1,
            'compare_preset' => '1',

            'compare_btn_typo_shop' => array(
                'size' => 14,
                'bold' => false,
                'italic' => false,
                'underline' => false,
                'color' => 'rgba(7, 7, 7, 1)',
                'hover_color' => 'rgba(255, 23, 107, 1)',
            ),
            'compare_btn_bg_shop' => array(
                'bg' => '',
                'hover_bg' => '',
            ),
            'compare_btn_padding_shop' => array(
                'top' => 0,
                'bottom' => 0,
                'left' => 0,
                'right' => 0,
            ),
            'compare_btn_border_shop' => array(
                'border' => 0,
                'color' => '',
            ),
            'compare_btn_radius_shop' => 0,
            'compare_icon_size_shop' => 16,

            'compare_btn_typo_single' => array(
                'size' => 14,
                'bold' => false,
                'italic' => false,
                'underline' => false,
                'color' => 'rgba(7, 7, 7, 1)',
                'hover_color' => 'rgba(255, 23, 107, 1)',
            ),
            'compare_btn_bg_single' => array(
                'bg' => '',
                'hover_bg' => '',
            ),
            'compare_btn_padding_single' => array(
                'top' => 0,
                'bottom' => 0,
                'left' => 0,
                'right' => 0,
            ),
            'compare_btn_border_single' => array(
                'border' => 0,
                'color' => '',
            ),
            'compare_btn_radius_single' => 0,
            'compare_icon_size_single' => 16,

            'compare_btn_typo_nav' => array(
                'size' => 14,
                'bold' => false,
                'italic' => false,
                'underline' => false,
                'color' => '',
                'hover_color' => '',
            ),
            'compare_btn_bg_nav' => array(
                'bg' => '',
                'hover_bg' => '',
            ),
            'compare_btn_padding_nav' => array(
                'top' => 0,
                'bottom' => 0,
                'left' => 0,
                'right' => 0,
            ),
            'compare_btn_border_nav' => array(
                'border' => 0,
                'color' => '',
            ),
            'compare_btn_radius_nav' => 0,
            'compare_icon_size_nav' => 18,

            'compare_tbl_cart_btn_typo' => array(
                'size' => 16,
                'bold' => false,
                'italic' => false,
                'underline' => false,
                'color' => '#ffffff',
                'hover_color' => '',
            ),
            'compare_tbl_cart_btn_bg' => array(
                'bg' => '#ff176b',
                'hover_bg' => '',
            ),
            'compare_tbl_cart_btn_padding' => array(
                'top' => 10,
                'bottom' => 10,
                'left' => 20,
                'right' => 20,
            ),
            'compare_tbl_cart_btn_border' => array(
                'border' => 0,
                'color' => '',
            ),
            'compare_tbl_cart_btn_radius' => 4,

            'compare_heading_typo' => array(
                'size' => 16,
                'bold' => false,
                'italic' => false,
                'underline' => false,
                'color' => '#070C1A',
                'hover_color' => '',
            ),
            'compare_body_text_typo' => array(
                'size' => 14,
                'bold' => false,
                'italic' => false,
                'underline' => false,
                'color' => '#5A5A5A',
                'hover_color' => '',
            ),
            'compare_column_padding' => array(
                'top' => 12,
                'bottom' => 12,
                'left' => 12,
                'right' => 12,
            ),
            'compare_column_space' => 0,
            'compare_column_border' => array(
                'border' => 1,
                'color' => '#E5E5E5',
            ),
        );
        foreach ($initial_data as $key => $val) {
            if ( ! isset( $settings[$key] ) ) {
                wopb_function()->set_setting($key, $val);
            }
        }

        if ( ! isset( $settings['compare_page'] ) ) {
            // Insert Compare Page
            $compare_arr = array(
                'post_title' => 'Compare',
                'post_type' => 'page',
                'post_content' => '<!-- wp:shortcode -->[wopb_compare]<!-- /wp:shortcode -->',
                'post_status' => 'publish',
                'comment_status' => 'closed',
                'ping_status' => 'closed',
                'post_author' => get_current_user_id(),
                'menu_order' => 0,
            );
            $compare_id = wp_insert_post( $compare_arr, false );
            if ( $compare_id ) {
                wopb_function()->set_setting( 'compare_page', $compare_id );
            }
        }
        $this->generate_css('wopb_compare');
    }

    /**
     * Compare Add Action Callback.
     *
     * @since v.1.1.0-
     * @return void|null
     */
    public function wopb_compare_callback() {
        if ( ! wp_verify_nonce( sanitize_key( $_REQUEST['wpnonce'] ), 'wopb-nonce' ) ) {
            return ;
        }

        $postId         = sanitize_text_field($_POST['postid']);
        $action_type    = sanitize_text_field($_POST['type']);
        $params = array(
            'source'        => 'ajax',
            'postid'        => $postId,
            'added_action'  => sanitize_text_field( $_POST['added_action'] ),
            'action_type'   => $action_type,
        );

        if( $action_type ) {
            $message = '';
            $data_id = $this->compare_id;
            if ( $postId && $action_type == 'add' ) {
                if( ! in_array( $postId, $data_id ) ){
                    $data_id[] = $postId;
                    $message = esc_html__('Compare Added.', 'product-blocks');
                }
            }elseif ( $action_type == 'clear' ) {
                $this->clear_compare_cookie();
                $data_id = [];
                $message = esc_html__( 'Compare Item Clear.', 'product-blocks' );
            }elseif ($postId && $action_type != 'nav_popup' ) {
                if ( false !== $key = array_search( $postId, $data_id ) ) {
                    unset( $data_id[$key] );
                    $message = esc_html__( 'Compare Removed.', 'product-blocks' );
                }
            }
            if( $user_id = get_current_user_id() ) {
                update_user_meta( $user_id, 'wopb_compare_ids', $data_id );
            }else {
                setcookie('wopb_compare', wp_json_encode($data_id), time() + 604800, '/'); // 7 Days
            }
            $params['data_id'] = $data_id;
            wp_send_json_success(
                array(
                    'html'          => $this->compare_wrapper($params),
                    'compare_count' => count($data_id),
                    'demo_column'   => $this->demo_column,
                    'message'       => $message
                )
            );
        }else {
            wp_send_json_error( __( 'Compare Not Added', 'product-blocks' ) );
        }
        die();
    }

    /**
     * Compare Wrapper
     *
     * @since v.3.1.7
     * @param $params
     * @return null
     */
    public function compare_wrapper( $params = [] ) {
        $output = '';
        $content = '';
        $wrapper_class = '';
        $added_action = isset( $params['added_action'] ) ? $params['added_action'] : '';
        
        if ( $added_action == 'sidebar' ) {
            $content = $this->modal_header() . $this->compare_sidebar_content( $params ) . $this->modal_footer( $params );
        } elseif ( $added_action == 'message' ) {
            $content = $this->compare_toast_message( $params );
            $wrapper_class .= 'wopb-modal-toaster wopb-right-top';
        } else {
            $content = $this->modal_header() . $this->compare_modal_content( $params ) . $this->modal_footer( $params ) . $this->product_list_modal( $params );
            $wrapper_class .= 'wopb-compare-layout-' . wopb_function()->get_setting('compare_layout');
        }
  
        $output .= '<div class="wopb-compare-wrapper ' . esc_attr( $wrapper_class ) . '">';
            $output .= $content;
        $output .= '</div>';

        return $output;
    }

    /**
     * Modal Header
     *
     * @return string
     *@since v.3.1.7
     */
    public function modal_header() {
        $html = '<div class="wopb-modal-header">';
            $html .= '<span class="wopb-header-title">';
                $html .= __('Compare Products', 'product-blocks');
            $html .= '</span>';
            if ( wopb_function()->get_setting('compare_close_button') == 'yes' ) {
                $html .= '<a class="wopb-modal-close">';
                    $html .= wopb_function()->svg_icon( 'close' );
                $html .= '</a>';
            }
        $html .= '</div>';
        return $html;
    }

    /**
     * Modal Footer
     *
     * @param $params
     * @return string
     * @since v.3.1.7
     */
    public function modal_footer($params) {
        $post_id = isset($params['postid']) ? $params['postid'] : esc_attr(get_the_ID());
        $compare_data = isset($params['source']) && $params['source'] == 'ajax' ? $params['data_id'] : $this->compare_id;
        $added_action = isset( $params['added_action'] ) ? $params['added_action'] : wopb_function()->get_setting('compare_action_added');
        $added_action = $added_action == 'message' ? 'clear_all' : $added_action;
        $html = '<div class="wopb-modal-footer">';
        if ( wopb_function()->get_setting('compare_clear') == 'yes' && count($compare_data) > 0 ) {
            $html .= '<a class="wopb-compare-clear-btn" data-action="clear" data-added-action="' . esc_attr($added_action) . '" data-postid="' . $post_id . '">';
                $html .= __('Clear All', 'product-blocks');
            $html .= '</a>';
        }
        if( wp_doing_ajax() && $added_action == 'sidebar' ) {
            $html .= '<a class="wopb-lets-compare-btn"';
                $html .= 'data-postid="' . $post_id .'"';
                $html .= 'data-action="nav_popup" ';
                $html .= 'data-added-action="nav_popup"';
                $html .= 'data-open-animation="wopb-' . esc_attr( wopb_function()->get_setting('compare_modal_open_animation') ) .'"';
                $html .= 'data-close-animation="wopb-' . esc_attr( wopb_function()->get_setting('compare_modal_close_animation') ) . '"';
                $html .= 'data-modal-loader="' . esc_attr( wopb_function()->get_setting('compare_modal_loading') ) . '"';
            $html .= '>';
                $html .= __("Let's Compare", 'product-blocks');
            $html .= '</a>';
        }
        $html .= '</div>';
        return $html;
    }

    /**
     * Product List Callback.
     *
     * @since v.3.1.1
     * @return array
     */
    public function wopb_product_list_callback() {
        if ( ! wp_verify_nonce( sanitize_key( $_REQUEST['wpnonce'] ), 'wopb-nonce' ) ) {
            return ;
        }
        $params = ['s' => isset($_POST['search']) ? sanitize_text_field($_POST['search']) : ''];
        return wp_send_json_success( array( 'html' => $this->product_list( $params ) ) );
        die();
    }

    /**
     * Get Product List.
     *
     * @since v.3.1.1
     * @param array
     * @return html
     */
    public function product_list( $params = [] ) {
        $output = '';
        $compare_data = isset( $params['source'] ) && $params['source'] == 'ajax' ? $params['data_id'] : $this->compare_id;
        $query_args = array(
            'posts_per_page'    => 10,
            'post_type'         => 'product',
            'post_status'       => 'publish',
            'no_found_rows'     => true,
            's'                 => ( isset( $params['s'] ) && $params['s'] != '' ) ? $params['s'] : ''
        );
        $products = wc_get_products( $query_args );

        if ( $products && count( $products ) > 0 ) {
            foreach ( $products as $product ) {
                if ( $product && ! in_array( $product->get_id(), $compare_data ) ) {
                    $output .= '<div class="wopb-compare-item wopb-compare-item-' . esc_attr( $product->get_id() ) .'">';
                        $output .= '<div class="wopb-compare-product-details">';
                            $output .= '<a href="' . esc_url( $product->get_permalink() ) . '" class="wopb-product-image">';
                                $output .= $product->get_image( 'shop_thumbnail' );
                            $output .= '</a>';
                            $output .= '<div class="wopb-compare-product-content">';
                                $output .= '<div class="wopb-compare-product-name">' . $product->get_title() . '</div>';
                                $output .= '<div class="wopb-compare-product-review">';
                                    $output .= '<div class="wopb-star-rating" aria-label="product review">';
                                        $output .= '<span style="width: ' . esc_attr( $product->get_average_rating() ? ( $product->get_average_rating() / 5 ) * 100 : 0 ) . '%"></span>';
                                    $output .= '</div>';
                                    $output .= '<span class="wopb-review-count">';
                                       $output .= esc_html( $product->get_rating_count() ) . ' ' . __( 'customer review', 'product-blocks' );
                                    $output .= '</span>';
                                $output .= '</div>';
                                $output .= '<div class="wopb-compare-product-price">';
                                    $output .= wp_kses_post( $product->get_price_html() );
                                $output .= '</div>';
                            $output .= '</div>';
                        $output .= '</div>';
                        $output .= '<a class="wopb-add-to-compare-btn" data-action="add" data-added-action="product_list" data-postid="' . esc_attr( $product->get_id() ) . '">';
                        $output .= wopb_function()->svg_icon( 'plus_3' );
                        $output .= '</a>';
                    $output .= '</div>';
                }
            }
            return $output;
        }
    }

    /**
     * Compare Nav Menu To Specific Location.
     *
     * @since v.3.1.1
     * @return html
     */
    public function nav_menu_item( $items, $args ) {
        $nav_location = wopb_function()->get_setting( 'compare_nav_menu_location' );
        if ( $nav_location )  {
            $nav_menu = '<li class="menu-item">' . $this->compare_nav_menu() . '</li>';
            if ( $args->theme_location ) {
                if ( $args->theme_location == $nav_location ) {
                    $items .= $nav_menu;
                }
            } else {
                $items .= $nav_menu;
            }
        }
        return $items;
    }

    /**
     * Compare Nav Menu.
     *
     * @since v.3.1.1
     * @return html
     */
    public function compare_nav_menu() {
        $output = '';
        $icon_position      = wopb_function()->get_setting( 'compare_nav_icon_position' );
        $compare_text       = wopb_function()->get_setting( 'compare_nav_text' );
        $nav_class          = $icon_position == 'top_text' ? 'wopb-flex-column-dir' : '';
        $nav_click_action   = wopb_function()->get_setting( 'compare_nav_click_action' );

        $output .= '<a
            class="wopb-compare-nav-item ' . $nav_class .' "
            data-action="' . ( $nav_click_action == 'popup' ? 'nav_popup' : 'redirect' ) .'"
            data-added-action="' . ( $nav_click_action == 'popup' ? 'nav_popup' : '' ) . '"
            data-postid="' . esc_attr( get_the_ID() ) . '"
            data-open-animation="wopb-' . esc_attr( wopb_function()->get_setting( 'compare_modal_open_animation' ) ) . '"
            data-close-animation="wopb-' . esc_attr( wopb_function()->get_setting( 'compare_modal_close_animation' ) ) . '"
            data-modal-loader="' . esc_attr( wopb_function()->get_setting('compare_modal_loading') ) . '"
            ' . ( $nav_click_action == 'redirect' ? 'data-redirect="' . esc_url( get_permalink( wopb_function()->get_setting( 'compare_page' ) ) ) . '"' : '' ) .'>';
            
            if ( $icon_position == 'after_text' ) { 
                $output .= $compare_text;
            }
            $output .= '<span class="wopb-compare-icon">';
                $output .= wopb_function()->svg_icon( wopb_function()->get_setting( 'compare_nav_icon' ) );
                $output .= '<span class="wopb-compare-count">' . esc_html( count( $this->compare_id ) ) . '</span>';
            $output .= '</span>';
            if ( $icon_position == 'before_text' || $icon_position == 'top_text' ) { 
                $output .= $compare_text;
            }
        $output .= '</a>';

        return $output;
    }


    /**
     * Compare Table Content
     *
     * @param array $params
     * @return html
     * @since v.3.1.1
     */
    public function compare_modal_content($params = []) {
        $compare_data = isset($params['source']) && $params['source'] == 'ajax' ? $params['data_id'] : $this->compare_id;
        ob_start();
?>
        <div
            class="wopb-modal-body"
            data-outside_click="yes"
        >
            <?php
                if ( count($compare_data) == 0 && wopb_function()->get_setting('compare_hide_empty_table') == 'yes' ) {
                    echo $this->empty_product_message($params);
                }
                if ( !(wopb_function()->get_setting('compare_hide_empty_table') == 'yes' && count($compare_data) == 0) ) {
                    $demo_column = count($compare_data) < $this->demo_column ? $this->demo_column - count($compare_data) : 0;
                    $row_class = wopb_function()->get_setting('compare_first_row_sticky') ? 'wopb-sticky-row' : '';
                    $column_class = wopb_function()->get_setting('compare_first_column_sticky') && ! wp_is_mobile() ? 'wopb-sticky-column' : '';
                    $compare_add_product_button = wopb_function()->get_setting('compare_add_product_button');
            ?>
                    <table class="wopb-compare-table">
                        <thead>
                            <tr class="<?php echo $row_class ?>">
                                <th class="<?php echo $column_class ?>"><?php echo __('Action' ,'product-blocks'); ?></th>
                                <?php
                                    foreach ($compare_data as $key => $val) {
                                        $product = wc_get_product($val);
                                ?>
                                        <td class="wopb-compare-item wopb-compare-item-<?php echo esc_attr($product->get_id()) ?>">
                                            <a class="wopb-compare-remove" data-action="remove" data-added-action="popup" data-postid="<?php echo esc_attr($product->get_id()) ?>">
                                                <?php echo wopb_function()->svg_icon('delete') ?>
                                                <span><?php echo __('Delete' ,'product-blocks'); ?></span>
                                            </a>
                                        </td>
                                <?php
                                    }
                                    for ($i = 0; $i < $demo_column; $i++) {
                                        echo '<td class="wopb-demo-column"><span></span></td>';
                                    }
                                    if ( $compare_add_product_button == 'yes' ) {
                                ?>
                                    <td class="wopb-action-add-btn">
                                        <a class="wopb-compare-add-btn">
                                            <?php echo wopb_function()->svg_icon('plus_3')  ?>
                                            <span class="wopb-tooltip"><?php echo __('Add Product', 'product-blocks'); ?></span>
                                        </a>
                                    </td>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                    <?php
                        $table_columns = wopb_function()->get_setting('compare_table_columns');
                        foreach ($table_columns as $table_column) {
                            $row_class = 'wopb-' . $table_column['key'] . '-row';
                            $column_class = wopb_function()->get_setting('compare_first_column_sticky') && ! wp_is_mobile() ? 'wopb-sticky-column' : '';
                    ?>
                            <tr class="<?php echo $row_class ?>">
                                <th class="<?php echo $column_class ?>"><?php echo esc_html( $table_column['label'] ); ?></th>
                                <?php
                                    foreach ($compare_data as $key => $val) {
                                        $product = wc_get_product($val);
                                ?>
                                        <td class="wopb-compare-item-<?php echo esc_attr($product->get_id()) ?>">
                                            <?php
                                                switch ($table_column['key']) {
                                                    case 'image':
                                                    case 'title':
                                            ?>
                                                        <a href="<?php echo esc_url($product->get_permalink()) ?>">
                                                            <?php
                                                                echo $table_column['key'] == 'image' ? $product->get_image('woocommerce_thumbnail') : $product->get_title()
                                                            ?>

                                                        </a>
                                            <?php
                                                        break;
                                                    case 'quantity':
                                            ?>
                                                        <div class="wopb-quantity-wrapper quantity">
                                                            <a class="wopb-add-to-cart-minus"><?php echo wopb_function()->svg_icon('minus_2') ?></a>
                                                            <input type="number" class="input-text qty text" value="1">
                                                            <a class="wopb-add-to-cart-plus"><?php echo wopb_function()->svg_icon('plus_2') ?></a>
                                                        </div>
                                            <?php
                                                    break;
                                                    case 'price':
                                                        echo $product->get_price_html() ? wp_kses_post($product->get_price_html()) : 'N/A';
                                                        break;
                                                    case 'description':
                                            ?>
                                                        <span class="wopb-description"><?php echo $product->get_short_description() ? wp_kses_post($product->get_short_description()) : 'N/A'; ?></span>
                                            <?php
                                                        break;
                                                    case 'stock_status':
                                                        if ( $product->is_purchasable() && $product->is_in_stock() ) {
                                                            echo $product->get_stock_quantity().' '.esc_html__('in stock', 'product-blocks');
                                                        }
                                                    break;
                                                    case 'add_to_cart':
                                                        $cart_btn_class = '';
                                                        $cart_text = $product->add_to_cart_text();
                                                        if ( $product->is_type('simple') && $product->is_in_stock() ) {
                                                            $cart_btn_class = 'ajax_add_to_cart';
                                                        }
                                            ?>
                                                    <span class="wopb-cart-action">
                                                        <a
                                                            href="<?php echo esc_url($product->add_to_cart_url()) ?>"
                                                            class="wopb-add-to-cart <?php echo esc_attr($cart_btn_class) ?>"
                                                            data-postid="<?php echo esc_attr($product->get_id()) ?>"
                                                        >
                                                            <?php echo esc_html( $cart_text ); ?>
                                                        </a>
                                                        <a
                                                            href="<?php echo esc_url(wc_get_cart_url()) ?>"
                                                            class="wopb-add-to-cart wopb-view-cart"
                                                        >
                                                            <?php esc_html_e('View Cart', 'product-blocks'); ?>
                                                        </a>
                                                    </span>
                                            <?php
                                                    break;
                                                    case 'review':
                                            ?>
                                                        <div class="wopb-review-content">
                                                            <div class="wopb-star-rating" aria-label=""><span style="width:<?php echo esc_attr($product->get_average_rating() ? ($product->get_average_rating() / 5) * 100 : 0) ?>%"></span></div>
                                                            <span class="wopb-review-count">
                                                                <?php echo esc_html( $product->get_rating_count() . ' customer review' ); ?>
                                                            </span>
                                                        </div>
                                            <?php
                                                    break;
                                                    case 'additional':
                                                        ob_start();
                                                            wc_display_product_attributes( $product );
                                                        $additional = ob_get_clean();
                                            ?>
                                                    <span class="wopb-additional">
                                                        <?php echo $additional ? $additional : 'N/A' ?>
                                                    </span>
                                            <?php
                                                        break;
                                                    case 'weight':
                                                        $weight = $product->get_weight();
                                                        $weight = $weight ? ( wc_format_localized_decimal( $weight ) . ' ' . esc_attr( get_option( 'woocommerce_weight_unit' ) ) ) : 'N/A';
                                                        echo $weight;
                                                        break;
                                                    case 'sku':
                                                        echo esc_html($product->get_sku() ? $product->get_sku() : 'N/A');
                                                        break;
                                                    case 'dimensions':
                                                        echo $product->get_dimensions(false) ? esc_html(wc_format_dimensions($product->get_dimensions(false))) : 'N/A';
                                                        break;
                                            ?>

                                            <?php
                                                default:
                                                    break;
                                                }
                                            ?>
                                        </td>
                                <?php
                                    }
                                    for ($i = 0; $i < $demo_column; $i++) {
                                        $demo_content = '';
                                        $demo_column_class = '';
                                        if ( $table_column['key'] == 'image' ) {
                                            $demo_column_class = ' image';
                                            $demo_content = wopb_function()->svg_icon( 'placeholder' );
                                        }
                                        echo '<td class="wopb-demo-column' . $demo_column_class . '"><span>' . $demo_content . '</span></td>';
                                    }
                                    if ( $compare_add_product_button == 'yes' ) {
                                ?>
                                    <td class="wopb-action-add-btn"></td>
                                <?php } ?>
                            </tr>
                    <?php } ?>
                        </tbody>
                    </table>
            <?php } ?>
        </div>
<?php
        return ob_get_clean();
    }

    /**
     * Compare Product List Modal
     *
     * @since v.3.1.1
     * @return null
     */
    public function product_list_modal( $params ) {
        $output = '';
        $output .= '<div class="wopb-compare-product-list-modal wopb-d-none">';
            $output .= '<div class="wopb-product-list-content">';
                $output .= '<a class="wopb-product-list-close">';
                    $output .= wopb_function()->svg_icon( 'close' );
                $output .= '</a>';
                $output .= '<div class="wopb-product-list-body">';
                    $output .= '<div class="wopb-product-search">';
                        $output .= '<input type="text" class="wopb-search-input" placeholder="' . __( 'Search for products by name...', 'product-blocks' ) . '">';
                        $output .= '<a class="wopb-search-icon">';
                            $output .= wopb_function()->svg_icon( 'search2' );
                        $output .= '</a>';
                    $output .= '</div>';
                    $output .= '<div class="wopb-product-list">';
                        $output .= $this->product_list( $params );
                    $output .= '</div>';
                $output .= '</div>';
            $output .= '</div>';
        $output .= '</div>';
        
        return $output;
    }

    /**
     * Comparison Sidebar Content
     *
     * @since v.3.1.1
     * @param $params
     * @return html
     */
    public function compare_sidebar_content( $params = [] ) {
        $output = '';
        $compare_data = isset($params['source']) && $params['source'] == 'ajax' ? $params['data_id'] : $this->compare_id;
      
        $output .= '<div class="wopb-modal-body" data-outside_click="yes">';
            if ( count( $compare_data ) == 0 ) {
                $output .= $this->empty_product_message( $params ); 
            } else {
                $output .= '<div class="wopb-product-list">';
                    foreach ( $compare_data as $key => $val ) {
                        $product = wc_get_product( $val );
                        $output .= '<div class="wopb-compare-item wopb-compare-item-' . esc_attr( $product->get_id() ) .'">';
                            $output .= '<div class="wopb-compare-product-details">';
                                $output .= '<a href="' . esc_url( $product->get_permalink() ) .'" class="wopb-product-image">';
                                    $output .= $product->get_image( 'woocommerce_thumbnail' );
                                $output .= '</a>';
                                $output .= '<a class="wopb-compare-product-name" href="' . esc_url( $product->get_permalink() ) . '" data-action="remove">';
                                    $output .= $product->get_title();
                                $output .= '</a>';
                            $output .= '</div>';
                            $output .= '<a class="wopb-compare-remove" data-action="remove" data-added-action="sidebar" data-postid="' . esc_attr( $product->get_id() ) . '">';
                                $output .= wopb_function()->svg_icon( 'delete' );
                            $output .= '</a>';
                        $output .= '</div>';
                    }
                $output .= '</div>';
            }
        $output .= '</div>';
    
        return $output;
    }

    /**
     * Message When There Is No Compare Product
     *
     * @since v.3.1.1
     * @return null
     */
    public function empty_product_message( $params = [] ) {
        $output = '';
        $class = isset( $params['added_action'] ) ? 'wopb-' . $params['added_action'] : '';
        
        $output .= '<div class="wopb-no-product ' . esc_attr( $class ) .'">';
            $output .= '<div class="wopb-no-product-text">' . esc_html__( 'No products were added to compare list', 'product-blocks' ) . '</div>';
            $output .= '<a href="' . wc_get_page_permalink( 'shop' ) . '" class="wopb-retun-shop">' . esc_html__( 'Return to Shop', 'product-blocks' ) . '</a>';
        $output .= '</div>';
        
        return $output;
    }

    /**
     * Comparison Sidebar Content
     *
     * @since v.3.1.1
     * @param $product_id
     * @return html
     */
    public function compare_toast_message( $params ) {
        $output  = '';
        $post_id = isset( $params['postid'] ) ? $params['postid'] : esc_attr( get_the_ID() );

        if ( $post_id ) {
            $product = wc_get_product( $post_id );
            $output .= '<div class="wopb-compare-item wopb-compare-item-' . esc_attr( $product->get_id() ) .'">';
                $output .= '<span class="wopb-compare-image">';
                    $output .= $product->get_image( 'shop_thumbnail' );
                $output .= '</span>';
                $output .= '<div class="wopb-compare-product-name"><span>' . $product->get_title() . '</span> ' . __( 'has been added on compare list.', 'product-blocks' ) . '</div>';
            $output .= '</div>';
        }
        $output .= '<a href="' . esc_url( get_permalink( wopb_function()->get_setting( 'compare_page' ) ) ) . '" class="wopb-compare-view-btn">';
            $output .= __( 'View Compare List', 'product-blocks' );
        $output .= '</a>';

        return $output;
    }

    /**
     * Compare Button In Single Product Page
     *
     * @since v.4.1.0
     * @return null
     */
    public function before_single_product() {
        $compare_position = wopb_function()->get_setting('compare_position');
        $position_filters = $this->button_position_filters();
        if( isset( $position_filters[$compare_position] ) ) {
            add_filter($position_filters[$compare_position], function ( $content ) {
                return $content . $this->get_compare( get_the_ID(), 'default' );
            }, 100, 1);
        }
    }

    /**
     * Compare Button In Shop Page
     *
     * @param $content
     * @return string
     * @since v.3.1.1
     */
    public function compare_button_in_cart( $content ) {
        if ( ! wopb_function()->is_builder() && (is_shop() || is_archive() ) ) {
            return $content . $this->get_compare( get_the_ID(), 'default' );
        }
    }

    /**
     * Compare Button Position Filters in Shop Page
     *
     * @since v.3.1.5
     * @return array
     */
    public function button_position_shop_filters() {
        return array(
            'top_cart'      => 'wopb_top_add_to_cart_loop',
            'before_cart'   => 'wopb_before_add_to_cart_loop',
            'after_cart'    => 'wopb_after_add_to_cart_loop',
            'bottom_cart'   => 'wopb_bottom_add_to_cart_loop',
            'above_image'   => 'wopb_before_shop_loop_title',
        );
    }

    /**
     * Compare Button Position Filters in Single Page
     *
     * @since v.3.1.5
     * @return array
     */
    public function button_position_filters() {
        return array(
            'top_cart'      => 'wopb_top_add_to_cart',
            'before_cart'   => 'wopb_before_add_to_cart',
            'after_cart'    => 'wopb_after_add_to_cart',
            'bottom_cart'   => 'wopb_bottom_add_to_cart',
        );
    }

    /**
     * Compare End Point Register For My Account
     *
     * @since v.3.1.1
     * @return null
     */
    public function my_account_compare_endpoint() {
        add_rewrite_endpoint( $this->my_account_compare_end_point, EP_ROOT | EP_PAGES );
        if ( ! wopb_function()->get_setting( $this->my_account_compare_end_point . '-endpoint' ) ) {
            flush_rewrite_rules();
            wopb_function()->set_setting( $this->my_account_compare_end_point . '-endpoint', 'yes' );
        }
    }

    /**
     * Compare Menu In My Account Menubar
     *
     * @since v.3.1.1
     * @param $menu_links
     * @return array
     */
    public function compare_my_account_menu_items( $menu_links ) {
        $menu_links[$this->my_account_compare_end_point] = __( 'Compare', 'product-blocks' );
        return $menu_links;
    }

    /**
     * WooCommerce Query Var For Compare
     *
     * @since v.3.1.1
     * @param $query
     * @return array
     */
    public function woocommerce_query_vars( $query ) {
        $query[$this->my_account_compare_end_point] = $this->my_account_compare_end_point;
        return $query;
    }

    /**
     * Compare Table Columns
     *
     * @since v.3.1.1
     * @param $default
     * @return array
     */
    public function compare_table_columns( $default = '' ) {
        $default_options = array(
            ['key' => 'image','label' => __( 'Image','product-blocks' )],
            ['key' => 'title','label' => __( 'Title','product-blocks' )],
            ['key' => 'price','label' => __( 'Price','product-blocks' )],
            ['key' => 'stock_status','label' => __( 'Stock Status','product-blocks' )],
            ['key' => 'quantity','label' => __( 'Quantity','product-blocks' )],
            ['key' => 'add_to_cart','label' => __( 'Add To Cart','product-blocks' )],
            ['key' => 'review','label' => __( 'Review','product-blocks' )],
        );
        $options = array(
            ['key' => '','label' => __( '- Select -','product-blocks' )],
            ...$default_options,
            ['key' => 'additional','label' => __( 'Additional','product-blocks' )],
            ['key' => 'description','label' => __( 'Description','product-blocks' )],
            ['key' => 'weight','label' => __( 'Weight','product-blocks' )],
            ['key' => 'dimensions','label' => __( 'Dimensions','product-blocks' )],
            ['key' => 'sku','label' => __( 'SKU','product-blocks' )],
        );
        return $default && $default == 'default' ? $default_options : $options;
    }


    /**
     * Dynamic CSS
     *
     * @since v.3.1.1
     * @return string
     */
    public function generate_css( $key ) {
        if ( $key == 'wopb_compare' ) {
            $settings = wopb_function()->get_setting();
            $shop_btn_style = array_merge($settings['compare_btn_typo_shop'], $settings['compare_btn_bg_shop']);
            $single_btn_style = array_merge( $settings['compare_btn_typo_single'], $settings['compare_btn_bg_single'] );
            $nav_btn_style = array_merge( $settings['compare_btn_typo_nav'], $settings['compare_btn_bg_nav'] );
            $table_cart_style = array_merge( $settings['compare_tbl_cart_btn_typo'], $settings['compare_tbl_cart_btn_bg'] );
            $css = '';

            /* Shop page button style */
            if( ! empty( $settings['compare_align_shop'] ) ) {
                $css .= '.wopb-compare-btn-wrap{';
                    $css .= 'display: inline-flex;';
                    $css .= 'justify-content: ' . $settings['compare_align_shop'] . ';';
                $css .= '}';
            }
            $css .= '.wopb-compare-btn.wopb-compare-shop-btn{';
                $css .= wopb_function()->convert_css('general', $shop_btn_style);
                $css .= wopb_function()->convert_css('border', $settings['compare_btn_border_shop']);
                $css .= wopb_function()->convert_css('radius', $settings['compare_btn_radius_shop']);
                $css .= 'padding: ' . wopb_function()->convert_css('dimension', $settings['compare_btn_padding_shop']) . ';';
            $css .= '}';
            $css .= '.wopb-compare-btn.wopb-compare-shop-btn:hover{';
                $css .= wopb_function()->convert_css('hover', $shop_btn_style);
            $css .= '}';
            $css .= '.wopb-compare-btn.wopb-compare-shop-btn svg{';
                $css .= 'height: ' . ( ! empty( $settings['compare_icon_size_shop'] ) ? $settings['compare_icon_size_shop'] : '16' ) . 'px;';
                $css .= 'width: ' . ( ! empty( $settings['compare_icon_size_shop'] ) ? $settings['compare_icon_size_shop'] : '16' ) . 'px;';
            $css .= '}';
            /* Shop page button style */

            /* Single product page button style */
            $css .= '.wopb-compare-btn.wopb-compare-single-btn{';
                $css .= wopb_function()->convert_css('general', $single_btn_style);
                $css .= wopb_function()->convert_css('border', $settings['compare_btn_border_single']);
                $css .= wopb_function()->convert_css('radius', $settings['compare_btn_radius_single']);
                $css .= 'padding: ' . wopb_function()->convert_css('dimension', $settings['compare_btn_padding_single']) . ';';
            $css .= '}';
            $css .= '.wopb-compare-btn.wopb-compare-single-btn:hover{';
                $css .= wopb_function()->convert_css('hover', $single_btn_style);
            $css .= '}';
            $css .= '.wopb-compare-btn.wopb-compare-single-btn svg{';
                $css .= 'height: ' . ( ! empty( $settings['compare_icon_size_single'] ) ? $settings['compare_icon_size_single'] : '16' ) . 'px;';
                $css .= 'width: ' . ( ! empty( $settings['compare_icon_size_single'] ) ? $settings['compare_icon_size_single'] : '16' ) . 'px;';
                if( ! empty( $settings['compare_text_single'] )  ) {
                    $css .= 'margin-bottom: -2px;';
                }
            $css .= '}';
            /* Single product page button style */

            /* Navbar style */
            $css .= '.wopb-compare-nav-item{';
                $css .= wopb_function()->convert_css('general', $nav_btn_style);
                $css .= wopb_function()->convert_css('border', $settings['compare_btn_border_nav']);
                $css .= wopb_function()->convert_css('radius', $settings['compare_btn_radius_nav']);
                $css .= 'padding: ' . wopb_function()->convert_css('dimension', $settings['compare_btn_padding_nav']) . ';';
            $css .= '}';
            $css .= '.wopb-compare-nav-item:hover{';
                $css .= wopb_function()->convert_css('hover', $nav_btn_style);
            $css .= '}';
            $css .= '.wopb-compare-nav-item .wopb-compare-icon svg{';
                $css .= 'height: ' . ( ! empty( $settings['compare_icon_size_nav'] ) ? $settings['compare_icon_size_nav'] : '18' ) . 'px;';
                $css .= 'width: ' . ( ! empty( $settings['compare_icon_size_nav'] ) ? $settings['compare_icon_size_nav'] : '18' ) . 'px;';
                if( ! empty( $settings['compare_nav_text'] )  ) {
                    $css .= 'margin-bottom: -2px;';
                }
            $css .= '}';
            /* Navbar style */

            /* Table style */
            $css .= '.wopb-compare-table .wopb-cart-action .wopb-add-to-cart{';
                $css .= wopb_function()->convert_css('general', $table_cart_style);
                $css .= wopb_function()->convert_css('border', $settings['compare_tbl_cart_btn_border']);
                $css .= wopb_function()->convert_css('radius', $settings['compare_tbl_cart_btn_radius']);
                $css .= 'padding: ' . wopb_function()->convert_css('dimension', $settings['compare_tbl_cart_btn_padding']) . ';';
            $css .= '}';
            $css .= '.wopb-compare-table .wopb-cart-action .wopb-add-to-cart:hover{';
                $css .= wopb_function()->convert_css('hover', $table_cart_style);
            $css .= '}';

            $css .= '.wopb-compare-table th{';
                $css .= wopb_function()->convert_css('general', $settings['compare_heading_typo']);
            $css .= '}';
            $css .= '.wopb-compare-table th:hover{';
                $css .= wopb_function()->convert_css('hover', $settings['compare_heading_typo']);
            $css .= '}';
            $css .= '.wopb-compare-table tbody td{';
                $css .= wopb_function()->convert_css('general', $settings['compare_body_text_typo']);
            $css .= '}';
            $css .= '.wopb-compare-table tbody td:hover{';
                $css .= wopb_function()->convert_css('hover', $settings['compare_body_text_typo']);
            $css .= '}';
            $css .= '.wopb-compare-table{';
            if( ! empty( $settings['compare_column_space'] ) ) {
                $css .= 'border-collapse: separate;';
                $css .= 'border-spacing: ' . $settings['compare_column_space'] . 'px;';
            }else {
                $css .= 'border-collapse: collapse;';
            }
            $css .= '}';
            $css .= '.wopb-compare-table th, .wopb-compare-table td{';
                $css .= wopb_function()->convert_css('border', $settings['compare_column_border']);
            $css .= 'padding: ' . wopb_function()->convert_css('dimension', $settings['compare_column_padding']) . ';';
            $css .= '}';
            /* Table style */

            wopb_function()->update_css( $key, 'add', $css );
        }
    }
}