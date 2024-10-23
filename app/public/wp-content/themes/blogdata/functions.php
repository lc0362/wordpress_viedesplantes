<?php define( 'BLOGDATA_THEME_DIR', get_template_directory() . '/' );
	define( 'BLOGDATA_THEME_URI', get_template_directory_uri() . '/' );
	
	$blogdata_theme_path = get_template_directory() . '/inc/ansar/';

	require( $blogdata_theme_path . '/custom-navwalker-class.php' );
	require( $blogdata_theme_path . '/default_menu_walker.php' );
	require( $blogdata_theme_path . '/font/font.php');
	require( $blogdata_theme_path . '/template-tags.php');
	require( $blogdata_theme_path . '/template-functions.php');
	require( $blogdata_theme_path . '/widgets/widgets-common-functions.php');
	require( $blogdata_theme_path . '/custom-control/custom-control.php');
	require( $blogdata_theme_path . '/custom-control/font/font-control.php');
	require_once get_template_directory() . '/inc/ansar/customizer-admin/admin-plugin-install.php';
	require_once( trailingslashit( get_template_directory() ) . 'inc/ansar/customize-pro/class-customize.php' );

	// Theme version.
	$blogdata_theme = wp_get_theme();
	define( 'BLOGDATA_THEME_VERSION', $blogdata_theme->get( 'Version' ) );
	define( 'BLOGDATA_THEME_NAME'   , $blogdata_theme->get( 'Name' ) );

	/*-----------------------------------------------------------------------------------*/
	/*	Enqueue scripts and styles.
	/*-----------------------------------------------------------------------------------*/
	require( $blogdata_theme_path .'/enqueue.php');
	/* ----------------------------------------------------------------------------------- */
	/* Customizer Layout*/
	/* ----------------------------------------------------------------------------------- */
	require( $blogdata_theme_path . '/custom-control/customize_layout.php');
	/* ----------------------------------------------------------------------------------- */
	/* Customizer */
	/* ----------------------------------------------------------------------------------- */
	require( $blogdata_theme_path . '/customize/customizer.php');

	// Load customize control class.
	require get_template_directory().'/inc/ansar/customize/customize-control-class.php';

	/* ----------------------------------------------------------------------------------- */
	/* Customizer */
	/* ----------------------------------------------------------------------------------- */

	require( $blogdata_theme_path  . '/widgets/widgets-init.php');

	/* ----------------------------------------------------------------------------------- */
	/* Widget */
	/* ----------------------------------------------------------------------------------- */

	require( $blogdata_theme_path  . '/hooks/hooks-init.php');

	/* custom-color file. */
	require( get_template_directory() . '/css/colors/theme-options-color.php');

	/* custom-dark-color file. */
	require( get_template_directory() . '/css/colors/theme-options-dark-color.php');


if ( ! function_exists( 'blogdata_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function blogdata_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on blogdata, use a find and replace
	 * to change 'blogdata' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'blogdata', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	// Add featured image sizes
        add_image_size('blogdata-slider-full', 1280, 720, true); // width, height, crop
        add_image_size('blogdata-featured', 1024, 0, false ); // width, height, crop
        add_image_size('blogdata-medium', 720, 380, true); // width, height, crop

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'blogdata' ),
        'footer' => __( 'Footer Menu', 'blogdata' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	$args = array(
    'default-color' => '#eee',
    'default-image' => '',
	);
	add_theme_support( 'custom-background', $args );

    // Set up the woocommerce feature.
    add_theme_support( 'woocommerce');

	// Woocommerce Gallery Support
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );

    // Added theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );
	
	/* Add theme support for gutenberg block */
	add_theme_support( 'align-wide' );
	
	/* Add theme support for responsive embeds */
	add_theme_support( 'responsive-embeds' );

	//Custom logo
	add_theme_support( 'custom-logo');
	
	// custom header Support
	$args = array(
		'default-image'		=> '',
		'width'			=> '1600',
		'height'		=> '600',
		'flex-height'		=> false,
		'flex-width'		=> false,
		'header-text'		=> true,
		'default-text-color'	=> '000',
		'wp-head-callback'       => 'blogdata_header_color',
	);
	add_theme_support( 'custom-header', $args );

	/*
     * Enable support for Post Formats on posts and pages.
     *
     * @link https://developer.wordpress.org/themes/functionality/post-formats/
     */
    add_theme_support( 'post-formats', array( 'image', 'video', 'gallery', 'audio' ) );

	// Enable default block styles for Gutenberg blocks
	add_theme_support( 'wp-block-styles' );

	//Editor Styling 
	add_editor_style( array( 'css/editor-style.css') );
}
endif;
add_action( 'after_setup_theme', 'blogdata_setup' );

add_action( 'after_switch_theme', 'blogdata_theme_activation');

function blogdata_theme_activation(){
	$custom_posts = get_posts(
		array(
			'post_type' => 'post', 
			'numberposts' => -1
		)
	);       

	foreach ($custom_posts as $post) {         
		update_post_meta($post->ID, 'post_image_type', 'list-blog');
	}
}

function blogdata_the_custom_logo() {

	if ( function_exists( 'the_custom_logo' ) ) {
		the_custom_logo();
	}
}
add_filter('get_custom_logo','blogdata_logo_class');

function blogdata_logo_class($html) {
	$html = str_replace('custom-logo-link', 'navbar-brand', $html);
	return $html;
}

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function blogdata_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'blogdata_content_width', 640 );
}
add_action( 'after_setup_theme', 'blogdata_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function blogdata_widgets_init() {
	
	$blogdata_footer_column_layout = esc_attr(get_theme_mod('blogdata_footer_column_layout',3));
	
	$blogdata_footer_column_layout = 12 / $blogdata_footer_column_layout;

	register_sidebar( array(
		'name'          => esc_html__( 'Header Toggle Sidebar', 'blogdata' ),
		'id'            => 'menu-sidebar-content',
		'description'   => '',
		'before_widget' => '<div id="%1$s" class="bs-widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<div class="bs-widget-title one"><h2 class="title">',
		'after_title'   => '</h2></div>',
	) );
	
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar Widget Area', 'blogdata' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<div id="%1$s" class="bs-widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<div class="bs-widget-title one"><h2 class="title">',
		'after_title'   => '</h2></div>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Footer Widget Area', 'blogdata' ),
		'id'            => 'footer_widget_area',
		'description'   => '',
		'before_widget' => '<div id="%1$s" class="col-lg-'.$blogdata_footer_column_layout.' col-sm-6 rotateInDownLeft animated bs-widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<div class="bs-widget-title one"><h2 class="title">',
		'after_title'   => '</h2></div>',
	) );

}
add_action( 'widgets_init', 'blogdata_widgets_init' );

/*-----------------------------------------------------------------------------------*/
/*  custom background
/*-----------------------------------------------------------------------------------*/
function blogdata_custom_background_function(){
	$page_bg_image_url = get_theme_mod('blogdata_default_bg_image','');
	if($page_bg_image_url!=''){
		echo '<style>body{ background-image:url("'.get_template_directory_uri().'/images/bg-pattern/'.$page_bg_image_url.'");}</style>';
	}
}
add_action('wp_head','blogdata_custom_background_function',10,0);

/* Category Js */
if(!function_exists('blogdata_category_js')):
    function blogdata_category_js(){
        if( null !== ( $screen = get_current_screen() ) && 'edit-category' !== $screen->id ) {
            return;
        }
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'wp-color-picker' );
    }
endif;
add_action( 'admin_enqueue_scripts', 'blogdata_category_js' );

if(!function_exists('blogdata_colorpicker_init_inline')):
    function blogdata_colorpicker_init_inline() {
        if( null !== ( $screen = get_current_screen() ) && 'edit-category' !== $screen->id ) {
            return;
        }
        ?>
        <script>
            jQuery( document ).ready( function( $ ) {
                jQuery( '.colorpicker' ).wpColorPicker();
            } );

        </script>
        <?php
    }
endif;
add_action( 'admin_print_scripts', 'blogdata_colorpicker_init_inline', 999 );

function blogdata_enqueue_color_picker() {
    wp_enqueue_style('wp-color-picker');
    wp_enqueue_script('wp-color-picker');
	wp_enqueue_script('blogdata-widget', get_template_directory_uri() . '/js/widget.js', array('jquery', 'wp-color-picker'), '1.0', true);
}

add_action('admin_print_scripts', 'blogdata_enqueue_color_picker');

function blogdata_customizer_script() {
    wp_enqueue_script( 'customizer-script', get_template_directory_uri() . '/inc/ansar/customize/js/customizer.js', array( 'jquery', 'customize-controls' ), '', true );
}
add_action( 'customize_controls_enqueue_scripts', 'blogdata_customizer_script' );

/**
 * Enqueue User Custom styles.
 */
if( ! function_exists( 'blogdata_range_style' ) ):
    function blogdata_range_style() {

		$blogdata_range_output = '';
		
		$blogdata_range_output   .= blogdata_customizer_value( 'site_title_font_size', '.site-branding-text .site-title a', array( 'font-size' ), array( 40, 35, 30 ), 'px' );
		$blogdata_range_output   .= blogdata_customizer_value( 'side_main_logo_width', '.bs-menu-full .navbar-brand img', array( 'width' ), array( 250, 200, 150 ), 'px' );
		
		$blogdata_range_output   .= blogdata_customizer_value( 'header_image_height', '.header-image-section .overlay', array( 'height' ), array( 200, 150, 130 ), 'px' );
		
		$blogdata_range_output   .= blogdata_customizer_value( 'blogdata_slider_title_font_size', '.bs-slide .inner .title', array( 'font-size' ), array( 38, 32, 24 ), 'px !important' );
		$blogdata_range_output   .= blogdata_customizer_value( 'blogdata_tren_edit_title_font_size', '.multi-post-widget .bs-blog-post.three.bsm .title', array( 'font-size' ), array( 22, 20, 16 ), 'px !important' );
		$blogdata_range_output   .= blogdata_customizer_value( 'blogdata_footer_main_logo_width', 'footer .bs-footer-bottom-area .custom-logo, footer .bs-footer-copyright .custom-logo', array( 'width' ), array( 210, 170, 130 ), 'px' );
		$blogdata_range_output   .= blogdata_customizer_value( 'blogdata_footer_main_logo_height', 'footer .bs-footer-bottom-area .custom-logo, footer .bs-footer-copyright .custom-logo', array( 'height' ), array( 70, 50, 40 ), 'px' );
        
        wp_add_inline_style( 'blogdata-style', $blogdata_range_output );
    }
endif;
add_action( 'wp_enqueue_scripts', 'blogdata_range_style' );