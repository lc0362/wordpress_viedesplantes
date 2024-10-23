/**
 * customizer.js
 *
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( $ ) {

	var myCustomizer = window.parent.window.wp.customize;

	// Site title and description.
	// wp.customize( 'blogname', function( value ) {
	// 	value.bind( function( to ) {
	// 		$( '.site-title a' ).text( to );
	// 		$( '.site-title-footer a' ).text( to );
	// 	} );
	// } );
	// wp.customize( 'blogdescription', function( value ) {
	// 	value.bind( function( to ) {
	// 		$( '.site-description' ).text( to );
	// 		$( '.site-description-footer' ).text( to );
	// 	} );
	// } );
	
	// Header text color.
	
	// Header text hide and show and text color.
	wp.customize( 'header_textcolor', function( value ) {
		if(value() == 'blank'){
			myCustomizer.control('site_title_font_size').container.hide();
		}else{
			myCustomizer.control('site_title_font_size').container.show();
		}
		value.bind( function( to ) {
			if ( 'blank' === to ) {
				$( 'header .site-title a,header .site-description' ).css( {
					'clip': 'rect(1px, 1px, 1px, 1px)',
					'position': 'absolute'
				} );
				$( 'header .site-branding-text ' ).addClass('d-none');
				myCustomizer.control('site_title_font_size').container.hide();
			} else {
				$('header .site-title').css('position', 'unset');
				$( 'header .site-title a,header .site-description' ).css( {
					'clip': 'auto',
					'position': 'relative'
				} );
				$( 'header .site-branding-text ' ).removeClass('d-none');
				$( 'header .site-title a, header .site-description' ).css( {
					'color': to
				} );
				myCustomizer.control('site_title_font_size').container.show();
			}
		} );
	} );

	// Footer Background Image.
	wp.customize( 'blogdata_footer_bg_img', function( value ) {
		value.bind( function( newVal ) {
			if(newVal !== ''){
				wp.media.attachment(newVal).fetch().then(function(attachment) {
					var imageUrl = attachment.url;
					$('footer').css('background-image', 'url("'+imageUrl+'")');
				});
				$('footer').addClass('back-img');
			}else{
				$('footer').removeAttr('style');
				$('footer').removeClass('back-img');
			}
		} );
	} );

	// Footer Background overlay color.
	wp.customize( 'blogdata_footer_overlay_color', function( value ) {
		value.bind( function( newVal ) {
			if(newVal !== ''){
				$('footer .overlay').css('background', newVal);
			}else{
				$('footer .overlay').css('background', '');
			}
		} );
	} );

	// Footer all Text color.
	wp.customize( 'blogdata_footer_text_color', function( value ) {
		value.bind( function( newVal ) {
			if(newVal !== ''){
				$('footer .mg-widget p, footer .site-title-footer a, footer .site-title a:hover , footer .site-description-footer, footer .site-description:hover').css('color', newVal);
			}else{
				$('footer .mg-widget p, footer .site-title-footer a, footer .site-title a:hover , footer .site-description-footer, footer .site-description:hover').css('color', '');
			}
		} );
	} );

	// Footer Widget Area Column.
	wp.customize( 'blogdata_footer_column_layout', function( value ) {
		var colum = 12 / value();
		var wclass = $('.animated.bs-widget');
		if(wclass.hasClass('col-lg-12')){
			wclass.removeClass('col-lg-12');
		}else if(wclass.hasClass('col-lg-6')){
			wclass.removeClass('col-lg-6');
		}else if(wclass.hasClass('col-lg-4')){
			wclass.removeClass('col-lg-4');
		}else if(wclass.hasClass('col-lg-3')){
			wclass.removeClass('col-lg-3');
		}
		wclass.addClass(`col-lg-${colum}`);

		value.bind( function( newVal ) {
			colum = 12 / newVal;
			wclass = $('.animated.bs-widget');
			if(wclass.hasClass('col-lg-12')){
				wclass.removeClass('col-lg-12');
			}else if(wclass.hasClass('col-lg-6')){
				wclass.removeClass('col-lg-6');
			}else if(wclass.hasClass('col-lg-4')){
				wclass.removeClass('col-lg-4');
			}else if(wclass.hasClass('col-lg-3')){
				wclass.removeClass('col-lg-3');
			}
			wclass.addClass(`col-lg-${colum}`);
		} );
	} );

	jQuery('.blogdata-customizer-target-btn').click(function(){
		var $this = jQuery(this);
		var type = $this.parent().attr('type');
		var setting_id = $this.parent().attr('type_id');
		if(type == "panel"){
			var focus_element = myCustomizer.panel(setting_id);
		}else if(type == 'section'){
			var focus_element = myCustomizer.section(setting_id);
		}else if(type == 'control'){
			var focus_element = myCustomizer.control(setting_id);
		}
		focus_element.focus();
	});

	myCustomizer.state( 'paneVisible' ).bind( function( paneVisible ) {
		if(paneVisible == false){
			$('.blogdata-customizer-edit-icon').addClass('d-none');
		}else{
			$('.blogdata-customizer-edit-icon').removeClass('d-none');
		}
	});

} )( jQuery );


/**
 * File customizer.js.
 *
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( $ ) {

	/**
     * Outputs custom css for responsive controls
     * @param  {[string]} setting customizer setting
     * @param  {[string]} css_selector
     * @param  {[array]} css_prop css property to write
     * @param  {String} ext css value extension eg: px, in
     * @return {[string]} css output
     */
    function range_live_media_load( setting, css_selector, css_prop, ext = '' ) {
        wp.customize(
            setting, function( value ) {
                'use strict';
                value.bind(
                    function( to ){
                        var values          = JSON.parse( to );
                        var desktop_value   = JSON.parse( values.desktop );
                        var tablet_value    = JSON.parse( values.tablet );
                        var mobile_value    = JSON.parse( values.mobile );

                        var class_name      = 'customizer-' + setting;
                        var css_class       = $( '.' + class_name );
                        var selector_name   = css_selector;
                        var property_name   = css_prop;

                        var desktop_css     = '';
                        var tablet_css      = '';
                        var mobile_css      = '';

                        if ( property_name.length == 1 ) {
                            var desktop_css     = property_name[0] + ': ' + desktop_value + ext + ';';
                            var tablet_css      = property_name[0] + ': ' + tablet_value + ext + ';';
                            var mobile_css      = property_name[0] + ': ' + mobile_value + ext + ';';
                        } else if ( property_name.length == 2 ) {
                            var desktop_css     = property_name[0] + ': ' + desktop_value + ext + ';';
                            var desktop_css     = desktop_css + property_name[1] + ': ' + desktop_value + ext + ';';

                            var tablet_css      = property_name[0] + ': ' + tablet_value + ext + ';';
                            var tablet_css      = tablet_css + property_name[1] + ': ' + tablet_value + ext + ';';

                            var mobile_css      = property_name[0] + ': ' + mobile_value + ext + ';';
                            var mobile_css      = mobile_css + property_name[1] + ': ' + mobile_value + ext + ';';
                        }

                        var head_append     = '<style class="' + class_name + '">' + selector_name + ' { ' + desktop_css + ' } @media (max-width: 991px){ ' + selector_name + ' { ' + tablet_css + ' } } @media (max-width: 575px){ ' + selector_name + ' { ' + mobile_css + ' } } </style>';

                        if ( css_class.length ) {
                            css_class.replaceWith( head_append );
                        } else {
                            $( "head" ).append( head_append );
                        }
                    }
                );
            }
        );
    }
	
	range_live_media_load( 'site_title_font_size', '.site-branding-text .site-title a', [ 'font-size' ], 'px' );
	range_live_media_load( 'side_main_logo_width', '.bs-menu-full .navbar-brand img', [ 'width' ], 'px' );
	range_live_media_load( 'header_image_height', '.header-image-section .overlay', [ 'height' ], 'px' );

	range_live_media_load( 'blogdata_slider_title_font_size', '.bs-slide .inner .title', [ 'font-size' ], 'px !important' );
	range_live_media_load( 'blogdata_tren_edit_title_font_size', '.multi-post-widget .bs-blog-post.three.bsm .title', [ 'font-size' ], 'px !important' );
	range_live_media_load( 'blogdata_footer_main_logo_width', 'footer .bs-footer-bottom-area .custom-logo, footer .bs-footer-copyright .custom-logo', [ 'width' ], 'px' );
	range_live_media_load( 'blogdata_footer_main_logo_height', 'footer .bs-footer-bottom-area .custom-logo, footer .bs-footer-copyright .custom-logo', [ 'height' ], 'px' );
	
} )( jQuery );