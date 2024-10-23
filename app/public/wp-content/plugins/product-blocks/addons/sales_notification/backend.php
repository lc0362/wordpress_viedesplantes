<?php
defined( 'ABSPATH' ) || exit;

/**
 * Sale Notification Addons Initial Configuration
 * @since v.4.0.0
 */
add_filter('wopb_addons_config', 'wopb_sales_notification_config');
function wopb_sales_notification_config( $config ) {
	$configuration = array(
		'name' => __( 'Sales Push Notification', 'product-blocks' ),
        'desc' => __( 'Build trust and give the customers confidence to purchase products from your online store.', 'product-blocks' ),
        'is_pro' => false,
        'live' => 'https://www.wpxpo.com/wowstore/woocommerce-sales-push-notification/live_demo_args',
        'docs' => 'https://wpxpo.com/docs/wowstore/add-ons/sales-push-notification/addon_doc_args',
        'type' => 'sales',
        'priority' => 10
	);
	$config['wopb_sales_notification'] = $configuration;
	return $config;
}

/**
 * Sale Notification Addons Default Settings
 *
 * @since v.4.0.0
 * @param ARRAY | Default Congiguration
 * @return ARRAY
 */
add_filter( 'wopb_settings', 'get_sales_notification_settings', 10, 1 );
function get_sales_notification_settings( $config ) {
    $arr = array(
        'wopb_sales_notification' => array(
            'label' => __('Sale Push Notification Settings', 'product-blocks'),
            'attr' => array(
                
                'tab' => array(
                    'type'  => 'tab',
                    'options'  => array(
                        'settings' => array(
                            'label' => __('Settings', 'product-blocks'),
                            'attr' => array(
                                'wopb_sales_notification' => array(
                                    'type' => 'toggle',
                                    'value' => 'true',
                                    'label' => __('Enable Sales Notification', 'product-blocks'),
                                    'desc' => __("Enable sales notification on your website", 'product-blocks')
                                ),
                                'container_1' => array(
                                    'type'=> 'container',
                                    'attr' => array(
                                        'sales_push_display_position' => array(
                                            'type' => 'radio',
                                            'label' => __('Display Position', 'product-blocks'),
                                            'display' => 'inline-box',
                                            'options' => array(
                                                'left' => __('Left', 'product-blocks'),
                                                'right' => __('Right', 'product-blocks')
                                            ),
                                            'default' => 'left'
                                        ),
                                        'sales_number' => array(
                                            'type' => 'number',
                                            'label' => __('Number of Recent Order', 'product-blocks'),
                                            'default' => 10
                                        ),
                                        'sales_name' => array(
                                            'type' => 'select',
                                            'label' => __('Name', 'product-blocks'),
                                            'options' => array(
                                                'display' => __( 'Display Name','product-blocks' ),
                                                'full' => __( 'Full Name','product-blocks' )
                                            ),
                                            'default' => 'display'
                                        ),
                                        'sales_label' => array(
                                            'type' => 'text',
                                            'label' => __('Label Text', 'product-blocks'),
                                            'default' => 'Just Purchased'
                                        ),
                                        'sales_image' => array(
                                            'type' => 'toggle',
                                            'label' => __('Product Image', 'product-blocks'),
                                            'default' => 'yes',
                                            'desc' => __('Show Product Image in Notification', 'product-blocks')
                                        ),
                                        'sales_time' => array(
                                            'type' => 'toggle',
                                            'label' => __('Show Purchased Time', 'product-blocks'),
                                            'default' => 'yes',
                                            'desc' => __('Show Product Time in Notification', 'product-blocks')
                                        ),
                                        'sales_time_prefix' => array(
                                            'type' => 'text',
                                            'label' => __('Time Prefix', 'product-blocks'),
                                            'default' => 'About',
                                            'depends' => array(
                                                'key' =>'sales_time',
                                                'condition' => '==',
                                                'value' => 'yes'
                                            )
                                        ),
                                        'sales_time_postfix' => array(
                                            'type' => 'text',
                                            'label' => __('Time Postfix', 'product-blocks'),
                                            'default' => 'Ago',
                                            'depends' => array(
                                                'key' =>'sales_time',
                                                'condition' => '==',
                                                'value' => 'yes'
                                            )
                                        ),
                                        'sales_gap_time' => array(
                                            'type' => 'number',
                                            'label' => __('Gap Time between two Notification (Seconds)', 'product-blocks'),
                                            'default' => 5
                                        ),
                                        'sales_stay_time' => array(
                                            'type' => 'number',
                                            'label' => __('Stay Notification on Screen (Seconds)', 'product-blocks'),
                                            'default' => 5
                                        ),
                                    )
                                )
                            )
                        ),
                        'design' => array(
                            'label' => __('Design', 'product-blocks'),
                            'attr' => array(
                                'container_2' => array(
                                    'type'=> 'container',
                                    'attr' => array(
                                        'sales_bg_color' => array (
                                            'type'=> 'color2',
                                            'field1'=> 'bg',
                                            'field2'=> 'hover_bg',
                                            'label'=> __('Background Color', 'product-blocks'),
                                            'default'=>  (object)array(
                                                'bg' => '#ffffff',
                                                'hover_bg' => '',
                                            ),
                                            'tooltip'=> __('Background Color', 'product-blocks'),
                                        ),
                                        'sales_name_typo' => array (
                                            'type'=> 'typography',
                                            'label'=> __('Name Typography', 'product-blocks'),
                                            'default'=> (object)array(
                                                'size' => 14,
                                                'bold' => false,
                                                'italic' => false,
                                                'underline' => false,
                                                'color' => '#6a7178',
                                                'hover_color' => '',
                                            ),
                                        ),
                                        'sales_label_typo' => array (
                                            'type'=> 'typography',
                                            'label'=> __('Purchase Typography', 'product-blocks'),
                                            'default'=> (object)array(
                                                'size' => 14,
                                                'bold' => false,
                                                'italic' => false,
                                                'underline' => false,
                                                'color' => '#037fff',
                                                'hover_color' => '',
                                            ),
                                        ),
                                        'sales_title_typo' => array (
                                            'type'=> 'typography',
                                            'label'=> __('Title Typography', 'product-blocks'),
                                            'default'=> (object)array(
                                                'size' => 15,
                                                'bold' => false,
                                                'italic' => false,
                                                'underline' => false,
                                                'color' => '#070c1a',
                                                'hover_color' => '',
                                            ),
                                        ),
                                        'sales_time_typo' => array (
                                            'type'=> 'typography',
                                            'label'=> __('Time Typography', 'product-blocks'),
                                            'default'=> (object)array(
                                                'size' => 14,
                                                'bold' => false,
                                                'italic' => false,
                                                'underline' => false,
                                                'color' => '#858585',
                                                'hover_color' => '',
                                            ),
                                        ),
                                        'sales_close_bg' => array (
                                            'type'=> 'color2',
                                            'field1'=> 'bg',
                                            'field2'=> 'hover_bg',
                                            'label'=> __('Close Icon Background', 'product-blocks'),
                                            'default'=>  (object)array(
                                                'bg' => '#DBDBDB',
                                                'hover_bg' => '',
                                            ),
                                            'tooltip'=> __('Color', 'product-blocks'),
                                        ),
                                        'sales_close_color' => array (
                                            'type'=> 'color2',
                                            'field1'=> 'color',
                                            'field2'=> 'hover_color',
                                            'label'=> __('Close Icon Color', 'product-blocks'),
                                            'default'=>  (object)array(
                                                'color' => '#000000',
                                                'hover_color' => '',
                                            ),
                                            'tooltip'=> __('Label Color', 'product-blocks'),
                                        ),
                                        'sales_radius' => array(
                                            'type' => 'number',
                                            'plus_minus' => true,
                                            'label' => __('Notification Radius', 'product-blocks'),
                                            'default' => 50
                                        ),
                                        'sales_image_radius' => array(
                                            'type' => 'number',
                                            'plus_minus' => true,
                                            'label' => __('Image Radius', 'product-blocks'),
                                            'default' => 50
                                        ),
                                    )
                                )
                            )
                        )
                    )
                )
            )
        )
    );
    return array_merge($config, $arr);
}