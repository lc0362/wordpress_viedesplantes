<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @package BlogData
 */ ?>
<!-- </main> -->
<?php do_action('blogdata_action_footer_missed_section'); ?>
    <!--==================== FOOTER AREA ====================-->
    <?php $footer_bg_img = blogdata_get_option('blogdata_footer_bg_img');
    $blogdata_footer_overlay_color = get_theme_mod('blogdata_footer_overlay_color');
    function get_footer_bg_img() {
        if ( blogdata_get_option('blogdata_footer_bg_img') > 0 ) {
            return wp_get_attachment_url( blogdata_get_option('blogdata_footer_bg_img') );
        }
    } ?>
    <footer class="footer one <?php echo esc_attr($footer_bg_img != '' ? 'back-img' : '' ); ?>"
    <?php if($footer_bg_img != '') { ?> style="background-image:url('<?php echo get_footer_bg_img()?>');" <?php } ?> >
        <div class="overlay" style="background-color: <?php echo esc_html($blogdata_footer_overlay_color);?>;">
            <!--Start bs-footer-widget-area-->
            <?php if ( is_active_sidebar( 'footer_widget_area' ) ) { ?>
                <div class="bs-footer-widget-area">
                    <div class="container">
                        <!--row-->
                            <div class="row">
                                <?php  dynamic_sidebar( 'footer_widget_area' ); ?>
                            </div>
                            <div class="divide-line"></div>
                        <!--/row-->
                    </div>
                    <!--/container-->
                </div>
            <?php } 
            $hide_copyright = esc_attr(get_theme_mod('hide_copyright',true));
            $copyright = blogdata_get_option( 'blogdata_footer_copyright'); ?>
                <div class="bs-footer-bottom-area">
                    <div class="container">
                        <div class="row align-center">
                            <div class="col-lg-6 col-md-6">
                                <div class="footer-logo text-xs">
                                    <?php the_custom_logo(); ?>
                                    <div class="site-branding-text">
                                        <p class="site-title-footer"> <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo('name'); ?></a></p>
                                        <p class="site-description-footer"><?php bloginfo('description'); ?></p>
                                    </div>
                                </div>
                            </div>
                            <!--col-lg-3-->
                            <div class="col-lg-6 col-md-6">
                                <?php if(get_theme_mod('footer_social_icon_enable', true) == true) { do_action('blogdata_action_social_section'); } ?>
                            </div>
                            <!--/col-lg-3-->
                        </div>
                        <!--/row-->
                    </div>
                    <!--/container-->
                </div>
                <!--End bs-footer-widget-area-->
                <?php if($hide_copyright == true ) { ?>
                    <div class="bs-footer-copyright">
                        <div class="container">
                            <div class="row">
                                <div class="<?php echo esc_attr( has_nav_menu( 'footer') ? 'col-lg-6 col-md-6 text-xs' : 'col-lg-12 text-center'); ?>">
                                    <?php if($hide_copyright == true) { ?>
                                        <p class="mb-0">
                                            <?php echo esc_html($copyright); ?>
                                            <span class="sep"> | </span>
                                            <?php  printf(esc_html__('%1$s by %2$s.', 'blogdata'), '<a href="https://themeansar.com/free-themes/blogdata/" target="_blank">BlogData</a>', '<a href="https://themeansar.com" target="_blank">Themeansar</a>'); ?>
                                            </a>
                                            </p>
                                    <?php } ?>                                       
                                </div>
                                <?php if( has_nav_menu( 'footer') ) { ?>
                                    <div class="col-lg-6 col-md-6 text-md-end text-xs">
                                        <?php blogdata_target_element('panel', 'nav_menus', 'Click To Edit Footer Menu.'); ?>
                                        <?php wp_nav_menu( array(
                                                'theme_location' => 'footer',
                                                'container'  => 'nav-collapse collapse navbar-inverse-collapse',
                                                'menu_class' => 'info-right',
                                                'fallback_cb' => 'blogdata_fallback_page_menu',
                                                'walker' => new blogdata_nav_walker()
                                            ) ); 
                                        ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div> 
                <?php } ?> 
        </div>
        <!--/overlay-->
    </footer>
    <!--/footer-->
</div>
<!--/wrapper-->
<!--Scroll To Top-->
    <?php blogdata_scrolltoup(); ?>
<!--/Scroll To Top-->
<?php wp_footer(); ?>
</body>
</html>