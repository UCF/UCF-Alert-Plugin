<?php
/**
 * Place common functions here.
 **/

if ( !class_exists( 'UCF_Alert_Common' ) ) {

	class UCF_Alert_Common {
		public function display_alert( $layout='classic' ) {

			if ( has_action( 'ucf_alert_display_' . $layout . '_before' ) ) {
				do_action( 'ucf_alert_display_' . $layout . '_before' );
			}

			if ( has_action( 'ucf_alert_display_' . $layout  ) ) {
				do_action( 'ucf_alert_display_' . $layout );
			}

			if ( has_action( 'ucf_alert_display_' . $layout . '_after' ) ) {
				do_action( 'ucf_alert_display_' . $layout . '_after' );
			}
		}
	}
}

if ( !function_exists( 'ucf_alert_display_classic_before' ) ) {

	function ucf_alert_display_classic_before() {
		$id = 'ucf-alert-' . wp_rand(); // just need some unique identifier here
		ob_start();
	?>
		<div data-script-id="<?php echo $id; ?>" class="ucf-alert-wrapper"></div>
		<script type="text/html" id="<?php echo $id; ?>">
			<div class="ucf-alert ucf-alert-classic" data-alert-id="" role="alert">
	<?php
		echo ob_get_clean();
	}

	add_action( 'ucf_alert_display_classic_before', 'ucf_alert_display_classic_before', 10, 1 );

}

if ( !function_exists( 'ucf_alert_display_classic' ) ) {

	function ucf_alert_display_classic() {
		ob_start();
	?>
		<button class="ucf-alert-close" aria-label="Close alert">&times;</button>
		<a class="ucf-alert-content" href="<?php echo UCF_Alert_Config::get_option_or_default( 'alerts_url' ); ?>">
			<strong class="ucf-alert-title"></strong>
			<div class="ucf-alert-body"></div>
			<div class="ucf-alert-cta">
				<?php echo UCF_Alert_Config::get_option_or_default( 'cta' ); ?>
			</div>
		</a>
	<?php
		echo ob_get_clean();
	}

	add_action( 'ucf_alert_display_classic', 'ucf_alert_display_classic', 10, 0 );

}

if ( !function_exists( 'ucf_alert_display_classic_after' ) ) {

	function ucf_alert_display_classic_after() {
		ob_start();
	?>
			</div>
		</script>
	<?php
		echo ob_get_clean();
	}

	add_action( 'ucf_alert_display_classic_after', 'ucf_alert_display_classic_after', 10, 0 );

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
			$site_url = parse_url( get_site_url() );
			wp_register_script( 'ucf_alert_js', plugins_url( 'static/js/ucf-alert.min.js', UCF_ALERT__PLUGIN_FILE ), $js_deps, false, true );
			wp_localize_script( 'ucf_alert_js', 'UCFAlert', array(
				'url' => UCF_Alert_Config::get_option_or_default( 'feed_url' ),
				'refreshInterval' => UCF_Alert_Config::get_option_or_default( 'refresh_interval' ) * 1000,
				'domain' => $site_url['host']
			));

			if ( $include_js_deps ) {
				wp_enqueue_script( 'js-cookie', 'https://cdnjs.cloudflare.com/ajax/libs/js-cookie/2.1.4/js.cookie.min.js', false, false, true );
			}

			wp_enqueue_script( 'ucf_alert_js' );
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
