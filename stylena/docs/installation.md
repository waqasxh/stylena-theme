# Installation

## Requirements
- WordPress 6.8 or higher (tested up to 6.8)
- PHP 8.1 or higher
- MySQL 5.6 or higher (8.0+ recommended)
- HTTPS recommended

## Install the theme
1. In the WordPress admin go to Appearance > Themes > Add New > Upload Theme.
2. Choose the `stylena.zip` from your download (use the zip that contains the `stylena` folder, not the whole package) and click Install Now.
3. Click Activate. (Manual alternative: upload the `stylena` folder to `/wp-content/themes/` by FTP.)

Activation does not create any content. Sample content is added only by the demo import.

## Install the plugins
After activation, a notice links to Appearance > Install Plugins.
1. Install and activate **Stylena Core** (required, bundled with the theme). It provides the Services, Team, Portfolio, FAQ and Image Slider content types. Without it those sections are empty (your content is not deleted).
2. Optional, one click each from WordPress.org: **One Click Demo Import**, **Contact Form 7**, **Yoast SEO**. See [Recommended plugins](recommended-plugins.md).

## First setup
1. Import the demo on a fresh site ([Demo import](demo-import.md)), or start empty.
2. Appearance > Menus: create menus and assign them to Primary, Top Left and Top Right locations.
3. Settings > Reading: set a static front page (the demo import does this for you).
4. Appearance > Customize > Site Identity: upload your logo.
5. Appearance > Customize > Stylena Theme Options: see the [Customizer guide](customizer.md).

## Updating
Back up files and database, then upload the new theme over the old one (Appearance > Themes > Add New > Upload, choose "Replace current with uploaded"). Customizer settings are stored in the database and are kept. Put code changes in a child theme, not in the theme files.
