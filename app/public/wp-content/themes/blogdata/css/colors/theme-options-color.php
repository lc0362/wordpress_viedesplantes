<?php
function theme_options_color() {

	// /*=================== Blog Post Color ===================*/	
	?>
<style type="text/css"> 
	:root {
		--wrap-color: <?php echo esc_attr(blogdata_get_option('body_background_color')); ?>;
	}
/*==================== Site title and tagline ====================*/
.site-title a, .site-description{
  color: <?php echo esc_attr(get_theme_mod('header_text_color', "000")); ?>;
}
body.dark .site-title a, body.dark .site-description{
  color: <?php echo esc_attr(get_theme_mod('header_text_dark_color', "#fff")); ?>;
}
/*=================== Blog Post Color ===================*/
.header-six .header-middle{
	background: <?php echo esc_attr(get_theme_support( 'custom-header', 'default-image' ))?>;
}
.header-six .header-middle .overlay{
	background-color: <?php echo esc_attr(get_theme_mod('blogdata_header_overlay_color'))?>;
}
.site-branding-text .site-title a:hover{
  color: <?php echo esc_attr(get_theme_mod('header_text_color_on_hover','#a90e6d'))?> !important;
}
body.dark .site-branding-text .site-title a:hover{
  color: <?php echo esc_attr(get_theme_mod('header_text_dark_color_on_hover','#a90e6d'))?> !important;
}
/* Footer Color*/
footer .bs-footer-copyright {
  background: <?php echo blogdata_get_option('blogdata_footer_copy_bg'); ?>;
}
footer .bs-widget p, footer .site-title a, footer .site-title a:hover , footer .site-description, footer .site-description:hover, footer .bs-widget h6, footer .mg_contact_widget .bs-widget h6 {
  color: <?php echo blogdata_get_option('blogdata_footer_text_color'); ?>;
}
footer .bs-footer-copyright p, footer .bs-footer-copyright a {
  color: <?php echo blogdata_get_option('blogdata_footer_copy_text'); ?>;
}
@media (min-width: 992px) {
    
    .archive-class .sidebar-right, .archive-class .sidebar-left , .index-class .sidebar-right, .index-class .sidebar-left{
      flex: 100;
      max-width:<?php echo esc_attr(get_theme_mod('blogdata_archive_page_sidebar_width')); ?>% !important;
    }
    .archive-class .content-right , .index-class .content-right {
      max-width: calc((100% - <?php echo esc_attr(get_theme_mod('blogdata_archive_page_sidebar_width')); ?>%)) !important;
    }
  }
</style>
<?php
$enable_blogdata_typo = get_theme_mod('enable_blogdata_typo', false);
if ($enable_blogdata_typo == true) { ?>
<style>
/* Headings Typography*/
h1, .h1, h2, .h2, h3, .h3, h4, .h4, h5, .h5, h6, .h6, .wp-block-search__label  {
  font-weight:<?php echo blogdata_get_option('heading_fontweight'); ?> !important;
  font-family:<?php echo blogdata_get_option('heading_fontfamily'); ?> !important;
}
/* Menus Font Family*/
.navbar-wp .sm-clean > li > a, .navbar-wp .dropdown-menu > li > a{ 
  font-family:<?php echo esc_attr(get_theme_mod('blogdata_menu_fontfamily','Inter'))?> !important; 
}
</style>
<?php }
}