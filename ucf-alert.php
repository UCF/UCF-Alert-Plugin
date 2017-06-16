<?php
/*
Plugin Name: UCF Alert
Description: Contains shortcode and widget for displaying latest alerts from ucf.edu/alert.
Version: 1.0.0
Author: UCF Web Communications
License: GPL3
*/
if ( ! defined( 'WPINC' ) ) {
	die;
}


define( 'UCF_ALERT__PLUGIN_FILE', __FILE__ );

require_once 'includes/ucf-alert-config.php';
require_once 'includes/ucf-alert-feed.php';
require_once 'includes/ucf-alert-common.php';
require_once 'includes/ucf-alert-shortcode.php';


/**
 * Activation/deactivation hooks
 **/
if ( !function_exists( 'ucf_alert_plugin_activation' ) ) {
	function ucf_alert_plugin_activation() {
		return UCF_Alert_Config::add_options();
	}
}

if ( !function_exists( 'ucf_alert_plugin_deactivation' ) ) {
	function ucf_alert_plugin_deactivation() {
		return;
	}
}

register_activation_hook( UCF_ALERT__PLUGIN_FILE, 'ucf_alert_plugin_activation' );
register_deactivation_hook( UCF_ALERT__PLUGIN_FILE, 'ucf_alert_plugin_deactivation' );


/**
 * Plugin-dependent actions:
 **/
add_action( 'plugins_loaded', function() {
	// If the `WP-Shortcode-Interface` plugin is installed, add the shortcode
	// definitions.
	if ( class_exists( 'WP_SCIF_Config' ) ) {
		add_filter( 'wp_scif_add_shortcode', 'ucf_alert_shortcode_interface', 10, 1 );
	}

} );

?>
