<?php /*** Option Panel
 *
 * @package blogdata
 */


$blogdata_default = blogdata_get_default_theme_options();
/*theme option panel info*/

//Theme Layout
Blogdata_Customizer_Control::add_panel(
	'themes_layout',
	array(
		'title' => esc_html__( 'Theme Layout', 'blogdata' ), 
        'priority' => 12,
        'capability' => 'edit_theme_options',
	)
);
//Sidebar Layout
Blogdata_Customizer_Control::add_section(
	'blogdata_theme_sidebar_setting',
	array(
		'title' => esc_html__( 'Sidebar', 'blogdata' ), 
        'priority' => 11,
        'panel' => 'themes_layout',
	)
);
Blogdata_Customizer_Control::add_field(
	array(
		'type'     => 'hidden', 
        'settings'  => 'blogdata_archive_sidebar_width_heading',
        'label' => esc_html__('Archive Pages', 'blogdata'),
		'section'  => 'blogdata_theme_sidebar_setting',
        'sanitize_callback' => 'blogdata_sanitize_text',
	)
);
// Sidebar Width 
Blogdata_Customizer_Control::add_field(
	array(
		'type'     => 'number', 
        'settings'  => 'blogdata_archive_page_sidebar_width',
        'label' => esc_html__('Sidebar Width', 'blogdata'),
		'section'  => 'blogdata_theme_sidebar_setting',
        'sanitize_callback' => 'absint',
        'default' => '33',
        'input_attrs' => array(
            'min' => 10,
            'max' => 90,
            'step' => 1,
        )
	)
);
Blogdata_Customizer_Control::add_field( 
	array(
		'type'     => 'radio-image', 
        'settings'  => 'blogdata_archive_page_layout',
		'section'  => 'blogdata_theme_sidebar_setting',
        'transport'         => 'postMessage',
        'default' => $blogdata_default['blogdata_archive_page_layout'],
        'choices'   => array(
            'align-content-left' => get_template_directory_uri() . '/images/left-sidebar.png',  
            'full-width-content'    => get_template_directory_uri() . '/images/full-content.png',
            'align-content-right'    => get_template_directory_uri() . '/images/right-sidebar.png',
        ),
        'sanitize_callback' => 'blogdata_sanitize_radio',
	)
);
Blogdata_Customizer_Control::add_field(
	array(
		'type'     => 'hidden', 
        'settings' => 'blogdata_pro_single_page_heading',
        'label' => esc_html__('Single Page', 'blogdata'),
		'section'  => 'blogdata_theme_sidebar_setting',
        'sanitize_callback' => 'blogdata_sanitize_text',
	)
);
Blogdata_Customizer_Control::add_field( 
	array(
		'type'     => 'radio-image', 
        'settings'  => 'blogdata_single_page_layout',
		'section'  => 'blogdata_theme_sidebar_setting',
        'transport'         => 'postMessage',
        'default' => 'single-align-content-right',
        'choices'   => array(
            'single-align-content-left' => get_template_directory_uri() . '/images/left-sidebar.png',
            'single-full-width-content'    => get_template_directory_uri() . '/images/full-content.png',
            'single-align-content-right'    => get_template_directory_uri() . '/images/right-sidebar.png',
        ),
        'sanitize_callback' => 'blogdata_sanitize_radio',
	)
);
Blogdata_Customizer_Control::add_field(
	array(
		'type'     => 'hidden',
        'settings' => 'blogdata_page_heading',
        'label' => esc_html__('Pages', 'blogdata'),
		'section'  => 'blogdata_theme_sidebar_setting',
        'sanitize_callback' => 'blogdata_sanitize_text',
	)
);
Blogdata_Customizer_Control::add_field( 
	array(
		'type'     => 'radio-image', 
        'settings'  => 'blogdata_page_layout',
		'section'  => 'blogdata_theme_sidebar_setting',
		'transport'  => 'postMessage',
        'default' => 'page-align-content-right',
        'choices'   => array(
            'page-align-content-left' => get_template_directory_uri() . '/images/left-sidebar.png',
            'page-full-width-content'    => get_template_directory_uri() . '/images/full-content.png',
            'page-align-content-right'    => get_template_directory_uri() . '/images/right-sidebar.png',
        ),
        'sanitize_callback' => 'blogdata_sanitize_radio',
	)
);
// Blog Layout Setting
Blogdata_Customizer_Control::add_section(
	'blog_layout_section',
	array(
		'title' => esc_html__( 'Blog', 'blogdata' ),
        'capability' => 'edit_theme_options',
        'panel' => 'themes_layout',
	)
);
Blogdata_Customizer_Control::add_field(
	array(
		'type'     => 'hidden', 
        'settings' => 'blog_layout_title_settings',
        'label' => esc_html__('Blog', 'blogdata'),
		'section'  => 'blog_layout_section',
        'sanitize_callback' => 'blogdata_sanitize_text',
	)
);
Blogdata_Customizer_Control::add_field( 
	array(
		'type'     => 'radio-image', 
        'settings'  => 'blog_post_layout',
		'section'  => 'blog_layout_section',
        'default' => 'grid-layout',
        'choices'   => array(
            'grid-layout'    => get_template_directory_uri() . '/images/blog/grid-layout.png',
            'list-layout' => get_template_directory_uri() . '/images/blog/list-layout.png',
        ),
        'sanitize_callback' => 'blogdata_sanitize_radio',
	)
);