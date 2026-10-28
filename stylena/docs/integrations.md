# Optional integrations

Both integrations do nothing (no styles, no theme support) unless the plugin is active. Code: `inc/functions/integrations.php`.

## WooCommerce (basic compatibility)
- Enabled when the `WooCommerce` class exists. Adds theme support for `woocommerce` and the product gallery zoom, lightbox and slider.
- Shop pages are wrapped in the theme container (full width, same markup as pages); `css/woocommerce.css` styles buttons, notices, the product grid, cart/checkout tables and form fields.
- No cart link is added to the header. Not a full WooCommerce template override set.

## Booking plugins (basic styling only)
- `css/booking.css` is loaded when one of these is detected: Bookly, Amelia, Simply Schedule Appointments, Easy Appointments, LatePoint, Calendly plugin.
- It only tidies width, buttons and fields; the theme does not claim full support. Plugin markup can change between versions.
- Force on or off: `add_filter( 'stylena_booking_plugins_active', '__return_true' );` (or `__return_false`).

## Hooks
- `stylena_booking_plugins_active` (filter, bool): override booking plugin detection.
- Standard WooCommerce hooks `woocommerce_before_main_content` / `woocommerce_after_main_content` are used for the wrapper; remove ours with `remove_action` in a child theme to supply your own.
