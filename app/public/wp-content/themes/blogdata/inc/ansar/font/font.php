<?php
/*--------------------------------------------------------------------*/
/*     Register Google Fonts
/*--------------------------------------------------------------------*/
add_action( 'wp_enqueue_scripts', 'blogdata_theme_fonts',1 );
add_action( 'enqueue_block_editor_assets', 'blogdata_theme_fonts',1 );
add_action( 'customize_preview_init', 'blogdata_theme_fonts', 1 );

function blogdata_theme_fonts() {
        $fonts_url = blogdata_fonts_url();
        // Load Fonts if necessary.
        if ( $fonts_url ) {
            require_once get_theme_file_path( 'inc/ansar/font/wptt-webfont-loader.php' );
            wp_enqueue_style( 'blogdata-theme-fonts', wptt_get_webfont_url( $fonts_url ), array(), '20201110' );
        }
}

function blogdata_fonts_url() {
	
    $fonts_url = '';
		
    $font_families = array();
 
	$font_families = array('Inter:100,200,300,400,500,600,700,800,900&display=swap|Kalam:300,400,500,600,700,800,900&display=swap|Open:300,400,500,600,700,800,900&display=swap Sans|Rokkitt:300,400,500,600,700,800,900&display=swap|Jost:300,400,500,600,700,800,900&display=swap|Poppins:300,400,500,600,700,800,900&display=swap|Lato:300,400,500,600,700,800,900&display=swap|Noto Serif:300,400,500,600,700,800,900&display=swap|Raleway:300,400,500,600,700,800,900&display=swap|Roboto:300,400,500,600,700,800,900&display=swap');
 
        $query_args = array(
            'family' => urlencode( implode( '|', $font_families ) ),
            'subset' => urlencode( 'latin,latin-ext' ),
        );
 
    return apply_filters( 'blogdata_fonts_url', add_query_arg( $query_args, 'https://fonts.googleapis.com/css' ) );

    return $fonts_url;
}