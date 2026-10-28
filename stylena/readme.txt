=== Stylena ===
Requires at least: 6.8
Tested up to: 6.8
Requires PHP: 8.1
Stable tag: 1.3.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Tags: one-column, two-columns, three-columns, left-sidebar, right-sidebar, custom-menu, featured-images, post-formats, threaded-comments, translation-ready, editor-style, blog, full-width-template

A theme for beauty and wellness businesses: salons, spas, clinics. Full-width hero with optional background video, services, team, portfolio, FAQ and slider.

== Description ==

Stylena is a classic PHP theme for beauty and wellness websites. It has a full-width homepage hero with an optional background video, a two-row navigation, page templates (Services, Portfolio, Team, About, Contact, Reviews, Membership), Google Reviews with manual fallback, AJAX live search, and a Customizer panel for fonts, colours, header, footer and sections. Fonts (Montserrat default, Exo, Aboreto) are self-hosted. It is translation-ready and includes an RTL stylesheet.

The Services, Team, Portfolio, FAQ and Image Slider content types come from the bundled required companion plugin Stylena Core, so your content stays when you switch themes. Contact Form 7, One Click Demo Import and Yoast SEO are optional.

== Installation ==

1. In the admin go to Appearance > Themes > Add New > Upload Theme, choose the theme zip and activate it.
2. Go to Appearance > Install Plugins and install and activate Stylena Core (required). Optionally add One Click Demo Import, Contact Form 7 and Yoast SEO.
3. Optional: Appearance > Import Demo Data to import the sample site on a fresh site.
4. Customize under Appearance > Customize > Stylena Theme Options.

Full guides are in the docs folder inside the theme.

== Frequently Asked Questions ==

= Does the theme work without Stylena Core? =
It loads, but Services, Team, Portfolio, FAQ and Slider sections are empty until the plugin is active. Content is never deleted.

= Are the demo photos included? =
No. The AI-generated sample images and hero video are downloaded from the demo server only when an administrator runs the demo import. If the server is unreachable the theme keeps neutral placeholders. Replace them with your own media.

= Does the theme load Google Fonts? =
No, not by default. Only if you enable "Load other fonts from Google Fonts" in the Customizer.

= How do I change the hero video? =
Appearance > Customize > Stylena Theme Options > Hero Section: Hero Video and Hero Poster Image.

== External Services ==

Stylena makes no third-party requests by default (fonts are self-hosted). Every external contact below is optional and initiated by an administrator:

* Demo media download (Appearance > Import Demo Data): downloads about 30 AI-generated sample images and one short looping video from stylena.zylonex.com into your Media Library. Your server's IP address is visible to that host. The files may become unavailable at any time; the demo then imports without them and the theme keeps its built-in placeholders. Not used on normal page views.
* Google Fonts (Customizer > Typography, off by default): loads non-bundled font families from Google when you enable it. Service: Google Fonts, privacy policy https://policies.google.com/privacy
* Google Places API (Customizer > Reviews Section, opt-in "Allow contact with Google (Places API)", off by default): only after you tick the opt-in and enter your own API key and Place ID, your server requests your reviews from maps.googleapis.com (sends the server's IP address, Place ID and API key; cached about 12 hours). The API key is stored in the non-autoloaded database option stylena_google_places_key (not in theme settings, so it is not part of Customizer or demo exports); you can instead define STYLENA_GOOGLE_PLACES_KEY in wp-config.php, which takes precedence. The option remains in the database if the theme is switched; delete it manually (wp option delete stylena_google_places_key) to remove it. Nothing is requested from Google before the opt-in. Service: Google Maps Platform, terms https://cloud.google.com/maps-platform/terms and privacy policy https://policies.google.com/privacy

== Changelog ==

= 1.3.0 =
* Change: The home page Portfolio is now a slider (3 items on desktop, 2 on tablet, 1 on phone) with previous/next buttons like Services and Team; it moved below the image slider, above Reviews and Need Advice.
* New: View All buttons under the Services, Team and Portfolio sections (they appear when the matching page exists).
* New: Reviews section uses the Site Reviews plugin when it is active (Customize > Reviews Section > Use the Site Reviews plugin), shared by the home page and the Reviews page template; the built-in Google/custom cards remain the fallback. See docs/reviews.md.
* New: Portfolio feature: responsive grid with Load More, accessible lightbox (Esc, arrows, focus trap), category filter buttons, Portfolio Categories taxonomy, and a home page Portfolio section. Customizer keys: stylena_home_portfolio_enable/_title/_subtitle/_count, stylena_portfolio_lightbox, stylena_portfolio_show_filters, stylena_portfolio_posts_count.
* Change: The home anchor #portfolio now reaches the new Portfolio section; the Team section is #team. Update any old menu links that pointed to #portfolio expecting Team.
* Change: The Instagram feed/API integration was removed (no Instagram token, opt-in or Appearance > Instagram Feed screen). The Portfolio now shows your own Portfolio Items; plain Instagram profile links in the footer remain. Items per load: Customize > Portfolio.
* Change: Minimum requirements raised to PHP 8.1 and WordPress 6.8 (needed by the recommended Site Reviews plugin).
* New: Site Reviews is recommended on Appearance > Install Plugins (TGMPA); it is optional and not installed automatically.
* Fix: Slider no longer shows a blank gap at the left margin (left buffer slides prepended).
* Change: Header offset and hero background video handling refined.
* New: Pause/play button for the hero background video (WCAG 2.2.2).
* Change: The Google API key field in the Customizer is now a masked password field.
* New: Yoast SEO added to the optional recommended plugins.
* New: Demo import also sets a hero background video (downloaded from the demo server).
* Change: Post-format badge images replaced with original SVG icons; screenshot refreshed.
* New: Demo import notice when sample images could not be downloaded; supports more than one media host.
* Change: AI-generated demo images are no longer bundled; the demo import downloads them and falls back to placeholders offline.
* Fix: Demo import only touches content it created.
* Change: Montserrat is the default font again; Exo, Aboreto and other choices remain available.
* Change: Services, Team, Portfolio, FAQ and Slider moved to the required companion plugin Stylena Core.
* Change: Licence changed to GPL-2.0-or-later.
* Fix: Accessibility, escaping and internationalisation improvements; JavaScript script-order errors.
* New: RTL stylesheet support.

= 1.2.7 =
* New: Full-width page templates for About Us, Contact Us, Reviews and Membership.
* Change: Removed the activation-time page seeder; demo pages come from the demo importer.
* Fix: The Services page now renders at /services/ (service post type archive disabled).

= 1.2.6 =
* Fix: Direct-access guard added to every PHP file.

= 1.2.5 =
* Fix: Theme description and docs no longer advertise the video background as always on.

= 1.2.4 =
* Fix: Removed non-redistributable default hero video.
* New: Customizer controls for Hero Video and Hero Poster Image.

= 1.2.3 =
* Fix: Removed an unlicensed font; replaced with Cormorant Garamond.

= 1.2.2 =
* Fix: Text domain mismatch, unescaped output, missing text domains.

Earlier history: see changelog.txt.

== Upgrade Notice ==

= 1.3.0 =
This release requires PHP 8.1+ and WordPress 6.8+. Check your hosting PHP version before updating. Also install and activate the companion plugin Stylena Core (Services, Team, Portfolio, FAQ, Slider), and re-enable the Google opt-in in the Customizer if you use it.

== Credits ==

Stylena is licensed under GPL v2 or later. Bundled third-party resources:

| Component | Licence | Notes |
| --- | --- | --- |
| Bootstrap 4.6.2 | MIT | assets/ (licence: assets/bootstrap-license.txt) |
| Font Awesome Free 5.15.4 | Font Awesome Free License (icons CC BY 4.0, fonts SIL OFL 1.1, code MIT) | assets/ |
| Exo font | SIL Open Font License 1.1 | fonts/exo/, self-hosted |
| Montserrat font | SIL Open Font License 1.1 | fonts/montserrat/, self-hosted |
| Aboreto font | SIL Open Font License 1.1 | fonts/aboreto/, self-hosted |
| TGM Plugin Activation 2.6.1 | GPL-2.0-or-later | inc/vendor/ (licence: inc/vendor/LICENSE-TGMPA.md) |

Author: Zylonex, https://zylonex.com. The code is GPL-2.0-or-later.
AI-generated demo media: Sample photographs, the hero video and the theme screenshot are AI-generated (Google Gemini) and are not part of the theme download except screenshot.png (preview only). They cover the hero poster, founder and team portraits, services, slider, portfolio, blog, membership card, map and similar images, all generated with the Google Gemini image generation API, not photographed. The demo AI media is separate from the GPL-licensed code. See docs/ai-disclosure.md. The people shown are synthetic and do not depict real individuals. These images are not included in the theme download; the demo import downloads them from the demo server. The hero background video is a looping zoom animation made from the AI hero poster (no third-party video or audio). Replace them with your own before launching a real site. Provenance is in demo/README.txt.
