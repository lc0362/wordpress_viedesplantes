<?php

if ( ! class_exists( 'Blogdata_Customizer_Control' ) ) { 

	class Blogdata_Customizer_Control { 

		private static $instance = null; 

		public static $panels = array();

		public static $sections = array();

		public static $fields = array();

		public static function get_instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}
			return self::$instance;
		}
        
		public function __construct() {
			add_action( 'customize_register', array( $this, 'customizer_register' ) ); 
			add_filter( 'blogdata_customizer_add_settings', array( $this, 'field_add_setting_args' ) );
			add_filter( 'blogdata_customizer_add_control', array( $this, 'field_add_control_args' ) );
		}

		public static function add_panel( $id = '', $args = array() ) {
			self::$panels[ $id ] = $args;
		}

		public static function add_section( $id, $args ) {
			self::$sections[ $id ] = $args;
		}

		public static function add_field( $args ) {
			if ( isset( $args['settings'] ) && isset( $args['type'] ) ) {
				self::$fields[ $args['settings'] ] = $args;
			}
		}

		public function customizer_register( $wp_customize ) {
			
			foreach ( self::$panels as $panel_id => $panel_args ) {
				$wp_customize->add_panel( $panel_id, $panel_args );
			}

			foreach ( self::$sections as $section_id => $section_args ) {
				$wp_customize->add_section( $section_id, $section_args );
			} 

            foreach ( self::$fields as $field_id => $field_args ) {
				$params = apply_filters( 'blogdata_customizer_add_settings', $field_args, $wp_customize );

				call_user_func( array( $wp_customize, 'add_setting' ), $field_id, $params );

				$args = apply_filters( 'blogdata_customizer_add_control', $field_args, $wp_customize );

				switch ( $field_args['type'] ) {
					case 'color':
						$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $field_id, $args ) );
                    break;
					case 'cropped_image':
						$wp_customize->add_control( new WP_Customize_Cropped_Image_Control( $wp_customize, $field_id, $args ) );
                    break;
					case 'toggle':
						$wp_customize->add_control( new Blogdata_Toggle_Control( $wp_customize, $field_id, $args ) );
                    break;
                    case 'color-alpha':
                        $wp_customize->add_control( new Blogdata_Customize_Alpha_Color_Control( $wp_customize, $field_id, $args ) );
                        break;
					case 'radio-image':
						$wp_customize->add_control( new Blogdata_Custom_Radio_Default_Image_Control( $wp_customize, $field_id, $args ) );
						break;
					case 'layout':
						$wp_customize->add_control( new Blogdata_layout_Customize_Control( $wp_customize, $field_id, $args ) );
						break;
					case 'taxonomies':
						$wp_customize->add_control( new Blogdata_Dropdown_Taxonomies_Control( $wp_customize, $field_id, $args ) );
						break;
					case 'blogdata-sortable':
						$wp_customize->add_control( new Blogdata_Sortable_Control( $wp_customize, $field_id, $args ) );
						break;
					case 'blogdata-range':
						$wp_customize->add_control( new Blogdata_Customizer_Range_Control( $wp_customize, $field_id, $args ) );
						break;
					case 'pro-text':
						$wp_customize->add_control( new Blogdata_Upgrade_Control( $wp_customize, $field_id, $args ) );
						break;
                    default:
                    $wp_customize->add_control( $field_id, $args );
                    break;
                }
            }
        }

		public function field_add_setting_args( $args ) {

			$args = array(
				'type'                 => isset( $args['type_mod'] ) ? $args['type_mod'] : 'theme_mod',
				'capability'           => isset( $args['capability'] ) ? $args['capability'] : 'edit_theme_options',
				'theme_supports'       => isset( $args['theme_supports'] ) ? $args['theme_supports'] : '',
				'default'              => isset( $args['default'] ) ? $args['default'] : '',
				'transport'            => isset( $args['transport'] ) ? $args['transport'] : 'refresh',
				'sanitize_callback'    => isset( $args['sanitize_callback'] ) ? $args['sanitize_callback'] : '',
				'sanitize_js_callback' => isset( $args['sanitize_js_callback'] ) ? $args['sanitize_js_callback'] : '',
			);

			return $args;
		}
        
		public function field_add_control_args( $args ) {
			if ( isset( $args['active_callback'] ) ) {
				if ( is_array( $args['active_callback'] ) ) {
					if ( ! is_callable( $args['active_callback'] ) ) {
						foreach ( $args['active_callback'] as $key => $val ) {
							if ( is_callable( $val ) ) {
								unset( $args['active_callback'][ $key ] );
							}
						}
						if ( isset( $args['active_callback'][0] ) ) {
							$args['required'] = $args['active_callback'];
						}
					}
				}
				if ( ! empty( $args['required'] ) ) {
					self::$dependencies[ $args['settings'] ] = $args['required'];
					$args['active_callback']                 = '__return_true';
					return $args;
				}
				// No need to proceed any further if we're using the default value.
				if ( '__return_true' === $args['active_callback'] ) {
					return $args;
				}
				// Make sure the function is callable, otherwise fallback to __return_true.
				if ( ! is_callable( $args['active_callback'] ) ) {
					$args['active_callback'] = '__return_true';
				}
			}
			return $args;
		}
    }
    
	if ( ! function_exists( 'blogdata_customizer' ) ) {

		function blogdata_customizer() {
			return Blogdata_Customizer_Control::get_instance();
		}
	}
	blogdata_customizer();
}