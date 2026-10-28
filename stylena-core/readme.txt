=== Stylena Core ===
Contributors: zylonex
Tags: services, team, portfolio, faq, slider
Requires at least: 6.8
Tested up to: 6.8
Requires PHP: 8.1
Stable tag: 1.0.0
License: GPL-2.0-or-later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Companion plugin for the Stylena theme: Services, Team, Portfolio, FAQ and Image Slider content types, shortcode and widgets.

== Description ==

Stylena Core registers the content types used by the Stylena theme so your content stays yours if you ever change themes.

* Services (with Service Categories)
* Team Members
* Portfolio Items
* FAQs
* Image Sliders, with the `[stylena_slider]` shortcode
* Widgets: Stylena Image Slider and Stylena Service Categories

Content you create is stored in your database and is never deleted when the plugin is deactivated or removed. The plugin makes no remote requests, loads no third-party services and does not create any content on activation.

== Installation ==

1. Upload the `stylena-core` folder to `/wp-content/plugins/`, or install it from the "required plugin" prompt shown after activating the Stylena theme.
2. Activate the plugin through the Plugins screen.
3. Add content under Services, Team, Portfolio, FAQs and Image Sliders in the admin menu.
4. To show a slider anywhere, use the shortcode `[stylena_slider]` or the Stylena Image Slider widget.

Upgrading from an older Stylena version that registered these content types itself: install and activate Stylena Core after updating the theme. Existing content is kept.

== Frequently Asked Questions ==

= Do I need this plugin with the Stylena theme? =

Yes. The theme's Services, Team, Portfolio, FAQ and Slider sections get their content from this plugin.

= What happens if I deactivate the plugin? =

The theme keeps working, but those sections show no items and the `[stylena_slider]` shortcode and widgets are unavailable. Nothing is deleted; everything reappears when you activate the plugin again.

= Is my content deleted if I uninstall the plugin? =

No. Posts, terms and meta are kept. Only the plugin's version and rewrite flags are removed. Content is hidden until the plugin is active again.

= Does the plugin contact external services? =

No.

== External services ==

This plugin does not connect to any external service.

== Changelog ==

= 1.0.0 =
* Initial release: Services, Team, Portfolio, FAQ and Image Slider post types, the Service Category taxonomy, the [stylena_slider] shortcode and two widgets.

== Upgrade Notice ==

= 1.0.0 =
Required by the Stylena theme for Services, Team, Portfolio, FAQ and Slider content.
