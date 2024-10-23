<?php function blogdata_scripts() {

	wp_enqueue_style('all-css',get_template_directory_uri().'/css/all.css');

	wp_enqueue_style('dark', get_template_directory_uri() . '/css/colors/dark.css');

	wp_enqueue_style('core', get_template_directory_uri() . '/css/core.css');
	
	wp_style_add_data('core', 'rtl', 'replace' );
	
	wp_enqueue_style('blogdata-style', get_stylesheet_uri() );
	
	wp_style_add_data('blogdata-style', 'rtl', 'replace' );
	
	wp_enqueue_style('wp-core', get_template_directory_uri() . '/css/wp-core.css');
	
	wp_enqueue_style('woocommerce-css', get_template_directory_uri() . '/css/woo.css');

	wp_enqueue_style('default', get_template_directory_uri() . '/css/colors/default.css');

	wp_enqueue_style('swiper-bundle-css', get_template_directory_uri() . '/css/swiper-bundle.css');
	
	wp_enqueue_style('menu-core-css', get_template_directory_uri() . '/css/sm-core-css.css');
	
	wp_enqueue_style('smartmenus',get_template_directory_uri().'/css/sm-clean.css');	 

	/* Js script */

	wp_enqueue_script( 'blogdata-navigation', get_template_directory_uri() . '/js/navigation.js', array('jquery'));

	wp_enqueue_script('swiper-bundle', get_template_directory_uri() . '/js/swiper-bundle.js', array('jquery'));

	wp_enqueue_script('sticky-js', get_template_directory_uri() . '/js/hc-sticky.js' , array('jquery'));

	wp_enqueue_script('sticky-header-js', get_template_directory_uri() . '/js/jquery.sticky.js' , array('jquery'));

	wp_enqueue_script('smartmenus-js', get_template_directory_uri() . '/js/jquery.smartmenus.js');

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	wp_enqueue_script('jquery-cookie', get_template_directory_uri() . '/js/jquery.cookie.min.js', array('jquery'));
}
add_action('wp_enqueue_scripts', 'blogdata_scripts');

function blogdata_admin_enqueue( $hook ) {

	wp_enqueue_script( 'media-upload' );

	wp_enqueue_media();

	wp_enqueue_style( 'blogdata-admin-style', get_template_directory_uri() . '/css/admin-style.css' );

}
add_action( 'admin_enqueue_scripts', 'blogdata_admin_enqueue' );

if ( ! function_exists( 'blogdata_admin_scripts' ) ) :
	function blogdata_admin_scripts() {
		wp_enqueue_script( 'blogdata-admin-script', get_template_directory_uri() . '/inc/ansar/customizer-admin/js/admin-script.js', array( 'jquery' ), '', true );
		wp_localize_script( 'blogdata-admin-script', 'blogdata_ajax_object',
			array( 'ajax_url' => admin_url( 'admin-ajax.php' ) )
		);
		wp_enqueue_style('blogdata-admin-style-css', get_template_directory_uri() . '/css/customizer-controls.css');
	}
	endif;
	add_action( 'admin_enqueue_scripts', 'blogdata_admin_scripts' );


//Custom Color
function blogdata_custom_js() {

	wp_enqueue_script('blogdata_custom-js', get_template_directory_uri() . '/js/custom.js' , array('jquery'));	
    
	wp_enqueue_script('blogdata-dark', get_template_directory_uri() . '/js/dark.js' , array('jquery'));

	theme_options_color();

	theme_options_dark_color();
}
add_action('wp_footer','blogdata_custom_js');


/**
 * Fix skip link focus in IE11.
 *
 * This does not enqueue the script because it is tiny and because it is only for IE11,
 * thus it does not warrant having an entire dedicated blocking script being loaded.
 *
 * @link https://git.io/vWdr2
 */
function blogdata_skip_link_focus_fix() {
	// The following is minified via `terser --compress --mangle -- js/skip-link-focus-fix.js`.
	?>
	<script>
	/(trident|msie)/i.test(navigator.userAgent)&&document.getElementById&&window.addEventListener&&window.addEventListener("hashchange",function(){var t,e=location.hash.substring(1);/^[A-z0-9_-]+$/.test(e)&&(t=document.getElementById(e))&&(/^(?:a|select|input|button|textarea)$/i.test(t.tagName)||(t.tabIndex=-1),t.focus())},!1);
	</script>
	<?php
}
add_action( 'wp_print_footer_scripts', 'blogdata_skip_link_focus_fix' );

function blogdata_customizer_scripts() {
	
	wp_enqueue_style( 'blogdata-customizer-styles', get_template_directory_uri() . '/css/customizer-controls.css' );

	wp_enqueue_style('blogdata-custom-controls-css', get_template_directory_uri() . '/inc/ansar/customize/css/customizer.css');
}
add_action( 'customize_controls_print_footer_scripts', 'blogdata_customizer_scripts' );