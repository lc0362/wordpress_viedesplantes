<?php
$blogdata_default = blogdata_get_default_theme_options(); 
/**
 * Frontpage options section
 *
 * @package BlogData
 */

// Main banner Slider Section.
Blogdata_Customizer_Control::add_section(
	'frontpage_main_banner_section_settings',
	array(
		'title' => esc_html__( 'Featured Slider', 'blogdata' ),  
        'priority' => 15,
        'capability' => 'edit_theme_options',
	)
);

// Featured Slider Tab
$wp_customize->add_setting(
    'slider_tabs',
    array(
        'default'           => '',
        'capability' => 'edit_theme_options', 
        'sanitize_callback' => 'sanitize_text_field',
    )
);
$wp_customize->add_control( new Custom_Tab_Control ( $wp_customize,'slider_tabs',
    array(
        'label'                 => '',
        'type' => 'custom-tab-control',
        'priority' => 1,
        'section'               => 'frontpage_main_banner_section_settings',
        'controls_general'      => json_encode( array( 
            '#customize-control- ',
            '#customize-control-show_main_banner_section', 
            '#customize-control- ', 
            '#customize-control-main_slider_position', 
            '#customize-control- ', 
            '#customize-control-main_banner_section_background_image',
            '#customize-control- ',
            '#customize-control- ', 
            '#customize-control-main_slider_section_title', 
            '#customize-control-select_slider_news_category',
            '#customize-control- ',  
            '#customize-control- ',  
            '#customize-control- ',  
            '#customize-control-main_trending_post_section_title', 
            '#customize-control- ', 
            '#customize-control- ', 
            '#customize-control-select_trending_news_category',
            '#customize-control-main_editor_post_section_title', 
            '#customize-control-select_editor_news_category',
            '#customize-control- ',
        ) ),
        'controls_design'       => json_encode( array(  
            '#customize-control-main_slider_section_title', 
            '#customize-control- ',
            '#customize-control- ', 
            '#customize-control- ', 
            '#customize-control-blogdata_slider_title_font_size',
            '#customize-control- ',
            '#customize-control- ',
            '#customize-control-slider_meta_enable',
            '#customize-control-tren_edit_section_title',
            '#customize-control-blogdata_tren_edit_title_font_size',
        ) ),
    )
));
//Slider Section title 
Blogdata_Customizer_Control::add_field(
	array(
		'type'      => 'hidden', 
        'settings'  => 'main_slider_section_title',
        'label' => esc_html__('Featured Slider', 'blogdata'),
		'section'   => 'frontpage_main_banner_section_settings',
	)
);
// Setting - show_main_banner_section.
Blogdata_Customizer_Control::add_field( 
	array(
		'type'     => 'toggle', 
        'settings'  => 'show_main_banner_section',
        'label' => esc_html__('Enable/Disable Banner', 'blogdata'),
		'section'  => 'frontpage_main_banner_section_settings',
        'default' => true,
        'sanitize_callback' => 'blogdata_sanitize_checkbox',
	)
);
// Slider Position
Blogdata_Customizer_Control::add_field( 
	array(
		'type'     => 'select', 
        'settings'  => 'main_slider_position',
        'label' => esc_html__('Slider Position', 'blogdata'),
		'section'  => 'frontpage_main_banner_section_settings',
        'default' => 'left',
        'choices'   => array(
            'left' => esc_html__( 'Left', 'blogdata' ),
            'right' => esc_html__( 'Right', 'blogdata' ),
        ),
        'sanitize_callback' => 'blogdata_sanitize_select',
        'active_callback' => 'blogdata_main_banner_section_status',
	)
);
// Setting main_banner_section_background_image.
Blogdata_Customizer_Control::add_field( 
	array(
		'type'     => 'cropped_image', 
        'settings'  => 'main_banner_section_background_image',
        'label' => esc_html__('Background image', 'blogdata'),
        'description' => sprintf(esc_html__('Recommended Size %1$s px X %2$s px', 'blogdata'), 1200, 720),
		'section'  => 'frontpage_main_banner_section_settings',
        'default' => '',
        'width' => 1200,
        'height' => 720,
        'flex_width' => true,
        'flex_height' => true,
        'sanitize_callback' => 'absint', 
        'active_callback' => 'blogdata_main_banner_section_status'
	)
);
// Setting - drop down category for slider.
Blogdata_Customizer_Control::add_field(
	array(
		'type'     => 'taxonomies', 
        'settings'  => 'select_slider_news_category',
        'label' => esc_html__('Select Category', 'blogdata'),
        'description' => esc_html__('Posts to be shown on banner slider section', 'blogdata'),
		'section'  => 'frontpage_main_banner_section_settings',
        'taxonomy' => 'category', 
        'default' => $blogdata_default['select_slider_news_category'],
        'sanitize_callback' => 'absint', 
        'active_callback' => 'blogdata_main_banner_section_status'
	)
);
//Trending Post Section title
Blogdata_Customizer_Control::add_field(
	array(
		'type'     => 'hidden',
        'settings'  => 'main_trending_post_section_title',
        'label' => esc_html__('Trending Post Section', 'blogdata'),
		'section'  => 'frontpage_main_banner_section_settings',
        'sanitize_callback' => 'blogdata_sanitize_text',
        'active_callback' => 'blogdata_main_banner_section_status',
	)
);
// Setting - drop down category for slider.
Blogdata_Customizer_Control::add_field(
	array(
		'type'     => 'taxonomies', 
        'settings'  => 'select_trending_news_category',
        'label' => esc_html__('Select Category', 'blogdata'),
        'description' => esc_html__('Posts to be shown on banner slider section', 'blogdata'),
		'section'  => 'frontpage_main_banner_section_settings',
        'taxonomy' => 'category', 
        'default' => $blogdata_default['select_trending_news_category'],
        'sanitize_callback' => 'absint', 
        'active_callback' => 'blogdata_main_banner_section_status'
	)
);
//Editor Post Section
//Trending Post Section title
Blogdata_Customizer_Control::add_field(
	array(
		'type'     => 'hidden',
        'settings'  => 'main_editor_post_section_title',
        'label' => esc_html__('Editor Post Section', 'blogdata'),
		'section'  => 'frontpage_main_banner_section_settings',
        'sanitize_callback' => 'blogdata_sanitize_text',
        'active_callback' => 'blogdata_main_banner_section_status',
	)
);
// Setting - drop down category for slider.
Blogdata_Customizer_Control::add_field(
	array(
		'type'     => 'taxonomies', 
        'settings'  => 'select_editor_news_category',
        'label' => esc_html__('Select Category', 'blogdata'),
        'description' => esc_html__('Posts to be shown on banner slider section', 'blogdata'),
		'section'  => 'frontpage_main_banner_section_settings',
        'taxonomy' => 'category', 
        'default' => $blogdata_default['select_editor_news_category'],
        'sanitize_callback' => 'absint', 
        'active_callback' => 'blogdata_main_banner_section_status'
	)
);
// STYLE
// Slider Title Font Size
Blogdata_Customizer_Control::add_field( 
	array(
		'type'     => 'blogdata-range', 
        'settings'  => 'blogdata_slider_title_font_size',
        'label' => esc_html__('Title Font Size', 'blogdata'),
		'section'  => 'frontpage_main_banner_section_settings',
        'transport'   => 'postMessage',
        'media_query'   => true,
        'input_attr'    => array(
            'mobile'  => array(
                'min'           => 0,
                'max'           => 300,
                'step'          => 1,
                'default_value' => 24,
            ),
            'tablet'  => array(
                'min'           => 0,
                'max'           => 300,
                'step'          => 1,
                'default_value' => 32,
            ),
            'desktop' => array(
                'min'           => 0,
                'max'           => 300,
                'step'          => 1,
                'default_value' => 38,
            ),
        ),
        // 'active_callback' => 'blogdata_main_banner_section_status',
    ),
);
// Hide / Show Author,Date,Comment
Blogdata_Customizer_Control::add_field( 
	array(
		'type'     => 'toggle', 
        'settings'  => 'slider_meta_enable',
        'label' => esc_html__('Hide/Show Meta', 'blogdata'),
		'section'  => 'frontpage_main_banner_section_settings',
        'default' => true,
        'sanitize_callback' => 'blogdata_sanitize_checkbox',
        // 'active_callback' => 'blogdata_main_banner_section_status',
	)
);
//Trending/Editor Section title
Blogdata_Customizer_Control::add_field(
	array(
		'type'     => 'hidden', 
        'settings'  => 'tren_edit_section_title',
        'label' => esc_html__('Trending/Editor Post Section', 'blogdata'),
		'section'  => 'frontpage_main_banner_section_settings',
        'sanitize_callback' => 'blogdata_sanitize_text',
        // 'active_callback' => 'blogdata_main_banner_section_status',
	)
); 
// Trending/Editor Title Font Size
Blogdata_Customizer_Control::add_field( 
	array(
		'type'     => 'blogdata-range', 
        'settings'  => 'blogdata_tren_edit_title_font_size',
        'label' => esc_html__('Title Font Size', 'blogdata'),
		'section'  => 'frontpage_main_banner_section_settings',
        'transport'   => 'postMessage',
        'media_query'   => true,
        'input_attr'    => array(
            'mobile'  => array(
                'min'           => 0,
                'max'           => 300,
                'step'          => 1,
                'default_value' => 16,
            ),
            'tablet'  => array(
                'min'           => 0,
                'max'           => 300,
                'step'          => 1,
                'default_value' => 20,
            ),
            'desktop' => array(
                'min'           => 0,
                'max'           => 300,
                'step'          => 1,
                'default_value' => 22,
            ),
        ),
        // 'active_callback' => 'blogdata_main_banner_section_status',
    ),
);