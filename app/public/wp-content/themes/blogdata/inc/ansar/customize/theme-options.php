<?php /*** Option Panel
 *
 * @package BlogData
 */

$blogdata_default = blogdata_get_default_theme_options();
/**
 * 
 * This class incorporates code from the Kirki Customizer Framework and from a tutorial
 * written by Otto Wood.
 * 
 * The Kirki Customizer Framework, Copyright Aristeides Stathopoulos (@aristath),
 * is licensed under the terms of the GNU GPL, Version 2 (or later).
 * 
 * @link https://github.com/reduxframework/kirki/
 * @link http://ottopress.com/2012/making-a-custom-control-for-the-theme-customizer/
 */
//========== Add General Options Panel. ===============
Blogdata_Customizer_Control::add_panel(
	'theme_option_panel',
	array(
		'title' => esc_html__('Theme Options', 'blogdata'),
        'priority' => 7,
        'capability' => 'edit_theme_options',
	)
);
//Breadcrumb Settings
Blogdata_Customizer_Control::add_section(
	'blogdata_breadcrumb_settings',
	array(
		'title' => esc_html__( 'Breadcrumb', 'blogdata' ),
        'panel' => 'theme_option_panel',
        'capability' => 'edit_theme_options',
	)
);

// Hide/Show Breadcrumb
Blogdata_Customizer_Control::add_field( 
	array(
		'type'     => 'toggle', 
        'settings'  => 'breadcrumb_settings',
        'label' => esc_html__('Hide/Show Breadcrumb', 'blogdata'),
		'section'  => 'blogdata_breadcrumb_settings',
        'default' => true,
        'sanitize_callback' => 'blogdata_sanitize_checkbox',
	)
);
//Type Of Bredcrumb 
Blogdata_Customizer_Control::add_field( 
	array(
		'type'     => 'select', 
        'settings'  => 'blogdata_site_breadcrumb_type',
        'label' => esc_html__('Breadcrumb Type', 'blogdata'),
		'section'  => 'blogdata_breadcrumb_settings',
        'default' => 'default',
        'description' => esc_html__( 'If you use other than "default" one you will need to install and activate respective plugins Breadcrumb NavXT, Yoast SEO and Rank Math SEO', 'blogdata' ),
        'choices'   => array(
            'default' => __( 'Default', 'blogdata' ),
            'navxt'  => __( 'NavXT', 'blogdata' ),
            'yoast'  => __( 'Yoast SEO', 'blogdata' ),
            'rankmath'  => __( 'Rank Math', 'blogdata' )
        ),
        'sanitize_callback' => 'blogdata_sanitize_select',
	)
);
// Social Icon Setting
Blogdata_Customizer_Control::add_section(
	'social_icon_options',
	array(
		'title' => esc_html__( 'Social Icon', 'blogdata' ), 
        'panel' => 'theme_option_panel',
        'capability' => 'edit_theme_options',
	)
);
//Enable and disable social icon
Blogdata_Customizer_Control::add_field( 
	array(
		'type'     => 'toggle', 
        'settings'  => 'footer_social_icon_enable',
        'label' => esc_html__('Enable Footer Social Icon', 'blogdata'),
		'section'  => 'social_icon_options',
        'priority' => 103,
        'default' => true,
        'sanitize_callback' => 'blogdata_sanitize_checkbox',
	)
);
// Social Icon Repaeter
$wp_customize->add_setting(
    'blogdata_social_icons',
    array(
        'default'           => blogdata_get_social_icon_default(),
        'sanitize_callback' => 'blogdata_repeater_sanitize'
    )
);
$wp_customize->add_control(
    new blogdata_Repeater_Control(
        $wp_customize,
        'blogdata_social_icons',
        array(
            'label'                            => esc_html__( 'Social Icons', 'blogdata' ),
            'section'                          => 'social_icon_options',
            'priority'                         =>  104,
            'add_field_label'                  => esc_html__( 'Add New Social', 'blogdata' ),
            'item_name'                        => esc_html__( 'Social', 'blogdata' ),
            'customizer_repeater_icon_control' => true,
            'customizer_repeater_link_control' => true,
            'customizer_repeater_checkbox_control' => true,
        )
    )
);
Blogdata_Customizer_Control::add_field( 
	array(
		'type'     => 'pro-text', 
        'settings'  => 'footer_social_icon_pro',
		'section'  => 'social_icon_options',
        'priority' => 153,
        'default' => '',
        'sanitize_callback' => 'blogdata_sanitize_checkbox',
	)
);
// Post Image Section
Blogdata_Customizer_Control::add_section(
	'post_image_options',
	array(
		'title' => esc_html__( 'Post Image', 'blogdata' ), 
        'panel' => 'theme_option_panel',
        'capability' => 'edit_theme_options',
	)
);

// Post Image Type
Blogdata_Customizer_Control::add_field( 
	array(
		'type'     => 'select', 
        'settings'  => 'post_image_type',
        'label' => esc_html__('Post Image display type:', 'blogdata'),
		'section'  => 'post_image_options',
        'default' => 'blogdata_post_img_hei',
        'choices'   => array(
            'blogdata_post_img_hei' => esc_html__( 'Fix Height Post Image', 'blogdata' ),
            'blogdata_post_img_acc' => esc_html__( 'Auto Height Post Image', 'blogdata' ),
        ),
        'sanitize_callback' => 'blogdata_sanitize_select',
	)
);
//404 Page Section
Blogdata_Customizer_Control::add_section(
	'404_options',
	array(
		'title' => esc_html__( '404 Page', 'blogdata' ), 
        'panel' => 'theme_option_panel',
        'capability' => 'edit_theme_options',
	)
);
// 404 page title
Blogdata_Customizer_Control::add_field(
	array(
		'type'     => 'text', 
        'settings'  => 'blogdata_404_title',
        'label' => esc_html__('Title', 'blogdata'),
        'default' => esc_html__('Oops! Page not found','blogdata'),
		'section'  => '404_options',
        'sanitize_callback' => 'sanitize_text_field',
	)
);
// 404 page desc
Blogdata_Customizer_Control::add_field(
	array(
		'type'     => 'textarea', 
        'settings'  => 'blogdata_404_desc',
        'label' => esc_html__('Description', 'blogdata'),
        'default' => esc_html__('We are sorry, but the page you are looking for does not exist.','blogdata'),
		'section'  => '404_options',
        'sanitize_callback' => 'sanitize_text_field',
	)
);
// 404 page btn title
Blogdata_Customizer_Control::add_field(
	array(
		'type'     => 'text', 
        'settings'  => 'blogdata_404_btn_title',
        'label' => esc_html__('Button Title', 'blogdata'),
        'default' => esc_html__('Go Back','blogdata'),
		'section'  => '404_options',
        'sanitize_callback' => 'sanitize_text_field',
	)
);
// Blog Page Section.
Blogdata_Customizer_Control::add_section(
	'site_post_date_author_settings',
	array(
		'title' => esc_html__( 'Blog Page', 'blogdata' ), 
        'panel' => 'theme_option_panel',
        'capability' => 'edit_theme_options',
	)
);
// blog Page heading
Blogdata_Customizer_Control::add_field(
	array(
		'type'     => 'hidden', 
        'settings'  => 'blogdata_blog_page_heading',
        'label' => esc_html__('Blog Post', 'blogdata'),
		'section'  => 'site_post_date_author_settings',
        'sanitize_callback' => 'blogdata_sanitize_text',
	)
);                                            
// Settings = Drop Caps
Blogdata_Customizer_Control::add_field( 
	array(
		'type'     => 'toggle', 
        'settings'  => 'blogdata_drop_caps_enable',
        'label' => esc_html__('Drop Caps (First Big Letter)', 'blogdata'),
		'section'  => 'site_post_date_author_settings',
        'default' => false,
        'sanitize_callback' => 'blogdata_sanitize_checkbox',
	)
);

// blog Page category
Blogdata_Customizer_Control::add_field( 
	array(
		'type'     => 'toggle', 
        'settings'  => 'blogdata_blog_post_category',
        'label' => esc_html__('Category', 'blogdata'),
		'section'  => 'site_post_date_author_settings',
        'default' => $blogdata_default['blogdata_blog_post_category'],
        'sanitize_callback' => 'blogdata_sanitize_checkbox',
	)
);
// blog Page meta
Blogdata_Customizer_Control::add_field( 
	array(
		'type'     => 'toggle', 
        'settings'  => 'blogdata_enable_post_meta',
        'label' => esc_html__('Post Meta', 'blogdata'),
		'section'  => 'site_post_date_author_settings',
        'default' => $blogdata_default['blogdata_enable_post_meta'],
        'sanitize_callback' => 'blogdata_sanitize_checkbox',
	)
);
Blogdata_Customizer_Control::add_field(
	array(
		'type'      => 'hidden', 
        'settings'  => 'blogdata_post_meta_heading',
        'label' => esc_html__('Post Meta', 'blogdata'),
		'section'   => 'site_post_date_author_settings',
	)
);        
// Blog Post Meta
Blogdata_Customizer_Control::add_field( 
	array(
		'type'     => 'blogdata-sortable', 
        'settings'  => 'blogdata_blog_post_meta',
		'section'  => 'site_post_date_author_settings',
        'default'    => array(
            'author',
            'date',
        ),
        'choices'    => array(
            'author'      => esc_attr__( 'Author', 'blogdata' ),
            'date'        => esc_attr__( 'Date', 'blogdata' ),
            'comments'    => esc_attr__( 'Comments', 'blogdata' ),
        ),
        // 'sanitize_callback' => 'blogdata_sanitize_alpha_color',
	)
);
Blogdata_Customizer_Control::add_field(
	array(
		'type'      => 'hidden', 
        'settings'  => 'blogdata_blog_content_settings',
        'label' => esc_html__('Choose Content Option', 'blogdata'),
		'section'   => 'site_post_date_author_settings',
	)
); 
Blogdata_Customizer_Control::add_field(
	array(
		'type'     => 'radio', 
        'settings'  => 'blogdata_blog_content',
        'default'  => $blogdata_default['blogdata_blog_content'],
		'section'  => 'site_post_date_author_settings',
        'choices'   =>  array(
            'excerpt'   => __('Excerpt', 'blogdata'),
            'content'   => __('Full Content', 'blogdata'),
        ),
        'sanitize_callback' => 'blogdata_sanitize_select',
	)
);
Blogdata_Customizer_Control::add_field(
	array(
		'type'      => 'hidden',
        'settings'  => 'blogdata_post_pagination_heading',
        'label' => esc_html__('Pagination', 'blogdata'),
		'section'   => 'site_post_date_author_settings',
	)
);
Blogdata_Customizer_Control::add_field( 
	array(
		'type'     => 'select', 
        'settings'  => 'blogdata_post_blog_pagination',
		'section'  => 'site_post_date_author_settings',
        'default' => 'number',
        'choices'   => array(
            'next_prev'   => __('Next-Prev', 'blogdata'),
            'number'   => __('Numbers', 'blogdata'),
        ),
        'sanitize_callback' => 'blogdata_sanitize_select',
	)
);
//========== single posts options ===============
// Single Section.
Blogdata_Customizer_Control::add_section(
	'site_single_posts_settings',
	array(
		'title' => esc_html__( 'Single Page', 'blogdata' ), 
        'panel' => 'theme_option_panel',
	)
);
// Single Page heading
Blogdata_Customizer_Control::add_field(
	array(
		'type'     => 'hidden', 
        'settings'  => 'blogdata_single_page_heading',
        'label' => esc_html__('Single Post', 'blogdata'),
		'section'  => 'site_single_posts_settings',
        'sanitize_callback' => 'blogdata_sanitize_text',
	)
);
// Single Page category
Blogdata_Customizer_Control::add_field( 
	array(
		'type'     => 'toggle', 
        'settings'  => 'blogdata_single_post_category',
        'label' => esc_html__('Category', 'blogdata'),
		'section'  => 'site_single_posts_settings',
        'default' => $blogdata_default['blogdata_single_post_category'],
        'sanitize_callback' => 'blogdata_sanitize_checkbox',
	)
);
// Single Page meta
Blogdata_Customizer_Control::add_field( 
	array(
		'type'     => 'toggle', 
        'settings'  => 'blogdata_single_post_meta',
        'label' => esc_html__('Post Meta', 'blogdata'),
		'section'  => 'site_single_posts_settings',
        'default' => $blogdata_default['blogdata_single_post_meta'],
        'sanitize_callback' => 'blogdata_sanitize_checkbox',
	)
);
// Single Page meta
Blogdata_Customizer_Control::add_field( 
	array(
		'type'     => 'toggle', 
        'settings'  => 'blogdata_single_post_image',
        'label' => esc_html__('Featured Image', 'blogdata'),
		'section'  => 'site_single_posts_settings',
        'default' => $blogdata_default['blogdata_single_post_image'],
        'sanitize_callback' => 'blogdata_sanitize_checkbox',
	)
);
// Single Page social icon
Blogdata_Customizer_Control::add_field( 
	array(
		'type'     => 'toggle', 
        'settings'  => 'blogdata_blog_post_icon_enable',
        'label' => esc_html__('Hide/Show Sharing Icons', 'blogdata'),
		'section'  => 'site_single_posts_settings',
        'default' => true,
        'sanitize_callback' => 'blogdata_sanitize_checkbox',
	)
);
// Single Page Post Meta Heading
Blogdata_Customizer_Control::add_field(
	array(
		'type'      => 'hidden',
        'settings'  => 'single_post_meta_heading',
        'label' => esc_html__('Post Meta', 'blogdata'),
		'section'   => 'site_single_posts_settings',
	)
);
// Single Page Post Meta
Blogdata_Customizer_Control::add_field( 
	array(
		'type'     => 'blogdata-sortable', 
        'settings'  => 'single_post_meta',
		'section'  => 'site_single_posts_settings',
        'default'    => array(
            'author',
            'date',
            'comments',
            'tags',
        ),
        'choices'    => array(
            'author'      => esc_attr__( 'Author', 'blogdata' ),
            'date'        => esc_attr__( 'Date', 'blogdata' ),
            'comments'    => esc_attr__( 'Comments', 'blogdata' ),
            'tags'        => esc_attr__( 'Tags', 'blogdata' ),
        ),
        'unsortable' => array(''),
	)
);
// Single Page Author
Blogdata_Customizer_Control::add_field(
	array(
		'type'      => 'hidden',
        'settings'  => 'blogdata_single_post_author_heading',
        'label' => esc_html__('Author', 'blogdata'),
		'section'   => 'site_single_posts_settings',
	)
);
Blogdata_Customizer_Control::add_field( 
	array(
		'type'     => 'toggle', 
        'settings'  => 'blogdata_enable_single_admin',
        'label' => esc_html__('Hide/Show Author', 'blogdata'),
		'section'  => 'site_single_posts_settings',
        'default' => $blogdata_default['blogdata_enable_single_admin'],
        'sanitize_callback' => 'blogdata_sanitize_checkbox',
	)
);
//Related Post haeding
Blogdata_Customizer_Control::add_field(
	array(
		'type'      => 'hidden',
        'settings'  => 'blogdata_single_related_post_heading',
        'label' => esc_html__('Related Post', 'blogdata'),
		'section'   => 'site_single_posts_settings',
	)
);
Blogdata_Customizer_Control::add_field( 
	array(
		'type'     => 'toggle', 
        'settings'  => 'blogdata_enable_single_related',
        'label' => esc_html__('Hide/Show Related Post', 'blogdata'),
		'section'  => 'site_single_posts_settings',
        'default' => $blogdata_default['blogdata_enable_single_related'],
        'sanitize_callback' => 'blogdata_sanitize_checkbox',
	)
);
//Related Post title
Blogdata_Customizer_Control::add_field( 
	array(
		'type'     => 'text', 
        'settings'  => 'blogdata_related_post_title',
        'label' => esc_html__('Related Post Title', 'blogdata'),
        'default' => esc_html__('Related Posts', 'blogdata'),
		'section'  => 'site_single_posts_settings',
        'transport'=> 'postMessage',
        'sanitize_callback' => 'sanitize_text_field',
	)
);
//Related Post category
Blogdata_Customizer_Control::add_field( 
	array(
		'type'     => 'toggle', 
        'settings'  => 'blogdata_enable_single_related_category',
        'label' => esc_html__('Hide/Show Categories', 'blogdata'),
		'section'  => 'site_single_posts_settings',
        'default' => $blogdata_default['blogdata_enable_single_related_category'],
        'sanitize_callback' => 'blogdata_sanitize_checkbox',
	)
);
//Related Post admin
Blogdata_Customizer_Control::add_field( 
	array(
		'type'     => 'toggle', 
        'settings'  => 'blogdata_enable_single_related_admin',
        'label' => esc_html__('Hide/Show Author Details', 'blogdata'),
		'section'  => 'site_single_posts_settings',
        'default' => $blogdata_default['blogdata_enable_single_related_admin'],
        'sanitize_callback' => 'blogdata_sanitize_checkbox',
	)
); 
//Related Post date
Blogdata_Customizer_Control::add_field( 
	array(
		'type'     => 'toggle', 
        'settings'  => 'blogdata_enable_single_related_date',
        'label' => esc_html__('Hide/Show Date', 'blogdata'),
		'section'  => 'site_single_posts_settings',
        'default' => $blogdata_default['blogdata_enable_single_related_date'],
        'sanitize_callback' => 'blogdata_sanitize_checkbox',
	)
);
Blogdata_Customizer_Control::add_field(
	array(
		'type'      => 'hidden',
        'settings'  => 'blogdata_single_post_element_heading',
        'label' => esc_html__('Post Comments', 'blogdata'),
		'section'   => 'site_single_posts_settings',
	)
);
//Related Post comment
Blogdata_Customizer_Control::add_field( 
	array(
		'type'     => 'toggle', 
        'settings'  => 'blogdata_enable_single_comments',
        'label' => esc_html__('Hide/Show Comments', 'blogdata'),
		'section'  => 'site_single_posts_settings',
        'default' => $blogdata_default['blogdata_enable_single_comments'],
        'sanitize_callback' => 'blogdata_sanitize_checkbox',
	)
);
//========== Add Sidebar Option Panel. ===============     
// Sticky Sidebar
Blogdata_Customizer_Control::add_section(
	'sticky_sidebar',
	array(
		'title' => esc_html__( 'Sticky Sidebar', 'blogdata' ), 
        'capability' => 'edit_theme_options', 
        'priority' => 9, 
        'panel' => 'theme_option_panel',
	)
);
Blogdata_Customizer_Control::add_field( 
	array(
		'type'     => 'toggle', 
        'settings'  => 'sticky_sidebar_toggle',
        'label' => esc_html__('On/Off', 'blogdata'),
		'section'  => 'sticky_sidebar',
        'default' => true,
        'sanitize_callback' => 'blogdata_sanitize_checkbox',
	)
);
//========== Add Theme colors Panel. ===============
Blogdata_Customizer_Control::add_panel(
	'Theme_colors_panel',
	array(
        'title' => esc_html__('Theme Colors', 'blogdata'),
        'priority' => 10,
        'capability' => 'edit_theme_options',
	)
);       
//Add Category Color Section 
Blogdata_Customizer_Control::add_section(
	'blogdata_cat_color_setting',
	array(
		'title' => esc_html__( 'Category Color', 'blogdata' ), 
        'panel' => 'Theme_colors_panel',
	)
);
$blogdataAllCats = get_categories();
if( $blogdataAllCats ) :
    foreach( $blogdataAllCats as $singleCat ) :
        // category colors control
        Blogdata_Customizer_Control::add_field( 
            array(
                'type'     => 'color', 
                'settings'  => 'category_' .absint($singleCat->term_id). '_color',
                'label' => $singleCat->name,
                'section'  => 'blogdata_cat_color_setting',
                'default' => '',
                'sanitize_callback' => 'blogdata_sanitize_alpha_color',
            )
        );
    endforeach;
endif;
//Add Site Title/Tagline Color Section
Blogdata_Customizer_Control::add_section(
	'blogdata_site_title_color_section',
	array(
		'title' => esc_html__( 'Site Title/Tagline', 'blogdata' ), 
        'panel' => 'Theme_colors_panel',
	)
);
// Site Title/Tagline Color Heading
Blogdata_Customizer_Control::add_field(
	array(
		'type'     => 'hidden', 
        'settings'  => 'site_title_tagline_title',
        'label' => esc_html__('Site Title/Tagline', 'blogdata'),
		'section'  => 'blogdata_site_title_color_section',
        'sanitize_callback' => 'blogdata_sanitize_text',
	)
);
$wp_customize->remove_control( 'header_textcolor');
Blogdata_Customizer_Control::add_field( 
	array(
		'type'     => 'color-alpha', 
        'settings'  => 'header_text_color',
        'label' => esc_html__('Color', 'blogdata'),
		'section'  => 'blogdata_site_title_color_section',
        'default' => '#000',
        'sanitize_callback' => 'blogdata_sanitize_alpha_color',
	)
);

Blogdata_Customizer_Control::add_field( 
	array(
		'type'     => 'color-alpha', 
        'settings'  => 'header_text_color_on_hover',
        'label' => esc_html__('Hover Color', 'blogdata'),
		'section'  => 'blogdata_site_title_color_section',
        'default' => '#a90e6d',
        'sanitize_callback' => 'blogdata_sanitize_alpha_color',
	)
);
Blogdata_Customizer_Control::add_field( 
	array(
		'type'     => 'color-alpha', 
        'settings'  => 'header_text_dark_color',
        'label' => esc_html__('Color (Dark Layout)', 'blogdata'),
		'section'  => 'blogdata_site_title_color_section',
        'default' => '#fff',
        'sanitize_callback' => 'blogdata_sanitize_alpha_color',
	)
);
Blogdata_Customizer_Control::add_field( 
	array(
		'type'     => 'color-alpha', 
        'settings'  => 'header_text_dark_color_on_hover',
        'label' => esc_html__('Hover Color (Dark Layout)', 'blogdata'),
		'section'  => 'blogdata_site_title_color_section',
        'default' => '#a90e6d',
        'sanitize_callback' => 'blogdata_sanitize_alpha_color',
	)
);
//Add Theme Mode Section
Blogdata_Customizer_Control::add_section(
	'blogdata_skin_section',
	array(
		'title' => esc_html__( 'Theme Mode', 'blogdata' ), 
        'panel' => 'Theme_colors_panel',
        'priority' => 10,
	)
);
// Theme Mode Heading
Blogdata_Customizer_Control::add_field(
	array(
		'type'     => 'hidden', 
        'settings'  => 'blogdata_skin_mode_title',
        'label' => esc_html__('Theme Mode', 'blogdata'),
		'section'  => 'blogdata_skin_section',
        'sanitize_callback' => 'blogdata_sanitize_text',
	)
);
Blogdata_Customizer_Control::add_field( 
	array(
		'type'     => 'radio-image', 
        'settings'  => 'blogdata_skin_mode',
		'section'  => 'blogdata_skin_section',
        'default' => 'defaultcolor',
        'choices'   => array(
            'defaultcolor'    => get_template_directory_uri() . '/images/color/white.png',
            'dark' => get_template_directory_uri() . '/images/color/black.png',
        ),
        'sanitize_callback' => 'blogdata_sanitize_radio',
	)
);

//Scroller Section
Blogdata_Customizer_Control::add_section(
	'scroller_options',
	array(
		'title' => esc_html__( 'Scroller', 'blogdata' ), 
        'panel' => 'theme_option_panel',
        'capability' => 'edit_theme_options',
	)
);
Blogdata_Customizer_Control::add_field(
	array(
		'type'     => 'hidden', 
        'settings'  => 'blogdata_scroll_to_top_settings',
        'label' => esc_html__('Scroll To Top', 'blogdata'),
		'section'  => 'scroller_options',
        'sanitize_callback' => 'blogdata_sanitize_text',
	)
);
Blogdata_Customizer_Control::add_field( 
	array(
		'type'     => 'toggle', 
        'settings'  => 'blogdata_scrollup_enable',
        'label' => esc_html__('Enable/Disable Scroll To Top', 'blogdata'),
		'section'  => 'scroller_options',
        'default' => true,
        'sanitize_callback' => 'blogdata_sanitize_checkbox',
	)
);
Blogdata_Customizer_Control::add_field( 
	array(
		'type'     => 'radio-image', 
        'settings'  => 'scrollup_layout',
		'section'  => 'scroller_options',
        'default' => 'fa-arrow-up',
        'choices'   => array(
            'fa-angle-up' => get_template_directory_uri() . '/images/fu1.png',
            'fa-angles-up'    => get_template_directory_uri() . '/images/fu2.png',
            'fa-arrow-up'    => get_template_directory_uri() . '/images/fu3.png',
            'fa-up-long'    => get_template_directory_uri() . '/images/fu4.png',
        ),
        'sanitize_callback' => 'blogdata_sanitize_radio',
	)
);
$font_family = array('Inter'=> 'Inter', 'Open Sans'=>'Open Sans', 'Kalam'=>'Kalam', 
'Rokkitt'=>'Rokkitt', 'Jost' => 'Jost', 'Poppins' => 'Poppins', 'Lato' => 'Lato', 'Noto Serif'=>'Noto Serif', 
'Raleway'=>'Raleway', 'Roboto' => 'Roboto');

$font_weight = array('300'=>'300 (Light)','400'=>'400 (Normal)','500'=>'500 (Medium)' ,'600'=>'600 (Semi Bold)',
'700'=>'700 (Bold)','800'=>'800 (Extra Bold)','900'=>'900 (Black)');

Blogdata_Customizer_Control::add_section(
	'blogdata_general_typography',
	array(
		'title' => esc_html__( 'Typography', 'blogdata' ),  
        'priority' => 20,
        'capability' => 'edit_theme_options',
	)
);
Blogdata_Customizer_Control::add_field( 
	array(
		'type'     => 'toggle', 
        'settings'  => 'enable_blogdata_typo',
        'label' => esc_html__('On/Off', 'blogdata'),
		'section'  => 'blogdata_general_typography',
        'default' => false,
        'sanitize_callback' => 'blogdata_sanitize_checkbox',
	)
);
Blogdata_Customizer_Control::add_field(
	array(
		'type'     => 'hidden', 
        'settings'  => 'heading_typo_title',
        'label' => esc_html__('Heading', 'blogdata'),
		'section'  => 'blogdata_general_typography', 
        'sanitize_callback' => 'blogdata_sanitize_text',  
	)
);
Blogdata_Customizer_Control::add_field( 
	array(
		'type'     => 'select', 
        'settings'  => 'heading_fontfamily',
        'label' => esc_html__('Font Family', 'blogdata'),
		'section'  => 'blogdata_general_typography',
        'default' => $blogdata_default['heading_fontfamily'],
        'choices'   => $font_family ,
        'sanitize_callback' => 'blogdata_sanitize_select',
	)
);
Blogdata_Customizer_Control::add_field( 
	array(
		'type'     => 'select', 
        'settings'  => 'heading_fontweight',
        'label' => esc_html__('Font Weight', 'blogdata'),
		'section'  => 'blogdata_general_typography',
        'default' => $blogdata_default['heading_fontweight'],
        'choices'   => $font_weight ,
        'sanitize_callback' => 'blogdata_sanitize_select',
	)
);

// Menu Typo
Blogdata_Customizer_Control::add_field(
	array(
		'type'     => 'hidden', 
        'settings'  => 'blogdata_menu_font',
        'label' => esc_html__('Menu Font', 'blogdata'),
		'section'  => 'blogdata_general_typography',
        'sanitize_callback' => 'blogdata_sanitize_text',
	)
);
Blogdata_Customizer_Control::add_field( 
	array(
		'type'     => 'select', 
        'settings'  => 'blogdata_menu_fontfamily',
        'label' => esc_html__('Font Family', 'blogdata'),
		'section'  => 'blogdata_general_typography',
        'default' => $blogdata_default['blogdata_menu_fontfamily'],
        'choices'   => $font_family ,
        'sanitize_callback' => 'blogdata_sanitize_select',
	)
);

// if ( ! function_exists( 'blogdata_sanitize_select' ) ) :
//     /**
//      * Sanitize select.
//      *
//      * @since 1.0.0
//      *
//      * @param mixed                $input The value to sanitize.
//      * @param WP_Customize_Setting $setting WP_Customize_Setting instance.
//      * @return mixed Sanitized value.
//      */
//     function blogdata_sanitize_select( $input, $setting ) {

//         // Ensure input is a slug.
//         $input = sanitize_key( $input );

//         // Get list of choices from the control associated with the setting.
//         $choices = $setting->manager->get_control( $setting->id )->choices;

//         // If the input is a valid key, return it; otherwise, return the default.
//         return ( array_key_exists( $input, $choices ) ? $input : $setting->default );

//     }

// endif;

function blogdata_template_page_sanitize_text( $input ) {

    return wp_kses_post( force_balance_tags( $input ) );

}

function blogdata_header_info_sanitize_text( $input ) {
                    
    return wp_kses_post( force_balance_tags( $input ) );

}
    
if ( ! function_exists( 'blogdata_sanitize_text_content' ) ) :
    /**
     * Sanitize text content.
     *
     * @since 1.0.0
     *
     * @param string               $input Content to be sanitized.
     * @param WP_Customize_Setting $setting WP_Customize_Setting instance.
     * @return string Sanitized content.
     */
    function blogdata_sanitize_text_content( $input, $setting ) {

        return ( stripslashes( wp_filter_post_kses( addslashes( $input ) ) ) );

    }
endif;
    
function blogdata_header_sanitize_checkbox( $input ) {
    // Boolean check 
    return ( ( isset( $input ) && true == $input ) ? true : false );
        
}
