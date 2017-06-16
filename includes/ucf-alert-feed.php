<?php
/**
 * Handles all feed related code.
 **/

if ( !class_exists( 'UCF_Alert_Feed' ) ) {

	class UCF_Alert_Feed {
		public static function get_alert( $args ) {
			$args           = UCF_Alert_Config::apply_option_defaults( $args );
			$feed_url       = $args['feed_url'];

			// Fetch new degree data
			$response = wp_remote_get( $feed_url, array( 'timeout' => 15 ) );

			if ( is_array( $response ) ) {
				$items = json_decode( wp_remote_retrieve_body( $response ) );
			}
			else {
				$items = false;
			}

			return $items;
		}
	}

}
