# UCF Alert Plugin #

Provides a shortcode, functions, and default styles for displaying UCF alerts.


## Description ##

This plugin provides a shortcode, helper functions, and default styles for displaying latest alerts from [ucf.edu/alert](https://ucf.edu/alert).  It is written to work out-of-the-box for non-programmers, but is also extensible and customizable for developers.

Note: jQuery is *required* for the JavaScript included with this plugin to work.


## Installation ##

### Manual Installation ###
1. Upload the plugin files (unzipped) to the `/wp-content/plugins` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the "Plugins" screen in WordPress
3. Configure plugin settings from the WordPress admin under "Settings > UCF Alert".

### WP CLI Installation ###
1. `$ wp plugin install --activate https://github.com/UCF/UCF-Alert-Plugin/archive/master.zip`.  See [WP-CLI Docs](http://wp-cli.org/commands/plugin/install/) for more command options.
2. Configure plugin settings from the WordPress admin under "Settings > UCF Alert".


## Changelog ##

### 2.0.0 ###
* Removed plugin options for specifying global alert links and call-to-action text, in favor of using per-alert values provided by the Alert theme's RSS feed.
* Updated default alert layout to remove references to deleted plugin options.
* Updated plugin js to populate alert URL and CTA values.

### 1.0.0 ###
* Initial release


## Upgrade Notice ##

n/a


## Installation Requirements ##

None


## Development & Contributing ##

NOTE: this plugin's readme.md file is automatically generated.  Please only make modifications to the readme.txt file, and make sure the `gulp readme` command has been run before committing readme changes.
