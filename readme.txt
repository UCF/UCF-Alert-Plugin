=== UCF Alert Plugin ===
Contributors: ucfwebcom
Tags: ucf, alerts
Requires at least: 4.7.3
Tested up to: 5.3
Stable tag: 2.0.1
License: GPLv3 or later
License URI: http://www.gnu.org/copyleft/gpl-3.0.html

Provides a shortcode, functions, and default styles for displaying UCF alerts.


== Description ==

This plugin provides a shortcode, helper functions, and default styles for displaying latest alerts from [ucf.edu/alert](https://ucf.edu/alert).  It is written to work out-of-the-box for non-programmers, but is also extensible and customizable for developers.

Note: jQuery is *required* for the JavaScript included with this plugin to work.


== Documentation ==

Head over to the [UCF Alert Plugin wiki](https://github.com/UCF/UCF-Alert-Plugin/wiki) for detailed information about this plugin, installation instructions, and more.


== Changelog ==

= 2.0.1 =
Enhancements:
* Updated packages and added linter configs, issue/PR templates and a CONTRIBUTING doc.
* Added cache busting to enqueued assets.

Bug fixes:
* Updated `ucfalert.removed` trigger to only fire when an alert is actually removed from the DOM.

= 2.0.0 =
* Removed plugin options for specifying global alert links and call-to-action text, in favor of using per-alert values provided by the Alert theme's RSS feed.
* Updated default alert layout to remove references to deleted plugin options.
* Updated plugin js to populate alert URL and CTA values.

= 1.0.0 =
* Initial release


== Upgrade Notice ==

n/a


== Development ==

Note that compiled, minified css and js files are included within the repo.  Changes to these files should be tracked via git (so that users installing the plugin using traditional installation methods will have a working plugin out-of-the-box.)

[Enabling debug mode](https://codex.wordpress.org/Debugging_in_WordPress) in your `wp-config.php` file is recommended during development to help catch warnings and bugs.

= Requirements =
* node v16+
* gulp-cli

= Instructions =
1. Clone the UCF-Alert-Plugin repo into your local development environment, within your WordPress installation's `plugins/` directory: `git clone https://github.com/UCF/UCF-Alert-Plugin.git`
2. `cd` into the new UCF-Alert-Plugin directory, and run `npm install` to install required packages for development into `node_modules/` within the repo
3. Optional: If you'd like to enable [BrowserSync](https://browsersync.io) for local development, or make other changes to this project's default gulp configuration, copy `gulp-config.template.json`, make any desired changes, and save as `gulp-config.json`.

    To enable BrowserSync, set `sync` to `true` and assign `target` the base URL of a site on your local WordPress instance that will use this plugin, such as `http://localhost/wordpress/my-site/`.  Your `target` value will vary depending on your local host setup.

    The full list of modifiable config values can be viewed in `gulpfile.js` (see `config` variable).
3. Run `gulp default` to process front-end assets.
4. If you haven't already done so, create a new WordPress site on your development environment to test this plugin against.
5. Activate this plugin on your development WordPress site.
6. Configure plugin settings from the WordPress admin under "UCF Alert".
7. Run `gulp watch` to continuously watch changes to scss and js files.  If you enabled BrowserSync in `gulp-config.json`, it will also reload your browser when plugin files change.

= Other Notes =
* This plugin's README.md file is automatically generated. Please only make modifications to the README.txt file, and make sure the `gulp readme` command has been run before committing README changes.  See the [contributing guidelines](https://github.com/UCF/UCF-Alert-Plugin/blob/master/CONTRIBUTING.md) for more information.


== Contributing ==

Want to submit a bug report or feature request?  Check out our [contributing guidelines](https://github.com/UCF/UCF-Alert-Plugin/blob/master/CONTRIBUTING.md) for more information.  We'd love to hear from you!
