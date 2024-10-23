<?php
// Load widget base.
require_once get_template_directory() . '/inc/ansar/widgets/widgets-base.php';

/* Theme Widget sidebars. */
require get_template_directory() . '/inc/ansar/widgets/widgets-common-functions.php';

/* Theme Widgets*/
require get_template_directory() . '/inc/ansar/widgets/widget-author-info.php';
require get_template_directory() . '/inc/ansar/widgets/widget-recent-post.php';

/* Register site widgets */
if ( ! function_exists( 'blogdata_widgets' ) ) :
    /**
     * Load widgets.
     *
     * @since 1.0.0
     */
    function blogdata_widgets() {
        register_widget( 'blogdata_author_info');
        register_widget( 'blogdata_featured_latest_news' );
    }
endif;
add_action( 'widgets_init', 'blogdata_widgets' );

/**
 * Blogdata Widgets - Loader.
 *
 * @package blogdata Widget
 * @since 1.0.0
 */

if ( ! class_exists( 'blogdata_Widgets_Loader' ) ) {

    /**
     * Customizer Initialization
     *
     * @since 1.0.0
     */
    class blogdata_Widgets_Loader {

        /**
         * Member Variable
         *
         * @var instance
         */
        private static $instance;

        /**
         *  Initiator
         */
        public static function get_instance() {
            if ( ! isset( self::$instance ) ) {
                self::$instance = new self;
            }
            return self::$instance;
        }

        /**
         *  Constructor
         */
        public function __construct() {


            // Add Widget.

            //  add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
             add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
        }
        
        function enqueue_admin_scripts() {
             

   wp_enqueue_style( 'wp-color-picker');
   
   wp_enqueue_script( 'wp-color-picker');
             
   }
        
        
    }
}

/**
*  Kicking this off by calling 'get_instance()' method
*/
blogdata_Widgets_Loader::get_instance();