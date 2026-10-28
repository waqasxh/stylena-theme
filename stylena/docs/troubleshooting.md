# Troubleshooting

**Services, Team, Portfolio, FAQ or Slider sections are empty**
Activate Stylena Core (Appearance > Install Plugins or Plugins). Then check that items exist and are published. Sample items exist only if you ran the demo import; activation does not create any.

**Demo import: "install and activate Stylena Core"**
Activate Stylena Core, then run the import again.

**Demo import: some images are missing**
The demo media server was unreachable. The built-in placeholders are used. See [Demo import](demo-import.md); add your own images or retry later. Also check that your host allows outbound HTTPS and a long enough PHP time limit.

**Imported twice, duplicates appeared**
Delete the duplicates, or reset to a fresh site and import once.

**Reviews do not show**
Check Reviews Section: Enable, "Allow contact with Google (Places API)" (off by default; without it only fallback reviews show), Google API Key, Place ID. Enable Places API and billing in Google Cloud. Administrators can use the "Clear Reviews Cache" button in the Reviews Section to clear the 12-hour cache. Errors: `REQUEST_DENIED` (enable Places API/billing), `INVALID_REQUEST` (wrong Place ID), `OVER_QUERY_LIMIT` (quota). Fallback reviews are shown if the API fails.

**Fonts look different from expected**
Montserrat is the default. Fonts other than Montserrat, Exo and Aboreto need "Load other fonts from Google Fonts" enabled in Typography.

**Hero video not playing**
Use MP4 (H.264), keep it small, and check the poster image is set. Some mobile modes block autoplay; the poster is shown instead.

**Layout broken or styles missing**
Clear caching plugins and the browser cache, check file permissions (644 files, 755 folders), deactivate plugins to test for conflicts, re-upload the theme.

**Live search returns nothing**
Make sure `admin-ajax.php` is reachable and that content exists.

**Site is slow**
Keep the hero video small (under about 10 MB, MP4/H.264) and compress images (WebP where possible). Enable a caching plugin and server-side Gzip/OPcache, use PHP 8.0 or newer, and avoid the optional Google Fonts setting if you do not need non-bundled fonts. If you hit memory limits, raise `WP_MEMORY_LIMIT` in wp-config.php (for example 256M) or ask your host.

**Menu link opened in a new tab**
Menu items set to "Open link in a new tab" keep their target and get `rel="noopener noreferrer"` automatically (merged with any rel you set). Enable "Link Target" under Screen Options on Appearance > Menus if the checkbox is hidden.

**Debugging**
In wp-config.php: `define( 'WP_DEBUG', true ); define( 'WP_DEBUG_LOG', true ); define( 'WP_DEBUG_DISPLAY', false );` then read `wp-content/debug.log`. Turn it off afterwards.
