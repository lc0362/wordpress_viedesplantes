<?php
/*** Customizer Core Panel
 *
 * @package BlogData
 */

$blogdata_default = blogdata_get_default_theme_options();
// Adding customizer home page setting

// Add Background Settings Section
Blogdata_Customizer_Control::add_section(
    'background_image',
    array(
        'title' => esc_html__('Background Settings', 'blogdata'),
        'priority' => 35,
        'capability' => 'edit_theme_options',
	)
);
// Background Color Heading
Blogdata_Customizer_Control::add_field(
	array(
		'type'     => 'hidden', 
        'settings'  => 'body_bg_color_heading',
        'label' => esc_html__('Background Color', 'blogdata'),
        'section' => 'colors',
        'sanitize_callback' => 'blogdata_sanitize_text',
	)
);
$wp_customize->remove_control('background_color');
//Theme Background Color
Blogdata_Customizer_Control::add_field( 
	array(
		'type'     => 'color-alpha', 
        'settings'  => 'body_background_color',
        'label' => esc_html__('Background Color', 'blogdata'),
		'section'  => 'colors',
        'default' => $blogdata_default['body_background_color'],
        'sanitize_callback' => 'blogdata_sanitize_alpha_color',
	)
);