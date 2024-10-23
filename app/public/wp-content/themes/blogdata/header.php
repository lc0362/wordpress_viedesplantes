<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @package BlogData
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<?php wp_head(); ?>
</head>
<?php 
$theme_layout = get_theme_mod('blogdata_theme_layout_options','wide');
$main_banner_section_background_image = get_theme_mod('main_banner_section_background_image', '');
function get_main_banner_section_background_image_url() {
    if ( get_theme_mod( 'main_banner_section_background_image' ) > 0 ) {
        return wp_get_attachment_url( get_theme_mod( 'main_banner_section_background_image' ) );
    }
}
if($theme_layout == "boxed")
{ $class="boxed bodyback"; }
else
{ $class="wide"; } ?>
<body <?php body_class($class); ?> >
<?php wp_body_open(); ?>
<div id="page" class="site">
<a class="skip-link screen-reader-text" href="#content">
<?php esc_html_e( 'Skip to content', 'blogdata' ); ?></a>

<!--wrapper-->
<div class="wrapper" id="custom-background-css">
<!--==================== Header ====================-->
<?php 
    do_action('blogdata_action_header_type_section');
  $blogdata_enable_main_slider = blogdata_get_option('show_main_banner_section');
  $slider_position = get_theme_mod('main_slider_position', 'left') == 'left' ? '' : ' row-reverse';
if(is_home() || is_front_page()) {  
    if($blogdata_enable_main_slider){ ?>
        <!--mainfeatured start-->
        <div class="mainfeatured <?php if (!empty($main_banner_section_background_image)) { echo' over mt-0'; }?>" style="background-image: url('<?php echo esc_attr( get_main_banner_section_background_image_url() ); ?>')">
            <div class="featinner">
                <!--container-->
                <div class="container">
                    <!--row-->  
                    <div class="row gap-1 gap-md-0<?php echo esc_attr($slider_position)?>">
                        <?php do_action('blogdata_action_front_page_main_section_1'); ?>
                    </div><!--/row-->
                </div><!--/container-->
            </div>
        </div>
        <!--mainfeatured end-->
        <?php
    }
    if(is_active_sidebar('magazine-content')){
        get_template_part('sidebar','magazine');
    }
    do_action('blogdata_action_banner_advertisement');
} 
?>