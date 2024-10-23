<?php
if( ! function_exists( 'blogdata_register_custom_controls' ) ) :
/**
 * Register Custom Controls
*/
function blogdata_register_custom_controls( $wp_customize ) {

    require_once get_template_directory() . '/inc/ansar/custom-control/toggle/class-toggle-control.php';
    require_once get_template_directory() . '/inc/ansar/custom-control/customizer-alpha-color-picker/class-customize-alpha-color-control.php';
    require_once get_template_directory() . '/inc/ansar/custom-control/custom_slider_control/custom_slider_control_class.php';
    require_once get_template_directory() . '/inc/ansar/custom-control/custom-sortable-control/custom-sortable-control-class.php';
    require_once get_template_directory() . '/inc/ansar/custom-control/custom_tab_control/custom_tab_control_class.php';

    $wp_customize->register_control_type( 'Blogdata_Toggle_Control' );
    $wp_customize->register_control_type( 'Blogdata_Sortable_Control' );
    $wp_customize->register_control_type( 'Blogdata_Customizer_Range_Control' );

}
endif;
add_action( 'customize_register', 'blogdata_register_custom_controls' );