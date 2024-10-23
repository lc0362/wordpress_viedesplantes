<?php
defined( 'ABSPATH' ) || exit;

add_filter( 'wopb_addons_config', 'wopb_divi_config' );
function wopb_divi_config( $config ) {
	$configuration = array(
		'name' => __( 'Divi', 'product-blocks' ),
		'desc' => __( 'Create your custom design with WowStore blocks, and templates and use the design with Divi Builder.', 'product-blocks' ),
		'is_pro' => false,
		'live' => 'https://www.wpxpo.com/wowstore/woocommerce-page-builder-integrations/?utm_source=db-wstore-addons&utm_medium=builder_integration-demo&utm_campaign=wstore-dashboard',
		'docs' => 'https://wpxpo.com/docs/wowstore/add-ons/divi-addon/addon_doc_args',
		'type' => 'integration',
		'priority' => 20
	);
	$config['wopb_divi'] = $configuration;
	return $config;
}