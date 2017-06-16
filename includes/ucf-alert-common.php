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
		// CSS
		$include_css = UCF_Alert_Config::get_option_or_default( 'include_css' );
		$css_deps = apply_filters( 'ucf_alert_style_deps', array() );

		if ( $include_css ) {
			wp_enqueue_style( 'ucf_alert_css', plugins_url( 'static/css/ucf-alert.min.css', UCF_ALERT__PLUGIN_FILE ), $css_deps, false, 'screen' );
		}

		// JS
		$include_js = UCF_Alert_Config::get_option_or_default( 'include_js_main' );
		$include_js_deps = UCF_Alert_Config::get_option_or_default( 'include_js_deps' );
		$js_deps = array( 'jquery' );
		if ( $include_js_deps ) {
			$js_deps[] = 'js-cookie';
		}
		$js_deps = apply_filters( 'ucf_alert_script_deps', $js_deps );

		if ( $include_js ) {
			if ( $include_js_deps ) {
				wp_enqueue_script( 'js-cookie', 'https://cdnjs.cloudflare.com/ajax/libs/js-cookie/2.1.4/js.cookie.min.js', false, false, true );
			}
			wp_enqueue_script( 'ucf_alert_js', plugins_url( 'static/js/ucf-alert.min.js', UCF_ALERT__PLUGIN_FILE ), $js_deps, false, true );
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
