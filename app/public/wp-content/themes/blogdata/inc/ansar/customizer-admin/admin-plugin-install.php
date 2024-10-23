<?php /**
 * AJAX handler to store the state of dismissible notices.
 */
function blogdata_ajax_notice_handler() {
    if ( isset( $_POST['type'] ) ) {
        // Pick up the notice "type" - passed via jQuery (the "data-notice" attribute on the notice)
        $type = sanitize_text_field( wp_unslash( $_POST['type'] ) );
        // Store it in the options table
        update_option( 'dismissed-' . $type, TRUE );
    }
}

add_action( 'wp_ajax_blogdata_dismissed_notice_handler', 'blogdata_ajax_notice_handler' );

function blogdata_deprecated_hook_admin_notice() {
        // Check if it's been dismissed...
        if ( ! get_option('dismissed-get_started', FALSE ) ) {
            // Added the class "notice-get-started-class" so jQuery pick it up and pass via AJAX,
            // and added "data-notice" attribute in order to track multiple / different notices
            // multiple dismissible notice states ?>
               <div class="blogdata-notice-started updated notice notice-get-started-class is-dismissible" data-notice="get_started">
                <div class="blogdata-notice clearfix">
                    <div class="blogdata-notice-content">
                        
                        <div class="blogdata-notice_text">
                        <div class="blogdata-hello">
                            <?php esc_html_e( 'Hello, ', 'blogdata' ); 
                            $current_user = wp_get_current_user();
                            echo esc_html( $current_user->display_name );
                            ?>
                            <img draggable="false" role="img" class="emoji" alt="👋🏻" src="https://s.w.org/images/core/emoji/14.0.0/svg/1f44b-1f3fb.svg">                
                        </div>
                        <h1><?php
                                $theme_info = wp_get_theme();
                                printf( esc_html__('Welcome to %1$s', 'blogdata'), esc_html( $theme_info->Name ), esc_html( $theme_info->Version ) ); ?>
                        </h1>
                        
                        <p><?php esc_html_e("Thank you for choosing blogdata theme. To take full advantage of the complete features of the theme click the Starter Sites and Install and Activate the", "blogdata");?> <a href="https://wordpress.org/plugins/ansar-import"><?php esc_html_e("Ansar Import", "blogdata");?></a> <?php esc_html_e("plugin then use the demo importer and install the Blogdata Demo according to your need.", "blogdata"); ?></p>

                        <div class="panel-column-6">
                        <a class="blogdata-btn-get-started button button-primary button-hero blogdata-button-padding" href="#" data-name="" data-slug=""><?php esc_html_e( 'Import Demo', 'blogdata' ) ?></a>

                        <a class="blogdata-btn-get-started-customize button button-primary button-hero blogdata-button-padding" href="<?php echo esc_url( admin_url( '/customize.php' ) ); ?>" data-name="" data-slug=""><?php esc_html_e( 'Customize Site', 'blogdata' ) ?></a>

                        <div class="blogdata-documentation">
                        <span aria-hidden="true" class="dashicons dashicons-external"></span>
                         <a class="blogdata-documentation" href="<?php echo esc_url('https://docs.themeansar.com/docs/blogdata-pro')?>" data-name="" data-slug=""><?php esc_html_e( 'View Documentation', 'blogdata' ) ?></a>
                        </div>

                        <div class="blogdata-demos">
                        <span aria-hidden="true" class="dashicons dashicons-external"></span>
                        <a class="blogdata-demos" href="<?php echo esc_url('https://demos.themeansar.com/blogdata-demos/')?>" data-name="" data-slug=""><?php esc_html_e( 'View Demos', 'blogdata' ) ?></a>
                        </div>

                        </div>
                        </div>
                        <div class="blogdata-notice_image">
                             <img class="blogdata-screenshot" src="<?php echo esc_url( get_theme_file_uri() . '/images/customize.webp' ); ?>" alt="<?php esc_attr_e( 'BlogData', 'blogdata' ); ?>" />
                        </div>
                    </div>
                </div>
            </div>
        <?php }
}

add_action( 'admin_notices', 'blogdata_deprecated_hook_admin_notice' );

/* Plugin Install */

add_action( 'wp_ajax_install_act_plugin', 'blogdata_admin_info_install_plugin' );

function blogdata_admin_info_install_plugin() {
    /**
     * Install Plugin.
     */
    include_once ABSPATH . '/wp-admin/includes/file.php';
    include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
    include_once ABSPATH . 'wp-admin/includes/plugin-install.php';

    if ( ! file_exists( WP_PLUGIN_DIR . '/ansar-import' ) ) {
        $api = plugins_api( 'plugin_information', array(
            'slug'   => sanitize_key( wp_unslash( 'ansar-import' ) ),
            'fields' => array(
                'sections' => false,
            ),
        ) );

        $skin     = new WP_Ajax_Upgrader_Skin();
        $upgrader = new Plugin_Upgrader( $skin );
        $result   = $upgrader->install( $api->download_link );
    }

    // Activate plugin.
    if ( current_user_can( 'activate_plugin' ) ) {
        $result = activate_plugin( 'ansar-import/ansar-import.php' );
    }
}