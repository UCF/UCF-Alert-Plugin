<?php
/**
 * Handles plugin configuration
 */

if ( !class_exists( 'UCF_Alert_Config' ) ) {

	class UCF_Alert_Config {
		public static
			$option_prefix = 'ucf_alert_',
			$option_defaults = array(
				'layout'          => 'classic',
				'feed_url'        => 'http://www.ucf.edu/alert/feed/?post_type=alert',
				'include_css'     => true,
				'include_js_main' => true,
				'include_js_deps' => true
			);

		public static function get_layouts() {
			$layouts = array(
				'classic' => 'Classic Layout',
			);

			$layouts = apply_filters( self::$option_prefix . 'get_layouts', $layouts );

			return $layouts;
		}

		/**
		 * Creates options via the WP Options API that are utilized by the
		 * plugin.  Intended to be run on plugin activation.
		 *
		 * @return void
		 **/
		public static function add_options() {
			$defaults = self::$option_defaults; // don't use self::get_option_defaults() here (default options haven't been set yet)

			add_option( self::$option_prefix . 'feed_url', $defaults['feed_url'] );
			add_option( self::$option_prefix . 'include_css', $defaults['include_css'] );
			add_option( self::$option_prefix . 'include_js_main', $defaults['include_js_main'] );
			add_option( self::$option_prefix . 'include_js_deps', $defaults['include_js_deps'] );
		}

		/**
		 * Deletes options via the WP Options API that are utilized by the
		 * plugin.  Intended to be run on plugin uninstallation.
		 *
		 * @return void
		 **/
		public static function delete_options() {
			delete_option( self::$option_prefix . 'feed_url' );
			delete_option( self::$option_prefix . 'include_css' );
			delete_option( self::$option_prefix . 'include_js_main' );
			delete_option( self::$option_prefix . 'include_js_deps' );
		}

		/**
		 * Returns a list of default plugin options. Applies any overridden
		 * default values set within the options page.
		 *
		 * @return array
		 **/
		public static function get_option_defaults() {
			$defaults = self::$option_defaults;

			// Apply default values configurable within the options page:
			$configurable_defaults = array(
				'feed_url'        => get_option( self::$option_prefix . 'feed_url', $defaults['feed_url'] ),
				'include_css'     => get_option( self::$option_prefix . 'include_css', $defaults['include_css'] ),
				'include_js_main' => get_option( self::$option_prefix . 'include_js_main', $defaults['include_js_main'] ),
				'include_js_deps' => get_option( self::$option_prefix . 'include_js_deps', $defaults['include_js_deps'] )
			);

			// Force configurable options to override $defaults, even if they are empty:
			$defaults = array_merge( $defaults, $configurable_defaults );

			return $defaults;
		}

		/**
		 * Performs typecasting, sanitization, etc on an array of plugin options.
		 *
		 * @param array $list | Assoc. array of plugin options, e.g. [ 'option_name' => 'val', ... ]
		 * @return array
		 **/
		public static function format_options( $list ) {
			foreach ( $list as $key => $val ) {
				switch ( $key ) {
					case 'include_css':
					case 'include_js_main':
					case 'include_js_deps':
						$list[$key] = filter_var( $val, FILTER_VALIDATE_BOOLEAN );
						break;
					default:
						break;
				}
			}

			return $list;
		}

		/**
		 * Applies formatting to a single option. Intended to be passed to the
		 * 'option_{$option}' hook.
		 **/
		public static function format_option( $value, $option_name ) {
			$option_formatted = self::format_options( array( $option_name => $value ) );
			return $option_formatted[$option_name];
		}

		/**
		 * Applies formatting to an array of shortcode attributes. Intended to
		 * be passed to the 'shortcode_atts_sc_ucf_alert' hook.
		 **/
		public static function format_sc_atts( $out, $pairs, $atts, $shortcode ) {
			return self::format_options( $out );
		}

		/**
		 * Adds filters for shortcode and plugin options that apply our
		 * formatting rules to attribute/option values.
		 **/
		public static function add_option_formatting_filters() {
			// Options
			$defaults = self::$option_defaults;
			foreach ( $defaults as $option => $default ) {
				add_filter( 'option_{$option}', array( 'UCF_Alert_Config', 'format_option' ), 10, 2 );
			}
			// Shortcode atts
			add_filter( 'shortcode_atts_sc_ucf_alert', array( 'UCF_Alert_Config', 'format_sc_atts' ), 10, 4 );
		}

		/**
		 * Convenience method for returning an option from the WP Options API
		 * or a plugin option default.
		 *
		 * @param $option_name
		 * @return mixed
		 **/
		public static function get_option_or_default( $option_name ) {
			// Handle $option_name passed in with or without self::$option_prefix applied:
			$option_name_no_prefix = str_replace( self::$option_prefix, '', $option_name );
			$option_name           = self::$option_prefix . $option_name_no_prefix;
			$defaults              = self::get_option_defaults();

			return get_option( $option_name, $defaults[$option_name_no_prefix] );
		}

		/**
		 * Initializes setting registration with the Settings API.
		 **/
		public static function settings_init() {
			// Register settings
			register_setting( 'ucf_alert', self::$option_prefix . 'feed_url' );
			register_setting( 'ucf_alert', self::$option_prefix . 'include_css' );
			register_setting( 'ucf_alert', self::$option_prefix . 'include_js_main' );
			register_setting( 'ucf_alert', self::$option_prefix . 'include_js_deps' );

			// Register setting sections
			add_settings_section(
				'ucf_alert_section_general', // option section slug
				'General Settings', // formatted title
				'', // callback that echoes any content at the top of the section
				'ucf_alert' // settings page slug
			);

			// Register fields
			add_settings_field(
				self::$option_prefix . 'feed_url',
				'UCF Alert RSS Feed URL',  // formatted field title
				array( 'UCF_Alert_Config', 'display_settings_field' ), // display callback
				'ucf_alert',  // settings page slug
				'ucf_alert_section_general',  // option section slug
				array(  // extra arguments to pass to the callback function
					'label_for'   => self::$option_prefix . 'feed_url',
					'description' => 'The feed URL to use for alerts from UCF\'s alert system.',
					'type'        => 'text'
				)
			);
			add_settings_field(
				self::$option_prefix . 'include_css',
				'Include Default CSS',  // formatted field title
				array( 'UCF_Alert_Config', 'display_settings_field' ),  // display callback
				'ucf_alert',  // settings page slug
				'ucf_alert_section_general',  // option section slug
				array(  // extra arguments to pass to the callback function
					'label_for'   => self::$option_prefix . 'include_css',
					'description' => 'Include the default css stylesheet for alerts within the theme.<br>Leave this checkbox checked unless your theme provides custom styles for alerts.',
					'type'        => 'checkbox'
				)
			);
			add_settings_field(
				self::$option_prefix . 'include_js_main',
				'Include Default JS',  // formatted field title
				array( 'UCF_Alert_Config', 'display_settings_field' ),  // display callback
				'ucf_alert',  // settings page slug
				'ucf_alert_section_general',  // option section slug
				array(  // extra arguments to pass to the callback function
					'label_for'   => self::$option_prefix . 'include_js_main',
					'description' => 'Include the default JavaScript for alerts within the theme.<br>Leave this checkbox checked unless your theme provides custom display logic for alerts.',
					'type'        => 'checkbox'
				)
			);
			add_settings_field(
				self::$option_prefix . 'include_js_deps',
				'Include JS Dependencies',  // formatted field title
				array( 'UCF_Alert_Config', 'display_settings_field' ),  // display callback
				'ucf_alert',  // settings page slug
				'ucf_alert_section_general',  // option section slug
				array(  // extra arguments to pass to the callback function
					'label_for'   => self::$option_prefix . 'include_js_deps',
					'description' => 'Include the JavaScript dependencies for alerts within the theme. Default JS must be enabled for dependencies to be loaded.<br>Leave this checkbox checked unless your theme provides dependent scripts (js-cookie) already.',
					'type'        => 'checkbox'
				)
			);
		}

		/**
		 * Displays an individual setting's field markup.
		 **/
		public static function display_settings_field( $args ) {
			$option_name   = $args['label_for'];
			$description   = $args['description'];
			$field_type    = $args['type'];
			$current_value = self::get_option_or_default( $option_name );
			$markup        = '';

			switch ( $field_type ) {
				case 'checkbox':
					ob_start();
				?>
					<input type="checkbox" id="<?php echo $option_name; ?>" name="<?php echo $option_name; ?>" <?php echo ( $current_value == true ) ? 'checked' : ''; ?>>
					<p class="description">
						<?php echo $description; ?>
					</p>
				<?php
					$markup = ob_get_clean();
					break;

				case 'number':
					ob_start();
				?>
					<input type="number" id="<?php echo $option_name; ?>" name="<?php echo $option_name; ?>" value="<?php echo $current_value; ?>">
					<p class="description">
						<?php echo $description; ?>
					</p>
				<?php
					$markup = ob_get_clean();
					break;

				case 'text':
				default:
					ob_start();
				?>
					<input type="text" id="<?php echo $option_name; ?>" name="<?php echo $option_name; ?>" value="<?php echo $current_value; ?>">
					<p class="description">
						<?php echo $description; ?>
					</p>
				<?php
					$markup = ob_get_clean();
					break;
			}
		?>

		<?php
			echo $markup;
		}


		/**
		 * Registers the settings page to display in the WordPress admin.
		 **/
		public static function add_options_page() {
			$page_title = 'UCF Alert Settings';
			$menu_title = 'UCF Alert';
			$capability = 'manage_options';
			$menu_slug  = 'ucf_alert';
			$callback   = array( 'UCF_Alert_Config', 'options_page_html' );

			return add_options_page(
				$page_title,
				$menu_title,
				$capability,
				$menu_slug,
				$callback
			);
		}


		/**
		 * Displays the plugin's settings page form.
		 **/
		public static function options_page_html() {
			ob_start();
		?>

		<div class="wrap">
			<h1><?php echo get_admin_page_title(); ?></h1>
			<form method="post" action="options.php">
				<?php
				settings_fields( 'ucf_alert' );
				do_settings_sections( 'ucf_alert' );
				submit_button();
				?>
			</form>
		</div>

		<?php
			echo ob_get_clean();
		}

	}

	// Register settings and options.
	add_action( 'admin_init', array( 'UCF_Alert_Config', 'settings_init' ) );
	add_action( 'admin_menu', array( 'UCF_Alert_Config', 'add_options_page' ) );

	// Apply custom formatting to shortcode attributes and options.
	UCF_Alert_Config::add_option_formatting_filters();
}
