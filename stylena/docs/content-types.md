# Content Types

Services, Team Members, Portfolio Items, FAQs and Image Sliders, plus the Service Categories taxonomy, are provided by the **Stylena Core** plugin (not the theme), so your content survives a theme switch. Install and activate it from Appearance > Install Plugins. If it is inactive the sections are empty and the shortcode and widgets are unavailable; nothing is deleted.

| Type | Admin menu | Notes |
| --- | --- | --- |
| Services | Services (+ Service Categories) | Title, description, price and duration fields, category, featured image (5:7 recommended). Each service has a detail page. Shown on the home page and on the page template "Services (Full Width)". |
| Team | Team Members | Name (title), position, bio, specialties, photo. Shown in the home page carousel and on "Teams (Full Width)". |
| Portfolio | Portfolio Items | Manual portfolio items; template "Portfolio (Full - Width)". |
| FAQ | FAQs | Question (title), answer (content), "show in footer section" option, order (lower first). Displayed as an accordion. |
| Slider | Image Sliders | Title, subtitle, content, featured image, button text and URL, text position, text colour, overlay opacity. |

Slider usage: `[stylena_slider]` with optional attributes `height`, `autoplay`, `autoplay_speed`, `show_arrows`, `show_dots`. Stylena Core also provides the "Stylena Image Slider" and "Stylena Service Categories" widgets. Field names are defined by the plugin; see `plugins/stylena-core/readme.txt`.

## Page templates (in the theme)
Services (Full Width), Portfolio (Full - Width), Teams (Full Width), About Us (Full Width), Contact Us (Full Width), Reviews (Full Width), Membership (Full Width), Full Width. Choose under Page Attributes > Template.
