# Reviews

The Reviews section appears on the home page and on any page that uses the **Reviews (Full Width)** template. Both use the same renderer, so they always match.

## Option 1: Site Reviews plugin (recommended)

1. Go to **Appearance > Install Plugins** and install and activate **Site Reviews** (free, from WordPress.org). It is recommended, not required, and is not bundled with the theme. It needs PHP 8.1 and WordPress 6.8 or newer.
2. Add or import reviews in **Site Reviews** in the admin menu.
3. The theme shows them automatically inside the Reviews section. The number shown and the section title come from **Customize > Reviews Section** (count and title).

Reviews are stored in your own database, so no visitor request goes to a third party for the review list.

Site Reviews is a separate plugin with its own privacy and third-party behaviour (for example optional review submission, spam checks and integrations). Review its settings and the [Site Reviews privacy information](https://wordpress.org/plugins/site-reviews/) before enabling those features.

To keep the built-in cards even when Site Reviews is active, untick **Use the Site Reviews plugin when it is active** in **Customize > Reviews Section**.

## Option 2: built-in cards (no plugin)

If Site Reviews is not active, the theme shows Google reviews (optional, off by default) or up to three text reviews you type in the Customizer. See the Customizer guide for the Google opt-in and API key. If there is nothing to show, the theme displays three generic **sample review cards** so a fresh install looks complete. Sample cards are used only when there is no Google data and no custom text review; real reviews always replace them. Logged-in users who can edit theme options see a short notice above the cards ("Sample reviews shown. Replace them under Customize > Reviews Section, or install Site Reviews."); visitors do not. To remove the samples, add a real review or untick **Enable Reviews**.

## Turning the section off

Untick **Enable Reviews** in **Customize > Reviews Section**.
