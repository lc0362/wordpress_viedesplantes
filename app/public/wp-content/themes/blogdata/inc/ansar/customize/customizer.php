<?php
/**
 * BlogData Theme Customizer
 *
 * @package BlogData
 */

// Load customize default values.
require get_template_directory().'/inc/ansar/customize/customizer-callback.php';

// Load customize default values.
require get_template_directory().'/inc/ansar/customize/customizer-default.php';


$repeater_path = trailingslashit( get_template_directory() ) . '/inc/ansar/customizer-repeater/functions.php';
if ( file_exists( $repeater_path ) ) {
require_once( $repeater_path );
}

function banner_slider_option($control) {

        $banner_slider_option = $control->manager->get_setting('banner_options_main')->value();

        if($banner_slider_option == 'banner_slider_section_option'){
            return true;
        } else{
           return false;
        }
    }

    function banner_slider_category_function($control){
  $no_option = $control->manager->get_setting('banner_options_main')->value();
  $banner_slider_category_option = $control->manager->get_setting('banner_slider_section_option')->value();
        if ($banner_slider_category_option == 'banner_slider_category_option' && $no_option == 'banner_slider_section_option') {
            return true;
        }else{ return false;}
    }

    function header_video_act_call($control){
        $video_banner_section = $control->manager->get_setting('banner_options_main')->value();
    
        if($video_banner_section == 'header_video'){
            return true;
        }else{
            return false;
        }
        }


function video_banner_section_function($control){
    $video_banner_section = $control->manager->get_setting('banner_options_main')->value();

    if($video_banner_section == 'video_banner_section'){
        return true;
    }else{
        return false;
    }
    }


function slider_callback($control){
  $banner_slider_option = $control->manager->get_setting('banner_options_main')->value();
  $banner_slider_section_option = $control->manager->get_setting('banner_slider_section_option')->value();
if ($banner_slider_option == 'banner_slider_section_option' && $banner_slider_section_option == 'latest_post_show') {
            return true;
        }else{
            return false;
        }
    }


    function overlay_text($control){

    $banner_slider_option = $control->manager->get_setting('banner_options_main')->value();

    if($banner_slider_option == 'header_video' || $banner_slider_option == 'video_banner_section'){
        return true;
    }else{
       return false;
    }

}

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function blogdata_customize_register($wp_customize) {

	// Load customize controls.
	require get_template_directory().'/inc/ansar/customize/customizer-control.php';


    // Load customize sanitize.
	require get_template_directory().'/inc/ansar/customize/customizer-sanitize.php';
    
	$wp_customize->get_setting('blogname')->transport         = 'postMessage';
	$wp_customize->get_setting('blogdescription')->transport  = 'postMessage';
	$wp_customize->get_setting('custom_logo')->transport = 'postMessage';
	$wp_customize->get_setting('header_textcolor')->transport 		= 'postMessage';

	if (isset($wp_customize->selective_refresh)) {
		$wp_customize->selective_refresh->add_partial('blogname', array(
            'selector'        => '.site-title a , .site-title-footer a',
            'render_callback' => 'blogdata_customize_partial_blogname',
        ));
        $wp_customize->selective_refresh->add_partial('blogdescription', array(
            'selector'        => '.site-description , .site-description-footer',
            'render_callback' => 'blogdata_customize_partial_blogdescription',
        ));
		$wp_customize->selective_refresh->add_partial('custom_logo', array(
			'selector'        => '.site-logo', 
			'render_callback' => 'custom_logo_selective_refresh'
		));	

        $wp_customize->selective_refresh->add_partial('blogdata_social_icons', array(
            'selector'        => 'footer .bs-social ',
        ));

        $wp_customize->selective_refresh->add_partial('blogdata_scrollup_enable', array(
            'selector'        => '.bs_upscr',
        ));

        $wp_customize->selective_refresh->add_partial('you_missed_title', array(
            'selector'          => '.missed .bs-widget-title',
            'render_callback'   => 'blogdata_customize_partial_you_missed_title',
        ));

        $wp_customize->selective_refresh->add_partial('sidebar_menu', array(
            'selector'        => '.navbar-wp [data-bs-toggle=offcanvas]',
            'render_callback' => 'blogdata_customize_partial_sidebar_menu',
        ));
        $wp_customize->selective_refresh->add_partial('blogdata_related_post_title', array(
            'selector'        => '.bs-related-post-info .mb-3 .title',
            'render_callback' => 'blogdata_customize_partial_blogdata_related_post_title',
        ));

        $wp_customize->selective_refresh->add_partial('blogdata_menu_search', array(
            'selector'        => '.desk-header .right-nav a',
            'render_callback' => 'blogdata_customize_partial_blogdata_menu_search',
        ));

        $wp_customize->selective_refresh->add_partial('subs_news_title', array(
            'selector'        => '.subscribe-btn span',
            'render_callback' => 'blogdata_customize_partial_subs_news_title',
        ));

        $wp_customize->selective_refresh->add_partial('blogdata_lite_dark_switcher', array(
            'selector'        => '.switch .slider',    
        ));

        $wp_customize->selective_refresh->add_partial('single_post_meta', array(
            'selector'        => '.bs-blog-post .bs-header .bs-blog-meta ',
        )); 
        $wp_customize->selective_refresh->add_partial('blogdata_drop_caps_enable', array(
            'selector'        => '.content-right .bs-blog-post .bs-blog-meta', 
        ));   
        $wp_customize->selective_refresh->add_partial('hide_copyright', array(
            'selector'        => '.bs-footer-copyright .container', 
        ));
        $wp_customize->selective_refresh->add_partial('main_banner_section_background_image', array(
            'selector'        => '.homemain .bs-blog-post.three .bs-blog-meta', 
        ));
        $wp_customize->selective_refresh->add_partial('blogdata_archive_page_layout', array(
            'selector'        => '.index-class .row, .archive-class > .container > .row', 
            'render_callback' => 'blogdata_customize_partial_archive_page'
        ));    
        $wp_customize->selective_refresh->add_partial('blogdata_single_page_layout', array(
            'selector'        => '.single-class .row', 
            'render_callback' => 'blogdata_customize_partial_single_page'
        ));    
        $wp_customize->selective_refresh->add_partial('blogdata_page_layout', array(
            'selector'        => '.page-class > .container > .row', 
            'render_callback' => 'blogdata_customize_partial_page'
        ));
	}

    $default = blogdata_get_default_theme_options();

	/*theme option panel info*/

    require get_template_directory().'/inc/ansar/customize/header-options.php';

	require get_template_directory().'/inc/ansar/customize/theme-options.php';

    /*Theme customizer general option*/
    require get_template_directory().'/inc/ansar/customize/footer-options.php';

	/*theme general layout panel*/
	require get_template_directory().'/inc/ansar/customize/theme-layout.php';

    /*theme Forntpage Options panel*/
    require get_template_directory() . '/inc/ansar/customize/frontpage-options.php';
    
    //Customizer Core Panel
	require get_template_directory().'/inc/ansar/customize/customize-core.php';
}
add_action('customize_register', 'blogdata_customize_register');

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function blogdata_customize_partial_blogname() {
	bloginfo('name');
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function blogdata_customize_partial_blogdescription() {
	bloginfo('description');
}

function custom_logo_selective_refresh() {
	if( get_theme_mod( 'custom_logo' ) === "" ) return;
	echo '<div id="site-logo">'.the_custom_logo().'</div>';
}

function blogdata_customize_partial_footer_social_icon_enable() {
    return get_theme_mod( 'blogdata_social_icons' ); 
}

function blogdata_customize_partial_blogdata_related_post_title() {
    return get_theme_mod( 'blogdata_related_post_title' ); 
}

function blogdata_customize_partial_subs_news_title() {
    return get_theme_mod( 'subs_news_title' ); 
}

function blogdata_customize_partial_you_missed_title() {
	if( get_theme_mod( 'you_missed_title' ) === "" ) return;
	echo '<h2 class="title">'.get_theme_mod( 'you_missed_title' ).'</h2>';
}

function blogdata_customize_partial_sidebar_menu() {
    return get_theme_mod( 'sidebar_menu' ); 
}

function blogdata_customize_partial_blogdata_menu_subscriber() {
    return get_theme_mod( 'blogdata_menu_subscriber' ); 
}

function blogdata_customize_partial_archive_page() {
    if(is_archive()){
        do_action('blogdata_action_archive_page_title');
        do_action('blogdata_action_main_content_layouts');
    }else{
        do_action('blogdata_action_main_content_layouts');
    }
}

function blogdata_customize_partial_single_page() {
    do_action('blogdata_action_single_main_content_layouts');
}

function blogdata_customize_partial_page() {
    do_action('blogdata_action_archive_page_title'); 
    get_template_part('sections/page','data'); 
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function blogdata_customize_preview_js() {
    wp_enqueue_media();
	wp_enqueue_script('blogdata-customizer', get_template_directory_uri().'/js/customizer.js', array('customize-preview'), '20151215', true);
}
add_action('customize_preview_init', 'blogdata_customize_preview_js');

/************************* Theme Customizer with Sanitize function *********************************/
function blogdata_theme_option( $wp_customize )
{   $blogdata_default = blogdata_get_default_theme_options();

    function blogdata_sanitize_text( $input ) {
        return wp_kses_post( force_balance_tags( $input ) );
    }
}
add_action('customize_register','blogdata_theme_option');

if ( ! function_exists( 'blogdata_get_social_icon_default' ) ) {

    function blogdata_get_social_icon_default() {
        return apply_filters(
            'blogdata_get_social_icon_default',
            json_encode(
                array(
                    array(
                        'icon_value' => 'fab fa-facebook',
                        'link'       => '#',
                        'id'         => 'customizer_repeater_header_social_001',
                    ),
                    array(
                        'icon_value' => 'fa-brands fa-x-twitter',
                        'link'       => '#',
                        'id'         => 'customizer_repeater_header_social_003',
                    ),
                    array(
                        'icon_value' => 'fab fa-instagram',
                        'link'       => '#',
                        'id'         => 'customizer_repeater_header_social_005',
                    ),
                    array(
                        'icon_value' => 'fab fa-youtube',
                        'link'       => '#',
                        'id'         => 'customizer_repeater_header_social_006',
                    ),
                    array(
                        'icon_value' => 'fab fa-telegram',
                        'link'       => '#',
                        'id'         => 'customizer_repeater_header_social_008',
                    ),
                )
            )
        );
    }
}
