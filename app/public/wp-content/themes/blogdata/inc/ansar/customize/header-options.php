<?php 
/**
 * Header Option Panel
 *
 * @package BlogData
 */

$blogdata_default = blogdata_get_default_theme_options();

// Add Panel
Blogdata_Customizer_Control::add_panel(
	'blogdata_site_identity_panel',
	array(
        'title' => esc_html__('Site Identity', 'blogdata'),
        'priority' => 5,
        'capability' => 'edit_theme_options',
	)
);
// Add Section.
Blogdata_Customizer_Control::add_section(
	'title_tagline',
	array(
		'title' => esc_html__( 'Logo & Site Icon', 'blogdata' ), 
        'panel' => 'blogdata_site_identity_panel',
	)
);

Blogdata_Customizer_Control::add_field( 
	array(
		'type'     => 'blogdata-range', 
        'settings'  => 'side_main_logo_width',
        'label' => esc_html__('Logo Width', 'blogdata'),
		'section'  => 'title_tagline',
        'transport'         => 'postMessage',
        'media_query'   => true,
        'input_attr'    => array(
            'mobile'  => array(
                'min'           => 0,
                'max'           => 300,
                'step'          => 1,
                'default_value' => 150,
            ),
            'tablet'  => array(
                'min'           => 0,
                'max'           => 350,
                'step'          => 1,
                'default_value' => 200,
            ),
            'desktop' => array(
                'min'           => 0,
                'max'           => 400,
                'step'          => 1,
                'default_value' => 250,
            ),
        ),
    ),
);
// Add Section.
Blogdata_Customizer_Control::add_section(
	'blogdata_site_title_section',
	array(
		'title' => esc_html__( 'Site Title & Tagline', 'blogdata' ), 
        'panel' => 'blogdata_site_identity_panel',
	)
);

$wp_customize->get_control( 'blogname' )->section = 'blogdata_site_title_section';
$wp_customize->get_control( 'display_header_text' )->section = 'blogdata_site_title_section';
$wp_customize->get_control( 'display_header_text' )->label = esc_html__( 'Display site title', 'blogdata' );
$wp_customize->get_control( 'blogdescription' )->section = 'blogdata_site_title_section';

Blogdata_Customizer_Control::add_field( 
	array(
		'type'     => 'blogdata-range', 
        'settings'  => 'site_title_font_size',
        'label' => esc_html__('Site Title Size', 'blogdata'),
		'section'  => 'blogdata_site_title_section',
        'transport'         => 'postMessage',
        'media_query'   => true,
        'input_attr'    => array(
            'mobile'  => array(
                'min'           => 0,
                'max'           => 100,
                'step'          => 1,
                'default_value' => 30,
            ),
            'tablet'  => array(
                'min'           => 0,
                'max'           => 110,
                'step'          => 1,
                'default_value' => 35,
            ),
            'desktop' => array(
                'min'           => 0,
                'max'           => 120,
                'step'          => 1,
                'default_value' => 40,
            ),
        ),
    ),
);
// Theme Header Panel 
Blogdata_Customizer_Control::add_panel(
	'header_option_panel',
	array(
        'title' => esc_html__('Header Option', 'blogdata'),
        'priority' => 6,
        'capability' => 'edit_theme_options',
	)
);

// Header Image
Blogdata_Customizer_Control::add_section(
	'header_image',
    array(
        'title' => esc_html__( 'Header Image', 'blogdata' ),  
        'priority' => 1, 
        'panel' => 'header_option_panel',
    )
);
// Enable/Disable header image overlay color
Blogdata_Customizer_Control::add_field( 
    array(
        'type'     => 'checkbox', 
        'settings'  => 'remove_header_image_overlay',
        'label' => esc_html__('Remove Overlay Color', 'blogdata'),
        'section'  => 'header_image',
        'default' => false,
        'sanitize_callback' => 'blogdata_sanitize_checkbox',
    )
);
Blogdata_Customizer_Control::add_field( 
    array(
        'type'     => 'color-alpha', 
        'settings'  => 'blogdata_header_overlay_color',
        'label' => esc_html__('Background Color', 'blogdata'),
        'section'  => 'header_image',
        'default' => '',
        'sanitize_callback' => 'blogdata_sanitize_alpha_color',
        'active_callback'   => function( $setting ) {
            if ( $setting->manager->get_setting( 'remove_header_image_overlay' )->value() == false ) {
                return true;
            }
            return false;
        }
    )
);
Blogdata_Customizer_Control::add_field( 
	array(
		'type'     => 'blogdata-range', 
        'settings'  => 'header_image_height',
        'label' => esc_html__('Height', 'blogdata'),
		'section'  => 'header_image',
        'transport'         => 'postMessage',
        'media_query'   => true,
        'input_attr'    => array(
            'mobile'  => array(
                'min'           => 0,
                'max'           => 300,
                'step'          => 1,
                'default_value' => 130,
            ),
            'tablet'  => array(
                'min'           => 0,
                'max'           => 400,
                'step'          => 1,
                'default_value' => 150,
            ),
            'desktop' => array(
                'min'           => 0,
                'max'           => 500,
                'step'          => 1,
                'default_value' => 200,
            ),
        ),
    ),
);

// Advertisement Section.
Blogdata_Customizer_Control::add_section(
	'header_advert_section',
	array(
		'title' => esc_html__( 'Banner Advertisement', 'blogdata' ), 
        'panel' => 'header_option_panel',
        // 'priority' => 3,
	)
);
// Setting banner_advertisement_section. 
Blogdata_Customizer_Control::add_field( 
	array(
		'type'     => 'cropped_image', 
        'settings'  => 'banner_ad_image',
        'label' => esc_html__('Banner Advertisement', 'blogdata'),
        'description' => sprintf(esc_html__('Recommended Size %1$s px X %2$s px', 'blogdata'), 930, 100),
        'section' => 'header_advert_section',
        'default' => $blogdata_default['banner_ad_image'],
        'width' => 930,
        'height' => 100,
        'flex_width' => true,
        'flex_height' => true,
        'sanitize_callback' => 'absint',
	)
);
/*banner_advertisement_section_url*/
Blogdata_Customizer_Control::add_field( 
	array(
		'type'     => 'text', 
        'settings'  => 'banner_ad_url',
        'label' => esc_html__('Link', 'blogdata'),
		'section'  => 'header_advert_section',
        'priority' => 15,
        'default' => '#',
        'sanitize_callback' => 'esc_url_raw',
	)
);

Blogdata_Customizer_Control::add_field( 
	array(
		'type'     => 'toggle', 
        'settings'  => 'banner_open_on_new_tab',
        'label' => esc_html__('Open link in a new tab', 'blogdata'),
		'section'  => 'header_advert_section',
        'priority' => 16,
        'default' => true,
        'sanitize_callback' => 'blogdata_sanitize_checkbox',
	)
);

// Header Rightbar
Blogdata_Customizer_Control::add_section(
	'header_rightbar',
	array(
		'title' => esc_html__( 'Menu', 'blogdata' ), 
        'panel' => 'header_option_panel',
        'priority' => 5,
	)
);
Blogdata_Customizer_Control::add_field(
    array(
        'type'     => 'hidden', 
        'settings'  => 'header_rightbar_settings',
        'label'     => esc_html__('Menu', 'blogdata'),
        'section'  => 'header_rightbar',
        'sanitize_callback' => 'blogdata_sanitize_text',
    )
);
// Hide/Show Menu Sidebar
Blogdata_Customizer_Control::add_field( 
	array(
		'type'     => 'toggle', 
        'settings'  => 'blogdata_menu_sidebar',
        'label' => esc_html__('Enable/Disable Toggle Icon', 'blogdata'),
		'section'  => 'header_rightbar',
        'default' => true,
        'sanitize_callback' => 'blogdata_sanitize_checkbox',
	)
);
Blogdata_Customizer_Control::add_field( 
	array(
		'type'     => 'toggle', 
        'settings'  => 'blogdata_menu_search',
        'label' => esc_html__('Enable/Disable Search Icon', 'blogdata'),
		'section'  => 'header_rightbar',
        'default' => true,
        'sanitize_callback' => 'blogdata_sanitize_checkbox',
	)
);
Blogdata_Customizer_Control::add_field( 
	array(
		'type'     => 'toggle', 
        'settings'  => 'blogdata_lite_dark_switcher',
        'label' => esc_html__('Enable/Disable Dark/Light Icon', 'blogdata'),
		'section'  => 'header_rightbar',
        'default' => true,
        'sanitize_callback' => 'blogdata_sanitize_checkbox',
	)
);
if( class_exists( 'WooCommerce' ) ) { 
    // Cart Icon Section Heading 
    Blogdata_Customizer_Control::add_field(
        array(
            'type'      => 'hidden', 
            'settings'  => 'shop_cart_btn_heading',
            'label'     => esc_html__('Shopping Cart', 'blogdata'),
            'section'   => 'header_rightbar',
        )
    );
    // Cart Hide/Show
    Blogdata_Customizer_Control::add_field( 
        array(
            'type'     => 'toggle', 
            'settings'  => 'blogdata_cart_enable',
            'label' => esc_html__('Hide/Show Cart', 'blogdata'),
            'section'  => 'header_rightbar',
            'default' => true,
            'sanitize_callback' => 'blogdata_sanitize_checkbox',
        )
    );
}

// Subscribe Section Heading 
Blogdata_Customizer_Control::add_field(
	array(
		'type'      => 'hidden', 
        'settings'  => 'subscriber_btn_settings',
        'label'     => esc_html__('Subscribe', 'blogdata'),
		'section'   => 'header_rightbar',
	)
);
// Hide/Show Subscribe
Blogdata_Customizer_Control::add_field( 
	array(
		'type'     => 'toggle', 
        'settings'  => 'blogdata_menu_subscriber',
        'label' => esc_html__('Hide/Show', 'blogdata'),
		'section'  => 'header_rightbar',
        'default' => true,
        'sanitize_callback' => 'blogdata_sanitize_checkbox',
	)
);
// Subscribe Icon Layout
Blogdata_Customizer_Control::add_field(
	array(
		'type'     => 'radio-image',
		'settings' => 'subsc_icon_layout',
        'label' => esc_html__('Icon', 'blogdata'),
		'section'  => 'header_rightbar',
		'default'  => 'play',
        'choices'       => array(
            'bell' => get_template_directory_uri() . '/images/subs1.png',
            'play'    => get_template_directory_uri() . '/images/subs3.png', 
        ),
        'active_callback'   => 'blogdata_menu_subscriber_section_status',
        'sanitize_callback' => 'blogdata_sanitize_radio',
	)
);
Blogdata_Customizer_Control::add_field( 
	array(
		'type'     => 'text', 
        'settings'  => 'subs_news_title',
        'label' => esc_html__('Title', 'blogdata'),
		'section'  => 'header_rightbar',
        'default' => esc_html__('Subscribe','blogdata'),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
        'active_callback'   => 'blogdata_menu_subscriber_section_status',
	)
);
// Subscribe Link
Blogdata_Customizer_Control::add_field( 
	array(
		'type'     => 'text', 
        'settings'  => 'blogdata_subsc_link',
        'label' => esc_html__('Link', 'blogdata'),
		'section'  => 'header_rightbar',
        'default' => '#',
        'sanitize_callback' => 'esc_url_raw',
        'active_callback'   => 'blogdata_menu_subscriber_section_status',
	)
);
// Subscribe Open in New Tab
Blogdata_Customizer_Control::add_field( 
	array(
		'type'     => 'toggle', 
        'settings'  => 'subsc_open_in_new',
        'label' => esc_html__('Open link in a new tab', 'blogdata'),
		'section'  => 'header_rightbar',
        'default' => true,
        'sanitize_callback' => 'blogdata_sanitize_checkbox',
        'active_callback'   => 'blogdata_menu_subscriber_section_status',
	)
);
// Sticky Header
Blogdata_Customizer_Control::add_section(
	'sticky_header',
	array(
		'title' => esc_html__( 'Sticky Header', 'blogdata' ), 
        'panel' => 'header_option_panel',
        'priority' => 6,
	)
);
Blogdata_Customizer_Control::add_field(
    array(
        'type'     => 'hidden', 
        'settings'  => 'sticky_header_heading',
        'label' => esc_html__('Sticky Header', 'blogdata'),
        'section'  => 'sticky_header',
        'sanitize_callback' => 'blogdata_sanitize_text',
    )
);
Blogdata_Customizer_Control::add_field( 
	array(
		'type'     => 'toggle', 
        'settings'  => 'sticky_header_toggle',
        'label' => esc_html__('Enable/Disable Sticky Header', 'blogdata'),
		'section'  => 'sticky_header',
        'default' => true,
        'sanitize_callback' => 'blogdata_sanitize_checkbox',
	)
);