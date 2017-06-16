<?php
/**
 * Place common functions here.
 **/

if ( !class_exists( 'UCF_Alert_Common' ) ) {

	class UCF_Alert_Common {
		public function display_alert( $items, $layout, $display_type='default' ) {

			if ( has_action( 'ucf_alert_display_' . $layout . '_before' ) ) {
				do_action( 'ucf_alert_display_' . $layout . '_before', $items, $display_type );
			}

			if ( has_action( 'ucf_alert_display_' . $layout  ) ) {
				do_action( 'ucf_alert_display_' . $layout, $items, $display_type );
			}

			if ( has_action( 'ucf_alert_display_' . $layout . '_after' ) ) {
				do_action( 'ucf_alert_display_' . $layout . '_after', $items, $display_type );
			}
		}
	}
}

if ( !function_exists( 'ucf_alert_display_classic_before' ) ) {

	function ucf_alert_display_classic_before( $items, $display_type ) {
		ob_start();
	?>
		<div class="ucf-alert ucf-alert-classic">
	<?php
		echo ob_get_clean();
	}

	add_action( 'ucf_alert_display_classic_before', 'ucf_alert_display_classic_before', 10, 3 );

}

if ( !function_exists( 'ucf_alert_display_classic' ) ) {

	function ucf_alert_display_classic( $items, $title ) {
		if ( ! is_array( $items ) ) { $items = array( $items ); }
		ob_start();
	?>
	TODO
	<?php
		echo ob_get_clean();
	}

	add_action( 'ucf_alert_display_classic', 'ucf_alert_display_classic', 10, 3 );

}

if ( !function_exists( 'ucf_alert_display_classic_after' ) ) {

	function ucf_alert_display_classic_after( $items, $title ) {
		ob_start();
	?>
		</div>
	<?php
		echo ob_get_clean();
	}

	add_action( 'ucf_alert_display_classic_after', 'ucf_alert_display_classic_after', 10, 3 );

}

if ( ! function_exists( 'ucf_alert_enqueue_assets' ) ) {
	function ucf_alert_enqueue_assets() {
		$include_css = UCF_Alert_Config::get_option_or_default( 'include_css' );

		if ( $include_css ) {
			wp_enqueue_style( 'ucf_alert_css', plugins_url( 'static/css/ucf-alert.min.css', UCF_ALERT__PLUGIN_FILE ), false, false, 'all' );
		}
	}

	add_action( 'wp_enqueue_scripts', 'ucf_alert_enqueue_assets' );
}

if ( ! function_exists( 'ucf_alert_whitelist_host' ) ) {
	function ucf_alert_whitelist_host( $allow, $host, $url ) {
		$default_url = UCF_Alert_Config::get_option_or_default( 'feed_url' );
		$default_host = parse_url( $default_url, PHP_URL_HOST );
		if ( $default_host === $host ) {
			$allow = true;
		}

		return $allow;
	}

	add_filter( 'http_request_host_is_external', 'ucf_alert_whitelist_host', 10, 3 );
}
