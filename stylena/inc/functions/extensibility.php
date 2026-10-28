<?php
/**
 * Extension helpers: child-theme friendly asset lookup, logo variants and the
 * database version migrator.
 *
 * @package Stylena
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Current schema/data version of the theme's stored options.
 */
if ( ! defined( 'STYLENA_DB_VERSION' ) ) {
	define( 'STYLENA_DB_VERSION', 4 );
}

/**
 * Run pending migrations. Admin requests only, and only when the stored
 * version differs from the code version; never writes on the frontend.
 *
 * @return void
 */
function stylena_maybe_migrate() {
	// admin_init also fires for visitors on admin-ajax.php; only an administrator runs migrations.
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$stored = (int) get_option( 'stylena_db_version', 0 );
	if ( STYLENA_DB_VERSION === $stored ) {
		return;
	}

	/**
	 * Fires when the stored DB version is older than the code version.
	 *
	 * @param int $stored  Stored version.
	 * @param int $current Code version.
	 */
	do_action( 'stylena_migrate', $stored, STYLENA_DB_VERSION );

	// Version 1: baseline. Later versions hook
	// their own upgrade routines to stylena_migrate.
	update_option( 'stylena_db_version', STYLENA_DB_VERSION );
}
add_action( 'admin_init', 'stylena_maybe_migrate' );

/**
 * DB version 3: remove data left behind by the retired Instagram integration.
 *
 * @param int $stored Stored DB version.
 * @return void
 */
function stylena_cleanup_instagram_leftovers( $stored ) {
	if ( (int) $stored >= 3 ) {
		return;
	}
	global $wpdb;

	delete_option( 'stylena_instagram_auth' );
	delete_option( 'stylena_instagram_app' );
	delete_transient( 'stylena_ig_refresh_lock' );
	wp_clear_scheduled_hook( 'stylena_instagram_refresh' );
	wp_clear_scheduled_hook( 'stylena_ig_refresh' );

	$mods = array( 'access_token', 'connection_status', 'account_id', 'username', 'user_id', 'client_id', 'redirect_uri' );
	foreach ( $mods as $mod ) {
		remove_theme_mod( 'stylena_instagram_' . $mod );
	}

	$prefixes = array( 'stylena_instagram_', 'stylena_ig_', 'instagram_oauth_state_' );
	foreach ( $prefixes as $prefix ) {
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		$wpdb->query(
			$wpdb->prepare(
				"DELETE FROM {$wpdb->options} WHERE option_name LIKE %s OR option_name LIKE %s OR option_name LIKE %s",
				$wpdb->esc_like( '_transient_' . $prefix ) . '%',
				$wpdb->esc_like( '_transient_timeout_' . $prefix ) . '%',
				$wpdb->esc_like( $prefix ) . '%'
			)
		);
	}
	wp_cache_delete( 'alloptions', 'options' );
	wp_cache_delete( 'notoptions', 'options' );
}
add_action( 'stylena_migrate', 'stylena_cleanup_instagram_leftovers' );

/**
 * DB version 4: move the Google Places API key out of the exportable theme_mod
 * into a non-autoloaded option.
 *
 * @param int $stored Stored DB version.
 * @return void
 */
function stylena_migrate_places_key( $stored ) {
	if ( (int) $stored >= 4 ) {
		return;
	}
	$mod = get_theme_mod( 'stylena_reviews_api_key', '' );
	if ( false === get_option( 'stylena_google_places_key', false ) ) {
		add_option( 'stylena_google_places_key', is_string( $mod ) ? sanitize_text_field( $mod ) : '', '', false );
	} elseif ( is_string( $mod ) && '' !== $mod && '' === get_option( 'stylena_google_places_key', '' ) ) {
		update_option( 'stylena_google_places_key', sanitize_text_field( $mod ) );
	}
	remove_theme_mod( 'stylena_reviews_api_key' );
}
add_action( 'stylena_migrate', 'stylena_migrate_places_key' );

/**
 * Make sure the Places key option exists with autoload off before the Customizer
 * (type=option setting) can create it autoloaded. Idempotent.
 *
 * @return void
 */
function stylena_ensure_places_key_option() {
	if ( false === get_option( 'stylena_google_places_key', false ) ) {
		add_option( 'stylena_google_places_key', '', '', false );
	}
}
add_action( 'customize_register', 'stylena_ensure_places_key_option', 1 );

/**
 * Google Places API key. A wp-config.php constant wins over the stored option.
 *
 * @return string
 */
function stylena_get_places_key() {
	if ( defined( 'STYLENA_GOOGLE_PLACES_KEY' ) && STYLENA_GOOGLE_PLACES_KEY ) {
		return (string) STYLENA_GOOGLE_PLACES_KEY;
	}
	if ( defined( 'STYLENA_GOOGLE_API_KEY' ) && STYLENA_GOOGLE_API_KEY ) {
		return (string) STYLENA_GOOGLE_API_KEY;
	}
	return (string) get_option( 'stylena_google_places_key', '' );
}

/**
 * Body class when the homepage Hero is switched off, so CSS can reserve space
 * for the fixed header that normally sits over the hero.
 *
 * @param string[] $classes Body classes.
 * @return string[]
 */
function stylena_no_hero_body_class( $classes ) {
	if ( is_front_page() && function_exists( 'stylena_section_enabled' ) && ! stylena_section_enabled( 'hero' ) ) {
		$classes[] = 'stylena-no-hero';
	}
	return $classes;
}
add_filter( 'body_class', 'stylena_no_hero_body_class' );

/**
 * Whether a homepage section is enabled (Customizer toggle, default on).
 *
 * @param string $section Section key: hero, about or services.
 * @return bool
 */
function stylena_section_enabled( $section ) {
	$enabled = (bool) get_theme_mod( 'stylena_show_' . sanitize_key( $section ), true );

	/**
	 * Filters whether a homepage section is shown.
	 *
	 * @param bool   $enabled Enabled state.
	 * @param string $section Section key.
	 */
	return (bool) apply_filters( 'stylena_section_enabled', $enabled, $section );
}

/**
 * URL of a header logo variant (light, dark, mobile), or '' when not set.
 *
 * @param string $variant light|dark|mobile.
 * @return string
 */
function stylena_get_logo_url( $variant ) {
	if ( ! in_array( $variant, array( 'light', 'dark', 'mobile' ), true ) ) {
		return '';
	}
	$url = esc_url_raw( (string) get_theme_mod( 'stylena_logo_' . $variant, '' ) );

	/**
	 * Filters a header logo variant URL.
	 *
	 * @param string $url     Image URL.
	 * @param string $variant light|dark|mobile.
	 */
	return (string) apply_filters( 'stylena_logo_url', $url, $variant );
}

/**
 * Markup for the header logo when a light and/or mobile variant is set.
 * Returns '' when no variant is configured so callers keep the old behaviour.
 * Output is escaped here.
 *
 * @return string
 */
function stylena_get_logo_variant_html() {
	$light  = stylena_get_logo_url( 'light' );
	$mobile = stylena_get_logo_url( 'mobile' );
	if ( '' === $light && '' === $mobile ) {
		return '';
	}

	$main = '' !== $light ? $light : $mobile;
	$alt  = get_bloginfo( 'name' );
	$html = '<picture>';
	if ( '' !== $mobile ) {
		$html .= '<source media="(max-width: 767.98px)" srcset="' . esc_url( $mobile ) . '">';
	}
	$html .= '<img src="' . esc_url( $main ) . '" alt="' . esc_attr( $alt ) . '" class="custom-logo stylena-logo-light">';
	$html .= '</picture>';

	return $html;
}
