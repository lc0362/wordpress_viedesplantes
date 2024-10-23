<?php
if (!function_exists('blogdata_banner_advertisement')):
    /**
     *
     * @since blogdata 1.0.0
     *
     */
    function blogdata_banner_advertisement()
    {

        if (('' != blogdata_get_option('banner_ad_image')) ) {

            $blogdata_banner_advertisement = blogdata_get_option('banner_ad_image');
            $blogdata_banner_advertisement = absint($blogdata_banner_advertisement);
            $blogdata_banner_advertisement = wp_get_attachment_image($blogdata_banner_advertisement, 'full');
            $banner_ad_url = blogdata_get_option('banner_ad_url');
            $banner_open_on_new_tab = blogdata_get_option('banner_open_on_new_tab');
            $banner_open_on_new_tab = ('' != $banner_open_on_new_tab) ? '_blank' : '';
            ?>
            <div class="advertising-banner">
                <div class="container">
                    <a class="pull-right img-fluid" href="<?php echo esc_url($banner_ad_url); ?>" target="<?php echo esc_attr($banner_open_on_new_tab); ?>">
                        <?php echo $blogdata_banner_advertisement; ?>
                    </a> 
                </div>
            </div>
            <?php
        }
    }
endif;

add_action('blogdata_action_banner_advertisement', 'blogdata_banner_advertisement', 10);