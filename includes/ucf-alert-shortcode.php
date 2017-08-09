<?php
/**
 * Handles the registration of the UCF Alert Shortcode
 **/

if ( !function_exists( 'sc_ucf_alert' ) ) {

	function sc_ucf_alert( $atts, $content='' ) {
		$atts = shortcode_atts( UCF_Alert_Config::get_option_defaults(), $atts, 'sc_ucf_alert' );

		ob_start();

		echo UCF_Alert_Common::display_alert( $atts['layout'], $atts );

		return ob_get_clean(); // Shortcode must *return*!  Do not echo the result!
	}

	add_shortcode( 'ucf-alert', 'sc_ucf_alert' );

}

if ( ! function_exists( 'ucf_alert_shortcode_interface' ) ) {
	function ucf_alert_shortcode_interface( $shortcodes ) {
		$settings = array(
			'command' => 'ucf-alert',
			'name'    => 'UCF Alert',
			'desc'    => 'Displays the latest alert from ucf.edu/alert if one is active.',
			'fields'  => array(),
			'content' => false
		);

		$shortcodes[] = $settings;

		return $shortcodes;
	}
}
